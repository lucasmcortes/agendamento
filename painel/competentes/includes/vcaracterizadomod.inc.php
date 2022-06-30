<?php

include_once __DIR__.'/../../../includes/setup.inc.php';

$permissao = new Conforto($uid);
$permissao = $permissao->Permissao('registro');
if ($permissao!==true) {
	return;
} // permitido

if (isset($_POST['modificacao'])) {
		$vid = $_POST['competente'];
		$caracterizado = $_POST['modificacao'];

		$competente = new ConsultaDatabase($uid);
		$competente = $competente->competente($vid);

		if ( ($competente['caracterizado']!=$caracterizado) && ($caracterizado!=0) ) {

			$vcaracterizadomod = new setRow();
			$vcaracterizadomod = $vcaracterizadomod->Vobs($uid,$vid,$competente['portas'],$competente['completo'],$caracterizado,$competente['revisao'],$competente['observacao'],$data);

			if ($vcaracterizadomod===true) {
				$mod = 'sucesso';
			} else {
				$mod = 0;
			} // vcaracterizadomod true
		} else {
			$mod = 0;
		} // caracterizado diferente

} else {
	$mod = 0;
}// $_post

echo $mod;
