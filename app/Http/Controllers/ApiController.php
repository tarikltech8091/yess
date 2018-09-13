<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Auth;
use App\User;
use App\Admin;
use DB;
use Webpatser\Uuid\Uuid;

class ApiController extends Controller
{
    public function __construct(){
        $this->page_title = \Request::route()->getName();
        $this->request_id =\App\Api::RequestLogWrite(\Request::all());

    }

    /********************************************
    ## GetAccessToken 
    *********************************************/
    public function GetAccessToken(){
        $now=date('Y-m-d H:i:s');

        try{

            $accessinfo = \Request::input('accessinfo');

            $imei_no= isset($accessinfo['imei_no']) ? \App\Api::IMEIChecker($accessinfo['imei_no']):'';
            $app_key= isset($accessinfo['app_key']) ? \App\Api::AppKeyChecker($accessinfo['app_key']):'';
           
            $uuid=\Webpatser\Uuid\Uuid::generate(4);

            if(!empty($imei_no) && !empty($app_key)){

                $get_info=\DB::table('table_app_token')->where('imei_no',$imei_no)->where('app_key',$app_key)->first();
                if(empty($get_info)){
                    $table_app_token_data=[
                                        "imei_no"=>$imei_no,
                                        "app_key"=>$app_key,
                                        "access_token"=>$uuid->string,
                                        "client_ip"=>$accessinfo['access_client_ip'],
                                        "access_browser"=>$accessinfo['access_browser'],
                                        "access_city"=>$accessinfo['access_city'],
                                        "access_division"=>$accessinfo['access_division'],
                                        "access_country"=>$accessinfo['access_country'],
                                        "referenceCode"=>$now,
                                        "token_status"=>1,
                                        "created_at"=>$now,
                                        "updated_at"=>$now,
                                    ];

                    $response["success"]= [
                        "statusCode"=> 200,
                        "successMessage"=> "Access Token Created Successfully",
                        "serverReferenceCode"=>$now
                    ];

                    $response["access_token"]=$uuid->string;

                    $requestlog_update_data=[
                        "request_response"=>json_encode($response),
                        "updated_at"=>$now,
                    ];

                    \DB::table('request_log')->where('request_id',$this->request_id)->update($requestlog_update_data);

                    $table_app_token_info=\DB::table('table_app_token')->insert($table_app_token_data);
                    \App\Api::ResponseLogWrite('insert,table_app_token',json_encode($table_app_token_data));

                    return \Response::json($response);


                }else{


                    $response["success"]= [
                        "statusCode"=> 200,
                        "successMessage"=> "Access Token already created",
                        "serverReferenceCode"=>$now
                    ];

                    $response["access_token"]=$get_info->access_token;

                    $requestlog_update_data=[
                        "request_response"=>json_encode($response),
                        "updated_at"=>$now,
                    ];

                    \DB::table('request_log')->where('request_id',$this->request_id)->update($requestlog_update_data);
                    \App\Api::ResponseLogWrite('existing,table_app_token',json_encode($response));

                    return \Response::json($response);
                }
            }else{
                $response["errors"]= [
                        "statusCode"=> 403,
                        "errorMessage"=> "IMEI Number or App Key is Missing",
                        "serverReferenceCode"=> $now
                    ];

                \DB::table('request_log')->where('request_id',$this->request_id)->update(array("request_response" =>json_encode($response),"updated_at"=>$now));

                \App\Api::ResponseLogWrite('IMEI Number or App Key is Missing',json_encode($response));

                return \Response::json($response);
            }

        }catch(\Exception $e){

            $response["errors"]= [
                    "statusCode"=> 501,
                    "errorMessage"=> "Missing or incorrect data, Sorry the requested resource does not exist",
                    "serverReferenceCode"=> $now,
                ];

            \DB::table('request_log')->where('request_id',$this->request_id)->update(array("request_response" =>json_encode($response),"updated_at"=>$now));

            $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();

            \App\System::ErrorLogWrite($message);
            \App\Api::ResponseLogWrite($message,json_encode($response));
            return \Response::json($response);
        }


    }


    /********************************************
    ## ClientDirectRegistration
    *********************************************/

    public function ClientDirectRegistration(){
        $now=date('Y-m-d H:i:s');

        try{

            $accessinfo = \Request::input('accessinfo');
            $userinfo = \Request::input('userinfo');
            $imei_no= isset($accessinfo['imei_no']) ? trim($accessinfo['imei_no']):'';
            $access_token= isset($accessinfo['access_token']) ? trim($accessinfo['access_token']):'';


            $get_info=\DB::table('table_app_token')->where('imei_no',$imei_no)->where('access_token',$access_token)->first();

            if(!empty($get_info) && !empty($userinfo)){

                $user_mobile=$userinfo['mobile'];
                $push_token = $userinfo['push_token'];
                $user_platform = $userinfo['user_platform'];

                $client_user_info=\DB::table('users')->where('mobile',$user_mobile)->first();

                if(empty($client_user_info) && is_numeric($user_mobile) && strlen($user_mobile)==11){

                    $password = mt_rand(1000, 9999);
                    $name = 'Unknown';
                    $name_slug = explode(' ',$name);
                    $name_slug = implode('_', $name_slug);

                        $client_registration_confirm=array(
                            'name' => $name,
                            'name_slug' => $name_slug,
                            'user_name' => $user_mobile,
                            'user_type' => 'client',
                            'login_status' => 0,
                            'status' => 1,
                            'mobile' => $user_mobile,
                            'push_token' => $push_token,
                            'user_platform' => $user_platform,
                            'registration_platform' => $user_platform,
                            'email' =>'',
                            'password' => bcrypt($password),
                            'created_at' => $now,
                            'updated_at' => $now,
                        );

                        $user_info=array(
                            'mobile' => $user_mobile,
                            'password' =>$password
                        );

                        $client_registration_info=\DB::table('users')->insertGetId($client_registration_confirm);
                        \App\System::EventLogWrite('insert,users',json_encode($client_registration_confirm));
                        $otp_send=\App\OTP::SendSMSForUserDirectRegistration($user_mobile,$password);


                        $response["success"]= [
                            "statusCode"=> 200,
                            "successMessage"=> "Registration Successfully.",
                            "serverReferenceCode"=>$now
                        ];

                        $response["clientinfo"]=$client_registration_info;
                        $response["userinfo"]=$user_info;


                        $requestlog_update_data=[
                            "request_response"=>json_encode($response),
                            "updated_at"=>$now,
                        ];

                        \DB::table('request_log')->where('request_id',$this->request_id)->update($requestlog_update_data);
                        \App\Api::ResponseLogWrite('Registration Successfully',json_encode($response));
                        return \Response::json($response);


                }else{
                    $response["errors"]= [
                            "statusCode"=> 403,
                            "errorMessage"=> "You are already registered user.",
                            "serverReferenceCode"=> $now
                        ];

                    \DB::table('request_log')->where('request_id',$this->request_id)->update(array("request_response" =>json_encode($response),"updated_at"=>$now));

                    \App\Api::ResponseLogWrite('You are already registered user.',json_encode($response));

                    return \Response::json($response);
                } 


            }else{

                $response["errors"]= [
                        "statusCode"=> 403,
                        "errorMessage"=> "IMEI Number or Access Token is invalid",
                        "serverReferenceCode"=> $now
                    ];

                \DB::table('request_log')->where('request_id',$this->request_id)->update(array("request_response" =>json_encode($response),"updated_at"=>$now));

                \App\Api::ResponseLogWrite('IMEI Number or Access Token is invalid',json_encode($response));

                return \Response::json($response);
            }

        }catch(\Exception $e){

            $response["errors"]= [
                    "statusCode"=> 501,
                    "errorMessage"=> "Missing or incorrect data, Sorry the requested resource does not exist",
                    "serverReferenceCode"=> $now,
                ];

            \DB::table('request_log')->where('request_id',$this->request_id)->update(array("request_response" =>json_encode($response),"updated_at"=>$now));

            $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();

            \App\System::ErrorLogWrite($message);
            \App\Api::ResponseLogWrite($message,json_encode($response));
            return \Response::json($response);
        }


    }


    /********************************************
    ## ClientDirectLogin
    *********************************************/
    public function ClientDirectLogin(){
        $now=date('Y-m-d H:i:s');
        try{

            $accessinfo = \Request::input('accessinfo');
            $logininfo = \Request::input('logininfo');
            $imei_no= isset($accessinfo['imei_no']) ? trim($accessinfo['imei_no']):'';
            $access_token= isset($accessinfo['access_token']) ? trim($accessinfo['access_token']):'';

            $get_info=\DB::table('table_app_token')->where('imei_no',$imei_no)->where('access_token',$access_token)->first();


            if(!empty($get_info) && !empty($logininfo)){

                    $user_mobile = $logininfo['mobile'];
                    $push_token = $logininfo['push_token'];
                    $user_platform =$logininfo['user_platform'];


                    $client_user_info=\DB::table('users')->where('mobile',$user_mobile)->where('status','1')->first();

                if(!empty($client_user_info)){

                        $credentials = [
                            'mobile' =>$user_mobile,
                        ];

                        $response["success"]= [
                            "statusCode"=> 200,
                            "successMessage"=> "Successfully Login.",
                            "serverReferenceCode"=>$now
                        ];

                        $response["logininfo"]= $client_user_info;

                        $requestlog_update_data=[
                            "request_response"=>json_encode($response),
                            "updated_at"=>$now,
                        ];
                        $user_update_data=[
                            "push_token"=>$push_token,
                            "user_platform"=>$user_platform,
                            "updated_at"=>$now,
                        ];
                                                
                        $client_registration_update=\DB::table('users')->where('id', $client_user_info->id)->update($user_update_data);

                        \DB::table('request_log')->where('request_id',$this->request_id)->update($requestlog_update_data);

                        \App\Api::ResponseLogWrite('Successfully Login.',json_encode($response));

                        return \Response::json($response);

                }else{
                    $response["errors"]= [
                        "statusCode"=> 403,
                        "errorMessage"=> "Invalid user or block user.",
                        "serverReferenceCode"=> $now
                    ];

                    \DB::table('request_log')->where('request_id',$this->request_id)->update(array("request_response" =>json_encode($response),"updated_at"=>$now));

                    \App\Api::ResponseLogWrite('Invalid user.',json_encode($response));

                    return \Response::json($response);
                }

            }else{

                $response["errors"]= [
                        "statusCode"=> 403,
                        "errorMessage"=> "IMEI Number or Access Token is invalid",
                        "serverReferenceCode"=> $now
                    ];

                \DB::table('request_log')->where('request_id',$this->request_id)->update(array("request_response" =>json_encode($response),"updated_at"=>$now));

                \App\Api::ResponseLogWrite('IMEI Number or Access Token is invalid',json_encode($response));

                return \Response::json($response);
            }

        }catch(\Exception $e){

            $response["errors"]= [
                    "statusCode"=> 501,
                    "errorMessage"=> "Missing or incorrect data, Sorry the requested resource does not exist",
                    "serverReferenceCode"=> $now,
                ];

            \DB::table('request_log')->where('request_id',$this->request_id)->update(array("request_response" =>json_encode($response),"updated_at"=>$now));

            $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();

            \App\System::ErrorLogWrite($message);
            \App\Api::ResponseLogWrite($message,json_encode($response));
            return \Response::json($response);
        }


    }




    /********************************************
    ## UserRegistration 
    *********************************************/
    public function UserRegistrationData(){
        $now=date('Y-m-d H:i:s');
        try{

            $accessinfo = \Request::input('accessinfo');
            $userinfo = \Request::input('userinfo');
            $imei_no= isset($accessinfo['imei_no']) ? trim($accessinfo['imei_no']):'';
            $access_token= isset($accessinfo['access_token']) ? trim($accessinfo['access_token']):'';

            $get_info=\DB::table('table_app_token')->where('imei_no',$imei_no)->where('access_token',$access_token)->first();


            if(!empty($get_info) && !empty($userinfo)){

                    $name = $userinfo['name'];
                    $user_mobile = $userinfo['mobile'];
                    $password = $userinfo['password'];

                $client_user_info=\DB::table('users')->where('mobile',$user_mobile)->first();


                if(empty($client_user_info) && is_numeric($user_mobile) && strlen($user_mobile)==11){

                    $client_otp=mt_rand(1000, 9999);
                    $client_data_info=array($name,$password,$client_otp);
                    $client_data=serialize($client_data_info);

                    $client_meta_info=\DB::table('tbl_user_meta')->where('user_id',$user_mobile)->where('user_meta_field_name','client_varify')->first();


                    $client_meta_data_info=array(
                        'user_id' =>$user_mobile,
                        'user_meta_field_name' => 'client_varify',
                        'user_meta_field_value' =>$client_data,
                        'created_at' => $now,
                        'updated_at' => $now,
                        );


                    $response["success"]= [
                        "statusCode"=> 200,
                        "successMessage"=> "User Registration Successfully, Now submit confirmation code for varify your mobile number",
                        "serverReferenceCode"=>$now
                    ];


                    $requestlog_update_data=[
                        "request_response"=>json_encode($response),
                        "updated_at"=>$now,
                    ];

                    \DB::table('request_log')->where('request_id',$this->request_id)->update($requestlog_update_data);

                    if(!empty($client_meta_info)){
                        $client_registration_save=\DB::table('tbl_user_meta')->where('user_meta_id',$client_meta_info->user_meta_id)->update($client_meta_data_info);
                        \App\Api::ResponseLogWrite('update,tbl_user_meta',json_encode($response));

                    }else{
                        $client_registration_save=\DB::table('tbl_user_meta')->insert($client_meta_data_info);
                        \App\Api::ResponseLogWrite('insert,tbl_user_meta',json_encode($response));

                    }

                    $otp_send=\App\OTP::SendSMSForUserRegistration($user_mobile,$client_otp);


                    return \Response::json($response);
                }else{
                    $response["errors"]= [
                        "statusCode"=> 403,
                        "errorMessage"=> "You are already registered user",
                        "serverReferenceCode"=> $now
                    ];

                    \DB::table('request_log')->where('request_id',$this->request_id)->update(array("request_response" =>json_encode($response),"updated_at"=>$now));

                    \App\Api::ResponseLogWrite('You are already registered user',json_encode($response));

                    return \Response::json($response);
                }

            }else{

                $response["errors"]= [
                        "statusCode"=> 403,
                        "errorMessage"=> "IMEI Number or Access Token is invalid",
                        "serverReferenceCode"=> $now
                    ];

                \DB::table('request_log')->where('request_id',$this->request_id)->update(array("request_response" =>json_encode($response),"updated_at"=>$now));

                \App\Api::ResponseLogWrite('IMEI Number or Access Token is invalid',json_encode($response));

                return \Response::json($response);
            }

        }catch(\Exception $e){

            $response["errors"]= [
                    "statusCode"=> 501,
                    "errorMessage"=> "Missing or incorrect data, Sorry the requested resource does not exist",
                    "serverReferenceCode"=> $now,
                ];

            \DB::table('request_log')->where('request_id',$this->request_id)->update(array("request_response" =>json_encode($response),"updated_at"=>$now));

            $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();

            \App\System::ErrorLogWrite($message);
            \App\Api::ResponseLogWrite($message,json_encode($response));
            return \Response::json($response);
        }


    }




    /********************************************
    ## ClientRegistrationConfirm 
    *********************************************/

