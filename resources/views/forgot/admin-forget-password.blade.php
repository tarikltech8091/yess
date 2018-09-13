@extends('dashboard.layout.master')
@section('login-content')
		<!-- start: BODY -->
	<body class="login example1">
		<div class="main-login col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3">
			<div class="logo"><img src="{{asset('assets/images/Yes/yes.png')}}" alt=" Yess">
			</div>
			<!-- start: LOGIN BOX -->
			<div class="box-login" style="display:block;">
				<h3>Forget Password</h3>
				<form class="form-login" action="{{url('/forget/password')}}" method="POST">
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

						<div class="form-group form-actions">
							<span class="input-icon">
								<input type="text" class="form-control" name="email" placeholder="User Email">
								<i class="fa fa-lock"></i>
							</span>	
						</div>

						<div class="form-group">
							<span class="input-icon">
								<input  type="text" name="mobile" value="{{old('mobile')}}" placeholder="User Mobile" class="form-control user" required >
								<i class="fa fa-mobile"></i>
							</span>
						</div>

						<div class="form-actions">
							<a href="{{url('/adminlogin')}}" class="forgot-pass-link color-green">Login Page</a>
							<button type="submit" class="btn btn-bricky pull-right">
								Send Email <i class="fa fa-arrow-circle-right"></i>
							</button>
						</div>
						
					</fieldset>
				</form>
			</div>
			<!-- end: LOGIN BOX -->
			<!-- start: COPYRIGHT -->
			<div class="copyright">
				{{date('Y')}} &copy; Developed by Live Technologies.
			</div>
			<!-- end: COPYRIGHT -->
		</div>
	</body>
@stop