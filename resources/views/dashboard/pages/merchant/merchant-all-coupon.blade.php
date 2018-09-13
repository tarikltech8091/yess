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
				<form method="get" action="{{url('/dashboard/all-coupon/transaction/list')}}">
					<!-- <input type="hidden" name="_token" value="{{csrf_token()}}"> -->
					<div class="row">

			          <div class="col-md-3">
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

			          <div class="col-md-3">
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
							$user_list=\DB::table('users')->where('user_type','user')->get();
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
								<th>Coupon</th>
								<th>Customer Mobile</th>
								<th>Coupon Code </th>
								<th>Discount Rate </th>
								<th>Commission Rate</th>
								<th>Buy Price</th>
								<th>Shopping Amount</th>
								<!-- <th>Action</th> -->
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

								<!-- <td> -->
									<!-- <a href="{{url('/dashboard/coupon/edit-/'.$list->coupon_transaction_id)}}" class="btn btn-xs btn-green tooltips" data-toggle1="tooltip" title="Coupon Edit"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
									<a href="{{url('/dashboard/coupon/delete-/'.$list->coupon_transaction_id)}}" class="btn btn-xs btn-bricky tooltips" data-toggle1="tooltip" title="Coupon Delete"><i class="fa  fa-trash-o"></i></a> -->
									<!-- <a data-toggle="modal" data-target="#UserDetailsModal"  data-id="{{$list->coupon_transaction_id}}" class="text_none user_details_show btn btn-xs btn-green tooltips" href=""><i class="fa fa-print" aria-hidden="true"></i></a> -->
								<!-- </td>	 -->
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