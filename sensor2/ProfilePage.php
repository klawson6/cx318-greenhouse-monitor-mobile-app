<!DOCTYPE html>
<html>
<head>
<title>Profile Page</title>
<link rel="stylesheet" href="style1.css" title="Mainly gray tbh"
    type="text/css" media="screen"/>


<style>
.error{
	color:red;
}
</style>
<!--
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.3/css/font-awesome.min.css">
-->
</head>
<body>

<!-- Sets up error handling stuff -->
<?php
// define variables and set to empty values
$nameErr = $emailErr = $periodErr = $email2Err = "";
$name = $email = $period =$email2 = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	if(isset($_POST["submit1"])){
		 if (empty($_POST["name"])) {
    $nameErr = "Name is required";
  } else {
    $name = test_input($_POST["name"]);
	$nameErr = "";
    // check if name only contains letters and whitespace
    if (!preg_match("/^[a-zA-Z ]*$/",$name)) {
      $nameErr = "Only letters and white space allowed"; 
    }
  }

  if (empty($_POST["email"])) {
    $emailErr = "Email is required";
  } else {
    $email = test_input($_POST["email"]);
	$emailErr = "";
    // check if e-mail address is well-formed
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $emailErr = "Invalid email format"; 
    }
  }

  if (empty($_POST["period"])) {
    $periodErr = "Times to be notified are required";
  } else {
    $period = test_input($_POST["period"]);
	$periodErr = "";
	if (!preg_match("/^[0-9 ]*$/",$period)) {
      $periodErr = "Only letters and white space allowed"; 
    }
  }
  
  if($nameErr === "" & $emailErr === "" & $periodErr === "" & $name!=="" & $email!=="" & $period!==""){
	 $userData = new \stdClass(); //Not having this means it doesnt comply with E_STRICT standards as the pbject would be created from null
		$userData->name = $name;
		$userData->email = $email;
		$userData->interval = $period;
		$myJSON = json_encode($userData);
		
		if(json_decode($myJSON)!=null){
		$file = fopen('emaildata.json', 'w+');
		
		fwrite($file, $myJSON);
		fclose($file);
		echo "triiiedddd";
		}
		else{
			echo "wooooooooooppppssss";
		}
		echo "Hi, " . $name . "<br>";
		echo $email . " will be sent a notification every " . $period . " hours";
	}
	else{
		echo "There were issues with your details so couldn't send you notifications";
	}
	
	
	
	}
	elseif(isset($_POST["submit2"])){
		if (empty($_POST["email2"])) {
    $email2Err = "Email is required";
  } else {
    $email2 = test_input($_POST["email2"]);
	$email2Err = "";
    // check if e-mail address is well-formed
    if (!filter_var($email2, FILTER_VALIDATE_EMAIL)) {
      $email2Err = "Invalid email format"; 
    }
  }
	}
	else{
		echo "god knows";
	}
 

  confirmationEmail($email, $emailErr);
  }
  function test_input($data) {
  $data = trim($data); //Takes out multiple spare spaces, tabs and newlines
  $data = stripslashes($data); //Takes out back slashes
  $data = htmlspecialchars($data); //Changes stuff that looks like html ('<', '>') to funky & stuff to protecc
  return $data;
}
function confirmationEmail($email, $emailErr){
// the message
$msg = "You're now registered, woo!!!";

// use wordwrap() if lines are longer than 70 characters
//$msg = wordwrap($msg,70);

// send email
if($emailErr == ""){
mail("$email","Registration",$msg);
}
}
?>




<h1 class="clearfix"> 
	<a href="index.html">
		<img src="arrow-left.svg" alt="Back Arrow" style="width:42px;height:90px;border:0;float:left;">
	</a>
	Profile Page!!
	<img class="imgGH" src="greenhouse.png" alt="Just wanted a greenhouse image" >
</h1>

<!-- <hr></hr> -->
<h2>Want To Be Notified Periodically?</h2>
<p>Multiple email addresses can be added, and these will all be used in order to send you a notification when motion has been detected in the greenhouse, 
and will also give you an update on what the temperature,light and humidity of the greenhouse (using sensortag with ID <b>987bf3131f23</b>)</p>
<p>Just fill in the wee form below, hit the button and maybe magic will happen</p>
<form action='<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>' style="margin:15px 30px;" method="post">
  Name:<br> <input type="text" style="margin:5px 0px;" name="name" value="<?php echo $name; ?>">
  <span class="error">* <?php echo $nameErr;?></span>
  <br><br>
  
  Email Address to be notified:<br> <input type="text" style="margin:5px 0px;" name="email" value="<?php echo $email; ?>" >
  <span class="error">* <?php echo $emailErr;?></span>
  <br><br>
  
  How often would you like to be notified? (in hours)<br> <input type="text" style="margin:5px 0px;" name="period" value="<?php echo $period; ?>" > 
  <span class="error">* <?php echo $periodErr;?></span>
  <br><br>
  <input type="submit" title="Once you're set, just hit here and we'll send you intermediate emails"
			name="submit1" style="margin:5px 0px;" value="Get notifying!">
</form>
<br>
<h2>Want To Stop Getting Emails? </h2>
<p>Just type in your email address and if we have it, we'll remove it from our system once you hit the button</p>
<form action='<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>' style="margin:15px 30px;" method="post">
  Email Address that was registered:<br> <input type="text" style="margin:5px 0px;" name="email2" value="<?php echo $email2; ?>" >
  <span class="error">* <?php echo $email2Err;?></span>
  <br><br>
  <input type="submit" title="Once you're set, just hit here and we'll get rid of that email address from our system"
			name="submit2" style="margin:5px 0px;" value="Remove Me!"> <!--onclick='alert("Ooh got a cheeky click")'-->
</form>
<p id="yo"></p>
<p>To go back <b><i>Home</i></b>, hit the Left Arrow which is clearly not labelled because I'm lazy</p>
<br><p style="margin:15px;"><b>
<!-- Some Error scripty stuff: -->

</b></p>
</body>
</html>