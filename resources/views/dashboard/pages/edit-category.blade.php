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
					 Edit Category
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
				<form method="post" action="{{url('/dashboard/category/update-'.$edit_category_info->category_id)}}">
					<input type="hidden" name="_token" value="{{csrf_token()}}">
					<div class="form-group">
						<label for="form-field-23"> Category Name <span class="symbol required"></span></label>
						<input type="text" id="form-field-3" class="form-control" name="category_name" value="{{($edit_category_info->category_name)?($edit_category_info->category_name):''}}">
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
					All Category
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
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						@if(!empty($category_info) && count($category_info) > 0)
						@foreach($category_info as $key => $list)
						<tr >
							<td>{{$key+1}}</td>
							<td>{{$list->category_name}}</td>
							<td>
								<a href="{{url('/dashboard/category/edit-',$list->category_id)}}" class="btn btn-green btn-xs tooltips"><i class="fa fa-pencil-square-o" aria-hidden="true" data-toggle1="tooltip" title="Attribute Class Edit"></i></a>
								<a href="{{url('/dashboard/category/delete-',$list->category_id)}}" class="btn btn-xs btn-bricky tooltips"><i class="fa  fa-trash-o" data-toggle1="tooltip" title="Attribute Class Delete"></i></a>
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
			{{isset($category_pagination) ? $category_pagination:""}}
			</div>
				</div>
			</div>
			<!-- end: TEXT AREA PANEL -->
		</div>

	</div>


@stop