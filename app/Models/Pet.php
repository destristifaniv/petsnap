<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pet extends Model
{
    use HasFactory;

    protected $fillable = [
        'foto',      // Foto hewan
        'nama',      // Nama hewan
        'jenis',     // Jenis hewan
        'warna',     // Warna hewan
        'usia',      // Usia hewan
        'kondisi',   // Kondisi hewan
        'pemilik_id' // ID pemilik hewan (relasi ke tabel Akun)
    ];

    public function pemilik()
    {
        return $this->belongsTo(Akun::class, 'pemilik_id');
    }
}
