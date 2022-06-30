<?php

include_once __DIR__.'/../../../includes/setup.inc.php';
BotaoFechar();

if (isset($_POST['documento'])) {
	$rg = $_POST['documento'];
	$_SESSION['rg'] = $rg;
	$documento = new ConsultaDatabase($uid);
	$documento = $documento->documento($rg);

	$cliente = new ConsultaDatabase($uid);
	$cliente = $cliente->clienteInfo($documento['lid']);

	$imagem = glob(__DIR__.'/../rg/'.$rg.'.*', GLOB_BRACE);
	if (!empty($imagem)) {
		usort($imagem, fn($a, $b) => filemtime($b) - filemtime($a)); // arquivo mais recente
		$imagem = basename($imagem[0]);
	} else {
		$imagem = '';
	}

} else {
	$lid = 0;
}// $_post
?>

<!-- items -->
<div class="items">
	<?php tituloCarro($cliente['nome']); ?>
	<div style='min-width:100%;max-width:100%;display:inline-block;margin-top:8%;'>
		<div style='min-width:100%;max-width:100%;display:inline-block;'>
				<?php
					if (!empty($imagem)) {
					echo "
						<div style='width:55%;display:inline-block;'>
					";
					if (strpos($imagem,'.pdf')!==false) {
						echo "
							<iframe id='rgimg' src='".$dominio."/painel/clientes/rg/".$imagem."?".rand(1,999)."' style='width:100%;auto;'></iframe>
							<p id='dldoc' style='background-color:var(--preto);padding:3px 5px;border-radius:var(--radius);color:var(--branco);margin-bottom:1.8%;'>ver pdf</p>
							<script>
								$('#dldoc').on('click', function() {
									window.open('".$dominio."/painel/clientes/rg/".$imagem."','_blank');
								})
							</script>
						";
					} else {
						echo "
							<img class='rgimg' style='max-width:100%;max-height:100%;' src='".$dominio."/painel/clientes/rg/".$imagem."?".rand(1, 999)."'></img>
							<script>
								$('.rgimg').on('click', function () {
									$.ajax({
										url: '".$dominio."/includes/biggerrg.inc.php',
										success: function(bigpic) {
											$('#vestimenta').html(bigpic);
										},
									});
								});
							</script>
						";
					}
							MontaBotao('atualizar imagem','atualizaimgrg');
						echo "
							<script>
								$('#atualizaimgrg').on('click', function () {
									loadVestimenta('".$dominio."/painel/clientes/novo/includes/atualizaimgrg.inc.php');
								});
							</script>
							</div>
						";
					} else {
						echo "
						<!-- img_rg_outer_wrap -->
	                                        <div id='img_rg_outer_wrap' class='uploadouterwrap'>
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
	                							<p id='statusUpload_rg' class='uploadstatusupload'></p>
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
										url: '".$dominio."/painel/clientes/novo/includes/salvaimgrg.inc.php'
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
	                                        <!-- img_rg_outer_wrap -->
						";
					} // se existe a imagem da rg
				?>

			</div>
		</div>
		<div style='min-width:100%;max-width:100%;display:inline-block;margin-top:5%;'>
			<?php MontaBotao('voltar','voltar'); ?>
		</div>
	</div>

</div>
<!-- items -->

<script>
	abreFundamental();
	$('#voltar').on('click',function() {
		lid = <?php echo $cliente['lid'] ?>;
		clienteFundamental(lid);
	});
</script>
