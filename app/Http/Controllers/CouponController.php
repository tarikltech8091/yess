<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\User;
use App\Admin;
use DB;
// use Session;


class CouponController extends Controller
{
    public function __construct(){
       
        $this->page_title = \Request::route()->getName();
        $description = \Request::route()->getAction();
        $this->page_desc = isset($description['desc']) ?  $description['desc']:'';
        \App\System::AccessLogWrite();
    }


    /********************************************
    ## Appspage 
    *********************************************/
    public function Appspage(){
        
        $data['page_title'] = $this->page_title;  
        $agent_info= $_SERVER['HTTP_USER_AGENT'];
        $pos = strpos($agent_info, "Android");
        if ($pos == true) {
            $platform ='Android';
        }
        $pos = strpos($agent_info, "iPhone");
        if ($pos == true) {
            $platform ='iPhone';
        }
        $pos = strpos($agent_info, "Windows");
        if ($pos == true) {
            $platform ='Windows';
        }

        if( isset($platform) && $platform == 'Android'){
            return \Redirect::to('https://play.google.com/store/apps/details?id=com.live.shohel.yess');
        }

        elseif (isset($platform) && $platform == 'iPhone'){
            return \Redirect::to('https://itunes.apple.com/us/app/yess/id1256304316?ls=1&mt=8');
        }
        else{
            return \Redirect::to('/');
        }
        
    }

    /********************************************
    ## HomePage 
    *********************************************/
    public function HomePage(){

        $all_subcategory_info=\DB::table('tbl_sub_category')
                        ->where('sub_category_status','1')
                        ->orderBy('tbl_sub_category.updated_at','desc')
                        ->limit(10)
                        ->get();


        $home_slider_info=\DB::table('tbl_setting_meta')
                        ->where('setting_meta_field_name','home_slider')
                        ->where('setting_meta_status','1')
                        ->orderBy('tbl_setting_meta.updated_at','desc')
                        ->limit(8)
                        ->get();
        $populer_merchant_info=\DB::table('tbl_merchant')
                        ->orderBy('tbl_merchant.created_at','desc')
                        ->limit(10)
                        ->get();
        $data['populer_merchant_info'] = $populer_merchant_info;   
        $data['all_subcategory_info'] = $all_subcategory_info;  
        $data['home_slider_info'] = $home_slider_info;  
        $data['page_title'] = $this->page_title;
        $data['page_desc'] = '';

        return \View::make('pages.index',$data);
    }




    /********************************************
    ## QuickViewCoupon 
    *********************************************/
    public function QuickViewCoupon($coupon_id){

        $select_coupon_info=\DB::table('tbl_coupon')
                        ->leftjoin('tbl_merchant','tbl_coupon.coupon_merchant_id','=','tbl_merchant.merchant_id')
                        ->leftjoin('tbl_branch','tbl_coupon.coupon_branch_id','=','tbl_branch.branch_id')
                        ->where('tbl_coupon.coupon_id',$coupon_id)
                        ->first();

        $data['select_coupon_info'] = $select_coupon_info;  
        $data['page_title'] = $this->page_title;  
        return \View::make('pages.ajax-coupon-details',$data);
    }

    /********************************************
    ## SinglePage 
    *********************************************/
    public function SinglePage($coupon_id){

        $select_coupon_info=\DB::table('tbl_coupon')
                        ->leftjoin('tbl_merchant','tbl_coupon.coupon_merchant_id','=','tbl_merchant.merchant_id')
                        ->leftjoin('tbl_branch','tbl_coupon.coupon_branch_id','=','tbl_branch.branch_id')
                        ->where('tbl_coupon.coupon_id',$coupon_id)
                        ->where('tbl_coupon.coupons_status','1')
                        ->where('tbl_merchant.merchant_status','1')
                        ->first();
        if(!empty($select_coupon_info) && count($select_coupon_info)>0){

            $select_coupon_review_info=\DB::table('tbl_coupon_review_comments')
                            ->leftjoin('users','tbl_coupon_review_comments.customer_id','=','users.id')
                            ->where('tbl_coupon_review_comments.coupon_id',$coupon_id)
                            ->orderBy('tbl_coupon_review_comments.updated_at','desc')
                            ->get();


            $locations =\DB::table('tbl_coupon')
                            ->leftjoin('tbl_merchant','tbl_coupon.coupon_merchant_id','=','tbl_merchant.merchant_id')
                            ->leftjoin('tbl_branch','tbl_coupon.coupon_branch_id','=','tbl_branch.branch_id')
                            ->where('tbl_coupon.coupon_id',$coupon_id)
                            ->where('tbl_coupon.coupons_status','1')
                            ->where('tbl_merchant.merchant_status','1')
                            ->get();

            $data['locations']= $locations;

            $data['select_coupon_info'] = $select_coupon_info;  
            $data['select_coupon_review_info'] = $select_coupon_review_info;
            $data['page_desc'] =$select_coupon_info->coupon_sub_category_id;

            if(\Auth::check()){
                 $client_rating=\DB::table('tbl_coupon_review_comments')
                 ->where('coupon_id', $select_coupon_info->coupon_id)
                 ->where('customer_id', \Auth::user()->id)
                 ->first();
                 $data['client_rating']=$client_rating;
            }

            $view_coupon_data=array(
                    'coupon_total_view' =>$select_coupon_info->coupon_total_view+1,
                    );

            $view_coupon_info=\DB::table('tbl_coupon')
                            ->where('coupon_id',$coupon_id)
                            ->update($view_coupon_data);
        }else{
            return \Redirect::back()->with('errormessage',"Invalid Coupon");
        }

        $data['page_title'] = $this->page_title;  
        return \View::make('pages.single-page',$data);
    }


    /********************************************
    ## GetSearchCoupon
    *********************************************/
    public function GetSearchCoupon(){

        $now=date('Y-m-d H:i:s');

        $search_text=\Request::input('search_text');

        if(\Auth::check()){
            $user_id=\Auth::user()->id;
            $user_type='client';
        }else{
            $user_id=-1;
            $user_type='guest';
        } 
            $search_data=array(
                'search_user_id' =>  $user_id,
                'user_type' => $user_type,
                'search_text' => $search_text,
                'created_by' => $user_id,
                'updated_by' => $user_id,
                'created_at' => $now,
                'updated_at' => $now,
                );

            try{

                $save_search_data_info=\DB::table('tbl_search_data')->insert($search_data);
                \App\System::EventLogWrite('insert,tbl_search_data',json_encode($search_data));
                return \Redirect::to('/search-result/text-'.$search_text);

            }catch(\Exception $e){

                $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                \App\System::ErrorLogWrite($message);

                return \Redirect::to('/search-result/text-'.$search_text);
            }
    }



