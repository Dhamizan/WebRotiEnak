<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Pengguna;

class Gerai extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_gerai',
        'alamat',
        'pegawai_id', // kepala toko
    ];

    // Relasi: kepala toko
    public function kepalaToko()
    {
        return $this->belongsTo(Pengguna::class, 'pegawai_id');
    }
}
