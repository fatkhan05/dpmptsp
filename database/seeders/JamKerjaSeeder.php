<?php

namespace Database\Seeders;

use App\Models\JamKerja;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JamKerjaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        JamKerja::create([
            'masuk' => '08:00:00',
            'pulang' => '17:00:00', 
        ]);
    }
}
