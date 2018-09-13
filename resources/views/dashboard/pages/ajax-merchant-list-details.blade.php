<div class="panel panel-info">
  <div class="panel-heading" align="center"><strong>{{$select_merchant_info->merchant_name}} Profile</strong></div>
   <p align="center">
    <br><img src="{{asset('assets/images/merchant/'.$select_merchant_info->merchant_logo)}}">
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
        <td>{{$select_merchant_info->merchant_name}}</td>
      </tr>
      <tr>
        <td>Merchant Code</td>
        <td>{{$select_merchant_info->merchant_code}}</td>
      </tr>
      <tr>
        <td>Propriter Name</td>
        <td>{{$select_merchant_info->merchant_propriter}}</td>
      </tr>
      <tr>
        <td> Email </td>
        <td>{{$select_merchant_info->merchant_email}}</td>
      </tr>
      <tr>
        <td> Mobile </td>
        <td>{{$select_merchant_info->merchant_propriter_mobile}}</td>
      </tr>
      <tr>
        <td>Merchant Website Url</td>
        <td>{{$select_merchant_info->merchant_website_url}}</td>
      </tr>      
      <tr>
        <td>Address</td>
        <td>{{$select_merchant_info->merchant_address}}</td>
      </tr>      
      <tr>
        <td>Description</td>
        <td>{{$select_merchant_info->merchant_description}}</td>
      </tr>

    </tbody>
  </table>
 </div>
</div>