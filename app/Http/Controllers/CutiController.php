<?php

namespace App\Http\Controllers;
use App\Models\Cuti;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CutiController extends Controller
{
    public function pengajuanCuti(Request $request){
        $request->validate([
            'waktu_mulai' => 'required|date',
            'waktu_selesai' => 'required|date',
            'alasan' => 'required|string',
            'dokumen_pendukung' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048'
        ]);

        $dokumenPath = null;
        if ($request->hasFile('dokumen_pendukung')) {
            $dokumenPath = $request->file('dokumen_pendukung')->store('dokumen_cuti', 'public');
        }

        $cuti = Cuti::create([
            'id_pengguna' => Auth::id(),
            'waktu_mulai' => $request->waktu_mulai,
            'waktu_selesai' => $request->waktu_selesai,
            'status' => 0,
            'alasan' => $request->alasan,
            'dokumen_pendukung' => $dokumenPath,
        ]);

        return response()->json([
            'message' => 'Pengajuan cuti berhasil',
            'data'=> $cuti
        ], 200);
    }

    public function lihatCuti($id)
    {
        $cuti = Cuti::where('id_pengguna', $id)->get();
        return response()->json([
            'success' => true,
            'data' => $cuti
        ]);
    }
     public function lihatCutiPegawai()
    {
        $user = Auth::user();

        $cuti = Cuti::with('pengguna') // ambil relasi pengguna
            ->where('id_pengguna',$user->id)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $cuti
        ]);
    }

    public function lihatSemuaCuti()
    {
        $pegawai =Cuti::where('peran', 2)->get();

        return response()->json([
            'success' => true,
            'data' => $pegawai
        ]);
    }

    public function penerimaanCuti($id, Request $request)
    {
        $request->validate([
            'status' => 'required|in:1,2',
        ]);

        $cuti = Cuti::findOrFail($id);

        if ($cuti->status != 0) {
            return response()->json([
                'pesan' => 'Status cuti sudah diproses dan tidak dapat diubah lagi.',
                'data' => $cuti
            ], 400);
        }

        $cuti->update(['status' => $request->status]);

        \Log::info("Status cuti ID $id diubah menjadi: " . $request->status);

        return response()->json([
            'pesan' => 'Status cuti berhasil diperbarui',
            'data' => $cuti
        ], 200);
    }

    public function cutiBelumDiproses() {
        $cuti = Cuti::with('pengguna') // ambil relasi pengguna
            ->where('status', 0)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $cuti
        ]);
    }
    
}