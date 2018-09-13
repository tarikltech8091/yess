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
				All User {{isset($count_user)? $count_user :''}}
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

			<!-- <div class="panel-body panel-scroll" style="height:450px, margin-bottom:20px"> -->
			<div class="panel-body">
				<div class="table-responsive cost_list posting_list">
					<table class="table table-hover table-bordered table-striped nopadding" id="sample-table-1">
						<thead>
							<tr>
								<th>SL</th>
								<th>User Name</th>
								<th>User Email</th>
								<th>User Mobile</th>
								<th>Status</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody class="cost_list">
							@if(!empty($all_user_info) && count($all_user_info) > 0)
							@foreach($all_user_info as $key => $list)
							<tr >
								<td>{{$key+1}}</td>
								<td>{{$list->name}}</td>
								<td>{{$list->email}}</td>
								<td>{{$list->mobile}}</td>
								<td>
									@if($list->status==1)
										<button class="btn btn-primary btn-xs user_status col-md-12" data-user-id="{{$list->id}}" data-action="-1">Block</button>
									@elseif($list->status==-1)
										<button class="btn btn-danger btn-xs user_status col-md-12" data-user-id="{{$list->id}}"  data-action="1">Unblock</button>
									@endif
								</td>

								<td>
									<a data-toggle="modal" data-target="#UserDetailsModal"  data-id="{{$list->id}}" class="text_none user_details_show btn btn-xs btn-green tooltips" href=""><i class="fa fa-print" aria-hidden="true"></i></a>
								</td>	
							</tr>
							@endforeach
							@else
							<tr class="text-center">
								<td colspan="7">No Data available</td>
							</tr>
							@endif
						</tbody>
					</table>
					{{isset($user_list_pagination)?$user_list_pagination:''}}
				</div>

			</div>
		</div>
	</div>
</div>


<!-- Modal -->
<div id="UserDetailsModal" class="modal fade " rtabindex="-1" role="dialog">
	<div class="modal-dialog ">
		<div class="modal-content">
			
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">User Details</h4>
			</div>
			<div class="modal-body">

				<div class="user_details">

				</div>

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default"  data-dismiss="modal">OK</button>
			</div>
		</div>
	</div>
</div>

@stop