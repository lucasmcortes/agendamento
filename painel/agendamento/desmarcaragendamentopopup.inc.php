<?php

include_once __DIR__.'/../../includes/setup.inc.php';
BotaoFecharVestimenta();

if (isset($_POST['agendamento'])) {
	$aid = $_POST['agendamento'];

	$agendamentoatual = new ConsultaDatabase($uid);
        $agendamentoatual = $agendamentoatual->Agendamento($aid);
        $dataAgendada = new DateTime($agendamentoatual['agendamento']);

        $clienteagendamento = new ConsultaDatabase($uid);
        $clienteagendamento = $clienteagendamento->Cliente($agendamentoatual['lid']);

} else {
	$vid = 0;
}// $_post
?>

<!-- items -->
<div class="items">
	<?php tituloPagina('desmarcar'); ?>
	<div id='resultado' style='text-align:center;margin:0 auto;margin:5% auto;margin-top:0;'>
		<div style='min-width:100%;max-width:100%;display:inline-block;margin:3% auto;'>
			<p style='padding:3% 8%;padding-top:0;'>
				Desmarcar o agendamento de <?php echo $clienteagendamento['nome'] ?> do dia <?php echo $dataAgendada->format('d/m/Y') ?> às <?php echo $dataAgendada->format('H') ?>h<?php echo $dataAgendada->format('i') ?>?
			</p>
		</div>
		<div style='min-width:48%;max-width:48%;display:inline-block;'>
			<?php MontaBotao('voltar','voltar'); ?>
		</div>
		<div style='min-width:48%;max-width:48%;display:inline-block;'>
			<?php MontaBotao('sim','desmarcar','vermelho'); ?>
		</div>
	</div>
</div>
<!-- items -->

<script>

	abreVestimenta();
	$('#voltar').on('click',function() {
		$('#fecharvestimenta').trigger('click');
	});

	$('#desmarcar').on('click',function () {
		$.ajax({
			type: 'POST',
			url: '<?php echo $dominio ?>/painel/agendamento/desmarcaragendamento.inc.php',
			data: {
				agendamento: <?php echo $aid ?>
			},
			success: function(desmarcar) {
				$('#resultado').html(desmarcar);
				if (desmarcar.includes('sucesso') == true) {
					$('#resultado').append('<img id=\"sucessogif\" src=\"<?php echo $dominio ?>/img/sucesso.gif\">');
					mostraFooter();
				} else {
					$('#bannerfooter').css('display','none');
				}
			}
		});
	});
</script>
