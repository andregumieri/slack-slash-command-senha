<?php
	require 'config.php';
	require 'functions.php';
	require 'functions-slash.php';

	if($_POST['token']!=SLACK_TOKEN) die("Token do slack inválido");
	if($_POST['command']!="/senha") die("Comando do slack inválido");

	$busca = $_POST['text'];
	$regexBusca = explode(" ", $busca);
	$regexBuscaParcial = "/" . implode("|", $regexBusca) . "/i";
	$regexBusca = "/\b" . implode("\b|\b", $regexBusca) . "\b/i";
	$regexBuscaParcial = stripAccents($regexBuscaParcial);
	$regexBusca = stripAccents($regexBusca);

	$token = getToken();
	if(!$token) {
		echo "Token inválido ou não configurado. Configure em " . URLBASE . "/auth";
		die();
	}

	$pesos = array(
		"cliente" => 10,
		"url" => 5,
		"usuario" => 5,
		"senha" => 0,
		"ultimaatualizacao" => 0,
		"tipo" => 10
	);


	// Lista todas as planilhas
	// file_get_contents("https://spreadsheets.google.com/feeds/spreadsheets/private/full");

	// Lista todos os worksheets
	$worksheets = file_get_contents("https://spreadsheets.google.com/feeds/worksheets/" . SHEET_ID . "/private/full?access_token=$token");
	// echo $worksheets;
	$xml = simplexml_load_string($worksheets);
	$listUrl = false;
	// echo "<pre>";
	foreach($xml->entry->link as $link) {
		foreach($link->attributes() as $attr=>$val) {
			// echo "$attr => $val\n";
			if($attr=="rel" && preg_match("/#listfeed/", $val) && $listUrl===false) $listUrl = true;
			if($attr=="href" && $listUrl===true) $listUrl = $val;
		}
	}


	// Pega todas as linhas
	$linhas = file_get_contents($listUrl . "?access_token=$token");
	$xml = simplexml_load_string($linhas);
	// echo "<pre>";
	// print_r($xml);

	$encontrados = array();

	foreach($xml->entry as $entry) {
		$pontos = 0;
		// echo (string)$entry->content . "\n";
		$keys = content2array((string)$entry->content);
		$keys['tipo'] = (string)$entry->title;
		// echo $content . "\n";
		// var_dump($keys);

		foreach($keys as $key=>$val) {
			$peso = isset($pesos[$key]) ? $pesos[$key] : 0;
			preg_match_all($regexBusca, stripAccents($val), $matches);
			$pontos += count($matches[0])*$peso;

			preg_match_all($regexBuscaParcial, stripAccents($val), $matches);
			$pontos += count($matches[0])*($peso/4);
		}

		if($pontos>0) {
			$encontrados[] = array(
				"pontos" => $pontos,
				"data" => $keys
			);
		}



		// echo $entry->content;
		// preg_match_all("/" . str_replace(" ", "|", $busca) . "/", $entry->content, $matches);
		// print_r($matches);
	}

	// print_r($encontrados);
	function encontradosOrdem($a, $b) {
		if($a['pontos']==$b['pontos']) return 0;
		return($a['pontos']<$b['pontos']) ? 1 : -1;
	}
	usort($encontrados, "encontradosOrdem");


	if(empty($encontrados)) {
		echo "Ops! Nenhuma senha foi encontrada para a busca '" . $busca . "'";
		echo "\n\nAh, se você conseguir essa senha, cadastra na <https://docs.google.com/spreadsheets/d/" . SHEET_ID . "/edit|planilha>! ;)";
		die();
	}

	// Retorna para o slack
	echo count($encontrados) . " resultado(s) para sua busca por '{$busca}'. Exibindo os " . LIMITE_RESULTADOS . " mais relevantes:\n\n";
	$pontoMaximo = $encontrados[0]['pontos'];
	foreach($encontrados as $x=>$encontrado) {
		if($encontrado['pontos']>0 && $x<LIMITE_RESULTADOS) {
			if($x>0) echo "\n\n\n";
			echo "*[" . $encontrado['data']['tipo'] . "] " . $encontrado['data']['cliente'] . "*\n";
			echo ">URL: <" . $encontrado['data']['url'] . "|" . $encontrado['data']['url'] . ">\n";
			echo ">Usuário: " . $encontrado['data']['usuario'] . "\n";
			echo ">Senha: " . $encontrado['data']['senha'];
		}
	}

	if(count($encontrados)>LIMITE_RESULTADOS) {
		echo "\nO resultado que precisa não está aí? Experimente refinar sua busca! Ex.: ftp nome do cliente";
	}







	// Pega a URL da lista
	//
?>
