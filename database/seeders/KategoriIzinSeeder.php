<?php

namespace Database\Seeders;

use App\Models\KategoriIzin;
use Illuminate\Database\Seeder;

class KategoriIzinSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        KategoriIzin::create([
            'nama' => 'izin Sakit' ,
            'lama' => 'Mengikuti Surat Dokter',
            'keterangan' => 'Ini adalah keterangannya',
        ]);
        KategoriIzin::create([
            'nama' => 'izin Cuti' ,
            'lama' => '7 hari',
            'keterangan' => 'Ini adalah Cuti',
        ]);
        KategoriIzin::create([
            'nama' => 'izin Keperluan Keluarga' ,
            'lama' => '1-3 hari',
            'keterangan' => 'Ini adalah keterangannya keperluan keluarga',
        ]);
    }
}
