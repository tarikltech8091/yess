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
	<div class="row">
		<div class="col-sm-6">
			<!-- start: TEXT AREA PANEL -->
			<div class="panel panel-default">
				<div class="panel-heading">
					<i class="fa fa-external-link-square"></i>
					 Add Slider Image
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
				<form method="post" action="{{url('/dashboard/home-slider')}}" enctype="multipart/form-data">
					<input type="hidden" name="_token" value="{{csrf_token()}}">

						<input type="hidden" class="form-control" name="setting_meta_field_name" value="home_slider">

					<div class="form-group">
						<label> Merchant <span class="symbol required"></span></label>
						<select class="form-control select_merchant_list" name="coupon_merchant_id">
							<option value="">Select Merchant</option>
							@if(!empty($merchant_info))
								@foreach($merchant_info As $key =>$list)
								<option value="{{$list->merchant_id}}">{{$list->merchant_name}}</option>
								@endforeach
							@endif
						</select>
					</div>

					<div class="form-group">
						<label> Home Slider Image <span class="symbol required"></span></label>
						<div class="fileupload fileupload-new" data-provides="fileupload">
							<div class="fileupload-new thumbnail" style="width: 140px; height: 140px;">
								<img src="{{asset('assets/images/profile.jpg')}}" alt="">
							</div>
							<div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 150px; max-height: 150px; line-height: 20px;"></div>
							<div class="user-edit-image-buttons">
								<span class="btn btn-light-grey btn-file"><span class="fileupload-new"><i class="fa fa-picture"></i> Select image</span><span class="fileupload-exists"><i class="fa fa-picture"></i> Change</span>
									<input type="file" name="home_slider_image" value="">
								</span>
								<a href="#" class="btn fileupload-exists btn-light-grey" data-dismiss="fileupload">
									<i class="fa fa-times"></i> Remove
								</a>
							</div>
						</div>
					</div>

					<div class="form-group">
						<label> Slider Preview Image <span class="symbol required"></span></label>
						<div class="fileupload fileupload-new" data-provides="fileupload">
							<div class="fileupload-new thumbnail" style="width: 140px; height: 140px;">
								<img src="{{asset('assets/images/profile.jpg')}}" alt="">
							</div>
							<div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 150px; max-height: 150px; line-height: 20px;"></div>
							<div class="user-edit-image-buttons">
								<span class="btn btn-light-grey btn-file"><span class="fileupload-new"><i class="fa fa-picture"></i> Select image</span><span class="fileupload-exists"><i class="fa fa-picture"></i> Change</span>
									<input type="file" name="slider_popup_image" value="">
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
					All Home Slider
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
							<th>Main Image</th>
							<th>Preview Image</th>
							<th>Status</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						@if(!empty($home_slider_info) && count($home_slider_info) > 0)
						@foreach($home_slider_info as $key => $list)
		                <?php 
                            $meta_field_value=$list->setting_meta_field_value; 
                            $home_slider_image=unserialize($meta_field_value);
                            $home_slider_main_image=$home_slider_image[0];
                            $home_slider_preview_image=$home_slider_image[1];
                        ?>
						<tr >
							<td>{{$key+1}}</td>
							<td><img src="{{asset('assets/images/slider/small-icon/'.$home_slider_main_image)}}"></td>
							<td><img height="50px" width="50px" src="{{asset('assets/images/slider/popup/'.$home_slider_preview_image)}}"></td>
							<td>
								@if($list->setting_meta_status==1)
									<button class="btn btn-primary btn-xs setting_meta_status col-md-12" data-id="{{$list->setting_id}}" data-action="-1">Block</button>
								@elseif($list->setting_meta_status==-1)
									<button class="btn btn-danger btn-xs setting_meta_status col-md-12" data-id="{{$list->setting_id}}" data-action="1">Unblock</button>
								@endif
							</td>
							<td>
								<a href="{{url('/dashboard/home-slider/delete-',$list->setting_id)}}" class="btn btn-xs btn-bricky tooltips"><i class="fa  fa-trash-o" data-toggle1="tooltip" title="Sub Category Delete"></i></a>
							</a>
						</td>
					</tr>
					@endforeach
					@else
					<tr class="text-center">
						<td colspan="4">No Data available</td>
					</tr>
					@endif
				</tbody>
			</table>
			{{isset($home_slider_pagination) ? $home_slider_pagination:""}}
			</div>
				</div>
			</div>
			<!-- end: TEXT AREA PANEL -->
		</div>

	</div>


@stop