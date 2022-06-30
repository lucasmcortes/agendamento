<?php
	require_once __DIR__.'/../../../cabecalho.php';

	if (isset($_SESSION['ag_id'])) {
		$adminivel = new ConsultaDatabase($uid);
		$adminivel = $adminivel->EncontraAdmin($_SESSION['ag_email']);

		$admincategoria = new ConsultaDatabase($uid);
		$admincategoria = $admincategoria->AdminCategoria($adminivel['nivel']);

                $listaadmin = new ConsultaDatabase($uid);
                $listaadmin = $listaadmin->ListaAdmin();

	} else {
		redirectToLogin();
	} // isset uid

	if ( (isset($_GET['inicio'])) && (isset($_GET['devolucao'])) ) {
		$inicioformatado = explode('/',$_GET['inicio']);
		$inicioformatado = $inicioformatado[2].'-'.$inicioformatado[1].'-'.$inicioformatado[0];
		$devolucaoformatado = explode('/',$_GET['devolucao']);
		$devolucaoformatado = $devolucaoformatado[2].'-'.$devolucaoformatado[1].'-'.$devolucaoformatado[0];
	} else {
		redirectToLogin();
	}// get


?>
	<corpo>

		<!-- conteudo -->
		<div class='conteudo'>
		        <div style='min-width:100%;max-width:100%;text-align:center;'>
		                <?php
					tituloPagina('disponibilidade');
				?>
                                <div style='min-width:100%;max-width:100%;display:inline-block;'>
					<div id ='respostadisponibilidadepordata' style='min-width:81%;max-width:81%;display:inline-block;'>
						<script>
							$.ajax({
								type: 'POST',
								url: '<?php echo $dominio ?>/painel/alugueis/novo/includes/buscadatadisponivel.inc.php',
								data: {
									inicio: '<?php echo $inicioformatado ?>',
									devolucao: '<?php echo $devolucaoformatado ?>'
								},
								success: function(desejo) {
									$('#respostadisponibilidadepordata').html('');
									if (desejo['quantidade_de_competentes']>0) {
										$('#respostadisponibilidadepordata').append('competentes disponíveis para o período de <b><?php echo $_GET['inicio'] ?></b> até <b><?php echo $_GET['devolucao'] ?></b>:<br>');
										competentes = desejo['competentes'] == null ? [] : (desejo['competentes'] instanceof Array ? desejo['competentes'] : [desejo['competentes']]);
										$.each(competentes, function(index, competente) {
											$('#'+competente['vid']+'_wrap').append('<div id=\"card_v_'+competente['vid']+'\" class=\"cardslot\"></div>');
											$('#'+competente['vid']+'_wrap').append('<div id=\"sel_v_'+competente['vid']+'\" class=\"selecao\"><p class=\"selecao\">Escolher '+competente['modelo']+'</p></div>');
											atualizaCard(competente['vid']);
										});
									} else {
										$('#respostadisponibilidadepordata').html('Todos os competentes estão ocupados para o período de <b><?php echo $_GET['inicio'] ?></b> até <b><?php echo $_GET['devolucao'] ?></b>.<br>');
									}
								}
							});
							$('body').on('click', '.selecao',function() {
								window.location.href='<?php echo $dominio ?>/painel/alugueis/novo/?v='+$(this).attr('id').split('_')[2]+'&inicio=<?php echo $_GET['inicio'] ?>&devolucao=<?php echo $_GET['devolucao'] ?>';
							});
						</script>
					</div> <!-- respostadisponibilidadepordata -->
					<?php
						$competentes = new ConsultaDatabase($uid);
						$competentes = $competentes->Listacompetentes();
						if ($competentes[0]['vid']!=0) {
							foreach ($competentes as $competente) {
								echo "<div id='".$competente['vid']."_wrap'></div>";
							} // foreach
						} // vid != 0
					?>
                                </div>
	        	</div>
		</div>
		<!-- conteudo -->

<?php
	require_once __DIR__.'/../../../rodape.php';
?>
