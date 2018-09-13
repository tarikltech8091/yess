<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(){
       
        $this->page_title = \Request::route()->getName();
    }



    /********************************************
    # UserProfile
    *********************************************/
    public function UserProfile(){

        if(isset($_REQUEST['tab']) && !empty($_REQUEST['tab'])){
            $tab = $_REQUEST['tab'];
        }else $tab = 'panel_overview';
        
        $data['tab']=$tab;

        $user_info=\DB::table('users')->where('email', \Auth::user()->email)
        ->first();
        $data['user_info']=$user_info;
        $data['name']=explode(' ', $user_info->name);

        $data['page_title'] = $this->page_title;
        return \View::make('dashboard.pages.user-profile',$data);
    }


    /********************************************
    ## UserProfileUpdatePassword 
    *********************************************/
    public function UserProfileUpdatePassword($user_id){

        $now=date('Y-m-d H:i:s');

        $rules=array(
            'new_password' => 'Required',
            'confirm_password' => 'Required',
            'current_password' => 'Required',
            );

        $v=\Validator::make(\Request::all(), $rules);
        

        if($v->passes()){

            $new_password=\Request::input('new_password');
            $confirm_password=\Request::input('confirm_password');

            $user_info=\DB::table('users')->where('user_id', $user_id)->first();
            
            if($new_password==$confirm_password){

                if (\Hash::check(\Request::input('current_password'), $user_info->password)) {

                    $update_password=array(
                        'password' => bcrypt(\Request::input('new_password')),
                        'updated_at' => $now,
                        );

                    try{
                        $update=\DB::table('users')->where('user_id', $user_info->user_id)->update($update_password);
                        \App\System::EventLogWrite('update,users', 'password changed');

                        return \Redirect::to('/user/profile/view/id-'.$user_id)->with('message',"Password Updated Successfully !");

                    }catch(\Exception $e){

                        $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                        \App\System::ErrorLogWrite($message);
                        return \Redirect::to('/user/profile/view/id-'.$user_id)->with('message',"Password Update Failed  !");
                    }

                }else return \Redirect::to('/user/profile/view/id-'.$user_id.'?tab=change_password')->with('message',"Current Password Doesn't Match !");

            }else return \Redirect::to('/user/profile/view/id-'.$user_id.'?tab=change_password')->with('message',"Password Combination Doesn't Match !");

        }else return \Redirect::to('/user/profile/view/id-'.$user_id.'?tab=change_password')->withErrors($v->messages());

    }


    /********************************************
    ## ProfileUpdate 
    *********************************************/
    public function ProfileUpdate(){

        $user_id=\Auth::user()->id;

        $rules=array(
            'name' => 'Required',
            'email' => 'Required|email',
            'user_mobile' => '',
            );

        $v=\Validator::make(\Request::all(), $rules);

        if($v->passes()){

            $now=date('Y-m-d H:i:s');
            $name_slug = explode(' ', strtolower(\Request::input('name')));
            $name_slug = implode('_', $name_slug);

            $user_info=\DB::table('users')->where('id', $user_id)->first();

            if(!empty(\Request::file('image_url'))){

                $image = \Request::file('image_url');
                $img_location=$image->getRealPath();
                $img_ext=$image->getClientOriginalExtension();
                $user_profile_image=\App\Admin::UserImageUpload($img_location, $name_slug, $img_ext);

                $user_profile_image=$user_profile_image;

                $user_new_img = array(
                 'user_profile_image' => $user_profile_image,
                 );
                $user_img_update=\DB::table('users')->where('id', $user_id)->update($user_new_img);

            }

            $user_info_update_data=array(
                'name' =>  \Request::input('name'),
                'name_slug' => $name_slug,
                'email' => \Request::input('email'),
                'mobile' => empty(\Request::input('user_mobile')) ? $user_info->user_mobile:\Request::input('user_mobile'),
                'updated_at' => $now,
                );

            try{

                $update_user_info=\DB::table('users')->where('id', $user_id)->update($user_info_update_data);
                \App\System::EventLogWrite('update,users',json_encode($user_info_update_data));
                return \Redirect::to('/user/profile')->with('message',"Info Updated Successfully !");

            }catch(\Exception $e){

                $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                \App\System::ErrorLogWrite($message);

                return \Redirect::to('/user/profile')->with('message',"Info Already Exist !");
            }

        }else return \Redirect::to('/user/profile')->withErrors($v->messages());


    }

    /********************************************
    ## UserChangePassword 
    *********************************************/
    public function UserChangePassword(){

        $now=date('Y-m-d H:i:s');

        $rules=array(
            'new_password' => 'Required',
            'confirm_password' => 'Required',
            'current_password' => 'Required',
            );

        $v=\Validator::make(\Request::all(), $rules);

        if($v->passes()){

            $new_password=\Request::input('new_password');
            $confirm_password=\Request::input('confirm_password');
            
            if($new_password==$confirm_password){

                if (\Hash::check(\Request::input('current_password'), \Auth::user()->password)) {

                    $update_password=array(
                        'password' => bcrypt(\Request::input('new_password')),
                        'updated_at' => $now,
                        );

                    try{
                        $update=\DB::table('users')->where('id', \Auth::user()->id)->update($update_password);
                        \App\System::EventLogWrite('update,users', 'password changed');

                        return \Redirect::to('/user/profile')->with('message',"Password Updated Successfully !");

                    }catch(\Exception $e){

                        $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                        \App\System::ErrorLogWrite($message);
                        return \Redirect::to('/user/profile')->with('message',"Password Update Failed  !");
                    }

                }else return \Redirect::to('/user/profile?tab=change_password')->with('message',"Current Password Doesn't Match !");

            }else return \Redirect::to('/user/profile?tab=change_password')->with('message',"Password Combination Doesn't Match !");

        }else return \Redirect::to('/user/profile?tab=change_password')->withErrors($v->messages());

    }


    /********************************************
    ## ContactPage 
    *********************************************/
    public function ContactPage(){

        $company_info=\DB::table('company_details')->latest()->first();
        $locations=\DB::table('company_details')->get();
        $data['locations']=$locations;
        $data['company_info']=$company_info;

        $data['page_title'] = $this->page_title;   
        return \View::make('pages.contact-page',$data);
    }

    /********************************************
    ## ContactConfirm
    *********************************************/
    public function ContactConfirm(){

        $user_id=\Auth::user()->id;
        $now=date('Y-m-d H:i:s');

        $rules=array(
            'user_name' => 'Required',
            'email_address' => 'Required|email',
            'message' => 'Required',
            );

        $v=\Validator::make(\Request::all(), $rules);

        if($v->passes()){

            $user_name = \Request::input('user_name');
            $email_address = \Request::input('email_address');
            $message = \Request::input('message');

            $contact_data=array(
                'client_name' =>  \Request::input('user_name'),
                'user_type' =>  \Request::input('user_type'),
                'contact_type' =>  'message_request',
                'client_mobile' => \Request::input('client_mobile'),
                'client_email' => \Request::input('email_address'),
                'client_message' => \Request::input('message'),
                'created_at' => $now,
                'updated_at' => $now,
            );



            try{
                $contact_data_info=\DB::table('tbl_contact')->insert($contact_data);
                \App\System::EventLogWrite('insert,tbl_contact',json_encode($contact_data));
                
                // \Mail::send($message, function($message) use ($email_address,$user_name) {
                //     $message->to($email_address,$user_name)->subject('Contact');
                // });
                return \Redirect::to('/contact/page')->with('message',"Contact Successfully !");

            }catch(\Exception $e){

                $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                \App\System::ErrorLogWrite($message);

                return \Redirect::to('/contact/page')->with('message',"Info Already Exist !");
            }

        }else return \Redirect::to('/contact/page')->withInput(\Request::all())->withErrors($v->messages());
    }



    /********************************************
    ## ClientCallRequest 
    *********************************************/
    public function ClientCallRequest(){


        $rules=array(
            'client_name' => 'Required',
            'user_type' => 'Required',
            'client_mobile' => 'Required',
            );

        $v=\Validator::make(\Request::all(), $rules);

        if($v->passes()){

            $now=date('Y-m-d H:i:s');

            $call_request_data=array(
                'client_name' =>  \Request::input('client_name'),
                'user_type' =>  \Request::input('user_type'),
                'contact_type' =>  'call_request',
                'client_mobile' => \Request::input('client_mobile'),
                'client_email' => \Request::input('client_email'),
                'client_message' => \Request::input('message'),
                'created_at' => $now,
                'updated_at' => $now,
                );

            try{

                $call_request_data_info=\DB::table('tbl_contact')->insert($call_request_data);
                \App\System::EventLogWrite('insert,tbl_contact',json_encode($call_request_data));
                return \Redirect::back()->with('message',"Call Request Successfully !");

            }catch(\Exception $e){

                $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                \App\System::ErrorLogWrite($message);

                return \Redirect::back()->with('message',"Info Already Exist !");
            }

        }else return \Redirect::back()->withErrors($v->messages());


    }



    /********************************************
    ## CreateEventPage
    *********************************************/
    public function CreateEventPage(){

        $merchant_info=\DB::table('tbl_merchant')
                ->where('merchant_status','1')
                ->get();

        $data['merchant_info'] = $merchant_info;    
        $data['page_title'] = $this->page_title;    
        return \View::make('pages.create-event-notification',$data);

    }



    /********************************************
    ## CreateEventConfirm
    *********************************************/
    public function CreateEventConfirm(){

        $user_id=\Auth::user()->id;
        $now=date('Y-m-d H:i:s');

        $rules=array(
            'event_type' => 'required',
            'event_date' => 'required',
            'coupon_merchant_id' => 'required',
            'title' => 'required',
            'message' => 'required',
            );

        $v=\Validator::make(\Request::all(), $rules);



        if($v->passes()){
            $event_type =\Request::input('event_type');
            $event_date =\Request::input('event_date');
            $coupon_merchant_id =\Request::input('coupon_merchant_id');
            $title =\Request::input('title');
            $message =\Request::input('message');

            $event_data=array(
                'event_type' =>$event_type,
                'event_user_id' =>$user_id,
                'event_merchant_id' => $coupon_merchant_id,
                'event_date' => $event_date,
                'title' => $title,
                'message' =>$message,
                'event_status' =>'1',
                'created_by' => $user_id,
                'updated_by' => $user_id,
                'created_at' => $now,
                'updated_at' => $now,
                );

            try{

                $event_data_info=\DB::table('tbl_event')->insert($event_data);
                \App\System::EventLogWrite('insert,tbl_event',json_encode($event_data));
                return \Redirect::to('/event/notification/page')->with('message',"Event Insert Successfully !");

            }catch(\Exception $e){

                $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                \App\System::ErrorLogWrite($message);

                return \Redirect::to('/event/notification/page')->with('errormessage',"Info Already Exist !");
            }

        }else return \Redirect::to('/event/notification/page')->withErrors($v->messages());
    }





    ############## End ###############
}