    /********************************************
    ## SearchResultPage 
    *********************************************/
    public function SearchResultPage($search_text){
        $now=date('Y-m-d').' 23:59:59';

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
                        ->paginate(9);
        $search_coupon_info->setPath(url('/search-result/text-'.$search_text));
        $coupon_pagination = $search_coupon_info->render();
        $data['coupon_pagination']=$coupon_pagination;

        $data['search_coupon_info'] = $search_coupon_info;   
        $data['page_title'] = $this->page_title;   
        return \View::make('pages.serach-result',$data);
    }


    /********************************************
    ## FAQPage 
    *********************************************/
    public function FAQPage(){

        $data['page_title'] = $this->page_title;   
        return \View::make('pages.faq-page',$data);
    }


    /********************************************
    ## ClientProfilePage 
    *********************************************/
    public function ClientProfilePage($client_id){

        if(isset($_REQUEST['tab']) && !empty($_REQUEST['tab'])){
            $tab = $_REQUEST['tab'];
        }else $tab = 'panel_overview';
        $data['tab']=$tab;

        if(isset($_REQUEST['tab2']) && !empty($_REQUEST['tab2'])){
            $tab2 = $_REQUEST['tab2'];
        }else $tab2 = 'coupon_list';
        $data['tab2']=$tab2;


        if(isset($_REQUEST['tab3']) && !empty($_REQUEST['tab3'])){
            $tab3 = $_REQUEST['tab3'];
        }else $tab3 = 'mobile_panel_overview';
        
        $data['tab3']=$tab3;

        if(isset($_REQUEST['tab4']) && !empty($_REQUEST['tab4'])){
            $tab4 = $_REQUEST['tab4'];
        }else $tab4 = 'mobile_coupon_list';
        $data['tab4']=$tab4;


        $client_info=\DB::table('users')
                        ->where('id',$client_id)
                        ->first();

        $client_meta_info=\DB::table('tbl_user_meta')
                        ->where('user_id',$client_id)
                        ->where('user_meta_field_name','point')
                        ->first();

        $client_shopping_info=\DB::table('tbl_coupon_transaction')
                        ->leftjoin('tbl_coupon','tbl_coupon_transaction.coupon_id','=','tbl_coupon.coupon_id')
                        ->leftjoin('tbl_merchant','tbl_coupon_transaction.transaction_merchant_id','=','tbl_merchant.merchant_id')
                        ->leftjoin('tbl_branch','tbl_coupon_transaction.transaction_branch_id','=','tbl_branch.branch_id')
                        ->where('tbl_coupon_transaction.customer_id',$client_id)
                        ->OrderBy('tbl_coupon_transaction.updated_at','desc')
                        ->paginate(10);
        $client_shopping_info->setPath(url('/client/profile/page/id-'.$client_id.'?tab2=coupon_list'));
        $client_shopping_pagination = $client_shopping_info->render();
        $data['client_shopping_pagination']=$client_shopping_pagination;  
        $data['client_shopping_info'] = $client_shopping_info;


        $client_follow_info=\DB::table('tbl_follow_activity')
                        ->leftjoin('users','tbl_follow_activity.activity_user_id','=','users.id')
                        ->leftjoin('tbl_merchant','tbl_follow_activity.merchant_or_coupon_id','=','tbl_merchant.merchant_id')
                        ->where('tbl_follow_activity.activity_type', 'follow')
                        ->where('tbl_follow_activity.activity_list_status', '1')
                        ->where('tbl_follow_activity.activity_user_id', $client_id)
                        ->get();  
        $data['client_follow_info'] = $client_follow_info;

        $client_wish_list_info=\DB::table('tbl_follow_activity')
                        ->leftjoin('users','tbl_follow_activity.activity_user_id','=','users.id')
                        ->leftjoin('tbl_coupon','tbl_follow_activity.merchant_or_coupon_id','=','tbl_coupon.coupon_id')
                        ->leftjoin('tbl_merchant','tbl_coupon.coupon_merchant_id','=','tbl_merchant.merchant_id')
                        ->leftjoin('tbl_branch','tbl_coupon.coupon_branch_id','=','tbl_branch.branch_id')
                        ->where('tbl_follow_activity.activity_type', 'wish_list')
                        ->where('tbl_follow_activity.activity_user_id', $client_id)
                        ->get();  
        $data['client_wish_list_info'] = $client_wish_list_info;

        $data['client_info'] = $client_info;   
        $data['client_meta_info'] = $client_meta_info;   
        $data['page_title'] = $this->page_title;   
        return \View::make('pages.client-profile',$data);
    }


    /********************************************
    ## ClientProfileUpdate 
    *********************************************/
    public function ClientProfileUpdate($client_id){

        $user_id=\Auth::user()->id;

        $rules=array(
            'name' => 'Required',
            'mobile' => 'Required',
            );

        $v=\Validator::make(\Request::all(), $rules);

        if($v->passes()){

            $now=date('Y-m-d H:i:s');
            $name=\Request::input('name');
            $client_name_slug = explode(' ', strtolower(\Request::input('name')));
            $name_slug = implode('_', $client_name_slug);

            if(!empty(\Request::file('image_url'))){

            $image = \Request::file('image_url');
            $img_location=$image->getRealPath();
            $img_ext=$image->getClientOriginalExtension();
            $user_profile_image=\App\Admin::UserImageUpload($img_location, $name_slug, $img_ext);

            $user_profile_image=$user_profile_image;

            $user_new_img = array(
             'user_profile_image' => $user_profile_image,
             );
            $user_img_update=\DB::table('users')->where('id', $client_id)->update($user_new_img);

            }
            
            $user_info_update_data=array(
                'name' => $name,
                'name_slug' => $name_slug,
                'email' => \Request::input('email'),
                'mobile' => \Request::input('mobile'),
                'updated_at' => $now,
                );

            try{

                $update_user_info=\DB::table('users')->where('id', $user_id)->update($user_info_update_data);
                \App\System::EventLogWrite('update,users',json_encode($user_info_update_data));

                return \Redirect::to('/client/profile/page/id-'.\Auth::user()->id.'?tab=panel_edit_account')->with('message',"Info Updated Successfully !");

            }catch(\Exception $e){

                $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                \App\System::ErrorLogWrite($message);

                return \Redirect::to('/client/profile/page/id-'.\Auth::user()->id.'?tab=panel_edit_account')->with('message',"Info Already Exist !");
            }

        }else return \Redirect::to('/client/profile/page/id-'.\Auth::user()->id.'?tab=panel_edit_account')->withErrors($v->messages());


    }




