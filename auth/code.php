<?php
	require("../config.php");
	require("../functions.php");
	if(!$_GET['code']) die("erro");
	$code = $_GET['code'];

	$apiData = array(
		'client_id' => CLIENT_ID,
		'client_secret' => CLIENT_SECRET,
		'grant_type' => 'authorization_code',
		'redirect_uri' => URLBASE . '/auth/code.php',
		'code' => $code
	);

	$jsonData = postToken($apiData);
	$jsonData = json_decode($jsonData);

	file_put_contents(__DIR__.'/refresh_token.txt', $jsonData->refresh_token);

	echo "Autenticado";
?>
