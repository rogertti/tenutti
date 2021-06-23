<?php
    // include database and object files

    include_once '../config/database.php';
    include_once '../objects/caixa.php';

    // get database connection

    $database = new Database();
    $db = $database->getConnection();

    // prepare object

    $caixa = new Caixa($db);

    // vars to control this script

    $msg = "Campo obrigat&oacute;rio vazio.";

        // filtering the inputs

        if (empty($_POST['rand'])) { die('Vari&aacute;vel de controle nula.'); }
        if (empty($_POST['idcaixa'])) { die('Vari&aacute;vel de controle nula.'); } else {
            $caixa->idcaixa = $_POST['idcaixa'];
        }
        
        if (!isset($_POST['tipo'])) {
            $filtro = 1;
            $caixa->tipo = filter_input(INPUT_POST, 'tipo_mask', FILTER_SANITIZE_NUMBER_INT);
        } else { // isset because can be 0
            $filtro = 1;
            $_POST['tipo'] = filter_input(INPUT_POST, 'tipo', FILTER_SANITIZE_NUMBER_INT);
            $caixa->tipo = $_POST['tipo'];
        }

        if (empty($_POST['descricao'])) { die($msg); } else {
            $filtro++;
            #$_POST['descricao'] = str_replace("'", "&#39;", $_POST['descricao']);
            #$_POST['descricao'] = str_replace('"', '&#34;', $_POST['descricao']);
            #$_POST['descricao'] = str_replace('%', '&#37;', $_POST['descricao']);
            $_POST['descricao'] = filter_input(INPUT_POST, 'descricao', FILTER_SANITIZE_STRING);
            $caixa->descricao = $_POST['descricao'];
        }
        if (empty($_POST['valor'])) { die($msg); } else {
            $filtro++;
            $_POST['valor'] = filter_input(INPUT_POST, 'valor', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION | FILTER_FLAG_ALLOW_THOUSAND);
            #$_POST['valor'] = filter_input(INPUT_POST, 'valor', FILTER_VALIDATE_FLOAT);
            $caixa->valor = $_POST['valor'];
        }
        if (!isset($_POST['pago'])) { die($msg); } else { // isset because can be 0
            $filtro++;
            $caixa->pago = filter_input(INPUT_POST, 'pago', FILTER_SANITIZE_NUMBER_INT);

                if ($caixa->pago == 0) {
                    $caixa->valor_pago = 0;
                } else if ($caixa->pago == 1) {
                    if (!empty($_POST['valor_pago'])) {
                        if ($_POST['valor_pago'] > 0) {
                            $caixa->valor_pago = filter_input(INPUT_POST, 'valor_pago', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION | FILTER_FLAG_ALLOW_THOUSAND);
                            #$_POST['valor'] = filter_input(INPUT_POST, 'valor', FILTER_VALIDATE_FLOAT);
                        } else {
                            $caixa->valor_pago = 0;
                        }
                    }
                }
        }
//echo $caixa->pago . ' - ' . $caixa->valor_pago; exit;
        if ($filtro == 4) {
            if ($caixa->update()) {
                echo'true';
            } else {
                die(var_dump($db->errorInfo()));
            }
        } else {
            die('Vari&aacute;vel de controle nula.');
        }
        
    unset($database,$db,$caixa,$msg);
?>