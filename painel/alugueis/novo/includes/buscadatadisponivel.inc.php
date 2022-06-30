<?php

include_once __DIR__.'/../../../../includes/setup.inc.php';

if (isset($_POST['inicio'])) {
	$hoje = $agora->format('Y-m-d H');
	$hora = $agora->format('H:i:s.u');

	$inicio_string = $_POST['inicio'].' '.$hora;
	$devolucao_string = $_POST['devolucao'].' '.$hora;
	$data_inicio = new DateTime($inicio_string);
	$data_devolucao = new DateTime($devolucao_string);

	$competentes_disponiveis = [];

	$competentes = new ConsultaDatabase($uid);
	$competentes = $competentes->Listacompetentes();
	if ($competentes[0]['vid']!=0) {
		foreach ($competentes as $competente) {
			if ($competente['ativo']=='S') {
				$possibilidade = new Conforto($uid);
				$possibilidade = $possibilidade->DiasDesejados($competente['vid'],$data_inicio,$data_devolucao);
				if (count($possibilidade)==0) {
					$competentes_disponiveis[] = array(
						'vid'=>$competente['vid'],
						'modelo'=>$competente['modelo']
					);
				} // competente com período disponível
			} // ativo
		} // foreach competente
	} // existe competente

	$resultado = array(
		'competentes'=>$competentes_disponiveis,
		'quantidade_de_competentes'=>count(($competentes_disponiveis)),
		'inicio'=>$inicio_string,
		'devolucao'=>$devolucao_string
	);
} else {
	$resultado = ':((';
}// $_post

header('Content-Type: application/json;');
echo json_encode($resultado, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PARTIAL_OUTPUT_ON_ERROR);

?>
