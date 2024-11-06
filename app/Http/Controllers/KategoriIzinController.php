<?php

namespace App\Http\Controllers;

use App\Models\KategoriIzin;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Validator;

class KategoriIzinController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = KategoriIzin::orderBy('created_at', 'desc');
            
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

        return view('kategori_izin.main');
    }

    public function form(Request $request)
    {
        $data = [];
        if ($request->id) {
            $data['kategori_izin'] = KategoriIzin::find($request->id);
        }
        
        $content = view('kategori_izin.form', $data)->render();
        return ['status' => 'success', 'content' => $content];
    }

    public function store(Request $request)
    {
        // VALIDATION
        $rules = [
            'nama' => 'required',
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

        if ($request->kategori_izin_id == '') {
            $kategori_izin = new KategoriIzin;
        }else {
            $kategori_izin = KategoriIzin::find($request->kategori_izin_id);
        }
        $kategori_izin->nama = $request->nama;
        $kategori_izin->keterangan = $request->keterangan;
        $kategori_izin->save();

        if ($kategori_izin) {
            return ['status' => 'success', 'code' => 200, 'message' => "Berhasil menyimpan kategori izin", 'data' => ''];
        }
    }

    public function delete(Request $request)
    {
        $deleted = KategoriIzin::find($request->id)->delete();

        if ($deleted) {
            return ['status' => 'success', 'message' => 'Anda Berhasil Menghapus Data', 'title' => 'Success'];
        } else {
            return ['status' => 'error', 'message' => 'Data Gagal Dihapus', 'title' => 'Whoops'];
        }
    }
}
