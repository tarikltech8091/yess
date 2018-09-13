@extends('dashboard.layout.master')
@section('content')
<div class="row">
	<div class="col-md-12 dashboard_btn" style="cursor:pointer;">

		<div class="col-md-12">
			<div id="dashboard">
	          <div class="to"> Manage Primery Module</div>
			</div>
		</div>

		<div class="col-md-3"  onclick="location.href='{{url('/dashboard/user-management')}}';">
			<div class="core-box">
				<div class="heading">
					<i class="clip-user-4 circle-icon circle-green"></i>
					<h2>Manage Users</h2>
				</div>
				
			</div>
		</div>
		<div class="col-md-3" onclick="location.href='{{url('/dashboard/category')}}';">
			<div class="core-box">
				<div class="heading">
					<i class="clip-clip circle-icon circle-red"></i>
					<h2>Category</h2>
				</div>
			</div>
		</div>
		<div class="col-md-3"  onclick="location.href='{{url('/dashboard/sub-category')}}';">
			<div class="core-box">
				<div class="heading">
					<i class="fa fa-tasks circle-icon circle-bricky"></i>
					<h2>Sub Category</h2>
				</div>
				
			</div>
		</div>
		

		<div class="col-md-3"  onclick="location.href='{{url('/dashboard/home-slider')}}';">
			<div class="core-box">
				<div class="heading">
					<i class="fa fa-credit-card circle-icon circle-teal"></i>
					<h2>Home Slider</h2>
				</div>
			</div>
		</div>

		

		<div class="col-md-12">
			<div id="dashboard">
	          <div class="to"> Manage Coupon</div>
			</div>
		</div>


		<div class="col-md-3"  onclick="location.href='{{url('/dashboard/sell-quantity')}}';">
			<div class="core-box">
				<div class="heading">
					<i class="clip-database  circle-icon circle-red"></i>
					<h2>Sell Quantity</h2>
				</div>
				
			</div>
		</div>


		<div class="col-md-3"  onclick="location.href='{{url('/dashboard/merchant-page')}}';">
			<div class="core-box">
				<div class="heading">
					<i class="clip-user-4 circle-icon circle-bricky"></i>
					<h2>Merchant</h2>
				</div>
			</div>
		</div>

		<div class="col-md-3"  onclick="location.href='{{url('/dashboard/merchant-branch')}}';">
			<div class="core-box">
				<div class="heading">
					<i class="fa fa-credit-card circle-icon circle-teal" aria-hidden="true"></i>
					<h2>Branch</h2>
				</div>
			</div>
		</div>

		<div class="col-md-3" onclick="location.href='{{url('/dashboard/coupon')}}';">
			<div class="core-box">
				<div class="heading">
					<i class="fa fa-tags circle-icon circle-green"></i>
					<h2> Coupon</h2>
				</div>
			</div>
		</div>



		<div class="col-md-12">
			<div id="dashboard">
	          <div class="to"> Manage All List </div>
			</div>
		</div>

		<div class="col-md-3"  onclick="location.href='{{url('/dashboard/all-merchant/list')}}';">
			<div class="core-box">
				<div class="heading">
					<i class="fa fa-bars circle-icon circle-bricky"></i>
					<h2>All Merchant List</h2>
				</div>
			</div>
		</div>

		<div class="col-md-3"  onclick="location.href='{{url('/dashboard/all-coupon/list')}}';" >
			<div class="core-box">
				<div class="heading">
					<i class="fa fa-th-list circle-icon circle-teal"></i>
					<h2> All Coupon List</h2>
				</div>
			</div>
		</div>

		<div class="col-md-3"  onclick="location.href='{{url('/dashboard/all-user/list')}}';">
			<div class="core-box">
				<div class="heading">
					<i class="fa fa-list-ul circle-icon circle-green"></i>
					<h2>All User List</h2>
				</div>
			</div>
		</div>


		<div class="col-md-3"  onclick="location.href='{{url('/dashboard/all-coupon/comments/list')}}';">
			<div class="core-box">
				<div class="heading">
					<i class="fa fa-th-list circle-icon circle-red"></i>
					<h2>All Comment List</h2>
				</div>
			</div>
		</div>


		<div class="col-md-3" onclick="location.href='{{url('/dashboard/active-deal')}}';">
			<div class="core-box">
				<div class="heading">
					<i class="fa fa-tasks circle-icon circle-teal"></i>
					<h2>Active Deal List</h2>
				</div>
			</div>
		</div>

		<div class="col-md-3" onclick="location.href='{{url('/dashboard/pending-coupon/transaction/list')}}';">
			<div class="core-box">
				<div class="heading">
					<i class="fa fa-list-ul circle-icon circle-green"></i>
					<h2>Pending Transaction</h2>
				</div>
			</div>
		</div>

		<div class="col-md-3"  onclick="location.href='{{url('/dashboard/all-coupon/confirm/transaction/list')}}';">
			<div class="core-box">
				<div class="heading">
					<i class="fa fa-bars circle-icon circle-red"></i>
					<h2>Confirm Transaction</h2>
				</div>
			</div>
		</div>


		<div class="col-md-3"  onclick="location.href='{{url('/dashboard/merchant/featured')}}';">
			<div class="core-box">
				<div class="heading">
					<i class="fa fa-list-alt circle-icon circle-bricky"></i>
					<h2>Merchant Featured </h2>
				</div>
			</div>
		</div>
		

		<div class="col-md-12">
			<div id="dashboard">
	          <div class="to"> Manage All Logs</div>
			</div>
		</div>

		<div class="col-md-3"  onclick="location.href='{{url('/system-admin/access-logs')}}';">
			<div class="core-box">
				<div class="heading">
					<i class="fa fa-briefcase circle-icon circle-green"></i>
					<h2>Access Logs</h2>
				</div>
				
			</div>
		</div>

		<div class="col-md-3"  onclick="location.href='{{url('/system-admin/event-logs')}}';">
			<div class="core-box">
				<div class="heading">
					<i class="fa fa-credit-card circle-icon circle-red"></i>
					<h2>Event Logs</h2>
				</div>
			</div>
		</div>

		<div class="col-md-3"  onclick="location.href='{{url('/system-admin/auth-logs')}}';">
			<div class="core-box">
				<div class="heading">
					<i class="clip-database circle-icon circle-bricky"></i>
					<h2>Auth Logs</h2>
				</div>
			</div>
		</div>

		<div class="col-md-3"  onclick="location.href='{{url('/system-admin/error-logs')}}';">
			<div class="core-box">
				<div class="heading">
					<i class="fa fa-money circle-icon circle-teal"></i>
					<h2>Error Logs </h2>
				</div>
			</div>
		</div>


	</div>
</div>
@stop