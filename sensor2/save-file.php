<?php
	function confirmationEmail($email){
	//create message:
	$msg = "Welcome!\nYou are now registered to recieve emails when motion has been detected within the greenhouse.";
	
	//send email:
	 mail("$email","Registration",$msg);
	
  }

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	
	$name = strip_tags(isset($_POST["name"]) ? $_POST["name"] : ""); //Used to take away possible cross site scripting
	$email = strip_tags(isset($_POST["email"]) ? $_POST["email"] : ""); //Used to take away possible cross site scripting
	
	 $userData = new \stdClass(); //Not having this means it doesnt comply with E_STRICT standards as the pbject would be created from null
		$userData->name = $name; //Adds key name and value $name 
		$userData->email = $email; //Adds key email and value $email
		$myJSON = json_encode($userData); //makes the userData string stuff into more of a JSON object
		
		if(json_decode($myJSON)!=null){
			$file = fopen('emaildata.json', 'w+'); //deletes any file with that name if one exists, and creates a new file with that name
			fwrite($file, $myJSON); //Writes the myJSON to the file just created
			fclose($file); //Closes the file so it is no longer in use
		}
	
	
	confirmationEmail($email); 
   
	 header("Location: https://devweb2018.cis.strath.ac.uk/~srb16177/ProfilePage.php"); //Brings user back to where they started before form submission
 
}
?>