@extends('dashboard.layout.master')
@section('content')

<div class="row">
	<div class="col-md-12">
		<div class="col-md-12 alert alert-success">
			<form action="{{url('/system-admin/event-logs')}}" class="form-inline" role="search" method="get">

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


	<div class="panel panel-body">
		<div class="row">
			<div class="col-md-12 ">
				<table class="table table-hover table-bordered table-striped">
					<thead>
						<tr>
							<th>SL</th>
							<th>Date & Time</th>
							<th>Client IP</th>
							<th>USER</th>
							<th>Page URL</th>
							<th>Event Type</th>
							<th>Event Data</th>
						</tr>
					</thead>

					<tbody>
						@if(count($event_log_list) > 0)
						@foreach($event_log_list as $key => $list)
						<tr>
							<td>{{$key+1}}</td>
							<td>{{$list->created_at}}</td>
							<td>{{$list->event_client_ip}}</td>
							<td>{{$list->event_user_id =='guest' ? 'Guest' : $list->name}}</td>
							<td>{{$list->event_request_url}}</td>
							<td>{{$list->event_type}}</td>
							<td>{{wordwrap($list->event_data, 10, '\n', true)}}</td>	
						</tr>
						@endforeach
						@else
						<tr>
							<td colspan="7">No data available</td>
						</tr>
						@endif
					</tbody>
				</table>
				{{isset($event_pagination) ? $event_pagination:""}}
			</div>
		</div>
	</div>
</div>	


@stop