<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BoardingController;
use App\Http\Controllers\ComplainController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\BoardingImageController;
use App\Http\Controllers\BoardingTypeController;
use App\Http\Controllers\FacilityController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ComplainTypeController;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PaymentMethodController;
use App\Http\Controllers\TransactionTypeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/login', [AuthController::class, 'getLoginPage'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/register', [AuthController::class, 'getRegisterPage'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::get('/', [BoardingController::class, 'getMainPage']);

Route::middleware('auth')->group(function () {
    Route::get('/logout', [AuthController::class, 'logout']);

    Route::get('/welcome', function () {
        return inertia('welcome');
    });

    Route::get('/profile', [UserController::class, 'getProfilePage']);
    Route::post('/profile/update', [UserController::class, 'updateProfile']);

    Route::get('/complain', [ComplainController::class, 'getComplainListPage'])->middleware('make.complain.access');
    Route::get('/complain/create', [ComplainController::class, 'getCreateComplainPage'])->middleware('make.complain.access');
    Route::post('/complain/create', [ComplainController::class, 'createComplain'])->middleware('make.complain.access');
    Route::get('/complain/detail', [ComplainController::class, 'getComplainDetail'])->middleware('complain.access');

    Route::post('/complain/status', [ComplainController::class, 'setComplainStatus'])->middleware('owner.manager.access');
    Route::get('/complain/owner', [ComplainController::class, 'getOwnerComplainPage'])->middleware('owner.manager.access');
    Route::get('/complain/house', [ComplainController::class, 'getSelectedBoardingHouseComplainList'])->middleware('owner.manager.access');

    Route::get('/review', [ReviewController::class, 'getAllReviewPage'])->middleware('tenant');
    Route::get('/review/create', [ReviewController::class, 'getCreateReviewOrViewReviewPage'])->middleware('review.access');
    Route::post('/review/create', [ReviewController::class, 'createReview'])->middleware('review.access');
    Route::post('/review/update', [ReviewController::class, 'updateReview'])->middleware('review.access');

    Route::get('/chat', [ChatController::class, 'getChatPage']);
    Route::post('/chat', [ChatController::class, 'storeChatMessage']);

    Route::get('/chat/get', [ChatController::class, 'getChatMessage']);
    Route::get('/wishlist', [WishlistController::class, 'showWishlist']);
    Route::post('/wishlist/add', [WishlistController::class, 'addWishlist']);
    Route::post('/wishlist/remove', [WishlistController::class, 'removeWishlist']);
    Route::post('/boarding/detail/rent/{id}', [BoardingController::class, 'boardingRent']);

    Route::get('/boardingAdmin', [BoardingController::class, 'indexAdmin'])->middleware('admin');
    Route::get('/boardingAdmin/request/{id}', [BoardingController::class, 'getAdminApproveBoarding'])->middleware('admin');
    Route::post('/boardingAdmin/request/{id}', [BoardingController::class, 'AdminApproveBoarding'])->middleware('admin');
    Route::get('/boardingOwner', [BoardingController::class, 'indexOwner'])->middleware('owner');
    Route::get('/boarding/create', [BoardingController::class, 'getCreateOwnerBoarding'])->middleware('owner');
    Route::post('/boarding/create', [BoardingController::class, 'createOwnerBoarding'])->middleware('owner');
    Route::get('/boarding/update/{id}', [BoardingController::class, 'getUpdateBoarding'])->middleware('boarding.approve.access');
    Route::get('/boarding/read/{id}', [BoardingController::class, 'getReadBoarding'])->middleware('boarding.read.access');
    Route::post('/boarding/update/{id}', [BoardingController::class, 'updateBoarding'])->middleware('boarding.approve.access');
    Route::post('/boarding/delete/{id}', [BoardingController::class, 'deleteBoarding'])->middleware('admin');
    Route::get('/boarding/reapprove/{id}', [BoardingController::class, 'getReapproveBoarding'])->middleware('boarding.reapprove.access');
    Route::post('/boarding/reapprove/{id}', [BoardingController::class, 'ReapproveBoarding'])->middleware('boarding.reapprove.access');
    Route::get('/boarding/enable/{id}', [BoardingController::class, 'enableBoardingOwner'])->middleware('boarding.approve.access');
    Route::get('/boarding/disable/{id}', [BoardingController::class, 'disableBoardingOwner'])->middleware('boarding.approve.access');

    Route::put('/boarding/image/delete/{id}', [BoardingImageController::class, 'deleteImage'])->middleware('owner');


    Route::get('/boardingManager', [BoardingController::class, 'indexManager'])->middleware('manager');
    Route::get('/tenantBoarding', [TenantController::class, 'getAllTenantBoarding'])->middleware('owner.manager.access'); //manager & Owner
    Route::get('/tenantBoarding/read/{id}', [TenantController::class, 'getDetailTenantBoarding'])->middleware('tenant.read.access');
    Route::get('/tenantBoarding/request/{id}', [TenantController::class, 'getRequestTenant'])->middleware('tenant.approve.access');
    Route::post('/tenantBoarding/request/{id}', [TenantController::class, 'RequestTenant'])->middleware('tenant.approve.access');


    //Tenant View all their Boarding House
    Route::get('/boardingTenant', [BoardingController::class, 'indexTenant'])->middleware('tenant');
    Route::get('/boardingTenant/detail/{id}', [BoardingController::class, 'getReadBoardingTenant'])->middleware('tenant');

    Route::get('/boarding/rent/end/{id}', [TenantController::class, 'getEndRentBoarding'])->middleware('tenant.endRent.access');
    Route::post('/boarding/rent/end/{id}', [TenantController::class, 'endRentBoarding'])->middleware('tenant.endRent.access');


    //Facility
    Route::get('/facilityAll',[FacilityController::class, 'getAllFacilityPage'])->middleware('admin');
    Route::get('/facility/create',[FacilityController::class, 'getFacilityCreate'])->middleware('admin');
    Route::post('/facility/create',[FacilityController::class, 'FacilityCreate'])->middleware('admin');
    Route::get('/facility/update/{id}',[FacilityController::class, 'getFacilityUpdate'])->middleware('admin');
    Route::post('/facility/update/{id}',[FacilityController::class, 'FacilityUpdate'])->middleware('admin');
    Route::get('/facility/read/{id}',[FacilityController::class, 'getFacilityDetail'])->middleware('admin');
    Route::post('/facility/delete/{id}',[FacilityController::class, 'FacilityDelete'])->middleware('admin');

    //User
    Route::get('/userAll',[UserController::class, 'getAllUserPage'])->middleware('admin');
    Route::get('/user/update/{id}',[UserController::class, 'getUserUpdate'])->middleware('admin');
    Route::post('/user/update/{id}',[UserController::class, 'UserUpdate'])->middleware('admin');
    Route::get('/user/read/{id}',[UserController::class, 'getUserDetail'])->middleware('admin');
    Route::post('/user/delete/{id}',[UserController::class, 'UserDelete'])->middleware('admin');

    //ComplainType
    Route::get('/complainTypeAll',[ComplainTypeController::class, 'getAllComplainTypePage'])->middleware('admin');
    Route::get('/complainType/create',[ComplainTypeController::class, 'getComplainTypeCreate'])->middleware('admin');
    Route::post('/complainType/create',[ComplainTypeController::class, 'ComplainTypeCreate'])->middleware('admin');
    Route::get('/complainType/update/{id}',[ComplainTypeController::class, 'getComplainTypeUpdate'])->middleware('admin');
    Route::post('/complainType/update/{id}',[ComplainTypeController::class, 'ComplainTypeUpdate'])->middleware('admin');
    Route::post('/complainType/delete/{id}',[ComplainTypeController::class, 'ComplainTypeDelete'])->middleware('admin');

    //BoardingType
    Route::get('/boardingTypeAll',[BoardingTypeController::class, 'getAllBoardingTypePage'])->middleware('admin');
    Route::get('/boardingType/create',[BoardingTypeController::class, 'getBoardingTypeCreate'])->middleware('admin');
    Route::post('/boardingType/create',[BoardingTypeController::class, 'BoardingTypeCreate'])->middleware('admin');
    Route::get('/boardingType/update/{id}',[BoardingTypeController::class, 'getBoardingTypeUpdate'])->middleware('admin');
    Route::post('/boardingType/update/{id}',[BoardingTypeController::class, 'BoardingTypeUpdate'])->middleware('admin');
    Route::post('/boardingType/delete/{id}',[BoardingTypeController::class, 'BoardingTypeDelete'])->middleware('admin');

    //TransactionType
    Route::get('/transactionTypeAll',[TransactionTypeController::class, 'getAllTransactionTypePage'])->middleware('admin');
    Route::get('/transactionType/create',[TransactionTypeController::class, 'getTransactionTypeCreate'])->middleware('admin');
    Route::post('/transactionType/create',[TransactionTypeController::class, 'TransactionTypeCreate'])->middleware('admin');
    Route::get('/transactionType/update/{id}',[TransactionTypeController::class, 'getTransactionTypeUpdate'])->middleware('admin');
    Route::post('/transactionType/update/{id}',[TransactionTypeController::class, 'TransactionTypeUpdate'])->middleware('admin');
    Route::post('/transactionType/delete/{id}',[TransactionTypeController::class, 'TransactionTypeDelete'])->middleware('admin');

    //PaymentMethod
    Route::get('/paymentMethodAll',[PaymentMethodController::class, 'getAllPaymentMethodPage'])->middleware('admin');
    Route::get('/paymentMethod/create',[PaymentMethodController::class, 'getPaymentMethodCreate'])->middleware('admin');
    Route::post('/paymentMethod/create',[PaymentMethodController::class, 'PaymentMethodCreate'])->middleware('admin');
    Route::get('/paymentMethod/update/{id}',[PaymentMethodController::class, 'getPaymentMethodUpdate'])->middleware('admin');
    Route::post('/paymentMethod/update/{id}',[PaymentMethodController::class, 'PaymentMethodUpdate'])->middleware('admin');
    Route::post('/paymentMethod/delete/{id}',[PaymentMethodController::class, 'PaymentMethodDelete'])->middleware('admin');

    //Gardi Punya
    Route::get('/paymentHistory',[PaymentController::class,'getAllPayment'])->middleware('read.history.payment.access');
    Route::get('/pay',[PaymentController::class,'getPaymentPageTenant'])->middleware('tenant.payment.access'); //tenant payment
    Route::post('/pay',[PaymentController::class,'addPaymentTenant'])->middleware('tenant.payment.access');
    Route::post('/getInvoiceData',[PaymentController::class,'getInvoiceDetail'])->middleware('read.specific.payment.access'); //read payment
    
    Route::get('/paymentBoarding/add',[PaymentController::class,'getPaymentPageManager'])->middleware('owner.manager.boarding.payment.access');
    Route::post('/paymentBoarding/add',[PaymentController::class,'addPaymentManager'])->middleware('owner.manager.boarding.payment.access');
    Route::get('/paymentBoarding/edit',[PaymentController::class,'getEditPayment'])->middleware('owner.manager.order.access');
    Route::post('/paymentBoarding/edit',[PaymentController::class,'updatePayment'])->middleware('owner.manager.order.access');
    Route::get('/paymentBoarding/cancel',[PaymentController::class,'cancelPayment'])->middleware('owner.manager.order.access');
    Route::get('/downPayment',[PaymentController::class,'getDownPayment'])->middleware('owner.manager.boarding.payment.access');
    Route::post('/downPayment/add',[PaymentController::class,'addDownPayment'])->middleware('owner.manager.boarding.payment.access');
    Route::post('/downPayment/edit',[PaymentController::class,'editDownPayment'])->middleware('owner.manager.order.access');
    
    Route::get('/checkInvoiceRequest',[PaymentController::class,'getCheckInvoiceRequest'])->middleware('admin');
    Route::post('/updateInvoiceStatus',[PaymentController::class,'updateInvoiceStatus'])->middleware('admin');
    Route::get('/remitPayment',[PaymentController::class,'getPaymentRemit'])->middleware('admin');
    Route::post('/updateTransferredStatus',[PaymentController::class,'updateTransferredStatus'])->middleware('admin');
    Route::get('/paymentSupport',[PaymentController::class,'getPaymentSupport'])->middleware('admin');

});

Route::get('/boarding/detail', [BoardingController::class, 'getBoardingHouseDetail']);
Route::get('/search', [BoardingController::class, 'searchBoardingByLocation']);
Route::get('/boarding/all', [BoardingController::class, 'getAllBoardingHouse']);
