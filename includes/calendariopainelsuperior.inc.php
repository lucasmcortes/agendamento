<?php

        require_once __DIR__.'/setup.inc.php';

        unsetHoraDisponivel();

        $reposta = array(
                'agenda'=>'',
                'data'=>strftime('%A, %d de %B de %Y', strtotime($data)),
                'anterior'=>'',
                'posterior'=>''
        );

        if (isset($_POST['diaCalendarioSuperior'])) {
                $diaCalendarioSuperior = $_POST['diaCalendarioSuperior'];
                $mesCalendarioSuperior = $_POST['mesCalendarioSuperior'];
                $anoCalendarioSuperior = $_POST['anoCalendarioSuperior'];
                $competente = $_POST['competenteAtual'];

                $agenda = '';

                $dataCalendarioSuperior = new DateTime($anoCalendarioSuperior.'-'.$mesCalendarioSuperior.'-'.$diaCalendarioSuperior);
                $dataExtenso = strftime('%A, %d de %B de %Y', strtotime($dataCalendarioSuperior->format('Y-m-d')));
                $resposta['data'] = $dataCalendarioSuperior->format('d/m/Y');

                $diaAnterior = $dataCalendarioSuperior->modify('-1 day');
                $resposta['anterior'] = array(
                        'competente'=>$competente,
                        'dia'=>$diaAnterior->format('d'),
                        'mes'=>$diaAnterior->format('m'),
                        'ano'=>$diaAnterior->format('Y')
                );
                $dataCalendarioSuperior->modify('+1 day');

                $diaPosterior = $dataCalendarioSuperior->modify('+1 day');
                $resposta['posterior'] = array(
                        'competente'=>$competente,
                        'dia'=>$diaPosterior->format('d'),
                        'mes'=>$diaPosterior->format('m'),
                        'ano'=>$diaPosterior->format('Y')
                );
                $dataCalendarioSuperior->modify('-1 day');

                $horasAgendaveis = new Conforto($uid);
                $horasAgendaveis = $horasAgendaveis->horasAgendaveis();

                for ($i=0;$i<count($horasAgendaveis['horarios']);$i++) {
                        $horaMarcada = new Conforto($uid);
                        $horaMarcada = $horaMarcada->FormatoHora($horasAgendaveis['horarios'][$i]);

                        $horaAgendamento = explode('h',$horaMarcada);
                        $horarioDaHora = $horaAgendamento[0];
                        $minutoDaHora = $horaAgendamento[1];

                        $agendamentoCompleto = $dataCalendarioSuperior->format('Y').'-'.$dataCalendarioSuperior->format('m').'-'.$dataCalendarioSuperior->format('d').'_'.$horarioDaHora.'-'.$minutoDaHora;

                        $agenda .= "
                                <div class='linhahora'>
                                        <div class='horadodia'>
                                                <span class='info' aria-label='".$horaMarcada."' data-horamarcada='".$agendamentoCompleto."' data-fila='".$horasAgendaveis['fila'][$i]."'><img id='add_".$agendamentoCompleto."' class='linhaicon' src='".$dominio."/img/relogio-preto.png'></img></span>
                                                <p>".$horaMarcada."</p>
                                        </div>
                        ";

                        $agendamentoinfo = new ConsultaDatabase($uid);
        		$agendamentoinfo = $agendamentoinfo->AgendamentoInfo($competente,$dataCalendarioSuperior->format('Y').'-'.$dataCalendarioSuperior->format('m').'-'.$dataCalendarioSuperior->format('d').' '.$horarioDaHora.':'.$minutoDaHora.':00.000000');
                        if ($agendamentoinfo['agendamento']==0) {
                                $disponivelSlot = new Conforto($uid);
                                $disponivelSlot = $disponivelSlot->DisponivelSlot($agendamentoCompleto);
                                $agenda .= $disponivelSlot;
                        } else {
                                if ($agendamentoinfo['ativo']==1) {
                                        $clienteagendamento = new ConsultaDatabase($uid);
                                        $clienteagendamento = $clienteagendamento->Cliente($agendamentoinfo['lid']);

                                        $agenda .= "
                                                <p class='agendado' data-aid='".$agendamentoinfo['aid']."'>
                                                        <span>agendado</span>
                                                        <span class='infolinear' style='padding:0px 8px;background-color:var(--amarelo);color:var(--cremelight);' aria-label='".$clienteagendamento['nome']."'>i</span>
                                                </p>
                                        ";

                                        $agenda .="
                                                        <div class='horaicones'>
                                                                <span class='info' aria-label='ligar'><img data-telefone='".str_replace(array('-','(',')',' '),'',$clienteagendamento['telefone'])."' class='linhaicon iconetelefone' src='".$dominio."/img/telefone-preto.png'></img></span>
                                                                <span class='info' aria-label='whatsapp'><img data-whatsapp='+55".str_replace(array('-','(',')',' '),'',$clienteagendamento['telefone'])."' class='linhaicon iconewhatsapp' src='".$dominio."/img/whatsapp-preto.png'></img></span>
                                                                <span class='info' aria-label='editar'><img data-aid='".$agendamentoinfo['aid']."' class='linhaicon iconeeditar' src='".$dominio."/img/editar-preto.png'></img></span>
                                                                <span class='info' aria-label='desmarcar'><img data-aid='".$agendamentoinfo['aid']."' class='linhaicon desmarcaricone' src='".$dominio."/img/borracha-preto.png'></img></span>
                                                        </div>
                                                </div>
                                                <!-- linhahora -->
                                        ";
                                } else {
                                        $disponivelSlot = new Conforto($uid);
                                        $disponivelSlot = $disponivelSlot->DisponivelSlot($agendamentoCompleto);
                                        $agenda .= $disponivelSlot;
                                } // se o agendamento tá ativo
                        } // agendamentoinfo = 0
                } // for hora

                $agenda .= "
                        <script>
                                $('.disponivel, .disponivelicone').on('click', function() {
                                        setHoraAgendamento($('#competentepainellinear').val(),$(this).siblings('.horadodia').find('.info').data('horamarcada'));
                                });
                                $('.agendado, .iconeeditar').on('click', function() {
                                        verAgendamento($(this).data('aid'));
                                });
                                $('.iconetelefone').on('click',function () {
                                        window.location='tel:'+$(this).data('telefone');
                                });
                                $('.iconewhatsapp').on('click',function () {
                                        window.location.href='https://wa.me/'+$(this).data('whatsapp');
                                });
                                $('.desmarcaricone').on('click', function() {
                                        desmarcarAgendamento($(this).data('aid'));
                                });
                        </script>
                ";

                // $agenda .= "
                //         <script>
                //                 $('.linhahora').hover(
                //                         function() {
                //                                 $(this).find('.linhaicon').each(function() {
                //                                         $(this).attr('src', $(this).attr('src').replace('preto', 'branco'));
                //                                 });
                //                         },
                //                         function() {
                //                                 $(this).find('.linhaicon').each(function() {
                //                                         $(this).attr('src', $(this).attr('src').replace('branco', 'preto'));
                //                                 });
                //                         }
                //                 );
                //         </script>
                // ";

                $resposta['agenda'] = $agenda;
        } else {
                returnToLogin();
        }

        header('Content-Type: application/json;');
	echo json_encode($resposta, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PARTIAL_OUTPUT_ON_ERROR);

?>
