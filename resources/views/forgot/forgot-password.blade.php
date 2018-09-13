@extends('layout.master')
@section('content')
<main id="mainContent" class="main-content">
<div class="page-container ptb-60">
	<!--error message*******************************************-->
	<div class="container">
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


	<div class="container">

		<div class="col-md-6">
			<div class="col-md-6 login-left">
			  	<h3>NEW Clients</h3>
				<p>By creating an account with our store, you will be able to buy discount coupon for multiple shops.</p>
				<a class="acount-btn" href="{{url('/sign-in/page')}}">Create an Account</a>
		    </div>
		</div>


		<div class="col-md-6">
			<div class="box-login" style="display:block;">
				<h3>Forget Password</h3>
				<p>If you have an account with us, please log in. Or <a class="forgot" href="{{url('/sign-in/page')}}">Register Now</a></p>

				<form class="form-login" action="{{url('/forget/password')}}" method="POST">
					<input type="hidden" name="_token" value="{{csrf_token()}}">

					<fieldset>

						<div class="form-group form-actions">
							<span class="input-icon">
								<input type="text" class="form-control lock" name="email" placeholder="User Email">
							</span>
								
						</div>
						<div class="form-actions">
							<button type="submit" class="btn btn-bricky pull-right">
								Send Email <i class="fa fa-arrow-circle-right"></i>
							</button>
						</div>
						
					</fieldset>
				</form>
			</div>
		</div>
	</div>

</div>
<!-- </div> -->

</main>
@stop

