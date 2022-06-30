<?php

        include_once __DIR__.'/../../includes/setup.inc.php';

        if (isset($_POST['horario'])) {
                $_SESSION['horaDisponivel'] = $_POST['horario'];
                $_SESSION['competenteEscolhido'] = $_POST['competente'];
        }

?>
