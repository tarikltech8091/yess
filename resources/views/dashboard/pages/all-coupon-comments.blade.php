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
				All Coupon Comments
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
				<form method="get" action="{{url('/dashboard/all-coupon/comments/list')}}">
					<!-- <input type="hidden" name="_token" value="{{csrf_token()}}"> -->
					<div class="row">

						<?php
							$all_merchant=\DB::table('tbl_merchant')->get();
						?>

						<div class="col-md-3">
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
							$all_branch=\DB::table('tbl_branch')->get();
						?>

						<div class="col-md-3">
							<div class="form-group ">
								<label>
									Branch<span class="symbol required"></span>
								</label>
								<div class="input-group">
									<select name="branch_name" class="branch_list form-control">
										<option value="0"> Select Merchant Branch</option>
									</select>

 								</div>
							</div>
						</div>

					
						<div class="col-md-2" style="margin-top:22px;">
							<div class="form-group">
								<input type="submit" class="btn btn-primary" data-toggle1="tooltip" title="Search Comments" value="Search">
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
								<th>Merchant </th>
								<th>Branch </th>
								<th>Coupon</th>
								<th class="text-center">Comments</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody class="cost_list">
							@if(!empty($coupon_comments_info) && count($coupon_comments_info) > 0)
							@foreach($coupon_comments_info as $key => $list)
							<tr >
								<td>{{$key+1}}</td>
								<td>{{$list->merchant_name}}</td>
								<td>{{$list->branch_name}}</td>
								<td>{{$list->coupon_code}}</td>
								<td>{{$list->coupon_comments}}</td>

								<td>
									<a href="{{url('/dashboard/coupon/comments/id-'.$list->review_comments_id)}}" class="btn btn-xs btn-bricky tooltips" data-toggle1="tooltip" title="Comments Delete"><i class="fa  fa-trash-o"></i></a>
								</td>	
							</tr>
							@endforeach
							@else
							<tr class="text-center">
								<td colspan="6">No Data available</td>
							</tr>
							@endif
						</tbody>
					</table>
					{{isset($coupon_comments_pagination)?$coupon_comments_pagination:''}}
					<input type="hidden" class="site_url" value="{{url('/')}}">
					<input type="hidden" name="current_page_url" class="current_page_url" value="{{\Request::fullUrl()}}">
				</div>

			</div>
		</div>
	</div>
</div>

@stop