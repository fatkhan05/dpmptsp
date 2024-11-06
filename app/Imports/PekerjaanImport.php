<?php

namespace App\Imports;

use App\Models\DetailUser;
use App\Models\KategoriHasPekerjaan;
use App\Models\KategoriPegawai;
use App\Models\PegawaiHasPekerjaan;
use App\Models\Pekerjaan;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;

class PekerjaanImport implements ToCollection,WithStartRow
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $detail_user = DetailUser::where('nik', $row[1])->first();
            $multi_pekerjaan = explode(';', $row[3]);

            // echo "<pre>";
            // print_r($row[2]);
            // echo "</pre>";
            // exit();

            // CEK APAKAH KATEGORI DI EXCEL TIDAK KOSONG APA TIDAK
            if (!empty($row[2])) { // JIKA TIDAK KOSONG MAKA AMBIL id_kategori_pegawai BERDASARKAN EXCEL
                $kategori_pegawai = KategoriPegawai::where('nama', 'like', "%$row[2]%")->first();
                $kategori_pegawai_id = $kategori_pegawai->id;
            } else {
                $kategori_pegawai_id = $detail_user->pegawai_has_kategori[0]->kategori_pegawai_id;
            }
            
            foreach ($multi_pekerjaan as $key) {
                if (!empty($key)) {
                    $pekerjaan = Pekerjaan::create([
                        'nama' => $key,
                        'lokasi' => 'Dalam',
                        'alamat' => $row[4],
                    ]);
                    $kategori_has_pekerjaan = KategoriHasPekerjaan::create([
                        'kategori_pegawai_id' => $kategori_pegawai_id,
                        'pekerjaan_id' => $pekerjaan->id,
                    ]);
    
                    $pegawai_has_pekerjaan = PegawaiHasPekerjaan::create([
                        'detail_user_id' => $detail_user->id,
                        'pekerjaan_id' => $pekerjaan->id,
                    ]);
                }
            }
        }
    }

    public function startRow(): int
    {
         return 2;
    }
}
