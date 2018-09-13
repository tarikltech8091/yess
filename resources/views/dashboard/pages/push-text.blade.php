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
			<div class="col-md-12">
				<!-- start: TEXT AREA PANEL -->
				<div class="tabbable">
					<ul class="nav nav-tabs tab-padding tab-space-3 tab-blue" id="myTab4">
						<li class="{{isset($tab) && ($tab=='push_new_coupon') ? 'active' : ''}}">
							<a data-toggle="tab" href="#push_new_coupon">
								New Coupon Push to User
							</a>
						</li>

						<li class="{{isset($tab) && ($tab=='push_new_coupon_to_all') ? 'active' : ''}}">
							<a data-toggle="tab" href="#push_new_coupon_to_all">
								New Coupon Push to All
							</a>
						</li>


						<li class="{{isset($tab) && ($tab=='greetings') ? 'active' : ''}}">
							<a data-toggle="tab" href="#greetings">
								Greetings Push
							</a>
						</li>

					</ul>
					<div class="tab-content">
						<div id="push_new_coupon" class="tab-pane in {{isset($tab) && ($tab=='push_new_coupon') ? 'active' : ''}}">

							<div class="panel panel-default">
								<div class="panel-body">
									<form method="post" action="{{url('/push/text')}}" enctype="multipart/form-data">
										<input type="hidden" name="_token" value="{{csrf_token()}}">
										<div class="form-group">
											<label> Type <span class="symbol required"></span></label>
											<select class="form-control" name="select_type">
												<option value="">Select Type <span class="symbol required"></span></option>
												<option value="follow">Follow<span class="symbol required"></span></option>
												<option value="category">Category <span class="symbol required"></span></option>

												<option value="all">All<span class="symbol required"></span></option>
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
										<div class="form-group ">
												<label>  Message <span class="symbol required"></span></label>
												<textarea class="form-control" name="message" cols="50" rows="3"></textarea>
										</div>


										<div class="form-group">
											<label> Featured Image </label>
											<div class="fileupload fileupload-new" data-provides="fileupload">
												<div class="fileupload-new thumbnail" style="width: 140px; height: 140px;">
													<img src="{{asset('assets/images/profile.jpg')}}" alt="">
												</div>
												<div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 150px; max-height: 150px; line-height: 20px;"></div>
												<div class="user-edit-image-buttons">
													<span class="btn btn-light-grey btn-file"><span class="fileupload-new"><i class="fa fa-picture"></i> Select image</span><span class="fileupload-exists"><i class="fa fa-picture"></i> Change</span>
														<input type="file" name="featured_image" value="">
													</span>
													<a href="#" class="btn fileupload-exists btn-light-grey" data-dismiss="fileupload">
														<i class="fa fa-times"></i> Remove
													</a>
												</div>
											</div>
										</div>

											<div class="form-group">
												<input type="reset" class="btn btn-danger" value="Reset">
												<input type="submit" class="btn btn-primary" value="Send">
											</div>
									</form>
								</div>

							</div>
						</div>

						<div id="push_new_coupon_to_all" class="tab-pane in {{isset($tab) && ($tab=='push_new_coupon_to_all') ? 'active' : ''}}">

							<div class="panel panel-default">
								<div class="panel-body">
									<form method="post" action="{{url('/push/text/allapp')}}" enctype="multipart/form-data">
										<input type="hidden" name="_token" value="{{csrf_token()}}">
										<div class="form-group">
											<label> Platform <span class="symbol required"></span></label>
											<select class="form-control" name="select_type">
												<option value="">Select Type <span class="symbol required"></span></option>
												<option value="android">Android<span class="symbol required"></span></option>
												<option value="ios">IOS <span class="symbol required"></span></option>

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

										<div class="form-group ">
											<label>  Message <span class="symbol required"></span></label>
											<textarea class="form-control" name="message" cols="50" rows="3"></textarea>
										</div>


										<div class="form-group">
											<label> Featured Image </label>
											<div class="fileupload fileupload-new" data-provides="fileupload">
												<div class="fileupload-new thumbnail" style="width: 140px; height: 140px;">
													<img src="{{asset('assets/images/profile.jpg')}}" alt="">
												</div>
												<div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 150px; max-height: 150px; line-height: 20px;"></div>
												<div class="user-edit-image-buttons">
													<span class="btn btn-light-grey btn-file"><span class="fileupload-new"><i class="fa fa-picture"></i> Select image</span><span class="fileupload-exists"><i class="fa fa-picture"></i> Change</span>
														<input type="file" name="featured_image" value="">
													</span>
													<a href="#" class="btn fileupload-exists btn-light-grey" data-dismiss="fileupload">
														<i class="fa fa-times"></i> Remove
													</a>
												</div>
											</div>
										</div>

											<div class="form-group">
												<input type="reset" class="btn btn-danger" value="Reset">
												<input type="submit" class="btn btn-primary" value="Send">
											</div>
									</form>
								</div>

							</div>
						</div>

						<div id="greetings" class="tab-pane in {{isset($tab) && ($tab=='greetings') ? 'active' : ''}}">

							<div class="panel panel-default">
								<div class="panel-body">
									<form method="post" action="{{url('/push/greetings/text')}}" enctype="multipart/form-data">
										<input type="hidden" name="_token" value="{{csrf_token()}}">
										<div class="form-group">
											<label> Platform <span class="symbol required"></span></label>
											<select class="form-control" name="select_type">
												<option value="">Select Type <span class="symbol required"></span></option>
												<option value="android">Android<span class="symbol required"></span></option>
												<option value="ios">IOS <span class="symbol required"></span></option>

											</select>
										</div>

										<div class="form-group ">
											<label>  Title  <span class="symbol required"></span></label>
											<input type="text" class="form-control" name="title">
										</div>
										<div class="form-group ">
											<label>  Message <span class="symbol required"></span></label>
											<textarea class="form-control" name="message" cols="50" rows="3"></textarea>
										</div>

										<div class="form-group ">
											<label>  Greetings Url </label>
											<input type="text" class="form-control" name="url">
										</div>

										<div class="form-group">
											<label> Featured Image </label>
											<div class="fileupload fileupload-new" data-provides="fileupload">
												<div class="fileupload-new thumbnail" style="width: 140px; height: 140px;">
													<img src="{{asset('assets/images/profile.jpg')}}" alt="">
												</div>
												<div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 150px; max-height: 150px; line-height: 20px;"></div>
												<div class="user-edit-image-buttons">
													<span class="btn btn-light-grey btn-file"><span class="fileupload-new"><i class="fa fa-picture"></i> Select image</span><span class="fileupload-exists"><i class="fa fa-picture"></i> Change</span>
														<input type="file" name="featured_image" value="">
													</span>
													<a href="#" class="btn fileupload-exists btn-light-grey" data-dismiss="fileupload">
														<i class="fa fa-times"></i> Remove
													</a>
												</div>
											</div>
										</div>

										<div class="form-group">
											<input type="reset" class="btn btn-danger" value="Reset">
											<input type="submit" class="btn btn-primary" value="Send">
										</div>
									</form>
								</div>

							</div>
						</div>

					</div>
				</div>
				<!-- end: TEXT AREA PANEL -->
			</div>


		</div>


@stop