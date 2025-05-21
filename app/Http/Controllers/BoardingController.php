<?php

namespace App\Http\Controllers;

// use App\Models\Boarding;

use App\Models\Boarding;
use App\Models\BoardingImage;
use App\Models\BoardingType;
use App\Models\Facility;
use App\Models\FacilityDetail;
use App\Models\ManagerBoarding;
use App\Models\OwnerBoarding;
use App\Models\TenantBoarding;
use App\Models\Review;
use App\Models\User;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class BoardingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function getMainPage()
    {
        $allReview = Review::all();
        $weightingFactor = 5;

        $boardingRatingDetails = [];
        foreach ($allReview as $review) {
            if (isset($boardingRatingDetails[$review->boarding_id])) {
                $boardingRatingDetails[$review->boarding_id]['total_star_rating'] += $review->rating;
                $boardingRatingDetails[$review->boarding_id]['total_review_count'] += 1;
            } else {
                $boardingRatingDetails[$review->boarding_id]['total_star_rating'] = $review->rating;
                $boardingRatingDetails[$review->boarding_id]['total_review_count'] = 1;
                $boardingRatingDetails[$review->boarding_id]['boarding_id'] = $review->boarding_id;
            }
        }

        $globalMean = Review::all()->avg('rating');
        foreach ($boardingRatingDetails as $key => $array) {
            $bayesianRating = (($weightingFactor * $globalMean) + ($array['total_star_rating'])) / ($weightingFactor + $array['total_review_count']);
            $boardingRatingDetails[$key]['bayesian_rating'] = $bayesianRating;
        }

        usort($boardingRatingDetails, function ($a, $b) {
            return $b['bayesian_rating'] <=> $a['bayesian_rating'];
        });

        $boardingIDs = array_slice(array_column($boardingRatingDetails, 'boarding_id'), 0, 6);
        $approvedBoardingHouseIDs = OwnerBoarding::where('owner_status', 'approved')->get()->pluck('boarding_id');

        $highlyRatedBoardingHouse = Boarding::whereIn('id', $boardingIDs)->whereIn('id', $approvedBoardingHouseIDs)->get();
        foreach ($highlyRatedBoardingHouse as $key => $boardingHouse) {
            $highlyRatedBoardingHouse[$key]->imageUrl = BoardingImage::where('boarding_id', $boardingHouse->id)->first()->image;
        }

        return inertia('MainPage', ['highlyRatedBoardingHouse' => $highlyRatedBoardingHouse]);
    }

    public function indexAdmin(Request $request)
    {

        $Boarding_data = Boarding::join('owner_boardings', 'boardings.id', '=', 'owner_boardings.boarding_id')
            ->join('users', 'users.id', '=', 'owner_boardings.user_id')
            ->when($request->search, function ($query, $search) {
                if ($search == 'all') {
                    $query;
                } else {
                    $query->where('owner_status', '=', $search);
                }
            })->when($request->searchQuery, function ($query, $searchQuery) {
                if ($searchQuery == '') {
                    $query;
                } else {
                    $query->where('boarding_name', 'like', '%' . $searchQuery . '%')
                        ->orWhere('owner_status', 'like', '%' . $searchQuery . '%')
                        ->orWhere('user_name', 'like', '%' . $searchQuery . '%');
                }
            })->paginate(5)->withQueryString();

        $all_boarding_count = Boarding::join('owner_boardings', 'boardings.id', "=", 'owner_boardings.boarding_id')
            ->select('owner_status', DB::raw('count(*) as total'))
            ->groupBy('owner_status')
            ->get()->toArray();

        $all = 0;
        $apv = 0;
        $dcl = 0;
        $pending = 0;
        $ban = 0;
        $disabled = 0;

        foreach ($all_boarding_count as $count => $collection) {
            if ($all_boarding_count[$count]["owner_status"] == "pending") {
                $all += $all_boarding_count[$count]["total"];
                $pending = $all_boarding_count[$count]["total"];
            } elseif ($all_boarding_count[$count]["owner_status"] == "approved") {
                $all += $all_boarding_count[$count]["total"];
                $apv = $all_boarding_count[$count]["total"];
            } elseif ($all_boarding_count[$count]["owner_status"] == "declined") {
                $all += $all_boarding_count[$count]["total"];
                $dcl = $all_boarding_count[$count]["total"];
            } elseif ($all_boarding_count[$count]["owner_status"] == "banned") {
                $all += $all_boarding_count[$count]["total"];
                $ban = $all_boarding_count[$count]["total"];
            } elseif ($all_boarding_count[$count]["owner_status"] == "disabled") {
                $all += $all_boarding_count[$count]["total"];
                $disabled = $all_boarding_count[$count]["total"];
            }
        }
        return Inertia::render('Boarding/BoardingManagementAdmin', [
            'all_count' => $all,
            'approved' => $apv,
            'pending' => $pending,
            'declined' => $dcl,
            'banned' => $ban,
            'disabled' => $disabled,
            'boardings' => $Boarding_data,
        ]);
    }

    public function indexManager(Request $request)
    {
        $Boarding_data = Boarding::join('manager_boardings', 'manager_boardings.boarding_id', '=', 'boardings.id')
            ->join('owner_boardings', 'boardings.id', "=", 'owner_boardings.boarding_id')
            ->join('users', 'users.id', "=", "owner_boardings.user_id")
            ->where('manager_boardings.user_id', '=', auth()->id())
            ->when($request->search, function ($query, $search) {
                if ($search == 'all') {
                    $query;
                } else {
                    $query->where('owner_status', '=', $search);
                }
            })->when($request->searchQuery, function ($query, $searchQuery) {
                if ($searchQuery == '') {
                    $query;
                } else {
                    $query->where('boarding_name', 'like', '%' . $searchQuery . '%')
                        ->orWhere('owner_status', 'like', '%' . $searchQuery . '%')
                        ->orWhere('user_name', 'like', '%' . $searchQuery . '%');
                }
            })->paginate(5)->withQueryString();

        $all_boarding_count = ManagerBoarding::join('boardings', 'boardings.id', 'manager_boardings.boarding_id')
            ->join('owner_boardings', 'boardings.id', "=", 'owner_boardings.boarding_id')
            ->select('owner_status', DB::raw('count(*) as total'))
            ->where('manager_boardings.user_id', '=', auth()->id())
            ->groupBy('owner_status')
            ->get()->toArray();

        $all = 0;
        $apv = 0;
        $dcl = 0;
        $pending = 0;
        $ban = 0;
        $disabled = 0;

        foreach ($all_boarding_count as $count => $collection) {
            if ($all_boarding_count[$count]["owner_status"] == "pending") {
                $all += $all_boarding_count[$count]["total"];
                $pending = $all_boarding_count[$count]["total"];
            } elseif ($all_boarding_count[$count]["owner_status"] == "approved") {
                $all += $all_boarding_count[$count]["total"];
                $apv = $all_boarding_count[$count]["total"];
            } elseif ($all_boarding_count[$count]["owner_status"] == "declined") {
                $all += $all_boarding_count[$count]["total"];
                $dcl = $all_boarding_count[$count]["total"];
            } elseif ($all_boarding_count[$count]["owner_status"] == "banned") {
                $all += $all_boarding_count[$count]["total"];
                $ban = $all_boarding_count[$count]["total"];
            } elseif ($all_boarding_count[$count]["owner_status"] == "disabled") {
                $all += $all_boarding_count[$count]["total"];
                $disabled = $all_boarding_count[$count]["total"];
            }
        }

        return Inertia::render('Boarding/BoardingManagementManager', [
            'all_count' => $all,
            'approved' => $apv,
            'pending' => $pending,
            'declined' => $dcl,
            'banned' => $ban,
            'disabled' => $disabled,
            'boardings' => $Boarding_data,
        ]);
    }

    public function indexOwner(Request $request)
    {

        $Boarding_data = Boarding::join('owner_boardings', 'boardings.id', '=', 'owner_boardings.boarding_id')
            ->join('users', 'users.id', '=', 'owner_boardings.user_id')
            ->where('owner_boardings.user_id', '=', auth()->id())
            ->when($request->search, function ($query, $search) {
                if ($search == 'all') {
                    $query;
                } else {
                    $query->where('owner_status', '=', $search);
                }
            })->when($request->searchQuery, function ($query, $searchQuery) {
                if ($searchQuery == '') {
                    $query;
                } else {
                    $query->where('boarding_name', 'like', '%' . $searchQuery . '%')
                        ->orWhere('owner_status', 'like', '%' . $searchQuery . '%')
                        ->orWhere('user_name', 'like', '%' . $searchQuery . '%');
                }
            })->paginate(5)->withQueryString();


        $all_boarding_count = OwnerBoarding::select('owner_status', DB::raw('count(*) as total'))
            ->where('user_id', '=', auth()->id())
            ->groupBy('owner_status')
            ->get()->toArray();

        $all = 0;
        $apv = 0;
        $dcl = 0;
        $pending = 0;
        $ban = 0;
        $disabled = 0;

        foreach ($all_boarding_count as $count => $collection) {
            if ($all_boarding_count[$count]["owner_status"] == "pending") {
                $all += $all_boarding_count[$count]["total"];
                $pending = $all_boarding_count[$count]["total"];
            } elseif ($all_boarding_count[$count]["owner_status"] == "approved") {
                $all += $all_boarding_count[$count]["total"];
                $apv = $all_boarding_count[$count]["total"];
            } elseif ($all_boarding_count[$count]["owner_status"] == "declined") {
                $all += $all_boarding_count[$count]["total"];
                $dcl = $all_boarding_count[$count]["total"];
            } elseif ($all_boarding_count[$count]["owner_status"] == "banned") {
                $all += $all_boarding_count[$count]["total"];
                $ban = $all_boarding_count[$count]["total"];
            } elseif ($all_boarding_count[$count]["owner_status"] == "disabled") {
                $all += $all_boarding_count[$count]["total"];
                $disabled = $all_boarding_count[$count]["total"];
            }
        }

        return Inertia::render('Boarding/BoardingManagementOwner', [
            'all_count' => $all,
            'approved' => $apv,
            'pending' => $pending,
            'declined' => $dcl,
            'banned' => $ban,
            'disabled' => $disabled,
            'boardings' => $Boarding_data,
        ]);
    }

    public function indexTenant(Request $request)
    {

        $Boarding_data = Boarding::join('owner_boardings', 'boardings.id', '=', 'owner_boardings.boarding_id')
            ->join('users', 'users.id', '=', 'owner_boardings.user_id')
            ->join('tenant_boardings', 'tenant_boardings.boarding_id', '=', 'boardings.id')
            ->select('boardings.boarding_name', 'tenant_boardings.tenant_status', 'users.user_name', 'boardings.id AS boarding_id', 'tenant_boardings.id AS tenant_id', 'tenant_boardings.end_date AS endDate')
            ->where('tenant_boardings.user_id', '=', auth()->id())
            ->when($request->search, function ($query, $search) {
                if ($search == 'all') {
                    $query;
                } else {
                    $query->where('tenant_status', '=', $search);
                }
            })->when($request->searchQuery, function ($query, $searchQuery) {
                if ($searchQuery == '') {
                    $query;
                } else {
                    $query->where('boarding_name', 'like', '%' . $searchQuery . '%')
                        ->orWhere('owner_status', 'like', '%' . $searchQuery . '%')
                        ->orWhere('user_name', 'like', '%' . $searchQuery . '%');
                }
            })->paginate(5)->withQueryString();


        $all_boarding_count = TenantBoarding::select('tenant_status', DB::raw('count(*) as total'))
            ->where('user_id', '=', auth()->id())
            ->groupBy('tenant_status')
            ->get()->toArray();
        $all = 0;
        $apv = 0;
        $dcl = 0;
        $pending = 0;
        $checkout = 0;
        $pendingPayment = 0;
        

        foreach ($all_boarding_count as $count => $collection) {
            if ($all_boarding_count[$count]["tenant_status"] == "pending") {
                $all += $all_boarding_count[$count]["total"];
                $pending = $all_boarding_count[$count]["total"];
            } elseif ($all_boarding_count[$count]["tenant_status"] == "approved") {
                $all += $all_boarding_count[$count]["total"];
                $apv = $all_boarding_count[$count]["total"];
            } elseif ($all_boarding_count[$count]["tenant_status"] == "declined") {
                $all += $all_boarding_count[$count]["total"];
                $dcl = $all_boarding_count[$count]["total"];
            } elseif ($all_boarding_count[$count]["tenant_status"] == "checkout") {
                $all += $all_boarding_count[$count]["total"];
                $checkout = $all_boarding_count[$count]["total"];
            } elseif ($all_boarding_count[$count]["tenant_status"] == "pending_payment") {
                $all += $all_boarding_count[$count]["total"];
                $pendingPayment = $all_boarding_count[$count]["total"];
            }
        }


        return Inertia::render('Boarding/BoardingManagementTenant', [
            'all_count' => $all,
            'approved' => $apv,
            'pending' => $pending,
            'declined' => $dcl,
            'checkout' => $checkout,
            'boardings' => $Boarding_data,
            'pendingPayment' => $pendingPayment,
        ]);
    }

    public function getAllBoardingHouse()
    {
        $approvedBoardingHouseId = OwnerBoarding::where('owner_status', 'approved')->get()->pluck('boarding_id');
        $allBoardingHouse = Boarding::whereIn('id', $approvedBoardingHouseId)->paginate(18);

        foreach ($allBoardingHouse as $key => $boardingHouse) {
            $allBoardingHouse[$key]->imageUrl = BoardingImage::where('boarding_id', $boardingHouse->id)->first()->image;
        }

        return Inertia::render('Boarding/AllBoardingHouse', ['allBoardingHouse' => $allBoardingHouse]);
    }

    public function getBoardingHouseDetail(Request $request)
    {
        $selectedBoardingHouseDetail = Boarding::where('id', $request->id)->first();
        $boardingHouseImages = BoardingImage::where('boarding_id', $request->id)->get()->pluck('image');

        $ownerId = OwnerBoarding::where('boarding_id', $request->id)->first()->user_id;
        $owner = User::where('id', $ownerId)->first();

        $boardingIdManagedBySameOwner = OwnerBoarding::where('user_id', $ownerId)->where('boarding_id', '!=', $request->id)->where('owner_status', 'approved')->take(4)->pluck('boarding_id');
        if ($boardingIdManagedBySameOwner) {
            $boardingListManagedBySameOwner = Boarding::whereIn('id', $boardingIdManagedBySameOwner)->get();
            foreach ($boardingListManagedBySameOwner as $key => $boardingHouse) {
                $boardingListManagedBySameOwner[$key]->imageUrl = BoardingImage::where('boarding_id', $boardingHouse->id)->first()->image;
            }
        }

        $facilityList = Facility::where('boarding_id', $request->id)->get();
        foreach ($facilityList as $key => $facility) {
            $facilityDetail = FacilityDetail::where('id', $facility->facility_id)->first();
            $facilityList[$key]->facility_detail_name = $facilityDetail->facility_detail_name;
            $facilityList[$key]->facility_img_path = $facilityDetail->facility_img_path;
        }

        $currVacancy = $selectedBoardingHouseDetail->rooms - (TenantBoarding::where([['boarding_id', $request->id]])->whereIn('tenant_status', ['approved', 'pending_payment'])->count());
        $currVacancy > 0 ? $isAvailable = true : $isAvailable = false;
        $reviews = Review::where('boarding_id', $request->id)->get();
        $totalRating = 0;
        $starRating = array(0, 0, 0, 0, 0);
        foreach ($reviews as $key => $review) {
            $reviews[$key]->user = User::where('id', $review->user_id)->first();
            $totalRating += $reviews[$key]->rating;
            $starRating[$reviews[$key]->rating - 1]++;
        }

        if (isset(Auth::user()->id)) {
            $onWishlist = Wishlist::where('user_id', Auth::user()->id)->where('boarding_id', $request->id)->first() ?? null;
        } else {
            $onWishlist = null;
        }

        return Inertia::render('Boarding/SelectedBoardingHouse', [
            'boardingHouseDetail' => $selectedBoardingHouseDetail,
            'images' => $boardingHouseImages,
            'ownerName' => $owner->user_name,
            'ownerPicture' => $owner->profile_picture,
            'facilityList' => $facilityList,
            'currVacancy' => $currVacancy,
            'isAvailable' => $isAvailable,
            'reviews' => $reviews,
            'averageRating' => count($reviews) == 0 ? count($reviews) : number_format($totalRating / count($reviews), 2),
            'totalReviewCount' => count($reviews),
            'ratingStar' => $starRating,
            'isWishlisted' => $onWishlist != null ? true : false,
            'boardingManagedBySameOwner' => $boardingListManagedBySameOwner ?? null
        ]);
    }

    public function searchBoardingByLocation(Request $request)
    {
        $radius = 2; //radius in km
        $earthRadius = 6371; //radius in km

        $approvedBoardingHouseIDs = OwnerBoarding::where('owner_status', 'approved')->get()->pluck('boarding_id');

        $boardingSearchResults = Boarding::select(
            'id',
            'boarding_name',
            'address',
            'latitude',
            'longitude',
            DB::raw("
                $earthRadius * 2 * ASIN(
                        SQRT(
                            POWER(SIN((RADIANS(latitude - $request->latitude)) / 2), 2)
                            + COS(RADIANS($request->latitude)) * COS(RADIANS(latitude))
                            * POWER(SIN((RADIANS(longitude - $request->longitude))/ 2), 2)
                        )
                    )
                AS distance")
        )->whereIn('id', $approvedBoardingHouseIDs)->having('distance', '<', $radius)->get();

        foreach ($boardingSearchResults as $key => $boardingHouse) {
            $boardingSearchResults[$key]->imageUrl = BoardingImage::where('boarding_id', $boardingHouse->id)->first()->image;
        }

        return Inertia::render('Boarding/SearchBoardingResult', ['searchResults' => $boardingSearchResults]);
    }


    public function getCreateOwnerBoarding()
    {
        $Manager_data = User::where('user_role_id', '=', '4')->get();
        return Inertia::render('Boarding/CreateBoarding', [
            'facilities' => FacilityDetail::get(),
            'types' => BoardingType::get(),
            'managers' => $Manager_data,
        ]);
    }

    public function createOwnerBoarding(Request $request)
    {
        $custom_messages = [
            'images.min' => 'Need at least 1 image !',
            'images.max' => 'Maximum 5 images !'
        ];

        $validation = $request->validate([
            'name' => ['required', 'max:200'],
            'address' => ['required'],
            'type' => ['required'],
            'rooms' => ['required', 'numeric', 'min:1'],
            'price' => ['required', 'numeric', 'min:1'],
            'facility' => ['min:1'],
            'description' => ['required', 'max:200', 'min:5'],
            'images' => ['min:1', 'max:5'],
        ], $custom_messages);


        $BoardingNow = Boarding::create([
            'boarding_name' => $request['name'],
            'address' => $request['address'],
            'latitude' => $request['lat'],
            'longitude' => $request['lng'],
            'type_id' => $request['type'],
            'rooms' => $request['rooms'],
            'shared_bathroom' => $request['sharedBathroom'],
            'price' => $request['price'],
            'boarding_desc' => $request['description'],
        ]);

        $BoardingNow->facilities()->attach($request['facility']);

        $OwnerBoardingNow = OwnerBoarding::create([
            'boarding_id' => $BoardingNow->id,
            'user_id' => $request->user()->id,
            'owner_status' => 'pending',
        ]);


        if (isset($request['manager']) && $request['manager'] !== null) {
            $ManagerBoardingNow = ManagerBoarding::create([
                'user_id' => $request['manager']['id'],
                'boarding_id' => $BoardingNow->id,
            ]);
        }

        //FILES
        if (($request->file('images') !== null)) {
            foreach ($request->file('images') as $image) {

                $path = $image->getClientOriginalName();
                $path = str_replace(" ", "-", $path);
                $path = time() . '-' . $path;
                $path = 'Boarding_House_Images/' . $path;
                $img = new BoardingImage();

                Storage::putFileAs('public/', $image, $path);
                $img->image = '/storage/' . $path;
                $img->boarding_id = $BoardingNow->id;
                $img->save();
            }
        }

        return redirect('/boardingOwner')->with('message', 'Success Adding new Boarding House');
    }

    public function getReadBoarding(Request $request)
    {
        $currBoarding = Boarding::where('id', '=', $request->id)->first();
        $currFacilities = ($currBoarding->facilities()->exists()) ? $currBoarding->facilities()->get() : null;
        $currType = $currBoarding->boardingType()->get()->first();
        $currManager = ($currBoarding->managerBoardings()->exists()) ? $currBoarding->managerBoardings()->get()->first() : null;
        $currImages = $currBoarding->images()->get();
        $currVacancy = $currBoarding->rooms - (TenantBoarding::where([['boarding_id', $request->id]])->whereIn('tenant_status', ['approved', 'pending_payment'])->count());
        $currOwner = OwnerBoarding::where('owner_boardings.boarding_id', '=', $currBoarding->id)->first();

        return Inertia::render('Boarding/ReadBoarding', [
            'currImages' => $currImages,
            'currBoarding' => $currBoarding,
            'currFacilities' => $currFacilities,
            'currType' => $currType,
            'currManager' => $currManager,
            'currOwner' => $currOwner,
            'currVacancy' => $currVacancy,
        ]);
    }

    public function getReadBoardingTenant(Request $request)
    {

        $currTenant = TenantBoarding::where('id', '=', $request->id)->first();
        $currBoarding = Boarding::where('id', '=', $currTenant->boarding_id)->first();
        $currFacilities = ($currBoarding->facilities()->exists()) ? $currBoarding->facilities()->get() : null;
        $currType = $currBoarding->boardingType()->get()->first();
        $currManager = $currBoarding->managerBoardings()->get()->first();
        $currImages = $currBoarding->images()->get();

        return Inertia::render('Boarding/ReadBoarding', [
            'currTenant' => $currTenant,
            'currImages' => $currImages,
            'currBoarding' => $currBoarding,
            'currFacilities' => $currFacilities,
            'currType' => $currType,
            'currManager' => $currManager,
        ]);
    }

    public function getUpdateBoarding(Request $request)
    {
        $Manager_data = User::where('user_role_id', '=', '4')->get();
        $currBoarding = Boarding::where('id', '=', $request->id)->get()->first();
        $currFacilities = ($currBoarding->facilities()->exists()) ? $currBoarding->facilities()->get() : null;

        $currManager = $currBoarding->managerBoardings()->get()->first();

        $currImages = $currBoarding->images()->get();

        $shared_bathroom = $currBoarding['shared_bathroom'] == 1 ? true : false;

        return Inertia::render('Boarding/UpdateBoarding', [
            'currImages' => $currImages,
            'currBoarding' => $currBoarding,
            'currFacilities' => $currFacilities,
            'currManager' => $currManager,
            'facilities' => FacilityDetail::get(),
            'types' => BoardingType::get(),
            'managers' => $Manager_data,
            'shared_bathroom_bool' => $shared_bathroom,
        ]);
    }

    public function updateBoarding(Request $request)
    {

        $max_pic = 5 - (int)$request['max_image'];
        $total_pic = (int)$request['max_image'] + (int)count($request['images']);
        $custom_messages = [
            'images.max' => 'Maximum number of image is 5, you have ' . $total_pic . ' image in this Boarding House, Please Upload Again !',
        ];

        $validation = $request->validate([
            'name' => ['required', 'max:200'],
            'address' => ['required'],
            'type' => ['required'],
            'rooms' => ['required', 'numeric', 'min:1'],
            'price' => ['required', 'numeric', 'min:1'],
            'facility' => ['min:1'],
            'description' => ['required', 'max:200', 'min:5'],
            'images' => ['max:' . $max_pic],
        ], $custom_messages);

        //Change from model into array of facility
        $facility_id = [];
        foreach ($request['facility'] as $fac) {
            array_push($facility_id, $fac['id']);
        }


        Boarding::findOrFail($request->id)->update([
            'boarding_name' => $request['name'],
            'address' => $request['address'],
            'latitude' => $request['lat'],
            'longitude' => $request['lng'],
            'type_id' => $request['type'],
            'rooms' => $request['rooms'],
            'shared_bathroom' => $request['sharedBathroom'],
            'price' => $request['price'],
            'boarding_desc' => $request['description'],
        ]);

        Boarding::findOrFail($request->id)->facilities()->sync($facility_id);

        $currOwner = OwnerBoarding::where('boarding_id', '=', $request->id)->get()->first();

        $currManager = ManagerBoarding::where([['boarding_id', '=', $request->id]])->get()->first();


        if (isset($request['manager']) && $request['manager'] !== null) {
            if ($currManager) {
                //current Manager Exists, update record
                $currManager->update([
                    'user_id'  => $request['manager']['id'],
                ]);
            } else {
                //current Manager doesnt exist, create new
                ManagerBoarding::create([
                    'boarding_id' => $request->id,
                    'user_id'  => $request['manager']['id'],
                ]);
            }
        } else if ($currManager) {
            //Current Manager Exists, Delete Record because empty request
            $currManager->delete();
        }


        //FILES
        if (($request->file('images') !== null)) {
            foreach ($request->file('images') as $image) {

                $path = $image->getClientOriginalName();
                $path = str_replace(" ", "-", $path);
                $path = time() . '-' . $path;
                $path = 'Boarding_House_Images/' . $path;
                $img = new BoardingImage();

                Storage::putFileAs('public/', $image, $path);
                $img->image = '/storage/' . $path;
                $img->boarding_id = $request->id;
                $img->save();
            }
        }

        if (Auth::user()->user_role_id == 1) {
            return redirect('/boardingAdmin')->with('message', 'Success Updating Boarding House');
        } else if (Auth::user()->user_role_id == 3) {
            return redirect('/boardingOwner')->with('message', 'Success Updating Boarding House');
        }
    }
    public function deleteBoarding(Request $request)
    {

        OwnerBoarding::where('boarding_id', '=', $request->id)->first()->update([
            'owner_status' => 'banned',
        ]);

        if (Auth::user()->user_role_id == 1) {
            return redirect('/boardingAdmin')->with('message', 'Success Deleting Boarding House');
        } else if (Auth::user()->user_role_id == 3) {
            return redirect('/boardingOwner')->with('message', 'Success Deleting Boarding House');
        }
    }

    public function boardingRent(Request $request)
    {
        if(Auth::user()->user_role_id != 2){
            return back()->with('message', 'Only a Tenant Can Rent this Boarding House !');
        }

        $currTenantBoardingPending = TenantBoarding::where([['user_id', '=', Auth::user()->id], ['tenant_status', '=', 'pending'],['boarding_id','=',$request->id]])->get();
        //Exist Pending Status of Current Boarding House

        if(!$currTenantBoardingPending->isEmpty()){
            return back()->with('message', 'You have a pending request with this Boarding House !');
        }


        $validation = $request->validate([
            'startDate' => ['required', 'date', 'after_or_equal:today'],
        ]);

        $currTenantBoarding = TenantBoarding::where([['user_id', '=', Auth::user()->id], ['tenant_status', '=', 'approved']])->count();

        if ($currTenantBoarding > 0) {
            return back()->with('message', 'Cannot rent boarding house when you have an active one !');
        }
        $BoardingNow = TenantBoarding::create([
            'user_id' => auth()->id(),
            'boarding_id' => $request->id,
            'start_date' => $request['startDate'],
            'tenant_status' => 1,
        ]);
        return redirect('/boarding/all')->with('message', 'Success Requesting rent to boarding house');
    }

    public function getAdminApproveBoarding(Request $request)
    {
        $currBoarding = Boarding::where('id', '=', $request->id)->get()->first();
        $currFacilities = ($currBoarding->facilities()->exists()) ? $currBoarding->facilities()->get() : null;
        $currType = $currBoarding->boardingType()->get()->first();
        $currOwner = $currBoarding->ownerBoardings()->get()->first();
        $currManager = $currBoarding->managerBoardings()->get()->first();
        $currImages = $currBoarding->images()->get();


        return Inertia::render('Boarding/AdminApproveBoarding', [
            'currImages' => $currImages,
            'currBoarding' => $currBoarding,
            'currFacilities' => $currFacilities,
            'currType' => $currType,
            'currOwner' => $currOwner,
            'currManager' => $currManager,
        ]);
    }

    public function AdminApproveBoarding(Request $request)
    {

        if ($request['decision'] == "accept") {
            OwnerBoarding::where('id', '=', $request->currID)->update([
                'owner_status' => 2,
                'declined_reason' => $request['reason'],
            ]);
            return redirect('/boardingAdmin')->with('message', 'Success Accepting new Boarding');
        } else if ($request['decision'] == "decline") {
            OwnerBoarding::where('id', '=', $request->currID)->update([
                'owner_status' => 3,
                'declined_reason' => $request['reason'],
            ]);
            return redirect('/boardingAdmin')->with('message', 'Success Declining new Boarding (Can Reapprove)');
        } else {
            OwnerBoarding::where('id', '=', $request->currID)->update([
                'owner_status' => 4,
                'declined_reason' => $request['reason'],
            ]);
            return redirect('/boardingAdmin?search=pending')->with('message', 'Success Declining new Boarding (Cannot Reapprove)');
        }
    }

    public function getReapproveBoarding(Request $request)
    {
        $Manager_data = User::where('user_role_id', '=', '4')->get();
        $currBoarding = Boarding::where('id', '=', $request->id)->first();
        $currFacilities = ($currBoarding->facilities()->exists()) ? $currBoarding->facilities()->get() : null;

        $currManager = $currBoarding->managerBoardings()->first();

        $currImages = $currBoarding->images()->get();

        $shared_bathroom = $currBoarding['shared_bathroom'] == 1 ? true : false;
        $currReason = OwnerBoarding::where('boarding_id', '=', $request->id)->first()->declined_reason;

        return Inertia::render('Boarding/ReapproveBoarding', [
            'currImages' => $currImages,
            'currBoarding' => $currBoarding,
            'currFacilities' => $currFacilities,
            'currManager' => $currManager,
            'facilities' => FacilityDetail::get(),
            'types' => BoardingType::get(),
            'managers' => $Manager_data,
            'shared_bathroom_bool' => $shared_bathroom,
            'reason' => $currReason,
        ]);
    }

    public function ReapproveBoarding(Request $request)
    {
        $max_pic = 5 - (int)$request['max_image'];
        $total_pic = (int)$request['max_image'] + (int)count($request['images']);
        $custom_messages = [
            'images.max' => 'Maximum number of image is 5, you have ' . $total_pic . ' image in this Boarding House, Please Upload Again !',
        ];

        $validation = $request->validate([
            'name' => ['required', 'max:200'],
            'address' => ['required'],
            'type' => ['required'],
            'rooms' => ['required', 'numeric', 'min:1'],
            'price' => ['required', 'numeric', 'min:1'],
            'facility' => ['min:1'],
            'description' => ['required', 'max:200', 'min:5'],
            'images' => ['max:' . $max_pic],
        ], $custom_messages);

        //Change from model into array of facility
        $facility_id = [];
        foreach ($request['facility'] as $fac) {
            array_push($facility_id, $fac['id']);
        }


        Boarding::findOrFail($request->id)->update([
            'boarding_name' => $request['name'],
            'address' => $request['address'],
            'latitude' => $request['lat'],
            'longitude' => $request['lng'],
            'type_id' => $request['type'],
            'rooms' => $request['rooms'],
            'shared_bathroom' => $request['sharedBathroom'],
            'price' => $request['price'],
            'boarding_desc' => $request['description'],
        ]);

        Boarding::findOrFail($request->id)->facilities()->sync($facility_id);

        $currOwner = OwnerBoarding::where('boarding_id', '=', $request->id)->get()->first();

        $currManager = ManagerBoarding::where([['boarding_id', '=', $request->id]])->get()->first();

        $currOwner->update([
            'owner_status'  => 1,
        ]);

        if (isset($request['manager']) && $request['manager'] !== null) {
            if ($currManager) {
                //current Manager Exists, update record
                $currManager->update([
                    'user_id'  => $request['manager']['id'],
                ]);
            } else {
                //current Manager doesnt exist, create new
                ManagerBoarding::create([
                    'boarding_id' => $request->id,
                    'user_id'  => $request['manager']['id'],
                ]);
            }
        } else if ($currManager) {
            //Current Manager Exists, Delete Record because empty request
            $currManager->delete();
        }


        //FILES
        if (($request->file('images') !== null)) {
            foreach ($request->file('images') as $image) {

                $path = $image->getClientOriginalName();
                $path = str_replace(" ", "-", $path);
                $path = time() . '-' . $path;
                $path = 'Boarding_House_Images/' . $path;
                $img = new BoardingImage();

                Storage::putFileAs('public/', $image, $path);
                $img->image = '/storage/' . $path;
                $img->boarding_id = $request->id;
                $img->save();
            }
        }

        return redirect('/boardingOwner')->with('message', 'Success Asking for Reapproval');
    }

    public function disableBoardingOwner(Request $request)
    {
        OwnerBoarding::where('boarding_id', '=', $request->id)->first()->update([
            'owner_status' => 'disabled',
        ]);
        return redirect('/boardingOwner')->with('message', 'Success Disabling Boarding House');
    }

    public function enableBoardingOwner(Request $request)
    {
        OwnerBoarding::where('boarding_id', '=', $request->id)->first()->update([
            'owner_status' => 'approved',
        ]);
        return redirect('/boardingOwner')->with('message', 'Success Enabling Boarding House');
    }
}
