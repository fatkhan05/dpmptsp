<?php

namespace App\Http\Controllers;

use App\Models\LokasiKantor;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Validator;

class LokasiKantorController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = LokasiKantor::query();
            
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                    return '
                        <button onclick="form_page('.$data->id.')" class="btn btn-xs btn-primary">Edit</button>
                        <button onclick="delete_data('.$data->id.')" class="btn btn-xs btn-danger">Hapus</button>
                    ';
                })
                ->make(true);
        }
        return view('lokasi_kantor.main');
    }

    public function form(Request $request)
    {
        $data = [];
        if ($request->id) {
            $data['lokasi_kantor'] = LokasiKantor::find($request->id);
        }
        
        $content = view('lokasi_kantor.form', $data)->render();
        return ['status' => 'success', 'content' => $content];
    }

    public function store(Request $request)
    {
        // VALIDATION
        $rules = [
            'nama' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
            'radius' => 'required',
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
        
        if ($request->lokasi_kantor_id == '') {
            $lokasi_kantor = new LokasiKantor;
        } else {
            $lokasi_kantor = LokasiKantor::find($request->lokasi_kantor_id);
        }
        $lokasi_kantor->nama = $request->nama;
        $lokasi_kantor->alamat = $request->alamat ?? null;
        $lokasi_kantor->deskripsi = $request->deskripsi ?? null;
        $lokasi_kantor->latitude = $request->latitude;
        $lokasi_kantor->longitude = $request->longitude;
        $lokasi_kantor->radius = $request->radius;
        $lokasi_kantor->save();

        if ($lokasi_kantor) {
            return ['status' => 'success', 'code' => 200, 'message' => "Berhasil menyimpan Lokasi Kantor", 'data' => ''];
        }
    }

    public function delete(Request $request)
    {
        $deleted = LokasiKantor::find($request->id)->delete();

        if ($deleted) {
            return ['status' => 'success', 'message' => 'Anda Berhasil Menghapus Data', 'title' => 'Success'];
        } else {
            return ['status' => 'error', 'message' => 'Data Gagal Dihapus', 'title' => 'Whoops'];
        }
    }

    public function get_lokasi_kantor(Request $request)
    {
        $lokasi_kantor = LokasiKantor::find($request->lokasi_kantor_id);

        if ($lokasi_kantor) {
            return ['status' => 'success', 'message' => 'Berhasil Mengambil Data', 'title' => 'Success', 'data' => $lokasi_kantor];
        }else {
            return ['status' => 'error', 'message' => 'Data Tidak ditemukan', 'title' => 'Success'];
        }
    }
}
