<?php

namespace App\Http\Controllers;

use App\Models\DetailUser;
use App\Models\PointTarget;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PointTargetController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = DetailUser::with(['pegawai_has_kategori.kategori_pegawai'])
                ->selectRaw('detail_users.*, point_targets.detail_user_id, point_targets.nilai, point_targets.kategori')
                ->rightJoin('point_targets', 'point_targets.detail_user_id', 'detail_users.id');
            // return $data->get();

            return Datatables::of($data)
                ->addIndexColumn()
                ->filterColumn('pegawai_has_kategori', function ($query, $keyword) {
                    $query->whereRelation('pegawai_has_kategori.kategori_pegawai', 'nama', 'like', "%$keyword%");
                })
                ->filterColumn('nilai', function ($query, $keyword) {
                    $query->whereRelation('point_target', 'nilai', 'like', "%$keyword%");
                })
                ->addColumn('action', function ($data) {
                    return '
                        <button onclick="form_page(' . $data->id . ')" class="btn btn-xs btn-primary">Edit</button>
                    ';
                })
                ->make(true);
        }
        return view('point_target.main');
    }

    public function form(Request $request)
    {
        $data = [];
        if ($request->id) {
            $data['detail_user_point_target'] = DetailUser::with('point_target')->find($request->id);
        }
        $data['detail_users'] = DetailUser::whereHas('pegawai_has_kategori', function ($query) {
            $query->where('kategori_pegawai_id', '!=', 1); // ambil yg tidak mempunyai kategori admin
        })->get();

        $data['kategoris'] = PointTarget::select('kategori')->distinct()->get();
        // return $data;

        $content = view('point_target.form', $data)->render();
        return ['status' => 'success', 'content' => $content];
    }

    public function store(Request $request)
    {
        // VALIDATION
        $rules = [
            'nilai' => 'required|numeric',
            // 'kategori' => 'required',
        ];
        $messages = [
            'required' => 'Kolom :attribute harus diisi',
            'numeric' => 'Kolom :attribute harus berupa angka',
        ];

        if (!$request->semua_pegawai) {
            $rules['detail_user_id'] = 'required';
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

        if ($request->semua_pegawai) {
            $detail_user = DetailUser::whereHas('pegawai_has_kategori', function ($query) {
                $query->where('kategori_pegawai_id', '!=', 1); // ambil yg tidak mempunyai kategori admin
            })
                ->get('id');
            $detail_user_ids = $detail_user->map(function ($val, $key) {
                return $val->id;
            });
        } else {
            $detail_user_ids = $request->detail_user_id;
        }

        foreach ($detail_user_ids as $detail_user_id) {
            $point_target = PointTarget::updateOrCreate(
                ['detail_user_id' => $detail_user_id],
                [
                    'kategori' => $request->kategori,
                    'nilai' => $request->nilai,
                    'deskripsi' => $request->deskripsi,
                ]
            );
        }

        if ($point_target) {
            return ['status' => 'success', 'code' => 200, 'message' => "Berhasil menyimpan point target", 'data' => ''];
        } else {
            return ['status' => 'error', 'code' => 500, 'message' => "Gagal menyimpan point target", 'data' => ''];
        }
    }
}