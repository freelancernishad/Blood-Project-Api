<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DonationLog extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'date',
        'blood_taker_name',
        'blood_taker_phone',
        'address',
        'hospital',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
