@extends('layout.master')
@section('content')
        <!-- –––––––––––––––[ PAGE CONTENT ]––––––––––––––– -->
        <main id="mainContent" class="main-content">
            <!-- Page Container -->
            <div class="page-container store-page ptb-60">
                <div class="container">

                    <section class="store-header-area panel t-xs-center t-sm-left">
                        <div class="row">

                            <div class="col-md-12" style="margin:20px;">
                                <h2 align="center">
									FAQ
								</h2>

								<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
								  <div class="panel panel-default">
								    <div class="panel-heading" role="tab" id="headingOne">
								      <h4 class="panel-title">
								        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne" style="color:#00a2ff;">
								          Step 1
								        </a>
								      </h4>
								    </div>
								    <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
								      <div class="panel-body">
								        At first active your coupon.
								      </div>
								    </div>
								  </div>
								  <div class="panel panel-default">
								    <div class="panel-heading" role="tab" id="headingTwo">
								      <h4 class="panel-title">
								        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo" style="color:#00a2ff;">
								          Step 2
								        </a>
								      </h4>
								    </div>
								    <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
								      <div class="panel-body">
								        Now submit your shopping amount.
								      </div>
								    </div>
								  </div>

								  <div class="panel panel-default">
								    <div class="panel-heading" role="tab" id="headingThree">
								      <h4 class="panel-title">
								        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree" style="color:#00a2ff;">
								          Step 3
								        </a>
								      </h4>
								    </div>
								    <div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
								      <div class="panel-body">
								        Then collect secret code from shop.
								    </div>
								  </div>
								</div>

								<div class="panel panel-default">
								    <div class="panel-heading" role="tab" id="headingFour">
								      <h4 class="panel-title">
								        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseFour" aria-expanded="false" aria-controls="collapseFour" style="color:#00a2ff;">
								          Step 4
								        </a>
								      </h4>
								    </div>
								    <div id="collapseFour" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingFour">
								      <div class="panel-body">
								        Now submit this secret code and getting discount.
								    </div>
								  </div>
								</div>

                            </div>
                        </div>
                    </section>

                </div>
            </div>


        </main>

        <!-- –––––––––––––––[ END PAGE CONTENT ]––––––––––––––– -->
@stop