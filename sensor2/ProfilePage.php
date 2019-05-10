<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="author" content="Connor">
<title>Profile Page</title>
<link rel="stylesheet" href="style1.css" title="Mainly gray tbh" type="text/css" media="screen"/>
<!--link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"-->
  
<style>
<!--instead of including in the style sheet, this will be used for errors:   -->
.error{
	color:red;
}
</style>

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
  
  if($nameErr === "" & $emailErr === "" & $periodErr === ""){
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
		fopen('emaildata.json', 'w+');
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




<h1> 
	<a href="index.html" style="vertical-align: middle;">
		<img class="arrow imgGH" src="arrow-left.svg" alt="Back Arrow" >
	</a>
	Profile Page!
	
</h1>

<h2 style="margin-bottom:0.5em; margin-top:0.5em;">Want To Be Notified Upon Motion Detection?</h2>
<p style="margin-bottom:0.5em;margin-top:0.5em;">Just provide your name and email address, and then these will be used to notify you when motion has been detected in the greenhouse, 
and will also give you an update on what the temperature, light and humidity is of the greenhouse (using sensortag with ID <i>987bf3131f23</i>) at that time.
<br>Just fill in the wee form below, hit the button and then you'll be sent a confirmation email.<br><br>
If you have already filled in this form but made a typo, or want to use a different email address, just fill it in again and it'll
replace the details we had on file.</p>

<p class="margin">
<form class="margin" action='<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>'  method="post">
  Name:<br> <input type="text" style="margin:5px 0px;" name="name"  style="margin:1vw 0vw;" value="<?php echo $name; ?>" placeholder="Fred Bloggs">
  <span class="error">* <?php echo $nameErr;?></span>
  
  <br>
  Email Address to be notified:<br> <input type="text"  style="margin:1vw 0vw;" name="email" value="<?php echo $email; ?>" placeholder="fredB@example.com" >
  <span class="error">* <?php echo $emailErr;?></span>
  
  <br>
  <input type="submit" title="Once you're set to sign up, just hit here"
			name="submit1" style="margin:1vw 0vw;" value="Get notifying!">
</form>
</p>

<h2 style="margin-bottom:0.5em;margin-top:0;">Want To Stop Getting Emails? </h2>
<p style="margin-bottom:0.5em;margin-top:0.5em;">Just hit the button below and we'll get rid of the data we had saved.</p>
<form class="margin" action='<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>'   method="post">
  <input type="submit" title="Once you're set, just hit here and we'll get rid of your email address from our system"
			name="submit2" onclick='alert("You will no longer recieve notifications")' style="margin:1vw 0vw;" value="Remove Me!"> 
</form>
<p id="finished"></p>

</body>
</html>