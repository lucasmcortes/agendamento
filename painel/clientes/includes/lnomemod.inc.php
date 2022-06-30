<?php

include_once __DIR__.'/../../../includes/setup.inc.php';

if (isset($_POST['cliente'])) {
		$lid = $_POST['cliente'];

		$cliente = new ConsultaDatabase($uid);
		$cliente = $cliente->clienteInfo($lid);

		$nome = $_POST['modificacao']?:$cliente['nome'];

		if ($nome!=$cliente['nome']) {
			$modnome = new UpdateRow();
			$modnome = $modnome->UpdateclienteNome($nome,$lid);
			if ($modnome===true) {
				$nome = 'sucesso';
			} else {
				$nome = 0;
			} // modnome true
		} else {
			$nome = 0;
		} // != nome
} else {
	$nome = 0;
}// $_post

echo $nome;
