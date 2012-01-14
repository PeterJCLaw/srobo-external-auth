<?php

require_once("lib/ConfigManager.php");

session_start();

$AuthClient = $_SESSION["Client"];

$USER_DISPLAY = $AuthClient->GetUserDisplayName($_SESSION["SSO_Username"]);
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
	<title>Student Robotics - Competition dashboard</title> 
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
		<form action="<?php echo $_SESSION["ReturnURL"]; ?>" method='POST'>
			<input type='hidden' name='sso_data' value="<?php echo $SSO_Data; ?>" />
			<div id='info'>
				You have now been logged in to Student Robotics.  Click the button below to return to the site you came from.
				Your single-sign-on session will continue for one hour, after which you will be prompted for a username and password again.
			</div>
			<input type='submit' value="Complete your logon" />
		</form>
	</div>
</body>
</html>
