@extends('dashboard.layout.master')
@section('content')
<div class="row">
	<div class="col-md-12 dashboard_btn" style="cursor:pointer;">
<!-- 		<div id="dashboard">
          <div class="to"> Manage</div>
		</div> -->

		<div class="col-md-3"  onclick="location.href='{{url('/user/profile')}}';">
			<div class="core-box">
				<div class="heading">
					<i class="clip-user-4 circle-icon circle-green"></i>
					<h2>User Profile</h2>
				</div>
				
			</div>
		</div>
		<div class="col-md-3" onclick="location.href='{{url('/dashboard/branch/summery')}}';">
			<div class="core-box">
				<div class="heading">
					<i class="clip-clip circle-icon circle-red"></i>
					<h2>Branch Summery</h2>
				</div>
			</div>
		</div>
		<div class="col-md-3"  onclick="location.href='{{url('/dashboard/branch/coupon-transaction/list')}}';">
			<div class="core-box">
				<div class="heading">
					<i class="fa fa-bell-o circle-icon circle-bricky"></i>
					<h2>Transaction List</h2>
				</div>
				
			</div>
		</div>
		

		<div class="col-md-3"  onclick="location.href='{{url('/dashboard/branch/confirm-transaction/list')}}';">
			<div class="core-box">
				<div class="heading">
					<i class="clip-pencil circle-icon circle-teal"></i>
					<h2>Confirm Transaction</h2>
				</div>
			</div>
		</div>

	</div>
</div>
@stop