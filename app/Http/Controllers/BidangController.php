<?php

namespace App\Http\Controllers;

use App\Models\Bidang;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Validator;

class BidangController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Bidang::with('kategori_pegawai')->get();
            // return $data;

            return Datatables::of($data)
                ->addIndexColumn()
                // ->filterColumn('pegawai_has_kategori', function ($query, $keyword) {
                //     $query->whereRelation('pegawai_has_kategori.kategori_pegawai', 'nama', 'like', "%$keyword%");
                // })
                // ->filterColumn('nilai', function ($query, $keyword) {
                //     $query->whereRelation('point_target', 'nilai', 'like', "%$keyword%");
                // })
                ->addColumn('action', function ($data) {
                    $hidden = '';
                    if (count($data->kategori_pegawai) > 0) {
                        $hidden = 'hidden';
                    }
                    return '
                        <button onclick="form_page(' . $data->id . ')" class="btn btn-xs btn-primary">Edit</button>
                        <button ' . $hidden . ' onclick="delete_data(' . $data->id . ')" class="btn btn-xs btn-danger">Hapus</button>
                    ';
                })
                ->make(true);
        }
        return view('bidang.main');
    }
    public function form(Request $request)
    {
        $data = [];
        if ($request->id) {
            $data['bidang'] = Bidang::find($request->id);
        }
        // $data['pegawais'] = DetailUser::all();

        $bidang = new Bidang;
        $data['kode'] = $this->generateKode($bidang, 'BK', 3);
        // return $data;
        $content = view('bidang.form', $data)->render();
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

        if ($request->id == '') {
            $bidang = new Bidang;
        } else {
            $bidang = Bidang::find($request->id);
        }
        $bidang->kode = $request->kode;
        $bidang->nama = $request->nama;
        $bidang->bidang_deskripsi = $request->deskripsi;
        $bidang->save();

        if ($bidang) {
            return ['status' => 'success', 'code' => 200, 'message' => "Berhasil menyimpan kategori bidang", 'data' => ''];
        }
    }
}