    public function ClientRegistrationConfirm(){
        $now=date('Y-m-d H:i:s');

        try{

            $accessinfo = \Request::input('accessinfo');
            $userinfo = \Request::input('userinfo');
            $imei_no= isset($accessinfo['imei_no']) ? trim($accessinfo['imei_no']):'';
            $access_token= isset($accessinfo['access_token']) ? trim($accessinfo['access_token']):'';


            $get_info=\DB::table('table_app_token')->where('imei_no',$imei_no)->where('access_token',$access_token)->first();

            if(!empty($get_info) && !empty($userinfo)){
                $user_mobile=$userinfo['mobile'];


                $client_user_info=\DB::table('users')->where('mobile',$user_mobile)->first();
                if(empty($client_user_info)){
                    $client_meta_info=\DB::table('tbl_user_meta')->where('user_id',$user_mobile)->where('user_meta_field_name','client_varify')->first();

                    if(!empty($client_meta_info)){


                            $otp_confirm = $userinfo['otp'];
			    $push_token = $userinfo['push_token'];
			    $user_platform = $userinfo['user_platform'];
                            $client_data=unserialize($client_meta_info->user_meta_field_value);
                            $name = $client_data[0];
                            $password = $client_data[1];
                            $otp = $client_data[2];
                            $name_slug = explode(' ',$name);
                            $name_slug = implode('_', $name_slug);
                            if($otp == $otp_confirm){

                                $client_registration_confirm=array(
                                    'name' => $name,
                                    'name_slug' => $name_slug,
                                    'user_name' => $user_mobile,
                                    'user_type' => 'client',
                                    'login_status' => 0,
                                    'status' => 1,
                                    'mobile' => $user_mobile,
				    'push_token' => $push_token,
				    'user_platform' => $user_platform,
                                    'registration_platform' => $user_platform,
                                    'email' =>'',
                                    'password' => bcrypt($password),
                                    'created_at' => $now,
                                    'updated_at' => $now,
                                    );

                                $client_registration_save=\DB::table('users')->insertGetId($client_registration_confirm);
                                \App\System::EventLogWrite('insert,users',json_encode($client_registration_confirm));


                                $response["success"]= [
                                    "statusCode"=> 200,
                                    "successMessage"=> "Registration Successfully! Login To Continue.",
                                    "serverReferenceCode"=>$now
                                ];
								
								$new_client_data = \DB::table('users')->where('id',$client_registration_save)->first();

                                $response["userinfo"]=$new_client_data;


                                $requestlog_update_data=[
                                    "request_response"=>json_encode($response),
                                    "updated_at"=>$now,
                                ];

                                \DB::table('request_log')->where('request_id',$this->request_id)->update($requestlog_update_data);
                                return \Response::json($response);

                            }else{ 
                                $response["errors"]= [
                                        "statusCode"=> 403,
                                        "errorMessage"=> "Invalid Code",
                                        "serverReferenceCode"=> $now
                                    ];

                                \DB::table('request_log')->where('request_id',$this->request_id)->update(array("request_response" =>json_encode($response),"updated_at"=>$now));

                                \App\Api::ResponseLogWrite('Invalid Code',json_encode($response));

                                return \Response::json($response);
                            }



                    }else{
                        $response["errors"]= [
                                "statusCode"=> 403,
                                "errorMessage"=> "Something Wrong Please Try again.",
                                "serverReferenceCode"=> $now
                            ];

                        \DB::table('request_log')->where('request_id',$this->request_id)->update(array("request_response" =>json_encode($response),"updated_at"=>$now));

                        \App\Api::ResponseLogWrite('Something Wrong Please Try again.',json_encode($response));

                        return \Response::json($response);
                    }
                }else{
                    $response["errors"]= [
                            "statusCode"=> 403,
                            "errorMessage"=> "You are already registered user.",
                            "serverReferenceCode"=> $now
                        ];

                    \DB::table('request_log')->where('request_id',$this->request_id)->update(array("request_response" =>json_encode($response),"updated_at"=>$now));

                    \App\Api::ResponseLogWrite('You are already registered user.',json_encode($response));

                    return \Response::json($response);
                } 


            }else{

                $response["errors"]= [
                        "statusCode"=> 403,
                        "errorMessage"=> "IMEI Number or Access Token is invalid",
                        "serverReferenceCode"=> $now
                    ];

                \DB::table('request_log')->where('request_id',$this->request_id)->update(array("request_response" =>json_encode($response),"updated_at"=>$now));

                \App\Api::ResponseLogWrite('IMEI Number or Access Token is invalid',json_encode($response));

                return \Response::json($response);
            }

        }catch(\Exception $e){

            $response["errors"]= [
                    "statusCode"=> 501,
                    "errorMessage"=> "Missing or incorrect data, Sorry the requested resource does not exist",
                    "serverReferenceCode"=> $now,
                ];

            \DB::table('request_log')->where('request_id',$this->request_id)->update(array("request_response" =>json_encode($response),"updated_at"=>$now));

            $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();

            \App\System::ErrorLogWrite($message);
            \App\Api::ResponseLogWrite($message,json_encode($response));
            return \Response::json($response);
        }


    }




    /********************************************
    ## ResendCodeForClientRegistration
    *********************************************/
    public function ResendCodeForClientRegistration(){
        $now=date('Y-m-d H:i:s');

        try{

            $accessinfo = \Request::input('accessinfo');
            $resendinfo = \Request::input('resendinfo');

            $imei_no= isset($accessinfo['imei_no']) ? trim($accessinfo['imei_no']):'';
            $access_token= isset($accessinfo['access_token']) ? trim($accessinfo['access_token']):'';

            $get_info=\DB::table('table_app_token')->where('imei_no',$imei_no)->where('access_token',$access_token)->first();

            if(!empty($get_info) && !empty($resendinfo)){
                
                $client_mobile= $resendinfo['client_mobile'];

                $select_user_meta_info=\DB::table('tbl_user_meta')
                                        ->where('tbl_user_meta.user_meta_field_name','client_varify')
                                        ->where('tbl_user_meta.user_id',$client_mobile)
                                        ->first();

                if(!empty($select_user_meta_info)){


                    $client_data=unserialize($select_user_meta_info->user_meta_field_value);
                    $name = $client_data[0];
                    $password = $client_data[1];
                    $client_otp = $client_data[2]; 

                    \App\OTP::SendSMSForUserRegistration($client_mobile,$client_otp);
                
                    $get_responce=\App\Admin::CouponJsonResponce('200','Code send successfully.');
                    \DB::table('request_log')->where('request_id',$this->request_id)->update(array("request_response" =>json_encode($get_responce),"updated_at"=>$now));
                    \App\Api::ResponseLogWrite('Code send successfully.',json_encode($get_responce));
                    return \Response::json($get_responce);

                }else{

                    $get_responce=\App\Admin::CouponJsonResponce('403','Invalid Client Mobile Number.');

                    \DB::table('request_log')->where('request_id',$this->request_id)->update(array("request_response" =>json_encode($get_responce),"updated_at"=>$now));
                    \App\Api::ResponseLogWrite('Invalid Client Mobile.',json_encode($get_responce));

                    return \Response::json($get_responce);
                }


            }else{

                $get_responce=\App\Admin::CouponJsonResponce('403','IMEI Number or Access Token is invalid');

                \DB::table('request_log')->where('request_id',$this->request_id)->update(array("request_response" =>json_encode($get_responce),"updated_at"=>$now));
                \App\Api::ResponseLogWrite('IMEI Number or Access Token is invalid',json_encode($get_responce));

                return \Response::json($get_responce);
            }

        }catch(\Exception $e){

            $get_responce=\App\Admin::CouponJsonResponce('501','Missing or incorrect data, Sorry the requested resource does not exist');


            \DB::table('request_log')->where('request_id',$this->request_id)->update(array("request_response" =>json_encode($get_responce),"updated_at"=>$now));

            $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();

            \App\System::ErrorLogWrite($message);
            \App\Api::ResponseLogWrite($message,json_encode($get_responce));
            return \Response::json($get_responce);
        }

    }




    /********************************************
    ## ClientLogin
    *********************************************/
    public function ClientLogin(){
        $now=date('Y-m-d H:i:s');
        try{

            $accessinfo = \Request::input('accessinfo');
            $logininfo = \Request::input('logininfo');
            $imei_no= isset($accessinfo['imei_no']) ? trim($accessinfo['imei_no']):'';
            $access_token= isset($accessinfo['access_token']) ? trim($accessinfo['access_token']):'';

            $get_info=\DB::table('table_app_token')->where('imei_no',$imei_no)->where('access_token',$access_token)->first();


            if(!empty($get_info) && !empty($logininfo)){

                    $user_mobile = $logininfo['mobile'];
                    $password = $logininfo['password'];
		    $push_token = $logininfo['push_token'];
		    $user_platform = $logininfo['user_platform'];

                    $client_user_info=\DB::table('users')->where('mobile',$user_mobile)->where('status','1')->first();

                if(!empty($client_user_info)){

                    $credentials = [
                        'mobile' =>$user_mobile,
                        'password'=>$password,
                    ];


                    if(\Auth::attempt($credentials)){


                        $response["success"]= [
                            "statusCode"=> 200,
                            "successMessage"=> "Successfully Login.",
                            "serverReferenceCode"=>$now
                        ];

                        $response["logininfo"]= $client_user_info;

                        $requestlog_update_data=[
                            "request_response"=>json_encode($response),
                            "updated_at"=>$now,
                        ];

			$user_update_data=[
                            "push_token"=>$push_token,
                            "user_platform"=>$user_platform,
                            "updated_at"=>$now,
                        ];
						
			$client_registration_update=\DB::table('users')->where('id', $client_user_info->id)->update($user_update_data);

                        \DB::table('request_log')->where('request_id',$this->request_id)->update($requestlog_update_data);

                        \App\Api::ResponseLogWrite('Successfully Login.',json_encode($response));


                        return \Response::json($response);
                    }else{
                        $response["errors"]= [
                            "statusCode"=> 403,
                            "errorMessage"=> "Incorrect combinations. Please try again.",
                            "serverReferenceCode"=> $now
                        ];


                        \DB::table('request_log')->where('request_id',$this->request_id)->update(array("request_response" =>json_encode($response),"updated_at"=>$now));

                        \App\Api::ResponseLogWrite('Incorrect combinations.Please try again.',json_encode($response));

                        return \Response::json($response);
                    }

                }else{
                    $response["errors"]= [
                        "statusCode"=> 403,
                        "errorMessage"=> "Invalid user or block user.",
                        "serverReferenceCode"=> $now
                    ];

                    \DB::table('request_log')->where('request_id',$this->request_id)->update(array("request_response" =>json_encode($response),"updated_at"=>$now));

                    \App\Api::ResponseLogWrite('Invalid user.',json_encode($response));

                    return \Response::json($response);
                }

            }else{

                $response["errors"]= [
                        "statusCode"=> 403,
                        "errorMessage"=> "IMEI Number or Access Token is invalid",
                        "serverReferenceCode"=> $now
                    ];

                \DB::table('request_log')->where('request_id',$this->request_id)->update(array("request_response" =>json_encode($response),"updated_at"=>$now));

                \App\Api::ResponseLogWrite('IMEI Number or Access Token is invalid',json_encode($response));

                return \Response::json($response);
            }

        }catch(\Exception $e){

            $response["errors"]= [
                    "statusCode"=> 501,
                    "errorMessage"=> "Missing or incorrect data, Sorry the requested resource does not exist",
                    "serverReferenceCode"=> $now,
                ];

            \DB::table('request_log')->where('request_id',$this->request_id)->update(array("request_response" =>json_encode($response),"updated_at"=>$now));

            $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();

            \App\System::ErrorLogWrite($message);
            \App\Api::ResponseLogWrite($message,json_encode($response));
            return \Response::json($response);
        }


    }



    /********************************************
    ## ForgetPassword
    *********************************************/
    public function ForgetPassword(){
        $now=date('Y-m-d H:i:s');
        $current_date=date('Y-m-d').' 23:59:59';

        try{
            $accessinfo = \Request::input('accessinfo');
            $requestinfo = \Request::input('requestinfo');

            $imei_no= isset($accessinfo['imei_no']) ? trim($accessinfo['imei_no']):'';
            $access_token= isset($accessinfo['access_token']) ? trim($accessinfo['access_token']):'';

            $get_info=\DB::table('table_app_token')->where('imei_no',$imei_no)->where('access_token',$access_token)->first();

            if(!empty($get_info) && !empty($requestinfo)){

                $mobile_no= $requestinfo['mobile_no'];  

                $select_user_info=\DB::table('users')
                ->where('mobile', $mobile_no)
                ->first();

                if(!empty($select_user_info)){


                    $user_id=$select_user_info->id;
                    $user_meta_otp=mt_rand(1000, 9999);
                    $user_meta_info=\DB::table('tbl_user_meta')
                                    ->where('user_meta_field_name','forget_password')
                                    ->where('user_id',$user_id)
                                    ->first();

                    $user_meta_update_data=array(
                        'user_meta_field_name' => 'forget_password',
                        'user_meta_field_value' => $user_meta_otp,
                        'user_id' => $user_id,
                        'created_by' => $user_id,
                        'updated_by' => $user_id,
                        'created_at' => $now,
                        'updated_at' => $now,
                        );



                    if(!empty($user_meta_info)){
                        $user_point_update=\DB::table('tbl_user_meta')->where('user_meta_id',$user_meta_info->user_meta_id)->update(array("user_meta_field_value" =>$user_meta_otp));
                        \App\System::EventLogWrite('update,tbl_user_meta',json_encode($user_meta_info->user_meta_id));

                    }else{
                        $user_point_insert=\DB::table('tbl_user_meta')->insert($user_meta_update_data);
                        \App\System::EventLogWrite('update,tbl_user_meta',json_encode($user_meta_update_data));

                    }

                    $otp_send=\App\OTP::SendSMSForForgetPassword($mobile_no, $user_meta_otp);
                    $get_responce=\App\Admin::CouponJsonResponce('200','Successfully send OTP in your mobile.');
                    \DB::table('request_log')->where('request_id',$this->request_id)->update(array("request_response" =>json_encode($get_responce),"updated_at"=>$now));
                    \App\Api::ResponseLogWrite('Successfully send OTP in your mobile.',json_encode($get_responce));
                    return \Response::json($get_responce);


                }else{
                    $get_responce=\App\Admin::CouponJsonResponce('403','Invalid User ! Please try again.');

                    \DB::table('request_log')->where('request_id',$this->request_id)->update(array("request_response" =>json_encode($get_responce),"updated_at"=>$now));
                    \App\Api::ResponseLogWrite('Invalid User ! Please try again.',json_encode($get_responce));
                    return \Response::json($get_responce);
                }
            }else{

                $get_responce=\App\Admin::CouponJsonResponce('403','IMEI Number or Access Token is invalid');

                \DB::table('request_log')->where('request_id',$this->request_id)->update(array("request_response" =>json_encode($get_responce),"updated_at"=>$now));
                \App\Api::ResponseLogWrite('IMEI Number or Access Token is invalid',json_encode($get_responce));

                return \Response::json($get_responce);
            }

        }catch(\Exception $e){

            $get_responce=\App\Admin::CouponJsonResponce('501','Missing or incorrect data, Sorry the requested resource does not exist');


            \DB::table('request_log')->where('request_id',$this->request_id)->update(array("request_response" =>json_encode($get_responce),"updated_at"=>$now));

            $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();

            \App\System::ErrorLogWrite($message);
            \App\Api::ResponseLogWrite($message,json_encode($get_responce));
            return \Response::json($get_responce);
        }

    }



    /********************************************
    ## NewPasswordConfirm
    *********************************************/
    public function NewPasswordConfirm(){
        $now=date('Y-m-d H:i:s');
        $current_date=date('Y-m-d').' 23:59:59';

        try{
            $accessinfo = \Request::input('accessinfo');
            $requestinfo = \Request::input('requestinfo');

            $imei_no= isset($accessinfo['imei_no']) ? trim($accessinfo['imei_no']):'';
            $access_token= isset($accessinfo['access_token']) ? trim($accessinfo['access_token']):'';

            $get_info=\DB::table('table_app_token')->where('imei_no',$imei_no)->where('access_token',$access_token)->first();

            if(!empty($get_info) && !empty($requestinfo)){

                $mobile_no= $requestinfo['mobile_no'];  
                $new_password= $requestinfo['new_password'];  
                $otp= $requestinfo['otp'];  

                $select_user_info=\DB::table('users')->where('mobile',$mobile_no)->first();

                if(!empty($select_user_info)){


                    $user_id=$select_user_info->id;

                    $user_meta_info=\DB::table('tbl_user_meta')
                                    ->where('user_meta_field_name','forget_password')
                                    ->where('user_meta_field_value',$otp)
                                    ->where('user_id',$user_id)
                                    ->first();

                    if(!empty($user_meta_info)){

                        $password=bcrypt($new_password);

                        $user_update=\DB::table('users')->where('id',$user_id)->update(array("password" =>$password));
                        \App\System::EventLogWrite('update,users',json_encode($password));
                        $get_responce=\App\Admin::CouponJsonResponce('200','Password Updated Successfully.');
                        \DB::table('request_log')->where('request_id',$this->request_id)->update(array("request_response" =>json_encode($get_responce),"updated_at"=>$now));
                        \App\Api::ResponseLogWrite('Password Updated Successfully.',json_encode($get_responce));
                        return \Response::json($get_responce);

                    }else{

                        $get_responce=\App\Admin::CouponJsonResponce('403','Invalid OTP ! Please try again.');

                        \DB::table('request_log')->where('request_id',$this->request_id)->update(array("request_response" =>json_encode($get_responce),"updated_at"=>$now));
                        \App\Api::ResponseLogWrite('Invalid OTP ! Please try again.',json_encode($get_responce));
                        return \Response::json($get_responce);

                    }


                }else{
                    $get_responce=\App\Admin::CouponJsonResponce('403','Invalid User ! Please try again.');

                    \DB::table('request_log')->where('request_id',$this->request_id)->update(array("request_response" =>json_encode($get_responce),"updated_at"=>$now));
                    \App\Api::ResponseLogWrite('Invalid User ! Please try again.',json_encode($get_responce));
                    return \Response::json($get_responce);
                }
            }else{

                $get_responce=\App\Admin::CouponJsonResponce('403','IMEI Number or Access Token is invalid');

                \DB::table('request_log')->where('request_id',$this->request_id)->update(array("request_response" =>json_encode($get_responce),"updated_at"=>$now));
                \App\Api::ResponseLogWrite('IMEI Number or Access Token is invalid',json_encode($get_responce));

                return \Response::json($get_responce);
            }

        }catch(\Exception $e){

            $get_responce=\App\Admin::CouponJsonResponce('501','Missing or incorrect data, Sorry the requested resource does not exist');


            \DB::table('request_log')->where('request_id',$this->request_id)->update(array("request_response" =>json_encode($get_responce),"updated_at"=>$now));

            $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();

            \App\System::ErrorLogWrite($message);
            \App\Api::ResponseLogWrite($message,json_encode($get_responce));
            return \Response::json($get_responce);
        }

    }




