<div class='optionswrap'>
	<div class='optionsinnerwrap'>
		<?php
			$permissao = new Conforto($uid);
			$permissao = $permissao->Permissao('modificacao');
			if ($permissao===true) {
				BotaoPainel('relatório geral','relatoriogeral','competentes/relatorio/geral');
				BotaoPainel('administradores','administradores','administradores');
				BotaoPainel('configurações','configuracoes','configuracoes');
			} // permitido
			
			BotaoPainel('clientes','clientes','clientes');
		 ?>
	</div> <!-- optionsinnerwrap -->
</div> <!-- optionswrap -->
<div class='optionswrap'>
	<div class='optionsinnerwrap'>
		<?php BotaoPainel('aluguéis','alugueis','alugueis'); ?>
		<?php BotaoPainel('reservas','reservas','reservas'); ?>
		<?php BotaoPainel('devoluções','devolucoes','devolucoes'); ?>
		<?php BotaoPainel('cobranças','cobrancas','cobrancas/aberto'); ?>
	</div> <!-- optionsinnerwrap -->
</div> <!-- optionswrap -->
<div class='optionswrap'>
	<div class='optionsinnerwrap'>
		<?php BotaoPainel('competentes','competentes','competentes'); ?>
		<?php BotaoPainel('manutenções','manutencoes','manutencoes'); ?>
		<?php BotaoPainel('retornos','retornos','retornos'); ?>
		<?php BotaoPainel('despesas','despesas','despesas'); ?>
	</div> <!-- optionsinnerwrap -->
</div> <!-- optionswrap -->
