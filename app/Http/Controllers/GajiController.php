<?php

namespace App\Http\Controllers;
use App\Models\Absensi;
use App\Models\Gaji;
use Illuminate\Support\Carbon;

class GajiController extends Controller
{
    public function hitungGaji($pegawai_id){
        $absensiHariIni = Absensi::where('id_pengguna', $pegawai_id)
            ->whereNotNull('jam_keluar')
            ->orderBy('created_at', 'desc')
            ->first();

        if (!$absensiHariIni) {
            return response()->json(['message' => 'Pegawai belum absen hari ini'], 400);
        }

        $jamMasuk = Carbon::parse($absensiHariIni->jam_masuk);
        $jamKeluar = Carbon::parse($absensiHariIni->jam_keluar);
        $jamKerja = $jamKeluar->diffInMinutes($jamMasuk)/60;
        $gaji = 60000;

        $id_absensi = $absensiHariIni->id;
        
        // Cek apakah pengguna ada
        if (!$absensiHariIni->pengguna) {
            return response()->json(['message' => 'Data pengguna tidak ditemukan'], 404);
        }

        // **Simpan gaji ke database**
        Gaji::create([
            'id_pengguna' => $pegawai_id,
            'id_absensi' => $id_absensi,
            'tanggal' => now(),
            'jam_kerja' => round($jamKerja, 4),
            'gaji' => $gaji
        ]);

        return response()->json([
            'message' => 'Perhitungan gaji berhasil',
            'pengguna' => $absensiHariIni->pengguna->nama,
            'jam_kerja' => round($jamKerja, 4) . ' jam',
            'gaji' => 'Rp ' . number_format($gaji, 0, ',', '.')
        ]);
    }
}
