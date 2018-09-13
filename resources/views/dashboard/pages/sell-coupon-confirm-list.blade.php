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
				<div class="row">
					<form method="get" action="{{url('/dashboard/all-coupon/confirm/transaction/list')}}">
					<!-- <input type="hidden" name="_token" value="{{csrf_token()}}"> -->

			          <div class="col-md-2">
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

			          <div class="col-md-2">
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



						<?php
							$all_merchant=\DB::table('tbl_merchant')->get();
						?>

						<div class="col-md-2">
							<div class="form-group ">
								
								<div class="input-group">
								<label>
									Merchant<span class="symbol required"></span>
								</label>
									<select name="merchant_name" class="select_merchant_list form-control">
										<option value="0">Select Merchant</option>
										@if(!empty($all_merchant) && count($all_merchant)>0)
										@foreach ($all_merchant as $key => $list){
										<option {{(isset($_GET['merchant_name']) && ($_GET['merchant_name'] == $list->merchant_id)) ? 'selected':''}} value="{{$list->merchant_id}}">{{$list->merchant_name}}</option>
							
										@endforeach
										@endif
										
									</select>

								</div>
							</div>
						</div>

						<?php
							$all_coupon=\DB::table('tbl_coupon')->get();
						?>

						<div class="col-md-2">
							<div class="form-group ">
								<label>
									Coupon<span class="symbol required"></span>
								</label>
								<div class="input-group">
									<select name="coupon_name" class="form-control">
										<option value="0"> Select Coupon </option>
										@if(!empty($all_coupon) && count($all_coupon)>0)
										@foreach ($all_coupon as $key => $list){
										<option {{(isset($_GET['coupon_name']) && ($_GET['coupon_name'] == $list->coupon_code)) ? 'selected':''}} value="{{$list->coupon_code}}">{{$list->coupon_code}}</option>
							
										@endforeach
										@endif
										
									</select>

								</div>
							</div>
						</div>



						<?php
							$user_list=\DB::table('users')->where('user_type','client')->get();
						?>

						<div class="col-md-2">
							<div class="form-group ">
								<label>
									User<span class="symbol required"></span>
								</label>
								<div class="input-group">
									<select name="user_name" class="form-control">
										<option value="0"> Select User </option>
										@if(!empty($user_list) && count($user_list)>0)
										@foreach ($user_list as $key => $list){

										<option {{(isset($_GET['user_name']) && ($_GET['user_name'] == $list->id)) ? 'selected':''}} value="{{$list->id}}">{{$list->name}}</option>

										@endforeach
										@endif
									</select>

 								</div>
							</div>
						</div>

					
						<div class="col-md-1" style="margin-top:22px;">
							<div class="form-group">
								<input type="submit" class="btn btn-primary" data-toggle1="tooltip" title="Confirm Transaction" value="Search">
							</div>
						</div>


					</form>
					<div class="col-md-1" style="margin-top:22px;">
						<div class="pull-right" >
							<a class="btn btn-success" href="{{url('/dashboard/transaction/pdf/from-'.(isset($_GET['search_from']) ? $_GET['search_from'] : date('Y-m-d')).'/to-'.(isset($_GET['search_to']) ? $_GET['search_to'] : date('Y-m-d')).'/mid-'.(isset($_GET['merchant_name'])?$_GET['merchant_name']:'0').'/coid-'.(isset($_GET['coupon_name'])?$_GET['coupon_name']:'0').'/cuid-'.(isset($_GET['user_name'])?$_GET['user_name']:'0'))}}">PDF</a>
						</div>
					</div>


				</div>
			</div>

			<div class="panel-body">
				<div class="table-responsive cost_list posting_list">
					<table class="table table-hover table-bordered table-striped nopadding" id="sample-table-1">
						<thead>
							<tr>
								<th>SL</th>
								<th>Coupon</th>
								<th>Customer Mobile</th>
								<th>Coupon Code </th>
								<th>Discount Rate </th>
								<th>Commission Rate</th>
								<th>Buy Price</th>
								<th>Shopping Amount</th>
								<th>Discount Amount</th>
								<th>Commission Amount</th>
							</tr>
						</thead>
						<tbody class="cost_list">
							@if(!empty($coupon_transaction_info) && count($coupon_transaction_info) > 0)
							@foreach($coupon_transaction_info as $key => $list)
							<tr >
								<td>{{$key+1}}</td>
								<td>{{$list->coupon_id}}</td>
								<td>{{$list->customer_mobile}}</td>
								<td>{{$list->coupon_code}}</td>
								<td>{{$list->coupon_discount_rate}}</td>
								<td>{{$list->coupon_commission_rate}}</td>
								<td>{{$list->coupon_buy_price}}</td>
								<td>{{$list->coupon_shopping_amount}}</td>
								<td>{{$list->coupon_discount_amount}}</td>
								<td>{{$list->coupon_commission_amount}}</td>

							</tr>
							@endforeach
							@else
							<tr class="text-center">
								<td colspan="11">No Data available</td>
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