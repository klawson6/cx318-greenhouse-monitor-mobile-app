<?php
//Gets the string/data from my emaildata.json file
$myjsonstring = "";

//$myjsonstring = fopen("emaildata.json", "r");
$myjsonstring = file_get_contents("emaildata.json");

//Decodes the pretty string to seem like a JSON object
$myjson = json_decode($myjsonstring,true);

if($myjson!== ""){ //if the file isn't empty
	$name = $email = $temp = $light = $humidity = $msg = "";
	
	$name = $myjson["name"]; //Gets the value for key: "name" 
	$email = $myjson["email"]; //Gets the value for key: "email" 
	
//The URL that we want to GET.
$urlTemp = "https://conrfield.com:1880/getTemp";
$urlLight = "https://conrfield.com:1880/getLight";
$urlHumidity = "https://conrfield.com:1880/getHumidity";
 
 //This is required in order to avoid insecure http issues which somehow were an issue
 $arrContextOptions=array(
    "ssl"=>array(
        "verify_peer"=>false,
        "verify_peer_name"=>false,
    ),
);
//Use file_get_contents to GET the URL in question.
$temp = file_get_contents($urlTemp, false, stream_context_create($arrContextOptions)) . "°C";
$light = file_get_contents($urlLight, false, stream_context_create($arrContextOptions)) . " lux";
$humidity = file_get_contents($urlHumidity, false, stream_context_create($arrContextOptions)) . "%rH";
 
 //The getTemp etc returns with a semi-colon at the end, so this takes it out so I can have units
$temp = str_replace(';', '', $temp);
$light = str_replace(';', '', $light);
$humidity = str_replace(';', '', $humidity);

	if($temp!==";"){ //Only testing for temp being pretty null as shouldn't need to test all of them
	$msg =	"Hi, " . $name . "\nThe motion sensor was activated. Also as an update for the last recorded conditions:
			\nLight was " . $light . "\nTemperature was " . $temp . "\nHumidity was " . $humidity;
	}
	else{
	$msg =	"Hi, " . $name . "\nThe motion sensor was activated. \n The sensortag was off so there was no data to update you with";
	}	
	
	//Sends the email
	mail("$email","Motion sensor update",$msg);
	
}
?>