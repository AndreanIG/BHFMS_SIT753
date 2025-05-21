<?php

namespace App\Http\Controllers;

use App\Models\Boarding;
use App\Models\Chat;
use App\Models\ManagerBoarding;
use App\Models\OwnerBoarding;
use App\Models\TenantBoarding;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class TenantController extends Controller
{
    public function getAllTenantBoarding(Request $request){
        // if Owner
        if(Auth::user()->user_role_id==3){
            $Tenant_data = TenantBoarding::
                join('users', 'users.id', '=', 'tenant_boardings.user_id')
                ->join('boardings', 'boardings.id', '=', 'tenant_boardings.boarding_id')
                ->join('owner_boardings', 'boardings.id', '=', 'owner_boardings.boarding_id')
                ->select('users.user_name','boardings.boarding_name','tenant_boardings.id','tenant_boardings.tenant_status','boardings.rooms','boardings.id AS boardingID')
                ->where('owner_boardings.user_id','=',auth()->id())
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
                        $query->where('boarding_name', 'like', '%'. $searchQuery . '%')
                        ->orWhere('owner_status', 'like', '%'. $searchQuery . '%')
                        ->orWhere('user_name', 'like', '%'. $searchQuery . '%');
                    }
                })->paginate(5)->withQueryString();
            
            foreach ($Tenant_data as $key => $tenantData) {
                $Tenant_data[$key]->capacity = $tenantData->rooms - (TenantBoarding::where([['boarding_id','=', $tenantData->boardingID]])->whereIn('tenant_status', ['approved', 'pending_payment'])->count());
            }
            
            $all_boarding_count = TenantBoarding::join('boardings', 'boardings.id', "=", 'tenant_boardings.boarding_id')
            ->join('owner_boardings','owner_boardings.boarding_id',"=",'boardings.id')
            ->select('tenant_status', DB::raw('count(*) as total'))
            ->groupBy('tenant_status')
            ->where('owner_boardings.user_id','=',auth()->id())->get()->toArray();
        }
        // if Manager
        else if(Auth::user()->user_role_id==4){
            $Tenant_data = TenantBoarding::
            join('users', 'users.id', '=', 'tenant_boardings.user_id')
            ->join('boardings', 'boardings.id', '=', 'tenant_boardings.boarding_id')
                ->join('manager_boardings', 'boardings.id', '=', 'manager_boardings.boarding_id')
                ->select('users.user_name','boardings.boarding_name','tenant_boardings.id','tenant_boardings.tenant_status','boardings.rooms','boardings.id AS boardingID')
                ->where('manager_boardings.user_id','=',auth()->id())
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
                        $query->where('boarding_name', 'like', '%'. $searchQuery . '%')
                        ->orWhere('owner_status', 'like', '%'. $searchQuery . '%')
                        ->orWhere('user_name', 'like', '%'. $searchQuery . '%');
                    }
                })->paginate(5)->withQueryString();
            
            foreach ($Tenant_data as $key => $tenantData) {
                $Tenant_data[$key]->capacity = $tenantData->rooms - (TenantBoarding::where([['boarding_id','=', $tenantData->boardingID]])->whereIn('tenant_status', ['approved', 'pending_payment'])->count());
            }
            
            $all_boarding_count = TenantBoarding::join('boardings', 'boardings.id', "=", 'tenant_boardings.boarding_id')
            ->join('manager_boardings','manager_boardings.boarding_id',"=",'boardings.id')
            ->select('tenant_status', DB::raw('count(*) as total'))
            ->groupBy('tenant_status')
            ->where('manager_boardings.user_id','=',auth()->id())->get()->toArray();
        }

        $all = 0;
        $apv = 0;
        $dcl = 0;
        $pending = 0;
        $pendingPayment = 0;
        $done = 0;

        foreach($all_boarding_count as $count => $collection) {
            if($all_boarding_count[$count]["tenant_status"] == "pending"){
                $all+= $all_boarding_count[$count]["total"]; 
                $pending = $all_boarding_count[$count]["total"];
            } 
                
            elseif($all_boarding_count[$count]["tenant_status"] == "approved"){
                $all+= $all_boarding_count[$count]["total"]; 
                $apv = $all_boarding_count[$count]["total"];

            }

            elseif($all_boarding_count[$count]["tenant_status"] == "declined"){
                $all+= $all_boarding_count[$count]["total"]; 
                $dcl = $all_boarding_count[$count]["total"];
            }

            elseif($all_boarding_count[$count]["tenant_status"] == "checkout"){
                $all+= $all_boarding_count[$count]["total"]; 
                $done = $all_boarding_count[$count]["total"];
            }

            elseif($all_boarding_count[$count]["tenant_status"] == "pending_payment"){
                $all+= $all_boarding_count[$count]["total"]; 
                $pendingPayment = $all_boarding_count[$count]["total"];
            }
        }

        return Inertia::render('Tenant/TenantList', [
            'all_count' => $all,
            'approved' => $apv,
            'pending' => $pending,
            'declined' => $dcl,
            'done' => $done,
            'pendingPayment' => $pendingPayment,
            'users' => $Tenant_data,
        ]);
    }

    public function getDetailTenantBoarding(Request $request){
        $tenantBoarding = TenantBoarding::find($request->id);
        $user_data = User::where("id",$tenantBoarding->user_id)->first();
        $boardingHouseRoom = Boarding::where('id',$tenantBoarding->boarding_id)->first()->rooms;
        $capacity = $boardingHouseRoom - (TenantBoarding::where([['boarding_id','=', $tenantBoarding->boarding_id]])->whereIn('tenant_status', ['approved', 'pending_payment'])->count());
        return Inertia::render('Tenant/ReadTenant', [
            'reason' => $tenantBoarding->declined_reason,
            'tenantBoarding' => $tenantBoarding,
            'currUser' => $user_data,
            'capacity' =>$capacity,
        ]);
    }

    public function getRequestTenant(Request $request){
        $tenantBoarding = TenantBoarding::find($request->id);
        $user_data = User::where("id",$tenantBoarding->user_id)->first();
        $boardingHouseRoom = Boarding::where('id',$tenantBoarding->boarding_id)->first()->rooms;
        $capacity = $boardingHouseRoom - (TenantBoarding::where([['boarding_id','=', $tenantBoarding->boarding_id]])->whereIn('tenant_status', ['approved', 'pending_payment'])->count());
        
        return Inertia::render('Tenant/RequestTenant', [
            'currID' => $request->id,
            'currUser' => $user_data,
            'capacity' =>$capacity,
        ]);
    }

    public function RequestTenant(Request $request){
        $accFirst = TenantBoarding::where('id','=',$request->currID)->first();
        $checkRoom = Boarding::where('id','=',$accFirst->boarding_id)->first()->rooms;
        $checkTenant = TenantBoarding::where('boarding_id', $accFirst->boarding_id)
                        ->whereIn('tenant_status', ['approved', 'pending_payment'])
                        ->count();
        if($checkRoom - $checkTenant <= 0 ){
            return redirect('/tenantBoarding')->with('message', 'Cannot Accept new Tenant as room is full');
        }

        if($request->accept){
            //Down Payment
            return redirect('/downPayment'.'?tenantBoarding='.$accFirst->id);
            
        }else{
            TenantBoarding::where('id','=',$request->currID)->update([
                'tenant_status' => 'declined',
                'declined_reason' => $request['reason'],
            ]);
            return redirect('/tenantBoarding')->with('message', 'Success Declining new Tenant');
        }

    }
    public function acceptTenant($tenantId) {
            

            $tenant = TenantBoarding::find($tenantId);
            $tenant->update([
                'tenant_status' => 'approved',
                // 'declined_reason' => $request['reason'],
            ]);
            $checkRoom = Boarding::where('id','=',$tenant->boarding_id)->first()->rooms;
            $checkTenant = TenantBoarding::where([['boarding_id','=',$tenant->boarding_id],['tenant_status','=','approved']])->count();
        
            //Decline all Current User Accepted Tenant Boarding
            $update = TenantBoarding::where('user_id','=',$tenant->user_id)->where('tenant_status','=','pending')->update([
                'tenant_status' => 'declined',
                'declined_reason' => 'Already Accepted in another Boarding House.',
            ]);

            //Decline all other request if room is full
            if($checkRoom - $checkTenant -1 <= 0 ){
                $update = TenantBoarding::where('boarding_id','=',$tenant->boarding_id)->where('tenant_status','=','pending')->update([
                    'tenant_status' => 'declined',
                    'declined_reason' => 'The Boarding House you requested is currently at maximum capacity as other tenants have been accepted first.',
                ]);
            }
    }
    public function getEndRentBoarding(Request $request){
        $tenantBoarding = TenantBoarding::where('id','=',$request->id)->first();
        $minEndDate = Carbon::createFromFormat('Y-m-d', $tenantBoarding->start_date)->addMonth(1)->format('Y-m-d');
        $minEndDate = $minEndDate > Carbon::now()->addWeek(1)->format('Y-m-d') ? $minEndDate: Carbon::now()->addWeek(1)->format('Y-m-d');
        return Inertia::render('Boarding/EndBoardingRent', [
            'tenantBoarding' => $tenantBoarding,
            'minEndDate' => $minEndDate,
        ]);

    }

    public function endRentBoarding(Request $request){
        
        $validation = $request->validate([
            'start_date' => ['required'],
            'end_date' => ['required'],
        ]);

        $startDateCarbon = Carbon::createFromFormat('Y-m-d', $request->start_date);
        $endDateCarbon = Carbon::createFromFormat('Y-m-d', $request->end_date)->format('D, d M Y');


        $tenantNow = TenantBoarding::where('id','=',$request->id)->first(); 
        $ownerID = OwnerBoarding::where('boarding_id','=',$tenantNow->boarding_id)->first()->user_id;
        $managerID = ManagerBoarding::where('boarding_id','=',$tenantNow->boarding_id)->first()->user_id ?? -1; 

        $tenantNow->update([
            'end_date' => $request->end_date,
        ]);

        Chat::create([
            'sender_id' => $tenantNow->user_id,
            'receiver_id' => $ownerID,
            'message' => 'This is an automated Message ! the user have asked to end the rent at '. $endDateCarbon,
        ]);

        if($managerID !== -1){
            Chat::create([
                'sender_id' => $tenantNow->user_id,
                'receiver_id' => $managerID,
                'message' => 'This is an automated Message ! the user have asked to end the rent at '. $endDateCarbon,
            ]);
        }
        return redirect('/boardingTenant')->with('message', 'Success Ending Rent of Current Boarding House');
    }
}
