<?php

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

	#Apps Page
	Route::get('/apps',array('as'=>'Apps Page' , 'uses' =>'CouponController@Appspage'));

	#Home Page
	Route::get('/',array('as'=>'Home' , 'uses' =>'CouponController@HomePage'));
	#Home Page
	Route::get('/home',array('as'=>'Home' , 'uses' =>'CouponController@HomePage'));
	#Quick View Page
	Route::get('/dashboard/coupon-details/id-{coupon_id}',array('as'=>'Quick View Coupon' , 'uses' =>'CouponController@QuickViewCoupon'));

	#Merchant View
	Route::get('/merchant-view/page',array('as'=>'Merchant View' , 'uses' =>'CouponController@MerchantViewPage'));

	#Single Page
	Route::get('/single-page/coupon_id-{coupon_id}',array('as'=>'Single Page' ,  'desc'=>'{sub_category_id}', 'uses' =>'CouponController@SinglePage'));

	#Search Coupon
	Route::post('/search/coupon/',array('as'=>'Search Coupon' , 'uses' =>'CouponController@GetSearchCoupon'));

	#Search View
	Route::get('/search-result/text-{search_text}',array('as'=>'Search View' , 'uses' =>'CouponController@SearchResultPage'));

	#Merchant Branch View
	Route::get('/merchant/branch-view/page/mid-{merchant_id}',array('as'=>'Merchant Branch View' ,  'desc'=>'{sub_category_id}', 'uses' =>'CouponController@MerchantBranchViewPage'));

	#Merchant All Coupon
	Route::get('all-merchant/coupon/page/cid-/{category_id}/subcid-{sub_category_id}',array('as'=>'Merchant All Coupon' ,  'desc'=>'{sub_category_id}', 'uses' =>'CouponController@AllMerchantCouponPage'));

	#Merchant Latest Coupon
	Route::get('/latest/coupon/page',array('as'=>'Latest Coupon' , 'uses' =>'CouponController@LatestCouponPage'));

	#Merchant Newest Coupon
	Route::get('/newest/coupon/page',array('as'=>'Newest Coupon' , 'uses' =>'CouponController@NewestCouponPage'));

	#FAQ Page
	Route::get('/faq/page',array('as'=>'FAQ Page ' , 'uses' =>'CouponController@FAQPage'));

	#Privacy Page
	Route::get('/privacy/page',array('as'=>'Privacy Page ' , 'uses' =>'CouponController@PrivacyPage'));

	#Contact Page
	Route::get('/contact/page',array('as'=>'Contact Page' , 'uses' =>'UserController@ContactPage'));

	#Contact Confirm
	Route::post('/contact/confirm',array('as'=>'Contact Confirm' , 'uses' =>'UserController@ContactConfirm'));

	#Client Call Request 
	Route::post('/client/call/request',array('as'=>'Client Call Request ' , 'uses' =>'UserController@ClientCallRequest'));



	#Client Signin Page
	Route::get('/sign-in/page',array('as'=>'Sign In Page' , 'uses' =>'SystemAuthController@ClientSignIn'));
	
	#Client Login Page
	Route::get('/login',array('as'=>'Login Page' , 'uses' =>'SystemAuthController@ClientSignIn'));
	
	#Client SignUp
	Route::post('/sign-up/page',array('as'=>'Sign Up Page' , 'uses' =>'SystemAuthController@ClientUserRegistration'));

	#Client SignUp Confirm
	Route::get('/sign-up/confirm/mobile-{user_mobile}',array('as'=>'Sign Up Confirm' , 'uses' =>'SystemAuthController@ClientSignUpConfirmPage'));

	#Client SignUp Confirm
	Route::get('/resend/user/confirm/code/mobile-{user_mobile}/otp-{client_otp}',array('as'=>'Resend Code For User Registration' , 'uses' =>'SystemAuthController@ResendSMSForUserRegistration'));

	#Client SignUp Confirm
	Route::post('/sign-up/confirm/mobile-{user_mobile}',array('as'=>'Sign Up Confirm' , 'uses' =>'SystemAuthController@ClientSignUpConfirm'));

	#Client Signin
	Route::post('/sign-in/page',array('as'=>'LogIn' , 'uses' =>'SystemAuthController@ClientAuthenticationCheck'));


	#Event Notification Send
	Route::get('/event/notification/send',array('as'=>'Event Notification Send' , 'uses' =>'AdminController@EventNotificationPush'));

	#Admin Login Page
	Route::get('/adminlogin',array('as'=>'AdminLogIn' , 'uses' =>'SystemAuthController@AdminLoginPage'));
	// Route::get('/login',array('as'=>'LogIn' , 'uses' =>'SystemAuthController@AdminLoginPage'));

	#Admin Login Confirm
	Route::post('/login',array('as'=>'LogIn' , 'uses' =>'SystemAuthController@AdminAuthenticationCheck'));

	#Forget Password Page
	Route::get('/forget/password',array('as'=>'Forget Password' , 'uses' =>'SystemAuthController@ForgotPasswordPage'));
	#Admin Forget Password Page
	Route::get('/admin/forget/password',array('as'=>'Admin Forget Password' , 'uses' =>'SystemAuthController@AdminForgotPasswordPage'));
	#Forget Password Confirm
	Route::post('/forget/password',array('as'=>'Forgot Password Confirm' , 'uses' =>'SystemAuthController@ForgotPasswordConfirm'));

	#ForgetPassword
	Route::get('/forget/password/{users_id}/verify',array('as'=>'Forgot Password Varify' , 'uses' =>'SystemAuthController@SystemForgotPasswordVerification'));

	#ForgetPasswordMail
	Route::post('/new/password',array('as'=>'New Password Submit' , 'uses' =>'SystemAuthController@SystemNewPasswordSubmit'));

	#ForgetPasswordMail
	Route::get('/forget-password-mail',array('as'=>'Forget Password Mail', 'uses'=>'SystemAuthController@ForgetPasswordMail'));


	Route::get('/logout',array('as'=>'Logout' , 'uses' =>'SystemAuthController@AdminLogout'));

    	#Enternal Error Page
    	Route::get('/error/request',array('as'=>'Error 404', 'uses'=>'SystemAuthController@ErrorRequestPage'));
    
    	#Errors Page
    	Route::get('/errors/page',array('as'=>'Errors Page', 'uses'=>'SystemAuthController@Page404'));

    	#Errors Page 503
    	Route::get('/errors/page/503',array('as'=>'Errors Page 503', 'uses'=>'SystemAuthController@Page503'));



	#Buy Coupon
	Route::get('/dashboard/coupon-{coupon_code}/amount-{transaction_amount}/mobile-{customer_mobile}',array('as'=>'Buy Coupon', 'uses'=>'AdminController@BuyCoupon'));


	#Buy Coupon From Web
	Route::get('/dashboard/webcoupon/coupon-{coupon_code}/amount-{transaction_amount}/mobile-{customer_mobile}',array('as'=>'Buy Coupon From Web', 'uses'=>'AdminController@BuyCouponFromWeb'));

	#Ajax Coupon Confirm Transaction 
	Route::get('/dashboard/coupon-{coupon_code}/tid-{coupon_transaction_id}/scode-{coupon_secret_code}',array('as'=>'Branch Coupon Transaction List', 'uses'=>'AdminController@AjaxCouponConfirmTransaction'));



