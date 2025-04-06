<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cuti extends Model
{
    use HasFactory;

    protected $table = 'cutis';
    protected $fillable = ['id_pengguna', 'waktu_mulai', 'waktu_selesai', 'alasan', 'dokumen_pendukung', 'status'];

    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'id_pengguna');
    }
}