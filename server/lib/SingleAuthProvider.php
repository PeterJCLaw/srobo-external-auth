<?php

require_once("lib/AuthProvider.php");

class SingleAuthProvider extends AuthProvider {

	public function CheckCredentials($username, $password)
	{
		return !empty($username) && !empty($password);
	}

	public function RequireMembership($username, $groupName)
	{
		return false;
	}

	public function GetDisplayName($username)
	{
		return $username;
	}

	public function GetGroups($username)
	{
		return array();
	}

}
