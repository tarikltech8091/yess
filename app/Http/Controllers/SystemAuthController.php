<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Auth;
use App\User;
use App\Admin;
use App\Mail;
use Session;
use Cookie;


class SystemAuthController extends Controller
{
    
	##############Construct#############
	public function __construct(){

		$this->page_title = \Request::route()->getName();
        \App\System::AccessLogWrite();
    }
	##############Construct#############


	 /********************************************
    ## AdminLoginPage 
    *********************************************/

     public function AdminLoginPage(){

        if(\Auth::check()){

            if(!empty(\Auth::user()->user_type)){

                if(\Auth::user()->user_type=="admin"){

                    \App\User::LogInStatusUpdate("login");
                    return \Redirect::to('/dashboard/admin');

                }
                elseif(\Auth::user()->user_type=="merchant"){

                    \App\User::LogInStatusUpdate("login");
                    return \Redirect::to('/dashboard/merchant');

                }
                elseif(\Auth::user()->user_type=="branch"){

                    \App\User::LogInStatusUpdate("login");
                    return \Redirect::to('/dashboard/branch');

                }
                else{
                    \App\User::LogInStatusUpdate("login");
                    return \Redirect::to('/');
                }
                
            }else{

                \Auth::logout();

                return \Redirect::to('/adminlogin')->with('errormessage',"Whoops, looks like something went wrong.");
            }
            
        }else{

            $data['page_title'] = $this->page_title;

            $session_user_type=\Session::get('user_type');

            if(!empty($session_user_type)){
                $user_info=\DB::table('users')->where('user_type', $session_user_type)->select('user_type','name')->first();

                $data['user_info']=$user_info;
            }


            return \View::make('dashboard.admin-login',$data);

        }

    }

    /********************************************
    ## AdminAuthenticationCheck 
    *********************************************/

    public function AdminAuthenticationCheck(){
       $rules = [
       'user_name' =>'required',
       'password'=> 'required',
       ];

       $v = \Validator::make(\Request::all(),$rules);

        if($v->passes()){

            $remember = (\Request::has('remember')) ? true : false;
            $credentials = [
            	'user_name' => \Request::input('user_name'),
                'password'=> \Request::input('password'),
            	'status'=> 1,
            ];

            if(\Auth::attempt($credentials,$remember)){

                \Session::put('user_name', \Auth::user()->user_name);


                if($remember)
                        \Session::put('current_user_info',\Auth::user());

                if ( \Session::has('pre_login_url') ){

                    $url = \Session::get('pre_login_url');
                    \Session::forget('pre_login_url');
                    return \Redirect::to($url);
                }

                elseif(\Auth::user()->user_type=="admin"){

                    \App\User::LogInStatusUpdate("login");
                    return \Redirect::to('/dashboard/admin');

                }
                elseif(\Auth::user()->user_type=="merchant"){

                    \App\User::LogInStatusUpdate("login");
                    return \Redirect::to('/dashboard/merchant');
                }
                elseif(\Auth::user()->user_type=="branch"){

                    \App\User::LogInStatusUpdate("login");
                    return \Redirect::to('/dashboard/branch');
                }
                else{

                    \App\User::LogInStatusUpdate("logout");
                    \Auth::logout();

                    return \Redirect::to('/adminlogin')->with('errormessage',"Whoops, looks like something went wrong.");
                }

            }else return \Redirect::to('/adminlogin')->with('errormessage',"Incorrect combinations.Please try again.");

        }else return  \Redirect::to('/adminlogin')->withInput()->withErrors($v->messages());

    }


    /********************************************
    ## LoginWithDifferentAccount 
    *********************************************/

    public function LoginWithDifferentAccount(){

       \Session::forget('user_name');

       return \Redirect::to('/adminlogin');

   }


    /********************************************
    ## AdminLogout 
    *********************************************/

