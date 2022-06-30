<?php

        include_once __DIR__.'/../../includes/setup.inc.php';
        BotaoFechar();

        $agendamentoatual = new ConsultaDatabase($uid);
        $agendamentoatual = $agendamentoatual->Agendamento($_SESSION['horaAgendada']);
        $dataAgendada = new DateTime($agendamentoatual['agendamento']);

        $clienteagendamento = new ConsultaDatabase($uid);
        $clienteagendamento = $clienteagendamento->Cliente($agendamentoatual['lid']);

        $enderecocliente = new ConsultaDatabase($uid);
        $enderecocliente = $enderecocliente->EnderecoCliente($agendamentoatual['lid']);

?>

<div class='wrap wrapagendar'>

        <?php
                tituloPagina('agendamento');
                EnviandoImg();
        ?>

        <p class='retorno'>

        <div id='wrapagendamento' class='innerwrapagendar'>
                <div class='container'>
                        <div style='min-width:100%;max-width:100%;margin:0 auto;display:inline-block;'>
                                <p id='agendamentoregistro' class='numregistro' data-aid='<?php echo $agendamentoatual['aid'] ?>'>
                                        <?php echo $agendamentoatual['guid'] ?>
                                </p>

                                <div class='linhaveragendamento'>
                                        <div style='flex:1;'>
                                                <?php InputDataAgendamento(); ?>
                                        </div>

                                        <div id='horarioswrap'>
                                                <div id='horainiciowrap' style='flex:1;'>
                                                        <label>Horário</label>
                                			<div class='inputouterwrap'>
                                				<div class='inputwrap'>
                                					<div class='preinput'></div>
                                                                        <select id='horario' class='wrappedinput' data-aid='<?php echo $agendamentoatual['aid'] ?>'>
                                                                                <option value=''>--ESCOLHA--</option>
                                                                                <?php
                                                                                        $horasAgendaveis = new Conforto($uid);
                                                                                        $horasAgendaveis = $horasAgendaveis->horasAgendaveis();

                                                                                        for ($i=0;$i<count($horasAgendaveis['horarios']);$i++) {
                                                                                                echo "
                                                                                                        <option value='".$horasAgendaveis['horarios'][$i]."'>".$horasAgendaveis['exibicao'][$i]."</option>
                                                                                                ";
                                                                                        } // foreach hora
                                                                                ?>
                                                                        </select>
                                					<div class='posinput'></div>
                                                                </div>
                                                        </div>
                                                </div>
                                        </div> <!-- horawrap -->
                                </div>

                                <div class='linhaveragendamento'>
                                        <div style='flex:1;'>
                                                <div id='competenteswrap'>
                                                        <div id='competentesinnerwrap' style='flex:1;'>
                                                                <label>Competente</label>
                                                                <div class='inputouterwrap'>
                                        				<div class='inputwrap'>
                                        					<div class='preinput'></div>
                                                                                <select id='competente' class='wrappedinput' data-aid='<?php echo $agendamentoatual['aid'] ?>'>
                                                                                        <option value=''>--ESCOLHA--</option>
                                                                                        <?php
                                                                                                $competentesDisponiveis = new ConsultaDatabase($uid);
                                                                                                $competentesDisponiveis = $competentesDisponiveis->ListaCompetentes();

                                                                                                if ($competentesDisponiveis[0]['vid']!=0) {
                                                                                                        foreach ($competentesDisponiveis as $competente) {
                                                                                                                echo "
                                                                                                                        <option value='".$competente['vid']."'>".$competente['nome']."</option>
                                                                                                                ";
                                                                                                        } // foreach competente
                                                                                                } // se tem competentes cadastrados
                                                                                        ?>
                                                                                </select>
                                        					<div class='posinput'></div>
                                                                        </div>
                                                                </div>
                                                        </div>
                                                </div> <!-- competenteswrap -->
                                        </div>

                                        <div style='flex:1;'>
                                                <label>Especificação</label>
                                                <div style='max-width:100%;min-width:100%;display:inline-block;'>
                                                        <p><?php echo $agendamentoatual['especificacao'] ?></p>
                                                </div>
                                        </div>
                                </div>

                                <div class='linhaveragendamento'>
                                        <div style='flex:1;'>
                                                <label>Nome</label>
                                                <div style='max-width:100%;min-width:100%;display:inline-block;'>
                                                        <div class='horaicones' style='justify-content:space-between;'>
                                                                <p style='margin:0;'><?php echo $clienteagendamento['nome'] ?></p>
                                                                <span class='infolinear' aria-label='ver cliente'><img data-lid='<?php echo $agendamentoatual['lid'] ?>' class='linhaicon iconeclienteagendamento' src='<?php echo $dominio ?>/img/clientes-preto.png'></img></span>
                                                        </div>
                                                </div>
                                        </div>

                                        <div style='flex:1;'>
                                                <label>Telefone</label>
                                                <div style='max-width:100%;min-width:100%;display:inline-block;'>
                                                        <div class='horaicones' style='justify-content:space-between;'>
                                                                <p style='margin:0;'><?php echo $clienteagendamento['telefone'] ?></p>
                                                                <div>
                                                                        <span class='infolinear' aria-label='ligar'><img data-telefone='<?php echo str_replace(array('-','(',')',' '),'',$clienteagendamento['telefone']) ?>' class='linhaicon iconetelefone' src='<?php echo $dominio ?>/img/telefone-preto.png'></img></span>
                                                                        <span class='infolinear' aria-label='whatsapp'><img data-whatsapp='+55<?php echo str_replace(array('-','(',')',' '),'',$clienteagendamento['telefone']) ?>' class='linhaicon iconewhatsapp' src='<?php echo $dominio ?>/img/whatsapp-preto.png'></img></span>
                                                                </div>
                                                        </div>
                                                </div>
                                        </div>
                                </div>

                                <div class='linhaveragendamento' style='flex-direction:column;'>
                                        <label>E-mail</label>
                                        <div class='horaicones'>
                                                <textarea id='emailarea' rows='1' style='margin:0;resize:none;border:0;font-size:16px;' disabled><?php echo $clienteagendamento['email'] ?></textarea>
                                                <span class='infolinear' aria-label='copiar e-mail'><img id='copiaremailbutton' class='linhaicon' src='<?php echo $dominio ?>/img/email-preto.png'></img></span>
                                        </div>
                                </div>

                                <div class='contalinha'>
                                        <div><?php MontaBotao('desmarcar','desmarcarbotao','vermelho'); ?></div>
					<div><?php MontaBotaoSecundario('fechar','voltarbotao'); ?></div>
				</div>
                        </div>
                </div> <!-- container -->
        </div><!--innerwrapentrar-->
