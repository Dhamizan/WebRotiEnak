<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Karyawan;
use App\Models\Absensi;
use App\Models\Gaji;

class GajiController extends Controller
{
    public function hitungGaji($karyawan_id)
    {
        // Ambil data absensi karyawan untuk hari ini
        $absensiHariIni = Absensi::where('karyawan_id', $karyawan_id)
            ->whereDate('tanggal', now()->toDateString())
            ->first();

        if (!$absensiHariIni) {
            return response()->json(['message' => 'Karyawan belum absen hari ini'], 400);
        }

        // Hitung gaji harian
        $gaji_per_hari = 100000;
        $tunjangan_per_bulan = 500000;
        $potongan = ($absensiHariIni->status === 'Terlambat') ? 20000 : 0;

        // Cek gaji udah dihitung belum hari ini
        $gajiHariIni = Gaji::where('karyawan_id', $karyawan_id)
            ->whereDate('created_at', now()->toDateString())
            ->first();

        if (!$gajiHariIni) {
            // Simpan gaji baru
            $gaji = Gaji::create([
                'karyawan_id' => $karyawan_id,
                'gaji' => $gaji_per_hari,
                'tunjangan' => $tunjangan_per_bulan,
                'potongan' => $potongan,
            ]);
        } else {
            // Update kalo sudah ada
            $gajiHariIni->update([
                'gaji' => $gaji_per_hari,
                'potongan' => $potongan,
            ]);
            $gaji = $gajiHariIni;
        }

        return response()->json([
            'message' => 'Gaji berhasil dihitung',
            'gaji' => $gaji
        ]);
    }
}