    public function AdminLogout(){

        if(\Auth::check()){

            //$user_infos = \App\User::where('email',\Auth::user()->email)->first();
            $user_info = \App\User::where('user_type',\Auth::user()->user_type)->first();

            if(!empty($user_info)){
                \App\User::LogInStatusUpdate("logout");
                \Auth::logout();
                return \Redirect::to('/adminlogin');

            }else return \Redirect::to('/adminlogin');

        }else return \Redirect::to('/adminlogin')->with('errormessage',"Error logout");
        
    }



    #--------------------------------User Module----------------------------#

    /********************************************
    ## UserManagementPage 
    *********************************************/
    public function UserManagementPage(){

            if(isset($_REQUEST['tab']) && !empty($_REQUEST['tab'])){
                $tab = $_REQUEST['tab'];
            }else $tab = 'create_user';
            $data['tab']=$tab;

            $data['user_info']=\DB::table('users')->get();
            $data['merchant_info']=\DB::table('tbl_merchant')->get();

        $data['page_title'] = $this->page_title;      
        return \View::make('dashboard.pages.admin.admin-user-management',$data);
    }


    /********************************************
    ## AjaxUserStatusChange
    *********************************************/
    public function AjaxUserStatusChange($select_user_id,$action,$tab){
        $user_id=\Auth::user()->id;
        $now=date('Y-m-d H:i:s');
        
        $user_update_data=array(
            'status' => $action,
            'updated_by' => $user_id,
            'updated_at' => $now,
            );

        try{

            $user_status_update=\DB::table('users')->where('id',$select_user_id)->update($user_update_data);
            \App\System::EventLogWrite('update,users',json_encode($user_update_data));
            return \Redirect::to('/dashboard/user-management')->with('message',"User Status Changed Successfully !");

        }catch(\Exception $e){

            $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
            \App\System::ErrorLogWrite($message);

            return \Redirect::to('/dashboard/user-management')->with('message',"Info Already Exist !");
        }

    }

    /********************************************
    ## UserRegistration 
    *********************************************/

