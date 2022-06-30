<?php

include_once __DIR__.'/../../../includes/setup.inc.php';
BotaoFechar();

if (isset($_REQUEST['competente'])) {
	$vid = $_REQUEST['competente'];
	$competente = new ConsultaDatabase($uid);
	$competente = $competente->Competente($vid);

} else {
	$vid = 0;
}// $_post
?>

<!-- items -->
<div class="items">
	<?php
		tituloCarro($competente['nome']);
		Icone('editarcompetente','editar','editaricon');
	?>

	<div class='listawrap' style='margin-top:2px;'>
		<div id='observacaowrap' style='min-width:100%;max-width:100%;margin:3px auto;'>
			<label>Observação</label>
			<div id='observacaoinner' style='display:inline-block;'>
				<textarea id='observacao' rows='9' style='vertical-align:middle;border:1px solid var(--preto);border-radius:var(--radius);'><?php echo $competente['observacao'] ?></textarea>
			</div>
			<p id='enviarobservacao' class='salvarconfig' style='display:none;vertical-align:top;'></p>
		</div> <!-- observacaowrap -->
		<script>
			obsclick = 0;
			$('#observacaoinner').css({
				'min-width':'100%',
				'max-width':'100%'
			});
			$('#observacao').on('click', function() {
				if (obsclick==0) {
					$('#observacaoinner').css({
						'display':'block',
						'min-width':'77.6%',
						'max-width':'77.6%'
					});
					setTimeout(function() {
						$('#observacaoinner').css({
							'float':'none',
							'display':'inline-block'
						});
						$('#enviarobservacao').css('display','inline-block');
		                        }, 350);

					obsclick = 1;
					if (obsclick==1) {
						$('body').on('click', function() {
							$('#observacaoinner').css({
								'display':'block',
								'min-width':'100%',
								'max-width':'100%'
							});
							$('#enviarobservacao').css('display','none');
							obsclick = 0;
						});
					} // obsclick = 1
				} // obsclick = 0
				return false;
			});
		</script>

	</div> <!-- listawrap -->

</div>
<!-- items -->

<script>
	abreFundamental();

	$('#editarcompetente').on('click',function () {
		window.location.href='<?php echo $dominio ?>/painel/competentes/editar/?v=<?php echo $competente['vid'] ?>';
	});

	$('#enviarobservacao').on('click', function() {
		vobservacao = $('#observacao').val();
		$.ajax({
			type: 'POST',
			url: '<?php echo $dominio ?>/painel/competentes/includes/vobservacaomod.inc.php',
			data: {
				competente: '<?php echo $competente['vid'] ?>',
				observacao: vobservacao
			},
			success: function(modobs) {
				if (modobs.includes('sucesso')) {
					$('#observacao').prop('disabled', 'disabled');
					$('#observacao').css('cursor', 'not-allowed');
					$('#observacao').css('background-color', 'var(--verde)');
					$('#observacao').css('color', 'var(--preto)');
					$('#observacao').css('border', '1px solid var(--preto)');
					$('#enviarobservacao').css('display', 'none');
					$('#observacaoinner').css('min-width', '100%');
					$('#observacaoinner').css('max-width', '100%');
					$('#observacaoenviar').css('display', 'none');
					mostraFooter();
				} else {
					$('#observacao').css('border', '1px solid var(--rosa)');
					$('#observacao').css('background-color', 'var(--branco)');
					$('#observacao').css('color', 'var(--preto)');
					$('#bannerfooter').css('display','none');
				}
			}
		});
	});

</script>
