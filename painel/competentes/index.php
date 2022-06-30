<?php
	require_once __DIR__.'/../../cabecalho.php';

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
?>
	<corpo>

		<!-- conteudo -->
		<div class='conteudo'>
			<div style='min-width:100%;max-width:100%;text-align:center;'>
				<?php
					tituloPagina('competentes');

					echo "<div style='min-width:100%;max-width:100%;display:inline-block;'>";
					Icone('addcompetente','adicionar competente','addcompetentes-preto');
					//Icone('addaluguel','adicionar aluguel','addaluguelicon');
					//Icone('competentesremovidos','competentes removidos','removecompetenteicon');
					echo "</div>";
					echo "
						<script>
							$('#addcompetente').on('click',function () {
								window.location.href='".$dominio."/painel/competentes/novo';
							});
							$('#addaluguel').on('click',function () {
								calendarioPop(3,'fundamental',0);
							});
							$('#competentesremovidos').on('click',function () {
								window.location.href='".$dominio."/painel/competentes/removidos';
							});
						</script>
					";
				?>

				<div style='min-width:100%;max-width:100%;display:inline-block;'>
					<div style='min-width:90%;max-width:90%;display:inline-block;margin:1.8% auto;'>
						<!-- competentes container -->
						<div style='min-width:100%;max-width:100%;margin:0 auto;display:inline-block;overflow:overlay;'>
								<?php
									$listacompetentes = new ConsultaDatabase($uid);
									$listacompetentes = $listacompetentes->ListaCompetentes();

									foreach ($listacompetentes as $competentes) {
										$competente = new ConsultaDatabase($uid);
										$competente = $competente->Competente($competentes['vid']);
										if ($competente['ativo']==1) {

											$cobs = new ConsultaDatabase($uid);
											$cobs = $cobs->Cobs($competentes['vid']);

											if ($competente['ativo']==1) {
												$ativo = 'Ativo';

												$corespecial = '';
											} else if ($competente['ativo']==0) {
												$ativo = 'Removido';

												$corespecial = "style='background-color:var(--bege);color:var(--preto);'";
											} // bg se tá ativo

											echo "
												<div id='competentewrap_".$competente['vid']."' class='relatoriowrap' ".$corespecial.">
													<div class='slotrelatoriowrap'>
														<div class='slotrelatorio'>
															<p class='headerslotrelatorio'><b>Nome:</b></p>
															<p class='infoslotrelatorio'>".$competente['nome']."</p>
														</div>
													</div>
													<div class='slotrelatoriowrap'>
														<div class='slotrelatorio'>
															<p class='headerslotrelatorio'><b>Observação:</b></p>
															<p class='infoslotrelatorio'>".mb_strimwidth($competente['observacao'], 0, 36, '[...]')."</p>
														</div>
													</div>
												</div>
											";
											//Icone($competente['vid']."_iconelimpeza",$arialimpeza,$iconelimpeza);
										} // ativos
									} // foreach competentes
								?>
						</div>
						<!-- competentes container -->

						<script>
						$('.relatoriowrap').on('click', function() {
							vid = $(this).attr('id').split('_')[1];
							competenteFundamental(vid);
						});
						</script>

						<?php
							if ($listacompetentes[0]['vid']==0) {
								VamosComecar();
							} else {
								echo "
									<div id='vercalendariotodoswrap' style='min-width:100%;max-width:100%;display:inline-block;'>
								";
								BotaoPainel('ver calendário','vercalendariotodos','competentes/calendario');
								echo "
									</div>
								";
							}
						?>

					</div>
				</div>
			</div>
		</div>
		<!-- conteudo -->

<?php
	require_once __DIR__.'/../../rodape.php';
?>
