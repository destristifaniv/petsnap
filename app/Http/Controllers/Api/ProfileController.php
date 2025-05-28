<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Akun; // Model untuk tabel users atau akun
use App\Models\Pemilik; // Model untuk tabel pemilik
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB; // Untuk transaksi database

class ProfileController extends Controller
{
    /**
     * Get user profile data (akun and associated pemilik data).
     *
     * @param  int  $akunId
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($akunId)
    {
        // Pastikan akunId adalah ID dari tabel akuns (users)
        $akun = Akun::with('pemilik')->find($akunId); // Eager load relasi pemilik

        if (!$akun) {
            return response()->json(['message' => 'Akun tidak ditemukan'], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'id' => $akun->id,
                'email' => $akun->email,
                'role' => $akun->role,
                'created_at' => $akun->created_at,
                'updated_at' => $akun->updated_at,
                'pemilik' => $akun->pemilik ? $akun->pemilik->toArray() : null, // Pastikan pemilik ada
            ]
        ]);
    }

    /**
     * Update user profile data (akun and associated pemilik data).
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $akunId
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $akunId)
    {
        // Pastikan akunId adalah ID dari tabel akuns (users)
        $akun = Akun::with('pemilik')->find($akunId);

        if (!$akun) {
            return response()->json(['message' => 'Akun tidak ditemukan'], 404);
        }

        DB::beginTransaction();
        try {
            // Validate Akun (email)
            $akunValidated = $request->validate([
                'email' => [
                    'required',
                    'string',
                    'email',
                    'max:255',
                    // Cek email unik kecuali untuk akun yang sedang diedit
                    ValidationException::withMessages([
                        'email' => ['Email ini sudah digunakan.'],
                    ])->when(
                        Akun::where('email', $request->email)->where('id', '!=', $akunId)->exists(),
                        fn ($value) => true
                    ),
                ],
            ]);

            // Update Akun data
            $akun->email = $akunValidated['email'];
            $akun->save();

            // Validate Pemilik data
            $pemilikValidated = $request->validate([
                'nama' => 'required|string|max:255',
                'telepon' => 'required|string|max:20',
                'alamat' => 'required|string|max:255',
            ]);

            // Update or create Pemilik data
            // Asumsi setiap akun_id pemilik akan memiliki satu entri di tabel pemilik
            $pemilik = $akun->pemilik;
            if (!$pemilik) {
                // Jika relasi pemilik belum ada, buat yang baru
                $pemilik = new Pemilik();
                $pemilik->akun_id = $akun->id;
            }

            $pemilik->nama = $pemilikValidated['nama'];
            $pemilik->telepon = $pemilikValidated['telepon'];
            $pemilik->alamat = $pemilikValidated['alamat'];
            $pemilik->save();

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Profil berhasil diperbarui.',
                'data' => [
                    'id' => $akun->id,
                    'email' => $akun->email,
                    'role' => $akun->role,
                    'pemilik' => $pemilik->toArray(),
                ]
            ]);

        } catch (ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Gagal memperbarui profil: ' . $e->getMessage() . ' di ' . $e->getFile() . ':' . $e->getLine());
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan server.',
                'error_detail' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Change user's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $akunId
     * @return \Illuminate\Http\JsonResponse
     */
    public function changePassword(Request $request, $akunId)
    {
        $akun = Akun::find($akunId);

        if (!$akun) {
            return response()->json(['message' => 'Akun tidak ditemukan'], 404);
        }

        $request->validate([
            'current_password' => ['required', 'string'],
            'new_password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if (!Hash::check($request->current_password, $akun->password)) {
            throw ValidationException::withMessages([
                'current_password' => ['Kata sandi saat ini salah.'],
            ]);
        }

        $akun->password = Hash::make($request->new_password);
        $akun->save();

        return response()->json(['status' => 'success', 'message' => 'Kata sandi berhasil diperbarui.']);
    }
}