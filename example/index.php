<?php

$sso_url = dirname($_SERVER["PHP_SELF"]) . '/../server/';
$sso_priv_key_path = 'example_keys/example';
$sso_key = file_get_contents($sso_priv_key_path);
$sso_key_pub = file_get_contents($sso_priv_key_path . ".pub");

require_once('../client/SSOClient.php');

try
{
	$client = new SSOClient($sso_url, $sso_key, $sso_key_pub);
	$client->DoSSO();
	if (empty($_GET['logout']))
	{
		$data = $client->GetData();
		echo '<h3>Login successful.</h3>', PHP_EOL;
		echo 'Data: <pre>'; var_dump($data); echo '</pre>';
		echo '<br />', PHP_EOL;
	}
	else
	{
		$client->Logout();
	}
}
catch (Exception $ex)
{
	echo '<h3>Login failed.</h3>', PHP_EOL;
	echo 'Exception: <pre>'; var_dump($ex); echo '</pre>';
}
