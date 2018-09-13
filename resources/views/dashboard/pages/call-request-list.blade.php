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
				Call Request List
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
				<form method="get" action="{{url('/call/request/list')}}">
					<!-- <input type="hidden" name="_token" value="{{csrf_token()}}"> -->
					<div class="row">
						<div class="col-md-4">
							<div class="form-group ">
								<label for="form-field-23">
									From<span class="symbol required"></span>
								</label>
								<div class="input-group">
									<input type="text" data-date-format="yyyy-mm-dd" data-date-viewmode="years" class="form-control date-picker" name="search_from" value="{{(isset($_GET['search_from']) ? $_GET['search_from'] : date("Y-m-d"))}}" placeholder="">
									<span class="input-group-addon"> <i class="fa fa-calendar"></i> </span>
								</div>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group ">
								<label for="form-field-23">
									To<span class="symbol required"></span>
								</label>
								<div class="input-group">
									<input type="text" data-date-format="yyyy-mm-dd" data-date-viewmode="years" class="form-control date-picker" name="search_to" value="{{(isset($_GET['search_to']) ? $_GET['search_to'] : date("Y-m-d"))}}">
									<span class="input-group-addon"> <i class="fa fa-calendar"></i> </span>
								</div>
							</div>
						</div>

						<div class="col-md-2">
							<div class="form-group ">
								<label for="form-field-23">
									User Type
								</label>
								<div class="input-group">
									<select name="user_type"  class="form-control">
                                        <option value="">Select Type</option>
                                        <option {{(isset($_GET['user_type']) && ($_GET['user_type'] == 'client')) ? 'selected':''}} value="client">Client</option>
                                        <option {{(isset($_GET['user_type']) && ($_GET['user_type'] == 'merchant')) ? 'selected':''}} value="merchant">Merchant</option>
                                    </select>
								</div>
							</div>
						</div>

					
						<div class="col-md-2" style="margin-top:22px;">
							<div class="form-group">
								<input type="submit" class="btn btn-primary" data-toggle1="tooltip" title="Search Call Request" value="Search">
							</div>
						</div>
					</div>
				</form>	
			</div>

			<div class="panel-body">
				<div class="table-responsive">
					<table class="table table-hover table-bordered table-striped nopadding" id="sample-table-1">
						<thead>
							<tr>
								<th>SL</th>
								<th> Name</th>
								<th> User Type</th>
								<th> Mobile</th>
								<th> Email </th>
								<th> Message </th>
								<th>Created At </th>
							</tr>
						</thead>
						<tbody class="cost_list">
							@if(!empty($call_request_info) && count($call_request_info) > 0)
							@foreach($call_request_info as $key => $list)
							<tr >
								<td>{{$key+1}}</td>
								<td>{{$list->client_name}}</td>
								<td>{{$list->user_type}}</td>
								<td>{{$list->client_mobile}}</td>
								<td>{{$list->client_email}}</td>
								<td>{{$list->client_message}}</td>
								<td>{{$list->created_at}}</td>
							</tr>
							@endforeach
							@else
							<tr class="text-center">
								<td colspan="7">No Data available</td>
							</tr>
							@endif
						</tbody>
					</table>
					{{isset($call_request_pagination)?$call_request_pagination:''}}
				</div>

			</div>
		</div>
	</div>
</div>

@stop