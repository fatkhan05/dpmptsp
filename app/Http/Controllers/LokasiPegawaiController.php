<?php

namespace App\Http\Controllers;

use App\Models\Shift;
use App\Models\LokasiKantor;
use Illuminate\Http\Request;
use App\Models\LokasiPegawai;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\DB;

class LokasiPegawaiController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = LokasiPegawai::with(['detail_user'])
                ->selectRaw("*, 
                CASE 
                    WHEN status = '1' THEN 'Diizinkan'
                    WHEN status = '0' THEN 'Tidak Diizinkan'
                END AS status
                ")
                ->whereDate('waktu', $request->tanggal);
            // return $data->get();
            
            return Datatables::of($data)
                ->addIndexColumn()
                ->setRowClass(function ($data){
                    return $data->status == 'Tidak Diizinkan' ? 'table-danger' : '';
                })
                ->addColumn('action', function ($data) {
                    $hidden = $data->status == 'Diizinkan' ? 'hidden' : '';
                    return '
                        <button '.$hidden.' onclick="peringatan('.$data->detail_user_id.')" class="btn btn-xs btn-warning">Peringatan</button>
                    ';
                })
                ->make(true);
        }

        $data['lokasi_pegawai'] = LokasiPegawai::with('detail_user.pegawai_has_kategori.kategori_pegawai')
            ->whereRaw('waktu IN (SELECT MAX(waktu) FROM lokasi_pegawais GROUP BY (detail_user_id))')
            ->get()
            ;

        $data['lokasi_kantor'] = LokasiKantor::all();
        $shift_umum = Shift::find(1); // SHIFT UMUM
        $mulai = $shift_umum->mulai;
        $selesai = $shift_umum->selesai;
        
        $cur_time = date('H:i');
        $data['status_waktu'] = $cur_time >= $mulai && $cur_time <= $selesai ? 'Jam Kerja': 'Diluar Jam Kerja';
        // return $data;
        return view('sebaran_pegawai.main', $data);
    }
}