    public function UserRegistration(){

        $now=date('Y-m-d H:i:s');
        $rules=array(
            'name' => 'required',
            'user_name' => 'required|unique:users,user_name',
            'user_mobile' => 'required|regex:/^[^0-9]*(88)?0/|max:11',
            'email' => 'required|email',
            'password' => 'required',
            'confirm_password' => 'required|in_array:password',
            );
        $v=\Validator::make(\Request::all(), $rules);

        if($v->passes()){


            $user_name = \Request::input('user_name');
            $user_name_info=\DB::table('users')->where('user_name',$user_name)->first();
            if(empty($user_name_info)){
                
                \DB::beginTransaction();
                try{

                    $name = \Request::input('name');
                    $name_slug = explode(' ',$name);
                    $name_slug = implode('_', $name_slug);

                    if(!empty(\Request::file('user_profile_image'))){

                        $image = \Request::file('user_profile_image');
                        $img_location=$image->getRealPath();
                        $img_ext=$image->getClientOriginalExtension();
                        $user_profile_image=\App\Admin::UserImageUpload($img_location, $name_slug, $img_ext);

                        $user_profile_image=$user_profile_image;

                        $user_new_img = array(
                         'user_profile_image' => $user_profile_image,
                         );

                    }
                    else{
                        $user_profile_image='';
                    }

                    $user_type = \Request::input('user_type');
                    $merchant_id=\Request::input('merchant_id');
                    $branch_id=\Request::input('branch_id');
		    $user_platform='web';

                    if($user_type == 'branch'){
                        $user_merchant_id=$merchant_id;
                    }else{
                        $user_merchant_id='';
                    }

                    $user_registration=array(
                        'name' => \Request::input('name'),
                        'name_slug' => $name_slug,
                        'user_type' => \Request::input('user_type'),
                        'login_status' => 1,
                        'status' => 1,
                        'mobile' => \Request::input('user_mobile'),
                        'email' => \Request::input('email'),
                        'user_platform' => $user_platform,
                        'registration_platform' => $user_platform,
                        'user_profile_image' => $user_profile_image,
                        'user_merchant_id' => $user_merchant_id,
                        'password' => bcrypt(\Request::input('password')),
                        'created_by' => \Auth::user()->id,
                        'updated_by' => \Auth::user()->id,
                        'created_at' => $now,
                        'updated_at' => $now,
                        );


                    $get_user_id=\DB::table('users')->insertGetId($user_registration);

                    if($user_type == 'merchant'){
                       $merchant_status_update=array(
                            'merchant_status' => 1,
                            'merchant_user_id' =>$get_user_id,
                        ); 
                        $merchant_update=\DB::table('tbl_merchant')->where('merchant_id',$merchant_id)->update($merchant_status_update);
                        \App\System::EventLogWrite('update,tbl_merchant',json_encode($merchant_status_update));


                    }elseif($user_type == 'branch'){
                        $branch_status_update=array(
                            'branch_status' => 1,
                            'branch_user_id' =>$get_user_id,
                        );
                        $branch_update=\DB::table('tbl_branch')->where('branch_id',$branch_id)->update($branch_status_update);
                        \App\System::EventLogWrite('update,tbl_branch',json_encode($branch_update));
                    }

                	\App\System::EventLogWrite('insert,users',json_encode($user_registration));

                    \DB::commit();
                    return \Redirect::to('/dashboard/user-management')->with('message',"Registration Successfull ! Login To Continue..");

                }catch(\Exception $e){

                    $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                    \App\System::ErrorLogWrite($message);
                    \DB::rollback();
                    return \Redirect::to('/dashboard/user-management')->with('message',"Duplicate email or Something Went Wrong On User Registration ! Please try Again..");
                }

            }else return \Redirect::to('/dashboard/user-management')->withInput(\Request::all())->with('errormessage','This user name is registered, please use another user name !!!');

        }else return \Redirect::to('/dashboard/user-management')->withInput(\Request::all())->withErrors($v->messages());
        
    }



    /********************************************
    ## ClientRegistration 
    *********************************************/

    public function ClientUserRegistration(){

        $now=date('Y-m-d H:i:s');
        $rules=array(
            'name' => 'required',
            'user_mobile' => 'required|regex:/^[^0-9]*(88)?0/|max:11',
            'password' => 'required',
            'confirm_password' => 'required|in_array:password',
            );

        $v=\Validator::make(\Request::all(), $rules);

        if($v->passes()){

            $name = \Request::input('name');
            $user_mobile = \Request::input('user_mobile');
            $password = \Request::input('password');

            $client_user_info=\DB::table('users')->where('mobile',$user_mobile)->first();

            if(empty($client_user_info)){

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

                \DB::beginTransaction();
                try{

                    if(!empty($client_meta_info)){
                        $client_registration_save=\DB::table('tbl_user_meta')->where('user_meta_id',$client_meta_info->user_meta_id)->update($client_meta_data_info);
                        \App\System::EventLogWrite('update,tbl_user_meta',json_encode($client_meta_data_info));
                    }else{
                        $client_registration_save=\DB::table('tbl_user_meta')->insert($client_meta_data_info);
                        \App\System::EventLogWrite('insert,tbl_user_meta',json_encode($client_meta_data_info));
                    }
                    $otp_send=\App\OTP::SendSMSForUserRegistration($user_mobile,$client_otp);
                    \DB::commit();
                    return \Redirect::to('/sign-up/confirm/mobile-'.$user_mobile)->with('message',"A confirm code send your mobile");

                }catch(\Exception $e){
                    \DB::rollback();
                    $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                    \App\System::ErrorLogWrite($message);
                    return \Redirect::to('sign-in/page')->with('message',"Duplicate email or Something Went Wrong On User Registration ! Please try Again..");
                }
            }else{
                return \Redirect::to('sign-in/page')->with('message',"You are already registered user");
            }

        }else return \Redirect::to('/sign-in/page')->withErrors($v->messages());
        
    }

