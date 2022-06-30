<?php

include_once __DIR__.'/../../../../includes/setup.inc.php';

if (isset($_POST['cliente'])) {
	$lid = $_POST['cliente'];
	$opcoes = [];
	$string_resultado = '';
	$placas_disponiveis = [];

	$cliente = new ConsultaDatabase($uid);
	$cliente = $cliente->clienteInfo($lid);
	if ($cliente['lid']!=0) {
		($cliente['associado']=='S') ? $associado = 'Desde '.strftime('%d de %B de %Y', strtotime($cliente['data_associado'])) : $associado = 'Não';
		$string_resultado .= '
			<span id="clienteresultspan" data-lid="'.$cliente['lid'].'" data-associado="'.$cliente['associado'].'">
				<b>'.$cliente['nome'].'</b>
				<br>
		';

		if ($associado!='Não') {
			$placas = new ConsultaDatabase($uid);
			$placas = $placas->Placas($cliente['lid']);
			$placas_string = '';
			if (count($placas)>1) {
				$plural_placas = 's';
			} else {
				$plural_placas = '';
			} // plural placas
			$string_resultado .= '<b>Placa'.$plural_placas.': </b>';
			foreach ($placas as $placa) {
				$ativa = new ConsultaDatabase($uid);
				$ativa = $ativa->PlacaAtiva($placa['pid']);
				if ( ($placa['data']>=$cliente['data_associado']) && ($ativa['ativa']==1)) {
					if (!in_array($ativa['pid'],$opcoes)) {
						// vê quantas cortesias a placa tem
						$cortesias = new Conforto($uid);
						$cortesias = $cortesias->Cortesias($placa['pid']);
						$placa += ['cortesias_disponiveis' => $cortesias['cortesias_disponiveis'] ?? 0];

						if (!in_array($placa, $placas_disponiveis)) {
							$placas_disponiveis[] = $placa;
						} // placa nova
						$placas_string .= $placa['placa'].', ';
					} // se ainda não é uma opção
					$opcoes[] = $ativa['pid'];
				} // if placa dessa associatividade
			} // foreach placa
			$placas_disponiveis = array_values(array_unique($placas_disponiveis, SORT_REGULAR));
			$string_resultado .= rtrim($placas_string, ', ');
			$string_resultado .= '<br>';
		} // associado

		$telefone = new Conforto($uid);
		$telefone = $telefone->FormatoTelefone($cliente['telefone'],'br');

		$string_resultado .= '
				<b>CPF:</b> '.$cliente['documento'].'
				<br>
				<b>RG:</b> '.$cliente['rg'].'
				<br>
				<b>Telefone:</b> '.$telefone.'
				<br>
				<b>Email:</b> '.$cliente['email'].'
				<br>
				<b>Observação:</b> '.$cliente['observacao'].'
			</span>
		';
	} else {
		$string_resultado .= 'cliente não encontrado. <span id="addcliente" style="cursor:pointer;text-decoration:underline;">Adicionar</span>';
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
