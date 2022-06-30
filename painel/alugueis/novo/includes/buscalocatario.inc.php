<?php

include_once __DIR__.'/../../../../includes/setup.inc.php';

if (isset($_POST['cliente'])) {
	$opcoes = [];
	$informacoes = '';
	$placas_disponiveis = [];

	$termo = '%'.$_POST['cliente'].'%';
	$clientes = new ConsultaDatabase($uid);
	$clientes = $clientes->Buscacliente($termo);
	if ($clientes[0]['lid']!=0) {
		foreach ($clientes as $cliente) {

			$telefone = new Conforto($uid);
			$telefone = $telefone->FormatoTelefone($cliente['telefone'],'br');

			($cliente['associado']=='S') ? $associado = 'Desde '.strftime('%d de %B de %Y', strtotime($cliente['data_associado'])) : $associado = 'Não';
			$div = "
				<div id='clienteswrap_".$cliente['lid']."' class='relatoriowrap opcaocliente'>
					<div class='slotrelatoriowrap'>
						<div class='slotrelatorio'>
							<p class='headerslotrelatorio'><b>Nome:</b></p>
							<p class='infoslotrelatorio'>".$cliente['nome']."</p>
						</div>
					</div>
					<div class='slotrelatoriowrap'>
						<div class='slotrelatorio'>
							<p class='headerslotrelatorio'><b>Telefone:</b></p>
							<p class='infoslotrelatorio'>".$telefone."</p>
							<!-- <p class='headerslotrelatorio'><b>CPF:</b></p>
							<p class='infoslotrelatorio'>".$cliente['documento']."</p>
							<p class='headerslotrelatorio'><b>RG:</b></p>
							<p class='infoslotrelatorio'>".$cliente['rg']."</p> -->
						</div>
					</div>
					<div class='slotrelatoriowrap'>
						<div class='slotrelatorio'>
							<p class='headerslotrelatorio'><b>Email:</b></p>
							<p class='infoslotrelatorio'>".$cliente['email']."</p>
							<!-- <p class='headerslotrelatorio'><b>Associado:</b></p>
							<p class='infoslotrelatorio'>".$associado."</p> -->
						</div>
					</div>
				</div>
			";
			$encontrados[] = array(
				'nome'=>$cliente['nome'],
				'lid'=>$cliente['lid'],
				'div'=>$div
			);

		} // foreach
	} else {
		$encontrados = 'cliente não encontrado. <span id="addcliente" style="cursor:pointer;text-decoration:underline;">Adicionar</span>';
		$encontrados .= "
					<script>
						$('#addcliente').on('click',function () {
							window.location.href='".$dominio."/painel/clientes/novo/'
						});
					</script>
		";;
	}



	$resultado = $encontrados;

	header('Content-Type: application/json;');
	echo json_encode($resultado, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PARTIAL_OUTPUT_ON_ERROR);


} // $_post

?>
