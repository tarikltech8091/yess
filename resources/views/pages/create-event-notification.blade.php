@extends('layout.master')
@section('content')

    <main id="mainContent" class="main-content">
        <!-- Page Container -->
        <div class="page-container ptb-60">
            <div class="container">

                <!-- Contact Us Area -->
                <div class="contact-area contact-area-v1 panel">
                 <!--error message*******************************************-->
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

                    <div class="ptb-30 prl-30">
                        <div class="row row-tb-20">
                            <div class="col-xs-12 col-md-6">
                                <div class="contact-area-col contact-form">
                                    <h3 class="t-uppercase h-title mb-20">Create Event</h3>
                                    <form action="{{url('/event/notification/push/confirm')}}" method="post">
                                        <input type="hidden" name="_token" value="{{csrf_token()}}">

										<div class="form-group">
											<label> Event Type <span class="symbol required"></span></label>
											<select class="form-control" name="event_type">
												<option value="">Select Type <span class="symbol required"></span></option>
												<option value="birtday">Birtday<span class="symbol required"></span></option>
												<option value="anniversary">Anniversary <span class="symbol required"></span></option>
												<option value="wedding"> Wedding <span class="symbol required"></span></option>
												<option value="others">Others<span class="symbol required"></span></option>
											</select>
										</div>

										<div class="form-group">
											<label> Event Date <span class="symbol required"></span></label>
											<div class="input-group">
								                <input type="text" data-date-format="yyyy-mm-dd" data-date-viewmode="years" class="form-control date-picker" name="event_date" placeholder="">
								                <span class="input-group-addon"> <i class="fa fa-calendar"></i> </span>
								            </div>
										</div>

                                        <div class="form-group">
											<label> Coupon 	Merchant <span class="symbol required"></span></label>
											<select class="form-control select_merchant_list" name="coupon_merchant_id">
												<option value="">Select Merchant</option>
												@if(!empty($merchant_info))
													@foreach($merchant_info As $key =>$list)
													<option value="{{$list->merchant_id}}">{{$list->merchant_name}}</option>
													@endforeach
												@endif
											</select>
										</div>

										<div class="form-group ">
											<label>  Title <span class="symbol required"></span></label>
											<input type="text" class="form-control" name="title">
										</div>

                                        <div class="form-group">
                                            <label>Message</label>
                                            <textarea rows="5" name="message" class="form-control" required="required"></textarea>
                                        </div>

                                        <button class="btn">Send</button>
                                    </form>
                                </div>
                            </div>
                            
                            <div class="col-xs-12 col-md-6">
                                <div class="contact-area-col contact-info">
                                    <div class="contact-info">
                                        <h3 class="t-uppercase h-title mb-20">All Event</h3>
                                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Veniam officia accusamus qui est. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Veniam officia accusamus qui est.</p>
                                        <ul class="contact-list mb-40">
                                            <li>
                                                <span class="icon lnr lnr-map-marker"></span>
                                                <h5>Address</h5>
                                                <p class="color-mid"></p>
                                            </li>
                                            <li>
                                                <span class="icon lnr lnr-envelope"></span>
                                                <h5>Email</h5>
                                                <p class="color-mid"></p>
                                            </li>
                                            <li>
                                                <span class="icon lnr lnr-phone-handset"></span>
                                                <h5>Our phone</h5>
                                                <p class="color-mid"></p>
                                            </li>
                                        </ul>
                                        <ul class="social-icons social-icons--colored list-inline">
                                            <li class="social-icons__item">
                                                <a href="#"><i class="fa fa-facebook"></i></a>
                                            </li>
                                            <li class="social-icons__item">
                                                <a href="#"><i class="fa fa-twitter"></i></a>
                                            </li>
                                            <li class="social-icons__item">
                                                <a href="#"><i class="fa fa-linkedin"></i></a>
                                            </li>
                                            <li class="social-icons__item">
                                                <a href="#"><i class="fa fa-google-plus"></i></a>
                                            </li>
                                            <li class="social-icons__item">
                                                <a href="#"><i class="fa fa-pinterest"></i></a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <!-- End Contact Us Area -->

            </div>
        </div>
        <!-- End Page Container -->


    </main>

@stop
