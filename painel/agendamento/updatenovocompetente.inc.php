<?php

include_once __DIR__.'/../../includes/setup.inc.php';

if (isset($_POST['aid'])) {
		$aid = $_POST['aid'];
		$horario = str_split($_POST['horarioagendamento']);
		$competente = $_POST['competente'];
		$dataagendamento = explode('/',$_POST['dataagendamento']);

		$horarioAgendamento = $dataagendamento[2].'-'.$dataagendamento[1].'-'.$dataagendamento[0].' '.$horario[0].$horario[1].':'.$horario[2].$horario[3].':0.000000';

		$novoHorario = new DateTime($horarioAgendamento);

		$agendamentoatual = new ConsultaDatabase($uid);
	        $agendamentoatual = $agendamentoatual->Agendamento($aid);

		$agendamentos = new ConsultaDatabase($uid);
		$agendamentos = $agendamentos->AgendamentosCompetente($horarioAgendamento,$competente);

		if ($agendamentos==0) {
			$modcompetente = new UpdateRow();
			$modcompetente = $modcompetente->UpdateCompetenteAgendamento($competente,$aid);
			if ($modcompetente===true) {
				$mod = 'sucesso';
			} else {
				$mod = 0;
			} // modcompetente true
		} else {
			$mod = 0;
		} // se tem outro agendamento no horário

} else {
	$mod = 0;
}// $_post

echo $mod;
