@extends('dashboard.layout.master')
@section('content')
 
 <!--error message*******************************************-->
 <div class="row page_row">
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
	<div class="row margin_top_20">
		<div class="col-sm-6">
			<!-- start: TEXT AREA PANEL -->
			<div class="panel panel-default">
				<div class="panel-heading">
					<i class="fa fa-external-link-square"></i>
					 Add Branch
					<div class="panel-tools">
						<a class="btn btn-xs btn-link panel-collapse collapses" href="#">
						</a>
						<a class="btn btn-xs btn-link panel-config" href="#panel-config" data-toggle="modal">
							<i class="fa fa-wrench"></i>
						</a>
						<a class="btn btn-xs btn-link panel-refresh" href="#">
							<i class="fa fa-refresh"></i>
						</a>
						<a class="btn btn-xs btn-link panel-expand" href="#">
							<i class="fa fa-resize-full"></i>
						</a>
						<a class="btn btn-xs btn-link panel-close" href="#">
							<i class="fa fa-times"></i>
						</a>
					</div>
				</div>
				<div class="panel-body insert">
				<form method="post" action="{{url('/dashboard/merchant-branch')}}" enctype="multipart/form-data">
					<input type="hidden" name="_token" value="{{csrf_token()}}">

					<div class="form-group">
						<label> Merchant Name <span class="symbol required"></span></label>
						<select class="form-control" name="merchant_id">
							<option value="">Select Marchant</option>
							@if(!empty($merchant_info))
							@foreach($merchant_info As $key =>$list)
							<option value="{{$list->merchant_id}}">{{$list->merchant_name}}</option>
							@endforeach
							@endif
						</select>
					</div>

					<div class="form-group">
						<label> Branch Name <span class="symbol required"></span></label>
						<input type="text" id="form-field-3" class="form-control" name="branch_name" value="{{old('branch_name')}}">
					</div>

					<div class="form-group">
						<label> Branch Code <span class="symbol required"></span></label>
						<input type="text" id="form-field-3" class="form-control" name="branch_code" value="{{old('branch_code')}}">
					</div>
					<div class="form-group">
						<label> Branch Mobile <span class="symbol required"></span></label>
						<input type="text" id="form-field-3" class="form-control" name="branch_mobile" value="{{old('branch_mobile')}}">
					</div>
					<div class="form-group">
						<label> Branch City <span class="symbol required"></span></label>
						<input type="text" id="form-field-3" class="form-control" name="branch_city" value="{{old('branch_city')}}">
					</div>
					<div class="form-group">
						<label> Branch GPRS Location Lat</label>
						<input type="text" id="form-field-3" class="form-control" name="branch_gprs_lat" value="{{old('branch_gprs_lat')}}">
					</div>
					<div class="form-group">
						<label> Branch GPRS Location Lng</label>
						<input type="text" id="form-field-3" class="form-control" name="branch_gprs_lng" value="{{old('branch_gprs_lng')}}">
					</div>
					<div class="form-group">
						<label> Branch Email <span class="symbol required"></span></label>
						<input type="text" id="form-field-3" class="form-control" name="branch_email" value="{{old('branch_email')}}">
					</div>
					<div class="form-group">
						<label> Branch Address <span class="symbol required"></span></label>
						<textarea class="form-control" name="branch_address" cols="50" rows="3"></textarea>
					</div>

					<div class="form-group">
						<input type="reset" class="btn btn-danger" value="Reset">
						<input type="submit" class="btn btn-primary" value="Save">
					</div>
				</form>	
				</div>

			</div>
			<!-- end: TEXT AREA PANEL -->
		</div>


		<div class="col-sm-6">
			<!-- start: TEXT AREA PANEL -->
			<div class="panel panel-default">
				<div class="panel-heading">
					<i class="fa fa-external-link-square"></i>
					All Branch
					<div class="panel-tools">
						<a class="btn btn-xs btn-link panel-collapse collapses" href="#">
						</a>
						<a class="btn btn-xs btn-link panel-config" href="#panel-config" data-toggle="modal">
							<i class="fa fa-wrench"></i>
						</a>
						<a class="btn btn-xs btn-link panel-refresh" href="#">
							<i class="fa fa-refresh"></i>
						</a>
						<a class="btn btn-xs btn-link panel-expand" href="#">
							<i class="fa fa-resize-full"></i>
						</a>
						<a class="btn btn-xs btn-link panel-close" href="#">
							<i class="fa fa-times"></i>
						</a>
					</div>
				</div>
				<div class="panel-body">
					<form method="get" action="{{url('/dashboard/merchant-branch')}}">
						<div class="row">

							<?php
								$all_merchant=\DB::table('tbl_merchant')->get();
							?>

							<div class="col-md-6">
								<div class="form-group ">
									
									<div class="input-group">
									<label>
										Merchant<span class="symbol required"></span>
									</label>
										<select name="merchant_name" class="form-control">
											<option value="0">Select Merchant</option>
											@if(!empty($merchant_info) && count($merchant_info)>0)
											@foreach ($merchant_info as $key => $list){
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
				<div class="table-responsive">
					<table class="table table-hover table-bordered table-striped nopadding" id="sample-table-1">
					<thead>
						<tr>
							<th>SL</th>
							<th> Merchant</th>
							<th> Branch</th>
							<th> Code</th>
							<th> Mobile</th>
							<!-- <th> Address</th> -->
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						@if(!empty($branch_info) && count($branch_info) > 0)
						@foreach($branch_info as $key => $list)
						<tr >
							<td>{{$key+1}}</td>
							<td>{{$list->merchant_name}}</td>
							<td>{{$list->branch_name}}</td>
							<td>{{$list->branch_code}}</td>
							<td>{{$list->branch_mobile}}</td>
							<!-- <td>{{substr($list->branch_address, 0,20)}}...</td> -->
							<td>
								<a href="{{url('/dashboard/branch/edit-',$list->branch_id)}}" class="btn btn-green btn-xs tooltips"><i class="fa fa-pencil-square-o" aria-hidden="true" data-toggle1="tooltip" title="Attribute Class Edit"></i></a>
								<a href="{{url('/dashboard/branch/delete-',$list->branch_id)}}" class="btn btn-xs btn-bricky tooltips"><i class="fa  fa-trash-o" data-toggle1="tooltip" title="Attribute Class Delete"></i></a>
							</a>
						</td>
					</tr>
					@endforeach
					@else
					<tr class="text-center">
						<td colspan="5">No Data available</td>
					</tr>
					@endif
				</tbody>
			</table>
			{{isset($branch_pagination) ? $branch_pagination:""}}
			</div>
				</div>
			</div>
			<!-- end: TEXT AREA PANEL -->
		</div>

	</div>


@stop