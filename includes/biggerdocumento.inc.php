<?php
	include_once __DIR__.'/setup.inc.php';
	BotaoFecharVestimenta();

	$imagem = glob(__DIR__.'/../painel/competentes/doc/'.$_SESSION['placa'].'.*', GLOB_BRACE);
	if (!empty($imagem)) {
		usort($imagem, fn($a, $b) => filemtime($b) - filemtime($a)); // arquivo mais recente
		$imagem = basename($imagem[0]);
	} else {
		$imagem = '';
	}
?>


	<!-- items -->
	<div style="overflow:auto;height:auto;max-height:81vh;">
		<img class='rgimg' style='max-width:300%;max-height:300%;' src='<?php echo $dominio ?>/painel/competentes/doc/<?php echo $imagem."?".rand(1,999) ?>'></img>
	</div>
	<!-- items -->

        <script>
        	abreVestimenta();
		$('#vestimenta').css('max-width','900px');
        </script>
