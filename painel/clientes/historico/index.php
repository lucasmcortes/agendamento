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

	if ( (isset($_GET['lid'])) && (is_numeric($_GET['lid'])) ) {
		$lid = $_GET['lid'];
		$cliente = new ConsultaDatabase($uid);
		$cliente = $cliente->clienteInfo($lid);

		$telefone = new Conforto($uid);
		$telefone = $telefone->FormatoTelefone($cliente['telefone'],'br');

		if ($cliente['associado']=='S') {
			$associado = 'Sim';
		} else {
			$associado = 'Não';
		}
		$placas = new ConsultaDatabase($uid);
		$placas = $placas->Placas($cliente['lid']);

		$rg = $cliente['rg'];
		$imagem = glob(__DIR__.'/../rg/'.$rg.'.*', GLOB_BRACE);
		if (!empty($imagem)) {
			usort($imagem, fn($a, $b) => filemtime($b) - filemtime($a)); // arquivo mais recente
			$imagem = basename($imagem[0]);
		} else {
			$imagem = '';
		}

	} else {
		redirectToLogin('painel/clientes');
	} // get lid
?>
	<corpo>

		<!-- conteudo -->
		<div class='conteudo'>
		        <div style='min-width:100%;max-width:100%;text-align:center;'>
		                <?php
					tituloPagina($cliente['nome']);
				?>
                                <div style='min-width:100%;max-width:100%;display:inline-block;'>
					<div style='min-width:90%;max-width:90%;display:inline-block;margin:1.8% auto;'>
						<!-- container -->
				                <div style='min-width:100%;max-width:100%;margin:0 auto;display:inline-block;overflow:auto;'>
							<p style='min-width:100%;max-width:100%;display:inline-block;'><b>Cadastrado em:</b> <?php echo strftime('%d de %B de %Y', strtotime($cliente['data_cadastro'])); ?></p>

							<p style='min-width:100%;max-width:100%;display:inline-block;'><b>RG:</b> <?php echo $cliente['rg'] ?></p>
							<div style='display:inline-block;border:1px solid var(--preto);border-radius:var(--radius);padding:1.8%;margin:1.8%;'>
								<?php
									if (!empty($imagem)) {
									echo "
										<div style='width:55%;display:inline-block;'>
									";
									if (strpos($imagem,'.pdf')!==false) {
										echo "
											<iframe id='rgimg' src='".$dominio."/painel/clientes/rg/".$imagem."?".rand(1,999)."' style='width:100%;auto;'></iframe>
											<p id='dldoc' style='background-color:var(--preto);padding:3px 5px;border-radius:var(--radius);color:var(--branco);margin-bottom:1.8%;'>ver pdf</p>
											<script>
												$('#dldoc').on('click', function() {
													window.open('".$dominio."/painel/clientes/rg/".$imagem."','_blank');
												})
											</script>
										";
									} else {
										echo "
											<img class='rgimg' style='max-width:100%;max-height:100%;' src='".$dominio."/painel/clientes/rg/".$imagem."?".rand(1, 999)."'></img>
											<script>
												$('.rgimg').on('click', function () {
													$.ajax({
														url: '".$dominio."/includes/biggerrg.inc.php',
														success: function(bigpic) {
															$('#vestimenta').html(bigpic);
														},
													});
												});
											</script>
										";
									}
											MontaBotao('atualizar imagem','atualizaimgrg');
										echo "
											<script>
												$('#atualizaimgrg').on('click', function () {
													loadVestimenta('".$dominio."/painel/clientes/novo/includes/atualizaimgrg.inc.php');
												});
											</script>
											</div>
										";
									} else {
										echo "
										<!-- img_rg_outer_wrap -->
										<div id='img_rg_outer_wrap' class='uploadouterwrap'>
											<label>Foto do RG:</label>
											<div id='img_rg_wrap' class='uploadwrap'>
												<label id='label_img_rg' for='img_rg' class='upload'>
													<img class='uploadicon' src='".$dominio."/img/addimg.png'></img>
													<p class='uploadcaption'>
														adicionar imagem
													</p>
												</label>
												<input type='file' name='img_rg' id='img_rg' class='plimgupload'  accept='image/jpeg,image/gif,image/png,application/pdf,image/x-eps' style='display:none;'>
												<div style='min-width:100%;max-width:100%;display:inline-block;'>
													<div id='progressBarWrap_rg' class='uploadprogressbar'>
														<div id='progressBar_rg' class='uploadprogressbarinner'></div>
														<p id='statusUpload_rg' class='uploadstatusupload'></p>
													</div>
												</div>
											</div>


											<script>
												img_rg_outer_wrap = $('#img_rg_outer_wrap').html();
												function uploadFile(elemento) {
													file = document.getElementById(elemento).files[0];

													formdata = new FormData();
													formdata.append('img_rg', file);
													formdata.append('uploaded_file_name', file.name);

													ajax = new XMLHttpRequest();
													ajax.upload.addEventListener('progress', progressHandler, false);
													ajax.addEventListener('load', completeHandler, false);
													ajax.addEventListener('error', errorHandler, false);
													ajax.addEventListener('abort', abortHandler, false);

													ajax.open('POST','".$dominio."/painel/clientes/novo/includes/addimgrg.inc.php');
													ajax.send(formdata);
												}

												function progressHandler(event) {
													percent = (event.loaded / event.total) * 100;
													$('#progressBar_rg').width(Math.round(percent) + '%');
													document.getElementById('statusUpload_rg').innerHTML = Math.round(percent) + '%';
												}

												function completeHandler(event) {
													document.getElementById('img_rg_wrap').innerHTML = event.target.responseText;

													$.ajax({
														url: '".$dominio."/painel/clientes/novo/includes/salvaimgrg.inc.php'
													});

													$('#remove_img_rg').on('click',function() {
														$('#img_rg_outer_wrap').html(img_rg_outer_wrap);
														$.ajax({
															url: '".$dominio."/painel/clientes/novo/includes/unsetrg.inc.php'
														});
													});
												}

												function errorHandler(event) {
													document.getElementById('img_rg_wrap').innerHTML = 'Upload falhou';
												}

												function abortHandler(event) {
													document.getElementById('img_rg_wrap').innerHTML = 'Upload cancelado';
												}

												$('#img_rg').change(function() {
													elemento = $(this).attr('id');
													uploadFile(elemento);
												});
											</script>
										</div>
										<!-- img_rg_outer_wrap -->
										";
									} // se existe a imagem da rg
								?>
							</div>

							<p style='min-width:100%;max-width:100%;display:inline-block;'><b>CPF:</b> <?php echo $cliente['documento'] ?></p>
							<p style='min-width:100%;max-width:100%;display:inline-block;'><b>Telefone:</b> <?php echo $telefone ?></p>
							<p style='min-width:100%;max-width:100%;display:inline-block;'><b>Email:</b> <?php echo $cliente['email'] ?></p>
							<p style='min-width:100%;max-width:100%;display:inline-block;'><b>Endereço:</b> <?php echo $cliente['rua'].', '.$cliente['numero'].' - '.$cliente['bairro'].' - '.$cliente['cidade'].' - '.$cliente['estado'] ?> </p>
							<?php
								if ($associado=='Sim') {
									echo "
										<p style='min-width:100%;max-width:100%;display:inline-block;'><b>Associado:</b> ".$associado."</p>
										<p style='min-width:100%;max-width:100%;display:inline-block;'><b>Desde:</b> ".strftime('%d de %B de %Y', strtotime($cliente['data_associado']))."</p>
									";
									$placas = new ConsultaDatabase($uid);
									$placas = $placas->Placas($cliente['lid']);
									$opcoes = [];
									foreach ($placas as $placa) {
										if ($placa['data']<$cliente['data_associado']) {
											$utilizadas = 0;
											$cortesia = new ConsultaDatabase($uid);
											$cortesia = $cortesia->Cortesia($placa['pid']);
											foreach($cortesia as $dias_gratis) {
												$utilizadas += $dias_gratis['utilizadas'];
											} // soma os dias utilizados de cortesia
											echo "
												<div style='border:1px solid var(--preto);padding:5px;display:inline-block;border-radius:var(--radius);margin:3px;'>
													<p style='border:1px solid var(--preto);background-color:var(--rosa);padding:5px 8px;margin:3px; display:inline-block;border-radius:var(--radius);'>".$placa['placa']."</p>
													<p>Cortesias utilizadas: ".$utilizadas."</p>
												</div>
											";
										} else {
											// da associação atual
											$ativa = new ConsultaDatabase($uid);
											$ativa = $ativa->PlacaAtiva($placa['pid']);
											// placas dessa associação que estão ativas (estão no PlacaAtiva)
											if (!in_array($ativa['pid'],$opcoes)) {
												if ($ativa['pid']!=0) {
													$utilizadas = 0;
													$cortesia = new ConsultaDatabase($uid);
													$cortesia = $cortesia->Cortesia($placa['pid']);
													foreach($cortesia as $dias_gratis) {
														$utilizadas += $dias_gratis['utilizadas'];
													} // soma os dias utilizados de cortesia
													echo "
														<div style='border:1px solid var(--preto);padding:5px;display:inline-block;border-radius:var(--radius);margin:3px;'>
															<p style='border:1px solid var(--preto);background-color:var(--verde);padding:5px 8px;margin:3px; display:inline-block;border-radius:var(--radius);'>".$placa['placa']."</p>
															<p>Cortesias utilizadas: ".$utilizadas."</p>
														</div>
													";
												} // placa que existe
											} // se ainda não é uma opção
											$opcoes[] = $ativa['pid'];

											// placas dessa associação mas que foram desativadas (não estão no PlacaAtiva)
											if (!in_array($placa['pid'],$opcoes)) {
												$utilizadas = 0;
												$cortesia = new ConsultaDatabase($uid);
												$cortesia = $cortesia->Cortesia($placa['pid']);
												foreach($cortesia as $dias_gratis) {
													$utilizadas += $dias_gratis['utilizadas'];
												} // soma os dias utilizados de cortesia
												echo "
													<div style='border:1px solid var(--preto);padding:5px;display:inline-block;border-radius:var(--radius);margin:3px;'>
														<p style='border:1px solid var(--preto);background-color:var(--rosa);padding:5px 8px;margin:3px; display:inline-block;border-radius:var(--radius);'>".$placa['placa']."</p>
														<p>Cortesias utilizadas: ".$utilizadas."</p>
													</div>
												";
											} // se ainda não é uma opção
											$opcoes[] = $placa['pid'];
										} // placa data
									} // foreach
								} // associado

								tituloPagina('aluguéis do cliente');
								$alugueis = new ConsultaDatabase($uid);
								$alugueis = $alugueis->ListaAlugueiscliente($cliente['lid']);
								if ($alugueis[0]['aid']!=0) {
									echo "
										<div style='min-width:90%;max-width:90%;display:inline-block;margin:1.8% auto;'>
											<!-- container -->
										";
													foreach ($alugueis as $aluguel) {
														$competente = new ConsultaDatabase($uid);
														$competente = $competente->competente($aluguel['vid']);

														$cliente = new ConsultaDatabase($uid);
														$cliente = $cliente->clienteInfo($aluguel['lid']);

														$categoria = new ConsultaDatabase($uid);
														$categoria = $categoria->competenteCategoria($competente['categoria']);

														$dia = new DateTime($aluguel['data']);
														$inicio = new DateTime($aluguel['inicio']);
														$devolucao = new DateTime($aluguel['devolucao']);

														$devolucaoaluguel = new ConsultaDatabase($uid);
														$devolucaoaluguel = $devolucaoaluguel->Devolucao($aluguel['aid']);
														if ($devolucaoaluguel['deid']==0) {
															$reserva = new ConsultaDatabase($uid);
															$reserva = $reserva->Reserva($aluguel['aid']);
															if ($reserva['reid']!=0) {
																$atividade = new ConsultaDatabase($uid);
																$atividade = $atividade->Ativacao($reserva['reid']);
																if ($atividade['ativa']=='S') {
																	$inicio = new DateTime($reserva['inicio']);
																	$devolucao = new DateTime($reserva['devolucao']);
																	if ($reserva['confirmada']==1) {
																		$ativo = 'ativo';
																	} else {
																		$ativo = '';
																	}// confirmada
																} // ativa
															} else {
																$ativo = 'ativo';
															} // reserva
														} else {
															$reserva = new ConsultaDatabase($uid);
															$reserva = $reserva->ReservaDevolvida($aluguel['aid']);
															if ($reserva['reid']!=0) {
																$atividade = new ConsultaDatabase($uid);
																$atividade = $atividade->Ativacao($reserva['reid']);
																if ($atividade['ativa']=='S') {
																	$inicio = new DateTime($reserva['inicio']);
																	$devolucao = new DateTime($reserva['devolucao']);
																	if ($reserva['confirmada']==1) {
																		$ativo = 'ativo';
																	} else {
																		$ativo = '';
																	}// confirmada
																} // ativa
															} else {
																$ativo = '';
															} // reserva
														} // deid 0

														$diarias = new Conforto($uid);
														$diarias = $diarias->TotalDiarias($inicio,$devolucao);

														echo "
															<div id='aluguelwrap_".$aluguel['aid']."' class='relatoriowrap ".$ativo."'>
																<div class='slotrelatoriowrap'>
																	<div class='slotrelatorio'>
																		<p class='headerslotrelatorio'><b>cliente:</b></p>
																		<p class='infoslotrelatorio'>".$cliente['nome']."</p>
																		<p class='headerslotrelatorio'><b>Data de registro:</b></p>
																		<p class='infoslotrelatorio'>".$dia->format('d/m/Y')." às ".$dia->format('H')."h".$dia->format('i')."</p>
																	</div>
																</div>
																<div class='slotrelatoriowrap'>
																	<div class='slotrelatorio'>
																		<p class='headerslotrelatorio'><b>Modelo:</b></p>
																		<p class='infoslotrelatorio'>".$competente['modelo']."</p>
																		<p class='headerslotrelatorio'><b>Placa:</b></p>
																		<p class='infoslotrelatorio'>".$competente['placa']."</p>
																		<p class='headerslotrelatorio'><b>Kilometragem:</b></p>
																		<p class='infoslotrelatorio'>".Kilometragem($aluguel['kilometragem'])."</p>
																	</div>
																</div>
																<div class='slotrelatoriowrap'>
																	<div class='slotrelatorio'>
																		<p class='headerslotrelatorio'><b>Data de início:</b></p>
																		<p class='infoslotrelatorio'>".$inicio->format('d/m/Y')." às ".$inicio->format('H')."h</p>
																		<p class='headerslotrelatorio'><b>Data de devolução:</b></p>
																		<p class='infoslotrelatorio'>".$devolucao->format('d/m/Y')." às ".$devolucao->format('H')."h</p>
																	</div>
																	<div class='slotrelatorio'>
																		<p class='headerslotrelatorio'><b>Previsão de diárias:</b></p>
																		<p class='infoslotrelatorio'>".$diarias." x ".Dinheiro($aluguel['diaria'])."</p>
																	</div>
																</div>
															</div>
														";

													} // foreach alugueis

												echo "
											</div>
											<!-- container -->
									";
								} // aid > 0
							?>
						</div>
						</div>
						<script>
							$('.relatoriowrap').on('click', function() {
								aid = $(this).attr('id').split('_')[1];
								if ($(this).hasClass('ativo')) {
									valativo = 1;
								} else {
									valativo = 0;
								}
								aluguelFundamental(aid,valativo);
							});
						</script>
						<!-- container -->
						<?php
							//tituloPagina($cliente['nome']);
						?>
					</div>
                                </div>
	        	</div>
		</div>
		<!-- conteudo -->

<?php
	require_once __DIR__.'/../../../rodape.php';
?>
