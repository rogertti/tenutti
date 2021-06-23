<?php
    // include database and object files

    include_once '../config/database.php';
    include_once '../objects/pedido.php';

    // get database connection

    $database = new Database();
    $db = $database->getConnection();

    // prepare client object

    $pedido = new Pedido($db);

    // vars to control this script

    $msg = "Campo obrigat&oacute;rio vazio.";

        // filtering the inputs

        if (empty($_POST['rand'])) { die('Vari&aacute;vel de controle nula.'); }
        if (empty($_POST['idpedido'])) { die('Vari&aacute;vel de controle nula.'); } else {
            $pedido->idpedido = $_POST['idpedido'];
        }
        if (empty($_POST['codigo'])) { die('Vari&aacute;vel de controle nula.'); } else {
            $_POST['codigo'] = filter_input(INPUT_POST, 'codigo', FILTER_SANITIZE_STRING);
            $pedido->codigo = $_POST['codigo'];
        }

        if (empty($_POST['cliente'])) { die($msg); } else {
            $filtro = 1;
            $pedido->idcliente = $_POST['cliente'];
        }
        /*if (empty($_POST['produto'])) { die($msg); } else {
            $filtro++;
            $pedido->idproduto = $_POST['produto'];
        }*/

        if ($filtro == 1) {
            if ($pedido->update()) {
                echo'true';
            } else {
                die(var_dump($db->errorInfo()));
            }
        } else {
            die('Vari&aacute;vel de controle nula.');
        }
        
    unset($database,$db,$pedido,$msg);
?>