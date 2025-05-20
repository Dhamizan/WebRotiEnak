<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Absensi extends Authenticatable
{
    use HasFactory;
    protected $fillable = ['id_pengguna', 'jam_masuk', 'jam_keluar', 'jam_kerja'];

    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'id_pengguna');
    }
    
}