    /********************************************
    ## ResendSMSForUserRegistration
    *********************************************/
    public function ResendSMSForUserRegistration($customer_mobile,$client_otp){
        try{
            \App\OTP::SendSMSForUserRegistration($customer_mobile,$client_otp);
            return \Redirect::back()->with('message',"Code Send Successfully");

        }catch(\Exception $e){
            \DB::rollback();
            $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
            \App\System::ErrorLogWrite($message);

            return \Redirect::back()->with('errormessage',"Info Already Exist !");
        }

    }


    /********************************************
    ## Client SignUp Confirm Page
    *********************************************/

    public function ClientSignUpConfirmPage($user_mobile){
        $client_user_info=\DB::table('users')->where('mobile',$user_mobile)->first();
        if(empty($client_user_info)){
            $client_meta_info=\DB::table('tbl_user_meta')->where('user_id',$user_mobile)->where('user_meta_field_name','client_varify')->first();
            if(!empty($client_meta_info)){

                $data['page_title'] = $this->page_title;  
                $data['user_mobile'] = $user_mobile;  
                $data['client_meta_info'] = $client_meta_info;  

                return \View::make('pages.confirm-client-registration',$data);
            }else{
                return \Redirect::to('/sign-in/page')->withErrors($v->messages());
            } 
        }else{
            return \Redirect::to('/sign-in/page')->with('errormessage','You are already registered user.');
        }    
    }

    /********************************************
    ## ClientSignUpConfirm 
    *********************************************/

    public function ClientSignUpConfirm($user_mobile){

        $now=date('Y-m-d H:i:s');
        $rules=array(
            'otp_confirm' => 'required',
            );

        $v=\Validator::make(\Request::all(), $rules);

        if($v->passes()){
            $client_user_info=\DB::table('users')->where('mobile',$user_mobile)->first();
            if(empty($client_user_info)){
                $client_meta_info=\DB::table('tbl_user_meta')->where('user_id',$user_mobile)->where('user_meta_field_name','client_varify')->first();

                if(!empty($client_meta_info)){

                    try{

                        $otp_confirm = \Request::input('otp_confirm');
                        $client_data=unserialize($client_meta_info->user_meta_field_value);
                        $name = $client_data[0];
                        $password = $client_data[1];
                        $otp = $client_data[2];
                        $name_slug = explode(' ',$name);
                        $name_slug = implode('_', $name_slug);
                        $user_platform='web';

                        if($otp == $otp_confirm){

                            $client_user_registration=array(
                                'name' => $name,
                                'name_slug' => $name_slug,
                                'user_name' => $user_mobile,
                                'user_type' => 'client',
                                'login_status' => 0,
                                'status' => 1,
                                'mobile' => $user_mobile,
                                'user_platform' => $user_platform,
                                'registration_platform' => $user_platform,
                                'email' =>'',
                                'password' => bcrypt($password),
                                'created_at' => $now,
                                'updated_at' => $now,
                                );

                            $client_registration_save=\DB::table('users')->insert($client_user_registration);
                            \App\System::EventLogWrite('insert,users',json_encode($client_user_registration));

                            return \Redirect::to('/sign-in/page')->with('message',"Registration Successfull ! Login To Continue..");

                        }else{
                            return \Redirect::to('/sign-up/confirm/mobile-'.$user_mobile)->with('errormessage',"Invalid OTP");
                        }




                    }catch(\Exception $e){

                        $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                        \App\System::ErrorLogWrite($message);

                        return \Redirect::to('/sign-up/confirm/mobile-'.$user_mobile)->with('message',"Duplicate email or Something Went Wrong On User Registration ! Please try Again..");
                    }

                }else{
                    return \Redirect::to('sign-in/page')->with('message',"Something Wrong Please Try again.");
                }
            }else{
                return \Redirect::to('/sign-in/page')->with('errormessage','You are already registered user.');
            }  

        }else return \Redirect::to('/sign-in/page')->withErrors($v->messages());
        
    }




