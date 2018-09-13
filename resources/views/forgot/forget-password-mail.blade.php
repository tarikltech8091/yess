<!DOCTYPE html>
<html>
<head>
	<title>Yess</title>
</head>
<body style="margin: 0; padding: 0; background: #ece8df;">

	<div class="main-area" style="background: #f2efe9;	padding-bottom: 60px;">

		<div class="logo" style="text-align: center; padding-top: 50px;	padding-bottom: 36px;">
			<img src="{{asset('assets/images/Yes/yes.png')}}" alt="Yess" style="width: 241px; height: 100px;">
		</div>

		<div class="content" style="background: #fff; width: 420px;	padding: 60px 90px;	margin: 0 auto;">
			<h1 style="margin-top: 0; font-weight: normal; color: #38434d; font-family: Georgia,serif; font-size: 16px;	margin-bottom: 14px; line-height: 24px;">Hello,</h1>

			<p style="margin-top: 0; font-weight: 400; font-size: 14px;	line-height: 22px; color: #7c7e7f; font-family: Georgia,serif; margin-bottom: 22px;"><b>{{$user_info->name}}</b> Your request is accepted for change user password of Coupon. Your user name is {{$user_info->user_name}}. Please click below button. </p>
			<center>
				<p style="margin-top: 0; font-weight: 400; font-size: 14px;	line-height: 22px; color: #7c7e7f; font-family: Georgia,serif; margin-bottom: 22px;">
				<a href="{{$reset_url}}"><button style="height:40px;width:200px;background-color:#337ab7;font-size:20px;color:white;">Reset Password</button></a></p>
			</center>

			<p style="margin-top: 0; font-weight: 400; font-size: 14px;	line-height: 22px; color: #7c7e7f; font-family: Georgia,serif; margin-bottom: 22px;">If you didn't make this request then ignore this email.</p>

			

			<div class="footer" style="padding-top: 25px;">
				<h2 style="margin-top: 0; font-weight: normal; color: #38434d; font-family: Georgia,serif; font-size: 14px;	margin-bottom: 7px;">Thanks</h2>
				<p style="margin-top: 0; font-weight: 400; font-size: 14px;	line-height: 22px; color: #7c7e7f; font-family: Georgia,serif; margin-bottom: 22px;">If you have any query, feel free to contact our support team :<a href="mailto:support@coupon.com.bd" style="text-decoration: none;"> info@yess.com.bd </a></p>
			</div>

		</div>

	</div>

</body>
</html>
