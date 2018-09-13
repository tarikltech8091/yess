@extends('layout.master')
@section('content')
<!-- –––––––––––––––[ PAGE CONTENT ]––––––––––––––– -->
<main id="mainContent" class="main-content">
    <!-- Page Container -->
    <div class="page-container ptb-60">
        <div class="container">
            <div class="row row-rl-10 single_page_margin">
                <div class="col-md-12">
                    @if(Session::has('message'))
                        <div class="alert alert-success" role="alert">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                            {{ Session::get('message') }}
                        </div> 
                    @endif
                </div>


                <div id="printMainSlider">
                    <div class="page-content col-xs-12 col-sm-7 col-md-7">
                        <div class="row row-tb-20">

                         @if(!empty($select_coupon_info) && count($select_coupon_info)>0)
                            <div class="col-xs-12">
                                <div class="deal-deatails panel">
                                    <img alt="" src="{{asset($select_coupon_info->coupon_featured_image)}}">
                                        <div>
                                            @if(\Auth::check())
                                                <a href="{{url('/active/deal/coupon-'.$select_coupon_info->coupon_code.'/mobile-'.\Auth::user()->mobile)}}" class="btn btn-sm btn-block">Claim Deal
                                                </a>
                                            @else
                                                <a href="{{url('/sign-in/page')}}" class="btn btn-sm btn-block">Claim Deal
                                                </a>
                                            @endif
                                        </div>
                                    <div class="deal-body p-20">
                                        <div class="time-left mb-30">
                                            <p>
                                                <h3 class="mb-10">{{$select_coupon_info->merchant_name}}</h3>{{$select_coupon_info->branch_address}}
                                            </p>
                                            @if(\Auth::check())
                                            <p>
                                                <span><strong>Coupon Code :</strong> {{$select_coupon_info->coupon_code}}</span>
                                            </p>
                                            @endif
                                        </div>

                                        <div class="time-left mb-30">
                                            <p class="t-uppercase color-muted">
                                                Hurry up Only a few Coupons left
                                            </p>
                                            <div class="color-mid font-14 font-lg-16">
                                                <span  class="t-uppercase btn btn-success" data-countdown="{{$select_coupon_info->coupon_closing_date}} 23:59:59"></span>
                                            </div>
                                        </div>

                                         <h2 class="price mb-15" style="font-size:20px;"><strong> How to Get Discount </strong></h2>
                                        <p class="mb-15">
                                            <iframe width="100%" height="315" src="https://www.youtube.com/embed/L0gQBH0NLJ0" frameborder="0" allowfullscreen></iframe>
                                        </p>

                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12">
                                <div class="posted-review panel p-30">
                                    <h3 class="h-title">Review</h3>
                                    <div style="width: 100%; height: 432px; overflow: auto; padding: 5px">
                                        @if(!empty($select_coupon_review_info) && count($select_coupon_review_info)>0)
                                        @foreach($select_coupon_review_info as $key => $list)
                                        <div class="review-single pt-30">
                                            <div class="media">
                                                <div class="media-body">

                                                    <div class="review-wrapper clearfix">
                                                        <ul class="list-inline">

                                                            <span class="rating_view pull-right" >
                                                                @if(!empty($list->coupon_rating))
                                                                @for($i=1;$i<=5;$i++)
                                                                <span  style="{{isset($list->coupon_rating) && ($i <= $list->coupon_rating) ? 'color:#f70' : ''}}"></span>
                                                                @endfor

                                                                @else
                                                                @for($i=1;$i<=5;$i++)
                                                                <span></span>
                                                                @endfor
                                                                @endif
                                                            </span>
                                                            
                                                            <li>
                                                                <span class="pull-left">
                                                                    @if(!empty($list->user_profile_image))
                                                                    <img src="{{asset('assets/images/userprofile/small-icon/'.$list->user_profile_image)}}">
                                                                    @else
                                                                    <img src="{{asset('assets/images/userprofile/small-icon/default.jpg')}}">
                                                                    @endif
                                                                </span>
                                                            </li><br>
                                                            <li>
                                                                <span class="review-holder-name h5"> {{($list->name)?$list->name :''}}</span>
                                                            </li>
                                                        </ul>
                                                        <p class="review-date mb-5">{{date('Y-m-d'),($list->created_at)?$list->created_at :''}}</p>
                                                        <p class="copy">{{substr($list->coupon_comments,0,50)}}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                        @endif
                                    </div>
                                </div>
                                
                            </div>
                        @else
                            <div class="col-sm-12 col-lg-12 col-md-12">
                                <h3 class="text-center"> Merchant Not Found</h3>
                            </div>
                        @endif

                        </div>
                    </div>
                    <div class="page-sidebar col-md-5 col-sm-5 col-xs-12">
                        <!-- Blog Sidebar -->
                        <aside class="sidebar blog-sidebar">
                            <div class="row row-tb-10">



                                 <div class="col-xs-12">
                                    <div class="widget latest-deals-widget panel prl-20 mr-15 ml-15">
                                        <div class="widget-body ptb-20">
                                            <div id="map" style="height: 400px; width: 100%;">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xs-12">
                                    <!-- Latest Deals Widegt -->
                                    <div class="widget latest-deals-widget panel prl-20">
                                        <div class="widget-body ptb-20">
                                            <div class="owl-slider" data-loop="true" data-autoplay="true" data-autoplay-timeout="10000" data-smart-speed="1000" data-nav-speed="false" data-nav="true" data-xxs-items="1" data-xxs-nav="true" data-xs-items="1" data-xs-nav="true" data-sm-items="1" data-sm-nav="true" data-md-items="1" data-md-nav="true" data-lg-items="1" data-lg-nav="true">
                                            <?php
                                                $related_coupon_info=\DB::table('tbl_coupon')
                                                    ->where('tbl_coupon.coupon_sub_category_id',$select_coupon_info->coupon_sub_category_id)
                                                    ->where('tbl_coupon.coupon_closing_date','>=',date('Y-m-d').' 23:59:59')
                                                    ->where('tbl_coupon.coupon_merchant_id','!=',$select_coupon_info->coupon_merchant_id)
                                                    ->select(['tbl_coupon.coupon_merchant_id', DB::raw('MAX(tbl_coupon.coupon_discount_rate) AS coupon_discount_rate')])
                                                    ->groupBy('tbl_coupon.coupon_merchant_id')
                                                    ->get();
                                                if(!empty($related_coupon_info) && count($related_coupon_info)>1){
                                                foreach ($related_coupon_info as $key => $coupon) {
                                                
                                                    $max_discount_branch=\DB::table('tbl_coupon')
                                                        ->where('tbl_coupon.coupon_merchant_id',$coupon->coupon_merchant_id)
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
                                                <div class="latest-deals__item item">
                                                    <figure class="deal-thumbnail embed-responsive embed-responsive-4by3" data-bg-img="{{asset($max_discount_branch->coupon_featured_image)}}">
                                                        <div class="label-discount top-10 right-10">{{$max_discount_branch->coupon_discount_rate}}%</div>
                                                        <ul class="deal-actions top-10 left-10">
                                                            <li class="like-deal">
                                                                <span>
                                                                    <a class="add_wish" data-id="{{$max_discount_branch->coupon_id}}" data-status="1">
                                                                        @if(!empty($wish_list))
                                                                            <p style="color:red;"><i class="fa fa-heart"></i></p>
                                                                        @else
                                                                            <p id="active_wish_{{$max_discount_branch->coupon_id}}"><i class="fa fa-heart "></i></p>
                                                                        @endif
                                                                    </a>
                                                                </span>
                                                            </li>
                                                            <li class="share-btn">
                                                                <div class="share-tooltip fade">
                                                                    <a target="_blank" href="#"><i class="fa fa-facebook"></i></a>
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
                                                        <div class="deal-about p-10 pos-a bottom-0 left-0">
                                                            <div class="rating mb-10">
                                                                
                                                            </div>
                                                            <div class="countdown_color">
                                                                <span>
                                                                    <i class="ico fa fa-clock-o mr-10"></i>
                                                                    <span class="t-uppercase" data-countdown="{{$max_discount_branch->coupon_closing_date}}"></span>
                                                                </span>
                                                            </div>

                                                            <h3 class="deal-title mb-10">
                                                                <a href="{{url('/merchant/branch-view/page/mid-'.$max_discount_branch->merchant_id)}}" class="color-lighter">{{$max_discount_branch->merchant_name}}</a>
                                                            </h3>

                                                        </div>
                                                    </figure>
                                                </div>
                                            <?php 
                                                }
                                                }else{ 
                                            ?>

                                                <div class="latest-deals__item item">
                                                    <figure class="deal-thumbnail embed-responsive embed-responsive-4by3" data-bg-img="{{asset('main-assets/images/deals/deal_03.jpg')}}">
                                                    </figure>
                                                </div>
                                                <div class="latest-deals__item item">
                                                    <figure class="deal-thumbnail embed-responsive embed-responsive-4by3" data-bg-img="{{asset('main-assets/images/deals/deal_02.jpg')}}">
                                                    </figure>
                                                </div>
                                                
                                            <?php }?>


                                            </div>
                                        </div>
                                    </div>
                                    <!-- End Latest Deals Widegt -->
                                </div>
                                <div class="col-xs-12">
                                    <h4 class="review_rating">Post Coupon Review & Rating</h4>
                                    <div class="form-group col-md-12">
                                        <fieldset class="rating coupon_id" data-coupon-id="{{$select_coupon_info->coupon_id}}">
                                            <input type="radio" class="ratings" id="star5" name="rating" value="5" {{isset($client_rating->coupon_rating) && ($client_rating->coupon_rating == '5') ? 'checked' : ''}} /><label for="star5" title="Rocks!">5 stars</label>
                                            <input type="radio" class="ratings" id="star4" name="rating" value="4" {{isset($client_rating->coupon_rating) && ($client_rating->coupon_rating == '4') ? 'checked' : ''}} /><label for="star4" title="Pretty good">4 stars</label>
                                            <input type="radio" class="ratings" id="star3" name="rating" value="3" {{isset($client_rating->coupon_rating) && ($client_rating->coupon_rating == '3') ? 'checked' : ''}} /><label for="star3" title="Meh">3 stars</label>
                                            <input type="radio" class="ratings" id="star2" name="rating" value="2" {{isset($client_rating->coupon_rating) && ($client_rating->coupon_rating == '2') ? 'checked' : ''}} /><label for="star2" title="Kinda bad">2 stars</label>
                                            <input type="radio" class="ratings" id="star1" name="rating" value="1" {{isset($client_rating->coupon_rating) && ($client_rating->coupon_rating == '1') ? 'checked' : ''}} /><label for="star1" title="Sucks big time">1 star</label>
                                        </fieldset>
                                    </div>
                                </div>
                                <div class="col-xs-12">

                                    <div class="post-review panel p-20">
                                        <h3 class="h-title">Post Review</h3>
                                        <form class="horizontal-form pt-30" action="{{url('/coupon/review/'.$select_coupon_info->coupon_id)}}" method="post">
                                            <input type="hidden" name="_token" value="{{csrf_token()}}">

                                            <div class="row row-v-10">
                                                <div class="col-xs-12 form-group">
                                                    <textarea class="form-control" placeholder="Your Review" name="coupon_comments" rows="12"></textarea>
                                                </div>
                                                    <input type="hidden" name="coupon_id" value="{{$select_coupon_info->coupon_id}}">

                                                <div class="col-xs-12 text-right">
                                                    <button type="submit" class="btn mt-20">Submit review</button>

                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                            </div>
                        </aside>
                        <!-- End Blog Sidebar -->
                    </div>
                </div>

                <div id="printOnly">
                    <div class="page-content col-xs-12 col-sm-7 col-md-7">
                        <div class="row row-tb-20">

                         @if(!empty($select_coupon_info) && count($select_coupon_info)>0)
                            <div class="col-xs-12">
                                <div class="deal-deatails panel">
                                    <img alt="Coupon Image" src="{{asset($select_coupon_info->coupon_featured_image)}}">

                                    <div class="claim_deal">
                                        @if(\Auth::check())
                                            <a href="{{url('/active/deal/coupon-'.$select_coupon_info->coupon_code.'/mobile-'.\Auth::user()->mobile)}}" class="btn btn-sm btn-block">Claim Deal
                                            </a>
                                        @else
                                            <a href="{{url('/sign-in/page')}}" class="btn btn-sm btn-block">Claim Deal
                                            </a>
                                        @endif
                                    </div>
                                    <div class="deal-body p-10" style="margin-top:-20px;">

                                        <div class="time-left mb-10">
                                            <p>
                                                <h3 class="mb-5">{{$select_coupon_info->merchant_name}}</h3>{{$select_coupon_info->branch_address}}
                                            </p>
                                        </div>

                                        <div class="time-left mb-10">
                                            <p class="t-uppercase color-muted">
                                                Hurry up Only a few Coupons left
                                            </p>
                                            <div class="color-mid font-14 font-lg-16">
                                                <span  class="t-uppercase btn btn-success" data-countdown="{{$select_coupon_info->coupon_closing_date}} 23:59:59"></span>
                                            </div>
                                        </div>

                                        <h2 class="price mb-15" style="font-size:16px;"><strong>How to Get Discount</strong></h2>
                                        <p class="mb-15">
                                            <iframe width="100%" height="200" src="https://www.youtube.com/embed/L0gQBH0NLJ0" frameborder="0" allowfullscreen></iframe>
                                        </p>

                                    </div>
                                </div>
                            </div>

                                <div class="col-xs-12">
                                    <div class="posted-review panel p-10">
                                        <h3 class="h-title">Review</h3>
                                        <div style="width: 100%; height: 216px; overflow: auto; padding: 5px">
                                            @if(!empty($select_coupon_review_info) && count($select_coupon_review_info)>0)
                                            @foreach($select_coupon_review_info as $key => $list)
                                            <div class="review-single pt-10">
                                                <div class="media">
                                                    <div class="media-body">

                                                        <div class="review-wrapper clearfix">
                                                            <ul class="list-inline">

                                                                <span class="rating_view pull-right" >
                                                                    @if(!empty($list->coupon_rating))
                                                                    @for($i=1;$i<=5;$i++)
                                                                    <span  style="{{isset($list->coupon_rating) && ($i <= $list->coupon_rating) ? 'color:#f70' : ''}}"></span>
                                                                    @endfor

                                                                    @else
                                                                    @for($i=1;$i<=5;$i++)
                                                                    <span></span>
                                                                    @endfor
                                                                    @endif
                                                                </span>
                                                                
                                                                <li>
                                                                    <span class="pull-left">
                                                                        @if(!empty($list->user_profile_image))
                                                                        <img src="{{asset('assets/images/userprofile/small-icon/'.$list->user_profile_image)}}">
                                                                        @else
                                                                        <img src="{{asset('assets/images/userprofile/small-icon/default.jpg')}}">
                                                                        @endif
                                                                    </span>
                                                                </li><br>
                                                                <li>
                                                                    <span class="review-holder-name h5"> {{($list->name)?$list->name :''}}</span>
                                                                </li>
                                                            </ul>
                                                            <p class="review-date mb-5">{{date('Y-m-d'),($list->created_at)?$list->created_at :''}}</p>
                                                            <p class="copy">{{substr($list->coupon_comments,0,50)}}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @endforeach
                                            @endif
                                        </div>
                                    </div>
                                </div>

                        @else
                            <div class="col-sm-12 col-lg-12 col-md-12">
                                <h3 class="text-center"> Merchant Not Found</h3>
                            </div>
                        @endif

                        </div>
                    </div>
                    <div class="page-sidebar col-md-5 col-sm-5 col-xs-12">
                        <!-- Blog Sidebar -->
                        <aside class="sidebar blog-sidebar">
                            <div class="row row-tb-10">


                                <div class="widget latest-deals-widget panel prl-20 mr-15 ml-15">
                                    <div class="widget-body ptb-20">
                                        <div id="map2" style="height: 200px; width: 100%;">
                                    </div>
                                </div>


                                <div class="col-xs-12">
                                    <h4 class="review_rating">Post Coupon Review & Rating</h4>
                                    <div class="form-group col-md-12">
                                        <fieldset class="rating coupon_id" data-coupon-id="{{$select_coupon_info->coupon_id}}">
                                            <input type="radio" class="ratings" id="star5" name="rating" value="5" {{isset($client_rating->coupon_rating) && ($client_rating->coupon_rating == '5') ? 'checked' : ''}} /><label for="star5" title="Rocks!">5 stars</label>
                                            <input type="radio" class="ratings" id="star4" name="rating" value="4" {{isset($client_rating->coupon_rating) && ($client_rating->coupon_rating == '4') ? 'checked' : ''}} /><label for="star4" title="Pretty good">4 stars</label>
                                            <input type="radio" class="ratings" id="star3" name="rating" value="3" {{isset($client_rating->coupon_rating) && ($client_rating->coupon_rating == '3') ? 'checked' : ''}} /><label for="star3" title="Meh">3 stars</label>
                                            <input type="radio" class="ratings" id="star2" name="rating" value="2" {{isset($client_rating->coupon_rating) && ($client_rating->coupon_rating == '2') ? 'checked' : ''}} /><label for="star2" title="Kinda bad">2 stars</label>
                                            <input type="radio" class="ratings" id="star1" name="rating" value="1" {{isset($client_rating->coupon_rating) && ($client_rating->coupon_rating == '1') ? 'checked' : ''}} /><label for="star1" title="Sucks big time">1 star</label>
                                        </fieldset>
                                    </div>
                                </div>
                                <div class="col-xs-12">

                                    <div class="post-review panel p-20">
                                        <h3 class="h-title">Post Review</h3>
                                        <form class="horizontal-form pt-30" action="{{url('/coupon/review/'.$select_coupon_info->coupon_id)}}" method="post">
                                            <input type="hidden" name="_token" value="{{csrf_token()}}">

                                            <div class="row row-v-10">
                                                <div class="col-xs-12 form-group">
                                                    <textarea class="form-control" placeholder="Your Review" name="coupon_comments" rows="5"></textarea>
                                                </div>
                                                    <input type="hidden" name="coupon_id" value="{{$select_coupon_info->coupon_id}}">

                                                <div class="col-xs-12 text-right">
                                                    <button type="submit" class="btn mt-20">Submit review</button>

                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                            </div>
                        </aside>
                        <!-- End Blog Sidebar -->
                    </div>
                </div>



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

    <!-- Modal -->
    <div id="ActiveDealModal" class="modal fade " rtabindex="-1" role="dialog">
        <div class="modal-dialog ">
            <div class="modal-content">
                
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Active Deal</h4>
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
            title: feature.branch_name,
            icon:'../assets/images/3.png',
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
            title: feature.branch_name,
            icon:'../assets/images/3.png',
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



@stop

