<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pekerjaan extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function pegawai_has_pekerjaan()
    {
        return $this->hasMany(PegawaiHasPekerjaan::class);
    }

    public function kategori_has_pekerjaan()
    {
        return $this->hasMany(KategoriHasPekerjaan::class);
    }
}
