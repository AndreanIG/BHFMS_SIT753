<?php

namespace App\Http\Controllers;

use App\Models\Boarding;
use App\Models\Review;
use App\Models\TenantBoarding;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ReviewController extends Controller
{
    public function getAllReviewPage()
    {
        $boardingHouseIDs = TenantBoarding::where('user_id', Auth::user()->id)->where('tenant_status', 'checkout')->get()->pluck('boarding_id');
        $boardingHouses = Boarding::whereIn('id', $boardingHouseIDs)->get();

        return Inertia::render('Review/AllReview', ['boardingHouseList' => $boardingHouses]);
    }

    public function getCreateReviewOrViewReviewPage(Request $request)
    {
        return Inertia::render('Review/CreateOrViewReview', [
            'review' => Review::where('user_id', Auth::user()->id)->where('boarding_id', $request->id)->first() ?? null,
            'boardingHouseId' => $request->id,
        ]);
    }

    public function createReview(Request $request)
    {
        $validation = $request->validate([
            'boardingId' => ['required'],
            'rating' => ['required', 'numeric', 'min:1'],
            'description' => ['required']
        ]);

        Review::create([
            'user_id' => Auth::user()->id,
            'boarding_id' => $request->boardingId,
            'rating' => $request->rating,
            'review_desc' => $request->description,
        ]);

        return redirect('/boardingTenant')->with('message', 'Success creating review');
    }

    public function updateReview(Request $request)
    {
        $validation = $request->validate([
            'boardingId' => ['required'],
            'rating' => ['required', 'numeric', 'min:1'],
            'description' => ['required']
        ]);

        Review::where('boarding_id', $request->boardingId)->where('user_id', Auth::user()->id)->update([
            'rating' => $request->rating,
            'review_desc' => $request->description,
        ]);

        return redirect('/boardingTenant')->with('message', 'Success updating review');
    }
}
