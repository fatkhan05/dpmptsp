<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Perizinan extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function kategori_izin()
    {
        return $this->belongsTo(KategoriIzin::class);
    }

    public function detail_user()
    {
        return $this->belongsTo(DetailUser::class);
    }

    public function detail_perizinan()
    {
        return $this->hasMany(DetailPerizinan::class);
    }
}
