<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Desa extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function kecamtan()
    {
        return $this->belongsTo(Kecamatan::class);
    }

    public function detail_user()
    {
        return $this->hasMany(DetailUser::class);
    }
}