    /********************************************
    ## ClientUserChangePassword 
    *********************************************/
    public function ClientUserChangePassword(){

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

                        return \Redirect::to('/client/profile/page/id-'.\Auth::user()->id)->with('message',"Password Updated Successfully !");

                    }catch(\Exception $e){

                        $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                        \App\System::ErrorLogWrite($message);
                        return \Redirect::to('/client/profile/page/id-'.\Auth::user()->id)->with('message',"Password Update Failed  !");
                    }

                }else return \Redirect::to('/client/profile/page/id-'.\Auth::user()->id.'?tab=change_password')->with('message',"Current Password Doesn't Match !");

            }else return \Redirect::to('/client/profile/page/id-'.\Auth::user()->id.'?tab=change_password')->with('message',"Password Combination Doesn't Match !");

        }else return \Redirect::to('/client/profile/page/id-'.\Auth::user()->id.'?tab=change_password')->withErrors($v->messages());

    }


    /********************************************
    ## ReviewPost
    *********************************************/
    public function ReviewPost($coupon_id){

        if(!empty(\Auth::check())){

            $user_id=\Auth::user()->id;
            $now=date('Y-m-d H:i:s');
            $review_check=\DB::table('tbl_coupon_review_comments')->where('coupon_id', $coupon_id)->where('customer_id', \Auth::user()->id)->first();

            $rules=array(
                'coupon_id' => 'Required',
                );

            $v=\Validator::make(\Request::all(), $rules);
            $coupon_id =  \Request::input('coupon_id');


            if($v->passes()){

                $review_data=array(
                    'coupon_id' =>  \Request::input('coupon_id'),
                    'customer_id' =>  \Auth::user()->id,
                    'coupon_comments' =>  \Request::input('coupon_comments'),
                    'created_by' => $user_id,
                    'updated_by' => $user_id,
                    'created_at' => $now,
                    'updated_at' => $now,
                    );

                $review_update_data=array(
                    'coupon_id' =>  \Request::input('coupon_id'),
                    'customer_id' =>  $user_id,
                    'coupon_comments' =>  \Request::input('coupon_comments'),
                    'updated_by' => $user_id,
                    'updated_at' => $now,
                    );


                try{

                    if(empty($review_check)){

                        $review_data_info=\DB::table('tbl_coupon_review_comments')->insert($review_data);
                        \App\System::EventLogWrite('insert,tbl_coupon_review_comments',json_encode($review_data));
                    }else{
                        $review_data_info=\DB::table('tbl_coupon_review_comments')->where('review_comments_id',$review_check->review_comments_id)->update($review_update_data);
                        \App\System::EventLogWrite('update,tbl_coupon_review_comments',json_encode($review_update_data));
                    }

                    return \Redirect::to('/single-page/coupon_id-'.$coupon_id)->with('message',"Your Review Successfull !");

                }catch(\Exception $e){

                    $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                    \App\System::ErrorLogWrite($message);

                    return \Redirect::to('/single-page/coupon_id-'.$coupon_id)->with('message',"Info Already Exist !");
                }

            }else return \Redirect::to('/single-page/coupon_id-'.$coupon_id)->withErrors($v->messages());
            
        }else return \Redirect::to('/sign-in/page')->with('errormessage',"Only registered user can comments.");

    }



    /********************************************
    ## ClientRating
    *********************************************/
    public function ClientRating($coupon_id, $rating_point){
        $now=date('Y-m-d H:i:s');
        if(!empty($coupon_id) && !empty(\Auth::check()) && !empty($rating_point)){

            $coupon_info=\DB::table('tbl_coupon')
                            ->where('coupon_id', $coupon_id)
                            ->first();

            $coupon_total_rating=$coupon_info->coupon_total_rating+$rating_point;
            $current_coupon_rating_client_count=$coupon_info->coupon_rating_client_count;

            $check_ratings=\DB::table('tbl_coupon_review_comments')
                            ->where('coupon_id', $coupon_id)
                            ->where('customer_id', \Auth::user()->id)
                            ->first();

            $rating=array(
                'coupon_id' => $coupon_id,
                'coupon_rating' => $rating_point,
                'customer_id' => \Auth::user()->id,
                'created_by' => \Auth::user()->id,
                'updated_by' => \Auth::user()->id,
                'created_at' => $now,
                'updated_at' => $now,
                );

            $update_rating=array(
                'coupon_id' => $coupon_id,
                'coupon_rating' => $rating_point,
                'customer_id' => \Auth::user()->id,
                'updated_by' => \Auth::user()->id,
                'updated_at' => $now,
                );

            

            if(!empty($check_ratings)){

                $current_rating=$check_ratings->coupon_rating;
                $current_coupon_total_rating=$coupon_total_rating-$current_rating;
                $coupon_rating_client_count=$current_coupon_rating_client_count;
                $rating_store=\DB::table('tbl_coupon_review_comments')->where('review_comments_id',$check_ratings->review_comments_id)->update($update_rating);

            }else{
                $current_coupon_total_rating=$coupon_total_rating;
                $coupon_rating_client_count=$current_coupon_rating_client_count+1;
                $rating_store=\DB::table('tbl_coupon_review_comments')->insert($rating);

            }

            $coupon_rating_update=array(
                'coupon_total_rating' => $current_coupon_total_rating,
                'coupon_rating_client_count' => $coupon_rating_client_count,
                );

            $coupon_rating_store=\DB::table('tbl_coupon')->where('coupon_id',$coupon_id)->update($coupon_rating_update);


        }else return \Redirect::to('/sign-in/page')->with('errormessage',"Only registered user can comments.");

    }



    /********************************************
    ## MerchantViewPage 
    *********************************************/
    public function MerchantViewPage(){
        $all_merchant_info=\DB::table('tbl_merchant')
                            ->where('tbl_merchant.merchant_status','1')
                            ->paginate(18);
        $all_merchant_info->setPath(url('/merchant-view/page'));
        $merchant_pagination = $all_merchant_info->render();
        $data['merchant_pagination']=$merchant_pagination;
        $data['all_merchant_info'] = $all_merchant_info;   
        $data['page_title'] = $this->page_title;   
        return \View::make('pages.merchant-view',$data);
    }


    /********************************************
    ## MerchantBranchViewPage 
    *********************************************/
    public function MerchantBranchViewPage($merchant_id){
        $now=date('Y-m-d');
        $all_branch_info=\DB::table('tbl_coupon')
                        ->leftjoin('tbl_merchant','tbl_coupon.coupon_merchant_id','=','tbl_merchant.merchant_id')
                        ->leftjoin('tbl_branch','tbl_coupon.coupon_branch_id','=','tbl_branch.branch_id')
                        ->where('tbl_merchant.merchant_id',$merchant_id)
                        ->where('tbl_coupon.coupon_closing_date','>=',$now)
                        ->where('tbl_coupon.coupons_status','1')
                        ->where('tbl_merchant.merchant_status','1')
                        ->get();

        if(!empty($all_branch_info) && count($all_branch_info)>0){
            if(!empty($merchant_id) && !empty(\Auth::check())){

                $user_id=\Auth::user()->id;
                $check_follow=\DB::table('tbl_follow_activity')
                                    ->where('activity_type', 'follow')
                                    ->where('merchant_or_coupon_id', $merchant_id)
                                    ->where('activity_user_id', $user_id)
                                    ->first();
                $data['check_follow']= $check_follow;

            }

            $merchant_featured_info=\DB::table('tbl_featured_product')
                            ->where('tbl_featured_product.merchant_id',$merchant_id)
                            ->leftjoin('tbl_merchant','tbl_featured_product.merchant_id','=','tbl_merchant.merchant_id')
                            ->leftjoin('tbl_branch','tbl_featured_product.branch_id','=','tbl_branch.branch_id')
                            ->leftjoin('tbl_coupon','tbl_featured_product.branch_id','=','tbl_coupon.coupon_branch_id')
                            ->where('tbl_featured_product.featured_product_status','1')
                            ->get();

        	$get_merchant__info=\DB::table('tbl_coupon')
                        ->leftjoin('tbl_merchant','tbl_coupon.coupon_merchant_id','=','tbl_merchant.merchant_id')
                        ->leftjoin('tbl_branch','tbl_coupon.coupon_branch_id','=','tbl_branch.branch_id')
                        ->where('tbl_merchant.merchant_id',$merchant_id)
                        ->where('tbl_coupon.coupon_closing_date','>=',$now)
                        ->where('tbl_coupon.coupons_status','1')
                        ->where('tbl_merchant.merchant_status','1')
                        ->first();

        	if(!empty($get_merchant__info)){
            		$data['page_desc'] = $get_merchant__info->coupon_sub_category_id;
        	}

            $locations =\DB::table('tbl_coupon')
                    ->leftjoin('tbl_merchant','tbl_coupon.coupon_merchant_id','=','tbl_merchant.merchant_id')
                    ->leftjoin('tbl_branch','tbl_coupon.coupon_branch_id','=','tbl_branch.branch_id')
                    ->select('tbl_coupon.*','tbl_branch.*','tbl_merchant.*')
                    ->where('tbl_merchant.merchant_id',$merchant_id)
                    ->where('tbl_coupon.coupon_closing_date','>=',$now)
                    ->where('tbl_coupon.coupons_status','1')
                    ->where('tbl_merchant.merchant_status','1')
                    ->get();

            $data['locations']= $locations;

            $data['merchant_featured_info']= $merchant_featured_info;
            $data['merchant_id'] = $merchant_id;     
            $data['all_branch_info'] = $all_branch_info;     
            $data['page_title'] = $this->page_title;
            return \View::make('pages.merchant-branch-view',$data);
        }else{
            return \Redirect::back()->with('errormessage',"There are no coupon.");
        }
    }



    /********************************************
    ## FollowMerchant
    *********************************************/
    public function FollowMerchant($merchant_id, $status){

        $now=date('Y-m-d H:i:s');
        if(!empty($merchant_id) && !empty(\Auth::check()) && !empty($status)){

            $user_id=\Auth::user()->id;

            $check_follow=\DB::table('tbl_follow_activity')
                            ->where('activity_type', 'follow')
                            ->where('merchant_or_coupon_id', $merchant_id)
                            ->where('activity_user_id', $user_id)
                            ->first();

            $follow_data=array(
                'activity_type' => 'follow',
                'activity_user_id' => $user_id,
                'merchant_or_coupon_id' => $merchant_id,
                'activity_list_status' => '1',
                'created_by' => $user_id,
                'updated_by' => $user_id,
                'created_at' => $now,
                'updated_at' => $now,
                );

            $update_follow_data=array(
                'activity_list_status' => $status,
                'updated_by' => $user_id,
                'updated_at' => $now,
                );
            

            if(!empty($check_follow)){

                $follow_store=\DB::table('tbl_follow_activity')->where('activity_id',$check_follow->activity_id)->update($update_follow_data);

                return \Redirect::to('/merchant/branch-view/page/mid-'.$merchant_id)->with('message',"You are now following these.");

            }else{
                $follow_store=\DB::table('tbl_follow_activity')->insert($follow_data);

                return \Redirect::to('/merchant/branch-view/page/mid-'.$merchant_id)->with('message',"You are already following this.");

            }


        }else return \Redirect::to('/sign-in/page')->with('errormessage',"Only registered user can follow.");

    }



    /********************************************
    ## ClientWishListAdd
    *********************************************/
    public function ClientWishListAdd($coupon_id, $status){
        $now=date('Y-m-d H:i:s');
        if(!empty($coupon_id) && !empty(\Auth::check()) && !empty($status)){

            $user_id=\Auth::user()->id;

            $check_wish_list=\DB::table('tbl_follow_activity')
                            ->where('activity_type','wish_list')
                            ->where('merchant_or_coupon_id', $coupon_id)
                            ->where('activity_user_id', $user_id)
                            ->first();

            $wish_list_data=array(
                'activity_type' => 'wish_list',
                'activity_user_id' => $user_id,
                'merchant_or_coupon_id' => $coupon_id,
                'activity_list_status' => '1',
                'created_by' => $user_id,
                'updated_by' => $user_id,
                'created_at' => $now,
                'updated_at' => $now,
                );


            if(empty($check_wish_list)){

                $wish_list_store=\DB::table('tbl_follow_activity')->insert($wish_list_data);
                return \Redirect::back()->with('message',"Successfully added wish list");
            }else{
                return \Redirect::back();
            }
        }else return \Redirect::to('/sign-in/page')->with('errormessage',"Only registered user can add.");

    }


    /********************************************
    ## ClientWishListDelete
    *********************************************/
    public function ClientWishListDelete($activity_id){

        if(!empty(\Auth::check())){

            $user_id=\Auth::user()->id;
            $wish_list_store=\DB::table('tbl_follow_activity')->where('activity_id',$activity_id)->delete();
            return \Redirect::to('client/profile/page/id-'.$user_id)->with('message',"Successfully deleted wish list");
        }else{
            return \Redirect::back()->with('errormessage',"Something Wrong");
        }

    }




    /********************************************
    ## AllMerchantCouponPage 
    *********************************************/
    public function AllMerchantCouponPage($category_id,$sub_category_id){
        $select_sub_category_info=\DB::table('tbl_sub_category')
                                ->leftjoin('tbl_category','tbl_sub_category.category_id','=','tbl_category.category_id')
                                ->where('tbl_sub_category.sub_category_id',$sub_category_id)
                                ->first();
        $data['select_sub_category_info'] = $select_sub_category_info;     
        $data['category_id'] = $category_id;     
        $data['sub_category_id'] = $sub_category_id;     
        $data['page_desc'] =$sub_category_id;

        $data['page_title'] = $this->page_title;    
        return \View::make('pages.all-merchant-coupon',$data);
    }

    /********************************************
    ## LatestCouponPage 
    *********************************************/
    public function LatestCouponPage(){
        $all_latest_coupon_info=\DB::table('tbl_coupon')
                    ->leftjoin('tbl_merchant','tbl_coupon.coupon_merchant_id','=','tbl_merchant.merchant_id')
                    ->where('tbl_coupon.coupon_closing_date','>=',date('Y-m-d').' 23:59:59')
                    ->where('tbl_coupon.coupons_status','1')
                    ->where('tbl_merchant.merchant_status','1')
                    ->select(['tbl_coupon.coupon_merchant_id', DB::raw('MAX(tbl_coupon.coupon_discount_rate) AS coupon_discount_rate') , \DB::raw('count(*) as total')])
                    ->groupBy('tbl_coupon.coupon_merchant_id')
                    ->paginate(9);
        $all_latest_coupon_info->setPath(url('/latest/coupon/page'));
        $latest_coupon_pagination = $all_latest_coupon_info->render();
        $data['latest_coupon_pagination']=$latest_coupon_pagination;
        $data['all_latest_coupon_info'] = $all_latest_coupon_info;         
        $data['page_title'] = $this->page_title;    
        return \View::make('pages.latest-coupon',$data);
    }

    /********************************************
    ## NewestCouponPage 
    *********************************************/
    public function NewestCouponPage(){
        $previous_date=date('Y-m-d',strtotime("-15 days"));
        $now=date('Y-m-d');
        $new_coupon_info=\DB::table('tbl_coupon')
                    ->leftjoin('tbl_merchant','tbl_coupon.coupon_merchant_id','=','tbl_merchant.merchant_id')
                    ->where('tbl_coupon.coupon_closing_date','>=',date('Y-m-d').' 23:59:59')
                    ->where('tbl_coupon.coupons_status','1')
                    ->where('tbl_merchant.merchant_status','1')
                    ->orderBy('coupon_id','desc')
                    ->paginate(9);
        $new_coupon_info->setPath(url('/latest/coupon/page'));
        $new_coupon_pagination = $new_coupon_info->render();
        $data['new_coupon_pagination']=$new_coupon_pagination;
        $data['new_coupon_info'] = $new_coupon_info;         
        $data['page_title'] = $this->page_title;    
        return \View::make('pages.newest-coupon',$data);
    }




    /********************************************
    ## CouponActiveDeal
    *********************************************/
    public function CouponActiveDeal($coupon_code,$customer_mobile){

        $now=date('Y-m-d H:i:s');
        $current_date=date('Y-m-d').' 23:59:59';
        $select_coupon_info=\DB::table('tbl_coupon')
                            ->leftjoin('tbl_merchant','tbl_coupon.coupon_merchant_id','=','tbl_merchant.merchant_id')
                            ->leftjoin('tbl_branch','tbl_coupon.coupon_branch_id','=','tbl_branch.branch_id')
                            ->where('coupon_code', $coupon_code)
                            ->where('tbl_coupon.coupons_status','1')
                            ->where('tbl_merchant.merchant_status','1')
                            ->first();
        if(!empty($select_coupon_info)){
            $dateline=$select_coupon_info->coupon_closing_date;
            $merchant_name=$select_coupon_info->merchant_name;
            $branch_name=$select_coupon_info->branch_name;
            $branch_address=$select_coupon_info->branch_address;
            $coupon_discount_rate=$select_coupon_info->coupon_discount_rate;

            if($dateline > $current_date){

                if(($dateline >= $current_date) && (($select_coupon_info->coupon_max_limit >$select_coupon_info->coupon_total_selled) ||($select_coupon_info->coupon_max_limit == '-1'))){
                    $coupon_transaction_info=\DB::table('tbl_coupon_transaction')
                                        ->where('coupon_code', $coupon_code)
                                        ->where('customer_mobile', $customer_mobile)
                                        //->where('coupon_status', '-1')
                                        ->where('tbl_coupon_transaction.coupon_status','!=','2')
                                        ->first();

                    if(empty($coupon_transaction_info)){

                        $select_coupon_info=\DB::table('tbl_coupon')->where('coupon_code', $coupon_code)->first();
                        $select_customer_info=\DB::table('users')->where('mobile', $customer_mobile)->first();
                        if(!empty($select_coupon_info) && !empty($select_customer_info)){

                            $user_id=\Auth::user()->id;
                            $customer_info=\DB::table('users')->where('mobile',$customer_mobile)->first();
                            if(!empty($customer_info)){
                                $customer_id=$customer_info->id;
                            }else{
                                $customer_id='';
                            }

                            $coupon_transaction_data=array(
                                'coupon_id' =>$select_coupon_info->coupon_id,
                                'transaction_merchant_id' =>$select_coupon_info->coupon_merchant_id,
                                'transaction_branch_id' =>$select_coupon_info->coupon_branch_id,
                                'customer_id' =>$customer_id,
                                'customer_mobile' =>$customer_mobile,
                                'coupon_code' =>$coupon_code,
                                'coupon_discount_rate' =>$select_coupon_info->coupon_discount_rate,
                                'coupon_commission_rate' =>$select_coupon_info->coupon_commision_rate,
                                'coupon_status' =>-1,
                                'created_by' =>$user_id,
                                'updated_by' => $user_id,
                                'created_at' =>$now,
                                'updated_at' => $now,
                                );


                            $coupon_total_selled=($select_coupon_info->coupon_total_selled)+1;

                            \DB::beginTransaction();
                            try{

                                $coupon_transaction_insert=\DB::table('tbl_coupon_transaction')->insert($coupon_transaction_data);

                                $coupon_sell_update= \DB::table('tbl_coupon')->where('coupon_code',$coupon_code)->update(array("coupon_total_selled" =>$coupon_total_selled));

                                \App\System::EventLogWrite('insert,tbl_coupon_transaction',json_encode($coupon_transaction_data));

                                $active_coupon_sms=\App\OTP::SendSMSForActiveCoupon($customer_mobile, $merchant_name, $branch_name, $branch_address, $coupon_discount_rate);

                                \DB::commit();
                                return \Redirect::back()->with('message',"Coupon Deal Active Successfully And Send A SMS To Your Mobile Number.");

                            }catch(\Exception $e){
                                \DB::rollback();
                                $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                                \App\System::ErrorLogWrite($message);

                                return \Redirect::back()->with('message',"Info Already Exist !");
                            }

                        }else{
                            return \Redirect::back()->with('message',"Coupon code or user  invalid.");  
                        }
                    }else{
                        return \Redirect::back()->with('message',"This is your active deal");  
                    }
                }else{
                    return \Redirect::back()->with('errormessage',"This coupon is stock out.");  
                }
            }else{
                return \Redirect::back()->with('errormessage',"This Coupon Time is Expired");  
            }
        }else{
           return \Redirect::back()->with('errormessage',"Invalid Coupon"); 
        }

    }



    /********************************************
    ## ShoppingAmountAndOTPPage 
    *********************************************/
    public function ShoppingAmountAndOTPPage($coupon_code,$customer_mobile,$coupon_transaction_id){  
        $data['page_title'] = $this->page_title;
        if(!empty($coupon_code) &&!empty($customer_mobile) &&!empty($coupon_transaction_id)){

            $select_transaction_info=\DB::table('tbl_coupon_transaction')
                            ->leftjoin('tbl_coupon','tbl_coupon_transaction.coupon_id','=','tbl_coupon.coupon_id')
                            ->leftjoin('tbl_merchant','tbl_coupon_transaction.transaction_merchant_id','=','tbl_merchant.merchant_id')
                            ->leftjoin('tbl_branch','tbl_coupon_transaction.transaction_branch_id','=','tbl_branch.branch_id')
                            ->leftjoin('users','tbl_coupon_transaction.customer_id','=','users.id')
                            ->where('tbl_coupon_transaction.coupon_transaction_id',$coupon_transaction_id)
                            ->where('tbl_coupon_transaction.customer_mobile',$customer_mobile)
                            ->where('tbl_coupon_transaction.coupon_code',$coupon_code)
                            ->where('tbl_coupon_transaction.coupon_status','!=','2')
                            ->first();

            $client_meta_info=\DB::table('tbl_user_meta')
                            ->where('user_id',$select_transaction_info->customer_id)
                            ->where('user_meta_field_name','point')
                            ->first();

            $client_shopping_info=\DB::table('tbl_coupon_transaction')
                            ->leftjoin('tbl_coupon','tbl_coupon_transaction.coupon_id','=','tbl_coupon.coupon_id')
                            ->leftjoin('tbl_merchant','tbl_coupon_transaction.transaction_merchant_id','=','tbl_merchant.merchant_id')
                            ->leftjoin('tbl_branch','tbl_coupon_transaction.transaction_branch_id','=','tbl_branch.branch_id')
                            ->where('tbl_coupon_transaction.customer_mobile',$customer_mobile)
                            ->OrderBy('tbl_coupon_transaction.updated_at','desc')
                            ->paginate(6);
            $client_shopping_info->setPath(url('/coupon/amount/otp/ccode-'.$select_transaction_info->coupon_code.'/cmobile-'.$select_transaction_info->customer_mobile.'/tid-'.$select_transaction_info->coupon_transaction_id));
            $client_shopping_pagination = $client_shopping_info->render();
            $data['client_shopping_pagination']=$client_shopping_pagination;  
            $data['client_shopping_info'] = $client_shopping_info;
            $data['client_meta_info'] = $client_meta_info;
            $data['select_transaction_info'] = $select_transaction_info;
            return \View::make('pages.shopping-amount',$data);
        }else{
            return \Redirect::back()->with('errormessage',"Something Wrong !!!");
        }
    }


    /********************************************
    ## EditShoppingAmountPage 
    *********************************************/
    public function EditShoppingAmountPage($coupon_transaction_id){  
        $data['page_title'] = $this->page_title;
        if(!empty($coupon_transaction_id)){

            $select_transaction_info=\DB::table('tbl_coupon_transaction')
                            ->leftjoin('tbl_coupon','tbl_coupon_transaction.coupon_id','=','tbl_coupon.coupon_id')
                            ->leftjoin('tbl_merchant','tbl_coupon_transaction.transaction_merchant_id','=','tbl_merchant.merchant_id')
                            ->leftjoin('tbl_branch','tbl_coupon_transaction.transaction_branch_id','=','tbl_branch.branch_id')
                            ->leftjoin('users','tbl_coupon_transaction.customer_id','=','users.id')
                            ->where('tbl_coupon_transaction.coupon_transaction_id',$coupon_transaction_id)
                            ->where('tbl_coupon_transaction.coupon_status','!=','2')
                            ->first();

            $client_meta_info=\DB::table('tbl_user_meta')
                            ->where('user_id',$select_transaction_info->customer_id)
                            ->where('user_meta_field_name','point')
                            ->first();

            $client_shopping_info=\DB::table('tbl_coupon_transaction')
                            ->leftjoin('tbl_coupon','tbl_coupon_transaction.coupon_id','=','tbl_coupon.coupon_id')
                            ->leftjoin('tbl_merchant','tbl_coupon_transaction.transaction_merchant_id','=','tbl_merchant.merchant_id')
                            ->leftjoin('tbl_branch','tbl_coupon_transaction.transaction_branch_id','=','tbl_branch.branch_id')
                            ->where('tbl_coupon_transaction.coupon_transaction_id',$coupon_transaction_id)
                            ->OrderBy('tbl_coupon_transaction.updated_at','desc')
                            ->paginate(6);
            $client_shopping_info->setPath(url('/coupon/amount/otp/ccode-'.$select_transaction_info->coupon_code.'/cmobile-'.$select_transaction_info->customer_mobile.'/tid-'.$select_transaction_info->coupon_transaction_id));
            $client_shopping_pagination = $client_shopping_info->render();
            $data['client_shopping_pagination']=$client_shopping_pagination;  
            $data['client_shopping_info'] = $client_shopping_info;
            $data['client_meta_info'] = $client_meta_info;
            $data['select_transaction_info'] = $select_transaction_info;
            return \View::make('pages.edit-shopping-amount',$data);
        }else{
            return \Redirect::back()->with('errormessage',"Something Wrong !!!");
        }
    }


    /********************************************
    ## CouponShoppingAmountSubmit
    *********************************************/
    public function CouponShoppingAmountSubmit(){


        $now=date('Y-m-d H:i:s');
        $rules=array(
            'shopping_amount' => 'Required|numeric',
            'coupon_code' => 'Required',
            'customer_mobile' => 'Required',
            );

        $v=\Validator::make(\Request::all(), $rules);

        if($v->passes()){

            $transaction_amount =(\Request::input('shopping_amount'));
            $coupon_code =\Request::input('coupon_code');
            $customer_mobile =\Request::input('customer_mobile');
            $coupon_transaction_id =\Request::input('coupon_transaction_id');

            $active_deal_coupon_info=\DB::table('tbl_coupon_transaction')
                                ->leftjoin('tbl_coupon','tbl_coupon.coupon_id','=','tbl_coupon_transaction.coupon_id')
                                ->where('tbl_coupon_transaction.coupon_transaction_id', $coupon_transaction_id)
                                ->where('tbl_coupon_transaction.coupon_code', $coupon_code)
                                ->where('tbl_coupon_transaction.customer_mobile',$customer_mobile)
                                ->where('tbl_coupon_transaction.coupon_status','-1')
                                ->first();
            if(!empty($active_deal_coupon_info)){
                $coupon_id=$active_deal_coupon_info->coupon_id;

                if (is_numeric($transaction_amount)){

                    $select_coupon_info=\DB::table('tbl_coupon')
                                        ->where('tbl_coupon.coupon_id', $coupon_id)
                                        ->leftjoin('tbl_merchant','tbl_coupon.coupon_merchant_id','=','tbl_merchant.merchant_id')
                                        ->leftjoin('tbl_branch','tbl_coupon.coupon_branch_id','=','tbl_branch.branch_id')
                                        ->first();
                    if(!empty($select_coupon_info)){

                        $branch_mobile=$select_coupon_info->branch_mobile;
                        $customer_info=\DB::table('users')->where('mobile',$customer_mobile)->first();
                        if(!empty($customer_info)){
                            $customer_id=$customer_info->id;
                        }else{
                            $customer_id='';
                        }
                        $user_id=$customer_id;


                        if(($select_coupon_info->coupon_applied_min_amount)<=$transaction_amount){

                            $coupon_max_limit=$select_coupon_info->coupon_max_limit;
                            $coupon_total_selled_info=$select_coupon_info->coupon_total_selled;

                            if(($select_coupon_info->coupon_max_limit == -1) || ($coupon_total_selled_info)){


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

                                $coupon_update_data=array(
                                    'coupon_total_sell_price' => $coupon_total_sell_price,
                                    'updated_by' => $user_id,
                                    'updated_at' => $now,
                                    );


                                \DB::beginTransaction();
                                try{
                                        $coupon_transaction_update=\DB::table('tbl_coupon_transaction')
                                                ->where('coupon_code',$coupon_code)
                                                ->where('customer_mobile',$customer_mobile)
                                                ->where('coupon_transaction_id',$active_deal_coupon_info->coupon_transaction_id)
                                                ->update($coupon_transaction_update_data);
                                        \App\System::EventLogWrite('update,tbl_coupon_transaction',json_encode($coupon_transaction_update_data));


                                    
                                    $coupon_update=\DB::table('tbl_coupon')->where('coupon_code',$coupon_code)->update($coupon_update_data);
                                    \App\System::EventLogWrite('update,tbl_coupon',json_encode($coupon_update_data));
                                    $otp_send=\App\OTP::SendSMSForBuyCoupon($branch_mobile,$coupon_secret_code,$customer_mobile,$transaction_amount, $coupon_discount_amount);
                                    \DB::commit();
                                    return \Redirect::back()->with('message',"Code send successfully, please collect from shop.");


                                }catch(\Exception $e){
                                    \DB::rollback();
                                    $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                                    \App\System::ErrorLogWrite($message);

                                    return \Redirect::back()->with('message',"Info Already Exist !");

                                }

                            }else{
                                return \Redirect::back()->with('message',"Coupon is stock out.");


                            }
                        
                        }else{
                            return \Redirect::back()->with('message',"Shopping amount is less than minimum amount.");

                        }

                    }else{
                        return \Redirect::back()->with('message',"Coupon code invalid.");
                    }
                }else{
                    return \Redirect::back()->with('message',"Transaction Amount is to be numeric !!!");
                }
            }else{
                return \Redirect::back()->with('message',"There are no active deal coupon !!!");

            }

        }else{
            return \Redirect::back()->withInput(\Request::all())->withErrors($v->messages());
        }

    }


    /********************************************
    ## NewCouponShoppingAmountSubmit
    *********************************************/
    public function NewCouponShoppingAmountSubmit(){


        $now=date('Y-m-d H:i:s');
        $rules=array(
            'shopping_amount' => 'Required|numeric',
            'coupon_transaction_id' => 'Required',
            );

        $v=\Validator::make(\Request::all(), $rules);

        if($v->passes()){

            $transaction_amount =(\Request::input('shopping_amount'));
            $coupon_transaction_id =\Request::input('coupon_transaction_id');

            $active_deal_coupon_info=\DB::table('tbl_coupon_transaction')
                                ->where('coupon_transaction_id', $coupon_transaction_id)
                                ->first();
            if(!empty($active_deal_coupon_info)){
                $coupon_id=$active_deal_coupon_info->coupon_id;
                $customer_mobile=$active_deal_coupon_info->customer_mobile;
                if (is_numeric($transaction_amount)){

                    $select_coupon_info=\DB::table('tbl_coupon')
                                        ->where('tbl_coupon.coupon_id', $coupon_id)
                                        ->leftjoin('tbl_merchant','tbl_coupon.coupon_merchant_id','=','tbl_merchant.merchant_id')
                                        ->leftjoin('tbl_branch','tbl_coupon.coupon_branch_id','=','tbl_branch.branch_id')
                                        ->first();
                    if(!empty($select_coupon_info)){

                        $branch_mobile=$select_coupon_info->branch_mobile;
                        $coupon_code=$select_coupon_info->coupon_code;
                        $customer_info=\DB::table('users')->where('mobile',$customer_mobile)->first();
                        if(!empty($customer_info)){
                            $customer_id=$customer_info->id;
                        }else{
                            $customer_id='';
                        }
                        $user_id=$customer_id;


                        if(($select_coupon_info->coupon_applied_min_amount)<=$transaction_amount){

                            $coupon_max_limit=$select_coupon_info->coupon_max_limit;
                            $coupon_total_selled_info=$select_coupon_info->coupon_total_selled;

                            if(($select_coupon_info->coupon_max_limit == -1) || ($coupon_total_selled_info)){


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

                                \DB::beginTransaction();
                                try{
                                        $coupon_transaction_update=\DB::table('tbl_coupon_transaction')
                                                ->where('coupon_transaction_id',$coupon_transaction_id)
                                                ->update($coupon_transaction_update_data);
                                        \App\System::EventLogWrite('update,tbl_coupon_transaction',json_encode($coupon_transaction_update_data));

                                    $otp_send=\App\OTP::SendSMSForBuyCoupon($branch_mobile,$coupon_secret_code, $customer_mobile, $transaction_amount, $coupon_discount_amount);
                                    \DB::commit();
                                    return \Redirect::to('/coupon/amount/otp/ccode-'.$coupon_code.'/cmobile-'.$customer_mobile.'/tid-'.$coupon_transaction_id)->with('message',"Code Send Successfully. Please collect code from shop");


                                }catch(\Exception $e){
                                    \DB::rollback();
                                    $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                                    \App\System::ErrorLogWrite($message);

                                    return \Redirect::back()->with('message',"Info Already Exist !");

                                }

                            }else{
                                return \Redirect::back()->with('message',"Coupon is stock out.");


                            }
                        
                        }else{
                            return \Redirect::back()->with('message',"Shopping amount is less than minimum amount.");
                        }

                    }else{
                        return \Redirect::back()->with('message',"Coupon code invalid.");
                    }
                }else{
                    return \Redirect::back()->with('message',"Transaction Amount is to be numeric !!!");
                }
            }else{
                return \Redirect::back()->with('message',"There are no active deal coupon !!!");

            }

        }else{
            return \Redirect::back()->withInput(\Request::all())->withErrors($v->messages());
        }

    }


    /********************************************
    ## ResendSMSForBuyCoupon
    *********************************************/
    public function ResendSMSForBuyCoupon($branch_mobile,$coupon_secret_code,$customer_mobile,$transaction_amount,$coupon_discount_amount){
        try{
            \App\OTP::SendSMSForBuyCoupon($branch_mobile,$coupon_secret_code,$customer_mobile,$transaction_amount, $coupon_discount_amount);
            return \Redirect::back()->with('message',"Code Send Successfully");

        }catch(\Exception $e){
            \DB::rollback();
            $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
            \App\System::ErrorLogWrite($message);

            return \Redirect::back()->with('errormessage',"Info Already Exist !");
        }

    }


    /********************************************
    ## CouponOTPSubmit
    *********************************************/
    public function CouponOTPSubmit(){


        $now=date('Y-m-d H:i:s');
        $rules=array(
            'coupon_otp' => 'Required|numeric',
            'coupon_code' => 'Required',
            'customer_mobile' => 'Required',
            );

        $v=\Validator::make(\Request::all(), $rules);

        if($v->passes()){

            $coupon_otp =(\Request::input('coupon_otp'));
            $coupon_code =\Request::input('coupon_code');
            $customer_mobile =\Request::input('customer_mobile');
            $coupon_transaction_id =\Request::input('coupon_transaction_id');


                $user_earning_point=0;
                $select_coupon_info=\DB::table('tbl_coupon')->where('coupon_code',$coupon_code)->first();
                $select_coupon_transaction_info=\DB::table('tbl_coupon_transaction')
                                    ->where('coupon_code', $coupon_code)
                                    ->where('coupon_transaction_id',$coupon_transaction_id)
                                    ->where('coupon_secret_code',$coupon_otp)
                                    ->where('coupon_status','1')
                                    ->first();

                if(!empty($select_coupon_transaction_info)){

                    \DB::beginTransaction();

                    try{

                        $user_id=$select_coupon_transaction_info->customer_id;
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
                            'created_by' => $user_id,
                            'updated_by' => $user_id,
                            'created_at' => $now,
                            'updated_at' => $now,
                            );

                        $coupon_update_data=array(
                            'coupon_total_shopping_amount' => $coupon_total_shopping_amount,
                            'coupon_total_discount' => $coupon_total_discount,
                            'coupon_total_commission' => $coupon_total_commission,
                            'updated_by' => $user_id,
                            'updated_at' => $now,
                            );




                        $coupon_transaction_update=\DB::table('tbl_coupon_transaction')->where('coupon_transaction_id',$coupon_transaction_id)->update($coupon_transaction_update_data);
                        $coupon_update=\DB::table('tbl_coupon')->where('coupon_code',$coupon_code)->update($coupon_update_data);
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
                        return \Redirect::to('/client/profile/page/id-'.$user_id)->with('message'," Thank You For Shopping !");



                    }catch(\Exception $e){
                        \DB::rollback();
                        $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                        \App\System::ErrorLogWrite($message);

                        return \Redirect::back()->with('errormessage',"Info Already Exist !");

                    }

                }else{
                    return \Redirect::back()->with('errormessage',"Invalid OTP ! Please try again.");

                }


        }else{
            return \Redirect::back()->withInput(\Request::all())->withErrors($v->messages());
        }
    }



    /********************************************
    ## PrivacyPage 
    *********************************************/
    public function PrivacyPage(){

        $data['page_title'] = $this->page_title;   
        return \View::make('pages.privacy',$data);
    }



    /********************************************
    ## FfEdit
    *********************************************/
    public function FfEdit($merchant_id){

        $edit_merchant_info=\DB::table('tbl_merchant')
                ->where('merchant_id',$merchant_id)
                ->first();

        $merchant_info=\DB::table('tbl_merchant')
                ->OrderBy('updated_at','desc')
                ->paginate(5);
        $merchant_info->setPath(url('/dashboard/merchant'));
        $merchant_pagination = $merchant_info->render();
        $data['merchant_pagination']=$merchant_pagination;
        $data['merchant_info'] = $merchant_info;    
        $data['edit_merchant_info'] = $edit_merchant_info;    
        $data['page_title'] = $this->page_title;    
        return \View::make('dashboard.pages.edit-merchant',$data);
    }







################################### End Controller #######################################

}
