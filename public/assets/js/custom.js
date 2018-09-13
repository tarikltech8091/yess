

/*##########################################
# All User's Status Change
############################################
*/

jQuery(function(){

    jQuery('.user_status_change').click(function(){
        var r = confirm("Are you confirm??");
        if (r == true) {
            var user_id = jQuery(this).data('user-id');
            var action = jQuery(this).data('status');
            var tab = jQuery(this).data('tab');
            var site_url = jQuery('.site_url').val();
            var request_url = site_url+'/dashboard/user/status-change/id-'+user_id+'/'+action+'/'+tab;
            jQuery.ajax({
                url: request_url,
                type: "get",
                success:function(data){

                     window.location.href =site_url+'/dashboard/user-management?tab='+tab;

                    }
            });
        }else{
            return false;
        }
    });
});


/*##########################################
# Coupon Pending Transaction Delete
############################################
*/

jQuery(function(){

    jQuery('.pending_transaction_delete').click(function(){
        var r = confirm("Are you confirm??");
        if (r == true) {
            var coupon_transaction_id = jQuery(this).data('tid');
            var site_url = jQuery('.site_url').val();
            var request_url = site_url+'/dashboard/active-deal/ctid-'+coupon_transaction_id;
            jQuery.ajax({
                url: request_url,
                type: "get",
                success:function(data){

                    jQuery('#transaction_delete_'+coupon_transaction_id).hide(); 

                    }
            });
        }else{
            return false;
        }
    });
});



// jQuery(function(){
//     jQuery('.select_type').change(function(){
//         alert('hello');
//     // jQuery(function change() {

//         var selectBox = document.getElementById("show");
//         var selected = selectBox.options[selectBox.selectedIndex].value;
//         var textarea = document.getElementById("text_area");
//         alert(selected);
//         alert(selectBox);
//         if(selected === '1'){
//             textarea.show();
//         }
//         elseif(selected === '1'){
//             textarea.show();
//         }
//         else{
//             textarea.hide();
//         }
//     });
// });

/*##########################################
# Change Home Slider Status
############################################*/

jQuery(function(){

    jQuery('.setting_meta_status').click(function(){
        var r = confirm("Are you confirm??");
        if (r == true) {
            var setting_id = jQuery(this).data('id');
            var action = jQuery(this).data('action');
            var site_url = jQuery('.site_url').val();
            var request_url = site_url+'/dashboard/home-slider/change-status/id-'+setting_id+'/'+action;
            jQuery.ajax({
                url: request_url,
                type: "get",
                success:function(data){

                     window.location.href =site_url+'/dashboard/home-slider';

                    }
            });
        }else{
            return false;
        }
    });
});



/*##########################################
# Sub Category List
############################################
*/

jQuery(function(){
    jQuery('.select_category').change(function(){

        var category_id = jQuery(this).val();
        var site_url = jQuery('.site_url').val();
        var request_url = site_url+'/ajax/sub-category/list-'+category_id;
        if(category_id.length != 0){
            jQuery.ajax({
                url: request_url,
                type: 'get',
                success:function(data){
                    jQuery('.sub_category_list').html(data);
                }
            });
        }
    });
});



/*##########################################
# Change Sub Category Status
############################################*/

jQuery(function(){

    jQuery('.sub_category_status').click(function(){
        var r = confirm("Are you confirm??");
        if (r == true) {
            var sub_category_id = jQuery(this).data('id');
            var action = jQuery(this).data('action');
            var site_url = jQuery('.site_url').val();
            var request_url = site_url+'/dashboard/sub-category/change-id-'+sub_category_id+'/'+action;
            jQuery.ajax({
                url: request_url,
                type: "get",
                success:function(data){

                     window.location.href =site_url+'/dashboard/sub-category';

                    }
            });
        }else{
            return false;
        }
    });
});

/*##########################################
# Sub Branch List
############################################
*/

jQuery(function(){
    jQuery('.select_merchant_list').change(function(){

        var merchant_id = jQuery(this).val();
        var site_url = jQuery('.site_url').val();
        var request_url = site_url+'/ajax/branch/list-'+merchant_id;

        if(merchant_id.length != 0){
            jQuery.ajax({
                url: request_url,
                type: 'get',
                success:function(data){
                    jQuery('.branch_list').html(data);
                }
            });
        }
    });
});



/*##########################################
# Change Merchant Featured Status
############################################*/

