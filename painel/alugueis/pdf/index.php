<?php

	include_once __DIR__.'/../../../includes/setup.inc.php';

	if (isset($_SESSION['ag_id'])) {
		if ( (isset($_GET['aid'])) && (isset($_GET['tipo'])) ) {
			$aid = $_GET['aid'];
			$aluguel = new ConsultaDatabase($uid);
			$aluguel = $aluguel->AluguelInfo($aid);
			if ($aluguel['aid']!=0) {

				($aluguel['kilometragem']==1) ? $especificacao_km = 'Kilometragem livre.' : $especificacao_km = '<u><b>Fica definido o limite de '.Kilometragem($aluguel['kilometragem']).' rodados</b></u>, sendo somados <b>'.Dinheiro($configuracoes['preco_km']).' ('.ltrim(mb_strtolower(extenso($configuracoes['preco_km'])),' ').') por kilometro excedente</b>.';

				$cliente = new ConsultaDatabase($uid);
				$cliente = $cliente->clienteInfo($aluguel['lid']);

				$telefone = new Conforto($uid);
				$telefone = $telefone->FormatoTelefone($cliente['telefone'],'br');

				$locador = new ConsultaDatabase($uid);
				$locador = $locador->AdminInfo($aluguel['uid']);

				$competente = new ConsultaDatabase($uid);
				$competente = $competente->competente($aluguel['vid']);

				$categoria = new ConsultaDatabase($uid);
				$categoria = $categoria->competenteCategoria($competente['categoria']);
				switch ($categoria) {
					case 'Carro':
						$excedente = $configuracoes['excedente_carro'];
						break;
					case 'Moto':
						$excedente = $configuracoes['excedente_moto'];
						break;
					case 'Utilitário':
						$excedente = $configuracoes['excedente_utilitario'];
						break;
					default:
						$excedente = $configuracoes['excedente_carro'];
						break;
				} // diária excedente

				$dia = new DateTime($aluguel['data']);
				$inicio = new DateTime($aluguel['inicio']);
				$devolucao = new DateTime($aluguel['devolucao']);

				$reserva = new ConsultaDatabase($uid);
				$reserva = $reserva->Reserva($aluguel['aid']);
				if ($reserva['reid']!=0) {
					$atividade = new ConsultaDatabase($uid);
					$atividade = $atividade->Ativacao($reserva['reid']);
					if ($atividade['ativa']=='S') {
						$inicio = new DateTime($reserva['inicio']);
						$devolucao = new DateTime($reserva['devolucao']);
					} // ativa
				} // reserva
				$intervalo = $inicio->diff($devolucao);
				$prazohoras = $intervalo->h;
				$prazohoras = $prazohoras + ($intervalo->days*24);

				$limpezaexecutiva = new ConsultaDatabase($uid);
				$limpezaexecutiva = $limpezaexecutiva->LimpezaTipo($devolucao->format('Y-m-d H:is.u'),$configuracoes['preco_le']);
				$limpezacompleta = new ConsultaDatabase($uid);
				$limpezacompleta = $limpezacompleta->LimpezaTipo($devolucao->format('Y-m-d H:is.u'),$configuracoes['preco_lc']);
				$limpezacompletacommotor = new ConsultaDatabase($uid);
				$limpezacompletacommotor = $limpezacompletacommotor->LimpezaTipo($devolucao->format('Y-m-d H:is.u'),$configuracoes['preco_lm']);

				($competente['limpeza']=='S') ? $limpezaatual = 'limpo' : $limpezaatual = 'à lavar';

				$tipo = $_GET['tipo'];

				$tipo_pdf = ucfirst($tipo); // promissoria / checklist / contrato
				$pasta_pdf = '_'.mb_strtolower($tipo_pdf).'s';

				$criador_pdf = 'Associação Apoio';
				$autor_pdf = 'Associação Apoio' ;
				$titulo_pdf = 'Associação Apoio' ;
				$assunto_pdf = 'Associação Apoio';
				$palavraschave_pdf = 'Associação Apoio';

				$aos = str_replace(array(' real',' reais'),'',extenso($devolucao->format('d')));
				if (strpos($aos,'um')) {
					$aos = 'dia primeiro';
				} else {
					$aos = $aos.' dias';
				} // aos

				$contrato_numero = $aid+$acrescimoaid;

				require_once __DIR__.'/includes/'.$tipo.'.pdf.php';

				$filename = $tipo_pdf.'_'.$aid.'_'.date('YmdHis').'.pdf';
				$filelocation = __DIR__ . '/'.$pasta_pdf.'/';

				$fileNL = $filelocation."/".$filename;

				if (!file_exists($filelocation)) {
					mkdir($filelocation, 0755, true);
				}

				// exibe pdf
				//$pdf->Output($fileNL, 'FD'); // salva
				$pdf->Output($fileNL, 'I'); // exibe
			} else {
				redirectToLogin();
			} // aluguel > 0
		} else if (!isset($_GET['aid'])) {
		        redirectToLogin();
		} // get aid
	} else if (!isset($_SESSION['ag_id'])) {
	        redirectToLogin();
	} // uid

?>
