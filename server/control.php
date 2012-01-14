<?php

require_once("lib/ConfigManager.php");

session_start();

header("Cache-control: no-cache");
header("Pragma: no-cache");
header("Content-type: application/json");

$AuthClient = $_SESSION["Client"];

$handle = fopen('php://input','r');
$jsonInput = fgets($handle);
$decoded = json_decode($jsonInput,true);
fclose($handle);

$_POST = array_merge($_POST,$decoded);

$response = array();

switch($_SERVER["PATH_INFO"]){
	case "/auth/authenticate":
		if($AuthClient->DoAuthentication($_POST["username"], $_POST["password"])){
			$_SESSION["AuthToken"] = $AuthClient->CreateToken();
			$_SESSION["SSO_Username"] = $_POST["username"];
			$_SESSION["SSO_Password"] = $_POST["password"];
			$response["status"] = true;
			$response["next"] = "sso_postback.php";
		}else{
			$response["status"] = false;
			$response["error"] = array(1, "Invalid username or password");
		}
		break;
}

echo json_encode($response);
?>
