<?php

class ConfigManager {

	private static $Clients = array();
	private static $Provider = NULL;

	public static function AddClient($clientObject){
		ConfigManager::$Clients[] = $clientObject;
	}

	public static function SetProvider($providerObject){
		ConfigManager::$Provider = $providerObject;
	}

	public static function GetClients(){ return ConfigManager::$Clients; }
	public static function GetProvider(){ return ConfigManager::$Provider; }

	public static function DetectClient($from){
		foreach(ConfigManager::GetClients() as $client){
			if(preg_match("|^" . $client->GetSetting("OriginURL") . "|", $from)) return $client;
		}
		return null;
	}

}

require_once("etc/config.inc.php");
