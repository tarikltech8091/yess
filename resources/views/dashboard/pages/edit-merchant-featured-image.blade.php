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
		<div class="col-sm-5">
			<!-- start: TEXT AREA PANEL -->
			<div class="panel panel-default">
				<div class="panel-heading">
					<i class="fa fa-external-link-square"></i>
					 Add Merchant Featured Image
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
				<form method="post" action="{{url('/dashboard/merchant/featured/update-'.$edit_merchant_featured_info->featured_product_id)}}" enctype="multipart/form-data">
					<input type="hidden" name="_token" value="{{csrf_token()}}">

					<div class="form-group">
						<label> Coupon 	Merchant <span class="symbol required"></span></label>
						<select class="form-control select_merchant_list" name="merchant_id">
							<option value="">Select Merchant</option>
							@if(!empty($merchant_info))
							@foreach($merchant_info As $key =>$list)
							<option {{($edit_merchant_featured_info->merchant_id == $list->merchant_id) ? "selected" :''}} value="{{$list->merchant_id}}">{{$list->merchant_name}}</option>

							@endforeach
							@endif
						</select>
					</div>

					<div class="form-group">
						<label> Coupon Branch <span class="symbol required"></span></label>
						<select class="form-control branch_list" name="branch_id">
							<option value="{{$edit_merchant_featured_info->branch_id}}">{{($edit_merchant_featured_info->branch_name)?$edit_merchant_featured_info->branch_name:''}}</option>
						</select>
					</div>

					<div class="form-group">
						<label> Product Featured Description </label>
						<textarea name="product_featured_description" class="form-control" rows="6">{{$edit_merchant_featured_info->product_featured_description}}</textarea>
					</div>

					<div class="form-group">
						<label> Merchant Featured Image <span class="symbol required"></span></label>
						<div class="fileupload fileupload-new" data-provides="fileupload">
							<div class="fileupload-new thumbnail" style="width: 140px; height: 140px;">
							@if(!empty($edit_merchant_featured_info->product_image))
								<img src="{{asset('assets/images/merchant-featured/'.$edit_merchant_featured_info->product_image)}}" alt="">
							@else
								<img src="{{asset('assets/images/profile.jpg')}}" alt="">
							@endif
							</div>
							<input type="hidden" name="update_merchant_featured_image" value="{{($edit_merchant_featured_info->product_image)?$edit_merchant_featured_info->product_image:''}}">
							<div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 150px; max-height: 150px; line-height: 20px;"></div>
							<div class="user-edit-image-buttons">
								<span class="btn btn-light-grey btn-file"><span class="fileupload-new"><i class="fa fa-picture"></i> Select image</span><span class="fileupload-exists"><i class="fa fa-picture"></i> Change</span>
									<input type="file" name="product_image" value="">
								</span>
								<a href="#" class="btn fileupload-exists btn-light-grey" data-dismiss="fileupload">
									<i class="fa fa-times"></i> Remove
								</a>
							</div>
						</div>
					</div>

					<div class="form-group">
						<label> Product Original Price <span class="symbol required"></span></label>
						<input type="text" class="form-control" name="product_original_price" value="{{$edit_merchant_featured_info->product_original_price}}">
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


		<div class="col-sm-7">
			<!-- start: TEXT AREA PANEL -->
			<div class="panel panel-default">
				<div class="panel-heading">
					<i class="fa fa-external-link-square"></i>
					All Featured Image
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
							<th>Merchant</th>
							<th>Branch</th>
							<th> Price </th>
							<th> Rate </th>
							<th>Discount Price </th>
							<th> Image </th>
							<th>Status</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						@if(!empty($featured_product_info) && count($featured_product_info) > 0)
						@foreach($featured_product_info as $key => $list)
						<tr >
							<td>{{$key+1}}</td>
							<td>{{$list->merchant_name}}</td>
							<td>{{$list->branch_name}}</td>
							<td>{{$list->product_original_price}}</td>
							<td>{{$list->product_discount_rate}}</td>
							<td>{{$list->product_discount_price}}</td>
							<td><img height="50px" width="50px" src="{{asset('assets/images/merchant-featured/small-icon/'.$list->product_image)}}"></td>
							<td>
							@if(($list->featured_product_status) == 1)
							<button class="btn btn-primary btn-xs merchant_featured_status col-md-12" data-id="{{$list->featured_product_id}}" data-action="-1">Active</button>
							@elseif(($list->featured_product_status) == -1)
							<button class="btn btn-danger btn-xs merchant_featured_status col-md-12" data-id="{{$list->featured_product_id}}" data-action="1">Block</button>
							@endif
							</td>
							<td>
								<a href="{{url('/dashboard/merchant/featured/edit-',$list->featured_product_id)}}" class="btn btn-green btn-xs tooltips"><i class="fa fa-pencil-square-o" aria-hidden="true" data-toggle1="tooltip" title="Sub Category Edit"></i></a>
								<a href="{{url('/dashboard/merchant/featured/delete-',$list->featured_product_id)}}" class="btn btn-xs btn-bricky tooltips"><i class="fa  fa-trash-o" data-toggle1="tooltip" title="Sub Category Delete"></i></a>
							</a>
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
			{{isset($featured_product_pagination) ? $featured_product_pagination:""}}
			</div>
				</div>
			</div>
			<!-- end: TEXT AREA PANEL -->
		</div>

	</div>


@stop