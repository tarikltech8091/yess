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
		<div class="col-sm-12">
			<!-- start: TEXT AREA PANEL -->
			<div class="panel panel-default">
				<div class="panel-heading">
					<i class="fa fa-external-link-square"></i>
					 Add Coupon
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

				<form method="post" action="{{url('/dashboard/coupon')}}" enctype="multipart/form-data">
					<input type="hidden" name="_token" value="{{csrf_token()}}">
				<div class="col-sm-12">
				<div class="col-sm-6">

					<div class="form-group">
						<label> Coupon Category <span class="symbol required"></span></label>
						<select class="form-control select_category" name="coupon_category_id">
							<option value="">Select Category</option>
							<option value="0">No category</option>
							@if(!empty($category_info))
							@foreach($category_info as $key =>$list)
							<option value="{{$list->category_id}}">{{$list->category_name}}</option>
							@endforeach
							@endif
						</select>
					</div>
					<div class="form-group">
						<label> Coupon Sub Category <span class="symbol required"></span></label>
						<select class="form-control sub_category_list" name="coupon_sub_category_id">
							<option value="">Select Sub Category <span class="symbol required"></span></option>
						</select>
					</div>

					<div class="form-group">
						<label> Coupon 	Merchant <span class="symbol required"></span></label>
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
						<label> Coupon Branch <span class="symbol required"></span></label>
						<select class="form-control branch_list" name="coupon_branch_id">
							<option value="">Select Branch</option>
						</select>
					</div>

					<div class="form-group">
						<label> Coupon Keyword <span class="symbol required"></span></label>
						<input type="text" id="form-field-3" class="form-control" name="coupon_keyword" value="{{old('coupon_keyword')}}">
					</div>
					
					<div class="form-group">
						<label> Coupon Discount rate <span class="symbol required"></span></label>
						<input type="text" id="form-field-3" class="form-control" name="coupon_discount_rate" value="{{old('coupon_discount_rate')}}">
					</div>

					<div class="form-group">
						<label> MAX Discount <span class="symbol required"></span></label>
						<input type="text" id="form-field-3" class="form-control" name="coupon_max_discount" value="{{old('coupon_max_discount')}}">
					</div>

					<div class="form-group">
						<label> Merchant Commission Rate <span class="symbol required"></span></label>
						<input type="text" id="form-field-3" class="form-control" name="coupon_commision_rate" value="{{old('coupon_commision_rate')}}">
					</div>

					<div class="form-group">
						<label> MAX Commission <span class="symbol required"></span></label>
						<input type="text" id="form-field-3" class="form-control" name="coupon_max_commission" value="{{old('coupon_max_commission')}}">
					</div>

					<div class="form-group">
						<label> Coupon Min Amount <span class="symbol required"></span></label>
						<input type="text" id="form-field-3" class="form-control" name="coupon_applied_min_amount" value="{{old('		coupon_applied_min_amount')}}">
					</div>

					<div class="form-group">
						<label> Coupon Max Limit <span class="symbol required"></span> (If unlimited  please submit -1)</label>
						<input type="text" id="form-field-3" class="form-control" name="coupon_max_limit" value="{{old('coupon_max_limit')}}">
					</div>
					
				</div>
				<div class="col-sm-6">

					<div class="form-group">
						<label> Coupon Opening Date <span class="symbol required"></span></label>
						<div class="input-group">
			                <input type="text" data-date-format="yyyy-mm-dd" data-date-viewmode="years" class="form-control date-picker" name="coupon_opening_date" value="{{date("Y-m-d")}}" placeholder="">
			                <span class="input-group-addon"> <i class="fa fa-calendar"></i> </span>
			            </div>
					</div>


					<div class="form-group">
						<label> Coupon Closing Date <span class="symbol required"></span></label>
						<div class="input-group">
			                <input type="text" data-date-format="yyyy-mm-dd" data-date-viewmode="years" class="form-control date-picker" name="coupon_closing_date" value="{{date("Y-m-d")}}" placeholder="">
			                <span class="input-group-addon"> <i class="fa fa-calendar"></i> </span>
			            </div>
					</div>

					<div class="form-group">
						<label> Coupon Applied Point </label>
						<input type="text" id="form-field-3" class="form-control" name="coupon_applied_point" value="{{old('coupon_applied_point')}}">
					</div>

					<div class="form-group">
						<label> Coupon Description </label>
						<textarea class="form-control" name="coupon_description" rows="6"></textarea>
					</div>
					
					<div class="form-group">
						<label> Coupon Image <span class="symbol required"></span></label>
						<div class="fileupload fileupload-new" data-provides="fileupload">
							<div class="fileupload-new thumbnail" style="width: 150px; height: 150px;">
								<img src="{{asset('assets/images/profile.jpg')}}" alt="">
							</div>
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