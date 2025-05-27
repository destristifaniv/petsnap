<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dokter extends Model
{
    use HasFactory;

    protected $fillable = ['akun_id', 'nama', 'alamat', 'no_hp', 'foto'];

    public function akun()
    {
        return $this->belongsTo(Akun::class);
    }
}
