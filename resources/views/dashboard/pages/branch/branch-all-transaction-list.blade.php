@extends('dashboard.layout.master')
@section('content')
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

<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="clip-users-2"></i>
				All Coupon
				<div class="panel-tools">
					<a class="btn btn-xs btn-link panel-collapse collapses" data-toggle="tooltip" data-placement="top" title="Show / Hide" href="#">
					</a>
					<a class="btn btn-xs btn-link panel-config" data-toggle="tooltip" data-placement="top" title="Add Account" href="#">
						<i class="clip-folder-plus"></i>
					</a>
					<a class="btn btn-xs btn-link panel-close red-tooltip" data-toggle="tooltip" data-placement="top" title="Close" href="#">
						<i class="fa fa-times"></i>
					</a>
				</div>
			</div>


			<div class="panel-body">
				<form method="get" action="{{url('/dashboard/branch/coupon-transaction/list')}}">
					<!-- <input type="hidden" name="_token" value="{{csrf_token()}}"> -->
					<div class="row">

			          <div class="col-md-5">
			            <div class="form-group ">
			              <label for="form-field-23">
			                From<span class="symbol required"></span>
			              </label>
			              <div class="input-group">
			                <input type="text" data-date-format="yyyy-mm-dd" data-date-viewmode="years" class="form-control date-picker" name="search_from" value="{{(isset($_GET['search_from']) ? $_GET['search_from'] : date("Y-m-d"))}}" placeholder="">
			                <span class="input-group-addon"> <i class="fa fa-calendar"></i> </span>
			              </div>
			            </div>
			          </div>

			          <div class="col-md-5">
			            <div class="form-group ">
			              <label for="form-field-23">
			                To<span class="symbol required"></span>
			              </label>
			              <div class="input-group">
			                <input type="text" data-date-format="yyyy-mm-dd" data-date-viewmode="years" class="form-control date-picker" name="search_to" value="{{(isset($_GET['search_to']) ? $_GET['search_to'] : date("Y-m-d"))}}">
			                <span class="input-group-addon"> <i class="fa fa-calendar"></i> </span>
			              </div>
			            </div>
			          </div>
					
						<div class="col-md-2" style="margin-top:22px;">
							<div class="form-group">
								<input type="submit" class="btn btn-primary" data-toggle1="tooltip" title="Search All Transaction" value="Search">
							</div>
						</div>
					</div>
				</form>	
			</div>

			<div class="panel-body">
				<div class="table-responsive cost_list posting_list">
					<table class="table table-hover table-bordered table-striped nopadding" id="sample-table-1">
						<thead>
							<tr>
								<th>SL</th>
								<th>Customer Mobile</th>
								<th>Coupon Code </th>
								<th>Secret Code </th>
								<th>Discount Rate </th>
								<th>Commission Rate</th>
								<th>Discount Amount </th>
								<th>Commission Amount</th>
								<th>Coupon Price</th>
								<th>Shopping Amount</th>
								<th>Staus</th>
							</tr>
						</thead>

						<tbody class="cost_list">
							@if(!empty($coupon_transaction_info) && count($coupon_transaction_info) > 0)
							@foreach($coupon_transaction_info as $key => $list)

							<tr>
								<td>{{$key+1}}</td>
								<td>{{$list->customer_mobile}}</td>
								<td>{{$list->coupon_code}}</td>
								<td>{{$list->coupon_secret_code}}</td>
								<td>{{$list->coupon_discount_rate}}</td>
								<td>{{$list->coupon_commission_rate}}</td>
								<td>{{$list->coupon_discount_amount}}</td>
								<td>{{$list->coupon_commission_amount}}</td>
								<td>{{$list->coupon_buy_price}}</td>
								<td>{{$list->coupon_shopping_amount}}</td>

								<td>
								@if(($list->coupon_status) == 1)
								<button class="btn btn-primary btn-xs">Confirm</button>
								@elseif(($list->coupon_status) == 2)
								<span class="btn btn-success">Success </span>
								@endif
								</td>

							</tr>
							@endforeach
							<tr >
								<td align="center" colspan="6"><strong> Total </strong></td>
								<th>{{$total_discount}}</th>
								<th>{{$total_commission}}</th>
								<th>{{$total_coupon_buy_price}}</th>
								<th colspan="2">{{$total_amount}}</th>
							</tr>
							@else
							<tr class="text-center">
								<td colspan="12">No Data available</td>
							</tr>
							@endif
						</tbody>
					</table>
					{{isset($coupon_transaction_pagination)?$coupon_transaction_pagination:''}}
					<input type="hidden" class="site_url" value="{{url('/')}}">
					<input type="hidden" name="current_page_url" class="current_page_url" value="{{\Request::fullUrl()}}">
				</div>

			</div>
		</div>
	</div>
</div>

@stop