@extends('layout.master')
@section('content')
<main id="mainContent" class="main-content">
    <div class="page-container ptb-60">
        <div class="container">
            <div class="row row-rl-10 row-tb-20">
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
                <div class="page-content col-xs-12 col-sm-8 col-md-4">
                        <section class="section checkout-area panel prl-30 pt-20 pb-40">
                            <h2 class="h3 mb-20 h-title">Amount Information</h2>
                            <form class="mb-30" method="post" action="{{url('/new/coupon/amount/submit')}}">
                                <input type="hidden" name="_token" value="{{csrf_token()}}">
                                <div class="row">

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Amount</label>
                                            <input type="text" name="shopping_amount" class="form-control" placeholder="Enter Shopping Amount">
                                            <input type="hidden" name="coupon_transaction_id" value="{{$select_transaction_info->coupon_transaction_id}}">
                                            <input type="hidden" name="customer_mobile" value="{{$select_transaction_info->customer_mobile}}">
                                        </div>
                                        <a href="{{url('/client/profile/page/id-'.$select_transaction_info->customer_id)}}" class="btn btn-lg btn-warning btn-rounded pull-left">Cancel</a>
                                    	<input type="submit" value="Submit" class="btn btn-lg btn-rounded mr-10 pull-right">
                                    </div>


                                </div>
                            </form>

                        </section>
                </div>
                <div class="page-sidebar col-xs-12 col-sm-4 col-md-8">

                    <aside class="sidebar blog-sidebar">
                        <div class="row row-tb-10">
                            <div class="col-xs-12">

                                <div class="panel-body" style="width: 100%; overflow: auto;">
                                    <table class="table table-hover table-bordered table-striped text-center">
                                        <thead>
                                            <tr>
                                                <th>SL</th>
                                                <th>Merchant</th>
                                                <th>Branch</th>
                                                <th>Shopping Amount(Tk)</th>
                                                <th>Discount Amount(Tk)</th>
                                                <th>Coupon Price(Tk)</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @if(!empty($client_shopping_info) && count($client_shopping_info)>0)
                                            @foreach($client_shopping_info as $key => $list)
                                            <tr>
                                                <td>{{$key+1}}</td>
                                                <td>{{$list->merchant_name}}</td>
                                                <td>
                                                    <a href="{{url('/single-page/coupon_id-'.$list->coupon_code)}}" class="">{{$list->branch_name}}</a>
                                                </td>

                                                <td>{{number_format($list->coupon_shopping_amount,2)}}</td>
                                                <td>{{number_format($list->coupon_discount_amount,2)}}</td>
                                                <td>{{number_format($list->coupon_buy_price,2)}}</td>
                                                <td>
                                                    @if($list->coupon_status=='-1')
                                                        Active Deal
                                                    @elseif($list->coupon_status=='1')
                                                        Buy Coupon
                                                    @elseif($list->coupon_status=='2')
                                                       	Success
                                                    @endif
                                                </td>
                                            </tr>
                                            @endforeach
                                            @else
                                            <tr>
                                                <td colspan="9">
                                                    <div class="alert alert-info">
                                                        <span class="text-center">You Don't Buy Coupon Yet !</span>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endif
                                        </tbody>

                                    </table>
                                    {{isset($client_shopping_pagination) ? $client_shopping_pagination:""}}
                                </div>

                            </div>
                        </div>
                    </aside>
                </div>
            </div>
        </div>
    </div>

</main>

@stop