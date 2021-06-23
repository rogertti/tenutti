<?php
    // include database and object files

    include_once '../config/database.php';
    include_once '../objects/pedido.php';
    include_once '../objects/produtoPedido.php';

    // get database connection

    $database = new Database();
    $db = $database->getConnection();

    // prepare object

    $pedido = new Pedido($db);
    $produtoPedido = new ProdutoPedido($db);

    // vars to control this script

    $msg = "Campo obrigat&oacute;rio vazio.";

        // filtering the inputs

        if (empty($_POST['rand'])) { die('Vari&aacute;vel de controle nula.'); }
        if (empty($_POST['codigo'])) { die('Vari&aacute;vel de controle nula.'); } else {
            $_POST['codigo'] = filter_input(INPUT_POST, 'codigo', FILTER_SANITIZE_STRING);
            $pedido->codigo = $_POST['codigo'];
        }
        if (empty($_POST['datado'])) { die('Vari&aacute;vel de controle nula.'); } else {
            $_POST['datado'] = filter_input(INPUT_POST, 'datado', FILTER_SANITIZE_STRING);
            $pedido->datado = $_POST['datado'];
        }

        if (empty($_POST['cliente'])) { die($msg); } else {
            $filtro = 1;
            $pedido->idcliente = $_POST['cliente'];
        }
        if (empty($_POST['produto'])) { die($msg); } else {
            $filtro++;
            $_POST['produto'] = filter_input(INPUT_POST, 'produto', FILTER_SANITIZE_STRING);
            $_POST['produto'] = explode('-', $_POST['produto']);
            $produtoPedido->idproduto = $_POST['produto'][0];
        }
        if (empty($_POST['quantidade'])) { die($msg); } else {
            $filtro++;
            $_POST['quantidade'] = filter_input(INPUT_POST, 'quantidade', FILTER_SANITIZE_NUMBER_INT);
            $produtoPedido->quantidade = $_POST['quantidade'];
        }
        if (empty($_POST['subtotal'])) { die($msg); } else {
            $filtro++;
            $_POST['subtotal'] = filter_input(INPUT_POST, 'subtotal', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            #$_POST['subtotal'] = filter_var($_POST['subtotal'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $produtoPedido->subtotal = $_POST['subtotal'];
        }

    $pedido->desconto = 0;
    $pedido->forma_pg = '';
    $pedido->parcela = 0;
    $pedido->finalizado = 0;
    $pedido->monitor = 1;
    
        if ($filtro == 4) {
            if ($idpedido = $pedido->insert()) {
                $produtoPedido->idpedido = $idpedido;
                $count = 1;
            } else {
                die(var_dump($db->errorInfo()));
            }

            if ($produtoPedido->insert()) {
                $count++;
            } else {
                die(var_dump($db->errorInfo()));
            }

            if ($count == 2) {
                #echo'true';
                $py_idpedido = md5('idpedido');
                echo'<url>pedido?'.$py_idpedido.'='.$idpedido.'</url>';
            }
        } else {
            die('Vari&aacute;vel de controle nula.');
        }

    unset($database,$db,$filtro,$pedido,$produtoPedido,$msg,$count);
?>