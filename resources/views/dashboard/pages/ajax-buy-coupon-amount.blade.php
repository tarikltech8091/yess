
<div class="panel panel-default">
		<ul class="nav nav-tabs tab-padding tab-space-3 tab-blue" id="myTab">
			<li class="{{isset($tab) && ($tab=='amount') ? 'active' : ''}}">
				<a href="#amount" role="tab" id="amount-tab" data-toggle="tab" aria-controls="amount">
					Buy Coupon
				</a>
			</li>

			<li class="{{isset($tab) && ($tab=='otp') ? 'active' : ''}}">
				<a href="#otp" role="tab" id="otp-tab" data-toggle="tab" aria-controls="otp">
					OTP Confirm
				</a>
			</li>
		</ul>

	<div class="tab-content">
		<div class="messageblock"></div>
		<div id="amount" class="tab-pane in {{isset($tab) && ($tab=='amount') ? 'in active' : ''}}" aria-labelledby="amount-tab">
				
				<div class="">
					<div class="form-group">
						<label> 
						Shopping Amount 
						<span class="symbol required"></span></label>
						<input type="text" id="shopping_amount" class="form-control" name="shopping_amount" required="">
					</div>

						<a class="btn btn-primary pull-right select_coupon_details shopping_amount_details_show" href="#otp"  data-toggle="tab" aria-controls="otp" data-ccode="{{$coupon_code}}" data-cmobile="{{$customer_mobile}}" data-tab="otp">Next<i class="fa fa-arrow-circle-right"></i></a>

				</div>
		</div>

		<div id="otp" class="tab-pane in {{isset($tab) && ($tab=='otp') ? 'in active' : ''}}" aria-labelledby="otp-tab" style="margin:5px;">
				<input type="hidden" name="_token" value="{{csrf_token()}}">
				<div class="row">
					<div class="col-md-12">
						<div class="form-group">
							<label class="control-label">
								OTP Confirm
							</label>
							<input type="text" placeholder="OTP" class="form-control" id="coupon_otp" name="coupon_otp" value="">
						</div>

						<a class="btn btn-primary pull-right coupon_otp_confirm" data-ccode="{{$coupon_code}}" data-cmobile="{{$customer_mobile}}" data-tid="{{$coupon_transaction_id}}">Confirm<i class="fa fa-arrow-circle-right"></i></a>

						<a class="btn btn-teal pull-left" href="#"><i class="fa fa-arrow-circle-right"></i>Resend OTP</a>
					</div>
				</div>
		</div>

	</div>

</div>

	<script type="text/javascript">
        $('#myTab a').click(function (e) {
         e.preventDefault()
        $(this).tab('show')
        })
       $('a[data-toggle="tab"]').addClass('disabled');
       $('a[data-toggle="tab"]').click(function(e){

            if($this.hasClass("disabled")){

                e.preventDefault();

                e.stopPropagation();

                e.stopImmediatePropagation();

                return false;

            }
		})
       $('a[data-toggle="tab"]').removeClass('disabled');

       </script>


<!-- <style type="text/css">
    /* Over the pointer-events:none, set the cursor to not-allowed.
    On this way you will have a more user friendly cursor. */
    .disabledTab {
        cursor: not-allowed;
    }
    /* Clicks are not permitted and change the opacity. */
    li.disabledTab > a[data-toggle="tab"] {
        pointer-events: none;
        filter: alpha(opacity=65);
        -webkit-box-shadow: none;
        box-shadow: none;
        opacity: .65;
    }
</style>

<ul class="nav nav-tabs tab-header">
    <li>
        <a href="#tab-infor" data-toggle="tab">Info</a>
    </li>
    <li class="disabledTab">
        <a href="#tab-date" data-toggle="tab">Date</a>
    </li>
    <li>
        <a href="#tab-photo" data-toggle="tab">Photo</a>
    </li>
</ul>
<div class="tab-content">
    <div class="tab-pane active" id="tab-infor">Info</div>
    <div class="tab-pane active" id="tab-date">Date</div>
    <div class="tab-pane active" id="tab-photo">Photo</div>
</div>

<script type="text/javascript">
if (false) //your condition
{
    $("a[data-toggle='tab'").prop('disabled', true);
    $("a[data-toggle='tab'").each(function () {
        $(this).prop('data-href', $(this).attr('href')); // hold you original href
        $(this).attr('href', '#'); // clear href
    });                
    $("a[data-toggle='tab'").addClass('disabled-link');
}
else
{
    $("a[data-toggle='tab'").prop('disabled', false);
    $("a[data-toggle='tab'").each(function () {
        $(this).attr('href', $(this).prop('data-href')); // restore original href
    });
    $("a[data-toggle='tab'").removeClass('disabled-link');
}
// if you want to show extra messages that the tab is disabled for a reason
$("a[data-toggle='tab'").click(function(){
   alert('Tab is disabled for a reason');
});
</script>
 -->

<!-- 

