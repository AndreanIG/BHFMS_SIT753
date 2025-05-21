<?php

namespace App\Http\Controllers;

use App\Models\ComplainType;
use Illuminate\Http\Request;

class ComplainTypeController extends Controller
{
    public function getAllComplainTypePage(Request $request){
        $complainTypes = ComplainType::when($request->searchQuery, function ($query, $searchQuery) {
            if ($searchQuery == '') {
                $query;
            } else {
                $query->where('complain_type_name', 'like', '%'. $searchQuery . '%');
            }
        })->paginate(5)->withQueryString();
        return inertia('ComplainType/ListComplainType', [
            'complainTypes' => $complainTypes
        ]);
    }

    public function getComplainTypeCreate(){
        return inertia('ComplainType/CreateComplainType', [
        ]);
    }

    public function ComplainTypeCreate(Request $request){
        $validation = $request->validate([
            'name' => ['required', 'max:200', 'min:3','unique:complain_types,complain_type_name,'],
        ]);

        ComplainType::create([
            'complain_type_name' => $request->name,
        ]);

        return redirect('/complainTypeAll')->with('message', 'Success Creating new complain Type');
    }

    public function getComplainTypeUpdate(Request $request){
        $currComplainType = ComplainType::find($request->id);
        return inertia('ComplainType/UpdateComplainType', [
            'complainType' => $currComplainType,
        ]);
    }

    public function ComplainTypeUpdate(Request $request){

        $validation = $request->validate([
            'name' => ['required', 'max:200', 'min:3','unique:complain_types,complain_type_name,' . $request->id],
        ]);

        ComplainType::where('id','=',$request->id)->update([
            'complain_type_name' => $request->name,
        ]);

        return redirect('/complainTypeAll')->with('message', 'Success Updating complain Type');
    }

    public function ComplainTypeDelete(Request $request){
        $currComplainType = ComplainType::where('id','=',$request->id)->first();

        $currComplainType->delete();

        return redirect('/complainTypeAll')->with('message', 'Success Deleting ComplainType');
    }
}
