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
		$agendamentos = $agendamentos->AgendamentosCompetente($horarioAgendamento,$agendamentoatual['vid']);

		if ($agendamentos==0) {
			$modhorario = new UpdateRow();
			$modhorario = $modhorario->UpdateHorarioAgendamento($novoHorario->format('Y-m-d H:i:s.u'),$aid);
			if ($modhorario===true) {
				$mod = 'sucesso';
			} else {
				$mod = 0;
			} // modhorario true
		} else {
			$mod = 0;
		} // se tem outro agendamento no horário

} else {
	$mod = 0;
}// $_post

echo $mod;
