<?php

include_once __DIR__.'/../../../../includes/setup.inc.php';

if (isset($_POST['submitcompetente'])) {
	$submit = '';
	$competente = '';

	$nome = $_POST['nome'];
	if (empty($nome)) {
		RespostaRetorno('nomecompetente');
		return;
	} // nome

	$observacao = $_POST['observacao']?:0;

	if (empty($nome)) {
		RespostaRetorno('vazio');
		return;
	} else {
		$encontraadmin = new ConsultaDatabase($uid);
		$encontraadmin = $encontraadmin->AdminInfo($uid);
		if ($encontraadmin!=0) {
			if ( ($encontraadmin['nivel']!=0) && ($encontraadmin['nivel']!=1) ) {
				// $authadmin = new ConsultaDatabase($uid);
				// $authadmin = $authadmin->AuthAdmin($encontraadmin['email'],$pwd);
				$authadmin = 1;
				if ($authadmin==0) {
					RespostaRetorno('authadmin');
					return;
				} else {
					$consultacompetente = new ConsultaDatabase($uid);
					$consultacompetente = $consultacompetente->EncontraCompetente($nome);
					if ($consultacompetente['vid']==0) {
						$addcompetente = new setRow();
						$addcompetente = $addcompetente->Competente($uid,$nome,1,$data);
				 		if ($addcompetente===true) {
							$vidcompetente = new ConsultaDatabase($uid);
							$vidcompetente = $vidcompetente->EncontraCompetente($nome);
						} else {
							RespostaRetorno('regcompetente');
							return;
						} // addcompetente true
					} else {
						RespostaRetorno('competenteexistente');
						return;
						$vidcompetente = new ConsultaDatabase($uid);
						$vidcompetente = $vidcompetente->EncontraCompetente($nome);
					} // consultacompetente true

					if ($vidcompetente['vid']==0) {
						RespostaRetorno('regcompetente');
						return;
					} else {
						$addcobs = new setRow();
						$addcobs = $addcobs->Cobs($uid,$vidcompetente['vid'],$observacao,$data);
						if ($addcobs===true) {
							RespostaRetorno('sucessocompetente');
							return;
						} else {
							RespostaRetorno('regvobs');
							return;
						} // addvobs true
					} // vidcompetente true
				} // authadmin true
			} else {
				RespostaRetorno('adminnivel');
				return;
			} // nivel ok
		} else {
			RespostaRetorno('adminencontrado');
			return;
		} // encontraadmin = 0
	} // campospreenchidos = 0
} else {
	$submit = ':((';
} // isset post submit

echo $submit;

?>
