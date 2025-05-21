<?php

namespace App\Http\Controllers;

use App\Models\Boarding;
use App\Models\BoardingImage;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class WishlistController extends Controller
{
    public function showWishlist()
    {
        $getBoardingIds = Wishlist::where('user_id', Auth::user()->id)->get()->pluck('boarding_id');
        $wishlistedBoardingHouse = Boarding::whereIn('id', $getBoardingIds)->get();

        foreach ($wishlistedBoardingHouse as $key => $boardingHouse) {
            $wishlistedBoardingHouse[$key]->imageUrl = BoardingImage::where('boarding_id', $boardingHouse->id)->first()->image;
        }

        return Inertia::render('Wishlist', ['wishlistedBoardingHouses' => $wishlistedBoardingHouse]);
    }

    public function addWishlist(Request $request)
    {
        Wishlist::create([
            'user_id' => Auth::user()->id,
            'boarding_id' => $request->id,
        ]);
        return back();
    }

    public function removeWishlist(Request $request)
    {
        Wishlist::where('user_id', Auth::user()->id)->where('boarding_id', $request->id)->delete();
        return back();
    }
}
