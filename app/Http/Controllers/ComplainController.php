<?php

namespace App\Http\Controllers;

use App\Models\Boarding;
use App\Models\Complain;
use App\Models\ComplainType;
use App\Models\ManagerBoarding;
use App\Models\OwnerBoarding;
use App\Models\TenantBoarding;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ComplainController extends Controller
{
    public function getComplainListPage()
    {
        $complainList = Complain::where('user_id', Auth::user()->id)->get();

        foreach ($complainList as $key => $complain) {
            $complainList[$key]['boarding_house_name'] = Boarding::where('id', $complain->boarding_id)->first()->boarding_name;
            $complainList[$key]['complain_type_name'] = ComplainType::where('id', $complain->complain_type_id)->first()->complain_type_name;
        }

        return Inertia::render('Complain/ComplainList', [
            'complainList' => $complainList
        ]);
    }


    public function getCreateComplainPage()
    {
        return Inertia::render('Complain/CreateComplain', [
            'allComplainType' =>  ComplainType::all()->pluck('complain_type_name')
        ]);
    }

    public function createComplain(Request $request)
    {
        $validation = $request->validate([
            'complainType' => ['required', 'max:50'],
            'description' => ['required'],
        ]);

        if ($request->hasFile('pictureFile')) {
            $file = $request->pictureFile;
            $fileName = $file->getClientOriginalName();
            $destinationPath = storage_path('app/public/images');
            $file->move($destinationPath, $fileName);
        }

        Complain::create([
            'user_id' => Auth::user()->id,
            'boarding_id' => TenantBoarding::where('user_id', Auth::user()->id)->where('tenant_status', 'approved')->first()->boarding_id ?? null,
            'complain_type_id' => ComplainType::where('complain_type_name', $request->complainType)->first()->id,
            'complain_desc' => $request->description,
            'complain_image_url' => '/storage/images/' . ($fileName ?? 'CJ-GTASA.png')
        ]);

        return redirect('/complain');
    }

    public function getComplainDetail(Request $request)
    {
        $complain = Complain::find($request->id);
        $complainType = ComplainType::where('id', $complain->complain_type_id)->first()->complain_type_name;

        return Inertia::render('Complain/ComplainDetail', [
            'complainType' => $complainType,
            'complain' => $complain
        ]);
    }

    public function setComplainStatus(Request $request)
    {
        Complain::find($request->id)->update([
            'complain_status' => $request->status
        ]);
    }

    public function getOwnerComplainPage()
    {
        if (Auth::user()->user_role_id == 4) {
            $boardingHouseIDs = OwnerBoarding::where('boarding_id', ManagerBoarding::where('user_id', Auth::user()->id)->first()->boarding_id ?? -1)->where('owner_status', 'approved')->get()->pluck('boarding_id') ?? -1;
        } else if (Auth::user()->user_role_id == 3) {
            $boardingHouseIDs = OwnerBoarding::where('user_id', Auth::user()->id)->where('owner_status', 'approved')->get()->pluck('boarding_id');
        }

        $boardingHouses = Boarding::whereIn('id', $boardingHouseIDs)->get();

        foreach ($boardingHouses as $key => $boarding) {
            $complainCounts = Complain::where('boarding_id', $boarding->id)
                ->select('complain_status', DB::raw('COUNT(*) as count'))
                ->groupBy('complain_status')
                ->pluck('count', 'complain_status')
                ->toArray();
            $boardingHouses[$key]->complain_finished_count = $complainCounts['finished'] ?? 0;
            $boardingHouses[$key]->complain_on_progress_count = $complainCounts['on progress'] ?? 0;
            $boardingHouses[$key]->complain_pending_count = $complainCounts['pending'] ?? 0;
        }

        return Inertia::render('Complain/Owner/BoardingHouseList', ['boardingHouseList' => $boardingHouses]);
    }

    public function getSelectedBoardingHouseComplainList(Request $request)
    {
        $selectedBoardingHouseComplainList = Complain::where('boarding_id', $request->id)->get();

        foreach ($selectedBoardingHouseComplainList as $key => $complain) {
            $selectedBoardingHouseComplainList[$key]->complain_type_name = ComplainType::where('id', $complain->complain_type_id)->first()->complain_type_name;
            $selectedBoardingHouseComplainList[$key]->user_name = User::where('id', $complain->user_id)->first()->user_name;
        }

        return Inertia::render('Complain/Owner/SelectedBoardingHouseComplainList', ['complainList' => $selectedBoardingHouseComplainList]);
    }
}
