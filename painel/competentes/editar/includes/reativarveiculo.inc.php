<?php

include_once __DIR__.'/../../../../includes/setup.inc.php';

if (isset($_POST['competente'])) {
	$vid = $_POST['competente'];
	$resultado = '';

	$competente = new ConsultaDatabase($uid);
	$competente = $competente->competente($vid);
	if ($competente['vid']!=0) {
		$addmodificacao = new UpdateRow();
		$addmodificacao = $addmodificacao->UpdatecompetenteAtivo('S',$vid);
		if ($addmodificacao===true) {
			$resultado = "
				<div style='min-width:100%;max-width:100%;display:inline-block;'>
					<p class='respostaalteracao'>
						competente reativado com sucesso.
					</p>
				</div>
			";
		} else {
			$resultado = "
				<div style='min-width:100%;max-width:100%;display:inline-block;'>
					<p class='respostaalteracao'>
						Erro ao ativar competente.
					</p>
					<script>
						setTimeout(function() {
							$('#fecharvestimenta').trigger('click');
						},5000);
					</script>
				</div>
			";
		} // addativacao true
	} // vid != 0
} else {
	$resultado = 0;
}// $_post

echo $resultado;
?>
