<?php

include_once __DIR__.'/../../../includes/setup.inc.php';

if (isset($_POST['cliente'])) {
	$lid = $_POST['cliente'];
	$opcoes = [];
	$string_resultado = '';
	$placas_disponiveis = [];

	$cliente = new ConsultaDatabase($uid);
	$cliente = $cliente->ClienteInfo($lid);
	if ($cliente['lid']!=0) {
		$string_resultado .= '
			<span id="clienteresultspan" data-lid="'.$cliente['lid'].'">
				<b>'.$cliente['nome'].'</b>
				<br>
		';

		$telefone = new Conforto($uid);
		$telefone = $telefone->FormatoTelefone($cliente['telefone'],'br');

		$string_resultado .= '
				<b>CPF:</b> '.$cliente['documento'].'
				<br>
				<b>RG:</b> '.$cliente['rg'].' '.$cliente['o_exp'].'
				<br>
				<b>Telefone:</b> '.$telefone.'
				<br>
				<b>Email:</b> '.$cliente['email'].'
		';

		if ($cliente['observacao']!=0) {
			$string_resultado .= '
				<br>
				<b>Observação:</b> '.$cliente['observacao'].'
			';
		} // se tem uma observacao

		$string_resultado .= '
			</span>
		';
	} else {
		$string_resultado .= 'Cliente não encontrado. <span id="addcliente" style="cursor:pointer;text-decoration:underline;">Adicionar</span>';
		$string_resultado .= "
					<script>
						$('#addcliente').on('click',function () {
							window.location.href='".$dominio."/painel/clientes/novo/'
						});
					</script>
		";
	}
} else {
	$string_resultado .= ':((';
}// $_post

$resultado = array(
	'resposta'=>$string_resultado,
	'placas'=>$placas_disponiveis
);

header('Content-Type: application/json;');
echo json_encode($resultado, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PARTIAL_OUTPUT_ON_ERROR);

?>
