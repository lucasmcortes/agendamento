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
					tituloPagina('cobranças');

					echo "<div style='min-width:100%;max-width:100%;display:inline-block;'>";
						Icone('vertodascobrancas','todas as faturas','verfaturaicon');
						Icone('veramaberto','recebíveis','addpagamentoicon');
					echo "</div>";
					echo "
						<script>
							$('#veramaberto').on('click',function () {
								window.location.href='".$dominio."/painel/cobrancas/aberto';
							});
							$('#vertodascobrancas').on('click',function () {
								window.location.href='".$dominio."/painel/cobrancas';
							});
						</script>
					";
				?>

				<p id='retorno' class='retorno' style='margin:1.8% auto;'>
			                Encontre o cliente pelo nome, CPF, rg, telefone, email, placa ou cadastre um novo cliente
			        </p> <!-- retorno -->

				<div>
					<div id='clientewrap' style='min-width:72%;max-width:72%;margin:3px auto;display:inline-block;'>
	                                        <label>cliente</label>
	                                        <div id='clienteinner' style='min-width:100%;max-width:100%;display:inline-block;'>
	                                                <input type='text' id='cliente' placeholder='cliente'></input>
	                                        </div>
	                                </div> <!-- clientewrap -->

	                                <div id='clienteresult' style='min-width:100%;max-width:100%;display:inline-block;'>
	                                        <p id='clienteresultp' style='display:none;min-width:100%;max-width:100%;text-align:left;padding:13px;'>
	                                        </p>
	                                </div>

	                                <script>
	                                        typingTimer = '';
	                                        doneTypingInterval = 555;
	                                        $('#cliente').on('keyup', function () {
	                                                clearTimeout(typingTimer);
	                                                typingTimer = setTimeout(doneTyping, doneTypingInterval);
	                                        });
	                                        $('#cliente').on('keydown', function () {
	                                                $('#retorno').empty();
	                                                clearTimeout(typingTimer);
	                                        });
	                                        function doneTyping () {
	                                                if ($('#cliente').val()!='') {
	                                                        BuscaCobrancascliente();
	                                                } else { /* query vazio */
	                                                        $('#retorno').html('Encontre o cliente pelo nome, CPF, rg, telefone, email ou placa');
								$('#containertabela').empty();
	                                                        $('#clienteresultp').empty();
	                                                        $('#clienteresultp').css({
	                                                                'display':'none',
	                                                                'background-color':'transparent'
	                                                        });
	                                                }
	                                        }
	                                        function BuscaCobrancascliente() {
	                                                $.ajax({
	                                                        type: 'POST',
	                                                        url: '<?php echo $dominio ?>/painel/cobrancas/cliente/includes/buscacobrancacliente.inc.php',
	                                                        data: { cliente: $('#cliente').val() },
					                        beforeSend: function() {
					                                $('#clienteresultp').html('');
					                                $('#clienteresultp').html("<div id='enviando' style='display:inline-block;'><div id='enviandospinner'></div></div>");
					                        },
	                                                        success: function(cliente) {
	                                                                $('#retorno').html(cliente['resposta']);

					                                $('#clienteresultp').empty();
					                                $('#clienteresultp').css('text-align','left');
	                                                                $('#clienteresultp').css('display','inline-block');
	                                                                $('#clienteresultp').css('background-color','transparent');

	                                                                if (!$('#clienteresultp').html().includes('listando')) {
										$('#containertabela').html(cliente['tabela']);

	                                                                        $('#clienteresultp').on('click',function() {
	                                                                                $('#clienteresultp').html(cliente['resposta']);
	                                                                                $('#clientewrap').css('display','none');
	                                                                                $(this).css('background-color','var(--verde)');
	                                                                                valcliente = $('#clienteresultspan').data('lid');
											console.log(cliente['cobrancas']);
	                                                                                $(this).on('click', function () {
	                                                                                        $('#clienteresultp').css('background-color','transparent');
	                                                                                        $('#clientewrap').css('display','inline-block');
	                                                                                        $('#retorno').empty();
	                                                                                        $('#clienteresultp').empty();
	                                                                                });
	                                                                        });
	                                                                }
	                                                        }
	                                                });
	                                        }
	                                </script>

					<!-- container -->
					<div id='containertabela' style='min-width:72%;max-width:72%;margin:0 auto;display:inline-block;overflow:auto;'>
					</div>
					<!-- container -->

				</div>
			</div>
                </div>
		<!-- conteudo -->

<?php
	require_once __DIR__.'/../../../rodape.php';
?>
