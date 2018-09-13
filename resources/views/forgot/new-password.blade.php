@extends('dashboard.layout.master')
@section('login-content')

	<!-- start: BODY -->
	<body class="login example1">
		<div class="main-login col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3">
			<div class="logo"><img src="{{asset('assets/images/Yes/yes.png')}}" alt=" Yess">
			</div>
			<!-- start: LOGIN BOX -->
			<div class="box-login" style="display:block;">
				<h3>New Password</h3>

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
			  	<form action="{{url('/new/password')}}" method="post">
			  		<input type="hidden" name="user_id" value="{{$email->id}}">
			  		<input type="hidden" name="token" value="{{$remember_token}}">
					<input type="hidden" name="_token" value="{{ csrf_token() }}" />

					<div class="form-group form-actions">
						<span class="input-icon">
							<input type="password" class="form-control lock" name="password" placeholder="*****">
							<i class="fa fa-lock"></i>
							
					</div>

					<div class="form-group form-actions">
						<span class="input-icon">
							<input type="password" class="form-control lock" name="confirm_password" placeholder="*****">
							<i class="fa fa-lock"></i>
							
					</div>

					<center>
						<div class="row">
							<input type="submit" value="PASSWORD SUBMIT"  class="btn btn-primary">
						</div>
					</center>
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