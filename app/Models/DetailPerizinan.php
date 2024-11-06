<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPerizinan extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function perizinan()
    {
        return $this->belongsTo(Perizinan::class);
    }
}
