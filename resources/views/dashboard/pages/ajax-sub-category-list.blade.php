@if(!empty($category_list))
	<option value="0">Select Sub Category</option>
	@foreach($category_list as $key => $list)
		<option value="{{$list->sub_category_id}}">{{$list->sub_category_name}}</option>
	@endforeach
@else
	<option value="0">Select Sub Category</option>
@endif