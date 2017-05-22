<?php
	function postToken($apiData) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'https://www.googleapis.com/oauth2/v3/token');
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($apiData));
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json'));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$result = curl_exec($ch);
		curl_close($ch);
		return $result;
	}

	function getToken() {
		$TOKEN_FILE = __DIR__.'/auth/refresh_token.txt';
		if(!file_exists($TOKEN_FILE)) return false;
		$refresh_token = file_get_contents($TOKEN_FILE);

		$apiData = array(
			'client_id' => CLIENT_ID,
			'client_secret' => CLIENT_SECRET,
			'grant_type' => 'refresh_token',
			'refresh_token' => $refresh_token
		);

		$jsonData = postToken($apiData);
		$jsonData = json_decode($jsonData);

		return $jsonData->access_token;
	}
?>