<div class="container">
<div class="stepwizard">
    <div class="stepwizard-row setup-panel">
        <div class="stepwizard-step">
            <a href="#step-1" type="button" class="btn btn-primary btn-circle">1</a>
            <p>Step 1</p>
        </div>
        <div class="stepwizard-step">
            <a href="#step-2" type="button" class="btn btn-default btn-circle" disabled="disabled">2</a>
            <p>Step 2</p>
        </div>
        <div class="stepwizard-step">
            <a href="#step-3" type="button" class="btn btn-default btn-circle" disabled="disabled">3</a>
            <p>Step 3</p>
        </div>
    </div>
</div>
<form role="form">
    <div class="row setup-content" id="step-1">
        <div class="col-xs-12">
            <div class="col-md-6">
                <h3> Step 1</h3>
                <div class="form-group">
                    <label class="control-label">First Name</label>
                    <input  type="text" required="required" class="form-control" placeholder="Enter First Name"  />
                </div>
                <div class="form-group">
                    <label class="control-label">Last Name</label>
                    <input type="text" required="required" class="form-control" placeholder="Enter Last Name" />
                </div>
                <button class="btn btn-primary nextBtn" type="button" >Next</button>
            </div>
        </div>
    </div>
    <div class="row setup-content" id="step-2">
        <div class="col-xs-12">
            <div class="col-md-6">
                <h3> Step 2</h3>
                <div class="form-group">
                    <label class="control-label">Company Name</label>
                    <input maxlength="200" type="text" required="required" class="form-control" placeholder="Enter Company Name" />
                </div>
                <div class="form-group">
                    <label class="control-label">Company Address</label>
                    <input maxlength="200" type="text" required="required" class="form-control" placeholder="Enter Company Address"  />
                </div>
                <button class="btn btn-primary nextBtn" type="button" >Next</button>
            </div>
        </div>
    </div>
    <div class="row setup-content" id="step-3">
        <div class="col-xs-12">
            <div class="col-md-6">
                <h3> Step 3</h3>
                <div class="form-group">
                    <label class="control-label">Last Name</label>
                    <input type="text" required="required" class="form-control" placeholder="Enter Last Name" />
                </div>
                <button class="btn btn-success btn-lg pull-right" type="submit">Finish!</button>
            </div>
        </div>
    </div>
</form>
</div>

<script type="text/javascript">
$(document).ready(function () {

    var navListItems = $('div.setup-panel div a'),
            allWells = $('.setup-content'),
            allNextBtn = $('.nextBtn');

    allWells.hide();

    navListItems.click(function (e) {
        e.preventDefault();
        var $target = $($(this).attr('href')),
                $item = $(this);

        if (!$item.hasClass('disabled')) {
            navListItems.removeClass('btn-primary').addClass('btn-default');
            $item.addClass('btn-primary');
            allWells.hide();
            $target.show();
            $target.find('input:eq(0)').focus();
        }
    });

    allNextBtn.click(function(){
        var curStep = $(this).closest(".setup-content"),
            curStepBtn = curStep.attr("id"),
            nextStepWizard = $('div.setup-panel div a[href="#' + curStepBtn + '"]').parent().next().children("a"),
            curInputs = curStep.find("input[type='text'],input[type='url']"),
            isValid = true;

        $(".form-group").removeClass("has-error");
        for(var i=0; i<curInputs.length; i++){
            if (!curInputs[i].validity.valid){
                isValid = false;
                $(curInputs[i]).closest(".form-group").addClass("has-error");
            }
        }

        if (isValid)
            nextStepWizard.removeAttr('disabled').trigger('click');
    });

    $('div.setup-panel div a.btn-primary').trigger('click');
});
</script> -->

<!-- 
<script type="text/javascript">
$(document).ready(function () {

    var navListItems = $('div.setup-panel div a'),
            allWells = $('.setup-content'),
            allNextBtn = $('.nextBtn');

    allWells.hide();

    navListItems.click(function (e) {
        e.preventDefault();
        var $target = $($(this).attr('href')),
                $item = $(this);

        if (!$item.hasClass('disabled')) {
            navListItems.removeClass('btn-primary').addClass('btn-default');
            $item.addClass('btn-primary');
            allWells.hide();
            $target.show();
            $target.find('input:eq(0)').focus();
        }
    });

    allNextBtn.click(function(){
        var curStep = $(this).closest(".setup-content"),
            curStepBtn = curStep.attr("id"),
            nextStepWizard = $('div.setup-panel div a[href="#' + curStepBtn + '"]').parent().next().children("a"),
            curInputs = curStep.find("input[type='text'],input[type='url']"),
            isValid = true;

        $(".form-group").removeClass("has-error");
        for(var i=0; i<curInputs.length; i++){
            if (!curInputs[i].validity.valid){
                isValid = false;
                $(curInputs[i]).closest(".form-group").addClass("has-error");
            }
        }

        if (isValid)
            nextStepWizard.removeAttr('disabled').trigger('click');
    });

    $('div.setup-panel div a.btn-primary').trigger('click');
});
</script> -->

