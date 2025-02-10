<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Karyawan;
use App\Models\Absensi;

class KaryawanController extends Controller
{
    public function getPendingkaryawan() {
        $karyawan = Karyawan::whereNull('fingerprint_id')->first();
        if (!$karyawan) {
            return response()->json(['message' => 'No pending karyawans'], 404);
        }
        return response()->json(['karyawan_id' => $karyawan->id]);
    }

    public function store(Request $request) {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:karyawans',
            'position' => 'required'
        ]);

        $karyawan = Karyawan::create($request->all());

        return response()->json([
            'message' => 'Karyawan added successfully',
            'karyawan' => [
                'id' => $karyawan->id, // Tambahkan ID dalam response
                'name' => $karyawan->name,
                'email' => $karyawan->email,
                'position' => $karyawan->position
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

        // Catat kehadiran
        Absensi::create([
            'karyawan_id' => $karyawan->id
        ]);

        return response()->json([
            'message' => 'Absensi berhasil',
            'karyawan' => $karyawan->name,
            'waktu_absen' => now()
        ]);
    }
    #Test
}
