@extends('layout.master')
@section('content')

    <main id="mainContent" class="main-content">

        <div class="page-container ptb-10">
            <div class="container">
                <section class="sign-area panel p-40">

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

                    <div id="printMainSlider">
                        <h3 class="sign-title">Sign Up</h3>
                        <div class="row row-rl-0">
                            <div class="col-sm-6 col-md-7 col-left">
                                <form class="p-40" action="{{url('sign-up/page')}}" method="post">

                                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                                    <div class="form-group">
                                        <label class="sr-only">Full Name</label>
                                        <input type="text" name="name" class="form-control input-lg" placeholder="Full Name">
                                    </div>
                                    <div class="form-group">
                                        <label class="sr-only">Mobile No</label>
                                        <input type="text" name="user_mobile" class="form-control input-lg" placeholder="Mobile No">
                                    </div>
                                    <div class="form-group">
                                        <label class="sr-only">Password</label>
                                        <input type="password" name="password" class="form-control input-lg" placeholder="Password">
                                    </div>
                                    <div class="form-group">
                                        <label class="sr-only">Confirm Password</label>
                                        <input type="password"  name="confirm_password" class="form-control input-lg" placeholder="Confirm Password">
                                    </div>
                                    <div class="form-group">
                                        <label class="sr-only">OTP</label>
                                        <input type="password"  name="otp_confirm" class="form-control input-lg hidden" placeholder="Confirm OTP">
                                    </div>
                                    <div class="custom-checkbox mb-20">
                                        <input type="checkbox" id="agree_terms" required>
                                        <label class="color-mid" for="agree_terms">I agree to the Terms of Use and Privacy Statement.</label>
                                    </div>
                                    <button type="submit" class="btn btn-block btn-lg">Sign Up</button>
                                </form>
                                <span class="or">Or</span>
                            </div>
                            
                            <div class="col-sm-6 col-md-5 col-right">
                                <form class="p-40" action="{{url('/sign-in/page')}}" method="post">
                                <input type="hidden" name="_token" value="{{csrf_token()}}">
                                	<div class="form-group">
                                        <label>
                                            <h3> Have You Account??</h3>
                                        	<strong>If no, fill up left form.</strong>
                                        </label>
                                    </div>
                                    <div class="form-group">
                                        <label class="sr-only">Mobile No</label>
                                        <input type="text" name="mobile" class="form-control input-lg" placeholder="Mobile No">
                                    </div>
                                    <div class="form-group">
                                        <label class="sr-only">Password</label>
                                        <input type="password" name="password" class="form-control input-lg" placeholder="Password">
                                    </div>
                                    <div class="form-group">
                                        <a href="{{url('/forget/password')}}" class="forgot-pass-link color-green">Forget Password </a>
                                    </div>
                                    <div class="custom-checkbox mb-20">
                                        <input type="checkbox" id="remember_account" checked>
                                        <label class="color-mid" for="remember_account">Keep me signed in on this computer.</label>
                                    </div>
                                    <button type="submit" class="btn btn-block btn-lg">Sign In</button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div id="printOnly">
                        <div class="row row-rl-0">

                            <div class="col-sm-12 col-md-12">
                                <form action="{{url('/sign-in/page')}}" method="post">
                                <input type="hidden" name="_token" value="{{csrf_token()}}">
                                    <div class="form-group">
                                        <label>
                                            <strong class="font-22"> Have You Account??</strong><br>
                                            <strong>If no, sigh up below form.</strong>
                                        </label>
                                    </div>
                                    <div class="form-group">
                                        <label class="sr-only">Mobile No</label>
                                        <input type="text" name="mobile" class="form-control input-lg" placeholder="Mobile No">
                                    </div>
                                    <div class="form-group">
                                        <label class="sr-only">Password</label>
                                        <input type="password" name="password" class="form-control input-lg" placeholder="Password">
                                    </div>
                                    <div class="form-group">
                                        <a href="{{url('/forget/password')}}" class="forgot-pass-link color-green">Forget Password </a>
                                    </div>
                                    <div class="custom-checkbox mb-20">
                                        <input type="checkbox" id="remember_account" checked>
                                        <label class="color-mid" for="remember_account">Keep me signed in on this computer.</label>
                                    </div>
                                    <button type="submit" class="btn btn-block btn-lg">Sign In</button>
                                </form>
                            </div>

                            <div class="col-sm-12 col-md-12 ">
                                <h3 align="center"> Sign Up</h3>
                                <form action="{{url('sign-up/page')}}" method="post">

                                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                                    <div class="form-group">
                                        <label class="sr-only">Full Name</label>
                                        <input type="text" name="name" class="form-control input-lg" placeholder="Full Name">
                                    </div>
                                    <div class="form-group">
                                        <label class="sr-only">Mobile No</label>
                                        <input type="text" name="user_mobile" class="form-control input-lg" placeholder="Mobile No">
                                    </div>
                                    <div class="form-group">
                                        <label class="sr-only">Password</label>
                                        <input type="password" name="password" class="form-control input-lg" placeholder="Password">
                                    </div>
                                    <div class="form-group">
                                        <label class="sr-only">Confirm Password</label>
                                        <input type="password"  name="confirm_password" class="form-control input-lg" placeholder="Confirm Password">
                                    </div>
                                    <div class="form-group">
                                        <label class="sr-only">OTP</label>
                                        <input type="password"  name="otp_confirm" class="form-control input-lg hidden" placeholder="Confirm OTP">
                                    </div>
                                    <div class="custom-checkbox mb-20">
                                        <input type="checkbox" id="agree_terms" required>
                                        <label class="color-mid" for="agree_terms">I agree to the Terms of Use and Privacy Statement.</label>
                                    </div>
                                    <button type="submit" class="btn btn-block btn-lg">Sign Up</button>
                                </form>
                            </div>

                        </div>
                    </div>

                </section>
            </div>
        </div>


    </main>
@stop