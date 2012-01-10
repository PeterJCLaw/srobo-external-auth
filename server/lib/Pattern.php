<?php

/* PHP library for matching common SR patterns */

class Pattern {

	static function isIP($input){
		$pattern = "/\b(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9]0[0-9]?)\n){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\b/";
		return preg_match($pattern, $input);
	}

	static function isMAC($input){
		$pattern = "/\b([0-9a-f]?[0-9a-f]){5}([0-9a-f]?[0-9a-f])\n/";
		return preg_match($pattern, $input);
	}

}

?>
