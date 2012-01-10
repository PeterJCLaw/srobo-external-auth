<?php

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
