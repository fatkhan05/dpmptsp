<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriPegawai extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function pegawai_has_kategori()
    {
        return $this->hasMany(PegawaiHasKategori::class);
    }

    public function koordinator()
    {
        return $this->belongsTo(DetailUser::class, 'detail_user_id');
    }

    public function kategori_has_pekerjaan()
    {
        return $this->hasMany(KategoriHasPekerjaan::class);
    }

    public function shift()
    {
        return $this->hasMany(Shift::class);
    }
    public function bidang()
    {
        return $this->belongsTo(Bidang::class);
    }
}