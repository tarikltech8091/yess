
<!-- start: SIDEBAR -->
<div class="main-navigation navbar-collapse collapse">
	<!-- start: MAIN MENU TOGGLER BUTTON -->
	<div class="navigation-toggler">
		<i class="clip-chevron-left"></i>
		<i class="clip-chevron-right"></i>
	</div>
	<!-- end: MAIN MENU TOGGLER BUTTON -->
	<!-- start: MAIN NAVIGATION MENU -->
	<ul class="main-navigation-menu">

		
	@if(\Auth::check() && (\Auth::user()->user_type)== 'admin')
		<li class="{{isset($page_title) && ($page_title=='Dashboard') ? 'active' : ''}} ">
			<a href="{{url('/dashboard/admin')}}"><i class="clip-home-3"></i>
				<span class="title"> Dashboard </span><span class="selected"></span>
			</a>
		</li>

		<li class="{{isset($page_title) && ($page_title=='User Profile') ? 'active' : ''}} ">
			<a href="{{url('/user/profile')}}"><i class="clip-user-2"></i>
				<span class="title"> My Profile </span><span class="selected"></span>
			</a>
		</li>

		<li class="{{isset($page_title) && ($page_title=='User Management') ? 'active' : ''}} ">
			<a href="javascript:void(0)"><i class="clip-user-plus"></i>
				<span class="title"> User Management </span><i class="icon-arrow"></i>
				<span class="selected"></span>
			</a>
			<ul class="sub-menu">
				<li>
					<a href="{{url('/dashboard/user-management?tab=create_user')}}">
						<span class="title"> Create User </span>
					</a>
				</li>
				<li>
					<a href="{{url('/dashboard/user-management?tab=blocked_user')}}">
						<span class="title"> Blocked User </span>
					</a>
				</li>

				<li>
					<a href="javascript:;">
						User List <i class="icon-arrow"></i>
					</a>
					<ul class="sub-menu">
						<li>
							<a href="{{url('/dashboard/user-management?tab=admin')}}">
								Admin
							</a>
						</li>

						<li>
							<a href="{{url('/dashboard/user-management?tab=merchant')}}">
								Merchant
							</a>
						</li>
						<li>
							<a href="{{url('/dashboard/user-management?tab=branch')}}">
								Branch
							</a>
						</li>
					</ul>
				</li>

			</ul>
		</li>

		

		<li class="{{isset($page_title) && ($page_title=='Company Info') ? 'active' : ''}} ">
			<a href="{{url('/dashboard/company/info')}}"><i class="clip-home-3"></i>
				<span class="title"> Company Info </span><span class="selected"></span>
			</a>
		</li>

		<li class="{{isset($page_title) && ($page_title=='Home Slider') ? 'active' : ''}} ">
			<a href="{{url('/dashboard/home-slider')}}"><i class="fa fa-sliders" aria-hidden="true"></i>
				<span class="title"> Home Slider </span><span class="selected"></span>
			</a>
		</li>

		<li class="{{isset($page_title) && ($page_title=='Sell Quantity') ? 'active' : ''}} ">
			<a href="{{url('/dashboard/sell-quantity')}}"><i class="fa fa-database" aria-hidden="true"></i>
				<span class="title"> Coupon Summery </span><span class="selected"></span>
			</a>
		</li>


		<li>
			<a href="javascript:;"><i class="fa fa-cogs" aria-hidden="true"></i>
				<span class="title"> Main Module </span><i class="icon-arrow"></i>
				<span class="selected"></span>
			</a>

			<ul class="sub-menu">

				<li class="{{isset($page_title) && ($page_title=='Category') ? 'active' : ''}} ">
					<a href="{{url('/dashboard/category')}}"><i class="fa fa-list"></i>
						<span class="title"> Category </span><span class="selected"></span>
					</a>
				</li>

				<li class="{{isset($page_title) && ($page_title=='Sub Category') ? 'active' : ''}} ">
					<a href="{{url('/dashboard/sub-category')}}"><i class="fa fa-th-list" aria-hidden="true"></i>
						<span class="title"> Sub Category </span><span class="selected"></span>
					</a>
				</li>

				<li class="{{isset($page_title) && ($page_title=='Merchant Page') ? 'active' : ''}} ">
					<a href="{{url('/dashboard/merchant-page')}}"><i class="fa fa-shopping-cart" aria-hidden="true"></i>
						<span class="title"> Merchant </span><span class="selected"></span>
					</a>
				</li>

				<li class="{{isset($page_title) && ($page_title=='Merchant Featured Image') ? 'active' : ''}} ">
					<a href="{{url('dashboard/merchant/featured')}}"><i class="fa fa-sliders" aria-hidden="true"></i>
						<span class="title"> Merchant Featured </span><span class="selected"></span>
					</a>
				</li>

				<li class="{{isset($page_title) && ($page_title=='Merchant Branch') ? 'active' : ''}} ">
					<a href="{{url('/dashboard/merchant-branch')}}"><i class="fa fa-list-alt"></i>
						<span class="title"> Branch </span><span class="selected"></span>
					</a>
				</li>

				<li class="{{isset($page_title) && ($page_title=='Coupon') ? 'active' : ''}} ">
					<a href="{{url('/dashboard/coupon')}}"><i class="fa fa-ticket"></i>
						<span class="title"> Coupon </span><span class="selected"></span>
					</a>
				</li>
			</ul>
		</li>




		<li class="{{isset($page_title) && ($page_title=='All Merchant List') ? 'active' : ''}} ">
			<a href="{{url('/dashboard/all-merchant/list')}}"><i class="fa fa-th-list" aria-hidden="true"></i>
				<span class="title"> Merchant List </span><span class="selected"></span>
			</a>
		</li>

		<li class="{{isset($page_title) && ($page_title=='All Coupon List') ? 'active' : ''}} ">
			<a href="{{url('/dashboard/all-coupon/list')}}"><i class="fa fa-list-alt" aria-hidden="true"></i>
				<span class="title"> Coupon List </span><span class="selected"></span>
			</a>
		</li>

		<li class="{{isset($page_title) && ($page_title=='All User List') ? 'active' : ''}} ">
			<a href="{{url('/dashboard/all-user/list')}}"><i class="fa fa-bars" aria-hidden="true"></i>
				<span class="title"> User List </span><span class="selected"></span>
			</a>
		</li>


		<li>
			<a href="javascript:;"><i class="fa fa-list" aria-hidden="true"></i>
				<span class="title"> All Type List </span><i class="icon-arrow"></i>
				<span class="selected"></span>
			</a>

			<ul class="sub-menu">
				<li class="{{isset($page_title) && ($page_title=='All Coupon List') ? 'active' : ''}} ">
					<a href="{{url('/dashboard/all-coupon/comments/list')}}"><i class="fa fa-th-list" aria-hidden="true"></i>
						<span class="title"> Comments List </span><span class="selected"></span>
					</a>
				</li>

				<li class="{{isset($page_title) && ($page_title=='Active Deal List') ? 'active' : ''}} ">
					<a href="{{url('/dashboard/active-deal')}}"><i class="fa fa-list" aria-hidden="true"></i>
						<span class="title"> Active Deal Transaction </span><span class="selected"></span>
					</a>
				</li>

				<li class="{{isset($page_title) && ($page_title=='Pending Coupon Transaction List') ? 'active' : ''}} ">
					<a href="{{url('/dashboard/pending-coupon/transaction/list')}}"><i class="fa fa-list" aria-hidden="true"></i>
						<span class="title"> Pending Transaction</span><span class="selected"></span>
					</a>
				</li>

				<li class="{{isset($page_title) && ($page_title=='All Coupon Confirm Transaction List') ? 'active' : ''}} ">
					<a href="{{url('/dashboard/all-coupon/confirm/transaction/list')}}"><i class="fa fa-bars" aria-hidden="true"></i>
						<span class="title"> Confirm Transaction </span><span class="selected"></span>
					</a>
				</li>


				<li class="{{isset($page_title) && ($page_title=='Push List') ? 'active' : ''}} ">
					<a href="{{url('/dashboard/push/list')}}"><i class="fa fa-list" aria-hidden="true"></i>
						<span class="title">All Push List </span><span class="selected"></span>
					</a>
				</li>

			</ul>
		</li>

		<li class="{{isset($page_title) && ($page_title=='Social Site Info') ? 'active' : ''}} ">
			<a href="{{url('/dashboard/social/site/info')}}"><i class="fa fa-th-list" aria-hidden="true"></i>
				<span class="title"> Social Site Info </span><span class="selected"></span>
			</a>
		</li>

		<li class="{{isset($page_title) && ($page_title=='Call Request List') ? 'active' : ''}} ">
			<a href="{{url('/call/request/list')}}"><i class="fa fa-bars" aria-hidden="true"></i>
				<span class="title"> Call Request List </span><span class="selected"></span>
			</a>
		</li>

		<li class="{{isset($page_title) && ($page_title=='Message Request List') ? 'active' : ''}} ">
			<a href="{{url('/message/request/list')}}"><i class="fa fa-list-alt" aria-hidden="true"></i>
				<span class="title"> Contact Message List </span><span class="selected"></span>
			</a>
		</li>

		<li class="{{isset($page_title) && ($page_title=='Push Notification Text') ? 'active' : ''}} ">
			<a href="{{url('/push/text')}}"><i class="fa fa-bars" aria-hidden="true"></i>
				<span class="title"> Push Notification Text </span><span class="selected"></span>
			</a>
		</li>


		<li>
			<a href="javascript:;"><i class="fa fa-cog" aria-hidden="true"></i>
				<span class="title"> All Logs </span><i class="icon-arrow"></i>
				<span class="selected"></span>
			</a>

			<ul class="sub-menu">
			<li>
				<a href="{{url('/system-admin/access-logs')}}">				
					<i class="fa fa-history" aria-hidden="true"></i>
					<span class="title"> Access Log</span>
				</a>
			</li>

			<li>
				<a href="{{url('/system-admin/error-logs')}}">								
					<i class="fa fa-exclamation-triangle" aria-hidden="true"></i>
					<span class="title"> Error Log </span>
				</a>
			</li>
			<li>
				<a href="{{url('/system-admin/event-logs')}}">				
					<i class="fa fa-calendar-o" aria-hidden="true"></i>
					<span class="title"> Event Log</span>
				</a>
			</li>

			<li>
				<a href="{{url('/system-admin/auth-logs')}}">								
					<i class="fa fa-shield" aria-hidden="true"></i>
					<span class="title"> Auth Log </span>
				</a>
			</li>
			</ul>
		</li>


		

		
	@endif

	@if(\Auth::check() && (\Auth::user()->user_type)== 'merchant')
		<li class="{{isset($page_title) && ($page_title=='Dashboard') ? 'active' : ''}} ">
			<a href="{{url('/dashboard/merchant')}}"><i class="clip-home-3"></i>
				<span class="title"> Dashboard </span><span class="selected"></span>
			</a>
		</li>
		<li class="{{isset($page_title) && ($page_title=='User Profile') ? 'active' : ''}} ">
			<a href="{{url('/user/profile')}}"><i class="clip-user-2"></i>
				<span class="title"> My Profile </span><span class="selected"></span>
			</a>
		</li>

		<li class="{{isset($page_title) && ($page_title=='Merchant Accept Transaction List') ? 'active' : ''}} ">
			<a href="{{url('/dashboard/merchnat-coupon/transaction/list')}}"><i class="clip-pencil"></i>
				<span class="title"> Transaction List </span><span class="selected"></span>
			</a>
		</li>

		<li class="{{isset($page_title) && ($page_title=='Merchant All Transaction List') ? 'active' : ''}} ">
			<a href="{{url('/dashboard/merchnat-buy/coupon/list')}}"><i class="fa fa-bell-o"></i>
				<span class="title">Confirm Transaction List  </span><span class="selected"></span>
			</a>
		</li>


		<li class="{{isset($page_title) && ($page_title=='Merchant All Summery') ? 'active' : ''}} ">
			<a href="{{url('/dashboard/merchnat/all-summery')}}"><i class="clip-clip"></i>
				<span class="title"> Merchant All Summery </span><span class="selected"></span>
			</a>
		</li>

	@endif

	@if(\Auth::check() && (\Auth::user()->user_type)== 'branch')

		<li class="{{isset($page_title) && ($page_title=='Dashboard Branch') ? 'active' : ''}} ">
			<a href="{{url('/dashboard/branch')}}"><i class="clip-home-3"></i>
				<span class="title"> Dashboard Branch </span><span class="selected"></span>
			</a>
		</li>

		<li class="{{isset($page_title) && ($page_title=='User Profile') ? 'active' : ''}} ">
			<a href="{{url('/user/profile')}}"><i class="clip-user-2"></i>
				<span class="title"> My Profile </span><span class="selected"></span>
			</a>
		</li>

		<li class="{{isset($page_title) && ($page_title=='Branch Coupon Transaction List') ? 'active' : ''}} ">
			<a href="{{url('/dashboard/branch/coupon-transaction/list')}}"><i class="fa fa-bell-o"></i>
				<span class="title"> Transaction List </span><span class="selected"></span>
			</a>
		</li>

		<li class="{{isset($page_title) && ($page_title=='Branch Confirm Transaction List') ? 'active' : ''}} ">
			<a href="{{url('/dashboard/branch/confirm-transaction/list')}}"><i class="clip-pencil"></i>
				<span class="title"> Confirm Transaction</span><span class="selected"></span>
			</a>
		</li>

		<li class="{{isset($page_title) && ($page_title=='Branch Summery') ? 'active' : ''}} ">
			<a href="{{url('/dashboard/branch/summery')}}"><i class="clip-clip"></i>
				<span class="title"> Branch Summery </span><span class="selected"></span>
			</a>
		</li>

	@endif


	</ul>
	<!-- end: MAIN NAVIGATION MENU -->
</div>
