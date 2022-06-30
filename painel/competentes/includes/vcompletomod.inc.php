<?php

include_once __DIR__.'/../../../includes/setup.inc.php';
$permissao = new Conforto($uid);
$permissao = $permissao->Permissao('registro');
if ($permissao!==true) {
	return;
} // permitido
if (isset($_POST['modificacao'])) {
		$vid = $_POST['competente'];
		$completo = $_POST['modificacao'];

		$competente = new ConsultaDatabase($uid);
		$competente = $competente->competente($vid);

		if ( ($competente['completo']!=$completo) && ($completo!=0) ) {
			$vcompletomod = new setRow();
			$vcompletomod = $vcompletomod->Vobs($uid,$vid,$competente['portas'],$completo,$competente['caracterizado'],$competente['revisao'],$competente['observacao'],$data);
			if ($vcompletomod===true) {
				$mod = 'sucesso';
			} else {
				$mod = 0;
			} // vcaracterizadomod true
		} else {
			$mod = 0;
		} // completo diferente

} else {
	$mod = 0;
}// $_post

echo $mod;
