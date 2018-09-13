<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::group(['prefix' => '/coupon/api/v1','middleware' => ['api']], function() {


 	#Get Token
	Route::post('/getAccessToken', array('as'=>'GetToken', 'uses'=>'ApiController@GetAccessToken'));

	#Client Registration
	Route::post('/registration', array('as'=>'User Registration', 'uses'=>'ApiController@UserRegistrationData'));

	#Client Direct Registration
	Route::post('/direct/registration', array('as'=>'Client Direct Registration', 'uses'=>'ApiController@ClientDirectRegistration'));

	#Client Direct Login
	Route::post('/client/direct/login', array('as'=>'Client Direct Login', 'uses'=>'ApiController@ClientDirectLogin'));

	#Client Resend Code
	Route::post('/client/resend/code', array('as'=>'Client Resend Code', 'uses'=>'ApiController@ResendCodeForClientRegistration'));

	#Client Registration Confirm
	Route::post('/registration/confirm', array('as'=>' User Registration Confirm', 'uses'=>'ApiController@ClientRegistrationConfirm'));

	#Client Login
	Route::post('/client/login', array('as'=>'Client Login', 'uses'=>'ApiController@ClientLogin'));

	#Forget Password
	Route::post('/forget/password', array('as'=>'Forget Password', 'uses'=>'ApiController@ForgetPassword'));

	#New Password Confirm
	Route::post('/new/password/confirm', array('as'=>'New Password Confirm', 'uses'=>'ApiController@NewPasswordConfirm'));

	#Profile Update
	Route::post('/profile/update', array('as'=>'Profile Update', 'uses'=>'ApiController@ProfileUpdate'));

	#Wish And Follow
	Route::post('/wishFollow', array('as'=>'Wish Follow', 'uses'=>'ApiController@UserWishAndFollowList'));

	#Wish And Follow Delete
	Route::post('/wishFollow/delete', array('as'=>'Wish Follow Delete', 'uses'=>'ApiController@UserWishAndFollowDelete'));


	#Review And Rating
	Route::post('/reviewRating', array('as'=>'Review Rating', 'uses'=>'ApiController@ReviewAndRating'));

	#Active Deal
	Route::post('/activeDeal', array('as'=>'Active Deal', 'uses'=>'ApiController@ActiveDealInfo'));

	#Get All Coupon
	Route::post('/getAllcoupon', array('as'=>'Get All Coupon', 'uses'=>'ApiController@AllCouponInfo'));

	#Get Category All Coupon
	Route::post('/getCategoryAllcoupon', array('as'=>'Get All Category Coupon', 'uses'=>'ApiController@CategoryAllCouponInfo'));

	#Get Single Coupon
	Route::post('/getcoupon', array('as'=>'Get Coupon', 'uses'=>'ApiController@SingleCouponInfo'));

	#Get Merchant All Coupon
	Route::post('/merchantcouponinfo', array('as'=>'Get Merchant All Coupon', 'uses'=>'ApiController@MerchantAllCouponInfo'));

	#Get Details Info
	Route::post('/getdetailsinfo', array('as'=>'Get Details', 'uses'=>'ApiController@PublicDetailsInfo'));

	#Get Search Coupon
	//Route::post('/get/search/coupon', array('as'=>'Get Search Coupon', 'uses'=>'ApiController@SearchCouponInfo'));
	
	#Get User Details
	Route::post('/getUser', array('as'=>'Get User Details', 'uses'=>'ApiController@GetUserInfo'));

	#Mobile Verification
	Route::post('/mobile/verification', array('as'=>'Mobile Verification', 'uses'=>'ApiController@OTPVerification'));

	#Mobile Confirmation
	Route::post('/mobile/confirmation', array('as'=>'Mobile Confirmation', 'uses'=>'ApiController@OTPConfirmation'));


	#Buy Coupon
 	Route::post('/buy/coupon',array('as'=>'Buy Coupon', 'uses'=>'ApiController@BuyCoupon'));

	#Edit Buy Coupon
 	Route::post('/edit/buy/coupon',array('as'=>'Edit Buy Coupon', 'uses'=>'ApiController@EditBuyCoupon'));

 	#Buy Coupon Confirm 
	Route::post('/buy/coupon/confirm',array('as'=>'Buy Coupon Confirm', 'uses'=>'ApiController@BuyCouponConfirm'));

	#Coupon Resend Code
	Route::post('/coupon/resend/code', array('as'=>'Coupon Resend Code', 'uses'=>'ApiController@ResendCodeForBuyCoupon'));

	#Call Back Request
	Route::post('/call/back/request', array('as'=>'Call Back Request', 'uses'=>'ApiController@CallBackRequest'));

	#Coupon Main Slider
	Route::post('/coupon/main/slider', array('as'=>'Coupon Main Slider', 'uses'=>'ApiController@CouponMainSlider'));

	#Event Notification Push
	Route::post('/event/notification', array('as'=>'Event Notification Push', 'uses'=>'ApiController@EventNotificationPush'));

	#Event Notification Delete
	Route::post('/event/notification/delete', array('as'=>'Event Notification Delete', 'uses'=>'ApiController@EventNotificationDelete'));

	#Test Api
	Route::post('/test/api', array('as'=>'Test Api', 'uses'=>'ApiController@TestApi'));

  	/*Route::get('/req/{key}/yes', array('as'=>'valid', 'uses'=>'ApiController@RequestData'));
  	Route::post('/send', array('as'=>'invalid', 'uses'=>'ApiController@InvalidRequest'));*/



});
