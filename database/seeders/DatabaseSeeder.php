<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call([
            UserSeeder::class,
            KategoriPegawaiSeeder::class,
            ProvinsiSeeder::class,
            KabupatenSeeder::class,
            KecamatanSeeder::class,
            DesaSeeder::class,
            DetailUserSeeder::class,
            PegawaiHasKategoriSeeder::class,
            ShiftSeeder::class,
            // PekerjaanSeeder::class,
            // KategoriIzinSeeder::class,
            // KategoriHasPekerjaanSeeder::class,
            // PegawaiHasPekerjaanSeeder::class,
            // JamKerjaSeeder::class,
            // LokasiKantorSeeder::class,
        ]);
    }
}
