<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\Gerai;
use App\Models\Pengguna;
use Illuminate\Http\Request;

class GeraiController extends Controller
{
    public function lihatGerai()
    {
        $gerai = Gerai::all()->map(function ($item) {
            $jumlahPegawai = Pengguna::where('gerai_id', $item->id)
                                ->where('peran', 2)
                                ->count();
            $item->jumlah_pegawai = $jumlahPegawai;
            return $item;
        });
    
        return response()->json([
            'data' => $gerai,
            'success' => true
        ]);
    }

    public function simpanGerai(Request $request)
    {
        $request->validate([
            'gerai' => 'required|string|max:255',
            'alamat' => 'required|string|max:255',
            'lat' => 'required|numeric',
            'long' => 'required|numeric',
            'jenis_gerai' => 'required|in:gerai,stan',
        ]);

        $gerai = Gerai::create(([
            'gerai' => $request->gerai,
            'alamat' => $request->alamat,
            'lat' => $request->lat,
            'long' => $request->long,
            'jenis_gerai' => $request->jenis_gerai,
            'jumlah_pegawai' => 0
        ]));

        return response()->json(['message' => 'Gerai berhasil ditambahkan', 'data' => $gerai]);
    }

    public function suntingGerai(Request $request, $id)
{
    \Log::info('Data yang diterima:', $request->all());  // Log data yang diterima

    $gerai = Gerai::findOrFail($id);

    $request->validate([
        'gerai' => 'required|string|max:255',
        'alamat' => 'required|string|max:255',
        'lat' => 'required|numeric',
        'long' => 'required|numeric',
        'jenis_gerai' => 'required|in:gerai,stan',
    ]);

    $gerai->update([
        'gerai' => $request->gerai,
        'alamat' => $request->alamat,
        'lat' => $request->lat,
        'long' => $request->long,
        'jenis_gerai' => $request->jenis_gerai,
    ]);

    return response()->json([
        'message' => 'Gerai berhasil diperbarui',
        'data' => $gerai
    ]);
}

    public function hapusGerai($id)
    {
        $gerai = Gerai::findOrFail($id);
        $gerai->delete();

        return response()->json(['message' => 'Gerai berhasil dihapus']);
    }
}
