<?php

include_once __DIR__.'/../../../includes/setup.inc.php';
require_once __DIR__.'/../../../includes/cep.inc.php';
BotaoFecharVestimenta();

if (isset($_POST['cliente'])) {
	$lid = $_POST['cliente'];
	$cliente = new ConsultaDatabase($uid);
	$cliente = $cliente->clienteInfo($lid);

} else {
	$lid = 0;
}// $_post
?>

<!-- items -->
<div class="items">
	<?php
		tituloCarro($cliente['nome']);
		EnviandoImg();
	?>

	<div style='min-width:100%;max-width:100%;display:inline-block;'>
		<p id='retorno' class='retorno'>
	        </p> <!-- retorno -->
		<div id='altendwrap' style='min-width:100%;max-width:100%;display:inline-block;'>

			<!-- endereco wrap -->
			<div id='enderecowrap'>
				<div style='display:flex;gap:2%;'>
					<div style='flex:3;'>
						<label>CEP</label>
						<input onkeyup='maskIt(this,event,"##.###-###")' max-length='8' type='text' placeholder='99.999-99' name='cep' id='cep'>
					</div>
					<div style='flex:8;'>
						<label>Rua</label>
						<input type='text' placeholder='Rua' name='rua' id='rua'>
					</div>
				</div>

				<div style='display:flex;gap:2%;'>
					<div style='flex:1;'>
						<label>Nº</label>
						<input type='text' placeholder='Número' name='numero' id='numero'>
					</div>
					<div style='flex:3;'>
						<label>Bairro</label>
						<input type='text' placeholder='Bairro' name='bairro' id='bairro'>
					</div>
				</div>

				<div style='display:flex;gap:2%;'>
					<div style='flex:3;'>
						<label>Cidade</label>
						<input type='text' placeholder='Cidade' name='cidade' id='cidade'>
					</div>
					<div style='flex:1;'>
						<label>UF</label>
						<input type='text' placeholder='Estado' name='estado' id='estado'>
					</div>
				</div>

				<div style='max-width:100%;min-width:100%;margin:0 auto;display:inline-block;'>
					<label>Complemento</label>
					<input type='text' placeholder='Complemento' name='complemento' id='complemento'>
				</div>
			</div>
			<!-- endereco wrap -->

		</div> <!-- altend wrap -->

		<div style='display:flex;'>
			<div style='flex:1;'>
				<?php MontaBotaoSecundario('voltar','voltar'); ?>
			</div>
			<div style='flex:1;'>
				<?php MontaBotao('salvar','enviaraltend'); ?>
			</div>
		</div>
	</div>

</div>
<!-- items -->

<script>
	abreVestimenta();

	$('#enviaraltend').on('click',function() {
                enviandoimg = $('#enviando');
                enviarform = $('#enviaraltend');
                retorno = $('#retorno');
                formulario = $('#altendwrap');

		lid = <?php echo $cliente['lid'] ?>;
		valcep = $('#cep').val();
		valrua = $('#rua').val();
		valnumero = $('#numero').val();
		valbairro = $('#bairro').val();
		valcidade = $('#cidade').val();
		valestado = $('#estado').val();
		valcomplemento = $('#complemento').val();
		$.ajax({
			type: 'POST',
			url: '<?php echo $dominio ?>/painel/clientes/includes/altendmod.inc.php',
			data: {
				cliente: lid,
				cep: valcep,
				rua: valrua,
				numero: valnumero,
				bairro: valbairro,
				cidade: valcidade,
				estado: valestado,
				complemento: valcomplemento
			},
			beforeSend: function(altendmod) {
				window.scrollTo(0,0);
				enviandoimg.css('display', 'block');
				formulario.css('display', 'none');
				retorno.css('display', 'none');
			},
			success: function(altendmod) {
				window.scrollTo(0,0);
				bordaRosa();
				enviandoimg.css('display', 'none');
				formulario.css('display', 'inline-block');
				retorno.css('display', 'inline-block');

				retorno.html(altendmod);

				if (altendmod.includes('sucesso') == true) {
					formulario.remove();
					retorno.append('<img id=\"sucessogif\" src=\"<?php echo $dominio ?>/img/sucesso.gif\">');
					setTimeout(function() {
						$('#voltar').trigger('click');
					},1234);
					mostraFooter();
				} else {
					$('#bannerfooter').css('display','none');
				}
			}
		});
	});

	$('#voltar').on('click',function() {
		$('#fecharvestimenta').trigger('click');
	});
</script>