################## Common Blade ###########################
Route::group(['middleware' => ['auth']], function () {

	#User Profile
	Route::get('/user/profile',array('as'=>'User Profile', 'uses'=>'UserController@UserProfile'));

	#User Profile Update
	Route::post('/user/profile/update',array('as'=>'User Profile Update', 'uses'=>'UserController@ProfileUpdate'));

	#User Password Change
	Route::post('/user/change/password',array('as'=>'User Password Change', 'uses'=>'UserController@UserChangePassword'));
// });


// Route::group(['middleware' => ['client_auth']], function () {


	#ClientLogout
	Route::get('/client/logout/{mobile}',array('as'=>'Client Logout' , 'uses' =>'SystemAuthController@ClientLogout'));

	#Client Profile Page
	Route::get('/client/profile/page/id-{client_id}',array('as'=>'Client Profile Page' , 'uses' =>'CouponController@ClientProfilePage'));

	#Client Profile Update
	Route::post('/client/profile/update/id-{client_id}',array('as'=>'Client Profile Update' , 'uses' =>'CouponController@ClientProfileUpdate'));

	#Client Change Password
	Route::post('/client/change-password/id-{client_id}',array('as'=>'Client Change Password' , 'uses' =>'CouponController@ClientUserChangePassword'));

	#ClientReview
    	Route::post('/coupon/review/{coupon_id}',array('as'=>'Coupon Review' , 'uses' =>'CouponController@ReviewPost'));

    	#ClientRating
	Route::get('/coupon/rating/{coupon_id}/{rating_point}', array('as'=>'Client Rating', 'uses'=>'CouponController@ClientRating'));

	#Active Deal
	Route::get('/active/deal/coupon-{coupon_code}/mobile-{mobile}',array('as'=>'Active Coupon', 'uses'=>'CouponController@CouponActiveDeal'));

	#Ajax Buy Coupon Details
	// Route::get('/ajax/buy/coupon/details/code-{coupon_code}/mobile-{customer_mobile}/tranid-{coupon_transaction_id}',array('as'=>'Active Coupon', 'uses'=>'AdminController@AjaxBuyCouponDetails'));

	Route::get('/ajax/buy/coupon/details/code-{coupon_code}/mobile-{customer_mobile}/tranid-{coupon_transaction_id}/tab-{tab}',array('as'=>'Active Coupon', 'uses'=>'AdminController@AjaxBuyCouponDetails'));

	#Coupon Amount And OTP Page
	Route::get('/coupon/amount/otp/ccode-{coupon_code}/cmobile-{customer_mobile}/tid-{coupon_transaction_id}',array('as'=>'Coupon Amount And OTP Page' , 'uses' =>'CouponController@ShoppingAmountAndOTPPage'));

	#Coupon Amount Edit Page
	Route::get('/coupon/amount/edit/tid-{coupon_transaction_id}',array('as'=>'Coupon Amount Edit Page' , 'uses' =>'CouponController@EditShoppingAmountPage'));

	#Coupon Shopping Amount Submit
	Route::post('/coupon/amount/submit',array('as'=>'Coupon Amount Submit', 'uses'=>'CouponController@CouponShoppingAmountSubmit'));

	#Coupon New Shopping Amount Submit
	Route::post('/new/coupon/amount/submit',array('as'=>'New Coupon Amount Submit', 'uses'=>'CouponController@NewCouponShoppingAmountSubmit'));
	
	#Resend Buy Coupon otp
	Route::get('/resend/code/coupon/confirm/mobile-{branch_mobile}/otp-{coupon_secret_code}/cmobile-{customer_mobile}/amount-{transaction_amount}/damount-{coupon_discount_amount}',array('as'=>'Resend Code For Buy Coupon' , 'uses' =>'CouponController@ResendSMSForBuyCoupon'));
	
	#Coupon OTP Submit
	Route::post('/coupon/otp/submit',array('as'=>'Coupon OTP Submit', 'uses'=>'CouponController@CouponOTPSubmit'));

	
	#Client Follow Merchant
	Route::get('/follow/merchant/id-{merchant_id}/status-{status}',array('as'=>'Follow Merchant' , 'uses' =>'CouponController@FollowMerchant'));

	#Client Wish List Add 
	Route::get('/add/wish-list/cid-{coupon_id}/status-{status}',array('as'=>'Wish List Add' , 'uses' =>'CouponController@ClientWishListAdd'));

	#Client Wish List Delete 
	Route::get('/delete/wish-list/id-{activity_id}',array('as'=>'Delete Wish List' , 'uses' =>'CouponController@ClientWishListDelete'));


	#Event Notification Push
	Route::get('/event/notification/push',array('as'=>'Event Notification Push' , 'uses' =>'UserController@CreateEventPage'));

	#Event Notification Push Confirm
	Route::post('/event/notification/push/confirm',array('as'=>'Event Notification Push Confirm' , 'uses' =>'UserController@CreateEventConfirm'));

});	


