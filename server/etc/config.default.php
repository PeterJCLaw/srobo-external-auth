<?php
require_once("lib/AuthClient.php");
require_once("lib/JSONFileAuthProvider.php");
#### COPY THIS FILE TO config.inc.php AND EDIT TO SUIT ####

## Set up some session stuff
ini_set("session.save_path", dirname(__FILE__) . "/../sessions/");
ini_set("session.gc_maxlifetime", 3600); //1 hour session time

## To add an authentication client copy the line below and edit to suit
## http://my.url/ should be replaced with the referer URL, and the Public Key
## (created by genkeypair.php) should be pasted into "PublicKeyHere".
## AuthProvider an instance of an auth provider
$client = new AuthClient();
$client->PutSetting("OriginURL", "http://my.url/");
$client->PutSetting("LoggedOutURL", "http://my.url/logged_out.php");
$client->PutSetting("PublicKey", file_get_contents("etc/keys/my_pub_key"));
$client->PutSetting("SSO_Title", "Log in name");
$client->PutSetting("SSO_Subtext", "Log in sub text here");
ConfigManager::AddClient($client);

ConfigManager::AddProvider(new FileAuthProvider());
