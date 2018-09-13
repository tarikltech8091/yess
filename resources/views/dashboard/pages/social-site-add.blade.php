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
	<div class="col-md-5">
		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="clip-stats"></i>
				Add Social Site Link
				<div class="panel-tools">
					<a class="btn btn-xs btn-link panel-collapse collapses" href="#">
					</a>
					<a class="btn btn-xs btn-link panel-close" href="#">
						<i class="fa fa-times"></i>
					</a>
				</div>
			</div>
			<div class="panel-body">

				<form action="{{url('/dashboard/social/site/info')}}" method="POST" enctype="multipart/form-data">
					<input type="hidden" name="_token" value="{{csrf_token()}}">

					<div class="form-group">
						<label>Social Site Name</label>
						<input type="text" class="form-control" name="social_site_name" required="">
					</div>
					<div class="form-group">
						<label>Social Site Url</label>
						<input type="text" class="form-control" name="social_site_url" required="">
					</div>


					<input class="btn btn-teal pull-right" type="submit" value="Add" name="social_site_link_add">
				</form>
			</div>
		</div>
	</div>
	<div class="col-md-7">
		<div class="row">
			<div class="col-sm-12">
				<div class="panel panel-default">
					<div class="panel-heading">
						<i class="clip-pie"></i>
						Company Social Site Link List
						<div class="panel-tools">
							<a class="btn btn-xs btn-link panel-collapse collapses" href="#">
							</a>
							<a class="btn btn-xs btn-link panel-config" href="#panel-config" data-toggle="modal">
								<i class="fa fa-wrench"></i>
							</a>
							<a class="btn btn-xs btn-link panel-refresh" href="#">
								<i class="fa fa-refresh"></i>
							</a>
							<a class="btn btn-xs btn-link panel-close" href="#">
								<i class="fa fa-times"></i>
							</a>
						</div>
					</div>
					<div class="panel-body">

						<table class="table table-striped table-bordered table-hover">
							<thead>
								<tr>
									<th>#</th>
									<th>Social Site Name</th>
									<th>Social Site Url</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>

								@if(!empty($social_site_info) && count($social_site_info) > 0)

									@foreach ($social_site_info as $key => $list) 
            							<?php $social_site_value=unserialize($list->setting_meta_field_value); ?>
										<tr>
											<td>{{$key+1}}</td>
											<td>{{$social_site_value['0']}}</td>
											<td>{{$social_site_value['1']}}</td>
											<td class="text-center">
												<a href="{{url('/dashboard/social/site/edit/sid-'.$list->setting_id)}}" class="btn btn-xs btn-teal tooltips"><i class="fa fa-edit"></i></a>
												<a href="{{url('/dashboard/social/site/delete/sid-'.$list->setting_id)}}" class="btn btn-xs btn-danger tooltips" data-original-title="Delete"><i class="fa fa-trash-o"></i></a>
											</td>
										</tr>

								@endforeach
								@else
									<tr>
										<td colspan="4" class="text-center"> No data available</td>
									</tr>
								@endif

							</tbody>
						</table>
						{{isset($social_site_pagination)?$social_site_pagination:''}}
					</div>
				</div>
			</div>
		</div>
	</div>
</div>


@stop