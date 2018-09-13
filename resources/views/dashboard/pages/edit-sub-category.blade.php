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
					 Edit Sub Category
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
				<form method="post" action="{{url('/dashboard/sub-category/update-'.$edit_sub_category_info->sub_category_id)}}" enctype="multipart/form-data">
					<input type="hidden" name="_token" value="{{csrf_token()}}">

					<div class="form-group">
						<label> Category Name <span class="symbol required"></span></label>
						<select class="form-control" name="category_id">
							<option value="0">No category</option>
							@if(!empty($category_info))
							@foreach($category_info as $key =>$list)
								<option {{($edit_sub_category_info->category_id == $list->category_id) ? "selected" :''}} value="{{$list->category_id}}">{{$list->category_name}}</option>
							@endforeach
							@endif
						</select>
					</div>

					<div class="form-group">
						<label for="form-field-23"> Sub Category Name <span class="symbol required"></span></label>
						<input type="text" id="form-field-3" class="form-control" name="sub_category_name" value="{{($edit_sub_category_info->sub_category_name)?($edit_sub_category_info->sub_category_name):''}}">
					</div>

					<div class="form-group">
						<label> Sub Category Image <span class="symbol required"></span></label>
						<div class="fileupload fileupload-new" data-provides="fileupload">
							<div class="fileupload-new thumbnail" style="width: 140px; height: 140px;">
							@if(isset($edit_sub_category_info->sub_category_featured_image))
								<img src="{{asset($edit_sub_category_info->sub_category_featured_image)}}" alt="">
							@else
								<img src="{{asset('assets/images/profile.jpg')}}" alt="">
							@endif
							</div>
							<input type="hidden" name="update_sub_category_image" value="{{($edit_sub_category_info->sub_category_featured_image)?$edit_sub_category_info->sub_category_featured_image:''}}">
							<div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 150px; max-height: 150px; line-height: 20px;"></div>
							<div class="user-edit-image-buttons">
								<span class="btn btn-light-grey btn-file"><span class="fileupload-new"><i class="fa fa-picture"></i> Select image</span><span class="fileupload-exists"><i class="fa fa-picture"></i> Change</span>
									<input type="file" name="sub_category_image" value="">
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
					All Sub Category
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
							<th>Category Name</th>
							<th>Sub Category Name</th>
							<th> Image </th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						@if(!empty($sub_category_info) && count($sub_category_info) > 0)
						@foreach($sub_category_info as $key => $list)
						<tr >
							<td>{{$key+1}}</td>
							<td>{{($list->category_name)?$list->category_name :'No Category'}}</td>
							<td>{{$list->sub_category_name}}</td>
							<td><img height="50px" width="50px" src="{{asset($list->sub_category_featured_image)}}"></td>
							<td>
								<a href="{{url('/dashboard/sub-category/edit-',$list->sub_category_id)}}" class="btn btn-green btn-xs tooltips"><i class="fa fa-pencil-square-o" aria-hidden="true" data-toggle1="tooltip" title="Sub Category Edit"></i></a>
								<a href="{{url('/dashboard/sub-category/delete-',$list->sub_category_id)}}" class="btn btn-xs btn-bricky tooltips"><i class="fa  fa-trash-o" data-toggle1="tooltip" title="Sub Category Delete"></i></a>
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
			{{isset($sub_category_pagination) ? $sub_category_pagination:""}}
			</div>
				</div>
			</div>
			<!-- end: TEXT AREA PANEL -->
		</div>

	</div>


@stop