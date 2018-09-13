<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\User;
use App\Admin;


class AdminController extends Controller
{
    public function __construct(){
       
        $this->page_title = \Request::route()->getName();
        \App\System::AccessLogWrite();
    }

    /********************************************
    ## AdminDashboardPage 
    *********************************************/
    public function AdminDashboardPage(){

        $data['page_title'] = $this->page_title;      
        return \View::make('dashboard.pages.dashboard-admin',$data);
    }

    /********************************************
    ## HomeSliderPage 
    *********************************************/
    public function HomeSliderPage(){

        $merchant_info=\DB::table('tbl_merchant')
                        ->where('merchant_status','1')
                        ->orderBy('tbl_merchant.updated_at','desc')
                        ->get();

        $home_slider_info=\DB::table('tbl_setting_meta')
                ->where('setting_meta_field_name','home_slider')
                ->OrderBy('updated_at','desc')
                ->paginate(5);
        $home_slider_info->setPath(url('/dashboard/home-slider'));
        $home_slider_pagination = $home_slider_info->render();
        $data['home_slider_pagination']=$home_slider_pagination;
        $data['home_slider_info'] = $home_slider_info;
        $data['merchant_info'] = $merchant_info;  
        $data['page_title'] = $this->page_title;    
        return \View::make('dashboard.pages.home-slider',$data);
    }


    /********************************************
    ## HomeSliderAdd
    *********************************************/
    public function HomeSliderAdd(){

        $user_id=\Auth::user()->id;
        $now=date('Y-m-d H:i:s');

        $rules=array(
	    'coupon_merchant_id' => 'required',
            'home_slider_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'slider_popup_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            );

        $v=\Validator::make(\Request::all(), $rules);



        if($v->passes()){

            $setting_meta_field_name =\Request::input('setting_meta_field_name');

            if(!empty(\Request::file('home_slider_image'))){

                $image = \Request::file('home_slider_image');
                $img_location=$image->getRealPath();
                $img_ext=$image->getClientOriginalExtension();
                $home_slider_image=\App\Admin::HomeSliderImageUpload($img_location, $setting_meta_field_name, $img_ext);
            }
            else{
                $home_slider_image='';
            }


            if(!empty(\Request::file('slider_popup_image'))){

                $image = \Request::file('slider_popup_image');
                $img_location=$image->getRealPath();
                $img_ext=$image->getClientOriginalExtension();
                $slider_popup_image=\App\Admin::SliderPopupImageUpload($img_location, $setting_meta_field_name, $img_ext);
            }
            else{
                $slider_popup_image='';
            }

	        $coupon_merchant_id=\Request::input('coupon_merchant_id');
            $main_slider_image_array=array($home_slider_image,$slider_popup_image,$coupon_merchant_id);
            $main_slider_image=serialize($main_slider_image_array);


            $home_slider_data=array(
                'setting_meta_field_name' =>  \Request::input('setting_meta_field_name'),
                'setting_meta_field_value' => $main_slider_image,
                'setting_meta_status' => '1',
                'created_by' => $user_id,
                'updated_by' => $user_id,
                'created_at' => $now,
                'updated_at' => $now,
                );

            try{

                $save_sub_category_info=\DB::table('tbl_setting_meta')->insert($home_slider_data);
                \App\System::EventLogWrite('insert,tbl_setting_meta',json_encode($home_slider_data));
                return \Redirect::to('/dashboard/home-slider')->with('message',"Home Slider Added Successfully !");

            }catch(\Exception $e){

                $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                \App\System::ErrorLogWrite($message);

                return \Redirect::to('/dashboard/home-slider')->with('message',"Info Already Exist !");
            }

        }else return \Redirect::to('/dashboard/home-slider')->withErrors($v->messages());
    }


    /********************************************
    ## AjaxHomeSliderChangeStatus
    *********************************************/
    public function AjaxHomeSliderChangeStatus($setting_id,$action){
        $user_id=\Auth::user()->id;
        $now=date('Y-m-d H:i:s');

        $home_slider_data=array(
            'setting_meta_status' => $action,
            'updated_by' => $user_id,
            'updated_at' => $now,
            );

        try{

            $merchant_status_update=\DB::table('tbl_setting_meta')->where('setting_id',$setting_id)->update($home_slider_data);
            \App\System::EventLogWrite('update,tbl_setting_meta',json_encode($home_slider_data));
            return \Redirect::to('/dashboard/home-slider')->with('message',"Home Slider Status Changed Successfully !");

        }catch(\Exception $e){

            $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
            \App\System::ErrorLogWrite($message);

            return \Redirect::to('/dashboard/home-slider')->with('message',"Info Already Exist !");
        }

    }

    /********************************************
    ## HomeSliderDelete
    *********************************************/
    public function HomeSliderDelete($setting_id){
        $home_slider_info=\DB::table('tbl_setting_meta')->where('setting_id',$setting_id)->first();
        if(!empty($home_slider_info)){

            try{

                $save_home_slider_info=\DB::table('tbl_setting_meta')->where('setting_id',$setting_id)->delete();
                \App\System::EventLogWrite('delete,tbl_setting_meta',json_encode($setting_id));
                return \Redirect::to('/dashboard/home-slider')->with('message',"Home Slider Deleted Successfully !");

            }catch(\Exception $e){

                $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                \App\System::ErrorLogWrite($message);

                return \Redirect::to('/dashboard/home-slider')->with('message',"Something Wrong !!!");
            }

        }else{
            return \Redirect::to('/dashboard/home-slider')->with('message',"Invalid Id");
        }

    }



   /********************************************
    ## CompanyPage
    *********************************************/
    public function CompanyPage(){

        if(isset($_REQUEST['tab']) && !empty($_REQUEST['tab'])){
            $tab = $_REQUEST['tab'];
        }else $tab = 'company_overview';
        $data['tab']=$tab;
        $company_info=\DB::table('company_details')->latest()->first();
        $data['company_info']=$company_info;
        $data['page_title'] = $this->page_title;       
        return \View::make('dashboard.pages.company.company-details',$data);

    }


    /********************************************
    ## CompanyDetailInsert 
    *********************************************/
    public function CompanyDetailInsert(){
        $now=date('Y-m-d H:i:s');
        $user =\Auth::user()->id;
        $rule = [
                'company_name' => 'Required',
                'company_address' => 'Required|max:190',
                'company_email' => 'Required',
                'company_contact' => 'Required',
                ];

        $v = \Validator::make(\Request::all(),$rule);

         if($v->passes()){

            $company_id=\Request::input('company_id');

            if($company_id!= null){

                $company_update_name_slug = explode(' ', strtolower(\Request::input('company_name')));
                $company_update_name_slug = implode('_', $company_update_name_slug);

                if(!empty(\Request::file('company_logo'))){

                    $image = \Request::file('company_logo');
                    $img_location=$image->getRealPath();
                    $img_ext=$image->getClientOriginalExtension();
                    $company_image_updates=\App\Admin::CompanyImageUpload($img_location, $company_update_name_slug, $img_ext);
                    $company_update_img = array(
                       'company_logo' => $company_image_updates,
                       );
                    \DB::table('company_details')->where('company_id',$company_id)->update($company_update_img);
                }

                    $company_update_data = [
                    'company_name' =>\Request::input('company_name'),
                    'company_name_slug' => $company_update_name_slug,
                    'company_contact' =>\Request::input('company_contact'),
                    'company_email' =>\Request::input('company_email'),
                    'company_address' =>\Request::input('company_address'),
                    'company_location_lat' =>\Request::input('company_location_lat'),
                    'company_location_lng' =>\Request::input('company_location_lng'),
                    'updated_at' =>$now,
                    'updated_by' =>$user,
                    ];

                    try{
                        \DB::table('company_details')->where('company_id',$company_id)->update($company_update_data);
                        \App\System::EventLogWrite('update,company_details',json_encode($company_update_data));
                        return \Redirect::to('/dashboard/company/info')->with('message',"Company Details Updated Successfully!");
                    }catch(\Exception $e){

                        $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                        \App\System::ErrorLogWrite($message);

                        return \Redirect::to('/dashboard/company/info')->with('message',"Info Already Exist !");
                    }

            }


            $company_name_slug = explode(' ', strtolower(\Request::input('company_name')));
            $company_name_slug = implode('_', $company_name_slug);
            if(!empty(\Request::file('company_logo'))){

                $image = \Request::file('company_logo');
                $img_location=$image->getRealPath();
                $img_ext=$image->getClientOriginalExtension();
                $company_logo=\App\Admin::CompanyImageUpload($img_location, $company_name_slug, $img_ext);
            }
            else{
                $company_logo='';
            }

            $company_data = [

            'company_name' =>\Request::input('company_name'),
            'company_name_slug' => $company_name_slug,
            'company_contact' =>\Request::input('company_contact'),
            'company_email' =>\Request::input('company_email'),
            'company_address' =>\Request::input('company_address'),
            'company_location_lat' =>\Request::input('company_location_lat'),
            'company_location_lng' =>\Request::input('company_location_lng'),
            'company_logo' => $company_logo,
            'created_at' =>$now,
            'updated_at' =>$now,
            'created_by' =>$user,
            'updated_by' =>$user,
            ];
           
            

            try{
                \DB::table('company_details')->insert($company_data);

                \App\System::EventLogWrite('insert,company_details',json_encode($company_data));
                return \Redirect::to('/dashboard/company/info')->with('message',"Company Details Added Successfully!");

            }catch(\Exception $e){

                $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                \App\System::ErrorLogWrite($message);

                return \Redirect::to('/dashboard/company/info')->with('message',"Info Already Exist !");
            }

         }else return \Redirect::to('/dashboard/company/info')->withErrors($v->messages());
    }



    /********************************************
    ## SocialSitePage 
    *********************************************/
    public function SocialSitePage(){

        $social_site_info=\DB::table('tbl_setting_meta')
                ->where('setting_meta_field_name','social_site')
                ->OrderBy('created_at','desc')
                ->paginate(5);
        $social_site_info->setPath(url('/dashboard/social/site/info'));
        $social_site_pagination = $social_site_info->render();
        $data['social_site_pagination']=$social_site_pagination;
        $data['social_site_info'] = $social_site_info;    
        $data['page_title'] = $this->page_title;    
        return \View::make('dashboard.pages.social-site-add',$data);
    }


    /********************************************
    ## SocialSiteAdd
    *********************************************/
    public function SocialSiteAdd(){

        $user_id=\Auth::user()->id;
        $now=date('Y-m-d H:i:s');

        $rules=array(
            'social_site_name' => 'required',
            'social_site_url' => 'required',
            );

        $v=\Validator::make(\Request::all(), $rules);



        if($v->passes()){
            $social_site_name =\Request::input('social_site_name');
            $social_site_url =\Request::input('social_site_url');
            $social_site_array=array($social_site_name,$social_site_url);
            $social_site_value=serialize($social_site_array);

            $social_site_data=array(
                'setting_meta_field_name' =>'social_site',
                'setting_meta_field_value' =>$social_site_value,
                'setting_meta_status' => '1',
                'created_by' => $user_id,
                'updated_by' => $user_id,
                'created_at' => $now,
                'updated_at' => $now,
                );

            try{

                $social_site_data_info=\DB::table('tbl_setting_meta')->insert($social_site_data);
                \App\System::EventLogWrite('insert,tbl_setting_meta',json_encode($social_site_data));
                return \Redirect::to('/dashboard/social/site/info')->with('message',"Social Site Added Successfully !");

            }catch(\Exception $e){

                $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                \App\System::ErrorLogWrite($message);

                return \Redirect::to('/dashboard/social/site/info')->with('errormessage',"Info Already Exist !");
            }

        }else return \Redirect::to('/dashboard/social/site/info')->withErrors($v->messages());
    }

    /********************************************
    ## SocialSiteEdit
    *********************************************/
    public function SocialSiteEdit($setting_id){

        $selest_social_site_info=\DB::table('tbl_setting_meta')->where('setting_meta_field_name','social_site')->where('setting_id',$setting_id)->first();
        $social_site_info=\DB::table('tbl_setting_meta')
                ->where('setting_meta_field_name','social_site')
                ->OrderBy('created_at','desc')
                ->paginate(5);
        $social_site_info->setPath(url('/dashboard/social/site/info'));
        $social_site_pagination = $social_site_info->render();
        $data['social_site_pagination']=$social_site_pagination;
        $data['social_site_info'] = $social_site_info;    
        $data['selest_social_site_info'] = $selest_social_site_info;    
        $data['page_title'] = $this->page_title;    
        return \View::make('dashboard.pages.social-site-edit',$data);
    }


    /********************************************
    ## SocialSiteUpdate
    *********************************************/
    public function SocialSiteUpdate($setting_id){

        $user_id=\Auth::user()->id;
        $now=date('Y-m-d H:i:s');

        $rules=array(
            'social_site_name' => 'required',
            'social_site_url' => 'required',
            );

        $v=\Validator::make(\Request::all(), $rules);



        if($v->passes()){
            $social_site_name =\Request::input('social_site_name');
            $social_site_url =\Request::input('social_site_url');
            $social_site_array=array($social_site_name,$social_site_url);
            $social_site_value=serialize($social_site_array);

            $social_site_data=array(
                'setting_meta_field_value' =>$social_site_value,
                'setting_meta_status' => '1',
                'updated_by' => $user_id,
                'updated_at' => $now,
                );

            try{

                $social_site_data_info=\DB::table('tbl_setting_meta')->where('setting_id',$setting_id)->update($social_site_data);
                \App\System::EventLogWrite('update,tbl_setting_meta',json_encode($social_site_data));
                return \Redirect::to('/dashboard/social/site/info')->with('message',"Social Site Updated Successfully !");

            }catch(\Exception $e){

                $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                \App\System::ErrorLogWrite($message);

                return \Redirect::to('/dashboard/social/site/info')->with('errormessage',"Info Already Exist !");
            }

        }else return \Redirect::to('/dashboard/social/site/info')->withErrors($v->messages());
    }


    /********************************************
    ## SocialSiteDelete
    *********************************************/
    public function SocialSiteDelete($setting_id){

        $social_site_info=\DB::table('tbl_setting_meta')
                        ->where('setting_id',$setting_id)
                        ->where('setting_meta_field_name','social_site')
                        ->first();

        if(!empty($social_site_info)){

            try{

                $social_site_data_info=\DB::table('tbl_setting_meta')->where('setting_id',$setting_id)->delete();
                \App\System::EventLogWrite('insert,tbl_setting_meta',json_encode($social_site_info));
                return \Redirect::to('/dashboard/social/site/info')->with('message',"Social Site Deleted Successfully !");

            }catch(\Exception $e){

                $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                \App\System::ErrorLogWrite($message);

                return \Redirect::to('/dashboard/social/site/info')->with('message',"Something Wrong !!!");
            }
        }else{
            return \Redirect::to('/dashboard/social/site/info')->with('message',"Invalid Id !!!");
        }

    }




    /********************************************
    ## CategoryPage 
    *********************************************/
    public function CategoryPage(){

        $category_info=\DB::table('tbl_category')
               	->OrderBy('updated_at','desc')
                ->paginate(10);
        $category_info->setPath(url('/dashboard/category'));
        $category_pagination = $category_info->render();
        $data['category_pagination']=$category_pagination;
        $data['category_info'] = $category_info;    
        $data['page_title'] = $this->page_title;    
        return \View::make('dashboard.pages.category',$data);


    }


    /********************************************
    ## CategoryAdd
    *********************************************/
    public function CategoryAdd(){

        $user_id=\Auth::user()->id;
        $now=date('Y-m-d H:i:s');

        $rules=array(
            'category_name' => 'Required',
            );

        $v=\Validator::make(\Request::all(), $rules);

        if($v->passes()){

            $category_name_slug = explode(' ', strtolower(\Request::input('category_name')));
            $category_name_slug = implode('_', $category_name_slug);


            $category_data=array(
                'category_name' =>  \Request::input('category_name'),
                'category_name_slug' => $category_name_slug,
                'created_by' => $user_id,
                'updated_by' => $user_id,
                'created_at' => $now,
                'updated_at' => $now,
                );

            try{

                $save_category_info=\DB::table('tbl_category')->insert($category_data);
                \App\System::EventLogWrite('insert,tbl_category',json_encode($category_data));
                return \Redirect::to('/dashboard/category')->with('message',"Category Added Successfully !");

            }catch(\Exception $e){

                $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                \App\System::ErrorLogWrite($message);

                return \Redirect::to('/dashboard/category')->with('message',"Info Already Exist !");
            }

        }else return \Redirect::to('/dashboard/category')->withInput(\Request::all())->withErrors($v->messages());
    }
    

    /********************************************
    ## CategoryEdit
    *********************************************/
    public function CategoryEdit($category_id){

    	$edit_category_info=\DB::table('tbl_category')
               	->where('category_id',$category_id)
                ->first();
        if(!empty($edit_category_info)){

            $category_info=\DB::table('tbl_category')
                   	->OrderBy('updated_at','desc')
                    ->paginate(10);
            $category_info->setPath(url('/dashboard/category'));
            $category_pagination = $category_info->render();
            $data['category_pagination']=$category_pagination;
            $data['category_info'] = $category_info;    
            $data['edit_category_info'] = $edit_category_info;    
            $data['page_title'] = $this->page_title;    
            return \View::make('dashboard.pages.edit-category',$data);
        }else{
            return \Redirect::to('/dashboard/category')->with('errormessage',"Invalid Category Id");
        }
    }


    /********************************************
    ## CategoryUpdate
    *********************************************/
    public function CategoryUpdate($category_id){

        $user_id=\Auth::user()->id;
        $now=date('Y-m-d H:i:s');

        $rules=array(
            'category_name' => 'Required',
            );

        $v=\Validator::make(\Request::all(), $rules);

        if($v->passes()){

            $category_name_slug = explode(' ', strtolower(\Request::input('category_name')));
            $category_name_slug = implode('_', $category_name_slug);


            $category_data=array(
                'category_name' =>  \Request::input('category_name'),
                'category_name_slug' => $category_name_slug,
                'updated_by' => $user_id,
                'updated_at' => $now,
                );

            try{

                $save_category_info=\DB::table('tbl_category')->where('category_id',$category_id)->update($category_data);
                \App\System::EventLogWrite('update,tbl_category',json_encode($category_data));
                return \Redirect::to('/dashboard/category')->with('message',"Category Updated Successfully !");

            }catch(\Exception $e){

                $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                \App\System::ErrorLogWrite($message);

                return \Redirect::to('/dashboard/category')->with('message',"Info Already Exist !");
            }

        }else return \Redirect::to('/dashboard/category')->withErrors($v->messages());
    }

    /********************************************
    ## CategoryDelete
    *********************************************/
    public function CategoryDelete($category_id){

        $category_info=\DB::table('tbl_sub_category')->where('category_id',$category_id)->first();
        if(empty($category_info)){

            try{

                $save_category_info=\DB::table('tbl_category')->where('category_id',$category_id)->delete();
                \App\System::EventLogWrite('insert,tbl_category',json_encode($category_info));
                return \Redirect::to('/dashboard/category')->with('message',"Category Deleted Successfully !");

            }catch(\Exception $e){

                $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                \App\System::ErrorLogWrite($message);

                return \Redirect::to('/dashboard/category')->with('message',"Something Wrong !!!");
            }
        }else{
            return \Redirect::to('/dashboard/category')->with('message',"Invalid Id !!!");
        }

    }

