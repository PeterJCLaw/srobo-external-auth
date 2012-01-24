<?php

require_once("lib/AuthProvider.php");

#
# This class provides support for authenticating against the SR IDE.

class IDEAuthProvider {

	static $UserData;
	private $IDE_URL;

	public function __construct($IDE_URL){
		if(file_exists("/tmp/ide-group-data")){
			self::$UserData = unserialize(file_get_contents("/tmp/ide-user-data"));
		}else{
			self::$UserData = array();
		}
		$this->IDE_URL = $IDE_URL;
	}

	public function CheckCredentials($username, $password){
		// Ask the IDE
		$cookieJar = tempnam("/tmp/", "sr_auth");
		$cURL = curl_init($this->IDE_URL . "control.php/auth/authenticate");
		curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($cURL, CURLOPT_POST, 1);
		curl_setopt($cURL, CURLOPT_COOKIEFILE, $cookieJar);
		curl_setopt($cURL, CURLOPT_COOKIEJAR, $cookieJar);
		$sendData = array("username" => $username, "password" => $password);
		curl_setopt($cURL, CURLOPT_POSTFIELDS, json_encode($sendData));
		$json = curl_exec($cURL);
		$data = json_decode($json);
		if(!isset($data->error)){
			curl_setopt($cURL, CURLOPT_URL, $this->IDE_URL . "control.php/user/info");
			$json = curl_exec($cURL);
			curl_close($cURL);
			$data = json_decode($json, true);
			self::$UserData[$username] = $data;
			$lockFile = fopen("/tmp/ide-user-data-lock", "w");
			flock($lockFile, LOCK_EX);
			fwrite($lockFile, "Locked by PID " . getmypid());
			file_put_contents("/tmp/ide-user-data", serialize(self::$UserData));
			fclose($lockFile);
			unlink($lockFile);
			unlink($cookieJar);
			return true;
		}
		curl_close($cURL);
		unlink($cookieJar);
		return false;
	}

	public function GetDisplayName($username){
		if(!isset(self::$UserData[$username])) return "";
		return self::$UserData[$username]["display-name"];
	}

	public function GetGroups($username){
		if(!isset(self::$UserData[$username])) return array();
		$groups = array();
		foreach(self::$UserData[$username]["teams"] as $id=>$name){
			$groups[] = "team-" . $id;
		}
		return $groups;
	}
}

?>
