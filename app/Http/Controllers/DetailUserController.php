<?php

namespace App\Http\Controllers;

use App\Models\Desa;
use App\Models\User;
use App\Models\Shift;
use App\Models\Provinsi;
use App\Models\Kabupaten;
use App\Models\Kecamatan;
use App\Models\DetailUser;
use Illuminate\Http\Request;
use App\Imports\PegawaiImport;
use App\Models\Bidang;
use App\Models\KategoriPegawai;
use App\Models\PegawaiHasKategori;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Validator;

class DetailUserController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = DetailUser::with(['bidang', 'pegawai_has_kategori.kategori_pegawai', 'user' => function ($query) {
                $query->selectRaw("*,
                CASE 
                    WHEN status = '1' THEN 'Aktif'
                    WHEN status = '0' THEN 'Tidak Aktif'
                END AS status
                ");
            }])
                ->join('shifts', 'shifts.id', 'detail_users.shift_id')
                ->selectRaw('detail_users.*, shifts.nama as nama_shift');
            // return $data->get();

            return Datatables::of($data)
                ->addIndexColumn()
                ->filterColumn('pegawai_has_kategori', function ($query, $keyword) {
                    $query->whereRelation('pegawai_has_kategori.kategori_pegawai', 'nama', 'like', "%$keyword%");
                })
                ->filterColumn('nama_shift', function ($query, $keyword) {
                    $query->whereRaw('shifts.nama like ?', ["%$keyword%"]);
                })
                ->setRowClass(function ($data) {
                    return $data->user->status == 'Tidak Aktif' ? 'table-danger' : '';
                })
                ->addColumn('action', function ($data) {
                    $hidden = ($data->id == 1 || auth()->user()->detail_user->id == $data->id) ? 'hidden' : '';
                    return '
                        <button onclick="form_page(' . $data->id . ')" class="btn btn-xs btn-primary">Edit</button>
                        <button onclick="form_page(' . $data->id . ', \'r\')" class="btn btn-xs btn-secondary">Detail</button>
                        <button ' . $hidden . ' onclick="delete_data(' . $data->id . ')" class="btn btn-xs btn-danger">Hapus</button>
                    ';
                })
                ->make(true);
        }
        return view('detail_user.main');
    }

    public function form(Request $request)
    {
        $data = [];
        if ($request->id) {
            $data['detail_user'] = DetailUser::with(['user', 'pegawai_has_kategori.kategori_pegawai', 'shift'])
                ->find($request->id);
            $data['kabupatens'] = Kabupaten::where('provinsi_id', $data['detail_user']->provinsi_id)->get();
            $data['kecamatans'] = Kecamatan::where('kabupaten_id', $data['detail_user']->kabupaten_id)->get();
            $data['desas'] = Desa::where('kecamatan_id', $data['detail_user']->kecamatan_id)->get();

            $array_kategori = [];
            foreach ($data['detail_user']->pegawai_has_kategori as $pegawai_has_kategori) {
                array_push($array_kategori, $pegawai_has_kategori->kategori_pegawai_id ?? 0);
            }
            $data['pegawai_has_kategori'] = $array_kategori;
        }
        $data['bidangs'] = Bidang::all();
        $data['shifts'] = Shift::all();
        $data['kategori_pegawais'] =  (!empty($data['detail_user']->bidang_id)) ? KategoriPegawai::where('bidang_id', $data['detail_user']->bidang_id)->get() : KategoriPegawai::all();
        $data['provinsis'] = Provinsi::all();
        $data['mode'] = $request->mode;
        $content = view('detail_user.form', $data)->render();
        return ['status' => 'success', 'content' => $content];
    }

    public function store(Request $request)
    {
        // return $request->all();
        // VALIDATION
        if ($request->level_user != 'kepala_dinas') {
            $rules = [
                'photo' => 'image|mimes:png,jpg,jpeg',
                'nama' => 'required',
                // 'bidang_id' => 'required',
                'kategori_pegawai_id' => 'required',
                'kategori_pegawai_id.*' => 'required',
            ];
        } else {
            //JIKA LEVEL YANG DIPILIH KEPALA DINAS MAKA HAPUS VALIDATION PADA FORMASI / KATEGORI PEGAWAI
            $rules = [
                'photo' => 'image|mimes:png,jpg,jpeg',
                'nama' => 'required',
            ];
        }

        $messages = [
            'required' => 'Kolom :attribute harus diisi',
            'unique' => ':attribute sudah dipakai',
        ];

        // INITIAL
        if ($request->detail_user_id == '') {
            $detail_user = new DetailUser;
            $user = new User;

            $user->username = $request->username_field ?? date('YmdHis');
            $user->password = Hash::make($request->password_field ?? '1234'); // jika password tidak diisi maka default 1234

            // RULE BEBEDA UNTUK NOMOR DAN NIK PEGAWAI
            $rules['nomor'] = 'required|unique:users';
            $rules['nik'] = 'required|unique:detail_users|max:16|min:16';
        } else {
            $detail_user = DetailUser::find($request->detail_user_id);
            $user = User::find($detail_user->user_id);
            $user->username = $request->username_field;

            // JIKA PASSWORD DIISI BERARTI PASSWORD DIUBAH
            if ($request->password_field != '') {
                $user->password = Hash::make($request->password_field);
            }
            if ($user->nomor != $request->nomor) { // validasi nomor jika nomor diubah
                $rules['nomor'] = 'required|unique:users';
            }
            if ($detail_user->nik != $request->nik) { // validasi nomor jika nomor diubah
                $rules['nik'] = 'required|unique:detail_users|max:16|min:16';
            }
        }

        // VALIDATOR
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $messages = [];
            foreach (json_decode($validator->errors()) as $key => $val) {
                array_push($messages, $val[0]);
            }
            $messages = implode('<br>', $messages);
            return ['status' => 'error', 'code' => 422, 'message' => $messages, 'data' => ''];
        }

        // SAVE USER
        $level = 2;
        // SIMPAN LEVEL SESUAI DENGAN YANG DIPILIH
        if ($request->level_user == 'pegawai') {
            $level = 2;
            //APAKAH PADAH PILIHAN FORMASI TERDAPAT ADMIN ATAUPUN PENILAI
            if (in_array('penilai', $request->kategori_pegawai_id)) {
                $level = 3;
            } elseif (in_array('1', $request->kategori_pegawai_id)) {
                $level = 1;
            }
        } elseif ($request->level_user == 'kepala_dinas') {
            $level = 4;
        }

        // $user->level = in_array('1', $request->kategori_pegawai_id) ? 1 : 2; // jika kategori = admin maka level = 1, selain itu level = 2
        $user->level = $level;
        $user->nomor = $request->nomor;
        $user->status = $request->status ?? 0;
        $user->save();

        // UPLOAD FOTO
        if ($request->file('photo')) {
            $file = $request->file('photo');
            $filename = date('YmdHis') . $file->getClientOriginalName();
            // $file->move(public_path('images/profile'), $filename);
            $img = Image::make($file->getRealPath());
            $img->resize(720, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })->save(public_path('images/profile') . '/' . $filename, 60);

            // DELETE OLD FILE
            if ($detail_user->photo != null) {
                File::delete(public_path("images/$detail_user->photo"));
            }

            $detail_user->photo = "profile/$filename";
        }

        $detail_user->user_id = $user->id;
        $detail_user->nama = $request->nama;
        $detail_user->nik = $request->nik;
        $detail_user->jenis_kelamin = $request->jenis_kelamin;
        $detail_user->tempat_lahir = $request->tempat_lahir;
        $detail_user->tgl_lahir = $request->tgl_lahir;
        $detail_user->telepon = $request->telepon;
        $detail_user->tgl_mulai_kerja = $request->tgl_mulai_kerja;
        // $detail_user->kategori_pegawai_id = $request->kategori_pegawai_id;
        $detail_user->alamat = $request->alamat;
        $detail_user->shift_id = $request->shift_id;
        $detail_user->provinsi_id = $request->provinsi;
        $detail_user->kabupaten_id = $request->kabupaten;
        $detail_user->kecamatan_id = $request->kecamatan;
        $detail_user->desa_id = $request->desa;
        $detail_user->bidang_id = intval($request->bidang_id);
        $detail_user->save();

        if (empty($request->kategori_pegawai_id)) {
            PegawaiHasKategori::where('detail_user_id', $detail_user->id)
                ->delete();
        } else {
            //Remove Penilaian Value From Fronted End
            $kategori = [];
            foreach ($request->kategori_pegawai_id as $value) {
                if ($value != 'penilai') {
                    array_push($kategori, $value);
                }
            }

            // DELETE KATEGORI PEGAWAI YG TIDAK ADA DI FORM REQUEST
            PegawaiHasKategori::where('detail_user_id', $detail_user->id)
                ->whereNotIn('kategori_pegawai_id', $kategori)
                ->delete();

            // STORE KATEGORI PEGAWAI 
            foreach ($kategori as $kategori_pegawai) {
                PegawaiHasKategori::firstOrCreate([
                    'detail_user_id' => $detail_user->id,
                    'kategori_pegawai_id' => $kategori_pegawai
                ]);
            }
        }

        if ($detail_user) {
            return ['status' => 'success', 'code' => 200, 'message' => "Berhasil menyimpan Data Pegawai", 'data' => ''];
        }
    }

    public function delete(Request $request)
    {
        $deleted = DetailUser::find($request->id);
        $deleted->user()->delete();
        $deleted->pegawai_has_kategori()->delete();
        $deleted->delete();

        if ($deleted) {
            return ['status' => 'success', 'message' => 'Anda Berhasil Menghapus Data', 'title' => 'Success'];
        } else {
            return ['status' => 'error', 'message' => 'Data Gagal Dihapus', 'title' => 'Whoops'];
        }
    }

    public function getFormasi(Request $request)
    {
        $data = [];
        if (!empty($request->id)) {
            $data = KategoriPegawai::where('bidang_id', $request->id)->get();
        }
        return response()->json($data);
    }
    public function getKabupaten(Request $request)
    {
        $data = Kabupaten::where('provinsi_id', $request->id)->get();
        return response()->json($data);
    }

    public function getKecamatan(Request $request)
    {
        $data = Kecamatan::where('kabupaten_id', $request->id)->get();
        return response()->json($data);
    }

    public function getDesa(Request $request)
    {
        $data = Desa::where('kecamatan_id', $request->id)->get();
        return response()->json($data);
    }

    public function import(Request $request)
    {
        // return $request->all();
        $this->validate($request, [
            'file_excel' => 'required|mimes:csv,xls,xlsx'
        ]);
        $file = $request->file('file_excel');
        Excel::import(new PegawaiImport, $file);

        return ['status' => 'success', 'message' => 'Berhasil Import Excel', 'title' => 'Success'];
    }
}