    /********************************************
    ## ClientLogin 
    *********************************************/

    public function ClientSignIn(){


        if(\Auth::check()){

            if(!empty(\Auth::user()->user_type)){

                if(\Auth::user()->user_type=="client" || \Auth::user()->user_type=="admin" || \Auth::user()->user_type=="merchant" || \Auth::user()->user_type=="branch"){

                    \App\User::LogInStatusUpdate("login");
                    return \Redirect::to('/');

                }

                else{
                    return \Redirect::to('/')->with('message','Please Login As Client'); 
                }
                
            }else{

                \Auth::logout();

                return \Redirect::to('/sign-in/page')->with('errormessage',"Whoops, looks like something went wrong.");
            }
            
        }else{

            $data['page_title'] = $this->page_title;

            return \View::make('pages.sign-in',$data);

        }

    }


    /********************************************
    ## ClientAuthenticationCheck 
    *********************************************/

    public function ClientAuthenticationCheck(){
       $rules = [
       'mobile' =>'required',
       'password'=> 'required',

       ];

       $v = \Validator::make(\Request::all(),$rules);


       if($v->passes()){

        $remember = (\Request::has('remember')) ? true : false;
        $credentials = [
        'mobile' => \Request::input('mobile'),
        'password'=> \Request::input('password'),
        'status'=> 1,
        ];

        if(\Auth::attempt($credentials)){
                $url = \Session::get('client_login_url');

            if ( \Session::has('client_login_url') ){

                $url = \Session::get('client_login_url');
                \Session::forget('client_login_url');
                return \Redirect::to($url);
            }elseif(\Auth::user()->user_type == "client" || \Auth::user()->user_type == 'admin' || \Auth::user()->user_type == 'branch' || \Auth::user()->user_type == 'merchant'){

                \App\User::LogInStatusUpdate("login");
                return \Redirect::to('/');
            }else{

                \App\User::LogInStatusUpdate("logout");
                \Auth::logout();

                return \Redirect::to('/sign-in/page')->with('errormessage',"Whoops, looks like something went wrong.");
            }

        }else return \Redirect::to('/sign-in/page')->with('errormessage',"Incorrect combinations.Please try again.");

    }else return  \Redirect::to('/sign-in/page')->withInput()->withErrors($v->messages());

}   

    /********************************************
    ## ClientLogout 
    *********************************************/

    public function ClientLogout($mobile){

        if(\Auth::check()){

            $user_info = \App\User::where('mobile',\Auth::user()->mobile)->first();

            if(!empty($user_info) && ($mobile==$user_info->mobile)){
                \App\User::LogInStatusUpdate("logout");
                \Session::forget('client_login_url');
                \Auth::logout();
                return \Redirect::to('/');

            }else return \Redirect::to('/sign-in/page');

        }else return \Redirect::to('/sign-in/page')->with('errormessage',"Error logout");
        
    }

    /********************************************
    ## ForgotPasswordPage
    *********************************************/

    public function ForgotPasswordPage(){
        $data['page_title'] = $this->page_title;
        return \View::make('forgot.forgot-password',$data);
    }

    /********************************************
    ## AdminForgotPasswordPage
    *********************************************/

    public function AdminForgotPasswordPage(){
        $data['page_title'] = $this->page_title;
        return \View::make('forgot.admin-forget-password',$data);
    }

    /********************************************
    ## ForgotPasswordConfirm
    *********************************************/

