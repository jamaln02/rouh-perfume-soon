<?php

namespace App\Http\Controllers;

use App\Models\SurveySubmission;
use App\Models\DiscountCode;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SurveyController extends Controller
{
  public function submit(Request $request)
{
    // Validation بأسماء الحقول اللي جاية من React (camelCase)
    $validated = $request->validate([
        'name'                => 'required|string|max:255',
        'phone'               => 'required|string|max:20|unique:survey_submissions,phone',
        'email'               => 'nullable|email|max:255|unique:survey_submissions,email',
        'age'                 => 'required|integer|min:12|max:100',
        'gender'              => 'required|in:male,female',
        'location'            => 'required|string|max:100',

        'perfumeQuality'      => 'required|in:original,tarkib,both',
        'favoritePerfumes'    => 'required|string',
        'purchaseFrequency'   => 'nullable|in:monthly,quarterly,biannually,yearly,rarely',
        'mainProblem'         => 'required|string',
        'priceRange'          => 'required|string',
        'perfumeType'         => 'nullable|in:oriental,western,mixed,other',
        'perfumeTypeOther'    => 'nullable|string',
        'discoveryMethod'     => 'nullable|in:social,friends,store,influencers,ads',
        'wishList'            => 'required|string',
    ]);

    // منع التكرار
    $existing = SurveySubmission::where('phone', $validated['phone'])
                ->orWhere('email', $validated['email'] ?? null)
                ->first();

    if ($existing) {
        return response()->json([
            'success' => false,
            'message' => 'لقد حصلت على كود خصم سابقاً. لا يمكنك المشاركة مرة أخرى.'
        ], 422);
    }

    // توليد كود الخصم
    $discountCode = 'ROUH-' . strtoupper(Str::random(6));

    // حفظ كود الخصم
    DiscountCode::create([
        'code'              => $discountCode,
        'discount_percent'  => 20,
        'expires_at'        => now()->addMonths(3),
        'is_used'           => true,
        'used_by_phone'     => $validated['phone'],
        'used_by_email'     => $validated['email'] ?? null,
    ]);

    // حفظ الاستبيان
    SurveySubmission::create([
        'name'                => $validated['name'],
        'phone'               => $validated['phone'],
        'email'               => $validated['email'],
        'age'                 => $validated['age'],
        'gender'              => $validated['gender'],
        'location'            => $validated['location'],
        'perfume_quality'     => $validated['perfumeQuality'],
        'favorite_perfumes'   => $validated['favoritePerfumes'],
        'purchase_frequency'  => $validated['purchaseFrequency'] ?? null,
        'main_problem'        => $validated['mainProblem'],
        'price_range'         => $validated['priceRange'],
        'perfume_type'        => $validated['perfumeType'] ?? null,
        'perfume_type_other'  => $validated['perfumeTypeOther'] ?? null,
        'discovery_method'    => $validated['discoveryMethod'] ?? null,
        'wishlist'            => $validated['wishList'],
        'discount_code'       => $discountCode,
        'is_used'             => true,
        'ip_address'          => $request->ip(),
    ]);

    return response()->json([
        'success'      => true,
        'discountCode' => $discountCode,
        'message'      => 'تم حفظ البيانات بنجاح!'
    ]);
}
}