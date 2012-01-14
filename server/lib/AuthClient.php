<?php

class AuthClient {

	private $CONFIG = array();

	public function GetSetting($settingName){
		if(isset($this->CONFIG[$settingName])) return $this->CONFIG[$settingName];
		return null;
	}

	public function PutSetting($settingName, $value){
		$this->CONFIG[$settingName] = $value;
	}

	public function DoAuthentication($username, $password){
		$ap = $this->GetSetting("AuthProvider");
		if($ap->CheckCredentials($username, $password)){
			if($this->GetSetting("RequireGroup")){
				if($ap->RequireMembership($username, $this->GetSetting("RequireGroup"))){
					return true;
				}else{
					return false;
				}
			}else{
				return true;	
			}
		}else{
			return false;
		}
	}

	public function GetUserDisplayName($username){
		$ap = $this->GetSetting("AuthProvider");
		return $ap->GetDisplayName($username);
	}

	public function CreateToken(){
		return sha1(microtime());
	}

}

?>
