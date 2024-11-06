<?php

namespace App\Http\Controllers;

use App\Models\Perizinan;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class PerizinanController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Perizinan::with(['detail_user.pegawai_has_kategori.kategori_pegawai', 'kategori_izin'])
                ->selectRaw('perizinans.*, DATEDIFF(perizinans.selesai, perizinans.mulai) AS lama')
                ->whereDate('perizinans.created_at', '>=', $request->tanggal_start)
                ->whereDate('perizinans.created_at', '<=', $request->tanggal_end);
            // return $data->get();
            return DataTables::of($data)
                ->filterColumn('lama', function ($query, $keyword){
                    $query->whereRaw('DATEDIFF(perizinans.selesai, perizinans.mulai) like ?', ["%$keyword%"]);
                })
                ->filterColumn('detail_user.pegawai_has_kategori', function ($query, $keyword){
                    $query->whereRelation('detail_user.pegawai_has_kategori.kategori_pegawai', 'nama', 'like', "%$keyword%");
                })
                ->setRowClass(function ($data){
                    if ($data->status == 'terima') {
                        $class = 'table-success';
                    }elseif ($data->status == 'tolak') {
                        $class = 'table-danger';
                    }elseif ($data->status == 'selesai') {
                        $class = 'table-secondary';
                    }else {
                        $class = '';
                    }
                    return $class;
                })
                ->addColumn('action', function ($data) {
                    $is_disabled1 = $data->status == "terima" || $data->status == "selesai" ? 'disabled': '';
                    $is_disabled2 = $data->status == "tolak" || $data->status == "selesai" ? 'disabled': '';
                    return '
                        <button ' . $is_disabled1 . ' onclick="change_status('.$data->id.', \'terima\')" class="btn btn-xs btn-success">Terima</button>
                        <button onclick="detail('.$data->id.')" class="btn btn-xs btn-primary">Detail</button>
                        <button ' . $is_disabled2 . ' onclick="change_status('.$data->id.', \'tolak\')" class="btn btn-xs btn-danger">Tolak</button>
                    ';
                })
                ->make(true);
        }
        return view('perizinan.main');
    }

    public function change_status(Request $request)
    {
        $perizinan = Perizinan::find($request->id);
        $perizinan->status = $request->status;
        $perizinan->save();
        if ($perizinan) {
            $heading = 'Perizinan';
            if ($request->status == 'selesai') {
                $content = "Izin Diterima.";
            }else {
                $content = "Izin Ditolak.";
            }
            // SEND STATUS
            $this->notification([$perizinan->detail_user->user->player_id], $heading, $content);
            return ['status' => 'success', 'code' => 200, 'message' => 'Pekerjaan '.$request->status, 'data' => ''];
        }else{
            return ['status' => 'error', 'code' => 500, 'message' => 'Pekerjaan gagal '.$request->status, 'data' => ''];
        }
    }

    public function detail(Request $request)
    {
        $data['perizinan'] = Perizinan::with(['detail_user.pegawai_has_kategori.kategori_pegawai', 'kategori_izin', 'detail_perizinan'])->find($request->id);
        // return $data;
        $content = view('perizinan.detail', $data)->render();
        return ['status' => 'success', 'content' => $content];
    }

    public function riwayat_perizinan(Request $request)
    {
        if ($request->ajax()) {
            $data = Perizinan::with(['detail_user.pegawai_has_kategori.kategori_pegawai', 'kategori_izin'])
                ->selectRaw('perizinans.*, DATEDIFF(selesai, mulai) AS lama')
                ->whereIn('status', ['terima', 'selesai'])
                ->whereDate('mulai', '>=', $request->tanggal_start)
                ->whereDate('mulai', '<=', $request->tanggal_end)
                ;
            // return $data->get();
            return DataTables::of($data)
                ->filterColumn('lama', function ($query, $keyword){
                    $query->whereRaw('DATEDIFF(perizinans.selesai, perizinans.mulai) like ?', ["%$keyword%"]);
                })
                ->setRowClass(function ($data){
                    if ($data->status == 'selesai') {
                        $class = 'table-secondary';
                    }else {
                        $class = '';
                    }
                    return $class;
                })
                ->addColumn('action', function ($data) {
                    $is_disabled1 = $data->status == "selesai" ? 'disabled': '';
                    return '
                        <button ' . $is_disabled1 . ' onclick="change_status('.$data->id.', \'selesai\')" class="btn btn-xs btn-primary">Selesai</button>
                    ';
                })
                ->make(true);
        }
        return view('riwayat_perizinan.main');
    }
}
