<?php
	require("../config.php");
	$url = "https://accounts.google.com/o/oauth2/auth";
	$url .= "?response_type=code";
	$url .= "&client_id=" . CLIENT_ID;
	$url .= "&redirect_uri=" . URLBASE . "/auth/code.php";
	$url .= "&scope=email%20profile%20https://spreadsheets.google.com/feeds";
	$url .= "&access_type=offline";
	$url .= "&approval_prompt=force";


?><!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
	<a href="<?php echo $url; ?>">Autenticar</a>
</body>
</html>