<?php
        tituloPagina('novo competente');
        EnviandoImg();
?>

<div class='content' id='content'>

        <p id='retorno' class='retorno'>
        </p> <!-- retorno -->

        <div id='id03'>
                <div class='container'>
                        <div style='min-width:100%;max-width:100%;margin:0 auto;text-align:center;'>

                                <?php
                                        InputGeral('Nome do competente', 'nomecompetente', 'nomecompetente', 'text', '100');
                                ?>

                                <!-- <div class='secaoconfig'>
                        		expediente
                        	</div>
                                <div style='display:flex;gap:2%;'>
                                        <div style='flex:1;'>
                                                <label>Horário de início do expediente</label>
                                                <input onkeyup='maskIt(this,event,"##")' type='text' placeholder='06h' name='inicioexp' id='inicioexp'>
                                        </div>
                                        <div style='flex:1;'>
                                                <label>Horário de de encerramento</label>
                                                <input onkeyup='maskIt(this,event,"##")' type='text' placeholder='18h' name='encexp' id='encexp'>
                                        </div>
                                </div> -->

                                <div style='max-width:100%;min-width:100%;margin:0 auto;margin-bottom:7px;display:inline-block;'>
                                        <label>Observações</label>
                                        <textarea id='observacao' placeholder='Observações' rows='5' style='max-width:100%;min-width:100%;'></textarea>
                                </div>

                        </div>

                        <div style='min-width:72%;max-width:72%;margin:0 auto;display:inline-block;'>
                                <?php MontaBotao('adicionar competente','enviarcompetente'); ?>
                        </div>

                </div> <!--container -->
        </div><!--id03-->
</div> <!-- content -->

<script>
        $(document).ready(function() {
                enviandoimg = $('#enviando');
                enviarform = $('#enviarcompetente');
                retorno = $('#retorno');
                formulario = $('#id03');

                function EnviarCompetente() {
                        $.ajax({
                                type: 'POST',
                                dataType: 'html',
                                async: true,
                                url: '<?php echo $dominio ?>/painel/competentes/novo/includes/competente.inc.php',
                                data: {
                                        submitcompetente: 1,
                                        nome: $('#nomecompetente').val(),
                                        observacao: $('#observacao').val()
                                },
                                beforeSend: function(possivel) {
                                        window.scrollTo(0,0);
                                        enviandoimg.css('display', 'block');
                                        formulario.css('opacity', '0');
                                        retorno.css('opacity', '0');
                                },
                                success: function(possivel) {
                                        window.scrollTo(0,0);
        				bordaRosa();
                                        enviandoimg.css('display', 'none');
                                        formulario.css('opacity', '1');
                                        retorno.css('opacity', '1');

                                        retorno.html(possivel);

                                        if ( (possivel.includes('sucesso') == true) || (possivel.includes('atualizados') == true) ) {
                                                formulario.css('display', 'none');
                                                retorno.append('<img id=\"sucessogif\" src=\"<?php echo $dominio ?>/img/sucesso.gif\">');
                                        }
                                }
                        });
                }

                enviarform.click(function() {
                        EnviarCompetente();
                });

                $(document).keypress(function(keyp) {
                        if (keyp.keyCode == 13) {
                                EnviarCompetente();
                        }
                });
        }); /* document ready */
</script>
