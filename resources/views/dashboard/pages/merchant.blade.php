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
					 Add Merchant
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
				<form method="post" action="{{url('/dashboard/merchant')}}" enctype="multipart/form-data">
					<input type="hidden" name="_token" value="{{csrf_token()}}">
				<div class="col-sm-6">

					<div class="form-group">
						<label> Merchant Name <span class="symbol required"></span></label>
						<input type="text" id="form-field-3" class="form-control" name="merchant_name" value="{{old('merchant_name')}}">
					</div>

					<div class="form-group">
						<label> Merchant Code <span class="symbol required"></span></label>
						<input type="text" id="form-field-3" class="form-control" name="merchant_code" value="{{old('merchant_code')}}">
					</div>
					<div class="form-group">
						<label> Merchant Propriter Name <span class="symbol required"></span></label>
						<input type="text" id="form-field-3" class="form-control" name="merchant_propriter" value="{{old('merchant_propriter')}}">
					</div>
					<div class="form-group">
						<label> Merchant Propriter Mobile <span class="symbol required"></span></label>
						<input type="text" id="form-field-3" class="form-control" name="merchant_propriter_mobile" value="{{old('merchant_propriter_mobile')}}">
					</div>
					<div class="form-group">
						<label> Merchant Email <span class="symbol required"></span></label>
						<input type="text" id="form-field-3" class="form-control" name="merchant_email" value="{{old('merchant_email')}}">
					</div>
					<div class="form-group">
						<label> Merchant Website Url </label>
						<input type="text" id="form-field-3" class="form-control" name="merchant_website_url" value="{{old('merchant_website_url')}}">
					</div>
				</div>
				<div class="col-sm-6">

					<div class="form-group">
						<label> Merchant Address <span class="symbol required"></span></label>
						<textarea class="form-control" name="merchant_address" cols="50" rows="3"></textarea>
					</div>
					<div class="form-group">
						<label> Merchant Description <span class="symbol required"></span></label>
						<textarea class="form-control" name="merchant_description" cols="50" rows="3"></textarea>

					</div>
					
					<div class="form-group">
						<label> Merchant Image <span class="symbol required"></span></label>
						<div class="fileupload fileupload-new" data-provides="fileupload">
							<div class="fileupload-new thumbnail" style="width: 140px; height: 140px;">
								<img src="{{asset('assets/images/profile.jpg')}}" alt="">
							</div>
							<div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 150px; max-height: 150px; line-height: 20px;"></div>
							<div class="user-edit-image-buttons">
								<span class="btn btn-light-grey btn-file"><span class="fileupload-new"><i class="fa fa-picture"></i> Select image</span><span class="fileupload-exists"><i class="fa fa-picture"></i> Change</span>
									<input type="file" name="merchant_image" value="">
								</span>
								<a href="#" class="btn fileupload-exists btn-light-grey" data-dismiss="fileupload">
									<i class="fa fa-times"></i> Remove
								</a>
							</div>
						</div>
					</div>
				</div>
				<br/>

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