<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscountCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'discount_percent',
        'expires_at',
        'is_used',
        'used_by_phone',
        'used_by_email',
    ];

    protected $casts = [
        'expires_at' => 'date',
        'is_used'    => 'boolean',
    ];

    // علاقة مع الاستبيانات
    public function surveySubmissions()
    {
        return $this->hasMany(SurveySubmission::class, 'discount_code', 'code');
    }
}