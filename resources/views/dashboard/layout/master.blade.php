<!DOCTYPE html>

<html lang="en" class="no-js">

<head>
	<title>{{isset($page_title) ? $page_title : ''}} Yess </title>
	<!-- start: META -->
	<meta charset="utf-8" />
	<!--[if IE]><meta http-equiv='X-UA-Compatible' content="IE=edge,IE=9,IE=8,chrome=1" /><![endif]-->
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta content="" name="description" />
	<meta content="" name="author" />
	<!-- end: META -->
	<!-- start: MAIN CSS -->


	<link rel="stylesheet" href="{{asset('assets/plugins/bootstrap/css/bootstrap.min.css')}}">
	<link rel="stylesheet" href="{{asset('assets/plugins/font-awesome/css/font-awesome.min.css')}}">
	<link rel="stylesheet" href="{{asset('assets/fonts/style.css')}}">
	<link rel="stylesheet" href="{{asset('assets/css/main.css')}}">
	<link rel="stylesheet" href="{{asset('assets/css/main-responsive.css')}}">
	<link rel="stylesheet" href="{{asset('assets/plugins/iCheck/skins/all.css')}}">
	<link rel="stylesheet" href="{{asset('assets/plugins/bootstrap-colorpalette/css/bootstrap-colorpalette.css')}}">
	<link rel="stylesheet" href="{{asset('assets/plugins/perfect-scrollbar/src/perfect-scrollbar.css')}}">
	<link rel="stylesheet" href="{{asset('assets/css/theme_light.css')}}" type="text/css" id="skin_color">
	<link rel="stylesheet" href="{{asset('assets/css/print.css')}}" type="text/css" media="print"/>
	<link rel="stylesheet" href="{{asset('assets/plugins/bootstrap-fileupload/bootstrap-fileupload.min.css')}}">
	<link rel="stylesheet" href="{{asset('assets/plugins/bootstrap-social-buttons/social-buttons-3.css')}}">

	<!-- custom css -->
	<link rel="stylesheet" href="{{asset('assets/css/custom.css')}}">

	<!-- table view -->
	<link rel="stylesheet" href="{{asset('assets/plugins/DataTables/media/css/DT_bootstrap.css')}}" />

	<!-- switch nutton -->
	<link rel="stylesheet" href="{{asset('assets/plugins/bootstrap-switch/static/stylesheets/bootstrap-switch.css')}}">
	
	<link rel="stylesheet" href="{{asset('assets/plugins/dropzone/downloads/css/dropzone.css')}}">


	<!--[if IE 7]>
	<link rel="stylesheet" href="assets/plugins/font-awesome/css/font-awesome-ie7.min.css">
	<![endif]-->
	<!-- end: MAIN CSS -->
	<!-- start: CSS REQUIRED FOR THIS PAGE ONLY -->
	<link rel="stylesheet" href="{{asset('assets/plugins/fullcalendar/fullcalendar/fullcalendar.css')}}">
	<!-- end: CSS REQUIRED FOR THIS PAGE ONLY -->
	<!-- <link rel="shortcut icon" href="favicon.ico" /> -->
	<link rel="shortcut icon" href="{{asset('assets/images/ficon.png')}}"/>

	<link rel="stylesheet" href="{{asset('css/bootstrap-datetimepicker.min.css')}}" />
	<!-- <link rel="stylesheet" href="{{asset('css/datetimepicker.css')}}" /> -->

</head>
<!-- end: HEAD -->
<!-- start: BODY -->