jQuery(function(){

    jQuery('.merchant_featured_status').click(function(){
        var r = confirm("Are you confirm??");
        if (r == true) {
            var featured_product_id = jQuery(this).data('id');
            var action = jQuery(this).data('action');
            var site_url = jQuery('.site_url').val();
            var request_url = site_url+'/dashboard/merchant/featured/change-id-'+featured_product_id+'/'+action;
            jQuery.ajax({
                url: request_url,
                type: "get",
                success:function(data){

                     window.location.href =site_url+'/dashboard/merchant/featured';

                    }
            });
        }else{
            return false;
        }
    });
});


/*###########################
# User Details Modal
############################
*/

jQuery(function(){
  jQuery('.user_details_show').click(function(){
    var user_id = jQuery(this).data('id');
    var site_url = jQuery('.site_url').val();
    var request_url  = site_url+'/dashboard/user-details/id-'+user_id;
    jQuery.ajax({
      url: request_url,
      type: 'get',
      success:function(data){

          jQuery('.user_details').html(data);

      }
    });

  });
});


/*###########################
# Merchant Details Modal
############################
*/

jQuery(function(){
  jQuery('.merchant_details_show').click(function(){
    var merchant_id = jQuery(this).data('id');
    var site_url = jQuery('.site_url').val();
    var request_url  = site_url+'/dashboard/merchant-details/id-'+merchant_id;
    jQuery.ajax({
      url: request_url,
      type: 'get',
      success:function(data){

          jQuery('.merchant_details').html(data);

      }
    });

  });
});



/*###########################
# Coupon Details Modal
############################
*/

jQuery(function(){
  jQuery('.coupon_detail_show').click(function(){
    var coupon_id = jQuery(this).data('id');
    var site_url = jQuery('.site_url').val();
    var request_url  = site_url+'/dashboard/coupon/view/id-'+coupon_id;
    jQuery.ajax({
      url: request_url,
      type: 'get',
      success:function(data){

          jQuery('.coupon_detail').html(data);

      }
    });

  });
});


/*##########################################
# Change Merchant Status
############################################*/

jQuery(function(){

    jQuery('.merchant_status').click(function(){
        var r = confirm("Are you confirm??");
        if (r == true) {
            var merchant_id = jQuery(this).data('merchant-id');
            var action = jQuery(this).data('action');
            var site_url = jQuery('.site_url').val();
            var request_url = site_url+'/dashboard/merchant/change-status/id-'+merchant_id+'/'+action;
            
            jQuery.ajax({
                url: request_url,
                type: "get",
                success:function(data){

                     window.location.href =site_url+'/dashboard/all-merchant/list';

                    }
            });
        }else{
            return false;
        }
    });
});


/*##########################################
# Change Merchant Rank
############################################*/

jQuery(function(){

    jQuery('.merchant_rank_status').click(function(){
        var r = confirm("Are you confirm??");
        if (r == true) {  
            var merchant_id = jQuery(this).data('merchant-id');
            var action = jQuery(this).data('action');
            var site_url = jQuery('.site_url').val();
            var request_url = site_url+'/dashboard/merchant/change-rank/id-'+merchant_id+'/'+action;
            
            jQuery.ajax({
                url: request_url,
                type: "get",
                success:function(data){

                     window.location.href =site_url+'/dashboard/all-merchant/list';

                    }
            });

        }else {
            return false;
        }
    });
});


/*##########################################
# Change User Status
############################################
*/

jQuery(function(){

    jQuery('.user_status').click(function(){
        var r = confirm("Are you confirm??");
        if (r == true) {
            var user_id = jQuery(this).data('user-id');
            var action = jQuery(this).data('action');
            var site_url = jQuery('.site_url').val();
            var request_url = site_url+'/dashboard/user/change-status/id-'+user_id+'/'+action;
            jQuery.ajax({
                url: request_url,
                type: "get",
                success:function(data){

                     window.location.href =site_url+'/dashboard/all-user/list';

                    }
            });

        }else{
            return false;
        }
    });
});


/*##########################################
# Merchant List
############################################
*/

