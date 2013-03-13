<?php

### Wrapper around OpenSSL

class Crypto {


	public static function encryptPublic($data, $pubKey){
		$outData = "";
		if(openssl_public_encrypt($data, $outData, openssl_pkey_get_public($pubKey))){
			return $outData;
		}
		return "";
	}

	public static function encryptPrivate($data, $privKey){
		$outData = "";
		if(openssl_private_encrypt($data, $outData, openssl_pkey_get_private($privKey))){
			return $outData;
		}
		return "";
	}

	public static function decryptPublic($data, $pubKey){
		$outData = "";
		if(openssl_public_decrypt($data, $outData, openssl_pkey_get_public($pubKey))){
			return $outData;
		}
		return "";
	}

	public static function decryptPrivate($data, $privKey){
		$outData = "";
		if(openssl_private_decrypt($data, $outData, openssl_pkey_get_private($privKey))){
			return $outData;
		}
		return "";
	}
	
	/***
     * Signs a block of data
     * Params:
     *  $data - The data to be signed
     *  $privKey - The private key of the origin server
     * Returns:
     *  The signature, or empty string on error
     ***/
	public function sign($data, $privKey){
		$signature = "";
		if(openssl_sign($data, $signature, openssl_pkey_get_private($privKey))){
			return $signature;
		}
		return "";
	}

    /***
     * Check a digital signature
     * Params:
     *  $data - The data to be checked
     *  $signature - The provided signature
     *  $pubKey - The public key of the origin server
     * Returns:
     *  True if the signature is valid
     *  False if there was an error or the signature is invalid
     ***/
    public static function checkSignature($data, $signature, $pubKey){
        return (openssl_verify($data, $signature, openssl_pkey_get_public($pubKey)) == 1);
    }

}
