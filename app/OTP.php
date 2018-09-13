<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Classes\CustomLogger;

class OTP extends Model
{
    
	
    /********************************************
    ## SendSMSForUserRegistration
    *********************************************/
    public static function SendSMSForUserRegistration($customer_mobile,$client_otp){

    	$message= "Your verification code is ".$client_otp.", Please submit this code for verify your mobile number.  For more details call 09614500206";
        \App\SMS::SendSMS($customer_mobile, $message);

    	return true;
    }


    /********************************************
    ## SendSMSForUserDirectRegistration
    *********************************************/
    public static function SendSMSForUserDirectRegistration($customer_mobile, $password){

        $message= "You are succeessfully registerd in YESS. Your password is ".$password.". For more details call 09614500206 ";
        \App\SMS::SendSMS($customer_mobile, $message);
        return true;
    }


    /********************************************
    ## SendSMSForActiveCoupon
    *********************************************/
    public static function SendSMSForActiveCoupon($customer_mobile, $merchant_name, $branch_name, $branch_address, $coupon_discount_rate){

    	$message= "You are successfully activate a coupon from Yess. Please go to ".$merchant_name." outlet at ".$branch_address." and getting your ".$coupon_discount_rate."% discount.";
        \App\SMS::SendSMS($customer_mobile, $message);

    	return true;
    }


    /********************************************
    ## SendSMSForBuyCoupon
    *********************************************/
    public static function SendSMSForBuyCoupon($branch_mobile,$coupon_secret_code,$customer_mobile, $transaction_amount, $coupon_discount_amount){

        //$message= $customer_mobile." are succeessfully buy ".$transaction_amount." Tk from Yess, Discount amount is ".$coupon_discount_amount." Please give him this ".$coupon_secret_code." code for confirmation.";
    	$message="Mobile No: ".$customer_mobile.", Secret Code : ".$coupon_secret_code.", Shopping Amount : ".$transaction_amount.", Discount Amount : ".$coupon_discount_amount.". Please give this code to user to verify mobile number.";
        \App\SMS::SendSMS($branch_mobile, $message);

    	return true;
    }

    /********************************************
    ## SendSMSForSuccess
    *********************************************/
    public static function SendSMSForSuccess($customer_mobile, $coupon_transaction_id, $coupon_shopping_amount, $coupon_discount_amount){

    	$message= "Your Coupon number is ".$coupon_transaction_id.". Your Shopping amount ".$coupon_shopping_amount.", discount amount ".$coupon_discount_amount.". Thank You for Shopping!";
        \App\SMS::SendSMS($customer_mobile, $message);

    	return true;
    }


    /********************************************
    ## SendSMSForForgetPassword
    *********************************************/
    public static function SendSMSForForgetPassword($customer_mobile, $user_meta_otp){

        $message= "Your mobile verification code is ".$user_meta_otp." , Please submit this code for change your account password in Yess.";
        \App\SMS::SendSMS($customer_mobile, $message);
        return true;
    }


    
}
