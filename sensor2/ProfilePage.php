<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
    <meta name="mobile-web-app-capable" content="yes"/>
    <meta name="apple-mobile-web-app-capable" content="yes"/>
    <meta name="author" content="Connor">
    <title>Profile</title>

    <!--My own style sheet:            -->
    <link rel="stylesheet" href="style1.css" title="My stylesheet" type="text/css" media="screen"/>

    <!--The below are added because I couldn't get the header background colours to reach the sides of the screen:          -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>

<!-- Sets up error handling stuff -->
<?php
// define variables and set to empty values
$nameErr = $emailErr = $email2Err = "";
$name = $email = $email2 = "";


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //If the first button is pressed:
    if (isset($_POST["submit1"])) {
        if (empty($_POST["name"])) {
            $nameErr = "Name is required";
        } else {
            $name = test_input($_POST["name"]);
            $nameErr = "";
            // check if name only contains letters and whitespace
            if (!preg_match("/^[a-zA-Z ]*$/", $name)) {
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


    }

    if ($nameErr === "" & $emailErr === "") {
        $userData = new \stdClass(); //Not having this means it doesnt comply with E_STRICT standards as the pbject would be created from null
        $userData->name = $name; //Adds key name and value $name
        $userData->email = $email; //Adds key email and value $email
        $myJSON = json_encode($userData); //makes the userData string stuff into more of a JSON object

        if (json_decode($myJSON) != null) {
            $file = fopen('emaildata.json', 'w+'); //deletes any file with that name if one exists, and creates a new file with that name
            fwrite($file, $myJSON); //Writes the myJSON to the file just created
            fclose($file); //Closes the file so it is no longer in use
        }
        confirmationEmail($email, $emailErr);
    }

} //If the second button was pressed:
elseif (isset($_POST["submit2"])) {
    fopen('emaildata.json', 'w+'); //Opens the file if it exists but deletes its content, otherwise creates a new file from scratch
}


function test_input($data)
{
    $data = trim($data); //Takes out multiple spare spaces, tabs and newlines
    $data = stripslashes($data); //Takes out back slashes
    $data = htmlspecialchars($data); //Changes stuff that looks like html ('<', '>') to funky & stuff to protecc
    return $data;
}

function confirmationEmail($email, $emailErr)
{
    //send message:
    $msg = "Welcome!\nYou are now registered to recieve emails when motion has been detected within the greenhouse.";
    //send email:
    if ($emailErr == "") {
        mail("$email", "Registration", $msg);
    }
}

?>

<h1>
    <a href="index.html" style="vertical-align:">
        <img class="arrow imgGH" src="img/arrow-left.svg" alt="Back Arrow">
    </a>
    Profile Page!

</h1>

<h2 style="margin-bottom:0.5em; margin-top:0.5em;">Want To Be Notified Upon Motion Detection?</h2>
<p style="margin-bottom:0.5em;margin-top:0.5em;">Just provide your name and email address, and then these will be used
    to notify you when motion has been detected in the greenhouse,
    and will also give you an update on what the temperature, light and humidity is of the greenhouse (using sensortag
    with ID <i>987bf3131f23</i>) at that time.
    <br><br>Just fill in the wee form below, hit the button and then you'll be sent a confirmation email.<br>
    If you have already filled in this form but made a typo, or want to use a different email address, just fill it in
    again and it'll
    replace the details we had on file.</p>

<p class="noUpDownMargin">
<form class="margin" style="margin-top:0vw;margin-bottom:0vw;"
      action='<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>' method="post">
    Name:<br> <input type="text" style="margin:5px 0px;" name="name" style="margin:1vw 0vw;"
                     value="<?php echo $name; ?>" placeholder="Fred Bloggs">
    <span style="color:red">* <?php echo $nameErr; ?></span>

    <br>
    Email Address to be notified:<br> <input type="text" style="margin:1vw 0vw;" name="email"
                                             value="<?php echo $email; ?>" placeholder="fredB@example.com">
    <span style="color:red">* <?php echo $emailErr; ?></span>

    <br>
    <input type="submit" title="Once you're set to sign up, just hit here"
           name="submit1" style="margin-bottom:0" value="Get notifying!">
</form>
</p>

<h2 style="margin-bottom:0.5em;margin-top:0;">Want To Stop Getting Emails? </h2>
<p style="margin-bottom:0.5em;margin-top:0.5em;">Just hit the button below and we'll get rid of the data we had
    saved.</p>
<form class="margin" action='<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>' style="margin-top:0vw;"
      method="post">
    <input type="submit" title="Once you're set, just hit here and we'll get rid of your email address from our system"
           name="submit2" onclick='alert("You will no longer recieve notifications")' style="margin-top:0vw;"
           value="Remove Me!">
</form>
<p>
<div id="finished"></div>
</p>

</body>
</html>