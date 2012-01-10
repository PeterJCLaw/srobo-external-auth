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
		while(!feof($userFile)){
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

}

?>
