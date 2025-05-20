<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Pengguna;

class Gerai extends Model
{
    use HasFactory;

    protected $fillable = [
        'gerai',
        'alamat',
        'lat',
        'long',
        'jumlah_pegawai',
        'jenis_gerai',
    ];

    public function pengguna(){
        return $this->hasMany(Pengguna::class, 'gerai_id');
    }
}
