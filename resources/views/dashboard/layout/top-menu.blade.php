

<!-- start: TOP NAVIGATION CONTAINER -->
<div class="container">
	<div class="navbar-header">
		<!-- start: RESPONSIVE MENU TOGGLER -->
		<button data-target=".navbar-collapse" data-toggle="collapse" class="navbar-toggle" type="button">
			<span class="clip-list-2"></span>
		</button>
		<!-- end: RESPONSIVE MENU TOGGLER -->
		<!-- start: LOGO -->
		<a class="navbar-brand" href="{{url('/dashboard/'.\Auth::user()->user_type)}}">
			<!-- <strong> Coupon </strong> -->
			<?php 
				$company_info=\DB::table("company_details")->first();
				$image_info=$company_info->company_logo;
			?>
			<img src="{{asset('assets/images/company/'.($image_info?$image_info:''))}}" alt="">
		</a>
		<!-- end: LOGO -->
	</div>
	<div class="navbar-tools">
		<!-- start: TOP NAVIGATION MENU -->
		<ul class="nav navbar-right">

			<!-- start: USER DROPDOWN -->
			<li class="dropdown current-user">
				<a data-toggle="dropdown" data-hover="dropdown" class="dropdown-toggle" data-close-others="true" href="#">
				@if(\Auth::check())
					@if(!empty(\Auth::user()->user_profile_image))
					<img src="{{asset('assets/images/userprofile/small-icon/'.\Auth::user()->user_profile_image)}}" class="circle-img" >
					@else
					<img src="{{asset('assets/images/userprofile/small-icon/profile.jpg')}}" class="circle-img" >
					@endif
				@endif
					<span class="username">{{isset(\Auth::user()->name) ? \Auth::user()->name : ''}}</span>
					<i class="clip-chevron-down"></i>
				</a>
				<ul class="dropdown-menu">
					<li>
						<a href="{{url('/dashboard/'.(isset(\Auth::user()->user_type) ? \Auth::user()->user_type : ''))}}">
							<i class="clip-home-2"></i>
							&nbsp;Dashboard
						</a>
					</li>
					<li>
						<a href="{{url('/user/profile')}}">
							<i class="clip-user-2"></i>
							&nbsp;My Profile
						</a>
					</li>

					<li class="divider"></li>

					<li>
						<a href="{{url('/user/profile/?tab=change_password')}}">
							<i class="fa fa-lock"></i>
							&nbsp;Change Password
						</a>
					</li>
					<li>
						<a href="{{url('/logout')}}">
							<i class="clip-exit"></i>
							&nbsp;Log Out
						</a>
					</li>
				</ul>
			</li>
				<!-- end: USER DROPDOWN -->

		</ul>
			<!-- end: TOP NAVIGATION MENU -->
	</div>
</div>
	<!-- end: TOP NAVIGATION CONTAINER -->
