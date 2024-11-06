<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'username' => 'admin',
            // 'nomor' => '',
            'password' => Hash::make('admin'),
            'status' => 1,
            'level' => 1,
            'token' => '-',
        ]);

        User::create([
            'username' => 'pegawai1',
            'nomor' => '1111',
            'password' => Hash::make('1234'),
            'status' => 1,
            'level' => 2,
            'token' => Str::random(15) . substr(md5(date('Y-m-d H:i:s', strtotime('now'))), -15),
        ]);
        User::create([
            'username' => 'pegawai2',
            'nomor' => '2222',
            'password' => Hash::make('1234'),
            'status' => 1,
            'level' => 2,
            'token' => Str::random(15) . substr(md5(date('Y-m-d H:i:s', strtotime('now'))), -15),
        ]);
        // User::create([
        //     'username' => 'pegawai3',
        //     'nomor' => '3333',
        //     'password' => Hash::make('1234'),
        //     'status' => 1,
        //     'level' => 2,
        //     'token' => Str::random(15) . substr(md5(date('Y-m-d H:i:s', strtotime('now'))), -15),
        // ]);
        // User::create([
        //     'username' => 'pegawai4',
        //     'nomor' => '4444',
        //     'password' => Hash::make('1234'),
        //     'status' => 1,
        //     'level' => 2,
        //     'token' => Str::random(15) . substr(md5(date('Y-m-d H:i:s', strtotime('now'))), -15),
        // ]);
    }
}
