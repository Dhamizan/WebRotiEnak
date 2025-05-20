<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Absensi;
use App\Models\Pengguna;
use Carbon\Carbon;
use App\Http\Controllers\GajiController;
use Illuminate\Support\Facades\Auth;

class AbsensiController extends Controller{
    protected $gajiController;

    public function __construct(GajiController $gajiController)
    {
        $this->gajiController = $gajiController; // Inject GajiController
    }

    public function rekapAbsensi(Request $request)
    {
        $request->validate([
            'id_sidik_jari' => 'required|numeric'
        ]);

        $pengguna = Pengguna::where('id_sidik_jari', $request->id_sidik_jari)->first();

        if (!$pengguna) {
            return response()->json(['message' => 'Fingerprint tidak ditemukan'], 404);
        }

        // Cek absensi hari ini
        $todayAbsensi = Absensi::where('id_pengguna', $pengguna->id)
            ->whereDate('created_at', today())
            ->first();

        if (!$todayAbsensi) {
            // Jika belum absen, ini dianggap sebagai jam masuk
            Absensi::create([
                'id_pengguna' => $pengguna->id,
                'jam_masuk' => now()->format('H:i:s')
            ]);

            return response()->json([
                'message' => 'Absensi masuk berhasil',
                'pengguna' => $pengguna->name,
                'waktu_absen' => now(),
                'status' => 'masuk'
            ]);
        }

        if (!$todayAbsensi->jam_keluar) {
            $jamMasuk = Carbon::parse($todayAbsensi->jam_masuk);
            $jamKeluar = now();

            $jamKerja = $jamKeluar->diff($jamMasuk)->format('%H:%I:%S');

            $todayAbsensi->update([
                'jam_keluar' => $jamKeluar->format('H:i:s'),
                'jam_kerja' => $jamKerja
            ]);

            $gajiResponse = $this->gajiController->hitungGaji($pengguna->id);

            return response()->json([
                'message' => 'Absensi keluar berhasil',
                'pengguna' => $pengguna->nama,
                'waktu_absen' => now(),
                'status' => 'keluar',
                'jam_kerja' => $jamKerja,
                'gaji' => $gajiResponse
            ]);
        }

        return response()->json(['message' => 'Anda sudah absen 2 kali hari ini'], 409);
    }

    public function lihatAbsensi($id) {
        $absensi = Absensi::where('id_pengguna', $id)->get();
    
        return response()->json([
            'success' => true,
            'data' => $absensi
        ]);
    }

    public function lihatAbsensiPegawai()
    {
        $user = Auth::user();

        $absensi = Absensi::where('id_pengguna', $user->id)
                    ->whereNotNull('jam_masuk')
                    ->get();

        $data = $absensi->map(function ($item) {
            $status = null;

            if ($item->jam_masuk && !$item->jam_keluar) {
                $status = 'Kerja';
            } elseif ($item->jam_masuk && $item->jam_keluar) {
                $status = 'Hadir';
            }

            return [
                'id' => $item->id,
                'jam_masuk' => $item->jam_masuk,
                'jam_keluar' => $item->jam_keluar,
                'created_at' => $item->created_at->format('Y-m-d'),
                'jam_kerja' => $item->jam_kerja,
                'status' => $status,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    public function absensiHariIni() {
        $absensi = Absensi::whereDate('created_at', now())
                    ->whereNotNull('jam_masuk')
                    ->get();
    
        return response()->json([
            'success' => true,
            'data' => $absensi
        ]);
    }    
}
