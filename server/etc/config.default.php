<?php

#### COPY THIS FILE TO config.inc.php AND EDIT TO SUIT ####

### Authentication clients
$_CONFIG["Clients"] = array();

## To add an authentication client copy the line below and edit to suit
# $_CONFIG["Clients"][] = new AuthClient("http://my.url/", "PublicKeyHere", 
					 "AuthProvider");
## http://my.url/ should be replaced with the referer URL, and the Public Key 
## (created by genkeypair.php) should be pasted into "PublicKeyHere".
## AuthProvider must be one of:
##   none, file, ldap

?>
