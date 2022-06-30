<?php

include __DIR__.'/../../../../includes/setup.inc.php';
BotaoFecharVestimenta();

echo "
        <!-- img_rg_outer_wrap -->
        <div id='img_rg_outer_wrap' style='max-width:100%;min-width:100%;padding:3%;padding-bottom:0;margin:8% auto;margin-bottom:0;display:inline-block;'>

                <label>Foto do RG:</label>
                <div id='img_rg_wrap' class='uploadwrap'>
                        <label id='label_img_rg' for='img_rg' class='upload'>
                                <img class='uploadicon' src='".$dominio."/img/addimg.png'></img>
                                <p class='uploadcaption'>
                                        adicionar imagem
                                </p>
                        </label>
                        <input type='file' name='img_rg' id='img_rg' class='plimgupload'  accept='image/jpeg,image/gif,image/png,application/pdf,image/x-eps' style='display:none;'>
                        <div style='min-width:100%;max-width:100%;display:inline-block;'>
                                <div id='progressBarWrap_rg' class='uploadprogressbar'>
                                        <div id='progressBar_rg' class='uploadprogressbarinner'></div>
                                        <p id='statusUpload_rg' style='position:absolute;left:0;right:0;margin:auto;text-align:center;font-size:13px;color:var(--preto);'></p>
                                </div>
                        </div>
                </div>

                <script>
                        img_rg_outer_wrap = $('#img_rg_outer_wrap').html();
                        function uploadFile(elemento) {
                                file = document.getElementById(elemento).files[0];

                                formdata = new FormData();
                                formdata.append('img_rg', file);
                                formdata.append('uploaded_file_name', file.name);

                                ajax = new XMLHttpRequest();
                                ajax.upload.addEventListener('progress', progressHandler, false);
                                ajax.addEventListener('load', completeHandler, false);
                                ajax.addEventListener('error', errorHandler, false);
                                ajax.addEventListener('abort', abortHandler, false);

                                ajax.open('POST','".$dominio."/painel/clientes/novo/includes/addimgrg.inc.php');
                                ajax.send(formdata);
                        }

                        function progressHandler(event) {
                                percent = (event.loaded / event.total) * 100;
                                $('#progressBar_rg').width(Math.round(percent) + '%');
                                document.getElementById('statusUpload_rg').innerHTML = Math.round(percent) + '%';
                        }

                        function completeHandler(event) {
                                document.getElementById('img_rg_wrap').innerHTML = event.target.responseText;

                                $.ajax({
                                        url: '".$dominio."/painel/clientes/novo/includes/salvaimgrg.inc.php',
                                        success: function(salvarg) {
                                                $('#rgimg').attr('src',salvadoc+'?".rand(1, 999)."');
                                        }
                                });

                                $('#remove_img_rg').on('click',function() {
                                        $('#img_rg_outer_wrap').html(img_rg_outer_wrap);
                                        $.ajax({
                                                url: '".$dominio."/painel/clientes/novo/includes/unsetrg.inc.php'
                                        });
                                });
                        }

                        function errorHandler(event) {
                                document.getElementById('img_rg_wrap').innerHTML = 'Upload falhou';
                        }

                        function abortHandler(event) {
                                document.getElementById('img_rg_wrap').innerHTML = 'Upload cancelado';
                        }

                        $('#img_rg').change(function() {
                                elemento = $(this).attr('id');
                                uploadFile(elemento);
                        });
                </script>
        </div>

        <div style='min-width:94%;max-width:94%;display:inline-block;margin:3% auto;'>
        ";
                MontaBotao('voltar','verrg');
        echo "
        </div>

        <script>
                abreVestimenta();
                $('#fecharvestimenta').on('click', function() {
                        verrg('".$_SESSION['rg']."');
                });

                $('#verrg').on('click', function () {
                        $('#fecharvestimenta').trigger('click');
                });
        </script>
        <!-- img_rg_outer_wrap -->
";
?>
