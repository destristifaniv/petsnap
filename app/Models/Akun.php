<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Akun extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'akuns'; // Pastikan nama tabel sesuai di database

    protected $fillable = [
        'nama',
        'email',
        'password',
        'role',
        'foto',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Relasi: satu akun memiliki satu pemilik
     */
    public function pemilik()
    {
        return $this->hasOne(Pemilik::class, 'akun_id', 'id');
    }

    /**
     * Relasi: satu akun memiliki satu dokter
     */
    public function dokter()
    {
        return $this->hasOne(Dokter::class, 'akun_id', 'id');
    }

    /**
     * Relasi: satu akun (pemilik) memiliki banyak pet
     */
    public function pets()
    {
        return $this->hasMany(Pet::class, 'pemilik_id', 'id');
    }
}
