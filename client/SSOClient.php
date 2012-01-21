<?php

class SSONoTokenError extends Exception { }

class SSOClient {

	private $url = null;
	private $private_key = null;

	/**
	 * Create a new SSOClient.
	 * @param sso_url: The url of the SSO srever.
	 * @param sso_private_key: The private key for this client.
	 */
	public function __construct($sso_url, $sso_private_key){
		$this->url = $sso_url;
		$this->private_key = $sso_private_key;

		if(empty($this->url)) die("The SSO server's url must be provided");
		if(empty($this->private_key)) die("This client's private key must be defined");
	}

	public function DoSSO(){
		session_start();
		if(isset($_SESSION["sr_sso_token"])) return;

		// No token, and no post data.
		if(!isset($_POST["sso_data"])){
			$this->redirect();
			return;
		}

		// SSO data is set, we may have a valid postback
		$SSO_Data = base64_decode($_POST["sso_data"]);
		$SSO_Data = Crypto::decryptPrivate($SSO_Data, $this->private_key);
		$SSO_Data = json_decode($SSO_Data);
		if($SSO_Data == NULL) throw new SSONoTokenError("No valid data sent");

		$_SESSION["SSO_Data"] = $SSO_Data;
	}

	private function redirect(){
		header("Location: " . $this->url . "?from=" .
			urlencode( (isset($_SERVER["HTTPS"]) ? "https://" : "http://") . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"])
			  );
		exit();
	}

	public function GetData(){ return $_SESSION["SSO_Data"]; }

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
