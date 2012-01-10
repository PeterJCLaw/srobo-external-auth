<?php

class PolicyException extends Exception {
	public function __construct(){
		parent::__construct("Access denied due to policy settings.");
	}
}

class AuthenticationException extends Exception {
	public function __construct(){
		parent::__construct("Invalid username or password.");
	}
}

class AlreadyAuthenticatedException extends Exception {
	public function __construct(){
		parent::__construct("Invalid username or password.");
	}
}
?>
