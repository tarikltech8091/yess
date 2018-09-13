<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Classes\CustomLogger;

class Admin extends Model
{
        
	/********************************************
    ## UserImageUpload
    *********************************************/

	public static function UserImageUpload($img_location, $name_slug, $img_ext){

		$filename  = $name_slug.'-'.time().'-'.rand(1111111,9999999).'.'.$img_ext;

		/*directory create*/
		if (!file_exists('assets/images/userprofile/'))
		   mkdir('assets/images/userprofile/', 0777, true);

		$path = public_path('assets/images/userprofile/' . $filename);
		\Image::make($img_location)->resize(150, 150)->save($path);

		/*directory create*/
		if (!file_exists('assets/images/userprofile/small-icon/'))
		   mkdir('assets/images/userprofile/small-icon/', 0777, true);

		$path2 = public_path('assets/images/userprofile/small-icon/' . $filename);
		\Image::make($img_location)->resize(30, 30)->save($path2);

		$user_profile_image=$filename;
		return $user_profile_image;
	}



	/********************************************
    ## AppProfileImageUpload
    *********************************************/

	public static function AppProfileImageUpload($FILE,$name_slug){

	   try{
	    	$file = $FILE["user_profile_image"]['tmp_name'];

	    	$ext = explode('.',$FILE['user_profile_image']['name']);
	    	$file_ext   = array('jpg','png','gif','bmp','JPG','jpeg');
	    	$post_ext   = end($ext);
	    	$photo_name = explode(' ', trim(strtolower($FILE['user_profile_image']['name'])));
	    	$photo_name = implode('_', $photo_name);
	    	$photo_type = $FILE['user_profile_image']['type'];
	    	$photo_size = $FILE['user_profile_image']['size'];
	    	$photo_tmp  = $FILE['user_profile_image']['tmp_name'];
	    	$photo_error= $FILE['user_profile_image']['error'];
	    
	    	if( in_array($post_ext,$file_ext) && ($photo_error == 0 )){

	    		$filename  = $name_slug.'-'.time().'-'.rand(1111111,9999999).'.'.$post_ext;

			/*directory create*/
			if (!file_exists('assets/images/userprofile/'))
			   mkdir('assets/images/userprofile/', 0777, true);

			$path = public_path('assets/images/userprofile/' . $filename);
			\Image::make($file)->resize(150, 150)->save($path);

			/*directory create*/
			if (!file_exists('assets/images/userprofile/small-icon/'))
			   mkdir('assets/images/userprofile/small-icon/', 0777, true);

			$path2 = public_path('assets/images/userprofile/small-icon/' . $filename);
			\Image::make($file)->resize(30, 30)->save($path2);

			$user_profile_image=$filename;
			return $user_profile_image;

	    	}

	    }catch(\Exception $e){

	         $response["errors"]= [
	            "statusCode"=> 501,
	            "errorMessage"=> $e->getMessage(),
	            "serverReferenceCode"=> date('Y-m-d H:i:s'),
		    "file_system" =>$FILE,
	        ];


	        $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();

	        \App\System::ErrorLogWrite($message);
	       
	        return \Response::json($response);
	    }

	}


	/********************************************
    ## PushImageUpload
    *********************************************/

	public static function PushImageUpload($img_location, $name_slug, $img_ext){

		$filename  = $name_slug.'-'.time().'-'.rand(1111111,9999999).'.'.$img_ext;

		/*directory create*/
		if (!file_exists('assets/images/push/'))
		   mkdir('assets/images/push/', 0777, true);

		$path = public_path('assets/images/push/' . $filename);
		\Image::make($img_location)->resize(800, 500)->save($path);

		$push_image='assets/images/push/'.$filename;
		return asset($push_image);
	}

	
	/********************************************
    ## CompanyImageUpload
    *********************************************/

	 public static function CompanyImageUpload($img_location, $company_name_slug, $img_ext){

	  $filename  = $company_name_slug.'-'.time().'-'.rand(1111111,9999999).'.'.$img_ext;

	  /*directory create*/
		if (!file_exists('assets/images/company/'))
		   mkdir('assets/images/company/', 0777, true);

	    $path = public_path('assets/images/company/' . $filename);
	    \Image::make($img_location)->resize(117, 30)->save($path);

		/*directory create*/
		if (!file_exists('assets/images/company/small-icon/'))
		   mkdir('assets/images/company/small-icon/', 0777, true);

		$path2 = public_path('assets/images/company/small-icon/' . $filename);
		\Image::make($img_location)->resize(24, 24)->save($path2);

		/*directory create*/
		if (!file_exists('assets/images/company/main-icon/'))
		   mkdir('assets/images/company/main-icon/', 0777, true);

		$path3 = public_path('assets/images/company/main-icon/' . $filename);
		\Image::make($img_location)->resize(400, 100)->save($path3);

	  $company_logo=$filename;
	  return $company_logo;
	 }



