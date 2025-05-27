<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Akun;
use Illuminate\Support\Facades\Hash;

class AkunSeeder extends Seeder
{
    public function run()
    {
        Akun::create([
            'nama' => 'dokter1',
            'email' => 'dokter1@mail.com',
            'password' => Hash::make('password123'),
            'role' => 'dokter',
            'foto' => null,
        ]);

        Akun::create([
            'nama' => 'pemilik1',
            'email' => 'pemilik1@mail.com',
            'password' => Hash::make('password123'),
            'role' => 'pemilik',
            'foto' => null,
        ]);
    }
}
