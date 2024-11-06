<?php

namespace Database\Seeders;

use App\Models\Pekerjaan;
use DateTime;
use Illuminate\Database\Seeder;

class PekerjaanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $mulai=mktime(11, 14, 54, 8, 8, 2022);
        $selesai=mktime(11, 14, 54, 9, 12, 2022);

        Pekerjaan::create([
            'nama' => 'pekerjaan a harus dikerjakan',
            'lokasi' => 'Dalam',
            'mulai' => date("Y-m-d h:i:s", $mulai),
            'selesai' => date("Y-m-d h:i:s", $selesai),
        ]);

        Pekerjaan::create([
            'nama' => 'pekerjaan b harus dikerjakan',
            'lokasi' => 'Dalam',
            'mulai' => date("Y-m-d h:i:s", $mulai),
            'selesai' => date("Y-m-d h:i:s", $selesai),
        ]);

        Pekerjaan::create([
            'nama' => 'pekerjaan c harus dikerjakan',
            'lokasi' => 'Dalam',
            'mulai' => date("Y-m-d h:i:s", $mulai),
            'selesai' => date("Y-m-d h:i:s", $selesai),
        ]);

        Pekerjaan::create([
            'nama' => 'pekerjaan d harus dikerjakan',
            'lokasi' => 'Dalam',
            'mulai' => date("Y-m-d h:i:s", $mulai),
            'selesai' => date("Y-m-d h:i:s", $selesai),
        ]);
        
        Pekerjaan::create([
            'nama' => 'pekerjaan e harus dikerjakan',
            'lokasi' => 'Dalam',
            'mulai' => date("Y-m-d h:i:s", $mulai),
            'selesai' => date("Y-m-d h:i:s", $selesai),
        ]);
        
        Pekerjaan::create([
            'nama' => 'pekerjaan f harus dikerjakan',
            'lokasi' => 'Luar',
            'alamat' => 'Jl. Raya Blok B',
            'mulai' => date("Y-m-d h:i:s", $mulai),
            'selesai' => date("Y-m-d h:i:s", $selesai),
        ]);
    }
}
