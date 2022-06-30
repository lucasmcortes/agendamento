<?php
	include_once __DIR__.'/../../includes/setup.inc.php';
?>

<!-- configuracoes -->
<div style='min-width:89%;max-width:89%;display:inline-block;'>
	<?php
		tituloPagina('configurações');

		//echo "<div style='min-width:100%;max-width:100%;display:inline-block;'>";
			//Icone('addadmin','adicionar administrador','addadminsicon');
		//echo "</div>";
		echo "
			<script>
				$('#addadmin').on('click',function () {
					window.location.href='".$dominio."/painel/administradores/novo';
				});
			</script>
		";
	?>

	<!-- expediente -->
	<div class='secaoconfig'>
		expediente
	</div>
	<div>
		<div id='cfigcexinwrap' style='min-width:100%;max-width:100%;margin:3px auto;'>
			<label>Horário de início de expediente:</label>
			<div class='inputouterwrap'>
				<div class='inputwrap'>
					<div class='preinput normal'></div>
					<input class='wrappedinput' type='number' placeholder='Horário em que é iniciada a agenda diária' name='cfigcexin' id='cfigcexin'></input>
					<div class='posinput'></div>
				</div>
			</div>
		</div> <!-- cfigcexinwrap -->
	</div>
	<div>
		<div id='cfigcexenwrap' style='min-width:100%;max-width:100%;margin:3px auto;'>
			<label>Horário de encerramento de expediente:</label>
			<div class='inputouterwrap'>
				<div class='inputwrap'>
					<div class='preinput normal'></div>
					<input class='wrappedinput' type='number' placeholder='Horário em que é encerrada a agenda diária' name='cfigcexen' id='cfigcexen'></input>
					<div class='posinput'></div>
				</div>
			</div>
		</div> <!-- cfigcexenwrap -->
	</div>
	<div>
		<div id='cfigcexintwrap' style='min-width:100%;max-width:100%;margin:3px auto;'>
			<label>Duração de cada agendamento:</label>
			<div class='inputouterwrap'>
				<div class='inputwrap'>
					<div class='preinput normal'></div>
					<input class='wrappedinput' type='number' placeholder='Duração (em minutos) de de cada agendamento' name='cfigcexint' id='cfigcexint'></input>
					<div class='posinput'></div>
				</div>
			</div>
		</div> <!-- cfigcexintwrap -->
	</div>
	<!-- expediente -->

</div>
<!-- configuracoes -->

