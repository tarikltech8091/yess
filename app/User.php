<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $primaryKey = 'id';



    /********************************************
    ## LogInStatusUpdate
    *********************************************/
    public static function LogInStatusUpdate($status){

        if(\Auth::checK()){

            if($status=='login')
                $change_status=1;
            else  $change_status=0;
            $now =date('Y-m-d H:i:s');

            $loginstatuschange = \App\User::where('id',\Auth::user()->id)
            ->update(array('login_status'=>$change_status, 'updated_at'=>$now));

            \App\System::AuthLogWrite($change_status);

            return $loginstatuschange;
        }
        
    }


    /********************************************
    ## CurlInit
    *********************************************/
    public static function CurlInit($url,$method,$data){
         $ch = curl_init($url);
         curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
         curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
         curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
         curl_setopt($ch, CURLOPT_HTTPHEADER, array(
          'Content-Type: application/json',
          'Content-Length: ' . strlen(json_encode($data)))
         );
         curl_setopt($ch, CURLOPT_TIMEOUT, 5);
         curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);

         $result = curl_exec($ch);
         curl_close($ch);

         return $result;
    }













}
