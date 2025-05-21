<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use App\Models\FacilityDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FacilityController extends Controller
{
    public function getAllFacilityPage(Request $request){
        $facilities = FacilityDetail::when($request->searchQuery, function ($query, $searchQuery) {
            if ($searchQuery == '') {
                $query;
            } else {
                $query->where('facility_detail_name', 'like', '%'. $searchQuery . '%');
            }
        })->paginate(5)->withQueryString();
        return inertia('Facility/ListFacility', [
            'facilities' => $facilities
        ]);
    }

    public function getFacilityCreate(){
        return inertia('Facility/CreateFacility', [
        ]);
    }

    public function FacilityCreate(Request $request){
        $custom_messages = [
            'images.max' => 'Maximum number of image is 1 !',
            'images.min' => 'Minimum number of image is 1 !',
        ];
        $validation = $request->validate([
            'name' => ['required', 'max:200', 'min:3','unique:facility_details,facility_detail_name,'],
            'images' => ['max:1','min:1'],
            'images.*' => ['mimes:jpeg,png,jpg,gif,svg'],
        ], $custom_messages);

        //FILE
        if (($request->file('images') !== null)) {
            foreach ($request->file('images') as $image) {

                $path = $image->getClientOriginalName();
                $path = str_replace(" ", "-", $path);
                $path = time() . '-' . $path;
                $path = 'Facility_Images/' . $path;

                Storage::putFileAs('public/',$image, $path);
                $path_in_db = '/storage/' . $path;

            }
        }

        FacilityDetail::create([
            'facility_detail_name' => $request->name,
            'facility_img_path' => $path_in_db,
        ]);

        return redirect('/facilityAll')->with('message', 'Success Creating new Facility');
    }

    public function getFacilityUpdate(Request $request){
        $currFacility = FacilityDetail::find($request->id);
        return inertia('Facility/UpdateFacility', [
            'facility' => $currFacility,
        ]);
    }

    public function FacilityUpdate(Request $request){

        $custom_messages = [
            'images.max' => 'Maximum number of image is 1 !',
        ];

        $validation = $request->validate([
            'name' => ['required', 'max:200', 'min:3','unique:facility_details,facility_detail_name,' . $request->id],
            'images' => ['max:1'],
            'images.*' => ['mimes:jpeg,png,jpg,gif,svg'],
        ], $custom_messages);

        if (($request->file('images') !== null)) {
            $currImage = FacilityDetail::where('id','=',$request->id)->first()->facility_img_path;
            $currImage_path = explode('/storage/', $currImage);

            if($currImage){
                Storage::delete('public/'.$currImage_path[1]);
            }

            foreach ($request->file('images') as $image) {

                $path = $image->getClientOriginalName();
                $path = str_replace(" ", "-", $path);
                $path = time() . '-' . $path;
                $path = 'Facility_Images/' . $path;

                Storage::putFileAs('public/',$image, $path);
                $path_in_db = '/storage/' . $path;

            }
            FacilityDetail::where('id','=',$request->id)->update([
                'facility_detail_name' => $request->name,
                'facility_img_path' => $path_in_db,
            ]);

        }else{
            FacilityDetail::where('id','=',$request->id)->update([
                'facility_detail_name' => $request->name,
            ]);
        }

        return redirect('/facilityAll')->with('message', 'Success Updating Facility');
    }

    public function FacilityDelete(Request $request){
        $currImage = FacilityDetail::where('id','=',$request->id)->first();
        $currImage_path = explode('/storage/', $currImage->facility_img_path);

        if($currImage){
            Storage::delete('public/'.$currImage_path[1]);
        }

        $currImage->delete();

        return redirect('/facilityAll')->with('message', 'Success Deleting Facility');
    }
}
