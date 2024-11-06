<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Bidang extends Model
{
    use HasFactory;

    public function kategori_pegawai()
    {
        return $this->hasMany(KategoriPegawai::class);
    }
}