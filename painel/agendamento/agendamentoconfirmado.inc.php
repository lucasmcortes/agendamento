<?php

include_once __DIR__.'/../../includes/setup.inc.php';

if (isset($_POST['cliente'])) {

	$cliente = $_POST['cliente'];
	$competente = $_POST['competente'];
	$agendamento = $_POST['agendamento'];
	$especificacao = $_POST['especificacao'];

	$guid = mb_strtoupper(Guid());
	// do {
	// 	$guid = mb_strtoupper(Guid());
	// } while ($guid==$agendamentoinfo['guid']);

	$addagendamento = new setRow();
	$addagendamento = $addagendamento->Agendamento($guid,$uid,$cliente,$competente,$agendamento,$especificacao,1,$data);
	if ($addagendamento===true) {
		$agendamento = new ConsultaDatabase($uid);
		$agendamento = $agendamento->AgendamentoAdicionado($data);

		$agendamentoinfo = new ConsultaDatabase($uid);
		$agendamentoinfo = $agendamentoinfo->AgendamentoInfo($competente,$agendamento['agendamento']);

		// manda cartinha, confirma
		RespostaRetorno('sucessoagendamento');
		return;
	} else {
		RespostaRetorno('regagendamento');
		return;
	} // addagendamento true
} else {
	$alugando = ':((';
} // isset post submit

echo $alugando;

?>
