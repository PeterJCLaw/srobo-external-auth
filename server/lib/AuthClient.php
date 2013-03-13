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

    private function _GetSSOData($username){
    	$ap = ConfigManager::GetProvider();
		$USER_DATA = array(
				"groups" => $ap->GetGroups($username),
				"username" => $username,
				"displayName" => $this->GetUserDisplayName($username),
			);
        return $USER_DATA;
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
		if(ConfigManager::GetPrivateKey() != ""){
			$key = ConfigManager::GetPrivateKey();
			return base64_encode(Crypto::encryptPrivate(json_encode($this->_GetSSOData($username)), $key));
		}else{
			throw new NoPublicKeyException("No private key was given for the server to use.");
		}
	}
	
	/*
	Function: GetSSOSignature
	Parameters:
		None
	Returns:
		A crypted string of all the user data as needed.
		This string is encrypted using the public key of the AuthClient
	*/
	public function GetSSOSignature($username){
		if(ConfigManager::GetPrivateKey() != ""){
			$key = ConfigManager::GetPrivateKey();
			return base64_encode(Crypto::sign(json_encode($this->_GetSSOData()), $key));
		}else{
			throw new NoPublicKeyException("No private key was given for the server to use.");
		}
	}

}
