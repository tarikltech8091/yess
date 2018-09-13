@extends('layout.master')
@section('content')
        <!-- –––––––––––––––[ PAGE CONTENT ]––––––––––––––– -->
        <main id="mainContent" class="main-content">
            <!-- Page Container -->
            <div class="page-container ptb-10">
                <div class="container">
                    <section class="stores-area stores-area-v1">
                        <h3 class="mb-40 t-uppercase">View deals by stores</h3>
                        <div class="row row-rl-15 row-tb-15 t-center">
                        @if(!empty($all_merchant_info) && count($all_merchant_info)>0)
                        @foreach($all_merchant_info as $key =>$list)
                            <div class="col-xs-6 col-sm-4 col-md-3 col-lg-2">
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
                        
                    </section>
                    
                    <div class="col-md-12 pull-left">{{isset($merchant_pagination) ? $merchant_pagination:''}}</div>
                </div>
            </div>
            <!-- End Page Container -->


        </main>
        <!-- –––––––––––––––[ END PAGE CONTENT ]––––––––––––––– -->
@stop