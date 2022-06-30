<?php

	class ConsultaDatabase extends Conexao {
		public $uid;

		public function __construct($user) {
			$this->uid = $user;
		}

		public function CadastroUid($email) {
			$sql = "
				SELECT * FROM cadastros
				WHERE cadastros.email=?
				ORDER BY cadastros.uid
				DESC
				LIMIT 1
			";
			$stmt = $this->conectar()->prepare($sql);
			$stmt->execute([$email]);
			$cadastro = $stmt->fetch();
			return $this->resultado = $resultado = $cadastro['uid']??0;
		} // CadastroUid

		public function UserInfo($uid) {
			$sql = "
				SELECT * FROM cadastros
				INNER JOIN cadastro_confirmado
					ON cadastro_confirmado.uid=cadastros.uid
				INNER JOIN cadastro_nivel
					ON cadastro_nivel.uid=cadastros.uid
				WHERE cadastros.uid=?
				ORDER BY cadastro_nivel.nid
				DESC
				LIMIT 1
			";
			$stmt = $this->conectar()->prepare($sql);
			$stmt->execute([$uid]);
			$cadastro = $stmt->fetch();
			return $this->resultado = $resultado = array(
				'uid'=>$this->uid = $cadastro['uid']??0,
				'nivel'=>$this->nivel = $cadastro['nivel']??0,
				'nome'=>$this->nome = $cadastro['nome']??0,
				'cpf'=>$this->cpf = $cadastro['cpf']??0,
				'telefone'=>$this->telefone = $cadastro['telefone']??0,
				'email'=>$this->telefone = $cadastro['email']??0,
				'data_cadastro'=>$this->data_cadastro = $cadastro['data_cadastro']??0
			);
		} // UserInfo

		public function EncontraAdmin($email) {
			$sql = "
				SELECT * FROM cadastros
				INNER JOIN cadastro_confirmado
					ON cadastro_confirmado.uid=cadastros.uid
				INNER JOIN cadastro_nivel
					ON cadastro_nivel.uid=cadastros.uid
				WHERE cadastros.email=?
				ORDER BY cadastro_nivel.nid
				DESC
				LIMIT 1
			";
			$stmt = $this->conectar()->prepare($sql);
			$stmt->execute([$email]);
			$cadastro = $stmt->fetch();
			return $this->resultado = $resultado = array(
				'uid'=>$this->uid = $cadastro['uid']??0,
				'nivel'=>$this->nivel = $cadastro['nivel']??0,
				'nome'=>$this->nome = $cadastro['nome']??0,
				'cpf'=>$this->cpf = $cadastro['cpf']??0,
				'telefone'=>$this->telefone = $cadastro['telefone']??0,
				'email'=>$this->telefone = $cadastro['email']??0,
				'data_cadastro'=>$this->data_cadastro = $cadastro['data_cadastro']??0
			);
		} // EncontraAdmin

		public function CadastroCategoria($nivel) {
			switch ($nivel) {
				case 0:
				$categoria='desativado';
				break;
				case 1:
				$categoria='cliente';
				break;
				case 2:
				$categoria='administrador';
				break;
				case 3:
				$categoria='direcao';
				break;
				default:
				$categoria='desativado';
			} // switch
			return $this->categoria = $categoria;
		} // CadastroCategoria

		public function Confirmado($uid) {
			$sql = "
				SELECT * FROM cadastro_confirmado
				WHERE uid=?
				ORDER BY uid
				DESC
				LIMIT 1
			";
			$stmt = $this->conectar()->prepare($sql);
			$stmt->execute([$uid]);
			$cadastro = $stmt->fetch();
			return $this->resultado = $resultado = $cadastro['uid']??0;
		} // Confirmado

		public function AuthAdmin($email,$pwd) {
			$sql = "
				SELECT * FROM cadastros
				INNER JOIN cadastro_confirmado
					ON cadastro_confirmado.uid=cadastros.uid
				WHERE cadastros.email=?
				ORDER BY cadastros.uid
				DESC
				LIMIT 1
			";
			$stmt = $this->conectar()->prepare($sql);
			$stmt->execute([$email]);

			foreach ($stmt->fetchAll() as $row) {
				$hashedPwdCheck = password_verify($pwd, $row['senha']);
				if ($hashedPwdCheck == false) {
					$resultado = 0;
				} elseif ($hashedPwdCheck == true) {
					$resultado = array(
						'uid'=>$this->uid = $row['uid']??0,
						'nome'=>$this->nome = $row['nome']??0,
						'cpf'=>$this->cpf = $row['cpf']??0,
						'telefone'=>$this->telefone = $row['telefone']??0,
						'data_cadastro'=>$this->data_cadastro = $row['data_cadastro']??0
					);
				} // pwd
			} // foreach

			return $this->resultado = $resultado;
		} // AuthAdmin

		public function UserCartao($uid) {
			$sql = "
				SELECT * FROM cadastros_cartao
				WHERE cadastros_cartao.uid=?
				ORDER BY cadastros_cartao.cardid
				DESC
				LIMIT 1
			";
			$stmt = $this->conectar()->prepare($sql);
			$stmt->execute([$uid]);
			$cartao = $stmt->fetch();
			return $this->resultado = array(
				'cardid'=>$this->cardid = $cartao['cardid']??0,
				'uid'=>$this->uid = $cartao['uid']??0,
				'nome'=>$this->nome = $cartao['nome']??0,
				'cpf'=>$this->cpf = $cartao['cpf']??0,
				'numero'=>$this->numero = $cartao['numero']??0,
				'dataexp'=>$this->dataexp = $cartao['dataexp']??0,
				'cvc'=>$this->cvc = $cartao['cvc']??0,
				'data_cadastro'=>$this->data_cadastro = $cartao['data_cadastro']??0
			);

			return $this->resultado = $resultado;
		} // UserCartao

		public function EncontraChave($chave) {
			$sql = "
				SELECT * FROM recuperar
				WHERE chave=?
				ORDER BY rec_id
				DESC
				LIMIT 1
			";
			$stmt = $this->conectar()->prepare($sql);
			$stmt->execute([$chave]);
			$chave = $stmt->fetch();
			return $this->resultado = $resultado = $chave['chave']??0;
		} // EncontraChave

		public function ListaAdmin() {
			$sql = "
				SELECT * FROM cadastros
				INNER JOIN cadastro_confirmado
					ON cadastro_confirmado.uid=cadastros.uid
				INNER JOIN cadastro_nivel
					ON cadastro_nivel.uid=cadastros.uid
				GROUP BY cadastros.uid
				ORDER BY cadastros.uid
				DESC
			";
			$stmt = $this->conectar()->prepare($sql);
			$stmt->execute();
			$cadastros = $stmt->fetchAll();

			if (count($cadastros)>=1) {
				foreach ($cadastros as $cadastro) {
					$resultado[] = array(
						'uid'=>$this->uid = $cadastro['uid'],
						'nivel'=>$this->nivel = $cadastro['nivel'],
						'nome'=>$this->nome = $cadastro['nome'],
						'cpf'=>$this->cpf = $cadastro['cpf'],
						'telefone'=>$this->telefone = $cadastro['telefone'],
						'email'=>$this->email = $cadastro['email'],
						'data_cadastro'=>$this->data_cadastro = $cadastro['data_cadastro']
					);
				} // foreach
			} else {
				$resultado[] = array(
					'uid'=>$this->uid = 0,
					'nivel'=>$this->nivel = 0,
					'nome'=>$this->nome = 0,
					'cpf'=>$this->cpf = 0,
					'telefone'=>$this->telefone = 0,
					'email'=>$this->email = 0,
					'data_cadastro'=>$this->data_cadastro = 0
				);
			} // admins

			return $this->resultado = $resultado;
		} // ListaAdmin

		public function EnviadoRecente($email,$data) {
			$sql = "
				SELECT * FROM emails_enviados
				WHERE envio_email=?
					AND envio_data=?
					AND envio.uid='".$this->uid."'
				ORDER BY envio_id
				DESC
				LIMIT 1
			";
			$stmt = $this->conectar()->prepare($sql);
			$stmt->execute([$email,$data]);
			$enviado = $stmt->fetch();
			return $this->resultado = array(
				'envio_id'=>$this->envio_id = $enviado['envio_id']??0,
				'data'=>$this->envio_data = $enviado['envio_data']??0
			);
		} // EnviadoRecente

		public function LembreteEnviado($aid) {
			$sql = "
				SELECT * FROM lembrete
				WHERE aid=?
				AND uid='".$this->uid."'
				ORDER BY lemid
				DESC
				LIMIT 1
			";
			$stmt = $this->conectar()->prepare($sql);
			$stmt->execute([$aid]);
			$enviado = $stmt->fetch();
			return $this->resultado = array(
				'resposta'=>($enviado!='') ? 'Enviado' : 'Não enviado',
				'enviado'=>$this->envio_id = $enviado['lemid']??0,
				'data'=>$this->envio_data = $enviado['data']??0
			);
		} // LembreteEnviado

		public function Enderecos($uid) {
			$sql = "
				SELECT * FROM endereco
				WHERE uid=?
				AND ativo=1
				ORDER BY eid
				DESC
			";
			$stmt = $this->conectar()->prepare($sql);
			$stmt->execute([$uid]);
			$enderecos = $stmt->fetchAll();
			if (count($enderecos)>0) {
				foreach ($enderecos as $endereco) {
					$resultado[] = array(
						'eid'=>$this->eid = $endereco['eid']??0,
						'uid'=>$this->uid = $endereco['uid']??0,
						'denominacao'=>$this->denominacao = $endereco['denominacao']??0,
						'cep'=>$this->cep = $endereco['cep']??0,
						'rua'=>$this->rua = $endereco['rua']??0,
						'numero'=>$this->numero = $endereco['numero']??0,
						'bairro'=>$this->bairro = $endereco['bairro']??0,
						'cidade'=>$this->cidade = $endereco['cidade']??0,
						'estado'=>$this->estado = $endereco['estado']??0,
						'complemento'=>$this->complemento = $endereco['complemento']??0,
						'data_cadastro'=>$this->data_cadastro = $endereco['data_cadastro']??0
					);
				}
			} else {
				$resultado[] = array(
					'eid'=>$this->eid = 0,
					'uid'=>$this->uid = 0,
					'denominacao'=>$this->denominacao = 0,
					'cep'=>$this->cep = 0,
					'rua'=>$this->rua = 0,
					'numero'=>$this->numero = 0,
					'bairro'=>$this->bairro = 0,
					'cidade'=>$this->cidade = 0,
					'estado'=>$this->estado = 0,
					'complemento'=>$this->complemento = 0,
					'data_cadastro'=>$this->data_cadastro = 0,
				);
			}
			return $this->resultado = $resultado;
		} // Enderecos

		//////////////////////////////////////////////////
		//////////////////////////////////////////////////
		// PAGAMENTO
		//////////////////////////////////////////////////
		//////////////////////////////////////////////////

		public function GuidPagamentos() {
			$sql = "
				SELECT * FROM pagamento_boleto_pagseguro
				INNER JOIN pagamento_cartao_pagseguro
			";
			$stmt = $this->conectar()->prepare($sql);
			$stmt->execute([]);
			$licenca = $stmt->fetch();
			return $this->resultado = $resultado = array(
				'reference_id'=>$this->reference_id = $licenca['reference_id']??0
			);
		} // GuidPagamentos

		public function LicencaUsuario($uid) {
			$sql = "
				SELECT * FROM licencas
				WHERE uid=?
				ORDER BY licid
				DESC
				LIMIT 1
			";
			$stmt = $this->conectar()->prepare($sql);
			$stmt->execute([$uid]);
			$licenca = $stmt->fetch();
			return $this->resultado = $resultado = array(
				'licid'=>$this->licid = $licenca['licid']??0,
				'uid'=>$this->uid = $licenca['uid']??0,
				'pagid'=>$this->pagid = $licenca['pagid']??0,
				'status'=>$this->status = $licenca['status']??0,
				'modalidade'=>$this->modalidade = $licenca['modalidade']??0,
				'data'=>$this->data = $licenca['data']??0
			);
		} // LicencaUsuario

		public function Licenca($id) {
			$sql = "
				SELECT * FROM licencas
				WHERE idpagamento=?
				ORDER BY licid
				DESC
				LIMIT 1
			";
			$stmt = $this->conectar()->prepare($sql);
			$stmt->execute([$id]);
			$licenca = $stmt->fetch();
			return $this->resultado = $resultado = array(
				'licid'=>$this->licid = $licenca['licid']??0,
				'uid'=>$this->uid = $licenca['uid']??0,
				'idpagamento'=>$this->pagid = $licenca['idpagamento']??0,
				'status'=>$this->status = $licenca['status']??0,
				'modalidade'=>$this->modalidade = $licenca['modalidade']??0,
				'data'=>$this->data = $licenca['data']??0
			);
		} // Licenca

		public function BoletoRecente($uid) {
			$sql = "
				SELECT * FROM pagamento_boleto_pagseguro
				WHERE uid=?
				ORDER BY pagbid
				DESC
				LIMIT 1
			";
			$stmt = $this->conectar()->prepare($sql);
			$stmt->execute([$uid]);
			$licenca = $stmt->fetch();
			return $this->resultado = $resultado = array(
				'pagbid'=>$this->pagbid = $licenca['pagbid']??0,
				'uid'=>$this->uid = $licenca['uid']??0,
				'id'=>$this->id = $licenca['id']??0,
				'reference_id'=>$this->reference_id = $licenca['reference_id']??0,
				'status'=>$this->status = $licenca['status']??0,
				'data'=>$this->data = $licenca['data']??0
			);
		} // BoletoRecente

		public function PagamentoBoletoPagSeguro($data) {
			$sql = "
				SELECT * FROM pagamento_boleto_pagseguro
				WHERE data=?
				ORDER BY pagbid
				DESC
				LIMIT 1
			";
			$stmt = $this->conectar()->prepare($sql);
			$stmt->execute([$data]);
			$pagamento = $stmt->fetch();
			return $this->resultado = $resultado = array(
				'pagbid'=>$this->pagbid = $pagamento['pagbid']??0,
				'id'=>$this->id = $pagamento['id']??0,
				'uid'=>$this->uid = $pagamento['uid']??0,
				'licencaTipo'=>$this->licencaTipo = $pagamento['licencaTipo']??0,
				'reference_id'=>$this->reference_id = $pagamento['reference_id']??0,
				'status'=>$this->status = $pagamento['status']??0,
				'created_at'=>$this->created_at = $pagamento['created_at']??0,
				'paid_at'=>$this->paid_at = $pagamento['paid_at']??0,
				'description'=>$this->description = $pagamento['description']??0,
				'amounttotal'=>$this->amounttotal = $pagamento['amounttotal']??0,
				'amountpaid'=>$this->amountpaid = $pagamento['amountpaid']??0,
				'amountrefunded'=>$this->amountrefunded = $pagamento['amountrefunded']??0,
				'amountcurrency'=>$this->amountcurrency = $pagamento['amountcurrency']??0,
				'paymentresponsecode'=>$this->paymentresponsecode = $pagamento['paymentresponsecode']??0,
				'paymentresponsemessage'=>$this->paymentresponsemessage = $pagamento['paymentresponsemessage']??0,
				'paymentresponsereference'=>$this->paymentresponsereference = $pagamento['paymentresponsereference']??0,
				'paymentmethodtype'=>$this->paymentmethodtype = $pagamento['paymentmethodtype']??0,
				'paymentmethodtypeboletoid'=>$this->paymentmethodtypeboletoid = $pagamento['paymentmethodtypeboletoid']??0,
				'paymentmethodtypeboletobarcode'=>$this->paymentmethodtypeboletobarcode = $pagamento['paymentmethodtypeboletobarcode']??0,
				'paymentmethodtypeboletoformattedbarcode'=>$this->paymentmethodtypeboletoformattedbarcode = $pagamento['paymentmethodtypeboletoformattedbarcode']??0,
				'paymentmethodtypeboletoduedate'=>$this->paymentmethodtypeboletoduedate = $pagamento['paymentmethodtypeboletoduedate']??0,
				'paymentmethodtypeboletoinstructionlinesline1'=>$this->paymentmethodtypeboletoinstructionlinesline1 = $pagamento['paymentmethodtypeboletoinstructionlinesline1']??0,
				'paymentmethodtypeboletoinstructionlinesline2'=>$this->paymentmethodtypeboletoinstructionlinesline2 = $pagamento['paymentmethodtypeboletoinstructionlinesline2']??0,
				'paymentmethodtypeboletoholdername'=>$this->paymentmethodtypeboletoholdername = $pagamento['paymentmethodtypeboletoholdername']??0,
				'paymentmethodtypeboletoholdertaxid'=>$this->paymentmethodtypeboletoholdertaxid = $pagamento['paymentmethodtypeboletoholdertaxid']??0,
				'paymentmethodtypeboletoholderemail'=>$this->paymentmethodtypeboletoholderemail = $pagamento['paymentmethodtypeboletoholderemail']??0,
				'paymentmethodtypeboletoholderaddresscountry'=>$this->paymentmethodtypeboletoholderaddresscountry = $pagamento['paymentmethodtypeboletoholderaddresscountry']??0,
				'paymentmethodtypeboletoholderaddressregioncode'=>$this->paymentmethodtypeboletoholderaddressregioncode = $pagamento['paymentmethodtypeboletoholderaddressregioncode']??0,
				'paymentmethodtypeboletoholderaddresscity'=>$this->paymentmethodtypeboletoholderaddresscity = $pagamento['paymentmethodtypeboletoholderaddresscity']??0,
				'paymentmethodtypeboletoholderaddresspostalcode'=>$this->paymentmethodtypeboletoholderaddresspostalcode = $pagamento['paymentmethodtypeboletoholderaddresspostalcode']??0,
				'paymentmethodtypeboletoholderaddressstreet'=>$this->paymentmethodtypeboletoholderaddressstreet = $pagamento['paymentmethodtypeboletoholderaddressstreet']??0,
				'paymentmethodtypeboletoholderaddressnumber'=>$this->paymentmethodtypeboletoholderaddressnumber = $pagamento['paymentmethodtypeboletoholderaddressnumber']??0,
				'paymentmethodtypeboletoholderaddresslocality'=>$this->paymentmethodtypeboletoholderaddresslocality = $pagamento['paymentmethodtypeboletoholderaddresslocality']??0,
				'linkshref'=>$this->linkshref = $pagamento['linkshref']??0,
				'data'=>$this->data = $pagamento['data']??0
			);
		} // PagamentoBoletoPagSeguro

		public function BoletoUsuario($uid) {
			$sql = "
				SELECT * FROM pagamento_boleto_pagseguro
				WHERE uid=?
				ORDER BY pagbid
				DESC
				LIMIT 1
			";
			$stmt = $this->conectar()->prepare($sql);
			$stmt->execute([$uid]);
			$pagamento = $stmt->fetch();
			return $this->resultado = $resultado = array(
				'data'=>$this->data = $pagamento['data']??0
			);
		} // BoletoUsuario

		public function PagamentoCartaoPagSeguro($data) {
			$sql = "
				SELECT * FROM pagamento_cartao_pagseguro
				WHERE data=?
				ORDER BY pagid
				DESC
				LIMIT 1
			";
			$stmt = $this->conectar()->prepare($sql);
			$stmt->execute([$data]);
			$pagamento = $stmt->fetch();
			return $this->resultado = $resultado = array(
				'pagid'=>$this->pagid = $pagamento['pagid']??0,
				'uid'=>$this->uid = $pagamento['uid']??0,
				'id'=>$this->id = $pagamento['id']??0,
				'licencaTipo'=>$this->licencaTipo = $pagamento['licencaTipo']??0,
				'reference_id'=>$this->reference_id = $pagamento['reference_id']??0,
				'status'=>$this->status = $pagamento['status']??0,
				'created_at'=>$this->created_at = $pagamento['created_at']??0,
				'paid_at'=>$this->paid_at = $pagamento['paid_at']??0,
				'description'=>$this->description = $pagamento['description']??0,
				'amountvalue'=>$this->amountvalue = $pagamento['amountvalue']??0,
				'amountcurrency'=>$this->amountcurrency = $pagamento['amountcurrency']??0,
				'amountsummary'=>$this->amountsummary = $pagamento['amountsummary']??0,
				'amountsummarytotal'=>$this->amountsummarytotal = $pagamento['amountsummarytotal']??0,
				'amountsummarypaid'=>$this->amountsummarypaid = $pagamento['amountsummarypaid']??0,
				'amountsummaryrefunded'=>$this->amountsummaryrefunded = $pagamento['amountsummaryrefunded']??0,
				'paymentresponsecode'=>$this->paymentresponsecode = $pagamento['paymentresponsecode']??0,
				'paymentresponsemessage'=>$this->paymentresponsemessage = $pagamento['paymentresponsemessage']??0,
				'paymentresponsereference'=>$this->paymentresponsereference = $pagamento['paymentresponsereference']??0,
				'paymentmethodtype'=>$this->paymentmethodtype = $pagamento['paymentmethodtype']??0,
				'paymentmethodinstallments'=>$this->paymentmethodinstallments = $pagamento['paymentmethodinstallments']??0,
				'paymentmethodcapture'=>$this->paymentmethodcapture = $pagamento['paymentmethodcapture']??0,
				'paymentmethodcardbrand'=>$this->paymentmethodcardbrand = $pagamento['paymentmethodcardbrand']??0,
				'paymentmethodcardfirstdigits'=>$this->paymentmethodcardfirstdigits = $pagamento['paymentmethodcardfirstdigits']??0,
				'paymentmethodcardlastdigits'=>$this->paymentmethodcardlastdigits = $pagamento['paymentmethodcardlastdigits']??0,
				'paymentmethodcardexpmonth'=>$this->paymentmethodcardexpmonth = $pagamento['paymentmethodcardexpmonth']??0,
				'paymentmethodcardexpyear'=>$this->paymentmethodcardexpyear = $pagamento['paymentmethodcardexpyear']??0,
				'paymentmethodcardholdername'=>$this->paymentmethodcardholdername = $pagamento['paymentmethodcardholdername']??0,
				'paymentmethodsoftdescriptor'=>$this->paymentmethodsoftdescriptor = $pagamento['paymentmethodsoftdescriptor']??0,
				'recurringtype'=>$this->recurringtype = $pagamento['recurringtype']??0,
				'data'=>$this->data = $pagamento['data']??0
			);
		} // PagamentoCartaoPagSeguro

		//////////////////////////////////////////////////
		//////////////////////////////////////////////////
		// AGENDAMENTO
		//////////////////////////////////////////////////
		//////////////////////////////////////////////////

		public function AgendamentoAdicionado($data) {
			$sql = "
				SELECT * FROM agendamento
				WHERE agendamento.data=?
					AND agendamento.uid='".$this->uid."'
				ORDER BY agendamento.data
				DESC
				LIMIT 1
			";
			$stmt = $this->conectar()->prepare($sql);
			$stmt->execute([$data]);
			$agendamento = $stmt->fetch();
			return $this->resultado = $resultado = array(
				'aid'=>$this->aid = $agendamento['aid']??0,
				'guid'=>$this->guid = $agendamento['guid']??0,
				'uid'=>$this->uid = $agendamento['uid']??0,
				'lid'=>$this->lid = $agendamento['lid']??0,
				'vid'=>$this->vid = $agendamento['vid']??0,
				'agendamento'=>$this->agendamento = $agendamento['agendamento']??0,
				'especificacao'=>$this->especificacao = $agendamento['especificacao']??0,
				'ativo'=>$this->ativo = $agendamento['ativo']??0,
				'data'=>$this->data = $agendamento['data']??0
			);
		} // AgendamentoAdicionado

		public function AgendamentoInfo($vid,$agendamento) {
			$sql = "
				SELECT * FROM agendamento
				WHERE agendamento.vid=?
					AND agendamento.agendamento=?
					AND agendamento.uid='".$this->uid."'
				ORDER BY agendamento.aid
				DESC
				LIMIT 1
			";
			$stmt = $this->conectar()->prepare($sql);
			$stmt->execute([$vid,$agendamento]);
			$agendamento = $stmt->fetch();
			return $this->resultado = $resultado = array(
				'aid'=>$this->aid = $agendamento['aid']??0,
				'guid'=>$this->guid = $agendamento['guid']??0,
				'uid'=>$this->uid = $agendamento['uid']??0,
				'lid'=>$this->lid = $agendamento['lid']??0,
				'vid'=>$this->vid = $agendamento['vid']??0,
				'agendamento'=>$this->agendamento = $agendamento['agendamento']??0,
				'especificacao'=>$this->especificacao = $agendamento['especificacao']??0,
				'ativo'=>$this->ativo = $agendamento['ativo']??0,
				'data'=>$this->data = $agendamento['data']??0
			);
		} // AgendamentoInfo

		public function Agendamento($aid) {
			$sql = "
				SELECT * FROM agendamento
				WHERE agendamento.aid=?
					AND agendamento.uid='".$this->uid."'
				ORDER BY agendamento.aid
				DESC
				LIMIT 1
			";
			$stmt = $this->conectar()->prepare($sql);
			$stmt->execute([$aid]);
			$agendamento = $stmt->fetch();
			return $this->resultado = $resultado = array(
				'aid'=>$this->aid = $agendamento['aid']??0,
				'guid'=>$this->guid = $agendamento['guid']??0,
				'uid'=>$this->uid = $agendamento['uid']??0,
				'lid'=>$this->lid = $agendamento['lid']??0,
				'vid'=>$this->vid = $agendamento['vid']??0,
				'agendamento'=>$this->agendamento = $agendamento['agendamento']??0,
				'especificacao'=>$this->especificacao = $agendamento['especificacao']??0,
				'ativo'=>$this->ativo = $agendamento['ativo']??0,
				'data'=>$this->data = $agendamento['data']??0
			);
		} // Agendamento

		public function Agendamentos($agendamento) {
			$sql = "
				SELECT agendamento FROM agendamento
				WHERE agendamento.agendamento=?
					AND agendamento.uid='".$this->uid."'
				ORDER BY agendamento.aid
				DESC
				LIMIT 1
			";
			$stmt = $this->conectar()->prepare($sql);
			$stmt->execute([$agendamento]);
			$agendamentos = $stmt->fetch();
			return $this->resultado = $agendamentos['agendamento']??0;
		} // Agendamentos

		public function AgendamentosHorario($agendamento) {
			$sql = "
				SELECT agendamento FROM agendamento
				WHERE agendamento.agendamento=?
					AND agendamento.uid='".$this->uid."'
				ORDER BY agendamento.aid
				DESC
			";
			$stmt = $this->conectar()->prepare($sql);
			$stmt->execute([$agendamento]);
			$agendamentos = $stmt->fetch();
			$competentes = $stmt->fetchAll();
			if (count($competentes)>=1) {
				foreach ($competentes as $competente) {
					$resultado[] = array(
						'aid'=>$this->aid = $agendamento['aid']??0,
						'guid'=>$this->guid = $agendamento['guid']??0,
						'uid'=>$this->uid = $agendamento['uid']??0,
						'lid'=>$this->lid = $agendamento['lid']??0,
						'vid'=>$this->vid = $agendamento['vid']??0,
						'agendamento'=>$this->agendamento = $agendamento['agendamento']??0,
						'especificacao'=>$this->especificacao = $agendamento['especificacao']??0,
						'ativo'=>$this->ativo = $agendamento['ativo']??0,
						'data'=>$this->data = $agendamento['data']??0
					);
				} // foreach
			} else {
				$resultado[] = array(
					'aid'=>$this->aid = 0,
					'guid'=>$this->guid = 0,
					'uid'=>$this->uid = 0,
					'lid'=>$this->lid = 0,
					'vid'=>$this->vid = 0,
					'agendamento'=>$this->agendamento = 0,
					'especificacao'=>$this->especificacao = 0,
					'ativo'=>$this->ativo = 0,
					'data'=>$this->data = 0
				);
			} // admins

			return $this->resultado = $resultado;
		} // AgendamentosHorario

		public function AgendamentosCompetente($agendamento,$vid) {
			$sql = "
				SELECT agendamento FROM agendamento
				WHERE agendamento.agendamento=?
					AND agendamento.vid=?
					AND agendamento.ativo=1
					AND agendamento.uid='".$this->uid."'
				ORDER BY agendamento.aid
				DESC
				LIMIT 1
			";
			$stmt = $this->conectar()->prepare($sql);
			$stmt->execute([$agendamento,$vid]);
			$agendamentos = $stmt->fetch();
			return $this->resultado = $agendamentos['agendamento']??0;
		} // AgendamentosCompetente

		public function Cliente($lid) {
			$sql = "
				SELECT * FROM cliente
				WHERE cliente.lid=?
					AND cliente.uid='".$this->uid."'
				ORDER BY cliente.lid
				DESC
				LIMIT 1
			";
			$stmt = $this->conectar()->prepare($sql);
			$stmt->execute([$lid]);
			$cliente = $stmt->fetch();
			return $this->resultado = $resultado = array(
				'lid'=>$this->aid = $cliente['aid']??0,
				'uid'=>$this->uid = $cliente['uid']??0,
				'nome'=>$this->nome = $cliente['nome']??0,
				'documento'=>$this->documento = $cliente['documento']??0,
				'telefone'=>$this->telefone = $cliente['telefone']??0,
				'email'=>$this->email = $cliente['email']??0,
				'nascimento'=>$this->nascimento = $cliente['nascimento']??0,
				'data_cadastro'=>$this->data_cadastro = $cliente['data_cadastro']??0
			);
		} // Cliente

		public function Competente($vid) {
			$sql = "
				SELECT
					competente.vid AS vid,
					competente.uid AS uid,
					competente.nome AS nome,
					competente.ativo AS ativo,
					competente.data_cadastro AS data_cadastro,
					cobs.void AS void,
					cobs.observacao AS observacao
				FROM competente
				LEFT JOIN cobs
					ON cobs.vid=competente.vid
				WHERE competente.vid=?
					AND competente.uid='".$this->uid."'
				ORDER BY cobs.void DESC
				LIMIT 1
			";
			$stmt = $this->conectar()->prepare($sql);
			$stmt->execute([$vid]);
			$competente = $stmt->fetch();
			return $this->resultado =
				$resultado = array(
					'vid'=>$this->vid = $competente['vid']??0,
					'uid'=>$this->uid = $competente['uid']??0,
					'nome'=>$this->nome = $competente['nome']??0,
					'ativo'=>$this->ativo = $competente['ativo']??0,
					'data'=>$this->data = $competente['data_cadastro']??0,
					'observacao'=>$this->observacao = $competente['observacao']??0,
				);
		} // Competente

		public function ListaCompetentes() {
			$sql = "
				SELECT * FROM competente
				WHERE competente.uid='".$this->uid."'
					AND competente.ativo=1
				ORDER BY vid
			";
			$stmt = $this->conectar()->prepare($sql);
			$stmt->execute();
			$competentes = $stmt->fetchAll();
			if (count($competentes)>=1) {
				foreach ($competentes as $competente) {
					$resultado[] = array(
						'vid'=>$this->vid = $competente['vid']??0,
						'uid'=>$this->uid = $competente['uid']??0,
						'nome'=>$this->nome = $competente['nome']??0,
						'ativo'=>$this->ativo = $competente['ativo']??0,
						'data'=>$this->data = $competente['data_cadastro']??0
					);
				} // foreach
			} else {
				$resultado[] = array(
					'vid'=>$this->vid = 0,
					'uid'=>$this->uid = $competente['uid']??0,
					'nome'=>$this->nome = $competente['nome']??0,
					'ativo'=>$this->ativo = $competente['ativo']??0,
					'data'=>$this->data = $competente['data_cadastro']??0
				);
			} // admins

			return $this->resultado = $resultado;
		} // ListaCompetentes

		public function Cobs($vid) {
			$sql = "
				SELECT * FROM cobs
				INNER JOIN competente
					ON competente.vid=cobs.vid
				WHERE cobs.vid=?
					AND competente.uid='".$this->uid."'
				ORDER BY cobs.void
				DESC
				LIMIT 1
			";
			$stmt = $this->conectar()->prepare($sql);
			$stmt->execute([$vid]);
			$cobs = $stmt->fetchAll();
			if (count($cobs)>=1) {
				foreach ($cobs as $cob) {
					$resultado[] = array(
						'void'=>$this->void = $cob['void']??0,
						'uid'=>$this->uid = $cob['uid']??0,
						'vid'=>$this->vid = $cob['vid']??0,
						'observacao'=>$this->observacao = $cob['observacao']??0,
						'data'=>$this->data = $cob['data_mod']??0
					);
				} // foreach
			} else {
				$resultado[] = array(
					'void'=>$this->void = 0,
					'uid'=>$this->uid = 0,
					'vid'=>$this->vid = 0,
					'observacao'=>$this->observacao = 0,
					'data'=>$this->data = 0
				);
			} // admins

			return $this->resultado = $resultado;
		} // Cobs

		public function EnderecoCliente($lid) {
			$sql = "
				SELECT * FROM endereco
				WHERE endereco.lid=?
					AND ativo=1
					AND endereco.uid='".$this->uid."'
				ORDER BY eid
				DESC
				LIMIT 1
			";
			$stmt = $this->conectar()->prepare($sql);
			$stmt->execute([$lid]);
			$endereco = $stmt->fetch();
			return $this->resultado = $resultado = array(
				'eid'=>$this->eid = $endereco['eid']??0,
				'uid'=>$this->uid = $endereco['uid']??0,
				'lid'=>$this->lid = $endereco['lid']??0,
				'cep'=>$this->cep = $endereco['cep']??0,
				'rua'=>$this->rua = $endereco['rua']??0,
				'numero'=>$this->numero = $endereco['numero']??0,
				'bairro'=>$this->bairro = $endereco['bairro']??0,
				'cidade'=>$this->cidade = $endereco['cidade']??0,
				'estado'=>$this->estado = $endereco['estado']??0,
				'complemento'=>$this->complemento = $endereco['complemento']??0,
				'ativo'=>$this->ativo = $endereco['ativo']??0,
				'data_cadastro'=>$this->data_cadastro = $endereco['data_cadastro']??0
			);
		} // EnderecoCliente

		public function EncontraCompetente($nome) {
			$sql = "
				SELECT * FROM competente
				WHERE competente.nome=?
					AND competente.ativo=1
					AND competente.uid='".$this->uid."'
				ORDER BY competente.vid
				DESC
				LIMIT 1
			";
			$stmt = $this->conectar()->prepare($sql);
			$stmt->execute([$nome]);
			$competente = $stmt->fetch();
			return $this->resultado = $resultado = array(
					'vid'=>$this->vid = $competente['vid']??0,
					'uid'=>$this->uid = $competente['uid']??0,
					'nome'=>$this->nome = $competente['nome']??0,
					'ativo'=>$this->ativo = $competente['ativo']??0,
					'data'=>$this->data = $competente['data_cadastro']??0
			);
		} // EncontraCompetente

		//////////////////////////////////////////////////
		//////////////////////////////////////////////////
		//ALUGUEL
		//////////////////////////////////////////////////
		//////////////////////////////////////////////////

		public function AdminUid($email) {
			$sql = "
				SELECT * FROM admin
				WHERE admin.email=?
				ORDER BY admin.uid
				DESC
				LIMIT 1
			";
			$stmt = $this->conectar()->prepare($sql);
			$stmt->execute([$email]);
			$admin = $stmt->fetch();
			return $this->resultado = $resultado = $admin['uid']??0;
		} // AdminUid

		public function AdminInfo($uid) {
			$sql = "
				SELECT * FROM cadastros
				INNER JOIN cadastro_confirmado
					ON cadastro_confirmado.uid=cadastros.uid
				INNER JOIN cadastro_nivel
					ON cadastro_nivel.uid=cadastros.uid
				WHERE cadastros.uid=?
				ORDER BY cadastro_nivel.nid
				DESC
				LIMIT 1
			";
			$stmt = $this->conectar()->prepare($sql);
			$stmt->execute([$uid]);
			$admin = $stmt->fetch();
			return $this->resultado = $resultado = array(
				'uid'=>$this->uid = $admin['uid']??0,
				'nivel'=>$this->nivel = $admin['nivel']??0,
				'nome'=>$this->nome = $admin['nome']??0,
				'cpf'=>$this->cpf = $admin['cpf']??0,
				'telefone'=>$this->telefone = $admin['telefone']??0,
				'email'=>$this->telefone = $admin['email']??0,
				'data_cadastro'=>$this->data_cadastro = $admin['data_cadastro']??0
			);
		} // AdminInfo

		public function AdminCategoria($nivel) {
			switch ($nivel) {
				case 0:
				$categoria='desativado';
				break;
				case 1:
				$categoria='leitura';
				break;
				case 2:
				$categoria='registro';
				break;
				case 3:
				$categoria='modificacao';
				break;
				default:
				$categoria='desativado';
			} // switch
			return $this->categoria = $categoria;
		} // AdminCategoria

		public function Ativacao($reid) {
			$sql = "
				SELECT * FROM ativacao
				WHERE ativacao.reid=?
				ORDER BY ativacao.atid
				DESC
				LIMIT 1
			";
			$stmt = $this->conectar()->prepare($sql);
			$stmt->execute([$reid]);
			$ativacao = $stmt->fetch();
			return $this->resultado = $resultado = array(
				'atid'=>$this->atid = $ativacao['atid']??0,
				'uid'=>$this->uid = $ativacao['uid']??0,
				'reid'=>$this->reid = $ativacao['reid']??0,
				'ativa'=>$this->ativa = $ativacao['ativa']??0,
				'data'=>$this->data = $ativacao['data']??0
			);
		} // Ativacao

		public function ListaCobrancas() {
			$sql = "
				SELECT
					cobranca.coid AS coid,
					cobranca.uid AS uid,
					aluguel.aid AS aid,
					aluguel.lid AS lid,
					devolucao.deid AS deid,
					devolucao.cortesias AS cortesias,
					cobranca.valor AS valor,
					transacao.tid AS tid,
					transacao.forma AS forma,
					aluguel.data AS data_aluguel,
					devolucao.data AS data_devolucao,
					cobranca.data AS data_cobranca,
					transacao.data AS data_pagamento
				FROM cobranca
				LEFT JOIN transacao
					ON transacao.coid=cobranca.coid
				INNER JOIN devolucao
					ON devolucao.deid=cobranca.deid
				INNER JOIN aluguel
					ON aluguel.aid=devolucao.aid
				WHERE cobranca.uid='".$this->uid."'
				ORDER BY cobranca.coid
				DESC
			";
			$stmt = $this->conectar()->prepare($sql);
			$stmt->execute();
			$cobrancas = $stmt->fetchAll();

			if (count($cobrancas)>=1) {
				foreach ($cobrancas as $cobranca) {
					$resultado[] = array(
						'coid'=>$this->coid = $cobranca['coid']??0,
						'aid'=>$this->aid = $cobranca['aid']??0,
						'lid'=>$this->lid = $cobranca['lid']??0,
						'deid'=>$this->deid = $cobranca['deid']??0,
						'cortesias'=>$this->cortesias = $cobranca['cortesias']??0,
						'valor'=>$this->valor = $cobranca['valor']??0,
						'tid'=>$this->tid = $cobranca['tid']??0,
						'forma'=>$this->forma = $cobranca['forma']??0,
						'data_aluguel'=>$this->data_aluguel = $cobranca['data_aluguel']??0,
						'data_devolucao'=>$this->data_devolucao = $cobranca['data_devolucao']??0,
						'data_cobranca'=>$this->data_cobranca = $cobranca['data_cobranca']??0,
						'data_pagamento'=>$this->data_pagamento = $cobranca['data_pagamento']??0
					);
				} // foreach
			} else {
				$resultado[] = array(
					'coid'=>$this->coid = 0,
					'aid'=>$this->aid = 0,
					'lid'=>$this->lid = 0,
					'deid'=>$this->deid = 0,
					'cortesias'=>$this->cortesias = 0,
					'valor'=>$this->valor = 0,
					'tid'=>$this->tid = 0,
					'forma'=>$this->forma = 0,
					'data_aluguel'=>$this->data_aluguel = 0,
					'data_devolucao'=>$this->data_devolucao = 0,
					'data_cobranca'=>$this->data_cobranca = 0,
					'data_pagamento'=>$this->data_pagamento = 0
				);
			} // admins

			return $this->resultado = $resultado;
		} // ListaCobrancas

		public function ListaCobrancasEpoca($inicio_relatorio,$conclusao_relatorio) {
			$sql = "
				SELECT
					cobranca.uid AS uid,
					cobranca.coid AS coid,
					cobranca.data AS data_cobranca,
					cobranca.valor AS valor,
					devolucao.deid AS deid,
					devolucao.cortesias AS cortesias,
					devolucao.data AS data_devolucao,
					aluguel.aid AS aid
				FROM cobranca
				INNER JOIN devolucao
					ON devolucao.deid=cobranca.deid
				INNER JOIN aluguel
					ON aluguel.aid=devolucao.aid
				WHERE devolucao.data >= ?
					AND devolucao.data < ?
					AND devolucao.cortesias > 0
					AND cobranca.uid='".$this->uid."'
				ORDER BY devolucao.data
				DESC
			";
			$stmt = $this->conectar()->prepare($sql);
			$stmt->execute([$inicio_relatorio,$conclusao_relatorio]);
			$cobrancas = $stmt->fetchAll();

			if (count($cobrancas)>=1) {
				foreach ($cobrancas as $cobranca) {
					$resultado[] = array(
						'coid'=>$this->coid = $cobranca['coid']??0,
						'aid'=>$this->aid = $cobranca['aid']??0,
						'deid'=>$this->deid = $cobranca['deid']??0,
						'cortesias'=>$this->cortesias = $cobranca['cortesias']??0,
						'data_devolucao'=>$this->data_devolucao = $cobranca['data_devolucao']??0,
						'data_cobranca'=>$this->data_cobranca = $cobranca['data_cobranca']??0
					);
				} // foreach
			} else {
				$resultado[] = array(
					'coid'=>$this->coid = 0,
					'aid'=>$this->aid = 0,
					'deid'=>$this->deid = 0,
					'cortesias'=>$this->cortesias = 0,
					'data_devolucao'=>$this->data_devolucao = 0,
					'data_cobranca'=>$this->data_cobranca = 0
				);
			} // admins

			return $this->resultado = $resultado;
		} // ListaCobrancasEpoca

		public function Cobrancascliente($lid) {
			$sql = "
				SELECT
					cobranca.coid AS coid,
					aluguel.aid AS aid,
					aluguel.lid AS lid,
					devolucao.deid AS deid,
					devolucao.cortesias AS cortesias,
					cobranca.valor AS valor,
					transacao.tid AS tid,
					transacao.forma AS forma,
					aluguel.data AS data_aluguel,
					devolucao.data AS data_devolucao,
					cobranca.data AS data_cobranca,
					transacao.data AS data_pagamento
				FROM cobranca
				LEFT JOIN transacao
					ON transacao.coid=cobranca.coid
				INNER JOIN devolucao
					ON devolucao.deid=cobranca.deid
				INNER JOIN aluguel
					ON aluguel.aid=devolucao.aid
				WHERE aluguel.lid=?
				ORDER BY cobranca.coid
				DESC
			";
			$stmt = $this->conectar()->prepare($sql);
			$stmt->execute([$lid]);
			$cobrancas = $stmt->fetchAll();

			if (count($cobrancas)>=1) {
				foreach ($cobrancas as $cobranca) {
					$resultado[] = array(
						'coid'=>$this->coid = $cobranca['coid']??0,
						'aid'=>$this->aid = $cobranca['aid']??0,
						'lid'=>$this->lid = $cobranca['lid']??0,
						'deid'=>$this->deid = $cobranca['deid']??0,
						'cortesias'=>$this->cortesias = $cobranca['cortesias']??0,
						'valor'=>$this->valor = $cobranca['valor']??0,
						'tid'=>$this->tid = $cobranca['tid']??0,
						'forma'=>$this->forma = $cobranca['forma']??0,
						'data_aluguel'=>$this->data_aluguel = $cobranca['data_aluguel']??0,
						'data_devolucao'=>$this->data_devolucao = $cobranca['data_devolucao']??0,
						'data_cobranca'=>$this->data_cobranca = $cobranca['data_cobranca']??0,
						'data_pagamento'=>$this->data_pagamento = $cobranca['data_pagamento']??0
					);
				} // foreach
			} else {
				$resultado[] = array(
					'coid'=>$this->coid = 0,
					'aid'=>$this->aid = 0,
					'lid'=>$this->lid = 0,
					'deid'=>$this->deid = 0,
					'cortesias'=>$this->cortesias = 0,
					'valor'=>$this->valor = 0,
					'tid'=>$this->tid = 0,
					'forma'=>$this->forma = 0,
					'data_aluguel'=>$this->data_aluguel = 0,
					'data_devolucao'=>$this->data_devolucao = 0,
					'data_cobranca'=>$this->data_cobranca = 0,
					'data_pagamento'=>$this->data_pagamento = 0
				);
			} // admins

			return $this->resultado = $resultado;
		} // Cobrancascliente

		public function CobrancaAluguel($aid) {
			$sql = "
				SELECT
					cobranca.coid AS coid,
					cobranca.valor AS valor,
					cobranca.data AS data_cobranca,
					aluguel.aid AS aid,
					aluguel.lid AS lid,
					aluguel.data AS data_aluguel,
					devolucao.deid AS deid,
					devolucao.cortesias AS cortesias,
					devolucao.data AS data_devolucao,
					transacao.tid AS tid,
					transacao.valor AS valor_transacao,
					transacao.forma AS forma,
					transacao.desconto AS desconto,
					transacao.data AS data_pagamento,
					transacao.uid AS recebedor
				FROM cobranca
				LEFT JOIN transacao
					ON transacao.coid=cobranca.coid
				INNER JOIN devolucao
					ON devolucao.deid=cobranca.deid
				INNER JOIN aluguel
					ON aluguel.aid=devolucao.aid
				WHERE aluguel.aid=?
				ORDER BY cobranca.coid
				DESC
				LIMIT 1
			";
			$stmt = $this->conectar()->prepare($sql);
			$stmt->execute([$aid]);
			$cobranca = $stmt->fetch();

			return $this->resultado = $resultado = array(
				'coid'=>$this->coid = $cobranca['coid']??0,
				'aid'=>$this->aid = $cobranca['aid']??0,
				'lid'=>$this->lid = $cobranca['lid']??0,
				'deid'=>$this->deid = $cobranca['deid']??0,
				'cortesias'=>$this->cortesias = $cobranca['cortesias']??0,
				'valor'=>$this->valor = $cobranca['valor']??0,
				'tid'=>$this->tid = $cobranca['tid']??0,
				'forma'=>$this->forma = $cobranca['forma']??0,
				'valor_transacao'=>$this->valor_transacao = $cobranca['valor_transacao']??0,
				'desconto'=>$this->desconto = $cobranca['desconto']??0,
				'data_aluguel'=>$this->data_aluguel = $cobranca['data_aluguel']??0,
				'data_devolucao'=>$this->data_devolucao = $cobranca['data_devolucao']??0,
				'data_cobranca'=>$this->data_cobranca = $cobranca['data_cobranca']??0,
				'data_pagamento'=>$this->data_pagamento = $cobranca['data_pagamento']??0,
				'recebedor'=>$this->recebedor = $cobranca['recebedor']??0
			);
		} // CobrancaAluguel

		public function Cobranca($coid) {
			$sql = "
				SELECT
					cobranca.coid AS coid,
					aluguel.aid AS aid,
					aluguel.lid AS lid,
					devolucao.deid AS deid,
					devolucao.cortesias AS cortesias,
					cobranca.valor AS valor,
					transacao.tid AS tid,
					transacao.valor AS transacao_valor,
					transacao.forma AS forma,
					aluguel.data AS data_aluguel,
					devolucao.data AS data_devolucao,
					cobranca.data AS data_cobranca,
					transacao.data AS data_pagamento,
					transacao.uid AS recebedor,
					transacao.desconto AS desconto
				FROM cobranca
				LEFT JOIN transacao
					ON transacao.coid=cobranca.coid
				INNER JOIN devolucao
					ON devolucao.deid=cobranca.deid
				INNER JOIN aluguel
					ON aluguel.aid=devolucao.aid
				WHERE cobranca.coid=?
				ORDER BY cobranca.coid
				DESC
				LIMIT 1
			";
			$stmt = $this->conectar()->prepare($sql);
			$stmt->execute([$coid]);
			$cobranca = $stmt->fetch();

			return $this->resultado = $resultado = array(
				'coid'=>$this->coid = $cobranca['coid']??0,
				'aid'=>$this->aid = $cobranca['aid']??0,
				'lid'=>$this->lid = $cobranca['lid']??0,
				'deid'=>$this->deid = $cobranca['deid']??0,
				'cortesias'=>$this->cortesias = $cobranca['cortesias']??0,
				'valor'=>$this->valor = $cobranca['valor']??0,
				'tid'=>$this->tid = $cobranca['tid']??0,
				'forma'=>$this->forma = $cobranca['forma']??0,
				'transacao_valor'=>$this->transacao_valor = $cobranca['transacao_valor']??0,
				'desconto'=>$this->desconto = $cobranca['desconto']??0,
				'data_aluguel'=>$this->data_aluguel = $cobranca['data_aluguel']??0,
				'data_devolucao'=>$this->data_devolucao = $cobranca['data_devolucao']??0,
				'data_cobranca'=>$this->data_cobranca = $cobranca['data_cobranca']??0,
				'data_pagamento'=>$this->data_pagamento = $cobranca['data_pagamento']??0,
				'recebedor'=>$this->recebedor = $cobranca['recebedor']??0
			);
		} // Cobranca

		public function CobrancaParcial($coid) {
			$sql = "
				SELECT
					cobranca_parcial.copid AS copid,
					cobranca.coid AS coid,
					aluguel.aid AS aid,
					aluguel.lid AS lid,
					devolucao.deid AS deid,
					devolucao.cortesias AS cortesias,
					cobranca_parcial.valor AS valor,
					cobranca_parcial.copid AS copid,
					cobranca_parcial.forma AS forma,
					aluguel.data AS data_aluguel,
					devolucao.data AS data_devolucao,
					cobranca.data AS data_cobranca,
					cobranca_parcial.data AS data_pagamento,
					cobranca_parcial.uid AS recebedor
				FROM cobranca_parcial
				INNER JOIN cobranca
					ON cobranca_parcial.coid=cobranca.coid
				INNER JOIN devolucao
					ON devolucao.deid=cobranca.deid
				INNER JOIN aluguel
					ON aluguel.aid=devolucao.aid
				WHERE cobranca_parcial.coid=?
				ORDER BY cobranca_parcial.coid
				DESC
			";
			$stmt = $this->conectar()->prepare($sql);
			$stmt->execute([$coid]);
			$cobrancas = $stmt->fetchAll();

			if (count($cobrancas)>=1) {
				foreach ($cobrancas as $cobranca) {
					$resultado[] = array(
						'copid'=>$this->copid = $cobranca['copid']??0,
						'coid'=>$this->coid = $cobranca['coid']??0,
						'aid'=>$this->aid = $cobranca['aid']??0,
						'lid'=>$this->lid = $cobranca['lid']??0,
						'deid'=>$this->deid = $cobranca['deid']??0,
						'cortesias'=>$this->cortesias = $cobranca['cortesias']??0,
						'valor'=>$this->valor = $cobranca['valor']??0,
						'copid'=>$this->copid = $cobranca['copid']??0,
						'forma'=>$this->forma = $cobranca['forma']??0,
						'data_aluguel'=>$this->data_aluguel = $cobranca['data_aluguel']??0,
						'data_devolucao'=>$this->data_devolucao = $cobranca['data_devolucao']??0,
						'data_cobranca'=>$this->data_cobranca = $cobranca['data_cobranca']??0,
						'data_pagamento'=>$this->data_pagamento = $cobranca['data_pagamento']??0,
						'recebedor'=>$this->recebedor = $cobranca['recebedor']??0
					);
				} // foreach
			} else {
				$resultado[] = array(
					'copid'=>$this->copid = 0,
					'coid'=>$this->coid = 0,
					'aid'=>$this->aid = 0,
					'lid'=>$this->lid = 0,
					'deid'=>$this->deid = 0,
					'cortesias'=>$this->cortesias = 0,
					'valor'=>$this->valor = 0,
					'copid'=>$this->copid = 0,
					'forma'=>$this->forma = 0,
					'data_aluguel'=>$this->data_aluguel = 0,
					'data_devolucao'=>$this->data_devolucao = 0,
					'data_cobranca'=>$this->data_cobranca = 0,
					'data_pagamento'=>$this->data_pagamento = 0,
					'recebedor'=>$this->recebedor = 0
				);
			} // count

			return $this->resultado = $resultado;
		} // CobrancaParcial

		public function Devolucao($aid) {
			$sql = "
				SELECT * FROM devolucao
				WHERE aid=?
				ORDER BY deid
				DESC
				LIMIT 1
			";
			$stmt = $this->conectar()->prepare($sql);
			$stmt->execute([$aid]);
			$devolucao = $stmt->fetch();
			return $this->resultado = $resultado = array(
				'deid'=>$this->deid = $devolucao['deid']??0,
				'uid'=>$this->uid = $devolucao['uid']??0,
				'aid'=>$this->aid = $devolucao['aid']??0,
				'limpeza'=>$this->limpeza = $devolucao['limpeza']??0,
				'cortesias'=>$this->cortesias = $devolucao['cortesias']??0,
				'km'=>$this->km = $devolucao['kilometragem']??0,
				'data'=>$this->data = $devolucao['data']??0
			);
		} // Devolucao

		public function DevolucaoId($deid) {
			$sql = "
				SELECT * FROM devolucao
				WHERE deid=?
				ORDER BY deid
				DESC
				LIMIT 1
			";
			$stmt = $this->conectar()->prepare($sql);
			$stmt->execute([$deid]);
			$devolucao = $stmt->fetch();
			return $this->resultado = $resultado = array(
				'deid'=>$this->deid = $devolucao['deid']??0,
				'uid'=>$this->uid = $devolucao['uid']??0,
				'aid'=>$this->aid = $devolucao['aid']??0,
				'limpeza'=>$this->limpeza = $devolucao['limpeza']??0,
				'cortesias'=>$this->cortesias = $devolucao['cortesias']??0,
				'km'=>$this->km = $devolucao['kilometragem']??0,
				'data'=>$this->data = $devolucao['data']??0
			);
		} // DevolucaoId

		public function Aluguel($vid) {
			$sql = "
				SELECT * FROM aluguel
				WHERE aluguel.vid=?
					AND NOT EXISTS (
						SELECT *
						FROM devolucao
						WHERE aluguel.aid=devolucao.aid
					)
				ORDER BY aluguel.aid
				LIMIT 1
			";
			$stmt = $this->conectar()->prepare($sql);
			$stmt->execute([$vid]);
			$aluguel = $stmt->fetch();
			return $this->resultado = $resultado = array(
				'aid'=>$this->aid = $aluguel['aid']??0,
				'uid'=>$this->uid = $aluguel['uid']??0,
				'lid'=>$this->lid = $aluguel['lid']??0,
				'vid'=>$this->vid = $aluguel['vid']??0,
				'diaria'=>$this->diaria = $aluguel['diaria']??0,
				'kilometragem'=>$this->kilometragem = $aluguel['km']??0,
				'inicio'=>$this->inicio = $aluguel['inicio']??0,
				'devolucao'=>$this->devolucao = $aluguel['devolucao']??0,
				'data'=>$this->data = $aluguel['data']??0
			);
		} // Aluguel

		public function AluguelAdicionado($vid) {
			$sql = "
				SELECT * FROM aluguel
				WHERE aluguel.vid=?
				ORDER BY aluguel.aid
				DESC
				LIMIT 1
			";
			$stmt = $this->conectar()->prepare($sql);
			$stmt->execute([$vid]);
			$aluguel = $stmt->fetch();
			return $this->resultado = $resultado = array(
				'aid'=>$this->aid = $aluguel['aid']??0,
				'uid'=>$this->uid = $aluguel['uid']??0,
				'lid'=>$this->lid = $aluguel['lid']??0,
				'vid'=>$this->vid = $aluguel['vid']??0,
				'diaria'=>$this->diaria = $aluguel['diaria']??0,
				'kilometragem'=>$this->kilometragem = $aluguel['km']??0,
				'inicio'=>$this->inicio = $aluguel['inicio']??0,
				'devolucao'=>$this->devolucao = $aluguel['devolucao']??0,
				'data'=>$this->data = $aluguel['data']??0
			);
		} // AluguelAdicionado

		public function PagamentosParciais($aid) {
			$sql = "
				SELECT * FROM pagamento_parcial
				WHERE aid=?
				ORDER BY papid
			";
			$stmt = $this->conectar()->prepare($sql);
			$stmt->execute([$aid]);
			$pagamentos = $stmt->fetchAll();
			if (count($pagamentos)>=1) {
				foreach ($pagamentos as $pagamento) {
					$resultado[] = array(
						'papid'=>$this->papid = $pagamento['papid']??0,
						'uid'=>$this->uid = $pagamento['uid']??0,
						'aid'=>$this->aid = $pagamento['aid']??0,
						'valor'=>$this->valor = $pagamento['valor']??0,
						'forma'=>$this->forma = $pagamento['forma']??0,
						'data'=>$this->data = $pagamento['data']??0
					);
				} // foreach
			} else {
				$resultado[] = array(
					'papid'=>$this->papid = 0,
					'uid'=>$this->uid = 0,
					'aid'=>$this->aid = 0,
					'valor'=>$this->valor = 0,
					'forma'=>$this->forma = 0,
					'data'=>$this->data = 0
				);
			} // > 0
			return $this->resultado = $resultado;
		} // PagamentosParciais

		public function AluguelDetalhes($vid) {
			$sql = "
				SELECT * FROM aluguel
				LEFT JOIN reserva
					ON aluguel.aid=reserva.aid
				LEFT JOIN ativacao
					ON reserva.reid=ativacao.reid
					AND ativacao.ativa='S'
				WHERE aluguel.vid=?
					AND ativacao.ativa='S'
					AND reserva.inicio<CURDATE()
					AND NOT EXISTS (
						SELECT *
						FROM devolucao
						WHERE aluguel.aid=devolucao.aid
					)
				ORDER BY aluguel.aid ASC,
					reserva.reid DESC,
					ativacao.atid DESC
				LIMIT 1
			";
			$stmt = $this->conectar()->prepare($sql);
			$stmt->execute([$vid]);
			$aluguel = $stmt->fetch();
			return $this->resultado = $resultado = array(
				'aid'=>$this->aid = $aluguel['aid']??0,
				'uid'=>$this->uid = $aluguel['uid']??0,
				'lid'=>$this->lid = $aluguel['lid']??0,
				'vid'=>$this->vid = $aluguel['vid']??0,
				'reid'=>$this->reid = $aluguel['reid']??0,
				'atid'=>$this->atid = $aluguel['atid']??0,
				'diaria'=>$this->diaria = $aluguel['diaria']??0,
				'kilometragem'=>$this->kilometragem = $aluguel['km']??0,
				'inicio'=>$this->inicio = $aluguel['inicio']??0,
				'devolucao'=>$this->devolucao = $aluguel['devolucao']??0,
				'data'=>$this->data = $aluguel['data']??0
			);
		} // AluguelDetalhes

		public function AluguelInfo($aid) {
			$sql = "
				SELECT
					aluguel.aid AS aid,
					aluguel.uid AS uid,
					aluguel.lid AS lid,
					aluguel.vid AS vid,
					aluguel.diaria AS diaria,
					aluguel.km AS km,
					aluguel.inicio AS inicio,
					aluguel.devolucao AS devolucao,
					aluguel.data AS data,
					aluguel_guid.guid AS guid,
					pagamento_inicial.valor AS valor,
					pagamento_inicial.forma AS forma,
					aluguel_caucao.valor AS valor_caucao,
					aluguel_caucao.forma AS forma_caucao
				FROM aluguel
				LEFT JOIN aluguel_guid
					ON aluguel_guid.aid=aluguel.aid
				LEFT JOIN pagamento_inicial
					ON pagamento_inicial.aid=aluguel.aid
				LEFT JOIN aluguel_caucao
					ON aluguel_caucao.aid=aluguel.aid
				WHERE aluguel.aid=?
				ORDER BY aluguel.aid
				DESC
				LIMIT 1
			";
			$stmt = $this->conectar()->prepare($sql);
			$stmt->execute([$aid]);
			$aluguel = $stmt->fetch();
			return $this->resultado = $resultado = array(
				'aid'=>$this->aid = $aluguel['aid']??0,
				'guid'=>$this->guid = $aluguel['guid']??0,
				'uid'=>$this->uid = $aluguel['uid']??0,
				'lid'=>$this->lid = $aluguel['lid']??0,
				'vid'=>$this->vid = $aluguel['vid']??0,
				'diaria'=>$this->diaria = $aluguel['diaria']??0,
				'kilometragem'=>$this->kilometragem = $aluguel['km']??0,
				'inicio'=>$this->inicio = $aluguel['inicio']??0,
				'devolucao'=>$this->devolucao = $aluguel['devolucao']??0,
				'valor'=>$this->valor = $aluguel['valor']??0,
				'forma'=>$this->forma = $aluguel['forma']??0,
				'valor_caucao'=>$this->valor_caucao = $aluguel['valor_caucao']??0,
				'forma_caucao'=>$this->forma_caucao = $aluguel['forma_caucao']??0,
				'data'=>$this->data = $aluguel['data']??0
			);
		} // AluguelInfo

		public function AluguelGUIDInfo($guid,$uid) {
			$sql = "
				SELECT
					aluguel.aid AS aid,
					aluguel.uid AS uid,
					aluguel.lid AS lid,
					aluguel.vid AS vid,
					aluguel.diaria AS diaria,
					aluguel.km AS km,
					aluguel.inicio AS inicio,
					aluguel.devolucao AS devolucao,
					aluguel.data AS data,
					aluguel_guid.guid AS guid,
					pagamento_inicial.valor AS valor,
					pagamento_inicial.forma AS forma,
					aluguel_caucao.valor AS valor_caucao,
					aluguel_caucao.forma AS forma_caucao
				FROM aluguel
				LEFT JOIN aluguel_guid
					ON aluguel_guid.aid=aluguel.aid
				LEFT JOIN pagamento_inicial
					ON pagamento_inicial.aid=aluguel.aid
				LEFT JOIN aluguel_caucao
					ON aluguel_caucao.aid=aluguel.aid
				WHERE aluguel_guid.guid=?
					AND aluguel.uid=?
				ORDER BY aluguel.aid
				DESC
				LIMIT 1
			";
			$stmt = $this->conectar()->prepare($sql);
			$stmt->execute([$guid,$uid]);
			$aluguel = $stmt->fetch();
			return $this->resultado = $resultado = array(
				'aid'=>$this->aid = $aluguel['aid']??0,
				'guid'=>$this->guid = $aluguel['guid']??0,
				'uid'=>$this->uid = $aluguel['uid']??0,
				'lid'=>$this->lid = $aluguel['lid']??0,
				'vid'=>$this->vid = $aluguel['vid']??0,
				'diaria'=>$this->diaria = $aluguel['diaria']??0,
				'kilometragem'=>$this->kilometragem = $aluguel['km']??0,
				'inicio'=>$this->inicio = $aluguel['inicio']??0,
				'devolucao'=>$this->devolucao = $aluguel['devolucao']??0,
				'valor'=>$this->valor = $aluguel['valor']??0,
				'forma'=>$this->forma = $aluguel['forma']??0,
				'valor_caucao'=>$this->valor_caucao = $aluguel['valor_caucao']??0,
				'forma_caucao'=>$this->forma_caucao = $aluguel['forma_caucao']??0,
				'data'=>$this->data = $aluguel['data']??0
			);
		} // AluguelGUIDInfo

		public function AlugueisAtivos() {
			$sql = "
				SELECT * FROM aluguel
				WHERE uid='".$this->uid."'
				ORDER BY aid
				DESC
			";
			$stmt = $this->conectar()->prepare($sql);
			$stmt->execute();
			$alugueis = $stmt->fetchAll();

			if (count($alugueis)>=1) {
				foreach ($alugueis as $aluguel) {
					$resultado[] = array(
						'aid'=>$this->aid = $aluguel['aid'],
						'uid'=>$this->uid = $aluguel['uid'],
						'lid'=>$this->lid = $aluguel['lid'],
						'vid'=>$this->vid = $aluguel['vid'],
						'diaria'=>$this->diaria = $aluguel['diaria'],
						'kilometragem'=>$this->kilometragem = $aluguel['km'],
						'inicio'=>$this->inicio = $aluguel['inicio'],
						'devolucao'=>$this->devolucao = $aluguel['devolucao'],
						'data'=>$this->data = $aluguel['data']
					);
				} // foreach
			} else {
				$resultado[] = array(
					'aid'=>$this->aid = 0,
					'uid'=>$this->uid = 0,
					'lid'=>$this->lid = 0,
					'vid'=>$this->vid = 0,
					'diaria'=>$this->diaria = 0,
					'kilometragem'=>$this->kilometragem = 0,
					'inicio'=>$this->inicio = 0,
					'devolucao'=>$this->devolucao = 0,
					'data'=>$this->data = 0
				);
			} // admins

			return $this->resultado = $resultado;
		} // AlugueisAtivos

		public function ListaAlugueis($ordem='DESC') {
			$sql = "
				SELECT * FROM aluguel
				WHERE aluguel.uid='".$this->uid."'
				ORDER BY aid
				$ordem
			";
			$stmt = $this->conectar()->prepare($sql);
			$stmt->execute([]);
			$alugueis = $stmt->fetchAll();

			if (count($alugueis)>=1) {
				foreach ($alugueis as $aluguel) {
					$resultado[] = array(
						'aid'=>$this->aid = $aluguel['aid'],
						'uid'=>$this->uid = $aluguel['uid'],
						'lid'=>$this->lid = $aluguel['lid'],
						'vid'=>$this->vid = $aluguel['vid'],
						'diaria'=>$this->diaria = $aluguel['diaria'],
						'kilometragem'=>$this->kilometragem = $aluguel['km'],
						'inicio'=>$this->inicio = $aluguel['inicio'],
						'devolucao'=>$this->devolucao = $aluguel['devolucao'],
						'data'=>$this->data = $aluguel['data']
					);
				} // foreach
			} else {
				$resultado[] = array(
					'aid'=>$this->aid = 0,
					'uid'=>$this->uid = 0,
					'lid'=>$this->lid = 0,
					'vid'=>$this->vid = 0,
					'diaria'=>$this->diaria = 0,
					'kilometragem'=>$this->kilometragem = 0,
					'inicio'=>$this->inicio = 0,
					'devolucao'=>$this->devolucao = 0,
					'data'=>$this->data = 0
				);
			} // admins

			return $this->resultado = $resultado;
		} // ListaAlugueis

		public function ListaDevolucoes() {
			$sql = "
				SELECT * FROM devolucao
				WHERE uid='".$this->uid."'
				ORDER BY deid

			";
			$stmt = $this->conectar()->prepare($sql);
			$stmt->execute([]);
			$devolucoes = $stmt->fetchAll();

			if (count($devolucoes)>=1) {
				foreach ($devolucoes as $devolucao) {
					$resultado[] = array(
						'deid'=>$this->deid = $devolucao['deid'],
						'uid'=>$this->uid = $devolucao['uid'],
						'aid'=>$this->aid = $devolucao['aid'],
						'limpeza'=>$this->limpeza = $devolucao['limpeza'],
						'cortesias'=>$this->cortesias = $devolucao['cortesias'],
						'kilometragem'=>$this->kilometragem = $devolucao['kilometragem'],
						'data'=>$this->data = $devolucao['data']
					);
				} // foreach
			} else {
				$resultado[] = array(
					'deid'=>$this->deid = 0,
					'uid'=>$this->uid = 0,
					'aid'=>$this->aid = 0,
					'limpeza'=>$this->limpeza = 0,
					'cortesias'=>$this->cortesias = 0,
					'kilometragem'=>$this->kilometragem = 0,
					'data'=>$this->data = 0
				);
			} // admins

			return $this->resultado = $resultado;
		} // ListaDevolucoes

		public function ListaAlugueiscliente($lid) {
			$sql = "
				SELECT
					aluguel.aid AS aid,
					aluguel.uid AS uid,
					aluguel.lid AS lid,
					aluguel.vid AS vid,
					aluguel.diaria AS diaria,
					aluguel.km AS km,
					aluguel.inicio AS inicio,
					aluguel.devolucao AS devolucao,
					aluguel.data AS data,
					pagamento_inicial.valor AS valor,
					pagamento_inicial.forma AS forma,
					aluguel_caucao.valor AS valor_caucao,
					aluguel_caucao.forma AS forma_caucao,
					reserva.reid AS reid,
					reserva.confirmada AS reserva_confirmada,
					reserva.inicio AS reserva_inicio,
					reserva.devolucao AS reserva_devolucao,
					ativacao.atid AS atid,
					ativacao.ativa AS ativa,
					devolucao.deid AS deid,
					devolucao.limpeza AS limpeza_devolucao,
					devolucao.cortesias AS cortesias_devolucao,
					devolucao.kilometragem AS devolucao_kilometragem,
					devolucao.data AS devolucao_data
				FROM aluguel
				LEFT JOIN pagamento_inicial
					ON pagamento_inicial.aid=aluguel.aid
				LEFT JOIN aluguel_caucao
					ON aluguel_caucao.aid=aluguel.aid
				LEFT JOIN reserva
					ON reserva.aid=aluguel.aid
				LEFT JOIN ativacao
					ON ativacao.reid=reserva.reid
				LEFT JOIN devolucao
					ON devolucao.aid=aluguel.aid
				WHERE aluguel.lid=?
				GROUP BY aluguel.aid
				ORDER BY aluguel.aid DESC,
					reserva.reid DESC,
					ativacao.ativa DESC,
					devolucao.deid DESC

			";
			$stmt = $this->conectar()->prepare($sql);
			$stmt->execute([$lid]);
			$alugueis = $stmt->fetchAll();

			if (count($alugueis)>=1) {
				foreach ($alugueis as $aluguel) {
					$resultado[] = array(
						'aid'=>$this->aid = $aluguel['aid']??0,
						'uid'=>$this->uid = $aluguel['uid']??0,
						'lid'=>$this->lid = $aluguel['lid']??0,
						'vid'=>$this->vid = $aluguel['vid']??0,
						'diaria'=>$this->diaria = $aluguel['diaria']??0,
						'kilometragem'=>$this->kilometragem = $aluguel['km']??0,
						'inicio'=>$this->inicio = $aluguel['inicio']??0,
						'devolucao'=>$this->devolucao = $aluguel['devolucao']??0,
						'data'=>$this->data = $aluguel['data']??0,
						'valor'=>$this->valor = $aluguel['valor']??0,
						'forma'=>$this->forma = $aluguel['forma']??0,
						'valor_caucao'=>$this->valor_caucao = $aluguel['valor_caucao']??0,
						'forma_caucao'=>$this->forma_caucao = $aluguel['forma_caucao']??0,
						'reserva_confirmada'=>$this->reserva_confirmada = $aluguel['reserva_confirmada']??0,
						'reserva_inicio'=>$this->reserva_inicio = $aluguel['reserva_inicio']??0,
						'reserva_devolucao'=>$this->reserva_devolucao = $aluguel['reserva_devolucao']??0,
						'ativa'=>$this->ativa = $aluguel['ativa']??0,
						'limpeza_devolucao'=>$this->limpeza_devolucao = $aluguel['limpeza_devolucao']??0,
						'cortesias_devolucao'=>$this->cortesias_devolucao = $aluguel['cortesias_devolucao']??0,
						'devolucao_kilometragem'=>$this->devolucao_kilometragem = $aluguel['devolucao_kilometragem']??0,
						'devolucao_data'=>$this->devolucao_data = $aluguel['devolucao_data']??0
					);
				} // foreach
			} else {
				$resultado[] = array(
					'aid'=>$this->aid = 0,
					'uid'=>$this->uid = 0,
					'lid'=>$this->lid = 0,
					'vid'=>$this->vid = 0,
					'diaria'=>$this->diaria = 0,
					'kilometragem'=>$this->kilometragem = 0,
					'inicio'=>$this->inicio = 0,
					'devolucao'=>$this->devolucao = 0,
					'data'=>$this->data = 0,
					'valor'=>$this->valor = 0,
					'forma'=>$this->forma = 0,
					'valor_caucao'=>$this->valor_caucao = 0,
					'forma_caucao'=>$this->forma_caucao = 0,
					'reserva_confirmada'=>$this->reserva_confirmada = 0,
					'reserva_inicio'=>$this->reserva_inicio = 0,
					'reserva_devolucao'=>$this->reserva_devolucao = 0,
					'ativa'=>$this->ativa = 0,
					'limpeza_devolucao'=>$this->limpeza_devolucao = 0,
					'cortesias_devolucao'=>$this->cortesias_devolucao = 0,
					'devolucao_kilometragem'=>$this->devolucao_kilometragem = 0,
					'devolucao_data'=>$this->devolucao_data = 0
				);
			} // admins

			return $this->resultado = $resultado;
		} // ListaAlugueiscliente

		public function ListaAlugueiscompetente($vid) {
			$sql = "
				SELECT * FROM aluguel
				WHERE vid=?
				ORDER BY aid
			";
			$stmt = $this->conectar()->prepare($sql);
			$stmt->execute([$vid]);
			$alugueis = $stmt->fetchAll();

			if (count($alugueis)>=1) {
				foreach ($alugueis as $aluguel) {
					$resultado[] = array(
						'aid'=>$this->aid = $aluguel['aid'],
						'uid'=>$this->uid = $aluguel['uid'],
						'lid'=>$this->lid = $aluguel['lid'],
						'vid'=>$this->vid = $aluguel['vid'],
						'diaria'=>$this->diaria = $aluguel['diaria'],
						'kilometragem'=>$this->kilometragem = $aluguel['km'],
						'inicio'=>$this->inicio = $aluguel['inicio'],
						'devolucao'=>$this->devolucao = $aluguel['devolucao'],
						'data'=>$this->data = $aluguel['data']
					);
				} // foreach
			} else {
				$resultado[] = array(
					'aid'=>$this->aid = 0,
					'uid'=>$this->uid = 0,
					'lid'=>$this->lid = 0,
					'vid'=>$this->vid = 0,
					'diaria'=>$this->diaria = 0,
					'kilometragem'=>$this->kilometragem = 0,
					'inicio'=>$this->inicio = 0,
					'devolucao'=>$this->devolucao = 0,
					'data'=>$this->data = 0
				);
			} // admins

			return $this->resultado = $resultado;
		} // ListaAlugueiscompetente

		public function MomentoLimpeza($vid,$data) {
			$sql = "
				SELECT * FROM limpeza
				WHERE limpeza.vid=?
					AND limpeza.data<=?
				ORDER BY limpeza.limid
				DESC
				LIMIT 1
			";
			$stmt = $this->conectar()->prepare($sql);
			$stmt->execute([$vid,$data]);
			$limpeza = $stmt->fetch();
			return $this->resultado = $resultado = array(
				'limid'=>$this->limid = $limpeza['limid']??0,
				'uid'=>$this->uid = $limpeza['uid']??0,
				'vid'=>$this->vid = $limpeza['vid']??0,
				'status'=>$this->status = $limpeza['status']??0,
				'data'=>$this->data = $limpeza['data']??0
			);
		} // MomentoLimpeza

		public function competenteCategoria($categoria) {
			switch ($categoria) {
				case 1:
					$categoria='Carro';
					break;
				case 2:
					$categoria='Utilitário';
					break;
				case 3:
					$categoria='Moto';
					break;
				default:
					$categoria=0;
			} // switch
			return $this->categoria = $categoria;
		} // competenteCategoria

		public function documento($rg) {
			$sql = "
				SELECT * FROM documento
				WHERE numero=?
				ORDER BY documento.hid
				DESC
				LIMIT 1
			";
			$stmt = $this->conectar()->prepare($sql);
			$stmt->execute([$rg]);
			$documento = $stmt->fetch();
			return $this->resultado = $resultado = array(
					'hid'=>$this->hid = $documento['hid']??0,
					'uid'=>$this->uid = $documento['uid']??0,
					'lid'=>$this->lid = $documento['lid']??0,
					'o_exp'=>$this->o_exp = $documento['o_exp']??0,
					'numero'=>$this->numero = $documento['numero']??0,
					'data'=>$this->data = $documento['data_cadastro']??0
				);
		} // documento

		public function competenteMotivo($motivo) {
			switch ($motivo) {
				case 1:
				$disponibilidade='Oficina';
				break;
				case 2:
				$disponibilidade='Lavando';
				break;
				case 3:
				$disponibilidade='Inativo';
				break;
				case 4:
				$disponibilidade='Removido';
				break;
				case 5:
				$disponibilidade='Revisão';
				break;
				case 6:
				$disponibilidade='Pintura';
				break;
				default:
				$disponibilidade='';
			} // switch
			return $this->disponibilidade = $disponibilidade;
		} // competenteDisponibilidade

		public function competenteDisponibilidade($motivo) {
			switch ($motivo) {
				case 1:
				$disponibilidade='Disponível';
				break;
				case 2:
				$disponibilidade='Lavando';
				break;
				case 3:
				$disponibilidade='Em manutenção';
				break;
				case 4:
				$disponibilidade='Inativo';
				break;
				case 5:
				$disponibilidade='Removido';
				break;
				case 6:
				$disponibilidade='Alugado';
				break;
				case 7:
				$disponibilidade='Em revisão';
				break;
				default:
				$disponibilidade='';
			} // switch
			return $this->disponibilidade = $disponibilidade;
		} // competenteDisponibilidade

		public function competenteStatus($status) {
			switch ($status) {
				case 'Disponível':
				$disponibilidade=1;
				break;
				case 'Devolvido':
				$disponibilidade=1;
				break;
				case 'Lavando':
				$disponibilidade=2;
				break;
				case 'Manutenção':
				$disponibilidade=3;
				break;
				case 'Inativo':
				$disponibilidade=4;
				break;
				case 'Removido':
				$disponibilidade=5;
				break;
				case 'Alugado':
				$disponibilidade=6;
				break;
				case 'Reservado':
				$disponibilidade=6;
				break;
				case 'Revisão':
				$disponibilidade=7;
				break;
				default:
				$disponibilidade='';
			} // switch
			return $this->disponibilidade = $disponibilidade;
		} // competenteStatus

		public function Kilometragem($vid) {
			$sql = "
				SELECT * FROM kilometragem
				INNER JOIN competente
					ON competente.vid=kilometragem.vid
				WHERE kilometragem.vid=?
				ORDER BY kilometragem.kid
				DESC
				LIMIT 1
			";
			$stmt = $this->conectar()->prepare($sql);
			$stmt->execute([$vid]);
			$km = $stmt->fetch();
			return $this->resultado = $resultado = array(
					'kid'=>$this->kid = $km['kid']??0,
					'uid'=>$this->uid = $km['uid']??0,
					'vid'=>$this->km = $km['vid']??0,
					'km'=>$this->km = $km['km']??0,
					'data'=>$this->data = $km['data']??0
				);
		} // Kilometragem

		public function KilometragemData($data) {
			$sql = "
				SELECT * FROM kilometragem
				INNER JOIN competente
					ON competente.vid=kilometragem.vid
				WHERE kilometragem.data=?
					AND kilometragem.uid='".$this->uid."'
				ORDER BY kilometragem.kid
				DESC
				LIMIT 1
			";
			$stmt = $this->conectar()->prepare($sql);
			$stmt->execute([$data]);
			$km = $stmt->fetch();
			return $this->resultado = $resultado = array(
					'kid'=>$this->kid = $km['kid']??0,
					'uid'=>$this->uid = $km['uid']??0,
					'vid'=>$this->km = $km['vid']??0,
					'km'=>$this->km = $km['km']??0,
					'data'=>$this->data = $km['data']??0
				);
		} // KilometragemData

		public function KilometragemAnterior($vid,$inicio,$conclusao) {
			$sql = "
				SELECT * FROM kilometragem
				INNER JOIN competente
					ON competente.vid=kilometragem.vid
				WHERE kilometragem.vid=?
					AND kilometragem.data<=?
					AND kilometragem.data<?
				ORDER BY kilometragem.kid
				DESC
				LIMIT 1
			";
			$stmt = $this->conectar()->prepare($sql);
			$stmt->execute([$vid,$inicio,$conclusao]);
			$km = $stmt->fetch();
			return $this->resultado = $resultado =  array(
				'kid'=>$this->kid = $km['kid']??0,
				'uid'=>$this->uid = $km['uid']??0,
				'vid'=>$this->km = $km['vid']??0,
				'km'=>$this->km = $km['km']??0,
				'data'=>$this->data = $km['data']??0
			);
		} // KilometragemAnterior

		public function KilometragemPosterior($vid,$inicio,$conclusao) {
			$sql = "
				SELECT * FROM kilometragem
				INNER JOIN competente
					ON competente.vid=kilometragem.vid
				WHERE kilometragem.vid=?
					AND kilometragem.data>?
					AND kilometragem.data<=?
				ORDER BY kilometragem.kid
				DESC
				LIMIT 1
			";
			$stmt = $this->conectar()->prepare($sql);
			$stmt->execute([$vid,$inicio,$conclusao]);
			$km = $stmt->fetch();
			return $this->resultado = $resultado =  array(
				'kid'=>$this->kid = $km['kid']??0,
				'uid'=>$this->uid = $km['uid']??0,
				'vid'=>$this->km = $km['vid']??0,
				'km'=>$this->km = $km['km']??0,
				'data'=>$this->data = $km['data']??0
			);
		} // KilometragemPosterior

		public function Kilometragens($vid) {
			$sql = "
				SELECT * FROM kilometragem
				WHERE kilometragem.vid=?
				ORDER BY kilometragem.kid
			";
			$stmt = $this->conectar()->prepare($sql);
			$stmt->execute([$vid]);
			$kms = $stmt->fetchAll();

			if (count($kms)>=1) {
				foreach ($kms as $km) {
					$resultado[] = array(
							'kid'=>$this->kid = $km['kid']??0,
							'uid'=>$this->uid = $km['uid']??0,
							'vid'=>$this->km = $km['vid']??0,
							'km'=>$this->km = $km['km']??0,
							'data'=>$this->data = $km['data']??0
						);
				} // foreach
			} else {
				$resultado[] = array(
						'kid'=>$this->kid = $km['kid']??0,
						'uid'=>$this->uid = $km['uid']??0,
						'vid'=>$this->km = $km['vid']??0,
						'km'=>$this->km = $km['km']??0,
						'data'=>$this->data = $km['data']??0
					);
			} // count

			return $this->resultado = $resultado;
		} // Kilometragens

		public function BuscaCliente($termo) {
			$sql = "
				SELECT
					cliente.lid AS lid,
					cliente.uid AS uid,
					cliente.nome AS nome,
					cliente.documento AS documento,
					cliente.telefone AS telefone,
					cliente.email AS email,
					cliente.nascimento AS nascimento,
					cliente.data_cadastro AS data_cadastro,
					lobs.observacao AS observacao,
					endereco.cep AS cep,
					endereco.rua AS rua,
					endereco.numero AS numero,
					endereco.bairro AS bairro,
					endereco.cidade AS cidade,
					endereco.estado AS estado,
					endereco.complemento AS complemento,
					endereco.data_cadastro AS endereco_data
				FROM cliente
				LEFT JOIN lobs
					ON lobs.lid=cliente.lid
				LEFT JOIN endereco
					ON endereco.lid=cliente.lid
				LEFT JOIN documento
					ON documento.lid=cliente.lid
				WHERE cliente.uid='".$this->uid."'
					AND cliente.nome LIKE ?
					OR cliente.documento LIKE ?
					OR cliente.telefone LIKE ?
					OR cliente.email LIKE ?
				GROUP BY cliente.lid
				ORDER BY cliente.lid DESC
				LIMIT 3
			";
			$stmt = $this->conectar()->prepare($sql);
			$stmt->execute([$termo,$termo,$termo,$termo]);
			$clientes = $stmt->fetchAll();
			if (count($clientes)>0) {
				foreach ($clientes as $cliente) {
					$resultado[] = array(
						'lid'=>$this->lid = $cliente['lid']??0,
						'uid'=>$this->uid = $cliente['uid']??0,
						'nome'=>$this->nome = $cliente['nome']??0,
						'documento'=>$this->documento = $cliente['documento']??0,
						'telefone'=>$this->telefone = $cliente['telefone']??0,
						'email'=>$this->email = $cliente['email']??0,
						'nascimento'=>$this->nascimento = $cliente['nascimento']??0,
						'data_cadastro'=>$this->data_cadastro = $cliente['data_cadastro']??0,
						'observacao'=>$this->observacao = $cliente['observacao']??0,
						'cep'=>$this->cep = $cliente['cep']??0,
						'rua'=>$this->rua = $cliente['rua']??0,
						'numero'=>$this->numero = $cliente['numero']??0,
						'bairro'=>$this->bairro = $cliente['bairro']??0,
						'cidade'=>$this->cidade = $cliente['cidade']??0,
						'estado'=>$this->estado = $cliente['estado']??0,
						'complemento'=>$this->complemento = $cliente['complemento']??0,
						'endereco_data'=>$this->endereco_data = $cliente['endereco_data']??0
					);
				} // foreach
			} else {
				$resultado[] = array(
					'lid'=>$this->lid = $cliente['lid']??0,
					'uid'=>$this->uid = $cliente['uid']??0,
					'nome'=>$this->nome = $cliente['nome']??0,
					'documento'=>$this->documento = $cliente['documento']??0,
					'telefone'=>$this->telefone = $cliente['telefone']??0,
					'email'=>$this->email = $cliente['email']??0,
					'nascimento'=>$this->nascimento = $cliente['nascimento']??0,
					'data_cadastro'=>$this->data_cadastro = $cliente['data_cadastro']??0,
					'observacao'=>$this->observacao = $cliente['observacao']??0,
					'cep'=>$this->cep = $cliente['cep']??0,
					'rua'=>$this->rua = $cliente['rua']??0,
					'numero'=>$this->numero = $cliente['numero']??0,
					'bairro'=>$this->bairro = $cliente['bairro']??0,
					'cidade'=>$this->cidade = $cliente['cidade']??0,
					'estado'=>$this->estado = $cliente['estado']??0,
					'complemento'=>$this->complemento = $cliente['complemento']??0,
					'endereco_data'=>$this->endereco_data = $cliente['endereco_data']??0
				);
			} // > 0
			return $this->resultado = $resultado;
		} // BuscaCliente

		public function BuscaUmcliente($termo) {
			$sql = "
				SELECT
					cliente.lid AS lid,
					cliente.uid AS uid,
					cliente.nome AS nome,
					cliente.documento AS documento,
					cliente.telefone AS telefone,
					cliente.email AS email,
					cliente.nascimento AS nascimento,
					cliente.data_cadastro AS data_cadastro,
					lobs.observacao AS observacao,
					endereco.cep AS cep,
					endereco.rua AS rua,
					endereco.numero AS numero,
					endereco.bairro AS bairro,
					endereco.cidade AS cidade,
					endereco.estado AS estado,
					endereco.complemento AS complemento,
					endereco.data_cadastro AS endereco_data,
					documento.o_exp AS o_exp,
					documento.numero AS rg,
					documento.data_cadastro AS documento_cadastro,
					associado.asid AS asid,
					associado.associado AS associado,
					associado.data AS data_associado,
					placa.pid AS pid,
					placa.placa AS placa,
					placa.data_cadastro AS data_placa,
					placa_ativacao.plaid AS plaid,
					placa_ativacao.pid AS placa_ativa_pid,
					placa_ativacao.ativa AS placa_ativa
				FROM cliente
				LEFT JOIN lobs
					ON lobs.lid=cliente.lid
				LEFT JOIN endereco
					ON endereco.lid=cliente.lid
				LEFT JOIN documento
					ON documento.lid=cliente.lid
				LEFT JOIN associado
					ON associado.lid=cliente.lid
				LEFT JOIN placa
					ON placa.lid=associado.lid
				LEFT JOIN placa_ativacao
					ON placa_ativacao.pid=placa.pid
					AND placa_ativacao.ativa=1
					AND placa_ativacao.pid NOT IN (
						SELECT placa_ativacao.pid
						FROM placa_ativacao
						WHERE placa_ativacao.ativa=0
						)
				WHERE cliente.uid='".$this->uid."'
					AND cliente.nome LIKE ?
					OR cliente.documento LIKE ?
					OR cliente.telefone LIKE ?
					OR cliente.email LIKE ?
					OR placa.placa LIKE ?
					OR documento.numero LIKE ?
				GROUP BY cliente.lid
				ORDER BY cliente.lid DESC,
					lobs.loid DESC,
					endereco.eid DESC,
					documento.hid DESC,
					associado.asid DESC,
					placa.pid DESC,
					placa_ativacao.plaid DESC
				LIMIT 1
			";
			$stmt = $this->conectar()->prepare($sql);
			$stmt->execute([$termo,$termo,$termo,$termo,$termo,$termo]);
			$cliente = $stmt->fetch();
			return $this->resultado = $resultado = array(
				'lid'=>$this->lid = $cliente['lid']??0,
				'uid'=>$this->uid = $cliente['uid']??0,
				'nome'=>$this->nome = $cliente['nome']??0,
				'documento'=>$this->documento = $cliente['documento']??0,
				'telefone'=>$this->telefone = $cliente['telefone']??0,
				'email'=>$this->email = $cliente['email']??0,
				'nascimento'=>$this->nascimento = $cliente['nascimento']??0,
				'data_cadastro'=>$this->data_cadastro = $cliente['data_cadastro']??0,
				'observacao'=>$this->observacao = $cliente['observacao']??0,
				'cep'=>$this->cep = $cliente['cep']??0,
				'rua'=>$this->rua = $cliente['rua']??0,
				'numero'=>$this->numero = $cliente['numero']??0,
				'bairro'=>$this->bairro = $cliente['bairro']??0,
				'cidade'=>$this->cidade = $cliente['cidade']??0,
				'estado'=>$this->estado = $cliente['estado']??0,
				'complemento'=>$this->complemento = $cliente['complemento']??0,
				'endereco_data'=>$this->endereco_data = $cliente['endereco_data']??0,
				'o_exp'=>$this->o_exp = $cliente['o_exp']??0,
				'rg'=>$this->rg = $cliente['rg']??0,
				'documento_cadastro'=>$this->documento_cadastro = $cliente['documento_cadastro']??0,
				'asid'=>$this->asid = $cliente['asid']??0,
				'associado'=>$this->associado = $cliente['associado']??0,
				'data_associado'=>$this->data_associado = $cliente['data_associado']??0,
				'pid'=>$this->pid = $cliente['pid']??0,
				'placa'=>$this->placa = $cliente['placa']??0,
				'data_placa'=>$this->data_placa = $cliente['data_placa']??0
			);;
		} // BuscaUmcliente

		public function BuscaPagador($termo) {
			$sql = "
				SELECT
					cliente.lid AS lid,
					cliente.uid AS uid,
					cliente.nome AS nome,
					cliente.documento AS documento,
					cliente.telefone AS telefone,
					cliente.email AS email,
					cliente.nascimento AS nascimento,
					cliente.data_cadastro AS data_cadastro,
					lobs.observacao AS observacao,
					endereco.cep AS cep,
					endereco.rua AS rua,
					endereco.numero AS numero,
					endereco.bairro AS bairro,
					endereco.cidade AS cidade,
					endereco.estado AS estado,
					endereco.complemento AS complemento,
					endereco.data_cadastro AS endereco_data,
					documento.o_exp AS o_exp,
					documento.numero AS rg,
					documento.data_cadastro AS documento_cadastro,
					associado.asid AS asid,
					associado.associado AS associado,
					associado.data AS data_associado,
					placa.pid AS pid,
					placa.placa AS placa,
					placa.data_cadastro AS data_placa,
					placa_ativacao.plaid AS plaid,
					placa_ativacao.pid AS placa_ativa_pid,
					placa_ativacao.ativa AS placa_ativa
				FROM cliente
				LEFT JOIN lobs
					ON lobs.lid=cliente.lid
				LEFT JOIN endereco
					ON endereco.lid=cliente.lid
				LEFT JOIN documento
					ON documento.lid=cliente.lid
				LEFT JOIN associado
					ON associado.lid=cliente.lid
				LEFT JOIN placa
					ON placa.lid=associado.lid
				LEFT JOIN placa_ativacao
					ON placa_ativacao.pid=placa.pid
					AND placa_ativacao.ativa=1
					AND placa_ativacao.pid NOT IN (
						SELECT placa_ativacao.pid
						FROM placa_ativacao
						WHERE placa_ativacao.ativa=0
						)
				WHERE cliente.nome LIKE ?
					AND cliente.uid='".$this->uid."'
				GROUP BY cliente.lid
				ORDER BY cliente.lid DESC,
					lobs.loid DESC,
					endereco.eid DESC,
					documento.hid DESC,
					associado.asid DESC,
					placa.pid DESC,
					placa_ativacao.plaid DESC
				LIMIT 10
			";
			$stmt = $this->conectar()->prepare($sql);
			$stmt->execute([$termo]);
			$clientes = $stmt->fetchAll();

			if (count($clientes)>0) {
				foreach ($clientes as $cliente) {
					$resultado[] = array(
						'lid'=>$this->lid = $cliente['lid']??0,
						'uid'=>$this->uid = $cliente['uid']??0,
						'nome'=>$this->nome = $cliente['nome']??0,
						'documento'=>$this->documento = $cliente['documento']??0,
						'telefone'=>$this->telefone = $cliente['telefone']??0,
						'email'=>$this->email = $cliente['email']??0,
						'nascimento'=>$this->nascimento = $cliente['nascimento']??0,
						'data_cadastro'=>$this->data_cadastro = $cliente['data_cadastro']??0,
						'observacao'=>$this->observacao = $cliente['observacao']??0,
						'cep'=>$this->cep = $cliente['cep']??0,
						'rua'=>$this->rua = $cliente['rua']??0,
						'numero'=>$this->numero = $cliente['numero']??0,
						'bairro'=>$this->bairro = $cliente['bairro']??0,
						'cidade'=>$this->cidade = $cliente['cidade']??0,
						'estado'=>$this->estado = $cliente['estado']??0,
						'complemento'=>$this->complemento = $cliente['complemento']??0,
						'endereco_data'=>$this->endereco_data = $cliente['endereco_data']??0,
						'o_exp'=>$this->o_exp = $cliente['o_exp']??0,
						'rg'=>$this->rg = $cliente['rg']??0,
						'documento_cadastro'=>$this->documento_cadastro = $cliente['documento_cadastro']??0,
						'asid'=>$this->asid = $cliente['asid']??0,
						'associado'=>$this->associado = $cliente['associado']??0,
						'data_associado'=>$this->data_associado = $cliente['data_associado']??0,
						'pid'=>$this->pid = $cliente['pid']??0,
						'placa'=>$this->placa = $cliente['placa']??0,
						'data_placa'=>$this->data_placa = $cliente['data_placa']??0
					);
				} // foreach
			} else {
				$resultado = [];
			} // count
			return $this->resultado = $resultado;
		} // BuscaPagador

		public function ClienteInfo($lid) {
			$sql = "
				SELECT
					cliente.lid AS lid,
					cliente.uid AS uid,
					cliente.nome AS nome,
					cliente.documento AS documento,
					cliente.telefone AS telefone,
					cliente.email AS email,
					cliente.nascimento AS nascimento,
					cliente.data_cadastro AS data_cadastro,
					lobs.observacao AS observacao,
					endereco.cep AS cep,
					endereco.rua AS rua,
					endereco.numero AS numero,
					endereco.bairro AS bairro,
					endereco.cidade AS cidade,
					endereco.estado AS estado,
					endereco.complemento AS complemento,
					endereco.data_cadastro AS endereco_data,
					documento.o_exp AS o_exp,
					documento.numero AS rg,
					documento.data_cadastro AS documento_cadastro,
					associado.asid AS asid,
					associado.associado AS associado,
					associado.data AS data_associado,
					placa.pid AS pid,
					placa.placa AS placa,
					placa.data_cadastro AS data_placa
				FROM cliente
				LEFT JOIN lobs
					ON lobs.lid=cliente.lid
				LEFT JOIN endereco
					ON endereco.lid=cliente.lid
				LEFT JOIN documento
					ON documento.lid=cliente.lid
				LEFT JOIN associado
					ON associado.lid=cliente.lid
				LEFT JOIN placa
					ON placa.lid=associado.lid
				WHERE cliente.lid=?
				ORDER BY cliente.lid DESC,
					lobs.loid DESC,
					endereco.eid DESC,
					documento.hid DESC,
					associado.asid DESC,
					placa.pid DESC
				LIMIT 1
			";
			$stmt = $this->conectar()->prepare($sql);
			$stmt->execute([$lid]);
			$cliente = $stmt->fetch();
			return $this->resultado =
				$resultado = array(
					'lid'=>$this->lid = $cliente['lid']??0,
					'uid'=>$this->uid = $cliente['uid']??0,
					'nome'=>$this->nome = $cliente['nome']??0,
					'documento'=>$this->documento = $cliente['documento']??0,
					'telefone'=>$this->telefone = $cliente['telefone']??0,
					'email'=>$this->email = $cliente['email']??0,
					'nascimento'=>$this->nascimento = $cliente['nascimento']??0,
					'data_cadastro'=>$this->data_cadastro = $cliente['data_cadastro']??0,
					'observacao'=>$this->observacao = $cliente['observacao']??0,
					'cep'=>$this->cep = $cliente['cep']??0,
					'rua'=>$this->rua = $cliente['rua']??0,
					'numero'=>$this->numero = $cliente['numero']??0,
					'bairro'=>$this->bairro = $cliente['bairro']??0,
					'cidade'=>$this->cidade = $cliente['cidade']??0,
					'estado'=>$this->estado = $cliente['estado']??0,
					'complemento'=>$this->complemento = $cliente['complemento']??0,
					'endereco_data'=>$this->endereco_data = $cliente['endereco_data']??0,
					'o_exp'=>$this->o_exp = $cliente['o_exp']??0,
					'rg'=>$this->rg = $cliente['rg']??0,
					'documento_cadastro'=>$this->documento_cadastro = $cliente['documento_cadastro']??0,
					'asid'=>$this->asid = $cliente['asid']??0,
					'associado'=>$this->associado = $cliente['associado']??0,
					'data_associado'=>$this->data_associado = $cliente['data_associado']??0,
					'pid'=>$this->pid = $cliente['pid']??0,
					'placa'=>$this->placa = $cliente['placa']??0,
					'data_placa'=>$this->data_placa = $cliente['data_placa']??0
				);
		} // clienteInfo

		public function Listaclientes() {
			$sql = "
				SELECT
					cliente.lid AS lid,
					cliente.uid AS uid,
					cliente.nome AS nome,
					cliente.documento AS documento,
					cliente.telefone AS telefone,
					cliente.email AS email,
					cliente.nascimento AS nascimento,
					cliente.data_cadastro AS data_cadastro,
					lobs.observacao AS observacao
				FROM cliente
				LEFT JOIN lobs
					ON lobs.lid=cliente.lid
				WHERE cliente.uid='".$this->uid."'
				GROUP BY cliente.documento
				ORDER BY cliente.lid DESC,
					lobs.loid DESC
			";
			$stmt = $this->conectar()->prepare($sql);
			$stmt->execute();
			$clientes = $stmt->fetchAll();

			if (count($clientes)>=1) {
				foreach ($clientes as $cliente) {
					$resultado[] = array(
						'lid'=>$this->lid = $cliente['lid'],
						'uid'=>$this->uid = $cliente['uid'],
						'nome'=>$this->nome = $cliente['nome'],
						'documento'=>$this->documento = $cliente['documento'],
						'telefone'=>$this->telefone = $cliente['telefone'],
						'email'=>$this->email = $cliente['email'],
						'nascimento'=>$this->nascimento = $cliente['nascimento'],
						'data'=>$this->data = $cliente['data_cadastro']
					);
				} // foreach
			} else {
				$resultado[] = array(
					'lid'=>$this->lid = 0,
					'uid'=>$this->uid = 0,
					'nome'=>$this->nome = 0,
					'documento'=>$this->documento = 0,
					'telefone'=>$this->telefone = 0,
					'email'=>$this->email = 0,
					'nascimento'=>$this->nascimento = 0,
					'data'=>$this->data = 0
				);
			} // admins

			return $this->resultado = $resultado;
		} // Listaclientes

		public function Manutencao($mid) {
			$sql = "
				SELECT
					manutencao.mid AS mid,
					manutencao.uid AS uid,
					manutencao.vid AS vid,
					manutencao.estabelecimento AS estabelecimento,
					manutencao.motivo AS motivo,
					manutencao.inicio AS inicio,
					manutencao.devolucao AS devolucao,
					manutencao.data AS data,
					manutencao_ativacao.matid AS matid,
					manutencao_ativacao.uid AS ativacao_uid,
					manutencao_ativacao.ativa AS ativa,
					manutencao_ativacao.data AS ativacao_data,
					manutencao_reserva.mreid AS mreid,
					manutencao_reserva.uid AS reserva_uid,
					manutencao_reserva.confirmada AS confirmada,
					manutencao_reserva.inicio AS reserva_inicio,
					manutencao_reserva.devolucao AS reserva_devolucao,
					manutencao_reserva.data_mod AS data_modificacao
				FROM manutencao
				LEFT JOIN manutencao_reserva
					ON manutencao_reserva.mid=manutencao.mid
				LEFT JOIN manutencao_ativacao
					ON manutencao_ativacao.mreid=manutencao_reserva.mreid
				WHERE manutencao.mid=?
				ORDER BY manutencao.mid
				DESC
				LIMIT 1
			";
			$stmt = $this->conectar()->prepare($sql);
			$stmt->execute([$mid]);
			$manutencao = $stmt->fetch();
			return $this->resultado = $resultado = array(
					'mid'=>$this->mid = $manutencao['mid']??0,
					'uid'=>$this->uid = $manutencao['uid']??0,
					'vid'=>$this->vid = $manutencao['vid']??0,
					'estabelecimento'=>$this->estabelecimento = $manutencao['estabelecimento']??0,
					'motivo'=>$this->motivo = $manutencao['motivo']??0,
					'inicio'=>$this->inicio = $manutencao['inicio']??0,
					'devolucao'=>$this->devolucao = $manutencao['devolucao']??0,
					'data'=>$this->data = $manutencao['data']??0,
					'matid'=>$this->matid = $manutencao['matid']??0,
					'ativacao_uid'=>$this->ativacao_uid = $manutencao['ativacao_uid']??0,
					'ativa'=>$this->ativa = $manutencao['ativa']??0,
					'ativacao_data'=>$this->ativacao_data = $manutencao['ativacao_data']??0,
					'mreid'=>$this->mreid = $manutencao['mreid']??0,
					'reserva_uid'=>$this->reserva_uid = $manutencao['reserva_uid']??0,
					'confirmada'=>$this->confirmada = $manutencao['confirmada']??0,
					'reserva_inicio'=>$this->reserva_inicio = $manutencao['reserva_inicio']??0,
					'reserva_devolucao'=>$this->reserva_devolucao = $manutencao['reserva_devolucao']??0,
					'data_modificacao'=>$this->data_modificacao = $manutencao['data_modificacao']??0
				);
		} // Manutencao

		public function competenteManutencoes($vid) {
			$sql = "
				SELECT * FROM manutencao
				WHERE manutencao.vid=?
				ORDER BY manutencao.mid
				DESC
			";
			$stmt = $this->conectar()->prepare($sql);
			$stmt->execute([$vid]);
			$manutencoes = $stmt->fetchAll();

			if (count($manutencoes)>=1) {
				foreach ($manutencoes as $manutencao) {
					$resultado[] = array(
							'mid'=>$this->mid = $manutencao['mid']??0,
							'uid'=>$this->uid = $manutencao['uid']??0,
							'vid'=>$this->vid = $manutencao['vid']??0,
							'estabelecimento'=>$this->estabelecimento = $manutencao['estabelecimento']??0,
							'motivo'=>$this->motivo = $manutencao['motivo']??0,
							'inicio'=>$this->inicio = $manutencao['inicio']??0,
							'devolucao'=>$this->devolucao = $manutencao['devolucao']??0,
							'data'=>$this->data = $manutencao['data']??0
						);
				} // foreach
			} else {
				$resultado[] = array(
						'mid'=>$this->mid = $manutencao['mid']??0,
						'uid'=>$this->uid = $manutencao['uid']??0,
						'vid'=>$this->vid = $manutencao['vid']??0,
						'estabelecimento'=>$this->estabelecimento = $manutencao['estabelecimento']??0,
						'motivo'=>$this->motivo = $manutencao['motivo']??0,
						'inicio'=>$this->inicio = $manutencao['inicio']??0,
						'devolucao'=>$this->devolucao = $manutencao['devolucao']??0,
						'data'=>$this->data = $manutencao['data']??0
					);
			} // admins

			return $this->resultado = $resultado;
		} // competenteManutencoes

		public function ManutencaoRecente($vid) {
			$sql = "
				SELECT
					manutencao.mid AS mid,
					manutencao.uid AS uid,
					manutencao.vid AS vid,
					manutencao.estabelecimento AS estabelecimento,
					manutencao.motivo AS motivo,
					manutencao.inicio AS inicio,
					manutencao.devolucao AS devolucao,
					manutencao.data AS data,
					manutencao_ativacao.matid AS matid,
					manutencao_ativacao.uid AS ativacao_uid,
					manutencao_ativacao.ativa AS ativa,
					manutencao_ativacao.data AS ativacao_data,
					manutencao_reserva.mreid AS mreid,
					manutencao_reserva.uid AS reserva_uid,
					manutencao_reserva.confirmada AS confirmada,
					manutencao_reserva.inicio AS reserva_inicio,
					manutencao_reserva.devolucao AS reserva_devolucao,
					manutencao_reserva.data_mod AS data_modificacao
				FROM manutencao
				LEFT JOIN manutencao_reserva
					ON manutencao_reserva.mid=manutencao.mid
				LEFT JOIN manutencao_ativacao
					ON manutencao_ativacao.mreid=manutencao_reserva.mreid
				WHERE manutencao.vid=?
				ORDER BY manutencao.mid
				DESC
				LIMIT 1
			";
			$stmt = $this->conectar()->prepare($sql);
			$stmt->execute([$vid]);
			$manutencao = $stmt->fetch();
			return $this->resultado =  $resultado = array(
					'mid'=>$this->mid = $manutencao['mid']??0,
					'uid'=>$this->uid = $manutencao['uid']??0,
					'vid'=>$this->vid = $manutencao['vid']??0,
					'estabelecimento'=>$this->estabelecimento = $manutencao['estabelecimento']??0,
					'motivo'=>$this->motivo = $manutencao['motivo']??0,
					'inicio'=>$this->inicio = $manutencao['inicio']??0,
					'devolucao'=>$this->devolucao = $manutencao['devolucao']??0,
					'data'=>$this->data = $manutencao['data']??0,
					'matid'=>$this->matid = $manutencao['matid']??0,
					'ativacao_uid'=>$this->ativacao_uid = $manutencao['ativacao_uid']??0,
					'ativa'=>$this->ativa = $manutencao['ativa']??0,
					'ativacao_data'=>$this->ativacao_data = $manutencao['ativacao_data']??0,
					'mreid'=>$this->mreid = $manutencao['mreid']??0,
					'reserva_uid'=>$this->reserva_uid = $manutencao['reserva_uid']??0,
					'confirmada'=>$this->confirmada = $manutencao['confirmada']??0,
					'reserva_inicio'=>$this->reserva_inicio = $manutencao['reserva_inicio']??0,
					'reserva_devolucao'=>$this->reserva_devolucao = $manutencao['reserva_devolucao']??0,
					'data_modificacao'=>$this->data_modificacao = $manutencao['data_modificacao']??0
				);
		} // ManutencaoRecente

		public function ManutencaoReserva($mid) {
			$sql = "
				SELECT * FROM manutencao_reserva
				WHERE manutencao_reserva.mid=?
				ORDER BY manutencao_reserva.mreid
				DESC
				LIMIT 1
			";
			$stmt = $this->conectar()->prepare($sql);
			$stmt->execute([$mid]);
			$manutencao = $stmt->fetch();
			return $this->resultado =  $resultado = array(
					'mreid'=>$this->mreid = $manutencao['mreid']??0,
					'uid'=>$this->uid = $manutencao['uid']??0,
					'mid'=>$this->mid = $manutencao['mid']??0,
					'confirmada'=>$this->confirmada = $manutencao['confirmada']??0,
					'ativa'=>$this->ativa = $manutencao['ativa']??0,
					'inicio'=>$this->inicio = $manutencao['inicio']??0,
					'devolucao'=>$this->devolucao = $manutencao['devolucao']??0,
					'data'=>$this->data = $manutencao['data_mod']??0
				);
		} // ManutencaoReserva

		public function ManutencaoAtivacao($mreid) {
			$sql = "
				SELECT * FROM manutencao
				INNER JOIN manutencao_reserva
					ON manutencao.mid=manutencao_reserva.mid
				INNER JOIN manutencao_ativacao
					ON manutencao_reserva.mreid=manutencao_ativacao.mreid
				WHERE manutencao_ativacao.mreid=?
				ORDER BY manutencao_ativacao.matid DESC,
					manutencao.mid DESC,
					manutencao_reserva.mreid DESC
				LIMIT 1
			";
			$stmt = $this->conectar()->prepare($sql);
			$stmt->execute([$mreid]);
			$manutencao = $stmt->fetch();
			return $this->resultado =  $resultado = array(
					'matid'=>$this->matid = $manutencao['matid']??0,
					'uid'=>$this->uid = $manutencao['uid']??0,
					'mid'=>$this->mid = $manutencao['mid']??0,
					'mreid'=>$this->mreid = $manutencao['mreid']??0,
					'ativa'=>$this->ativa = $manutencao['ativa']??0,
					'inicio'=>$this->inicio = $manutencao['inicio']??0,
					'devolucao'=>$this->devolucao = $manutencao['devolucao']??0,
					'data'=>$this->data = $manutencao['data_mod']??0
				);
		} // ManutencaoAtivacao

		public function ManutencaoAtual($vid) {
			$sql = "
				SELECT * FROM manutencao
				WHERE manutencao.vid=?
					AND NOT EXISTS (
						SELECT *
						FROM retorno
						WHERE retorno.mid=manutencao.mid
					)
				ORDER BY manutencao.mid
				DESC
				LIMIT 1
			";
			$stmt = $this->conectar()->prepare($sql);
			$stmt->execute([$vid]);
			$manutencao = $stmt->fetch();
			return $this->resultado = $resultado = array(
					'mid'=>$this->mid = $manutencao['mid']??0,
					'uid'=>$this->uid = $manutencao['uid']??0,
					'vid'=>$this->vid = $manutencao['vid']??0,
					'estabelecimento'=>$this->estabelecimento = $manutencao['estabelecimento']??0,
					'motivo'=>$this->motivo = $manutencao['motivo']??0,
					'inicio'=>$this->inicio = $manutencao['inicio']??0,
					'devolucao'=>$this->devolucao = $manutencao['devolucao']??0,
					'data'=>$this->data = $manutencao['data']??0
				);
		} // ManutencaoAtual

		public function ManutencoesAtivas($vid) {
			$sql = "
				SELECT
					manutencao.mid AS mid,
					manutencao.uid AS uid,
					manutencao.vid AS vid,
					manutencao.estabelecimento AS estabelecimento,
					manutencao.motivo AS motivo,
					manutencao.inicio AS inicio,
					manutencao.devolucao AS devolucao,
					manutencao.data AS data,
					manutencao_ativacao.matid AS matid,
					manutencao_ativacao.uid AS ativacao_uid,
					manutencao_ativacao.ativa AS ativa,
					manutencao_ativacao.data AS ativacao_data,
					manutencao_reserva.mreid AS mreid,
					manutencao_reserva.uid AS reserva_uid,
					manutencao_reserva.confirmada AS confirmada,
					manutencao_reserva.inicio AS reserva_inicio,
					manutencao_reserva.devolucao AS reserva_devolucao,
					manutencao_reserva.data_mod AS data_modificacao
				FROM manutencao
				LEFT JOIN manutencao_reserva
					ON manutencao_reserva.mid=manutencao.mid
				LEFT JOIN manutencao_ativacao
					ON manutencao_ativacao.mreid=manutencao_reserva.mreid
				WHERE manutencao.vid=?
					AND NOT EXISTS (
						SELECT *
						FROM retorno
						WHERE retorno.mid=manutencao.mid
					)
				GROUP BY manutencao.mid
				ORDER BY manutencao.mid DESC,
					manutencao_reserva.mreid DESC,
					manutencao_ativacao.matid DESC
			";
			$stmt = $this->conectar()->prepare($sql);
			$stmt->execute([$vid]);
			$manutencoes = $stmt->fetchAll();

			if (count($manutencoes)>=1) {
				foreach ($manutencoes as $manutencao) {
					$resultado[] = array(
							'mid'=>$this->mid = $manutencao['mid']??0,
							'uid'=>$this->uid = $manutencao['uid']??0,
							'vid'=>$this->vid = $manutencao['vid']??0,
							'estabelecimento'=>$this->estabelecimento = $manutencao['estabelecimento']??0,
							'motivo'=>$this->motivo = $manutencao['motivo']??0,
							'inicio'=>$this->inicio = $manutencao['inicio']??0,
							'devolucao'=>$this->devolucao = $manutencao['devolucao']??0,
							'data'=>$this->data = $manutencao['data']??0,
							'matid'=>$this->matid = $manutencao['matid']??0,
							'ativacao_uid'=>$this->ativacao_uid = $manutencao['ativacao_uid']??0,
							'ativa'=>$this->ativa = $manutencao['ativa']??0,
							'ativacao_data'=>$this->ativacao_data = $manutencao['ativacao_data']??0,
							'mreid'=>$this->mreid = $manutencao['mreid']??0,
							'reserva_uid'=>$this->reserva_uid = $manutencao['reserva_uid']??0,
							'confirmada'=>$this->confirmada = $manutencao['confirmada']??0,
							'reserva_inicio'=>$this->reserva_inicio = $manutencao['reserva_inicio']??0,
							'reserva_devolucao'=>$this->reserva_devolucao = $manutencao['reserva_devolucao']??0,
							'data_modificacao'=>$this->data_modificacao = $manutencao['data_modificacao']??0
						);
				} // foreach
			} else {
				$resultado[] = array(
						'mid'=>$this->mid = $manutencao['mid']??0,
						'uid'=>$this->uid = $manutencao['uid']??0,
						'vid'=>$this->vid = $manutencao['vid']??0,
						'estabelecimento'=>$this->estabelecimento = $manutencao['estabelecimento']??0,
						'motivo'=>$this->motivo = $manutencao['motivo']??0,
						'inicio'=>$this->inicio = $manutencao['inicio']??0,
						'devolucao'=>$this->devolucao = $manutencao['devolucao']??0,
						'data'=>$this->data = $manutencao['data']??0,
						'matid'=>$this->matid = $manutencao['matid']??0,
						'ativacao_uid'=>$this->ativacao_uid = $manutencao['ativacao_uid']??0,
						'ativa'=>$this->ativa = $manutencao['ativa']??0,
						'ativacao_data'=>$this->ativacao_data = $manutencao['ativacao_data']??0,
						'mreid'=>$this->mreid = $manutencao['mreid']??0,
						'reserva_uid'=>$this->reserva_uid = $manutencao['reserva_uid']??0,
						'confirmada'=>$this->confirmada = $manutencao['confirmada']??0,
						'reserva_inicio'=>$this->reserva_inicio = $manutencao['reserva_inicio']??0,
						'reserva_devolucao'=>$this->reserva_devolucao = $manutencao['reserva_devolucao']??0,
						'data_modificacao'=>$this->data_modificacao = $manutencao['data_modificacao']??0
					);
			} // admins
			return $this->resultado = $resultado;
		} // ManutencoesAtivas

		public function competenteManutencao($motivo) {
			switch ($motivo) {
				case 1:
				$motivo='Oficina';
				break;
				case 2:
				$motivo='Lavando';
				break;
				case 3:
				$motivo='Inativo';
				break;
				case 4:
				$motivo='Removido';
				break;
				case 5:
				$motivo='Revisão';
				break;
				case 6:
				$motivo='Pintura';
				break;
				default:
				$motivo=0;
			} // switch
			return $this->motivo = $motivo;
		} // competenteManutencao

		public function ListaManutencoes() {
			$sql = "
				SELECT
					manutencao.mid AS mid,
					manutencao.uid AS uid,
					manutencao.vid AS vid,
					manutencao.estabelecimento AS estabelecimento,
					manutencao.motivo AS motivo,
					manutencao.inicio AS inicio,
					manutencao.devolucao AS devolucao,
					manutencao.data AS data,
					manutencao_ativacao.matid AS matid,
					manutencao_ativacao.uid AS ativacao_uid,
					manutencao_ativacao.ativa AS ativa,
					manutencao_ativacao.data AS ativacao_data,
					manutencao_reserva.mreid AS mreid,
					manutencao_reserva.uid AS reserva_uid,
					manutencao_reserva.confirmada AS confirmada,
					manutencao_reserva.inicio AS reserva_inicio,
					manutencao_reserva.devolucao AS reserva_devolucao,
					manutencao_reserva.data_mod AS data_modificacao
				FROM manutencao
				LEFT JOIN manutencao_reserva
					ON manutencao_reserva.mid=manutencao.mid
				LEFT JOIN manutencao_ativacao
					ON manutencao_ativacao.mreid=manutencao_reserva.mreid
				WHERE manutencao.uid='".$this->uid."'
				GROUP BY manutencao.mid
				ORDER BY manutencao.mid
				DESC
			";
			$stmt = $this->conectar()->prepare($sql);
			$stmt->execute();
			$manutencoes = $stmt->fetchAll();

			if (count($manutencoes)>=1) {
				foreach ($manutencoes as $manutencao) {
					$resultado[] = array(
							'mid'=>$this->mid = $manutencao['mid']??0,
							'uid'=>$this->uid = $manutencao['uid']??0,
							'vid'=>$this->vid = $manutencao['vid']??0,
							'estabelecimento'=>$this->estabelecimento = $manutencao['estabelecimento']??0,
							'motivo'=>$this->motivo = $manutencao['motivo']??0,
							'inicio'=>$this->inicio = $manutencao['inicio']??0,
							'devolucao'=>$this->devolucao = $manutencao['devolucao']??0,
							'data'=>$this->data = $manutencao['data']??0,
							'matid'=>$this->matid = $manutencao['matid']??0,
							'ativacao_uid'=>$this->ativacao_uid = $manutencao['ativacao_uid']??0,
							'ativa'=>$this->ativa = $manutencao['ativa']??0,
							'ativacao_data'=>$this->ativacao_data = $manutencao['ativacao_data']??0,
							'mreid'=>$this->mreid = $manutencao['mreid']??0,
							'reserva_uid'=>$this->reserva_uid = $manutencao['reserva_uid']??0,
							'confirmada'=>$this->confirmada = $manutencao['confirmada']??0,
							'reserva_inicio'=>$this->reserva_inicio = $manutencao['reserva_inicio']??0,
							'reserva_devolucao'=>$this->reserva_devolucao = $manutencao['reserva_devolucao']??0,
							'data_modificacao'=>$this->data_modificacao = $manutencao['data_modificacao']??0
						);
				} // foreach
			} else {
				$resultado[] = array(
						'mid'=>$this->mid = $manutencao['mid']??0,
						'uid'=>$this->uid = $manutencao['uid']??0,
						'vid'=>$this->vid = $manutencao['vid']??0,
						'estabelecimento'=>$this->estabelecimento = $manutencao['estabelecimento']??0,
						'motivo'=>$this->motivo = $manutencao['motivo']??0,
						'inicio'=>$this->inicio = $manutencao['inicio']??0,
						'devolucao'=>$this->devolucao = $manutencao['devolucao']??0,
						'data'=>$this->data = $manutencao['data']??0,
						'matid'=>$this->matid = $manutencao['matid']??0,
						'ativacao_uid'=>$this->ativacao_uid = $manutencao['ativacao_uid']??0,
						'ativa'=>$this->ativa = $manutencao['ativa']??0,
						'ativacao_data'=>$this->ativacao_data = $manutencao['ativacao_data']??0,
						'mreid'=>$this->mreid = $manutencao['mreid']??0,
						'reserva_uid'=>$this->reserva_uid = $manutencao['reserva_uid']??0,
						'confirmada'=>$this->confirmada = $manutencao['confirmada']??0,
						'reserva_inicio'=>$this->reserva_inicio = $manutencao['reserva_inicio']??0,
						'reserva_devolucao'=>$this->reserva_devolucao = $manutencao['reserva_devolucao']??0,
						'data_modificacao'=>$this->data_modificacao = $manutencao['data_modificacao']??0
					);
			} // admins

			return $this->resultado = $resultado;
		} // ListaManutencoes

		public function Retorno($mid) {
			$sql = "
				SELECT * FROM retorno
				WHERE retorno.mid=?
				ORDER BY retorno.rid
				DESC
				LIMIT 1
			";
			$stmt = $this->conectar()->prepare($sql);
			$stmt->execute([$mid]);
			$retorno = $stmt->fetch();
			return $this->resultado = $resultado = array(
				'rid'=>$this->rid = $retorno['rid']??0,
				'uid'=>$this->uid = $retorno['uid']??0,
				'mid'=>$this->km = $retorno['mid']??0,
				'valor'=>$this->valor = $retorno['valor']??0,
				'observacao'=>$this->observacao = $retorno['observacao']??0,
				'data'=>$this->data = $retorno['data']??0
			);
		} // Retorno

		public function ListaRetornos() {
			$sql = "
				SELECT * FROM retorno
				WHERE uid='".$this->uid."'
				ORDER BY rid
				DESC
			";
			$stmt = $this->conectar()->prepare($sql);
			$stmt->execute();
			$retornos = $stmt->fetchAll();

			if (count($retornos)>=1) {
				foreach ($retornos as $retorno) {
					$resultado[] = array(
						'rid'=>$this->rid = $retorno['rid']??0,
						'uid'=>$this->uid = $retorno['uid']??0,
						'mid'=>$this->km = $retorno['mid']??0,
						'valor'=>$this->valor = $retorno['valor']??0,
						'observacao'=>$this->observacao = $retorno['observacao']??0,
						'data'=>$this->data = $retorno['data']??0
					);
				} // foreach
			} else {
				$resultado[] = array(
					'rid'=>$this->rid = 0,
					'uid'=>$this->uid = 0,
					'mid'=>$this->km = 0,
					'valor'=>$this->valor = 0,
					'observacao'=>$this->observacao = 0,
					'data'=>$this->data = 0
				);
			} // admins

			return $this->resultado = $resultado;
		} // ListaRetornos

		public function Cortesia($pid) {
			$sql = "
				SELECT * FROM placa
				LEFT JOIN cortesia
					ON cortesia.pid=placa.pid
				WHERE placa.pid=?
				ORDER BY cortesia.cid
				DESC
			";
			$stmt = $this->conectar()->prepare($sql);
			$stmt->execute([$pid]);
			$cortesias = $stmt->fetchAll();

			if (count($cortesias)>=1) {
				foreach ($cortesias as $cortesia) {
					$resultado[] = array(
						'cid'=>$this->cid = $cortesia['cid']??0,
						'uid'=>$this->uid = $cortesia['uid']??0,
						'pid'=>$this->pid = $cortesia['pid']??0,
						'utilizadas'=>$this->utilizadas = $cortesia['utilizadas']??0,
						'data'=>$this->data = $cortesia['data']??0
					);
				} // foreach
			} else {
				$resultado[] = array(
					'cid'=>$this->cid = 0,
					'uid'=>$this->uid = 0,
					'pid'=>$this->pid = 0,
					'utilizadas'=>$this->utilizadas = 0,
					'data'=>$this->data = 0
				);
			} // foreach

			return $this->resultado = $resultado;
		} // Cortesia

		public function Placa($pid) {
			$sql = "
				SELECT * FROM placa
				WHERE pid=?
				ORDER BY pid
				DESC
				LIMIT 1
			";
			$stmt = $this->conectar()->prepare($sql);
			$stmt->execute([$pid]);
			$placa = $stmt->fetch();
			return $this->resultado = $resultado = array(
				'pid'=>$this->pid = $placa['pid']??0,
				'uid'=>$this->uid = $placa['uid']??0,
				'lid'=>$this->lid = $placa['lid']??0,
				'asid'=>$this->asid = $placa['asid']??0,
				'placa'=>$this->placa = $placa['placa']??0,
				'data'=>$this->data = $placa['data_cadastro']??0
			);
		} // Placa

		public function Placas($lid) {
			$sql = "
				SELECT
					placa.pid AS pid,
					placa.uid AS uid,
					placa.lid AS lid,
					placa.asid AS asid,
					placa.placa AS placa,
					placa.data_cadastro AS data_cadastro,
					max(placa_ativacao.plaid) AS plaid,
					placa_ativacao.ativa AS ativa
				FROM placa
				LEFT JOIN placa_ativacao
					ON placa.pid=placa_ativacao.pid
				WHERE placa.lid=?
				GROUP BY placa.pid
				ORDER BY placa_ativacao.plaid DESC,
					placa.pid DESC
			";
			$stmt = $this->conectar()->prepare($sql);
			$stmt->execute([$lid]);
			$placas = $stmt->fetchAll();

			if (count($placas)>=1) {
				foreach ($placas as $placa) {
					$resultado[] = array(
						'pid'=>$this->pid = $placa['pid']??0,
						'uid'=>$this->uid = $placa['uid']??0,
						'lid'=>$this->lid = $placa['lid']??0,
						'asid'=>$this->asid = $placa['asid']??0,
						'placa'=>$this->placa = $placa['placa']??0,
						'plaid'=>$this->plaid = $placa['plaid']??0,
						'ativa'=>$this->ativa = $placa['ativa']??0,
						'data'=>$this->data = $placa['data_cadastro']??0
					);
				} // foreach
			} else {
				$resultado[] = array(
					'pid'=>$this->pid = 0,
					'uid'=>$this->uid = 0,
					'lid'=>$this->lid = 0,
					'asid'=>$this->asid = 0,
					'placa'=>$this->placa = 0,
					'data'=>$this->data = 0
				);
			} // foreach

			return $this->resultado = $resultado;
		} // Placas

		public function PlacaAtiva($pid) {
			$sql = "
				SELECT *
				FROM placa_ativacao
				INNER JOIN placa
					ON placa_ativacao.pid=placa.pid
				WHERE placa_ativacao.pid=?
					AND placa_ativacao.ativa=1
					AND placa_ativacao.pid NOT IN (
						SELECT placa_ativacao.pid
						FROM placa_ativacao
						WHERE placa_ativacao.ativa=0
						)
				GROUP BY placa_ativacao.pid
				ORDER BY placa_ativacao.plaid
				DESC
				LIMIT 1
			";
			$stmt = $this->conectar()->prepare($sql);
			$stmt->execute([$pid]);
			$placa = $stmt->fetch();
			return $this->resultado = $resultado = array(
				'plaid'=>$this->plaid = $placa['plaid']??0,
				'uid'=>$this->uid = $placa['uid']??0,
				'pid'=>$this->pid = $placa['pid']??0,
				'placa'=>$this->placa = $placa['placa']??0,
				'ativa'=>$this->ativa = $placa['ativa']??0,
				'data'=>$this->data = $placa['data_mod']??0
			);
		} // PlacaAtiva

		public function PlacaRecente($lid) {
			$sql = "
				SELECT placa.pid,placa.lid
				FROM placa
				WHERE placa.lid=?
				ORDER BY placa.pid
				DESC
				LIMIT 1
			";
			$stmt = $this->conectar()->prepare($sql);
			$stmt->execute([$lid]);
			$placa = $stmt->fetch();

			return $this->resultado = $placa['pid']??0;
		} // PlacaRecente

		public function ListaReservas() {
			$sql = "
				SELECT
					reserva.reid AS reid,
					reserva.uid AS reserva_uid,
					reserva.confirmada AS confirmada,
					reserva.inicio AS reserva_inicio,
					reserva.devolucao AS reserva_devolucao,
					reserva.data_mod AS reserva_data_mod,
					aluguel.aid AS aid,
					aluguel.uid AS aluguel_uid,
					aluguel.lid AS lid,
					aluguel.diaria AS diaria,
					aluguel.km AS km,
					aluguel.inicio AS aluguel_inicio,
					aluguel.devolucao AS aluguel_devolucao,
					aluguel.data AS aluguel_data,
					ativacao.atid AS atid,
					ativacao.uid AS ativacao_uid,
					ativacao.reid AS ativacao_reid,
					ativacao.ativa AS ativa,
					ativacao.data AS ativacao_data
				FROM reserva
				INNER JOIN aluguel
					ON aluguel.aid=reserva.aid
				INNER JOIN ativacao
					ON ativacao.reid=reserva.reid
				WHERE reserva.uid='".$this->uid."'
				GROUP BY reserva.aid
				ORDER BY reserva.reid DESC,
					ativacao.atid DESC
			";
			$stmt = $this->conectar()->prepare($sql);
			$stmt->execute();
			$reservas = $stmt->fetchAll();

			if (count($reservas)>=1) {
				foreach ($reservas as $reserva) {
					$resultado[] = array(
						'reid'=>$this->reid = $reserva['reid']??0,
						'uid'=>$this->uid = $reserva['reserva_uid']??0,
						'confirmada'=>$this->confirmada = $reserva['confirmada']??0,
						'reserva_inicio'=>$this->reserva_inicio = $reserva['reserva_inicio']??0,
						'reserva_devolucao'=>$this->reserva_devolucao = $reserva['reserva_devolucao']??0,
						'reserva_data_mod'=>$this->reserva_data_mod = $reserva['reserva_data_mod']??0,
						'aid'=>$this->aid = $reserva['aid']??0,
						'aluguel_uid'=>$this->aluguel_uid = $reserva['aluguel_uid']??0,
						'lid'=>$this->lid = $reserva['lid']??0,
						'diaria'=>$this->diaria = $reserva['diaria']??0,
						'km'=>$this->km = $reserva['km']??0,
						'aluguel_inicio'=>$this->aluguel_inicio = $reserva['aluguel_inicio']??0,
						'aluguel_devolucao'=>$this->aluguel_devolucao = $reserva['aluguel_devolucao']??0,
						'aluguel_data'=>$this->aluguel_data = $reserva['aluguel_data']??0,
						'atid'=>$this->atid = $reserva['atid']??0,
						'ativacao_uid'=>$this->ativacao_uid = $reserva['ativacao_uid']??0,
						'ativacao_reid'=>$this->ativacao_reid = $reserva['ativacao_reid']??0,
						'ativa'=>$this->ativa = $reserva['ativa']??0,
						'ativacao_data'=>$this->ativacao_data = $reserva['ativacao_data']??0
					);
				} // foreach
			} else {
				$resultado[] = array(
					'reid'=>$this->reid = 0,
					'uid'=>$this->uid = 0,
					'inicio'=>$this->inicio = 0,
					'devolucao'=>$this->devolucao = 0,
					'reserva_data_mod'=>$this->reserva_data_mod = 0,
					'aid'=>$this->aid = 0,
					'aluguel_uid'=>$this->aluguel_uid = 0,
					'lid'=>$this->lid = 0,
					'diaria'=>$this->diaria = 0,
					'km'=>$this->km = 0,
					'aluguel_inicio'=>$this->aluguel_inicio = 0,
					'aluguel_devolucao'=>$this->aluguel_devolucao = 0,
					'aluguel_data'=>$this->aluguel_data = 0,
					'atid'=>$this->atid = 0,
					'ativacao_uid'=>$this->ativacao_uid = 0,
					'ativacao_reid'=>$this->ativacao_reid = 0,
					'ativa'=>$this->ativa = 0,
					'ativacao_data'=>$this->ativacao_data = 0
				);
			} // foreach

			return $this->resultado = $resultado;
		} // ListaReservas

		public function Reservados($vid) {
			$sql = "
				SELECT
					reserva.reid AS reid,
					reserva.inicio AS inicio,
					reserva.devolucao AS devolucao,
					reserva.data_mod AS data,
					aluguel.aid AS aid,
					ativacao.atid AS atid,
					ativacao.ativa AS ativa
				FROM reserva
				INNER JOIN aluguel
					ON reserva.aid=aluguel.aid
				INNER JOIN ativacao
					ON ativacao.reid=reserva.reid
				WHERE aluguel.vid=?
					AND ativacao.ativa='S'
					AND NOT EXISTS (
						SELECT *
						FROM devolucao
						WHERE reserva.aid=devolucao.aid
					)
				ORDER BY ativacao.atid DESC,
					reserva.inicio ASC
			";
			$stmt = $this->conectar()->prepare($sql);
			$stmt->execute([$vid]);
			$reservados = $stmt->fetchAll();
			if (count($reservados)>0) {
				foreach ($reservados as $reservado) {
					$resultado[] = $reservado['aid']??0;
				} // foreach
			} else {
				$resultado[] = 0;
			} // count
			return $this->resultado = $resultado;
		} // Reservados

		public function HistoricoReserva($aid) {
			$sql = "
				SELECT *
				FROM reserva
				WHERE reserva.aid=?
				ORDER BY reserva.data_mod DESC
			";
			$stmt = $this->conectar()->prepare($sql);
			$stmt->execute([$aid]);
			$reservas = $stmt->fetchAll();
			if (count($reservas)>0) {
				foreach ($reservas as $reserva) {
					$resultado[] = array(
						'reid'=>$this->reid = $reserva['reid']??0,
						'uid'=>$this->uid = $reserva['uid']??0,
						'aid'=>$this->aid = $reserva['aid']??0,
						'confirmada'=>$this->confirmada = $reserva['confirmada']??0,
						'inicio'=>$this->inicio = $reserva['inicio']??0,
						'devolucao'=>$this->devolucao = $reserva['devolucao']??0,
						'data'=>$this->data = $reserva['data_mod']??0
					);
				} // foreach
			} else {
				$resultado[] = array(
					'reid'=>$this->reid = $reserva['reid']??0,
					'uid'=>$this->uid = $reserva['uid']??0,
					'aid'=>$this->aid = $reserva['aid']??0,
					'confirmada'=>$this->confirmada = $reserva['confirmada']??0,
					'inicio'=>$this->inicio = $reserva['inicio']??0,
					'devolucao'=>$this->devolucao = $reserva['devolucao']??0,
					'data'=>$this->data = $reserva['data_mod']??0
				);
			} // count
			return $this->resultado = $resultado;
		} // HistoricoReserva

		public function Reserva($aid) {
			$sql = "
				SELECT
					aluguel.aid AS aid,
					aluguel.lid AS lid,
					reserva.reid AS reid,
					reserva.uid AS uid,
					reserva.confirmada AS confirmada,
					reserva.inicio AS inicio,
					reserva.devolucao AS devolucao,
					reserva.data_mod AS data
				FROM reserva
				INNER JOIN aluguel
					ON reserva.aid=aluguel.aid
				WHERE reserva.aid=?
					AND NOT EXISTS (
						SELECT *
						FROM devolucao
						WHERE reserva.aid=devolucao.aid
					)
				ORDER BY reserva.reid
				DESC
				LIMIT 1
			";
			$stmt = $this->conectar()->prepare($sql);
			$stmt->execute([$aid]);
			$reserva = $stmt->fetch();
			return $this->resultado = $resultado = array(
				'reid'=>$this->reid = $reserva['reid']??0,
				'uid'=>$this->uid = $reserva['uid']??0,
				'aid'=>$this->aid = $reserva['aid']??0,
				'confirmada'=>$this->confirmada = $reserva['confirmada']??0,
				'lid'=>$this->lid = $reserva['lid']??0,
				'inicio'=>$this->inicio = $reserva['inicio']??0,
				'devolucao'=>$this->devolucao = $reserva['devolucao']??0,
				'data'=>$this->data = $reserva['data']??0
			);
		} // Reserva

		public function ReservaDevolvida($aid) {
			$sql = "
				SELECT
					aluguel.aid AS aid,
					aluguel.lid AS lid,
					reserva.reid AS reid,
					reserva.uid AS uid,
					reserva.confirmada AS confirmada,
					reserva.inicio AS inicio,
					reserva.devolucao AS devolucao,
					reserva.data_mod AS data
				FROM reserva
				INNER JOIN aluguel
					ON reserva.aid=aluguel.aid
				WHERE reserva.aid=?
				ORDER BY reserva.reid
				DESC
				LIMIT 1
			";
			$stmt = $this->conectar()->prepare($sql);
			$stmt->execute([$aid]);
			$reserva = $stmt->fetch();
			return $this->resultado = $resultado = array(
				'reid'=>$this->reid = $reserva['reid']??0,
				'uid'=>$this->uid = $reserva['uid']??0,
				'aid'=>$this->aid = $reserva['aid']??0,
				'confirmada'=>$this->confirmada = $reserva['confirmada']??0,
				'lid'=>$this->lid = $reserva['lid']??0,
				'inicio'=>$this->inicio = $reserva['inicio']??0,
				'devolucao'=>$this->devolucao = $reserva['devolucao']??0,
				'data'=>$this->data = $reserva['data']??0
			);
		} // ReservaDevolvida

		public function ReservaProxima($aid) {
			$sql = "
				SELECT
					aluguel.aid AS aid,
					aluguel.lid AS lid,
					reserva.reid AS reid,
					reserva.uid AS uid,
					reserva.inicio AS inicio,
					reserva.devolucao AS devolucao,
					reserva.data_mod AS data
				FROM reserva
				INNER JOIN aluguel
					ON reserva.aid=aluguel.aid
				WHERE reserva.aid=?
					AND NOT EXISTS (
						SELECT *
						FROM devolucao
						WHERE reserva.aid=devolucao.aid
					)
				ORDER BY reserva.reid
				DESC
				LIMIT 1
			";
			$stmt = $this->conectar()->prepare($sql);
			$stmt->execute([$aid]);
			$reserva = $stmt->fetch();
			return $this->resultado = $resultado = array(
				'reid'=>$this->reid = $reserva['reid']??0,
				'uid'=>$this->uid = $reserva['uid']??0,
				'aid'=>$this->aid = $reserva['aid']??0,
				'lid'=>$this->lid = $reserva['lid']??0,
				'inicio'=>$this->inicio = $reserva['inicio']??0,
				'devolucao'=>$this->devolucao = $reserva['devolucao']??0,
				'data'=>$this->data = $reserva['data']??0
			);
		} // ReservaProxima

		public function ReservaAtiva($aid) {
			$sql = "
				SELECT * FROM reserva
				INNER JOIN aluguel
					ON aluguel.aid=reserva.aid
				LEFT JOIN ativacao
					ON ativacao.reid=reserva.reid
				WHERE reserva.aid=?
				ORDER BY ativacao.atid
				DESC
				LIMIT 1
			";
			$stmt = $this->conectar()->prepare($sql);
			$stmt->execute([$aid]);
			$ativa = $stmt->fetch();
			return $this->resultado = $resultado = array(
					'atid'=>$this->atid = $ativa['atid']??0,
					'uid'=>$this->uid = $ativa['uid']??0,
					'aid'=>$this->aid = $ativa['aid']??0,
					'reid'=>$this->reid = $ativa['reid']??0,
					'ativa'=>$this->ativa = $ativa['ativa']??0,
					'data'=>$this->data = $ativa['data']??0
				);
		} // ReservaAtiva

		public function Transacao($tid) {
			$sql = "
				SELECT * FROM transacao
				WHERE tid=?
				ORDER BY tid
				DESC
				LIMIT 1
			";
			$stmt = $this->conectar()->prepare($sql);
			$stmt->execute([$tid]);
			$transacao = $stmt->fetch();
			return $this->resultado = $resultado = array(
				'tid'=>$this->tid = $transacao['tid']??0,
				'uid'=>$this->uid = $transacao['uid']??0,
				'coid'=>$this->coid = $transacao['coid']??0,
				'valor'=>$this->valor = $transacao['valor']??0,
				'desconto'=>$this->desconto = $transacao['desconto']??0,
				'forma'=>$this->forma = $transacao['forma']??0,
				'data'=>$this->data = $transacao['data']??0
			);
		} // Transacao

		public function ValorAdicional($deid) {
			$sql = "
				SELECT * FROM valor_adicional
				WHERE deid=?
				ORDER BY vaid
				DESC
				LIMIT 1
			";
			$stmt = $this->conectar()->prepare($sql);
			$stmt->execute([$deid]);
			$valoradicional = $stmt->fetch();
			return $this->resultado = $resultado = array(
				'vaid'=>$this->vaid = $valoradicional['vaid']??0,
				'uid'=>$this->uid = $valoradicional['uid']??0,
				'deid'=>$this->deid = $valoradicional['deid']??0,
				'valor'=>$this->valor = $valoradicional['valor']??0,
				'descricao'=>$this->descricao = $valoradicional['descricao']??0,
				'data'=>$this->data = $valoradicional['data']??0
			);
		} // Transacao

		////////////////////////////////////

		public function LimpezaTipo($data,$preco) {
			$sql = "
				(SELECT cpleid as limpeza, preco, data_mod, 'limpeza executiva' as tipo FROM cfig_preco_le WHERE data_mod<? AND preco=? AND uid='".$this->uid."' ORDER BY cpleid ASC LIMIT 1)
				UNION
				(SELECT cplcid as limpeza, preco, data_mod, 'limpeza completa' as tipo FROM cfig_preco_lc WHERE data_mod<? AND preco=? AND uid='".$this->uid."' ORDER BY cplcid ASC LIMIT 1)
				UNION
				(SELECT cplmid as limpeza, preco, data_mod, 'limpeza completa com motor' as tipo FROM cfig_preco_lm WHERE data_mod<? AND preco=? AND uid='".$this->uid."' ORDER BY cplmid ASC LIMIT 1)
			";
			$stmt = $this->conectar()->prepare($sql);
			$stmt->execute([$data,$preco,$data,$preco,$data,$preco]);
			$limpeza = $stmt->fetch();
			return $this->resultado = $resultado = array(
				'limpeza'=>$this->limpeza = $limpeza['limpeza']??0,
				'preco'=>$this->preco = $limpeza['preco']??0,
				'data'=>$this->data = $limpeza['data_mod']??0,
				'tipo'=>$this->tipo = $limpeza['tipo']??0
			);
		} // LimpezaTipo

		public function PrecoKMData($data) {
			$sql = "
				SELECT *
				FROM cfig_preco_km
				WHERE data_mod<?
					AND uid='".$this->uid."'
				ORDER BY cpkmid
				DESC
				LIMIT 1
			";
			$stmt = $this->conectar()->prepare($sql);
			$stmt->execute([$data]);
			$km = $stmt->fetch();
			return $this->resultado = $resultado = array(
				'preco'=>$this->preco = $km['preco']??0,
				'data'=>$this->data = $km['data_mod']??0,
			);
		} // PrecoKMData

		public function ToleranciaData($data) {
			$sql = "
				SELECT *
				FROM cfig_tol_dev
				WHERE data_mod<?
					AND uid='".$this->uid."'
				ORDER BY ctdid
				DESC
				LIMIT 1
			";
			$stmt = $this->conectar()->prepare($sql);
			$stmt->execute([$data]);
			$tolerancia = $stmt->fetch();
			return $this->resultado = $resultado = array(
				'minutos'=>$this->minutos = $tolerancia['minutos']??0,
				'data'=>$this->data = $tolerancia['data_mod']??0,
			);
		} // ToleranciaData

		public function DiariaExcedenteData($data) {
			$configuracoes = $this->Configuracoes();
			$excedentes = [];
			// CARRO
			$sql = "
				SELECT * FROM cfig_diaria_exc_carro WHERE data_mod<? AND uid='".$this->uid."' ORDER BY cdecid DESC LIMIT 1
			";
			$stmt = $this->conectar()->prepare($sql);
			$stmt->execute([$data]);
			$excedente = $stmt->fetch();
			$excedentes += ['excedente_carro'=>$this->excedente_carro = $excedente['preco']??$configuracoes['excedente_carro']];
			$excedentes += ['excedente_carro_mod'=>$this->excedente_carro_mod = $excedente['data_mod']??$configuracoes['excedente_carro_mod']];

			// MOTO
			$sql = "
				SELECT * FROM cfig_diaria_exc_moto WHERE data_mod<? AND uid='".$this->uid."' ORDER BY cdemid DESC LIMIT 1
			";
			$stmt = $this->conectar()->prepare($sql);
			$stmt->execute([$data]);
			$excedente = $stmt->fetch();
			$excedentes+= ['excedente_moto'=>$this->excedente_moto = $excedente['preco']??$configuracoes['excedente_moto']];
			$excedentes += ['excedente_moto_mod'=>$this->excedente_moto_mod = $excedente['data_mod']??$configuracoes['excedente_moto_mod']];

			// UTILIÁRIO
			$sql = "
				SELECT * FROM cfig_diaria_exc_uti WHERE data_mod<? AND uid='".$this->uid."' ORDER BY cdeuid DESC LIMIT 1
			";
			$stmt = $this->conectar()->prepare($sql);
			$stmt->execute([$data]);
			$excedente = $stmt->fetch();
			$excedentes += ['excedente_utilitario'=>$this->excedente_utilitario = $excedente['preco']??$configuracoes['excedente_utilitario']];
			$excedentes += ['excedente_utilitario_mod'=>$this->excedente_utilitario_mod = $excedente['data_mod']??$configuracoes['excedente_utilitario_mod']];

			return $this->excedentes = $excedentes;
		} // DiariaExcedenteData

		public function Configuracoes() {
			$configuracoes = [];

			// HORÁRIO INÍCIO EXPEDIENTE
			$sql = "
				SELECT cfig_exp_inicio.hora AS hora_inicio_expediente, cfig_exp_inicio.data_mod AS hora_inicio_expediente_mod FROM cfig_exp_inicio WHERE uid='".$this->uid."' ORDER BY cexin DESC LIMIT 1
			";
			$stmt = $this->conectar()->prepare($sql);
			$stmt->execute();
			$config = $stmt->fetch();
			$configuracoes += ['hora_inicio_expediente'=>$this->hora_inicio_expediente = $config['hora_inicio_expediente']??0];
			$configuracoes += ['hora_inicio_expediente_mod'=>$this->hora_inicio_expediente_mod = $config['hora_inicio_expediente_mod']??0];

			// HORÁRIO ENCERRAMENTO EXPEDIENTE
			$sql = "
				SELECT cfig_exp_encerramento.hora AS hora_encerramento_expediente, cfig_exp_encerramento.data_mod AS hora_encerramento_expediente_mod FROM cfig_exp_encerramento WHERE uid='".$this->uid."' ORDER BY cexen DESC LIMIT 1
			";
			$stmt = $this->conectar()->prepare($sql);
			$stmt->execute();
			$config = $stmt->fetch();
			$configuracoes += ['hora_encerramento_expediente'=>$this->hora_encerramento_expediente = $config['hora_encerramento_expediente']??0];
			$configuracoes += ['hora_encerramento_expediente_mod'=>$this->hora_encerramento_expediente_mod = $config['hora_encerramento_expediente_mod']??0];

			// DURAÇÃO INTERVALO AGENDAMENTO
			$sql = "
				SELECT cfig_exp_intervalo.minutos AS duracao_intervalo_agendamento, cfig_exp_intervalo.data_mod AS duracao_intervalo_agendamento_mod FROM cfig_exp_intervalo WHERE uid='".$this->uid."' ORDER BY cexint DESC LIMIT 1
			";
			$stmt = $this->conectar()->prepare($sql);
			$stmt->execute();
			$config = $stmt->fetch();
			$configuracoes += ['duracao_intervalo_agendamento'=>$this->duracao_intervalo_agendamento = $config['duracao_intervalo_agendamento']??0];
			$configuracoes += ['duracao_intervalo_agendamento_mod'=>$this->duracao_intervalo_agendamento_mod = $config['duracao_intervalo_agendamento_mod']??0];

			// DIÁRIA CARRO ASSOCIADO
			$sql = "
				SELECT cfig_diaria_as.preco AS preco_diaria_associado, cfig_diaria_as.data_mod AS preco_diaria_associado_mod FROM cfig_diaria_as WHERE uid='".$this->uid."' ORDER BY cdasid DESC LIMIT 1
			";
			$stmt = $this->conectar()->prepare($sql);
			$stmt->execute();
			$config = $stmt->fetch();
			$configuracoes += ['preco_diaria_associado'=>$this->preco_diaria_associado = $config['preco_diaria_associado']??0];
			$configuracoes += ['preco_diaria_associado_mod'=>$this->preco_diaria_associado_mod = $config['preco_diaria_associado_mod']??0];

			// DIÁRIA MOTO ASSOCIADO
			$sql = "
				SELECT cfig_diaria_as_moto.preco AS preco_diaria_moto_associado, cfig_diaria_as_moto.data_mod AS preco_diaria_moto_associado_mod FROM cfig_diaria_as_moto WHERE uid='".$this->uid."' ORDER BY cdasmid DESC LIMIT 1
			";
			$stmt = $this->conectar()->prepare($sql);
			$stmt->execute();
			$config = $stmt->fetch();
			$configuracoes += ['preco_diaria_moto_associado'=>$this->preco_diaria_moto_associado = $config['preco_diaria_moto_associado']??0];
			$configuracoes += ['preco_diaria_moto_associado_mod'=>$this->preco_diaria_moto_associado_mod = $config['preco_diaria_moto_associado_mod']??0];

			// DIÁRIA UTILITARIO ASSOCIADO
			$sql = "
				SELECT cfig_diaria_as_utilitario.preco AS preco_diaria_utilitario_associado, cfig_diaria_as_utilitario.data_mod AS preco_utilitario_moto_associado_mod FROM cfig_diaria_as_utilitario WHERE uid='".$this->uid."' ORDER BY cdasuid DESC LIMIT 1
			";
			$stmt = $this->conectar()->prepare($sql);
			$stmt->execute();
			$config = $stmt->fetch();
			$configuracoes += ['preco_diaria_utilitario_associado'=>$this->preco_diaria_utilitario_associado = $config['preco_diaria_utilitario_associado']??0];
			$configuracoes += ['preco_diaria_utilitario_associado_mod'=>$this->preco_diaria_utilitario_associado_mod = $config['preco_diaria_utilitario_associado_mod']??0];

			// DIAS ACIONAMENTO ANO
			$sql = "
				SELECT cfig_dias_ac.dias AS dias_por_acionamento, cfig_dias_ac.data_mod AS dias_por_acionamento_mod FROM cfig_dias_ac WHERE uid='".$this->uid."' ORDER BY cdacid DESC LIMIT 1
			";
			$stmt = $this->conectar()->prepare($sql);
			$stmt->execute();
			$config = $stmt->fetch();
			$configuracoes += ['dias_por_acionamento'=>$this->dias_por_acionamento = $config['dias_por_acionamento']??0];
			$configuracoes += ['dias_por_acionamento_mod'=>$this->dias_por_acionamento_mod = $config['dias_por_acionamento_mod']??0];

			// DIAS DE CORTESIA DA PLACA ANO
			$sql = "
				SELECT cfig_dias_pla.dias AS dias_cortesia_placa_ano, cfig_dias_pla.data_mod AS dias_cortesia_placa_ano_mod FROM cfig_dias_pla WHERE uid='".$this->uid."' ORDER BY cdplid DESC LIMIT 1
			";
			$stmt = $this->conectar()->prepare($sql);
			$stmt->execute();
			$config = $stmt->fetch();
			$configuracoes += ['dias_cortesia_placa_ano'=>$this->dias_cortesia_placa_ano = $config['dias_cortesia_placa_ano']??0];
			$configuracoes += ['dias_cortesia_placa_ano_mod'=>$this->dias_cortesia_placa_ano_mod = $config['dias_cortesia_placa_ano_mod']??0];

			// DIAS DE CORTESIA DA PLACA MES
			$sql = "
				SELECT cfig_dias_pla_mes.dias AS dias_cortesia_placa_mes, cfig_dias_pla_mes.data_mod AS dias_cortesia_placa_mes_mod FROM cfig_dias_pla_mes WHERE uid='".$this->uid."' ORDER BY cdplmid DESC LIMIT 1
			";
			$stmt = $this->conectar()->prepare($sql);
			$stmt->execute();
			$config = $stmt->fetch();
			$configuracoes += ['dias_cortesia_placa_mes'=>$this->dias_cortesia_placa_mes = $config['dias_cortesia_placa_mes']??0];
			$configuracoes += ['dias_cortesia_placa_mes_mod'=>$this->dias_cortesia_placa_mes_mod = $config['dias_cortesia_placa_mes_mod']??0];

			// PRECO KM EXCEDENTE
			$sql = "
				SELECT cfig_preco_km.preco AS preco_km, cfig_preco_km.data_mod AS preco_km_mod FROM cfig_preco_km WHERE uid='".$this->uid."' ORDER BY cpkmid DESC LIMIT 1
			";
			$stmt = $this->conectar()->prepare($sql);
			$stmt->execute();
			$config = $stmt->fetch();
			$configuracoes += ['preco_km'=>$this->preco_km = $config['preco_km']??0];
			$configuracoes += ['preco_km_mod'=>$this->preco_km_mod = $config['preco_km_mod']??0];

			// PRECO LIMPEZA EXECUTIVA
			$sql = "
				SELECT cfig_preco_le.preco AS preco_le, cfig_preco_le.data_mod AS preco_le_mod FROM cfig_preco_le WHERE uid='".$this->uid."' ORDER BY cpleid DESC LIMIT 1
			";
			$stmt = $this->conectar()->prepare($sql);
			$stmt->execute();
			$config = $stmt->fetch();
			$configuracoes += ['preco_le'=>$this->preco_le = $config['preco_le']??0];
			$configuracoes += ['preco_le_mod'=>$this->preco_le_mod = $config['preco_le_mod']??0];

			// PRECO LIMPEZA COMPLETA
			$sql = "
				SELECT cfig_preco_lc.preco AS preco_lc, cfig_preco_lc.data_mod AS preco_lc_mod FROM cfig_preco_lc WHERE uid='".$this->uid."' ORDER BY cplcid DESC LIMIT 1
			";
			$stmt = $this->conectar()->prepare($sql);
			$stmt->execute();
			$config = $stmt->fetch();
			$configuracoes += ['preco_lc'=>$this->preco_lc = $config['preco_lc']??0];
			$configuracoes += ['preco_lc_mod'=>$this->preco_lc_mod = $config['preco_lc_mod']??0];

			// PRECO LIMPEZA COMPLETA MOTOR
			$sql = "
				SELECT cfig_preco_lm.preco AS preco_lm, cfig_preco_lm.data_mod AS preco_lm_mod FROM cfig_preco_lm WHERE uid='".$this->uid."' ORDER BY cplmid DESC LIMIT 1
			";
			$stmt = $this->conectar()->prepare($sql);
			$stmt->execute();
			$config = $stmt->fetch();
			$configuracoes += ['preco_lm'=>$this->preco_lm = $config['preco_lm']??0];
			$configuracoes += ['preco_lm_mod'=>$this->preco_lm_mod = $config['preco_lm_mod']??0];

			// MINUTOS TOLERANCIA DEVOLUCAO
			$sql = "
				SELECT cfig_tol_dev.minutos AS min_tolerancia, cfig_tol_dev.data_mod AS min_tolerancia_mod FROM cfig_tol_dev WHERE uid='".$this->uid."' ORDER BY ctdid DESC LIMIT 1
			";
			$stmt = $this->conectar()->prepare($sql);
			$stmt->execute();
			$config = $stmt->fetch();
			$configuracoes += ['min_tolerancia'=>$this->min_tolerancia = $config['min_tolerancia']??0];
			$configuracoes += ['min_tolerancia_mod'=>$this->min_tolerancia_mod = $config['min_tolerancia_mod']??0];

			// KM PARA REVISAO CARRO ANTES LIMIAR
			$sql = "
				SELECT cfig_rev_car_prev.km AS rev_car_prev, cfig_rev_car_prev.data_mod AS rev_car_prev_mod FROM cfig_rev_car_prev WHERE uid='".$this->uid."' ORDER BY crvpcid DESC LIMIT 1
			";
			$stmt = $this->conectar()->prepare($sql);
			$stmt->execute();
			$config = $stmt->fetch();
			$configuracoes += ['rev_car_prev'=>$this->rev_car_prev = $config['rev_car_prev']??0];
			$configuracoes += ['rev_car_prev_mod'=>$this->rev_car_prev_mod = $config['rev_car_prev_mod']??0];

			// KM PARA REVISAO CARRO APOS LIMIAR
			$sql = "
				SELECT cfig_rev_car_apos.km AS rev_car_apos, cfig_rev_car_apos.data_mod AS rev_car_apos_mod FROM cfig_rev_car_apos WHERE uid='".$this->uid."' ORDER BY crvacid DESC LIMIT 1
			";
			$stmt = $this->conectar()->prepare($sql);
			$stmt->execute();
			$config = $stmt->fetch();
			$configuracoes += ['rev_car_apos'=>$this->rev_car_apos = $config['rev_car_apos']??0];
			$configuracoes += ['rev_car_apos_mod'=>$this->rev_car_apos_mod = $config['rev_car_apos_mod']??0];

			// KM LIMIAR PARA REVISAO CARRO
			$sql = "
				SELECT cfig_rev_car_limiar.km AS rev_car_limiar, cfig_rev_car_limiar.data_mod AS rev_car_limiar_mod FROM cfig_rev_car_limiar WHERE uid='".$this->uid."' ORDER BY crclid DESC LIMIT 1
			";
			$stmt = $this->conectar()->prepare($sql);
			$stmt->execute();
			$config = $stmt->fetch();
			$configuracoes += ['rev_car_limiar'=>$this->rev_car_limiar = $config['rev_car_limiar']??0];
			$configuracoes += ['rev_car_limiar_mod'=>$this->rev_car_limiar_mod = $config['rev_car_limiar_mod']??0];

			// KM PARA REVISAO MOTO
			$sql = "
				SELECT cfig_rev_moto.km AS rev_moto, cfig_rev_moto.data_mod AS rev_moto_mod FROM cfig_rev_moto WHERE uid='".$this->uid."' ORDER BY crmid DESC LIMIT 1
			";
			$stmt = $this->conectar()->prepare($sql);
			$stmt->execute();
			$config = $stmt->fetch();
			$configuracoes += ['rev_moto'=>$this->rev_moto = $config['rev_moto']??0];
			$configuracoes += ['rev_moto_mod'=>$this->rev_moto_mod = $config['rev_moto_mod']??0];

			// NOTIFICACOES REVISAO
			$sql = "
				SELECT cfig_rev.ativa AS rev_ativa, cfig_rev.data_mod AS rev_mod FROM cfig_rev WHERE uid='".$this->uid."' ORDER BY crvid DESC LIMIT 1
			";
			$stmt = $this->conectar()->prepare($sql);
			$stmt->execute();
			$config = $stmt->fetch();
			$configuracoes += ['rev_ativa'=>$this->rev_ativa = $config['rev_ativa']??0];
			$configuracoes += ['rev_mod'=>$this->rev_mod = $config['rev_mod']??0];

			// PRECO PADRAO CAUCAO
			$sql = "
				SELECT cfig_caucao.preco AS caucao_preco, cfig_caucao.data_mod AS caucao_mod FROM cfig_caucao WHERE uid='".$this->uid."' ORDER BY ccpid DESC LIMIT 1
			";
			$stmt = $this->conectar()->prepare($sql);
			$stmt->execute();
			$config = $stmt->fetch();
			$configuracoes += ['caucao_preco'=>$this->caucao_preco = $config['caucao_preco']??0];
			$configuracoes += ['caucao_mod'=>$this->caucao_mod = $config['caucao_mod']??0];

			// DIARIA EXCEDENTE MOTO
			$sql = "
				SELECT cfig_diaria_exc_moto.preco AS excedente_moto, cfig_diaria_exc_moto.data_mod AS excedente_moto_mod FROM cfig_diaria_exc_moto WHERE uid='".$this->uid."' ORDER BY cdemid DESC LIMIT 1
			";
			$stmt = $this->conectar()->prepare($sql);
			$stmt->execute();
			$config = $stmt->fetch();
			$configuracoes += ['excedente_moto'=>$this->excedente_moto = $config['excedente_moto']??0];
			$configuracoes += ['excedente_moto_mod'=>$this->excedente_moto_mod = $config['excedente_moto_mod']??0];

			// DIARIA EXCEDENTE CARRO
			$sql = "
				SELECT cfig_diaria_exc_carro.preco AS excedente_carro, cfig_diaria_exc_carro.data_mod AS excedente_carro_mod FROM cfig_diaria_exc_carro WHERE uid='".$this->uid."' ORDER BY cdecid DESC LIMIT 1
			";
			$stmt = $this->conectar()->prepare($sql);
			$stmt->execute();
			$config = $stmt->fetch();
			$configuracoes += ['excedente_carro'=>$this->excedente_carro = $config['excedente_carro']??0];
			$configuracoes += ['excedente_carro_mod'=>$this->excedente_carro_mod = $config['excedente_carro_mod']??0];

			// DIARIA EXCEDENTE UTILIÁRIO
			$sql = "
				SELECT cfig_diaria_exc_uti.preco AS excedente_utilitario, cfig_diaria_exc_uti.data_mod AS excedente_utilitario_mod FROM cfig_diaria_exc_uti WHERE uid='".$this->uid."' ORDER BY cdeuid DESC LIMIT 1
			";
			$stmt = $this->conectar()->prepare($sql);
			$stmt->execute();
			$config = $stmt->fetch();
			$configuracoes += ['excedente_utilitario'=>$this->excedente_utilitario = $config['excedente_utilitario']??0];
			$configuracoes += ['excedente_utilitario_mod'=>$this->excedente_utilitario_mod = $config['excedente_utilitario_mod']??0];

			return $this->configuracoes = $configuracoes;
		} // Configuracoes

	} // class ConsultaDatabase

?>
