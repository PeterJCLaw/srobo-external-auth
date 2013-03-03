<?php

require_once("lib/ConfigManager.php");
session_start();

if(!isset($_GET["SSO_Username"])){
	// user not logged in yet
	header("Location: index.php"); //Redirect back to the index.
}

$AuthClient = new AuthClient();
$AuthClient->PutSetting("ClientURL", $_GET["clientURL"]);
$AuthClient->PutSetting("PublicKey", $_GET["clientKey"]);

// Get the display name through the client.
$USER_DISPLAY = $AuthClient->GetUserDisplayName($_GET["SSO_Username"]);
try{
	// Get the SSO data to pass to the remote site.
	$SSO_Data = $AuthClient->GetSSOData($_GET["SSO_Username"]);
}catch(Exception $ex){
	$_SESSION["SSO_Error"] = $ex;
	header("Location: sso_error.php");
}

// Render a "We'll transfer you when you click this button"
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
	<!-- Style Sheets -->
	<link rel="stylesheet" href="web/css/newstyle.css" type="text/css">
	<!-- Javascript Source Files -->
	<script src="web/javascript/base64.js" type="text/javascript"></script>
	<script src="web/javascript/json2.js" type="text/javascript"></script>
	<script src="web/javascript/ide.js" type="text/javascript"></script>
	<script src="web/javascript/MochiKit.js" type="text/javascript"></script>
	<script src="web/javascript/status.js" type="text/javascript"></script>
	<script src="web/javascript/dashboard.js" type="text/javascript"></script>
	<title>Student Robotics Login Successful</title>
</head>
<body>
	<div id="top">
		<ul id="topleft">
			<li><a href="control.php/auth/deauthenticate" id="logout-button">Logout</a></li>
			<li id="teaminfo">Welcome, <?php echo $USER_DISPLAY; ?></li>
		</ul>
		<div id="static-box"><img src="web/images/static.png" alt="logo"></div>
	</div>
	<div id='page'>
		<p>
			<form action="<?php echo $AuthClient->GetSetting("ClientURL"); ?>" method='POST'>
				<input type='hidden' name='sso_data' value="<?php echo $SSO_Data; ?>" />
				<div id='info'>
					<p>You have now been logged in to Student Robotics.  Click the button below to return to the site you came from.</p>
					<p>When you click the button below, you may recieve a warning about posting data to a remote site.
					This is to be expected - because it's <b>exactly</b> what you are trying to do!</p>
				</div>
				<input type='submit' value="Complete your logon" />
			</form>
		</p>
	</div>
</body>
</html>
