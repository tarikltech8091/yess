@extends('dashboard.layout.master')
@section('content')

<!--error message*******************************************-->
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
<!--end of error message*************************************-->

<div class="row">
	<div class="col-md-12">

		<div class="col-md-12 alert alert-success">
				<form method="get" action="{{url('/dashboard/sell-quantity')}}">
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
							$all_merchant=\DB::table('tbl_merchant')->get();
						?>

						<div class="col-md-2">
							<div class="form-group ">
								<label for="form-field-23">
									Merchant<span class="symbol required"></span>
								</label>
								<div class="input-group">
									<select name="merchant_name" class="form-control select_merchant_list">
										<option value=""> Select Merchant</option>
										@if(!empty($all_merchant) && count($all_merchant)>0)
										@foreach ($all_merchant as $key => $list){
										<option {{(isset($_GET['merchant_name']) && ($_GET['merchant_name'] == $list->merchant_id)) ? 'selected':''}} value="{{$list->merchant_id}}">{{$list->merchant_name}}</option>
							
										@endforeach
										@endif
										
									</select>

								</div>
							</div>
						</div>


						<div class="col-md-2">
							<div class="form-group ">
								<label for="form-field-23">
									Branch<span class="symbol required"></span>
								</label>
								<div class="input-group">
									<select name="branch_name" class="form-control branch_list">
										<option value=""> Select Branch</option>

									</select>

 								</div>
							</div>
						</div>

					
						<div class="col-md-2" style="margin-top:22px;">
							<div class="form-group">
								<input type="submit" class="btn btn-primary" data-toggle1="tooltip" title="Search Transaction" value="Search">
							</div>
						</div>
					</div>
				</form>	
			</div>

		<div class="col-md-12 alert alert-success">

				<div class="row">
					<div class="col-md-4">
						<div class="report_view reprt_color_1 cursor dashborad_menus centered">
							<p>	
								<i class="fa fa-ticket" aria-hidden="true"></i>
							</p>
							<p class="report_name">	
								<strong>Total Sell Coupon</strong></br> {{$total_sell_coupon}}
							</p>
						</div>
					</div>


					

					<div class="col-md-4">
						<div class="report_view reprt_color_1 cursor dashborad_menus centered">
							<p>	
								<a href="">
									<i class="fa fa-money" aria-hidden="true"></i>
								</a>
							</p>
							<p class="report_name">	
								Total Sell Coupon Amount</br> {{$total_sell_coupon_amount}}
							</p>
						</div>
					</div>

					<div class="col-md-4">
						<div class="report_view reprt_color_1 cursor dashborad_menus centered">
							<p>	
								<i class="fa fa-ticket" aria-hidden="true"></i>
							</p>
							<p class="report_name">	
								Total Sell Confirm Coupon</br>{{$total_sell_confirm_coupon}}
							</p>
						</div>
					</div>
					
				</div><br>

				<div class="row">
					<div class="col-md-4">
						<div class="report_view reprt_color_1 cursor dashborad_menus centered">
							<p>	
								<i class="fa fa-money" aria-hidden="true"></i>
							</p>
							<p class="report_name">	
								Total Shopping Amount</br>{{$total_shopping_amount}}
							</p>
						</div>
					</div>



					<div class="col-md-4">
						<div class="report_view reprt_color_1 cursor dashborad_menus centered">
							<p>	
								<a href="">
									<i class="fa fa-credit-card" aria-hidden="true"></i>
								</a>
							</p>
							<p class="report_name">	
								Total Discount Amount</br> {{$total_discount_amount}}
							</p>
						</div>
					</div>

					<div class="col-md-4">
						<div class="report_view reprt_color_1 cursor dashborad_menus centered">
							<p>	
								<a href="">
									<i class="fa fa-money" aria-hidden="true"></i>
								</a>
							</p>
							<p class="report_name">	
								Total Commission Amount</br> {{$total_commisssion_amount}}
							</p>
						</div>
					</div>
				</div>
		</div>
	</div>
</div>

@stop