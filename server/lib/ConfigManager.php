<?php


class ConfigManager {

	private static $Clients = array();

	public static function AddClient($clientObject){
		ConfigManager::$Clients[] = $clientObject;
	}

	public static function GetClients(){ return ConfigManager::$Clients; }

	public static function DetectClient($from){
		foreach(ConfigManager::GetClients() as $client){
			if(preg_match("|^" . $client->GetSetting("OriginURL") . "|", $from)) return $client;
		}
		return null;
	}

}

require_once("etc/config.inc.php");
?>
