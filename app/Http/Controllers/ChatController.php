<?php

namespace App\Http\Controllers;

use App\Events\NewChatMessage;
use App\Models\Chat;
use App\Models\ManagerBoarding;
use App\Models\OwnerBoarding;
use App\Models\TenantBoarding;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ChatController extends Controller
{
    public function getChatPage()
    {
        $userRoleId = User::where('id', Auth::user()->id)->first()->user_role_id;
        $contactIDs = [];
        if($userRoleId == 1){ //Admin Support
            $adminSupportID = User::where('email','bhfms@gmail.com')->first()->id;
            $listHelps = Chat::selectRaw("DISTINCT CASE WHEN sender_id = {$adminSupportID} THEN receiver_id ELSE sender_id END AS id")
            ->where(function ($query) use ($adminSupportID) {
                $query->where('sender_id', $adminSupportID)
                    ->where('receiver_id', '!=', $adminSupportID)
                ->orWhere('receiver_id', $adminSupportID)
                    ->where('sender_id', '!=', $adminSupportID);
            })
            ->groupBy('id', 'sender_id', 'receiver_id')
            ->orderBy('created_at')
            ->get();
            foreach ($listHelps as $listHelp) {
                array_push($contactIDs, User::find($listHelp->id)->id ?? -1);
            }
        }elseif ($userRoleId == 2) { //tenant
            
            $boardingId = TenantBoarding::where('user_id', Auth::user()->id)->where('tenant_status', 'approved')->first()->boarding_id ?? -1;

            array_push($contactIDs, OwnerBoarding::where('boarding_id', $boardingId)->first()->user_id ?? -1);
            array_push($contactIDs, ManagerBoarding::where('boarding_id', $boardingId)->first()->user_id ?? -1);
            array_push($contactIDs, User::where('email','bhfms@gmail.com')->first()->id);
        } elseif ($userRoleId == 3) { //owner
            $boardingIDs = OwnerBoarding::where('user_id', Auth::user()->id)->where('owner_status', 'approved')->get()->pluck('boarding_id') ?? -1;

            foreach ($boardingIDs as $boardingId) {
                array_push($contactIDs, ...TenantBoarding::where('boarding_id', $boardingId)->where('tenant_status', 'approved')->get()->pluck('user_id') ?? -1);

                $managerId = ManagerBoarding::where('boarding_id', $boardingId)->first()->user_id ?? -1;
                in_array($managerId, $contactIDs) ? array_push($contactIDs, -1) : array_push($contactIDs, $managerId);
            }
            array_push($contactIDs, User::where('email','bhfms@gmail.com')->first()->id);
        } elseif ($userRoleId == 4) { //manager
            $boardingIDs = ManagerBoarding::where('user_id', Auth::user()->id)->get()->pluck('boarding_id') ?? -1;

            foreach ($boardingIDs as $boardingId) {
                array_push($contactIDs, ...TenantBoarding::where('boarding_id', $boardingId)->where('tenant_status', 'approved')->get()->pluck('user_id') ?? -1);

                $ownerId = OwnerBoarding::where('boarding_id', $boardingId)->where('owner_status', 'approved')->first()->user_id ?? -1;
                in_array($ownerId, $contactIDs) ? array_push($contactIDs, -1) : array_push($contactIDs, $ownerId);
            }
            array_push($contactIDs, User::where('email','bhfms@gmail.com')->first()->id);
        }
    
        $contactDetails = [];
        foreach ($contactIDs as $id) {
            if ($id == -1) {
                continue;
            }
            array_push($contactDetails, User::where('id', $id)->first());
        }

        return Inertia::render('Chat', ['contactDetails' => $contactDetails]);
    }

    public function getChatMessage(Request $request)
    {
        $messagesSent = Chat::where('sender_id', Auth::user()->id)->where('receiver_id', $request->id)->get();
        $messagesReceived = Chat::where('sender_id', $request->id)->where('receiver_id', Auth::user()->id)->get();

        $messages = $messagesSent->merge($messagesReceived)->sortBy('id')->values()->all();

        return response()->json([
            'messages' => $messages,
            'receiverDetails' => User::where('id', $request->id)->first()
        ]);
    }

    public function storeChatMessage(Request $request)
    {
        $message = Chat::create([
            'sender_id' => Auth::user()->id,
            'receiver_id' => $request->id,
            'message' => $request->message,
        ]);

        broadcast(new NewChatMessage($message))->toOthers();

        return back();
    }

    public function storeSpecificChatMessage(int $senderId, int $receiverId, String $message)
    {
        $message = Chat::create([
            'sender_id' => $senderId,
            'receiver_id' => $receiverId,
            'message' => $message,
        ]);

        broadcast(new NewChatMessage($message))->toOthers();

        return;
    }
}
