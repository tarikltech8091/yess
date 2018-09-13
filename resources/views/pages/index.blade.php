@extends('layout.master')
@section('content')

        <!-- –––––––––––––––[ PAGE CONTENT ]––––––––––––––– -->
        <main id="mainContent" class="main-content">
            <div class="page-container ptb-10">
                <div id="printMainSlider">
                    <div class="container">
                        <div class="section deals-header-area ptb-30">
                            <div class="row row-tb-20">

                             <div class="col-md-12">

                                    <div class="wrapper coupon_home_slider">
                                        <div id="ei-sliders" class="ei-slider">
                                            <ul class="ei-slider-large">
                                            @if(!empty($home_slider_info) && count($home_slider_info)>0)
                                                @foreach($home_slider_info as $key=>$list)
                                                <?php 
                                                    $meta_field_value=$list->setting_meta_field_value; 
                                                    $home_slider_image=unserialize($meta_field_value);
                                                    $home_slider_main_image=$home_slider_image[0];
                                                    $merchant=$home_slider_image[2];
                                                ?>
                                                <li>
                                                    <a href="{{url('merchant/branch-view/page/mid-'.$merchant)}}">
                                                        <img src="{{asset('assets/images/slider/'.$home_slider_main_image)}}" alt="image0{{$key}}" style="width:100%;" />
                                                    </a>
                                                </li>
                                            @endforeach
                                            @endif

                                            </ul><!-- ei-slider-large -->
                                            <ul class="ei-slider-thumbs">
                                                <li class="ei-slider-element">Current</li>
                                                @if(!empty($home_slider_info) && count($home_slider_info)>0)
                                                @foreach($home_slider_info as $key2=>$value)
                                                <?php 
                                                    $meta_field_value=$value->setting_meta_field_value; 
                                                    $home_slider_image=unserialize($meta_field_value);
                                                    $home_slider_preview_image=$home_slider_image[1];
                                                ?>
                                                <li><a href="#">Slide 6</a>
                                                <img src="{{asset('assets/images/slider/popup/'.$home_slider_preview_image)}}" alt="thumb0{{$key2}}" />
                                                </li>
                                                @endforeach
                                                @endif

                                            </ul><!-- ei-slider-thumbs -->
                                        </div><!-- ei-slider -->
                                    </div>

                                <div class="clear"></div>

                                </div>
                            </div>
                        </div>

                        <div class="section explain-process-area ptb-30">
                            <div class="row row-rl-10">
                                <div class="col-md-4">
                                    <div class="item panel prl-15 ptb-20">
                                        <div class="row row-rl-5 row-xs-cell">
                                            <div class="col-xs-4 valign-middle">
                                                <img class="pr-10" src="{{asset('main-assets/images/icons/tablet.png')}}" alt="">
                                            </div>
                                            <div class="col-xs-8">
                                                <h5 class="mb-10 pt-5">Deals & Coupons</h5>
                                                <p class="color-mid"></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="item panel prl-15 ptb-20">
                                        <div class="row row-rl-5 row-xs-cell">
                                            <div class="col-xs-4 valign-middle">
                                                <img class="pr-10" src="{{asset('main-assets/images/icons/online-shop-6.png')}}" alt="">
                                            </div>
                                            <div class="col-xs-8">
                                                <h5 class="mb-10 pt-5">Find Best Offers</h5>
                                                <p class="color-mid"></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="item panel prl-15 ptb-20">
                                        <div class="row row-rl-5 row-xs-cell">
                                            <div class="col-xs-4 valign-middle">
                                                <img class="pr-10" src="{{asset('main-assets/images/icons/money.png')}}" alt="">
                                            </div>
                                            <div class="col-xs-8">
                                                <h5 class="mb-10 pt-5">Save Money</h5>
                                                <p class="color-mid"></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <section class="section latest-deals-area ptb-10">

                            @if(!empty($all_subcategory_info) && count($all_subcategory_info)>0)
                            @foreach($all_subcategory_info as $key =>$value)

                            <?php

                                $all_branch_info=\DB::table('tbl_coupon')
                                        ->leftjoin('tbl_merchant','tbl_coupon.coupon_merchant_id','=','tbl_merchant.merchant_id')
                                        ->where('tbl_coupon.coupon_sub_category_id',$value->sub_category_id)
                                        ->where('tbl_merchant.merchant_rank','=','1')
                                        ->where('tbl_coupon.coupons_status','1')
                                        ->where('tbl_merchant.merchant_status','1')
                                        ->where('tbl_coupon.coupon_closing_date','>=',date('Y-m-d'))
                                        ->select(['tbl_coupon.coupon_merchant_id', DB::raw('MAX(tbl_coupon.coupon_discount_rate) AS coupon_discount_rate') , \DB::raw('count(*) as total')])
                                        ->groupBy('tbl_coupon.coupon_merchant_id')
                                        ->limit(4)
                                        ->get();

                                $sidebar_icon=array('fa fa-cutlery','fa fa-desktop','fa fa-child','fa fa-bars','fa fa-eye','fa fa-list','fa fa-child','fa fa-female','fa fa-desktop','fa fa-bars','fa fa-eye','fa fa-female','fa fa-list');

                            ?>
                            <nav class="bs-docs-sidebar hidden">
                                <ul id="sidebar" class="nav nav-stacked fixed">
                                @if(!empty($all_subcategory_info) && count($all_subcategory_info)>0)
                                    @foreach($all_subcategory_info as $key2 =>$category)
                                    <li><a href="#couponscroll_{{$key2+1}}">
                                            <i class="{{$sidebar_icon[$key2]}}"></i>
                                            <span style="color:#00a2ff;"> {{$category->sub_category_name}}</span>
                                        </a>
                                    </li>
                                    @endforeach
                                @endif

                                </ul>
                            </nav>


                            @if((($key+1)%2) == 1)
                            <div class="row row-masnory row-tb-10" id="couponscroll_{{$key+1}}">
                                <div class="col-md-12">
                                    <header class="panel ptb-15 prl-20 pos-r mb-30">
                                        <span><strong> {{$value->sub_category_name}} </strong></span>
                                        <div class="right-10 pos-tb-center">
                                            <a class="btn btn-o btn-xs pos-a right-10 pos-tb-center" href="{{url('all-merchant/coupon/page/cid-/'.$value->category_id.'/subcid-'.$value->sub_category_id)}}">Views All</a>
                                        </div>
                                    </header>
                                </div>
                                <div class="col-md-4">
                                    <div>
                                        <img width="100%" src="{{asset($value->sub_category_featured_image)}}">
                                    </div>
                                </div>
                            
                                <div class="col-md-8" style="padding-right:1px;">
                                    @if(!empty($all_branch_info) && count($all_branch_info)>0)
                                    @foreach($all_branch_info as $key2 =>$list)

                                    <?php 

                                        $max_discount_branch=\DB::table('tbl_coupon')
                                            ->where('tbl_coupon.coupon_merchant_id',$list->coupon_merchant_id)
                                            ->leftjoin('tbl_merchant','tbl_coupon.coupon_merchant_id','=','tbl_merchant.merchant_id')
                                            ->first();
                                        if(\Auth::check()){
                                            $wish_list=\DB::table('tbl_follow_activity')
                                                        ->where('activity_type','wish_list')
                                                        ->where('merchant_or_coupon_id',$max_discount_branch->coupon_id)
                                                        ->where('activity_user_id',\Auth::user()->id)
                                                        ->first();
                                        }
                                        else{
                                            $wish_list='';
                                        }
                                    ?>


                                        <div class="col-sm-6 col-lg-6">
                                            <div class="deal-single panel">
                                            

                                                <figure data-id="{{url('/merchant/branch-view/page/mid-'.$max_discount_branch->merchant_id)}}" class="deal-thumbnail embed-responsive embed-responsive-new" data-bg-img="{{asset($max_discount_branch->coupon_featured_image)}}">

                                                    <a class="index_discount" href="{{url('single-page/coupon_id-'.$max_discount_branch->coupon_id)}}">
                                                        <div class="label-discount left-20 top-15">{{$max_discount_branch->coupon_discount_rate}}%</div>
                                                    </a>

                                                    <ul class="deal-actions top-15 right-20">
                                                        <li class="like-deal">
                                                            <span>
                                                                <a class="index_wish" data-id="{{$max_discount_branch->coupon_id}}" data-status="1">
                                                                @if(!empty($wish_list))
                                                                    <p style="color:red;"><i class="fa fa-heart"></i></p>
                                                                @else
                                                                    <p id="active_wish_{{$max_discount_branch->coupon_id}}"><i class="fa fa-heart"></i></p>
                                                                @endif
                                                                </a>
                                                            </span>
                                                        </li>
                                                        <li class="share-btn index_social_link">
                                                            <div class="share-tooltip fade">
                                                                <a href="javascript:void(0)" onclick="javascript:genericSocialShare('http://www.facebook.com/sharer.php?u=yess.com.bd/merchant/branch-view/page/mid-{{$max_discount_branch->coupon_merchant_id}}')"><i class="fa fa-facebook"></i></a>
                                                                <a target="_blank" href="#"><i class="fa fa-twitter"></i></a>
                                                                <a target="_blank" href="#"><i class="fa fa-google-plus"></i></a>
                                                                <a target="_blank" href="#"><i class="fa fa-pinterest"></i></a>
                                                            </div>
                                                            <span><i class="fa fa-share-alt"></i></span>
                                                        </li>
                                                        <a data-id="{{$max_discount_branch->coupon_id}}" class="index_quick_view text_none coupon_details_show tooltips">
                                                            <li>
                                                                <span>
                                                                    <i class="fa fa-external-link"></i>
                                                                </span>
                                                            </li>
                                                        </a> 
                                                    </ul>
                                                    
                                                    <div class="time-left bottom-15 right-20 font-md-14">
                                                        <span class="countdown_div">
                                                            <span class="t-uppercase btn btn-success" data-countdown="{{$max_discount_branch->coupon_closing_date}} 23:59:59">
                                                            <i class="ico fa fa-clock-o mr-10"></i>  
                                                            </span>
                                                        </span>
                                                    </div>

                                                    <div class="deal-store-logo" style="">
                                                        <a href="{{url('/merchant/branch-view/page/mid-'.$max_discount_branch->merchant_id)}}">
                                                            <img src="{{asset('/assets/images/merchant/'.$max_discount_branch->merchant_logo)}}" alt="">
                                                        </a>
                                                    </div>
                                                </figure>
                                            </div>
                                        </div>

                                    @endforeach
                                    @else
                                    <div><strong>There are no Coupon</strong></div>
                                    @endif
                                </div>
                                
                            </div>


                            @elseif((($key+1)%2) == 0)
                            
                            <div class="row row-masnory row-tb-10" id="couponscroll_{{$key+1}}">
                                <div class="col-md-12">
                                    <header class="panel ptb-15 prl-20 pos-r mb-30">
                                        <span><strong> {{$value->sub_category_name}} </strong></span>
                                        <div class="right-10 pos-tb-center">
                                            <a class="btn btn-o btn-xs pos-a right-10 pos-tb-center" href="{{url('all-merchant/coupon/page/cid-/'.$value->category_id.'/subcid-'.$value->sub_category_id)}}">Views All</a>
                                        </div>
                                    </header>
                                </div>
                                <div class="col-md-8" style="margin-left:-16px;">

                                @if(!empty($all_branch_info) && count($all_branch_info)>0)
                                @foreach($all_branch_info as $key2 =>$list)

                                <?php

                                    $max_discount_branch=\DB::table('tbl_coupon')
                                        ->where('tbl_coupon.coupon_merchant_id',$list->coupon_merchant_id)
                                        ->leftjoin('tbl_merchant','tbl_coupon.coupon_merchant_id','=','tbl_merchant.merchant_id')
                                        ->first();
                                    if(\Auth::check()){
                                        $wish_list=\DB::table('tbl_follow_activity')
                                                    ->where('activity_type','wish_list')
                                                    ->where('merchant_or_coupon_id',$max_discount_branch->coupon_id)
                                                    ->where('activity_user_id',\Auth::user()->id)
                                                    ->first();
                                    }
                                    else{
                                        $wish_list='';
                                    }

                                ?>

                                    <div class="col-sm-6 col-lg-6">
                                        <div class="deal-single panel">
                                        <a href="{{url('/merchant/branch-view/page/mid-'.$max_discount_branch->merchant_id)}}">
                                            <figure data-id="{{url('/merchant/branch-view/page/mid-'.$max_discount_branch->merchant_id)}}" class="deal-thumbnail embed-responsive embed-responsive-new" data-bg-img="{{asset($max_discount_branch->coupon_featured_image)}}">
                                                <a class="index_discount" href="{{url('single-page/coupon_id-'.$max_discount_branch->coupon_id)}}">
                                                    <div class="label-discount left-20 top-15">{{$max_discount_branch->coupon_discount_rate}}%</div>
                                                </a>
                                                <ul class="deal-actions top-15 right-20">
                                                    <li class="like-deal">
                                                        <span>
                                                            <a class="index_wish" data-id="{{$max_discount_branch->coupon_id}}" data-status="1">
                                                            @if(!empty($wish_list))
                                                                <p style="color:red;"><i class="fa fa-heart"></i></p>
                                                            @else
                                                                <p id="active_wish"><i class="fa fa-heart"></i></p>
                                                            @endif
                                                            </a>
                                                        </span>
                                                    </li>
                                                    <li class="share-btn index_social_link">
                                                        <div class="share-tooltip fade">
                                                            <a href="javascript:void(0)" onclick="javascript:genericSocialShare('http://www.facebook.com/sharer.php?u=yess.com.bd/merchant/branch-view/page/mid-{{$max_discount_branch->coupon_merchant_id}}')"><i class="fa fa-facebook"></i></a>
                                                            <a target="_blank" href="#"><i class="fa fa-twitter"></i></a>
                                                            <a target="_blank" href="#"><i class="fa fa-google-plus"></i></a>
                                                            <a target="_blank" href="#"><i class="fa fa-pinterest"></i></a>
                                                        </div>
                                                        <span><i class="fa fa-share-alt"></i></span>
                                                    </li>
                                                    <a data-id="{{$max_discount_branch->coupon_id}}" class="text_none coupon_details_show index_quick_view">
                                                        <li>
                                                            <span>
                                                                <i class="fa fa-external-link"></i>
                                                            </span>
                                                        </li>
                                                    </a> 
                                                </ul>

                                                <div class="time-left bottom-15 right-20 font-md-14">
                                                    <span  class="countdown_div">
                                                        <span class="t-uppercase font_size btn btn-success" data-countdown="{{$max_discount_branch->coupon_closing_date}} 23:59:59"></span>
                                                    </span>
                                                </div>
                                                <div class="deal-store-logo">
                                                <a href="{{url('/merchant/branch-view/page/mid-'.$max_discount_branch->merchant_id)}}">
                                                    <img src="{{asset('/assets/images/merchant/'.$max_discount_branch->merchant_logo)}}" alt="">
                                                </a>
                                                </div>
                                            </figure>
                                        </a>
                                        </div>
                                    </div>
                                @endforeach
                                @else
                                    <div align="center"><strong>There are no Coupon</strong></div>
                                @endif
                                </div>
                                <div class="col-md-4 pull-right">
                                    <div>
                                        <img width="100%" height="100px" src="{{asset($value->sub_category_featured_image)}}">
                                    </div>
                                </div>   
                            </div>
                            @endif

                            @endforeach
                            @endif
                        </section>


                        <section class="section latest-coupons-area ptb-30">
                            <header class="panel ptb-15 prl-20 pos-r mb-30">
                                <h3 class="section-title font-18">Latest Coupon</h3>
                                <a href="{{url('merchant-view/page')}}" class="btn btn-o btn-xs pos-a right-10 pos-tb-center">View All</a>
                            </header>

                            <div class="latest-coupons-slider owl-slider" data-autoplay-hover-pause="true" data-loop="true" data-autoplay="true" data-smart-speed="1000" data-autoplay-timeout="10000" data-margin="30" data-nav-speed="false" data-items="1" data-xxs-items="1" data-xs-items="2" data-sm-items="2" data-md-items="3" data-lg-items="4">
                                <?php
                                    $latest_coupon_info=\DB::table('tbl_coupon')
                                        ->where('tbl_coupon.coupon_closing_date','>=',date('Y-m-d').' 23:59:59')
                                        ->select(['tbl_coupon.coupon_sub_category_id', DB::raw('MAX(tbl_coupon.coupon_discount_rate) AS coupon_discount_rate')])
                                        ->groupBy('tbl_coupon.coupon_sub_category_id')
                                        ->get();

                                    if(!empty($latest_coupon_info) && count($latest_coupon_info)>=1){
                                    foreach ($latest_coupon_info as $key => $coupon) {

                                        $max_discount_coupon=\DB::table('tbl_coupon')
                                            ->where('tbl_coupon.coupon_sub_category_id', $coupon->coupon_sub_category_id)
                                            ->where('tbl_coupon.coupon_closing_date','>=',date('Y-m-d').' 23:59:59')
                                            ->leftjoin('tbl_merchant','tbl_coupon.coupon_merchant_id','=','tbl_merchant.merchant_id')
                                            ->leftjoin('tbl_branch','tbl_coupon.coupon_branch_id','=','tbl_branch.branch_id')
                                            ->first();
                                ?>

                                <div class="coupon-item">
                                    <div class="coupon-single panel t-center">
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <div class="text-center p-20">
                                                    <img class="store-logo" src="{{asset($max_discount_coupon->coupon_featured_image)}}" alt="">
                                                </div>
                                                <!-- end media -->
                                            </div>
                                            <!-- end col -->

                                            <div class="col-xs-12">
                                                <div class="panel-body">
                                                    <ul class="deal-meta list-inline mb-10">
                                                        <li class="color-muted"><i class="ico fa fa-map-marker mr-5"></i>{{$max_discount_coupon->branch_city}}</li>
                                                        
                                                    </ul>
                                                    <h4 class="color-green mb-10 t-uppercase">{{$max_discount_coupon->coupon_discount_rate}}% OFF</h4>
                                                    <h5 class="deal-title mb-10">
                                                        <a href="{{url('merchant/branch-view/page/mid-'.$max_discount_coupon->merchant_id)}}"><strong>{{$max_discount_coupon->merchant_name}}</strong></a>
                                                    </h5>
                                                    <p class="mb-15 color-muted mb-20 font-12"><i class="lnr lnr-clock mr-10"></i>Expires On {{date('Y-m-d'),$max_discount_coupon->coupon_closing_date}}</p>

                                                    <div class="showcode">
                                                        <a href="{{url('single-page/coupon_id-'.$max_discount_coupon->coupon_id)}}" class="btn btn-sm btn-block">See Coupon Details
                                                        </a>
                                                    </div>
                                                    
                                                </div>
                                            </div>
                                            <!-- end col -->
                                        </div>
                                        <!-- end row -->
                                    </div>
                                </div>
                                <?php }}else{ ?>
                                <div class="coupon-item">
                                    <div class="coupon-single panel t-center">
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <div class="text-center p-20">
                                                    <img class="store-logo" src="{{asset('main-assets/images/coupons/coupon_03.jpg')}}" alt="">
                                                </div>
                                                <!-- end media -->
                                            </div>
                                            <!-- end col -->
                                        </div>
                                        <!-- end row -->
                                    </div>
                                </div>

                                <?php } ?>
                            </div>
                        </section>

                        <section class="section stores-area stores-area-v1 ptb-30">
                            <header class="panel ptb-15 prl-20 pos-r mb-30">
                                <h3 class="section-title font-18">Popular Stores</h3>
                                <a href="{{url('/merchant-view/page')}}" class="btn btn-o btn-xs pos-a right-10 pos-tb-center">All Stores</a>
                            </header>
                            <div class="popular-stores-slider owl-slider" data-loop="true" data-autoplay="true" data-smart-speed="1000" data-autoplay-timeout="10000" data-margin="20" data-items="2" data-xxs-items="2" data-xs-items="2" data-sm-items="3" data-md-items="5" data-lg-items="6">

                            @if(!empty($populer_merchant_info) && count($populer_merchant_info)>0)
                            @foreach($populer_merchant_info as $key=>$list)
                                <div class="store-item t-center">
                                    <a href="{{url('/merchant/branch-view/page/mid-'.$list->merchant_id)}}" class="panel is-block">
                                        <div class="embed-responsive embed-responsive-4by3">
                                            <div class="store-logo">
                                                <img src="{{asset('/assets/images/merchant/'.$list->merchant_logo)}}" alt="">
                                            </div>
                                        </div>
                                        <h6 class="store-name ptb-10">{{$list->merchant_name}}</h6>
                                    </a>
                                </div>
                            @endforeach
                            @endif
                            </div>
                        </section>

                    </div>
                </div>
                <div id="printOnly">
                    <div class="container">
                        <div class="section deals-header-area">
                            <div class="row">

                             <div class="col-md-12">

                                    <div class="header-deals-slider owl-slider" data-loop="true" data-autoplay="true" data-autoplay-timeout="10" data-smart-speed="5" data-nav-speed="false" data-nav="true" data-xxs-items="1" data-xxs-nav="true" data-xs-items="1" data-xs-nav="true" data-sm-items="1" data-sm-nav="true" data-md-items="1" data-md-nav="true" data-lg-items="1" data-lg-nav="true">

                                        @if(!empty($home_slider_info) && count($home_slider_info)>0)
                                        @foreach($home_slider_info as $key=>$list)
                                            <?php 
                                                $meta_field_value=$list->setting_meta_field_value; 
                                                $home_slider_image=unserialize($meta_field_value);
                                                $home_slider_main_image=$home_slider_image[0];
                                            ?>
                                                <img width="100%" src="{{asset('assets/images/slider/'.$home_slider_main_image)}}">
                                        @endforeach
                                        @endif
                                    </div>

                                <!-- wrapper -->

                                <div class="clear"></div>

                                </div>
                            </div>
                        </div>


                        <div class="mt-5">
                            <div>
                                <table>
                                    <tr>
                                        <td class="properties_icon" align="center">
                                            <div class="">
                                                <img class="pr-10" src="{{asset('main-assets/images/icons/tablet.png')}}" alt="">
                                            </div>
                                            <div>
                                                <h5>Deals & Coupons</h5>
                                            </div>

                                        </td>
                                            

                                        <td style="padding:5px;" align="center"></td>
                                        <td class="properties_icon" align="center">
                                            <div class="">
                                                <img class="pr-10" src="{{asset('main-assets/images/icons/online-shop-6.png')}}" alt="">
                                            </div>
                                            <div>
                                                <h5>Find Best Offers</h5>
                                            </div>
                                        </td>
                                        <td style="padding:5px;" align="center"></td>
                                        <td class="properties_icon" align="center">
                                            <div class="">
                                                <img class="pr-10" src="{{asset('main-assets/images/icons/money.png')}}" alt="">
                                            </div>
                                            <div>
                                                <h5>Find Best Offers</h5>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr></tr>
                                    
                                </table>

                            </div>
                        </div>


                    <section class="section latest-coupons-area ptb-10">
                        <div class="inline_menu_area" id="header-navbar">
                                
                            <table>
                            
                                <tr class="nav-menu inline_menu_bar">
                                    <?php 
                                        $all_sub_category=\DB::table('tbl_sub_category')->get();
                                        if(!empty($all_sub_category) && count($all_sub_category)>0){
                                        foreach ($all_sub_category as $key => $sub_category) {
                                        
                                    ?>
                                        <td class="hcolor" align="center">
                                            <a class="inline_menu" href="{{url('all-merchant/coupon/page/cid-/'.$sub_category->sub_category_id.'/subcid-'.$sub_category->sub_category_id)}}">
                                                {{$sub_category->sub_category_name}}
                                            </a>
                                        </td>

                                    <?php
                                        }
                                        }
                                    ?>

                                </tr>
                            </table>
                        </div>

                    </section>




                        <section class="section latest-coupons-area ptb-10 nine_size_font">


                                    <?php 
                                        $all_sub_category=\DB::table('tbl_sub_category')->Orderby('sub_category_id','asc')->get();

                                    ?>
                                    @if(!empty($all_sub_category) && count($all_sub_category)>0)
                                    @foreach ($all_sub_category as $key => $sub_category)


                                        <a href="{{url('all-merchant/coupon/page/cid-/'.$sub_category->sub_category_id.'/subcid-'.$sub_category->sub_category_id)}}"><h4 class="mobile-heading">{{$sub_category->sub_category_name}}</h4></a>

                                       <div class="popular-stores-slider owl-slider" data-loop="true" data-autoplay="true" data-smart-speed="1000" data-autoplay-timeout="10000" data-margin="5" data-items="3" data-xxs-items="3" data-xs-items="3" data-sm-items="3" data-md-items="3" data-lg-items="3">
                                            <?php
                                                    $all_merchant_info=\DB::table('tbl_coupon')
                                                        ->leftjoin('tbl_merchant','tbl_coupon.coupon_merchant_id','=','tbl_merchant.merchant_id')
                                                        ->where('tbl_coupon.coupon_sub_category_id',$sub_category->sub_category_id)
                                                        ->where('tbl_merchant.merchant_rank','=','1')
                                                        ->where('tbl_coupon.coupons_status','1')
                                                        ->where('tbl_merchant.merchant_status','1')
                                                        ->where('tbl_coupon.coupon_closing_date','>=',date('Y-m-d').' 23:59:59')
                                                        ->select(['tbl_coupon.coupon_merchant_id', DB::raw('MAX(tbl_coupon.coupon_discount_rate) AS coupon_discount_rate') , \DB::raw('count(*) as total')])
                                                        ->groupBy('tbl_coupon.coupon_merchant_id')
                                                        ->get();


                                                if(!empty($all_merchant_info) && count($all_merchant_info)>=1){
                                                foreach ($all_merchant_info as $key => $max_discount_branch) {

                                                    $max_discount_coupon=\DB::table('tbl_coupon')
                                                        ->where('tbl_coupon.coupon_merchant_id', $max_discount_branch->coupon_merchant_id)
                                                        ->leftjoin('tbl_merchant','tbl_coupon.coupon_merchant_id','=','tbl_merchant.merchant_id')
                                                        ->leftjoin('tbl_branch','tbl_coupon.coupon_branch_id','=','tbl_branch.branch_id')
                                                        ->first();

                                            ?>

                                            <div class="coupon-item">
                                                <div class="coupon-single panel t-center">
                                                    <div class="row">
                                                        <div>
                                                            <div>
                                                                <a href="{{url('merchant/branch-view/page/mid-'.$max_discount_coupon->merchant_id)}}">
                                                                    <img class="store-logo" src="{{asset($max_discount_coupon->coupon_featured_image)}}" alt="Image">
                                                                </a>
                                                            </div>
                                                            <!-- end media -->
                                                        </div>
                                                        <!-- end col -->

                                                        <div class="col-xs-12">
                                                            <div class="panel-body">
                                                                <h5 class="deal-title mb-10">
                                                                    <a href="{{url('merchant/branch-view/page/mid-'.$max_discount_coupon->merchant_id)}}"><strong><?php echo substr(($max_discount_coupon->merchant_name), 0,12); ?> </strong><br>
                                                                        {{$max_discount_coupon->coupon_discount_rate}}% OFF
                                                                    </a>
                                                                </h5>
                                                                <div class="claim_deal">
                                                                                                                                       
                                                                    @if(\Auth::check())
                                                                        <a href="{{url('/active/deal/coupon-'.$max_discount_coupon->coupon_code.'/mobile-'.\Auth::user()->mobile)}}" class="btn btn-sm btn-block">Claim Deal
                                                                        </a>
                                                                    @else
                                                                        <a href="{{url('/sign-in/page')}}" class="btn btn-sm btn-block">Claim Deal
                                                                        </a>
                                                                    @endif

								</div>
                                                                
                                                            </div>
                                                        </div>
                                                        <!-- end col -->
                                                    </div>
                                                    <!-- end row -->
                                                </div>
                                            </div>
                                            
                                            <?php }}else{ ?>
                                            <div class="coupon-item">
                                                <div class="coupon-single panel t-center">
                                                    <div class="row">
                                                        <div class="col-xs-12">
                                                            <div class="text-center p-20">
                                                                <img class="store-logo" src="{{asset('main-assets/images/coupons/coupon_03.jpg')}}" alt="">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <?php } ?>
                                       </div>


                                    @endforeach
                                    @endif


                        </section>



                        <section class="mobile_hot_deal">
                            <header>
                                <h3>
                                    <a href="{{url('/merchant-view/page')}}">Hot Deal</a>
                                </h3>
                            </header>
                            <div class="latest-coupons-slider owl-slider" data-autoplay-hover-pause="true" data-loop="true" data-autoplay="true" data-smart-speed="1000" data-autoplay-timeout="10000" data-margin="5" data-nav-speed="false" data-items="3" data-xxs-items="3" data-xs-items="3" data-sm-items="3" data-md-items="3" data-lg-items="4">
                                <?php
                                    $latest_coupon_info=\DB::table('tbl_coupon')
                                        ->where('tbl_coupon.coupon_closing_date','>=',date('Y-m-d').' 23:59:59')
                                        ->select(['tbl_coupon.coupon_sub_category_id', DB::raw('MAX(tbl_coupon.coupon_discount_rate) AS coupon_discount_rate')])
                                        ->groupBy('tbl_coupon.coupon_sub_category_id')
                                        ->get();

                                    if(!empty($latest_coupon_info) && count($latest_coupon_info)>=1){
                                    foreach ($latest_coupon_info as $key => $coupon) {

                                        $max_discount_coupon=\DB::table('tbl_coupon')
                                            ->where('tbl_coupon.coupon_sub_category_id', $coupon->coupon_sub_category_id)
                                            ->where('tbl_coupon.coupon_closing_date','>=',date('Y-m-d').' 23:59:59')
                                            ->leftjoin('tbl_merchant','tbl_coupon.coupon_merchant_id','=','tbl_merchant.merchant_id')
                                            ->leftjoin('tbl_branch','tbl_coupon.coupon_branch_id','=','tbl_branch.branch_id')
                                            ->first();
                                ?>

                                <div class="store-item">
                                    <a href="{{url('/merchant/branch-view/page/mid-'.$max_discount_coupon->merchant_id)}}" class="panel is-block">
                                        <div class="embed-responsive embed-responsive-4by3">
                                            <div class="store-logo">
                                                <img src="{{asset('/assets/images/merchant/'.$max_discount_coupon->merchant_logo)}}" alt="">
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <?php }}else{ ?>
                                <div class="coupon-item">
                                    <div class="coupon-single panel t-center">
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <div class="text-center p-20">
                                                    <img class="store-logo" src="{{asset('main-assets/images/coupons/coupon_03.jpg')}}" alt="">
                                                </div>
                                                <!-- end media -->
                                            </div>
                                            <!-- end col -->
                                        </div>
                                        <!-- end row -->
                                    </div>
                                </div>

                                <?php } ?>
                            </div>
                        </section>

                        <section class="mobile_populer_deal">
                            <header>
                                <h3>
                                    <a href="{{url('/merchant-view/page')}}">Popular Stores</a>
                                </h3>
                            </header>
                            <div class="popular-stores-slider owl-slider" data-loop="true" data-autoplay="true" data-smart-speed="1000" data-autoplay-timeout="10000" data-margin="5" data-items="4" data-xxs-items="4" data-xs-items="4" data-sm-items="4" data-md-items="4" data-lg-items="6">

                            @if(!empty($populer_merchant_info) && count($populer_merchant_info)>0)
                            @foreach($populer_merchant_info as $key=>$list)
                                <div class="">
                                    <a href="{{url('/merchant/branch-view/page/mid-'.$list->merchant_id)}}" class="panel is-block">
                                        <img src="{{asset('/assets/images/merchant/'.$list->merchant_logo)}}" alt="">
                                    </a>
                                </div>
                            @endforeach
                            @endif
                            </div>
                        </section>

                    </div>
                </div>


            </div>

            <!-- Modal -->
            <div id="MainCouponDetailsModal" class="modal fade " rtabindex="-1" role="dialog">
                <div class="modal-dialog ">
                    <div class="modal-content">
                        
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Coupon Details</h4>
                        </div>
                        <div class="modal-body">

                            <div class="main_coupon_details">

                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </main>
        <!-- –––––––––––––––[ END PAGE CONTENT ]––––––––––––––– -->
        @stop