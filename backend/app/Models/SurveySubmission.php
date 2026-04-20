<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveySubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'email',
        'age',
        'gender',
        'location',
        'perfume_quality',
        'favorite_perfumes',
        'purchase_frequency',
        'main_problem',
        'price_range',
        'perfume_type',
        'perfume_type_other',
        'discovery_method',
        'wishlist',
        'discount_code',
        'is_used',
        'ip_address',
    ];

    protected $casts = [
        'age'      => 'integer',
        'is_used'  => 'boolean',
    ];

    // علاقة مع جدول discount_codes
    public function discountCode()
    {
        return $this->belongsTo(DiscountCode::class, 'discount_code', 'code');
    }
}