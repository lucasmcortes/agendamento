<?php

include_once __DIR__.'/../../includes/setup.inc.php';

if (isset($_POST['agendamento'])) {
	$aid = $_POST['agendamento'];
	$resultado = '';

	$agendamentoatual = new ConsultaDatabase($uid);
        $agendamentoatual = $agendamentoatual->Agendamento($aid);
        $dataAgendada = new DateTime($agendamentoatual['agendamento']);

	$desmarcar = new UpdateRow();
	$desmarcar = $desmarcar->DesmarcarAgendamento($aid);

	if ($desmarcar===true) {
		$resultado = "
			<div style='min-width:100%;max-width:100%;display:inline-block;'>
				<p class='respostaalteracao'>
					O agendamento foi desmarcado com sucesso.
				</p>
				<script>
					setTimeout(function() {
						$('#fecharvestimenta').trigger('click');
						$('#fechar').trigger('click');
					},5000);
				</script>
			</div>
		";
	} else {
		$resultado = "
			<div style='min-width:100%;max-width:100%;display:inline-block;'>
				<p class='respostaalteracao'>
					Erro ao desmarcado o agendamento.
				</p>
				<script>
					setTimeout(function() {
						$('#fecharvestimenta').trigger('click');
						$('#fechar').trigger('click');
					},5000);
				</script>
			</div>
		";
	} // addativacao true
} else {
	$resultado = 0;
}// $_post

$resultado .= "<script>calendarioSuperior($('#competentepainellinear').val(),".$dataAgendada->format('d').",".$dataAgendada->format('m').",".$dataAgendada->format('Y').");</script>";

echo $resultado;

?>