     /********************************************
     ## ProfileUpdate 
     *********************************************/
        public function ProfileUpdate(){

            $now=date('Y-m-d H:i:s');
            try{

                $accessinfo = \Request::input('accessinfo');
                $imei_no= isset($accessinfo['imei_no']) ? trim($accessinfo['imei_no']):'';
                $access_token= isset($accessinfo['access_token']) ? trim($accessinfo['access_token']):'';
                $userinfo = \Request::input('userinfo');



                $get_info=\DB::table('table_app_token')->where('imei_no',$imei_no)->where('access_token',$access_token)->first();
                if(!empty($get_info) && !empty($userinfo)){

                    $user_id=$accessinfo['user_id'];
                    $user_info=\DB::table('users')->where('id',$user_id)->first();

                    if(!empty($userinfo['name'])){
                       $name=$userinfo['name'];
                       $name_slug = explode(' ', strtolower($name));
                       $name_slug = implode('_', $name_slug);
                    }else{
                        $name=$user_info->name;
                        $name_slug=$user_info->name_slug;
                    }

                    if(!empty($userinfo['email'])){
                       $email=$userinfo['email'];

                    }else{
                        $email=$user_info->email;
                    }

                    //if(!empty($userinfo['user_profile_image'])){

                        //$file_data = $userinfo['user_profile_image'];

                        //$image_path =\App\Admin::AppProfileImageUpload($file_data,$name_slug);
                        //$user_image=$image_path;

                    //}
                    //else{
                        $user_image=$user_info->user_profile_image;
                    //}

                    $users_update_data=[
                                    "name"=>$name,
                                    "name_slug"=>$name_slug,
                                    "user_profile_image"=>$user_image,
                                    "email"=>$email,
                                    "updated_at"=>$now,
                                ];

                    $response["success"]= [
                        "statusCode"=> 200,
                        //"successMessage"=> $user_image,
			"successMessage"=> "Successfully updated",
                        "serverReferenceCode"=>$now
                    ];


                    $requestlog_update_data=[
                        "request_response"=>json_encode($response),
                        "updated_at"=>$now,
                    ];
                    \DB::table('request_log')->where('request_id',$this->request_id)->update($requestlog_update_data);
                    $user_update_info=\DB::table('users')->where('id',$accessinfo['user_id'])->update($users_update_data);

                    $update_user_info=\DB::table('users')->where('id',$user_id)->first();

                    $response["user_info"]=$update_user_info;

                    \App\System::EventLogWrite('update,users',json_encode($users_update_data));
                    \App\Api::ResponseLogWrite('insert,users',json_encode($response));
                    return \Response::json($response);

                }else{
                     $response["errors"]= [
                        "statusCode"=> 403,
                        "errorMessage"=> "IMEI Number or Access Token is invalid",
                        "serverReferenceCode"=> $now
                    ];

                    \DB::table('request_log')->where('request_id',$this->request_id)->update(array("request_response" =>json_encode($response),"updated_at"=>$now));

                    \App\Api::ResponseLogWrite('IMEI Number or Access Token is invalid',json_encode($response));

                    return \Response::json($response);
                }

            }catch(\Exception $e){

                 $response["errors"]= [
                    "statusCode"=> 501,
                    "errorMessage"=>  $e->getMessage(),
                    "serverReferenceCode"=> $now,
                ];

                \DB::table('request_log')->where('request_id',$this->request_id)->update(array("request_response" =>json_encode($response),"updated_at"=>$now));

                $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();

                \App\System::ErrorLogWrite($message);
                \App\Api::ResponseLogWrite($message,json_encode($response));
                return \Response::json($response);
            }





    }


     /********************************************
     ## UserWishAndFollowList
     *********************************************/
        public function UserWishAndFollowList(){

            try{

                $now=date('Y-m-d H:i:s');

                $accessinfo = \Request::input('accessinfo');
                $imei_no= isset($accessinfo['imei_no']) ? trim($accessinfo['imei_no']):'';
                $access_token= isset($accessinfo['access_token']) ? trim($accessinfo['access_token']):'';
                $wishFollow_info = \Request::input('wishFollow_info');

                $get_info=\DB::table('table_app_token')->where('imei_no',$imei_no)->where('access_token',$access_token)->first();


                if(!empty($get_info) && !empty($wishFollow_info) ){

                    $info_type=$wishFollow_info['info_type'];
                    $type_id=$wishFollow_info['type_id'];

                    #validation check for type id
                    if(!empty($info_type) && !empty($type_id)){

                        $valid_type_info = \App\Api::TypeIdInfo($info_type,$type_id);

                        if(empty($valid_type_info)){
                            $response["errors"]= [
                            "statusCode"=> 403,
                            "errorMessage"=> "Info type or Type Id is Invalid",
                            "serverReferenceCode"=> $now
                            ];

                            \DB::table('request_log')->where('request_id',$this->request_id)->update(array("request_response" =>json_encode($response),"updated_at"=>$now));

                            \App\Api::ResponseLogWrite('Info type or Type Id is Invalid',json_encode($response));

                            return \Response::json($response);
                        }


                        #Wish/Follow list 
                        $wishFollow_info=\DB::table('tbl_follow_activity')
                                    ->where('activity_user_id',$accessinfo['user_id'])
                                    ->where('merchant_or_coupon_id',$type_id)
                                    ->where('activity_type',$info_type)
                                    ->first();

                        if(empty($wishFollow_info)){

                            $wishFollow_info_data=[
                                        "activity_type"=>$info_type,
                                        "activity_user_id"=>$accessinfo['user_id'],
                                        "merchant_or_coupon_id"=>$type_id,
                                        "activity_list_status"=>'1',
                                        "created_at"=>$now,
                                        "created_by"=>$accessinfo['user_id'],
                                        "updated_at"=>$now,
                                        "updated_by"=>$accessinfo['user_id'],
                                    ];


                            $response["success"]= [
                                "statusCode"=> 200,
                                "successMessage"=> "Add to Wish List or Follow Succefully",
                                "serverReferenceCode"=>$now
                            ];

                            $requestlog_update_data=[
                                "request_response"=>json_encode($response),
                                "updated_at"=>$now,
                            ];


                            \DB::table('request_log')->where('request_id',$this->request_id)->update($requestlog_update_data);
                            \DB::table('tbl_follow_activity')->insert($wishFollow_info_data);
                            \App\System::EventLogWrite('insert,tbl_follow_activity',json_encode($wishFollow_info_data));
                            \App\Api::ResponseLogWrite('insert,tbl_follow_activity',json_encode($requestlog_update_data));


                            return \Response::json($response);

                        }else{

                            $response["success"]= [
                                "statusCode"=> 200,
                                "successMessage"=> "You are already Following this",
                                "serverReferenceCode"=>$now
                            ];
                            \DB::table('request_log')->where('request_id',$this->request_id)->update(array("request_response" =>json_encode($response),"updated_at"=>$now));
                            \App\Api::ResponseLogWrite('You are already Following this',json_encode($response));


                            return \Response::json($response);
                        }

                    }else{
                        $response["errors"]= [
                        "statusCode"=> 403,
                        "errorMessage"=> "Info type or Type Id is required",
                        "serverReferenceCode"=> $now
                        ];

                        \DB::table('request_log')->where('request_id',$this->request_id)->update(array("request_response" =>json_encode($response),"updated_at"=>$now));

                        \App\Api::ResponseLogWrite('Info type or Type Id is required',json_encode($response));

                        return \Response::json($response);
                    }

                }else{
                    $response["errors"]= [
                        "statusCode"=> 403,
                        "errorMessage"=> "Sorry, the requested resource does not exist because Imei number or access token invalid",
                        "serverReferenceCode"=> "timestamp"
                    ];
                    \DB::table('request_log')->where('request_id',$this->request_id)->update(array("request_response" =>json_encode($response),"updated_at"=>$now));

                    return \Response::json($response);
                }

            }catch(\Exception $e){
                 $response["errors"]= [
                    "statusCode"=> 501,
                    "errorMessage"=> "Missing or incorrect data, Sorry the requested resource does not exist",
                    "serverReferenceCode"=> $now,
                ];

                \DB::table('request_log')->where('request_id',$this->request_id)->update(array("request_response" =>json_encode($response),"updated_at"=>$now));

                $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();

                \App\System::ErrorLogWrite($message);
                \App\Api::ResponseLogWrite($message,json_encode($response));
                return \Response::json($response);
            }


    }


     /********************************************
     ## UserWishAndFollowDelete
     *********************************************/
        public function UserWishAndFollowDelete(){

            try{

                $now=date('Y-m-d H:i:s');

                $accessinfo = \Request::input('accessinfo');
                $imei_no= isset($accessinfo['imei_no']) ? trim($accessinfo['imei_no']):'';
                $access_token= isset($accessinfo['access_token']) ? trim($accessinfo['access_token']):'';
                $wishFollow_info = \Request::input('wishFollow_info');

                $get_info=\DB::table('table_app_token')->where('imei_no',$imei_no)->where('access_token',$access_token)->first();

                if(!empty($get_info) && !empty($wishFollow_info) ){

                    $info_type=$wishFollow_info['info_type'];
                    $type_id=$wishFollow_info['type_id'];
                    $activity_id=$wishFollow_info['activity_id'];


                    #validation check for type id
                    if(!empty($info_type) && !empty($type_id)){

                        $valid_type_info = \App\Api::TypeIdInfo($info_type,$type_id);

                        if(empty($valid_type_info)){
                            $response["errors"]= [
                            "statusCode"=> 403,
                            "errorMessage"=> "Info type or Type Id is Invalid",
                            "serverReferenceCode"=> $now
                            ];

                            \DB::table('request_log')->where('request_id',$this->request_id)->update(array("request_response" =>json_encode($response),"updated_at"=>$now));

                            \App\Api::ResponseLogWrite('Info type or Type Id is Invalid',json_encode($response));

                            return \Response::json($response);
                        }


                        #Wish/Follow list 
                        $wishFollow_info=\DB::table('tbl_follow_activity')
                                    ->where('activity_user_id',$accessinfo['user_id'])
                                    ->where('merchant_or_coupon_id',$type_id)
                                    ->where('activity_type',$info_type)
                                    ->first();

                        if(!empty($wishFollow_info)){

                            $wish_list_delete=\DB::table('tbl_follow_activity')->where('activity_id',$activity_id)->delete();



                            $response["success"]= [
                                "statusCode"=> 200,
                                "successMessage"=> "Follow or Wish List  Delete Succefully",
                                "serverReferenceCode"=>$now
                            ];

                            $requestlog_update_data=[
                                "request_response"=>json_encode($response),
                                "updated_at"=>$now,
                            ];


                            \DB::table('request_log')->where('request_id',$this->request_id)->update($requestlog_update_data);
                            \App\System::EventLogWrite('delete,tbl_follow_activity',json_encode($wishFollow_info));
                            \App\Api::ResponseLogWrite('delete,tbl_follow_activity',json_encode($wishFollow_info));


                            return \Response::json($response);

                        }else{

                            $response["success"]= [
                                "statusCode"=> 200,
                                "successMessage"=> "Invalid Activity Id",
                                "serverReferenceCode"=>$now
                            ];
                            \DB::table('request_log')->where('request_id',$this->request_id)->update(array("request_response" =>json_encode($response),"updated_at"=>$now));
                            \App\Api::ResponseLogWrite('Invalid Activity Id',json_encode($response));


                            return \Response::json($response);
                        }

                    }else{
                        $response["errors"]= [
                        "statusCode"=> 403,
                        "errorMessage"=> "Info type or Type Id is required",
                        "serverReferenceCode"=> $now
                        ];

                        \DB::table('request_log')->where('request_id',$this->request_id)->update(array("request_response" =>json_encode($response),"updated_at"=>$now));

                        \App\Api::ResponseLogWrite('Info type or Type Id is required',json_encode($response));

                        return \Response::json($response);
                    }

                }else{
                    $response["errors"]= [
                        "statusCode"=> 403,
                        "errorMessage"=> "Sorry, the requested resource does not exist because Imei number or access token invalid",
                        "serverReferenceCode"=> "timestamp"
                    ];
                    \DB::table('request_log')->where('request_id',$this->request_id)->update(array("request_response" =>json_encode($response),"updated_at"=>$now));

                    return \Response::json($response);
                }

            }catch(\Exception $e){
                 $response["errors"]= [
                    "statusCode"=> 501,
                    "errorMessage"=> "Missing or incorrect data, Sorry the requested resource does not exist",
                    "serverReferenceCode"=> $now,
                ];

                \DB::table('request_log')->where('request_id',$this->request_id)->update(array("request_response" =>json_encode($response),"updated_at"=>$now));

                $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();

                \App\System::ErrorLogWrite($message);
                \App\Api::ResponseLogWrite($message,json_encode($response));
                return \Response::json($response);
            }


    }




    /********************************************
    ## ReviewAndRating
    *********************************************/
        public function ReviewAndRating(){
            $now=date('Y-m-d H:i:s');

            try{

                $accessinfo = \Request::input('accessinfo');
                $imei_no= isset($accessinfo['imei_no']) ? trim($accessinfo['imei_no']):'';
                $access_token= isset($accessinfo['access_token']) ? trim($accessinfo['access_token']):'';
                $reviewRating_info = \Request::input('reviewRating_info');

                $get_info=\DB::table('table_app_token')->where('imei_no',$imei_no)->where('access_token',$access_token)->first();
                if(!empty($get_info) && !empty($reviewRating_info)){

                        
                        $get_reviewrating_info=\DB::table('tbl_coupon_review_comments')
                                            ->where('coupon_id',$reviewRating_info['coupon'])
                                            ->where('customer_id',$accessinfo['user_id'])
                                            ->first();


                        $reviewRating_info_data=[
                                    "coupon_id"=>$reviewRating_info['coupon'],
                                    "customer_id"=>$accessinfo['user_id'],
                                    "coupon_rating"=>$reviewRating_info['rating'],
                                    "coupon_comments"=>$reviewRating_info['review'],
                                    "created_by"=>$accessinfo['user_id'],
                                    "updated_by"=>$accessinfo['user_id'],
                                    "created_at"=>$now,
                                    "updated_at"=>$now,
                                ];

                        if(!empty($get_reviewrating_info)){

                            $reviewRating_update_data=[
                                    "coupon_rating"=>$reviewRating_info['rating'],
                                    "coupon_comments"=>$reviewRating_info['review'],
                                    "updated_at"=>$now,
                                ];

                            $response["success"]= [
                                "statusCode"=> 200,
                                "successMessage"=> "Review Rating update Successfully",
                                "serverReferenceCode"=>$now
                            ];

                            $requestlog_update_data=[
                                "request_response"=>json_encode($response),
                                "updated_at"=>$now
                            ];
                            \DB::table('request_log')->where('request_id',$this->request_id)->update($requestlog_update_data);

                            \DB::table('tbl_coupon_review_comments')->where('review_comments_id',$get_reviewrating_info->review_comments_id)->update($reviewRating_update_data);
                            \App\Api::ResponseLogWrite('update,tbl_coupon_review_comments',json_encode($requestlog_update_data));
                            \App\System::EventLogWrite('update,tbl_coupon_review_comments',json_encode($reviewRating_update_data));

                            return \Response::json($response);


                        }else{
                            $response["success"]= [
                                "statusCode"=> 200,
                                "successMessage"=> "Review Rating Insert Successfully",
                                "serverReferenceCode"=>$now
                            ];

                            $requestlog_update_data=[
                                "request_response"=>json_encode($response),
                                "updated_at"=>$now,
                            ];
                            \DB::table('request_log')->where('request_id',$this->request_id)->update($requestlog_update_data);
                             \DB::table('tbl_coupon_review_comments')->insert($reviewRating_info_data);
                            \App\Api::ResponseLogWrite('insert,tbl_coupon_review_comments',json_encode($requestlog_update_data));

                            return \Response::json($response);
                        }


                }else{
                    $response["errors"]= [
                        "statusCode"=> 403,
                        "errorMessage"=> "Accessinfo or Review and Rating is required",
                        "serverReferenceCode"=> $now
                    ];

                    \DB::table('request_log')->where('request_id',$this->request_id)->update(array("request_response" =>json_encode($response),"updated_at"=>$now));
                    \App\Api::ResponseLogWrite('Sorry, the requested resource does not exist or IMEI number or Access token invalid',json_encode($response));

                    return \Response::json($response);
                }

            }catch(\Exception $e){

                 $response["errors"]= [
                    "statusCode"=> 501,
                    "errorMessage"=> "Missing or incorrect data, Sorry the requested resource does not exist",
                    "serverReferenceCode"=> $now,
                ];

                \DB::table('request_log')->where('request_id',$this->request_id)->update(array("request_response" =>json_encode($response),"updated_at"=>$now));

                $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();

                \App\System::ErrorLogWrite($message);
                \App\Api::ResponseLogWrite($message,json_encode($response));
                return \Response::json($response);
            }

    }


