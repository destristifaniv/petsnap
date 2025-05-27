<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Import semua Controller yang digunakan
use App\Http\Controllers\Api\AkunController;
use App\Http\Controllers\Api\PetController;
use App\Http\Controllers\Api\DiagnosaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DokterController;

/*
|--------------------------------------------------------------------------
| Public API Routes (No Authentication Needed)
|--------------------------------------------------------------------------
| Ini adalah route untuk login dan register (tidak butuh token).
*/

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

// Route untuk mengambil data dokter berdasarkan akun_id (TIDAK BUTUH TOKEN)
Route::get('/dokter-by-akun/{akunId}', [DokterController::class, 'getByAkunId']);

/*
|--------------------------------------------------------------------------
| Protected API Routes (Requires Sanctum Token)
|--------------------------------------------------------------------------
| Route ini hanya bisa diakses jika sudah login dengan token.
*/

Route::middleware('auth:sanctum')->group(function () {

    // Mengambil data user yang sedang login
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Profil akun pemilik (beserta relasi ke pemilik dan pets-nya)
    Route::get('/profil/{id}', [AkunController::class, 'show']);

    /*
    |--------------------------------------------------------------------------
    | Dokter
    |--------------------------------------------------------------------------
    */

    // Update profil dokter berdasarkan akun_id
    Route::put('/dokter/update-by-akun/{akunId}', [DokterController::class, 'updateByAkun']);

    /*
    |--------------------------------------------------------------------------
    | Diagnosa
    |--------------------------------------------------------------------------
    */

    // Simpan diagnosa baru oleh dokter
    Route::post('/dokter/diagnosas', [DiagnosaController::class, 'store']);

    // Ambil daftar diagnosa oleh dokter tertentu
    Route::get('/diagnosa-by-dokter/{akunId}', [DiagnosaController::class, 'getDiagnosaByDokter']);

    /*
    |--------------------------------------------------------------------------
    | Hewan Peliharaan (Pets)
    |--------------------------------------------------------------------------
    */

    // Daftar hewan yang ditugaskan ke dokter
    Route::get('/pets/by-dokter/{dokterId}', [PetController::class, 'petsByDokter']);

    // Daftar hewan yang telah didiagnosa oleh dokter
    Route::get('/pets/diagnosa-by-dokter/{dokterId}', [PetController::class, 'getPetsByDokterDiagnosa']);

    // Daftar hewan milik pemilik berdasarkan akun ID
    Route::get('/pets/by-pemilik-akun/{akunId}', [PetController::class, 'petsByPemilikAkun']);

    /*
    |--------------------------------------------------------------------------
    | CRUD Hewan (Jika diaktifkan)
    |--------------------------------------------------------------------------
    | Hapus komentar ini jika ingin pakai resource controller standar.
    */

    // Route::apiResource('pets', PetController::class);
});
