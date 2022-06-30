<?php

        include_once __DIR__.'/../../includes/setup.inc.php';

        if (isset($_POST['agendamento'])) {
                $_SESSION['horaAgendada'] = $_POST['agendamento'];
        }

?>