    #############################################################

    /********************************************
    ## SubCategoryPage 
    *********************************************/
    public function SubCategoryPage(){

        $category_info=\DB::table('tbl_category')->get();
        $sub_category_info=\DB::table('tbl_sub_category')
                ->leftjoin('tbl_category','tbl_sub_category.category_id','=','tbl_category.category_id')
                ->OrderBy('tbl_sub_category.updated_at','desc')
                ->paginate(5);
        $sub_category_info->setPath(url('/dashboard/sub-category'));
        $sub_category_pagination = $sub_category_info->render();
        $data['sub_category_pagination']=$sub_category_pagination;
        $data['sub_category_info'] = $sub_category_info;    
        $data['category_info'] = $category_info;    
        $data['page_title'] = $this->page_title;    
        return \View::make('dashboard.pages.sub-category',$data);
    }


    /********************************************
    ## AjaxSubCategoryStatusChange
    *********************************************/
    public function AjaxSubCategoryStatusChange($sub_category_id,$action){
        $user_id=\Auth::user()->id;
        $now=date('Y-m-d H:i:s');

        $sub_category_update_data=array(
            'sub_category_status' => $action,
            'updated_by' => $user_id,
            'updated_at' => $now,
            );

        try{

            $sub_category_status_update=\DB::table('tbl_sub_category')->where('sub_category_id',$sub_category_id)->update($sub_category_update_data);
            \App\System::EventLogWrite('update,tbl_sub_category',json_encode($sub_category_update_data));
            return \Redirect::to('/dashboard/all-merchant/list')->with('message',"Sub Category Status Changed Successfully !");

        }catch(\Exception $e){

            $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
            \App\System::ErrorLogWrite($message);

            return \Redirect::to('/dashboard/sub-category')->with('message',"Info Already Exist !");
        }

    }

    /********************************************
    ## AjaxSubCategoryList
    *********************************************/
    public function AjaxSubCategoryList($category_id){

        $category_list=\DB::table('tbl_sub_category')->where('category_id',$category_id)->get();
        $data['category_list'] = $category_list;    
        $data['page_title'] = $this->page_title;    
        return \View::make('dashboard.pages.ajax-sub-category-list',$data);
    }

    /********************************************
    ## AjaxBranchList
    *********************************************/
    public function AjaxBranchList($merchant_id){

        $branch_list=\DB::table('tbl_branch')->where('merchant_id',$merchant_id)->get();
        $data['branch_list'] = $branch_list;    
        $data['page_title'] = $this->page_title;    
        return \View::make('dashboard.pages.ajax-branch-list',$data);
    }

    /********************************************
    ## AjaxMerchantList
    *********************************************/
    public function AjaxMerchantList($user_type){
        if($user_type == 'merchant'){
            $merchant_list=\DB::table('tbl_merchant')
            ->where('merchant_status','1')
            ->get();
        }
        elseif($user_type == 'branch'){
            $merchant_list=\DB::table('tbl_merchant')->get();
        }else{
            $merchant_list=0;
        }
        $data['merchant_list'] = $merchant_list;
        $data['page_title'] = $this->page_title;    
        return \View::make('dashboard.pages.ajax-merchant-list',$data);
    }

    /********************************************
    ## AjaxMerchantBranchList
    *********************************************/
    public function AjaxMerchantBranchList($merchant_id){
        $branch_list=\DB::table('tbl_branch')
                    ->where('merchant_id',$merchant_id)
                    ->where('branch_status','0')
                    ->get();

        $data['branch_list'] = $branch_list;
        $data['page_title'] = $this->page_title;    
        return \View::make('dashboard.pages.ajax-branch-user-list',$data);
    }


