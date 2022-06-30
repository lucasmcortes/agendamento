<?php

include_once __DIR__.'/../../../../includes/setup.inc.php';

if (isset($_POST['submitcliente'])) {
	$cadastrando = '';

	$nome = mb_strtoupper($_POST['nome']);
	if (empty($nome)) {
		RespostaRetorno('nomecliente');
		return;
	} // nome

	$cpf = $_POST['cpf'];
	if (empty($cpf)) {
		RespostaRetorno('cpf');
		return;
	} else {
		if (preg_match('/^(\d{3}\.\d{3}\.\d{3}\-\d{2})?+$/', $cpf, $cpf, PREG_UNMATCHED_AS_NULL)) {
			$cpf = $cpf[0];
		} else {
			RespostaRetorno('cpf');
			return;
		} // regex cpf
	} // cpf

	$telefone = $_POST['telefone'];
	if (empty($telefone)) {
		RespostaRetorno('telefone');
		return;
	} else {
		if (preg_match('/(\(\d{2}\)[ ]{1}\d{5}\-\d{4})/', $telefone, $telefone, PREG_UNMATCHED_AS_NULL)) {
			$telefone = $telefone[0];
		} else {
			RespostaRetorno('telefone');
			return;
		} // regex telefone
	} // telefone

	$rg = $_POST['rg'];
	if (empty($rg)) {
		RespostaRetorno('rg');
		return;
	} else {
		$o_exp = mb_strtoupper($_POST['oexp']);
		if (empty($o_exp)) {
			RespostaRetorno('rgoexp');
			return;
		} // o_exp
	} // rg
	// if (preg_match('/^(\d{9}\-\d{2})+$/', $rg, $rg, PREG_UNMATCHED_AS_NULL)) {
	// 	$rg = $rg[0];
	// 	$nome_img_rg = str_replace('-','',$rg);
	// } else {
	// 	$rg = '';
	// } // regex rg

	$email = $_POST['email'];
	if (empty($email)) {
		RespostaRetorno('email');
		return;
	} else {
		if (preg_match('/^[^\s@]+@[^\s@]+\.[^\s@]+$/', $email, $email, PREG_UNMATCHED_AS_NULL)) {
			$_SESSION['cadastro_email'] = $email = $email[0];
		} else {
			RespostaRetorno('email');
			return;
		} // regex email
	} // email

	$nascimento = $_POST['nascimento'];
	if (preg_match('/^(\d{2}\/\d{2}\/\d{4})+$/', $nascimento, $nascimento, PREG_UNMATCHED_AS_NULL)) {
		$_SESSION['cadastro_nascimento'] = $nascimento = $nascimento[0];
	} else {
		$nascimento = '';
	} // regex data de nascimento

	$cep = $_POST['cep'];
	if (preg_match('/^(\d{2}\.\d{3}\-\d{3})+$/', $cep, $cep, PREG_UNMATCHED_AS_NULL)) {
		$cep = $cep[0];
	} else {
		$cep = '';
	} // regex cep

	$rua = $_POST['rua']?:0;
	$numero = $_POST['numero']?:0;
	$bairro = $_POST['bairro']?:0;
	$cidade = $_POST['cidade']?:0;
	$estado = $_POST['estado']?:0;
	$complemento = $_POST['complemento']?:0;

	// $pwd = $_POST['pwd'];
	// if (empty($pwd)) {
	// 	RespostaRetorno('senha');
	// 	return;
	// } // pwd

	$remote_addr = $_SERVER['REMOTE_ADDR'];

	if (empty($nome) || empty($cpf) || empty($telefone) || empty($email) || empty($rg) ) {
		RespostaRetorno('vazio');
		return;

	} else {
		// if (!isset($_SESSION['img_rg_info_session'])) {
		// 	RespostaRetorno('rgimagem');
		// 	return;
		// } // se tem imagem da rg

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
					$valida_documento = new ValidaCPFCNPJ($cpf);
					if ($valida_documento->valida() ) {
						$consultacliente = new ConsultaDatabase($uid);
						$consultacliente = $consultacliente->cliente($cpf);
						if ($consultacliente['lid']==0) {
							$addcliente = new setRow();
							$addcliente = $addcliente->Cliente($uid,$nome,$cpf,$telefone,$email,$nascimento,$data);
							if ($addcliente===true) {
								$consultacliente = new ConsultaDatabase($uid);
								$consultacliente = $consultacliente->Cliente($cpf);
								if ($consultacliente['lid']!=0) {
									$addendereco = new setRow();
									$addendereco = $addendereco->Endereco($uid,$consultacliente['lid'],$cep,$rua,$numero,$bairro,$cidade,$estado,$complemento,1,$data);
									if ($addendereco===true) {
										$adddocumento = new setRow();
										$adddocumento = $adddocumento->Documento($uid,$consultacliente['lid'],$o_exp,$rg,$data);
										if ($adddocumento===true) {

											//// IMAGEM rg cliente
											clearstatcache();
											if (isset($_SESSION['img_rg_info_session'])) {
												if (is_file($_SESSION['img_rg_info_session']['img_rg_path'].$_SESSION['img_rg_info_session']['img_rg_nome_completo'])) {
													$imagem_location_pra_rename = $_SESSION['img_rg_info_session']['img_rg_url_completo'];
													fit_image_file_to_width($_SESSION['img_rg_info_session']['img_rg_url_completo'], 1080, $_SESSION['img_rg_info_session']['img_rg_mime']);
													$imagem_location_rename = __DIR__.'/../../rg/'.$rg.$_SESSION['img_rg_info_session']['img_rg_extensao'];
													copy($imagem_location_pra_rename,$imagem_location_rename);
													unlink($imagem_location_pra_rename);
													unset($_SESSION['img_rg_info_session']);

													clearstatcache();
													if (is_file($imagem_location_rename)) {
														// deleta imagens temporárias de rg
														//unlinkTemp(__DIR__.'/../temp/');
													       RespostaRetorno('sucessocliente');
													       return;
													} else {
													       RespostaRetorno('regrgimagem');
													       return;
													} // moveu a imagem true
												} else {
												       RespostaRetorno('regrgimagem');
												       return;
												} // file_exists
											} // $_SESSION['img_rg_info_session']

										} else {
										       RespostaRetorno('regdocumento');
										       return;
										} // adddocumento true
									} else {
									       RespostaRetorno('regendereco');
									       return;
									} // addendereco true
								} else {
								       RespostaRetorno('regcliente');
								       return;
								} // consultacliente
							} else {
							       RespostaRetorno('regcliente');
							       return;
							} // addcliente true
						} else {
						       RespostaRetorno('regclienteexistente');
						       return;
						} // novo cliente
					} else {
					       RespostaRetorno('cpfinvalido');
					       return;
					} // cpf válido
				} // authadmin true
			} else {
				RespostaRetorno('adminnivel');
				return;
			} // nivel ok
		} else {
			RespostaRetorno('adminencontrado');
			return;
		} // encontraadmin = 0
	} // campos preenchidos
} else {
	$cadastrando = ':((';
} // isset post submit

echo $cadastrando;

?>
