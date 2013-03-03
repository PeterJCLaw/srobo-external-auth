<?php

class SSONoTokenError extends Exception { }

class SSOClient {

	private $url = null;
	private $private_key = null;
	private $session_key = null;

	const POST_KEY = 'sso_data';	// the key the server uses to post data at us.

	/**
	 * Create a new SSOClient.
	 * @param sso_url: The url of the SSO srever.
	 * @param sso_private_key: The private key for this client.
	 * @param sso_public_key: The public key for this client.
	 */
	public function __construct($sso_url, $sso_private_key, $sso_public_key){
		$this->url = $sso_url;
		$this->private_key = $sso_private_key;
		$this->public_key = $sso_public_key;

		if(empty($this->url)) die("The SSO server's url must be provided");
		if(empty($this->private_key)) die("This client's private key must be defined");
		if(empty($this->public_key)) die("This client's public key must be defined");

		$this->session_key = 'SSO-Data-'.sha1($this->url.$this->private_key);
	}

	/**
	 * Helper function that wraps a simple usage of this client.
	 * Assumes that you either want to login, or are on the postback.
	 */
	public function DoSSO(){
		// No token, and no post data.
		if(!$this->IsPostback()){
			$this->RedirectToLoginPage();
			return null;
		}

		$SSO_Data = $this->HandlePostback();
		return $SSO_Data;
	}

	public function IsPostback(){
		return isset($_POST[self::POST_KEY]);
	}

	public function HandlePostback(){
		// SSO data is set, we may have a valid postback
		$SSO_Data = base64_decode($_POST[self::POST_KEY]);
		$SSO_Data = Crypto::decryptPrivate($SSO_Data, $this->private_key);
		$SSO_Data = json_decode($SSO_Data);
		if($SSO_Data == NULL) throw new SSONoTokenError("No valid data sent");

		$this->saveData($SSO_Data);
		return $SSO_Data;
	}

	public function RedirectToLoginPage($postbackURL = null){
		if($postbackURL === null){
			// default to the current page
			$postbackURL = 'http'.(empty($_SERVER['HTTPS'])?'':'s').'://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
		}
		header("Location: " . $this->url . "?clientURL=" . urlencode($postbackURL) . "&clientKey=" . urlencode(Crypto::StripKeyHeaders($this->public_key)));
		exit();
	}

	/**
	 * If you'd like to use a different mechanism to save the data returned
	 * create a derived class and override this, clearData & GetData.
	 */
	protected function saveData($SSO_Data){
		session_start();
		$_SESSION[$this->session_key] = $SSO_Data;
	}

	/**
	 * If you'd like to use a different mechanism to save the data returned
	 * create a derived class and override this, saveData & GetData.
	 */
	protected function clearData(){
		unset($_SESSION[$this->session_key]);
	}

	public function GetData(){ return $_SESSION[$this->session_key]; }

	public function Logout(){
		$this->clearData();
		header('Location: ' . $this->url . '/control.php/auth/deauthenticate');
		exit();
	}

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

	public static function StripKeyHeaders($inKeyText){
		$out = "";
		foreach(split("\n", $inKeyText) as $line){
			if($line == "-----BEGIN PUBLIC KEY-----"){ $inKey = true; continue; }
			if($line == "-----END PUBLIC KEY-----"){ $inKey = false; continue; }
			if($inKey) $out .= trim($line);
		}
		return $out;
	}

}
