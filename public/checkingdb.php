<?php
$DBHOST = "gator4263.hostgator.com";
$DBNAME = "sharif_yess";
$DBUSER = "sharif_yess";
$DBPASS = "YesSc0m@_Db";

$KEY = 'dhfakHueyrer93KJr4042diJri0Nfk';

var_dump($_SERVER);

function get_database_connection()
{
	global $DBHOST,$DBUSER,$DBPASS,$DBNAME;


	$link= mysql_connect($DBHOST,$DBUSER,$DBPASS,$DBNAME);

	if(!$link)
	{
		die('Could not connect:'.mysql_connect_error());
	}
	return $link;

}

//$return_value = get_database_connection();
//var_dump($return_value);

//phpinfo();


// Create connection
try {
    $conn = new PDO("mysql:host=$DBHOST;dbname=$DBNAME", $DBUSER, $DBPASS);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected successfully"; 
    }
catch(PDOException $e)
    {
    echo "Connection failed: " . $e->getMessage();
    }





?>