<?php

require_once("lib/AuthProvider.php");

#
# This class provides support for authenticating against the SR IDE.

class IDEAuthProvider {

	private $IDE_URL;

	public function __construct($IDE_URL){
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
			$_SESSION["user_data_cache"] = $data;
			unlink($cookieJar);
			return true;
		}
		curl_close($cURL);
		unlink($cookieJar);
		return false;
	}

	public function GetDisplayName($username){
		if(!isset($_SESSION["user_data_cache"])) return "";
		return $_SESSION["user_data_cache"]["display-name"];
	}

	public function GetGroups($username){
		if(!isset($_SESSION["user_data_cache"])) return array();
		$groups = array();
		foreach($_SESSION["user_data_cache"]["teams"] as $id=>$name){
			if( ($id > 1000) && (! in_array("mentors", $groups)) )
				$groups[] = "mentors";

			$groups[] = "team-" . $id;
		}
		return $groups;
	}

}
