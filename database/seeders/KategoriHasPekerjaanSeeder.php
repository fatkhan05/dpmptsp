<?php

namespace Database\Seeders;

use App\Models\KategoriHasPekerjaan;
use Illuminate\Database\Seeder;

class KategoriHasPekerjaanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        KategoriHasPekerjaan::create([
            'kategori_pegawai_id' => 2,
            'pekerjaan_id' => 1,
        ]);
        KategoriHasPekerjaan::create([
            'kategori_pegawai_id' => 2,
            'pekerjaan_id' => 2,
        ]);
        KategoriHasPekerjaan::create([
            'kategori_pegawai_id' => 2,
            'pekerjaan_id' => 3,
        ]);
        KategoriHasPekerjaan::create([
            'kategori_pegawai_id' => 2,
            'pekerjaan_id' => 4,
        ]);

        KategoriHasPekerjaan::create([
            'kategori_pegawai_id' => 3,
            'pekerjaan_id' => 3,
        ]);
        KategoriHasPekerjaan::create([
            'kategori_pegawai_id' => 3,
            'pekerjaan_id' => 4,
        ]);
        KategoriHasPekerjaan::create([
            'kategori_pegawai_id' => 3,
            'pekerjaan_id' => 5,
        ]);
        KategoriHasPekerjaan::create([
            'kategori_pegawai_id' => 3,
            'pekerjaan_id' => 6,
        ]);
    }
}
