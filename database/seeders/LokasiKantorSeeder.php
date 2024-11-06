<?php

namespace Database\Seeders;

use App\Models\LokasiKantor;
use Illuminate\Database\Seeder;

class LokasiKantorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        LokasiKantor::create([
            'nama' => 'DPMPTSP',
            'latitude' => -7.482949,
            'longitude' => 112.449323,
            'radius' => 20,
            'alamat' => 'Jl. Raya Blok CGI',
            'deskripsi' => 'Ini adalah kantor utama'
        ]);
    }
}
