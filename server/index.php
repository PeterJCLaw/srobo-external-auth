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
	<title>Student Robotics Login</title>
</head>
<body id="login-back">
	<form id="login-box" method="POST" action="sso.php">
		<strong>Student Robotics Authentication</strong>
<?php
if(@$_GET["clientURL"] == ""){
    $_GET["clientURL"] = "https://www.studentrobotics.org";
}
?>
		<em id="login-feedback">Use your IDE username and password to log in</em>
		<input type="hidden" name="clientURL" value="<?php echo $_GET["clientURL"]; ?>" id="clientURL">
		<input type="text" name="username" value="username" id="username">
		<input type="password" name="password" id="password">
		<button type="submit" id="login-button">Log In</button>
		<br />
		<!--<a href="https://www.studentrobotics.org/forgotpassword/">&raquo; Forgotten password</a> -->
	</form>

</body>
</html>
