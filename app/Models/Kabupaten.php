<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kabupaten extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function kecamtan()
    {
        return $this->hasMany(Kecamatan::class);
    }

    public function provinsi()
    {
        return $this->belongsTo(provinsi::class);
    }

    public function detail_user()
    {
        return $this->hasMany(DetailUser::class);
    }
}
