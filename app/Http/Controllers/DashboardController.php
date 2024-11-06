<?php

namespace App\Http\Controllers;

use App\Models\Pekerjaan;
use App\Models\DetailUser;
use Illuminate\Http\Request;
use App\Models\LokasiPegawai;

class DashboardController extends Controller
{
    public function index()
    {
        $data['jumlah_pegawai'] = DetailUser::all()->count();
        $data['jumlah_pekerjaan'] = Pekerjaan::all()->count();
        $data['jumlah_pegawai_diluar'] = LokasiPegawai::with('detail_user.pegawai_has_kategori.kategori_pegawai')
            ->whereRaw('waktu IN (SELECT MAX(waktu) FROM lokasi_pegawais GROUP BY (detail_user_id))')
            ->where('lokasi', 'Luar')
            ->get()
            ->count()
        ;
        return view('dashboard.main', $data);
    }
}
