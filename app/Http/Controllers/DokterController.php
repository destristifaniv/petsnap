<?php

namespace App\Http\Controllers;

use App\Models\Dokter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DokterController extends Controller
{
    public function getByAkunId($akunId)
    {
        $dokter = Dokter::where('akun_id', $akunId)->first();

        if (!$dokter) {
            return response()->json(['message' => 'Dokter tidak ditemukan'], 404);
        }

        // Tambahkan relasi ke akun jika perlu
        $akun = $dokter->akun;

        return response()->json([
            'nama' => $dokter->nama,
            'email' => $akun->email, // dari relasi akun
            'alamat' => $dokter->alamat,
            'no_hp' => $dokter->no_hp,
            'foto' => $dokter->foto,
        ]);
    }

    public function update(Request $request, $akunId)
    {
        $dokter = Dokter::where('akun_id', $akunId)->first();

        if (!$dokter) {
            return response()->json(['message' => 'Dokter tidak ditemukan'], 404);
        }

        $dokter->nama = $request->input('nama', $dokter->nama);
        $dokter->alamat = $request->input('alamat', $dokter->alamat);
        $dokter->no_hp = $request->input('no_hp', $dokter->no_hp);

        if ($request->hasFile('foto')) {
            if ($dokter->foto && Storage::disk('public')->exists($dokter->foto)) {
                Storage::disk('public')->delete($dokter->foto);
            }

            $fotoPath = $request->file('foto')->store('dokters', 'public');
            $dokter->foto = $fotoPath;
        }

        $dokter->save();

        return response()->json(['message' => 'Profil dokter berhasil diperbarui']);
    }

    public function updateByAkun(Request $request, $akunId)
    {
        return $this->update($request, $akunId);
    }
}
