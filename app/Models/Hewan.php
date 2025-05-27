<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hewan extends Model
{
    public function pemilik()
    {
        return $this->belongsTo(Pemilik::class, 'pemilik_id');
    }
}