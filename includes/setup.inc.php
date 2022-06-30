<?php

	if (session_status() === PHP_SESSION_NONE) {
		session_start();
	}
	$sid = session_id();

	setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
	setlocale(LC_CTYPE, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
	setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
	date_default_timezone_set('America/Sao_Paulo');
	mb_internal_encoding('UTF8');
	mb_regex_encoding('UTF8');

	$data = date_create()->format('Y-m-d H:i:s.u');
	$data_extenso = strftime('%A, %d de %B de %Y às %Hh%M', strtotime($data));
	$agora = new DateTime($data);
	$anoatual = $agora->format("Y");

	// função que carrega as classes quando chama nos scripts
	spl_autoload_register(function ($classe) {
		$caminho = __DIR__.'/classes/';
		$extensao = '.class.php';
		$caminhao = $caminho . $classe . $extensao;

		if (!file_exists($caminhao)) {
			return false;
		}

		include_once $caminhao;
	}); // func loadClasses

	// DOMÍNIO
	$nomedaloja = 'agendamento';
	$nomedadb = 'agendamento';
	$dominio = 'http://localhost/agendamento';
	// $dominio = 'https://www.lucascortes.com.br/' . $nomedaloja;

	// pra onde ir depois de entrar no function eDepois($next_url)
	if (!isset($_SESSION['ag_id'])) {

		$uid = 0;

		$next_url = explode('/', $_SERVER['REQUEST_URI']);
		$next_url = array_slice($next_url, 1, -1);
		$next_url = implode('/', $next_url);
		// e usa na função eDepois($next_url);
		// na página que precisa redirecionar depois de logar

	} else if (isset($_SESSION['ag_id'])) {
		$uid = $_SESSION['ag_id'];
	}

	require_once __DIR__.'/../vendor/autoload.php'; // composer
	require_once __DIR__.'/root.inc.php'; // cores no var(--cor);
	require_once __DIR__.'/precos.inc.php'; // precos
   	require_once __DIR__.'/functions.php'; // várias funções do site
	require_once __DIR__.'/title.inc.php'; // título dinâmico
	require_once __DIR__.'/visita.inc.php'; // diz o link que entrará como visita na db
	require_once __DIR__.'/setup.mail.php'; // class cartinha

	$configuracoes = new ConsultaDatabase($uid);
	$configuracoes = $configuracoes->Configuracoes();
	$acrescimoaid = 0;

	// pro 'entrar/entrar-portatil.php'
	if (isset($visita_link_cleared)) {
		setcookie('sequencia',str_replace($dominio.'/', '', $visita_link_cleared),time() + (86400 * 30),"/");
	} // if

	$fragmentoatual = explode('/',$_SERVER['REQUEST_URI'],-1);
	$fragmentoatual = end($fragmentoatual);

?>
