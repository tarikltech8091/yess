
  /********************************************
    ## Client Ratings
    *********************************************/

    jQuery(function(){
        jQuery('.ratings').click(function(){

            var coupon_id = jQuery('.coupon_id').data('coupon-id');
            var rating_point = jQuery(this).val();
            var site_url = jQuery('.site_url').val();
            var request_url = site_url+'/coupon/rating/'+coupon_id+'/'+rating_point;

            jQuery.ajax({
                url: request_url,
                type: "get",
                success:function(data){
                	// window.location.href =site_url+'/single-page/coupon_id-'+coupon_id;
                }
            });
            
        });
    });


  /********************************************
    ## Client Follow List 
    *********************************************/

    jQuery(function(){
        jQuery('.follow_list').click(function(){
            var r = confirm("Are you confirm??");
            if (r == true) {
                var merchant_id = jQuery(this).data('fid');
                var user_id = jQuery(this).data('uid');
                var status = jQuery(this).data('status');
                var site_url = jQuery('.site_url').val();
                var request_url = site_url+'/follow/merchant/id-'+merchant_id+'/status-'+status;
                jQuery.ajax({
                    url: request_url,
                    type: "get",
                    success:function(data){
                        jQuery('#unfollow_'+merchant_id).hide(); 

                    }
                });

            }else{
                return false;
            }
            
        });
    });




    /********************************************
    ## Single div link
    *********************************************/

    jQuery(function(){
        
        $(".index_discount").click(function(e){
           e.stopPropagation();
            window.location.href=$(this).attr('href');
            e.preventDefault();
        })
        $(".index_quick_view").click(function(e){
              e.stopPropagation();
                var coupon_id = jQuery(this).data('id');
                var site_url = jQuery('.site_url').val();
                var request_url  = site_url+'/dashboard/coupon-details/id-'+coupon_id;
                jQuery.ajax({
                  url: request_url,
                  aysnc:false,
                  type: 'get',
                  success:function(data){

                      jQuery('.main_coupon_details').html(data);
                       $("#MainCouponDetailsModal").modal('toggle');

                  }
                });

        })

        $(".index_wish").click(function(e){
              e.stopPropagation();
                var coupon_id = jQuery(this).data('id');
                var status = jQuery(this).data('status');
                var site_url = jQuery('.site_url').val();
                var request_url = site_url+'/add/wish-list/cid-'+coupon_id+'/status-'+status;
                jQuery.ajax({
                    url: request_url,
                    type: "get",
                    success:function(data){
                        jQuery('#active_wish_'+coupon_id).css("color", "red"); 
                    }
                });

        })

        $(".index_social_link").click(function(e){
           e.stopPropagation();

        })

        $(".deal-thumbnail.embed-responsive.embed-responsive-new").click(function(e){
            window.location.href=$(this).data('id');
        })
      
    });



    /********************************************
    ## Client Wish Add 
    *********************************************/
    jQuery(function(){

        jQuery('.add_wish').click(function(){
                var coupon_id = jQuery(this).data('id');
                var status = jQuery(this).data('status');
                var site_url = jQuery('.site_url').val();
                var request_url = site_url+'/add/wish-list/cid-'+coupon_id+'/status-'+status;
                jQuery.ajax({
                    url: request_url,
                    type: "get",
                    success:function(data){
                        console.log(this);
                        jQuery('#active_wish_'+coupon_id).css("color", "red");
                    }
                });
            
        });
    });



    /********************************************
    ## Client Follow Add 
    *********************************************/

    jQuery(function(){
        jQuery('.add_folow').click(function(){
                var merchant_id = jQuery(this).data('id');
                var status = jQuery(this).data('status');
                var site_url = jQuery('.site_url').val();
                var request_url = site_url+'/follow/merchant/id-'+merchant_id+'/status-'+status;
                
                jQuery.ajax({
                    url: request_url,
                    type: "get",
                    success:function(data){
                        jQuery('#active_follow_'+merchant_id).css("background-color", "#00a2ff"); 
                        jQuery('#active_follow_'+merchant_id).css("color", "#fff"); 

                    }
                });

            
        });
    });



    /********************************************
    ## Client Wish List 
    *********************************************/

    jQuery(function(){
        jQuery('.wish_list').click(function(){
            var r = confirm("Are you confirm??");
            if (r == true) {
                var coupon_id = jQuery('.wish_list').data('id');
                var status = jQuery('.wish_list').data('status');
                var site_url = jQuery('.site_url').val();

                var request_url = site_url+'/add/wish-list/cid-'+coupon_id+'/status-'+status;
                
                jQuery.ajax({
                    url: request_url,
                    type: "get",
                    success:function(data){
                        // window.location.href =site_url+'/single-page/coupon_id-'+coupon_id;
                        jQuery(this).css("color", "red"); 

                    }
                });
            }else{
                return false;
            }
            
        });
    });



    /********************************************
    ## Client Wish List Delete
    *********************************************/

    jQuery(function(){
        jQuery('.wish_list_delete').click(function(){
            var r = confirm("Are you confirm??");
            if (r == true) {
                var activity_id = jQuery(this).data('id');
                var site_url = jQuery('.site_url').val();

                var request_url = site_url+'/delete/wish-list/id-'+activity_id;
                
                jQuery.ajax({
                    url: request_url,
                    type: "get",
                    success:function(data){
                        // jQuery('#wish_delete').css("color", "black"); 
                        jQuery('#wish_delete_'+activity_id).hide(); 
                    }
                });
            }else{
                return false;
            }
            
        });
    });




