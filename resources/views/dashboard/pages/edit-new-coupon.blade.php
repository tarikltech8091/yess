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
	<div class="row margin_top_20">
		<div class="col-sm-12">
			<!-- start: TEXT AREA PANEL -->
			<div class="panel panel-default">
				<div class="panel-heading">
					<i class="fa fa-external-link-square"></i>
					 Edit Coupon
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

				<form method="post" action="{{url('/dashboard/transaction/coupon/update-'.$edit_coupon_info->coupon_id)}}" enctype="multipart/form-data">
					<input type="hidden" name="_token" value="{{csrf_token()}}">
				<div class="col-sm-12">
					<div class="col-sm-6">

						<div class="form-group">
							<label> Coupon Discount rate <span class="symbol required"></span></label>
							<input type="text" id="form-field-3" class="form-control" name="coupon_discount_rate" value="{{$edit_coupon_info->coupon_discount_rate}}">
						</div>

						<div class="form-group">
							<label> Merchant Commission Rate <span class="symbol required"></span></label>
							<input type="text" id="form-field-3" class="form-control" name="coupon_commision_rate" value="{{$edit_coupon_info->coupon_commision_rate}}">
						</div>

						<div class="form-group">
							<label> MAX Discount <span class="symbol required"></span></label>
							<input type="text" id="form-field-3" class="form-control" name="coupon_max_discount" value="{{$edit_coupon_info->coupon_max_discount}}">
						</div>

						<div class="form-group">
							<label> MAX Commission <span class="symbol required"></span></label>
							<input type="text" id="form-field-3" class="form-control" name="coupon_max_commission" value="{{$edit_coupon_info->coupon_max_commission}}">
						</div>

						<div class="form-group">
							<label> Coupon Min Amount <span class="symbol required"></span></label>
							<input type="text" id="form-field-3" class="form-control" name="coupon_applied_min_amount" value="{{$edit_coupon_info->coupon_applied_min_amount}}">
						</div>

						<div class="form-group">
							<label> Total Coupon Sell <span class="symbol required"></span></label>
							<input type="text" class="form-control" name="coupon_total_selled" value="{{$edit_coupon_info->coupon_total_selled}}" readonly="">
						</div>


						<div class="form-group">
							<label> Coupon Max Limit <span class="symbol required"></span>(If unlimited  please submit -1)</label>
							<input type="text" id="form-field-3" class="form-control" name="coupon_max_limit" value="{{$edit_coupon_info->coupon_max_limit}}">
						</div>


						<div class="form-group">
							<label> Coupon Applied Point </label>
							<input type="text" id="form-field-3" class="form-control" name="coupon_applied_point" value="{{$edit_coupon_info->coupon_applied_point}}">
						</div>
						
					</div>
					<div class="col-sm-6">

						<div class="form-group">
							<label> Coupon Closing Date <span class="symbol required"></span></label>
							<div class="input-group">
				                <input type="text" data-date-format="yyyy-mm-dd" data-date-viewmode="years" class="form-control date-picker" name="coupon_closing_date" value="{{$edit_coupon_info->coupon_closing_date}}" placeholder="">
				                <span class="input-group-addon"> <i class="fa fa-calendar"></i> </span>
				            </div>
						</div>

						<div class="form-group">
							<label> Coupon Description </label>
							<textarea class="form-control" name="coupon_description" rows="6">{{$edit_coupon_info->coupon_description}}</textarea>
						</div>
						
						<div class="form-group">
							<label> Coupon Image <span class="symbol required"></span></label>
							<div class="fileupload fileupload-new" data-provides="fileupload">
								<div class="fileupload-new thumbnail" style="width: 150px; height: 150px;">
								@if(!empty($edit_coupon_info->coupon_featured_image))
									<img src="{{asset($edit_coupon_info->coupon_featured_image)}}" alt="">
								@else
									<img src="{{asset('assets/images/profile.jpg')}}" alt="">
								@endif
								</div>
								<input type="hidden" name="update_coupon_featured_image" value="{{($edit_coupon_info->coupon_featured_image)?$edit_coupon_info->coupon_featured_image:''}}">
								<div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 150px; max-height: 150px; line-height: 20px;"></div>
								<div class="user-edit-image-buttons">
									<span class="btn btn-light-grey btn-file"><span class="fileupload-new"><i class="fa fa-picture"></i> Select image</span><span class="fileupload-exists"><i class="fa fa-picture"></i> Change</span>
										<input type="file" name="coupon_featured_image" value="">
									</span>
									<a href="#" class="btn fileupload-exists btn-light-grey" data-dismiss="fileupload">
										<i class="fa fa-times"></i> Remove
									</a>
								</div>
							</div>
						</div>
					</div>


				</div>
					<div class="form-group pull-right">
						<input type="reset" class="btn btn-danger" value="Reset">
						<input type="submit" class="btn btn-primary" value="Save">
					</div>
				</form>	
				</div>

			</div>
			<!-- end: TEXT AREA PANEL -->
		</div>

	</div>


@stop