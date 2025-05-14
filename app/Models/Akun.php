<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Akun extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'email',
        'password',
        'role',
        'foto',
    ];

    public function pets()
    {
        return $this->hasMany(Pet::class, 'pemilik_id');
    }
}
