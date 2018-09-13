@extends('layout.master')
@section('content')
    <!-- –––––––––––––––[ PAGE CONTENT ]––––––––––––––– -->
    <main id="mainContent" class="main-content">
        <!-- Page Container -->
        <div class="page-container ptb-10">
            <div class="container">

                <div class="row">
                    <div class="col-md-12">
                        @if($errors->count() > 0 )

                        <div class="alert alert-danger">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                            <h6>The following errors have occurred:</h6>
                            <ul>
                                @foreach( $errors->all() as $message )
                                <li>{{ $message }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

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
                </div>
                
                <section class="section deals-area ptb-30">
                    <header class="panel ptb-15 prl-20 pos-r mb-30">

                        <!-- List Control View -->
                        <ul class="list-control-view list-inline">
                            <li><i class="fa fa-bars"></i> Latest Coupon
                            </li>

                        </ul>
                        <!-- End List Control View -->

                        <div class="right-10 pos-tb-center">
                            <a class="btn btn-o btn-xs pos-a right-10 pos-tb-center" href="{{url('/merchant-view/page')}}">Views All</a>
                        </div>
                    </header>
                    <div class="row row-masnory row-tb-20">

                        @if(!empty($all_latest_coupon_info) && count($all_latest_coupon_info)>0)
                        @foreach($all_latest_coupon_info as $key =>$list)

                        <?php
                            $max_discount_branch=\DB::table('tbl_coupon')
                                    ->where('tbl_coupon.coupon_merchant_id',$list->coupon_merchant_id)
                                    ->leftjoin('tbl_merchant','tbl_coupon.coupon_merchant_id','=','tbl_merchant.merchant_id')
                                    ->leftjoin('tbl_branch','tbl_coupon.coupon_id','=','tbl_branch.branch_id')
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


                            $select_coupon_rating=0;
                            $final_coupon_rating=0;
                            $all_rating_view=\DB::table('tbl_coupon_review_comments')
                                            ->leftjoin('tbl_coupon','tbl_coupon_review_comments.coupon_id','=','tbl_coupon.coupon_id')
                                            ->where('tbl_coupon.coupon_id',$max_discount_branch->coupon_id)
                                            ->get();

                            if(($max_discount_branch->coupon_rating_client_count)>1){
                                $select_coupon_rating=$max_discount_branch->coupon_total_rating;
                                $final_coupon_rating=$select_coupon_rating/$max_discount_branch->coupon_rating_client_count;
                            }
                        ?>
                        <div class="col-sm-6 col-lg-4">
                            <div class="deal-single panel">
                                <figure class="deal-thumbnail embed-responsive embed-responsive-16by9" data-bg-img="{{asset($max_discount_branch->coupon_featured_image)}}">
                                    <div class="label-discount left-20 top-15">
                                    <a href="{{url('single-page/coupon_id-'.$max_discount_branch->coupon_code)}}">{{$max_discount_branch->coupon_discount_rate}}%</a></div>
                                    <ul class="deal-actions top-15 right-20">
                                        <li class="like-deal">
                                            <span>
                                                <a class="add_wish" data-id="{{$max_discount_branch->coupon_id}}" data-status="1">
                                                    @if(!empty($wish_list))
                                                        <p style="color:red;"><i class="fa fa-heart"></i></p>
                                                    @else
                                                        <p id="active_wish_{{$max_discount_branch->coupon_id}}"><i class="fa fa-heart"></i></p>
                                                    @endif
                                                </a>
                                            </span>
                                        </li>
                                        <li class="share-btn">
                                            <div class="share-tooltip fade">
                                                <a href="javascript:void(0)" onclick="javascript:genericSocialShare('http://www.facebook.com/sharer.php?u=yess.com.bd/merchant/branch-view/page/mid-{{$max_discount_branch->coupon_merchant_id}}')"><i class="fa fa-facebook"></i></a>
                                                <a target="_blank" href="#"><i class="fa fa-twitter"></i></a>
                                                <a target="_blank" href="#"><i class="fa fa-google-plus"></i></a>
                                                <a target="_blank" href="#"><i class="fa fa-pinterest"></i></a>
                                            </div>
                                            <span><i class="fa fa-share-alt"></i></span>
                                        </li>
                                        <li>
                                            <span>
                                                <a data-toggle="modal" data-target="#CouponDetailsModal"  data-id="{{$max_discount_branch->coupon_id}}" class="text_none coupon_details_show tooltips" href=""><i class="fa fa-external-link"></i></a>
                                            </span>
                                        </li>
                                    </ul>
                                    <div class="time-left bottom-15 right-20 font-md-14">
                                        <span class="countdown_div">
                                            <span class="t-uppercase btn btn-success" data-countdown="{{$max_discount_branch->coupon_closing_date}} 23:59:59"></span>
                                        </span>
                                    </div>
                                    <div class="deal-store-logo">
                                        <a href="{{url('/merchant/branch-view/page/mid-'.$max_discount_branch->merchant_id)}}">
                                            <img src="{{asset('/assets/images/merchant/'.$max_discount_branch->merchant_logo)}}" alt="">
                                        </a>
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
                                                ( <span class="rating-count">{{($select_coupon_rating)?$select_coupon_rating:'0'}}</span> rates )
                                            </span>
                                            <h3 class="deal-title mb-10">
                                                <a href="{{url('/single-page/coupon_id-'.$max_discount_branch->coupon_id)}}">{{$max_discount_branch->merchant_name}} , {{$max_discount_branch->branch_name}}</a>
                                            </h3>

                                            <p class="text-muted mb-20"><strong> Description : </strong>{{substr($max_discount_branch->merchant_description,0,200)}}</p>
                                        </div>

                                    </div>
                                </div>
                                <div>
                                    <a href="{{url('/single-page/coupon_id-'.$max_discount_branch->coupon_id)}}" class="btn btn-sm btn-block">Coupon Details
                                    </a>
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
                    {{isset($latest_coupon_pagination) ? $latest_coupon_pagination:''}}

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
    <!-- –––––––––––––––[ END PAGE CONTENT ]––––––––––––––– -->
@stop