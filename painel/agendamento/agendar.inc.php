<?php

        include_once __DIR__.'/../../includes/setup.inc.php';
        BotaoFechar();

?>

<div class='wrap wrapagendar'>

        <?php
                tituloPagina('agendar');
                EnviandoImg();

                if (isset($_SESSION['horaDisponivel'])) {
                        $competenteEscolhido = $_SESSION['competenteEscolhido'];
                        $horaDisponivel = explode('_',$_SESSION['horaDisponivel']);
                        $horaDisponivelDia = explode('-',$horaDisponivel[0]);
                        $horaDisponivelHora = explode('-',$horaDisponivel[1]);

                        // HORA
                        $horaDisponivelDataTime = new DateTime($horaDisponivelDia[0].'-'.$horaDisponivelDia[1].'-'.$horaDisponivelDia[2].' '.$horaDisponivelHora[0].':'.$horaDisponivelHora[1].':00.000000');
                } else {
                        $horaDisponivelDataTime = $agora;
                } // $_SESSION['horaDisponivel']

        ?>

        <p id='retorno' class='retorno'>Encontre o cliente pelo nome, CPF, telefone ou email</p>

        <div id='wrapagendamento' class='innerwrapagendar'>
                <div class='container'>
                        <div style='min-width:100%;max-width:100%;margin:0 auto;display:inline-block;'>

                                <div style='max-width:100%;min-width:100%;display:inline-block;'>
                                        <?php require_once __DIR__.'/../clientes/includes/clientes.inc.php'; ?>
                                </div>

                                <?php InputDataAgendamento(); ?>

                                <div id='horarioswrap' style='min-width:100%;max-width:100%;display:flex;justify-content:space-between;gap:4%;'>
                                        <div id='horainiciowrap' style='flex:1;'>
                                                <label>Horário</label>
                                                <div id='horainicioinner' style='min-width:100%;max-width:100%;display:inline-block;'>
                                                        <select id='horainicio'>
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
                                                </div>
                                        </div>
                                </div> <!-- horawrap -->

                                <div id='competenteswrap' style='min-width:100%;max-width:100%;display:flex;justify-content:space-between;gap:4%;'>
                                        <div id='competentesinnerwrap' style='flex:1;'>
                                                <label>Competente</label>
                                                <div id='competentesinner' style='min-width:100%;max-width:100%;display:inline-block;'>
                                                        <select id='competente'>
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
                                                </div>
                                        </div>
                                </div> <!-- competenteswrap -->

                                <div id='especificacaowrap' style='min-width:100%;max-width:100%;margin:3px auto;'>
                			<label>Especificação</label>
                			<div id='especificacaoinner'>
                				<textarea id='especificacao' rows='3' style='vertical-align:middle;border:1px solid var(--preto);border-radius:var(--radius);'></textarea>
                			</div>
                			<p id='enviarespecificacao' class='salvarconfig' style='display:none;vertical-align:top;'></p>
                		</div> <!-- especificacaowrap -->

                                <div class='contalinha'>
					<div><?php MontaBotaoSecundario('voltar','voltarbotao'); ?></div>
					<div><?php MontaBotao('agendar','agendarbotao'); ?></div>
				</div>
                        </div>
                </div> <!-- container -->
        </div><!--innerwrapentrar-->
</div> <!-- content -->

<script>
	abreFundamental();
        setFulldamental();

        $('#diadehoje').html(dataRespostaCalendario);

	$(document).ready(function() {
		setFulldamental();
	});

        $('#voltarbotao').on('click',function() {
                $('#fechar').trigger('click');
        });

        <?php
                if (isset($_SESSION['horaDisponivel'])) {
                        echo "
                                $('#horainicio').val('".$horaDisponivelDataTime->format('Hi')."').change();
                                $('#inicio').val('".$horaDisponivelDataTime->format('d/m/Y')."');
                                $('#competente').val('".$competenteEscolhido."');
                        ";
                } // session hora marcada
        ?>

        $(document).ready(function() {
                enviandoimg = $('#enviando');
                enviarform = $('#agendarbotao');
                retorno = $('.retorno');
                formulario = $('#wrapagendamento');

                function EnviarAgendamento() {

                        $.ajax({
                                type: 'POST',
                                dataType: 'html',
                                async: true,
                                url: '<?php echo $dominio ?>/painel/agendamento/novoagendamento.inc.php',
                                data: {
                                        agendar:1,
                                        cliente: $('#clienteresultspan').data('lid')||0,
                                        dataagendamento: $('#inicio').val(),
                                        horario: $('#horainicio').val(),
                                        competente: $('#competente').val(),
                                        especificacao: $('#especificacao').val()
                                },
                                beforeSend: function() {
                                        window.scrollTo(0,0);
                                        enviandoimg.css('display', 'block');
                                        formulario.css('opacity', '0');
                                        retorno.css('opacity', '0');
                                },
                                success: function(possivel) {
                                        window.scrollTo(0,0);
                                        enviandoimg.css('display', 'none');
                                        formulario.css('opacity', '1');
                                        retorno.css('opacity', '1');

                                        if (possivel.includes('confirmar')==true) {
                                                formulario.css('display', 'none');
                                        }

                                        retorno.html(possivel);
                                }
                        });
                }

                enviarform.click(function() {
                        EnviarAgendamento();
                });

                $(document).keypress(function(keyp) {
                        if (keyp.keyCode == 13) {
                                EnviarAgendamento();
                        }
                });
        }); /* document ready */
</script>