@if($page_title=='AdminLogIn' || $page_title=='Admin Forget Password' || $page_title=='Forget Password' || $page_title=='New Password' || $page_title=='Forgot Password Varify')
@yield('login-content')
@else	
<body>

	<!-- start: MAIN CONTAINER -->
	<div class="main-container">

		<div class="navbar navbar-inverse navbar-fixed-top">
			@include('dashboard.layout.top-menu')
		</div>


		<div class="navbar-content">
			@include('dashboard.layout.sidebar-menu')
		</div>

		<div class="main-content">

			<div class="container">
				@include('dashboard.layout.breadcrumb')
				<!-- start: PAGE CONTENT -->
				
				@yield('content')
				

			</div>
		</div>

	</div>
	<!-- end: MAIN CONTAINER -->

	<!-- start: FOOTER -->
	<div class="footer clearfix">
		<div class="footer-inner">
			2017 &copy; Live Technologies Ltd.
		</div>
		<div class="footer-items">
			<span class="go-top"><i class="clip-chevron-up"></i></span>
		</div>
	</div>
	<!-- end: FOOTER -->

	<!-- start: MAIN JAVASCRIPTS -->
	<!--[if lt IE 9]>
	<script src="assets/plugins/respond.min.js"></script>
	<script src="assets/plugins/excanvas.min.js"></script>
	<script type="text/javascript" src="assets/plugins/jQuery-lib/1.10.2/jquery.min.js"></script>
	<![endif]-->
	<!--[if gte IE 9]><!-->
	<script src="{{asset('assets/plugins/jQuery-lib/2.0.3/jquery.min.js')}}"></script>
	<!--<![endif]-->
	<script src="{{asset('assets/plugins/jquery-ui/jquery-ui-1.10.2.custom.min.js')}}"></script>
	<script src="{{asset('assets/plugins/bootstrap/js/bootstrap.min.js')}}"></script>
	<script src="{{asset('assets/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js')}}"></script>
	<script src="{{asset('assets/plugins/blockUI/jquery.blockUI.js')}}"></script>
	<script src="{{asset('assets/plugins/iCheck/jquery.icheck.min.js')}}"></script>
	<script src="{{asset('assets/plugins/perfect-scrollbar/src/jquery.mousewheel.js')}}"></script>
	<script src="{{asset('assets/plugins/perfect-scrollbar/src/perfect-scrollbar.js')}}"></script>
	<script src="{{asset('assets/plugins/less/less-1.5.0.min.js')}}"></script>
	<script src="{{asset('assets/plugins/jquery-cookie/jquery.cookie.js')}}"></script>
	<script src="{{asset('assets/plugins/bootstrap-colorpalette/js/bootstrap-colorpalette.js')}}"></script>
	<script src="{{asset('assets/js/main.js')}}"></script>
	<!-- end: MAIN JAVASCRIPTS -->
	<!-- start: JAVASCRIPTS REQUIRED FOR THIS PAGE ONLY -->
	<script src="{{asset('assets/plugins/flot/jquery.flot.js')}}"></script>
	<script src="{{asset('assets/plugins/flot/jquery.flot.pie.js')}}"></script>
	<script src="{{asset('assets/plugins/flot/jquery.flot.resize.min.js')}}"></script>
	<script src="{{asset('assets/plugins/jquery.sparkline/jquery.sparkline.js')}}"></script>
	<script src="{{asset('assets/plugins/jquery-easy-pie-chart/jquery.easy-pie-chart.js')}}"></script>
	<script src="{{asset('assets/plugins/jquery-ui-touch-punch/jquery.ui.touch-punch.min.js')}}"></script>
	<script src="{{asset('assets/plugins/fullcalendar/fullcalendar/fullcalendar.js')}}"></script>
	<script src="{{asset('assets/js/index.js')}}"></script>
	<script src="{{asset('assets/plugins/bootstrap-fileupload/bootstrap-fileupload.min.js')}}"></script>
	<script src="{{asset('assets/js/login.js')}}"></script>
	

	<!-- custom js -->
	<script src="{{asset('assets/js/custom.js')}}"></script>

	<!-- table view -->
	<script type="text/javascript" src="{{asset('assets/plugins/DataTables/media/js/jquery.dataTables.min.js')}}"></script>
	<script type="text/javascript" src="{{asset('assets/plugins/DataTables/media/js/DT_bootstrap.js')}}"></script>

	<!-- switch button -->
	<script src="{{asset('assets/plugins/bootstrap-switch/static/js/bootstrap-switch.min.js')}}"></script>

		<script src="{{asset('assets/plugins/dropzone/downloads/dropzone.min.js')}}"></script>
		<script src="{{asset('assets/js/form-dropzone.js')}}"></script>



		<script src="{{asset('assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js')}}"></script>
		<script src="{{asset('assets/plugins/jquery-inputlimiter/jquery.inputlimiter.1.3.1.min.js')}}"></script>
		<script src="{{asset('assets/plugins/autosize/jquery.autosize.min.js')}}"></script>
		<script src="{{asset('assets/plugins/select2/select2.min.js')}}"></script>
		<script src="{{asset('assets/plugins/jquery.maskedinput/src/jquery.maskedinput.js')}}"></script>
		<script src="{{asset('assets/plugins/jquery-maskmoney/jquery.maskMoney.js')}}"></script>
		<script src="{{asset('assets/js/form-elements.js')}}"></script>

		<!-- <script src="{{asset('assets/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.js')}}"></script> -->
		<!-- <script src="{{asset('assets/plugins/bootstrap-colorpicker/js/commits.js')}}"></script> -->
		<!-- <script src="{{asset('assets/plugins/jQuery-Tags-Input/jquery.tagsinput.js')}}"></script> -->
		<!-- <script src="{{asset('assets/plugins/bootstrap-fileupload/bootstrap-fileupload.min.js')}}"></script> -->
		<!-- <script src="{{asset('assets/plugins/summernote/build/summernote.min.js')}}"></script> -->
		<!-- <script src="{{asset('assets/plugins/ckeditor/ckeditor.js')}}"></script> -->
		<!-- <script src="{{asset('assets/plugins/ckeditor/adapters/jquery.js')}}"></script> -->


		<script>
			jQuery(document).ready(function() {
				Main.init();
				FormElements.init();
				
				$('.date-picker-2').datepicker({
						autoclose: true
					});

			});
		</script>


	<input type="hidden" class="site_url" value="{{url('/')}}" >
</body>
@endif
<!-- end: BODY -->

</html>