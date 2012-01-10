<?php

abstract class AuthProvider {

	public abstract function CheckCredentials($username, $password);
	public abstract function RequireMembership($username, $group);

}

?>
