@extends('dashboard.layout.master')
@section('content')
<div class="row">
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

<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="clip-users-2"></i>
				All Push List
				<div class="panel-tools">
					<a class="btn btn-xs btn-link panel-collapse collapses" data-toggle="tooltip" data-placement="top" title="Show / Hide" href="#">
					</a>
					<a class="btn btn-xs btn-link panel-config" data-toggle="tooltip" data-placement="top" title="Add Account" href="#">
						<i class="clip-folder-plus"></i>
					</a>
					<a class="btn btn-xs btn-link panel-close red-tooltip" data-toggle="tooltip" data-placement="top" title="Close" href="#">
						<i class="fa fa-times"></i>
					</a>
				</div>
			</div>


			<div class="panel-body">
				<div class="table-responsive ">
					<table class="table table-hover table-bordered table-striped nopadding" id="sample-table-1">
						<thead>
							<tr>
								<th>SL</th>
								<th>Title </th>
								<th>Message </th>
								<th>Date </th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							@if(!empty($all_push_info) && count($all_push_info) > 0)
							@foreach($all_push_info as $key => $list)
							<tr >
								<td>{{$key+1}}</td>
								<td>{{isset($list->title)? $list->title : 'Coupon Push' }}</td>
								<td>{{$list->message}}</td>
								<td>{{$list->created_at }}</td>
								<td>
									<a href="{{url('/dashboard/push/delete-'.$list->notification_id)}}" class="btn btn-xs btn-bricky tooltips" data-toggle1="tooltip" title="Push Delete"><i class="fa  fa-trash-o"></i></a>
								</td>	
							</tr>
							@endforeach
							@else
							<tr class="text-center">
								<td colspan="5">No Data available</td>
							</tr>
							@endif
						</tbody>
					</table>
					{{isset($push_pagination)?$push_pagination:''}}
				</div>

			</div>
		</div>
	</div>
</div>


@stop