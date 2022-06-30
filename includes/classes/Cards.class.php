<?php

	include_once __DIR__.'/../setup.inc.php';

	class Cards extends ConsultaDatabase {
		public $uid;

		public function __construct($user) {
			$this->uid = $user;
		}

		public function RecebiveisPainel() {
			global $dominio;

			$a=0;
			$cards = '';
			$emaberto = [];
			$listacobrancas = parent::ListaCobrancas();
			foreach ($listacobrancas as $cobranca) {
				if ($cobranca['valor']!=0) {
					if ($cobranca['tid']==0) {
						$devolucao = parent::DevolucaoId($cobranca['deid']);
						$aluguel = parent::AluguelInfo($devolucao['aid']);
						$parciais = new Conforto($this->uid);
						$parciais = $parciais->SomaParciais($cobranca['coid']);

						$pagamentosaluguel = new Conforto($this->uid);
						$pagamentosaluguel = $pagamentosaluguel->SomaPagamentosAluguel($aluguel['aid']);
						if ($cobranca['valor']-$parciais-$pagamentosaluguel>0) {
							$emaberto[] = $cobranca;

							$cliente = parent::clienteInfo($cobranca['lid']);
							if ($a<=3) {
								$cards .= "
									<div id='cobranca_".$cobranca['coid']."' class='competente cardslot'>
										<p class='grande'>
											".Dinheiro($cobranca['valor']-$parciais-$pagamentosaluguel)."
										</p>
										<p class='pequeno' style='color:var(--azulclaro);'>
											para receber de ".$cliente['nome']."
										</p>
									</div>
									<script>
										$('#cobranca_".$cobranca['coid']."').on('click', function() {
											coid = $(this).attr('id').split('_')[1];
											cobrancaFundamental(coid);
										});
									</script>
								";
							} // while
							$a++;
						} // valor da devolucao > valor pago no ato da reserva
					} // em aberto
				} // R$>0
			} // foreach

			return $this->recebiveis = array(
				'recentes'=>$cards,
				'quantidade'=>count($emaberto)
			);
		} // ManutencaoPainel

		public function AlugavelPainel($vid) {
			global $dominio;

			$competente = parent::competente($vid);

			echo "
				<div id='competente_".$competente['vid']."' class='competente cardslot'>
					<p class='grande'>
						".$competente['modelo']."
					</p>
					<p class='pequeno' style='font-weight:900;color:var(--azulclaro);'>
						está disponível para alugar
					</p>
				</div>
				<script>
					$('#competente_".$competente['vid']."').on('click', function() {
						vid = $(this).attr('id').split('_')[1];
						competenteFundamental(vid);
					});
				</script>
			";
		} // ManutencaoPainel

		public function AluguelPainel($aluguel) {
			$cliente = parent::clienteInfo($aluguel['lid']);
			$competente = parent::competente($aluguel['vid']);

			$reserva = parent::Reserva($aluguel['aid']);
			if ($reserva['reid']!=0) {
				$ativacao = parent::Ativacao($reserva['reid']);
				if ($ativacao['ativa']=='S') {
					$inicio = new DateTime($reserva['inicio']);
					$devolucao = new DateTime($reserva['devolucao']);
				} else {
					$inicio = new DateTime($aluguel['inicio']);
					$devolucao = new DateTime($aluguel['devolucao']);
				} // ativa
			} else {
				$inicio = new DateTime($aluguel['inicio']);
				$devolucao = new DateTime($aluguel['devolucao']);
			}// se é uma reserva

			echo "
				 <div id='aluguel_".$aluguel['aid']."' class='aluguel cardslot'>
					<p class='grande'>
						".$competente['modelo']."
					</p>
					<p class='grande'>
						".$cliente['nome']."
					</p>
					<p class='pequeno' style='font-weight:900;color:var(--azulclaro);'>
						alugado até o dia ".$devolucao->format('d/m/Y')." às ".$devolucao->format('H')."h
					</p>
				</div>
				<script>
					$('#aluguel_".$aluguel['aid']."').on('click', function() {
						aid = $(this).attr('id').split('_')[1];
						aluguelFundamental(aid, 1);
					});
				</script>
			";
		} // AluguelPainel

		public function ReservaPainel($reserva) {
			$cliente = parent::clienteInfo($reserva['lid']);
			$competente = parent::competente($reserva['vid']);
			$reservadata = parent::Reserva($reserva['aid']);
			$inicio = new DateTime($reservadata['inicio']);
			$devolucao = new DateTime($reservadata['devolucao']);

			echo "
				<div id='reserva_".$reserva['aid']."' class='reserva cardslot'>
					<p class='grande'>
						".$competente['modelo']."
					</p>
					<p class='grande'>
						".$cliente['nome']."
					</p>
					<p class='pequeno' style='font-weight:900;color:var(--azulclaro);'>
						reservado para o dia ".$inicio->format('d/m/Y')." às ".$inicio->format('H')."h
					</p>
				</div>
				<script>
					$('#reserva_".$reserva['aid']."').on('click', function() {
						aid = $(this).attr('id').split('_')[1];
						reservaFundamental(aid, 1);
					});
				</script>
			";
		} // ReservaPainel

		public function Cardcompetente($vid) {
			global $dominio;
			$confirmar = 0;

			$this->data_agora = date_create()->format('Y-m-d H:i:s.u');
			$this->data_de_hoje = new DateTime($this->data_agora);

			$agendamento_pro_competente[$vid]['reserva'] = [];
			$agendamento_pro_competente[$vid]['aluguel'] = [];
			$agendamento_pro_competente[$vid]['manutencao'] = [];
			$agendamento_pro_competente[$vid]['manutencaoagendada'] = [];

			$competente = parent::competente($vid);
			$alugueiscompetente = parent::ListaAlugueiscompetente($vid);
			if ($alugueiscompetente[0]['aid']!=0) {
				foreach ($alugueiscompetente as $aluguel) {
					if ($aluguel['aid']!=0) {
						$consultaaluguel = parent::AluguelInfo($aluguel['aid']);
						$reserva = parent::Reserva($aluguel['aid']);
						if ($reserva['reid']!=0) {
							$ativacao = parent::Ativacao($reserva['reid']);
							if ($ativacao['ativa']=='S') {
								$devolucao = parent::Devolucao($reserva['aid']);
								if ($devolucao['deid']==0) {
									$agendamento_pro_competente[$vid]['reserva'][] = array(
										'aid'=>$reserva['aid'],
										'confirmada'=>$reserva['confirmada'],
										'inicio'=>new DateTime($reserva['inicio']),
										'devolucao'=>new DateTime($reserva['devolucao'])
									);
								} // sem devolver ainda
							} // reserva ativa
						} else {
							$devolucao = parent::Devolucao($aluguel['aid']);
							if ($devolucao['deid']==0) {
								$agendamento_pro_competente[$vid]['aluguel'][] = array(
									'aid'=>$aluguel['aid'],
									'inicio'=>new DateTime($aluguel['inicio']),
									'devolucao'=>new DateTime($aluguel['devolucao'])
								);
							} // sem devolver ainda
						} // é reserva
					} // aluguel existe
				} // foreach listaalugueiscompetente
			} // aid >0

			$manutencoes = parent::competenteManutencoes($vid);
			if ($manutencoes[0]['mid']!=0) {
				foreach ($manutencoes as $manutencao) {
					$motivo = parent::competenteMotivo($manutencao['motivo']);
					$retorno = parent::Retorno($manutencao['mid']);
					if ($retorno['rid']==0) {
						$reservamanutencao = parent::ManutencaoReserva($manutencao['mid']);
						if ($reservamanutencao['mreid']!=0) {
							$manutencaoativa = parent::ManutencaoAtivacao($reservamanutencao['mreid']);
							if ($manutencaoativa['ativa']=='S') {
								$agendamento_pro_competente[$vid]['manutencaoagendada'][] = array(
									'mid'=>$manutencao['mid'],
									'confirmada'=>$reservamanutencao['confirmada'],
									'motivo'=>$motivo,
									'inicio'=>new DateTime($reservamanutencao['inicio']),
									'devolucao'=>new DateTime($reservamanutencao['devolucao'])
								);
							} // ta ativa a reserva da manutencao
						} else {
							$agendamento_pro_competente[$vid]['manutencao'][] = array(
								'mid'=>$manutencao['mid'],
								'motivo'=>$motivo,
								'inicio'=>new DateTime($manutencao['inicio']),
								'devolucao'=>new DateTime($manutencao['devolucao'])
							);
						} // se é agendamento
					} // ainda está em manutenção
				} // foreach manutencao
			} // mid > 0

			$revisao_dez_mil_km = new Conforto($this->uid);
			$revisao_dez_mil_km = $revisao_dez_mil_km->RevisaoDezKm($competente['vid']);
			if ($revisao_dez_mil_km!=0) {
				$iconerevisao = 'oi';
			} else {
				$iconerevisao = '';
			} // fazer revisão dos 10k

			echo "
				<div id='competente_".$vid."' class='competente'>
			";

			echo "
				<p id='nome_".$competente['vid']."' class='grande'>
					".$competente['modelo']."
				</p>
				<p class='pequeno' style='color:var(--branco);margin-bottom:3px;'>
					".$competente['placa']."
				</p>
			";

			if ($iconerevisao!='') {
				echo "<script>";
				echo "$('#nome_".$competente['vid']."').append('<span class=\"info\" aria-label=\"revisar\"><img class=\"icone\" style=\"vertical-align:super;max-width:9px;display:inline-block;\" src=\"http://localhost/aluguel/aluguel/img/revisaricon.png\"></img></span>');";
				echo "</script>";
			} // se precisa revisar

			if ((count($agendamento_pro_competente[$vid]['reserva'])==0) && (count($agendamento_pro_competente[$vid]['aluguel'])==0) && (count($agendamento_pro_competente[$vid]['manutencao'])==0)) {
				if (count($agendamento_pro_competente[$vid]['manutencaoagendada'])==0) {
					echo "
						<p class='pequeno' style='color:var(--verde);'>
							• disponível para alugar
						</p>
						<script>
							$('#competente_".$vid."').css({
								'border': '5px solid var(--verde)',
								'border-top': '0',
								'border-left': '0',
								'border-right': '0',
								'padding-bottom': '3%'
							});
						</script>
					";
				} else {
					foreach ($agendamento_pro_competente[$vid]['manutencaoagendada'] as $agendamentocompetente) {
						if ($agendamentocompetente['inicio']->format('Y-m-d H:i')>$this->data_de_hoje->format("Y-m-d H:i")) {
							echo "
								<p class='pequeno' style='color:var(--verde);'>
									• disponível para alugar
								</p>
								<script>
									$('#competente_".$vid."').css({
										'border': '5px solid var(--verde)',
										'border-top': '0',
										'border-left': '0',
										'border-right': '0',
										'padding-bottom': '3%'
									});
								</script>
							";
						} else {
							if ($agendamentocompetente['confirmada']==0) {
								// echo "
								// 	<p class='pequeno' style='color:var(--verde);'>
								// 		• disponível para alugar
								// 	</p>
								// 	<script>
								// 		$('#competente_".$vid."').css({
								// 			'border': '5px solid var(--verde)',
								// 			'border-top': '0',
								// 			'border-left': '0',
								// 			'border-right': '0',
								// 			'padding-bottom': '3%'
								// 		});
								// 	</script>
								// ";
							} // se confirmou
						} // se é antes que iniciou
					} // foreach agendamento
				} // agendamento de manutencao
			}

			krsort($agendamento_pro_competente[$vid]['reserva']);
			foreach ($agendamento_pro_competente[$vid]['reserva'] as $reservacompetente) {
				if ($reservacompetente['inicio']->format('Y-m-d H:i')<=$this->data_de_hoje->format("Y-m-d H:i")) {
					if ($reservacompetente['devolucao']->format('Y-m-d H:i')>$this->data_de_hoje->format("Y-m-d H:i")) {
						// reserva acontecendo
						if ($reservacompetente['confirmada']==0) {
							$confirmar = 1;
							$aluguel = parent::AluguelInfo($reservacompetente['aid']);
							$cliente = parent::clienteInfo($aluguel['lid']);
							echo "
								<p class='pequeno' style='color:var(--azulclaro);'>
									• o competente foi retirado por ".$cliente['nome']." para a reserva do dia ".$reservacompetente['inicio']->format('d/m/Y')." às ".$reservacompetente['inicio']->format('H')."h?
								</p>
								<div id='confirmacao_".$reservacompetente['aid']."_wrap' class='confirmacaocardwrap'></div>

								<script>
									verReserva = document.createElement('div');
									$('#confirmacao_".$reservacompetente['aid']."_wrap').append(verReserva);
									verReserva.classList.add('confirmacaocard');
									verReserva.setAttribute('id', 'visualizar_".$reservacompetente['aid']."');
									$(verReserva).append('Ver reserva');
									$('#visualizar_".$reservacompetente['aid']."').on('click',function() {
										aid = $(this).attr('id').split('_')[1];
										reservaFundamental(aid, 1);
									});

									novaConfirmacao = document.createElement('div');
									$('#confirmacao_".$reservacompetente['aid']."_wrap').append(novaConfirmacao);
									novaConfirmacao.classList.add('confirmacaocard');
									novaConfirmacao.setAttribute('id', 'confirmar_".$reservacompetente['aid']."');
									$(novaConfirmacao).append('Confirmar');
									$('#confirmar_".$reservacompetente['aid']."').on('click',function() {
										confirmarReserva('".$reservacompetente['aid']."','vestimenta');
									});

									novoCancelamento = document.createElement('div');
									$('#confirmacao_".$reservacompetente['aid']."_wrap').append(novoCancelamento);
									novoCancelamento.classList.add('confirmacaocard');
									novoCancelamento.setAttribute('id', 'cancelar_".$reservacompetente['aid']."');
									$(novoCancelamento).append('Cancelar');
									$('#cancelar_".$reservacompetente['aid']."').on('click',function() {
										cancelaReserva('".$reservacompetente['aid']."','vestimenta');
									});
								</script>
							";
						} else {
							echo "
								<p class='pequeno' style='color:var(--azulclaro);'>
									• alugado até ".$reservacompetente['devolucao']->format('d/m/Y')." às ".$reservacompetente['devolucao']->format('H')."h
								</p>
							";
						} // confirmada
					} else {
						// devolver
						echo "
							<p class='pequeno' style='color:var(--azulclaro);'>
								• alugado com prazo ultrapassado desde ".$reservacompetente['devolucao']->format('d/m/Y')." às ".$reservacompetente['devolucao']->format('H')."h
							</p>
						";
					} // devolucao depois de agora
				} else {
					// reserva pra depois
					$dia = $reservacompetente['inicio']->format('d/m/Y');
					if ($reservacompetente['inicio']->format('Y-m-d')==$this->data_de_hoje->format('Y-m-d')) {
						$dia = '<b>hoje</b>';
					} // se o início da reserva é hoje
					echo "
						<p class='pequeno' style='color:var(--amarelomesmo);'>
							• reservado à partir de ".$dia." às ".$reservacompetente['inicio']->format('H')."h
						</p>
					";
					if (strpos($dia,'hoje')>0) {
						echo '
							<p class="pequeno" style="color:var(--amarelomesmo);" id="contagem_'.$reservacompetente['aid'].'"></p>

							<script>
								contagem("'.$reservacompetente['inicio']->format('Y-m-d H:i').'","contagem_'.$reservacompetente['aid'].'","'.$vid.'");
							</script>
						';
						// echo "
						// 	<div id='lembrete_".$reservacompetente['aid']."_wrap' class='icone lembranca'>
						// 		<span class='info' aria-label='enviar lembrete'>
						// 			<img class='icone' src='".$dominio."/img/.png'></img>
						// 		</span>
						// 	</div>
						// 	<script>
						// 		enviarLembrete = document.createElement('div');
						// 		$('#lembrete_".$reservacompetente['aid']."_wrap').append(enviarLembrete);
						// 		enviarLembrete.classList.add('lembretecard');
						// 		enviarLembrete.classList.add('lembranca');
						// 		enviarLembrete.setAttribute('id', 'lembrar_".$reservacompetente['aid']."');
						// 		$(enviarLembrete).append('Enviar lembrete');
						// 		$('.lembranca').on('click',function() {
						// 			aid = $(this).attr('id').split('_')[1];
						// 			lembreteReserva(aid,'vestimenta');
						// 		});
						// 		/*$('#lembrete_".$reservacompetente['aid']."_wrap').insertAfter($('#card_v_".$vid."'));*/
						// 		$('#card_v_".$vid."').append($('#lembrete_".$reservacompetente['aid']."_wrap'));
						// 	</script>
						// ";
					} // é hoje
				} // inicio antes de agora
			} // foreach agendamento

			foreach ($agendamento_pro_competente[$vid]['aluguel'] as $aluguelcompetente) {
				if ($aluguelcompetente['inicio']->format('Y-m-d H:i')<=$this->data_de_hoje->format("Y-m-d H:i")) {
					if ($aluguelcompetente['devolucao']->format('Y-m-d H:i')>$this->data_de_hoje->format("Y-m-d H:i")) {
						// aluguel acontecendo
						echo "
							<p class='pequeno' style='color:var(--azulclaro);'>
								• alugado até ".$aluguelcompetente['devolucao']->format('d/m/Y')." às ".$aluguelcompetente['devolucao']->format('H')."h
							</p>
						";
					} else {
						// devolver
						echo "
							<p class='pequeno' style='color:var(--azulclaro);'>
								• alugado com prazo ultrapassado desde ".$aluguelcompetente['devolucao']->format('d/m/Y')." às ".$aluguelcompetente['devolucao']->format('H')."h
							</p>
						";
					} // devolucao depois de agora
				} // inicio antes de agora
			} // foreach agendamento

			foreach ($agendamento_pro_competente[$vid]['manutencao'] as $manutencaocompetente) {
				if ($manutencaocompetente['inicio']->format('Y-m-d H:i')<=$this->data_de_hoje->format("Y-m-d H:i")) {
					if ($manutencaocompetente['devolucao']->format('Y-m-d')>$this->data_de_hoje->format("Y-m-d")) {
						// manutenção acontecendo
						echo "
							<p class='pequeno' style='color:var(--rosa);'>
								• em manutenção (".mb_strtolower($manutencaocompetente['motivo']).") com previsão de retorno em ".$manutencaocompetente['devolucao']->format('d/m/Y')."
							</p>
						";
					} else if ($manutencaocompetente['devolucao']->format('Y-m-d')==$this->data_de_hoje->format("Y-m-d")) {
						echo "
							<p class='pequeno' style='color:var(--rosa);'>
								• manutenção (".mb_strtolower($manutencaocompetente['motivo']).") com prazo previsto para devolução <b>hoje</b>
							</p>
						";
					} else {
						echo "
							<p class='pequeno' style='color:var(--rosa);'>
								• manutenção (".mb_strtolower($manutencaocompetente['motivo']).") ultrapassou o prazo previsto para devolução
							</p>
						";
					} // previsao devolucao
				} // data manutencao
			} // foreach agendamento

			foreach ($agendamento_pro_competente[$vid]['manutencaoagendada'] as $manutencaocompetente) {
				if ($manutencaocompetente['inicio']->format('Y-m-d H:i')<=$this->data_de_hoje->format("Y-m-d H:i")) {
					// manutenção acontecendo
					if ($manutencaocompetente['confirmada']==0) {
						$confirmar = 1;
						$manutencao = parent::Manutencao($manutencaocompetente['mid']);
						echo "
							<p class='pequeno' style='color:var(--azulclaro);'>
								• o competente foi para a manutenção em ".$manutencao['estabelecimento']."?
							</p>
							<div id='mconfirmacao_".$manutencaocompetente['mid']."_wrap' class='confirmacaocardwrap'></div>

							<script>
								novaConfirmacao = document.createElement('div');
								$('#mconfirmacao_".$manutencaocompetente['mid']."_wrap').append(novaConfirmacao);
								novaConfirmacao.classList.add('confirmacaocard');
								novaConfirmacao.setAttribute('id', 'mconfirmar_".$manutencaocompetente['mid']."');
								$(novaConfirmacao).append('Confirmar');
								$('#mconfirmar_".$manutencaocompetente['mid']."').on('click',function() {
									confirmarReservaManutencao('".$manutencaocompetente['mid']."','vestimenta');
								});

								novoCancelamento = document.createElement('div');
								$('#mconfirmacao_".$manutencaocompetente['mid']."_wrap').append(novoCancelamento);
								novoCancelamento.classList.add('confirmacaocard');
								novoCancelamento.setAttribute('id', 'mcancelar_".$manutencaocompetente['mid']."');
								$(novoCancelamento).append('Cancelar');
								$('#mcancelar_".$manutencaocompetente['mid']."').on('click',function() {
									cancelaReservaManutencao('".$manutencaocompetente['mid']."','vestimenta');
								});
							</script>
						";
					} else {
						if ($manutencaocompetente['devolucao']->format('Y-m-d')>$this->data_de_hoje->format("Y-m-d")) {
							// manutenção acontecendo
							echo "
								<p class='pequeno' style='color:var(--rosa);'>
									• em manutenção (".mb_strtolower($manutencaocompetente['motivo']).") com previsão de retorno em ".$manutencaocompetente['devolucao']->format('d/m/Y')."
								</p>
							";
						} else if ($manutencaocompetente['devolucao']->format('Y-m-d')==$this->data_de_hoje->format("Y-m-d")) {
							echo "
								<p class='pequeno' style='color:var(--rosa);'>
									• manutenção (".mb_strtolower($manutencaocompetente['motivo']).") com prazo previsto para devolução <b>hoje</b>
								</p>
							";
						} else {
							echo "
								<p class='pequeno' style='color:var(--rosa);'>
									• manutenção (".mb_strtolower($manutencaocompetente['motivo']).") ultrapassou o prazo previsto para devolução
								</p>
							";
						} // previsao devolucao
					} // confirmada
				} else {
					echo "
						<p class='pequeno' style='color:var(--rosa);'>
							• manutenção agendada para o dia ".$manutencaocompetente['inicio']->format('d/m/Y')." (".mb_strtolower($manutencaocompetente['motivo']).")
						</p>
					";

				} // data manutencao
			} // foreach agendamento

			echo "
				</div>
			";

			if ($confirmar==0) {
				echo "
					<script>
						$('#card_v_".$vid."').on('click', function() {
							vid = $(this).attr('id').split('_')[2];
							competenteFundamental(vid);
						});
					</script>
				";
			} // se não precisa confirmar deixa clicar e virar vinfo
		} // Cardcompetente

		public function ManutencaoPainel($manutencao) {
			global $dominio;

			echo "
				<div id='manutencao_".$manutencao['mid']."' class='manutencao cardslot'>
					<p class='grande'>
						".$manutencao['competente']."
					</p>
					<p class='grande'>
						".$manutencao['estabelecimento'].",
					</p>
					<p class='pequeno' style='font-weight:900;color:var(--azulclaro);'>
						".mb_strtolower($manutencao['status'])." desde ".$manutencao['data']->format('d/m/Y')." às ".$manutencao['data']->format('H')."h
					</p>
				</div>
				<script>
					$('#manutencao_".$manutencao['mid']."').on('click', function() {
						mid = $(this).attr('id').split('_')[1];
						manutencaoFundamental(mid);
					});
				</script>
			";
		} // ManutencaoPainel

	} // conexao

?>
