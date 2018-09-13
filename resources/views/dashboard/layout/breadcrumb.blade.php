<!-- start: PAGE HEADER -->
<div class="row">
	<div class="col-sm-12">

		<!-- start: PAGE TITLE & BREADCRUMB -->
		<ol class="breadcrumb">
			<li>
				<i class="clip-home-3"></i>
				<a href="{{url('/dashboard/'.Auth::user()->user_type)}}">
					Dashboard
				</a>
			</li>
			<li class="active">
				<a href="{{\Request::url()}}">
					{{isset($page_title) ? $page_title : ''}}
				</a>
			</li>

		</ol>

		<!-- end: PAGE TITLE & BREADCRUMB -->
		<div class="page-header">
			<h1>{{isset($page_title) ? strtoupper($page_title) : ''}} <small>{{isset($page_title) ? strtolower($page_title) : ''}} page</small></h1>
		</div>
	</div>
</div>
<!-- end: PAGE HEADER -->