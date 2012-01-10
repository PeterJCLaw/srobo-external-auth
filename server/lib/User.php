<?php

define("IDE_URL", "https://www.studentrobotics.org/ide/");
define("LDAP_QUERY_URL", "https://www.studentrobotics.org/~cmalton/ldap-query/");

class SRUser{

	static function authenticate($username, $password){
		// Ask the IDE
		$cookieJar = tempnam("/tmp/", "sr_auth");
		$cURL = curl_init(IDE_URL . "control.php/auth/authenticate");
		curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($cURL, CURLOPT_POST, 1);
		curl_setopt($cURL, CURLOPT_COOKIEFILE, $cookieJar);
		curl_setopt($cURL, CURLOPT_COOKIEJAR, $cookieJar);
		$sendData = array("username" => $username, "password" => $password);
		curl_setopt($cURL, CURLOPT_POSTFIELDS, json_encode($sendData));
		$json = curl_exec($cURL);
		$data = json_decode($json);
		if(!isset($data->error)){
			curl_setopt($cURL, CURLOPT_URL, IDE_URL . "control.php/user/info");
			$json = curl_exec($cURL);
			curl_close($cURL);
			$data = json_decode($json, true);
			$db = DB::get();
			$stmt = $db->prepare("REPLACE INTO user_detail (username, displayName, contactEmail,groups) VALUES (:username, :displayname, :email, :groups)");
			$stmt->bindParam(":username", $username);
			$stmt->bindParam(":displayname", $data["display-name"]);
			$stmt->bindParam(":email", $data["email"]);
			$teams = json_encode($data["teams"]);
			$stmt->bindParam(":groups", $teams);
			$stmt->execute();
			unlink($cookieJar);
			return true;
		}
		curl_close($cURL);
		unlink($cookieJar);
		return false;
	}

	static function isMemberMulti($username, $groups){
		foreach($groups as $group){
			if(SRUser::isMember($username, $group)) return true;
		}
		return false;
	}

	static function isMember($username, $group){
		// Ask the website
		$data = file_get_contents(LDAP_QUERY_URL . "memberof.php?user=$username&group=$group");
		$data = json_decode($data);
		if($data->status != "OK") return false;
		return $data->result;
	}

	static function getGroups($username){
		$db = DB::get();
		$stmt = $db->prepare("SELECT groups FROM user_detail WHERE username = :username");
		$stmt->bindParam(":username", $username);
		$stmt->execute();
		if($stmt->rowCount()){
			return json_decode($stmt->fetchObject()->groups, true);
		}
		return array();
	}

	static function fromUsername($username){
		$db = DB::get();
		$stmt = $db->prepare("SELECT * FROM user_detail WHERE username = :username");
		$stmt->bindParam(":username", $username);
		$stmt->execute();
		return $stmt->fetchObject("SRUser");
	}

}
?>
