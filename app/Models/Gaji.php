<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Gaji extends Model
{
    use HasFactory;

    protected $table = 'gajis';
    protected $fillable = ['id_pengguna', 'id_absensi', 'gaji'];

    public function pegawai()
    {
        return $this->belongsTo(Pengguna::class);
    }
}