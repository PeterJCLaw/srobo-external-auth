<?php
require_once("etc/config.inc.php");
require_once(LIB_DIR . "Portal.php");

if(Portal::get_policy() && isset($_GET["target"])){
	header("Location: " . $_GET["target"]);
	exit();
}else if(Portal::get_policy()){
	header("Location: splash.php");
	exit();
}

if(isset($_GET["target"])){
	$target = $_GET["target"];
	unset($_GET["target"]);
	if(count($_GET)!=0){
		$target.= "?";
		foreach($_GET as $key => $value)
			$target .= $key . "=" . urlencode($value);
	}
}

if(!isset($target)) $target = "splash.php";
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
	<script src="web/javascript/login.js" type="text/javascript"></script> 
	<title>Student Robotics Network Login</title> 
</head> 
<body id="login-back"> 
 
	<form id="login-box" method="POST" action="./authenticate.php"> 
		<strong>Student Robotics Network Login:</strong> 
		<em id="login-feedback">You must be logged in to use the network</em>
		<input type="hidden" name="next" id="next" value="<?php echo $target; ?>" />
		<input type="text" name="username" value="username" id="username"> 
		<input type="password" name="password" id="password"> 
		<button type="submit" id="login-button">Log In</button> 
		<br /> 
		<!--<a href="https://www.studentrobotics.org/forgotpassword/">&raquo; Forgotten password</a> -->
	</form> 
 
</body> 
</html> 

