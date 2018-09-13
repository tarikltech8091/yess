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
					 Add Outlet Details
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
				<form method="post" action="{{url('/dashboard/outlet-details/update-'.$edit_outlet_details_info->outlet_details_id)}}">
					<input type="hidden" name="_token" value="{{csrf_token()}}">
					<div class="form-group">
						<label> Outlet Name </label>
						<select class="form-control" name="outlet_id">
							<option>Select Oultel</option>
							@if(!empty($outlet_data) && count($outlet_data)>0)
							@foreach ($outlet_data as $key => $value)
							<!-- <option value="{{$value->outlet_id}}">{{$value->outlet_name}}</option> -->
							<option {{($edit_outlet_details_info->outlet_id == $value->outlet_id) ? "selected" :''}} value="{{$value->outlet_id}}">{{$value->outlet_name}}</option>
							@endforeach
							@endif
								
						</select>
					</div>

					<div class="form-group">
						<label> Outlet Mobile No </label>
						<input type="text" class="form-control" name="outlet_mobile_number" value="{{($edit_outlet_details_info->outlet_mobile_number)}}">
					</div>

					<div class="form-group">
						<label> Outlet Code </label>
						<input type="text" class="form-control" name="outlet_code" value="{{($edit_outlet_details_info->outlet_code)}}">
					</div>

					<div class="form-group">
						<label> Outlet Address </label>
						<textarea name="outlet_address" cols="75" rows="8">{{($edit_outlet_details_info->outlet_address)}}</textarea>
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
					All Outlet Details
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
				<div class="table-responsive">
					<table class="table table-hover table-bordered table-striped nopadding" id="sample-table-1">
					<thead>
						<tr>
							<th>SL</th>
							<th>Outlet Name</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						@if(!empty($outlet_details_info) && count($outlet_details_info) > 0)
						@foreach($outlet_details_info as $key => $list)
						<tr >
							<td>{{$key+1}}</td>
							<td>{{$list->outlet_name}}</td>
							<td>
								<a href="{{url('/dashboard/outlet-details/edit-',$list->outlet_details_id)}}" class="btn btn-green btn-xs tooltips"><i class="fa fa-pencil-square-o" aria-hidden="true" data-toggle1="tooltip" title="Attribute Class Edit"></i></a>
								<a href="{{url('/dashboard/outlet-details/delete-',$list->outlet_details_id)}}" class="btn btn-xs btn-bricky tooltips"><i class="fa  fa-trash-o" data-toggle1="tooltip" title="Attribute Class Delete"></i></a>
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
			{{isset($outlet_details_pagination) ? $outlet_details_pagination:""}}
			</div>
				</div>
			</div>
			<!-- end: TEXT AREA PANEL -->
		</div>

	</div>


@stop