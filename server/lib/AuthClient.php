<?php

require_once("lib/Crypto.php");
require_once("lib/ConfigManager.php");

class NoPublicKeyException extends Exception { }

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
		$ap = ConfigManager::GetProvider();
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
		$ap = ConfigManager::GetProvider();
		return $ap->GetDisplayName($username);
	}

	public function CreateToken(){
		return sha1(microtime());
	}

	/*
	Function: GetSSOData
	Parameters:
		None
	Returns:
		A crypted string of all the user data as needed.
		This string is encrypted using the public key of the AuthClient
	*/
	public function GetSSOData($username){
		if($this->GetSetting("PublicKey")){
            $key = $this->GetSetting("PublicKey");
            $key = "-----BEGIN PUBLIC KEY-----\n" . chunk_split($key, 64, "\n") . "-----END PUBLIC KEY-----";
			$ap = ConfigManager::GetProvider();
			$USER_DATA = array(
						"groups" => $ap->GetGroups($username),
						"username" => $username,
						"displayName" => $this->GetUserDisplayName($username),
					);
			return base64_encode(Crypto::encryptPublic(json_encode($USER_DATA), $key));
		}else{
			throw new NoPublicKeyException("No public key was given for the AuthClient in use.");
		}
	}

}
