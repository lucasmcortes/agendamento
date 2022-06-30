<?php

include_once __DIR__.'/../../../includes/setup.inc.php';
$permissao = new Conforto($uid);
$permissao = $permissao->Permissao('registro');
if ($permissao!==true) {
	return;
} // permitido
if (isset($_POST['observacao'])) {
		$vid = $_POST['competente'];
		$observacao = $_POST['observacao'];

		$competente = new ConsultaDatabase($uid);
		$competente = $competente->Competente($vid);

		if ($competente['observacao']!=$observacao) {
			$vobservacaomod = new setRow();
			$vobservacaomod = $vobservacaomod->Cobs($uid,$vid,$observacao,$data);

			if ($vobservacaomod===true) {
				$mod = 'sucesso';
			} else {
				$mod = $vobservacaomod;
			} // vcaracterizadomod true
		} else {
			$mod = 0;
		} // observacao diferente

} else {
	$mod = 0;
}// $_post

echo $mod;
