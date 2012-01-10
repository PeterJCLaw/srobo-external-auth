<?php

class AuthClient {

	private $CONFIG = array();

	public function GetSetting($settingName){
		if(isset($this->CONFIG[$settingName])) return $this->CONFIG[$settingName];
		return null;
	}

	public function PutSetting($settingName, $value){
		$this->CONFIG[$settingName] = $value;
	}

}

?>
