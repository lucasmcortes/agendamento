<?php

include_once __DIR__.'/../../../includes/setup.inc.php';

if (isset($_POST['cliente'])) {
		$lid = $_POST['cliente'];
		$telefone = $_POST['modificacao'];

		if (preg_match('/(\(\d{2}\)[ ]{1}\d{5}\-\d{4})/', $telefone, $telefone, PREG_UNMATCHED_AS_NULL)) {
			$telefone = $telefone[0];

			$cliente = new ConsultaDatabase($uid);
			$cliente = $cliente->clienteInfo($lid);

			if ($telefone!=$cliente['telefone']) {
				$modtel = new UpdateRow();
				$modtel = $modtel->UpdateclienteTelefone($telefone,$lid);
				if ($modtel===true) {
					$telefone = 'sucesso';
				} else {
					$telefone = 0;
				} // modtel true
			} else {
				$telefone = 0;
			} // != telefone
		} else {
			$telefone = 0;
		} // regex telefone
} else {
	$telefone = 0;
}// $_post

echo $telefone;
