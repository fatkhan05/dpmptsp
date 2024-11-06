<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function kategori_pegawai()
    {
        return $this->belongsTo(KategoriPegawai::class);
    }

    public function detail_user()
    {
        return $this->hasMany(DetailUser::class);
    }
}
