<?php

	include_once __DIR__.'/includes/setup.inc.php';

?>

<div id='outerwrapsuperiorlogado'>
	<div class='optionsmenuwrap'>
		<div class='optionsmenuinnerwraplogado'>
			<p id='fecharsuperior' style='display:none;'></p>
			<?php
				// echo "<div class='dropdownlogado'> <!-- wrap -->
				// 	<div id='atalhodisponibilidade' class='iconemenu atalhosuperior'>
				// 		<img class='iconemenu' style='cursor:pointer;max-width:18px;' src='".$dominio."/img/calendarioformicon.png'></img>
				// 		<p class='legendaatalhosuperior'>Disponibilidade</p>
				// 	</div>
				// 	<script>
				// 		$('#atalhodisponibilidade').on('click',function () {
				// 			calendarioPop(3,'fundamental',0);
				// 		});
				// 	</script>
				// 	</div> <!-- wrap -->
				// ";

				$permissao = new Conforto($uid);
				$permissao = $permissao->Permissao('modificacao');
				if ($permissao===true) {
					echo "<div class='dropdownlogado'> <!-- wrap -->";
					IconeMenu('atalhoclientes','clientes','clientes-preto');
					echo "
						<div class='submenu'>
							<div class='submenuinner'>
								<img class='iconemenu' src='".$dominio."/img/clientes-preto.png'></img>
								<a href='".$dominio."/painel/clientes'>clientes</a>
							</div>
							<div class='submenuinner'>
								<img class='iconemenu' src='".$dominio."/img/addclientes-preto.png'></img>
								<a href='".$dominio."/painel/clientes/novo'>+ cliente</a>
							</div>
						</div>
						<script>
							if ( (document.title=='Clientes') || ((document.title=='Novo cliente')) ) {
								$('#atalhoclientes').css('background-color','var(--verdeclaro)');
								$('#atalhoclientes').find('img.iconemenu').attr('src','".$dominio."/img/clientes-branco.png');
							}
						</script>
						</div> <!-- wrap -->
					";
				} // permitido

				// echo "<div class='dropdownlogado'> <!-- wrap -->";
				// IconeMenu('atalhoagendamentos','agendamentos','agendar-preto');
				// echo "
				// 	<div class='submenu'>
				// 		<a href='".$dominio."/painel/agendamentos'>agendamentos</a>
				// 	</div>
				// 	<script>
				// 		if ( (document.title=='Agendamentos') ) {
				// 			$('#atalhoagendamentos').css('background-color','var(--verdeclaro)');
				// 			$('#atalhoagendamentos').find('img.iconemenu').attr('src','".$dominio."/img/competentes-branco.png');
				// 		}
				// 	</script>
				// 	</div> <!-- wrap -->
				// ";

				echo "<div class='dropdownlogado'> <!-- wrap -->";
				IconeMenu('atalhocompetentes','competentes','competentes-preto');
				echo "
					<div class='submenu'>
						<div class='submenuinner'>
							<img class='iconemenu' src='".$dominio."/img/competentes-preto.png'></img>
							<a href='".$dominio."/painel/competentes'>competentes</a>
						</div>
						<div class='submenuinner'>
							<img class='iconemenu' src='".$dominio."/img/addcompetentes-preto.png'></img>
							<a href='".$dominio."/painel/competentes/novo'>+ competente</a>
						</div>

						<!-- <a href='".$dominio."/painel/competentes/calendario'>calend??rio de competentes</a> -->
					</div>
					<script>
						if ( (document.title=='Competentes') || (document.title=='Novo competente') || (document.title=='Calend??rio de competentes') ) {
							$('#atalhocompetentes').css('background-color','var(--verdeclaro)');
							$('#atalhocompetentes').find('img.iconemenu').attr('src','".$dominio."/img/competentes-branco.png');
						}
					</script>
					</div> <!-- wrap -->
				";

				// echo "<div class='dropdownlogado'> <!-- wrap -->";
				// IconeMenu('atalhoalugueis','alugu??is','alugueisicon');
				// echo "
				// 	<div class='submenu'>
				// 		<a href='".$dominio."/painel/alugueis/novo'>novo aluguel</a>
				// 		<a href='".$dominio."/painel/devolucoes/novo'>devolver competente</a>
				// 		<a href='".$dominio."/painel/alugueis'>alugu??is atuais</a>
				// 		<a href='".$dominio."/painel/alugueis/anteriores'>alugu??is anteriores</a>
				// 		<a href='".$dominio."/painel/alugueis/busca'>buscar aluguel</a>
				// 	</div>
				// 	<script>
				// 		if ( (document.title=='Alugu??is') || ((document.title=='Novo aluguel')) || (document.title=='Alugu??is anteriores') ) {
				// 			$('#atalhoalugueis').css('background-color','var(--verdeclaro)');
				// 			$('#atalhoalugueis').find('img.iconemenu').attr('src','".$dominio."/img/alugueisbrancoicon.png');
				// 		}
				// 	</script>
				// 	</div> <!-- wrap -->
				// ";

				// echo "<div class='dropdownlogado'> <!-- wrap -->";
				// IconeMenu('atalhoreservas','reservas','reservasicon');
				// echo "
				// 	<div class='submenu'>
				// 		<a href='".$dominio."/painel/alugueis/novo'>nova reserva</a>
				// 		<a href='".$dominio."/painel/reservas'>reservas pra hoje</a>
				// 		<a href='".$dominio."/painel/reservas/futuras'>reservas futuras</a>
				// 		<a href='".$dominio."/painel/reservas/anteriores'>reservas anteriores</a>
				// 		<a href='".$dominio."/painel/reservas/canceladas'>reservas canceladas</a>
				// 	</div>
				// 	<script>
				// 		if ( (document.title=='Reservas para hoje') || (document.title=='Reservas anteriores') || (document.title=='Reservas canceladas') || (document.title=='Reservas futuras')) {
				// 			$('#atalhoreservas').css('background-color','var(--verdeclaro)');
				// 			$('#atalhoreservas').find('img.iconemenu').attr('src','".$dominio."/img/reservasbrancoicon.png');
				// 		}
				// 	</script>
				// 	</div> <!-- wrap -->
				// ";

				// echo "<div class='dropdownlogado'> <!-- wrap -->";
				// IconeMenu('atalhomanutencoes','manuten????es','manutencoesicon');
				// echo "
				// 	<div class='submenu'>
				// 		<a href='".$dominio."/painel/manutencoes/novo'>nova manuten????o</a>
				// 		<a href='".$dominio."/painel/retornos/novo'>novo retorno</a>
				// 		<a href='".$dominio."/painel/manutencoes'>manuten????es ativas</a>
				// 		<a href='".$dominio."/painel/retornos'>manuten????es anteriores</a>
				// 		<a href='".$dominio."/painel/despesas'>despesas</a>
				// 	</div>
				// 	<script>
				// 		if ( (document.title=='Manuten????es') || ((document.title=='Nova manuten????o')) || (document.title=='Despesas') || (document.title=='Retornos') || ((document.title=='Novo retorno')) ) {
				// 			$('#atalhomanutencoes').css('background-color','var(--verdeclaro)');
				// 			$('#atalhomanutencoes').find('img.iconemenu').attr('src','".$dominio."/img/manutencoesbrancoicon.png');
				// 		}
				// 	</script>
				// 	</div> <!-- wrap -->
				// ";

				// echo "<div class='dropdownlogado'> <!-- wrap -->";
				// IconeMenu('atalhocobrancas','cobran??as','cobrancasicon');
				// echo "
				// 	<div class='submenu'>
				// 		<a href='".$dominio."/painel/cobrancas'>todas as cobran??as</a>
				// 		<a id='relatoriopagamentos' href='javascript:void(0);'>relat??rio mensal</a>
				// 		<a href='".$dominio."/painel/cobrancas/aberto'>cobran??as em aberto</a>
				// 		<a href='".$dominio."/painel/cobrancas/cliente'>cobran??as por cliente</a>
				// 		<a href='".$dominio."/painel/cobrancas/recibo'>novo recibo</a>
				// 		<a href='".$dominio."/painel/cobrancas/recibo/emitidos'>recibos emitidos</a>
				// 	</div>
				// 	<script>
				// 		if ( (document.title=='Cobran??as') || (document.title=='Cobran??as em aberto') || (document.title=='Cobran??as por cliente') ) {
				// 			$('#atalhocobrancas').css('background-color','var(--verdeclaro)');
				// 			$('#atalhocobrancas').find('img.iconemenu').attr('src','".$dominio."/img/cobrancasbrancoicon.png');
				// 		}
				//
				// 		$('#relatoriopagamentos').on('click',function () {
				// 			loadFundamental('".$dominio."/painel/cobrancas/relatorio/relatoriopopup.inc.php');
				// 		});
				// 	</script>
				// 	</div> <!-- wrap -->
				// ";

				$permissao = new Conforto($uid);
				$permissao = $permissao->Permissao('modificacao');
				if ($permissao===true) {

					echo "<div class='dropdownlogado'> <!-- wrap -->";
					IconeMenu('atalhosconfiguracoes','configura????es','configuracoesicon');
					echo "
						<div class='submenu'>
							<div class='submenuinner'>
								<img class='iconemenu' src='".$dominio."/img/minhaconta-preto.png'></img>
								<a href='".$dominio."/minhaconta'>minha conta</a>
							</div>
							<div class='submenuinner'>
								<img class='iconemenu' src='".$dominio."/img/configuracoesicon.png'></img>
								<a href='".$dominio."/painel/configuracoes'>configura????es</a>
							</div>
							<div class='submenuinner'>
								<img class='iconemenu' src='".$dominio."/img/relatorioicon.png'></img>
								<a href='".$dominio."/painel/competentes/relatorio/geral'>relat??rio geral</a>
							</div>
							<div class='submenuinner'>
								<img class='iconemenu' src='".$dominio."/img/suporte-preto.png'></img>
								<a id='suportesuperiorsistema' href='#'>suporte</a>
							</div>
					";
						//	<a href='".$dominio."/painel/administradores'>administradores</a>
						//	<a href='".$dominio."/painel/administradores/novo'>adicionar administrador</a>
					echo "
						</div>
						<script>

			                                $('#suportesuperiorsistema').on('click', function() {
			                                        loadFundamental('".$dominio."/includes/suporte/suportepopup.inc.php');
			                                });
							if ( (document.title=='Configura????es') || (document.title=='Contato') || ((document.title=='Minha Conta')) || (document.title=='Relat??rio geral') ) {
								$('#atalhosconfiguracoes').css('background-color','var(--verdeclaro)');
								$('#atalhosconfiguracoes').find('img.iconemenu').attr('src','".$dominio."/img/configuracoesbrancoicon.png');
							}
						</script>
						</div> <!-- wrap -->
					";
				} // permissao

				if (isset($_SESSION['ag_id'])) {
					echo '
						<div id="logouticon" class="iconemenu atalhosuperior" style="padding-top:55%;">
							<img class="iconemenu"" src="'.$dominio.'/img/logouticon.png"></img>
							<p class="legendaatalhosuperior">Desconectar</p>
						</div>
						<script>
				                        $("#logouticon").on("click", function () {
				                                window.location.href = "'.$dominio.'/entrar/logout/";
				                        });
						</script>
					';
				} // isset uid
			?>
		</div> <!-- optionsmenuinnerwrap -->
	</div> <!-- optionsmenuwrap -->
</div>

<script>
	abreSuperior();
	fechaSuperior();
</script>
