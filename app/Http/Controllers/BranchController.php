<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BranchController extends Controller
{
    public function __construct(){
       
        $this->page_title = \Request::route()->getName();
        \App\System::AccessLogWrite();
    }

    /********************************************
    ## BranchDashboard 
    *********************************************/
    public function BranchDashboard(){
    
        $data['page_title'] = $this->page_title;    
        return \View::make('dashboard.pages.branch.dashboard-branch',$data);
    }



    /********************************************
    ##BranchAllTransactionList 
    *********************************************/
    public function BranchAllTransactionList(){
        $total_discount=0;
        $total_commission=0;
        $total_amount=0;
        $total_coupon_buy_price=0;
        $now=date('Y-m-d');
        $user=\Auth::user()->id;
        $branch_info=\db::table('tbl_branch')->where('branch_user_id',$user)->first();
        if(!empty($branch_info)){
            $branch_id=$branch_info->branch_id;
            if(isset($_GET['search_from']) && isset($_GET['search_to'])){

                $search_from = $_GET['search_from'].' 00:00:00';
                $search_to = $_GET['search_to'].' 23:59:59';

                $all_coupon_transaction_info=\DB::table('tbl_coupon_transaction')
                    ->leftjoin('tbl_coupon','tbl_coupon_transaction.coupon_id','=','tbl_coupon.coupon_id')
                    ->where('tbl_coupon_transaction.coupon_status','!=','-1')
                    ->whereBetween('tbl_coupon_transaction.updated_at',[$search_from,$search_to])
                    ->where('tbl_coupon_transaction.transaction_branch_id',$branch_id)
                    ->OrderBy('tbl_coupon_transaction.updated_at','desc')
                    ->get();

                $coupon_transaction_info=\DB::table('tbl_coupon_transaction')
                    ->leftjoin('tbl_coupon','tbl_coupon_transaction.coupon_id','=','tbl_coupon.coupon_id')
                    ->where('tbl_coupon_transaction.coupon_status','!=','-1')
                    ->whereBetween('tbl_coupon_transaction.updated_at',[$search_from,$search_to])
                    ->where('tbl_coupon_transaction.transaction_branch_id',$branch_id)
                    ->OrderBy('tbl_coupon_transaction.updated_at','desc')
                    ->paginate(5);

                $coupon_transaction_info->setPath(url('/dashboard/branch/coupon-transaction/list'));

                $coupon_transaction_pagination = $coupon_transaction_info->appends(['search_from' => $_GET['search_from'], 'search_to'=> $_GET['search_to']])->render();


            }else{
                $search_from=date('Y-m-d').' 00:00:00';
                $search_to = date('Y-m-d').' 23:59:59';
                $all_coupon_transaction_info=\DB::table('tbl_coupon_transaction') 
                        ->leftjoin('tbl_coupon','tbl_coupon_transaction.coupon_id','=','tbl_coupon.coupon_id')
                        ->where('tbl_coupon_transaction.coupon_status','!=','-1')
                        ->whereBetween('tbl_coupon_transaction.updated_at',[$search_from,$search_to])
                    	->where('tbl_coupon_transaction.transaction_branch_id',$branch_id)
                        ->OrderBy('tbl_coupon_transaction.updated_at','desc')
                        ->get();
     
                $coupon_transaction_info=\DB::table('tbl_coupon_transaction') 
                        ->leftjoin('tbl_coupon','tbl_coupon_transaction.coupon_id','=','tbl_coupon.coupon_id')
                        ->where('tbl_coupon_transaction.coupon_status','!=','-1')
                        ->whereBetween('tbl_coupon_transaction.updated_at',[$search_from,$search_to])
                    	->where('tbl_coupon_transaction.transaction_branch_id',$branch_id)
                        ->OrderBy('tbl_coupon_transaction.updated_at','desc')
                        ->paginate(5);
                $coupon_transaction_info->setPath(url('/dashboard/branch/coupon-transaction/list'));
                $coupon_transaction_pagination = $coupon_transaction_info->render();
            }

            if(!empty($all_coupon_transaction_info)){
                foreach ($all_coupon_transaction_info as $key => $list) {
                    $total_discount=$total_discount+$list->coupon_discount_amount;
                    $total_commission=$total_commission+$list->coupon_commission_amount;
                    $total_amount=$total_amount+$list->coupon_shopping_amount;
                    $total_coupon_buy_price=$total_coupon_buy_price+$list->coupon_buy_price;
                }
            }

            $data['total_discount']=$total_discount;
            $data['total_commission']=$total_commission;
            $data['total_amount']=$total_amount;
            $data['total_coupon_buy_price']=$total_coupon_buy_price;
            $data['coupon_transaction_pagination']=$coupon_transaction_pagination;
            $data['coupon_transaction_info']=$coupon_transaction_info;
            $data['page_title'] = $this->page_title;    
            return \View::make('dashboard.pages.branch.branch-all-transaction-list',$data);
        }else{
            return \Redirect::back()->with('errormessage','Something wrong !!!');
        }
    }



    /********************************************
    ##BranchAllConfirmTransactionList
    *********************************************/
    public function BranchAllConfirmTransactionList(){
        $total_discount=0;
        $total_commission=0;
        $total_amount=0;
        $total_coupon_buy_price=0;
        $now=date('Y-m-d');
        $user=\Auth::user()->id;
        $branch_info=\db::table('tbl_branch')->where('branch_user_id',$user)->first();
        if(!empty($branch_info)){

            $branch_id=$branch_info->branch_id;
            if(isset($_GET['search_from']) && isset($_GET['search_to'])){

                $search_from = $_GET['search_from'].' 00:00:00';
                $search_to = $_GET['search_to'].' 23:59:59';

                $all_coupon_transaction_info=\DB::table('tbl_coupon_transaction')
                    ->leftjoin('tbl_coupon','tbl_coupon_transaction.coupon_id','=','tbl_coupon.coupon_id')
                    ->whereBetween('tbl_coupon_transaction.updated_at',[$search_from,$search_to])
                    ->where('tbl_coupon_transaction.transaction_branch_id',$branch_id)
                    ->where('tbl_coupon_transaction.coupon_status','2')
                    ->OrderBy('tbl_coupon_transaction.updated_at','desc')
                    ->get();

                $coupon_transaction_info=\DB::table('tbl_coupon_transaction')
                    ->leftjoin('tbl_coupon','tbl_coupon_transaction.coupon_id','=','tbl_coupon.coupon_id')
                    ->whereBetween('tbl_coupon_transaction.updated_at',[$search_from,$search_to])
                    ->where('tbl_coupon_transaction.coupon_status','2')
                    ->where('tbl_coupon_transaction.transaction_branch_id',$branch_id)
                    ->OrderBy('tbl_coupon_transaction.updated_at','desc')
                    ->paginate(10);

                $coupon_transaction_info->setPath(url('/dashboard/branch/coupon-transaction/list'));

                $coupon_transaction_pagination = $coupon_transaction_info->appends(['search_from' => $_GET['search_from'], 'search_to'=> $_GET['search_to']])->render();


            }else{
                $search_from=date('Y-m-d').' 00:00:00';
                $search_to = date('Y-m-d').' 23:59:59';
                $all_coupon_transaction_info=\DB::table('tbl_coupon_transaction') 
                        ->leftjoin('tbl_coupon','tbl_coupon_transaction.coupon_id','=','tbl_coupon.coupon_id')
                        ->whereBetween('tbl_coupon_transaction.updated_at',[$search_from,$search_to])
                    	->where('tbl_coupon_transaction.transaction_branch_id',$branch_id)
                    	->where('tbl_coupon_transaction.coupon_status','2')
                        ->OrderBy('tbl_coupon_transaction.updated_at','desc')
                        ->get();
     
                $coupon_transaction_info=\DB::table('tbl_coupon_transaction') 
                        ->leftjoin('tbl_coupon','tbl_coupon_transaction.coupon_id','=','tbl_coupon.coupon_id')
                        ->whereBetween('tbl_coupon_transaction.updated_at',[$search_from,$search_to])
                    	->where('tbl_coupon_transaction.transaction_branch_id',$branch_id)
                    	->where('tbl_coupon_transaction.coupon_status','2')
                        ->OrderBy('tbl_coupon_transaction.updated_at','desc')
                        ->paginate(10);
                $coupon_transaction_info->setPath(url('/dashboard/branch/coupon-transaction/list'));
                $coupon_transaction_pagination = $coupon_transaction_info->render();
            }

            if(!empty($all_coupon_transaction_info)){
                foreach ($all_coupon_transaction_info as $key => $list) {
                    $total_discount=$total_discount+$list->coupon_discount_amount;
                    $total_commission=$total_commission+$list->coupon_commission_amount;
                    $total_amount=$total_amount+$list->coupon_shopping_amount;
                    $total_coupon_buy_price=$total_coupon_buy_price+$list->coupon_buy_price;
                }
            }

            $data['total_discount']=$total_discount;
            $data['total_commission']=$total_commission;
            $data['total_amount']=$total_amount;
            $data['total_coupon_buy_price']=$total_coupon_buy_price;
            $data['coupon_transaction_pagination']=$coupon_transaction_pagination;
            $data['coupon_transaction_info']=$coupon_transaction_info;
            $data['page_title'] = $this->page_title;    
            return \View::make('dashboard.pages.branch.branch-all-confirm-transaction-list',$data);
        }else{
            return \Redirect::back()->with('errormessage','Something wrong !!!');
        }
    }


    /********************************************
    ## BranchSummery
    *********************************************/

    public function BranchSummery(){

        $today = date('Y-m-d');
        $total_sell_coupon=0;
        $total_sell_confirm_coupon=0;
        $total_sell_coupon_amount=0;
        $total_shopping_amount=0;
        $total_discount_amount=0;
        $total_commisssion_amount=0;

        $user=\Auth::user()->id;
        $branch_info=\db::table('tbl_branch')->where('branch_user_id',$user)->first();
        if(!empty($branch_info)){

            $branch_id=$branch_info->branch_id;
            if(isset($_GET['search_from']) && isset($_GET['search_to']) || isset($_GET['branch_name'])){

                $search_from = $_GET['search_from'].' 00:00:00';
                $search_to = $_GET['search_to'].' 23:59:59';

                $total_initial_transaction_info=\DB::table('tbl_coupon_transaction')
                    ->leftjoin('tbl_coupon','tbl_coupon_transaction.coupon_id','=','tbl_coupon.coupon_id')
                    ->where('tbl_coupon_transaction.coupon_status','!=','-1')
                    ->whereBetween('tbl_coupon_transaction.updated_at',array($search_from,$search_to))
                    ->where('tbl_coupon_transaction.transaction_branch_id',$branch_id)
                    ->get();

                $total_sell_coupon=count($total_initial_transaction_info);

                if(!empty($total_initial_transaction_info) && count($total_initial_transaction_info)>0){
                    foreach ($total_initial_transaction_info as $key => $list) {
                        $total_sell_coupon_amount=$total_sell_coupon_amount+$list->coupon_buy_price;
                    }
                 }

                $total_final_transaction_info=\DB::table('tbl_coupon_transaction')
                    ->leftjoin('tbl_coupon','tbl_coupon_transaction.coupon_id','=','tbl_coupon.coupon_id')
                    ->whereBetween('tbl_coupon_transaction.updated_at',array($search_from,$search_to))
                    ->where('tbl_coupon_transaction.transaction_branch_id',$branch_id)
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
                    ->where('tbl_coupon_transaction.coupon_status','!=','-1')
                    ->whereBetween('tbl_coupon_transaction.updated_at',array($search_from,$search_to))
                    ->where('tbl_coupon_transaction.transaction_branch_id',$branch_id)
                    ->get();

                $total_sell_coupon=count($total_initial_transaction_info);

                if(!empty($total_initial_transaction_info) && count($total_initial_transaction_info)>0){
                    foreach ($total_initial_transaction_info as $key => $list) {
                        $total_sell_coupon_amount=$total_sell_coupon_amount+$list->coupon_buy_price;
                    }
                 }

                $total_final_transaction_info=\DB::table('tbl_coupon_transaction')
                    ->leftjoin('tbl_coupon','tbl_coupon_transaction.coupon_id','=','tbl_coupon.coupon_id')
                    ->whereBetween('tbl_coupon_transaction.updated_at',array($search_from,$search_to))
                    ->where('tbl_coupon_transaction.transaction_branch_id',$branch_id)
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
            return \View::make('dashboard.pages.branch.branch-summery',$data);
        }else{
            return \Redirect::back()->with('errormessage','Something wrong !!!');
        }
    }



}
