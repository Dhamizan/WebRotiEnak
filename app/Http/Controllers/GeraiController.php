<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\Gerai;
use Illuminate\Http\Request;

class GeraiController extends Controller
{
    public function index()
    {
        $gerai = Gerai::with('kepalaToko')->get();
        return response()->json($gerai);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_gerai' => 'required|string|max:255',
            'alamat' => 'required|string|max:255',
        ]);

        $gerai = Gerai::create($request->only(['nama_gerai', 'alamat']));

        return response()->json(['message' => 'Gerai berhasil ditambahkan', 'data' => $gerai]);
    }

    public function show($id)
    {
        $gerai = Gerai::with('kepalaToko')->findOrFail($id);
        return response()->json($gerai);
    }

    public function update(Request $request, $id)
    {
        $gerai = Gerai::findOrFail($id);

        $request->validate([
            'nama_gerai' => 'sometimes|required|string|max:255',
            'alamat' => 'sometimes|required|string|max:255',
        ]);

        $gerai->update($request->only(['nama_gerai', 'alamat']));

        return response()->json(['message' => 'Gerai berhasil diperbarui', 'data' => $gerai]);
    }

    public function destroy($id)
    {
        $gerai = Gerai::findOrFail($id);
        $gerai->delete();

        return response()->json(['message' => 'Gerai berhasil dihapus']);
    }
}
