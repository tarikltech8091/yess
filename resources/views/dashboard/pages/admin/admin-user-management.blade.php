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
<!--end of error message*************************************-->


<div class="row ">
	<div class="col-sm-12">
		<div class="tabbable">
			<ul class="nav nav-tabs tab-padding tab-space-3 tab-blue" id="myTab4">
				<li class="{{($tab=='create_user') ? 'active' : ''}}">
					<a data-toggle="tab" href="#create_user">
						Create User
					</a>
				</li>
				<li class="{{($tab=='blocked_user') ? 'active' : ''}}">
					<a data-toggle="tab" href="#blocked_user">
						Blocked Users
					</a>
				</li>
				<li class="{{$tab=='admin' ? 'active':''}}">
					<a data-toggle="tab" href="#admin">
						Admin
					</a>
				</li>

				<li class="{{$tab=='merchant' ? 'active':''}}">
					<a data-toggle="tab" href="#merchant">
						Merchant
					</a>
				</li>
				<li class="{{$tab=='branch' ? 'active':''}}">
					<a data-toggle="tab" href="#branch">
						Branch
					</a>
				</li>

			</ul>


			<div class="tab-content">
				<div id="create_user" class="tab-pane {{$tab=='create_user' ? 'active':''}}">
					<div class="row">
						<div class="col-md-12">
							<form action="{{url('/dashboard/user-create')}}" method="post" enctype="multipart/form-data" role="form" id="form">

								<div class="row">
									<div class="col-md-12">
										<h3>Account Info</h3>
										<hr>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label class="control-label">
											 Name
											</label>
											<input type="text" placeholder="Name" class="form-control" id="firstname" name="name" value="{{old('name')}}">
										</div>

										<div class="form-group">
											<label class="control-label">
											 User Name
											</label>
											<input type="text" placeholder="User Name" class="form-control" id="user_name" name="user_name" value="{{old('user_name')}}">
										</div>

										<div class="form-group">
											<label class="control-label">
												Email Address
											</label>
											<input type="email" placeholder="email@example.com" class="form-control" id="email" name="email" value="{{old('email')}}">
										</div>
										<div class="form-group">
											<label class="control-label">
												Mobile
											</label>
											<input type="text" placeholder="User Mobile" class="form-control" id="phone" name="user_mobile" value="{{old('user_mobile')}}">
										</div>
										<div class="form-group">
											<label class="control-label">
												Position
											</label>
											<select id="show" class="form-control select_user_type select_type" name="user_type" onchange="change()">
												<option value="admin">Admin</option>
												<option value="merchant">Merchant</option>
												<option value="branch">Branch</option>
											</select>
										</div>

										<div id="merchant_select_box" class="form-group text_area" hidden="">
											<label class="control-label">
												Merchant
											</label>
											<select class="form-control merchant_list select_merchant_user" name="merchant_id">
												<option>Select Merchant</option>

											</select>
										</div>

										

										<div id="branch_select_box" class="form-group" hidden="">
											<label class="control-label">
												Branch
											</label>
											<select class="form-control branch_user_list" name="branch_id">
												<option>Select Branch</option>
												
											</select>
										</div>

									</div>

									<div class="col-md-6">
										
										<div class="form-group">
											<label class="control-label">
												Password
											</label>
											<input type="password" name="password" placeholder="********" class="form-control" value="" />
										</div>

										<div class="form-group">
											<label class="control-label">
												Confirm Password
											</label>
											<input type="password" class="form-control" name="confirm_password" placeholder="********" value="" />
										</div>

										<div class="form-group">
											<label>
												Image Upload
											</label>


											<div class="fileupload fileupload-new" data-provides="fileupload">

												<div class="fileupload-new thumbnail profile_img_size" style="width: 150px; height: 150px;"><img src="{{asset('assets/images/profile.jpg')}}" alt="">
												</div>
												<div class="fileupload-preview fileupload-exists thumbnail profile_img_size" style="max-width: 150px; max-height: 150px; line-height: 20px;"></div>

												<div class="user-edit-image-buttons">
													<span class="btn btn-light-grey btn-file">
														<span class="fileupload-new image-filechange"><i class="fa fa-picture"></i> Select image</span>
														<span class="fileupload-exists image-filechange"><i class="fa fa-picture"></i> Change</span>
														<input type="file" name="user_profile_image" value="" />
													</span>
													<a href="#" class="btn fileupload-exists btn-light-grey" data-dismiss="fileupload">
														<i class="fa fa-times"></i> Remove
													</a>

												</div>
											</div>


										</div>
									</div>
								</div>


								<hr>

								<div class="row">
									<div class="col-md-8">
										<p>
											By clicking Register, you are agreeing to the Policy and Terms &amp; Conditions.
										</p>
									</div>
									<div class="col-md-4">
										<input type="hidden" name="_token" value="{{csrf_token()}}">
										<button class="btn btn-teal btn-block" type="submit">
											Register <i class="fa fa-arrow-circle-right"></i>
										</button>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
				<div id="blocked_user" class="tab-pane {{$tab=='blocked_user' ? 'active':''}}">
					<div class="row">
						<div class="col-md-12">

							<!-- start: DYNAMIC TABLE PANEL -->
							<div class="table-responsive">
								<table class="table table-bordered table-hover" id="sample-table-1">
									<thead>
										<tr>
											<th>SL</th>
											<th>Name</th>
											<th>Email</th>
											<th>Mobile</th>
											<th>Login Status</th>
											<th>Status</th>
											<th>Action</th>
										</tr>
									</thead>
									<tbody>
										@if(!empty($user_info))
										@foreach($user_info as $key => $blocked_user_list)
										@if($blocked_user_list->status == '-1')
										<tr>
											<td>{{$key+1}}</td>
											<td>{{$blocked_user_list->name}}</td>
											<td>{{$blocked_user_list->email}}</td>
											<td>{{$blocked_user_list->mobile}}</td>
											<td>{{isset($blocked_user_list->login_status) && ($blocked_user_list->login_status=='1') ? 'Logged In' : 'Logged Out'}}</td>
											<td>{{isset($blocked_user_list->status) && ($blocked_user_list->status=='1') ? 'Active' : 'Inactive'}}</td>
											<td>
												@if($blocked_user_list->status==1)
												<button class="btn btn-primary btn-xs user_status_change col-md-12" data-user-id="{{$blocked_user_list->id}}" data-tab="blocked_user" data-status="-1">Block</button>
												@else
												<button class="btn btn-danger btn-xs user_status_change col-md-12" data-user-id="{{$blocked_user_list->id}}" data-tab="blocked_user" data-status="1">Unblock</button>
												@endif

											</td>
										</tr>
										@endif
										@endforeach
										@else
										<tr>
											<td colspan="9">
												<div class="alert alert-success" role="alert">
													<center><h4>No Data Available !</h4></center>
												</div> 
											</td>
										</tr>
										@endif

									</tbody>
								</table>
							</div>
							<!-- end: DYNAMIC TABLE PANEL -->

						</div>
					</div>
				</div>


				<div id="admin" class="tab-pane {{$tab=='admin' ? 'active':''}}">
					<div class="row">
						<div class="col-md-12">

							<!-- start: DYNAMIC TABLE PANEL -->
							<div class="table-responsive">
								<table class="table table-bordered table-hover" id="sample-table-1">
									<thead>
										<tr>
											<th>SL</th>
											<th>Name</th>
											<th>Email</th>
											<th>Mobile</th>
											<th>Login Status</th>
											<th>User Status</th>
											<th>Action</th>
										</tr>
									</thead>
									<tbody>
										@if(!empty($user_info))
										@foreach($user_info as $key => $admin_user_list)
										@if(($admin_user_list->user_type == 'admin'))
										<tr>
											<td>{{$key+1}}</td>
											<td>{{$admin_user_list->name}}</td>
											<td>{{$admin_user_list->email}}</td>
											<td>{{$admin_user_list->mobile}}</td>
											<td>{{isset($admin_user_list->login_status) && ($admin_user_list->login_status=='1') ? 'Logged In' : 'Logged Out'}}</td>
											<td>{{isset($admin_user_list->status) && ($admin_user_list->status=='1') ? 'Active' : 'Inactive'}}</td>
											<td>
												@if($admin_user_list->status==1)
												<button class="btn btn-primary btn-xs user_status_change col-md-12" data-user-id="{{$admin_user_list->id}}" data-tab="admin" data-status="-1">Block</button>
												@else
												<button class="btn btn-danger btn-xs user_status_change col-md-12" data-user-id="{{$admin_user_list->id}}" data-tab="admin" data-status="1">Unblock</button>
												@endif
											</td>
										</tr>
										@endif
										@endforeach
										@else
										<tr>
											<td colspan="9">
												<div class="alert alert-success" role="alert">
													<center><h4>No Data Available !</h4></center>
												</div> 
											</td>
										</tr>
										@endif

									</tbody>
								</table>
							</div>
							<!-- end: DYNAMIC TABLE PANEL -->

						</div>
					</div>
				</div>

				<div id="merchant" class="tab-pane {{$tab=='merchant' ? 'active':''}}">
					<div class="row">
						<div class="col-md-12">
							<div class="table-responsive">
								<table class="table table-bordered table-hover" id="sample-table-1">
									<thead>
										<tr>
											<th>SL</th>
											<th>Name</th>
											<th>Email</th>
											<th>Mobile</th>
											<th>Login Status</th>
											<th>User Status</th>
											<th>Action</th>
										</tr>
									</thead>
									<tbody>
										@if(!empty($user_info))
										@foreach($user_info as $key1 => $merchant_user_list)
										@if(($merchant_user_list->user_type == 'merchant'))
										<tr>
											<td>{{$key1+1}}</td>
											<td>{{$merchant_user_list->name}}</td>
											<td>{{$merchant_user_list->email}}</td>
											<td>{{$merchant_user_list->mobile}}</td>
											<td>{{isset($merchant_user_list->login_status) && ($merchant_user_list->login_status=='1') ? 'Logged In' : 'Logged Out'}}</td>
											<td>{{isset($merchant_user_list->status) && ($merchant_user_list->status=='1') ? 'Active' : 'Inactive'}}</td>
											<td>
												@if($merchant_user_list->status==1)
												<button class="btn btn-primary btn-xs user_status_change col-md-12" data-user-id="{{$merchant_user_list->id}}" data-tab="merchant" data-status="-1">Block</button>
												@else
												<button class="btn btn-danger btn-xs user_status_change col-md-12" data-user-id="{{$merchant_user_list->id}}" data-tab="merchant" data-status="1">Unblock</button>
												@endif
											</td>
										</tr>
										@endif
										@endforeach
										@else
										<tr>
											<td colspan="9">
												<div class="alert alert-success" role="alert">
													<center><h4>No Data Available !</h4></center>
												</div> 
											</td>
										</tr>
										@endif

									</tbody>
								</table>
							</div>

						</div>
					</div>
				</div>

				<div id="branch" class="tab-pane {{$tab=='branch' ? 'active':''}}">
					<div class="row">
						<div class="col-md-12">
							<div class="table-responsive">
								<table class="table table-bordered table-hover" id="sample-table-1">
									<thead>
										<tr>
											<th>SL</th>
											<th>Name</th>
											<th>Email</th>
											<th>Mobile</th>
											<th>Login Status</th>
											<th>User Status</th>
											<th>Action</th>
										</tr>
									</thead>
									<tbody>
										@if(!empty($user_info))
										@foreach($user_info as $key1 => $branch_user_list)
										@if(($branch_user_list->user_type == 'branch'))
										<tr>
											<td>{{$key1+1}}</td>
											<td>{{$branch_user_list->name}}</td>
											<td>{{$branch_user_list->email}}</td>
											<td>{{$branch_user_list->mobile}}</td>
											<td>{{isset($branch_user_list->login_status) && ($branch_user_list->login_status=='1') ? 'Logged In' : 'Logged Out'}}</td>
											<td>{{isset($branch_user_list->status) && ($branch_user_list->status=='1') ? 'Active' : 'Inactive'}}</td>
											<td>
												@if($branch_user_list->status==1)
												<button class="btn btn-primary btn-xs user_status_change col-md-12" data-user-id="{{$branch_user_list->id}}" data-tab="branch" data-status="-1">Block</button>
												@else
												<button class="btn btn-danger btn-xs user_status_change col-md-12" data-user-id="{{$branch_user_list->id}}" data-tab="branch" data-status="1">Unblock</button>
												@endif
											</td>
										</tr>
										@endif
										@endforeach
										@else
										<tr>
											<td colspan="9">
												<div class="alert alert-success" role="alert">
													<center><h4>No Data Available !</h4></center>
												</div> 
											</td>
										</tr>
										@endif

									</tbody>
								</table>
							</div>

						</div>
					</div>
				</div>

			</div>


		</div>
	</div>

	@stop