<?php
//Gets the string/data from my emaildata.json file
$myjsonstring = "";
//$myjsonstring = fopen("emaildata.json", "r");
$myjsonstring = file_get_contents("emaildata.json");
echo $myjsonstring;
//Decodes the pretty string to seem like a JSON object
$myjson = json_decode($myjsonstring,true);

if($myjson!== ""){ //if the file isn't empty
	$name = $email = $temp = $light = $humidity = $msg = "";
	
	$name = $myjson["name"];
	//$email = $myjson->email;
	$email = $myjson["email"];
	
	//echo $myjson->name;
	//echo $name;
	
//The URL that we want to GET.
$urlTemp = "https://conrfield.com:1880/getTemp";
$urlLight = "https://conrfield.com:1880/getLight";
$urlHumidity = "https://conrfield.com:1880/getHumidity";
 
 //$response = http_get($urlTemp);
 //echo $response;
 $arrContextOptions=array(
    "ssl"=>array(
        "verify_peer"=>false,
        "verify_peer_name"=>false,
    ),
);
//Use file_get_contents to GET the URL in question.
$temp = file_get_contents($urlTemp, false, stream_context_create($arrContextOptions));
echo $urlTemp;
echo $temp;
//$light = file_get_contents($urlLight);
//$humidity = file_get_contents($urlHumidity);
 


echo $temp;
	if($temp!==";"){
	//$msg =	"Hi, " . $name . "\nThe motion sensor was activated. Also as an update:
		//	\nLight was " . $light . "\nTemperature was " . $temp . "\nHumidity was " . $humidity;
	}
	else{
	$msg =	"Hi, " . $name . "\nThe motion sensor was activated. \n The sensortag was off so there was no data to update you with";
	}	
	
	echo $msg;
	mail("$email","Motion sensor update",$msg);
	
	
	
}

?>