<?php

include_once __DIR__.'/../../../includes/setup.inc.php';

$permissao = new Conforto($uid);
$permissao = $permissao->Permissao('registro');
if ($permissao!==true) {
	return;
} // permitido

if (isset($_POST['modificacao'])) {
		$vid = $_POST['competente'];
		$portas = $_POST['modificacao'];

		$competente = new ConsultaDatabase($uid);
		$competente = $competente->competente($vid);

		if ( ($competente['portas']!=$portas) && ($portas!=0) ) {
			$vportasmod = new setRow();
			$vportasmod = $vportasmod->Vobs($uid,$vid,$portas,$competente['completo'],$competente['caracterizado'],$competente['revisao'],$competente['observacao'],$data);

			if ($vportasmod===true) {
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
