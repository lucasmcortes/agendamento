<?php

include __DIR__.'/../../../../includes/setup.inc.php';

clearstatcache();
if (is_file($_SESSION['img_rg_info_session']['img_rg_path'].$_SESSION['img_rg_info_session']['img_rg_nome_completo'])) {
        $imagem_location_pra_rename = $_SESSION['img_rg_info_session']['img_rg_url_completo'];
        if ( ($_SESSION['img_rg_info_session']['img_rg_extensao']!='.pdf') && ($_SESSION['img_rg_info_session']['img_rg_extensao']!='.png') ) {
                fit_image_file_to_width($_SESSION['img_rg_info_session']['img_rg_url_completo'], 1080, $_SESSION['img_rg_info_session']['img_rg_mime']);
        }
        $imagem_location_rename = __DIR__.'/../../rg/'.$_SESSION['rg'].$_SESSION['img_rg_info_session']['img_rg_extensao'];
        copy($imagem_location_pra_rename,$imagem_location_rename);

        clearstatcache();
        if (is_file($imagem_location_rename)) {
                // deleta imagens temporárias de rg
                unlinkTemp(__DIR__.'/../temp/');
                // volta o nome da imagem
                echo $imagem_location_rename;
        } // moveu a imagem true

} // file_exists

unset($_SESSION['img_rg_info_session']);

?>
