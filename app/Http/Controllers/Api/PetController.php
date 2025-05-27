<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pet;
use App\Models\Diagnosa;
use App\Models\Dokter;
use App\Models\Pemilik;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PetController extends Controller
{
    // Untuk pemilik: ambil semua hewan milik akun_id tertentu
    public function petsByPemilikAkun($akunId)
    {
        Log::info("PetController@petsByPemilikAkun: Menerima akun_id pemilik: $akunId");

        $pemilik = Pemilik::where('akun_id', $akunId)->first();

        if (!$pemilik) {
            Log::warning("Pemilik dengan akun_id $akunId tidak ditemukan.");
            return response()->json(['message' => 'Pemilik tidak ditemukan'], 404);
        }

        $pets = Pet::where('pemilik_id', $pemilik->id)->get();

        return response()->json([
            'status' => 'success',
            'data' => $pets,
        ]);
    }

    // Untuk dokter: ambil semua hewan yang sedang ditangani dokter tersebut
    public function petsByDokter($akunId)
    {
        Log::info("PetController@petsByDokter: Menerima akun_id dokter: $akunId");

        $dokter = Dokter::where('akun_id', $akunId)->first();

        if (!$dokter) {
            Log::warning("Dokter dengan akun_id $akunId tidak ditemukan.");
            return response()->json(['message' => 'Dokter tidak ditemukan'], 404);
        }

        $pets = Pet::where('dokter_id', $dokter->id)->get();

        return response()->json([
            'status' => 'success',
            'data' => $pets,
        ]);
    }

    // Untuk dokter: ambil semua hewan yang pernah didiagnosa oleh dokter ini
    public function getPetsByDokterVisitedOwner($akunId)
    {
        Log::info("PetController@getPetsByDokterVisitedOwner: Menerima akun_id dokter: $akunId");

        $dokter = Dokter::where('akun_id', $akunId)->first();
        if (!$dokter) {
            return response()->json(['message' => 'Dokter tidak ditemukan'], 404);
        }

        $pets = Pet::whereIn('pemilik_id', function ($query) use ($dokter) {
            $query->select('pemilik_id')
                  ->from('pets')
                  ->join('diagnosas', 'pets.id', '=', 'diagnosas.hewan_id')
                  ->where('diagnosas.dokter_id', $dokter->id);
        })->get();

        return response()->json([
            'status' => 'success',
            'data' => $pets,
        ]);
    }

    // Opsional: jika ingin hewan berdasarkan diagnosa spesifik dari dokter ini
    public function getPetsByDokterDiagnosa($akunId)
    {
        Log::info("PetController@getPetsByDokterDiagnosa: Menerima akun_id dokter: $akunId");

        $dokter = Dokter::where('akun_id', $akunId)->first();
        if (!$dokter) {
            return response()->json(['message' => 'Dokter tidak ditemukan'], 404);
        }

        $hewanIds = Diagnosa::where('dokter_id', $dokter->id)->pluck('hewan_id')->unique();

        $pets = Pet::with(['diagnosa' => function ($query) use ($dokter) {
            $query->where('dokter_id', $dokter->id);
        }])
        ->whereIn('id', $hewanIds)
        ->get();

        return response()->json([
            'status' => 'success',
            'data' => $pets,
        ]);
    }

    // di PetController@getPetsByPemilikAkunId atau PemilikController@getPetsByPemilikAkunId
    // (Catatan: kode berikut harus berada di dalam sebuah method, bukan langsung di dalam kelas)
}
