<?php

include_once __DIR__.'/../../../includes/setup.inc.php';

$permissao = new Conforto($uid);
$permissao = $permissao->Permissao('registro');
if ($permissao!==true) {
	return;
} // permitido

if (isset($_POST['modificacao'])) {
		$vid = $_POST['competente'];
		$modelo = $_POST['modificacao'];

		$competente = new ConsultaDatabase($uid);
		$competente = $competente->competente($vid);

		if ($competente['modelo']!=$modelo) {

			$vmodelomod = new UpdateRow();
			$vmodelomod = $vmodelomod->UpdatecompetenteModelo($modelo,$vid);

			if ($vmodelomod===true) {
				$mod = 'sucesso';
			} else {
				$mod = 0;
			} // true
		} else {
			$mod = 0;
		} // diferente

} else {
	$mod = 0;
}// $_post

echo $mod;
