<?php

include_once __DIR__.'/../../../includes/setup.inc.php';

$permissao = new Conforto($uid);
$permissao = $permissao->Permissao('registro');
if ($permissao!==true) {
	return;
} // permitido

if (isset($_POST['modificacao'])) {
		$vid = $_POST['competente'];
		$chassi = str_replace(' ','',$_POST['modificacao']);

		$competente = new ConsultaDatabase($uid);
		$competente = $competente->competente($vid);

		if ($competente['chassi']!=$chassi) {

			$vchassimod = new UpdateRow();
			$vchassimod = $vchassimod->UpdatecompetenteChassi($chassi,$vid);

			if ($vchassimod===true) {
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
