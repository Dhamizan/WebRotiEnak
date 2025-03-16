<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use App\Models\Pengguna;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use App\Mail\ResetPasswordLink;

class PenggunaController extends Controller
{
    public function masuk(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'kata_sandi' => 'required'
        ]);

        $pengguna = Pengguna::where('email', $request->email)->first();

        // Cek apakah user ditemukan dan kata sandi cocok
        if (!$pengguna || !Hash::check($request->kata_sandi, $pengguna->kata_sandi)) {
            return response()->json(['message' => 'Email atau kata sandi salah'], 401);
        }

        // Cek role dan generate token
        if ($pengguna->peran === 1) {
            $token = $pengguna->createToken('admin-token')->plainTextToken;
            return response()->json([
                'message' => 'Login admin berhasil',
                'token' => $token
            ]);
        }

        if ($pengguna->peran === 2) {
            $token = $pengguna->createToken('user-token')->plainTextToken;
            return response()->json([
                'message' => 'Login user berhasil',
                'token' => $token
            ]);
        }

        return response()->json(['message' => 'Role tidak valid'], 403);
    }

    public function keluar (Request $request){
        auth()->pengguna()->tokens()->delete();
        return response()->json(['message' => 'Logout berhasil']);
    }
    
    public function __construct(){
         $this->middleware('auth:sanctum')->except(['masuk', 'kirimTautanPengaturanUlang', 'verifikasiEmail', 'kirimTautanVerifikasiEmail', 'membuatSidikJari']);
    }

    public function antreanPengguna() {
        $pengguna = Pengguna::whereNull('id_sidik_jari')->first();
        if (!$pengguna) {
            return response()->json(['message' => 'No pending penggunas'], 404);
        }
        return response()->json(['id_pengguna' => $pengguna->id]);
    }

    public function tambahKaryawan(Request $request) {

        if (Auth::user()->peran != 1){
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'nama' => 'required',
            'email' => 'required|email|unique:penggunas',
            'notelp' => 'required',
            'alamat' => 'required',
            'kata_sandi' => 'required',
            'peran' => 'required',
            'jenis_kelamin' => 'required'
        ]);

        $pengguna = Pengguna::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'notelp' => $request->notelp,
            'alamat' => $request->alamat,
            'kata_sandi' => $request->kata_sandi,
            'peran' => $request->peran,
            'jenis_kelamin' => $request->jenis_kelamin
        ]);
        
        return response()->json([
            'message' => 'pengguna added successfully',
            'pengguna' => [
                'id' => $pengguna->id,
                'nama' => $pengguna->nama,
                'email' => $pengguna->email,
                'notelp' => $request->notelp,
                'alamat' => $request->alamat,
                'kata_sandi' => $pengguna->kata_sandi,
                'peran' => $pengguna->peran,
                'jenis_kelamin' => $request->jenis_kelamin
            ]
        ], 201);
    }

    public function membuatSidikJari(Request $request, $id) {

        $pengguna = Pengguna::find($id);

        if (!$pengguna) {
            return response()->json(['message' => 'pengguna not found'], 404);
        }

        $request->validate([
            'id_sidik_jari' => 'required|numeric'
        ]);

        // Update fingerprint ID ke database
        $pengguna->update(['id_sidik_jari' => $request->id_sidik_jari]);

        Log::info('Request masuk', ['id' => $id, 'data' => $request->all()]);

        return response()->json([
            'message' => 'Fingerprint registered successfully',
            'pengguna' => $pengguna
        ], 200);
    }

    public function lihatKaryawan(){
        return response()->json(Pengguna::all());
    }
    
    

    public function kirimTautanPengaturanUlang(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email']
        ]);
        $pengguna = Pengguna::where('email', $request->email)->first();
        if (!$pengguna) {
            return response()->json(['message' => 'Akunmu tidak ditemukan'], 404);
        }
        if (!$pengguna->verifikasi_email) {
            return response()->json(['message' => 'Email belum terverifikasi'], 405);
        }

        // Generate backend signed URL
        $signedUrl = URL::temporarySignedRoute('reset.password', now()->addMinutes(5), ['email' => $request->email]);

        // Buat URL untuk dikirim ke frontend
        $url_frontend = env('FRONTEND_URL') . '/reset-password?backend_url=' . urlencode($signedUrl);

        // Kirimkan email
        Mail::to($request->email)->send(new ResetPasswordLink($url_frontend, $pengguna->name));

        return response()->json(['message' => 'Lihat email-mu untuk reset password']);
    }

    public function halamanResetPassword(Request $request)
    {
        // Ini hanya validasi URL
        return response()->json(['message' => 'Link valid, silakan reset password']);
    }

    public function aturUlangKataSandi(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'kata_sandi' => 'required'
        ]);

        $pengguna = Pengguna::where('email', $request->email)->firstOrFail();

        $pengguna->update([
            'kata_sandi' => Hash::make($request->kata_sandi)
        ]);

        return response()->json(['message' => 'Kata sandi telah diubah']);
    }

    public function kirimTautanVerifikasiEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:penggunas,email'
        ]);

        $pengguna = Pengguna::where('email', $request->email)->first();

        if ($pengguna->verifikasi_email) {
            return response()->json(['message' => 'Email sudah diverifikasi'], 400);
        }

        // Membuat URL verifikasi yang ditandatangani
        $url = URL::temporarySignedRoute(
            'verify.email',
            now()->addMinutes(60), // Berlaku 1 jam
            ['email' => $pengguna->email]
        );

        // Kirim email dengan tautan verifikasi
        Mail::to($pengguna->email)->send(new \App\Mail\VerificationEmail($url));

        return response()->json([
            'message' => 'Silakan cek email untuk verifikasi akun'
        ]);
    }

    public function verifikasiEmail(Request $request)
    {
        if (!$request->hasValidSignature()) {
            return response()->json(['message' => 'Tautan tidak valid atau telah kadaluarsa'], 403);
        }

        $pengguna = Pengguna::where('email', $request->email)->first();

        if (!$pengguna) {
            return response()->json(['message' => 'Akun tidak ditemukan'], 404);
        }

        if ($pengguna->verifikasi_email) {
            return response()->json(['message' => 'Email sudah diverifikasi sebelumnya'], 400);
        }

        // Tandai email sebagai terverifikasi
        $pengguna->verifikasi_email = now();
        $pengguna->save();

        // Cek apakah perubahan tersimpan
        $check = Pengguna::where('email', $request->email)->first();
        if ($check->verifikasi_email) {
            return response()->json(['message' => 'Email berhasil diverifikasi']);
        } else {
            return response()->json(['message' => 'Gagal memperbarui status verifikasi'], 500);
        }
    }
}