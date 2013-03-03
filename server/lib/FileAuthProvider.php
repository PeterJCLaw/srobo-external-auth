<?php

require_once("lib/AuthProvider.php");

class FileAuthProvider extends AuthProvider {

	private $usersFile;
	private $groupsFile;

	public function __construct($basePath = "etc/"){
		$this->usersFile = $basePath . "users";
		$this->groupFile = $basePath . "groups";
	}

	public function CheckCredentials($username, $password){
		$userFile = fopen($this->usersFile, "r");
		while($userFile !== false && !feof($userFile)){
			$userLine = trim(fgets($userFile));
			$userLine = explode(":", $userLine);
			$userLineUsername = $userLine[0];
			$userLinePassHash = $userLine[1];
			if( ($userLineUsername == $username) && ($userLinePassHash == sha1($password) ) ){
				return true;
			}
		}
		fclose($userFile);
		return false;
	}

	public function RequireMembership($username, $groupName){
		return false;
	}

	public function GetDisplayName($username){
		$userFile = fopen($this->usersFile, "r");
		while($userFile !== false && !feof($userFile)){
			$userLine = trim(fgets($userFile));
			$userLine = explode(":", $userLine);
			$userLineUsername = $userLine[0];
			if($userLineUsername == $username)
				return $userLine[2];
		}
		fclose($userFile);
		return null;
	}

	public function GetGroups($username){
		$groups = array();
		$groupFile = fopen($this->groupFile, "r");
		while($groupFile !== false && !feof($groupFile)){
			$groupLine = trim(fgets($groupFile));
			$groupLine = explode(":", $groupLine);
			$groupLineGroupName = $groupLine[0];
			$groupLineGroupUsers = split(",", $groupLine[1]);
			if(in_array($username, $groupLineGroupUsers))
				$groups[] = $groupLineGroupName;
		}
		fclose($userFile);
		return $groups;
	}

}
