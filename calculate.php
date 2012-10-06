<?php

	header("Content-Type: application/json");

	$return = array();

	$text = mb_strtolower($_POST['text'], 'UTF-8');
	if (isset($_FILES['file']))
		$text = mb_strtolower(file_get_contents($_FILES['file']['tmp_name']), 'UTF-8');

	$lang = $_POST['lang'];
	$return['occurences'] = array();

	// remove everyhing except letters
	$text = preg_replace("/([^a-zčćžšđ]*)/", "", $text);

	$chrArray = getSymbols($text, $lang);
	$textLength = count($chrArray);
	for ($i = 0; $i < count($chrArray); $i++) {
		$currChar = $chrArray[$i];
		$return['occurences'][$currChar]++;
	}

	ksort($return['occurences']);

	$return['entropy'] = 0;
	foreach ($return['occurences'] as $key => $value) {
		$poss = $value / $textLength;
		$return['possibilites'][$key] = $poss;
		$return['entropy'] -= $poss * log10($poss) / log10(2);
	}

	echo json_encode($return);

	function getSymbols( $text, $lang = 'en' ) {

		$specialSymbols = array(
			'hr' => array(
				'nj',
				'lj'
			)
		);

		$symbols = array();
		if (isset($specialSymbols[$lang])) {
			$symbols = $specialSymbols[$lang];
			$text = str_replace(array_values($symbols), array_keys($symbols), $text);
		}

		$chrArray = preg_split('//u', $text, -1, PREG_SPLIT_NO_EMPTY);
		foreach ($chrArray as &$symbol) {
			if ( isset($symbols[$symbol]) )
				$symbol = $symbols[$symbol];
		} unset($symbol);

		return $chrArray;

	}

?>