    /********************************************
     ## ActiveDealInfo
     *********************************************/
        public function ActiveDealInfo(){

            $now=date('Y-m-d H:i:s');
            $current_date=date('Y-m-d').' 23:59:59';
            $coupon_total_selled=0;

            try{
                $accessinfo = \Request::input('accessinfo');
                $activeDeal_info = \Request::input('activeDeal_info');

                $imei_no= isset($accessinfo['imei_no']) ? trim($accessinfo['imei_no']):'';
                $access_token= isset($accessinfo['access_token']) ? trim($accessinfo['access_token']):'';
                $user_id=$accessinfo['user_id'];

                $get_info=\DB::table('table_app_token')->where('imei_no',$imei_no)->where('access_token',$access_token)->first();

                if(!empty($get_info)){

                        $coupon= $activeDeal_info['coupon'];
                        $merchant= $activeDeal_info['merchant'];
                        $branch= $activeDeal_info['branch'];                   
                        $user_info=\DB::table('users')->where('id',$user_id)->first();
                    if(!empty($user_info)){

                        $coupon_info=\DB::table('tbl_coupon')
                                    ->where('tbl_coupon.coupon_id',$coupon)
                                    ->leftjoin('tbl_merchant','tbl_coupon.coupon_merchant_id','=','tbl_merchant.merchant_id')
                                    ->leftjoin('tbl_branch','tbl_coupon.coupon_branch_id','=','tbl_branch.branch_id')
                                    ->first();

                        if(!empty($coupon_info)){
                            $dateline=$coupon_info->coupon_closing_date;
                            $merchant_name=$coupon_info->merchant_name;
                            $branch_name=$coupon_info->branch_name;
                            $branch_address=$coupon_info->branch_address;
                            $customer_mobile=$user_info->mobile;
                            $coupon_discount_rate=$coupon_info->coupon_discount_rate;

                            if($dateline > $current_date){

                                if(($dateline >= $current_date) && (($coupon_info->coupon_max_limit >$coupon_info->coupon_total_selled) ||($coupon_info->coupon_max_limit == '-1'))){

                                        $coupon_transaction_info=\DB::table('tbl_coupon_transaction')
                                                                ->where('coupon_id',$coupon)
                                                                ->where('tbl_coupon_transaction.coupon_status','!=','2')
                                                                ->where('customer_id',$user_id)
                                                                ->first();
                                        if(empty($coupon_transaction_info)){

                                            $activeDeal_info_data=[
                                                'coupon_id' =>$coupon,
                                                'transaction_merchant_id' =>$merchant,
                                                'transaction_branch_id' =>$branch,
                                                'customer_id' =>$user_id,
                                                'customer_mobile' =>$user_info->mobile,
                                                'coupon_code' =>$coupon_info->coupon_code,
                                                'coupon_discount_rate' =>$coupon_info->coupon_discount_rate,
                                                'coupon_commission_rate' =>$coupon_info->coupon_commision_rate,
                                                'coupon_status' =>-1,
                                                'created_by' =>$user_id,
                                                'updated_by' => $user_id,
                                                'created_at' =>$now,
                                                'updated_at' => $now,
                                            ];

                                            $coupon_total_selled=($coupon_info->coupon_total_selled)+1;


                                            $response["success"]= [
                                                "statusCode"=> 200,
                                                "successMessage"=> "Deal Active is completed",
                                                "serverReferenceCode"=>time()
                                            ];


                                            $requestlog_update_data=[
                                                "request_response"=>json_encode($response),
                                                "updated_at"=>$now,
                                            ];

                                            \DB::table('request_log')->where('request_id',$this->request_id)->update($requestlog_update_data);

                                            \DB::table('tbl_coupon_transaction')->insert($activeDeal_info_data);

                                            $coupon_sell_update= \DB::table('tbl_coupon')->where('coupon_id',$coupon)->update(array("coupon_total_selled" =>$coupon_total_selled));

                                            \App\Api::ResponseLogWrite('insert,tbl_coupon_transaction',json_encode($requestlog_update_data));
                                            $active_coupon_sms=\App\OTP::SendSMSForActiveCoupon($customer_mobile, $merchant_name, $branch_name, $branch_address, $coupon_discount_rate);
                                            return \Response::json($response);

                                        }else{

                                          $response["success"]= [
                                                "statusCode"=> 200,
                                                "successMessage"=> "Deal is already activated",
                                                "serverReferenceCode"=>$now
                                            ];  

                                            \DB::table('request_log')->where('request_id',$this->request_id)->update(array("request_response" =>json_encode($response),"updated_at"=>$now));

                                            return \Response::json($response);

                                        }
                                }else{
                                    $response["errors"]= [
                                        "statusCode"=> 403,
                                        "errorMessage"=> "This coupon is stock out",
                                        "serverReferenceCode"=> $now
                                    ];


                                    return \Response::json($response);
                                }
                            }else{
                                $response["errors"]= [
                                    "statusCode"=> 403,
                                    "errorMessage"=> "This coupon time is expired",
                                    "serverReferenceCode"=> $now
                                ];


                                return \Response::json($response);
                            }

                        }else{
                            $response["errors"]= [
                                "statusCode"=> 403,
                                "errorMessage"=> "Coupon does not found.",
                                "serverReferenceCode"=> $now
                            ];


                            return \Response::json($response);
                        }
                    }else{
                        $response["errors"]= [
                            "statusCode"=> 403,
                            "errorMessage"=> "Invalid User Id",
                            "serverReferenceCode"=> $now
                        ];


                        return \Response::json($response);
                    }



                }else{
                    $response["errors"]= [
                        "statusCode"=> 403,
                        "errorMessage"=> "IMEI Number or Access Token is invalid",
                        "serverReferenceCode"=> $now
                    ];

                    \DB::table('request_log')->where('request_id',$this->request_id)->update(array("request_response" =>json_encode($response),"updated_at"=>$now));

                    return \Response::json($response);
                }

            }catch(\Exception $e){

                $response["errors"]= [
                    "statusCode"=> 501,
                    "errorMessage"=> "Missing or incorrect data, Sorry the requested resource does not exist",
                    "serverReferenceCode"=> $now,
                ];

                \DB::table('request_log')->where('request_id',$this->request_id)->update(array("request_response" =>json_encode($response),"updated_at"=>$now));

                $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();

                \App\System::ErrorLogWrite($message);
                \App\Api::ResponseLogWrite($message,json_encode($response));
                return \Response::json($response);
            }


    }



    /********************************************
    ## BuyCoupon
    *********************************************/
    public function BuyCoupon(){
        $now=date('Y-m-d H:i:s');
        $current_date=date('Y-m-d').' 23:59:59';

        try{
            $accessinfo = \Request::input('accessinfo');
            $activeDeal_info = \Request::input('activeDeal_info');

            $imei_no= isset($accessinfo['imei_no']) ? trim($accessinfo['imei_no']):'';
            $access_token= isset($accessinfo['access_token']) ? trim($accessinfo['access_token']):'';
            $user_id=$accessinfo['user_id'];

            $get_info=\DB::table('table_app_token')->where('imei_no',$imei_no)->where('access_token',$access_token)->first();

            if(!empty($get_info) && !empty($activeDeal_info)){
                $coupon_id= $activeDeal_info['coupon_id'];
                $coupon_transaction_id= $activeDeal_info['coupon_transaction_id'];
                $transaction_amount= $activeDeal_info['transaction_amount'];

                $active_deal_coupon_info=\DB::table('tbl_coupon_transaction')
                                        ->where('tbl_coupon_transaction.coupon_transaction_id', $coupon_transaction_id)
                                        ->where('tbl_coupon_transaction.coupon_id', $coupon_id)
                                        ->where('tbl_coupon_transaction.customer_id',$user_id)
                                        ->where('tbl_coupon_transaction.coupon_status','-1')
                                        ->first();
                if(!empty($active_deal_coupon_info)){


                    if (is_numeric($transaction_amount)){

                        $select_coupon_info=\DB::table('tbl_coupon')
                                            ->where('tbl_coupon.coupon_id', $coupon_id)
                                            ->leftjoin('tbl_merchant','tbl_coupon.coupon_merchant_id','=','tbl_merchant.merchant_id')
                                            ->leftjoin('tbl_branch','tbl_coupon.coupon_branch_id','=','tbl_branch.branch_id')
                                            ->first();
                        if(!empty($select_coupon_info)){

                            $branch_mobile=$select_coupon_info->branch_mobile;
                            $coupon_dateline=$select_coupon_info->coupon_closing_date;
                            $customer_mobile=$active_deal_coupon_info->customer_mobile;

                            if($coupon_dateline >= date('Y-m-d').'23:59:59'){

                                $customer_info=\DB::table('users')->where('id',$user_id)->first();
                                if(!empty($customer_info)){
                                    $customer_id=$customer_info->id;
                                    $customer_mobile=$customer_info->mobile;

                                }else{
                                    $customer_id='';
                                }
                                $user_id=$customer_id;


                                if(($select_coupon_info->coupon_applied_min_amount)<=$transaction_amount){

                                    $coupon_max_limit=$select_coupon_info->coupon_max_limit;
                                    $coupon_total_selled_info=$select_coupon_info->coupon_total_selled;

                                    if(($select_coupon_info->coupon_max_limit == -1) || ($coupon_max_limit>$coupon_total_selled_info)){

                                        \DB::beginTransaction();

                                            if($select_coupon_info->coupon_max_discount != 0){
                                                $coupon_discount_amount=(($transaction_amount*($select_coupon_info->coupon_discount_rate))/100);
                                                $coupon_commission_amount=(($transaction_amount*($select_coupon_info->coupon_commision_rate))/100);

                                                if($coupon_discount_amount>($select_coupon_info->coupon_max_discount)){
                                                    $coupon_discount_amount=$select_coupon_info->coupon_max_discount;
                                                    $coupon_commission_amount=$select_coupon_info->coupon_max_commission;
                                                }
                                            }else{
                                                $coupon_discount_amount=(($transaction_amount*($select_coupon_info->coupon_discount_rate))/100);
                                                $coupon_commission_amount=(($transaction_amount*($select_coupon_info->coupon_commision_rate))/100); 
                                            }

                                            $coupon_total_selled=($select_coupon_info->coupon_total_selled)+1;
                                            $coupon_total_sell_price=($select_coupon_info->coupon_total_sell_price)+($select_coupon_info->coupon_sele_price);
                                            $coupon_secret_code=mt_rand(10000, 99999);

                                            $coupon_transaction_data=array(
                                                'coupon_id' =>$select_coupon_info->coupon_id,
                                                'coupon_secret_code'=>$coupon_secret_code,
                                                'transaction_merchant_id' =>$select_coupon_info->coupon_merchant_id,
                                                'transaction_branch_id' =>$select_coupon_info->coupon_branch_id,
                                                'customer_id' =>$customer_id,
                                                'customer_mobile' =>$customer_mobile,
                                                'coupon_code' =>$select_coupon_info->coupon_code,
                                                'coupon_discount_rate' =>$select_coupon_info->coupon_discount_rate,
                                                'coupon_commission_rate' =>$select_coupon_info->coupon_commision_rate,
                                                'coupon_buy_price' =>$select_coupon_info->coupon_sele_price,
                                                'coupon_shopping_amount' =>$transaction_amount,
                                                'coupon_discount_amount' =>$coupon_discount_amount,
                                                'coupon_commission_amount' =>$coupon_commission_amount,
                                                'coupon_status' =>1,
                                                'created_by' =>$user_id,
                                                'updated_by' => $user_id,
                                                'created_at' =>$now,
                                                'updated_at' => $now,
                                                );


                                            $coupon_transaction_update_data=array(
                                                'coupon_secret_code'=>$coupon_secret_code,
                                                'coupon_discount_rate' =>$select_coupon_info->coupon_discount_rate,
                                                'coupon_commission_rate' =>$select_coupon_info->coupon_commision_rate,
                                                'coupon_buy_price' =>$select_coupon_info->coupon_sele_price,
                                                'coupon_shopping_amount' =>$transaction_amount,
                                                'coupon_discount_amount' =>$coupon_discount_amount,
                                                'coupon_commission_amount' =>$coupon_commission_amount,
                                                'coupon_status' =>1,
                                                'updated_by' => $user_id,
                                                'updated_at' => $now,
                                                );

                                            $coupon_update_data=array(
                                                'coupon_total_sell_price' => $coupon_total_sell_price,
                                                'updated_by' => $user_id,
                                                'updated_at' => $now,
                                                );


                                            if(!empty($active_deal_coupon_info)){
                                                $coupon_transaction_update=\DB::table('tbl_coupon_transaction')
                                                        ->where('coupon_id',$coupon_id)
                                                        ->where('customer_mobile',$customer_mobile)
                                                        ->where('coupon_transaction_id',$active_deal_coupon_info->coupon_transaction_id)
                                                        ->update($coupon_transaction_update_data);
                                                \App\System::EventLogWrite('update,tbl_coupon_transaction',json_encode($coupon_transaction_update_data));

                                            }else{
                                               $coupon_transaction_update=\DB::table('tbl_coupon_transaction')->insert($coupon_transaction_data);
                                                \DB::table('tbl_coupon')->where('coupon_id',$coupon_id)->update(array("coupon_total_selled" =>$coupon_total_selled));
                                                \App\System::EventLogWrite('insert,tbl_coupon_transaction',json_encode($coupon_transaction_data));

                                            }
                                            
                                            $coupon_update=\DB::table('tbl_coupon')->where('coupon_id',$coupon_id)->update($coupon_update_data);
                                            $otp_send=\App\OTP::SendSMSForBuyCoupon($branch_mobile,$coupon_secret_code, $customer_mobile, $transaction_amount, $coupon_discount_amount);

                                            $get_responce=\App\Admin::CouponJsonResponce('200','Buy Coupon Successfully and a code send merchant branch mobile.');

                                            \DB::table('request_log')->where('request_id',$this->request_id)->update(array("request_response" =>json_encode($get_responce),"updated_at"=>$now));
                                            \App\Api::ResponseLogWrite('Buy Coupon Successfully and a code send merchant branch mobile.',json_encode($get_responce));

                                            \DB::commit();
                                            return \Response::json($get_responce);

                                    }else{
                                        $get_responce=\App\Admin::CouponJsonResponce('403','Coupon is stock out.');

                                        \DB::table('request_log')->where('request_id',$this->request_id)->update(array("request_response" =>json_encode($get_responce),"updated_at"=>$now));
                                        \App\Api::ResponseLogWrite('Coupon is stock out.',json_encode($get_responce));
                                        return \Response::json($get_responce);
                                    }
                                
                                }else{
                                    $get_responce=\App\Admin::CouponJsonResponce('403','Shopping amount is less than minimum amount.');

                                    \DB::table('request_log')->where('request_id',$this->request_id)->update(array("request_response" =>json_encode($get_responce),"updated_at"=>$now));
                                    \App\Api::ResponseLogWrite('Shopping amount is less than minimum amount.',json_encode($get_responce));
                                    return \Response::json($get_responce);
                                }
                            }else{
                                $get_responce=\App\Admin::CouponJsonResponce('403','Coupon date is over');

                                \DB::table('request_log')->where('request_id',$this->request_id)->update(array("request_response" =>json_encode($get_responce),"updated_at"=>$now));
                                \App\Api::ResponseLogWrite('Coupon date is over',json_encode($get_responce));
                                return \Response::json($get_responce);       
                            }

                        }else{
                            $get_responce=\App\Admin::CouponJsonResponce('403','Coupon is invalid !!!');

                            \DB::table('request_log')->where('request_id',$this->request_id)->update(array("request_response" =>json_encode($get_responce),"updated_at"=>$now));
                            \App\Api::ResponseLogWrite('Coupon is invalid !!!',json_encode($get_responce));
                            return \Response::json($get_responce);
                        }
                    }else{
                        $get_responce=\App\Admin::CouponJsonResponce('403','Transaction Amount is to be numeric !!!');

                        \DB::table('request_log')->where('request_id',$this->request_id)->update(array("request_response" =>json_encode($get_responce),"updated_at"=>$now));
                        \App\Api::ResponseLogWrite('Transaction Amount is to be numeric !!!',json_encode($get_responce));
                        return \Response::json($get_responce);

                    }
                }else{

                    $get_responce=\App\Admin::CouponJsonResponce('403','Invalid coupon, Please active your deal.');
                                    
                    \DB::table('request_log')->where('request_id',$this->request_id)->update(array("request_response" =>json_encode($get_responce),"updated_at"=>$now));
                    \App\Api::ResponseLogWrite('Invalid coupon, Please active your deal',json_encode($get_responce));

                    return \Response::json($get_responce);
                }
            }else{

                $get_responce=\App\Admin::CouponJsonResponce('403','IMEI Number or Access Token is invalid');
                                
                \DB::table('request_log')->where('request_id',$this->request_id)->update(array("request_response" =>json_encode($get_responce),"updated_at"=>$now));
                \App\Api::ResponseLogWrite('IMEI Number or Access Token is invalid',json_encode($get_responce));

                return \Response::json($get_responce);
            }

        }catch(\Exception $e){

            $get_responce=\App\Admin::CouponJsonResponce('501','Missing or incorrect data, Sorry the requested resource does not exist');


            \DB::table('request_log')->where('request_id',$this->request_id)->update(array("request_response" =>json_encode($get_responce),"updated_at"=>$now));

            $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();

            \App\System::ErrorLogWrite($message);
            \App\Api::ResponseLogWrite($message,json_encode($get_responce));
            return \Response::json($get_responce);
        }

    }


