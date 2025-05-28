<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pet;
use App\Models\Diagnosa;
use App\Models\Dokter;
use App\Models\Pemilik;
use App\Models\Akun;
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

    public function store(Request $request)
    {
        Log::info('PetController@store: Request diterima', $request->all());

        // Validasi data sesuai dengan skema tabel 'pets'
        $validatedData = $request->validate([
            'nama' => 'required|string|max:255',
            'jenis' => 'required|string|max:255',
            'warna' => 'nullable|string|max:255', // Nullable di DB
            'usia' => 'required|integer|min:0',
            'kondisi' => 'required|string|max:255', // NOT NULL di DB
            'pemilik_id' => 'required|integer', // Ini adalah akun_id dari Flutter
            'dokter_id' => 'nullable|integer|exists:dokters,id', // Nullable di DB, jika dikirim
            'foto' => 'nullable|string|max:255', // Nullable di DB, jika dikirim
        ]);

        // --- PENTING: Konversi akun_id menjadi id primary key pemilik ---
        $akunIdDariFlutter = $validatedData['pemilik_id'];
        $pemilik = Pemilik::where('akun_id', $akunIdDariFlutter)->first();

        if (!$pemilik) {
            Log::error('PetController@store: Pemilik tidak ditemukan untuk akunId: ' . $akunIdDariFlutter);
            return response()->json(['status' => 'error', 'message' => 'Pemilik tidak valid atau tidak terdaftar'], 400);
        }
        Log::info("PetController@store: Pemilik ditemukan, ID: {$pemilik->id}");
        // ------------------------------------------------------------------

        try {
            $pet = Pet::create([
                'nama' => $validatedData['nama'],
                'jenis' => $validatedData['jenis'],
                'warna' => $validatedData['warna'] ?? null, // Gunakan null jika kosong dari Flutter
                'usia' => $validatedData['usia'],
                'kondisi' => $validatedData['kondisi'],
                'pemilik_id' => $pemilik->id, // <-- Gunakan ID primary key pemilik yang sebenarnya
                'dokter_id' => $validatedData['dokter_id'] ?? null,
                'foto' => $validatedData['foto'] ?? null,
            ]);

            Log::info("Hewan berhasil disimpan: ID {$pet->id}");
            return response()->json(['status' => 'success', 'message' => 'Hewan berhasil ditambahkan', 'data' => $pet], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error("PetController@store: Validasi gagal: " . json_encode($e->errors()));
            return response()->json([
                'status' => 'error',
                'message' => 'Data validasi tidak sesuai',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error("PetController@store: Gagal menyimpan hewan: " . $e->getMessage() . " di " . $e->getFile() . ":" . $e->getLine());
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menyimpan hewan: ' . $e->getMessage()
            ], 500);
        }
    }
}