</div> <!-- content -->

<script>
	abreFundamental();
        $('#diadehoje').html(dataRespostaCalendario);

	$(document).ready(function() {
		setFulldamental();
	});

        $('#voltarbotao').on('click',function() {
                $('#fechar').trigger('click');
        });

        $('.iconeclienteagendamento').on('click', function() {
                clienteVestimenta($(this).data('lid'));
        });

        $('.iconetelefone').on('click',function () {
                window.location='tel:'+$(this).data('telefone');
        });

        $('.iconewhatsapp').on('click',function () {
                window.location.href='https://wa.me/'+$(this).data('whatsapp');
        });

        $('#copiaremailbutton').on('click',function() {
                emaildoagendamento = $('#emailarea').val();
                document.getElementById('emailarea').select();
                document.execCommand('copy');

                $('#emailarea').css('background-color','var(--verdedois)');
                $('#emailarea').html('copiado');
                setTimeout(function() {
                        $('#emailarea').html(emaildoagendamento);
                        $('#emailarea').css('background-color','var(--cremelight)');
                }, 3000);
        });

        $('#clienteresultp').val();
        $('#horario').val('<?php echo $dataAgendada->format('Hi') ?>').change();
        $('#inicio').val('<?php echo $dataAgendada->format('d/m/Y') ?>');

        <?php
                $competenteEscolhido = new ConsultaDatabase($uid);
                $competenteEscolhido = $competenteEscolhido->Competente($agendamentoatual['vid']);
                echo "
                        $('#competente').val('".$agendamentoatual['vid']."').change();
                ";
        ?>

        $('#desmarcarbotao').on('click', function() {
                desmarcarAgendamento(<?php echo $agendamentoatual['aid'] ?>);
        });

        $('.posinput').on('click', function() {
                elemento = $(this).siblings('.wrappedinput').attr('id');
                $.ajax({
                        type: 'POST',
                        url: '<?php echo $dominio ?>/painel/agendamento/updatenovo'+elemento+'.inc.php',
                        data: {
                                aid: $('#agendamentoregistro').data('aid'),
                                dataagendamento: $('#inicio').val(),
                                horarioagendamento: $('#horario').val(),
                                competente: $('#competente').val()
                        },
                        success: function(novohorario) {
                                if (novohorario.includes('sucesso')) {
                                        if ($('#'+elemento).siblings('.preinput').hasClass('bgrosa')) {
                                                $('#'+elemento).siblings('.preinput').removeClass('bgrosa');
                                        }
                                        $('#'+elemento).css('border', '0');
                                        $('#'+elemento).closest('.inputouterwrap').css('background-color', 'var(--verdedois)');
                                        $('#'+elemento).closest('.inputouterwrap').find('*').prop('disabled', 'disabled');
                                        $('#'+elemento).closest('.inputouterwrap *').css('cursor','not-allowed');
                                        $('#'+elemento).closest('.inputouterwrap *').css('pointer-events','none');
                                        $('#'+elemento).siblings('.preinput').removeClass('normal');
                                        $('#'+elemento).siblings('.preinput').addClass('modificado');
                                        mostraFooter();
                                        calendarioSuperior($('#competentepainellinear').val(),<?php echo $dataAgendada->format('d') ?>,<?php echo $dataAgendada->format('m') ?>,<?php echo $dataAgendada->format('Y') ?>);
                                } else {
                                        $('#'+elemento).siblings('.preinput').addClass('bgrosa');
                                        $('#bannerfooter').css('display','none');
                                }
                        }
                });
        });

</script>
