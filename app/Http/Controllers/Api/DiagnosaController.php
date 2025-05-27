<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Dokter;
use App\Models\Diagnosa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DiagnosaController extends Controller
{
    /**
     * Mengambil daftar diagnosa (beserta detail pasien) yang dilakukan oleh dokter tertentu.
     * Digunakan oleh HomeDokterScreen.
     *
     * @param int $akunId ID akun dokter dari Flutter
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDiagnosaByDokter($akunId)
    {
        Log::info("DiagnosaController@getDiagnosaByDokter: Menerima akun_id dokter: $akunId");

        // Langkah 1: Cari objek Dokter berdasarkan akun_id
        $dokter = Dokter::where('akun_id', $akunId)->first();

        if (!$dokter) {
            Log::warning("DiagnosaController@getDiagnosaByDokter: Dokter dengan akun_id $akunId tidak ditemukan di tabel dokters.");
            return response()->json(['status' => 'error', 'message' => 'Dokter tidak ditemukan'], 404);
        }

        Log::info("DiagnosaController@getDiagnosaByDokter: Dokter ditemukan, ID internal: {$dokter->id}");

        try {
            // Langkah 2: Cari diagnosa berdasarkan dokter_id dari objek Dokter
            // Gunakan eager loading 'with(pasien)' untuk menyertakan data pet terkait
            $diagnosas = Diagnosa::with('pasien')
                ->where('dokter_id', $dokter->id)
                ->orderBy('tanggal_diagnosa', 'desc') // Mengurutkan dari diagnosa terbaru
                ->get();

            Log::info("DiagnosaController@getDiagnosaByDokter: Ditemukan " . $diagnosas->count() . " diagnosa untuk dokter ID: {$dokter->id}");

            // Jika daftar diagnosa kosong, log ini dan kembalikan data kosong
            if ($diagnosas->isEmpty()) {
                Log::info("DiagnosaController@getDiagnosaByDokter: Tidak ada diagnosa ditemukan untuk dokter ID: {$dokter->id}.");
                return response()->json([
                    'status' => 'success',
                    'data' => [], // Mengembalikan array kosong jika tidak ada data
                ]);
            }

            // Jika ada diagnosa, log beberapa detail untuk verifikasi
            foreach ($diagnosas as $diagnosa) {
                Log::info("Diagnosa ID: {$diagnosa->id}, Hewan ID: {$diagnosa->hewan_id}, Catatan: {$diagnosa->catatan}");
                if ($diagnosa->pasien) {
                    Log::info("  Pasien terkait: {$diagnosa->pasien->nama} (ID: {$diagnosa->pasien->id})");
                } else {
                    Log::warning("  Diagnosa ID: {$diagnosa->id} tidak memiliki relasi pasien yang dimuat atau pasien null.");
                }
            }

            // Langkah 3: Mengembalikan respons JSON dengan data diagnosa
            return response()->json([
                'status' => 'success',
                'data' => $diagnosas, // Ini akan mengembalikan array objek diagnosa, dengan 'pasien' termuat
            ]);

        } catch (\Exception $e) {
            Log::error("DiagnosaController@getDiagnosaByDokter: Gagal mengambil diagnosa: " . $e->getMessage() . " di " . $e->getFile() . ":" . $e->getLine());
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengambil data diagnosa. Kesalahan internal server.',
                'error_detail' => $e->getMessage(), // Detail error untuk debugging
            ], 500);
        }
    }

    /**
     * Menyimpan diagnosa baru yang dibuat oleh dokter.
     * Digunakan oleh TambahDiagnosaScreen.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        Log::info("DiagnosaController@store: Request diterima", $request->all());

        // Validasi data dari request
        $validated = $request->validate([
            'hewanId' => 'required|integer|exists:pets,id', // 'integer' ditambahkan untuk tipe data
            'dokterId' => 'required|integer', // Ini adalah akun_id dokter dari Flutter, harus diubah ke ID Dokter di DB
            'tanggalDiagnosa' => 'required|date',
            'catatan' => 'nullable|string|max:1000', // max:1000 ditambahkan sebagai contoh
        ]);

        // Cari objek Dokter berdasarkan akun_id yang dikirim Flutter
        $dokter = Dokter::where('akun_id', $validated['dokterId'])->first();

        if (!$dokter) {
            Log::error('DiagnosaController@store: Dokter tidak ditemukan untuk akunId: ' . $validated['dokterId']);
            return response()->json(['status' => 'error', 'message' => 'Dokter tidak valid'], 400);
        }

        Log::info("DiagnosaController@store: Dokter ditemukan, ID internal: {$dokter->id}");

        try {
            // Buat record diagnosa baru di database
            $diagnosa = Diagnosa::create([
                'hewan_id' => $validated['hewanId'],
                'dokter_id' => $dokter->id, // <-- Gunakan ID primary key dokter di sini
                'tanggal_diagnosa' => $validated['tanggalDiagnosa'], // Nama kolom di DB
                'catatan' => $validated['catatan'],                   // Nama kolom di DB
            ]);

            Log::info("Diagnosa berhasil disimpan: ID {$diagnosa->id} untuk hewan {$diagnosa->hewan_id} oleh dokter {$diagnosa->dokter_id}");

            // Mengembalikan respons sukses
            return response()->json([
                'status' => 'success',
                'message' => 'Diagnosa berhasil ditambahkan',
                'data' => $diagnosa // Mengembalikan objek diagnosa yang baru dibuat
            ], 201); // Status 201 Created
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error("DiagnosaController@store: Validasi gagal: " . json_encode($e->errors()));
            return response()->json([
                'status' => 'error',
                'message' => 'Data validasi tidak sesuai',
                'errors' => $e->errors()
            ], 422); // Unprocessable Entity
        } catch (\Exception $e) {
            Log::error("DiagnosaController@store: Gagal menyimpan diagnosa: " . $e->getMessage() . " di " . $e->getFile() . ":" . $e->getLine());
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menyimpan diagnosa: ' . $e->getMessage() // Detail error untuk debugging
            ], 500); // Internal Server Error
        }
    }
}