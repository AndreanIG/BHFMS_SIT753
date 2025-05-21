<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserRole;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function getProfilePage()
    {
        return inertia('EditProfile', [
            'userDetails' => Auth::user()
        ]);
    }

    public function updateProfile(Request $request)
    {
        $validation = $request->validate([
            'name' => ['required', 'max:50'],
            'email' => ['required', 'email','unique:users,email,' . Auth::user()->id],
            'dateOfBirth' => ['required'],
            'phoneNumber' => ['required', 'max:50'],
            'profilePicture' => ['nullable'], ['mimes:jpeg,png,jpg,gif,svg']
        ]);

        if ($request->hasFile('profilePicture')) {
            $file = $request->file('profilePicture');
            $fileName = $file->getClientOriginalName();
            $fileName = str_replace(" ", "-", $fileName);
            $fileName = time() . '-' . $fileName;
            $fileName = Auth::user()->id . $fileName;
            $fileName = 'images/' . $fileName;
            Storage::putFileAs('public/',$file, $fileName);
            $path_db = '/storage/' . $fileName;
        }

        User::findOrFail(Auth::user()->id)->update([
            'user_name' => $validation['name'],
            'email' => $validation['email'],
            'date_of_birth' => $validation['dateOfBirth'],
            'phone' => $validation['phoneNumber'],
            'profile_picture' => $path_db ?? null
        ]);

        return redirect('/profile');
    }

    public function getAllUserPage(Request $request){
        $users = User::when($request->searchQuery, function ($query, $searchQuery) {
            if ($searchQuery == '') {
                $query;
            } else {
                $query->where('user_name', 'like', '%'. $searchQuery . '%')
                ->orWhere('user_status', 'like', '%'. $searchQuery . '%')
                ->orWhere('email', 'like', '%'. $searchQuery . '%');
            }
        })->paginate(5)->withQueryString();

        return inertia('User/ListUser', [
            'users' => $users
        ]);
    }

    public function getUserDetail(Request $request){
        $currUser = User::find($request->id);
        $currRole = $currUser->role()->first()->user_role_name;
        return inertia('User/ReadUser', [
            'currUser' => $currUser,
            'currRole'=>$currRole,
        ]);
    }

    public function getUserUpdate(Request $request){
        $currUser = User::find($request->id);
        $currRole = $currUser->user_role_id;
        $allRole = UserRole::get();
        $currDOB = Carbon::parse($currUser->date_of_birth)->format('Y-m-d');
        return inertia('User/UpdateUser', [
            'currUser' => $currUser,
            'all_role'=>$allRole,
            'currRole'=>$currRole,
            'currDOB'=>$currDOB,
        ]);
    }

    public function UserUpdate(Request $request){
        $custom_messages = [
            'images.max' => 'Maximum number of image is 1 !',
        ];

        $validation = $request->validate([
            'name' => ['required', 'max:200', 'min:3'],
            'gender'=>['required'],
            'status'=>['required'],
            'user_role'=>['required'],
            'email'=>['required', 'email','unique:users,email,' . $request->id],
            'dob'=>['required'],
            'phone' => ['required'],
            'images'=> ['max:1'],
            'images.*' => ['mimes:jpeg,png,jpg,gif,svg'],
        ], $custom_messages);

        if (($request->file('images') !== null)) {
            $currImage = User::where('id','=',$request->id)->first()->profile_picture;
            $currImagePath = explode('/storage/', $currImage);

            if($currImage){
                Storage::delete('public/'.$currImagePath[1]);
            }
            $currUserId = User::where('id','=',$request->id)->first()->id;

            foreach ($request->file('images') as $image) {

                $path = $image->getClientOriginalName();
                $path = str_replace(" ", "-", $path);
                $path = time() . '-' . $path;
                $path = $currUserId . $path;
                $path = 'images/' . $path;

                Storage::putFileAs('public/',$image, $path);
                $pathDB = '/storage/' . $path;

            }
            User::where('id','=',$request->id)->update([
                'user_name' => $validation['name'],
                'gender'=>$validation['gender'],
                'user_status'=>$validation['status'],
                'user_role_id'=>$validation['user_role'],
                'email'=>$validation['email'],
                'date_of_birth'=>$validation['dob'],
                'phone' => $validation['phone'],
                'profile_picture' => $pathDB,
            ]);

        }else{
            User::where('id','=',$request->id)->update([
                'user_name' => $validation['name'],
                'gender'=>$validation['gender'],
                'user_status'=>$validation['status'],
                'user_role_id'=>$validation['user_role'],
                'email'=>$validation['email'],
                'date_of_birth'=>$validation['dob'],
                'phone' => $validation['phone'],
            ]);
        }

        return redirect('/userAll')->with('message', 'Success Updating User');
    }

    public function UserDelete(Request $request){
        $currImage = User::where('id','=',$request->id)->first();
        $currImage->profile_picture == null ? null :$currImage_path = explode('/storage/', $currImage->profile_picture);

        if($currImage->profile_picture != null){
            Storage::delete('public/'.$currImage_path[1]);
        }

        User::where('id','=',$request->id)->update([
            'user_status'=>"banned",
        ]);

        return redirect('/userAll')->with('message', 'Success Deleting / Banning User');
    }
}