    /********************************************
    ## EditBuyCoupon
    *********************************************/
    public function EditBuyCoupon(){
        $now=date('Y-m-d H:i:s');
        $current_date=date('Y-m-d').' 23:59:59';

        try{
            $accessinfo = \Request::input('accessinfo');
            $activeDeal_info = \Request::input('activeDeal_info');

            $imei_no= isset($accessinfo['imei_no']) ? trim($accessinfo['imei_no']):'';
            $access_token= isset($accessinfo['access_token']) ? trim($accessinfo['access_token']):'';
            $user_id=$accessinfo['user_id'];

            $get_info=\DB::table('table_app_token')->where('imei_no',$imei_no)->where('access_token',$access_token)->first();

            if(!empty($get_info) && !empty($activeDeal_info)){
                $coupon_id= $activeDeal_info['coupon_id'];
                $coupon_transaction_id= $activeDeal_info['coupon_transaction_id'];
                $transaction_amount= $activeDeal_info['transaction_amount'];

                $active_deal_coupon_info=\DB::table('tbl_coupon_transaction')
                                        ->where('tbl_coupon_transaction.coupon_transaction_id', $coupon_transaction_id)
                                        ->where('tbl_coupon_transaction.coupon_id', $coupon_id)
                                        ->where('tbl_coupon_transaction.customer_id',$user_id)
                                        ->where('tbl_coupon_transaction.coupon_status','1')
                                        ->first();
                if(!empty($active_deal_coupon_info)){


                    if (is_numeric($transaction_amount)){

                        $select_coupon_info=\DB::table('tbl_coupon')
                                            ->where('tbl_coupon.coupon_id', $coupon_id)
                                            ->leftjoin('tbl_merchant','tbl_coupon.coupon_merchant_id','=','tbl_merchant.merchant_id')
                                            ->leftjoin('tbl_branch','tbl_coupon.coupon_branch_id','=','tbl_branch.branch_id')
                                            ->first();
                        if(!empty($select_coupon_info)){

                            $branch_mobile=$select_coupon_info->branch_mobile;
                            $customer_mobile=$active_deal_coupon_info->customer_mobile;
                            $coupon_dateline=$select_coupon_info->coupon_closing_date;
                            if($coupon_dateline >= date('Y-m-d').'23:59:59'){

                                $customer_info=\DB::table('users')->where('id',$user_id)->first();
                                if(!empty($customer_info)){
                                    $customer_id=$customer_info->id;
                                    $customer_mobile=$customer_info->mobile;

                                }else{
                                    $customer_id='';
                                }
                                $user_id=$customer_id;


                                if(($select_coupon_info->coupon_applied_min_amount)<=$transaction_amount){

                                    $coupon_max_limit=$select_coupon_info->coupon_max_limit;
                                    $coupon_total_selled_info=$select_coupon_info->coupon_total_selled;

                                    if(($select_coupon_info->coupon_max_limit == -1) || ($coupon_max_limit>$coupon_total_selled_info)){

                                        \DB::beginTransaction();

                                            if($select_coupon_info->coupon_max_discount != 0){
                                                $coupon_discount_amount=(($transaction_amount*($select_coupon_info->coupon_discount_rate))/100);
                                                $coupon_commission_amount=(($transaction_amount*($select_coupon_info->coupon_commision_rate))/100);

                                                if($coupon_discount_amount>($select_coupon_info->coupon_max_discount)){
                                                    $coupon_discount_amount=$select_coupon_info->coupon_max_discount;
                                                    $coupon_commission_amount=$select_coupon_info->coupon_max_commission;
                                                }
                                            }else{
                                                $coupon_discount_amount=(($transaction_amount*($select_coupon_info->coupon_discount_rate))/100);
                                                $coupon_commission_amount=(($transaction_amount*($select_coupon_info->coupon_commision_rate))/100); 
                                            }

                                            $coupon_total_sell_price=($select_coupon_info->coupon_total_sell_price)+($select_coupon_info->coupon_sele_price);
                                            $coupon_secret_code=mt_rand(10000, 99999);


                                            $coupon_transaction_update_data=array(
                                                'coupon_secret_code'=>$coupon_secret_code,
                                                'coupon_discount_rate' =>$select_coupon_info->coupon_discount_rate,
                                                'coupon_commission_rate' =>$select_coupon_info->coupon_commision_rate,
                                                'coupon_buy_price' =>$select_coupon_info->coupon_sele_price,
                                                'coupon_shopping_amount' =>$transaction_amount,
                                                'coupon_discount_amount' =>$coupon_discount_amount,
                                                'coupon_commission_amount' =>$coupon_commission_amount,
                                                'coupon_status' =>1,
                                                'updated_by' => $user_id,
                                                'updated_at' => $now,
                                                );



                                                $coupon_transaction_update=\DB::table('tbl_coupon_transaction')
                                                        ->where('coupon_id',$coupon_id)
                                                        ->where('customer_mobile',$customer_mobile)
                                                        ->where('coupon_transaction_id',$active_deal_coupon_info->coupon_transaction_id)
                                                        ->update($coupon_transaction_update_data);
                                                \App\System::EventLogWrite('update,tbl_coupon_transaction',json_encode($coupon_transaction_update_data));
                                            
                                            $otp_send=\App\OTP::SendSMSForBuyCoupon($branch_mobile, $coupon_secret_code, $customer_mobile, $transaction_amount, $coupon_discount_amount);

                                            $get_responce=\App\Admin::CouponJsonResponce('200','Buy Coupon Successfully and a code send merchant branch mobile.');

                                            \DB::table('request_log')->where('request_id',$this->request_id)->update(array("request_response" =>json_encode($get_responce),"updated_at"=>$now));
                                            \App\Api::ResponseLogWrite('Buy Coupon Successfully and a code send merchant branch mobile.',json_encode($get_responce));

                                            \DB::commit();
                                            return \Response::json($get_responce);

                                    }else{
                                        $get_responce=\App\Admin::CouponJsonResponce('403','Coupon is stock out.');

                                        \DB::table('request_log')->where('request_id',$this->request_id)->update(array("request_response" =>json_encode($get_responce),"updated_at"=>$now));
                                        \App\Api::ResponseLogWrite('Coupon is stock out.',json_encode($get_responce));
                                        return \Response::json($get_responce);
                                    }
                                
                                }else{
                                    $get_responce=\App\Admin::CouponJsonResponce('403','Shopping amount is less than minimum amount.');

                                    \DB::table('request_log')->where('request_id',$this->request_id)->update(array("request_response" =>json_encode($get_responce),"updated_at"=>$now));
                                    \App\Api::ResponseLogWrite('Shopping amount is less than minimum amount.',json_encode($get_responce));
                                    return \Response::json($get_responce);
                                }
                            }else{
                                $get_responce=\App\Admin::CouponJsonResponce('403','Coupon date is over');

                                \DB::table('request_log')->where('request_id',$this->request_id)->update(array("request_response" =>json_encode($get_responce),"updated_at"=>$now));
                                \App\Api::ResponseLogWrite('Coupon date is over',json_encode($get_responce));
                                return \Response::json($get_responce);       
                            }

                        }else{
                            $get_responce=\App\Admin::CouponJsonResponce('403','Coupon is invalid !!!');

                            \DB::table('request_log')->where('request_id',$this->request_id)->update(array("request_response" =>json_encode($get_responce),"updated_at"=>$now));
                            \App\Api::ResponseLogWrite('Coupon is invalid !!!',json_encode($get_responce));
                            return \Response::json($get_responce);
                        }
                    }else{
                        $get_responce=\App\Admin::CouponJsonResponce('403','Transaction Amount is to be numeric !!!');

                        \DB::table('request_log')->where('request_id',$this->request_id)->update(array("request_response" =>json_encode($get_responce),"updated_at"=>$now));
                        \App\Api::ResponseLogWrite('Transaction Amount is to be numeric !!!',json_encode($get_responce));
                        return \Response::json($get_responce);

                    }
                }else{

                    $get_responce=\App\Admin::CouponJsonResponce('403','Invalid coupon, Please active your deal.');
                                    
                    \DB::table('request_log')->where('request_id',$this->request_id)->update(array("request_response" =>json_encode($get_responce),"updated_at"=>$now));
                    \App\Api::ResponseLogWrite('Invalid coupon, Please active your deal',json_encode($get_responce));

                    return \Response::json($get_responce);
                }
            }else{

                $get_responce=\App\Admin::CouponJsonResponce('403','IMEI Number or Access Token is invalid');
                                
                \DB::table('request_log')->where('request_id',$this->request_id)->update(array("request_response" =>json_encode($get_responce),"updated_at"=>$now));
                \App\Api::ResponseLogWrite('IMEI Number or Access Token is invalid',json_encode($get_responce));

                return \Response::json($get_responce);
            }

        }catch(\Exception $e){

            $get_responce=\App\Admin::CouponJsonResponce('501','Missing or incorrect data, Sorry the requested resource does not exist');


            \DB::table('request_log')->where('request_id',$this->request_id)->update(array("request_response" =>json_encode($get_responce),"updated_at"=>$now));

            $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();

            \App\System::ErrorLogWrite($message);
            \App\Api::ResponseLogWrite($message,json_encode($get_responce));
            return \Response::json($get_responce);
        }

    }



    /********************************************
    ## BuyCouponConfirm
    *********************************************/
    public function BuyCouponConfirm(){
        $now=date('Y-m-d H:i:s');
        $current_date=date('Y-m-d').' 23:59:59';

        try{
            $accessinfo = \Request::input('accessinfo');
            $activeDeal_info = \Request::input('activeDeal_info');

            $imei_no= isset($accessinfo['imei_no']) ? trim($accessinfo['imei_no']):'';
            $access_token= isset($accessinfo['access_token']) ? trim($accessinfo['access_token']):'';
            $user_id=$accessinfo['user_id'];

            $get_info=\DB::table('table_app_token')->where('imei_no',$imei_no)->where('access_token',$access_token)->first();

            if(!empty($get_info) && !empty($activeDeal_info)){
                $coupon_id= $activeDeal_info['coupon_id'];
                $coupon_transaction_id= $activeDeal_info['coupon_transaction_id'];
                $coupon_secret_code= $activeDeal_info['coupon_secret_code'];  
                $user_earning_point=0;

                $select_coupon_info=\DB::table('tbl_coupon')->where('coupon_id',$coupon_id)->first();
                $select_coupon_transaction_info=\DB::table('tbl_coupon_transaction')
                                            ->leftjoin('tbl_coupon','tbl_coupon_transaction.coupon_id','=','tbl_coupon.coupon_id')
                                            ->where('tbl_coupon_transaction.coupon_transaction_id',$coupon_transaction_id)
                                            ->where('tbl_coupon_transaction.coupon_secret_code',$coupon_secret_code)
                                            ->where('tbl_coupon_transaction.coupon_status','1')
                                            ->first();

                if(!empty($select_coupon_transaction_info)){

                    $coupon_dateline=$select_coupon_transaction_info->coupon_closing_date;
                    if($coupon_dateline >= date('Y-m-d').'23:59:59'){

                        $user_id=$select_coupon_transaction_info->customer_id;
                        $customer_mobile=$select_coupon_transaction_info->customer_mobile;
                        $user_point_info=\DB::table('tbl_user_meta')
                                        ->where('user_meta_field_name','point')
                                        ->where('user_id',$user_id)
                                        ->first();
                        $coupon_shopping_amount=$select_coupon_transaction_info->coupon_shopping_amount;
                        $coupon_discount_amount=$select_coupon_transaction_info->coupon_discount_amount;
                        $coupon_total_shopping_amount=$select_coupon_info->coupon_total_shopping_amount+$select_coupon_transaction_info->coupon_shopping_amount;
                        $coupon_total_discount=$select_coupon_info->coupon_total_discount+$select_coupon_transaction_info->coupon_discount_amount;
                        $coupon_total_commission=$select_coupon_info->coupon_total_commission+$select_coupon_transaction_info->coupon_commission_amount;

                        $coupon_transaction_update_data=array(
                            'coupon_status' => 2,
                            'updated_by' => $user_id,
                            'updated_at' => $now,
                            );

                        $user_earning_point_data=array(
                            'user_id' => $user_id,
                            'user_meta_field_name' => 'point',
                            'user_meta_field_value' => $select_coupon_info->coupon_applied_point,
                            'updated_by' => $user_id,
                            'updated_at' => $now,
                            );

                        $coupon_update_data=array(
                            'coupon_total_shopping_amount' => $coupon_total_shopping_amount,
                            'coupon_total_discount' => $coupon_total_discount,
                            'coupon_total_commission' => $coupon_total_commission,
                            'created_by' => $user_id,
                            'updated_by' => $user_id,
                            'created_at' => $now,
                            'updated_at' => $now,
                            );


                        \DB::beginTransaction();

                            $coupon_transaction_update=\DB::table('tbl_coupon_transaction')->where('coupon_transaction_id',$coupon_transaction_id)->update($coupon_transaction_update_data);
                            $coupon_update=\DB::table('tbl_coupon')->where('coupon_id',$coupon_id)->update($coupon_update_data);
                            if(!empty($user_point_info)){
                                $earning_point=$user_earning_point+$user_point_info->user_meta_field_value+$select_coupon_info->coupon_applied_point;
                                $user_point_update=\DB::table('tbl_user_meta')->where('user_meta_id',$user_point_info->user_meta_id)->update(array("user_meta_field_value" =>$earning_point));
                            }else{
                                $user_point_insert=\DB::table('tbl_user_meta')->insert($user_earning_point_data);

                            }
                            \App\System::EventLogWrite('update,tbl_coupon_transaction',json_encode($coupon_transaction_update_data));
                            \App\System::EventLogWrite('update,tbl_coupon',json_encode($coupon_update_data));
                            $otp_send=\App\OTP::SendSMSForSuccess($customer_mobile, $coupon_transaction_id, $coupon_shopping_amount, $coupon_discount_amount);
                            \DB::commit();
                            $get_responce=\App\Admin::CouponJsonResponce('200','Thank You For Shopping !');
                            \DB::table('request_log')->where('request_id',$this->request_id)->update(array("request_response" =>json_encode($get_responce),"updated_at"=>$now));
                            \App\Api::ResponseLogWrite('Thank You For Shopping !',json_encode($get_responce));
                            return \Response::json($get_responce);

                    }else{
                        $get_responce=\App\Admin::CouponJsonResponce('403','Coupon dateline is over.');
                        \DB::table('request_log')->where('request_id',$this->request_id)->update(array("request_response" =>json_encode($get_responce),"updated_at"=>$now));
                        \App\Api::ResponseLogWrite('Coupon dateline is over.',json_encode($get_responce));
                        return \Response::json($get_responce);
                    }

                }else{
                    $get_responce=\App\Admin::CouponJsonResponce('403','Invalid OTP ! Please try again.');

                    \DB::table('request_log')->where('request_id',$this->request_id)->update(array("request_response" =>json_encode($get_responce),"updated_at"=>$now));
                    \App\Api::ResponseLogWrite('Invalid OTP ! Please try again.',json_encode($get_responce));
                    return \Response::json($get_responce);
                }
            }else{

                $get_responce=\App\Admin::CouponJsonResponce('403','IMEI Number or Access Token is invalid');

                \DB::table('request_log')->where('request_id',$this->request_id)->update(array("request_response" =>json_encode($get_responce),"updated_at"=>$now));
                \App\Api::ResponseLogWrite('IMEI Number or Access Token is invalid',json_encode($get_responce));

                return \Response::json($get_responce);
            }

        }catch(\Exception $e){

            $get_responce=\App\Admin::CouponJsonResponce('501','Missing or incorrect data, Sorry the requested resource does not exist');


            \DB::table('request_log')->where('request_id',$this->request_id)->update(array("request_response" =>json_encode($get_responce),"updated_at"=>$now));

            $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();

            \App\System::ErrorLogWrite($message);
            \App\Api::ResponseLogWrite($message,json_encode($get_responce));
            return \Response::json($get_responce);
        }

    }


    /********************************************
    ## ResendCodeForBuyCoupon
    *********************************************/
    public function ResendCodeForBuyCoupon(){
        $now=date('Y-m-d H:i:s');

        try{
            $accessinfo = \Request::input('accessinfo');
            $resendinfo = \Request::input('resendinfo');

            $imei_no= isset($accessinfo['imei_no']) ? trim($accessinfo['imei_no']):'';
            $access_token= isset($accessinfo['access_token']) ? trim($accessinfo['access_token']):'';

            $get_info=\DB::table('table_app_token')->where('imei_no',$imei_no)->where('access_token',$access_token)->first();

            if(!empty($get_info) && !empty($resendinfo)){

                $user_id=$accessinfo['user_id'];
                $coupon_transaction_id= $resendinfo['coupon_transaction_id'];
                $coupon_id= $resendinfo['coupon_id'];

                $select_coupon_transaction_info=\DB::table('tbl_coupon_transaction')
                                        ->leftjoin('tbl_coupon','tbl_coupon_transaction.coupon_id','=','tbl_coupon.coupon_id')
                                        ->leftjoin('tbl_branch','tbl_coupon_transaction.transaction_branch_id','=','tbl_branch.branch_id')
                                        ->where('tbl_coupon_transaction.customer_id',$user_id)
                                        ->where('tbl_coupon_transaction.coupon_transaction_id',$coupon_transaction_id)
                                        ->where('tbl_coupon_transaction.coupon_id',$coupon_id)
                                        ->where('tbl_coupon_transaction.coupon_status','1')
                                        ->first();

                if(!empty($select_coupon_transaction_info)){

                    $branch_mobile=$select_coupon_transaction_info->branch_mobile;
		    $customer_mobile=$select_coupon_transaction_info->customer_mobile;
                    $coupon_secret_code=$select_coupon_transaction_info->coupon_secret_code;
                    $transaction_amount=$select_coupon_transaction_info->coupon_shopping_amount;
		    $coupon_discount_amount=$select_coupon_transaction_info->coupon_discount_amount;


                    \App\OTP::SendSMSForBuyCoupon($branch_mobile,$coupon_secret_code, $customer_mobile, $transaction_amount, $coupon_discount_amount);

                    $get_responce=\App\Admin::CouponJsonResponce('200','Code send successfully.');
                    \DB::table('request_log')->where('request_id',$this->request_id)->update(array("request_response" =>json_encode($get_responce),"updated_at"=>$now));
                    \App\Api::ResponseLogWrite('Code send successfully.',json_encode($get_responce));
                    return \Response::json($get_responce);

                }else{

                    $get_responce=\App\Admin::CouponJsonResponce('403','Invalid Coupon.');

                    \DB::table('request_log')->where('request_id',$this->request_id)->update(array("request_response" =>json_encode($get_responce),"updated_at"=>$now));
                    \App\Api::ResponseLogWrite('Invalid Coupon.',json_encode($get_responce));

                    return \Response::json($get_responce);
                }


            }else{

                $get_responce=\App\Admin::CouponJsonResponce('403','IMEI Number or Access Token is invalid');

                \DB::table('request_log')->where('request_id',$this->request_id)->update(array("request_response" =>json_encode($get_responce),"updated_at"=>$now));
                \App\Api::ResponseLogWrite('IMEI Number or Access Token is invalid',json_encode($get_responce));

                return \Response::json($get_responce);
            }

        }catch(\Exception $e){

            $get_responce=\App\Admin::CouponJsonResponce('501','Missing or incorrect data, Sorry the requested resource does not exist');


            \DB::table('request_log')->where('request_id',$this->request_id)->update(array("request_response" =>json_encode($get_responce),"updated_at"=>$now));

            $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();

            \App\System::ErrorLogWrite($message);
            \App\Api::ResponseLogWrite($message,json_encode($get_responce));
            return \Response::json($get_responce);
        }

    }






