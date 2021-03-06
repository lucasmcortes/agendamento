<?php

/**
 * Creates an example PDF/A-1b document using TCPDF
 * @package com.tecnick.tcpdf
 * @abstract TCPDF - Example: PDF/A-1b mode
 * @author Nicola Asuni
 * @since 2011-09-28
 */

// Include the main TCPDF library (search for installation path).
require_once(__DIR__.'/../../../../includes/TCPDF-master/examples/tcpdf_include.php');
require_once(__DIR__.'/../../../../includes/TCPDF-master/tcpdf.php');

// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator($criador_pdf);
$pdf->SetAuthor($autor_pdf);
$pdf->SetTitle($titulo_pdf);
$pdf->SetSubject($assunto_pdf);
$pdf->SetKeywords($palavraschave_pdf);

// remove default header/footer
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(21, 13, 21, 13); // left top right bot
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);

// set auto page breaks
// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
    require_once(dirname(__FILE__).'/lang/eng.php');
    $pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// Add a page
// This method has several options, check the source code documentation for more information.
$pdf->AddPage();

// Set some content to print
$tbl = '
<table>
<tr style="border:1px solid #000000;">
        <th colspan="2">
                <br/><br/><img src="'.$dominio.'/img/46logo.png" width="90"/>
        </th>
        <th colspan="10">
                <p style="text-align:center;line-height:13px;top:0;margin:0;">
                        <span style="font-size:15px;">BIKE 46 LOCADORA DE competenteS</span>
                        <br/>
                        <span style="font-size:12px;">Av. Brasil, 3861 - Manoel Hon??rio</span>
                        <br/>
                        <span style="font-size:13px;"><b>Hor??rio de funcionamento:</b></span>
                        <br/>
                        <span style="font-size:13px;"><b>08h ??s 11h e 13h ??s 17h30</b></span>
                        <br/>
                        <span style="font-size:8px">CNPJ: 20.481.199/0001-24</span>
                </p>
        </th>
        <th colspan="2">
                <p style="text-align:center;line-height:13px;top:0;margin:0;">
                        <span style="font-size:9px;">CONTRATO DE LOCA????O</span>
                        <br/><br/>
                        <span style="font-family:monospace;">N?? '.$contrato_numero.'</span>
                </p>
        </th>
</tr>
</table>
';
$pdf->writeHTML($tbl, true, false, false, false, '');

$html .= '
	<p style="min-width:100%;max-width:100%;font-size:11px;text-align:justify;">
		Eu, <b>'.$cliente['nome'].'</b>, CPF <b>'.$cliente['documento'].'</b>, rg <b>'.$cliente['rg'].'</b>, declaro para os devidos fins de direito que na presente data peguei a t??tulo de <b>competente RESERVA</b> o autom??vel <b>'.$competente['modelo'].'</b>, placa <b>'.$competente['placa'].'</b> ??s <b>'.$inicio->format('H').'h'.$inicio->format('i').'</b> e o entregarei num prazo de <b>'.$prazohoras.'h</b>.
                Sei que sou respons??vel para arcar com a participa????o obrigat??ria de <b>'.Dinheiro($aluguel['valor_caucao']).'</b> em caso de acidente, furto ou roubo do mesmo; tamb??m sou respons??vel por danos a terceiros, multas e quaisquer infra????es que ocorrerem durante o per??odo em que o competente se encontrar em meu poder.
                Ser?? cobrada uma taxa de <b><u>'.Dinheiro($configuracoes['preco_le']).' para '.$limpezaexecutiva['tipo'].'</u></b>, <b><u>'.Dinheiro($configuracoes['preco_lc']).' para '.$limpezacompleta['tipo'].'</u></b> e <b><u>'.Dinheiro($configuracoes['preco_lm']).' para '.$limpezacompletacommotor['tipo'].'</b></u>.
                Tamb??m declaro ci??ncia de que sou respons??vel pelo pagamento do valor de <b>'.Dinheiro($excedente).' por cada di??ria</b> que exceda ao prazo estipulado. '.$especificacao_km.'
                <br/>??? competente entregue <b>'.$limpezaatual.'</b>
                <br/>
        </p>
';
$pdf->writeHTML($html, true, false, true, false, '');