/*###########################
# Coupon Details Modal
############################
*/

jQuery(function(){
  jQuery('.coupon_details_show').click(function(){
    var coupon_id = jQuery(this).data('id');
    var site_url = jQuery('.site_url').val();
    var request_url  = site_url+'/dashboard/coupon-details/id-'+coupon_id;
    jQuery.ajax({
      url: request_url,
      aysnc:false,
      type: 'get',
      success:function(data){

          jQuery('.coupon_details').html(data);
           // $("#CouponDetailsModal").modal('toggle');

      }
    });

  });
});



//***************//
// scroll widget //
//***************//

$(document).ready(function() { 

    // add image + title form sidebar
    // $('.latest-deals-area').each(function(){
    //   var this_id = $(this).attr("id");
    //   var src_img = $('.panel-title i',this).attr("class");
    //   var this_title = $('.panel-title',this).text();

    //   var html = '<li><a href="#'+this_id+'"><i class="'+src_img+'"></i><span>'+this_title+'</span></a></li>';
    //   $('.bs-docs-sidebar ul').append(html);
    // });
    
    $('body').scrollspy({
      target: '.bs-docs-sidebar',
      offset: 60
    });

    $('.bs-docs-sidebar li a').click(function(event) {

      if(!$(this).closest('li').hasClass('active')){
        $('.bs-docs-sidebar li').removeClass('active');
        $(this).closest('li').addClass('active');
      }  
      event.preventDefault();
      $($(this).attr('href'))[0].scrollIntoView();

    });

});


// scroll hidden sidebar
$(window).scroll(function(){
  if (($(".latest-deals-area").length > 0)){
    var viewableOffset = $(".latest-deals-area").first().offset().top - $(window).scrollTop(); 
    var scrollTop =  $(".main-header").first().offset().bottom - $(window).scrollTop();
    var scrollBottom =  $(".footer-top-area").first().offset().top - $(window).scrollTop();

    console.log("top: " + viewableOffset);
    console.log("last: " + scrollTop);
    console.log("last: " + scrollBottom);

    if(viewableOffset <= 40 ) {
      $('.bs-docs-sidebar').removeClass('hidden');
    }
    if(viewableOffset >= 700){
      $('.bs-docs-sidebar').addClass('hidden');
    }
    if(scrollTop <= 590){
      $('.bs-docs-sidebar').addClass('hidden');
    }
    if(scrollBottom <= 590){
      $('.bs-docs-sidebar').addClass('hidden');
    }
    
  } 
});

/*###########################
# Shoping Amount Modal
############################
*/

jQuery(function(){
  jQuery('.shopping_amount_details_show').click(function(){
    var merchant_id = jQuery(this).data('id');
    var code = jQuery(this).data('code');
    var mobile = jQuery(this).data('mobile');
    var coupon_transaction_id = jQuery(this).data('tid');
    var tab = jQuery(this).data('tab');
    var site_url = jQuery('.site_url').val();
    var request_url  = site_url+'/ajax/buy/coupon/details/code-'+code+'/mobile-'+mobile+'/tranid-'+coupon_transaction_id+'/tab-'+tab;
    jQuery.ajax({
      url: request_url,
      type: 'get',
      success:function(data){

          jQuery('.shopping_amount_show').html(data);

      }
    });

  });
});



    /********************************************
    ## Shopping Amount Submit
    *********************************************/

    jQuery(function(){
        jQuery('.shopping_amount_show').on('click', '.select_coupon_details', function() {

            var coupon_code = jQuery('.select_coupon_details').data('ccode');
            var customer_mobile = jQuery('.select_coupon_details').data('cmobile');
            var amount = document.getElementById('shopping_amount').value;
            var site_url = jQuery('.site_url').val();

            var request_url = site_url+'/dashboard/webcoupon/coupon-'+coupon_code+'/amount-'+amount+'/mobile-'+customer_mobile;
                jQuery.ajax({
                    url: request_url,
                    type: "GET",
                    success:function(data){

                        jQuery('.messageblock').html('<div class="alert alert-danger">'+data['message']+'</div>');

                    }

                });

            
        });
    });




    /********************************************
    ## Shopping OTP Submit
    *********************************************/

    jQuery(function(){
        jQuery('.shopping_amount_show').on('click', '.coupon_otp_confirm', function() {

            var coupon_code = jQuery('.coupon_otp_confirm').data('ccode');
            var customer_mobile = jQuery('.coupon_otp_confirm').data('cmobile');
            var coupon_transaction_id = jQuery('.coupon_otp_confirm').data('tid');
            var coupon_otp = document.getElementById('coupon_otp').value;
            var site_url = jQuery('.site_url').val();


            var request_url = site_url+'/dashboard/coupon-'+coupon_code+'/tid-'+coupon_transaction_id+'/scode-'+coupon_otp;
                jQuery.ajax({
                    url: request_url,
                    type: "get",
                    success:function(data){
                        jQuery('.messageblock').html('<div class="alert alert-danger">'+data['message']+'</div>');

                    }
                });

        });
    });



