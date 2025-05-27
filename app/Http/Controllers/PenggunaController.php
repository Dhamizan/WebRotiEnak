<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use App\Models\Pengguna;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use App\Mail\ResetPasswordLink;
use Illuminate\Support\Facades\Storage;

class PenggunaController extends Controller
{
    public function pegawai()
    {
        $pegawai = Pengguna::all(); // ambil semua data pegawai
        return view('admin.pegawai', compact('pegawai'));
    }

        public function masuk(Request $request)
        {
            \Log::info('Login request:', $request->all());

            $request->validate([
                'nama' => 'required',
                'kata_sandi' => 'required'
            ]);

            $pengguna = Pengguna::where('nama', $request->nama)->first();

            if (!$pengguna || !Hash::check($request->kata_sandi, $pengguna->kata_sandi)) {
                return response()->json(['message' => 'nama atau Kata Sandi Salah'], 401);
            }

            if (in_array($pengguna->peran, [1, 2, 3])) {
                $token = $pengguna->createToken('login-token')->plainTextToken;

                $roleNames = [
                    1 => 'admin',
                    2 => 'pegawai',
                    3 => 'fingerprint'
                ];

                return response()->json([
                    'message' => 'Masuk Kedalam Akun Berhasil',
                    'token' => $token,
                    'peran' => $roleNames[$pengguna->peran] ?? 'tidak diketahui'
                ]);  
            }

            return response()->json(['message' => 'Role tidak valid'], 403);
        }

    public function keluar (Request $request){
        $user = Auth::user();

        if ($user) {
            $user->tokens()->delete();
            return response()->json(['message' => 'Keluar Akun Berhasil']);
        }

        return response()->json(['message' => 'Unauthorized'], 401);
    }
    
    public function __construct(){
         $this->middleware('auth:sanctum')->except(['masuk', 'kirimTautanPengaturanUlang', 'verifikasiEmail', 'kirimTautanVerifikasiEmail', 'membuatSidikJari']);
    }

    public function antreanPengguna() {
        $pengguna = Pengguna::whereNull('id_sidik_jari')->first();
        if (!$pengguna) {
            return response()->json(['message' => 'Tidak Ada Pending Pegawai'], 404);
        }
        return response()->json(['id_pengguna' => $pengguna->id]);
    }
    
    public function tambahPegawai(Request $request) {
        if (Auth::user()->peran != 1){
            return response()->json(['message' => 'Unauthorized'], 403);
        }
    
        // Cek apakah masih ada pegawai tanpa id_sidik_jari
        $belumLengkap = Pengguna::whereNull('id_sidik_jari')->latest()->first();
    
        if ($belumLengkap) {
            return response()->json([
                'message' => 'Harap Lengkapi ID Sidik Jari Pegawai Terlebih Dahulu Sebelum Menambahkan Pegawai Baru'
            ], 400);
        }
    
        $request->validate([
            'nama' => 'required',
            'email' => 'required|email|unique:penggunas',
            'notelp' => 'required',
            'alamat' => 'required',
            'gerai_id' => 'required|exists:gerais,id',
            'kata_sandi' => 'required',
            'peran' => 'required',
            'jenis_kelamin' => 'required|in:Pria,Wanita',
            'gambar_profil' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);
    
        $gambarPath = null;

        if ($request->hasFile('gambar_profil')) {
            $gambarPath = $request->file('gambar_profil')->store('gambar_profil', 'public');
}

        $pengguna = Pengguna::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'notelp' => $request->notelp,
            'alamat' => $request->alamat,
            'gerai_id' => $request->gerai_id,
            'kata_sandi' => $request->kata_sandi,
            'peran' => $request->peran,
            'jenis_kelamin' => $request->jenis_kelamin,
            'gambar_profil' => $gambarPath,
        ]);
        