    public function ForgotPasswordConfirm(){

        $rules=array(
            'mobile' => 'Required|regex:/^[^0-9]*(88)?0/|max:11',
            'email' => 'Required|email',
        );

        $v=\Validator::make(\Request::all(), $rules);

        if($v->passes()){


                $data['page_title'] = $this->page_title;
		$email=\Request::input('email');
                $mobile=\Request::input('mobile');
                $user_info= \DB::table('users')->where('email','=',$email)->where('mobile','=',$mobile)->first();

            if(!empty($user_info)){

                try{    
                    $users_email=$user_info->email; 
                    $users_id=$user_info->id; 
                    $user_type=$user_info->user_type; 

                        $a=\Cookie::queue('petp_reset_password_email', $users_email, 60);
                            if(!empty($users_email)){
                                if(($users_email == $email) || ($user_type == 'client') || ($user_type == 'admin') || ($user_type == 'merchant') || ($user_type == 'branch')){
                                        $reset_url= url('/forget/password/'.$users_id.'/verify').'?token='.$user_info->remember_token;

                                        \App\Admin::ForgotPasswordEmail($users_id, $reset_url);

                                    return \Redirect::back()->with('message',"Please Check Email !");

                                }else{
                                
                                return \Redirect::back()->with('message',"Email does not match or you are not this type user !!!");
                                
                                }
                            }


                        \Cookie::queue('petp_reset_password_email', null, 60);




                }catch(\Exception $e){

                    $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                    \App\System::ErrorLogWrite($message);

                    return \Redirect::back()->with('message',"Info Already Exist !");
                }
                
            }else return \Redirect::back()->with('message',"Invalid Email!");

        }else return  \Redirect::back()->withInput()->withErrors($v->messages());

    }

    

    /********************************************
    ## ForgetPasswordMail 
    *********************************************/
    public function ForgetPasswordMail(){

        $data['page_title'] = $this->page_title;
        return \View::make('forgot.forget-password-mail',$data);

    }


    /********************************************
    ## SystemForgotPasswordVerification
    *********************************************/

    public function SystemForgotPasswordVerification($users_id){
        $remember_token=$_GET['token'];
        $email= \DB::table('users')->where('id','=',$users_id)->first();

        $data['email']=$email;
        $data['remember_token']=$remember_token;
        $data['page_title'] = $this->page_title;
        return \View::make('forgot.new-password',$data);
    }



    /********************************************
    ## SystemNewPasswordSubmit
    *********************************************/

    public function SystemNewPasswordSubmit(){
        $data['page_title'] = $this->page_title;
        $user_id=\Request::input('user_id');
        $rem_token=\Request::input('token');

        $now=date('Y-m-d H:i:s');
        $rules = [
        'password'=> 'required',
        'confirm_password'  =>'required|same:password',
        ];

        $v = \Validator::make(\Request::all(),$rules);
        if($v->passes()){

            $new_password_data =array(
                'password'=>\Hash::make( \Request::input('password')),
                'updated_at'=>$now,
                );
            
                try{

                    $chnage_password=\DB::table('users')->where('id',$user_id)->where('remember_token',$rem_token)->update($new_password_data);
                }catch(\Exception $e){

                    $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                    \App\System::ErrorLogWrite($message);

                    return \Redirect::back()->with('message',"Info Already Exist !");
                }

            if(!empty($chnage_password)){

                return \Redirect::to('/adminlogin')->with('message','Password Change Successfully.');
            }else{
                return \Redirect::to('/adminlogin')->with('errormessage','Password already changed, Please send email again.');
            }

        }else{ 
            return \Redirect::back()->with('message','Please Enter Same Password.');
        }
        return \Redirect::back()->with('message','Something Wrong');
    }




    
    /********************************************
    ## ErrorRequestPage
    *********************************************/
    public function ErrorRequestPage(){

        $data['page_title'] = $this->page_title;

        return \View::make('dashboard.errors.internal-404',$data);
    }

    /********************************************
    ## Page404
    *********************************************/
    public function Page404(){

        $data['page_title'] = $this->page_title;

        return \View::make('dashboard.errors.404',$data);
    }

    /********************************************
    ## Page503
    *********************************************/
    public function Page503(){

        $data['page_title'] = $this->page_title;

        return \View::make('dashboard.errors.503',$data);
    }






##################### End ###########################

}
