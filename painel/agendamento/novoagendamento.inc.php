<?php

include_once __DIR__.'/../../includes/setup.inc.php';

if (isset($_POST['agendar'])) {

	$cliente = $_POST['cliente'];
	if (empty($_POST['cliente'])) {
		RespostaRetorno('agendamentocliente');
		return;
	} // dataagendamento

	$dataagendamento = explode('/',$_POST['dataagendamento']);
	if (empty($_POST['dataagendamento'])) {
		RespostaRetorno('dataagendamento');
		return;
	} // dataagendamento

	$horario = str_split($_POST['horario'],2);
	if (empty($_POST['horario'])) {
		RespostaRetorno('horaagendamento');
		return;
	} else {
		$agendamento = new DateTime($dataagendamento[2].'-'.$dataagendamento[1].'-'.$dataagendamento[0].' '.$horario[0].':'.$horario[1].':00');
	} // horario

	$competente = $_POST['competente']?:1;
	if (empty($_POST['competente'])) {
		RespostaRetorno('competenteagendamento');
		return;
	} // competente

	$agendamentos = new ConsultaDatabase($uid);
	$agendamentos = $agendamentos->AgendamentosCompetente($agendamento->format('Y-m-d H:i:s.u'),$competente);
	if ($agendamentos!=0) {
		RespostaRetorno('horaindisponivelagendamento');
		return;
	} // se o horário tá disponível pra esse competente

	$especificacao = $_POST['especificacao'];
	if (empty($_POST['especificacao'])) {
		RespostaRetorno('especificacaoagendamento');
		return;
	} // especificacao

	$agendando = "<div id='informacaoagendandowrap'><!-- informacao agendando wrap -->";
	$passe=0;
	$reserva=0;

	$vid = $_POST['competente'];
	// $competente = new ConsultaDatabase($uid);
	// $competente = $competente->competente($vid);

	$lid = $_POST['cliente'];
	if (empty($lid)) {
		RespostaRetorno('lid');
		return;
	} // lid
	$cliente = new ConsultaDatabase($uid);
	$cliente = $cliente->ClienteInfo($lid);

	// $possibilidade = new Conforto($uid);
	// $possibilidade = $possibilidade->AluguelPossivel($vid,$comeco,$conclusao);
	// if (count($possibilidade)>0) {
	// 	$dias_agendados = 'O(s) dia(s) ';
	// 	foreach ($possibilidade as $dia) {
	// 		$dia = new DateTime($dia);
	// 		$dia = $dia->format('d/m/Y');
	// 		$dias_agendados .= '<b>'.$dia.'</b>, ';
	// 	} // foreach
	// 	$dias_agendados = rtrim($dias_agendados,', ');
	// 	$dias_agendados .= ' já estão reservados para esse competente.<br>';
	// 	echo $dias_agendados;
	// 	return;
	// } // existem dias desejados nessa modificação que estão agendados por outra reserva

	if (empty($competente) || empty($lid) || empty($agendamento) ) {
		RespostaRetorno('vazio');
		return;
	} else {
		$encontraadmin = new ConsultaDatabase($uid);
		$encontraadmin = $encontraadmin->AdminInfo($uid);
		if ($encontraadmin!=0) {
			if ( ($encontraadmin['nivel']!=0) && ($encontraadmin['nivel']!=1) ) {
				// $authadmin = new ConsultaDatabase($uid);
				// $authadmin = $authadmin->AuthAdmin($encontraadmin['email'],$pwd);
				$authadmin = 1;

				if ($authadmin==0) {
					RespostaRetorno('authadmin');
					return;
				} else {
					$competenteEscolhido = new ConsultaDatabase($uid);
					$competenteEscolhido = $competenteEscolhido->Competente($competente);

					$agendando .= "
						<div id='agendandowrap' class='relatoriowrap'>
							<div class='slotrelatoriowrap'>
								<div class='slotrelatorio'>
									<p class='headerslotrelatorio'><b>Cliente:</b></p>
									<p class='infoslotrelatorio'>".$cliente['nome']."</p>
								</div>
							</div>
							<div class='slotrelatoriowrap'>
								<div class='slotrelatorio'>
									<p class='headerslotrelatorio'><b>Competente:</b></p>
									<p class='infoslotrelatorio'>".$competenteEscolhido['nome']."</p>
									<p class='headerslotrelatorio'><b>Agendamento:</b></p>
									<p class='infoslotrelatorio'>".$agendamento->format('d/m/y')." às ".$agendamento->format('H')."h".$agendamento->format('i')."</p>
								</div>
							</div>
							<div class='slotrelatoriowrap'>
								<div class='slotrelatorio'>
									<p class='headerslotrelatorio'><b>Especificação:</b></p>
									<p class='infoslotrelatorio'>".$especificacao."</p>
								</div>
							</div>
						</div>
					";
					$agendando .= "<div id='confirmaraluguel' class='confirmacao'>confirmar agendamento</div>";
					$agendando .= "<div id='voltaragendamento' class='confirmacao'>voltar</div>";
					$agendando .= "</div><!-- informacao agendando wrap -->";
					$agendando .= "
						<script>
							$('#voltaragendamento').on('click', function() {
								formulario.css('display', 'inline-block');
								$('#informacaoagendandowrap').html('');
							});
					";

					$agendando .= "
							$('#confirmaraluguel').on('click', function() {
								$.ajax({
									type: 'POST',
									dataType: 'html',
									async: true,
									url: '".$dominio."/painel/agendamento/agendamentoconfirmado.inc.php',
									data: {
										cliente: '".$cliente['lid']."',
										competente: '".$competente."',
										agendamento: '".$agendamento->format('Y-m-d')."T".$agendamento->format('H:i:s.u')."',
										especificacao: '".$especificacao."'
									},
									success: function(possivel) {
										if (possivel.includes('sucesso') == true) {
											retorno.html(possivel);
											retorno.append('<img id=\"sucessogif\" src=\"".$dominio."/img/sucesso.gif\">');
											calendarioSuperior($('#competentepainellinear').val(),".$agendamento->format('d').",".$agendamento->format('m').",".$agendamento->format('Y').");
											setTimeout(function() {
												mostraMenuTop();
										                $('#fechar').trigger('click');
										        }, 5000);

										} else {
											formulario.css('display', 'inline-block');
											$('#informacaoagendandowrap').html('');
										}
									}
								});
							});
					";

					$agendando .= '</script>';
				} // authadmin
			} else {
				RespostaRetorno('adminnivel');
				return;
			} // nivel
		} else {
			RespostaRetorno('adminencontrado');
			return;
		} // admin não encontrado
	} // campos preenchidos
} else {
	return false;
} // isset post submit

echo $agendando;

?>
