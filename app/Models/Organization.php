<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Organization extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory;

    protected $fillable = [
        'logo',
        'name',
        'mobile',
        'email',
        'whatsapp_number',
        'division',
        'district',
        'thana',
        'union',
        'password',
    ];
    protected $hidden = [
        'password',
    ];
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    // Required method from JWTSubject
    public function getJWTCustomClaims()
    {
        return [];
    }


    public function users()
    {
        return $this->hasMany(User::class, 'org');
    }
}
