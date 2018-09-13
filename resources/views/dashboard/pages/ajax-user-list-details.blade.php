<div class="panel panel-info">
	<div class="panel-heading" align="center"><strong>{{$select_user_info->name}} Profile</strong></div>
	 <p align="center">
	 	@if(!empty($select_user_info->user_profile_image))
	 	<br><img src="{{asset('assets/images/userprofile/'.$select_user_info->user_profile_image)}}">
	 	@else
	 	<br><img height="150px" width="150px" src="{{asset('assets/images/profile.jpg')}}">
	 	@endif
	 </p>
 <div class="panel-body">
  <table border="1" class="table table-hover table-bordered table-striped">
    <thead>
  		<th>Field</th>
  		<th>Value</th>
    </thead>
  	<tbody>
		<tr>
	  		<td>NAME</td>
	  		<td>{{$select_user_info->name}}</td>
  		</tr>
  		<tr>
	  		<td>EMAIL</td>
	  		<td>{{$select_user_info->email}}</td>
  		</tr>
  		<tr>
	  		<td>MOBILE</td>
	  		<td>{{$select_user_info->mobile}}</td>

	    </tr>
	    @if(!empty($user_meta_info))
	    @foreach ($user_meta_info as $key => $value)
		<tr>
	  		<td>{{strtoupper($value->user_meta_field_name)}}</td>
	  		<td>{{$value->user_meta_field_value}}</td>
	    </tr>
	    @endforeach
	    @endif

  	</tbody>
  </table>
 </div>
</div>