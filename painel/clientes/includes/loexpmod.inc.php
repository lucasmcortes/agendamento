<?php

include_once __DIR__.'/../../../includes/setup.inc.php';

if (isset($_POST['cliente'])) {
		$lid = $_POST['cliente'];
		$o_exp = $_POST['modificacao'];

		$cliente = new ConsultaDatabase($uid);
		$cliente = $cliente->ClienteInfo($lid);

		if ($o_exp!=$cliente['o_exp']) {
			$modvalidade = new UpdateRow();
			$modvalidade = $modvalidade->UpdateOrgaoExpeditor($o_exp,$cliente['rg']);
			if ($modvalidade===true) {
				$validade = 'sucesso';
			} else {
				$validade = 0;
			} // modvalidade true
		} else {
			$validade = 0;
		} // != validade
} else {
	$validade = 0;
}// $_post

echo $validade;