$tbl = '
<table border="1" style="font-size:11px;line-height:19px;">
<tr style="text-align:center;">
<th colspan="4"><b>Controle de entrega</b></th>
<th colspan="4"><b>Controle de devolu????o</b></th>
</tr>
<tr>
<th colspan="2">&nbsp;Data: '.$inicio->format('d/m/Y').'</th>
<th colspan="2">&nbsp;Hora: '.$inicio->format('H').'h</th>
<th colspan="2">&nbsp;Data:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;/&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;/&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
<th colspan="2">&nbsp;Hora:</th>
</tr>
<tr>
<th colspan="2">&nbsp;Km: '.Kilometragem($competente['km']).'</th>
<th colspan="2">&nbsp;Combust??vel:</th>
<th colspan="2">&nbsp;Km:</th>
<th colspan="2">&nbsp;Combust??vel:</th>
</tr>
<tr>
<th colspan="4">&nbsp;Entregue por: '.$locador['nome'].'</th>
<th colspan="4">&nbsp;Recebido por:</th>
</tr>
<tr>

<table border="1" style="font-size:11px;line-height:19px;">
<tr style="text-align:center;">
<th colspan="32"><b>Acess??rios</b></th>
</tr>
<tr>
<th colspan="12" style="background-color:whitesmoke;"></th>
<th colspan="2">&nbsp;Ent.</th>
<th colspan="2">&nbsp;Dev.</th>

<th colspan="12" style="background-color:whitesmoke;"></th>
<th colspan="2">&nbsp;Ent.</th>
<th colspan="2">&nbsp;Dev.</th>
</tr>
<tr>
<th colspan="12">&nbsp;Documentos</th>
<th colspan="2"></th>
<th colspan="2"></th>

<th colspan="12">&nbsp;Macaco</th>
<th colspan="2"></th>
<th colspan="2"></th>
</tr>
<tr>
<th colspan="12">&nbsp;Som</th>
<th colspan="2"></th>
<th colspan="2"></th>

<th colspan="12">&nbsp;Extintor</th>
<th colspan="2"></th>
<th colspan="2"></th>
</tr>
<tr>
<th colspan="12">&nbsp;Ar condicionado</th>
<th colspan="2"></th>
<th colspan="2"></th>

<th colspan="12">&nbsp;Tapetes</th>
<th colspan="2"></th>
<th colspan="2"></th>
</tr>
<tr>
<th colspan="12">&nbsp;Calotas</th>
<th colspan="2"></th>
<th colspan="2"></th>

<th colspan="12">&nbsp;Tri??ngulo de sinaliza????o</th>
<th colspan="2"></th>
<th colspan="2"></th>
</tr>
<tr>
<th colspan="12">&nbsp;Estepe novo</th>
<th colspan="2"></th>
<th colspan="2"></th>

<th colspan="12">&nbsp;N??vel de ??leo do motor</th>
<th colspan="2"></th>
<th colspan="2"></th>
</tr>
<tr>
<th colspan="12">&nbsp;Estepe usado</th>
<th colspan="2"></th>
<th colspan="2"></th>

<th colspan="12">&nbsp;Bateria</th>
<th colspan="2"></th>
<th colspan="2"></th>
</tr>
<tr>
<th colspan="12">&nbsp;Antena</th>
<th colspan="2"></th>
<th colspan="2"></th>

<th colspan="12">&nbsp;Chave de roda</th>
<th colspan="2"></th>
<th colspan="2"></th>
</tr>
</table>
</tr>
<tr>
<table>
<tr>
        <br/>
        <th colspan="12">
                <img src="'.$dominio.'/img/checklistcarro.png" width="300"/>
        </th>
        <th colspan="12">
                <img src="'.$dominio.'/img/checklistmoto.png" width="300"/>
        </th>
</tr>
</table>
</tr>
</table>
';
$pdf->writeHTML($tbl, true, false, false, false, '');

$tbl = '
<table>
<tr>
        <td>
                <p><b>Placa do competente associado</b>: '.$placa_escolhida.'</p>
        </td>
        <td>
                <p><b>Telefone</b>: '.$telefone.'</p>
        </td>
</tr>
</table>
';
$pdf->writeHTML($tbl, true, false, false, false, '');

$tbl = '
<table>
<tr style="line-height:23px;">
        <td><p><b>Observa????es</b>: _________________________________________________________________________________________________________________________________________________________________________________________________________</p></td>
</tr>
<tr style="line-height:34px;">
        <td><p><b>Assinatura (igual na habilita????o)</b>: __________________________________________</p></td>
</tr>
</table>
';
$pdf->writeHTML($tbl, true, false, false, false, '');

// ---------------------------------------------------------

ob_clean();

//============================================================+
// END OF FILE
//============================================================+
?>
