<?php

namespace App\Http\Controllers;

use App\Models\DetailUser;
use App\Models\Presensi;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class PresensiController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $tanggal = $request->tanggal;
            $data = Presensi::with('detail_user.pegawai_has_kategori.kategori_pegawai')
                ->whereDate('masuk', $tanggal);
            // return $data->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->filterColumn('detail_user.pegawai_has_kategori', function ($query, $keyword){
                    $query->whereRelation('detail_user.pegawai_has_kategori.kategori_pegawai', 'nama', 'like', "%$keyword%");
                })
                ->editColumn('masuk', function ($data){
                    $tanggal = strtotime($data->masuk);
                    return date('H:i:s', $tanggal);
                })
                ->editColumn('pulang', function ($data){
                    $tanggal = strtotime($data->pulang ?? '0000-00-00 00:00:00');
                    return date('H:i:s', $tanggal);
                })
                ->editColumn('foto_masuk', function ($data){
                    return asset('images'). '/' . $data->foto_masuk;
                })
                ->editColumn('foto_pulang', function ($data){
                    $img = null;
                    if ($data->foto_pulang) {
                        $img = asset('images'). '/' . $data->foto_pulang;
                    }
                    return $img;
                })
                ->addColumn('action', function ($data) {
                    return '
                        <button onclick="detail('.$data->detail_user_id.')" class="btn btn-xs btn-primary">Detail</button>
                    ';
                })
                ->make(true);
        }
        return view('presensi.main');
    }

    public function detail(Request $request)
    {
        $data['detail_user'] = DetailUser::with('pegawai_has_kategori.kategori_pegawai')->find($request->detail_user_id);
        // return $data;
        $content = view('presensi.detail', $data)->render();
        return ['status' => 'success', 'content' => $content];
    }

    public function dt_detail_presensi(Request $request)
    {
        $tanggal =  explode('-', $request->tanggal);
        // $tanggal = strtotime($request->tanggal);
        $bulan = $tanggal[0];
        $tahun = $tanggal[1];
        // return $tahun;
        
        $data = Presensi::with('detail_user.pegawai_has_kategori.kategori_pegawai')
            ->where('presensis.detail_user_id', $request->detail_user_id) 
            ->whereMonth('presensis.masuk', $bulan)
            ->whereYear('presensis.masuk', $tahun)
            ->selectRaw('presensis.*, pegawai_has_pekerjaans.nilai , DATE_FORMAT(pegawai_has_pekerjaans.created_at, "%Y-%m-%d") tanggal, SUM(pegawai_has_pekerjaans.nilai) / (COUNT(presensis.created_at) * 5) as percent')
            // ->selectRaw('presensis.*, pegawai_has_pekerjaans.detail_user_id, SUM(pegawai_has_pekerjaans.nilai) / (COUNT(presensis.created_at) * 5) as percent, DATE_FORMAT(pegawai_has_pekerjaans.created_at, "%Y-%m-%d") tanggal')
            ->join('pegawai_has_pekerjaans', 'pegawai_has_pekerjaans.detail_user_id', 'presensis.detail_user_id')
            ->where(
                function ($query){
                    $query->selectRaw('DATE_FORMAT(presensis.created_at, "%Y-%m-%d")');
                },
                function ($query){
                    $query->selectRaw('DATE_FORMAT(pegawai_has_pekerjaans.time_take_sesudah, "%Y-%m-%d")');
                }
            )
            ->groupBy('tanggal');
            ;
        $total = $data->get()->sum('percent');
        // $total = 80;
        // return $data->get();
        return DataTables::of($data)
            ->editColumn('created_at', function ($data){
                return $data->created_at->format('d-m-Y');
            })
            ->editColumn('masuk', function ($data){
                $tanggal = strtotime($data->masuk);
                return date('H:i:s', $tanggal);
            })
            ->editColumn('pulang', function ($data){
                $tanggal = strtotime($data->pulang ?? '0000-00-00 00:00:00');
                return date('H:i:s', $tanggal);
            })
            ->editColumn('foto_masuk', function ($data){
                return asset('images'). '/' . $data->foto_masuk;
            })
            ->editColumn('foto_pulang', function ($data){
                $img = null;
                if ($data->foto_pulang) {
                    $img = asset('images'). '/' . $data->foto_pulang;
                }
                return $img;
            })
            ->with('total', $total * 100)
            ->make(true);
    }
}
