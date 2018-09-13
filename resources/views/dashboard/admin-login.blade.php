@extends('dashboard.layout.master')
@section('login-content')

	@if(isset($current_user_info) && !empty($current_user_info))
	<!-- start: BODY -->
	<body class="lock-screen">
		<div class="main-ls">
			<div class="logo">
				<img src="{{asset('assets/images/Yes/yes.png')}}" align="Yess">
				<i class="clip-clip"></i>
			</div>

			<div class="box-ls">
				<img alt="{{$current_user_info->name}}" src="{{ !empty($current_user_info->user_profile_image) ? asset($current_user_info->user_profile_image) : asset('assets/images/profile.png')}}" />
				<div class="user-info">
					<h1><i class="fa fa-lock"></i>{{$current_user_info->name}}</h1>
					<span>{{$current_user_info->email}}</span>
					<span><em>Please enter your password to un-lock.</em></span>

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

					<form class="form-login" action="{{url('/login')}}" method="POST">
						<input type="hidden" name="_token" value="{{csrf_token()}}">
						<div class="input-group">
							<input type="password" placeholder="Password" name="password" class="form-control">
							<span class="input-group-btn">
								<button class="btn btn-blue" type="submit">
									<i class="fa fa-chevron-right"></i>
								</button> </span>
						</div>
						<div class="relogin">
							<a href="{{url('/login?box=login')}}">
								Not {{$current_user_info->name}}?</a>
						</div>
						<input type="hidden" name="email" value="{{$current_user_info->email}}">
						<input type="hidden" name="user_type" value="{{$current_user_info->user_type}}">
					</form>
				</div>
			</div>
			<div class="copyright">
				2017 &copy; Developed by Live Technologies.
			</div>
		</div>
		<!-- start: MAIN JAVASCRIPTS -->
	@else
		<!-- start: BODY -->
	<body class="login example1">
		<div class="main-login col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3">
			<div class="logo">
				<img src="{{asset('assets/images/Yes/yes.png')}}" alt=" Yess">
			</div>
			<!-- start: LOGIN BOX -->
			<div class="box-login" style="display:block;">
				<h3>Sign in to your account</h3>
				<p>
					Please enter your name and password to log in.
				</p>
				<form class="form-login" action="{{url('/login')}}" method="POST">
					<input type="hidden" name="_token" value="{{csrf_token()}}">
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

					<fieldset>
						<div class="form-group">
							<span class="input-icon">
								<input type="text" class="form-control" name="user_name" placeholder="User Name">
								<i class="fa fa-user"></i>
							</span>
						</div>
						<div class="form-group form-actions">
							<span class="input-icon">
								<input type="password" class="form-control password" name="password" placeholder="Password">
								<i class="fa fa-lock"></i>
							</span>	
						</div>

						<div class="form-actions">
							<label for="remember" class="checkbox-inline">
								<input type="checkbox" class="grey remember" id="remember" name="remember">
								Keep me signed in
							</label>
							<a href="{{url('/admin/forget/password')}}" class="forgot-pass-link color-green">Forget Password </a>
							<button type="submit" class="btn btn-bricky pull-right">
								Login <i class="fa fa-arrow-circle-right"></i>
							</button>
						</div>
						
					</fieldset>
				</form>
			</div>
			<!-- end: LOGIN BOX -->
			<!-- start: COPYRIGHT -->
			<div class="copyright">
				2017 &copy; Developed by Live Technologies.
			</div>
			<!-- end: COPYRIGHT -->
		</div>
	</body>
	@endif
@stop