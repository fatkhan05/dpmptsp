<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PegawaiHasPekerjaan extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function detail_user()
    {
        return $this->belongsTo(DetailUser::class);
    }

    public function pekerjaan()
    {
        return $this->belongsTo(Pekerjaan::class);
    }
}