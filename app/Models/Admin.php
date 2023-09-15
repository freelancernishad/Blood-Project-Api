<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
class Admin extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];
    protected $hidden = [
        'password',
        'created_at',
        'updated_at',
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


}
