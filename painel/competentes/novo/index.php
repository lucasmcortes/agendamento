<?php

	include_once __DIR__.'/../../../includes/setup.inc.php';

	if (isset($_SESSION['ag_id'])) {
		$permissao = new Conforto($uid);
		$permissao = $permissao->Permissao('registro');
		if ($permissao!==true) {
			redirectToLogin();
		} // permitido

		require_once __DIR__.'/../../../cabecalho.php';
		echo '<div class="conteudo">';
			require_once __DIR__.'/includes/competente-slot.inc.php';
		echo '</div> <!-- conteudo -->';
		require_once __DIR__.'/../../../rodape.php';
	} else {
		redirectToLogin();
	} // isset $uid

?>
