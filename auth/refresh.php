<pre>
<?php
	$apiData = array(
		'client_id' => 'XXX',
		'client_secret' => 'XXX',
		'grant_type' => 'refresh_token',
		'refresh_token' => 'XXX'
	);

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, 'https://www.googleapis.com/oauth2/v3/token');
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($apiData));
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json'));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$jsonData = curl_exec($ch);
	curl_close($ch);

	print_r($jsonData);

	// var_dump($jsonData);
	// $user = @json_decode($jsonData);
?>
</pre>
