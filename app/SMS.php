<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Classes\CustomLogger;

class SMS extends Model
{
   
	/************************
	### SendSMS 
	************************/

	public static function SendSMS($mobileno,$text) {

		$plaintext=$text;
		$text = urlencode($text); 

		if (strlen($mobileno) == 10)
			$mobileno = "880" . $mobileno;
		if (strlen($mobileno) == 11)
			$mobileno = "88" . $mobileno;

		$curl_data = "&To=$mobileno&Message=$text";
	 
		$url = "http://103.4.146.204/Client/sms_api.php?Username=yessuser&Password=yessuser@231&From=8801841771771".$curl_data;
		//"http://103.4.146.204/Client/sms_api.php?Username=yessuser&Password=yessuser@231&From=8801841771771"
		$ch = curl_init($url);
 
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$htmlText = curl_exec($ch);
		
		\App\SMS::SMSLogWrite($mobileno,$plaintext,$htmlText);
		//echo $htmlText;
		curl_close($ch);

		return true;
	}
	
	/********************************************
    ## SMSLogWrite 
    *********************************************/
    public static function SMSLogWrite($mobileno,$text,$delivery){
     
        $now = date('Y-m-d H:i:s');
        $sms_insert = [
                              
                                'received_mobile' => $mobileno,
                                'sms_body'   => $text,
								'delivery_report'=>$delivery,
                                'created_at'  => $now,
                                'updated_at'  => $now 

                        ];

         \DB::table('sms_log')->insert($sms_insert);


        /***********Text Log**************************/

        $message = $mobileno.'|'.$text.'|'.$delivery;

        \App\System::CustomLogWritter("smslog","sms_log",$message);

        return true;
    }
    
}
