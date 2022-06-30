<?php
	require_once __DIR__.'/../../cabecalho.php';

	if (isset($_SESSION['ag_id'])) {
		$adminivel = new ConsultaDatabase($uid);
		$adminivel = $adminivel->EncontraAdmin($_SESSION['ag_email']);
		if ($adminivel['nivel']!=3) {
			redirectToLogin();
		} // nivel != 3

		$permissao = new Conforto($uid);
	        $permissao = $permissao->Permissao('registro');
	        if ($permissao!==true) {
	                redirectToLogin();
	        } // permitido

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
					tituloPagina('clientes');

					echo "<div style='min-width:100%;max-width:100%;display:inline-block;'>";
					Icone('addcliente','adicionar cliente','addclientes-preto');
					echo "</div>";
					echo "
						<script>
							$('#addcliente').on('click',function () {
								window.location.href='".$dominio."/painel/clientes/novo';
							});
						</script>
					";
				?>

                                <div style='min-width:100%;max-width:100%;display:inline-block;'>
						<?php
							$clientes = [];
							$listaclientes = new ConsultaDatabase($uid);
							$listaclientes = $listaclientes->Listaclientes();
							if ($listaclientes[0]['lid']!=0) {
								foreach ($listaclientes as $cliente) {
									$clientes[] = $cliente;
								}
							} // lid > 0

							$filtro = new Conforto($uid);
							$filtro = $filtro->Exibicao($clientes);
							echo $filtro['botoes'];

							echo "<div style='min-width:90%;max-width:90%;display:inline-block;'>";
							require_once __DIR__.'/includes/buscacliente.inc.php';
							echo "</div>";

							if ($filtro['i']>0) {
								echo "
									<div style='min-width:90%;max-width:90%;display:inline-block;margin:1.8% auto;'>
									<!-- container -->
							                <div style='min-width:100%;max-width:100%;margin:0 auto;display:inline-block;overflow:auto;'>
								";

											$paginas = new Conforto($uid);
											$paginas = $paginas->Paginacao($filtro['itens']);
											foreach ($paginas['itens'] as $clientes) {
												$cliente = new ConsultaDatabase($uid);
												$cliente = $cliente->clienteInfo($clientes['lid']);

												$telefone = new Conforto($uid);
												$telefone = $telefone->FormatoTelefone($cliente['telefone'],'br');

												($cliente['associado']=='S') ? $associado = 'Desde '.strftime('%d de %B de %Y', strtotime($cliente['data_associado'])) : $associado = 'Não';
												echo "
													<div id='clienteswrap_".$cliente['lid']."' class='relatoriowrap'>
														<div class='slotrelatoriowrap'>
															<div class='slotrelatorio'>
																<p class='headerslotrelatorio'><b>Nome:</b></p>
																<p class='infoslotrelatorio'>".$cliente['nome']."</p>
																<p class='headerslotrelatorio'><b>Telefone:</b></p>
																<p class='infoslotrelatorio'>".$telefone."</p>
															</div>
														</div>
														<div class='slotrelatoriowrap'>
															<div class='slotrelatorio'>
																<p class='headerslotrelatorio'><b>CPF:</b></p>
																<p class='infoslotrelatorio'>".$cliente['documento']."</p>
																<p class='headerslotrelatorio'><b>RG:</b></p>
																<p class='infoslotrelatorio'>".$cliente['rg']."</p>
															</div>
														</div>
														<div class='slotrelatoriowrap'>
															<div class='slotrelatorio'>
																<p class='headerslotrelatorio'><b>Email:</b></p>
																<p class='infoslotrelatorio'>".$cliente['email']."</p>
																<p class='headerslotrelatorio'><b>Associado:</b></p>
																<p class='infoslotrelatorio'>".$associado."</p>
															</div>
														</div>
													</div>
												";
											} // foreach competentes
								echo "
									</div>
									<!-- container -->
									".$paginas['botoes']."
								";
							} else {
								NenhumRegistro();
							}// i > 0
						?>

						<script>
							$('.relatoriowrap').on('click', function() {
								lid = $(this).attr('id').split('_')[1];
								clienteFundamental(lid);
							});
						</script>
					</div>
                                </div>
	        	</div>
		</div>
		<!-- conteudo -->

<?php
	require_once __DIR__.'/../../rodape.php';
?>
