<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    use HasFactory;
    protected $fillable = ['karyawan_id', 'jam_masuk', 'jam_keluar', 'jam_kerja'];

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class);
    }
}
