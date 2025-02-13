<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Karyawan extends Model
{
    use HasApiTokens, HasFactory;
    protected $fillable = ['name', 'email', 'password', 'position', 'fingerprint_id', 'role'];
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    public function createTokenKaryawan()
    {
        return $this->createToken('auth_token')->plainTextToken;
    }
}
