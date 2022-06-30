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
?>
	<corpo>

		<!-- conteudo -->
		<div class='conteudo'>
		        <div style='min-width:100%;max-width:100%;text-align:center;'>
		                <?php
					tituloPagina('competentes removidos');

					echo "<div style='min-width:100%;max-width:100%;display:inline-block;'>";
					Icone('vercompetentes','competentes ativos','competentesicon');
					Icone('addcompetente','adicionar competente','addcompetenteicon');
					Icone('addaluguel','adicionar aluguel','addaluguelicon');
					echo "</div>";
					echo "
						<script>
							$('#vercompetentes').on('click',function () {
								window.location.href='".$dominio."/painel/competentes/';
							});
							$('#addaluguel').on('click',function () {
								calendarioPop(3,'fundamental',0);
							});
							$('#addcompetente').on('click',function () {
								window.location.href='".$dominio."/painel/competentes/novo';
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
									$listacompetentes = $listacompetentes->Listacompetentes();

									foreach ($listacompetentes as $competentes) {
										$competente = new ConsultaDatabase($uid);
										$competente = $competente->competente($competentes['vid']);
										if ($competente['ativo']=='N') {
											$categoria = new ConsultaDatabase($uid);
											$categoria = $categoria->competenteCategoria($competentes['categoria']);

											$disponibilidade_competente = new Conforto($uid);
						                                      	$disponibilidade_competente = $disponibilidade_competente->Possibilidade($competente['vid']);

											// atualiza o array de disponibilidade tirando as datas de antes de hoje
											if (key($disponibilidade_competente['disponibilidade'])>=0) {
												// atualiza o array de disponibilidade tirando as datas de antes de hoje
												while ($disponibilidade_competente['disponibilidade'][key($disponibilidade_competente['disponibilidade'])]<$agora->format('Y-m-d')) {
													unset($disponibilidade_competente['disponibilidade'][key($disponibilidade_competente['disponibilidade'])]);
												} // while
											}

						                                       	$disponibilidade = $disponibilidade_competente['disponibilidade'][$agora->format('Y-m-d')]['status']??$disponibilidade_competente['status'];

											$revisao_dez_mil_km = new Conforto($uid);
											$revisao_dez_mil_km = $revisao_dez_mil_km->RevisaoDezKm($competente['vid']);
											($revisao_dez_mil_km==0) ? $revisao_dez_mil_km = 'Revisão em dia' : $revisao_dez_mil_km = '<b>Fazer revisão</b>';

											($competente['observacao']=='') ? $observacao = 'Ok' : $observacao = $competente['observacao'];
											($competente['caracterizado']=='S') ? $caracterizado = 'Sim' : $caracterizado = 'Não';
											($competente['limpeza']=='S') ? $iconelimpeza = 'limpoicon' : $iconelimpeza = 'lavaricon';
											($iconelimpeza=='limpoicon') ? $arialimpeza = 'limpo' : $arialimpeza = 'lavar';

											if ($competente['ativo']=='S') {
												$ativo = 'Ativo';

												if ( ($disponibilidade=='Oficina') || ($disponibilidade=='Pintura') || ($disponibilidade=='Revisão') || ($disponibilidade=='Lavando') ) {
													$corespecial = "style='background-color:var(--rosa);color:var(--branco);'";
												} else if ($disponibilidade=='Alugado') {
													$corespecial = "style='background-color:var(--azul);color:var(--preto);'";
												} else {
													$corespecial = '';
												}
											} else if ($competente['ativo']=='N') {
												$ativo = 'Removido';

												$corespecial = "style='background-color:var(--bege);color:var(--preto);'";
											} // bg se tá ativo

											echo "
												<div id='competentewrap_".$competente['vid']."' class='relatoriowrap' ".$corespecial.">
													<div class='slotrelatoriowrap'>
														<div class='slotrelatorio'>
															<p class='headerslotrelatorio'><b>Modelo:</b></p>
															<p class='infoslotrelatorio'>".$competente['modelo']."</p>
															<p class='headerslotrelatorio'><b>Placa:</b></p>
															<p class='infoslotrelatorio'>".$competente['placa']."</p>
															<p class='headerslotrelatorio'><b>Ano:</b></p>
															<p class='infoslotrelatorio'>".$competente['ano']."</p>
															<p class='headerslotrelatorio'><b>Cor:</b></p>
															<p class='infoslotrelatorio'>".$competente['cor']."</p>
														</div>
													</div>
													<div class='slotrelatoriowrap'>
														<div class='slotrelatorio'>
															<p class='headerslotrelatorio'><b>Kilometragem:</b></p>
															<p class='infoslotrelatorio'>".Kilometragem($competente['km'])."</p>
															<p class='headerslotrelatorio'><b>Categoria:</b></p>
															<p class='infoslotrelatorio'>".$categoria."</p>
															<p class='headerslotrelatorio'><b>Caracterizado:</b></p>
															<p class='infoslotrelatorio'>".$caracterizado."</p>
															<p class='headerslotrelatorio'><b>Limpeza:</b></p>
															<p class='infoslotrelatorio'>".ucfirst($arialimpeza)."</p>
														</div>
													</div>
													<div class='slotrelatoriowrap'>
														<div class='slotrelatorio'>
															<p class='headerslotrelatorio'><b>Observação:</b></p>
															<p class='infoslotrelatorio'>".mb_strimwidth($observacao, 0, 36, '[...]')."</p>
															<p class='headerslotrelatorio'><b>Disponibilidade:</b></p>
															<p class='infoslotrelatorio'>".$disponibilidade."</p>
															<p class='headerslotrelatorio'><b>Revisão:</b></p>
															<p class='infoslotrelatorio'>".$revisao_dez_mil_km."</p>
															<p class='headerslotrelatorio'><b>Status:</b></p>
															<p class='infoslotrelatorio'>".$ativo."</p>
														</div>
													</div>
												</div>
											";
											//Icone($competente['vid']."_iconelimpeza",$arialimpeza,$iconelimpeza);
										} // removido
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

					</div>
				</div>
	        	</div>
		</div>
		<!-- conteudo -->

<?php
	require_once __DIR__.'/../../../rodape.php';
?>
