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
				All Merchant
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
				<form method="get" action="{{url('/dashboard/all-merchant/list')}}">
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

					
						<div class="col-md-2" style="margin-top:22px;">
							<div class="form-group">
								<input type="submit" class="btn btn-primary" data-toggle1="tooltip" title="Search Coupon" value="Search">
							</div>
						</div>
					</div>
				</form>	
			</div>



			<div class="panel-body">
				<div class="table-responsive cost_list posting_list">
					<table class="table table-hover table-bordered table-striped nopadding" id="sample-table-1">
					<!-- <caption>You can not delete or update all type data !!!!</caption> -->
						<thead>
							<tr>
								<th colspan="3" class="text-center">Active Merchant :</th>
								<th colspan="2" class="text-center"> {{($active_merchant)?$active_merchant:'0'}}</th>
								<th colspan="3" class="text-center">Block Merchant :</th>
								<th colspan="2" class="text-center"> {{($block_merchant)?$block_merchant:'0'}}</th>
							</tr>
							<tr>
								<th> SL</th>
								<th> Name</th>
								<th> Merchant Code</th>
								<th> Propiter Name</th>
								<th> Mobile</th>
								<th> Email</th>
								<th> Website</th>
								<th> Ranking</th>
								<th> Status</th>
								<th> Action</th>
							</tr>
						</thead>
						<tbody class="cost_list">
							@if(!empty($all_merchant_info) && count($all_merchant_info) > 0)
							@foreach($all_merchant_info as $key => $list)
							<tr >
								<td>{{$key+1}}</td>
								<td>{{$list->merchant_name}}</td>
								<td>{{$list->merchant_code}}</td>
								<td>{{$list->merchant_propriter}}</td>
								<td>{{$list->merchant_propriter_mobile}}</td>
								<td>{{$list->merchant_email}}</td>
								<td>{{$list->merchant_website_url}}</td>
								<td>
									@if($list->merchant_rank==1)
										<button class="btn btn-success btn-xs merchant_rank_status col-md-12" data-merchant-id="{{$list->merchant_id}}" data-action="0">Top</button>
									@elseif($list->merchant_rank==0)
										<button class="btn btn-teal btn-xs merchant_rank_status col-md-12" data-merchant-id="{{$list->merchant_id}}" data-action="1">Normal</button>
									@endif
								</td>
								<td>
									@if($list->merchant_status==1)
										<button class="btn btn-primary btn-xs merchant_status col-md-12" data-merchant-id="{{$list->merchant_id}}" data-action="-1">Block</button>
									@elseif($list->merchant_status==-1)
										<button class="btn btn-danger btn-xs merchant_status col-md-12" data-merchant-id="{{$list->merchant_id}}" data-action="1">Unblock</button>
									@endif
								</td>
								<td>
									<a href="{{url('/dashboard/merchant/edit-/'.$list->merchant_id)}}" class="btn btn-xs btn-green tooltips" data-toggle1="tooltip" title="Merchant Edit"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
									<a href="{{url('/dashboard/merchant/delete-/'.$list->merchant_id)}}" class="btn btn-xs btn-bricky tooltips" data-toggle1="tooltip" title="Merchant Delete"><i class="fa  fa-trash-o"></i></a>
									<a data-toggle="modal" data-target="#MerchantDetailsModal"  data-id="{{$list->merchant_id}}" class="text_none merchant_details_show btn btn-xs btn-green tooltips" href=""><i class="fa fa-print" aria-hidden="true"></i></a>

								</td>	
							</tr>
							@endforeach
							@else
							<tr class="text-center">
								<td colspan="7">No Data available</td>
							</tr>
							@endif
						</tbody>
					</table>
					{{isset($merchant_list_pagination)?$merchant_list_pagination:''}}
				</div>

			</div>
		</div>
	</div>
</div>

<!-- Modal -->
<div id="MerchantDetailsModal" class="modal fade " rtabindex="-1" role="dialog">
	<div class="modal-dialog ">
		<div class="modal-content">
			
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Merchant Details</h4>
			</div>
			<div class="modal-body">

				<div class="merchant_details">

				</div>

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default"  data-dismiss="modal">OK</button>
			</div>
		</div>
	</div>
</div>

@stop