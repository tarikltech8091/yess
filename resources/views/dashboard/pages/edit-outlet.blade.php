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
					 Add Outlet
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
				<form method="post" action="{{url('/dashboard/outlet/update-'.$edit_outlet_info->outlet_id)}}" enctype="multipart/form-data">
					<input type="hidden" name="_token" value="{{csrf_token()}}">
					<div class="form-group">
						<label> Outlet Name </label>
						<input type="text" id="form-field-3" class="form-control" name="outlet_name" value="{{($edit_outlet_info->outlet_name)?($edit_outlet_info->outlet_name):''}}">
					</div>
					<div class="form-group">
					<label> Outlet Image </label>
						<div class="fileupload fileupload-new" data-provides="fileupload">
							<div class="fileupload-new thumbnail" style="width: 150px; height: 150px;">
								<img src="{{asset('images/default.jpg')}}" alt="">
							</div>
							<input type="hidden" name="update_outlet_image" value="{{($edit_outlet_info->outlet_image)?($edit_outlet_info->outlet_image):''}}">
							<div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 150px; max-height: 150px; line-height: 20px;"></div>
							<div class="user-edit-image-buttons">
								<span class="btn btn-light-grey btn-file"><span class="fileupload-new"><i class="fa fa-picture"></i> Select image</span><span class="fileupload-exists"><i class="fa fa-picture"></i> Change</span>
									<input type="file" name="outlet_image" value="">
								</span>
								<a href="#" class="btn fileupload-exists btn-light-grey" data-dismiss="fileupload">
									<i class="fa fa-times"></i> Remove
								</a>
							</div>
						</div>
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
					All Outlet
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
						@if(!empty($outlet_info) && count($outlet_info) > 0)
						@foreach($outlet_info as $key => $list)
						<tr >
							<td>{{$key+1}}</td>
							<td>{{$list->outlet_name}}</td>
							<td>
								<a href="{{url('/dashboard/outlet/edit-',$list->outlet_id)}}" class="btn btn-green btn-xs tooltips"><i class="fa fa-pencil-square-o" aria-hidden="true" data-toggle1="tooltip" title="Attribute Class Edit"></i></a>

								<a href="{{url('/dashboard/outlet/delete-',$list->outlet_id)}}" class="btn btn-xs btn-bricky tooltips"><i class="fa  fa-trash-o" data-toggle1="tooltip" title="Attribute Class Delete"></i></a>
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
			{{isset($outlet_pagination) ? $outlet_pagination:""}}
			</div>
				</div>
			</div>
			<!-- end: TEXT AREA PANEL -->
		</div>

	</div>


@stop