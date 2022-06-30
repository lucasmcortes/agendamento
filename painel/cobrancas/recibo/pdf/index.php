<?php

	include_once __DIR__.'/../../../../includes/setup.inc.php';

	if (isset($_SESSION['ag_id'])) {
		$criador_pdf = 'Associação Apoio';
		$autor_pdf = 'Associação Apoio' ;
		$titulo_pdf = 'Associação Apoio' ;
		$assunto_pdf = 'Associação Apoio';
		$palavraschave_pdf = 'Associação Apoio';

		require_once __DIR__.'/recibo.pdf.php';

		$filename ='recibo_'.date('YmdHis').'.pdf';
		$filelocation = __DIR__ . '/../_recibos/';

		$fileNL = $filelocation."/".$filename;

		if (!file_exists($filelocation)) {
			mkdir($filelocation, 0755, true);
		}

		// exibe pdf
		$pdf->Output($fileNL, 'FI'); // salva e exibe
	} else if (!isset($_SESSION['ag_id'])) {
	        redirectToLogin();
	} // uid

?>
