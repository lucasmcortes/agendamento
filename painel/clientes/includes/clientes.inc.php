<div id='clienteresult' style='min-width:100%;max-width:100%;'>
        <label>Cliente</label>
        <p id='clienteresultp' style='min-width:100%;max-width:100%;text-align:center;'></p>
</div>

<div id='clientewrap' style='min-width:100%;max-width:100%;'>
        <div id='clienteinner' style='min-width:100%;max-width:100%;display:inline-block;'>
                <input type='text' id='cliente' placeholder='Cliente'></input>
        </div>
</div> <!-- clientewrap -->

<script>
        buscando = 0;
        valplaca = 0;
        typingTimer = '';
        doneTypingInterval = 1200;
        $('#cliente').on('keyup', function () {
                clearTimeout(typingTimer);
                typingTimer = setTimeout(doneTyping, doneTypingInterval);
        });
        $('#cliente').on('keydown', function () {
                $('.retorno').empty();
                clearTimeout(typingTimer);
        });
        function doneTyping () {
                if ($('#cliente').val()!='') {
                        if (buscando==0) {
                                Buscacliente();
                        }

                } else { /* query vazio */
                        $('.retorno').html('Encontre o cliente pelo nome, CPF, telefone ou email');
                        $('#clienteresultp').empty();
                        $('#clienteresultp').css('text-align','center');
                }
        }
        function Buscacliente() {
                buscando = 1;
                document.getElementById('cliente').disabled = true;
                $.ajax({
                        type: 'POST',
                        url: '<?php echo $dominio ?>/painel/clientes/includes/buscacliente.inc.php',
                        data: { cliente: $('#cliente').val() },
                        beforeSend: function() {
                                $('#clienteresultp').html('');
                                $('#clienteresultp').html("<div id='enviando' style='display:inline-block;'><div id='enviandospinner'></div></div>");
                        },
                        success: function(cliente) {
                                buscando = 0;
                                document.getElementById('cliente').disabled = false;
                                $('#clienteresultp').empty();

                                if (cliente.includes('encontrado')===false) {
                                        $('#clienteresult').css('height','auto');

                                        $.each(cliente, function(index, lid) {
                                                /* $("#clienteresultp").append('<span id="lid_' + lid.lid + '" class="opcaocliente sombraabaixo hoverbranco" style="background-color:var(--preto);color:var(--branco);min-width:100%;max-width:100%;margin:1.2% auto;padding:5% 8%;border-radius:var(--radius);border:1px solid var(--preto);display:inline-block;cursor:pointer;">' + lid.nome + '</span>'); */
                                                $("#clienteresultp").append(lid.div);
                                        });

                                        $('.opcaocliente').on('click',function() {
                                                valcliente = $(this).attr('id').split('_')[1];
                                                $('#clienteresultp').html($(this).html());
                                                $('#clientewrap').css('display','none');
                                                $('#clienteresultp').css('display','inline-block');
                                                $('#clienteresultp').css('background-color','var(--verde)');
                                                $.ajax({
                                                        type: 'POST',
                                                        url: '<?php echo $dominio ?>/painel/clientes/includes/infocliente.inc.php',
                                                        data: { cliente: valcliente },
                                                        success: function(cliente) {
                                                                $('.retorno').empty();
                                                                $('#clienteresultp').empty();
                                                                $('#clienteresultp').css('text-align','left');

                                                                $("#clienteresult").append('<div id="clientresultbuttons"><p id="editar" class="sombraabaixo clientresultbuttons">editar</p><p id="trocar" class="sombraabaixo clientresultbuttons">trocar</p></div>');

                                                                $("#trocar").on("click",function() {
                                                                        $('#clienteresultp').trigger("click");
                                                                        $('#clienteresultp').empty();
                                                                        $('#clienteresult').css('height','0px');
                                                                        $('#cliente').val('');
                                                                        $('#clientewrap').css('display','block');
                                                                        $('#clientresultbuttons').remove();
                                                                        $('.retorno').html('Encontre o cliente pelo nome, CPF, telefone ou email');
                                                                });
                                                                $("#editar").on("click",function() {
                                                                        clienteVestimenta(valcliente);
                                                                });

                                                                $("#placa").empty();
                                                                $('#clienteresultp').html(cliente['resposta']);
                                                                $('#clienteresultp').css('display','inline-block');
                                                                $('#clienteresultp').css('background-color','transparent');

                                                                tiraBordaRosa();
                                                                $('#clienteresultp').html(cliente['resposta']);

                                                                $(this).css('background-color','var(--verde)');
                                                                valcliente = $('#clienteresultspan').data('lid');
                                                                $('#aluguelinfo').css('display','inline-block');

                                                                if ($('#clienteresultspan').data('associado')=='S') {

                                                                } else {
                                                                        valacionamento = 'N';
                                                                        valparticular = 'S';
                                                                        $('#particularwrap').css('display','none');
                                                                        $('#acionamentowrap').css('display','none');
                                                                        $('#diariawrap').css('display','inline-block');
                                                                }

                                                       }
                                                });
                                        });
                                } else {
                                        $('#clienteresultp').html(cliente);
                                }
                        }
                });
        }
</script>