Route::group(['middleware' => ['admin_auth']], function () {

	#Dashboard Page
	Route::get('/dashboard/admin',array('as'=>'Dashboard' , 'uses' =>'AdminController@AdminDashboardPage'));

	#User Management Page
	Route::get('/dashboard/user-management',array('as'=>'User' , 'uses' =>'SystemAuthController@UserManagementPage'));

	#User Registration Page
	Route::post('/dashboard/user-create',array('as'=>'User' , 'uses' =>'SystemAuthController@UserRegistration'));

	#All User's Status Change
	Route::get('/dashboard/user/status-change/id-{user_id}/{action}/{tab}',array('as'=>'User Status Change', 'uses'=>'SystemAuthController@AjaxUserStatusChange'));

	#Company Page
	Route::get('/dashboard/company/info',array('as'=>'Company Info', 'uses'=>'AdminController@CompanyPage'));

	#Company Detail Insert Update
	Route::post('/dashboard/company/info',array('as'=>'Company Info','uses'=>'AdminController@CompanyDetailInsert'));


	#Social Site Page
	Route::get('/dashboard/social/site/info',array('as'=>'Social Site Info', 'uses'=>'AdminController@SocialSitePage'));

	#Social Site Insert
	Route::post('/dashboard/social/site/info',array('as'=>'Social Site Info', 'uses'=>'AdminController@SocialSiteAdd'));

	#Social Site Edit
	Route::get('/dashboard/social/site/edit/sid-{setting_id}',array('as'=>'Social Site Edit', 'uses'=>'AdminController@SocialSiteEdit'));

	#Social Site Update
	Route::post('/dashboard/social/site/update/sid-{setting_id}',array('as'=>'Social Site Update', 'uses'=>'AdminController@SocialSiteUpdate'));

	#Social Site Delete
	Route::get('/dashboard/social/site/delete/sid-{setting_id}',array('as'=>'Social Site Delete', 'uses'=>'AdminController@SocialSiteDelete'));


	#Home Slider Page
	Route::get('/dashboard/home-slider',array('as'=>'Home Slider', 'uses'=>'AdminController@HomeSliderPage'));

	#Home Slider Add
	Route::post('/dashboard/home-slider',array('as'=>'Home Slider', 'uses'=>'AdminController@HomeSliderAdd'));


	#Home Slider Change Status
	Route::get('/dashboard/home-slider/change-status/id-{setting_id}/{action}',array('as'=>'Home Slider Change Status', 'uses'=>'AdminController@AjaxHomeSliderChangeStatus'));

	#Home Slider delete
	Route::get('/dashboard/home-slider/delete-/{setting_id}',array('as'=>'Home Slider Delete', 'uses'=>'AdminController@HomeSliderDelete'));

	#CouponHighlight
	Route::get('coupon/highlight/{coupon_id}/{action}',array('as'=>'Coupon Highlight', 'uses'=>'AdminController@CouponHighlight'));
 
	#Category Page
	Route::get('/dashboard/category',array('as'=>'Category', 'uses'=>'AdminController@CategoryPage'));

	#Category Add
	Route::post('/dashboard/category',array('as'=>'Category', 'uses'=>'AdminController@CategoryAdd'));

	#Category Edit
	Route::get('/dashboard/category/edit-/{category_id}',array('as'=>'Category Edit', 'uses'=>'AdminController@CategoryEdit'));

	#Category Update
	Route::post('/dashboard/category/update-{category_id}',array('as'=>'Category Update', 'uses'=>'AdminController@CategoryUpdate'));

	#Category delete
	Route::get('/dashboard/category/delete-/{category_id}',array('as'=>'Category Delete', 'uses'=>'AdminController@CategoryDelete'));


	#SubCategory Page
	Route::get('/dashboard/sub-category',array('as'=>'Sub Category', 'uses'=>'AdminController@SubCategoryPage'));

	#Ajax SubCategory Status Change
	Route::get('/dashboard/sub-category/change-id-{sub_category_id}/{action}',array('as'=>'Sub Category Status Change', 'uses'=>'AdminController@AjaxSubCategoryStatusChange'));

	#Ajax Category List
	Route::get('/ajax/sub-category/list-{category_id}',array('as'=>'Ajax Sub Category', 'uses'=>'AdminController@AjaxSubCategoryList'));

	#Ajax Branch List
	Route::get('/ajax/branch/list-{merchant_id}',array('as'=>'Ajax Branch List', 'uses'=>'AdminController@AjaxBranchList'));

	#Ajax Merchant List
	Route::get('/ajax/merchant/list-{user_type}',array('as'=>'Ajax Merchant List', 'uses'=>'AdminController@AjaxMerchantList'));

	#Ajax Merchant Branch List
	Route::get('/ajax/merchant-branch/list-{merchant_id}',array('as'=>'Ajax Merchant Branch List', 'uses'=>'AdminController@AjaxMerchantBranchList'));

	#SubCategory Add
	Route::post('/dashboard/sub-category',array('as'=>'Sub Category', 'uses'=>'AdminController@SubCategoryAdd'));

	#SubCategory Edit
	Route::get('/dashboard/sub-category/edit-/{sub_category_id}',array('as'=>'Sub Category Edit', 'uses'=>'AdminController@SubCategoryEdit'));

	#SubCategory Update
	Route::post('/dashboard/sub-category/update-{sub_category_id}',array('as'=>'Sub Category Update', 'uses'=>'AdminController@SubCategoryUpdate'));

	#SubCategory delete
	Route::get('/dashboard/sub-category/delete-/{category_id}',array('as'=>'Sub Category Delete', 'uses'=>'AdminController@SubCategoryDelete'));




	#Merchant Page
	Route::get('/dashboard/merchant-page',array('as'=>'Merchant Page', 'uses'=>'AdminController@MerchantPage'));

	#Merchant Add
	Route::post('/dashboard/merchant',array('as'=>'Merchant', 'uses'=>'AdminController@MerchantAdd'));

	#Merchant Edit
	Route::get('/dashboard/merchant/edit-/{merchant_id}',array('as'=>'Merchant Edit', 'uses'=>'AdminController@MerchantEdit'));

	#Merchant Update
	Route::post('/dashboard/merchant/update-{merchant_id}',array('as'=>'Merchant Update', 'uses'=>'AdminController@MerchantUpdate'));

	#Merchant delete
	Route::get('/dashboard/merchant/delete-/{merchant_id}',array('as'=>'Merchant Delete', 'uses'=>'AdminController@MerchantDelete'));




	#Merchant Featured Image
	Route::get('/dashboard/merchant/featured',array('as'=>'Merchant Featured Image', 'uses'=>'AdminController@MerchantFeaturedImage'));

	#Merchant Featured Add
	Route::post('/dashboard/merchant/featured',array('as'=>'Merchant Featured Image', 'uses'=>'AdminController@MerchantFeaturedAdd'));

	#Merchant Featured Edit
	Route::get('/dashboard/merchant/featured/edit-/{featured_product_id}',array('as'=>'Merchant Featured Edit', 'uses'=>'AdminController@MerchantFeaturedEdit'));

	#Merchant Featured Update
	Route::post('/dashboard/merchant/featured/update-{featured_product_id}',array('as'=>'Merchant Featured Update', 'uses'=>'AdminController@MerchantFeaturedUpdate'));

	#Merchant Featured delete
	Route::get('/dashboard/merchant/featured/delete-/{featured_product_id}',array('as'=>'Merchant Featured Delete', 'uses'=>'AdminController@MerchantFeaturedDelete'));
	
	#Merchant Featured Status Change
	Route::get('/dashboard/merchant/featured/change-id-{featured_product_id}/{action}',array('as'=>'Merchant Featured Change Status', 'uses'=>'AdminController@MerchantFeaturedChangeStatus'));

	




	#Branch Page
	Route::get('/dashboard/merchant-branch',array('as'=>'Merchant Branch', 'uses'=>'AdminController@BranchPage'));

	#Branch Add
	Route::post('/dashboard/merchant-branch',array('as'=>'Merchant Branch', 'uses'=>'AdminController@BranchAdd'));

	#Branch Edit
	Route::get('/dashboard/branch/edit-/{branch_id}',array('as'=>'Branch Edit', 'uses'=>'AdminController@BranchEdit'));

	#Branch Update
	Route::post('/dashboard/branch/update-{branch_id}',array('as'=>'Branch Update', 'uses'=>'AdminController@BranchUpdate'));

	#Branch delete
	Route::get('/dashboard/branch/delete-/{branch_id}',array('as'=>'Branch Delete', 'uses'=>'AdminController@BranchDelete'));




	#Coupon Page
	Route::get('/dashboard/coupon',array('as'=>'Coupon', 'uses'=>'AdminController@CouponPage'));

	#Coupon Add
	Route::post('/dashboard/coupon',array('as'=>'Coupon', 'uses'=>'AdminController@CouponInsert'));

	#All Coupon List
	Route::get('/dashboard/all-coupon/list',array('as'=>'All Coupon List', 'uses'=>'AdminController@AllCouponList'));

	#Coupon Status Change
	Route::get('/coupon/change/status/cid-{coupon_id}/{action}',array('as'=>'Coupon Change Status', 'uses'=>'AdminController@CouponChangeStatus'));

	#Ajax Coupon Details
	Route::get('/dashboard/coupon/view/id-{coupon_id}',array('as'=>'Coupon Details', 'uses'=>'AdminController@AjaxCouponListDetails'));

	#Coupon Edit
	Route::get('/dashboard/coupon/edit-{coupon_id}',array('as'=>'Coupon Edit', 'uses'=>'AdminController@CouponEdit'));

	#Coupon Update
	Route::post('/dashboard/coupon/update-{coupon_id}',array('as'=>'Coupon Update', 'uses'=>'AdminController@CouponUpdate'));
		
	#Transaction Coupon Update
	Route::post('/dashboard/transaction/coupon/update-{coupon_id}',array('as'=>'Transaction Coupon Update', 'uses'=>'AdminController@TransactionCouponUpdate'));	

	#Coupon delete
	Route::get('/dashboard/coupon/delete-/{coupon_id}',array('as'=>'Coupon Delete', 'uses'=>'AdminController@CouponDelete'));

	#All Coupon Comments
	Route::get('/dashboard/all-coupon/comments/list',array('as'=>'All Coupon Comments List', 'uses'=>'AdminController@AllCouponComments'));


	#Delete Coupon Comments
	Route::get('/dashboard/coupon/comments/id-{review_comments_id}',array('as'=>'Delete Coupon Comments', 'uses'=>'AdminController@CouponCommentsDelete'));






	#All Merchant List
	Route::get('/dashboard/all-merchant/list',array('as'=>'All Merchant List', 'uses'=>'AdminController@AllMerchantList'));

	#Ajax Merchant Details
	Route::get('/dashboard/merchant-details/id-{merchant_id}',array('as'=>'Merchant Details', 'uses'=>'AdminController@AjaxMerchantListDetails'));

	#Merchant Status Change
	Route::get('/dashboard/merchant/change-status/id-{merchant_id}/{action}',array('as'=>'Merchant Status Changed', 'uses'=>'AdminController@AjaxMerchantChangeStatus'));


	#Ajax Merchant Rank Change
	Route::get('/dashboard/merchant/change-rank/id-{merchant_id}/{action}',array('as'=>'Merchant Rank Change', 'uses'=>'AdminController@AjaxMerchantRankChange'));

	#All User List
	Route::get('/dashboard/all-user/list',array('as'=>'All User List', 'uses'=>'AdminController@AllUserList'));

	#User Delete
	Route::get('/dashboard/user/delete-{user_id}',array('as'=>'Delete User', 'uses'=>'AdminController@UserDelete'));

	#Ajax User Details
	Route::get('/dashboard/user-details/id-{user_id}',array('as'=>'User Details', 'uses'=>'AdminController@AjaxUserListDetails'));

	#User Status Change
	Route::get('/dashboard/user/change-status/id-{user_id}/{action}',array('as'=>'User Status Changed', 'uses'=>'AdminController@AjaxUserChangeStatus'));

	#Active Deal List
	Route::get('/dashboard/active-deal',array('as'=>'Active Deal List', 'uses'=>'AdminController@ActiveDealList'));

	#Active Deal Delete
	Route::get('/dashboard/active-deal/ctid-{coupon_transaction_id}',array('as'=>'Active Deal Delete', 'uses'=>'AdminController@ActiveDealDelete'));

	#Pending Coupon Transaction List
	Route::get('/dashboard/pending-coupon/transaction/list',array('as'=>'Pending Coupon Transaction List', 'uses'=>'AdminController@PendingCouponTransactionList'));

	#Coupon Transaction Pdf
	Route::get('/dashboard/transaction/pdf/from-{search_from}/to-{search_to}/mid-{merchant_id}/coid-{coupon_id}/cuid-{customer_id}',array('as'=>'Coupon Transaction Pdf', 'uses'=>'AdminController@CouponTransactionPdf'));



	#All Coupon Confirm Transaction List
	Route::get('/dashboard/all-coupon/confirm/transaction/list',array('as'=>'All Coupon Confirm Transaction List', 'uses'=>'AdminController@AllCouponConfirmTransaction'));


	#Sell Quantity Summery
	Route::get('/dashboard/sell-quantity',array('as'=>" Sell Quantity",'uses'=>'AdminController@SellQuantityPage'));


	#Call Request List
	Route::get('/call/request/list',array('as'=>"Call Request List",'uses'=>'AdminController@CallRequestList'));

	#Message Request List
	Route::get('/message/request/list',array('as'=>"Message Request List",'uses'=>'AdminController@MessageRequestList'));


	#Push Text
	Route::get('/push/text',array('as'=>'Push Notification Text' , 'uses' =>'AdminController@PushText'));

	#Push Text
	Route::post('/push/text',array('as'=>'Push Notification Text' , 'uses' =>'AdminController@PushTextSubmit'));

	#Push Text In All App
	Route::post('/push/text/allapp',array('as'=>'Push Text In All App' , 'uses' =>'AdminController@PushTextAllApp'));

	#Push Greetings Text
	Route::post('/push/greetings/text',array('as'=>'Push Greetings Text' , 'uses' =>'AdminController@PushGreetings'));

	#All Push List
	Route::get('/dashboard/push/list',array('as'=>'Push List' , 'uses' =>'AdminController@AllPushList'));

	#Push Delete
	Route::get('/dashboard/push/delete-{notification_id}',array('as'=>'Push Delete' , 'uses' =>'AdminController@PushDelete'));


	#Access Log List
	Route::get('/system-admin/access-logs',array('as'=>"Access Logs",'uses'=>'AdminController@AccessLogs'));

	# Error Log List
	Route::get('/system-admin/error-logs',array('as'=>"Error Logs",'uses'=>'AdminController@ErrorLogs'));

	#Event Log List
	Route::get('/system-admin/event-logs',array('as'=>"Event Logs",'uses'=>'AdminController@EventLogs'));

	#Auth Log List
	Route::get('/system-admin/auth-logs',array('as'=>"Auth Logs",'uses'=>'AdminController@AuthLogs'));


   
});




