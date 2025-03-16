<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CutiController extends Controller
{
    public function index()
    {
        $cuti = Cuti::with('karyawan_id')->get();
        return response()->json($cuti);
    }

    // 2️⃣ Tambah Data Cuti
    public function store(Request $request)
    {
        $request->validate([
            'karyawan_id' => 'required|exists:karyawan_id,id',
            'tgl_cuti' => 'required|date',
            'tgl_selesai' => 'required|date|after_or_equal:tgl_cuti',
        ]);

        $cuti = Cuti::create($request->all());

        return response()->json([
            'message' => 'Cuti berhasil ditambahkan!',
            'data' => $cuti
        ], 201);
    }

    // 3️⃣ Ambil Data Cuti Berdasarkan ID
    public function show($id)
    {
        $cuti = Cuti::find($id);
        if (!$cuti) {
            return response()->json(['message' => 'Data cuti tidak ditemukan'], 404);
        }

        return response()->json($cuti);
    }

    // 4️⃣ Update Data Cuti
    public function update(Request $request, $id)
    {
        $cuti = Cuti::find($id);
        if (!$cuti) {
            return response()->json(['message' => 'Data cuti tidak ditemukan'], 404);
        }

        $request->validate([
            'tgl_cuti' => 'date',
            'tgl_selesai' => 'date|after_or_equal:tgl_cuti',
        ]);

        $cuti->update($request->all());

        return response()->json([
            'message' => 'Cuti berhasil diperbarui!',
            'data' => $cuti
        ]);
    }

    // 5️⃣ Hapus Data Cuti
    public function destroy($id)
    {
        $cuti = Cuti::find($id);
        if (!$cuti) {
            return response()->json(['message' => 'Data cuti tidak ditemukan'], 404);
        }

        $cuti->delete();

        return response()->json(['message' => 'Cuti berhasil dihapus!']);
    }
}
