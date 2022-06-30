<?php

include __DIR__.'/../../../../includes/setup.inc.php';

unset($_SESSION['img_rg_info_session']);
$uploaded_file_name = $_POST['uploaded_file_name'];

$fileName = $_FILES['img_rg']['name'];
$fileTmpLoc = $_FILES['img_rg']['tmp_name'];
$fileType = $_FILES['img_rg']['type'];
$fileSize = $_FILES['img_rg']['size'];
$fileErrorMsg = $_FILES['img_rg']['error'];
if (!$fileTmpLoc) {
        echo 'Erro';
        exit();
}

// NOMES DO ARQUIVO QUE VEM UPLOADED
// o diretório alvo é o próprio diretório onde fica esse aquivo aqui
$target_dir = __DIR__.'/../temp/';

// pega o tipo do arquivo pra colocar a extensão certa
$extensao_img = '.'.str_replace(array('image/','application/'), '', $fileType);

// url completo do arquivo na pasta temporária
// o endereço do do arquivo completo, com o diretório, tmp_name e a extensão
$nome_completo_arquivo = microtime(true).'-'.$uploaded_file_name;
$arquivo_url_completo = $target_dir.$nome_completo_arquivo;

// coloca as informações do arquivo nesse array
$img_rg_info = array (
    'img_rg_nome_completo' => $nome_completo_arquivo,
    'img_rg_extensao' => $extensao_img,
    'img_rg_mime' => $fileType,
    'img_rg_path' => $target_dir,
    'img_rg_url_completo' => $arquivo_url_completo
);
// e na session
$_SESSION['img_rg_info_session'] = $img_rg_info;

if (move_uploaded_file($fileTmpLoc, $arquivo_url_completo)) {
        $resultimgimg_rg = "
        <!-- rg_img_result_wrap -->
        <div id='rg_img_result_wrap' style='min-width:100%;max-width:100%;text-align:center;position:relative;'>
                <div style='position:absolute;right:0;top:3%;'>
                        <img id='remove_img_rg' alt='remover' title='remover' style='width:26px;height:auto;margin:0 auto;cursor:pointer;' src='".$dominio."/img/red-x.svg'></img>
                </div>
                <p style='min-width:100%;max-width:100%;display:inline-block;'>
                Arquivo recebido
        </p>
        <div style='min-width:100%;max-width:100%;display:inline-block;'>
        ";

        if ($extensao_img=='.pdf') {
                $resultimgimg_rg .= "
                        <iframe id='docimg' src='".$dominio."/painel/clientes/novo/temp/".$nome_completo_arquivo."' style='width:100%;auto;'></iframe>
                ";
        } else {
                $resultimgimg_rg .= "
                        <img id='docimg' style='max-width:222px;width:100%;height:auto;' src='".$dominio."/painel/clientes/novo/temp/".$nome_completo_arquivo."'></img>
                ";
        }

        $resultimgimg_rg .= "
                </div>
        </div> <!-- rg_img_result_wrap -->
        ";
} else {
       $resultimgimg_rg = "
       <!-- rg_img_result_wrap -->
       <div id='rg_img_result_wrap' style='min-width:100%;max-width:100%;text-align:center;'>
               <p style='min-width:100%;max-width:100%;display:inline-block;'>
                        Erro no upload.
               </p>
               <p id='remove_img_rg' style='min-width:100%;max-width:100%;display:inline-block;cursor:pointer;'>
                        Clique aqui para tentar novamente >
               </p>
       </div> <!-- rg_img_result_wrap -->
       ";
}

echo $resultimgimg_rg;

?>
