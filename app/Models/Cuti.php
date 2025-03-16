<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cuti extends Model
{
    use HasFactory;

    protected $table = 'cuti';
    protected $fillable = ['karyawan_id', 'tgl_cuti', 'tgl_selesai'];

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class);
    }
}
