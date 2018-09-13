@if(!empty($merchant_list) && count($merchant_list)>0)
	<option value="0">Select Merchant</option>
	@foreach($merchant_list as $key => $list)
		<option value="{{$list->merchant_id}}">{{$list->merchant_name}}</option>
	@endforeach
@else
	<option value="0">Select Merchant</option>
@endif