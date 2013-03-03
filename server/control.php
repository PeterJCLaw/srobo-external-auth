<?php

require_once("lib/ConfigManager.php");

session_start();

header("Cache-control: no-cache");
header("Pragma: no-cache");
header("Content-type: application/json");

$handle = fopen('php://input','r');
$jsonInput = fgets($handle);
$decoded = json_decode($jsonInput,true);
fclose($handle);

$_POST = array_merge($_POST,$decoded);

$AuthClient = new AuthClient();
$AuthClient->PutSetting("ClientURL", $_POST["clientURL"]);
$AuthClient->PutSetting("PublicKey", $_POST["clientKey"]);
$response = array();

switch($_SERVER["PATH_INFO"]){
	case "/auth/authenticate":
		if($AuthClient->DoAuthentication($_POST["username"], $_POST["password"])){
			$response["status"] = true;
			$response["next"] = "sso_postback.php?clientURL=" . urlencode($_POST["clientURL"]) . "&clientKey=" . urlencode($_POST["clientKey"]) . "&SSO_Username=" . $_POST["username"] . "&AuthToken=" . $AuthClient->CreateToken();
		}else{
			$response["status"] = false;
			$response["error"] = array(1, "Invalid username or password");
		}
		break;
	case "/auth/deauthenticate":
		session_destroy();
		header("Location: " . $AuthClient->GetSetting("LoggedOutURL"));
}

echo json_encode($response);