    /********************************************
     ## AllCouponInfo
     *********************************************/
        public function AllCouponInfo(){

            $now=date('Y-m-d H:i:s');

            try{
                $accessinfo = \Request::input('accessinfo');
                $requestinfo = \Request::input('requesinfo');

                $imei_no= isset($accessinfo['imei_no']) ? trim($accessinfo['imei_no']):'';
                $access_token= isset($accessinfo['access_token']) ? trim($accessinfo['access_token']):'';
                $user_id=$accessinfo['user_id'];

                $get_info=\DB::table('table_app_token')->where('imei_no',$imei_no)->where('access_token',$access_token)->first();

                if(!empty($get_info) && ($requestinfo =='getAllcoupon')){

                        $all_cupon=\DB::table('tbl_coupon')
                                ->leftjoin('tbl_category','tbl_coupon.coupon_category_id','=','tbl_category.category_id')
                                ->leftjoin('tbl_sub_category','tbl_coupon.coupon_sub_category_id','=','tbl_sub_category.sub_category_id')
                                ->leftjoin('tbl_merchant','tbl_coupon.coupon_merchant_id','=','tbl_merchant.merchant_id')
                                ->leftjoin('tbl_branch','tbl_coupon.coupon_branch_id','=','tbl_branch.branch_id')
                                ->where('tbl_coupon.coupon_closing_date','>=',date('Y-m-d').' 23:59:59')
				                ->select('tbl_coupon.*','tbl_category.*','tbl_sub_category.*','tbl_merchant.*','tbl_branch.*')
				                //->groupBy('tbl_coupon.coupon_merchant_id')
                                ->get();




                        /*

                        $numofcouponinfo = \Request::input('num_of_coupon');
                        $coupon_count = (isset($numofcouponinfo['num_of_coupon']) && is_numeric($numofcouponinfo['num_of_coupon']))? $numofcouponinfo['num_of_coupon']:10;

                        $all_cupon=\DB::table('tbl_coupon')
                                ->leftjoin('tbl_category','tbl_coupon.coupon_category_id','=','tbl_category.category_id')
                                ->leftjoin('tbl_sub_category','tbl_coupon.coupon_sub_category_id','=','tbl_sub_category.sub_category_id')
                                ->leftjoin('tbl_merchant','tbl_coupon.coupon_merchant_id','=','tbl_merchant.merchant_id')
                                ->leftjoin('tbl_branch','tbl_coupon.coupon_branch_id','=','tbl_branch.branch_id')
                                ->where('tbl_coupon.coupon_closing_date','>=',date('Y-m-d').' 23:59:59')
                                ->select('tbl_coupon.*','tbl_category.*','tbl_sub_category.*','tbl_merchant.*','tbl_branch.*')
                                ->groupBy('tbl_coupon.coupon_merchant_id')
                                ->paginate($coupon_count);

                        $all_cupon->appends(
                            [
                                'coupon_count' => $coupon_count,
                            ]
                        )->render();

                        $nextPageUrl = $all_cupon->nextPageUrl();

                        if($all_cupon->isEmpty())
                            throw new \Exception("No data available for Coupon.");

                        $response['nextPageUrl']= $nextPageUrl;  */




                        $merchant_info=\DB::table('tbl_merchant')
                            ->where('tbl_merchant.merchant_status','1')
                            ->orderBy('tbl_merchant.merchant_id','desc')
                            ->get();

                        $category_info=\DB::table('tbl_category')
                            ->orderBy('tbl_category.category_id','desc')
                            ->get();

                        $sub_category_info=\DB::table('tbl_sub_category')
                            ->orderBy('tbl_sub_category.sub_category_id','desc')
                            ->get();

            		    $response["merchant_info"]=$merchant_info;
            		    $response["category_info"]=$category_info;
            		    $response["sub_category_info"]=$sub_category_info;

                    $total_coupon=count($all_cupon);


                    $all_coupons=collect($all_cupon)->groupBy('tbl_merchant.merchant_id');


                    $response["getAllcoupon"]=$all_cupon;
                    $response["success"]= [
                        "statusCode"=> 200,
                        "successMessage"=> "Get All Coupon Successfully",
                        "serverReferenceCode"=>$now,
                    ];

                    $requestlog_update_data=[
                        "request_response"=>json_encode($response["success"]),
                        "updated_at"=>$now,
                    ];
                    \DB::table('request_log')->where('request_id',$this->request_id)->update($requestlog_update_data);
                    \App\Api::ResponseLogWrite('Get All coupon',json_encode($response));

                    return \Response::json($response);

                }else{
                    $response["errors"]= [
                        "statusCode"=> 403,
                        "errorMessage"=> "IMEI Number or App Key is invalid",
                        "serverReferenceCode"=> $now
                    ];

                    \DB::table('request_log')->where('request_id',$this->request_id)->update(array("request_response" =>json_encode($response),"updated_at"=>$now));

                    \App\Api::ResponseLogWrite('IMEI Number or App Key is invalid',json_encode($response));

                    return \Response::json($response);
                }

            }catch(\Exception $e){

                 $response["errors"]= [
                    "statusCode"=> 501,
                    "errorMessage"=> "Missing or incorrect data, Sorry the requested resource does not exist",
                    "serverReferenceCode"=> $now,
                ];

                \DB::table('request_log')->where('request_id',$this->request_id)->update(array("request_response" =>json_encode($response),"updated_at"=>$now));

                $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();

                \App\System::ErrorLogWrite($message);
                \App\Api::ResponseLogWrite($message,json_encode($response));
                return \Response::json($response);
            }


    }




    /********************************************
     ## SingleCouponInfo
     *********************************************/
        public function SingleCouponInfo(){

            $now=date('Y-m-d H:i:s');

            try{
                $accessinfo = \Request::input('accessinfo');
                $coupon = \Request::input('coupon');

                $imei_no= isset($accessinfo['imei_no']) ? trim($accessinfo['imei_no']):'';
                $access_token= isset($accessinfo['access_token']) ? trim($accessinfo['access_token']):'';
                $user_id=$accessinfo['user_id'];

                $get_info=\DB::table('table_app_token')->where('imei_no',$imei_no)->where('access_token',$access_token)->first();

                if(!empty($get_info)){

                    $get_copon_info=\DB::table('tbl_coupon')->where('tbl_coupon.coupon_id',$coupon)->first();

                    if(!empty($get_copon_info)){

                        $couponinfo=\DB::table('tbl_coupon')
                                ->leftjoin('tbl_category','tbl_coupon.coupon_category_id','=','tbl_category.category_id')
                                ->leftjoin('tbl_sub_category','tbl_coupon.coupon_sub_category_id','=','tbl_sub_category.sub_category_id')
                                ->leftjoin('tbl_merchant','tbl_coupon.coupon_merchant_id','=','tbl_merchant.merchant_id')
                                ->leftjoin('tbl_branch','tbl_coupon.coupon_branch_id','=','tbl_branch.branch_id')
                                ->where('tbl_coupon.coupon_closing_date','>=',date('Y-m-d').' 23:59:59')
                                ->where('tbl_coupon.coupon_id',$coupon)
                                ->first();
                        
                        $cupon_comments_info=\DB::table('tbl_coupon_review_comments')
					    ->where('tbl_coupon_review_comments.coupon_id',$get_copon_info->coupon_id)
					    ->leftjoin('users','tbl_coupon_review_comments.customer_id','=','users.id')
					    ->select('tbl_coupon_review_comments.*','users.name','users.user_profile_image')
					    ->get();

                        $response["getCouponComment"]=$cupon_comments_info;

                        $response["couponinfo"]=$couponinfo;

                        $response["success"]= [
                            "statusCode"=> 200,
                            "successMessage"=> "Get Coupon Successfully",
                            "serverReferenceCode"=>time()
                        ];
                            
                        $requestlog_update_data=[
                            "request_response"=>json_encode($response),
                            "updated_at"=>$now,
                        ];


                        \DB::table('request_log')->where('request_id',$this->request_id)->update($requestlog_update_data);

                        \App\Api::ResponseLogWrite('Get Coupon Successfully',json_encode($response));
                        return \Response::json($response);

                    }else{

                        $response["errors"]= [
                            "statusCode"=> 403,
                            "errorMessage"=> "Invalid Coupon",
                            "serverReferenceCode"=> $now,
                        ];
                        \DB::table('request_log')->where('request_id',$this->request_id)->update(array("request_response" =>json_encode($response),"updated_at"=>$now));
                        \App\Api::ResponseLogWrite('Invalid Coupon',json_encode($response));

                        return \Response::json($response);
                    }

                }else{
                    $response["errors"]= [
                        "statusCode"=> 403,
                        "errorMessage"=> "IMEI Number or App Key is invalid",
                        "serverReferenceCode"=> $now
                    ];

                    \DB::table('request_log')->where('request_id',$this->request_id)->update(array("request_response" =>json_encode($response),"updated_at"=>$now));
                    \App\Api::ResponseLogWrite('IMEI Number or App Key is invalid',json_encode($response));

                    return \Response::json($response);
                }

            }catch(\Exception $e){
                $response["errors"]= [
                    "statusCode"=> 501,
                    "errorMessage"=> "Missing or incorrect data, Sorry the requested resource does not exist",
                    "serverReferenceCode"=> $now,
                ];

                \DB::table('request_log')->where('request_id',$this->request_id)->update(array("request_response" =>json_encode($response),"updated_at"=>$now));

                $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();

                \App\System::ErrorLogWrite($message);
                \App\Api::ResponseLogWrite($message,json_encode($response));
                return \Response::json($response);
            }


    }



    /********************************************
     ## PublicDetailsInfo
     *********************************************/
        public function PublicDetailsInfo(){

            $now=date('Y-m-d H:i:s');

            try{
                $accessinfo = \Request::input('accessinfo');
                $requestinfo = \Request::input('requesinfo');

                $imei_no= isset($accessinfo['imei_no']) ? trim($accessinfo['imei_no']):'';
                $access_token= isset($accessinfo['access_token']) ? trim($accessinfo['access_token']):'';

                $get_info=\DB::table('table_app_token')->where('imei_no',$imei_no)->where('access_token',$access_token)->first();

                if(!empty($get_info) && ($requestinfo=='detailsinfo')){

                    $all_sub_category=\DB::table('tbl_sub_category')
                                ->orderBy('sub_category_id', 'asc')
                                ->get();

                    $response["detailsinfo"]=$all_sub_category;
                    $response["success"]= [
                        "statusCode"=> 200,
                        "successMessage"=> "Get All Details Info Successfully",
                        "serverReferenceCode"=>$now,
                    ];

                    $requestlog_update_data=[
                        "request_response"=>json_encode($response["success"]),
                        "updated_at"=>$now,
                    ];
                    \DB::table('request_log')->where('request_id',$this->request_id)->update($requestlog_update_data);
                    \App\Api::ResponseLogWrite('Get All Details Info',json_encode($response));

                    return \Response::json($response);

                }else{
                    $response["errors"]= [
                        "statusCode"=> 403,
                        "errorMessage"=> "IMEI Number or App Key is invalid",
                        "serverReferenceCode"=> $now
                    ];

                    \DB::table('request_log')->where('request_id',$this->request_id)->update(array("request_response" =>json_encode($response),"updated_at"=>$now));

                    \App\Api::ResponseLogWrite('IMEI Number or App Key is invalid',json_encode($response));

                    return \Response::json($response);
                }

            }catch(\Exception $e){

                 $response["errors"]= [
                    "statusCode"=> 501,
                    "errorMessage"=> "Missing or incorrect data, Sorry the requested resource does not exist",
                    "serverReferenceCode"=> $now,
                ];

                \DB::table('request_log')->where('request_id',$this->request_id)->update(array("request_response" =>json_encode($response),"updated_at"=>$now));

                $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();

                \App\System::ErrorLogWrite($message);
                \App\Api::ResponseLogWrite($message,json_encode($response));
                return \Response::json($response);
            }


    }





    /********************************************
     ## SearchCouponInfo
     *********************************************/
/*        public function SearchCouponInfo(){

            $now=date('Y-m-d H:i:s');

            try{
                $accessinfo = \Request::input('accessinfo');
                $requestinfo = \Request::input('requestinfo');

                $imei_no= isset($accessinfo['imei_no']) ? trim($accessinfo['imei_no']):'';
                $access_token= isset($accessinfo['access_token']) ? trim($accessinfo['access_token']):'';

                $get_info=\DB::table('table_app_token')->where('imei_no',$imei_no)->where('access_token',$access_token)->first();

                if(!empty($get_info)){
                        $search_text=isset($requestinfo['search_text']) ? trim($requestinfo['search_text']):'';
                        $search_coupon_info=\DB::table('tbl_coupon')
                                        ->leftjoin('tbl_category','tbl_coupon.coupon_category_id','=','tbl_category.category_id')
                                        ->leftjoin('tbl_sub_category','tbl_coupon.coupon_sub_category_id','=','tbl_sub_category.sub_category_id')
                                        ->leftjoin('tbl_merchant','tbl_coupon.coupon_merchant_id','=','tbl_merchant.merchant_id')
                                        ->leftjoin('tbl_branch','tbl_coupon.coupon_branch_id','=','tbl_branch.branch_id')
                                        ->Orwhere('tbl_category.category_name','like', "%$search_text%")
                                        ->Orwhere('tbl_sub_category.sub_category_name','like', "%$search_text%")
                                        ->Orwhere('tbl_merchant.merchant_name','like', "%$search_text%")
                                        ->Orwhere('tbl_branch.branch_name','like', "%$search_text%")
                                        ->OrderBy('tbl_coupon.updated_at','desc')
                                        ->get();
                        


                        $response["couponinfo"]=$search_coupon_info;

                        $response["success"]= [
                            "statusCode"=> 200,
                            "successMessage"=> "Get Search Coupon Successfully",
                            "serverReferenceCode"=>time()
                        ];
                            
                        $requestlog_update_data=[
                            "request_response"=>json_encode($response),
                            "updated_at"=>$now,
                        ];


                        \DB::table('request_log')->where('request_id',$this->request_id)->update($requestlog_update_data);

                        \App\Api::ResponseLogWrite('Get Search Coupon Successfully',json_encode($response));
                        return \Response::json($response);


                }else{
                    $response["errors"]= [
                        "statusCode"=> 403,
                        "errorMessage"=> "IMEI Number or App Key is invalid",
                        "serverReferenceCode"=> $now
                    ];

                    \DB::table('request_log')->where('request_id',$this->request_id)->update(array("request_response" =>json_encode($response),"updated_at"=>$now));
                    \App\Api::ResponseLogWrite('IMEI Number or App Key is invalid',json_encode($response));

                    return \Response::json($response);
                }

            }catch(\Exception $e){
                $response["errors"]= [
                    "statusCode"=> 501,
                    "errorMessage"=> "Missing or incorrect data, Sorry the requested resource does not exist",
                    "serverReferenceCode"=> $now,
                ];

                \DB::table('request_log')->where('request_id',$this->request_id)->update(array("request_response" =>json_encode($response),"updated_at"=>$now));

                $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();

                \App\System::ErrorLogWrite($message);
                \App\Api::ResponseLogWrite($message,json_encode($response));
                return \Response::json($response);
            }


    }*/


