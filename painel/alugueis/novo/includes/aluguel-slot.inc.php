<?php
        tituloPagina('novo aluguel');
        EnviandoImg();

        $listacompetentes = new ConsultaDatabase($uid);
        $listacompetentes = $listacompetentes->Listacompetentes();
?>

<div class='content' id='content' style='min-width:100%;max-width:100%;display:inline-block;'>

        <p id='retorno' class='retorno'>
                Encontre o cliente pelo nome, CPF, rg, telefone, email, placa ou cadastre um novo cliente
        </p> <!-- retorno -->

        <div id='id03'>
                <div class='container'>
                        <div style='min-width:100%;max-width:100%;margin:0 auto;text-align:center;'>
                                <?php require_once __DIR__.'/../../../clientes/includes/achacliente.inc.php'; ?>

                                <div id='aluguelinfo' style='min-width:100%;max-width:100%;display:inline-block;margin-top:3%;'> <!-- aluguelinfo -->

                                        <label>competente</label>
                                        <div style='min-width:100%;max-width:100%;display:inline-block;padding:5% 0px;padding-top:0;'>
                                                <?php
                                                        $slideindex = 1;
                                                        $slidevid = [];
                                                        echo '
                                                                <div id="slidescompetente" class="slideshow-wrapper">
                                                                <div class="slideshow-container" style="position:relative;">
                                                        ';

                                                        // slide 1
                                                        echo '<!-- slides competente -->';
                                                        foreach ($listacompetentes as $competentes) {
                                                                if ($competentes['ativo']=='S') {
                                                                        $vid = $competentes['vid'];
                                                                        $slidevid[$vid] = $slideindex;
                                                                        $slideindex++;

                                                                        $competente = new ConsultaDatabase($uid);
                                                                        $competente = $competente->competente($competentes['vid']);
                                                                        ($competente['limpeza']=='S') ? $limpeza = 'Limpo' : $limpeza = 'Lavar';
                                                                        $categoria = new ConsultaDatabase($uid);
                                                                        $categoria = $categoria->competenteCategoria($competente['categoria']);
                                                                        $potencia = new Conforto($uid);
                                                                	$potencia = $potencia->Potencia($vid);

                                                                	$disponibilidade_competente = new Conforto($uid);
                                                                	$disponibilidade_competente = $disponibilidade_competente->Possibilidade($vid);

                                                                	if (key($disponibilidade_competente['disponibilidade'])>=0) {
                                                                		// atualiza o array de disponibilidade tirando as datas de antes de hoje
                                                                		while ($disponibilidade_competente['disponibilidade'][key($disponibilidade_competente['disponibilidade'])]<$agora->format('Y-m-d')) {
                                                                			unset($disponibilidade_competente['disponibilidade'][key($disponibilidade_competente['disponibilidade'])]);
                                                                		} // while
                                                                	}

                                                                	$disponibilidade = $disponibilidade_competente['status'];

                                                                        echo "
                                                                                <!-- slide -->
                                                                                <div id='v_".$competente['vid']."' data-kilometragem='".$competente['km']."' class='carouselslides fade slidecompetente'>
                                                                                        <div class='innerSlidecompetente'>

                                                                                                <!-- <div class='slideVImg'>
                                                                                                        <img class='slideResImg' src='".$dominio."/img/competenteslideplaceholder.png'></img>
                                                                                                </div> -->

                                                                                                <div class='slideVDivTxt'>
                                                                                                        <p class='pSlideTitulo'>
                                                                                                                <b>".$competente['modelo']." ".$potencia."</b>
                                                                                                                <span style='min-width:100%;max-width:100%;display:inline-block;'><b>".$disponibilidade."</b></span>
                                                                                                                <span style='min-width:100%;max-width:100%;display:inline-block;'><b>".$limpeza."</b></span>
                                                                                                        </p>
                                                                                                        <p class='pSlideDesc'>
                                                                                                                <span class='categoriaslide'>".$categoria."</span>
                                                                                                                <br>
                                                                                                                ".$competente['placa']."
                                                                                                                <br>
                                                                                                                ".Kilometragem($competente['km'])."
                                                                                                        </p>
                                                                                                        <p id='detalhes_v_".$competente['vid']."' class='detalhescompetente botaopretobranco sombraabaixo hoverbranco' style='margin-top:1.8%;'>
                                                                                                                ver detalhes
                                                                                                        </p>
                                                                                                </div>

                                                                                        </div>
                                                                                </div>
                                                                                <!-- slide -->
                                                                        ";
                                                                } // ativo
                                                        } // foreach competente
                                                        echo '
                                                                <div style="min-width:100%;max-width:100%;margin:0 auto;">
                                                                        <a class="prev controleslide" onclick="plusSlides(-1)"></a>
                                                                        <a class="next controleslide" onclick="plusSlides(1)"></a>
                                                                </div>
                                                                </div> <!-- container -->
                                                        ';

                                                        // echo '
                                                        //         <div id="bolinhascontainer">
                                                        //                 <div>
                                                        // ';
                                                        //                 for ($sv=1;$sv<=$slidestotal;$sv++) {
                                                        //                         echo '
                                                        //                                 <span class="bolinhaselector" onclick="currentSlide('.$sv.')"></span>
                                                        //                         ';
                                                        //                 }
                                                        // echo '
                                                        //                 </div>
                                                        //         </div>
                                                        // ';

                                                        echo '
                                                                </div> <!-- wrapper -->
                                                        <!-- slides competente -->
                                                        <script>
                                                                var slideIndex = '.$slideindex.';
                                                                showSlides(slideIndex);

                                                                function plusSlides(n) {
                                                                        showSlides(slideIndex += n);
                                                                }

                                                                function currentSlide(n) {
                                                                        showSlides(slideIndex = n);
                                                                }

                                                                function showSlides(n) {
                                                                        var i;
                                                                        var slides = document.getElementsByClassName("carouselslides");
                                                                        var bolinhaselectors = document.getElementsByClassName("bolinhaselector");
                                                                        if (n > slides.length) {slideIndex = 1}
                                                                        if (n < 1) {slideIndex = slides.length}
                                                                        for (i = 0; i < slides.length; i++) {
                                                                                slides[i].style.display = "none";
                                                                        }

                                                                        slides[slideIndex-1].style.display = "block";
                                                                }
                                                        ';

                                                        if ( (isset($_GET['v'])) && (is_numeric($_GET['v'])) && (array_key_exists($_GET['v'],$slidevid)) ) {
                                                                $slideindex = $slidevid[$_GET['v']];
                                                                echo 'valcompetente='.$_GET['v'].';';
                                                        } else {
                                                                $slideindex = 1;
                                                                echo 'valcompetente='.array_keys($slidevid)[0].';';
                                                        }

                                                        echo '
                                                                currentSlide('.$slideindex.');

                                                                preco_diaria_carro = "'.$configuracoes['preco_diaria_associado'].'";
                                                                preco_diaria_moto = "'.$configuracoes['preco_diaria_moto_associado'].'";
                                                                preco_diaria_utilitario = "'.$configuracoes['preco_diaria_utilitario_associado'].'";

                                                                $(".controleslide").on("click", function() {
                                                                        valcompetente=$(".slidecompetente").not(":hidden").attr("id").split("_")[1];
                                                                        $("#kilometragematual").val($("#v_"+valcompetente+"").data("kilometragem"));
                                                                        DiariaCategoriaSelecionada();
                                                                });
                                                        </script>
                                                       ';
                                                ?>
                                        </div>

                                        <div id='diariawrap' style='min-width:100%;max-width:100%;'>
                                                <label>Preço por diária</label>
                                                <div id='diariainner' style='min-width:100%;max-width:100%;display:inline-block;'>
                                                        <input type='number' id='diaria' placeholder='Preço por diária' value='<?php echo $configuracoes['preco_diaria_associado'] ?>'></input>
                				</div>
                			</div> <!-- diariawrap -->

                                        <div id='kmatualwrap' style='min-width:100%;max-width:100%;'>
                                                <div style='min-width:100%;max-width:100%;margin:0 auto;display:inline-block;'>
                                                        <?php
                                                                InputGeral('Kilometragem atual', 'kilometragematual', 'kilometragematual', 'number', '100');
                                                        ?>
                                                </div>
                                        </div> <!-- kmatualwrap -->

                                        <p id='avisokmlivre' class='aviso sombraabaixo' style='display:none;'>Kilometragem livre para associado</p>

                                        <div id='kmmaximawrap' style='min-width:100%;max-width:100%;'>
                                                <div style='min-width:100%;max-width:100%;margin:0 auto;display:inline-block;'>
                                                        <?php
                                                                InputGeral('Km máxima permitida para uso no aluguel', 'kilometragem', 'kilometragem', 'number', '100');
                                                        ?>
                                                </div>
                                        </div> <!-- kmmaximawrap -->

                                        <?php InputDatas(); ?>

                                        <div id='horarioswrap' style='min-width:100%;max-width:100%;display:flex;justify-content:space-between;gap:4%;'>
                                                <div id='horainiciowrap' style='flex:1;'>
                                			<label>Horário de início</label>
                                			<div id='horainicioinner' style='min-width:100%;max-width:100%;display:inline-block;'>
                                				<select id='horainicio'>
                                					<option value=''>--ESCOLHA--</option>
                                					<option value='06'>06h</option>
                                					<option value='07'>07h</option>
                                					<option value='08'>08h</option>
                                					<option value='09'>09h</option>
                                					<option value='10'>10h</option>
                                					<option value='11'>11h</option>
                                					<option value='12'>12h</option>
                                					<option value='13'>13h</option>
                                					<option value='14'>14h</option>
                                					<option value='15'>15h</option>
                                					<option value='16'>16h</option>
                                					<option value='17'>17h</option>
                                					<option value='18'>18h</option>
                                					<option value='19'>19h</option>
                                					<option value='20'>20h</option>
                                					<option value='21'>21h</option>
                                					<option value='22'>22h</option>
                                					<option value='23'>23h</option>
                                					<option value='00'>00h</option>
                                					<option value='01'>01h</option>
                                					<option value='02'>02h</option>
                                					<option value='03'>03h</option>
                                					<option value='04'>04h</option>
                                					<option value='05'>05h</option>
                                				</select>
                                			</div>
                                                </div>
                                                <div id='horadevolucaowrap' style='flex:1;'>
                                			<label>Horário de devolução</label>
                                			<div id='horadevolucaoinner' style='min-width:100%;max-width:100%;display:inline-block;'>
                                				<select id='horadevolucao'>
                                					<option value=''>--ESCOLHA--</option>
                                					<option value='06'>06h</option>
                                					<option value='07'>07h</option>
                                					<option value='08'>08h</option>
                                					<option value='09'>09h</option>
                                					<option value='10'>10h</option>
                                					<option value='11'>11h</option>
                                					<option value='12'>12h</option>
                                					<option value='13'>13h</option>
                                					<option value='14'>14h</option>
                                					<option value='15'>15h</option>
                                					<option value='16'>16h</option>
                                					<option value='17'>17h</option>
                                					<option value='18'>18h</option>
                                					<option value='19'>19h</option>
                                					<option value='20'>20h</option>
                                					<option value='21'>21h</option>
                                					<option value='22'>22h</option>
                                					<option value='23'>23h</option>
                                					<option value='00'>00h</option>
                                					<option value='01'>01h</option>
                                					<option value='02'>02h</option>
                                					<option value='03'>03h</option>
                                					<option value='04'>04h</option>
                                					<option value='05'>05h</option>
                                				</select>
                                			</div>
                                                </div>
                        		</div> <!-- horawrap -->

                                        <div id='pagamentoinicialwrap'>
                                                <div style='display:flex;gap:2%;justify-content:space-between;'>
                                                        <div style='flex:1;'>
                                                                <?php
                                                                        InputGeral('Valor para pagamento inicial','paginicial','paginicial','number',100);
                                                                ?>
                                                        </div>
                                                        <div style='flex:1;'>
                                                                <div id='formawrap' style='min-width:100%;max-width:100%;display:inline-block;'>
                                                                        <?php SelectFormaPagamento('Forma de pagamento','forma'); ?>
                                                		</div> <!-- formawrap -->
                                                        </div>
                                                </div>
                                        </div> <!-- pagamentoinicialwrap -->

                                        <div style='display:flex;gap:2%;justify-content:space-between;'>
                                                <div style='flex:1;'>
                                                        <label>Valor do pagamento caução</label>
                                                        <div id='caucaoinner' style='min-width:100%;max-width:100%;display:inline-block;'>
                                                                <input type='number' id='caucao' placeholder='Valor do caução' value='<?php echo $configuracoes['caucao_preco'] ?>'></input>
                        				</div>
                                                </div>
                                                <div style='flex:1;'>
                                                        <div id='formacaucaowrap' style='min-width:100%;max-width:100%;display:inline-block;'>
                                                                <?php SelectFormaPagamento('Forma de pagamento do caução','formacaucao'); ?>
                                                        </div> <!-- formawrap -->
                                                </div>
                			</div> <!-- caucaowrap -->

                                        <div id='enviaraluguel_wrap' style='min-width:100%;max-width:100%;margin:0 auto;display:inline-block;'>
                                                <?php

                                                        //InputGeral('Senha', 'pwd', 'pwd', 'password', '100');

                                                        echo "<div style='min-width:100%;max-width:100%;margin:0 auto;display:inline-block;'>";
                                                                MontaBotao('alugar','enviaraluguel');
                                                        echo "</div>";

                                                ?>
                                        </div>
                                </div> <!-- aluguelinfo -->

                                <script>
                                        $('#aluguelinfo').css('display','none');
                                        $('#horainicio').val('<?php
                                                $hora_aluguel = $agora->format('H');
                                                if (strlen($hora_aluguel)==1) {
                                                        $hora_aluguel = '0'.$hora_aluguel;
                                                }
                                                echo $hora_aluguel;
                                                ?>');
                                        $('#horadevolucao').val('<?php
                                                $hora_aluguel = $agora->format('H');
                                                if (strlen($hora_aluguel)==1) {
                                                        $hora_aluguel = '0'.$hora_aluguel;
                                                }
                                                echo $hora_aluguel;
                                                ?>');

                                        $('#competente').on('change',function() {
                                                valcompetente = $('#competente').val();
                                                $('#kilometragematual').val($(this).find(':selected').data('kilometragem'));
                                                $('#retorno').empty();
                                        });


                                        $('#inicio').on('click',function () {
                                                if (valcompetente=='') {
                                                        $('#retorno').html('Escolha um competente');
                                                        window.scrollTo(0,0);
                                                } else {
                                                        $('#retorno').empty();
                                                        calendarioPop(1,'fundamental',valcompetente);
                                                }
                                        });

                                        $('#calendarioinicio').on('click',function () {
                                                if (valcompetente=='') {
                                                        $('#retorno').html('Escolha um competente');
                                                        window.scrollTo(0,0);
                                                } else {
                                                        $('#retorno').empty();
                                                        calendarioPop(1,'fundamental',valcompetente);
                                                }
                                        });

                                        $('#devolucao').on('click',function () {
                                                if (valcompetente=='') {
                                                        $('#retorno').html('Escolha um competente');
                                                        window.scrollTo(0,0);
                                                } else {
                                                        $('#retorno').empty();
                                                        calendarioPop(2,'fundamental',valcompetente);
                                                }
                                        });

                                        $('#calendariodevolucao').on('click',function () {
                                                if (valcompetente=='') {
                                                        $('#retorno').html('Escolha um competente');
                                                        window.scrollTo(0,0);
                                                } else {
                                                        $('#retorno').empty();
                                                        calendarioPop(2,'fundamental',valcompetente);
                                                }
                                        });

                                        $('#vinfo').on('click', function() {
                                                if (valcompetente=='') {
                                                        $('#retorno').html('Escolha um competente');
                                                        window.scrollTo(0,0);
                                                } else {
                                                        $('#retorno').empty();
                                                        valcompetente = $('#competente').val();
                                                        competenteFundamental(valcompetente);
                                                }
                                        });

                                        $('.detalhescompetente').on('click', function() {
                                                if (valcompetente=='') {
                                                        $('#retorno').html('Escolha um competente');
                                                        window.scrollTo(0,0);
                                                } else {
                                                        $('#retorno').empty();
                                                        valcompetente = $(this).attr('id').split('_')[2];
                                                        competenteFundamental(valcompetente);
                                                }
                                        });

                                        $('#inicio').keydown(function() {
                                                return false;
                                        });
                                        $('#devolucao').keydown(function() {
                                                return false;
                                        });

                                </script>
                        </div>
                </div> <!--container -->
        </div><!--id03-->
</div> <!-- content -->

<script>
        $(document).ready(function() {
                enviandoimg = $('#enviando');
                enviarform = $('#enviaraluguel');
                retorno = $('#retorno');
                formulario = $('#id03');

                function EnviarAluguel() {
                        valdiaria = $('#diaria').val() ?? 1;
                        valkmatual = $('#kilometragematual').val();
                        valkilometragem = $('#kilometragem').val() || 1;
                        valinicio = $('#inicio').val();
                        valdevolucao = $('#devolucao').val();
                        valhorainicio = $('#horainicio').val();
                        valhoradevolucao = $('#horadevolucao').val();
                        valpaginicial = $('#paginicial').val();
                        valforma = $('#forma').val();
                        valcaucao = $('#caucao').val();
                        valformacaucao = $('#formacaucao').val();
                        valpwd = $('#pwd').val();

                        $.ajax({
                                type: 'POST',
                                dataType: 'html',
                                async: true,
                                url: '<?php echo $dominio ?>/painel/alugueis/novo/includes/aluguel.inc.php',
                                data: {
                                        submitaluguel: 1,
                                        cliente: valcliente,
                                        diaria: valdiaria,
                                        kilometragematual: valkmatual,
                                        kilometragem: valkilometragem,
                                        competente: valcompetente,
                                        inicio: valinicio,
                                        devolucao: valdevolucao,
                                        horainicio: valhorainicio,
                                        horadevolucao: valhoradevolucao,
                                        paginicial: valpaginicial,
                                        forma: valforma,
                                        caucao: valcaucao,
                                        formacaucao: valformacaucao,
                                        pwd: valpwd
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

                                        if (possivel.includes('confirmar')==true) {
                                                formulario.css('display', 'none');
                                        }

                                        retorno.html(possivel);
                                }
                        });
                }

                enviarform.click(function() {
                        EnviarAluguel();
                });

                $(document).keypress(function(keyp) {
                        if (keyp.keyCode == 13) {
                                EnviarAluguel();
                        }
                });
        }); /* document ready */
</script>

<?php
        if ((isset($_GET['inicio']))) {
                $inicio_do_get = $_GET['inicio'];
                echo "
                        <script>
                                $('#inicio').val('".$inicio_do_get."');
                        </script>
                ";
        } // inicio do get

        if ((isset($_GET['devolucao']))) {
                $devolucao_do_get = $_GET['devolucao'];
                echo "
                        <script>
                                $('#devolucao').val('".$devolucao_do_get."');
                        </script>
                ";
        } // devolucao do get

        echo '
                <script>
                        DiariaCategoriaSelecionada();
                        $("#kilometragematual").val($("#v_"+valcompetente+"").data("kilometragem"));
                </script>
        ';
?>
