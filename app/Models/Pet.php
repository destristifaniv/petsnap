<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pet extends Model
{
    use HasFactory;

    protected $table = 'pets';
    protected $fillable = [
        'foto',      // Foto hewan
        'nama',      // Nama hewan
        'jenis',     // Jenis hewan
        'warna',     // Warna hewan
        'usia',      // Usia hewan
        'catatan',   // Kondisi hewan
        'pemilik_id' // ID pemilik hewan (relasi ke tabel Akun)
    ];

    public function pemilik()
    {
        return $this->belongsTo(Akun::class, 'pemilik_id');
    }

     public function dokter()
    {
        return $this->belongsTo(Dokter::class, 'dokter_id');
    }

    public function diagnosas()
    {
        return $this->hasMany(Diagnosa::class, 'hewan_id');
    }
}