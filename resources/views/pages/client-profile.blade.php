@extends('layout.master')
@section('content')
<main id="mainContent" class="main-content">
<div class="page-container ptb-10">
	<!--error message*******************************************-->
	<div class="container">
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


	<div class="container">


		<div id="printMainSlider">

			<div class="col-md-4">
				<div class="row">
					<div class="col-sm-12">
						<!-- <div class="tabbable"> -->
							<div class="panel panel-default">
								<div class="panel-heading" style="padding:0">
									<ul class="nav nav-tabs tab-padding tab-space-3 tab-blue" id="myTab4">
										<li class="{{isset($tab) && ($tab=='panel_overview') ? 'active' : ''}}">
											<a href="#panel_overview" role="tab" id="panel_overview-tab" data-toggle="tab" aria-controls="panel_overview">
												Overview
											</a>
										</li>

										<li class="{{isset($tab) && ($tab=='panel_edit_account') ? 'active' : ''}}">
											<a href="#panel_edit_account" role="tab" id="panel_edit_account-tab" data-toggle="tab" aria-controls="panel_edit_account">
												Update
											</a>
										</li>

										<li class="{{isset($tab) && ($tab=='change_password') ? 'active' : ''}}">
											<a href="#change_password" role="tab" id="change_password-tab" data-toggle="tab" aria-controls="change_password">
												Change Password
											</a>
										</li>
									</ul>
								</div>

								<div class="tab-content">
									<div id="panel_overview" class="tab-pane in {{isset($tab) && ($tab=='panel_overview') ? 'in active' : ''}}" aria-labelledby="panel_overview-tab">

										<div class="form-group" align="center">
											<br><h4 align="center">
											{{isset($client_info->name) ? $client_info->name : ''}}</h4>
											<table class="table table-condensed table-hover">
												@if(!empty($client_info->user_profile_image))
												<img src="{{asset('assets/images/userprofile/'.$client_info->user_profile_image)}}" alt="User Profile Photo" class="thumbnail">
												@else
												<img src="{{asset('assets/images/profile.jpg')}}" height="150px" width="150px" alt="User Profile Photo" class="thumbnail">
												@endif
											</table>
										</div>


										<table class="table table-condensed table-hover">
											<thead>
												<tr>
													<th colspan="3">Contact Information</th>
												</tr>
											</thead>
											<tbody>

												<tr>
													<td>Name :</td>
													<td>
													<a href="">
														{{isset($client_info->name) ? strtolower($client_info->name) : ''}}
													</a></td>
												</tr>

												<tr>
												<td>Email :</td>
												<td>
													<a href="">
														{{isset($client_info->email) ? $client_info->email : ''}}
													</a></td>
												</tr>
												<tr>
													<td>Mobile :</td>
													<td>{{isset($client_info->mobile) ? $client_info->	mobile : ''}}</td>
												</tr>

											</tbody>
										</table>
										<table class="table table-condensed table-hover">
											<thead>
												<tr>
													<th colspan="3">General information</th>
												</tr>
											</thead>
											<tbody>
												<tr>
													<td>Position :</td>
													<td>{{isset($client_info->user_type) ? ucfirst($client_info->user_type) : ''}}</td>
													<td><a href="" class="show-tab"></a></td>
												</tr>

											</tbody>
										</table>

									</div>

									<div id="panel_edit_account" class="tab-pane in {{isset($tab) && ($tab=='panel_edit_account') ? 'in active' : ''}}" aria-labelledby="panel_edit_account-tab" style="margin:5px;">
										<form action="{{url('/client/profile/update/id-'.$client_info->id)}}" method="post" enctype="multipart/form-data" role="form" id="form">

											<div class="row">
												<div class="col-md-12">
													<h2 align="center">Account Info</h2>
													<hr>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<label class="control-label">
															Name
														</label>
														<input type="text" placeholder="Name" class="form-control" id="name" name="name" value="{{isset($client_info->name) ? $client_info->name : ''}}">
													</div>

													<div class="form-group">
														<label class="control-label">
															Email Address
														</label>
														<input type="email" placeholder="email@example.com" class="form-control" id="email" name="email" value="{{isset($client_info->email) ? $client_info->email : ''}}">
													</div>
													<div class="form-group">
														<label class="control-label">
															Mobile
														</label>
														<input type="text" placeholder="User Mobile" class="form-control" id="phone" name="mobile" value="{{isset($client_info->mobile) ? $client_info->mobile : ''}}">
													</div>


												</div>

												<div class="col-md-6">
													@if(!empty($client_info->user_profile_image))
													<img src="{{asset('assets/images/userprofile/'.$client_info->user_profile_image)}}" alt="User Profile Photo" class="thumbnail">
													@else
													<img src="{{asset('assets/images/profile.jpg')}}" height="150px" width="150px" alt="User Profile Photo" class="thumbnail">
													@endif

													<input type="file" name="image_url" />

												</div>
											</div>

											<hr>

											<div class="row">
												<div class="col-md-8">

												</div>
												<div class="col-md-4">
													<input type="hidden" name="_token" value="{{csrf_token()}}">
													<button class="btn btn-success btn-block" type="submit">
														Update <i class="fa fa-arrow-circle-right"></i>
													</button>
												</div>
											</div>
										</form>
									</div>



									<div id="change_password" class="tab-pane in {{isset($tab) && ($tab=='change_password') ? 'in active' : ''}}" aria-labelledby="change_password-tab">
										<div class="row">
											<div class="col-md-12 change_password"  style="margin:10px;">
												<div class="col-md-5">
													@if(!empty($client_info->user_profile_image))
													<img alt="profile photo" src="{{asset('assets/images/userprofile/'.$client_info->user_profile_image)}}" class="thumbnail profile_img_size"/>
													@else
													<img src="{{asset('assets/images/profile.jpg')}}" height="150px" width="150px" alt="User Profile Photo" class="thumbnail">
													@endif
													<h3>{{isset($client_info->name) ? $client_info->name : ''}}</h3>
												</div>
												<div class="col-md-7">
													<form action="{{url('/client/change-password/id-'.$client_info->id)}}" method="post" enctype="multipart/form-data">
														<input type="hidden" name="_token" value="{{csrf_token()}}" >
														<div class="input-group" style="margin-top:7px">
															<span><i>New Password</i></span>
															<input type="password" name="new_password" placeholder="New Password" class="form-control" value="">
														</div>


														<div class="input-group" style="margin-top:7px">
															<span><i>Confirm Password</i></span>
															<input type="password" name="confirm_password" placeholder="Confirm Password" class="form-control" value="">
														</div>
														<!-- <br> -->
														<div class="input-group" style="margin-top:7px">
															<!-- <span><i>Current Password</i></span> -->
															<input type="password" name="current_password" placeholder="Current Password" class="form-control" value="">
															<span class="input-group-btn">
																<button class="btn btn-blue" type="submit">
																	<i class="fa fa-chevron-right"></i>
																</button>
															</span>
														</div>

													</form>
												</div>
											</div>
										</div>
									</div>
								</div>

							</div>
						<!-- </div> -->
					</div>
					<div  class="col-sm-12">
						<div class="panel panel-default">
							<div class="panel-body">
								
								<table class="table table-condensed table-hover">
									<thead>
										<tr>
											<th class="text-center"><strong> Point Info </strong></th>
										</tr>
									</thead>
									<tbody>
										<td class="text-center"><strong>Total Point :
										@if(!empty($client_meta_info))
										{{(($client_meta_info->user_meta_field_value)?($client_meta_info->user_meta_field_value) :0)}}
										@else
											<span>0</span>
										@endif
										</strong></td>
									</tbody>
								</table>
							</div>
						</div>
					</div>
					<br>
				</div>
			</div>


			<div class="col-md-8">
				<div class="panel panel-default">
					<div class="panel-heading">
						<ul class="nav nav-tabs">
							<li class="{{isset($tab2) && ($tab2=='coupon_list') ? 'active' : ''}}"><a class="btn btn-info btn-squared" href="#coupon_list" aria-controls="Coupon" role="tab" data-toggle="tab">Coupon</a></li>
							<li class="{{isset($tab2) && ($tab2=='wish_list') ? 'active' : ''}}"><a class="btn btn-info btn-squared" href="#wish_list" aria-controls="Wish List" role="tab" data-toggle="tab">Wish List</a></li>
							<li class="{{isset($tab2) && ($tab2=='follow_list') ? 'active' : ''}}"><a class="btn btn-info btn-squared" href="#follow_list" aria-controls="Follow List" role="tab" data-toggle="tab">Follow </a></li>
						</ul>
					</div>
					<div class="panel-body">
						<!-- Tab panes -->
						<div class="tab-content">
							<div role="tabpanel" class="tab-pane active" id="coupon_list" style="width: 100%; overflow: auto; padding: 5px">
								<table class="table table-hover table-bordered table-striped text-center">
									<thead>
										<tr>
											<th>SL</th>
											<th>Merchant</th>
											<th>Branch</th>
											<th class="text-center">Closing Time</th>
											<th>Status</th>
											<th>Shopping Amount(Tk)</th>
											<th>Discount Amount(Tk)</th>
											<th>Coupon Price(Tk)</th>

										</tr>
									</thead>

									<tbody>
										@if(!empty($client_shopping_info) && count($client_shopping_info)>0)
										@foreach($client_shopping_info as $key => $list)
										<tr>
											<td>{{$key+1}}</td>
											<td>{{$list->merchant_name}}</td>
											<td>
	                        					{{$list->branch_name}}
											</td>
											<td>
	                        					@if($list->coupon_closing_date >=  date('Y-m-d').' 23:59:59')
													<a href="{{url('/single-page/coupon_id-'.$list->coupon_id)}}" class="">
														<span class="t-uppercase btn btn-success" data-countdown="{{$list->coupon_closing_date}} 23:59:59"></span>
													</a>
												@else
													<span class="t-uppercase btn btn-success" data-countdown="{{$list->coupon_closing_date}} 23:59:59"></span>

												@endif
											</td>
											<td>
	                        				@if($list->coupon_closing_date >=  date('Y-m-d').' 23:59:59')

												@if($list->coupon_status=='-1')

													<!-- <a data-toggle="modal" data-target="#BuyCouponModal"  data-id="{{$list->merchant_id}}" data-code="{{$list->coupon_code}}" data-mobile="{{$list->customer_mobile}}" data-tid="{{$list->coupon_transaction_id}}" data-tab="amount" class="text_none shopping_amount_details_show btn btn-warning btn-xs tooltips">Active Deal</a> -->

													<a href="{{url('/coupon/amount/otp/ccode-'.$list->coupon_code.'/cmobile-'.$list->customer_mobile.'/tid-'.$list->coupon_transaction_id)}}" class="text_none btn btn-warning btn-xs tooltips">Active Deal</a>
													
												@elseif($list->coupon_status=='1')

													<!-- <a data-toggle="modal" data-target="#BuyCouponModal"  data-id="{{$list->merchant_id}}" data-code="{{$list->coupon_code}}" data-mobile="{{$list->customer_mobile}}" data-tid="{{$list->coupon_transaction_id}}" data-tab="otp" class="text_none shopping_amount_details_show btn btn-info btn-xs tooltips">Buy Coupon</a> -->

													<a href="{{url('/coupon/amount/otp/ccode-'.$list->coupon_code.'/cmobile-'.$list->customer_mobile.'/tid-'.$list->coupon_transaction_id)}}" class="text_none btn btn-info btn-xs tooltips">Buy Coupon</a>
												@elseif($list->coupon_status=='2')
													<button class="btn btn-success btn-xs">Success</button>
												@endif
											@elseif($list->coupon_status=='2')
												<button class="btn btn-success btn-xs">Success</button>
											@else
												<button class="btn btn-success btn-xs">Expired</button>
											@endif

											</td>
											<td>{{number_format($list->coupon_shopping_amount,2)}}</td>
											<td>{{number_format($list->coupon_discount_amount,2)}}</td>
											<td>{{number_format($list->coupon_buy_price,2)}}</td>

										</tr>
										@endforeach
										@else
										<tr>
											<td colspan="9">
												<div class="alert alert-info">
													<span class="text-center">You Don't Buy Coupon Yet !</span>
												</div>
											</td>
										</tr>
										@endif
									</tbody>

								</table>
								{{isset($client_shopping_pagination) ? $client_shopping_pagination:""}}
							</div>

							<div role="tabpanel" class="tab-pane" id="wish_list"  style="width: 100%; height: 500px; overflow: auto; padding: 5px">
								<table class="table table-hover table-bordered table-striped text-center">
									<thead>
										<tr>
											<th>SL</th>
											<th>Merchant</th>
											<th>Branch</th>
											<th>Branch City</th>
											<th>Coupon Code</th>
											<th>Branch Mobile</th>
											<th>Coupon Status</th>
											<th>Delete</th>
										</tr>
									</thead>

									<tbody>
										@if(!empty($client_wish_list_info) && count($client_wish_list_info)>0)
										@foreach($client_wish_list_info as $key => $list)
										<tr  id="wish_delete_{{$list->activity_id}}">
											<td>{{$key+1}}</td>
											<td>{{$list->merchant_name}}</td>
											<td>{{$list->branch_name}}</td>
											<td>{{$list->branch_city}}</td>
											<td>{{$list->coupon_code}}</td>
											<td>{{$list->branch_mobile}}</td>

											<td>
												@if(($list->coupon_closing_date) >= (date('Y-m-d')))
													<a href="{{url('/single-page/coupon_id-'.$list->coupon_code)}}" class="btn btn-success">Details</a>
												@else
													<span class="btn btn-warning">Expired</span>
												@endif
											</td>
											<td>
												<a class="btn btn-warning btn-xs wish_list_delete" data-id="{{$list->activity_id}}">Remove</a>

											</td>
										</tr>
										@endforeach
										@else
										<tr>
											<td class="text-center" colspan="7">
												You don't have  wish list yet !
											</td>
										</tr>
										@endif
									</tbody>

								</table>
								{{isset($order_list_pagination) ? $order_list_pagination:""}}
							</div>

							<div role="tabpanel" class="tab-pane" id="follow_list"   style="width: 100%; height: 500px; overflow: auto; padding: 5px">
								<table class="table table-striped table-hover table-bordered  text-center">
									<thead class="thead-default">
										<tr>
											<th class="text-center">SL</th>
											<th class="text-center">Merchant Name</th>
											<th class="text-center">View</th>
											<th class="text-center">Status</th>
										</tr>
									</thead>

									<tbody>
										@if(!empty($client_follow_info) && count($client_follow_info)>0)
										@foreach($client_follow_info as $key => $value)
										<tr id="unfollow_{{$value->merchant_id}}">
											<td>{{$key+1}}</td>
											<td><a href="{{url('/merchant/branch-view/page/mid-'.$value->merchant_id)}}">{{$value->merchant_name}}</a></td>
											<td>{{$value->merchant_website_url}}</td>

											<td>
												@if($value->activity_list_status=='1')
												<a class="follow_list btn btn-warning btn-xs" data-fid="{{$value->merchant_id}}" data-uid="{{$value->activity_user_id}}" data-status="-1">Follow</a>
												@endif
											</td>
										</tr>
										@endforeach
										@else
										<tr>
											<tr>
											<td class="text-center" colspan="4">
												You bon't have follow list yet !
											</td>
										</tr>
										</tr>
										@endif
									</tbody>

								</table>
								{{isset($order_list_pagination) ? $order_list_pagination:""}}
							</div>


						</div>

					</div>
				</div>
			</div>
			
		</div>

		
        <div id="printOnly">
        
				<div class="row">
					<div class="col-md-12">
						<div class="panel panel-default">
							<div class="panel-heading">
								<ul class="nav nav-tabs">
									<li class="{{isset($tab4) && ($tab4=='mobile_coupon_list') ? 'active' : ''}}"><a class="btn btn-info btn-squared" href="#mobile_coupon_list" aria-controls="Coupon" role="tab" data-toggle="tab">Coupon</a></li>
									<li class="{{isset($tab4) && ($tab4=='mobile_wish_list') ? 'active' : ''}}"><a class="btn btn-info btn-squared" href="#mobile_wish_list" aria-controls="Wish List" role="tab" data-toggle="tab">Wish List</a></li>
									<li class="{{isset($tab4) && ($tab4=='mobile_follow_list') ? 'active' : ''}}"><a class="btn btn-info btn-squared" href="#mobile_follow_list" aria-controls="Follow List" role="tab" data-toggle="tab">Follow </a></li>
								</ul>
							</div>
							<div class="panel-body">
								<!-- Tab panes -->
								<div class="tab-content">
									<div role="tabpanel" class="tab-pane active" id="mobile_coupon_list" style="width: 100%; overflow: auto; padding: 5px">
										<table class="table table-hover table-bordered table-striped text-center">
											<thead>
												<tr>
													<th>SL</th>
													<th>Merchant</th>
													<th>Status</th>
													<th>Branch</th>
													<th class="text-center">Closing Time</th>
													<th>Shopping Amount(Tk)</th>
													<th>Discount Amount(Tk)</th>
													<th>Coupon Price(Tk)</th>

												</tr>
											</thead>

											<tbody>
												@if(!empty($client_shopping_info) && count($client_shopping_info)>0)
												@foreach($client_shopping_info as $key => $list)
												<tr>
													<td>{{$key+1}}</td>
													<td>{{$list->merchant_name}}</td>
													<td>
				                        				@if($list->coupon_closing_date >=  date('Y-m-d').' 23:59:59')

															@if($list->coupon_status=='-1')


																<a href="{{url('/coupon/amount/otp/ccode-'.$list->coupon_code.'/cmobile-'.$list->customer_mobile.'/tid-'.$list->coupon_transaction_id)}}" class="text_none btn btn-warning btn-xs tooltips">Active Deal</a>
																
															@elseif($list->coupon_status=='1')


																<a href="{{url('/coupon/amount/otp/ccode-'.$list->coupon_code.'/cmobile-'.$list->customer_mobile.'/tid-'.$list->coupon_transaction_id)}}" class="text_none btn btn-info btn-xs tooltips">Buy Coupon</a>
															@elseif($list->coupon_status=='2')
																<button class="btn btn-success btn-xs">Success</button>
															@endif
														@elseif($list->coupon_status=='2')
															<button class="btn btn-success btn-xs">Success</button>
														@else
															<button class="btn btn-success btn-xs">Expired</button>
														@endif

													</td>
													<td>{{$list->branch_name}}</td>
													<td>
			                        					@if($list->coupon_closing_date >=  date('Y-m-d').' 23:59:59')
															<a href="{{url('/single-page/coupon_id-'.$list->coupon_id)}}" class="">
																<span class="t-uppercase btn btn-success" data-countdown="{{$list->coupon_closing_date}} 23:59:59"></span>
															</a>
														@else
															<span class="t-uppercase btn btn-success" data-countdown="{{$list->coupon_closing_date}} 23:59:59"></span>

														@endif
													</td>
													<td>{{number_format($list->coupon_shopping_amount,2)}}</td>
													<td>{{number_format($list->coupon_discount_amount,2)}}</td>
													<td>{{number_format($list->coupon_buy_price,2)}}</td>

												</tr>
												@endforeach
												@else
												<tr>
													<td colspan="9">
														<div class="alert alert-info">
															<span class="text-center">You Don't Buy Coupon Yet !</span>
														</div>
													</td>
												</tr>
												@endif
											</tbody>

										</table>
										{{isset($client_shopping_pagination) ? $client_shopping_pagination:""}}
									</div>

									<div role="tabpanel" class="tab-pane" id="mobile_wish_list" style="width: 100%; overflow: auto; padding: 5px">
										<table class="table table-hover table-bordered table-striped text-center">
											<thead>
												<tr>
													<th>SL</th>
													<th>Merchant</th>
													<th>Branch</th>
													<th>Branch City</th>
													<th>Coupon Code</th>
													<th>Branch Mobile</th>
													<th>Coupon Status</th>
													<th>Delete</th>
												</tr>
											</thead>

											<tbody>
												@if(!empty($client_wish_list_info) && count($client_wish_list_info)>0)
												@foreach($client_wish_list_info as $key => $list)
												<tr  id="wish_delete_{{$list->activity_id}}">
													<td>{{$key+1}}</td>
													<td>{{$list->merchant_name}}</td>
													<td>{{$list->branch_name}}</td>
													<td>{{$list->branch_city}}</td>
													<td>{{$list->coupon_code}}</td>
													<td>{{$list->branch_mobile}}</td>

													<td>
														@if(($list->coupon_closing_date) >= (date('Y-m-d')))
															<a href="{{url('/single-page/coupon_id-'.$list->coupon_code)}}" class="btn btn-success">Details</a>
														@else
															<span class="btn btn-warning">Expired</span>
														@endif
													</td>
													<td>
														<a class="btn btn-warning btn-xs wish_list_delete" data-id="{{$list->activity_id}}">Remove</a>

													</td>
												</tr>
												@endforeach
												@else
												<tr>
													<td class="text-center" colspan="7">
														You don't have  wish list yet !
													</td>
												</tr>
												@endif
											</tbody>

										</table>
										{{isset($order_list_pagination) ? $order_list_pagination:""}}
									</div>

									<div role="tabpanel" class="tab-pane" id="mobile_follow_list"   style="width: 100%; overflow: auto; padding: 5px">
										<table class="table table-striped table-hover table-bordered  text-center">
											<thead class="thead-default">
												<tr>
													<th class="text-center">SL</th>
													<th class="text-center">Merchant Name</th>
													<th class="text-center">View</th>
													<th class="text-center">Status</th>
												</tr>
											</thead>

											<tbody>
												@if(!empty($client_follow_info) && count($client_follow_info)>0)
												@foreach($client_follow_info as $key => $value)
												<tr id="unfollow_{{$value->merchant_id}}">
													<td>{{$key+1}}</td>
													<td><a href="{{url('/merchant/branch-view/page/mid-'.$value->merchant_id)}}">{{$value->merchant_name}}</a></td>
													<td>{{$value->merchant_website_url}}</td>

													<td>
														@if($value->activity_list_status=='1')
														<a class="follow_list btn btn-warning btn-xs" data-fid="{{$value->merchant_id}}" data-uid="{{$value->activity_user_id}}" data-status="-1">Follow</a>
														@endif
													</td>
												</tr>
												@endforeach
												@else
												<tr>
													<tr>
													<td class="text-center" colspan="4">
														You don't have follow list yet !
													</td>
												</tr>
												</tr>
												@endif
											</tbody>

										</table>
										{{isset($order_list_pagination) ? $order_list_pagination:""}}
									</div>


								</div>

							</div>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-sm-12">
						<div class="tabbable">
							<div class="panel panel-default">
								<div class="panel-heading" style="font-size:11px;">
									<ul class="nav nav-tabs tab-padding tab-space-3 tab-blue" id="myTab4">
										<li class="{{isset($tab3) && ($tab3=='mobile_panel_overview') ? 'active' : ''}}">
											<a href="#mobile_panel_overview" role="tab" id="mobile_panel_overview-tab" data-toggle="tab" aria-controls="panel_overview" class="btn-info">
												Overview
											</a>
										</li>

										<li class="{{isset($tab3) && ($tab3=='mobile_panel_edit_account') ? 'active' : ''}}">
											<a href="#mobile_panel_edit_account" role="tab" id="mobile_panel_edit_account-tab" data-toggle="tab" aria-controls="panel_edit_account" class="btn-info">
												Update
											</a>
										</li>

										<li class="{{isset($tab3) && ($tab3=='mobile_change_password') ? 'active' : ''}}">
											<a href="#mobile_change_password" role="tab" id="mobile_change_password-tab" data-toggle="tab" aria-controls="change_password" class="btn-info">
												Change Password
											</a>
										</li>
									</ul>
								</div>

								<div class="tab-content">
									<div id="mobile_panel_overview" class="tab-pane in {{isset($tab3) && ($tab3=='mobile_panel_overview') ? 'in active' : ''}}" aria-labelledby="panel_overview-tab">

										<div class="form-group" align="center">
											<br><h4 align="center">
											{{isset($client_info->name) ? $client_info->name : ''}}</h4>
											<table class="table table-condensed table-hover">
												@if(!empty($client_info->user_profile_image))
												<img src="{{asset('assets/images/userprofile/'.$client_info->user_profile_image)}}" alt="User Profile Photo" class="thumbnail">
												@else
												<img src="{{asset('assets/images/profile.jpg')}}" height="150px" width="150px" alt="User Profile Photo" class="thumbnail">
												@endif
											</table>
										</div>


										<table class="table table-condensed table-hover">
											<thead>
												<tr>
													<th colspan="3">Contact Information</th>
												</tr>
											</thead>
											<tbody>

												<tr>
													<td>Name :</td>
													<td>
													<a href="">
														{{isset($client_info->name) ? strtolower($client_info->name) : ''}}
													</a></td>
												</tr>

												<tr>
												<td>Email :</td>
												<td>
													<a href="">
														{{isset($client_info->email) ? $client_info->email : ''}}
													</a></td>
												</tr>
												<tr>
													<td>Mobile :</td>
													<td>{{isset($client_info->mobile) ? $client_info->	mobile : ''}}</td>
												</tr>

											</tbody>
										</table>
										<table class="table table-condensed table-hover">
											<thead>
												<tr>
													<th colspan="3">General information</th>
												</tr>
											</thead>
											<tbody>
												<tr>
													<td>Position :</td>
													<td>{{isset($client_info->user_type) ? ucfirst($client_info->user_type) : ''}}</td>
													<td><a href="" class="show-tab"></a></td>
												</tr>

											</tbody>
										</table>

									</div>

									<div id="mobile_panel_edit_account" class="tab-pane in {{isset($tab3) && ($tab3=='mobile_panel_edit_account') ? 'in active' : ''}}" aria-labelledby="mobile_panel_edit_account-tab" style="margin:5px;">
										<form action="{{url('/client/profile/update/id-'.$client_info->id)}}" method="post" enctype="multipart/form-data" role="form" id="form">

											<div class="row">
												<div class="col-md-12">
													<h2 align="center">Account Info</h2>
													<hr>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<label class="control-label">
															Name
														</label>
														<input type="text" placeholder="Name" class="form-control" id="name" name="name" value="{{isset($client_info->name) ? $client_info->name : ''}}">
													</div>

													<div class="form-group">
														<label class="control-label">
															Email Address
														</label>
														<input type="email" placeholder="email@example.com" class="form-control" id="email" name="email" value="{{isset($client_info->email) ? $client_info->email : ''}}">
													</div>
													<div class="form-group">
														<label class="control-label">
															Mobile
														</label>
														<input type="text" placeholder="User Mobile" class="form-control" id="phone" name="mobile" value="{{isset($client_info->mobile) ? $client_info->mobile : ''}}">
													</div>


												</div>

												<div class="col-md-6">
													@if(!empty($client_info->user_profile_image))
													<img src="{{asset('assets/images/userprofile/'.$client_info->user_profile_image)}}" alt="User Profile Photo" class="thumbnail">
													@else
													<img src="{{asset('assets/images/profile.jpg')}}" height="150px" width="150px" alt="User Profile Photo" class="thumbnail">
													@endif

													<input type="file" name="image_url" />

												</div>
											</div>

											<hr>

											<div class="row">
												<div class="col-md-8">

												</div>
												<div class="col-md-4">
													<input type="hidden" name="_token" value="{{csrf_token()}}">
													<button class="btn btn-success btn-block" type="submit">
														Update <i class="fa fa-arrow-circle-right"></i>
													</button>
												</div>
											</div>
										</form>
									</div>



									<div id="mobile_change_password" class="tab-pane in {{isset($tab3) && ($tab3=='mobile_change_password') ? 'in active' : ''}}" aria-labelledby="mobile_change_password-tab">
										<div class="row">
											<div class="col-md-12 change_password"  style="margin:10px;">
												<div class="col-md-5">
													@if(!empty($client_info->user_profile_image))
													<img alt="profile photo" src="{{asset('assets/images/userprofile/'.$client_info->user_profile_image)}}" class="thumbnail profile_img_size"/>
													@else
													<img src="{{asset('assets/images/profile.jpg')}}" height="150px" width="150px" alt="User Profile Photo" class="thumbnail">
													@endif
													<h3>{{isset($client_info->name) ? $client_info->name : ''}}</h3>
												</div>
												<div class="col-md-7">
													<form action="{{url('/client/change-password/id-'.$client_info->id)}}" method="post" enctype="multipart/form-data">
														<input type="hidden" name="_token" value="{{csrf_token()}}" >
														<div class="input-group" style="margin-top:7px">
															<span><i>New Password</i></span>
															<input type="password" name="new_password" placeholder="New Password" class="form-control" value="">
														</div>


														<div class="input-group" style="margin-top:7px">
															<span><i>Confirm Password</i></span>
															<input type="password" name="confirm_password" placeholder="Confirm Password" class="form-control" value="">
														</div>
														<!-- <br> -->
														<div class="input-group" style="margin-top:7px">
															<!-- <span><i>Current Password</i></span> -->
															<input type="password" name="current_password" placeholder="Current Password" class="form-control" value="">
															<span class="input-group-btn">
																<button class="btn btn-blue" type="submit">
																	<i class="fa fa-chevron-right"></i>
																</button>
															</span>
														</div>

													</form>
												</div>
											</div>
										</div>
									</div>
								</div>

							</div>
						</div>
					</div>
					<div  class="col-sm-12">
						<div class="panel panel-default">
							<div class="panel-body">
								
								<table class="table table-condensed table-hover">
									<thead>
										<tr>
											<th class="text-center"><strong> Point Info </strong></th>
										</tr>
									</thead>
									<tbody>
										<td class="text-center"><strong>Total Point :
										@if(!empty($client_meta_info))
										{{(($client_meta_info->user_meta_field_value)?($client_meta_info->user_meta_field_value) :0)}}
										@else
											<span>0</span>
										@endif
										</strong></td>
									</tbody>
								</table>
							</div>
						</div>
					</div>
					<br>
				</div>


		</div>


	</div>

</div>
<!-- </div> -->

<!-- Modal -->
<div id="BuyCouponModal" class="modal fade " rtabindex="-1" role="dialog">
	<div class="modal-dialog ">
		<div class="modal-content">
			
			<div class="modal-body">
				<button type="button" class="close" data-dismiss="modal">&times;</button>

					<div class="shopping_amount_show">

					</div>
			</div>
		</div>
	</div>
</div>

</main>


@stop

