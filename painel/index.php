<?php
	require_once __DIR__.'/../cabecalho.php';

	if (isset($_SESSION['ag_id'])) {
		$adminivel = new ConsultaDatabase($uid);
		$adminivel = $adminivel->EncontraAdmin($_SESSION['ag_email']);
		if ($adminivel['nivel']==0) {
			redirectToLogin('entrar/logout');
		} // nivel 0
		$admincategoria = new ConsultaDatabase($uid);
		$admincategoria = $admincategoria->CadastroCategoria($adminivel['nivel']);

	} else {
		redirectToLogin();
	} // isset uid
?>
	<corpo>

		<!-- conteudo -->
		<div class='conteudo'>
			<?php
			$competentesDisponiveis = new ConsultaDatabase($uid);
			$competentesDisponiveis = $competentesDisponiveis->ListaCompetentes();

			if ($competentesDisponiveis[0]['vid']!=0) {
				echo "
					<div id='horasouterwrap'>
						<div id='horasinnerwrap''>
							<div id='horas'></div> <!-- horas -->
						</div>
					</div>
					<!-- horasouterwrap -->
				";
			} else {
				VamosComecar();
			} // se tem competentes
			?>

		</div>
		<!-- conteudo -->

<?php
	require_once __DIR__.'/../rodape.php';
?>
