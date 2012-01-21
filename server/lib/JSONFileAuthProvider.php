<?php

require_once("lib/AuthProvider.php");

class JSONFileAuthProvider extends AuthProvider {

	private $usersFile;
	private $groupsFile;

	private $usersData = null;
	private $groupsData = null;

	public function __construct($basePath = "etc/"){
		$this->usersFile = $basePath . "users.json";
		$this->groupFile = $basePath . "groups.json";
	}

	private function getUsersData()
	{
		if ($this->usersData === null && file_exists($this->usersFile))
		{
			$this->usersData = json_decode(file_get_contents($this->usersFile));
		}
		return $this->usersData;
	}

	private function getGroupsData()
	{
		if ($this->groupsData === null && file_exists($this->groupsFile))
		{
			$this->groupsData = json_decode(file_get_contents($this->groupsFile));
		}
		return $this->groupsData;
	}

	public function CheckCredentials($username, $password){
		$data = $this->getUsersData();
		if (isset($data->$username))
		{
			return $data->$username->password === sha1($password);
		}
		return false;
	}

	public function RequireMembership($username, $groupName){
		return false;
	}

	public function GetDisplayName($username){
		$data = $this->getUsersData();
		if (isset($data->$username))
		{
			$user = (array)$data->$username;
			return $user['display-name'];
		}
		return null;
	}

	public function GetGroups($username){
		$groups = array();
		$data = $this->getGroupsData();
		foreach ((array)$data as $group => $members)
		{
			if (in_array($username, $members))
			{
				$groups[] = $group;
			}
		}
		return $groups;
	}

}