jQuery(function(){
    jQuery('.select_user_type').change(function(){

        var user_type = jQuery(this).val();
        var site_url = jQuery('.site_url').val();

        var request_url = site_url+'/ajax/merchant/list-'+user_type;
        if((user_type == 'merchant') ||  (user_type == 'branch')){


          if(user_type.length != 0){
              jQuery.ajax({
                  url: request_url,
                  type: 'get',
                  success:function(data){
                      jQuery('.merchant_list').html(data);
                  }
              });
          }
          $('#merchant_select_box').show();
          if(user_type == 'branch'){
            $('#branch_select_box').show();
          }

        }else{
            $('#merchant_select_box').hide();
            $('#branch_select_box').hide();
        }

    });
});


/*##########################################
# Merchant Branch User List
############################################
*/

jQuery(function(){
    jQuery('.select_merchant_user').change(function(){

        var merchant_id = jQuery(this).val();
        var site_url = jQuery('.site_url').val();

        var request_url = site_url+'/ajax/merchant-branch/list-'+merchant_id;
          if(merchant_id.length != 0){
              jQuery.ajax({
                  url: request_url,
                  type: 'get',
                  success:function(data){
                      jQuery('.branch_user_list').html(data);
                  }
              });
          }
    });
});



    /********************************************
    ## Coupon Highlight List 
    *********************************************/

    jQuery(function(){
        jQuery('.highlight_coupon_list').click(function(){
            var r = confirm("Are you confirm??");
            if (r == true) {
                var coupon_id = jQuery(this).data('id');
                var action = jQuery(this).data('action');
                var site_url = jQuery('.site_url').val();
                var request_url = site_url+'/coupon/highlight/'+coupon_id+'/'+action;

                jQuery.ajax({
                    url: request_url,
                    type: "get",
                    success:function(data){
                        window.location.href =site_url+'/dashboard/all-coupon/list';
                    }
                });
            }else{
                return false;
            }
            
        });
    });

    /********************************************
    ## Coupon Status Change 
    *********************************************/

    jQuery(function(){
        jQuery('.change_coupon_status').click(function(){
            var r = confirm("Are you confirm??");
            if (r == true) {
                var coupon_id = jQuery(this).data('id');
                var action = jQuery(this).data('action');
                var site_url = jQuery('.site_url').val();
                var request_url = site_url+'/coupon/change/status/cid-'+coupon_id+'/'+action;

                jQuery.ajax({
                    url: request_url,
                    type: "get",
                    success:function(data){
                        window.location.href =site_url+'/dashboard/all-coupon/list';
                    }
                });
            }else{
                return false;
            }
            
        });
    });



  /********************************************
    ## Client Ratings
    *********************************************/

    jQuery(function(){
        jQuery('.rating').click(function(){

            var coupon_id = jQuery('.coupon_id').data('coupon-id');
            var rating_point = jQuery(this).val();
            var site_url = jQuery('.site_url').val();

            var request_url = site_url+'/client/rating/'+coupon_id+'/'+rating_point;

            jQuery.ajax({
                url: request_url,
                type: "get",
                success:function(data){

                }
            });
            
        });
    });



/*##########################################
# Coupon Confirm
############################################
*/

jQuery(function(){

    jQuery('.coupon_confirm').click(function(){
        var r = confirm("Are you confirm??");
        if (r == true) {
            var coupon_transaction_id = jQuery(this).data('id');
            var coupon_code = jQuery(this).data('code');
            var status = jQuery(this).data('status');
            var site_url = jQuery('.site_url').val();
            var request_url = site_url+'/dashboard/coupon-'+coupon_code+'/id-'+coupon_transaction_id;
            jQuery.ajax({
                url: request_url,
                type: "get",
                success:function(data){

                     window.location.href =site_url+'/dashboard/branch/coupon-transaction/list';

                    }
            });
        }else{
            return false;
        }
    });
});







/*##########################################
# Change Merchant Status
############################################
*/

// jQuery(function(){

//     jQuery('.merchndat_status').click(function(){
//         var merchnat_id = jQuery(this).data('merchnat_id');
//         var tab = jQuery(this).data('tab');
//         var action = jQuery(this).data('action');
//         var site_url = jQuery('.site_url').val();
//         var request_url = site_url+'/dashboard/change-merchant-status/'+merchnat_id+'/'+action;
    
//         jQuery.ajax({
//             url: request_url,
//             type: "get",
//             success:function(data){

//                 if(action==-1)
//                  window.location.href =site_url+'/dashboard/admin/user/management?tab='+tab;

//                 if(action==1)
//                     window.location.href =site_url+'/dashboard/admin/user/management?tab='+tab;
//             }
//         });
//     });
// });

