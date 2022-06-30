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
					tituloPagina('cobranças');

					echo "<div style='min-width:100%;max-width:100%;display:inline-block;'>";
						Icone('buscacliente','buscar por cliente','buscaclienteicon');
						Icone('veramaberto','recebíveis','addpagamentoicon');
					echo "</div>";
					echo "
						<script>
							$('#buscacliente').on('click',function () {
								window.location.href='".$dominio."/painel/cobrancas/cliente';
							});
							$('#veramaberto').on('click',function () {
								window.location.href='".$dominio."/painel/cobrancas/aberto';
							});
						</script>
					";
				?>

                                <div style='min-width:100%;max-width:100%;display:inline-block;'>
					<?php
						$cobrancasComPreco = [];
						$listacobrancas = new ConsultaDatabase($uid);
						$listacobrancas = $listacobrancas->ListaCobrancas();
						if ($listacobrancas[0]['coid']!=0) {
							$paginas = new Conforto($uid);
							$paginas = $paginas->Paginacao($listacobrancas);
							foreach ($listacobrancas as $cobranca) {
								if ($cobranca['valor']!=0) {
									$cobrancasComPreco[] = $cobranca;
								} // R$ > 0
							} // foreach
						} // coid > 0

						$filtro = new Conforto($uid);
						$filtro = $filtro->Exibicao($cobrancasComPreco);
						echo $filtro['botoes'];

						if ($filtro['i']>0) {
							$paginas = new Conforto($uid);
							$paginas = $paginas->Paginacao($filtro['itens']);
							echo "
								<div style='min-width:90%;max-width:90%;display:inline-block;margin:1.8% auto;'>
								<!-- container -->
						                <div style='min-width:100%;max-width:100%;margin:0 auto;display:inline-block;overflow:auto;'>
							";
							foreach ($paginas['itens'] as $cobranca) {
								$cliente = new ConsultaDatabase($uid);
								$cliente = $cliente->clienteInfo($cobranca['lid']);

								$residual = new Conforto($uid);
								$residual = $residual->Residual($cobranca['coid']);

								echo "
									<div id='cobrancawrap_".$cobranca['coid']."' class='relatoriowrap'>
										<div class='slotrelatoriowrap'>
											<div class='slotrelatorio'>
												<p class='headerslotrelatorio'><b>cliente:</b></p>
												<p class='infoslotrelatorio'>".$cliente['nome']."</p>
											</div>
										</div>
										<div class='slotrelatoriowrap'>
											<div class='slotrelatorio'>
												<p class='headerslotrelatorio'><b>Valor da fatura:</b></p>
												<p class='infoslotrelatorio'>".Dinheiro($cobranca['valor'])."</p>
											</div>
										</div>
										<div class='slotrelatoriowrap'>
											<div class='slotrelatorio'>
												<p class='headerslotrelatorio'><b>Status:</b></p>
												<p class='infoslotrelatorio'>".$residual['status']."".$residual['valor']."</p>
											</div>
										</div>
									</div>
								";
							} // foreach
							echo "
							</div>
							<!-- container -->
							".$paginas['botoes']."
						</div>
							";
						} else {
							NenhumRegistro();
						} // i > 0
					?>

					<script>
						$('.relatoriowrap').on('click', function() {
							coid = $(this).attr('id').split('_')[1];
							cobrancaFundamental(coid);
						});
					</script>
                                </div>
	        	</div>
		</div>
		<!-- conteudo -->

<?php
	require_once __DIR__.'/../../rodape.php';
?>
