<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Akun; // Pastikan ini adalah model untuk tabel akun/users Anda
use App\Models\Pemilik; // Pastikan model Pemilik di-import
use App\Models\Pet; // Pastikan model Pet di-import (jika Anda ingin memuat relasi hewan langsung di sini)
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AkunController extends Controller
{
    public function show($id)
    {
        Log::info("AkunController@show: Menerima ID akun: $id"); // Tambahkan semicolon

        try {
            // Ambil data akun (user) beserta relasi pemilik dan hewan (pets)
            // 'pemilik.pets' berarti: dari Akun -> load relasi 'pemilik' -> dari Pemilik load relasi 'pets'
            $akun = Akun::with(['pemilik.pets'])->findOrFail($id); // Gunakan $akun bukan $user agar konsisten

            // Pastikan relasi 'pemilik' tidak null sebelum mengakses propertinya
            if (is_null($akun->pemilik)) {
                Log::warning("AkunController@show: Data pemilik tidak ditemukan untuk akun ID: $id");
                // Jika tidak ada data pemilik, kembalikan respons yang sesuai
                return response()->json([
                    'status' => 'success',
                    'data' => [
                        'email' => $akun->email,
                        'pemilik' => null, // Menandakan tidak ada data pemilik
                        'hewan' => [] // Menandakan tidak ada data hewan
                    ]
                ], 200);
            }

            // Format respons sesuai kebutuhan Flutter
            $formattedResponse = [
                'email' => $akun->email,
                'pemilik' => [
                    'id' => $akun->pemilik->id ?? null, // Tambahkan ID pemilik
                    'nama' => $akun->pemilik->nama ?? null,
                    'telepon' => $akun->pemilik->telepon ?? null,
                    'alamat' => $akun->pemilik->alamat ?? null,
                ],
                // Pastikan $akun->pemilik->pets ada dan itu adalah Collection
                'hewan' => $akun->pemilik->pets->map(function ($hewan) {
                    return [
                        'id' => $hewan->id, // Tambahkan ID hewan
                        'nama' => $hewan->nama,
                        'jenis' => $hewan->jenis,
                        'warna' => $hewan->warna, // Tambahkan detail lain yang relevan
                        'usia' => $hewan->usia,
                        // Tambahkan 'catatan', 'pemilik_id', 'dokter_id' jika diperlukan untuk tampilan
                        'catatan' => $hewan->catatan,
                        'pemilik_id' => $hewan->pemilik_id,
                        'dokter_id' => $hewan->dokter_id,
                    ];
                })->values()->toArray(), // .values() dan .toArray() untuk memastikan menjadi array biasa di JSON
            ];

            Log::info("AkunController@show: Profil berhasil diambil untuk akun ID: $id");
            return response()->json([
                'status' => 'success', // Tambahkan status sesuai konvensi API Anda
                'data' => $formattedResponse
            ], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::warning("AkunController@show: Akun dengan ID $id tidak ditemukan (ModelNotFoundException).");
            return response()->json([
                'status' => 'error',
                'message' => 'Profil tidak ditemukan',
            ], 404);
        } catch (\Exception $e) {
            Log::error("AkunController@show: Gagal mengambil data profil: " . $e->getMessage() . " di " . $e->getFile() . ":" . $e->getLine());
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengambil data profil. Kesalahan internal server.',
                'error_detail' => $e->getMessage() // Detail error untuk debugging
            ], 500);
        }
    }
}