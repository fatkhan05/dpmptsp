<?php

namespace Database\Seeders;

use App\Models\Shift;
use Illuminate\Database\Seeder;

class ShiftSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Shift::create([
            'nama' => 'Shift Umum',
            'mulai' => '08:00:00',
            'selesai' => '17:00:00',
        ]);
        // Shift::create([
        //     'nama' => 'Shift 1',
        //     'kategori_pegawai_id' => 3,
        //     'mulai' => '07:00:00',
        //     'selesai' => '12:00:00',
        // ]);
        // Shift::create([
        //     'nama' => 'Shift 2',
        //     'kategori_pegawai_id' => 3,
        //     'mulai' => '12:00:00',
        //     'selesai' => '19:00:00',
        // ]);
        // Shift::create([
        //     'nama' => 'Shift 3',
        //     'kategori_pegawai_id' => 3,
        //     'mulai' => '19:00:00',
        //     'selesai' => '07:00:00',
        // ]);
    }
}