        return response()->json([
            'message' => 'Pengguna berhasil ditambahkan. Silakan input ID sidik jari.',
            'pengguna' => [
                'id' => $pengguna->id,
                'nama' => $pengguna->nama,
                'email' => $pengguna->email,
                'notelp' => $request->notelp,
                'alamat' => $request->alamat,
                'gerai_id' => $request->gerai_id,
                'kata_sandi' => $pengguna->kata_sandi,
                'peran' => $pengguna->peran,
                'jenis_kelamin' => $request->jenis_kelamin,
                'gambar_profil' => $gambarPath
            ]
        ], 201);
    }    

    public function hapusPegawai($id)
    {
        $gerai_id = Pengguna::findOrFail($id);
        $gerai_id->delete();

        return response()->json(['message' => 'Pegawai berhasil dihapus']);
    }

    public function membuatSidikJari(Request $request, $id) {

        $pengguna = Pengguna::find($id);

        if (!$pengguna) {
            return response()->json(['message' => 'pengguna not found'], 404);
        }

        $request->validate([
            'id_sidik_jari' => 'required|numeric'
        ]);

        $pengguna->update(['id_sidik_jari' => $request->id_sidik_jari]);

        Log::info('Request masuk', ['id' => $id, 'data' => $request->all()]);

        return response()->json([
            'message' => 'Fingerprint Berhasil Didaftarkan',
            'pengguna' => $pengguna
        ], 200);
    }

    public function lihatAdmin(){
        $admin = Pengguna::with('gerai')->where('peran', 1)->get();

        return response()->json([
            'success' => true,
            'data' => $admin
        ]);
    }


    public function lihatPegawai(){
        $pegawai = Pengguna::with('gerai')->where('peran', 2)->get();

        return response()->json([
            'success' => true,
            'data' => $pegawai
        ]);
    }
    public function lihatPegawaiProfil(){

        $user = Auth::user();

        $pegawai = Pengguna::with('gerai')
            ->where('id',$user->id)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $pegawai
        ]);
    }
    public function rincianPegawai($id)
    {
        $pegawai = Pengguna::with('gerai')->find($id);

        if (!$pegawai) {
            return response()->json([
                'success' => false,
                'message' => 'Pegawai tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $pegawai
        ]);
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
        $signedUrl = URL::temporarySignedRoute('reset.password.form', now()->addMinutes(60), ['email' => $request->email]);

        // Ambil token dari signed URL
        $parsed = parse_url($signedUrl);
        parse_str($parsed['query'], $queryParams);
        $token = $queryParams['signature'];

        // Buat URL frontend sesuai route
        $url_frontend = url('/ubah-kata-sandi') . '?' . http_build_query($queryParams);


        // Kirimkan email
        Mail::to($request->email)->send(new ResetPasswordLink($url_frontend, $pengguna->name));

        return response()->json(['message' => 'Lihat email-mu untuk reset password']);
    }

    public function aturUlangKataSandi(Request $request)
    {
        \Log::info('Reset Password Request:', $request->all());

        $request->validate([
            'email' => 'required|email',
            'kata_sandi' => 'required'
        ]);

        $pengguna = Pengguna::where('email', $request->email)->firstOrFail();

        $pengguna->kata_sandi = $request->kata_sandi;
        $pengguna->save();

        // $pengguna->update([
        //     'kata_sandi' =>Hash::make($request->kata_sandi)
        // ]);

        return response()->json(['message' => 'Kata sandi telah diubah'], 200);
    }

    public function kirimTautanVerifikasiEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:penggunas,email'
        ]);

        $pengguna = Pengguna::where('email', $request->email)->first();

        if ($pengguna->verifikasi_email) {
            return response()->json([
                'status' => 'Sudah_Verifikasi',
                'message' => 'Email sudah diverifikasi'
            ]);
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
            'status' => 'Link_Terkirim',
            'message' => 'Silakan cek email untuk verifikasi akun'
        ]);
    }

    public function verifikasiEmail(Request $request)
    {
        if (!$request->hasValidSignature()) {
            return redirect('/')->with('error', 'Tautan tidak valid atau telah kadaluarsa.');
        }
    
        $pengguna = Pengguna::where('email', $request->email)->first();
    
        if (!$pengguna) {
            return redirect('/')->with('error', 'Akun tidak ditemukan.');
        }
    
        if (!$pengguna->verifikasi_email) {
            $pengguna->verifikasi_email = now();
            $pengguna->save();
        }
    
        // Redirect ke halaman sukses
        return redirect('/berhasil-verifikasi-email');
    }

        public function suntingProfil(Request $request)
        {
            $user = Auth::user();   

            if (!$user) {
                return response()->json(['message' => 'Tidak dapat mengakses pengguna.'], 401);
            }

            // if ($user->peran != 1) {
            //     return response()->json(['message' => 'Akses ditolak. Hanya admin yang bisa menyunting profil ini.'], 403);
            // }

            // Karena di Postman kamu gak kirim 'nama', kita hapus dari validasi
            $validated = $request->validate([
                'nama' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'notelp' => 'required|string|max:20',
                'alamat' => 'required|string|max:255',
                'jenis_kelamin' => 'required|in:Pria,Wanita',
                'gerai_id' => 'required|exists:gerais,id',
                'gambar_profil' => 'nullable|file|image|mimes:jpg,jpeg,png|max:2048',
            ]);

            // Isi data ke model
            $user->nama = $validated['nama'];
            $user->email = $validated['email'];
            $user->notelp = $validated['notelp'];
            $user->alamat = $validated['alamat'];
            $user->jenis_kelamin = $validated['jenis_kelamin'];
            $user->gerai_id = $validated['gerai_id'];

            // Kalau ada gambar_profil
            if ($request->hasFile('gambar_profil')) {
                if ($user->gambar_profil && Storage::disk('public')->exists($user->gambar_profil)) {
                    Storage::disk('public')->delete($user->gambar_profil);
                }

                $path = $request->file('gambar_profil')->store('profil', 'public');
                $user->gambar_profil = $path;
            }

            $user->save();

            return response()->json([
                'message' => 'Profil berhasil diperbarui',
                'data' => $user
            ]);
        }

    public function pegawaiTerakhir()
    {
        $pegawai = Pengguna::latest()->first();
        return response()->json($pegawai);
    }

        public function halamanResetPassword(Request $request)
    {
        // Ini hanya validasi URL
        return response()->json(['message' => 'Link valid, silakan reset password']);
    }

}
