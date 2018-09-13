    <div id="printMainSlider">
        
        <section class="footer-top-area pt-70 pb-30 pos-r bg-blue">
            <div class="container">
                <div class="row row-tb-20">

                    <div class="col-sm-12 col-md-3">
                        <div class="row row-tb-20">
                            <div class="footer-col col-sm-12">
                                <div class="footer-about">
                                <?php $company_info=\DB::table('company_details')->latest()->first(); ?>
                                    @if(!empty($company_info->company_logo))
                                    <img class="mb-40" src="{{asset('assets/images/company/'.$company_info->company_logo)}}" alt="User Profile Photo">
                                    @else
                                    <img class="mb-40" src="{{asset('assets/images/profile.png')}}" alt="">
                                    @endif
                                    <p class="color-light">{{$company_info->company_address?$company_info->company_address:''}}</p>
                                </div>
                            </div>
                        </div>
                    </div>

<!--                <div class="col-sm-12 col-md-3">
                        <div class="footer-col col-sm-12">
                            <div class="footer-top-instagram instagram-widget">
                                <h2>Instagram Widget</h2>
                                <div class="row row-tb-5 row-rl-5">


                                    <div class="instagram-widget__item col-xs-4">
                                        <img src="{{asset('main-assets/images/instagram/instagram_01.jpg')}}" alt="">
                                    </div>


                                    <div class="instagram-widget__item col-xs-4">
                                        <img src="{{asset('main-assets/images/instagram/instagram_02.jpg')}}" alt="">
                                    </div>


                                    <div class="instagram-widget__item col-xs-4">
                                        <img src="{{asset('main-assets/images/instagram/instagram_03.jpg')}}" alt="">
                                    </div>


                                    <div class="instagram-widget__item col-xs-4">
                                        <img src="{{asset('main-assets/images/instagram/instagram_04.jpg')}}" alt="">
                                    </div>


                                    <div class="instagram-widget__item col-xs-4">
                                        <img src="{{asset('main-assets/images/instagram/instagram_05.jpg')}}" alt="">
                                    </div>


                                    <div class="instagram-widget__item col-xs-4">
                                        <img src="{{asset('main-assets/images/instagram/instagram_06.jpg')}}" alt="">
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div> -->
                    <div class="col-sm-12 col-md-1">
                    </div>

                    <div class="col-md-2">
                        <div class="row row-tb-20">
                            <div class="footer-col col-sm-12">
                                <div class="footer-links">
                                    <h2 class="color-lighter">Social Site</h2>
                                    <ul>
                                    <?php 
                                        $social_site_info=\DB::table('tbl_setting_meta')->where('setting_meta_field_name','social_site')->get();
                                        if(!empty($social_site_info) && count($social_site_info)>0){
                                        foreach ($social_site_info as $key => $list) {
                                            $social_site_value=unserialize($list->setting_meta_field_value);
                                    ?>
                                        <li><a href="{{url($social_site_value[1])}}">{{$social_site_value[0]}}</a>
                                        </li>
                                    <?php                                                 
                                        }
                                        }
                                    ?>
                                    </ul>
                                </div>
                            </div>

                        </div>
                    </div>


                    <div class="col-sm-12 col-md-1">
                    </div>

                    <div class="col-md-2">
                        <div class="row row-tb-20">
                            <div class="footer-col col-sm-12">
                                <div class="footer-links">
                                    <h2 class="color-lighter">Quick Links</h2>
                                    <ul>
                                        <li><a href="{{url('/latest/coupon/page')}}">Latest Coupon</a>
                                        </li>
                                        <li><a href="{{url('/newest/coupon/page')}}">Newest Coupons</a>
                                        </li>
                                        <li><a href="{{url('/contact/page')}}">Contact Us</a>
                                        </li>
                                        <li><a href="{{url('/faq/page')}}">FAQs</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="col-sm-12 col-md-1">
                    </div>

                    <div class="col-md-2">
                        <div class="row row-tb-20">
                            <div class="footer-col col-sm-12">
                                <div class="footer-links">
                                    <h2 class="color-lighter">Category</h2>
                                    <ul>
                                    <?php 
                                        $sub_category_info=\DB::table('tbl_sub_category')->get();
                                        if(!empty($sub_category_info) && count($sub_category_info)>0){
                                        foreach ($sub_category_info as $key => $list) {
                                    ?>
                                        <li><a href="{{url('all-merchant/coupon/page/cid-/'.$list->category_id.'/subcid-'.$list->sub_category_id)}}">{{$list->sub_category_name}}</a>
                                        </li>
                                    <?php                                                 
                                        }
                                        }
                                    ?>
                                    </ul>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </section>
        <!-- –––––––––––––––[ FOOTER ]––––––––––––––– -->

        <footer id="mainFooter" class="main-footer">
            <div class="container">
                <div class="row">
                    <p>Copyright &copy; 2017 
                    Designed and Developed by<a href="{{url('http://live-technologies.net/')}}"> Live Technologies LTD.</a></p>
                </div>
            </div>
        </footer>
        <!-- –––––––––––––––[ END FOOTER ]––––––––––––––– -->
    </div>

    <div id="printOnly">
        <section class="footer-top-area pos-r bg-blue">
            <div class="container">
                <div class="row" align="center">
                    <table>
                        <tr>
                            <td><a href="{{url('/')}}">Home</a> | </td>
                            <td><a href="{{url('/faq/page')}}">FAQs</a> | </td>
                            <td><a href="{{url('/contact/page')}}">Contact Us</a></td>
                        </tr>
                    </table>
                </div>
            </div>
        </section>
        <!-- –––––––––––––––[ FOOTER ]––––––––––––––– -->

        <footer id="mainFooter" class="main-footer">
            <div class="container">
                <div class="row">
                    <p  style="font-size:10px;">Copyright &copy; 2017 
                    Designed and Developed by<a href="{{url('http://live-technologies.net/')}}"> Live Technologies LTD.</a></p>
                </div>
            </div>
        </footer>
        <!-- –––––––––––––––[ END FOOTER ]––––––––––––––– -->
    </div>