	/********************************************
    ## HomeSliderImageUpload
    *********************************************/

	public static function HomeSliderImageUpload($img_location, $name_slug, $img_ext){

		$filename  = $name_slug.'-'.time().'-'.rand(1111111,9999999).'.'.$img_ext;

		/*directory create*/
		if (!file_exists('assets/images/slider/'))
		   mkdir('assets/images/slider/', 0777, true);

		$path = public_path('assets/images/slider/' . $filename);
		\Image::make($img_location)->resize(1280, 500)->save($path);

		/*directory create*/
		if (!file_exists('assets/images/slider/small-icon/'))
		   mkdir('assets/images/slider/small-icon/', 0777, true);

		$path2 = public_path('assets/images/slider/small-icon/' . $filename);
		\Image::make($img_location)->resize(50, 50)->save($path2);

		$home_slider_image=$filename;
		return $home_slider_image;
	}


	/********************************************
    ## SliderPopupImageUpload
    *********************************************/

	public static function SliderPopupImageUpload($img_location, $name_slug, $img_ext){

		$filename  = $name_slug.'-'.time().'-'.rand(1111111,9999999).'.'.$img_ext;

		/*directory create*/
		if (!file_exists('assets/images/slider/popup/'))
		   mkdir('assets/images/slider/popup/', 0777, true);

		$path = public_path('assets/images/slider/popup/' . $filename);
		\Image::make($img_location)->resize(200, 100)->save($path);

		$home_slider_image=$filename;
		return $home_slider_image;
	}


	/********************************************
    ## MarchantLogoUpload
    *********************************************/

	public static function MarchantLogoUpload($img_location, $name_slug, $img_ext){

		$filename  = $name_slug.'-'.time().'-'.rand(1111111,9999999).'.'.$img_ext;

		/*directory create*/
		if (!file_exists('assets/images/merchant/'))
		   mkdir('assets/images/merchant/', 0777, true);

		$path = public_path('assets/images/merchant/' . $filename);
		\Image::make($img_location)->resize(200, 200)->save($path);

		/*directory create*/
		if (!file_exists('assets/images/merchant/small-icon/'))
		   mkdir('assets/images/merchant/small-icon/', 0777, true);

		$path2 = public_path('assets/images/merchant/small-icon/' . $filename);
		\Image::make($img_location)->resize(50, 50)->save($path2);

		$merchant_image=$filename;
		return $merchant_image;
	}


	/********************************************
    ## MerchantFeaturedImageUpload
    *********************************************/

	public static function MerchantFeaturedImageUpload($img_location, $name_slug, $img_ext){

		$filename  = $name_slug.'-'.time().'-'.rand(1111111,9999999).'.'.$img_ext;

		/*directory create*/
		if (!file_exists('assets/images/merchant-featured/'))
		   mkdir('assets/images/merchant-featured/', 0777, true);

		$path = public_path('assets/images/merchant-featured/' . $filename);
		\Image::make($img_location)->resize(800, 500)->save($path);

		/*directory create*/
		if (!file_exists('assets/images/merchant-featured/small-icon/'))
		   mkdir('assets/images/merchant-featured/small-icon/', 0777, true);

		$path2 = public_path('assets/images/merchant-featured/small-icon/' . $filename);
		\Image::make($img_location)->resize(50, 50)->save($path2);

		$merchant_featured_image=$filename;
		return $merchant_featured_image;
	}




	/********************************************
    ## SubCategoryImageUpload
    *********************************************/

