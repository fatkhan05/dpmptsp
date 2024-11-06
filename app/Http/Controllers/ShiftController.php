<?php

namespace App\Http\Controllers;

use App\Models\DetailUser;
use App\Models\KategoriPegawai;
use App\Models\Shift;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Validator;

class ShiftController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Shift::selectRaw('shifts.*, kategori_pegawais.nama as nama_kategori')
                ->leftjoin('kategori_pegawais', 'kategori_pegawais.id', 'shifts.kategori_pegawai_id');
            // return $data->get();
            
            return Datatables::of($data)
                ->addIndexColumn()
                ->filterColumn('nama_kategori', function ($query, $keyword){
                    $query->whereRaw('kategori_pegawais.nama like ?', ["%$keyword%"]);
                })
                ->addColumn('action', function ($data) {
                    $hidden = $data->id == 1 ? 'hidden' : '';
                    return '
                        <button onclick="form_page('.$data->id.')" class="btn btn-xs btn-primary">Edit</button>
                        <button '.$hidden.' onclick="delete_data('.$data->id.')" class="btn btn-xs btn-danger">Hapus</button>
                    ';
                })
                ->make(true);
        }
        return view('shift.main');
    }

    public function form(Request $request)
    {
        $data = [];
        if ($request->id) {
            $data['shift'] = Shift::find($request->id);
        }
        $data['kategori_pegawai'] = KategoriPegawai::all();
        // return $data;
        $content = view('shift.form', $data)->render();
        return ['status' => 'success', 'content' => $content];
    }

    public function store(Request $request)
    {
        // VALIDATION
        $rules = [
            'nama' => 'required',
            'kategori_pegawai_id' => 'required',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
        ];
        $messages = [
            'required' => 'Kolom :attribute harus diisi',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $messages = [];
            foreach (json_decode($validator->errors()) as $key => $val) {
                array_push($messages, $val[0]);
            }
            $messages = implode('<br>', $messages);
            return ['status' => 'error', 'code' => 422, 'message' => $messages, 'data' => ''];
        }
        
        if ($request->shift_id == '') {
            $shift = new Shift;
        } else {
            $shift = Shift::find($request->shift_id);
        }
        $shift->nama = $request->nama;
        $shift->kategori_pegawai_id = $request->kategori_pegawai_id;
        $shift->mulai = $request->jam_mulai;
        $shift->selesai = $request->jam_selesai;
        $shift->save();

        if ($shift) {
            return ['status' => 'success', 'code' => 200, 'message' => "Berhasil menyimpan Master Shift", 'data' => ''];
        }
    }

    public function delete(Request $request)
    {
        $deleted = Shift::find($request->id)->delete();

        if ($deleted) {
            return ['status' => 'success', 'message' => 'Anda Berhasil Menghapus Data', 'title' => 'Success'];
        } else {
            return ['status' => 'error', 'message' => 'Data Gagal Dihapus', 'title' => 'Whoops'];
        }
    }

    public function penjadwalan(Request $request)
    {
        if ($request->ajax()) {
            $data = DetailUser::with(['pegawai_has_kategori.kategori_pegawai'])
                ->join('shifts', 'shifts.id', 'detail_users.shift_id')
                ->selectRaw('detail_users.*, shifts.nama as nama_shift, shifts.mulai, shifts.selesai')
                ;
            // return $data->get();
            
            return Datatables::of($data)
                ->addIndexColumn()
                ->filterColumn('pegawai_has_kategori', function ($query, $keyword){
                    $query->whereRelation('pegawai_has_kategori.kategori_pegawai', 'nama', 'like', "%$keyword%");
                })
                ->filterColumn('nama_shift', function ($query, $keyword){
                    $query->whereRaw('shifts.nama like ?', ["%$keyword%"]);
                })
                ->filterColumn('mulai', function ($query, $keyword){
                    $query->whereRaw('shifts.mulai like ?', ["%$keyword%"]);
                })
                ->filterColumn('selesai', function ($query, $keyword){
                    $query->whereRaw('shifts.selesai like ?', ["%$keyword%"]);
                })
                ->addColumn('action', function ($data) {
                    return '
                        <button onclick="form_page('.$data->id.')" class="btn btn-xs btn-primary">Ubah Shift</button>
                    ';
                })
                ->make(true);
        }
        return view('penjadwalan_shift.main');
    }

    public function form_penjadwalan(Request $request)
    {
        $data = [];
        if ($request->id) {
            $data['detail_user'] = DetailUser::with(['pegawai_has_kategori'])->find($request->id);
            $kategori_array  = [];
            foreach ($data['detail_user']->pegawai_has_kategori as $pegawai_has_kategori) {
                array_push($kategori_array, $pegawai_has_kategori->kategori_pegawai_id);
            }
            $data['shifts'] = Shift::with('kategori_pegawai')->whereIn('kategori_pegawai_id', $kategori_array)->get();
        }else {
            $data['shifts'] = Shift::with('kategori_pegawai')->all();
        }
        // return $data;
        $content = view('penjadwalan_shift.form', $data)->render();
        return ['status' => 'success', 'content' => $content];
    }

    public function store_penjadwalan(Request $request)
    {
        // VALIDATION
        $rules = [
            'shift_id' => 'required',
        ];
        $messages = [
            'required' => 'Kolom :attribute harus diisi',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $messages = [];
            foreach (json_decode($validator->errors()) as $key => $val) {
                array_push($messages, $val[0]);
            }
            $messages = implode('<br>', $messages);
            return ['status' => 'error', 'code' => 422, 'message' => $messages, 'data' => ''];
        }
        
        // if ($request->detail_user_id == '') {
        //     // $shift = new Shift;
        // } else {
        // }
        $detail_user = DetailUser::find($request->detail_user_id);
        $detail_user->shift_id = $request->shift_id;
        $detail_user->save();
        
        $jam_mulai = $detail_user->shift->mulai;
        $jam_selesai = $detail_user->shift->selesai;

        if ($detail_user) {
            $this->notification([$detail_user->user->player_id], 'Shift', "Jam Shift anda telah di ubah ($jam_mulai - $jam_selesai)");
            return ['status' => 'success', 'code' => 200, 'message' => "Berhasil Mengubah Shift", 'data' => ''];
        }
    }
}
