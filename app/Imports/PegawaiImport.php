<?php

namespace App\Imports;

use App\Http\Controllers\Controller;
use App\Models\DetailUser;
use App\Models\KategoriPegawai;
use App\Models\PegawaiHasKategori;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;

class PegawaiImport implements ToCollection,WithStartRow
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        $controller = new Controller;
        $kategori_pegawai_object = new KategoriPegawai;
        foreach ($rows as $row)
        {
            // echo "<pre>";
            // print_r($row[7]);
            // echo "</pre>";
            // exit();

            $detail_user = DetailUser::where('nik', $row[2])->first();
            
            if (!$detail_user) { // JIKA PEGAWAI BELUM ADA
                $user = User::create([
                    'username' => date('YmdHis'),
                    'password' => Hash::make($row[2]),
                    'level' => 2,
                    'status' => 1,
                ]);

                $detail_user = new DetailUser;
                $detail_user->user_id = $user->id;
                $detail_user->nik = $row[2];
            }
            
            $detail_user->nama = $row[1];
            $detail_user->telepon = $row[4];
            $detail_user->pendidikan = $row[5];
            $detail_user->email = $row[6];
            $detail_user->tgl_mulai_kerja = $row[7];
            $detail_user->alamat = $row[8];
            $detail_user->save();

            // $detail_user = DetailUser::firstOrCreate([
            //     'user_id' => $user->id,
            //     'nama' => $row[1],
            //     'nik' => $row[2],
            //     'telepon' => $row[4],
            //     'pendidikan' => $row[5],
            //     'email' => $row[6],
            //     'tgl_mulai_kerja' => $row[7],
            //     'alamat' => $row[8],
            // ]);

            if (!empty($row[3])) {
                $kategori_pegawai = KategoriPegawai::firstOrCreate(
                    ['nama' => $row[3]],
                    ['kode' => $controller->generateKode($kategori_pegawai_object, 'DK', 3)]
                );
                $pegawai_has_kategori = PegawaiHasKategori::firstOrCreate([
                    'detail_user_id' => $detail_user->id,
                    'kategori_pegawai_id' => $kategori_pegawai->id,
                ]);
            }

        }
    }

    public function startRow(): int
    {
         return 2;
    }
}
