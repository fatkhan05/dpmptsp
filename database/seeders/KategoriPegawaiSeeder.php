<?php

namespace Database\Seeders;

use App\Models\KategoriPegawai;
use Illuminate\Database\Seeder;

class KategoriPegawaiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        KategoriPegawai::create([
            'nama' => 'Admin',
            'kode' => 'DK-001',
            'detail_user_id' => 1
        ]);

        // KategoriPegawai::create([
        //     'nama' => 'Kebersihan',
        //     'kode' => 'DK-002',
        //     'detail_user_id' => 2
        // ]);

        // KategoriPegawai::create([
        //     'nama' => 'Keamanan',
        //     'kode' => 'DK-003',
        //     'detail_user_id' => 4
        // ]);
    }
}
