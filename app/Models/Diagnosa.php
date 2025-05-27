<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Diagnosa extends Model
{
    use HasFactory;

    protected $table = 'diagnosas';
    protected $fillable = [
        'hewan_id', 'dokter_id', 'tanggal_diagnosa', 'catatan'
    ];

     public function pasien() // Pastikan nama relasi ini 'pasien'
    {
        return $this->belongsTo(Pet::class, 'hewan_id'); // foreign key di tabel diagnosas adalah 'hewan_id'
    }

    public function hewan()
    {
        return $this->belongsTo(Pet::class, 'hewan_id');
    }

    public function dokter()
    {
        return $this->belongsTo(Akun::class, 'dokter_id');
    }

    public function obats()
    {
    return $this->hasMany(Obat::class);
    }
    
    public function pet()
    {
        return $this->belongsTo(Pet::class, 'hewan_id');
    }
}