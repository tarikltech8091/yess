        <header id="mainHeader" class="main-header">

            <!-- Header Header -->
            <div class="header-header bg-white">
                <div class="container">
                
                    <div class="row mobile_app_ref mobile_app_shade" id="printOnly">

                        <?php
                            $agent_info= $_SERVER['HTTP_USER_AGENT'];

                            $pos = strpos($agent_info, "Android");
                            if ($pos == true) {
                                $platform ='Android';
                            }
                            $pos = strpos($agent_info, "iPhone");
                            if ($pos == true) {
                                $platform ='iPhone';
                            }
                            $pos = strpos($agent_info, "Windows");
                            if ($pos == true) {
                                $platform ='Windows';
                            }
                        ?>

                        <div class="col-md-6 pull-left ">
                            <img src="{{asset('assets/images/ficon.png')}}" alt="Icon">
                            Yess Mobile App Free</span></td>
                        </div>


                        @if (isset($platform) && $platform == 'Android')
                            <div class="col-md-6 pull-right">
                                <a style="text-decoration:none;" href="{{url('https://play.google.com/store/apps/details?id=com.live.shohel.yess')}}">
                                    <img src="{{asset('assets/images/play_store.png')}}" alt="Icon">
                                </a>
                            </div>
                        @elseif (isset($platform) && $platform == 'iPhone')
                            <div class="col-md-6 pull-right">
                                <a style="text-decoration:none;" href="{{url('https://itunes.apple.com/us/app/yess/id1256304316?ls=1&mt=8')}}">
                                    <img src="{{asset('assets/images/app_store.png')}}" border="0" alt="Icon">
                                </a>
                            </div>
                        @else
                            <div class="col-md-6 pull-right">
                                <a style="text-decoration:none;" href="{{url('https://play.google.com/store/apps/details?id=com.live.shohel.yess')}}">
                                    <img src="{{asset('assets/images/play_store.png')}}" alt="Icon">
                                </a>
                            </div>
                        @endif

                    </div>

                    <div class="row row-rl-0 row-tb-20 row-md-cell">
                        <div id="printOnly">

                            <script>
                                function openNav() {
                                    document.getElementById("mySidenav").style.width = "250px";
                                }

                                function closeNav() {
                                    document.getElementById("mySidenav").style.width = "0";
                                }
                            </script>


                            <table>
                                <tr style="width:100%">
                                    <td  style="width:5%">
                                        <div id="mySidenav" class="sidenav">
                                          <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a><br><br>
                                                            
                                            <?php 
						$sidebar_icon=array('fa fa-cutlery','fa fa-desktop','fa fa-child','fa fa-bars','fa fa-eye','fa fa-list','fa fa-child','fa fa-female','fa fa-desktop','fa fa-bars','fa fa-eye','fa fa-female','fa fa-list');
                                                $all_sub_category=\DB::table('tbl_sub_category')->get();
                                                if(!empty($all_sub_category)){
                                                foreach ($all_sub_category as $key => $sub_category) {
                                                
                                            ?>
						<div class="side_content">
                                                    <a href="{{url('all-merchant/coupon/page/cid-/'.$sub_category->sub_category_id.'/subcid-'.$sub_category->sub_category_id)}}"><i class="{{$sidebar_icon[$key]}}"></i> {{$sub_category->sub_category_name}}</a>
						</div>
                                            <?php
                                                }
                                                }
                                            ?>
                                        </div>
                                        <span style="font-size:30px;cursor:pointer" onclick="openNav()">&#9776;</span>
                                    </td>


                                    <td style="width:50%">
                                        <a href="{{url('/')}}" class="logo main_logo screen_logo">
                                            <?php $company_info=\DB::table('company_details')->latest()->first(); ?>
                                            @if(!empty($company_info->company_logo))
                                            <img src="{{asset('assets/images/company/main-icon/'.$company_info->company_logo)}}" alt="Company Logo" width="250">
                                            @else
                                            <img src="{{asset('assets/images/profile.png')}}" alt="" width="250">
                                            @endif
                                        </a>
                                    </td>


                                     @if(\Auth::check())
                                            <?php
                                            $first_name=explode(' ', \Auth::user()->name);
                                            ?>
                                    <td style="width:15%">

                                        <div class="header-cart user_all_activity">
                                            <a href="{{url('/client/profile/page/id-'.\Auth::user()->id)}}">
                                                <span class="top_title header-icon fa fa-user"></span>
                                                <span class="title top_title_text">{{$first_name[0]}}</span>
                                            </a>
                                        </div>
                                    </td>
                                    <td  style="width:15%">

                                        <div class="header-wishlist ml-20 user_all_activity">
                                            <a href="{{url('/client/logout/'.\Auth::user()->mobile)}}">
                                            <span class="top_title header-icon fa fa-sign-out"></span>
                                            <span class="title top_title_text">Log Out</span>
                                            </a>
                                        </div>
                                    </td>
                                    @else
                                    <td  style="width:15%">

                                        <div class="header-cart user_all_activity">
                                            <a href="{{url('/sign-in/page')}}" class="all_title">
                                                <span class="top_title header-icon fa fa-lock"></span>
                                                <span class="title top_title_text ">Log In</span>
                                            </a>
                                        </div>
                                    </td>
                                    <td style="width:15%">
                                        <div class="header-wishlist ml-20 user_all_activity">
                                            <a href="{{url('/sign-in/page')}}">
                                                <span class="top_title header-icon fa fa-user"></span>
                                                <span class="title top_title_text">Sign Up</span>
                                            </a>
                                        </div>
                                    </td>
                                    @endif
                                </tr>

                            </table>


                            <table style="width:100%; margin-top:15px;">
                                <tr>
                                    <td>
                                        <form action="{{url('/search/coupon/')}}" class="search-form" method="post"> 
                                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                                        <div class="input-group">
                                            <input type="text" name="search_text" class="form-control input-lg search-input" placeholder="Enter Keyword Here ..." required="required">
                                            <div class="input-group-btn">
                                                <div class="input-group">
                                                    <div class="input-group-btn">
                                                        <button type="submit" class="btn btn-lg btn-search btn-block">
                                                            <i class="fa fa-search font-16"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        </form>
                                    </td>
                                </tr>
                            </table>


                        </div>

                        <div id="printMainSlider">

                            <div class="brand col-md-3 t-xs-center t-md-left valign-middle">
                                <a href="{{url('/')}}" class="logo">
                                        <?php $company_info=\DB::table('company_details')->latest()->first(); ?>
                                        @if(!empty($company_info->company_logo))
                                        <img class="mb-40" src="{{asset('assets/images/company/main-icon/'.$company_info->company_logo)}}" alt="Company Logo" width="250">
                                        @else
                                        <img class="mb-40" src="{{asset('assets/images/profile.png')}}" alt="" width="250">
                                        @endif
                                </a>
                            </div>
                            <div class="header-search col-md-9">
                                <div class="row row-tb-10 ">
                                    <div class="col-md-8 search-bar">
                                        <form action="{{url('/search/coupon/')}}" class="search-form" method="post"> 
                                            <input type="hidden" name="_token" value="{{csrf_token()}}">
                                            <div class="input-group">
                                                <input type="text" name="search_text" class="form-control input-lg search-input" placeholder="Enter Keyword Here ..." required="required">
                                                <div class="input-group-btn">
                                                    <div class="input-group">
                                                        <div class="input-group-btn">
                                                            <button type="submit" class="btn btn-lg btn-search btn-block">
                                                                <i class="fa fa-search font-16"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="col-md-4 search-bar" align="right">


                                        @if(\Auth::check())
                                                <?php
                                                $first_name=explode(' ', \Auth::user()->name);
                                                ?>

                                            <div class="header-cart user_all_activity">
                                                <a href="{{url('/client/profile/page/id-'.\Auth::user()->id)}}">
                                                    <span class="top_title header-icon fa fa-user"></span>
                                                    <span class="title top_title_text">{{$first_name[0]}}</span>
                                                </a>
                                            </div>          
                                            <div class="header-wishlist ml-20 user_all_activity">
                                                <a href="{{url('/client/logout/'.\Auth::user()->mobile)}}">
                                                <span class="top_title header-icon fa fa-sign-out"></span>
                                                <span class="title top_title_text">Log Out</span>
                                                </a>
                                            </div>
                                        @else
                                            <div class="header-cart user_all_activity">
                                                <a href="{{url('/sign-in/page')}}" class="all_title">
                                                    <span class="top_title header-icon fa fa-lock"></span>
                                                    <span class="title top_title_text ">Log In</span>
                                                </a>
                                            </div>
                                            <div class="header-wishlist ml-20 user_all_activity">
                                                <a href="{{url('/sign-in/page')}}">
                                                    <span class="top_title header-icon fa fa-user"></span>
                                                    <span class="title top_title_text">Join Now</span>
                                                </a>
                                            </div>
                                        @endif


                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>
            </div>
            <!-- End Header Header -->

            <div id="printMainSlider">

                <!-- Header Menu -->
                <div class="header-menu bg-blue">
                    <div class="container">
                        <nav class="nav-bar">
                            <div class="nav-header">
                                <span class="nav-toggle" data-toggle="#header-navbar">
    		                        <i></i>
    		                        <i></i>
    		                        <i></i>
    		                    </span>
                            </div>
                            <div id="header-navbar" class="nav-collapse">
                                <ul class="nav-menu">
                                    <li class="{{isset($page_desc) && ($page_desc=='') ? 'active' : ''}}">
                                        <a href="{{url('/home')}}">Home</a>
                                    </li>
                                    
                                    <?php 
                                        $all_sub_category=\DB::table('tbl_sub_category')->where('category_id','0')->get();
                                        if(!empty($all_sub_category)){
                                        foreach ($all_sub_category as $key => $sub_category) {
                                        
                                    ?>
                                        <li class="{{isset($page_desc) && ($page_desc==$sub_category->sub_category_id) ? 'active' : ''}}">
                                            <a href="{{url('all-merchant/coupon/page/cid-/'.$sub_category->category_id.'/subcid-'.$sub_category->sub_category_id)}}">{{$sub_category->sub_category_name}}</a>
                                        </li>

                                    <?php
                                        }
                                        }
                                    ?>

                                    <?php 
                                        $all_category=\DB::table('tbl_category')->get();
                                        if(!empty($all_category)){
                                        foreach ($all_category as $key => $category) {
                                        
                                    ?>
                                    <li>
                                        <a href="#">{{$category->category_name}}</a>
                                        <ul>
                                            <?php 
                                                $all_sub_category=\DB::table('tbl_sub_category')->where('category_id',$category->category_id)->get();
                                                if(!empty($all_sub_category)){
                                                foreach ($all_sub_category as $key2 => $sub_category) {
                                            ?>
                                            <li>
						<a href="{{url('all-merchant/coupon/page/cid-/'.$sub_category->sub_category_id.'/subcid-'.$sub_category->sub_category_id)}}">{{$sub_category->sub_category_name}}</a>
                                            </li>
                                            <?php
                                                }
                                                }
                                            ?>
                                        </ul>
                                    </li>
                                    <?php
                                        }
                                        }
                                    ?>

                                </ul>
                            </div>
                        </nav>
                    </div>
                </div>
                <!-- End Header Menu -->
            </div>


        </header>
        <!-- –––––––––––––––[ HEADER ]––––––––––––––– -->