<?php

class ConfigManager {

	private static $Provider = NULL;
	private static $PubKey = "";
	private static $PrivKey = "";

	public static function SetProvider($providerObject){
		ConfigManager::$Provider = $providerObject;
	}

    public static function SetKeys($priv_key, $pub_key){
        ConfigManager::$PubKey = $pub_key;
        ConfigManager::$PrivKey = $priv_key;
    }

    public static function GetPrivateKey(){ return ConfigManager::$PrivKey; }
    public static function GetPublicKey(){ return ConfigManager::$PubKey; }
	public static function GetProvider(){ return ConfigManager::$Provider; }

}

require_once("etc/config.inc.php");
