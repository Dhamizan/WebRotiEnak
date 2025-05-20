<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;

class Pengguna extends Authenticatable
{
    use HasApiTokens, HasFactory;

    protected $table = 'penggunas';

    protected $fillable = ['nama', 'email', 'notelp', 'alamat', 'gerai_id', 'kata_sandi', 'peran', 'jenis_kelamin', 'verifikasi_email', 'id_sidik_jari', 'gambar_profil'];

    public function getAuthPassword()
    {
        return $this->kata_sandi;
    }

    public function getPasswordAttribute()
    {
        return $this->kata_sandi;
    }

    public function setKataSandiAttribute($value)
    {
        $this->attributes['kata_sandi'] = Hash::make($value);
    }   

    public function createTokenPengguna()
    {
        return $this->createToken('auth_token')->plainTextToken;
    }

    public function gerai()
    {
        return $this->belongsTo(Gerai::class, 'gerai_id');
    }

    public function absensi()
    {
        return $this->belongsTo(Absensi::class, 'absensi_id');
    }

}
