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
	 */
	public function __construct($sso_url, $sso_private_key, $sso_public_key){
		$this->url = $sso_url;
		$this->private_key = $sso_private_key;
		$this->public_key = $sso_public_key;

		if(empty($this->url)) die("The SSO server's url must be provided");
		if(empty($this->private_key)) die("This client's private key must be defined");
		if(empty($this->public_key)) die("This client's private key must be defined");

		$this->session_key = 'SSO-Data-'.sha1($this->url.$this->private_key);
	}

	public function DoSSO(){
		session_start();

		// No token, and no post data.
		if(!isset($_POST[self::POST_KEY])){
			$this->redirect($_GET["from"]);
			return;
		}

		// SSO data is set, we may have a valid postback
		$SSO_Data = base64_decode($_POST[self::POST_KEY]);
		$SSO_Data = Crypto::decryptPrivate($SSO_Data, $this->private_key);
		$SSO_Data = json_decode($SSO_Data);
		if($SSO_Data == NULL) throw new SSONoTokenError("No valid data sent");

		$_SESSION[$this->session_key] = $SSO_Data;
		return $SSO_Data;
	}

	private function redirect($originURL){
		header("Location: " . $this->url . "?clientURL=" . urlencode($_SERVER["PHP_SELF"]) . "&clientKey=" . urlencode(Crypto::StripKeyHeaders($this->public_key)));
		exit();
	}

	public function GetData(){ return $_SESSION[$this->session_key]; }

	public function Logout(){
		unset($_SESSION[$this->session_key]);
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

?>