	public static function SubCategoryImageUpload($img_location, $name_slug, $img_ext){

		$filename  = $name_slug.'-'.time().'-'.rand(1111111,9999999).'.'.$img_ext;

		/*directory create*/
		if (!file_exists('assets/images/sub-category/'))
		   mkdir('assets/images/sub-category/', 0777, true);

		$path = public_path('assets/images/sub-category/' . $filename);
		
		\Image::make($img_location)->resize(300, 350)->save($path);

		/*directory create*/
		if (!file_exists('assets/images/sub-category/small-icon/'))
		   mkdir('assets/images/sub-category/small-icon/', 0777, true);

		$path2 = public_path('assets/images/sub-category/small-icon/' . $filename);
		\Image::make($img_location)->resize(50, 50)->save($path2);

		$sub_category_image='assets/images/sub-category/'.$filename;
		return $sub_category_image;
	}


	/********************************************
    ## CouponImageUpload
    *********************************************/

	public static function CouponImageUpload($img_location, $name_slug, $img_ext){

		$filename  = $name_slug.'-'.time().'-'.rand(1111111,9999999).'.'.$img_ext;

		/*directory create*/
		if (!file_exists('assets/images/coupon/'))
		   mkdir('assets/images/coupon/', 0777, true);

		$path = public_path('assets/images/coupon/' . $filename);
		\Image::make($img_location)->resize(800, 500)->save($path);

		/*directory create*/
		if (!file_exists('assets/images/coupon/small-icon/'))
		   mkdir('assets/images/coupon/small-icon/', 0777, true);

		$path2 = public_path('assets/images/coupon/small-icon/' . $filename);
		\Image::make($img_location)->resize(50, 50)->save($path2);

		$coupon_image='assets/images/coupon/'.$filename;
		return $coupon_image;
	}


	 /********************************************
	    ## ForgotPasswordEmail 
	 *********************************************/
	 public static function ForgotPasswordEmail($users_id,$reset_url){

	 	$user_info=\DB::table('users')->where('id',$users_id)->first();

	 	if(isset($user_info)){
	 		$users_id=$user_info->id;
	 		$users_type=$user_info->user_type;
	 		$users_email=$user_info->email;

	 	}else{
	 		return \Redirect::back()->with('message',"Invalid User ID!");
	 	}    

	 	$data['user_info'] = $user_info;
	 	$data['reset_url'] = $reset_url;

	 	$user_email = $users_email;
	 	$user_name = $user_info->name;


	 	\Mail::send('forgot.forget-password-mail', $data, function($message) use ($user_email,$user_name) {
	 		
	 		$message->to($user_email,$user_name)->subject('Password Recovery');

	 	});

	 	return true;
	 }

	/********************************************
    ## CouponJsonResponce
    *********************************************/
    public static function CouponJsonResponce($responce_code,$message){

        $now=date('Y-m-d H:i:s');
        
        if($responce_code == '200'){
	        $response["success"]= [
	            "statusCode"=> $responce_code,
	            "successMessage"=> $message,
	            "serverReferenceCode"=> $now
	        ];
	    }elseif($responce_code == '403'){
	        $response["errors"]= [
	            "statusCode"=> $responce_code,
	            "errorMessage"=> $message,
	            "serverReferenceCode"=> $now
	        ];
	    }else{
	    	$response["errors"]= [
	            "statusCode"=> $responce_code,
	            "errorMessage"=> $message,
	            "serverReferenceCode"=> $now
	        ];
	    }
    	return $response;
    }	

    /********************************************
    ## IMEIChecker
    *********************************************/
    // public static function IMEIChecker($imei_no){
    // 	return $imei_no;
    // }

    /********************************************
    ## AppKeyChecker
    *********************************************/
    // public static function AppKeyChecker($app_key){

    // 	return $app_key;
    // }


    /********************************************
    ## SendSMSForUserRegistration
    *********************************************/
    // public static function SendSMSForUserRegistration($customer_mobile,$client_otp){

    // 	$message= "Your confirmation code is ".$client_otp.", Please submit this code for confirm registration in Yess";

    // 	return $message;
    // }


    /********************************************
    ## SendSMSForActiveCoupon
    *********************************************/
    // public static function SendSMSForActiveCoupon($customer_mobile, $merchant_name, $branch_name){

    // 	$message= "You are succeessfully activate a coupon from Yess. Please go to ".$merchant_name." ,".$branch_name." outlet and confirm this for getting discount ";

    // 	return $message;
    // }


    /********************************************
    ## SendSMSForBuyCoupon
    *********************************************/
    // public static function SendSMSForBuyCoupon($customer_mobile,$coupon_secret_code){

    // 	$message= "You are succeessfully buy a coupon from Yess,Please Submit this".$coupon_secret_code." code for confirmation.";

    // 	return $message;
    // }













############################ End #################################
}
