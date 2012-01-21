<?php

$sso_url = 'http://my.url/server/';
$sso_priv_key_path = 'my_key';
$sso_key = file_get_contents($sso_priv_key_path);

require_once('../client/SSOClient.php');

try
{
	$client = new SSOClient($sso_url, $sso_key);
	$client->DoSSO();
	if (empty($_GET['logout']))
	{
		$data = $client::GetData();
		echo '<h3>Login successful.</h3>', PHP_EOL;
		echo 'Data: <pre>'; var_dump($data); echo '</pre>';
		echo '<br /><a href="?logout=1">Logout</a>', PHP_EOL;
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
