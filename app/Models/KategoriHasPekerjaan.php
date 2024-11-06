<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriHasPekerjaan extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function kategori_pegawai()
    {
        return $this->belongsTo(KategoriPegawai::class);
    }

    public function pekerjaan()
    {
        return $this->belongsTo(Pekerjaan::class);
    }
}
