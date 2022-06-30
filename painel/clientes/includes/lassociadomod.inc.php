<?php

include_once __DIR__.'/../../../includes/setup.inc.php';

if (isset($_POST['cliente'])) {
	$lid = $_POST['cliente'];
	$associado = $_POST['associado'];

	$cliente = new ConsultaDatabase($uid);
	$cliente = $cliente->clienteInfo($lid);

	if ($associado!=$cliente['associado']) {
		$modassociado = new setRow();
		$modassociado = $modassociado->Associado($uid,$lid,$associado,$data);
		if ($modassociado===true) {
			$associado = 'sucesso';
		} else {
			$associado = 0;
		} // modemail true
	} else {
		$associado = 0;
	} // != email
} else {
	$associado = 0;
}// $_post

echo $associado;
