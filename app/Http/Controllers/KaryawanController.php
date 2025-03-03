<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use App\Models\Karyawan;
use App\Models\Absensi;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class KaryawanController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $karyawan = Karyawan::where('email', $request->email)->first();

        if (!$karyawan || !Hash::check($request->password, $karyawan->password)) {
            return response()->json(['message' => 'Email atau password salah'], 401);
        }

        if ($karyawan->role !== 1) {
            return response()->json(['message' => 'Anda bukan admin'], 403);
        }

        $token = $karyawan->createToken('admin-token')->plainTextToken;

        return response()->json([
            'message' => 'Login berhasil',
            'token' => $token
        ]);
    }

    public function logout (Request $request){
        $request->user()->tokens()->delete();
        return response()->json(['message' => 'Logout berhasil']);
    }

    public function __construct(){
        $this->middleware('auth:sanctum')->except(['login']);
    }

    public function getPendingkaryawan() {
        $karyawan = Karyawan::whereNull('fingerprint_id')->first();
        if (!$karyawan) {
            return response()->json(['message' => 'No pending karyawans'], 404);
        }
        return response()->json(['karyawan_id' => $karyawan->id]);
    }

    public function store(Request $request) {
        if (Auth::user()->role != 1){
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:karyawans',
            'password' => 'required',
            'role' => 'required',
        ]);

        $karyawan = Karyawan::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'role' => $request->role
        ]);
        
        return response()->json([
            'message' => 'Karyawan added successfully',
            'karyawan' => [
                'id' => $karyawan->id,
                'name' => $karyawan->name,
                'email' => $karyawan->email,
                'password' => $karyawan->password,
                'role' => $karyawan->role,
            ]
        ], 201);
    }

    public function updateFingerprint(Request $request, $id) {
        $karyawan = Karyawan::find($id);

        if (!$karyawan) {
            return response()->json(['message' => 'Karyawan not found'], 404);
        }

        $request->validate([
            'fingerprint_id' => 'required|numeric'
        ]);

        // Update fingerprint ID ke database
        $karyawan->update(['fingerprint_id' => $request->fingerprint_id]);

        return response()->json([
            'message' => 'Fingerprint registered successfully',
            'karyawan' => $karyawan
        ]);
    }

    public function data_karyawan(){
        return response()->json(Karyawan::all());
    }
    
    public function absensi(Request $request){
        $request->validate([
            'fingerprint_id' => 'required|numeric'
        ]);
    
        $karyawan = Karyawan::where('fingerprint_id', $request->fingerprint_id)->first();
    
        if (!$karyawan) {
            return response()->json(['message' => 'Fingerprint tidak ditemukan'], 404);
        }
    
        // Cek absensi hari ini
        $todayAbsensi = Absensi::where('karyawan_id', $karyawan->id)
            ->whereDate('created_at', today())
            ->first();
    
        if (!$todayAbsensi) {
            // Jika belum absen, ini dianggap sebagai jam masuk
            Absensi::create([
                'karyawan_id' => $karyawan->id,
                'jam_masuk' => now()->format('H:i:s')
            ]);
    
            return response()->json([
                'message' => 'Absensi masuk berhasil',
                'karyawan' => $karyawan->name,
                'waktu_absen' => now(),
                'status' => 'masuk'
            ]);
        }
    
        if (!$todayAbsensi->jam_keluar) {
            // Jika sudah ada jam masuk tapi belum ada jam keluar, maka ini dianggap jam keluar
            $jamMasuk = Carbon::parse($todayAbsensi->jam_masuk);
            $jamKeluar = now();
    
            // Hitung selisih waktu kerja
            $jamKerja = $jamKeluar->diff($jamMasuk)->format('%H:%I:%S');
    
            $todayAbsensi->update([
                'jam_keluar' => $jamKeluar->format('H:i:s'),
                'jam_kerja' => $jamKerja
            ]);
    
            return response()->json([
                'message' => 'Absensi keluar berhasil',
                'karyawan' => $karyawan->name,
                'waktu_absen' => now(),
                'status' => 'keluar',
                'jam_kerja' => $jamKerja
            ]);
        }
    
        // Jika sudah ada jam masuk dan keluar, tolak absensi ketiga
        return response()->json(['message' => 'Anda sudah absen 2 kali hari ini'], 409);
    }    
}
