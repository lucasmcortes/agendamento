<?php

	class UpdateRow extends Conexao {

		public function UpdateTeste($cid,$tid,$info) {
			try {
				$stmt = "UPDATE testando SET teste_data=?  WHERE teste_id=? LIMIT 1";
				$stmt = $this->conectar()->prepare($stmt);
				$stmt->execute([$cid,$tid]);
				$stmt = "UPDATE testando SET teste_info=?  WHERE teste_id=? LIMIT 1";
				$stmt = $this->conectar()->prepare($stmt);
				$stmt->execute([$info,$tid]);
				return true;
			} catch(PDOException $erro) {
				return $erro->getMessage();
			}
		} // UpdateTeste

		public function UpdateSenha($senha,$email) {
			try {
				$stmt = "UPDATE cadastros SET senha=?  WHERE email=? LIMIT 1";
				$stmt = $this->conectar()->prepare($stmt);
				$stmt->execute([$senha,$email]);
				return true;
			} catch(PDOException $erro) {
				return $erro->getMessage();
			}
		} // UpdateSenha

		public function UpdateNivel($nivel,$uid) {
			try {
				$stmt = "UPDATE cadastro_nivel SET nivel=?  WHERE uid=? LIMIT 1";
				$stmt = $this->conectar()->prepare($stmt);
				$stmt->execute([$nivel,$uid]);
				return true;
			} catch(PDOException $erro) {
				return $erro->getMessage();
			}
		} // UpdateNivel

		public function RemoveEndereco($eid) {
			try {
				$stmt = "UPDATE endereco SET ativo=0 WHERE eid=? LIMIT 1";
				$stmt = $this->conectar()->prepare($stmt);
				$stmt->execute([$eid]);
				return true;
			} catch(PDOException $erro) {
				return $erro->getMessage();
			}
		} // RemoveEndereco

		//////////////////////////////////////////////////////
		//////////////////////////////////////////////////////
		// PAGAMENTO
		//////////////////////////////////////////////////////
		//////////////////////////////////////////////////////

		public function CancelaPlano($licid) {
			try {
				$stmt = "UPDATE licencas SET status='Cancelada' WHERE licid=? LIMIT 1";
				$stmt = $this->conectar()->prepare($stmt);
				$stmt->execute([$licid]);
				return true;
			} catch(PDOException $erro) {
				return $erro->getMessage();
			}
		} // CancelaPlano

		public function CancelarBoleto($pagbid) {
			try {
				$stmt = "UPDATE pagamento_boleto_pagseguro SET status='CANCELADO' WHERE pagbid=? LIMIT 1";
				$stmt = $this->conectar()->prepare($stmt);
				$stmt->execute([$pagbid]);
				return true;
			} catch(PDOException $erro) {
				return $erro->getMessage();
			}
		} // CancelarBoleto

		//////////////////////////////////////////////////////
		//////////////////////////////////////////////////////
		// AGENDAMENTO
		//////////////////////////////////////////////////////
		//////////////////////////////////////////////////////

		public function UpdateHorarioAgendamento($horario,$aid) {
			try {
				$stmt = "UPDATE agendamento SET agendamento=?  WHERE aid=? LIMIT 1";
				$stmt = $this->conectar()->prepare($stmt);
				$stmt->execute([$horario,$aid]);
				return true;
			} catch(PDOException $erro) {
				return $erro->getMessage();
			}
		} // UpdateHorarioAgendamento

		public function UpdateCompetenteAgendamento($competente,$aid) {
			try {
				$stmt = "UPDATE agendamento SET vid=?  WHERE aid=? LIMIT 1";
				$stmt = $this->conectar()->prepare($stmt);
				$stmt->execute([$competente,$aid]);
				return true;
			} catch(PDOException $erro) {
				return $erro->getMessage();
			}
		} // UpdateCompetenteAgendamento

		public function DesmarcarAgendamento($aid) {
			try {
				$stmt = "UPDATE agendamento SET ativo=0 WHERE aid=? LIMIT 1";
				$stmt = $this->conectar()->prepare($stmt);
				$stmt->execute([$aid]);
				return true;
			} catch(PDOException $erro) {
				return $erro->getMessage();
			}
		} // DesmarcarAgendamento

		//////////////////////////////////////////////////////
		//////////////////////////////////////////////////////
		// ALUGUEL
		//////////////////////////////////////////////////////
		//////////////////////////////////////////////////////

		public function UpdateUserCPF($cpf,$uid) {
			try {
				$stmt = "UPDATE cadastros SET cpf=?  WHERE uid=? LIMIT 1";
				$stmt = $this->conectar()->prepare($stmt);
				$stmt->execute([$cpf,$uid]);
				return true;
			} catch(PDOException $erro) {
				return $erro->getMessage();
			}
		} // UpdateUserCPF

		public function UpdateUserEmail($email,$uid) {
			try {
				$stmt = "UPDATE cadastros SET email=?  WHERE uid=? LIMIT 1";
				$stmt = $this->conectar()->prepare($stmt);
				$stmt->execute([$email,$uid]);
				return true;
			} catch(PDOException $erro) {
				return $erro->getMessage();
			}
		} // UpdateUserEmail

		public function UpdateUserTelefone($telefone,$uid) {
			try {
				$stmt = "UPDATE cadastros SET telefone=?  WHERE uid=? LIMIT 1";
				$stmt = $this->conectar()->prepare($stmt);
				$stmt->execute([$telefone,$uid]);
				return true;
			} catch(PDOException $erro) {
				return $erro->getMessage();
			}
		} // UpdateUserTelefone

		public function UpdateGuid($guid,$aid) {
			try {
				$stmt = "UPDATE aluguel_guid SET guid=?  WHERE aid=? LIMIT 1";
				$stmt = $this->conectar()->prepare($stmt);
				$stmt->execute([$guid,$aid]);
				return true;
			} catch(PDOException $erro) {
				return $erro->getMessage();
			}
		} // UpdateGuid

		public function UpdateclienteNome($nome,$lid) {
			try {
				$stmt = "UPDATE cliente SET nome=?  WHERE lid=? LIMIT 1";
				$stmt = $this->conectar()->prepare($stmt);
				$stmt->execute([$nome,$lid]);
				return true;
			} catch(PDOException $erro) {
				return $erro->getMessage();
			}
		} // UpdateclienteNome

		public function Updatedocumento($rg,$lid) {
			try {
				$stmt = "UPDATE documento SET numero=?  WHERE lid=? LIMIT 1";
				$stmt = $this->conectar()->prepare($stmt);
				$stmt->execute([$rg,$lid]);
				return true;
			} catch(PDOException $erro) {
				return $erro->getMessage();
			}
		} // Updatedocumento

		public function UpdateOrgaoExpeditor($oexp,$numero) {
			try {
				$stmt = "UPDATE documento SET o_exp=?  WHERE numero=? LIMIT 1";
				$stmt = $this->conectar()->prepare($stmt);
				$stmt->execute([$oexp,$numero]);
				return true;
			} catch(PDOException $erro) {
				return $erro->getMessage();
			}
		} // UpdateOrgaoExpeditor

		public function UpdateclienteEmail($email,$lid) {
			try {
				$stmt = "UPDATE cliente SET email=?  WHERE lid=? LIMIT 1";
				$stmt = $this->conectar()->prepare($stmt);
				$stmt->execute([$email,$lid]);
				return true;
			} catch(PDOException $erro) {
				return $erro->getMessage();
			}
		} // UpdateclienteEmail

		public function UpdateclienteTelefone($telefone,$lid) {
			try {
				$stmt = "UPDATE cliente SET telefone=?  WHERE lid=? LIMIT 1";
				$stmt = $this->conectar()->prepare($stmt);
				$stmt->execute([$telefone,$lid]);
				return true;
			} catch(PDOException $erro) {
				return $erro->getMessage();
			}
		} // UpdateclienteTelefone

		public function UpdateReservaConfirmacao($confirmada,$aid) {
			try {
				$stmt = "UPDATE reserva SET confirmada=?  WHERE aid=?";
				$stmt = $this->conectar()->prepare($stmt);
				$stmt->execute([$confirmada,$aid]);
				return true;
			} catch(PDOException $erro) {
				return $erro->getMessage();
			}
		} // UpdateReservaConfirmacao

		public function UpdateReservaManutencaoConfirmacao($confirmada,$mid) {
			try {
				$stmt = "UPDATE manutencao_reserva SET confirmada=?  WHERE mid=?";
				$stmt = $this->conectar()->prepare($stmt);
				$stmt->execute([$confirmada,$mid]);
				return true;
			} catch(PDOException $erro) {
				return $erro->getMessage();
			}
		} // UpdateReservaManutencaoConfirmacao

		public function UpdatecompetenteDoAluguel($vid,$aid) {
			try {
				$stmt = "UPDATE aluguel SET vid=?  WHERE aid=?";
				$stmt = $this->conectar()->prepare($stmt);
				$stmt->execute([$vid,$aid]);
				return true;
			} catch(PDOException $erro) {
				return $erro->getMessage();
			}
		} // UpdatecompetenteDoAluguel

		public function UpdatecompetenteRenavam($renavam,$vid) {
			try {
				$stmt = "UPDATE competente SET renavam=?  WHERE vid=? LIMIT 1";
				$stmt = $this->conectar()->prepare($stmt);
				$stmt->execute([$renavam,$vid]);
				return true;
			} catch(PDOException $erro) {
				return $erro->getMessage();
			}
		} // UpdatecompetenteRenavam

		public function UpdatecompetenteChassi($chassi,$vid) {
			try {
				$stmt = "UPDATE competente SET chassi=?  WHERE vid=? LIMIT 1";
				$stmt = $this->conectar()->prepare($stmt);
				$stmt->execute([$chassi,$vid]);
				return true;
			} catch(PDOException $erro) {
				return $erro->getMessage();
			}
		} // UpdatecompetenteChassi

		public function UpdatecompetentePlaca($placa,$vid) {
			try {
				$stmt = "UPDATE competente SET placa=?  WHERE vid=? LIMIT 1";
				$stmt = $this->conectar()->prepare($stmt);
				$stmt->execute([$placa,$vid]);
				return true;
			} catch(PDOException $erro) {
				return $erro->getMessage();
			}
		} // UpdatecompetentePlaca

		public function UpdatecompetenteAno($ano,$vid) {
			try {
				$stmt = "UPDATE competente SET ano=?  WHERE vid=? LIMIT 1";
				$stmt = $this->conectar()->prepare($stmt);
				$stmt->execute([$ano,$vid]);
				return true;
			} catch(PDOException $erro) {
				return $erro->getMessage();
			}
		} // UpdatecompetenteAno

		public function UpdatecompetenteCor($cor,$vid) {
			try {
				$stmt = "UPDATE competente SET cor=?  WHERE vid=? LIMIT 1";
				$stmt = $this->conectar()->prepare($stmt);
				$stmt->execute([$cor,$vid]);
				return true;
			} catch(PDOException $erro) {
				return $erro->getMessage();
			}
		} // UpdatecompetenteCor

		public function UpdatecompetenteCategoria($categoria,$vid) {
			try {
				$stmt = "UPDATE competente SET categoria=?  WHERE vid=? LIMIT 1";
				$stmt = $this->conectar()->prepare($stmt);
				$stmt->execute([$categoria,$vid]);
				return true;
			} catch(PDOException $erro) {
				return $erro->getMessage();
			}
		} // UpdatecompetenteCategoria

		public function UpdatecompetentePotencia($potencia,$vid) {
			try {
				$stmt = "UPDATE competente SET potencia=?  WHERE vid=? LIMIT 1";
				$stmt = $this->conectar()->prepare($stmt);
				$stmt->execute([$potencia,$vid]);
				return true;
			} catch(PDOException $erro) {
				return $erro->getMessage();
			}
		} // UpdatecompetentePotencia

		public function UpdatecompetenteMarca($marca,$vid) {
			try {
				$stmt = "UPDATE competente SET marca=?  WHERE vid=? LIMIT 1";
				$stmt = $this->conectar()->prepare($stmt);
				$stmt->execute([$marca,$vid]);
				return true;
			} catch(PDOException $erro) {
				return $erro->getMessage();
			}
		} // UpdatecompetenteMarca

		public function UpdatecompetenteModelo($modelo,$vid) {
			try {
				$stmt = "UPDATE competente SET modelo=?  WHERE vid=? LIMIT 1";
				$stmt = $this->conectar()->prepare($stmt);
				$stmt->execute([$modelo,$vid]);
				return true;
			} catch(PDOException $erro) {
				return $erro->getMessage();
			}
		} // UpdatecompetenteModelo

		public function UpdatecompetenteAtivo($ativo,$vid) {
			try {
				$stmt = "UPDATE competente SET ativo=?  WHERE vid=? LIMIT 1";
				$stmt = $this->conectar()->prepare($stmt);
				$stmt->execute([$ativo,$vid]);
				return true;
			} catch(PDOException $erro) {
				return $erro->getMessage();
			}
		} // UpdatecompetenteAtivo

	} // class updaterow

?>
