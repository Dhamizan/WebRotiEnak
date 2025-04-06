<?php

namespace App\Http\Controllers;
use App\Models\Cuti;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class CutiController extends Controller
{
    public function pengajuanCuti(Request $request){
        $request->validate([
            'waktu_mulai' => 'required|date',
            'waktu_selesai' => 'required|date',
            'alasan' => 'required|string',
            'dokumen_pendukung' => 'nullable|string'
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

    public function kelolaCuti()
    {
        $cutis = Cuti::with('pengguna')->get();
        return view('admin.kelola_cuti', compact('cutis'));
    }

    public function penerimaanCuti($id, Request $request)
    {
        \Log::info('Data yang diterima:', $request->all());

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
}