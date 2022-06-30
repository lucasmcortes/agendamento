<?php
	require_once __DIR__.'/cabecalho.php';
?>

<corpo>

	<!-- conteudo -->
	<div id='conteudo' class='conteudo' style='padding-top:3%;'>

		<div class='topouterwrap'>
			<div class='landingtopouterwrap'>
				<div class='landingtopwrap'>
					<div class='landingtoppwrap'>
						<p class='landingtopp'>
							Mais compreensão<br>nos seus agendamentos
						</p>
						<p class='descindexmenor' style='max-width:72%;'>
							Registre e gerencie seus agendamentos facilmente
						</p>
					</div>
				</div>
			</div>
		</div>

		<div style='margin:5% auto;'> <!-- linhas wrap -->
			<div class='linhaflex'>
				<img class='linhaimg' src='<?php echo $dominio ?>/img/2502221211.png'></img>
				<div class='sectiontexto'>
					<div class='passos'>
						<p class='descindex'>
							Clareza
						</p>
						<p class='descindexmenor'>
							Disponha seus agendamentos por dia e por responsável, com informações detalhadas sobre o cliente e a cronologia
						</p>
					</div>
				</div>
			</div>

			<div class='linhaflex'>
				<div class='sectiontexto'>
					<div class='passos'>
						<p class='descindex'>
							Facilidade
						</p>
						<p class='descindexmenor'>
							Registre clientes e entre de contato em um toque à partir dos agendamentos
						</p>
					</div>
				</div>
				<img class='linhaimg' src='<?php echo $dominio ?>/img/2502221241.png'></img>
			</div>

			<div class='linhaflex'>
				<img class='linhaimg' src='<?php echo $dominio ?>/img/2702221343.png'></img>
				<div class='sectiontexto'>
					<div class='passos'>
						<p class='descindex'>
							Juntos
						</p>
						<p class='descindexmenor'>
							Conte com a gente para tudo que precisar fazer na aplicação
						</p>
					</div>
				</div>
			</div>

			<div class='linhaflex'>
				<div class='sectiontexto'>
					<div class='passos'>
						<p class='descindex'>
							Agilidade
						</p>
						<p class='descindexmenor'>
							Tudo que você precisa saber sobre seus agendamentos com um toque
						</p>
					</div>
				</div>
				<img class='linhaimg' src='<?php echo $dominio ?>/img/2702221354.png'></img>
			</div>
		</div> <!-- linhaswrap -->

		<!-- preco card landing
		<div style='max-width:80%;margin:0 auto;'>
			<div class='precocardlandinginnerwrap'>
				<div class='landinglicencawrap'>
					<div>
						<div class='individualinnercabecalho'>
			                                <p class='cardcabecalhonome'>
			                                        Licença anual
			                                </p>
			                        </div>
					</div>
					<div class='individualinnercabecalhovalorwrap'>
						<div class='individualinnercabecalhovalor'>
							<p class='cardcabecalhovalor'>
								R$<?php //echo $preco_anual_vista; ?><span style='font-size:13px;'>/ano</span>
							</p>
						</div>
					</div>
				</div>
				<div style='flex:1;'>
					<div class='individualinnerwrap'>
						<div class='caracteristicaswrap'>
							<p class='individuallista'>
								Pagamento anual
							</p>
							<p class='individuallista'>
								Licença para uso de todos os recursos
							</p>
							<p class='individuallista'>
								Registro de competentes, clientes, aluguéis, reservas e manutenções
							</p>
							<p class='individuallista'>
								Registro de recebíveis antes, durante e depois da devolução do competente
							</p>
							<p class='individuallista'>
								Configurações personalizadas
							</p>
							<p class='individuallista'>
								Todas as informações do seus competentes disponíveis online 24h por dia
							</p>
							<p class='individuallista'>
								Relatórios e estatísicas da suas operações para consulta online e exportação em arquivos XLS
							</p>
							<p class='individuallista'>
								Checklist, contratos e promissórias geradas automaticamente para imprimir ou salvar em PDF
							</p>
						</div>
						<a class='individualcta' href='<?php //echo $dominio ?>/cadastro/'>
							Começar
						</a>
						<p style='text-align:center;background-color:var(--verde);font-size:12px;padding:3%;'>
							Cadastre-se grátis, comece a usar agora e pague só se quiser continuar usando após 30 dias.
						</p>
					</div>
				</div>
			</div>
		</div>
		preco card landing -->

		<!-- <div style='display:inline-block;margin:3%;'></div> -->

		<!-- preco card landing
		<div style='max-width:80%;margin:0 auto;'>
			<div class='precocardlandinginnerwrap'>
				<div class='landinglicencawrap'>
					<div>
						<div class='individualinnercabecalho'>
			                                <p class='cardcabecalhonome'>
			                                        Licença Vitalícia
			                                </p>
			                        </div>
					</div>
					<div class='individualinnercabecalhovalorwrap'>
						<div class='individualinnercabecalhovalor'>
							<p class='cardcabecalhovalor'>
								R$<?php //echo $preco_vital_vista; ?><span class='especificacaoperiodo'>licença vitalícia</span>
							</p>
						</div>
					</div>
				</div>
				<div style='flex:1;'>
					<div class='individualinnerwrap'>
						<div class='caracteristicaswrap'>
							<p class='individuallista'>
								Pagamento único
							</p>
							<p class='individuallista'>
								Licença para uso de todos os recursos
							</p>
							<p class='individuallista'>
								Registro de competentes, clientes, aluguéis, reservas e manutenções
							</p>
							<p class='individuallista'>
								Registro de recebíveis antes, durante e depois da devolução do competente
							</p>
							<p class='individuallista'>
								Configurações personalizadas
							</p>
							<p class='individuallista'>
								Todas as informações do seus competentes disponíveis online 24h por dia
							</p>
							<p class='individuallista'>
								Relatórios e estatísicas da suas operações para consulta online e exportação em arquivos XLS
							</p>
							<p class='individuallista'>
								Checklist, contratos e promissórias geradas automaticamente para imprimir ou salvar em PDF
							</p>
						</div>
						<a class='individualcta' href='<?php //echo $dominio ?>/cadastro/'>
							Começar
						</a>
						<p style='text-align:center;background-color:var(--verde);font-size:12px;padding:3%;'>
							Cadastre-se grátis, comece a usar agora e pague só se quiser continuar usando após 30 dias.
						</p>
					</div>
				</div>
			</div>
		</div>
		preco card landing -->

		<div class='bottomouterwrap'>
			<div class='landingtopouterwrap'>
				<div class='landingtopwrap'>
					<div class='landingbottompwrap'>
						<p class='landingbottomp'>
							Quer saber mais?
						</p>
						<a class='individualcta' href='<?php echo $dominio ?>/contato/'>
							Fale com a gente
						</a>
					</div>
				</div>
			</div>
		</div>

	</div>
	<!-- conteudo -->

	<script>
		$('#testedrive').on('click',function() {
			nome = $('#nomedrive').val()||0;
			email = $('#emaildrive').val()||0;
			$.ajax({
				type: 'POST',
				dataType: 'json',
				async: true,
				url: '<?php echo $dominio ?>/includes/testedrive.inc.php',
				data: {
					nomedrive: nome,
					emaildrive: email
				},
				success: function(testedrive) {
					if (testedrive['titulo']!='') {
						$('#testedrivetitle').html(testedrive['titulo'])
						$('.retorno').html(testedrive['descricao']);
						$('#formulariotestedrive').css('display','none');
						$('#testedrive').css('display','none');
						$('#testedriveinnerwrap').css('background-color','var(--verde)');
					} else {
						$('.retorno').html('Preencha os campos corretamente');
					}
				}
			});
		});
	</script>

<?php
	require_once __DIR__.'/rodape.php';
?>
