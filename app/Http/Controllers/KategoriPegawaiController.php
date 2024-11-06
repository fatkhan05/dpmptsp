<?php

namespace App\Http\Controllers;

use App\Models\Bidang;
use App\Models\DetailUser;
use App\Models\KategoriPegawai;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Validator;

class KategoriPegawaiController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = KategoriPegawai::with('bidang')->selectRaw('kategori_pegawais.*, detail_users.nama as nama_koordinator, detail_users.telepon')
                ->leftjoin('detail_users', 'detail_users.id', 'kategori_pegawais.detail_user_id');
            // return $data->get();

            return Datatables::of($data)
                ->addIndexColumn()
                ->filterColumn('nama_koordinator', function ($query, $keyword) {
                    $query->whereRaw('detail_users.nama like ?', ["%$keyword%"]);
                })
                ->filterColumn('telepon', function ($query, $keyword) {
                    $query->whereRaw('detail_users.telepon like ?', ["%$keyword%"]);
                })
                ->addColumn('action', function ($data) {
                    $hidden = $data->id == 1 ? 'hidden' : '';
                    return '
                        <button onclick="form_page(' . $data->id . ')" class="btn btn-xs btn-primary">Edit</button>
                        <button ' . $hidden . ' onclick="delete_data(' . $data->id . ')" class="btn btn-xs btn-danger">Hapus</button>
                    ';
                })
                ->make(true);
        }
        return view('kategori_pegawai.main');
    }

    public function form(Request $request)
    {
        $data = [];
        if ($request->id) {
            $data['kategori_pegawai'] = KategoriPegawai::with('koordinator')
                ->find($request->id);
        }
        $data['pegawais'] = DetailUser::all();
        $data['bidang'] = Bidang::all();

        $kategori_pegawai = new KategoriPegawai;
        $data['kode'] = $this->generateKode($kategori_pegawai, 'DK', 3);
        // return $data;
        $content = view('kategori_pegawai.form', $data)->render();
        return ['status' => 'success', 'content' => $content];
    }

    public function store(Request $request)
    {
        // VALIDATION
        $rules = [
            'nama' => 'required',
            'bidang_id' => 'required',
        ];
        $messages = [
            'required' => 'Kolom :attribute harus diisi',
            'unique' => ':attribute sudah dipakai',
        ];

        if ($request->kategori_pegawai_id == '') {
            $kategori_pegawai = new KategoriPegawai;
            $rules['kode'] = 'required|unique:kategori_pegawais';
        } else {
            $kategori_pegawai = KategoriPegawai::find($request->kategori_pegawai_id);
            if ($kategori_pegawai->kode != $request->kode) { // JIKA BEDA MAKA AKAN DIVALIDASI
                $rules['kode'] = 'required|unique:kategori_pegawais';
            }
        }

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $messages = [];
            foreach (json_decode($validator->errors()) as $key => $val) {
                array_push($messages, $val[0]);
            }
            $messages = implode('<br>', $messages);
            return ['status' => 'error', 'code' => 422, 'message' => $messages, 'data' => ''];
        }

        $kategori_pegawai->kode = $request->kode;
        $kategori_pegawai->nama = $request->nama;
        $kategori_pegawai->detail_user_id = $request->koordinator_id;
        $kategori_pegawai->bidang_id = $request->bidang_id;
        $kategori_pegawai->save();

        if ($kategori_pegawai) {
            return ['status' => 'success', 'code' => 200, 'message' => "Berhasil menyimpan Kategori pegawai", 'data' => ''];
        }
    }

    public function delete(Request $request)
    {
        $deleted = KategoriPegawai::find($request->id)->delete();

        if ($deleted) {
            return ['status' => 'success', 'message' => 'Anda Berhasil Menghapus Data', 'title' => 'Success'];
        } else {
            return ['status' => 'error', 'message' => 'Data Gagal Dihapus', 'title' => 'Whoops'];
        }
    }
}