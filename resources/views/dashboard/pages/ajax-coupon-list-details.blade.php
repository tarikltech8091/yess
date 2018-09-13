<div class="panel panel-info">
  <div class="panel-heading" align="center"><strong>{{$select_coupon_info->merchant_name}} Profile</strong></div>
   <p align="center">
    <br><img height="100px" width="200px" src="{{asset($select_coupon_info->coupon_featured_image)}}">
   </p>
 <div class="panel-body">
  <table border="1" class="table table-hover table-bordered table-striped">
    <thead>
      <th>Field</th>
      <th>Value</th>
    </thead>
    <tbody>
      <tr>
        <td>Merchant Name</td>
        <td>{{$select_coupon_info->merchant_name}}</td>
      </tr>
      <tr>
        <td>Branch Name</td>
        <td>{{$select_coupon_info->branch_name}}</td>
      </tr>
      <tr>
        <td>Coupon Code</td>
        <td>{{$select_coupon_info->coupon_code}}</td>
      </tr>

      <tr>
        <td>Coupon Sele Price</td>
        <td>{{$select_coupon_info->coupon_sele_price}}</td>
      </tr>
      <tr>
        <td>Discount Rate</td>
        <td>{{$select_coupon_info->coupon_discount_rate}}</td>
      </tr>

       <tr>
        <td>Commision Rate</td>
        <td>{{$select_coupon_info->coupon_commision_rate}}</td>
      </tr>
      <tr>
        <td> Total Shopping Amount	</td>
        <td>{{$select_coupon_info->coupon_total_shopping_amount}}</td>
      </tr>
      <tr>
        <td> Total Discount </td>
        <td>{{$select_coupon_info->coupon_total_discount}}</td>
      </tr>
      <tr>
        <td>Total Commission</td>
        <td>{{$select_coupon_info->coupon_total_commission}}</td>
      </tr>      
      <tr>
        <td>Closing Date</td>
        <td>{{$select_coupon_info->coupon_closing_date}}</td>
      </tr>

      <tr>
        <td>Total Selled</td>
        <td>{{$select_coupon_info->coupon_total_selled}}</td>
      </tr>  

      <tr>
        <td>Applied Min Amount</td>
        <td>{{$select_coupon_info->coupon_applied_min_amount}}</td>
      </tr>      
      <tr>
        <td>Applied point</td>
        <td>{{$select_coupon_info->coupon_applied_point}}</td>
      </tr>

      <tr>
        <td>Description</td>
        <td>{{$select_coupon_info->coupon_description}}</td>
      </tr>

    </tbody>
  </table>
 </div>
</div>