@extends('dashboard.layout.master')
@section('content')

	<div class="row">
		<div class="col-md-12 ">
			<div class="col-md-12 alert alert-success">
				<form action="{{url('/system-admin/auth-logs')}}" class="form-inline" role="search" method="get">

				<div class="col-md-4">
		            <div class="form-group ">
		              <label for="form-field-23">
		                From<span class="symbol required"></span>
		              </label>
		              <div class="input-group">
		                <input type="text" data-date-format="yyyy-mm-dd" data-date-viewmode="years" class="form-control date-picker" name="form_search_date" value="{{isset($_GET['form_search_date']) ? $_GET['form_search_date'] : date('Y-m-d')}}" placeholder="">
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
		                <input type="text" data-date-format="yyyy-mm-dd" data-date-viewmode="years" class="form-control date-picker" name="to_search_date" value="{{isset($_GET['to_search_date']) ? $_GET['to_search_date'] : date('Y-m-d')}}" placeholder="">
		                <span class="input-group-addon"> <i class="fa fa-calendar"></i> </span>
		              </div>
		            </div>
		          </div>


					<button type="submit" class="btn btn-primary">Search</button>
				</form>
			</div>
		</div>
	
		<div class="row">
			<div class="panel panel-body">
				<div class="col-md-12">
					<table class="table table-hover table-bordered table-striped nopadding" >
						<thead>
							<tr>
								<th>SL</th>
								<th>Date & Time</th>
								<th>Client IP</th>
								<th>USER</th>
								<th>User Type</th>
								<th>Browser</th>
								<th>Platform</th>
								<th>City</th>
								<th>Country</th>
							</tr>
					</thead>

					<tbody>
					
						@if(count($auth_log_list) > 0)
							@foreach($auth_log_list as $key => $list)
							<tr >
								<td>{{$key+1}}</td>
								<td>{{$list->created_at}}</td>
								<td>{{$list->auth_client_ip}}</td>
								<td>{{$list->auth_user_id =='guest' ? 'Guest' : $list->name}}</td>
								<td>{{$list->auth_type}}</td>
								<td>{{$list->auth_browser}}</td>
								<td>{{$list->auth_platform}}</td>
								<td>{{$list->auth_city}}</td>
								<td>{{$list->auth_country}}</td>								
							</tr>
							@endforeach
						@else
							<tr>
								<td colspan="8">No data available</td>
							</tr>
						@endif
					</tbody>
				</table>
				{{isset($auth_pagination) ? $auth_pagination:""}}
			</div>
			</div>
		</div>
	</div>

@stop