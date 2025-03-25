<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;

class Pengguna extends Authenticatable
{
    use HasApiTokens, HasFactory;

    protected $table = 'penggunas';

    protected $fillable = ['nama', 'email', 'notelp', 'alamat', 'kata_sandi', 'peran', 'jenis_kelamin', 'verifikasi_email', 'id_sidik_jari', 'gambar_profil'];

    public function getAuthPassword()
    {
        return $this->kata_sandi;
    }

    // Enkripsi password saat disimpan
    public function setKataSandiAttribute($value)
    {
        $this->attributes['kata_sandi'] = bcrypt($value);
    }

    // Membuat token autentikasi Sanctum
    public function createTokenPengguna()
    {
        return $this->createToken('auth_token')->plainTextToken;
    }
}
