<?php
	define('SHEET_ID', 'XXX');
	define('CLIENT_ID', 'xxx');
	define('CLIENT_SECRET', 'XXX');
	define('SLACK_TOKEN', 'XXX');
	define('LIMITE_RESULTADOS', 5);

	if(preg_match("/localhost/", $_SERVER['HTTP_HOST'])) {
		define('URLBASE', 'http://localhost:8888/gsheet-search-pass');
	} else {
		define('URLBASE', 'http://site.com.br/gsheet-search-pass');
	}
?>
