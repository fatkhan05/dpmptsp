<?php

namespace Database\Seeders;

use App\Models\DetailUser;
use Illuminate\Database\Seeder;

class DetailUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DetailUser::create([
            'user_id' => 1,
            'nama' => 'Admin',
            'photo' => 'profile/photo_profile.jpg',
            'nik' => '11223344',
            'jenis_kelamin' => 'Laki-laki',
            'tempat_lahir' => 'Tempat Lahitnya',
            'tgl_lahir' => '1999-12-30',
            'telepon' => '09876543',
            'tgl_mulai_kerja' => date('Y-m-d'),
            // 'kategori_pegawai_id' => 1,
            'alamat' => 'alamat nya',
            'provinsi_id' => 11,
            'kabupaten_id' => 1101,
            'kecamatan_id' => 1101010,
            'desa_id' => 1101010001,
        ]);

        DetailUser::create([
            'user_id' => 2,
            'nama' => 'Pegawai kebersihan A',
            'photo' => 'profile/photo_profile.jpg',
            'nik' => '11223344',
            'jenis_kelamin' => 'Perempuan',
            'tempat_lahir' => 'Tempat Lahitnya',
            'tgl_lahir' => '1999-12-30',
            'telepon' => '09876543',
            'tgl_mulai_kerja' => date('Y-m-d'),
            // 'kategori_pegawai_id' => 2, // kebersihan
            'alamat' => 'alamat nya',
            'provinsi_id' => 11,
            'kabupaten_id' => 1101,
            'kecamatan_id' => 1101010,
            'desa_id' => 1101010001,
        ]);
        DetailUser::create([
            'user_id' => 3,
            'nama' => 'Pegawai kebersihan B',
            'photo' => 'profile/photo_profile.jpg',
            'nik' => '11223344',
            'jenis_kelamin' => 'Perempuan',
            'tempat_lahir' => 'Tempat Lahitnya',
            'tgl_lahir' => '1999-12-30',
            'telepon' => '09876543',
            'tgl_mulai_kerja' => date('Y-m-d'),
            // 'kategori_pegawai_id' => 2, // kebersihan
            'alamat' => 'alamat nya',
            'provinsi_id' => 11,
            'kabupaten_id' => 1101,
            'kecamatan_id' => 1101010,
            'desa_id' => 1101010001,
        ]);

        // DetailUser::create([
        //     'user_id' => 4,
        //     'nama' => 'Pegawai Keamanan C',
        //     'photo' => 'profile/photo_profile.jpg',
        //     'nik' => '11223344',
        //     'jenis_kelamin' => 'Laki-Laki',
        //     'tempat_lahir' => 'Tempat Lahitnya',
        //     'tgl_lahir' => '1999-12-30',
        //     'telepon' => '09876543',
        //     'tgl_mulai_kerja' => date('Y-m-d'),
        //     'kategori_pegawai_id' => 3, // kemananan
        //     'alamat' => 'alamat nya',
        //     'provinsi_id' => 11,
        //     'kabupaten_id' => 1101,
        //     'kecamatan_id' => 1101010,
        //     'desa_id' => 1101010001,
        // ]);
        // DetailUser::create([
        //     'user_id' => 5,
        //     'nama' => 'Pegawai Keamanan D',
        //     'photo' => 'default-profile/male1.png',
        //     'nik' => '11223344',
        //     'jenis_kelamin' => 'Laki-Laki',
        //     'tempat_lahir' => 'Tempat Lahitnya',
        //     'tgl_lahir' => '1999-12-30',
        //     'telepon' => '09876543',
        //     'tgl_mulai_kerja' => date('Y-m-d'),
        //     'kategori_pegawai_id' => 3, // kemananan
        //     'alamat' => 'alamat nya',
        //     'provinsi_id' => 11,
        //     'kabupaten_id' => 1101,
        //     'kecamatan_id' => 1101010,
        //     'desa_id' => 1101010001,
        // ]);


    }
}
