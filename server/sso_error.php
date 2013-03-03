<?php

require_once("lib/ConfigManager.php");

session_start();
if(!isset($_SESSION["SSO_Error"])){
	// Oops - no error!
	header("Location: index.php"); //Redirect back to the index.
}

if(!isset($_SESSION["Client"])){
	// Oops - no client class available
	header("Location: index.php"); //Redirect back to the index.
}

// Get hold of the client.
$AuthClient = $_SESSION["Client"];

// Get the display name through the client.
$USER_DISPLAY = $AuthClient->GetUserDisplayName($_SESSION["SSO_Username"]);

// Render an error page.
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
		<h2>Single-sign-on error</h2>
		<p>
			Unfortunately, an error occurred processing the request.  Details of the error are below:
			<pre class='code'><?php
					echo $_SESSION["SSO_Error"]->__toString();
				?></pre>
			Please contact one of the Student Robotics team about this error and we will investigate.  Please include the error details above.
		</p>
	</div>
</body>
</html>
