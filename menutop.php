<?php

        echo "
                <!-- menutopwrap -->
                <div id='menutopwrap'>
                        <div id='menutopinnerwrap'>
                        <div class='inicioanchor' style='display:flex;margin:3%;cursor:pointer;'>
                                <img src='".$dominio."/img/logo.png'></img>
                                <p style='margin:auto;font-size:13px;'>ophanim</p>
                        </div>
                        <div class='opcoestopwrap'>
        ";

        if (!isset($_SESSION['ag_id'])) {
                echo "
                        <div class='opcoestop'>
                                <a class='inneropcoestop' href='".$dominio."/cadastro'>Começar</a>
                        </div>
                        <div class='opcoestop'>
                                <a class='inneropcoestop' href='".$dominio."/contato'>Contato</a>
                        </div>
                ";
        } else {
                echo "
                        <div class='opcoestop'>
                                <a class='inneropcoestop' href='".$dominio."/painel'>Painel</a>
                        </div>
                        <div class='opcoestop'>
                                <a class='inneropcoestop' href='".$dominio."/minhaconta'>Minha Conta</a>
                        </div>
                ";
        }

        echo "
                        </div>
                        <div class='flexflex'></div>
        ";

        if (isset($_SESSION['ag_id'])) {
                echo "
                <div class='buttontopwraplogado'>
                        <div id='infotopwrap'>
                                <div id='infotop'>
                                        <p style='display:inline-block;vertical-align:middle;'>Olá, ".NomeCliente($_SESSION['ag_nome'])."!</p>
                                </div>
                        </div>
                ";
        } else {
                echo "
                        <div class='buttontopwrap'>
                                <div id='top_areacliente'>
                                        <img id='top_areacliente' src='".$dominio."/img/user.png' style='max-width:21px;'></img>
                                </div>
                ";
        }

        echo "
                </div>
                        <!-- buttonwrap -->

                                <div id='abremenusuperior'>
                                        <span></span>
                                        <span></span>
                                        <span></span>
                                        <span></span>
                                </div>
                        </div>
        ";
        if (isset($_SESSION['ag_id'])) {
                echo "
                        <div class='flexbreak'></div>
                        ";
        } // logado

        echo "
                        <div id='horapainellinear'>

                                <div id='competenteslinearwrap'>
                                        <div id='competenteslinearinnerwrap' style='flex:1;'>
                                                <div id='competenteslinearinner'>
                                                        <select id='competentepainellinear'>
        ";
                                                                        $competentesDisponiveis = new ConsultaDatabase($uid);
                                                                        $competentesDisponiveis = $competentesDisponiveis->ListaCompetentes();

                                                                        if ($competentesDisponiveis[0]['vid']!=0) {
                                                                                foreach ($competentesDisponiveis as $competente) {
                                                                                        echo "
                                                                                                <option value='".$competente['vid']."'>".$competente['nome']."</option>
                                                                                        ";
                                                                                } // foreach competente
                                                                        } // se tem competentes cadastrados
        echo "
                                                        </select>
                                                </div>
                                        </div>
                                </div> <!-- competenteslinearwrap -->

                                <div id='calendariopainellinear'>
                                        <p id='setasuperioranterior' class='horapainel setas'><</p>
                                        <p id='diadehoje' class='horapainel' style='flex:3;'></p>
                                        <p id='setasuperiorposterior' class='horapainel setas'>></p>
                                </div>
                        </div>

                        <script>
                                $(document).ready(function() {
                                        calendarioSuperior($('#competentepainellinear').val(),".$agora->format('d').",".$agora->format('m').",".$agora->format('Y').");
                                        $('#setasuperioranterior').on('click', function() {
                                                calendarioSuperior($('#competentepainellinear').val(),sessionStorage.getItem('dataAnteriorDia'),sessionStorage.getItem('dataAnteriorMes'),sessionStorage.getItem('dataAnteriorAno'));
                                        });
                                        $('#setasuperiorposterior').on('click', function() {
                                                calendarioSuperior($('#competentepainellinear').val(),sessionStorage.getItem('dataSuperiorDia'),sessionStorage.getItem('dataSuperiorMes'),sessionStorage.getItem('dataSuperiorAno'));
                                        });
                                        $('#competentepainellinear').on('change', function() {
                                                calendarioSuperior($('#competentepainellinear').val(),sessionStorage.getItem('dataAtualDia'),sessionStorage.getItem('dataAtualMes'),sessionStorage.getItem('dataAtualAno'));
                                        });
                                });
                        </script>

                        <div id='banneraviso' class='banneraviso'>
                                <button id='fechabanneraviso' class='fechabanneraviso' aria-label='fechar' tabindex='0'>✕</button>
                                <div>
                                        <p id='banneravisomsg'></p>
                                </div>
                        </div>

                </div>

                </div>
                <!-- menutopwrap -->

                <script>

                        $('#diadehoje').on('click',function () {
                                calendarioPop(3,'fundamental',0);
                        });

                        $('#top_areacliente').on('click', function () {
                                loadFundamental('".$dominio."/entrar/includes/entrarfundamental.inc.php');
                        });

                        $('#top_logout').on('click', function () {
                                window.location.href='".$dominio."/entrar/logout'
                        });
        ";

        if (!isset($_SESSION['ag_id'])) {
                echo "

                        $('.inicioanchor').on('click', function() {
                                window.location.href = '".$dominio."';
                        });

                        window.addEventListener('load', function() {
                                $('#abremenusuperior').on('click', function() {
                                        if ($('#superior').css('left')!='0px') {
                                                $('#fechar').trigger('click');
                                                $('#fecharvestimenta').trigger('click');
                                                $('#fechacarrinho').trigger('click');
                                                $('#fechabanneraviso').trigger('click');
                                                loadSuperior('".$dominio."/menusuperior.php');
                                                $(this).addClass('open');
                                        } else if ($('#superior').css('left')=='0px') {
                                                $('#fecharsuperior').trigger('click');
                                                $(this).removeClass('open');
                                        }
                                });
                        });
                ";
        } else {
                echo "

                        $('.inicioanchor').on('click', function() {
                                window.location.href = '".$dominio."/painel';
                        });

                        window.addEventListener('load', function() {
                                $('#abremenusuperior').on('click', function() {
                                        if ($('#superior').css('left')!='0px') {
                                                $('#fechar').trigger('click');
                                                $('#fecharvestimenta').trigger('click');
                                                $('#fechacarrinho').trigger('click');
                                                $('#fechabanneraviso').trigger('click');
                                                loadSuperior('".$dominio."/menusuperiorsistema.php');
                                                $(this).addClass('open');
                                        } else if ($('#superior').css('left')=='0px') {
                                                $('#fecharsuperior').trigger('click');
                                                $(this).removeClass('open');
                                        }
                                });
                        });
                ";

        } // menusuperior

        echo "
                        $(document).ready(function(cms) {
                                $(document).click(function(cms) {
                                        if ($(cms.target).closest('.fundamental').attr('id')==='fundamental') return;
                                        if ($(cms.target).closest('.iconesuporte').attr('id')==='msgsuporteicon') return;
                                        if ($('#superior').css('left')=='0px') {
                                                if (cms.pageX>141) {
                                                        $('#fechar').trigger('click');
                                                        $('#fecharvestimenta').trigger('click');
                                                        $('#fecharsuperior').trigger('click');
                                                        $('#fechabanneraviso').trigger('click');
                                                        $('#abremenusuperior').removeClass('open');
                                                }
                                        }
                                });
                        });

                        $('.mostracarrinho').on('click', function() {
                                abreCarrinho();
                        });

                        $('.opcoestop').on('click',function() {
                                window.location.href = $(this).find('a').attr('href');
                        });
        ";

        if ($fragmentoatual=='painel') {
                echo "
                                $(document).ready(function() {
                                        podefecharmenutopporcausadocarrinho = 1;
                                        var prevScrollpos = window.pageYOffset;
                                        window.onscroll = function() {
                                                var currentScrollPos = window.pageYOffset;

                                                if (currentScrollPos>alturaHeader) {
                                                        if (prevScrollpos >= currentScrollPos) {
                                                                mostraMenuTop();
                                                        } else if (prevScrollpos < currentScrollPos) {
                                                                hideMenuTop();
                                                        }
                                                }

                                                prevScrollpos = currentScrollPos;
                                        }
                                });
                ";
        } else {
                echo "
                        $('#horapainellinear').css('display','none');
                ";
        } // se tá no painel

        echo "
                </script>
        ";

        if (isset($_SESSION['ag_id'])) {
                echo "
                        <div id='botoesbottomlinear'>
                                <div id='iniciobottomicon'><span class='infolinear' aria-label='painel'><img class='iconbottomlinear' src='".$dominio."/img/inicio-branco.png'></img></span></div>
                                <div id='addcompetentebottomicon'><span class='infolinear' aria-label='+ competente'><img class='iconbottomlinear' src='".$dominio."/img/addcompetentes-branco.png'></img></span></div>
                                <div id='addclientesbottomicon'><span class='infolinear' aria-label='+ cliente'><img class='iconbottomlinear' src='".$dominio."/img/addclientes-branco.png'></img></span></div>
                                <div id='msgsuporteicon'><span class='infolinear' aria-label='suporte'><img class='iconbottomlinear' src='".$dominio."/img/suporte-branco.png'></img></span></div>
                        </div>

                        <script>
                                $('#botoesbottomlinear > div').hover(
                                        function() {
                                                $(this).find('.iconbottomlinear').each(function() {
                                                        $(this).attr('src', $(this).attr('src').replace('branco', 'preto'));
                                                });
                                        },
                                        function() {
                                                $(this).find('.iconbottomlinear').each(function() {
                                                        $(this).attr('src', $(this).attr('src').replace('preto', 'branco'));
                                                });
                                        }
                                );

                                $('#iniciobottomicon').on('click',function () {
                                        window.location.href='".$dominio."/painel';
                                });
                                $('#addcompetentebottomicon').on('click', function() {
                                        loadFundamental('".$dominio."/painel/competentes/add-competentes-fundamental.inc.php');
                                });
                                $('#addclientesbottomicon').on('click',function () {
                                        loadFundamental('".$dominio."/painel/clientes/add-clientes-fundamental.inc.php');
                                });
                                $('#msgsuporteicon').on('click', function() {
                                        loadFundamental('".$dominio."/includes/suporte/suportepopup.inc.php');
                                });
                        </script>
                ";
        } // isset uid

?>
