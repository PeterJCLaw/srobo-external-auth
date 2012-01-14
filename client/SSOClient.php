<?php

class SSONoTokenError extends Exception { }

class SSOClient {

	public static function DoSSO(){
		if(!defined("SR_SSO_URL")) die("SR_SSO_URL must be defined");
		if(!defined("SSO_PRIVKEY")) die("SSO_PRIVKEY must be defined");

		session_start();
		try{
			if(isset($_SESSION["sr_sso_token"])) return;

			// No token, and no post data.
			if(!isset($_POST["sso_data"]))
				throw new SSONoTokenError("No token and no post data");

			// SSO data is set, we may have a valid postback
			$SSO_Data = base64_decode($_POST["sso_data"]);
			$SSO_Data = Crypto::decryptPrivate($SSO_Data, SSO_PRIVKEY);
			$SSO_Data = json_decode($SSO_Data);
			if($SSO_Data == NULL) throw new SSONoTokenError("No valid data sent");

			$_SESSION["SSO_Data"] = $SSO_Data;

		}catch(SSONoTokenError $ex){
			header("Location: " . SR_SSO_URL . "?from=" . 
				urlencode( (isset($_SERVER["HTTPS"]) ? "https://" : "http://") . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]) 
			      );
			exit();
		}
	}

	public static function GetData(){ return $_SESSION["SSO_Data"]; }

}

### Wrapper around OpenSSL

class Crypto {


	public function encryptPublic($data, $pubKey){
		$outData = "";
		if(openssl_public_encrypt($data, $outData, openssl_pkey_get_public($pubKey))){
			return $outData;
		}
		return "";
	}

	public function encryptPrivate($data, $privKey){
		$outData = "";
		if(openssl_private_encrypt($data, $outData, openssl_pkey_get_private($privKey))){
			return $outData;
		}
		return "";
	}

	public function decryptPublic($data, $pubKey){
		$outData = "";
		if(openssl_public_decrypt($data, $outData, openssl_pkey_get_public($pubKey))){
			return $outData;
		}
		return "";
	}

	public function decryptPrivate($data, $privKey){
		$outData = "";
		if(openssl_private_decrypt($data, $outData, openssl_pkey_get_private($privKey))){
			return $outData;
		}
		return "";
	}

}

?>