    /********************************************
     ## GetUserInfo
     *********************************************/
        public function GetUserInfo(){

            $now=date('Y-m-d H:i:s');

            try{
                $accessinfo = \Request::input('accessinfo');
                $requesinfo = \Request::input('requesinfo');

                $imei_no= isset($accessinfo['imei_no']) ? trim($accessinfo['imei_no']):'';
                $access_token= isset($accessinfo['access_token']) ? trim($accessinfo['access_token']):'';
                $user_id=$accessinfo['user_id'];

                $get_info=\DB::table('table_app_token')->where('imei_no',$imei_no)->where('access_token',$access_token)->first();

                if(!empty($get_info)){

                    $user_info=\DB::table('users')->where('id',$user_id)->first();

                    if(!empty($user_info)){

                        $user_meta_info=\DB::table('tbl_user_meta')->where('user_id',$user_id)->get();
                        $user_activity=\DB::table('tbl_follow_activity')
                            ->where('tbl_follow_activity.activity_user_id',$user_id)
                            ->get();
			$wish_list_activity=\DB::table('tbl_follow_activity')
                            ->where('tbl_follow_activity.activity_user_id',$user_id)
                            ->where('tbl_follow_activity.activity_type','wish_list')
                            ->leftjoin('tbl_coupon','tbl_follow_activity.merchant_or_coupon_id','=','tbl_coupon.coupon_id')
                            ->leftjoin('tbl_merchant','tbl_coupon.coupon_merchant_id','=','tbl_merchant.merchant_id')
                            ->leftjoin('tbl_branch','tbl_coupon.coupon_branch_id','=','tbl_branch.branch_id')
                            ->leftjoin('tbl_category','tbl_coupon.coupon_category_id','=','tbl_category.category_id')
                            ->leftjoin('tbl_sub_category','tbl_coupon.coupon_sub_category_id','=','tbl_sub_category.sub_category_id')
                            ->get();

                        $follow_activity=\DB::table('tbl_follow_activity')
                            ->where('tbl_follow_activity.activity_user_id',$user_id)
                            ->where('tbl_follow_activity.activity_type','follow')
                            ->leftjoin('tbl_merchant','tbl_follow_activity.merchant_or_coupon_id','=','tbl_merchant.merchant_id')
                            // ->leftjoin('tbl_coupon','tbl_merchant.merchant_id','=','tbl_coupon.coupon_merchant_id')
                            // ->leftjoin('tbl_branch','tbl_coupon.coupon_branch_id','=','tbl_branch.branch_id')
                            // ->leftjoin('tbl_category','tbl_coupon.coupon_category_id','=','tbl_category.category_id')
                            // ->leftjoin('tbl_sub_category','tbl_coupon.coupon_sub_category_id','=','tbl_sub_category.sub_category_id')
                            ->get();

                        $user_coupon_transaction_info=\DB::table('tbl_coupon_transaction')
                            ->where('tbl_coupon_transaction.customer_id',$user_id)
                            ->leftjoin('tbl_coupon','tbl_coupon_transaction.coupon_id','=','tbl_coupon.coupon_id')
                            ->leftjoin('tbl_merchant','tbl_coupon_transaction.transaction_merchant_id','=','tbl_merchant.merchant_id')
                            ->leftjoin('tbl_branch','tbl_coupon_transaction.transaction_branch_id','=','tbl_branch.branch_id')
			    ->orderBy('tbl_coupon_transaction.coupon_transaction_id','desc')
                            ->get();

                        $notification_info=\DB::table('tbl_notification')
			    ->where('tbl_notification.notification_status','2')
                            ->whereIn('notification_type',['new_coupon_message','event'])
			    ->whereIn('notification_user_id',['all',$user_id])
			    //->leftjoin('tbl_merchant','tbl_notification.notification_merchant_id','=','tbl_merchant.merchant_id')
                            ->leftjoin('tbl_coupon','tbl_notification.notification_coupon_id','=','tbl_coupon.coupon_id')
                            ->leftjoin('tbl_merchant','tbl_coupon.coupon_merchant_id','=','tbl_merchant.merchant_id')
                            ->leftjoin('tbl_branch','tbl_coupon.coupon_branch_id','=','tbl_branch.branch_id')
                            ->leftjoin('tbl_category','tbl_coupon.coupon_category_id','=','tbl_category.category_id')
                            ->leftjoin('tbl_sub_category','tbl_coupon.coupon_sub_category_id','=','tbl_sub_category.sub_category_id')
                            ->orderBy('tbl_notification.notification_id','desc')
                            ->get();

                        $event_info=\DB::table('tbl_notification')
			    ->where('tbl_notification.notification_status','1')
			    ->leftjoin('tbl_merchant','tbl_notification.notification_merchant_id','=','tbl_merchant.merchant_id')
                            ->orderBy('tbl_notification.notification_id','desc')
                            ->get();

                        $merchant_info=\DB::table('tbl_merchant')
                            ->where('tbl_merchant.merchant_status','1')
                            ->orderBy('tbl_merchant.merchant_id','desc')
                            ->get();


                        $response["user_info"]=$user_info;
                        $response["user_meta_info"]=$user_meta_info;
                        $response["merchant_info"]=$merchant_info;
                        $response["user_coupon_transaction_info"]=$user_coupon_transaction_info;
                        $response["user_activity"]=$user_activity;
                        $response["wish_list_activity"]=$wish_list_activity;
                        $response["follow_activity"]=$follow_activity;
                        $response["event_info"]=$event_info;
                        $response["notification_info"]=$notification_info;
                        $response["success"]= [
                            "statusCode"=> 200,
                            "successMessage"=> "Get User All Data Successfully",
                            "serverReferenceCode"=>$now,
                        ];
                                
                        $requestlog_update_data=[
                            "request_response"=>json_encode($response["success"]),
                            "updated_at"=>$now,
                        ];
                        
                        \DB::table('request_log')->where('request_id',$this->request_id)->update($requestlog_update_data);
                        \App\Api::ResponseLogWrite('Get User All Data Successfully',json_encode($response));

                        return \Response::json($response);


                    }else{

                        $response["errors"]= [
                            "statusCode"=> 403,
                            "errorMessage"=> "Invalid User ID",
                            "serverReferenceCode"=> $now
                        ];

                        \DB::table('request_log')->where('request_id',$this->request_id)->update(array("request_response" =>json_encode($response),"updated_at"=>$now));
                        \App\Api::ResponseLogWrite('Invalid User ID',json_encode($response));

                        return \Response::json($response);
                    }
                    

                }else{
                    $response["errors"]= [
                        "statusCode"=> 403,
                        "errorMessage"=> "IMEI Number or App Key is invalid",
                        "serverReferenceCode"=> $now
                    ];

                    \DB::table('request_log')->where('request_id',$this->request_id)->update(array("request_response" =>json_encode($response),"updated_at"=>$now));
                    \App\Api::ResponseLogWrite('IMEI Number or App Key is invalid',json_encode($response));

                    return \Response::json($response);
                }

            }catch(\Exception $e){
                $response["errors"]= [
                    "statusCode"=> 501,
                    "errorMessage"=> "Missing or incorrect data, Sorry the requested resource does not exist",
                    "serverReferenceCode"=> $now,
                ];

                \DB::table('request_log')->where('request_id',$this->request_id)->update(array("request_response" =>json_encode($response),"updated_at"=>$now));

                $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();

                \App\System::ErrorLogWrite($message);
                \App\Api::ResponseLogWrite($message,json_encode($response));
                return \Response::json($response);
            }


    }




    /********************************************
    ## OTPVerification 
    *********************************************/
    public function OTPVerification(){
        $now=date('Y-m-d H:i:s');

        try{

            $accessinfo = \Request::input('accessinfo');
            $imei_no= isset($accessinfo['imei_no']) ? trim($accessinfo['imei_no']):'';
            $access_token= isset($accessinfo['access_token']) ? trim($accessinfo['access_token']):'';
            $verifyinfo = \Request::input('verifyinfo');
            $new_otp=mt_rand(1000, 9999);

            $get_info=\DB::table('table_app_token')->where('imei_no',$imei_no)->where('access_token',$access_token)->first();
            if(!empty($get_info)){

                $mobile_verification_data=[
                            "mobile_no"=>$verifyinfo['mobile'],
                            "verification_from"=>'app',
                            "app_push_token"=>$verifyinfo['app_push_token'],
                            "OTP"=>$new_otp,
                            "status"=>1,
                            "created_at"=>$now,
                            "updated_at"=>$now,
                            ];


                $response["mobileverify"]= [
                    "mobile"=> $verifyinfo['mobile'],
                    "OTP"=> $new_otp
                ];


                $response["success"]= [
                    "statusCode"=> 200,
                    "successMessage"=> "Request Successfully completed",
                    "serverReferenceCode"=>time()
                ];

                $requestlog_update_data=[
                    "request_response"=>json_encode($response),
                    "request_message"=>json_encode($mobile_verification_data),
                    "updated_at"=>$now,
                ];
                \DB::table('request_log')->where('request_id',$this->request_id)->update($requestlog_update_data);


                $users_data_info=\DB::table('mobile_verification')->insert($mobile_verification_data);
                \App\Api::ResponseLogWrite('insert,mobile_verification',json_encode($mobile_verification_data));

                return \Response::json($response);
            }else{
                $response["errors"]= [
                    "statusCode"=> 403,
                    "errorMessage"=> "Sorry, the requested resource does not exist because Imei number or access token invalid",
                    "serverReferenceCode"=> $now,
                ];
                \DB::table('request_log')->where('request_id',$this->request_id)->update(array("request_response" =>json_encode($response)));

                return \Response::json($response);
            }

        }catch(\Exception $e){

            $response["errors"]= [
                    "statusCode"=> 501,
                    "errorMessage"=> "Missing or incorrect data, Sorry the requested resource does not exist",
                    "serverReferenceCode"=> $now,
                ];

            \DB::table('request_log')->where('request_id',$this->request_id)->update(array("request_response" =>json_encode($response)));

            $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();

            \App\System::ErrorLogWrite($message);
            \App\Api::ResponseLogWrite($message,json_encode($response));
            return \Response::json($response);
        }


    }


    /********************************************
    ## OTPConfirmation 
    *********************************************/
    public function OTPConfirmation(){
        $now=date('Y-m-d H:i:s');

        try{

            $accessinfo = \Request::input('accessinfo');
            $imei_no= isset($accessinfo['imei_no']) ? trim($accessinfo['imei_no']):'';
            $access_token= isset($accessinfo['access_token']) ? trim($accessinfo['access_token']):'';
            $mobileverifyinfo = \Request::input('mobileverifyinfo');

            $get_info=\DB::table('table_app_token')->where('imei_no',$imei_no)->where('access_token',$access_token)->first();
            if(!empty($get_info)){

                $mobile_verification_info=\DB::table('mobile_verification')
                                ->where('mobile_no',$mobileverifyinfo['mobile'])
                                ->where('OTP',$mobileverifyinfo['OTP'])
                                ->first();

                if(!empty($mobile_verification_info)){


                    $response["success"]= [
                        "statusCode"=> 200,
                        "successMessage"=> "Request Successfully completed",
                        "serverReferenceCode"=>time()
                    ];

                    $response["confirmation"]='Mobile Number Verification Successfully';

                    $requestlog_update_data=[
                        "request_response"=>json_encode($response),
                        "request_message"=>json_encode($mobile_verification_info),
                        "updated_at"=>$now,
                    ];
                    \DB::table('request_log')->where('request_id',$this->request_id)->update($requestlog_update_data);

                    \DB::table('mobile_verification')
                    ->where('mobile_no',$mobileverifyinfo['mobile'])
                    ->where('OTP',$mobileverifyinfo['OTP'])
                    ->update(array("status" =>'2'));


                    \App\Api::ResponseLogWrite('update,mobile_verification',json_encode($mobile_verification_info));

                    return \Response::json($response);

                }else{
                    $response["errors"]= [
                        "statusCode"=> 403,
                        "errorMessage"=> "Invalid mobile no or OTP",
                        "serverReferenceCode"=> $now,
                    ];
                    \DB::table('request_log')->where('request_id',$this->request_id)->update(array("request_response" =>json_encode($response)));

                    return \Response::json($response);
                }

            }else{
                $response["errors"]= [
                    "statusCode"=> 403,
                    "errorMessage"=> "Sorry, the requested resource does not exist because Imei number or access token invalid",
                    "serverReferenceCode"=> $now,
                ];
                \DB::table('request_log')->where('request_id',$this->request_id)->update(array("request_response" =>json_encode($response)));

                return \Response::json($response);
            }

        }catch(\Exception $e){

            $response["errors"]= [
                "statusCode"=> 501,
                "errorMessage"=> "Missing or incorrect data, Sorry the requested resource does not exist",
                "serverReferenceCode"=> $now,
            ];

            \DB::table('request_log')->where('request_id',$this->request_id)->update(array("request_response" =>json_encode($response)));

            $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();

            \App\System::ErrorLogWrite($message);
            \App\Api::ResponseLogWrite($message,json_encode($response));
            return \Response::json($response);
        }


    }



    /********************************************
    ## CallBackRequest
    *********************************************/
    public function CallBackRequest(){
        $now=date('Y-m-d H:i:s');

        try{
            $accessinfo = \Request::input('accessinfo');
            $requestinfo = \Request::input('requestinfo');

            $imei_no= isset($accessinfo['imei_no']) ? trim($accessinfo['imei_no']):'';
            $access_token= isset($accessinfo['access_token']) ? trim($accessinfo['access_token']):'';

            $get_info=\DB::table('table_app_token')->where('imei_no',$imei_no)->where('access_token',$access_token)->first();

            if(!empty($get_info) && !empty($requestinfo)){

                $client_name= $requestinfo['client_name'];
                $user_type= $requestinfo['user_type'];
		        $contact_type= $requestinfo['contact_type'];
                $client_mobile= $requestinfo['client_mobile'];
                $client_message= $requestinfo['client_message'];


                $call_request_data=array(
                    'client_name' => $client_name,
                    'user_type' => $user_type,
                    'contact_type' =>  $contact_type,
                    'client_mobile' => $client_mobile,
                    'client_message' => $client_message,
                    'created_at' => $now,
                    'updated_at' => $now,
                    );


                    $call_request_data_info=\DB::table('tbl_contact')->insert($call_request_data);

                    $get_responce=\App\Admin::CouponJsonResponce('200','Call Back Request Successfully !');
                    \DB::table('request_log')->where('request_id',$this->request_id)->update(array("request_response" =>json_encode($get_responce),"updated_at"=>$now));
                    \App\Api::ResponseLogWrite('Call Back Request Successfully !',json_encode($get_responce));
                    return \Response::json($get_responce);


            }else{

                $get_responce=\App\Admin::CouponJsonResponce('403','IMEI Number or Access Token is invalid');

                \DB::table('request_log')->where('request_id',$this->request_id)->update(array("request_response" =>json_encode($get_responce),"updated_at"=>$now));
                \App\Api::ResponseLogWrite('IMEI Number or Access Token is invalid',json_encode($get_responce));

                return \Response::json($get_responce);
            }

        }catch(\Exception $e){

            $get_responce=\App\Admin::CouponJsonResponce('501','Missing or incorrect data, Sorry the requested resource does not exist');


            \DB::table('request_log')->where('request_id',$this->request_id)->update(array("request_response" =>json_encode($get_responce),"updated_at"=>$now));

            $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();

            \App\System::ErrorLogWrite($message);
            \App\Api::ResponseLogWrite($message,json_encode($get_responce));
            return \Response::json($get_responce);
        }

    }

    /********************************************
    ## CouponMainSlider
    *********************************************/
    public function CouponMainSlider(){
        $now=date('Y-m-d H:i:s');

        try{
            $accessinfo = \Request::input('accessinfo');
            $requesinfo = \Request::input('requestinfo');

            $imei_no= isset($accessinfo['imei_no']) ? trim($accessinfo['imei_no']):'';
            $access_token= isset($accessinfo['access_token']) ? trim($accessinfo['access_token']):'';

            $get_info=\DB::table('table_app_token')->where('imei_no',$imei_no)->where('access_token',$access_token)->first();

            if(!empty($get_info) && !empty($requesinfo)){


                $main_slider_info=\DB::table('tbl_setting_meta')
                        ->where('setting_meta_field_name','home_slider')
                        ->where('setting_meta_status','1')
                        ->orderBy('tbl_setting_meta.created_at','desc')
                        ->get();

                $get_responce["mainsliderinfo"]=$main_slider_info;


                $get_responce["success"]= [
                    "statusCode"=> 200,
                    "successMessage"=> "Successfully get all Slider info.",
                    "serverReferenceCode"=>$now,
                ];

                \DB::table('request_log')->where('request_id',$this->request_id)->update(array("request_response" =>json_encode($get_responce),"updated_at"=>$now));
                \App\Api::ResponseLogWrite('Call Back Request Successfully !',json_encode($get_responce));
                return \Response::json($get_responce);


            }else{

                $get_responce=\App\Admin::CouponJsonResponce('403','IMEI Number or Access Token is invalid');

                \DB::table('request_log')->where('request_id',$this->request_id)->update(array("request_response" =>json_encode($get_responce),"updated_at"=>$now));
                \App\Api::ResponseLogWrite('IMEI Number or Access Token is invalid',json_encode($get_responce));

                return \Response::json($get_responce);
            }

        }catch(\Exception $e){

            $get_responce=\App\Admin::CouponJsonResponce('501','Missing or incorrect data, Sorry the requested resource does not exist');


            \DB::table('request_log')->where('request_id',$this->request_id)->update(array("request_response" =>json_encode($get_responce),"updated_at"=>$now));

            $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();

            \App\System::ErrorLogWrite($message);
            \App\Api::ResponseLogWrite($message,json_encode($get_responce));
            return \Response::json($get_responce);
        }

    }



    /********************************************
    ## EventNotificationPush
    *********************************************/
    /*public function EventNotificationPush(){
        $now=date('Y-m-d H:i:s');

        try{
            $accessinfo = \Request::input('accessinfo');
            $requestinfo = \Request::input('requestinfo');

            $imei_no= isset($accessinfo['imei_no']) ? trim($accessinfo['imei_no']):'';
            $access_token= isset($accessinfo['access_token']) ? trim($accessinfo['access_token']):'';

            $get_info=\DB::table('table_app_token')->where('imei_no',$imei_no)->where('access_token',$access_token)->first();

            if(!empty($get_info) && !empty($requestinfo)){

                $event_type= $requestinfo['event_type'];
                $user_id= $requestinfo['user_id'];
                $event_merchant_id= $requestinfo['event_merchant_id'];
                $event_date= $requestinfo['event_date'];
                $title= $requestinfo['title'];
                $message= $requestinfo['message'];


                $event_data=array(
                    'event_type' =>$event_type,
                    'event_user_id' =>$user_id,
                    'event_merchant_id' => $event_merchant_id,
                    'event_date' => $event_date,
                    'title' => $title,
                    'message' =>$message,
                    'event_status' =>'1',
                    'created_by' => $user_id,
                    'updated_by' => $user_id,
                    'created_at' => $now,
                    'updated_at' => $now,
                    );

                    $event_data_info=\DB::table('tbl_event')->insert($event_data);
                    \App\System::EventLogWrite('insert,tbl_event',json_encode($event_data));

                    $get_responce=\App\Admin::CouponJsonResponce('200','Event Notification Add Successfully !');
                    \DB::table('request_log')->where('request_id',$this->request_id)->update(array("request_response" =>json_encode($get_responce),"updated_at"=>$now));
                    \App\Api::ResponseLogWrite('Event Notification Add Successfully !',json_encode($get_responce));
                    return \Response::json($get_responce);


            }else{

                $get_responce=\App\Admin::CouponJsonResponce('403','IMEI Number or Access Token is invalid');

                \DB::table('request_log')->where('request_id',$this->request_id)->update(array("request_response" =>json_encode($get_responce),"updated_at"=>$now));
                \App\Api::ResponseLogWrite('IMEI Number or Access Token is invalid',json_encode($get_responce));

                return \Response::json($get_responce);
            }

        }catch(\Exception $e){

            $get_responce=\App\Admin::CouponJsonResponce('501','Missing or incorrect data, Sorry the requested resource does not exist');


            \DB::table('request_log')->where('request_id',$this->request_id)->update(array("request_response" =>json_encode($get_responce),"updated_at"=>$now));

            $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();

            \App\System::ErrorLogWrite($message);
            \App\Api::ResponseLogWrite($message,json_encode($get_responce));
            return \Response::json($get_responce);
        }

    }*/