    /********************************************
    ## SubCategoryAdd
    *********************************************/
    public function SubCategoryAdd(){

        $user_id=\Auth::user()->id;
        $now=date('Y-m-d H:i:s');

        $rules=array(
            'category_id' => 'Required',
            'sub_category_name' => 'Required',
            'sub_category_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            );

        $v=\Validator::make(\Request::all(), $rules);

        if($v->passes()){

            $sub_category_name_slug = explode(' ', strtolower(\Request::input('sub_category_name')));
            $sub_category_name_slug = implode('_', $sub_category_name_slug);

            if(!empty(\Request::file('sub_category_image'))){

                $image = \Request::file('sub_category_image');
                $img_location=$image->getRealPath();
                $img_ext=$image->getClientOriginalExtension();
                $sub_category_image=\App\Admin::SubCategoryImageUpload($img_location, $sub_category_name_slug, $img_ext);
            }
            else{
                $sub_category_image='';

            }


            $sub_category_data=array(
                'category_id' =>  \Request::input('category_id'),
                'sub_category_name' =>  \Request::input('sub_category_name'),
                'sub_category_name_slug' => $sub_category_name_slug,
                'sub_category_featured_image' => $sub_category_image,
                'sub_category_status' => 0,
                'created_by' => $user_id,
                'updated_by' => $user_id,
                'created_at' => $now,
                'updated_at' => $now,
                );

            try{

                $save_sub_category_info=\DB::table('tbl_sub_category')->insert($sub_category_data);
                \App\System::EventLogWrite('insert,tbl_sub_category',json_encode($sub_category_data));
                return \Redirect::to('/dashboard/sub-category')->with('message',"Sub Category Added Successfully !");

            }catch(\Exception $e){

                $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                \App\System::ErrorLogWrite($message);

                return \Redirect::to('/dashboard/sub-category')->with('message',"Info Already Exist !");
            }

        }else return \Redirect::to('/dashboard/sub-category')->withInput(\Request::all())->withErrors($v->messages());
    }

    /********************************************
    ## SubCategoryEdit
    *********************************************/
    public function SubCategoryEdit($sub_category_id){

        $edit_sub_category_info=\DB::table('tbl_sub_category')
                ->where('sub_category_id',$sub_category_id)
                ->first();
        if(!empty($edit_sub_category_info)){
            $category_info=\DB::table('tbl_category')->get();
            $sub_category_info=\DB::table('tbl_sub_category')
                    ->leftjoin('tbl_category','tbl_sub_category.category_id','=','tbl_category.category_id')
                    ->OrderBy('tbl_sub_category.updated_at','desc')
                    ->paginate(10);
            $sub_category_info->setPath(url('/dashboard/sub-category'));
            $sub_category_pagination = $sub_category_info->render();
            $data['sub_category_pagination']=$sub_category_pagination;
            $data['sub_category_info'] = $sub_category_info;    
            $data['category_info'] = $category_info;    
            $data['edit_sub_category_info'] = $edit_sub_category_info;    
            $data['page_title'] = $this->page_title;    
            return \View::make('dashboard.pages.edit-sub-category',$data);
        }else{
            return \Redirect::to('/dashboard/category')->with('errormessage',"Invalid Sub Category Id");
        }
    }


    /********************************************
    ## CategoryUpdate
    *********************************************/
    public function SubCategoryUpdate($sub_category_id){

        $user_id=\Auth::user()->id;
        $now=date('Y-m-d H:i:s');

        $rules=array(
            'category_id' => 'Required',
            'sub_category_name' => 'Required',

            );

        $v=\Validator::make(\Request::all(), $rules);

        if($v->passes()){

            $sub_category_name_slug = explode(' ', strtolower(\Request::input('sub_category_name')));
            $sub_category_name_slug = implode('_', $sub_category_name_slug);

            if(!empty(\Request::file('sub_category_image'))){
                $rules2=array(
                    'sub_category_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                        );
                $val=\Validator::make(\Request::all(), $rules2);

                if($val->passes()){

                    $image = \Request::file('sub_category_image');
                    $img_location=$image->getRealPath();
                    $img_ext=$image->getClientOriginalExtension();
                    $sub_category_image=\App\Admin::SubCategoryImageUpload($img_location, $sub_category_name_slug, $img_ext);

                }else return \Redirect::to('/dashboard/sub-category')->with('errormessage','Image size is to be maximum 2048 kb');
            }
            else{
                $sub_category_image=\Request::input('update_sub_category_image');
            }


            $update_sub_category_data=array(
                'category_id' =>  \Request::input('category_id'),
                'sub_category_name' =>  \Request::input('sub_category_name'),
                'sub_category_name_slug' => $sub_category_name_slug,
                'sub_category_featured_image' => $sub_category_image,
                'updated_by' => $user_id,
                'updated_at' => $now,
                );

            try{

                $save_sub_category_info=\DB::table('tbl_sub_category')->where('sub_category_id',$sub_category_id)->update($update_sub_category_data);
                \App\System::EventLogWrite('insert,tbl_sub_category',json_encode($update_sub_category_data));
                return \Redirect::to('/dashboard/sub-category')->with('message',"Sub Category Updated Successfully !");

            }catch(\Exception $e){

                $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                \App\System::ErrorLogWrite($message);

                return \Redirect::to('/dashboard/sub-category')->with('message',"Info Already Exist !");
            }

        }else return \Redirect::to('/dashboard/sub-category')->withErrors($v->messages());
    }

    /********************************************
    ## CategoryDelete
    *********************************************/
    public function SubCategoryDelete($sub_category_id){


        $coupon_info=\DB::table('tbl_coupon')->where('coupon_sub_category_id',$sub_category_id)->first();
        if(empty($coupon_info)){
            \DB::table('tbl_sub_category')->where('sub_category_id',$sub_category_id)->delete();
            return \Redirect::to('/dashboard/sub-category')->with('message',"Sub Category Deleted Successfully !");
        }else{
            return \Redirect::to('/dashboard/sub-category')->with('message',"You Can not delete this because it has coupon");
        }

    }




    /********************************************
    ## MerchantPage 
    *********************************************/
    public function MerchantPage(){

        $merchant_info=\DB::table('tbl_merchant')
                ->OrderBy('updated_at','desc')
                ->paginate(10);
        $merchant_info->setPath(url('/dashboard/merchant'));
        $merchant_pagination = $merchant_info->render();
        $data['merchant_pagination']=$merchant_pagination;
        $data['merchant_info'] = $merchant_info;    
        $data['page_title'] = $this->page_title;    
        return \View::make('dashboard.pages.merchant',$data);
    }


    /********************************************
    ## MerchantAdd
    *********************************************/
    public function MerchantAdd(){

        $user_id=\Auth::user()->id;
        $now=date('Y-m-d H:i:s');

        $rules=array(
            'merchant_name' => 'Required',
            'merchant_code' => 'Required',
            'merchant_address' => 'Required|max:190',
            'merchant_propriter' => 'Required',
            'merchant_propriter_mobile' => 'Required|regex:/^[^0-9]*(88)?0/|max:11',
            'merchant_email' => 'Required|email',
            'merchant_description' => 'Required|max:190',
            'merchant_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',

            );

        $v=\Validator::make(\Request::all(), $rules);

        if($v->passes()){

            $merchant_name_slug = explode(' ', strtolower(\Request::input('merchant_name')));
            $merchant_name_slug = implode('_', $merchant_name_slug);


                if(!empty(\Request::file('merchant_image'))){

                    $image = \Request::file('merchant_image');
                    $img_location=$image->getRealPath();
                    $img_ext=$image->getClientOriginalExtension();
                    $merchant_image=\App\Admin::MarchantLogoUpload($img_location, $merchant_name_slug, $img_ext);
                }
                else{
                    $merchant_image='';

                }

                $merchant_data=array(
                    'merchant_name' =>  \Request::input('merchant_name'),
                    'merchant_name_slug' => $merchant_name_slug,
                    'merchant_code' =>  \Request::input('merchant_code'),
                    'merchant_address' =>  \Request::input('merchant_address'),
                    'merchant_propriter' =>  \Request::input('merchant_propriter'),
                    'merchant_propriter_mobile' =>  \Request::input('merchant_propriter_mobile'),
                    'merchant_email' =>  \Request::input('merchant_email'),
                    'merchant_featured_coupon' => 1,
                    'merchant_status' => 1,
                    'merchant_logo' =>$merchant_image,
                    'merchant_description' =>  \Request::input('merchant_description'),
                    'merchant_website_url' =>  \Request::input('merchant_website_url'),
                    'created_by' => $user_id,
                    'updated_by' => $user_id,
                    'created_at' => $now,
                    'updated_at' => $now,
                    );

                try{

                    $save_merchant_info=\DB::table('tbl_merchant')->insert($merchant_data);
                    \App\System::EventLogWrite('insert,tbl_merchant',json_encode($merchant_data));
                    return \Redirect::to('/dashboard/merchant-page')->with('message',"Merchant Added Successfully !");

                }catch(\Exception $e){

                    $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                    \App\System::ErrorLogWrite($message);

                    return \Redirect::to('/dashboard/merchant-page')->with('message',"Info Already Exist !");
                }

        }else return \Redirect::to('/dashboard/merchant-page')->withInput(\Request::all())->withErrors($v->messages());
    }

    /********************************************
    ## MerchantEdit
    *********************************************/
    public function MerchantEdit($merchant_id){

        $edit_merchant_info=\DB::table('tbl_merchant')
                ->where('merchant_id',$merchant_id)
                ->first();
        if(!empty($edit_merchant_info)){
            $merchant_info=\DB::table('tbl_merchant')
                    ->OrderBy('updated_at','desc')
                    ->paginate(10);
            $merchant_info->setPath(url('/dashboard/merchant'));
            $merchant_pagination = $merchant_info->render();
            $data['merchant_pagination']=$merchant_pagination;
            $data['merchant_info'] = $merchant_info;    
            $data['edit_merchant_info'] = $edit_merchant_info;    
            $data['page_title'] = $this->page_title;    
            return \View::make('dashboard.pages.edit-merchant',$data);
        }else{
            return \Redirect::to('/dashboard/category')->with('errormessage',"Invalid Merchant Id");
        }
    }


    /********************************************
    ## MerchantUpdate
    *********************************************/
    public function MerchantUpdate($merchant_id){

        $user_id=\Auth::user()->id;
        $now=date('Y-m-d H:i:s');

        $rules=array(
                'merchant_name' => 'Required',
                'merchant_code' => 'Required',
                'merchant_address' => 'Required|max:190',
                'merchant_propriter' => 'Required',
                'merchant_propriter_mobile' => 'Required|regex:/^[^0-9]*(88)?0/|max:11',
                'merchant_email' => 'Required|email',
                'merchant_description' => 'Required|max:190',

            );

        $v=\Validator::make(\Request::all(), $rules);

        if($v->passes()){

            $merchant_name_slug = explode(' ', strtolower(\Request::input('merchant_name')));
            $merchant_name_slug = implode('_', $merchant_name_slug);
            $update_merchant_image = \Request::input('update_merchant_image');

            $marchant_transaction_info=\DB::table('tbl_coupon_transaction')->where('transaction_merchant_id',$merchant_id)->get();

            if(empty($marchant_transaction_info)){
                if(!empty(\Request::file('merchant_image'))){
                    $rules2=array(
                        'merchant_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                            );
                    $val=\Validator::make(\Request::all(), $rules2);

                    if($val->passes()){
                        $image = \Request::file('merchant_image');
                        $img_location=$image->getRealPath();
                        $img_ext=$image->getClientOriginalExtension();
                        $merchant_image=\App\Admin::MarchantLogoUpload($img_location, $merchant_name_slug, $img_ext);
                    }else{
                        return \Redirect::to('/dashboard/all-merchant/list')->with('errormessage',"Image size is to be maximun 2048 kb");
                    }
                }
                else{
                    $merchant_image=$update_merchant_image;

                }


                $update_merchant_data=array(
                    'merchant_name' =>  \Request::input('merchant_name'),
                    'merchant_name_slug' => $merchant_name_slug,
                    'merchant_code' =>  \Request::input('merchant_code'),
                    'merchant_address' =>  \Request::input('merchant_address'),
                    'merchant_propriter' =>  \Request::input('merchant_propriter'),
                    'merchant_propriter_mobile' =>  \Request::input('merchant_propriter_mobile'),
                    'merchant_email' =>  \Request::input('merchant_email'),
                    'merchant_featured_coupon' => 1,
                    'merchant_status' => 1,
                    'merchant_logo' =>$merchant_image,
                    'merchant_description' =>  \Request::input('merchant_description'),
                    'merchant_website_url' =>  \Request::input('merchant_website_url'),
                    'updated_by' => $user_id,
                    'updated_at' => $now,
                    );

                try{

                    $update_marchant_info=\DB::table('tbl_merchant')->where('merchant_id',$merchant_id)->update($update_merchant_data);
                    \App\System::EventLogWrite('update,tbl_merchant',json_encode($update_merchant_data));
                    return \Redirect::to('/dashboard/all-merchant/list')->with('message',"Merchant Updated Successfully !");

                }catch(\Exception $e){

                    $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                    \App\System::ErrorLogWrite($message);

                    return \Redirect::to('/dashboard/all-merchant/list')->with('message',"Info Already Exist !");
                }
            }else return \Redirect::to('/dashboard/all-merchant/list')->with('errormessage',"This merchant has transaction.");
        }else return \Redirect::to('/dashboard/all-merchant/list')->withErrors($v->messages());
    }

    /********************************************
    ## MerchantDelete
    *********************************************/
    public function MerchantDelete($merchant_id){

        $coupon_info=\DB::table('tbl_coupon')->where('coupon_merchant_id',$merchant_id)->first();
        if(empty($coupon_info)){
            \DB::table('tbl_merchant')->where('merchant_id',$merchant_id)->delete();
            return \Redirect::to('/dashboard/all-merchant/list')->with('message',"Merchant Deleted Successfully !");
        }else{
            return \Redirect::to('/dashboard/all-merchant/list')->with('message',"You Can not delete this because it has coupon");
        }

    }



    /********************************************
    ## MerchantFeaturedImage 
    *********************************************/
    public function MerchantFeaturedImage(){

        $merchant_info=\DB::table('tbl_merchant')->get();
        $featured_product_info=\DB::table('tbl_featured_product')
                ->leftjoin('tbl_merchant','tbl_featured_product.merchant_id','=','tbl_merchant.merchant_id')
                ->leftjoin('tbl_branch','tbl_featured_product.branch_id','=','tbl_branch.branch_id')
                ->leftjoin('tbl_coupon','tbl_featured_product.branch_id','=','tbl_coupon.coupon_branch_id')
                ->OrderBy('tbl_featured_product.created_at','desc')
                ->paginate(5);
        $featured_product_info->setPath(url('/dashboard/merchant/featured'));
        $featured_product_pagination = $featured_product_info->render();
        $data['featured_product_pagination']=$featured_product_pagination;
        $data['featured_product_info'] = $featured_product_info;    
        $data['merchant_info'] = $merchant_info;    
        $data['page_title'] = $this->page_title;    
        return \View::make('dashboard.pages.merchant-featured-image',$data);
    }


   /********************************************
    ## MerchantFeaturedAdd
    *********************************************/
    public function MerchantFeaturedAdd(){

        $user_id=\Auth::user()->id;
        $now=date('Y-m-d H:i:s');

        $rules=array(
            'merchant_id' => 'Required',
            'branch_id' => 'Required',
            'product_original_price' => 'Required|numeric',
            'product_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',

            );

        $v=\Validator::make(\Request::all(), $rules);

        if($v->passes()){
            $product_discount_price=0;
            $merchant_id = \Request::input('merchant_id');
            $branch_id = \Request::input('branch_id');
            $product_original_price = \Request::input('product_original_price');
            $product_featured_description = \Request::input('product_featured_description');

            $get_coupon_info=\DB::table('tbl_coupon')
                            ->where('coupon_merchant_id',$merchant_id)
                            ->where('coupon_branch_id',$branch_id)
                            ->first();
            if(!empty($get_coupon_info)){
	    if(!empty($get_coupon_info)){
                $discount_amount=($product_original_price*$get_coupon_info->coupon_discount_rate)/100;
                if($get_coupon_info->coupon_max_discount != 0){
                    if($discount_amount>($get_coupon_info->coupon_max_discount)){
               		$coupon_discount_amount=$get_coupon_info->coupon_max_discount;
                        $product_discount_price=$product_original_price-$coupon_discount_amount;
                    }
                }else{
                        $product_discount_price=$product_original_price-$discount_amount; 
                }
            }else{
                $product_discount_price=$product_original_price;
            }

            if(!empty(\Request::file('product_image'))){

                $image = \Request::file('product_image');
                $img_location=$image->getRealPath();
                $img_ext=$image->getClientOriginalExtension();
                $merchant_featured_image=\App\Admin::MerchantFeaturedImageUpload($img_location, $merchant_id, $img_ext);
            }
            else{
                $merchant_featured_image='';

            }

            $merchant_featured_data=array(
                'merchant_id' => $merchant_id,
                'branch_id' => $branch_id,
                'product_image' =>$merchant_featured_image,
                'product_original_price' =>  $product_original_price,
                'product_discount_rate' =>  $get_coupon_info->coupon_discount_rate,
                'product_discount_price' =>  $product_discount_price,
                'product_featured_description' =>  $product_featured_description,
                'featured_product_status' => 1,
                'created_by' => $user_id,
                'updated_by' => $user_id,
                'created_at' => $now,
                'updated_at' => $now,
                );

            try{

                $save_merchant_featured_info=\DB::table('tbl_featured_product')->insert($merchant_featured_data);
                \App\System::EventLogWrite('insert,tbl_featured_product',json_encode($merchant_featured_data));
                return \Redirect::to('/dashboard/merchant/featured')->with('message',"Merchant Featured Added Successfully !");

            }catch(\Exception $e){

                $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                \App\System::ErrorLogWrite($message);

                return \Redirect::to('/dashboard/merchant/featured')->with('message',"Info Already Exist !");
            }
	}else return \Redirect::to('/dashboard/merchant/featured')->with('errormessage',"This merchant have no coupon");

        }else return \Redirect::to('/dashboard/merchant/featured')->withInput(\Request::all())->withErrors($v->messages());
    }

    /********************************************
    ## MerchantFeaturedEdit
    *********************************************/
    public function MerchantFeaturedEdit($featured_product_id){

        $merchant_info=\DB::table('tbl_merchant')
                ->leftjoin('tbl_branch','tbl_merchant.merchant_id','=','tbl_branch.merchant_id')
                ->where('tbl_merchant.merchant_status','1')
                ->get();

        $edit_merchant_featured_info=\DB::table('tbl_featured_product')
                ->leftjoin('tbl_branch','tbl_featured_product.branch_id','=','tbl_branch.branch_id')
                ->where('tbl_featured_product.featured_product_id',$featured_product_id)
                ->first();
        if(!empty($edit_merchant_featured_info)){
            $featured_product_info=\DB::table('tbl_featured_product')
                ->leftjoin('tbl_merchant','tbl_featured_product.merchant_id','=','tbl_merchant.merchant_id')
                ->leftjoin('tbl_branch','tbl_featured_product.branch_id','=','tbl_branch.branch_id')
                ->leftjoin('tbl_coupon','tbl_featured_product.branch_id','=','tbl_coupon.coupon_branch_id')
                ->OrderBy('tbl_featured_product.created_at','desc')
                ->paginate(5);
            $featured_product_info->setPath(url('/dashboard/merchant/featured'));
            $featured_product_pagination = $featured_product_info->render();
            $data['featured_product_pagination']=$featured_product_pagination;
            $data['featured_product_info'] = $featured_product_info;    
            $data['edit_merchant_featured_info'] = $edit_merchant_featured_info;    
            $data['merchant_info'] = $merchant_info;    
            $data['page_title'] = $this->page_title;    
            return \View::make('dashboard.pages.edit-merchant-featured-image',$data);
        }else{
            return \Redirect::to('/dashboard/merchant/featured')->with('errormessage',"Invalid Merchant Featured Id");
        }
    }


    /********************************************
    ## MerchantFeaturedUpdate
    *********************************************/
    public function MerchantFeaturedUpdate($featured_product_id){

        $user_id=\Auth::user()->id;
        $now=date('Y-m-d H:i:s');

        $rules=array(
            'merchant_id' => 'Required',
            'branch_id' => 'Required',
            'product_original_price' => 'Required|numeric',
            );

        $v=\Validator::make(\Request::all(), $rules);

        if($v->passes()){


            $merchant_id = \Request::input('merchant_id');
            $branch_id = \Request::input('branch_id');
            $product_original_price = \Request::input('product_original_price');
            $product_featured_description = \Request::input('product_featured_description');
            $update_merchant_featured_image = \Request::input('update_merchant_featured_image');

            $get_coupon_info=\DB::table('tbl_coupon')
                            ->where('coupon_merchant_id',$merchant_id)
                            ->where('coupon_branch_id',$branch_id)
                            ->first();
            if(!empty($get_coupon_info)){
	    if(!empty($get_coupon_info)){
                $discount_amount=($product_original_price*$get_coupon_info->coupon_discount_rate)/100;
                if($get_coupon_info->coupon_max_discount != 0){
                    if($discount_amount>($get_coupon_info->coupon_max_discount)){
                        $coupon_discount_amount=$get_coupon_info->coupon_max_discount;
                        $product_discount_price=$product_original_price-$coupon_discount_amount;
                    }else{
                        $product_discount_price=$product_original_price-$discount_amount; 
                    }
                }else{
                     $product_discount_price=$product_original_price-$discount_amount; 
                }
            }else{
                $product_discount_price=$product_original_price;
            }

            if(!empty(\Request::file('product_image'))){
                $rules2=array(
                    'product_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                        );
                $val=\Validator::make(\Request::all(), $rules2);

                if($val->passes()){
                    $image = \Request::file('product_image');
                    $img_location=$image->getRealPath();
                    $img_ext=$image->getClientOriginalExtension();
                    $product_image=\App\Admin::MerchantFeaturedImageUpload($img_location, $merchant_id, $img_ext);
                }else{
                    return \Redirect::to('/dashboard/merchant/featured')->with('errormessage',"Image size is to be maximun 2048 kb");
                }
            }
            else{
                $product_image=$update_merchant_featured_image;

            }



            $update_merchant_featured_data=array(
                'merchant_id' => $merchant_id,
                'branch_id' => $branch_id,
                'product_image' =>$product_image,
                'product_original_price' =>  $product_original_price,
                'product_discount_rate' =>  $get_coupon_info->coupon_discount_rate,
                'product_discount_price' =>  $product_discount_price,
                'product_featured_description' =>  $product_featured_description,
                'featured_product_status' => 1,
                'updated_by' => $user_id,
                'updated_at' => $now,
                );

            try{

                $update_marchant_info=\DB::table('tbl_featured_product')->where('featured_product_id',$featured_product_id)->update($update_merchant_featured_data);
                \App\System::EventLogWrite('update,tbl_featured_product',json_encode($update_merchant_featured_data));
                return \Redirect::to('/dashboard/merchant/featured')->with('message',"Merchant Featured Updated Successfully !");

            }catch(\Exception $e){

                $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                \App\System::ErrorLogWrite($message);

                return \Redirect::to('/dashboard/merchant/featured')->with('message',"Info Already Exist !");
            }
	}else return \Redirect::to('/dashboard/merchant/featured')->with('errormessage',"This merchant have no coupon");

        }else return \Redirect::to('/dashboard/merchant/featured')->withErrors($v->messages());
    }

    /********************************************
    ## MerchantFeaturedDelete
    *********************************************/
    public function MerchantFeaturedDelete($featured_product_id){

        $merchant_featured_info=\DB::table('tbl_featured_product')->where('featured_product_id',$featured_product_id)->first();
        if(!empty($merchant_featured_info)){
            \DB::table('tbl_featured_product')->where('featured_product_id', $featured_product_id)->delete();
            return \Redirect::to('/dashboard/merchant/featured')->with('message',"Merchant Featured Deleted Successfully !");
        }else{
            return \Redirect::to('/dashboard/merchant/featured')->with('message',"Invalid Request");
        }

    }


    /********************************************
    ## MerchantFeaturedChangeStatus
    *********************************************/
    public function MerchantFeaturedChangeStatus($featured_product_id,$action){
        $user_id=\Auth::user()->id;
        $now=date('Y-m-d H:i:s');

        $featured_product_data=array(
            'featured_product_status' => $action,
            'updated_by' => $user_id,
            'updated_at' => $now,
            );

        try{

            $merchant_featured_status_update=\DB::table('tbl_featured_product')->where('featured_product_id',$featured_product_id)->update($featured_product_data);
            \App\System::EventLogWrite('update,tbl_featured_product',json_encode($featured_product_data));
            return \Redirect::to('/dashboard/merchant/featured')->with('message',"Merchant Featured Status Changed Successfully !");

        }catch(\Exception $e){

            $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
            \App\System::ErrorLogWrite($message);

            return \Redirect::to('/dashboard/merchant/featuredr')->with('message',"Info Already Exist !");
        }

    }


    /********************************************
    ## BranchPage 
    *********************************************/
    public function BranchPage(){



        if(isset($_GET['merchant_name'])){

            $branch_info=\DB::table('tbl_branch')
                ->leftjoin('tbl_merchant','tbl_branch.merchant_id','=','tbl_merchant.merchant_id')
                ->where(function($query){

                    if(isset($_GET['merchant_name']) && ($_GET['merchant_name'] !=0)){
                        $query->where(function ($q){
                            $q->where('tbl_branch.merchant_id', $_GET['merchant_name']);
                          });
                    }

                })
                ->paginate(10);

            if(isset($_GET['merchant_name']))
                $merchant_name = $_GET['merchant_name'];
            else $merchant_name = null;

            $branch_info->setPath(url('/dashboard/merchant-branch'));

            $branch_pagination = $branch_info->appends(['merchant_name' => $merchant_name])->render();


        }else{

            $branch_info=\DB::table('tbl_branch')
                    ->leftjoin('tbl_merchant','tbl_branch.merchant_id','=','tbl_merchant.merchant_id')
                    ->OrderBy('tbl_branch.updated_at','desc')
                    ->paginate(10);
            $branch_info->setPath(url('/dashboard/merchant-branch'));
            $branch_pagination = $branch_info->render();
        }

        $merchant_info=\DB::table('tbl_merchant')->get();
        $data['merchant_info'] = $merchant_info;    
        $data['branch_pagination']=$branch_pagination;
        $data['branch_info'] = $branch_info;    
        $data['page_title'] = $this->page_title;    
        return \View::make('dashboard.pages.branch',$data);
    }


    /********************************************
    ## BranchAdd
    *********************************************/
    public function BranchAdd(){

        $user_id=\Auth::user()->id;
        $now=date('Y-m-d H:i:s');

        $rules=array(
            'merchant_id' => 'Required',
            'branch_code' => 'Required',
            'branch_name' => 'Required',
            'branch_address' => 'Required|max:190',
            'branch_email' => 'Required|email',
            'branch_mobile' => 'Required|unique:tbl_branch,branch_mobile|regex:/^[^0-9]*(88)?0/|max:11',            
            );

        $v=\Validator::make(\Request::all(), $rules);

        if($v->passes()){

            $branch_name_slug = explode(' ', strtolower(\Request::input('branch_name')));
            $branch_name_slug = implode('_', $branch_name_slug);


            $branch_data=array(
                'merchant_id' =>  \Request::input('merchant_id'),
                'branch_name' =>  \Request::input('branch_name'),
                'branch_slug' => $branch_name_slug,
                'branch_code' =>  \Request::input('branch_code'),
                'branch_city' =>  \Request::input('branch_city'),
                'branch_address' =>  \Request::input('branch_address'),
                'branch_mobile' =>  \Request::input('branch_mobile'),
                'branch_email' =>  \Request::input('branch_email'),
                'branch_gprs_lat' =>  \Request::input('branch_gprs_lat'),
                'branch_gprs_lng' =>  \Request::input('branch_gprs_lng'),
                'created_by' => $user_id,
                'updated_by' => $user_id,
                'created_at' => $now,
                'updated_at' => $now,
                );

            try{

                $save_branch_info=\DB::table('tbl_branch')->insert($branch_data);
                \App\System::EventLogWrite('insert,tbl_branch',json_encode($branch_data));
                return \Redirect::to('/dashboard/merchant-branch')->with('message',"Branch Added Successfully !");


            }catch(\Exception $e){

                $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                \App\System::ErrorLogWrite($message);

                return \Redirect::to('/dashboard/merchant-branch')->with('message',"Info Already Exist !");
            }

        }else return \Redirect::to('/dashboard/merchant-branch')->withInput(\Request::all())->withErrors($v->messages());
    }

    /********************************************
    ## BranchEdit
    *********************************************/
    public function BranchEdit($branch_id){
        $merchant_info=\DB::table('tbl_merchant')->get();
        $edit_branch_info=\DB::table('tbl_branch')
            ->leftjoin('tbl_merchant','tbl_branch.merchant_id','=','tbl_merchant.merchant_id')
            ->where('tbl_branch.branch_id',$branch_id)
            ->first();

        if(!empty($edit_branch_info)){

            if(isset($_GET['merchant_name'])){

                $branch_info=\DB::table('tbl_branch')
                    ->leftjoin('tbl_merchant','tbl_branch.merchant_id','=','tbl_merchant.merchant_id')
                    ->where(function($query){

                        if(isset($_GET['merchant_name']) && ($_GET['merchant_name'] !=0)){
                            $query->where(function ($q){
                                $q->where('tbl_branch.merchant_id', $_GET['merchant_name']);
                              });
                        }

                    })
                    ->paginate(10);

                if(isset($_GET['merchant_name']))
                    $merchant_name = $_GET['merchant_name'];
                else $merchant_name = null;

                $branch_info->setPath(url('/dashboard/merchant-branch'));

                $branch_pagination = $branch_info->appends(['merchant_name' => $merchant_name])->render();

            }else{

                $branch_info=\DB::table('tbl_branch')
                        ->leftjoin('tbl_merchant','tbl_branch.merchant_id','=','tbl_merchant.merchant_id')
                        ->OrderBy('tbl_branch.updated_at','desc')
                        ->paginate(10);
                $branch_info->setPath(url('/dashboard/merchant-branch'));
                $branch_pagination = $branch_info->render();
            }


            $data['branch_pagination']=$branch_pagination;
            $data['branch_info'] = $branch_info;    
            $data['merchant_info'] = $merchant_info;    
            $data['edit_branch_info'] = $edit_branch_info;    
            $data['page_title'] = $this->page_title;    
            return \View::make('dashboard.pages.edit-branch',$data);
        }else{
            return \Redirect::to('/dashboard/merchant-branch')->with('errormessage',"Invalid Branch Id");
        }
    }


    /********************************************
    ## BranchUpdate
    *********************************************/
    public function BranchUpdate($branch_id){

        $user_id=\Auth::user()->id;
        $now=date('Y-m-d H:i:s');

        $rules=array(
            'merchant_id' => 'Required',
            'branch_code' => 'Required',
            'branch_name' => 'Required',
            'branch_address' => 'Required|max:190',
            'branch_email' => 'Required|email',
            'branch_mobile' => 'Required|regex:/^[^0-9]*(88)?0/|max:11',
            );

        $v=\Validator::make(\Request::all(), $rules);

        if($v->passes()){

            $branch_name_slug = explode(' ', strtolower(\Request::input('branch_name')));
            $branch_name_slug = implode('_', $branch_name_slug);
            $update_merchant_name = \Request::input('merchant_name');


            $branch_transaction_info=\DB::table('tbl_coupon_transaction')->where('transaction_branch_id', $branch_id)->get();

                //if(empty($branch_transaction_info) && count($branch_transaction_info)<1){

                    $update_branch_data=array(
                        'merchant_id' =>  \Request::input('merchant_id'),
                        'branch_name' =>  \Request::input('branch_name'),
                        'branch_slug' => $branch_name_slug,
                        'branch_code' =>  \Request::input('branch_code'),
                        'branch_address' =>  \Request::input('branch_address'),
                        'branch_mobile' =>  \Request::input('branch_mobile'),
                        'branch_email' =>  \Request::input('branch_email'),
                        'branch_city' =>  \Request::input('branch_city'),
                        'branch_gprs_lat' =>  \Request::input('branch_gprs_lat'),
                        'branch_gprs_lng' =>  \Request::input('branch_gprs_lng'),
                        'updated_by' => $user_id,
                        'updated_at' => $now,
                        );

                    try{

                        $update_branch_info=\DB::table('tbl_branch')->where('branch_id',$branch_id)->update($update_branch_data);
                        \App\System::EventLogWrite('update,tbl_branch',json_encode($update_branch_data));
                        return \Redirect::to('/dashboard/merchant-branch')->with('message',"Branch Updated Successfully !");

                    }catch(\Exception $e){

                        $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                        \App\System::ErrorLogWrite($message);

                        return \Redirect::to('/dashboard/merchant-branch')->with('message',"Info Already Exist !");
                    }
                //}else return \Redirect::to('/dashboard/merchant-branch')->with('errormessage',"This branch has transaction.");

        }else return \Redirect::to('/dashboard/merchant-branch')->withErrors($v->messages());
    }

    /********************************************
    ## BranchDelete
    *********************************************/
    public function BranchDelete($branch_id){

        $coupon_info=\DB::table('tbl_coupon')->where('coupon_branch_id',$branch_id)->first();
        if(empty($coupon_info)){
            \DB::table('tbl_branch')->where('branch_id',$branch_id)->delete();
            return \Redirect::to('/dashboard/merchant-branch')->with('message',"Branch Deleted Successfully !");
        }else{
            return \Redirect::to('/dashboard/merchant-branch')->with('message',"You Can not delete this because it has coupon");
        }
    }



############################### Coupon #######################################


    /********************************************
    ## CouponPage 
    *********************************************/
    public function CouponPage(){

        $category_info=\DB::table('tbl_category')->get();
        $sub_category_info=\DB::table('tbl_sub_category')->get();
        $merchant_info=\DB::table('tbl_merchant')->get();
        $branch_info=\DB::table('tbl_branch')->get();
        $coupon_info=\DB::table('tbl_coupon')
                ->leftjoin('tbl_category','tbl_coupon.coupon_category_id','=','tbl_category.category_id')
                ->leftjoin('tbl_sub_category','tbl_coupon.coupon_sub_category_id','=','tbl_sub_category.sub_category_id')
                ->leftjoin('tbl_merchant','tbl_coupon.coupon_merchant_id','=','tbl_merchant.merchant_id')
                ->leftjoin('tbl_branch','tbl_coupon.coupon_branch_id','=','tbl_branch.branch_id')
                ->OrderBy('tbl_coupon.updated_at','desc')
                ->paginate(10);
        $coupon_info->setPath(url('/dashboard/coupon'));
        $coupon_pagination = $coupon_info->render();
        $data['coupon_info']=$coupon_info;
        $data['coupon_pagination']=$coupon_pagination;

        $data['category_info'] = $category_info;    
        $data['sub_category_info'] = $sub_category_info; 
        $data['merchant_info'] = $merchant_info;
        $data['branch_info'] = $branch_info;
        $data['page_title'] = $this->page_title;    
        return \View::make('dashboard.pages.coupon',$data);
    }

    /********************************************
    ## AllCouponList 
    *********************************************/
    public function AllCouponList(){

        if(isset($_GET['merchant_name']) || isset($_GET['branch_name'])){

            // $merchant_name = $_GET['merchant_name'];
            // $branch_name = $_GET['branch_name'];

            $coupon_info=\DB::table('tbl_coupon')
                ->where(function($query){

                    if(isset($_GET['merchant_name']) && ($_GET['merchant_name'] !=0)){
                        $query->where(function ($q){
                            $q->where('coupon_merchant_id', $_GET['merchant_name']);
                          });
                    }
                    if(isset($_GET['branch_name']) && ($_GET['branch_name'] !=0)){
                        $query->where(function ($q){
                            $q->where('coupon_branch_id', $_GET['branch_name']);
                          });
                    }

                })
                ->leftjoin('tbl_category','tbl_coupon.coupon_category_id','=','tbl_category.category_id')
                ->leftjoin('tbl_sub_category','tbl_coupon.coupon_sub_category_id','=','tbl_sub_category.sub_category_id')
                ->leftjoin('tbl_merchant','tbl_coupon.coupon_merchant_id','=','tbl_merchant.merchant_id')
                ->leftjoin('tbl_branch','tbl_coupon.coupon_branch_id','=','tbl_branch.branch_id')
                ->OrderBy('tbl_coupon.updated_at','desc')
                ->paginate(10);

            if(isset($_GET['merchant_name']))
                $merchant_name = $_GET['merchant_name'];
            else $merchant_name = null;

            if(isset($_GET['branch_name']))
                $branch_name = $_GET['branch_name'];
            else $branch_name = null;

            $coupon_info->setPath(url('/dashboard/all-coupon/list'));

            $coupon_pagination = $coupon_info->appends(['merchant_name' => $merchant_name, 'branch_name' => $branch_name])->render();


        }else{
 
            $coupon_info=\DB::table('tbl_coupon') 
                    ->leftjoin('tbl_category','tbl_coupon.coupon_category_id','=','tbl_category.category_id')
                    ->leftjoin('tbl_sub_category','tbl_coupon.coupon_sub_category_id','=','tbl_sub_category.sub_category_id')
                    ->leftjoin('tbl_merchant','tbl_coupon.coupon_merchant_id','=','tbl_merchant.merchant_id')
                    ->leftjoin('tbl_branch','tbl_coupon.coupon_branch_id','=','tbl_branch.branch_id')
                    ->OrderBy('tbl_coupon.updated_at','desc')
                    ->paginate(10);
            $coupon_info->setPath(url('/dashboard/all-coupon/list'));
            $coupon_pagination = $coupon_info->render();
        }

        $data['coupon_pagination']=$coupon_pagination;
        $data['coupon_info']=$coupon_info;
        $data['page_title'] = $this->page_title;    
        return \View::make('dashboard.pages.all-coupon-list',$data);
    }


    /********************************************
    ## CouponChangeStatus
    *********************************************/
    public function CouponChangeStatus($coupon_id,$action){
        $user_id=\Auth::user()->id;
        $now=date('Y-m-d H:i:s');

        $coupon_update_data=array(
            'coupons_status' => $action,
            'updated_by' => $user_id,
            'updated_at' => $now,
            );

        try{

            $merchant_featured_status_update=\DB::table('tbl_coupon')->where('coupon_id',$coupon_id)->update($coupon_update_data);
            \App\System::EventLogWrite('update,tbl_couppon',json_encode($coupon_update_data));
            return \Redirect::to('/dashboard/all-coupon/list')->with('message',"Coupon  Status Changed Successfully !");

        }catch(\Exception $e){

            $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
            \App\System::ErrorLogWrite($message);

            return \Redirect::to('/dashboard/all-coupon/list')->with('message',"Info Already Exist !");
        }

    }


    /********************************************
    ## AjaxCouponListDetails
    *********************************************/
    public function AjaxCouponListDetails($coupon_id){

        $select_coupon_info=\DB::table('tbl_coupon')
                        ->leftjoin('tbl_category','tbl_coupon.coupon_category_id','=','tbl_category.category_id')
                        ->leftjoin('tbl_sub_category','tbl_coupon.coupon_sub_category_id','=','tbl_sub_category.sub_category_id')
                        ->leftjoin('tbl_merchant','tbl_coupon.coupon_merchant_id','=','tbl_merchant.merchant_id')
                        ->leftjoin('tbl_branch','tbl_coupon.coupon_branch_id','=','tbl_branch.branch_id')
                        ->where('coupon_id',$coupon_id)
                        ->first();

        $data['select_coupon_info'] = $select_coupon_info;    
        $data['page_title'] = $this->page_title;    
        return \View::make('dashboard.pages.ajax-coupon-list-details',$data);
    }



    /********************************************
    ## CouponInsert
    *********************************************/
    public function CouponInsert(){

        $user_id=\Auth::user()->id;
        $now=date('Y-m-d H:i:s');

        $rules=array(
            'coupon_category_id' => 'Required',
            'coupon_sub_category_id' => 'Required',
            'coupon_merchant_id' => 'Required',
            'coupon_branch_id' => 'Required|integer',
            'coupon_keyword' => 'Required|alpha',
            'coupon_discount_rate' => 'Required|integer',
            'coupon_max_discount' => 'Required|integer',
            'coupon_commision_rate' => 'Required',
            'coupon_max_commission' => 'Required|integer',
            'coupon_applied_min_amount' => 'Required',
            'coupon_max_limit' => 'Required',
            'coupon_opening_date' => 'Required',
            'coupon_closing_date' => 'Required',
            'coupon_featured_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            );

        $v=\Validator::make(\Request::all(), $rules);

        if($v->passes()){

            $coupon_merchant_id = \Request::input('coupon_merchant_id');
            $coupon_branch_id = \Request::input('coupon_branch_id');
            $coupon_keyword = \Request::input('coupon_keyword');
            $random_code=mt_rand(100, 999);

            $coupon_code = $coupon_keyword.$coupon_branch_id.$random_code;

            if(!empty(\Request::file('coupon_featured_image'))){

                $image = \Request::file('coupon_featured_image');
                $img_location=$image->getRealPath();
                $img_ext=$image->getClientOriginalExtension();
                $coupon_featured_image=\App\Admin::CouponImageUpload($img_location, $coupon_code, $img_ext);
            }
            else{
                $coupon_featured_image='';
            }


            $coupon_data=array(
                'coupon_category_id' =>  \Request::input('coupon_category_id'),
                'coupon_sub_category_id' =>  \Request::input('coupon_sub_category_id'),
                'coupon_merchant_id' =>  \Request::input('coupon_merchant_id'),
                'coupon_branch_id' =>  \Request::input('coupon_branch_id'),
                'coupon_keyword' =>  $coupon_keyword,
                'coupon_code' =>  $coupon_code,
                'coupon_discount_rate' =>  \Request::input('coupon_discount_rate'),
                'coupon_max_discount' =>  \Request::input('coupon_max_discount'),
                'coupon_commision_rate' =>  \Request::input('coupon_commision_rate'),
                'coupon_max_commission' =>  \Request::input('coupon_max_commission'),
                'coupon_applied_min_amount' =>  \Request::input('coupon_applied_min_amount'),
                'coupon_max_limit' =>  \Request::input('coupon_max_limit'),
                'coupon_opening_date' =>  \Request::input('coupon_opening_date'),
                'coupon_closing_date' =>  \Request::input('coupon_closing_date'),
                'coupon_applied_point' =>  \Request::input('coupon_applied_point'),
                'coupon_description' =>  \Request::input('coupon_description'),
                'coupon_featured_image' =>  $coupon_featured_image,
                'coupons_status' =>  '1',
                'created_by' => $user_id,
                'updated_by' => $user_id,
                'created_at' => $now,
                'updated_at' => $now,
                );

            try{

                $savecoupon_info=\DB::table('tbl_coupon')->insert($coupon_data);
                \App\System::EventLogWrite('insert,tbl_coupon',json_encode($coupon_data));
                return \Redirect::to('/dashboard/coupon')->with('message',"Coupon Added Successfully !");

            }catch(\Exception $e){

                $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                \App\System::ErrorLogWrite($message);

                return \Redirect::to('/dashboard/coupon')->with('message',"Info Already Exist !");
            }

        }else return \Redirect::to('/dashboard/coupon')->withInput(\Request::all())->withErrors($v->messages());
    }

    /********************************************
    ## CouponEdit
    *********************************************/
    public function CouponEdit($coupon_id){

        $edit_coupon_info=\DB::table('tbl_coupon')
                ->leftjoin('tbl_category','tbl_coupon.coupon_category_id','=','tbl_category.category_id')
                ->leftjoin('tbl_sub_category','tbl_coupon.coupon_sub_category_id','=','tbl_sub_category.sub_category_id')
                ->leftjoin('tbl_merchant','tbl_coupon.coupon_merchant_id','=','tbl_merchant.merchant_id')
                ->leftjoin('tbl_branch','tbl_coupon.coupon_branch_id','=','tbl_branch.branch_id')
                ->where('coupon_id',$coupon_id)
                ->first();

        if(!empty($edit_coupon_info)){
            $coupon_transaction_info=\DB::table('tbl_coupon_transaction')->where('coupon_id',$edit_coupon_info->coupon_id)->get();
            $count_coupon_transaction=count($coupon_transaction_info);

            if($count_coupon_transaction == 0){

                $category_info=\DB::table('tbl_category')->get();
                $sub_category_info=\DB::table('tbl_sub_category')->get();
                $merchant_info=\DB::table('tbl_merchant')->get();
                $branch_info=\DB::table('tbl_branch')->get();
                $coupon_info=\DB::table('tbl_coupon')
                        ->leftjoin('tbl_category','tbl_coupon.coupon_category_id','=','tbl_category.category_id')
                        ->leftjoin('tbl_sub_category','tbl_coupon.coupon_sub_category_id','=','tbl_sub_category.sub_category_id')
                        ->leftjoin('tbl_merchant','tbl_coupon.coupon_merchant_id','=','tbl_merchant.merchant_id')
                        ->leftjoin('tbl_branch','tbl_coupon.coupon_branch_id','=','tbl_branch.branch_id')
                        ->OrderBy('tbl_coupon.updated_at','desc')
                        ->paginate(10);
                $coupon_info->setPath(url('/dashboard/coupon'));
                $coupon_pagination = $coupon_info->render();
                $data['coupon_info']=$coupon_info;
                $data['coupon_pagination']=$coupon_pagination;

                $data['category_info'] = $category_info;   
                $data['sub_category_info'] = $sub_category_info; 
                $data['merchant_info'] = $merchant_info;
                $data['branch_info'] = $branch_info;   
                $data['branch_info'] = $branch_info;  
                $data['edit_coupon_info'] = $edit_coupon_info;  
                $data['page_title'] = $this->page_title;    
                return \View::make('dashboard.pages.edit-coupon',$data);
            }else{
                $data['edit_coupon_info'] = $edit_coupon_info;  
                $data['page_title'] = $this->page_title;    
                return \View::make('dashboard.pages.edit-new-coupon',$data);                
            }

        }else{
            return \Redirect::to('/dashboard/all-coupon/list')->with('errormessage',"Invalid Coupon Id");
        }
    }


    /********************************************
    ## CouponUpdate
    *********************************************/
    public function CouponUpdate($coupon_id){

        $coupon_info=\DB::table('tbl_coupon')->where('coupon_id',$coupon_id)->first();
        $coupon_code=$coupon_info->coupon_code;

        $user_id=\Auth::user()->id;
        $now=date('Y-m-d H:i:s');

         $rules=array(
            'coupon_category_id' => 'Required',
            'coupon_sub_category_id' => 'Required',
            'coupon_merchant_id' => 'Required',
            'coupon_branch_id' => 'Required',
            'coupon_keyword' => 'Required',
            'coupon_discount_rate' => 'Required|integer',
            'coupon_max_discount' => 'Required|integer',            
            'coupon_commision_rate' => 'Required',
            'coupon_max_commission' => 'Required|integer',
            'coupon_applied_min_amount' => 'Required',
            'coupon_max_limit' => 'Required',
            'coupon_opening_date' => 'Required',
            'coupon_closing_date' => 'Required',

            );

        $v=\Validator::make(\Request::all(), $rules);

        if($v->passes()){

            $coupon_merchant_id = \Request::input('coupon_merchant_id');
            $coupon_branch_id = \Request::input('coupon_branch_id');
            $coupon_keyword = \Request::input('coupon_keyword');

            $random_code=mt_rand(100, 999);
            $coupon_code = $coupon_keyword.$coupon_branch_id.$random_code;


            // $resent_coupon_code_info=sscanf($coupon_code, "%[A-Za-z]%[0-9]");

            // $coupon_code = $coupon_keyword.$resent_coupon_code_info[1];

            if(!empty(\Request::file('coupon_featured_image'))){

                $rules2=array(
                    'coupon_featured_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                        );
                $val=\Validator::make(\Request::all(), $rules2);

                if($val->passes()){

                    $image = \Request::file('coupon_featured_image');
                    $img_location=$image->getRealPath();
                    $img_ext=$image->getClientOriginalExtension();
                    $coupon_featured_image=\App\Admin::CouponImageUpload($img_location, $coupon_code, $img_ext);
                }else{
                    return \Redirect::to('/dashboard/all-coupon/list')->with('errormessage',"Image size is to be maximum 2048 kb");
                }
            }
            else{
                $coupon_featured_image=\Request::input('update_coupon_featured_image');
            }


            $coupon_data=array(
                'coupon_category_id' =>  \Request::input('coupon_category_id'),
                'coupon_sub_category_id' =>  \Request::input('coupon_sub_category_id'),
                'coupon_merchant_id' =>  \Request::input('coupon_merchant_id'),
                'coupon_branch_id' =>  \Request::input('coupon_branch_id'),
                'coupon_keyword' =>  $coupon_keyword,
                'coupon_code' =>  $coupon_code,
                'coupon_discount_rate' =>  \Request::input('coupon_discount_rate'),
                'coupon_max_discount' =>  \Request::input('coupon_max_discount'),
                'coupon_commision_rate' =>  \Request::input('coupon_commision_rate'),
                'coupon_max_commission' =>  \Request::input('coupon_max_commission'),
                'coupon_applied_min_amount' =>  \Request::input('coupon_applied_min_amount'),
                'coupon_max_limit' =>  \Request::input('coupon_max_limit'),
                'coupon_opening_date' =>  \Request::input('coupon_opening_date'),
                'coupon_closing_date' =>  \Request::input('coupon_closing_date'),
                'coupon_applied_point' =>  \Request::input('coupon_applied_point'),
                'coupon_description' =>  \Request::input('coupon_description'),
                'coupon_featured_image' =>  $coupon_featured_image,                
                'updated_by' => $user_id,
                'updated_at' => $now,
                );

            try{

                $savecoupon_info=\DB::table('tbl_coupon')->where('coupon_id',$coupon_id)->update($coupon_data);
                \App\System::EventLogWrite('update,tbl_coupon',json_encode($coupon_data));
                return \Redirect::to('/dashboard/all-coupon/list')->with('message',"Coupon Updated Successfully !");

            }catch(\Exception $e){

                $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                \App\System::ErrorLogWrite($message);

                return \Redirect::to('/dashboard/all-coupon/list')->with('message',"Info Already Exist !");
            }

        }else return \Redirect::to('/dashboard/all-coupon/list')->withErrors($v->messages());
    }

    /********************************************
    ## TransactionCouponUpdate
    *********************************************/
    public function TransactionCouponUpdate($coupon_id){

        $coupon_info=\DB::table('tbl_coupon')->where('coupon_id',$coupon_id)->first();
        if(!empty($coupon_info)){
            $coupon_code=$coupon_info->coupon_code;
            $user_id=\Auth::user()->id;
            $now=date('Y-m-d H:i:s');

             $rules=array(
                'coupon_discount_rate' => 'Required|integer',
                'coupon_commision_rate' => 'Required|numeric',
                'coupon_max_discount' => 'Required|integer',            
                'coupon_max_commission' => 'Required|integer',
                'coupon_applied_min_amount' => 'Required',
                'coupon_max_limit' => 'Required',
                'coupon_closing_date' => 'Required',

                );

            $v=\Validator::make(\Request::all(), $rules);

            if($v->passes()){

                if(!empty(\Request::file('coupon_featured_image'))){

                    $rules2=array(
                        'coupon_featured_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                            );
                    $val=\Validator::make(\Request::all(), $rules2);

                    if($val->passes()){

                        $image = \Request::file('coupon_featured_image');
                        $img_location=$image->getRealPath();
                        $img_ext=$image->getClientOriginalExtension();
                        $coupon_featured_image=\App\Admin::CouponImageUpload($img_location, $coupon_code, $img_ext);
                    }else{
                        return \Redirect::to('/dashboard/all-coupon/list')->with('errormessage',"Image size is to be maximum 2048 kb");
                    }
                }
                else{
                    $coupon_featured_image=\Request::input('update_coupon_featured_image');
                }


                $coupon_data=array(
                    'coupon_discount_rate' =>  \Request::input('coupon_discount_rate'),
                    'coupon_commision_rate' =>  \Request::input('coupon_commision_rate'),
                    'coupon_max_discount' =>  \Request::input('coupon_max_discount'),
                    'coupon_max_commission' =>  \Request::input('coupon_max_commission'),
                    'coupon_applied_min_amount' =>  \Request::input('coupon_applied_min_amount'),
                    'coupon_max_limit' =>  \Request::input('coupon_max_limit'),
                    'coupon_closing_date' =>  \Request::input('coupon_closing_date'),
                    'coupon_applied_point' =>  \Request::input('coupon_applied_point'),
                    'coupon_description' =>  \Request::input('coupon_description'),
                    'coupon_featured_image' =>  $coupon_featured_image,                
                    'updated_by' => $user_id,
                    'updated_at' => $now,
                    );

                try{

                    $savecoupon_info=\DB::table('tbl_coupon')->where('coupon_id',$coupon_id)->update($coupon_data);
                    \App\System::EventLogWrite('insert,tbl_coupon',json_encode($coupon_data));
                    return \Redirect::to('/dashboard/all-coupon/list')->with('message',"Coupon Updated Successfully !");

                }catch(\Exception $e){

                    $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                    \App\System::ErrorLogWrite($message);

                    return \Redirect::to('/dashboard/all-coupon/list')->with('message',"Info Already Exist !");
                }

            }else return \Redirect::to('/dashboard/all-coupon/list')->withErrors($v->messages());
        }else return \Redirect::to('/dashboard/all-coupon/list')->with('errormessage','Invalid Coupon.');
    }

    /********************************************
    ## CouponDelete
    *********************************************/
    public function CouponDelete($coupon_id){

        $coupon_transaction_info=\DB::table('tbl_coupon_transaction')->where('coupon_id',$coupon_id)->get();
        if(isset($coupon_transaction_info) && count($coupon_transaction_info)<=0){

            \DB::table('tbl_coupon')->where('coupon_id',$coupon_id)->delete();
            return \Redirect::to('/dashboard/all-coupon/list')->with('message',"Coupon Deleted Successfully !");
        }else{
            return \Redirect::to('/dashboard/all-coupon/list')->with('errormessage',"Coupon has transaction !");  
        }

    }



    /********************************************
    ## AllCouponComments 
    *********************************************/
    public function AllCouponComments(){

        if(isset($_GET['merchant_name']) || isset($_GET['branch_name'])){

            // $merchant_name = $_GET['merchant_name'];
            // $branch_name = $_GET['branch_name'];

            $coupon_comments_info=\DB::table('tbl_coupon_review_comments')
                ->leftjoin('tbl_coupon','tbl_coupon_review_comments.coupon_id','=','tbl_coupon.coupon_id')
                ->where(function($query){

                    if(isset($_GET['merchant_name']) && ($_GET['merchant_name'] !=0)){
                        $query->where(function ($q){
                            $q->where('coupon_merchant_id', $_GET['merchant_name']);
                          });
                    }
                    if(isset($_GET['branch_name']) && ($_GET['branch_name'] !=0)){
                        $query->where(function ($q){
                            $q->where('coupon_branch_id', $_GET['branch_name']);
                          });
                    }

                })
                ->leftjoin('tbl_merchant','tbl_coupon.coupon_merchant_id','=','tbl_merchant.merchant_id')
                ->leftjoin('tbl_branch','tbl_coupon.coupon_branch_id','=','tbl_branch.branch_id')
                ->OrderBy('tbl_coupon.updated_at','desc')
                ->paginate(10);

            if(isset($_GET['merchant_name']))
                $merchant_name = $_GET['merchant_name'];
            else $merchant_name = null;

            if(isset($_GET['branch_name']))
                $branch_name = $_GET['branch_name'];
            else $branch_name = null;

            $coupon_comments_info->setPath(url('/dashboard/all-coupon/comments/list'));

            $coupon_comments_pagination = $coupon_comments_info->appends(['merchant_name' => $merchant_name, 'branch_name' => $branch_name])->render();


        }else{
 
            $coupon_comments_info=\DB::table('tbl_coupon_review_comments') 
                    ->leftjoin('tbl_coupon','tbl_coupon_review_comments.coupon_id','=','tbl_coupon.coupon_id')
                    ->leftjoin('tbl_merchant','tbl_coupon.coupon_merchant_id','=','tbl_merchant.merchant_id')
                    ->leftjoin('tbl_branch','tbl_coupon.coupon_branch_id','=','tbl_branch.branch_id')
                    ->OrderBy('tbl_coupon.updated_at','desc')
                    ->paginate(10);
            $coupon_comments_info->setPath(url('/dashboard/all-coupon/comments/list'));
            $coupon_comments_pagination = $coupon_comments_info->render();
        }

        $data['coupon_comments_pagination']=$coupon_comments_pagination;
        $data['coupon_comments_info']=$coupon_comments_info;
        $data['page_title'] = $this->page_title;    
        return \View::make('dashboard.pages.all-coupon-comments',$data);
    }


    /********************************************
    ## CouponCommentsDelete
    *********************************************/
    public function CouponCommentsDelete($review_comments_id){

        $coupon_comments_info=\DB::table('tbl_coupon_review_comments')->where('review_comments_id',$review_comments_id)->first();
        if(!empty($coupon_comments_info)){
            $coupon_info=\DB::table('tbl_coupon')->where('coupon_id',$coupon_comments_info->coupon_id)->first();
            $coupon_total_rating=($coupon_info->coupon_total_rating)-($coupon_comments_info->coupon_rating);
            $coupon_rating_client_count=($coupon_info->coupon_rating_client_count)-1;

            $update_coupon_rating_data=array(
                    'coupon_total_rating' =>  $coupon_total_rating,
                    'coupon_rating_client_count' =>  $coupon_rating_client_count,
                    'updated_by' => \Auth::user()->id,
                    'updated_at' => date('Y-m-d'),
                    );

            try{

                $coupon_comments_info=\DB::table('tbl_coupon')->where('coupon_id',$coupon_comments_info->coupon_id)->update($update_coupon_rating_data);
                $coupon_comments_info=\DB::table('tbl_coupon_review_comments')->where('review_comments_id',$review_comments_id)->delete();
                \App\System::EventLogWrite('update,tbl_coupon',json_encode($update_coupon_rating_data));
                \App\System::EventLogWrite('delete,tbl_coupon_review_comments',json_encode($review_comments_id));
                return \Redirect::to('/dashboard/all-coupon/comments/list')->with('message',"Coupon Comments Deleted Successfully !");

            }catch(\Exception $e){

                $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                \App\System::ErrorLogWrite($message);

                return \Redirect::to('/dashboard/all-coupon/comments/list')->with('message',"Something Wrong !!!");
            }
        }else{
           return \Redirect::to('/dashboard/all-coupon/comments/list')->with('message',"Invalid Id"); 
        }

    }


    /********************************************
    ## PendingCouponTransactionList 
    *********************************************/
    public function PendingCouponTransactionList(){
        $now=date('Y-m-d');
        if(isset($_GET['search_from']) && isset($_GET['search_to']) || isset($_GET['coupon_name']) || isset($_GET['user_name']) || isset($_GET['merchant_name'])){

            $search_from = $_GET['search_from'].' 00:00:00';
            $search_to = $_GET['search_to'].' 23:59:59';

            $coupon_transaction_info=\DB::table('tbl_coupon_transaction')
                ->where('tbl_coupon_transaction.coupon_status','=','1')
                ->where(function($query){

                    if(isset($_GET['coupon_name']) && ($_GET['coupon_name'] !=0)){
                        $query->where(function ($q){
                            $q->where('tbl_coupon_transaction.coupon_code', $_GET['coupon_name']);
                          });
                    }
                    if(isset($_GET['merchant_name']) && ($_GET['merchant_name'] !=0)){
                        $query->where(function ($q){
                            $q->where('tbl_coupon_transaction.transaction_merchant_id', $_GET['merchant_name']);
                          });
                    }
                    if(isset($_GET['user_name']) && ($_GET['user_name'] !=0)){
                        $query->where(function ($q){
                            $q->where('customer_id', $_GET['user_name']);
                          });
                    }

                })
                ->whereBetween('tbl_coupon_transaction.created_at',[$search_from,$search_to])
                ->leftjoin('tbl_coupon','tbl_coupon_transaction.coupon_id','=','tbl_coupon.coupon_id')
                ->OrderBy('tbl_coupon_transaction.updated_at','desc')
                ->paginate(10);

            if(isset($_GET['coupon_name']))
                $coupon_name = $_GET['coupon_name'];
            else $coupon_name = null;

            if(isset($_GET['merchant_name']))
                $merchant_name = $_GET['merchant_name'];
            else $merchant_name = null;

            if(isset($_GET['user_name']))
                $user_name = $_GET['user_name'];
            else $user_name = null;

            $coupon_transaction_info->setPath(url('/dashboard/pending-coupon/transaction/list'));

            $coupon_transaction_pagination = $coupon_transaction_info->appends(['search_from' => $_GET['search_from'], 'search_to'=> $_GET['search_to'], 'coupon_name' => $coupon_name,'merchant_name' => $merchant_name, 'user_name' => $user_name])->render();


        }else{
            $search_from=date('Y-m-d').' 00:00:00';
            $search_to = date('Y-m-d').' 23:59:59';

            $coupon_transaction_info=\DB::table('tbl_coupon_transaction') 
                    ->leftjoin('tbl_coupon','tbl_coupon_transaction.coupon_id','=','tbl_coupon.coupon_id')
                    ->where('tbl_coupon_transaction.coupon_status','=','1')
                    ->whereBetween('tbl_coupon_transaction.created_at',[$search_from,$search_to])
                    ->OrderBy('tbl_coupon_transaction.updated_at','desc')
                    ->paginate(10);
            $coupon_transaction_info->setPath(url('/dashboard/pending-coupon/transaction/list'));
            $coupon_transaction_pagination = $coupon_transaction_info->render();
        }

        $data['coupon_transaction_pagination']=$coupon_transaction_pagination;
        $data['coupon_transaction_info']=$coupon_transaction_info;
        $data['page_title'] = $this->page_title;
        return \View::make('dashboard.pages.sell-coupon-list',$data);
    }



    /********************************************
    ## CouponTransactionPdf 
    *********************************************/
    public function CouponTransactionPdf($search_from,$search_to,$merchant_id,$coupon_id,$customer_id){
        
	$now=date('Y-m-d');
        $search_from=$search_from.' 00:00:00';
        $search_to=$search_to.' 23:59:59';

        if(!empty($search_from) && !empty($search_to) || !empty($merchant_id) || !empty($coupon_id) || !empty($customer_id)){
        $coupon_transaction_info=\DB::table('tbl_coupon_transaction') 
                ->leftjoin('tbl_coupon','tbl_coupon_transaction.coupon_id','=','tbl_coupon.coupon_id')
                ->leftjoin('tbl_merchant','tbl_coupon_transaction.transaction_merchant_id','=','tbl_merchant.merchant_id')
                ->leftjoin('tbl_branch','tbl_coupon_transaction.transaction_branch_id','=','tbl_branch.branch_id')
                ->leftjoin('users','tbl_coupon_transaction.customer_id','=','users.id')
                ->where('tbl_coupon_transaction.coupon_status','2')

                ->where(function($query) use ($merchant_id,$coupon_id,$customer_id){
                    if(!empty($merchant_id) && ($merchant_id != 0)){
                        $query->where(function ($q) use ($merchant_id){
                            $q->where('tbl_coupon_transaction.transaction_merchant_id',$merchant_id);
                        });
                    }

                    if(!empty($coupon_id) && ($coupon_id != 0)){
                        $query->where(function ($q) use ($coupon_id){
                            $q->where('tbl_coupon.coupon_code',$coupon_id);
                          });
                    }

                    if(!empty($customer_id) && ($customer_id != 0)){
                        $query->where(function ($q) use ($customer_id){
                            $q->where('tbl_coupon_transaction.customer_id', $customer_id);
                          });
                    }

                })
                ->whereBetween('tbl_coupon_transaction.updated_at',[$search_from,$search_to])
                ->OrderBy('tbl_coupon_transaction.updated_at','desc')
                ->get();
        }else{

            $search_from=$date('Y-m-d').' 00:00:00';
            $search_to=$date('Y-m-d').' 23:59:59';
            $coupon_transaction_info=\DB::table('tbl_coupon_transaction') 
                ->leftjoin('tbl_coupon','tbl_coupon_transaction.coupon_id','=','tbl_coupon.coupon_id')
                ->leftjoin('tbl_merchant','tbl_coupon_transaction.transaction_merchant_id','=','tbl_merchant.merchant_id')
                ->leftjoin('tbl_branch','tbl_coupon_transaction.transaction_branch_id','=','tbl_branch.branch_id')
                ->leftjoin('users','tbl_coupon_transaction.customer_id','=','users.id')
                ->where('tbl_coupon_transaction.coupon_status','2')
                ->whereBetween('tbl_coupon_transaction.updated_at',[$search_from,$search_to])
                ->OrderBy('tbl_coupon_transaction.updated_at','desc')
                ->get();
        }

        $data['coupon_transaction_info']=$coupon_transaction_info;
        $data['search_from']=$search_from;
        $data['search_to']=$search_to;
        $data['page_title'] = $this->page_title;
        $pdf = \PDF::loadView('dashboard.pages.pdf.coupon-transaction-pdf', $data);
        $pdfname = time().'_coupon_transaction.pdf';
        return $pdf->download($pdfname); 
        // return $pdf->stream($pdfname); 
        // return \View::make('dashboard.pages.pdf.coupon-transaction-pdf',$data);
    }

    /********************************************
    ## MerchantTransactionPdf 
    *********************************************/
    public function MerchantTransactionPdf($search_from,$search_to,$branch_id){
        $now=date('Y-m-d');
        $data['search_from'] = $search_from;
        $data['search_to'] = $search_to;
        $search_from=$search_from.' 00:00:00';
        $search_to=$search_to.' 23:59:59';

        $merchant_user_id=\Auth::user()->id;
        $merchant_info=\DB::table('tbl_merchant')->where('merchant_user_id',$merchant_user_id)->first();
        $merchant_id=$merchant_info->merchant_id;

        if(!empty($branch_id)){
            if(($branch_id!=0)){
                $coupon_transaction_info=\DB::table('tbl_coupon_transaction') 
                        ->where('tbl_coupon_transaction.coupon_status','2')
                        ->where('tbl_coupon_transaction.transaction_merchant_id',$merchant_id)
                        ->where('tbl_coupon_transaction.transaction_branch_id',$branch_id)
                        ->whereBetween('tbl_coupon_transaction.updated_at',[$search_from,$search_to])
                        ->leftjoin('tbl_coupon','tbl_coupon_transaction.coupon_id','=','tbl_coupon.coupon_id')
                        ->leftjoin('tbl_merchant','tbl_coupon_transaction.transaction_merchant_id','=','tbl_merchant.merchant_id')
                        ->leftjoin('tbl_branch','tbl_coupon_transaction.transaction_branch_id','=','tbl_branch.branch_id')
                        ->leftjoin('users','tbl_coupon_transaction.customer_id','=','users.id')
                        ->OrderBy('tbl_coupon_transaction.updated_at','desc')
                        ->get();
            }elseif($branch_id == 0){
                $coupon_transaction_info=\DB::table('tbl_coupon_transaction') 
                        ->where('tbl_coupon_transaction.coupon_status','2')
                        ->where('tbl_coupon_transaction.transaction_merchant_id',$merchant_id)
                        ->whereBetween('tbl_coupon_transaction.updated_at',[$search_from,$search_to])
                        ->leftjoin('tbl_coupon','tbl_coupon_transaction.coupon_id','=','tbl_coupon.coupon_id')
                        ->leftjoin('tbl_merchant','tbl_coupon_transaction.transaction_merchant_id','=','tbl_merchant.merchant_id')
                        ->leftjoin('tbl_branch','tbl_coupon_transaction.transaction_branch_id','=','tbl_branch.branch_id')
                        ->leftjoin('users','tbl_coupon_transaction.customer_id','=','users.id')
                        ->OrderBy('tbl_coupon_transaction.updated_at','desc')
                        ->get();
            }

        }else{
            $coupon_transaction_info=\DB::table('tbl_coupon_transaction') 
                ->leftjoin('tbl_coupon','tbl_coupon_transaction.coupon_id','=','tbl_coupon.coupon_id')
                ->leftjoin('tbl_merchant','tbl_coupon_transaction.transaction_merchant_id','=','tbl_merchant.merchant_id')
                ->leftjoin('tbl_branch','tbl_coupon_transaction.transaction_branch_id','=','tbl_branch.branch_id')
                ->leftjoin('users','tbl_coupon_transaction.customer_id','=','users.id')
                ->where('tbl_coupon_transaction.transaction_merchant_id',$merchant_id)
                ->where('tbl_coupon_transaction.coupon_status','2')
                ->whereBetween('tbl_coupon_transaction.updated_at',[$search_from,$search_to])
                ->OrderBy('tbl_coupon_transaction.updated_at','desc')
                ->get();
        }

        $data['coupon_transaction_info']=$coupon_transaction_info;
        $data['page_title'] = $this->page_title;
        $pdf = \PDF::loadView('dashboard.pages.pdf.coupon-transaction-pdf', $data);
        $pdfname = time().'_coupon_transaction.pdf';
        return $pdf->download($pdfname); 
        // return \View::make('dashboard.pages.pdf.coupon-transaction-pdf',$data);
    }


    /********************************************
    ## BranchTransactionPdf 
    *********************************************/
    public function BranchTransactionPdf($search_from,$search_to,$branch_id){
        $now=date('Y-m-d');
        $data['search_from'] = $search_from;
        $data['search_to'] = $search_to;
        $search_from=$search_from.' 00:00:00';
        $search_to=$search_to.' 23:59:59';
        
        $merchant_id=\Auth::user()->user_merchant_id;

        if(!empty($branch_id)){
            $coupon_transaction_info=\DB::table('tbl_coupon_transaction') 
                    ->where('tbl_coupon_transaction.coupon_status','2')
                    ->where('tbl_coupon_transaction.transaction_branch_id',$branch_id)
                    ->whereBetween('tbl_coupon_transaction.updated_at',[$search_from,$search_to])
                    ->leftjoin('tbl_coupon','tbl_coupon_transaction.coupon_id','=','tbl_coupon.coupon_id')
                    ->leftjoin('tbl_merchant','tbl_coupon_transaction.transaction_merchant_id','=','tbl_merchant.merchant_id')
                    ->leftjoin('tbl_branch','tbl_coupon_transaction.transaction_branch_id','=','tbl_branch.branch_id')
                    ->leftjoin('users','tbl_coupon_transaction.customer_id','=','users.id')
                    ->OrderBy('tbl_coupon_transaction.updated_at','desc')
                    ->get();
        }else{
            $coupon_transaction_info=\DB::table('tbl_coupon_transaction') 
                ->leftjoin('tbl_coupon','tbl_coupon_transaction.coupon_id','=','tbl_coupon.coupon_id')
                ->leftjoin('tbl_merchant','tbl_coupon_transaction.transaction_merchant_id','=','tbl_merchant.merchant_id')
                ->leftjoin('tbl_branch','tbl_coupon_transaction.transaction_branch_id','=','tbl_branch.branch_id')
                ->leftjoin('users','tbl_coupon_transaction.customer_id','=','users.id')
                ->where('tbl_coupon_transaction.transaction_branch_id',$branch_id)
                ->where('tbl_coupon_transaction.coupon_status','2')
                ->whereBetween('tbl_coupon_transaction.updated_at',[$search_from,$search_to])
                ->OrderBy('tbl_coupon_transaction.updated_at','desc')
                ->get();
        }

        $data['coupon_transaction_info']=$coupon_transaction_info;
        $data['page_title'] = $this->page_title;
        $pdf = \PDF::loadView('dashboard.pages.pdf.coupon-transaction-pdf', $data);
        $pdfname = time().'_coupon_transaction.pdf';
        return $pdf->download($pdfname); 
        // return \View::make('dashboard.pages.pdf.coupon-transaction-pdf',$data);
    }



    /********************************************
    ## ActiveDealList 
    *********************************************/
    public function ActiveDealList(){
        $now=date('Y-m-d');
        if(isset($_GET['search_from']) && isset($_GET['search_to']) || isset($_GET['coupon_name']) || isset($_GET['user_name']) || isset($_GET['merchant_name'])){

            $search_from = $_GET['search_from'].' 00:00:00';
            $search_to = $_GET['search_to'].' 23:59:59';

            $coupon_transaction_info=\DB::table('tbl_coupon_transaction')
                ->where('tbl_coupon_transaction.coupon_status','=','-1')
                ->where(function($query){

                    if(isset($_GET['coupon_name']) && ($_GET['coupon_name'] !=0)){
                        $query->where(function ($q){
                            $q->where('tbl_coupon_transaction.coupon_code', $_GET['coupon_name']);
                          });
                    }
                    if(isset($_GET['merchant_name']) && ($_GET['merchant_name'] !=0)){
                        $query->where(function ($q){
                            $q->where('tbl_coupon_transaction.transaction_merchant_id', $_GET['merchant_name']);
                          });
                    }

                    if(isset($_GET['user_name']) && ($_GET['user_name'] !=0)){
                        $query->where(function ($q){
                            $q->where('customer_id', $_GET['user_name']);
                          });
                    }

                })
                ->whereBetween('tbl_coupon_transaction.created_at',[$search_from,$search_to])
                ->leftjoin('tbl_coupon','tbl_coupon_transaction.coupon_id','=','tbl_coupon.coupon_id')
                ->leftjoin('tbl_merchant','tbl_coupon_transaction.transaction_merchant_id','=','tbl_merchant.merchant_id')
                ->leftjoin('tbl_branch','tbl_coupon_transaction.transaction_branch_id','=','tbl_branch.branch_id')
                ->OrderBy('tbl_coupon_transaction.updated_at','desc')
                ->paginate(10);

            if(isset($_GET['coupon_name']))
                $coupon_name = $_GET['coupon_name'];
            else $coupon_name = null;

            if(isset($_GET['merchant_name']))
                $merchant_name = $_GET['merchant_name'];
            else $merchant_name = null;

            if(isset($_GET['user_name']))
                $user_name = $_GET['user_name'];
            else $user_name = null;

            $coupon_transaction_info->setPath(url('/dashboard/active-deal'));

            $coupon_transaction_pagination = $coupon_transaction_info->appends(['search_from' => $_GET['search_from'], 'search_to'=> $_GET['search_to'], 'coupon_name' => $coupon_name,'merchant_name' => $merchant_name, 'user_name' => $user_name])->render();


        }else{
            $search_from=date('Y-m-d').' 00:00:00';
            $search_to = date('Y-m-d').' 23:59:59';

            $coupon_transaction_info=\DB::table('tbl_coupon_transaction') 
                    ->leftjoin('tbl_coupon','tbl_coupon_transaction.coupon_id','=','tbl_coupon.coupon_id')
                    ->leftjoin('tbl_merchant','tbl_coupon_transaction.transaction_merchant_id','=','tbl_merchant.merchant_id')
                    ->leftjoin('tbl_branch','tbl_coupon_transaction.transaction_branch_id','=','tbl_branch.branch_id')
                    ->where('tbl_coupon_transaction.coupon_status','=','-1')
                    ->whereBetween('tbl_coupon_transaction.created_at',[$search_from,$search_to])
                    ->OrderBy('tbl_coupon_transaction.updated_at','desc')
                    ->paginate(10);
            $coupon_transaction_info->setPath(url('/dashboard/active-deal'));
            $coupon_transaction_pagination = $coupon_transaction_info->render();
        }

        $data['coupon_transaction_pagination']=$coupon_transaction_pagination;
        $data['coupon_transaction_info']=$coupon_transaction_info;
        $data['page_title'] = $this->page_title;
        return \View::make('dashboard.pages.active-deal-list',$data);
    }


    /********************************************
    ## ActiveDealDelete
    *********************************************/
    public function ActiveDealDelete($coupon_transaction_id){

        $coupon_transaction_info=\DB::table('tbl_coupon_transaction')->where('coupon_transaction_id',$coupon_transaction_id)->where('coupon_status','!=','2')->first();
        if(!empty($coupon_transaction_info)){
            \DB::beginTransaction();
            try{
                $select_coupon_info=\DB::table('tbl_coupon')->where('coupon_id',$coupon_transaction_info->coupon_id)->first();

                $coupon_total_selled=($select_coupon_info->coupon_total_selled)-1;
                \DB::table('tbl_coupon_transaction')->where('coupon_transaction_id',$coupon_transaction_id)->delete();
                \DB::table('tbl_coupon')->where('coupon_id',$select_coupon_info->coupon_id)->update(array("coupon_total_selled" =>$coupon_total_selled));
                \DB::commit();
                \App\System::EventLogWrite('delete,tbl_coupon_transaction',json_encode($coupon_transaction_info));
                return \Redirect::to('/dashboard/active-deal')->with('message',"Active Coupon Deleted Successfully !");
            }catch(\Exception $e){
                \DB::rollback();
                $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                \App\System::ErrorLogWrite($message);

                return \Redirect::to('/dashboard/active-deal')->with('message',"Info Already Exist !");
            }

        }else{
            return \Redirect::to('/dashboard/active-deal')->with('errormessage',"Invalid Active Coupon !");  
        }

    }




    /********************************************
    ## AllCouponConfirmTransaction
    *********************************************/
    public function AllCouponConfirmTransaction(){
        $now=date('Y-m-d');
        if(isset($_GET['search_from']) && isset($_GET['search_to']) || isset($_GET['merchant_name']) || isset($_GET['coupon_name']) || isset($_GET['user_name'])){

            $search_from = $_GET['search_from'].' 00:00:00';
            $search_to = $_GET['search_to'].' 23:59:59';

            $coupon_transaction_info=\DB::table('tbl_coupon_transaction')
                ->where(function($query){

                    if(isset($_GET['merchant_name']) && ($_GET['merchant_name'] !=0)){
                        $query->where(function ($q){
                            $q->where('tbl_coupon_transaction.transaction_merchant_id', $_GET['merchant_name']);
                          });
                    }
                    if(isset($_GET['coupon_name']) && ($_GET['coupon_name'] !=0)){
                        $query->where(function ($q){
                            $q->where('tbl_coupon_transaction.coupon_code', $_GET['coupon_name']);
                          });
                    }
                    if(isset($_GET['user_name']) && ($_GET['user_name'] !=0)){
                        $query->where(function ($q){
                            $q->where('customer_id', $_GET['user_name']);
                          });
                    }

                })
                ->whereBetween('tbl_coupon_transaction.created_at',[$search_from,$search_to])
                ->where('tbl_coupon_transaction.coupon_status','2')
                ->leftjoin('tbl_coupon','tbl_coupon_transaction.coupon_id','=','tbl_coupon.coupon_id')
                ->OrderBy('tbl_coupon_transaction.updated_at','desc')
                ->paginate(10);

            if(isset($_GET['merchant_name']))
                $merchant_name = $_GET['merchant_name'];
            else $merchant_name = null;
                
            if(isset($_GET['coupon_name']))
                $coupon_name = $_GET['coupon_name'];
            else $coupon_name = null;

            if(isset($_GET['user_name']))
                $user_name = $_GET['user_name'];
            else $user_name = null;

            $coupon_transaction_info->setPath(url('/dashboard/all-coupon/confirm/transaction/list'));

            $coupon_transaction_pagination = $coupon_transaction_info->appends(['search_from' => $_GET['search_from'], 'search_to'=> $_GET['search_to'], 'coupon_name' => $coupon_name, 'user_name' => $user_name])->render();
            $total_sell=count($coupon_transaction_info);



        }else{
            $search_from=date('Y-m-d').' 00:00:00';
            $search_to = date('Y-m-d').' 23:59:59';
            $coupon_transaction_info=\DB::table('tbl_coupon_transaction') 
                    ->leftjoin('tbl_coupon','tbl_coupon_transaction.coupon_id','=','tbl_coupon.coupon_id')
                    ->whereBetween('tbl_coupon_transaction.created_at',[$search_from,$search_to])
                    ->where('tbl_coupon_transaction.coupon_status','2')
                    ->OrderBy('tbl_coupon_transaction.updated_at','desc')
                    ->paginate(10);
            $coupon_transaction_info->setPath(url('/dashboard/all-coupon/confirm/transaction/list'));
            $coupon_transaction_pagination = $coupon_transaction_info->render();
            $total_sell=count($coupon_transaction_info);
        }

        $data['coupon_transaction_pagination']=$coupon_transaction_pagination;
        $data['coupon_transaction_info']=$coupon_transaction_info;
        $data['total_sell']=$total_sell;
        $data['page_title'] = $this->page_title;    
        return \View::make('dashboard.pages.sell-coupon-confirm-list',$data);
    }



############################### Coupon #######################################


    /********************************************
    ## AllMerchantList 
    *********************************************/
    public function AllMerchantList(){

        $active_merchant_info=\DB::table('tbl_merchant')->where('merchant_status','1')->get();
        $block_merchant_info=\DB::table('tbl_merchant')->where('merchant_status','-1')->get();
        $active_merchant=count($active_merchant_info);
        $block_merchant=count($block_merchant_info);
        $data['active_merchant'] = $active_merchant;    
        $data['block_merchant'] = $block_merchant;



        if(isset($_GET['merchant_name'])){


            $all_merchant_info=\DB::table('tbl_merchant')
                ->where(function($query){

                    if(isset($_GET['merchant_name']) && ($_GET['merchant_name'] !=0)){
                        $query->where(function ($q){
                            $q->where('merchant_id', $_GET['merchant_name']);
                          });
                    }

                })
                ->paginate(10);

            if(isset($_GET['merchant_name']))
                $merchant_name = $_GET['merchant_name'];
            else $merchant_name = null;

            $all_merchant_info->setPath(url('/dashboard/all-merchant/list'));

            $merchant_list_pagination = $all_merchant_info->appends(['merchant_name' => $merchant_name])->render();


        }else{
 
            $all_merchant_info=\DB::table('tbl_merchant')
                    ->OrderBy('created_at','desc')
                    ->paginate(10);
            $all_merchant_info->setPath(url('/dashboard/all-merchant/list'));
            $merchant_list_pagination = $all_merchant_info->render();
        }

        
        $data['merchant_list_pagination']=$merchant_list_pagination;
        $data['all_merchant_info'] = $all_merchant_info;    
        $data['page_title'] = $this->page_title;    
        return \View::make('dashboard.pages.all-merchant-list',$data);
    }


    /********************************************
    ## AjaxMerchantListDetails
    *********************************************/
    public function AjaxMerchantListDetails($merchant_id){

        $select_merchant_info=\DB::table('tbl_merchant')
                        ->where('merchant_id',$merchant_id)
                        ->first();

        $data['select_merchant_info'] = $select_merchant_info;    
        $data['page_title'] = $this->page_title;    
        return \View::make('dashboard.pages.ajax-merchant-list-details',$data);
    }



    /********************************************
    ## AjaxMerchantChangeStatus
    *********************************************/
    public function AjaxMerchantChangeStatus($merchant_id,$action){
        $user_id=\Auth::user()->id;
        $now=date('Y-m-d H:i:s');

        $merchant_update_data=array(
            'merchant_status' => $action,
            'updated_by' => $user_id,
            'updated_at' => $now,
            );

        try{

            $merchant_status_update=\DB::table('tbl_merchant')->where('merchant_id',$merchant_id)->update($merchant_update_data);
            \App\System::EventLogWrite('update,tbl_merchant',json_encode($merchant_update_data));
            return \Redirect::to('/dashboard/all-merchant/list')->with('message',"Merchant Status Changed Successfully !");

        }catch(\Exception $e){

            $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
            \App\System::ErrorLogWrite($message);

            return \Redirect::to('/dashboard/all-merchant/list')->with('message',"Info Already Exist !");
        }

    }



    /********************************************
    ## AjaxMerchantRankChange
    *********************************************/
    public function AjaxMerchantRankChange($merchant_id,$action){
        $user_id=\Auth::user()->id;
        $now=date('Y-m-d H:i:s');

        $merchant_update_data=array(
            'merchant_rank' => $action,
            );

        try{

            $merchant_status_update=\DB::table('tbl_merchant')->where('merchant_id',$merchant_id)->update($merchant_update_data);
            \App\System::EventLogWrite('update,tbl_merchant',json_encode($merchant_update_data));
            return \Redirect::to('/dashboard/all-merchant/list')->with('message',"Merchant Status Changed Successfully !");

        }catch(\Exception $e){

            $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
            \App\System::ErrorLogWrite($message);

            return \Redirect::to('/dashboard/all-merchant/list')->with('message',"Info Already Exist !");
        }

    }




    /********************************************
    ## AllUserList 
    *********************************************/
    public function AllUserList(){

        $all_user_info=\DB::table('users')
                ->where('user_type','client')
                ->OrderBy('updated_at','desc')
                ->paginate(10);
        $all_user_info->setPath(url('/dashboard/all-user/list'));
        $user_list_pagination = $all_user_info->render();
        $count_user_info=\DB::table('users')->where('user_type','client')->get();
        $count_user=count($count_user_info);
        $data['count_user']=$count_user;
        $data['user_list_pagination']=$user_list_pagination;
        $data['all_user_info'] = $all_user_info;    
        $data['page_title'] = $this->page_title;    
        return \View::make('dashboard.pages.all-user-list',$data);
    }

    /********************************************
    ## UserDelete
    *********************************************/
    public function UserDelete($user_id){

        $coupon_transaction_info=\DB::table('tbl_coupon_transaction')->where('customer_id',$user_id)->get();
        if(isset($coupon_transaction_info) && count($coupon_transaction_info)<=0){

            \DB::table('users')->where('id',$user_id)->delete();
            return \Redirect::to('/dashboard/all-user/list')->with('message',"User Deleted Successfully !");
        }else{
            return \Redirect::to('/dashboard/all-user/list')->with('errormessage',"User Has Coupon transaction !");  
        }

    }


    /********************************************
    ## AjaxUserListDetails
    *********************************************/
    public function AjaxUserListDetails($user_id){

        $select_user_info=\DB::table('users')
                        ->where('id',$user_id)
                        ->first();
        $user_meta_info=\DB::table('tbl_user_meta')
                        ->where('user_id',$user_id)
                        ->get();
        $data['user_meta_info'] = $user_meta_info;    
        $data['select_user_info'] = $select_user_info;    
        $data['page_title'] = $this->page_title;    
        return \View::make('dashboard.pages.ajax-user-list-details',$data);
    }

    /********************************************
    ## AjaxUserChangeStatus
    *********************************************/
    public function AjaxUserChangeStatus($select_user_id,$action){
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
            return \Redirect::to('/dashboard/all-user/list')->with('message',"User Status Changed Successfully !");

        }catch(\Exception $e){

            $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
            \App\System::ErrorLogWrite($message);

            return \Redirect::to('/dashboard/all-user/list')->with('message',"Info Already Exist !");
        }

    }



    /********************************************
    ## BuyCoupon
    *********************************************/
    public function BuyCoupon($coupon_code,$transaction_amount,$customer_mobile){
        $now=date('Y-m-d H:i:s');
        $active_deal_coupon_info=\DB::table('tbl_coupon_transaction')
                                ->where('tbl_coupon_transaction.coupon_code', $coupon_code)
                                ->where('tbl_coupon_transaction.customer_mobile',$customer_mobile)
                                ->where('tbl_coupon_transaction.coupon_status','-1')
                                ->first();

        if (is_numeric($transaction_amount)){

            $select_coupon_info=\DB::table('tbl_coupon')
                                ->where('tbl_coupon.coupon_code', $coupon_code)
                                ->leftjoin('tbl_merchant','tbl_coupon.coupon_merchant_id','=','tbl_merchant.merchant_id')
                                ->leftjoin('tbl_branch','tbl_coupon.coupon_branch_id','=','tbl_branch.branch_id')
                                ->first();
            if(!empty($select_coupon_info)){

                $branch_mobile=$select_coupon_info->branch_mobile;
                $coupon_dateline=$select_coupon_info->coupon_closing_date;
                if($coupon_dateline >= date('Y-m-d').'23:59:59'){

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

                        if(($select_coupon_info->coupon_max_limit == -1) || ($coupon_max_limit>$coupon_total_selled_info)){

                            \DB::beginTransaction();
                            try{

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
                                    'coupon_code' =>$coupon_code,
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
                                            ->where('coupon_code',$coupon_code)
                                            ->where('customer_mobile',$customer_mobile)
                                            ->where('coupon_transaction_id',$active_deal_coupon_info->coupon_transaction_id)
                                            ->update($coupon_transaction_update_data);
                                    \DB::table('tbl_coupon')->where('coupon_code',$coupon_code)->update(array("coupon_total_selled" =>$coupon_total_selled));
                                    \App\System::EventLogWrite('update,tbl_coupon_transaction',json_encode($coupon_transaction_update_data));

                                }else{
                                   $coupon_transaction_update=\DB::table('tbl_coupon_transaction')->insert($coupon_transaction_data);
                                    \App\System::EventLogWrite('insert,tbl_coupon_transaction',json_encode($coupon_transaction_data));
                                }
                                
                                $coupon_update=\DB::table('tbl_coupon')->where('coupon_code',$coupon_code)->update($coupon_update_data);
                                \App\System::EventLogWrite('update,tbl_coupon',json_encode($coupon_update_data));
                                $otp_send=\App\OTP::SendSMSForBuyCoupon($branch_mobile, $coupon_secret_code, $customer_mobile, $transaction_amount, $coupon_discount_amount);

                                \DB::commit();
                                $get_responce=\App\Admin::CouponJsonResponce('200','Coupon Transaction Update Successfully !');
                                return \Response::json($get_responce);

                            }catch(\Exception $e){
                                \DB::rollback();
                                $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                                \App\System::ErrorLogWrite($message);

                                $get_responce=\App\Admin::CouponJsonResponce('403','Info Already Exist !');
                                return \Response::json($get_responce);

                            }

                        }else{
                            $get_responce=\App\Admin::CouponJsonResponce('403','Coupon is stock out.');
                            return \Response::json($get_responce);
                        }
                    
                    }else{
                        $get_responce=\App\Admin::CouponJsonResponce('403','Shopping amount is less than minimum amount.');
                        return \Response::json($get_responce);
                    }
                }else{
                    $get_responce=\App\Admin::CouponJsonResponce('403','Coupon date is over');
                    return \Response::json($get_responce);       
                }

            }else{
                $get_responce=\App\Admin::CouponJsonResponce('403','Coupon code invalid !!!');
                return \Response::json($get_responce);
            }
        }else{
            $get_responce=\App\Admin::CouponJsonResponce('403','Transaction Amount is to be numeric !!!');
            return \Response::json($get_responce);

        }

    }



    /********************************************
    ## BuyCouponFromWeb
    *********************************************/
    public function BuyCouponFromWeb($coupon_code,$transaction_amount,$customer_mobile){
        $now=date('Y-m-d H:i:s');
        $active_deal_coupon_info=\DB::table('tbl_coupon_transaction')
                                ->where('coupon_code', $coupon_code)
                                ->where('customer_mobile',$customer_mobile)
                                ->where('coupon_status','-1')
                                ->first();
        if(!empty($active_deal_coupon_info)){

            if (is_numeric($transaction_amount)){

                $select_coupon_info=\DB::table('tbl_coupon')->where('coupon_code', $coupon_code)->first();
                if(!empty($select_coupon_info)){

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

                        if(($select_coupon_info->coupon_max_limit == -1) || ($coupon_max_limit>$coupon_total_selled_info)){


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

                            $coupon_transaction_update_data=array(
                                'coupon_secret_code'=>mt_rand(10000, 99999),
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
                                    \DB::table('tbl_coupon')->where('coupon_code',$coupon_code)->update(array("coupon_total_selled" =>$coupon_total_selled));
                                    \App\System::EventLogWrite('update,tbl_coupon_transaction',json_encode($coupon_transaction_update_data));


                                
                                $coupon_update=\DB::table('tbl_coupon')->where('coupon_code',$coupon_code)->update($coupon_update_data);
                                \App\System::EventLogWrite('update,tbl_coupon',json_encode($coupon_update_data));
                                \DB::commit();
                                return \Response::json(['message'=>'Coupon Transaction Update Successfully !']);

                            }catch(\Exception $e){
                                \DB::rollback();
                                $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                                \App\System::ErrorLogWrite($message);

                                return \Response::json(['message'=>'Info Already Exist !']);

                            }

                        }else{
                            return \Response::json(['message'=>'Coupon is stock out']);

                        }
                    
                    }else{
                        return \Response::json(['message'=>'Shopping amount is less than minimum amount.']);

                    }

                }else{
                    return \Response::json(['message'=>'Coupon code invalid.']);

                }
            }else{
                return \Response::json(['message'=>'Transaction Amount is to be numeric !!!']);

            }
        }else{
            return \Response::json(['message'=>'There are no active deal coupon !!!']);

        }

    }



    /********************************************
    ## AjaxCouponConfirmTransaction
    *********************************************/
    public function AjaxCouponConfirmTransaction($coupon_code,$coupon_transaction_id,$coupon_secret_code){
        $user_id=\Auth::user()->id;
        $now=date('Y-m-d H:i:s');
        $user_earning_point=0;

        $select_coupon_info=\DB::table('tbl_coupon')->where('coupon_code',$coupon_code)->first();
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
                try{

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
                    $get_responce=\App\Admin::CouponJsonResponce('200','Thank You For Shopping !');
                    return \Response::json($get_responce);


                }catch(\Exception $e){
                    \DB::rollback();
                    $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                    \App\System::ErrorLogWrite($message);

                    $get_responce=\App\Admin::CouponJsonResponce('403','Info Already Exist !');
                    return \Response::json($get_responce);

                }
            }else{
                $get_responce=\App\Admin::CouponJsonResponce('403','Coupon dateline is over.');
                return \Response::json($get_responce);
            }

        }else{
            $get_responce=\App\Admin::CouponJsonResponce('403','Invalid OTP ! Please try again.');
            return \Response::json($get_responce);
        }

    }


    /********************************************
    ## AjaxBuyCouponDetails
    *********************************************/
    public function AjaxBuyCouponDetails($coupon_code,$customer_mobile,$coupon_transaction_id, $tab_name){
        if(isset($_REQUEST['tab']) && !empty($_REQUEST['tab'])){
            $tab = $_REQUEST['tab'];
        }else $tab = $tab_name;
        $data['tab']=$tab;
        $data['coupon_code'] = $coupon_code;    
        $data['customer_mobile'] = $customer_mobile;    
        $data['coupon_transaction_id'] = $coupon_transaction_id;    
        $data['page_title'] = $this->page_title;    
        return \View::make('dashboard.pages.ajax-buy-coupon-amount',$data);
    }


    
    /********************************************
    ## SellQuantityPage
    *********************************************/
    public function SellQuantityPage(){

        $today = date('Y-m-d');
        $total_sell_coupon=0;
        $total_sell_confirm_coupon=0;
        $total_sell_coupon_amount=0;
        $total_shopping_amount=0;
        $total_discount_amount=0;
        $total_commisssion_amount=0;

        $user=\Auth::user()->id;

        if(isset($_GET['search_from']) && isset($_GET['search_to'])|| isset($_GET['merchant_name']) || isset($_GET['branch_name'])){

            $search_from = $_GET['search_from'].' 00:00:00';
            $search_to = $_GET['search_to'].' 23:59:59';

            $total_initial_transaction_info=\DB::table('tbl_coupon_transaction')
                ->leftjoin('tbl_coupon','tbl_coupon_transaction.coupon_id','=','tbl_coupon.coupon_id')
                ->whereBetween('tbl_coupon_transaction.created_at',array($search_from,$search_to))
                ->where(function($query){

                    if(isset($_GET['merchant_name']) && ($_GET['merchant_name'] !=0)){
                        $query->where(function ($q){
                            $q->where('tbl_coupon.coupon_merchant_id', $_GET['merchant_name']);
                          });
                    }
                    if(isset($_GET['branch_name']) && ($_GET['branch_name'] !=0)){
                        $query->where(function ($q){
                            $q->where('tbl_coupon.coupon_branch_id', $_GET['branch_name']);
                          });
                    }
                })
                ->get();

            $total_sell_coupon=count($total_initial_transaction_info);

            if(!empty($total_initial_transaction_info) && count($total_initial_transaction_info)>0){
                foreach ($total_initial_transaction_info as $key => $list) {
                    $total_sell_coupon_amount=$total_sell_coupon_amount+$list->coupon_buy_price;
                }
             }

            $total_final_transaction_info=\DB::table('tbl_coupon_transaction')
                ->leftjoin('tbl_coupon','tbl_coupon_transaction.coupon_id','=','tbl_coupon.coupon_id')
                ->whereBetween('tbl_coupon_transaction.created_at',array($search_from,$search_to))
                ->where(function($query){
                    if(isset($_GET['merchant_name']) && ($_GET['merchant_name'] !=0)){
                        $query->where(function ($q){
                            $q->where('tbl_coupon.coupon_merchant_id', $_GET['merchant_name']);
                          });
                    }
                    if(isset($_GET['branch_name']) && ($_GET['branch_name'] !=0)){
                        $query->where(function ($q){
                            $q->where('tbl_coupon.coupon_branch_id', $_GET['branch_name']);
                          });
                    }
                })
                ->where('coupon_status','2')
                ->get();

            $total_sell_confirm_coupon=count($total_final_transaction_info);

            if(!empty($total_final_transaction_info) && count($total_final_transaction_info)>0){
                foreach ($total_final_transaction_info as $key => $value) {
                    $total_shopping_amount=$total_shopping_amount+$value->coupon_shopping_amount;
                    $total_discount_amount=$total_discount_amount+$value->coupon_discount_amount;
                    $total_commisssion_amount=$total_commisssion_amount+$value->coupon_commission_amount;
                }
            }

        }else{
            $search_from=date('Y-m-d').' 00:00:00';
            $search_to = date('Y-m-d').' 23:59:59';
            $total_initial_transaction_info=\DB::table('tbl_coupon_transaction')
                ->leftjoin('tbl_coupon','tbl_coupon_transaction.coupon_id','=','tbl_coupon.coupon_id')
                ->whereBetween('tbl_coupon_transaction.created_at',array($search_from,$search_to))
                ->get();

            $total_sell_coupon=count($total_initial_transaction_info);

            if(!empty($total_initial_transaction_info) && count($total_initial_transaction_info)>0){
                foreach ($total_initial_transaction_info as $key => $list) {
                    $total_sell_coupon_amount=$total_sell_coupon_amount+$list->coupon_buy_price;
                }
             }

            $total_final_transaction_info=\DB::table('tbl_coupon_transaction')
                ->leftjoin('tbl_coupon','tbl_coupon_transaction.coupon_id','=','tbl_coupon.coupon_id')
                ->whereBetween('tbl_coupon_transaction.created_at',array($search_from,$search_to))
                ->where('coupon_status','2')
                ->get();

            $total_sell_confirm_coupon=count($total_final_transaction_info);

            if(!empty($total_final_transaction_info) && count($total_final_transaction_info)>0){
                foreach ($total_final_transaction_info as $key => $value) {
                    $total_shopping_amount=$total_shopping_amount+$value->coupon_shopping_amount;
                    $total_discount_amount=$total_discount_amount+$value->coupon_discount_amount;
                    $total_commisssion_amount=$total_commisssion_amount+$value->coupon_commission_amount;
                }
            }

        }

        $data['total_sell_coupon']=$total_sell_coupon;
        $data['total_sell_coupon_amount']=$total_sell_coupon_amount;
        $data['total_sell_confirm_coupon']=$total_sell_confirm_coupon;
        $data['total_shopping_amount']=$total_shopping_amount;
        $data['total_discount_amount']=$total_discount_amount;
        $data['total_commisssion_amount']=$total_commisssion_amount;

        $data['page_title'] = $this->page_title;
        return \View::make('dashboard.pages.sell-quantity',$data);
    }



    /********************************************
    ## CouponHighlight
    *********************************************/
    public function CouponHighlight($coupon_id,$action){
        $now=date('Y-m-d H:i:s');

        $coupon_highlight=array(
            'coupon_id' => $coupon_id,
            'coupon_highlight_status' => $action,
            'updated_at' => $now,
            );

        try{

            $coupon_highlight_save=\DB::table('tbl_coupon')->where('coupon_id',$coupon_id)->update($coupon_highlight);
            \App\System::EventLogWrite('update,tbl_coupon',json_encode($coupon_highlight));
            return \Redirect::to('dashboard/all-coupon/list');

        }catch(\Exception $e){

            $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
            \App\System::ErrorLogWrite($message);

            return \Redirect::to('dashboard/all-coupon/list');
        }

    }



    /********************************************
    ## CallRequestList 
    *********************************************/
    public function CallRequestList(){
        $now=date('Y-m-d');
        if(isset($_GET['search_from']) && isset($_GET['search_to'])  || isset($_GET['user_type'])){

            $search_from = $_GET['search_from'].' 00:00:00';
            $search_to = $_GET['search_to'].' 23:59:59';

            $call_request_info=\DB::table('tbl_contact')
                ->where(function($query){
                    if(isset($_GET['user_type']) && ($_GET['user_type'] != Null)){
                        $query->where(function ($q){
                            $q->where('tbl_contact.user_type', $_GET['user_type']);
                          });
                    }
                })
                ->where('tbl_contact.contact_type','=','call_request')
                ->whereBetween('tbl_contact.created_at',[$search_from,$search_to])
                ->OrderBy('tbl_contact.created_at','desc')
                ->paginate(10);

            $call_request_info->setPath(url('/call/request/list'));

            $call_request_pagination = $call_request_info->appends(['search_from' => $_GET['search_from'], 'search_to'=> $_GET['search_to'],'user_type'=> $_GET['user_type']])->render();


        }else{
            $search_from=date('Y-m-d').' 00:00:00';
            $search_to = date('Y-m-d').' 23:59:59';

            $call_request_info=\DB::table('tbl_contact')
                ->where('tbl_contact.contact_type','=','call_request')
                ->whereBetween('tbl_contact.created_at',[$search_from,$search_to])
                ->OrderBy('tbl_contact.created_at','desc')
                ->paginate(10);
            $call_request_info->setPath(url('/call/request/list'));
            $call_request_pagination = $call_request_info->render();
        }

        $data['call_request_pagination']=$call_request_pagination;
        $data['call_request_info']=$call_request_info;
        $data['page_title'] = $this->page_title;
        return \View::make('dashboard.pages.call-request-list',$data);
    }


    /********************************************
    ## MessageRequestList 
    *********************************************/
    public function MessageRequestList(){
        $now=date('Y-m-d');
        if(isset($_GET['search_from']) && isset($_GET['search_to'])){

            $search_from = $_GET['search_from'].' 00:00:00';
            $search_to = $_GET['search_to'].' 23:59:59';

            $message_request_info=\DB::table('tbl_contact')
                ->whereIn('tbl_contact.contact_type',['message_request','shop_request'])
                ->whereBetween('tbl_contact.created_at',[$search_from,$search_to])
                ->OrderBy('tbl_contact.created_at','desc')
                ->paginate(10);

            $message_request_info->setPath(url('/message/request/list'));

            $message_request_pagination = $message_request_info->appends(['search_from' => $_GET['search_from'], 'search_to'=> $_GET['search_to']])->render();


        }else{
            $search_from=date('Y-m-d').' 00:00:00';
            $search_to = date('Y-m-d').' 23:59:59';

            $message_request_info=\DB::table('tbl_contact')
                ->where('tbl_contact.contact_type','=','call_request')
                ->whereBetween('tbl_contact.created_at',[$search_from,$search_to])
                ->OrderBy('tbl_contact.created_at','desc')
                ->paginate(10);
            $message_request_info->setPath(url('/message/request/list'));
            $message_request_pagination = $message_request_info->render();
        }

        $data['message_request_pagination']=$message_request_pagination;
        $data['message_request_info']=$message_request_info;
        $data['page_title'] = $this->page_title;
        return \View::make('dashboard.pages.message-request-list',$data);
    }



    
    ########################### All Logs ######################



    /********************************************
    ## AccessLogListPage
    *********************************************/

    public function AccessLogs(){
        
        
        /*-----------------------Get Request-------------------------------*/
         if(isset($_GET['form_search_date']) && isset($_GET['to_search_date']) ){

            $form_search_date = $_GET['form_search_date'].' 00:00:00';
            $to_search_date = $_GET['to_search_date'].' 23:59:59';

            $access_log_list = \DB::table('access_log')
                                ->whereBetween('access_log.created_at',array($form_search_date,$to_search_date))
                                ->leftJoin('users','access_log.access_user_id','=','users.id')
                                ->select('access_log.*','users.name','users.id')
                                ->orderBy('access_log.created_at','desc')
                                ->paginate(10);

            $access_log_list->setPath(url('/system-admin/access-logs'));

            $pagination = $access_log_list->appends(['form_search_date' => $_GET['form_search_date'], 'to_search_date'=> $_GET['to_search_date']])->render();

            $data['pagination'] = $pagination;
            $data['access_log_list'] = $access_log_list;
            

         } 
        /*--------------------------/Get Request--------------------------*/
        else{
            $today = date('Y-m-d');
            $access_log_list=\DB::table('access_log')->where('access_log.created_at','like',$today."%")
                            ->leftJoin('users','access_log.access_user_id','=','users.id')
                            ->select('access_log.*','users.name','users.id')
                            ->orderBy('access_log.created_at','desc') 
                            ->paginate(10);
            $access_log_list->setPath(url('/system-admin/access-logs'));
            $pagination = $access_log_list->render();
            $data['pagination'] = $pagination;
            $data['access_log_list'] = $access_log_list;
        }
        $data['page_title'] = $this->page_title;
                
        return \View::make('dashboard.pages.system-admin.access-log',$data);

    }



    /********************************************
    ## ErrorLogListPage
    *********************************************/

    public function ErrorLogs(){

        /*----------------Get Request----------------------*/
         if(isset($_GET['form_search_date']) && isset($_GET['to_search_date']) ){

            $form_search_date = $_GET['form_search_date'].' 00:00:00';
            $to_search_date = $_GET['to_search_date'].' 23:59:59';

            $error_log_list = \DB::table('error_log')
                                ->whereBetween('error_log.created_at',array($form_search_date,$to_search_date))
                                ->leftJoin('users','error_log.error_user_id','=','users.id')
                                ->select('error_log.*','users.name','users.id')
                                ->orderBy('error_log.created_at','desc')
                                ->paginate(10);

            $error_log_list->setPath(url('/system-admin/error-logs'));

            $error_pagination = $error_log_list->appends(['form_search_date' => $_GET['form_search_date'], 'to_search_date'=> $_GET['to_search_date']])->render();

            $data['error_pagination'] = $error_pagination;
            $data['error_log_list'] = $error_log_list;

            
            

         }
        /*-------------------------/Get Request--------------------------*/
        else{
            $today = date('Y-m-d');
            $error_log_list=\DB::table('error_log')->where('error_log.created_at','like',$today."%")
                            ->leftJoin('users','error_log.error_user_id','=','users.id')
                            ->select('error_log.*','users.name','users.id')
                            ->orderBy('error_log.created_at','desc')
                            ->paginate(10);
            $error_log_list->setPath(url('/system-admin/error-logs'));
            $error_pagination = $error_log_list->render();
            $data['error_pagination'] = $error_pagination;
            $data['error_log_list'] = $error_log_list;
        }
        $data['page_title'] = $this->page_title;
                
        return \View::make('dashboard.pages.system-admin.error-log',$data);
    }






    /********************************************
    ## EventLogListPage
    *********************************************/

    public function EventLogs(){

        /*--------------------Get Request----------------------*/
         if(isset($_GET['form_search_date']) && isset($_GET['to_search_date']) ){

            $form_search_date = $_GET['form_search_date'].' 00:00:00';
            $to_search_date = $_GET['to_search_date'].' 23:59:59';

            $event_log_list = \DB::table('event_log')->whereBetween('event_log.created_at',array($form_search_date,$to_search_date))
                              ->leftJoin('users','event_log.event_user_id','=','users.id')
                              ->select('event_log.*','users.name','users.id')
                              ->orderBy('event_log.created_at','desc')
                              ->paginate(10);

            $event_log_list->setPath(url('/system-admin/event-logs'));

            $event_pagination = $event_log_list->appends(['form_search_date' => $_GET['form_search_date'], 'to_search_date'=> $_GET['to_search_date']])->render();

            $data['event_pagination'] = $event_pagination;
            $data['event_log_list'] = $event_log_list;

            
            

         }
        /*------------------------/Get Request-------------------*/
        else{
            $today = date('Y-m-d');
            $event_log_list=\DB::table('event_log')->where('event_log.created_at','like',$today."%")
                            ->leftJoin('users','event_log.event_user_id','=','users.id')
                            ->select('event_log.*','users.name','users.id')
                            ->orderBy('event_log.created_at','desc')
                            ->paginate(10);
            $event_log_list->setPath(url('/system-admin/event-logs'));
            $event_pagination = $event_log_list->render();
            $data['event_pagination'] = $event_pagination;
            $data['event_log_list'] = $event_log_list;
        }
        $data['page_title'] = $this->page_title;
                
        return \View::make('dashboard.pages.system-admin.event-log',$data);
    }





    /********************************************
    ## AuthLogListPage
    *********************************************/

    public function AuthLogs(){

        /*-------------------------Get Request---------------------*/
         if(isset($_GET['form_search_date']) && isset($_GET['to_search_date']) ){

            $form_search_date = $_GET['form_search_date'].' 00:00:00';
            $to_search_date = $_GET['to_search_date'].' 23:59:59';

            $auth_log_list = \DB::table('auth_log')
                            ->whereBetween('auth_log.created_at',array($form_search_date,$to_search_date))
                            ->leftJoin('users','auth_log.auth_user_id','=','users.id')
                            ->select('auth_log.*','users.name','users.id')
                            ->orderBy('auth_log.created_at','desc')
                            ->paginate(10);

            $auth_log_list->setPath(url('/system-admin/auth-logs'));

            $auth_pagination = $auth_log_list->appends(['form_search_date' => $_GET['form_search_date'], 'to_search_date'=> $_GET['to_search_date']])->render();

            $data['auth_pagination'] = $auth_pagination;
            $data['auth_log_list'] = $auth_log_list;

            
            

         }
        /*-------------------------/Get Request-----------------------*/
        else{
            $today = date('Y-m-d');
            $auth_log_list=\DB::table('auth_log')->where('auth_log.created_at','like',$today."%")
                            ->leftJoin('users','auth_log.auth_user_id','=','users.id')
                            ->select('auth_log.*','users.name','users.id')
                            ->orderBy('auth_log.created_at','desc')
                            ->paginate(10);
            $auth_log_list->setPath(url('/system-admin/auth-logs'));
            $auth_pagination = $auth_log_list->render();
            $data['auth_pagination'] = $auth_pagination;
            $data['auth_log_list'] = $auth_log_list;
        }
        $data['page_title'] = $this->page_title;
                
        return \View::make('dashboard.pages.system-admin.auth-log',$data);
    }



    /********************************************
    ## AllPushList 
    *********************************************/
    public function AllPushList(){

        $all_push_info=\DB::table('tbl_notification')
                ->where('notification_type', '!=','event')
                ->OrderBy('updated_at','desc')
                ->paginate(10);
        $all_push_info->setPath(url('/dashboard/push/list'));
        $push_pagination = $all_push_info->render();
        $data['push_pagination']=$push_pagination;
        $data['all_push_info'] = $all_push_info;    
        $data['page_title'] = $this->page_title;    
        return \View::make('dashboard.pages.all-push-list',$data);
    }


    /********************************************
    ## PushDelete
    *********************************************/
    public function PushDelete($notification_id){
        try{

            $push_info=\DB::table('tbl_notification')->where('notification_id',$notification_id)->get();
            if(isset($push_info)){

                $push_delete=\DB::table('tbl_notification')->where('notification_id',$notification_id)->delete();
                \App\System::EventLogWrite('delete,tbl_notification',json_encode($notification_id));
                return \Redirect::to('/dashboard/push/list')->with('message',"Push Deleted Successfully !");
            }else{
                return \Redirect::to('/dashboard/push/list')->with('errormessage',"Do not have push value !");  
            }
        }catch(\Exception $e){

            $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
            \App\System::ErrorLogWrite($message);

            return \Redirect::to('/dashboard/push/list')->with('message',"Info Already Exist !");
        }


    }



    /********************************************
    ## PushText 
    *********************************************/
    public function PushText(){

        if(isset($_REQUEST['tab']) && !empty($_REQUEST['tab'])){
            $tab = $_REQUEST['tab'];
        }else $tab = 'push_new_coupon';
        
        $data['tab']=$tab;
        $merchant_info=\DB::table('tbl_merchant')
			->where('merchant_status','1')
                        ->orderBy('tbl_merchant.created_at','desc')
                        ->get();
        $data['merchant_info'] = $merchant_info;           
        $data['page_title'] = $this->page_title;  
        return \View::make('dashboard.pages.push-text',$data);
    }


    /********************************************
    ## PushTextSubmit 
    *********************************************/
   public function PushTextSubmit(){

        try{

            $user_id=\Auth::user()->id;
            $now=date('Y-m-d H:i:s');

            $rules=array(
                'select_type' => 'required',
                'coupon_merchant_id' => 'required',
                'coupon_branch_id' => 'required',
                'message' => 'required',
                );

            $v=\Validator::make(\Request::all(), $rules);

            if($v->passes()){

                $select_type =\Request::input('select_type');
                $coupon_merchant_id =\Request::input('coupon_merchant_id');
                $coupon_branch_id =\Request::input('coupon_branch_id');
                $message =\Request::input('message');

                $select_coupon_info=\DB::table('tbl_coupon')
                    ->where('coupon_branch_id',$coupon_branch_id)
                    ->leftjoin('tbl_category','tbl_coupon.coupon_category_id','=','tbl_category.category_id')
                    ->leftjoin('tbl_sub_category','tbl_coupon.coupon_sub_category_id','=','tbl_sub_category.sub_category_id')
                    ->leftjoin('tbl_merchant','tbl_coupon.coupon_merchant_id','=','tbl_merchant.merchant_id')
                    ->leftjoin('tbl_branch','tbl_coupon.coupon_branch_id','=','tbl_branch.branch_id')
                    ->first();

                if(!empty($select_coupon_info)){

                    $total_success=0;
                    $total_failure=0;
                    $coupon_id=$select_coupon_info->coupon_id;
                    $merchant_name=$select_coupon_info->merchant_name;
                    $merchant_logo=$select_coupon_info->merchant_logo;
                    $branch_address=$select_coupon_info->branch_name;
                    $coupon_id=$select_coupon_info->coupon_id;
                    $coupon_discount_rate=$select_coupon_info->coupon_discount_rate;
                    $coupon_category_id=$select_coupon_info->coupon_category_id;
                    $coupon_sub_category_id=$select_coupon_info->coupon_sub_category_id;

                    if(!empty(\Request::file('featured_image'))){

                        $image = \Request::file('featured_image');
                        $img_location=$image->getRealPath();
                        $img_ext=$image->getClientOriginalExtension();
                        $push_featured_image=\App\Admin::PushImageUpload($img_location, $coupon_id, $img_ext);
                    }
                    else{
                        $push_featured_image='';
                    }

                    if($select_type == 'follow'){

                        $follow_user_info=\DB::table('tbl_follow_activity')
                        ->where('tbl_follow_activity.activity_type','follow')
                        ->where('tbl_follow_activity.activity_list_status','1')
                        ->where('tbl_follow_activity.merchant_or_coupon_id',$coupon_merchant_id)
                        ->where('tbl_follow_activity.activity_list_status','1')
                        ->leftjoin('users','tbl_follow_activity.activity_user_id','=','users.id')
                        ->where('users.status','1')
                        ->whereIn('users.user_platform', ['android','ios'])
                        ->get();

                        if(!empty($follow_user_info) && count($follow_user_info)>0){
                            foreach ($follow_user_info as $key => $value) {
                                $push_token=$value->push_token;
                                $platform_type=$value->user_platform;

                                $get_all_value=\App\Api::SendMessagePush($message, $select_type,$merchant_name, $merchant_logo, $branch_address,$coupon_id, $coupon_discount_rate,$push_token, $platform_type, $push_featured_image);
				$get_all =json_decode($get_all_value, true);

                                if($get_all['success'] == '1'){
                                    $total_success=$total_success+1;
                                }
                                if($get_all['failure'] == '1'){
                                    $total_failure=$total_failure+1;
                                }


                            }

                        }else{
                            return \Redirect::back()->with('errormessage',"There are no followers.");
                        }

                    }elseif($select_type == 'category'){

                        $old_user_info=\DB::table('tbl_coupon_transaction')
                            ->leftjoin('tbl_coupon','tbl_coupon_transaction.coupon_id','=','tbl_coupon.coupon_id')
                            ->leftjoin('users','tbl_coupon_transaction.customer_id','=','users.id')
                            ->where('users.status','1')
                            ->whereIn('users.user_platform', ['android','ios'])
                            ->where('tbl_coupon.coupon_sub_category_id',$coupon_sub_category_id)
                            ->groupBy('tbl_coupon_transaction.customer_id')
                            ->get();


                        if(!empty($old_user_info) && count($old_user_info)>0){
                            foreach ($old_user_info as $key => $value) {
                                $push_token=$value->push_token;
                                $platform_type=$value->user_platform;

                                $get_all_value=\App\Api::SendMessagePush($message, $select_type,$merchant_name, $merchant_logo, $branch_address,$coupon_id, $coupon_discount_rate, $push_token, $platform_type, $push_featured_image);
                                $get_all =json_decode($get_all_value, true);

                                if($get_all['success'] == '1'){
                                    $total_success=$total_success+1;
                                }
                                if($get_all['failure'] == '1'){
                                    $total_failure=$total_failure+1;
                                }
                            }

                        }else{
                            return \Redirect::back()->with('errormessage',"There are no users.");
                        }

                    }elseif($select_type == 'all'){

                        $all_user_info=\DB::table('users')
                                    ->where('status','1')
                                    ->whereIn('users.user_platform', ['android','ios'])
                                    ->get();

                        if(!empty($all_user_info) && count($all_user_info)>0){
                            foreach ($all_user_info as $key => $value) {
                                $push_token=$value->push_token;
                                $platform_type=$value->user_platform;

                                $get_all_value=\App\Api::SendMessagePush($message, $select_type,$merchant_name, $merchant_logo, $branch_address,$coupon_id, $coupon_discount_rate, $push_token, $platform_type, $push_featured_image);
                                $get_all =json_decode($get_all_value, true);

                                if($get_all['success'] == '1'){
                                    $total_success=$total_success+1;
                                }
                                if($get_all['failure'] == '1'){
                                    $total_failure=$total_failure+1;
                                }
                            }

                        }else{
                            return \Redirect::back()->with('errormessage',"There are no users.");
                        }
                    }

                    $push_notification_date=array(
                        'notification_type' => 'new_coupon_message',
                        'coupon_notification_type' =>$select_type,
                        'notification_coupon_id' => $coupon_id,
			'notification_user_id' => 'all',
                        'platform_type' => 'all',
                        'message' => $message,
                        'featured_image' => $push_featured_image,
			'notification_status' => '2',
                        'created_by' => $user_id,
                        'updated_by' => $user_id,
                        'created_at' => $now,
                        'updated_at' => $now,
                        );


                    $coupon_highlight_save=\DB::table('tbl_notification')->insert($push_notification_date);
                    \App\System::EventLogWrite('insert,tbl_notification',json_encode($push_notification_date));

                    $response["success"]= [
                            "message_type"=> $select_type,
                            "coupon_id"=> $coupon_id,
                            "message"=> 'Notification Push Successfully',
                            "total_success"=> $total_success,
                            "total_failure"=>$total_failure
                        ];

                    \App\Api::ResponseLogWrite('Text Message Push',json_encode($response));


                    return \Redirect::to('/push/text')->with('message',"Notification Push Successfully");

                }else{
                    return \Redirect::back()->with('errormessage',"Invalid Coupon");
                }

            }else return \Redirect::to('/push/text')->withErrors($v->messages());

        }catch(\Exception $e){

            $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
            \App\System::ErrorLogWrite($message);

            return \Redirect::to('/push/text')->with('message',"Info Already Exist !");
        }


    }


    /********************************************
    ## PushTextAllApp 
    *********************************************/
    public function PushTextAllApp(){

        try{

            $user_id=\Auth::user()->id;
            $now=date('Y-m-d H:i:s');

            $rules=array(
                'select_type' => 'required',
                'coupon_merchant_id' => 'required',
                'coupon_branch_id' => 'required',
                'message' => 'required',
                );

            $v=\Validator::make(\Request::all(), $rules);

            if($v->passes()){

                $select_type =\Request::input('select_type');
                $coupon_merchant_id =\Request::input('coupon_merchant_id');
                $coupon_branch_id =\Request::input('coupon_branch_id');
                $message =\Request::input('message');

                $select_coupon_info=\DB::table('tbl_coupon')
                    ->where('coupon_branch_id',$coupon_branch_id)
                    ->leftjoin('tbl_category','tbl_coupon.coupon_category_id','=','tbl_category.category_id')
                    ->leftjoin('tbl_sub_category','tbl_coupon.coupon_sub_category_id','=','tbl_sub_category.sub_category_id')
                    ->leftjoin('tbl_merchant','tbl_coupon.coupon_merchant_id','=','tbl_merchant.merchant_id')
                    ->leftjoin('tbl_branch','tbl_coupon.coupon_branch_id','=','tbl_branch.branch_id')
                    ->first();

                if(!empty($select_coupon_info)){

                    $total_success=0;
                    $total_failure=0;
                    $coupon_id=$select_coupon_info->coupon_id;
                    $merchant_name=$select_coupon_info->merchant_name;
                    $merchant_logo=$select_coupon_info->merchant_logo;
                    $branch_address=$select_coupon_info->branch_name;
                    $coupon_id=$select_coupon_info->coupon_id;
                    $coupon_discount_rate=$select_coupon_info->coupon_discount_rate;
                    $coupon_category_id=$select_coupon_info->coupon_category_id;
                    $coupon_sub_category_id=$select_coupon_info->coupon_sub_category_id;

                    

                    if(!empty(\Request::file('featured_image'))){

                        $image = \Request::file('featured_image');
                        $img_location=$image->getRealPath();
                        $img_ext=$image->getClientOriginalExtension();
                        $push_featured_image=\App\Admin::PushImageUpload($img_location, $coupon_id, $img_ext);
                    }
                    else{
                        $push_featured_image='';
                    }

                	if($select_type =="android"){
                    		$get_all_value=\App\Api::SendMessagePushAllApp($message, $merchant_name, $merchant_logo, $coupon_id, $coupon_discount_rate, $select_type, $push_featured_image);

                	}elseif($select_type =="ios"){
                    		$get_all_value=\App\Api::SendMessagePushAllApp($message, $merchant_name, $merchant_logo, $coupon_id, $coupon_discount_rate, $select_type, $push_featured_image);

                	}

                    $push_notification_date=array(
                        'notification_type' => 'new_coupon_message',
                        'coupon_notification_type' =>'all_app',
                        'notification_coupon_id' => $coupon_id,
			'notification_user_id' => 'all',
                        'platform_type' => $select_type, 
                        'message' => $message,
                        'featured_image' => $push_featured_image,
			'notification_status' => '2',
                        'created_by' => $user_id,
                        'updated_by' => $user_id,
                        'created_at' => $now,
                        'updated_at' => $now,
                        );


                    $coupon_highlight_save=\DB::table('tbl_notification')->insert($push_notification_date);
                    \App\System::EventLogWrite('insert,tbl_notification',json_encode($push_notification_date));


                    $response["success"]= [
                            "coupon_id"=> $coupon_id,
			    "app_type"=> $select_type,
                            "message"=> 'All App Message Push Successfully',
                        ];

                    \App\Api::ResponseLogWrite('All App Message Push',json_encode($response));


                    return \Redirect::to('/push/text?tab=push_new_coupon_to_all')->with('message',"All App Message Push Successfully");

                }else{
                    return \Redirect::back()->with('errormessage',"Invalid Coupon");
                }

            }else return \Redirect::to('/push/text?tab=push_new_coupon_to_all')->withErrors($v->messages());

        }catch(\Exception $e){

            $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
            \App\System::ErrorLogWrite($message);

            return \Redirect::to('/push/text?tab=push_new_coupon_to_all')->with('message',"Info Already Exist !");
        }


    }



    /********************************************
    ## PushGreetings 
    *********************************************/
    public function PushGreetings(){



            $user_id=\Auth::user()->id;
            $now=date('Y-m-d H:i:s');

            $rules=array(
                'select_type' => 'required',
		'title' => 'required',
                'message' => 'required',
                );

            $v=\Validator::make(\Request::all(), $rules);

            if($v->passes()){

                $select_type =\Request::input('select_type');
                $title =\Request::input('title');
                $message =\Request::input('message');
                $push_type="greetings";

                if(!empty(\Request::input('url'))){

		     $url =\Request::input('url');
                }
                else{
                    $url='';
                }

            	try{
                    if(!empty(\Request::file('featured_image'))){

                        $image = \Request::file('featured_image');
                        $img_location=$image->getRealPath();
                        $img_ext=$image->getClientOriginalExtension();
                        $push_featured_image=\App\Admin::PushImageUpload($img_location, $push_type, $img_ext);
                    }
                    else{
                        $push_featured_image='';
                    }

                    if($select_type =="android"){

                        $get_all_android_value=\App\Api::SendGreetingsMessagePush($title, $message, $select_type, $push_featured_image, $url);
                    }elseif($select_type =="ios"){
                        $get_all_ios_value=\App\Api::SendGreetingsMessagePush($title, $message, $select_type, $push_featured_image, $url);

                    }

                        $push_notification_date=array(
                            'notification_type' => 'new_coupon_message',
                            'coupon_notification_type' =>'all_app',
                            'platform_type' => $select_type,
                            'message' => $message,
    			            'notification_status' => '2',
                            'created_by' => $user_id,
                            'updated_by' => $user_id,
                            'created_at' => $now,
                            'updated_at' => $now,
                            );


                        $coupon_highlight_save=\DB::table('tbl_notification')->insert($push_notification_date);
                        \App\System::EventLogWrite('insert,tbl_notification',json_encode($push_notification_date));

                        $response["success"]= [
                                "message_type"=> 'Greetings Message',
                                "message"=> 'Greetings message push Successfully',
                            ];

                        \App\Api::ResponseLogWrite('Text Message Push',json_encode($response));

                        return \Redirect::to('/push/text?tab=greetings')->with('message',"Greetings message  push Successfully");

                }catch(\Exception $e){

                    $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
                    \App\System::ErrorLogWrite($message);

                    return \Redirect::to('/push/text?tab=greetings')->with('message',"Info Already Exist !");
                }

            }else return \Redirect::to('/push/text?tab=greetings')->withErrors($v->messages());



    }




    /********************************************
    ## EventNotificationPush
    *********************************************/
    public function EventNotificationPush(){

        try{
            $event_date=date('Y-m-d');
            $user_id=\Auth::user()->id;


            $event_info=\DB::table('tbl_notification')
                ->leftjoin('users','tbl_notification.notification_user_id','=','users.id')
                ->leftjoin('tbl_merchant','tbl_notification.notification_merchant_id','=','tbl_merchant.merchant_id')
                ->where('tbl_notification.notification_type','event')
                ->where('tbl_notification.notification_status','1')
                ->where('tbl_notification.event_date',$event_date)
                ->orderBy('tbl_notification.notification_id','desc')
                ->get();



            if(!empty($event_info)){

                $total_success=0;
                $total_failure=0;
                foreach ($event_info as $key => $value) {


                    $event_id=$value->notification_id;
                    $message=$value->message;
                    $title=$value->title;
                    $merchant_id=$value->notification_merchant_id;
                    $merchant_name=$value->merchant_name;
                    $merchant_logo=$value->merchant_logo;
                    $push_token=$value->push_token;
                    $platform_type=$value->user_platform;

                    $get_all_value=\App\Api::SendEventMessagePush($title, $message, $merchant_id, $merchant_logo, $push_token, $platform_type);

                    $coupon_highlight_save=\DB::table('tbl_notification')->where('notification_id',$event_id)->update(array("notification_status"=>'2'));
                    \App\System::EventLogWrite('update,tbl_notification',json_encode($value));

                    $get_all =json_decode($get_all_value, true);

                    if($get_all['success'] == '1'){
                        $total_success=$total_success+1;
                    }
                    if($get_all['failure'] == '1'){
                        $total_failure=$total_failure+1;
                    }
                }



                $response["success"]= [
                        "message_type"=> 'event_push',
                        "message"=> 'Event Notification Push Successfully',
                        "total_success"=> $total_success,
                        "total_failure"=>$total_failure
                    ];

                \App\Api::ResponseLogWrite('Event Notification Push Successfully',json_encode($response));
                return \Redirect::to('/')->with('message',"Event Notification Push Successfully.");


            }else{
                return \Redirect::to('/')->with('errormessage',"Invalid Coupon");
            }



        }catch(\Exception $e){

            $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
            \App\System::ErrorLogWrite($message);

            return \Redirect::to('/push/text?tab=push_new_coupon_to_all')->with('message',"Info Already Exist !");
        }


    }






###################### End Logs #############################




    /********************************************
    ## TestPage 
    *********************************************/
    public function TestPage(){  
        $data['page_title'] = $this->page_title;
        return \View::make('pages.sss',$data);
    }

####################### End #############################




}
