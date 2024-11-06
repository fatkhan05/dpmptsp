<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailUser extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pegawai_has_pekerjaan()
    {
        return $this->hasMany(PegawaiHasPekerjaan::class);
    }

    public function provinsi()
    {
        return $this->belongsTo(Provinsi::class);
    }

    public function kabupaten()
    {
        return $this->belongsTo(Kabupaten::class);
    }

    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class);
    }

    public function desa()
    {
        return $this->belongsTo(Desa::class);
    }

    public function perizinan()
    {
        return $this->hasMany(Perizinan::class);
    }

    public function presensi()
    {
        return $this->hasMany(Presensi::class);
    }

    public function pegawai_has_kategori()
    {
        return $this->hasMany(PegawaiHasKategori::class);
    }

    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }

    public function point_target()
    {
        return $this->hasOne(PointTarget::class);
    }

    public function lokasi_pegawai()
    {
        return $this->hasMany(LokasiPegawai::class);
    }

    public function notification()
    {
        return $this->hasMany(Notification::class);
    }
    public function bidang()
    {
        return $this->belongsTo(Bidang::class);
    }
}