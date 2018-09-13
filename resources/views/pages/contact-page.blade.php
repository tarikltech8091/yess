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
                    <div class="row">

                        <div class="col-md-12 t-center">
                            <div id="map" style="height: 300px; width: 100%;">
                            </div>
                        </div>
                    </div>
                    <div class="ptb-30 prl-30">
                        <div class="row row-tb-20">
                            <div class="col-xs-12 col-md-6">
                                <div class="contact-area-col contact-info">
                                    <div class="contact-info">
                                        <h3 class="t-uppercase h-title mb-20">Contact informations</h3>
                                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Veniam officia accusamus qui est. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Veniam officia accusamus qui est.</p>
                                        <ul class="contact-list mb-40">
                                            <li>
                                                <span class="icon lnr lnr-map-marker"></span>
                                                <h5>Address</h5>
                                                <p class="color-mid">{{($company_info->company_address)?$company_info->company_address:''}}</p>
                                            </li>
                                            <li>
                                                <span class="icon lnr lnr-envelope"></span>
                                                <h5>Email</h5>
                                                <p class="color-mid">{{($company_info->company_email)?$company_info->company_email:''}}</p>
                                            </li>
                                            <li>
                                                <span class="icon lnr lnr-phone-handset"></span>
                                                <h5>Our phone</h5>
                                                <p class="color-mid">{{($company_info->company_contact)?$company_info->company_contact:''}}</p>
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
                            <div class="col-xs-12 col-md-6">
                                <div class="contact-area-col contact-form">
                                    <h3 class="t-uppercase h-title mb-20">Get in touch</h3>
                                    <form action="{{url('/contact/confirm')}}" method="post">
                                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                                        <input type="hidden" name="user_type" value="all">
                                        <div class="form-group">
                                            <label>Name</label>
                                            <input type="text" name="user_name" class="form-control" required="required">
                                        </div>
                                        <div class="form-group">
                                            <label>Mobile</label>
                                            <input type="text" name="client_mobile" class="form-control" required="required">
                                        </div>
                                        <div class="form-group">
                                            <label>Email Address</label>
                                            <input type="text" name="email_address" class="form-control" required="required">
                                        </div>
                                        <div class="form-group">
                                            <label>Message</label>
                                            <textarea rows="5" name="message" class="form-control" required="required"></textarea>
                                        </div>
                                        <button class="btn">Send Message</button>
                                    </form>
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

    <script>
        var map;
        function initMap() {
            map = new google.maps.Map(document.getElementById('map'), {
              zoom: 10,
              center: new google.maps.LatLng(23.7806286, 90.2793683),
              mapTypeId: 'roadmap'
            });

            var infowindow = new google.maps.InfoWindow();
            var features = <?php print_r(json_encode($locations)) ?>;

            // Create markers.
            features.forEach(function(feature) {
                var marker = new google.maps.Marker({
                    position: new google.maps.LatLng(feature.company_location_lat,feature.company_location_lng),
                    title: feature.company_name,
                    map: map
                });
                google.maps.event.addListener(marker, 'click', function() {
                    infowindow.setContent('<div><strong>'+feature.company_name+'<br>Company Address : '+feature.company_address+
                    '</div>');
                  infowindow.open(map, this);
                });
            });
        }
    </script>
    <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBeCI4td4NDWEoCn8cySpkFw7bf62CMETs&callback=initMap">
    </script>
@stop
