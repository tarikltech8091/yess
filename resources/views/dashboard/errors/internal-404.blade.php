@extends('layout.master')
@section('content')
<div class="row">
	<div class="col-sm-12 page-error">
		<div class="error-details col-sm-6 col-sm-offset-3">
			<h3>Oops! You are stuck at 404</h3>
			<p>
				Unfortunately the page you were looking for could not be found.
				<br>
				It may be temporarily unavailable, moved or no longer exist.
				<br>
				Check the URL you entered for any mistakes and try again.
				<br>
				<!-- <a href="{{(\Auth::check()) ? url('/dashboard/'.\Auth::user()->user_type):'#'}}" class="btn btn-teal btn-return"> -->
				<a href="{{url('/')}}" class="btn btn-teal btn-return">
					Return home
				</a>
				
			</p>
		</div>
	</div>
</div>
@stop