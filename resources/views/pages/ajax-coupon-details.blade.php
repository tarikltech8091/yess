<div class="panel panel-info">
	<div class="panel-body">
		<div class="row">

		 	<div class="col-md-6">
		 		@if(!empty($select_coupon_info->coupon_featured_image))
		 			<img src="{{asset($select_coupon_info->coupon_featured_image)}}">
		 		@else
		 			<img src="{{asset('assets/images/default.jpg')}}">
		 		@endif	
		 	</div>

		 	<div class="col-md-6">
		 		<ul>
			 		<li><h3>{{$select_coupon_info->merchant_name}}, {{$select_coupon_info->branch_name}}</h3></li>
			 		<!-- <li><strong>Coupon Code : </strong>{{$select_coupon_info->coupon_code}}</li> -->
			 		<li><strong>Address : </strong>{{$select_coupon_info->branch_address}}</li>
			 	</ul>
		 	</div>

		 	<div class="col-md-12">
		 		<ul>
			 		<li><h3>How to Get</h3></li>
				    <li>Download yess app from Google playstore, Apple apps store or browse www.yess.com.bd. Choose your discount deal and press Claim Deal.Simultaneously customer will get a SMS with branch name, Location and discount percentage. Go to store and choose your product.After choosing product go to cash counter, open active deal, give product price and press next. An OTP(one time password) will be sent to the merchant mobile number. Press next, collect OTP from cash counter & press finish.You will get a successful sms.</li>
			    </ul>
		 	</div>

		</div>
	</div>
</div>