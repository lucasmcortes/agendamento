<?php

include_once __DIR__.'/../../../includes/setup.inc.php';

$permissao = new Conforto($uid);
$permissao = $permissao->Permissao('registro');
if ($permissao!==true) {
	return;
} // permitido

if (isset($_POST['modificacao'])) {
		$vid = $_POST['competente'];
		$ano = $_POST['modificacao'];

		$competente = new ConsultaDatabase($uid);
		$competente = $competente->competente($vid);

		if ($competente['ano']!=$ano) {

			$vanomod = new UpdateRow();
			$vanomod = $vanomod->UpdatecompetenteAno($ano,$vid);

			if ($vanomod===true) {
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