Route::group(['middleware' => ['merchant_auth']], function () {

	#Merchant Dashboard
	Route::get('/dashboard/merchant',array('as'=>'Dashboard Merchant', 'uses'=>'MerchantController@MerchantDashboard'));

	#Merchant All Coupon Transaction List
	Route::get('/dashboard/merchnat-coupon/transaction/list',array('as'=>'Merchant Accept Transaction List', 'uses'=>'MerchantController@MerchantAllCouponTransactionList'));

	#Merchant User Buy Coupon List
	Route::get('/dashboard/merchnat-buy/coupon/list',array('as'=>'Merchant All Transaction List', 'uses'=>'MerchantController@UserBuyingCouponList'));

	#Merchant All Summery
	Route::get('/dashboard/merchnat/all-summery',array('as'=>'Merchant All Summery', 'uses'=>'MerchantController@MerchantAllSummery'));

	#Merchant Transaction Pdf
	Route::get('/dashboard/transaction/pdf/from-{search_from}/to-{search_to}/bid-{branch_name}',array('as'=>'Merchant Transaction Pdf', 'uses'=>'AdminController@MerchantTransactionPdf'));


});

Route::group(['middleware' => ['branch_auth']], function () {

	#Branch Dashboard
	Route::get('/dashboard/branch',array('as'=>'Dashboard Branch', 'uses'=>'BranchController@BranchDashboard'));

	#Branch Coupon Transaction List
	Route::get('/dashboard/branch/coupon-transaction/list',array('as'=>'Branch Coupon Transaction List', 'uses'=>'BranchController@BranchAllTransactionList'));

	#Branch Confirm Transaction List
	Route::get('/dashboard/branch/confirm-transaction/list',array('as'=>'Branch Confirm Transaction List', 'uses'=>'BranchController@BranchAllConfirmTransactionList'));

	#Branch Summery
	Route::get('/dashboard/branch/summery',array('as'=>'Branch Summery', 'uses'=>'BranchController@BranchSummery'));

	#Branch Transaction Pdf
	Route::get('/dashboard/transaction/pdf/from-{search_from}/to-{search_to}/bid-{branch_id}',array('as'=>'Branch Transaction Pdf', 'uses'=>'AdminController@BranchTransactionPdf'));

});
 


Route::get('/cache/clear', function() {
    Artisan::call('cache:clear');
    echo "OK";
});
Route::get('/config/cache', function() {
    Artisan::call('config:cache');
    echo "OK";
});

Route::get('/user-agent', function() {
    var_dump($_SERVER['HTTP_USER_AGENT']);
});

Route::get('/all/clear', function() {
    Artisan::call('cache:clear');
    Artisan::call('config:cache');
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    Artisan::call('config:clear');
    echo "OK! All Clear.";
});





