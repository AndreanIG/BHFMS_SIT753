<?php

namespace App\Http\Controllers;

use App\Models\Boarding;
use App\Models\ManagerBoarding;
use App\Models\OwnerBoarding;
use App\Models\PaymentMethod;
use App\Models\RentTransaction;
use App\Models\TenantBoarding;
use App\Models\TransactionType;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class PaymentController extends Controller
{
    const PAGINATION_NUMBER = 5;
    public function addPaymentTenant(Request $request)
    {
        $transaction = RentTransaction::where('invoice_id',$_GET['order'])->first();
        
        $validation = $request->validate([
            'paymentMethod' => ['required'],
            'proofOfPayment' => ['required', 'image']
        ]);
        
        $fileName = date('Ymd', strtotime($transaction->payment_date)) . $_GET['order'] . '.png';
        $validation['proofOfPayment']->move(storage_path('app/public/proofOfPayment'), $fileName);
        
        $transaction->payment_method_id = PaymentMethod::where('payment_method_name',$validation['paymentMethod'])->first()->id;
        $transaction->payment_status = 'Processing';
        $transaction->save();

        return redirect('/paymentHistory')->with('message','Invoice has been paid !');
    }

    public function addPaymentManager(Request $request)
    {
        $customMessage = [
            'tenantEmail.required' => 'Please select tenant',
        ];
        
        $validation = $request->validate([
            'paymentDate' => ['required'],
            'paymentAmount' => ['required', 'numeric','min:10000'],
            'tenantEmail' => ['required'],
            'transactionType' => ['required']
        ], $customMessage);
        RentTransaction::create([
            'tenant_boarding_id'=>$this->getTenantIdByEmail($validation['tenantEmail'],$_GET['boarding'])->id,
            'transaction_type_id' => TransactionType::where('transaction_type_name',$validation['transactionType'])->first()->id,
            'invoice_id' => $this->generateInvoice($validation['paymentDate']),
            'amount' => $validation['paymentAmount'] +  rand(0,1000),
            'payment_date' => $validation['paymentDate'],
            'repeat_payment'=> $request['paymentRepeat'] == 'true' ?  true: false,
        ]);   
        
        if(Auth::user()->user_role_id == 3) {
            return redirect('/boardingOwner')->with('message', 'Payment has been made !'); 
        }
        
        return redirect('/boardingManager')->with('message', 'Payment has been made !');

    }

    public function addDownPayment(Request $request)
    {
        $validation = $request->validate([
            'paymentDate' => ['required'],
            'paymentAmount' => ['required', 'numeric','min:10000'],
            'transactionType' => ['required'],
            'tenantEmail' => ['required'],
            'boardingId' => ['required']
        ]);
        $tenantId = $_GET['tenantBoarding'];
        RentTransaction::create([
            'tenant_boarding_id'=>$tenantId,
            'transaction_type_id' => TransactionType::where('transaction_type_name',$validation['transactionType'])->first()->id,
            'invoice_id' => $this->generateInvoice($validation['paymentDate']),
            'amount' => $validation['paymentAmount'] +  rand(0,1000),
            'payment_date' => $validation['paymentDate'],
            'repeat_payment'=> false,
        ]);   
        TenantBoarding::find($tenantId)->update([
            'tenant_status' => 'pending_payment'
        ]);
        return redirect('/tenantBoarding')->with('message', 'Down Payment has been made !');
        
    }
    public function getPaymentPageManager()
    {
        return Inertia::render('Payment/PaymentPageManager', [
            'listTenants' => $this->getAllTenants($_GET['boarding']),
            'boardingHouseName' => $this->getBoardingHouseName($_GET['boarding']),
            'transactionTypes' => $this->getTransactionTypeName(),
        ]);
    }

    public function getCheckInvoiceRequest(){
        $userRole = Auth::user()->user_role_id;
        return Inertia::render('Payment/PaymentCheckInvoice', [
            'userRole' => $userRole,
            'invoiceList' => $this->getInvoiceListProcessing(),
        ]); 
    }

    public function getInvoiceListProcessing(){
        return RentTransaction::where('payment_status','Processing')
        ->paginate(self::PAGINATION_NUMBER);
    }

 
    public function getAllPayment()
    {
        $userRole = Auth::user()->user_role_id;
        $paymentList = [];
        
        if ($userRole == 2) {
            $paymentList = $this->getListPaymentByUser(Auth::user()->id);
        } elseif ($userRole == 3 || $userRole == 4) {
            $boardingId = isset($_GET['boarding']) && $_GET['boarding'] !== 'null' ? $_GET['boarding'] : null;
            if ($boardingId) {
                $paymentList = $this->getListPaymentByBoardingId($boardingId);
            } else if($userRole == 3) { //Owner History
                $boardingIDs = OwnerBoarding::where('user_id',Auth::user()->id)
                ->where('owner_status', '!=', 'declined')
                ->pluck('boarding_id');
                $paymentList = RentTransaction::join('tenant_boardings', 'tenant_boardings.id', '=', 'tenant_boarding_id')
                ->join('users','users.id','=','tenant_boardings.user_id')
                ->whereIn('tenant_boardings.boarding_id',$boardingIDs)
                ->paginate(self::PAGINATION_NUMBER);
            } else if($userRole == 4) { // Manager History
                $boardingIDs = ManagerBoarding::where('user_id',Auth::user()->id)
                ->pluck('boarding_id');
                $paymentList = RentTransaction::join('tenant_boardings', 'tenant_boardings.id', '=', 'tenant_boarding_id')
                ->join('users','users.id','=','tenant_boardings.user_id')
                ->whereIn('tenant_boardings.boarding_id',$boardingIDs)
                ->paginate(self::PAGINATION_NUMBER);
            }
        }
        return Inertia::render('Payment/PaymentHistory', [
            'userRole' => $userRole,
            'paymentList' => $paymentList,
        ]);
    }

    public function getInvoiceDetail(Request $request)
    {
        $invoiceId = json_decode($request->getContent())->invoice_id;
        $transactionDetail = RentTransaction::join('transaction_types','transaction_type_id','=','transaction_types.id')
        ->where("invoice_id",$invoiceId)
        ->first();
        $tenantBoarding = $this->getTenantBoarding($transactionDetail->tenant_boarding_id);
        $username = User::where('id',$tenantBoarding->user_id)->value('user_name');
        $boradingName = Boarding::where('id',$tenantBoarding->boarding_id)->value('boarding_name');
        return [$transactionDetail->amount,$transactionDetail,$username,$boradingName];
    }

    public function getEditPayment()
    {
        $transaction = RentTransaction::where('invoice_id', $_GET['order'])->first();
        if($transaction->payment_status != 'Pending'){
            redirect('/paymentHistory')->with('message','Invoice cannot be edited !');
        }

        $tenantBoarding = $this->getTenantBoarding($transaction->tenant_boarding_id);
        $transaction->amount = floor($transaction->amount / 1000) * 1000;

        if(TransactionType::find($transaction->transaction_type_id)->transaction_type_name == 'Down Payment') {

            return Inertia::render('Payment/PaymentPageManagerSingle', [
                'tenant' => User::find($tenantBoarding->user_id),
                'boardingHouseName' =>  $this->getBoardingHouseName($tenantBoarding->boarding_id),
                'transactionType' => 'Down Payment',
                'boardingId' => $tenantBoarding->boarding_id,
                'transaction' => $transaction
            ]);
        }
        return Inertia::render('Payment/PaymentPageManager', [
            'transaction' => $transaction,
            'listTenants' => $this->getAllTenants($tenantBoarding->boarding_id),
            'boardingHouseName' => $this->getBoardingHouseName($tenantBoarding->boarding_id),
            'tenant' => $this->getTenantById($tenantBoarding->user_id),
            'transactionTypes' => $this->getTransactionTypeName(),
        ]);
    }

    public function getDownPayment()
    {   
        $tenantBoarding = $this->getTenantBoarding($_GET['tenantBoarding']);
        return Inertia::render('Payment/PaymentPageManagerSingle', [
            'tenant' => User::find($tenantBoarding->user_id),
            'boardingHouseName' =>  $this->getBoardingHouseName($tenantBoarding->boarding_id),
            'transactionType' => 'Down Payment',
            'boardingId' => $tenantBoarding->boarding_id
        ]);
    }

    public function getPaymentPageTenant()
    {
        $paymentDetail = RentTransaction::join('tenant_boardings','tenant_boardings.id','=','tenant_boarding_id')
            ->join('boardings','boardings.id','=','tenant_boardings.boarding_id')
            ->where("invoice_id",$_GET['order'])
            ->whereIn('payment_status', ['Pending','Late','Rejected'])
            ->first();

        if(!$paymentDetail){//No data
            return redirect('/paymentHistory');
        }
        return Inertia::render('Payment/PaymentPageTenant', [
            'listPaymentMethod' => PaymentMethod::where("status","available")->pluck('payment_method_name'),
            'paymentDetail' => $paymentDetail,
        ]);
    }

    public function getPaymentRemit()
    {
        $userRole = Auth::user()->user_role_id;
        $paymentList = RentTransaction::join('tenant_boardings', 'tenant_boardings.id', '=', 'tenant_boarding_id')
        ->join('owner_boardings','owner_boardings.boarding_id','=','tenant_boardings.boarding_id')
        ->join('users','users.id','owner_boardings.user_id')
        ->where('payment_status','Approved')
        ->where('payment_transferred_status','Pending')
        ->paginate(self::PAGINATION_NUMBER);
        return Inertia::render('Payment/PaymentRemit',[
            'userRole' => $userRole,
            'invoiceList' => $paymentList,
        ]);
    }
    public function getPaymentSupport()
    {
        $userRole = Auth::user()->user_role_id;
        $paymentList = RentTransaction::join('tenant_boardings', 'tenant_boardings.id', '=', 'tenant_boarding_id')
        ->join('boardings','boardings.id','=','tenant_boardings.boarding_id')
        ->where(function ($query) {
            $query->where('payment_transferred_status', 'Processing_Refund')
                ->where('payment_status', 'Canceled');
        })->orWhere('payment_transferred_status', 'Declined')
        ->paginate(self::PAGINATION_NUMBER);
        return Inertia::render('Payment/PaymentSupport',[
            'userRole' => $userRole,
            'paymentList' => $paymentList,
        ]);
    }
    
    public function cancelPayment()
    {
        $transaction = RentTransaction::where('invoice_id', $_GET['order'])->first();
    
        if ($transaction->payment_status === 'Canceled') {
            return redirect('/paymentHistory?boarding=' . $_GET['boarding']);
        }

        if (TransactionType::find($transaction->transaction_type_id)->transaction_type_name == 'Down Payment') {
            return redirect('/paymentHistory?boarding=' . $_GET['boarding'])->with('message','Cannot cancel Down Payment !');
        }
        
        switch ($transaction->payment_status) {
            case 'Processing': //The tenant have paid and still being processed
                $transaction->payment_transferred_status = 'Processing_Refund'; //Return to Tenant
                break;
            case 'Approved': //Owner have recieve the money
                if ($transaction->payment_transferred_status === 'Successful') { //Admin have transferred to the owner 
                    break;
                    // $transaction->payment_transferred_status = 'Owner_Refund';
                    // session()->flash('message','Payment has been transferred to Owner cannot be canceled');
                } else { //Admin have not transferred to owner
                    $chatController = new ChatController;
                    $chatController->storeSpecificChatMessage(User::where('email','bhfms@gmail.com')->first()->id,
                    $this->getTenantBoarding($transaction->tenant_boarding_id)->user_id,
                    'Please send your Bank name and Account Number for refund on this transaction - '.$transaction->invoice_id);
                    $transaction->payment_transferred_status = 'Processing_Refund'; //Return to Tenant
                    $transaction->payment_status = 'Canceled';
                }
                break;
            case 'Rejected':
                $transaction->payment_status = 'Canceled';
                break;
            case 'Pending':
                $transaction->payment_status = 'Canceled';
                break;
            default:
                break;
        }
        
        $transaction->save();
        return redirect('/paymentHistory?boarding=' . $_GET['boarding'])->with('message','Invoice has been canceled !');
    }

    public function updateInvoiceStatus(Request $request) 
    {   
        $msg = 'An error has occurred please contact your administrator';
        
        $invoiceStatus = $request->invoiceStatus;
        $invoiceId = $request->invoiceID;
        $transaction = RentTransaction::where('invoice_id',$invoiceId)->first();
        $transaction->payment_status = $invoiceStatus;
        $msg = 'Payment invoice: '.$transaction->invoice_id.', has been '.$invoiceStatus;
        if($invoiceStatus == 'Approved' && TransactionType::find($transaction->transaction_type_id)->transaction_type_name == 'Down Payment') {
            $tenantController = new TenantController;
            $tenantController->acceptTenant($transaction->tenant_boarding_id);
            $msg = 'Payment invoice: '.$transaction->invoice_id.', has been approved';
        }
        if($invoiceStatus == 'Approved' && $transaction->payment_transferred_status=='Processing_Refund'){
            $chatController = new ChatController;
            $chatController->storeSpecificChatMessage(User::where('email','bhfms@gmail.com')->first()->id,
            $this->getTenantBoarding($transaction->tenant_boarding_id)->user_id,
            'Please send your Bank name and Account Number for refund on this transaction - '.$transaction->invoice_id);
            $transaction->payment_status = 'Canceled';
            $msg = 'Payment invoice: '.$transaction->invoice_id.', has been approved';
        }
        if($invoiceStatus == 'Rejected' && Carbon::parse($transaction->payment_date)->lt(Carbon::today()->timezone('Asia/Jakarta')->startOfDay())){
            $transaction->payment_date = Carbon::today()->timezone('Asia/Jakarta')->startOfDay()->addDay()->toDateString();
            $msg = 'Payment invoice: '.$transaction->invoice_id.', has been rejected';
        }
        if($invoiceStatus == 'Rejected' && $request->reason!=null) {
            $chatController = new ChatController;
            $chatController->storeSpecificChatMessage(User::where('email','bhfms@gmail.com')->first()->id,
            $this->getTenantBoarding($transaction->tenant_boarding_id)->user_id,
            $request->reason);
            $msg = 'Payment invoice: '.$transaction->invoice_id.', has been rejected';
        }
        $transaction->save();
        return response()->json(['message' => $msg]);
    }

    public function updatePayment(Request $request) 
    {
        $custom_messages = [
            'tenantEmail.required' => 'Please select tenant',
        ];
        $validation = $request->validate([
            'paymentDate' => ['required'],
            'paymentAmount' => ['required', 'numeric','min:10000'],
            'tenantEmail' => ['required'],
            'transactionType' => ['required']
        ], $custom_messages);
 
        $transaction = RentTransaction::where('invoice_id',$_GET['order'])->first();
        if($transaction->payment_status != 'Pending'){
            redirect('paymentHistory');
        }
        $boardingId = $this->getTenantBoarding($transaction->tenant_boarding_id)->boarding_id;
        $transaction->update([
            'tenant_boarding_id'=>$this->getTenantIdByEmail($validation['tenantEmail'],$boardingId)->id,
            'transaction_type_id' => TransactionType::select('id')->where('transaction_type_name',$validation['transactionType'])->first()->id,
            'amount' => $validation['paymentAmount'] +  rand(0,1000),
            'payment_date' => $validation['paymentDate'],
            'repeat_payment'=> $request['paymentRepeat'] == 'true' ?  true: false,
        ]);

        return redirect('/paymentHistory?boarding='.$boardingId)->with('message','Invoice has been edited !');
    }
    public function editDownPayment(Request $request) {
        $validation = $request->validate([
            'paymentDate' => ['required'],
            'paymentAmount' => ['required', 'numeric','min:10000'],
            'transactionType' => ['required'],
            'tenantEmail' => ['required'],
            'boardingId' => ['required']
        ]);
        $transaction = RentTransaction::where('invoice_id',$_GET['order'])->first();
        
        $transaction->update([
            'amount' => $validation['paymentAmount'] +  rand(0,1000),
            'payment_date' => $validation['paymentDate']
        ]);  

        $boardingId = $this->getTenantBoarding($transaction->tenant_boarding_id)->boarding_id;
        
        return redirect('/paymentHistory?boarding='.$boardingId)->with('message','Invoice has been edited !');
    }
    public function updateTransferredStatus(Request $request)
    {
        $status = $request->invoiceStatus;
        $transaction = RentTransaction::where('invoice_id',$request->invoiceID)->first();
        $transaction->payment_transferred_status = $status;
        $msg = 'An error has occurred please contact your administrator';
        if($status=='Successful') {
            if($request->hasFile('image')){
                $file = $request->file('image');
                $file->move(storage_path('app/public/proofOfTransferred'), $transaction->invoice_id.'.'.$file->getClientOriginalExtension());
            }
            if(TransactionType::find($transaction->transaction_type_id)->transaction_type_name=='Down Payment') {
                $tenantController = new TenantController;
                $tenantController->acceptTenant($transaction->tenant_boarding_id);
            }
            $msg = 'Payment invoice: '.$transaction->invoice_id.', has been remitted';
        }
        if($status == 'Declined') {
            $chatController = new ChatController;
            $chatController->storeSpecificChatMessage(User::where('email','bhfms@gmail.com')->first()->id,
            $this->getTenantBoarding($transaction->tenant_boarding_id)->user_id,
            $request->reason);
            $msg = 'Payment invoice: '.$transaction->invoice_id.', has been rejected';
        
        }
        $transaction->save();
        return response()->json(['message' => $msg]);
    } 

    private function generateInvoice(String $date)
    {
        $year = Carbon::createFromDate($date)->format('y') * 10000000;
        $month = Carbon::createFromDate($date)->format('m') * 1000000;
        $day = Carbon::createFromDate($date)->format('d') * 10000;
        $random = rand(0, 10000);
        $invoice_id = $year + $month + $day + $random;

        while (RentTransaction::where('invoice_id', $invoice_id)->first()) {
            $random = rand(0, 10000);
            $invoice_id = $year + $month + $day + $random;
        }

        return $invoice_id;
    }

    private function getBoardingHouseName(int $boardingId)
    {
        return Boarding::find($boardingId)->boarding_name;
    }
    
    private function getTransactionTypeName()
    {
        return TransactionType::pluck('transaction_type_name');
    }

    private function getAllTenants(int $boardingId)
    {
        return TenantBoarding::select('users.user_name','users.email')
        ->join('users','users.id','=','tenant_boardings.user_id')
        ->where('boarding_id',$boardingId)
        ->where('tenant_status','approved')
        ->get();
    }

    private function getTenantBoarding(int $tenantBoarding)
    {
        return TenantBoarding::find($tenantBoarding);
    }

    private function getTenantIdByEmail(String $email, int $boardingId)
    {
        return TenantBoarding::whereIn('user_id',function($query) use ($email){
                    $query->select('id')
                    ->from('users')
                    ->where('email',$email);})
                ->where('boarding_id',$boardingId)
                ->orderBy('id', 'desc')
                ->first();
    }

    private function getListPaymentByUser(int $userId)
    {
        
        return RentTransaction::select('rent_transactions.id', 'payment_status', 'payment_date', 'boarding_name', 'invoice_id')
        ->join('tenant_boardings', 'tenant_boardings.id', '=', 'tenant_boarding_id')
        ->join('boardings', 'boardings.id', '=', 'boarding_id')
        ->whereIn('tenant_boarding_id', function ($query) use ($userId) {
            $query->select('id')
                ->from('tenant_boardings')
                ->where('user_id', $userId);
        })
        ->paginate(self::PAGINATION_NUMBER);
    }

    private function getListPaymentByBoardingId(int $boardingId){
        $subquery = TenantBoarding::select('id','user_id','boarding_id')
        ->where('boarding_id',$boardingId);
       
        return RentTransaction::joinSub($subquery, 'tenant_boardings', function ($join) {
                $join->on('tenant_boardings.id', '=', 'tenant_boarding_id');
            })
        ->join('users', 'users.id', '=', 'tenant_boardings.user_id')
        ->paginate(self::PAGINATION_NUMBER);
    }

    private function getTenantById(int $tenantId)
    {
        return User::find($tenantId);
    }

}

