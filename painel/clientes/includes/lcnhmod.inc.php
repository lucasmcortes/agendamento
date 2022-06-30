<?php

include_once __DIR__.'/../../../includes/setup.inc.php';

if (isset($_POST['cliente'])) {
		$lid = $_POST['cliente'];
		$rg = $_POST['modificacao'];

		$cliente = new ConsultaDatabase($uid);
		$cliente = $cliente->clienteInfo($lid);

		if ($rg!=$cliente['rg']) {
			$modrg = new UpdateRow();
			$modrg = $modrg->Updatedocumento($rg,$lid);
			if ($modrg===true) {
				$rg = 'sucesso';
			} else {
				$rg = 0;
			} // modrg true
		} else {
			$rg = 0;
		} // != rg
} else {
	$rg = 0;
}// $_post

echo $rg;
