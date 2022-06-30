<?php

include_once __DIR__.'/setup.inc.php';

if (isset($_POST['competente'])) {
	$vid = $_POST['competente'];

	$card = new Cards($uid);
	$card = $card->Cardcompetente($vid);

} else {
	echo ':((';
}// $_post

?>