<script>

	velemento = 0;
	$('#cfigcexin').val('<?php echo $configuracoes['hora_inicio_expediente']; ?>');
	$('#cfigcexen').val('<?php echo $configuracoes['hora_encerramento_expediente']; ?>');
	$('#cfigcexint').val('<?php echo $configuracoes['duracao_intervalo_agendamento']; ?>');

	$('#cfigcaucao').val('<?php echo $configuracoes['caucao_preco']; ?>');
	$('#cfigdiariaasmoto').val('<?php echo $configuracoes['preco_diaria_moto_associado']; ?>');
	$('#cfigdiariaexcmoto').val('<?php echo $configuracoes['excedente_moto']; ?>');
	$('#cfigdiariaas').val('<?php echo $configuracoes['preco_diaria_associado']; ?>');
	$('#cfigdiariaexccarro').val('<?php echo $configuracoes['excedente_carro']; ?>');
	$('#cfigdiariaasutilitario').val('<?php echo $configuracoes['preco_diaria_utilitario_associado']; ?>');
	$('#cfigdiariaexcutilitario').val('<?php echo $configuracoes['excedente_utilitario']; ?>');
	$('#cfigprecokm').val('<?php echo str_replace('R$','',Dinheiro($configuracoes['preco_km'])); ?>');
	$('#cfigprecole').val('<?php echo $configuracoes['preco_le']; ?>');
	$('#cfigprecolc').val('<?php echo $configuracoes['preco_lc']; ?>');
	$('#cfigprecolm').val('<?php echo $configuracoes['preco_lm']; ?>');
	$('#cfigdiasac').val('<?php echo $configuracoes['dias_por_acionamento']; ?>');
	$('#cfigdiaspla').val('<?php echo $configuracoes['dias_cortesia_placa_ano']; ?>');
	$('#cfigdiasplames').val('<?php echo $configuracoes['dias_cortesia_placa_mes']; ?>');
	$('#cfigtoldev').val('<?php echo $configuracoes['min_tolerancia']; ?>');
	$('#cfigrevcarlimiar').val('<?php echo $configuracoes['rev_car_limiar']; ?>');
	$('#cfigrevcarprev').val('<?php echo $configuracoes['rev_car_prev']; ?>');
	$('#cfigrevcarapos').val('<?php echo $configuracoes['rev_car_apos']; ?>');
	$('#cfigrevmoto').val('<?php echo $configuracoes['rev_moto']; ?>');

	$('.posinput').on('click', function() {
		elemento = $(this).siblings('.wrappedinput').attr('id');
		pwd = $('#pwd').val();

		if ($('#'+elemento).hasClass('lista') || $('#'+elemento).hasClass('swap')) {
			velemento = velemento;
		} else {
			velemento = $('#'+elemento).val();
		}

		if (elemento=='cfigrev') {
			if ($('#switchativanotificacaorevisao').prop('checked')) {
				valrevconfig = 'S';
			} else {
				valrevconfig = 'N';
			}
			$(this).closest('.info').attr('aria-label', ($(this).is(':checked')) ? 'Sim' : 'Não');

			$.ajax({
				type: 'POST',
				url: '<?php echo $dominio ?>/painel/configuracoes/cfigrev.inc.php',
				data: {
					cfigrev: valrevconfig,
					senha: pwd
				},
				success: function(cfigswitchativanotificacaorevisao) {
					if (cfigswitchativanotificacaorevisao.includes('sucesso')) {
						$('#switchativanotificacaorevisao').prop('checked', (valrevconfig=='S') ? true : false);
						$('#switchativanotificacaorevisaoinfo').attr('aria-label', (valrevconfig=='S') ? 'Sim' : 'Não');
						if (valrevconfig=='S') {
							$('.revconfig').css({
								'opacity':'1',
								'cursor':'auto'
							});
							$('.revconfig').find('*').css({
								'cursor':'auto',
								'pointer-events':'auto'
							});
							$('.revconfig').find('.salvarconfig').css({
								'cursor':'pointer',
								'pointer-events':'auto'
							});
							$('.revconfig').find('*').prop('disabled', false);
						} else {
							$('.revconfig').css({
								'opacity':'0.34',
								'cursor':'not-allowed'
							});
							$('.revconfig').find('*').css({
								'cursor':'not-allowed',
								'pointer-events':'none'
							});
							$('.revconfig').find('*').prop('disabled', true);
						}

						$('#pwd').css('cursor', 'not-allowed');
						$('#pwd').css('background-color', 'var(--verde)');
						$('#pwd').css('color', 'var(--preto)');
						$('#pwd').css('border', '1px solid var(--preto)');

						mostraFooter();
					} else {
						$('#switchativanotificacaorevisao').prop('checked', <?php echo ($configuracoes['rev_ativa']=='S') ? 'true' : 'false'; ?>);
						$('#switchativanotificacaorevisaoinfo').attr('aria-label', '<?php echo ($configuracoes['rev_ativa']=='S') ? 'Sim' : 'Não'; ?>');

						$('#pwd').css('border', '1px solid var(--rosa)');
						$('#pwd').css('background-color', 'var(--branco)');
						$('#pwd').css('color', 'var(--preto)');

						$('#bannerfooter').css('display','none');
					}
				}
			});
		} else {
			$.ajax({
				type: 'POST',
				url: '<?php echo $dominio ?>/painel/configuracoes/'+elemento+'.inc.php',
				data: {
					modificacao: velemento,
					senha: pwd
				},
				beforeSend: function() {
					$('#'+elemento).siblings('.preinput').removeClass('modificado');
					$('#'+elemento).siblings('.preinput').addClass('normal');
				},
				success: function(modconfig) {
					$('#'+elemento).siblings('.preinput').html('');
					if (modconfig.includes('sucesso')) {
						if ($('#'+elemento).siblings('.preinput').hasClass('bgrosa')) {
							$('#'+elemento).siblings('.preinput').removeClass('bgrosa');
							$('#pwd').removeClass('bordarosa');
						}
						$('#'+elemento).css('border', '0');
						$('#'+elemento).closest('.inputouterwrap').css('background-color', 'var(--verdedois)');
						$('#'+elemento).closest('.inputouterwrap').find('*').prop('disabled', 'disabled');
						$('#'+elemento).closest('.inputouterwrap *').css('cursor','not-allowed');
						$('#'+elemento).closest('.inputouterwrap *').css('pointer-events','none');
						$('#'+elemento).siblings('.preinput').removeClass('normal');
						$('#'+elemento).siblings('.preinput').addClass('modificado');
						mostraFooter();
					} else {
						$('#'+elemento).siblings('.preinput').addClass('bgrosa');
						$('#pwd').addClass('bordarosa');
						$('#bannerfooter').css('display','none');
					}
				}
			});
		} /* se cfigrev */
	});

	valrevconfig='<?php echo $configuracoes['rev_ativa'] ?>';
	$('#switchativanotificacaorevisao').prop('checked', <?php echo ($configuracoes['rev_ativa']=='S') ? 'true' : 'false' ?>);
	if ($('#switchativanotificacaorevisao').prop('checked')) {
		$('.revconfig').css({
			'opacity':'1',
			'cursor':'auto'
		});
		$('.revconfig').find('*').css({
			'cursor':'auto',
			'pointer-events':'auto'
		});
		$('.revconfig').find('.salvarconfig').css({
			'cursor':'pointer',
			'pointer-events':'auto'
		});
		$('.revconfig').find('*').prop('disabled', false);
	} else {
		$('.revconfig').css({
			'opacity':'0.34',
			'cursor':'not-allowed'
		});
		$('.revconfig').find('*').css({
			'cursor':'not-allowed',
			'pointer-events':'none'
		});
		$('.revconfig').find('*').prop('disabled', true);
	}
	$('#switchativanotificacaorevisaoinfo').attr('aria-label', ($('#switchativanotificacaorevisao:checked').length!=0) ? 'Sim' : 'Não');

</script>
