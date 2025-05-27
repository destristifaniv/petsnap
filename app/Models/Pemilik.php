<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pemilik extends Model
{
    use HasFactory;

    protected $table = 'pemiliks'; // Pastikan nama tabel benar
    protected $fillable = [
        'nama', 'telepon', 'alamat', 'akun_id' // Pastikan 'akun_id' ada di sini
    ];

    // Relasi ke Akun (satu pemilik dimiliki oleh satu akun)
    public function akun()
    {
        return $this->belongsTo(Akun::class, 'akun_id');
    }

    // Relasi ke Pets (satu pemilik bisa punya banyak pet)
    public function pets()
    {
        // 'pemilik_id' adalah foreign key di tabel 'pets' yang menunjuk ke 'pemiliks.id'
        return $this->hasMany(Pet::class, 'pemilik_id', 'id');
    }
}