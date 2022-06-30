<?php

include_once __DIR__.'/../../../includes/setup.inc.php';

$permissao = new Conforto($uid);
$permissao = $permissao->Permissao('registro');
if ($permissao!==true) {
	return;
} // permitido

if (isset($_POST['modificacao'])) {
		$vid = $_POST['competente'];
		$potencia = $_POST['modificacao'];

		$competente = new ConsultaDatabase($uid);
		$competente = $competente->competente($vid);

		if ($competente['potencia']!=$potencia) {

			$vpotenciamod = new UpdateRow();
			$vpotenciamod = $vpotenciamod->UpdatecompetentePotencia($potencia,$vid);

			if ($vpotenciamod===true) {
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
