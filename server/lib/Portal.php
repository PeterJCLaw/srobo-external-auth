<?php

require_once(LIB_DIR . "DB.php");

class Portal {

	/*
	 * my_mac
	 * Gets the current user's MAC address
	 * Parameters:
	 *  None
	 * Returns:
	 *  The client's MAC
	 */
	public static function my_mac(){
		return trim(shell_exec("/usr/sbin/arp -n | egrep \"^" . $_SERVER["REMOTE_ADDR"] . "\\s\" | awk '{print \$3;}'"));
	}

	/*
	 * get_policy
	 * Gets the policy applied for $mac.
	 * Parameters:
	 *  $mac - MAC address to check.  If null, use current MAC.
	 * Returns:
	 *  boolean - True if client is allowed through the firewall, False if not.
	 */
	public static function get_policy($mac = null) {
		if($mac == null) $mac = Portal::my_mac();
		$isAlreadyAuthed = trim(shell_exec("sudo /opt/SR/bin/portal/status $mac"));
		return (preg_match("/ authenticated/i", $isAlreadyAuthed) ? true : false);
	}


	/*
	 * set_policy
	 * Sets the policy applied for $mac.
	 * Parameters:
	 *  $permitted - Boolean - True to permit, False to deny.
	 *  $mac - MAC address to check.  If null, use current MAC.
	 * Returns:
	 *  boolean - True if client is allowed through the firewall, False if not.
	 */
	public static function set_policy($permitted, $mac = null) {
		if($mac == null) $mac = Portal::my_mac();
		if( $permitted ){
			shell_exec("sudo /opt/SR/bin/portal/authenticate $mac");
		}else{
			shell_exec("sudo /opt/SR/bin/portal/deauthenticate $mac");
		}
	}

	/*
	 * get_user
	 * Gets the username for $mac.
	 * Parameters:
	 *  $mac - MAC address to get data for
	 * Returns:
	 *  string - Username of the bound user.
	 */
	public static function get_user($mac = null){
		if($mac == null) $mac = Portal::my_mac();
		$database = DB::get();
		$query = $database->prepare("SELECT user FROM mac_user WHERE mac = :mac ORDER BY timestamp DESC LIMIT 1"); //Most recently logged on user.
		$query->bindParam(":mac", $mac);
		$query->execute();
		$result = $query->fetchObject();
		if($result){
			return $result->user;
		}else{
			return null;
		}
	}

	/*
	 * get_user
	 * Gets the username for $mac.
	 * Parameters:
	 *  $mac - MAC address to get data for
	 * Returns:
	 *  string - Username of the bound user.
	 */
	public static function set_user($username, $mac = null){
		if($mac == null) $mac = Portal::my_mac();
		$database = DB::get();
		$query = $database->prepare("INSERT INTO mac_user (timestamp, mac, user) VALUES (NOW(), :mac, :user)");
		$query->bindParam(":mac", $mac);
		$query->bindParam(":user", $username);
		$query->execute();
	}
}

?>
