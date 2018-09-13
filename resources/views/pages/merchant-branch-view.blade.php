@extends('layout.master')
@section('content')
        <!-- –––––––––––––––[ PAGE CONTENT ]––––––––––––––– -->
        <main id="mainContent" class="main-content">
            <!-- Page Container -->
            <div class="page-container store-page ptb-10">
                <div class="container">

                    <div id="printMainSlider">

                        <section class="store-header-area panel t-xs-center t-sm-left">
                            <div class="row row-rl-10">
                                <div class="col-md-12">
                                    @if(Session::has('message'))
                                    <div class="alert alert-success" role="alert">
                                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                        {{ Session::get('message') }}
                                    </div> 
                                    @endif
                                    @if(Session::has('errormessage'))
                                    <div class="alert alert-danger" role="alert">
                                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                        {{ Session::get('errormessage') }}
                                    </div>
                                    @endif
                                </div>
                                <div class="col-md-12 t-center">
                                    <h2>
    					Map
    				    </h2>
				    <div id="map" style="height: 400px; width: 100%;"></div>
                                </div>
                            </div>
                        </section>

                        @if(!empty($merchant_featured_info) && count($merchant_featured_info)>0)
                        <section class="section latest-coupons-area ptb-10">
                            <header class="panel ptb-15 prl-20 pos-r mb-10">
                                <h3 class="section-title font-18">Merchat Featured Product</h3>
                                <a href="{{url('merchant-view/page')}}" class="btn btn-o btn-xs pos-a right-10 pos-tb-center">View All</a>
                            </header>

                            <div class="latest-coupons-slider owl-slider" data-autoplay-hover-pause="true" data-loop="true" data-autoplay="true" data-smart-speed="1000" data-autoplay-timeout="10000" data-margin="30" data-nav-speed="false" data-items="1" data-xxs-items="2" data-xs-items="2" data-sm-items="2" data-md-items="3" data-lg-items="4">
                                <?php
                                   
                                    foreach ($merchant_featured_info as $key => $list) {
                                    
                                ?>

                                <div class="coupon-item">
                                    <div class="coupon-single panel t-center">
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <div class="text-center p-20">
                                                    <img class="store-logo" src="{{asset('assets/images/merchant-featured/'.$list->product_image)}}" alt="">
                                                </div>
                                                <!-- end media -->
                                            </div>
                                            <!-- end col -->

                                            <div class="col-xs-12">
                                                <div class="panel-body">
                                                <p class="text-center">{{$list->product_featured_description}}</p>
                                                    <h5 class="color-green mb-10"><span class="reducedfrom">Regular Price : {{$list->product_original_price}} Tk</span></h5>
                                                    <h4 class="color-green mb-10">
                                                        Discount Price : {{$list->product_discount_price}} Tk
                                                    </h4>
                                                    
                                                </div>
                                            </div>
                                            <!-- end col -->
                                        </div>
                                        <!-- end row -->
                                    </div>
                                </div>
                                <?php } ?>

                            </div>
                        </section>
                        @endif

                        <section class="section deals-area ptb-30">
                            <header class="panel ptb-15 prl-20 pos-r mb-30">
                                <h3 class="section-title font-18">Store Deals</h3>
                                @if(isset($check_follow) && !empty($check_follow))
                                    @if(($check_follow->activity_list_status) == 1)  
                                        <button class="btn btn-o btn-xs pull-right right-10 pos-tb-center button_radius" style="background-color:#00a2ff; color:#fff;">Following </button>
                                    @elseif(($check_follow->activity_list_status) == -1)
                                        <a class="btn btn-o btn-xs pos-a right-10 pos-tb-center button_radius add_folow" id="active_follow_{{$merchant_id}}" data-id="{{$merchant_id}}" data-status="1">Follow</a>

                                    @endif 
                                @else
                                    <a class="btn btn-o btn-xs pos-a right-10 pos-tb-center add_folow"  id="active_follow_{{$merchant_id}}" data-id="{{$merchant_id}}" data-status="1">Follow</a>
                                @endif
                            </header>
                            <div class="row row-masnory row-tb-20">

    						@if(!empty($all_branch_info) && count($all_branch_info)>0)
                            @foreach($all_branch_info as $key =>$list)
                                <?php
                                if(\Auth::check()){
                                    $wish_list=\DB::table('tbl_follow_activity')
                                                ->where('activity_type','wish_list')
                                                ->where('merchant_or_coupon_id',$list->coupon_id)
                                                ->where('activity_user_id',\Auth::user()->id)
                                                ->first();
                                }
                                else{
                                    $wish_list='';
                                }
                                    $select_coupon_rating=0;
                                    $final_coupon_rating=0;
                                    $all_rating_view=\DB::table('tbl_coupon_review_comments')
                                                    ->leftjoin('tbl_coupon','tbl_coupon_review_comments.coupon_id','=','tbl_coupon.coupon_id')
                                                    ->where('tbl_coupon.coupon_id',$list->coupon_id)
                                                    ->get();

                                    if(($list->coupon_rating_client_count)>=1){
                                        $select_coupon_rating=$list->coupon_total_rating;
                                        $final_coupon_rating=$select_coupon_rating/$list->coupon_rating_client_count;
                                    }
                                ?>
                                <div class="col-sm-6 col-lg-4">
                                    <div class="deal-single panel">
                                        <figure class="deal-thumbnail embed-responsive embed-responsive-16by9" data-bg-img="{{asset($list->coupon_featured_image)}}">
                                            <div class="label-discount left-20 top-15">
                                                <a href="{{url('single-page/coupon_id-'.$list->coupon_id)}}">
                                                    {{$list->coupon_discount_rate}}%
                                                </a>
                                            </div>
                                            <ul class="deal-actions top-15 right-20">
                                                <li class="like-deal">
                                                    <span>
                                                        <a class="add_wish" data-id="{{$list->coupon_id}}" data-status="1">
                                                        @if(!empty($wish_list))
        					                                <p style="color:red;"><i class="fa fa-heart"></i></p>
                                                        @else
                                                            <p id="active_wish_{{$list->coupon_id}}"><i class="fa fa-heart "></i></p>
                                                        @endif
                                                        </a>

    					                            </span>
                                                </li>
                                                <li class="share-btn">
                                                    <div class="share-tooltip fade">
                                                        <a href="javascript:void(0)" onclick="javascript:genericSocialShare('http://www.facebook.com/sharer.php?u=yess.com.bd/merchant/branch-view/page/mid-{{$list->coupon_merchant_id}}')"><i class="fa fa-facebook"></i></a>
                                                        <a target="_blank" href="#"><i class="fa fa-twitter"></i></a>
                                                        <a target="_blank" href="#"><i class="fa fa-google-plus"></i></a>
                                                        <a target="_blank" href="#"><i class="fa fa-pinterest"></i></a>
                                                    </div>
                                                    <span><i class="fa fa-share-alt"></i></span>
                                                </li>
                                                <li>
                                                    <span>
                		                                <a data-toggle="modal" data-target="#CouponDetailsModal"  data-id="{{$list->coupon_id}}" class="text_none coupon_details_show tooltips" href=""><i class="fa fa-external-link"></i></a>
                		                            </span>
                                                </li>
                                            </ul>
                                            <div class="time-left bottom-15 right-20 font-md-14">
                                                <span class="countdown_div">
                		                            <span class="t-uppercase btn btn-success" data-countdown="{{$list->coupon_closing_date}} 23:59:59"></span>
                		                        </span>
                                            </div>
                                            <div class="deal-store-logo">
                                                <img src="{{asset('/assets/images/merchant/'.$list->merchant_logo)}}" alt="">
                                            </div>
                                        </figure>
                                        <div class="bg-white pt-20 pl-20 pr-15">
                                            <div class="pr-md-10">
                                                <div class="rating mb-10">
                                                    <span class="rating_view">
                                                    @if(!empty($final_coupon_rating))
                                                    @for($i=1;$i<=5;$i++)
                                                    <span  style="{{isset($final_coupon_rating) && ($i <= $final_coupon_rating) ? 'color:#f70' : ''}}"></span>
                                                    @endfor

                                                    @else
                                                    @for($i=1;$i<=5;$i++)
                                                    <span></span>
                                                    @endfor
                                                    @endif
                                                    </span>
                                                    <span class="rating-reviews">
    		                        		            ( <span class="rating-count">{{$select_coupon_rating}}</span> rates )
                                                    </span>
                                                    <br>

                                                    <h3 class="deal-title mb-10">
                    		                            <a href="{{url('/single-page/coupon_id-'.$list->coupon_id)}}"><strong>{{$list->merchant_name}}</strong>, {{$list->branch_name}}</a>
                    		                    </h3>
                                                </div>

                                            </div>
                                        </div>
                                        <div>
                                        @if(\Auth::check())
                                            <a href="{{url('/active/deal/coupon-'.$list->coupon_code.'/mobile-'.\Auth::user()->mobile)}}" class="btn btn-sm btn-block">Claim Deal
                                            </a>
                                        @else
                                            <a href="{{url('/sign-in/page')}}" class="btn btn-sm btn-block"> Claim Deal
                                            </a>
                                        @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            @else
                                <div class="col-sm-12 col-lg-12 col-md-12">
                                    <h3 class="text-center"> Coupon Not Found </h3>
                                </div>
                            @endif

                            </div>

                        </section>

                        <section class="section latest-coupons-area ptb-30">
                            <header class="panel ptb-15 prl-20 pos-r mb-10">
                                <h3 class="section-title font-18">Latest Coupon</h3>
                                <a href="{{url('merchant-view/page')}}" class="btn btn-o btn-xs pos-a right-10 pos-tb-center">View All</a>
                            </header>

                            <div class="latest-coupons-slider owl-slider" data-autoplay-hover-pause="true" data-loop="true" data-autoplay="true" data-smart-speed="1000" data-autoplay-timeout="10000" data-margin="30" data-nav-speed="false" data-items="1" data-xxs-items="2" data-xs-items="2" data-sm-items="2" data-md-items="3" data-lg-items="4">
                                <?php
                                    $related_coupon_info=\DB::table('tbl_coupon')
                                        ->select(['tbl_coupon.coupon_sub_category_id', DB::raw('MAX(tbl_coupon.coupon_discount_rate) AS coupon_discount_rate')])
                                        ->groupBy('tbl_coupon.coupon_sub_category_id')
                                        ->get();
                                    if(!empty($related_coupon_info) && count($related_coupon_info)>=1){
                                    foreach ($related_coupon_info as $key => $coupon) {
                                    
                                        $max_discount_coupon=\DB::table('tbl_coupon')
                                            ->where('tbl_coupon.coupon_sub_category_id', $coupon->coupon_sub_category_id)
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
                                                    <h4 class="color-green mb-10 t-uppercase">{{$max_discount_coupon->coupon_discount_rate}}% OFF</h4>
                                                    <h5 class="deal-title mb-10">
                                                        <a href="{{url('merchant/branch-view/page/mid-'.$max_discount_coupon->merchant_id)}}"><strong>{{$max_discount_coupon->merchant_name}}</strong></a>
                                                    </h5>
                                                    <p class="mb-15 color-muted mb-20 font-12"><i class="lnr lnr-clock mr-10"></i>Expires On {{date('Y-m-d'),$max_discount_coupon->coupon_closing_date}}</p>

                                                    <div class="showcode">
                                                        <a href="{{url('single-page/coupon_id-'.$max_discount_coupon->coupon_code)}}" class="btn btn-sm btn-block">See Details
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
                    </div>

                    <div id="printOnly">
                        @if(!empty($merchant_featured_info) && count($merchant_featured_info)>0)
                        <section class="section latest-coupons-area ptb-10">
                            <header class="panel prl-20 pos-r mb-10">
                                <h3 class="section-title font-20">Featured Product</h3>
                                <a href="{{url('merchant-view/page')}}" class="btn btn-o btn-xs pos-a right-10 pos-tb-center">View All</a>
                            </header>

                            <div class="latest-coupons-slider owl-slider" data-autoplay-hover-pause="true" data-loop="true" data-autoplay="true" data-smart-speed="1000" data-autoplay-timeout="10000" data-margin="30" data-nav-speed="false" data-items="1" data-xxs-items="2" data-xs-items="2" data-sm-items="2" data-md-items="3" data-lg-items="4">
                                <?php
                                   
                                    foreach ($merchant_featured_info as $key => $list) {
                                    
                                ?>

                                <div class="coupon-item">
                                    <div class="coupon-single panel t-center">
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <div class="text-center p-20">
                                                    <img class="store-logo" src="{{asset('assets/images/merchant-featured/'.$list->product_image)}}" alt="">
                                                </div>
                                                <!-- end media -->
                                            </div>
                                            <!-- end col -->

                                            <div class="col-xs-12">
                                                <div class="panel-body">
                                                <p class="text-center">{{$list->product_featured_description}}</p>
                                                    <h5 class="color-green mb-10"><span class="reducedfrom">Regular Price : {{$list->product_original_price}} Tk</span></h5>
                                                    <h4 class="color-green mb-10">Discount Price : {{$list->product_discount_price}} Tk</h4>
                                                    
                                                </div>
                                            </div>
                                            <!-- end col -->
                                        </div>
                                        <!-- end row -->
                                    </div>
                                </div>
                                <?php } ?>

                            </div>
                        </section>
                        @endif

                        <section class="section deals-area ptb-30">
                            <header class="panel prl-20 pos-r mb-10">
                                <h3 class="section-title font-20"><strong>Store Deals</strong></h3>
                                @if(isset($check_follow) && !empty($check_follow))
                                    @if(($check_follow->activity_list_status) == 1)  
                                        <button class="btn btn-o btn-xs pull-right right-10 pos-tb-center button_radius" style="background-color:#00a2ff; color:#fff;">Following </button>
                                    @elseif(($check_follow->activity_list_status) == -1)
                                        <a class="btn btn-o btn-xs pos-a right-10 pos-tb-center button_radius add_folow" id="active_follow_{{$merchant_id}}" data-id="{{$merchant_id}}" data-status="1">Follow</a>

                                    @endif 
                                @else
                                    <a class="btn btn-o btn-xs pos-a right-10 pos-tb-center add_folow"  id="active_follow_{{$merchant_id}}" data-id="{{$merchant_id}}" data-status="1">Follow</a>
                                @endif
                            </header>
                            <div class="row row-masnory row-tb-10">

                            @if(!empty($all_branch_info) && count($all_branch_info)>0)
                            @foreach($all_branch_info as $key =>$list)
                                <?php
                                if(\Auth::check()){
                                    $wish_list=\DB::table('tbl_follow_activity')
                                                ->where('activity_type','wish_list')
                                                ->where('merchant_or_coupon_id',$list->coupon_id)
                                                ->where('activity_user_id',\Auth::user()->id)
                                                ->first();
                                }
                                else{
                                    $wish_list='';
                                }
                                    $select_coupon_rating=0;
                                    $final_coupon_rating=0;
                                    $all_rating_view=\DB::table('tbl_coupon_review_comments')
                                                    ->leftjoin('tbl_coupon','tbl_coupon_review_comments.coupon_id','=','tbl_coupon.coupon_id')
                                                    ->where('tbl_coupon.coupon_id',$list->coupon_id)
                                                    ->get();

                                    if(($list->coupon_rating_client_count)>=1){
                                        $select_coupon_rating=$list->coupon_total_rating;
                                        $final_coupon_rating=$select_coupon_rating/$list->coupon_rating_client_count;
                                    }
                                ?>
                                <div class="col-sm-6 col-lg-4">
                                    <div class="deal-single panel">


                                        <figure class="deal-thumbnail embed-responsive embed-responsive-16by9" data-bg-img="{{asset($list->coupon_featured_image)}}">
                                            <div class="label-discount left-20 top-15">
                                                <a href="{{url('single-page/coupon_id-'.$list->coupon_id)}}">
                                                    {{$list->coupon_discount_rate}}%
                                                </a>
                                            </div>
                                            <ul class="deal-actions top-15 right-20">
                                                <li class="like-deal">
                                                    <span>
                                                        <a class="add_wish" data-id="{{$list->coupon_id}}" data-status="1">
                                                        @if(!empty($wish_list))
                                                            <p style="color:red;"><i class="fa fa-heart"></i></p>
                                                        @else
                                                            <p id="active_wish_{{$list->coupon_id}}"><i class="fa fa-heart "></i></p>
                                                        @endif
                                                        </a>

                                                    </span>
                                                </li>
                                                <li class="share-btn">
                                                    <div class="share-tooltip fade">
                                                        <a href="javascript:void(0)" onclick="javascript:genericSocialShare('http://www.facebook.com/sharer.php?u=yess.com.bd/merchant/branch-view/page/mid-{{$list->coupon_merchant_id}}')"><i class="fa fa-facebook"></i></a>
                                                        <a target="_blank" href="#"><i class="fa fa-twitter"></i></a>
                                                        <a target="_blank" href="#"><i class="fa fa-google-plus"></i></a>
                                                        <a target="_blank" href="#"><i class="fa fa-pinterest"></i></a>
                                                    </div>
                                                    <span><i class="fa fa-share-alt"></i></span>
                                                </li>
                                                <li>
                                                    <span>
                                                        <a data-toggle="modal" data-target="#CouponDetailsModal"  data-id="{{$list->coupon_id}}" class="text_none coupon_details_show tooltips" href=""><i class="fa fa-external-link"></i></a>
                                                    </span>
                                                </li>
                                            </ul>
                                            <div class="time-left bottom-15 right-20 font-md-14">
                                                <span class="countdown_div">
                                                    <span class="t-uppercase btn btn-success" data-countdown="{{$list->coupon_closing_date}} 23:59:59"></span>
                                                </span>
                                            </div>
                                            <div class="deal-store-logo">
                                                <img src="{{asset('/assets/images/merchant/'.$list->merchant_logo)}}" alt="">
                                            </div>

                                        </figure>
                                        <div class="claim_deal">
                                            @if(\Auth::check())
                                                <a href="{{url('/active/deal/coupon-'.$list->coupon_code.'/mobile-'.\Auth::user()->mobile)}}" class="btn btn-sm btn-block">Claim Deal
                                                </a>
                                            @else
                                                <a href="{{url('/sign-in/page')}}"  class="btn btn-sm btn-block">Claim Deal
                                                </a>
                                            @endif
                                        </div>

                                        <div class="bg-white pl-20 pr-15">
                                            <div class="pr-md-10">
                                                <div class="rating mb-10">
                                                    <h3 class="deal-title mb-10 font-16">
                                                        <a href="{{url('/single-page/coupon_id-'.$list->coupon_id)}}"><strong>{{$list->merchant_name}}</strong>, {{$list->branch_name}}</a>
                                                    </h3>
                                                    <span class="rating_view">
                                                    @if(!empty($final_coupon_rating))
                                                    @for($i=1;$i<=5;$i++)
                                                    <span  style="{{isset($final_coupon_rating) && ($i <= $final_coupon_rating) ? 'color:#f70' : ''}}"></span>
                                                    @endfor

                                                    @else
                                                    @for($i=1;$i<=5;$i++)
                                                    <span></span>
                                                    @endfor
                                                    @endif
                                                    </span>
                                                    <span class="rating-reviews">
                                                        ( <span class="rating-count">{{$select_coupon_rating}}</span> rates )
                                                    </span>
                                                    
                                                </div>

                                            </div>
                                        </div>
                                        
                                    </div>
                                </div>
                            @endforeach
                            @else
                                <div class="col-sm-12 col-lg-12 col-md-12">
                                    <h3 class="text-center"> Coupon Not Found</h3>
                                </div>
                            @endif

                            </div>

                        </section>

                        <section class="store-header-area panel t-xs-center t-sm-left">
                            <div class="row row-rl-10">
                                <div class="col-md-12">
                                    @if(Session::has('message'))
                                    <div class="alert alert-success" role="alert">
                                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                        {{ Session::get('message') }}
                                    </div> 
                                    @endif
                                    @if(Session::has('errormessage'))
                                    <div class="alert alert-danger" role="alert">
                                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                        {{ Session::get('errormessage') }}
                                    </div>
                                    @endif
                                </div>
                                <div class="col-md-12 t-center">
				    <div id="map2" style="height: 200px; width: 100%;"></div>
                                </div>

                            </div>
                        </section>

                        <section class="section latest-coupons-area ptb-30">
                            <header class="panel prl-20 pos-r mb-10">
                                <h3 class="section-title font-20"><strong>Latest Coupon </strong></h3>
                                <a href="{{url('merchant-view/page')}}" class="btn btn-o btn-xs pos-a right-10 pos-tb-center">View All</a>
                            </header>

                            <div class="latest-coupons-slider owl-slider" data-autoplay-hover-pause="true" data-loop="true" data-autoplay="true" data-smart-speed="1000" data-autoplay-timeout="10000" data-margin="30" data-nav-speed="false" data-items="1" data-xxs-items="2" data-xs-items="2" data-sm-items="2" data-md-items="3" data-lg-items="4">
                                <?php
                                    $related_coupon_info=\DB::table('tbl_coupon')
                                        ->select(['tbl_coupon.coupon_sub_category_id', DB::raw('MAX(tbl_coupon.coupon_discount_rate) AS coupon_discount_rate')])
                                        ->groupBy('tbl_coupon.coupon_sub_category_id')
                                        ->get();
                                    if(!empty($related_coupon_info) && count($related_coupon_info)>=1){
                                    foreach ($related_coupon_info as $key => $coupon) {
                                    
                                        $max_discount_coupon=\DB::table('tbl_coupon')
                                            ->where('tbl_coupon.coupon_sub_category_id', $coupon->coupon_sub_category_id)
                                            ->leftjoin('tbl_merchant','tbl_coupon.coupon_merchant_id','=','tbl_merchant.merchant_id')
                                            ->leftjoin('tbl_branch','tbl_coupon.coupon_branch_id','=','tbl_branch.branch_id')
                                            ->first();
                                ?>

                                <div class="coupon-item">
                                    <div class="coupon-single panel t-center">
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <div class="text-center p-5">
                                                    <img class="store-logo" src="{{asset($max_discount_coupon->coupon_featured_image)}}" alt="">
                                                </div>
                                                <!-- end media -->
                                            </div>
                                            <!-- end col -->

                                            <div class="col-xs-12">
                                                <div class="panel-body">
                                                    
                                                    <h4 class="color-green mb-10 t-uppercase">{{$max_discount_coupon->coupon_discount_rate}}% OFF</h4>
                                                    <h5 class="deal-title mb-10 name_height">
                                                        <a href="{{url('merchant/branch-view/page/mid-'.$max_discount_coupon->merchant_id)}}"><strong>{{substr($max_discount_coupon->merchant_name ,0,30)}}</strong></a>
                                                    </h5>
                                                    <p class="mb-15 color-muted mb-20 font-12"><i class="lnr lnr-clock mr-10"></i>Expires On {{date('Y-m-d'),$max_discount_coupon->coupon_closing_date}}</p>

                                                    <div class="showcode">
                                                        <a href="{{url('single-page/coupon_id-'.$max_discount_coupon->coupon_code)}}" class="btn btn-sm btn-block">See Details
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
                    </div>

                </div>
            </div>
            <!-- End Page Container -->

            <!-- Modal -->
            <div id="CouponDetailsModal" class="modal fade " rtabindex="-1" role="dialog">
                <div class="modal-dialog ">
                    <div class="modal-content">
                        
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Coupon Details</h4>
                        </div>
                        <div class="modal-body">

                            <div class="coupon_details">

                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </main>

    <script>
      var map, map2;
      function initMap() {
        map = new google.maps.Map(document.getElementById('map'), {
          zoom: 10,
          center: new google.maps.LatLng(23.7806286, 90.2793683),
          mapTypeId: 'roadmap'
        });
        map2 = new google.maps.Map(document.getElementById('map2'), {
          zoom: 10,
          center: new google.maps.LatLng(23.7806286, 90.2793683),
          mapTypeId: 'roadmap'
        });
        var infowindow = new google.maps.InfoWindow();
        var features = <?php print_r(json_encode($locations)) ?>;
        var features2 = <?php print_r(json_encode($locations)) ?>;

        // Create markers.
        features.forEach(function(feature) {
                var marker = new google.maps.Marker({
                    position: new google.maps.LatLng(feature.branch_gprs_lat,feature.branch_gprs_lng),
                    label:{text: feature.coupon_discount_rate+'%', color: "#ff0000", fontWeight:'bold'},
                    title: feature.merchant_name+','+feature.branch_name,
                    icon:'../../../assets/images/3.png',
                    map: map
                });
                google.maps.event.addListener(marker, 'click', function() {
                    infowindow.setContent('<div><strong>'+feature.merchant_name+',<br>'+feature.branch_name+','+feature.branch_city+'<br>Discount Rate : '+feature.coupon_discount_rate+'%</strong><br>Coupon Code : '+feature.coupon_code+'<br>Branch Address : '+feature.branch_address+
                    '</div>');
                  infowindow.open(map, this);
                });
        });

        features2.forEach(function(feature) {
            var marker = new google.maps.Marker({
                position: new google.maps.LatLng(feature.branch_gprs_lat,feature.branch_gprs_lng),
                label:{text: feature.coupon_discount_rate+'%', color: "#ff0000", fontWeight:'bold'},
                title: feature.merchant_name+','+feature.branch_name,
                icon:'../../../assets/images/3.png',
                map: map2
            });
            google.maps.event.addListener(marker, 'click', function() {
                infowindow.setContent('<div><strong>'+feature.merchant_name+',<br>'+feature.branch_name+','+feature.branch_city+'<br>Discount Rate : '+feature.coupon_discount_rate+'%</strong><br>Coupon Code : '+feature.coupon_code+'<br>Branch Address : '+feature.branch_address+
                '</div>');
              infowindow.open(map, this);
            });
        });
      }
    </script>
    <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBeCI4td4NDWEoCn8cySpkFw7bf62CMETs&callback=initMap">
    </script>
        <!-- –––––––––––––––[ END PAGE CONTENT ]––––––––––––––– -->
@stop