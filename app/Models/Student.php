<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;


class Student extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory;

    protected $fillable = [
        'founder_name',
        'company_name',
        'short_note',
        'founder_email',
        'password',
        'location',
        'founder_phone',
        'business_category',
        'founder_gender',
        'website_url',
        'employee_number',
        'formation_of_company',
        'company_video_link',
        'facebook_link',
        'youtube_link',
        'linkedin_link',
        'attachment_file',
        'batch_id',
        'rating'
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



    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }
    public function exams()
    {
        return $this->belongsToMany(Exam::class, 'exam_student')->withPivot('score');
    }
}