    /********************************************
    ## EventNotificationPush
    *********************************************/
    public function EventNotificationPush(){
        $now=date('Y-m-d H:i:s');

        try{
            $accessinfo = \Request::input('accessinfo');
            $requestinfo = \Request::input('requestinfo');

            $imei_no= isset($accessinfo['imei_no']) ? trim($accessinfo['imei_no']):'';
            $access_token= isset($accessinfo['access_token']) ? trim($accessinfo['access_token']):'';

            $get_info=\DB::table('table_app_token')->where('imei_no',$imei_no)->where('access_token',$access_token)->first();

            if(!empty($get_info) && !empty($requestinfo)){

                $event_name= $requestinfo['event_name'];
                $user_id= $requestinfo['user_id'];
                $event_merchant_id= $requestinfo['event_merchant_id'];
                $event_date= $requestinfo['event_date'];
                $title= $requestinfo['title'];
                $message= $requestinfo['message'];


                $event_data=array(
                    'notification_type' =>'event',
                    'notification_user_id' =>$user_id,
                    'notification_merchant_id' => $event_merchant_id,
                    'event_name' => $event_name,
                    'event_date' => $event_date,
                    'title' => $title,
                    'message' =>$message,
                    'notification_status' =>'1',
                    'created_by' => $user_id,
                    'updated_by' => $user_id,
                    'created_at' => $now,
                    'updated_at' => $now,
                    );


                    $event_data_info=\DB::table('tbl_notification')->insert($event_data);
                    \App\System::EventLogWrite('insert,tbl_notification',json_encode($event_data));

                    $get_responce=\App\Admin::CouponJsonResponce('200','Event Notification Add Successfully !');
                    \DB::table('request_log')->where('request_id',$this->request_id)->update(array("request_response" =>json_encode($get_responce),"updated_at"=>$now));
                    \App\Api::ResponseLogWrite('Event Notification Add Successfully !',json_encode($get_responce));
                    return \Response::json($get_responce);


            }else{

                $get_responce=\App\Admin::CouponJsonResponce('403','IMEI Number or Access Token is invalid');

                \DB::table('request_log')->where('request_id',$this->request_id)->update(array("request_response" =>json_encode($get_responce),"updated_at"=>$now));
                \App\Api::ResponseLogWrite('IMEI Number or Access Token is invalid',json_encode($get_responce));

                return \Response::json($get_responce);
            }

        }catch(\Exception $e){

            $get_responce=\App\Admin::CouponJsonResponce('501','Missing or incorrect data, Sorry the requested resource does not exist');


            \DB::table('request_log')->where('request_id',$this->request_id)->update(array("request_response" =>json_encode($get_responce),"updated_at"=>$now));

            $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();

            \App\System::ErrorLogWrite($message);
            \App\Api::ResponseLogWrite($message,json_encode($get_responce));
            return \Response::json($get_responce);
        }

    }



    /********************************************
    ## EventNotificationDelete
    *********************************************/
    public function EventNotificationDelete(){
        $now=date('Y-m-d H:i:s');

        try{
            $accessinfo = \Request::input('accessinfo');
            $requestinfo = \Request::input('requestinfo');

            $imei_no= isset($accessinfo['imei_no']) ? trim($accessinfo['imei_no']):'';
            $access_token= isset($accessinfo['access_token']) ? trim($accessinfo['access_token']):'';

            $get_info=\DB::table('table_app_token')->where('imei_no',$imei_no)->where('access_token',$access_token)->first();

            if(!empty($get_info) && !empty($requestinfo)){

                $user_id= $requestinfo['user_id'];
                $notification_id= $requestinfo['notification_id'];

                $event_info=\DB::table('tbl_notification')
                          ->where('notification_id',$notification_id)
                          ->where('notification_type','event')
                          ->where('notification_status','1')
                          ->first();

                if(!empty($event_info)){


                    $event_data_info=\DB::table('tbl_notification')->where('notification_id',$notification_id)->delete();
                    \App\System::EventLogWrite('delete,tbl_notification',json_encode($event_info));

                    $get_responce=\App\Admin::CouponJsonResponce('200','Event Delete Successfully !');
                    \DB::table('request_log')->where('request_id',$this->request_id)->update(array("request_response" =>json_encode($get_responce),"updated_at"=>$now));
                    \App\Api::ResponseLogWrite('Event Delete Successfully  !',json_encode($get_responce));
                    return \Response::json($get_responce);
                }else{

                    $get_responce=\App\Admin::CouponJsonResponce('403','Invalid Event ID');

                    \DB::table('request_log')->where('request_id',$this->request_id)->update(array("request_response" =>json_encode($get_responce),"updated_at"=>$now));
                    \App\Api::ResponseLogWrite('Invalid Event ID',json_encode($get_responce));

                    return \Response::json($get_responce);
                }


            }else{

                $get_responce=\App\Admin::CouponJsonResponce('403','IMEI Number or Access Token is invalid');

                \DB::table('request_log')->where('request_id',$this->request_id)->update(array("request_response" =>json_encode($get_responce),"updated_at"=>$now));
                \App\Api::ResponseLogWrite('IMEI Number or Access Token is invalid',json_encode($get_responce));

                return \Response::json($get_responce);
            }

        }catch(\Exception $e){

            $get_responce=\App\Admin::CouponJsonResponce('501','Missing or incorrect data, Sorry the requested resource does not exist');


            \DB::table('request_log')->where('request_id',$this->request_id)->update(array("request_response" =>json_encode($get_responce),"updated_at"=>$now));

            $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();

            \App\System::ErrorLogWrite($message);
            \App\Api::ResponseLogWrite($message,json_encode($get_responce));
            return \Response::json($get_responce);
        }

    }



    /********************************************
    ## TestApi
    *********************************************/
     public function TestApi(){
        $now=date('Y-m-d H:i:s');

        try{

			/*
			    $path =date('Y_m_d_H_is').".jpg";
			    $requesinfo = \Request::input('requestinfo');
			    $image = $requesinfo['user_profile_image'];
			
			    $actualpath = "http://yess.com.bd/public/assets/images/userprofile/".$path;
			    $ifp = fopen('$actualpath', 'wb' );
			
			    $effect = file_put_contents($path,base64_decode($image));
			    fclose( $ifp ); 
			    echo "Successfully Uploaded";
			 
                $requesinfo = \Request::input('requestinfo');

                $name=$requesinfo['name'];
                $email=$requesinfo['email'];
                $user_profile_image=$requesinfo['user_profile_image'];

                $get_responce["requesinfo"]=$requesinfo;

                \DB::table('request_log')->where('request_id',$this->request_id)->update(array("request_response" =>json_encode($get_responce),"updated_at"=>$now));
                \App\Api::ResponseLogWrite('Testing',json_encode($get_responce));
                return \Response::json($get_responce);
				$get_responce["requesinfo"]=$effect;
				$get_responce["actualpath"]=$actualpath;*/



                $get_responce['name']=array('Tarik','Naim','Shohel');
                $get_responce['message']=\App\Admin::CouponJsonResponce('200','Testing Successfully !');


				\DB::table('request_log')->where('request_id',$this->request_id)->update(array("request_response" =>json_encode($get_responce),"updated_at"=>$now));
                \App\Api::ResponseLogWrite('Testing',json_encode($get_responce));

                return \Response::json($get_responce);



        }catch(\Exception $e){

            $get_responce=\App\Admin::CouponJsonResponce('501','Missing or incorrect data, Sorry the requested resource does not exist');
            \DB::table('request_log')->where('request_id',$this->request_id)->update(array("request_response" =>json_encode($get_responce),"updated_at"=>$now));

            $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();

            \App\System::ErrorLogWrite($message);
            \App\Api::ResponseLogWrite($message,json_encode($get_responce));
            return \Response::json($get_responce);
        }

    }



    /********************************************
     ## CategoryAllCouponInfo
     *********************************************/
        public function CategoryAllCouponInfo(){

            $now=date('Y-m-d H:i:s');

            try{
                $accessinfo = \Request::input('accessinfo');
                $requestinfo = \Request::input('requesinfo');

                $imei_no= isset($accessinfo['imei_no']) ? trim($accessinfo['imei_no']):'';
                $access_token= isset($accessinfo['access_token']) ? trim($accessinfo['access_token']):'';
                $user_id=$accessinfo['user_id'];

                $get_info=\DB::table('table_app_token')->where('imei_no',$imei_no)->where('access_token',$access_token)->first();

                if(!empty($get_info) && ($requestinfo['categorycoupon']=='getCategorycoupon')){


                       $coupon_count = (isset($requestinfo['coupon_count']) && is_numeric($requestinfo['coupon_count']))? $requestinfo['coupon_count']:10;
                       $coupon_sub_category = $requestinfo['coupon_sub_category'];


                        $all_cupon=\DB::table('tbl_coupon')
                                ->where(function($query) use ($coupon_sub_category) {
                                    if(!empty($coupon_sub_category) && ($coupon_sub_category != 0)){
                                        $query->where(function ($q) use ($coupon_sub_category) {
                                            $q->where('tbl_coupon.coupon_sub_category_id', $coupon_sub_category);
                                        });
                                    }
                                })
                                ->leftjoin('tbl_category','tbl_coupon.coupon_category_id','=','tbl_category.category_id')
                                ->leftjoin('tbl_sub_category','tbl_coupon.coupon_sub_category_id','=','tbl_sub_category.sub_category_id')
                                ->leftjoin('tbl_merchant','tbl_coupon.coupon_merchant_id','=','tbl_merchant.merchant_id')
                                ->leftjoin('tbl_branch','tbl_coupon.coupon_branch_id','=','tbl_branch.branch_id')
                                ->where('tbl_coupon.coupon_closing_date','>=',date('Y-m-d').' 23:59:59')
                                ->select('tbl_coupon.*','tbl_category.*','tbl_sub_category.*','tbl_merchant.*','tbl_branch.*')
                                ->groupBy('tbl_coupon.coupon_merchant_id')
                                ->paginate($coupon_count);

                        $all_cupon->appends(
                            [
                                'coupon_count' => $coupon_count,
                            ]
                        )->render();

                        $nextPageUrl = $all_cupon->nextPageUrl();

                        if($all_cupon->isEmpty())
                            throw new \Exception("No data available for Coupon.");

                        $response['nextPageUrl']= $nextPageUrl;


                        $response["success"]= [
                            "statusCode"=> 200,
                            "successMessage"=> "Coupon Info Successfully Send",
                            "serverReferenceCode"=>$now
                        ];


                        $response["getCategorycoupon"] = $all_cupon;




                        $merchant_info=\DB::table('tbl_merchant')
                            ->where('tbl_merchant.merchant_status','1')
                            ->orderBy('tbl_merchant.merchant_id','desc')
                            ->get();

                        $category_info=\DB::table('tbl_category')
                            ->orderBy('tbl_category.category_id','desc')
                            ->get();

                        $sub_category_info=\DB::table('tbl_sub_category')
                            ->orderBy('tbl_sub_category.sub_category_id','desc')
                            ->get();

                        $response["merchant_info"]=$merchant_info;
                        $response["category_info"]=$category_info;
                        $response["sub_category_info"]=$sub_category_info;

                    $total_coupon=count($all_cupon);


                    $all_coupons=collect($all_cupon)->groupBy('tbl_merchant.merchant_id');


                    $response["getAllcoupon"]=$all_cupon;
                    $response["success"]= [
                        "statusCode"=> 200,
                        "successMessage"=> "Get All Coupon Successfully",
                        "serverReferenceCode"=>$now,
                    ];

                    $requestlog_update_data=[
                        "request_response"=>json_encode($response["success"]),
                        "updated_at"=>$now,
                    ];
                    \DB::table('request_log')->where('request_id',$this->request_id)->update($requestlog_update_data);
                    \App\Api::ResponseLogWrite('Get All coupon',json_encode($response));

                    return \Response::json($response);

                }else{
                    $response["errors"]= [
                        "statusCode"=> 403,
                        "errorMessage"=> "IMEI Number or App Key is invalid",
                        "serverReferenceCode"=> $now
                    ];

                    \DB::table('request_log')->where('request_id',$this->request_id)->update(array("request_response" =>json_encode($response),"updated_at"=>$now));

                    \App\Api::ResponseLogWrite('IMEI Number or App Key is invalid',json_encode($response));

                    return \Response::json($response);
                }

            }catch(\Exception $e){

                 $response["errors"]= [
                    "statusCode"=> 501,
                    "errorMessage"=> "Missing or incorrect data, Sorry the requested resource does not exist",
                    "serverReferenceCode"=> $now,
                ];

                \DB::table('request_log')->where('request_id',$this->request_id)->update(array("request_response" =>json_encode($response),"updated_at"=>$now));

                $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();

                \App\System::ErrorLogWrite($message);
                \App\Api::ResponseLogWrite($message,json_encode($response));
                return \Response::json($response);
            }


        }




    /********************************************
     ## MerchantAllCouponInfo
     *********************************************/
        public function MerchantAllCouponInfo(){

            $now=date('Y-m-d H:i:s');

            try{
                $accessinfo = \Request::input('accessinfo');
                $requestinfo = \Request::input('requesinfo');

                $imei_no= isset($accessinfo['imei_no']) ? trim($accessinfo['imei_no']):'';
                $access_token= isset($accessinfo['access_token']) ? trim($accessinfo['access_token']):'';
                $user_id=$accessinfo['user_id'];
                $merchant_id=$requestinfo['merchant_id'];

                $get_info=\DB::table('table_app_token')->where('imei_no',$imei_no)->where('access_token',$access_token)->first();

                if(!empty($get_info) && !empty($merchant_id)){

                    $get_merchant_info=\DB::table('tbl_merchant')->where('tbl_merchant.merchant_id',$merchant_id)->first();

                    if(!empty($get_merchant_info)){

                        $merchantcouponinfo=\DB::table('tbl_coupon') 
                                ->where('tbl_coupon.coupon_merchant_id',$merchant_id)
                                ->where('tbl_coupon.coupon_closing_date','>=',date('Y-m-d').' 23:59:59')
                                ->leftjoin('tbl_category','tbl_coupon.coupon_category_id','=','tbl_category.category_id')
                                ->leftjoin('tbl_sub_category','tbl_coupon.coupon_sub_category_id','=','tbl_sub_category.sub_category_id')
                                ->leftjoin('tbl_merchant','tbl_coupon.coupon_merchant_id','=','tbl_merchant.merchant_id')
                                ->leftjoin('tbl_branch','tbl_coupon.coupon_branch_id','=','tbl_branch.branch_id')
                                ->get();
                        

                        $response["merchantcouponinfo"]=$merchantcouponinfo;

                        $response["success"]= [
                            "statusCode"=> 200,
                            "successMessage"=> "Get Merchant All Coupon Successfully",
                            "serverReferenceCode"=>time()
                        ];
                            
                        $requestlog_update_data=[
                            "request_response"=>json_encode($response),
                            "updated_at"=>$now,
                        ];


                        \DB::table('request_log')->where('request_id',$this->request_id)->update($requestlog_update_data);

                        \App\Api::ResponseLogWrite('Get Coupon Successfully',json_encode($response));
                        return \Response::json($response);

                    }else{

                        $response["errors"]= [
                            "statusCode"=> 403,
                            "errorMessage"=> "Invalid Coupon",
                            "serverReferenceCode"=> $now,
                        ];
                        \DB::table('request_log')->where('request_id',$this->request_id)->update(array("request_response" =>json_encode($response),"updated_at"=>$now));
                        \App\Api::ResponseLogWrite('Invalid Merchant',json_encode($response));

                        return \Response::json($response);
                    }

                }else{
                    $response["errors"]= [
                        "statusCode"=> 403,
                        "errorMessage"=> "IMEI Number or App Key is invalid",
                        "serverReferenceCode"=> $now
                    ];

                    \DB::table('request_log')->where('request_id',$this->request_id)->update(array("request_response" =>json_encode($response),"updated_at"=>$now));
                    \App\Api::ResponseLogWrite('IMEI Number or App Key is invalid',json_encode($response));

                    return \Response::json($response); 
                }

            }catch(\Exception $e){
                $response["errors"]= [
                    "statusCode"=> 501,
                    "errorMessage"=> "Missing or incorrect data, Sorry the requested resource does not exist",
                    "serverReferenceCode"=> $now,
                ];

                \DB::table('request_log')->where('request_id',$this->request_id)->update(array("request_response" =>json_encode($response),"updated_at"=>$now));

                $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();

                \App\System::ErrorLogWrite($message);
                \App\Api::ResponseLogWrite($message,json_encode($response));
                return \Response::json($response);
            }


        }









    /*--------------------End of API Controller------------------------*/

}
