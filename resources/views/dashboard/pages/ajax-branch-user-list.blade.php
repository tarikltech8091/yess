@if(!empty($branch_list) && count($branch_list)>0)
	<option value="0">Select Branch</option>
	@foreach($branch_list as $key => $list)
		<option value="{{$list->branch_id}}">{{$list->branch_name}}</option>
	@endforeach
@else
	<option value="0">Select Branch</option>
@endif