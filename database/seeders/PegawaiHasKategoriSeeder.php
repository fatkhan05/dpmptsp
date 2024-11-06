<?php

namespace Database\Seeders;

use App\Models\PegawaiHasKategori;
use Illuminate\Database\Seeder;

class PegawaiHasKategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PegawaiHasKategori::create([
            'detail_user_id' => 1,
            'kategori_pegawai_id' => 1,
        ]);

        PegawaiHasKategori::create([
            'detail_user_id' => 2,
            'kategori_pegawai_id' => 2,
        ]);

        PegawaiHasKategori::create([
            'detail_user_id' => 3,
            'kategori_pegawai_id' => 2,
        ]);
    }
}
