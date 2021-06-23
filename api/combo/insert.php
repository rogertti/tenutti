<?php
    // include database and object files

    include_once '../config/database.php';
    include_once '../objects/cliente.php';
    include_once '../objects/produto.php';
    include_once '../objects/pedido.php';
    include_once '../objects/produtoPedido.php';

    // get database connection

    $database = new Database();
    $db = $database->getConnection();

    // prepare objects

    $cliente = new Cliente($db);
    $produto = new Produto($db);
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
            $_POST['cliente'] = filter_input(INPUT_POST, 'cliente', FILTER_SANITIZE_STRING);
            $cliente->nome = ucwords($_POST['cliente']);
        }
        if (empty($_POST['produto'])) { die($msg); } else {
            $filtro++;
            $_POST['produto'] = filter_input(INPUT_POST, 'produto', FILTER_SANITIZE_STRING);
            $produto->descricao = $_POST['produto'];
        }

        if (($produto->descricao == 'Serviço') or ($produto->descricao == 'Tela') or ($produto->descricao == 'Manutenção Tela')) {
            $filtro = 5;
            $produto->tipo = '';
            $produto->tamanho = '';
            $produto->cor = '';
        } else {
            if (empty($_POST['tipo'])) { die($msg); } else {
                $filtro++;
                $_POST['tipo'] = filter_input(INPUT_POST, 'tipo', FILTER_SANITIZE_STRING);
                $produto->tipo = $_POST['tipo'];
            }
            if (empty($_POST['tamanho'])) { die($msg); } else {
                $filtro++;
                $_POST['tamanho'] = filter_input(INPUT_POST, 'tamanho', FILTER_SANITIZE_STRING);
                $produto->tamanho = $_POST['tamanho'];
            }
            if (empty($_POST['cor'])) { die($msg); } else {
                $filtro++;
                $_POST['cor'] = filter_input(INPUT_POST, 'cor', FILTER_SANITIZE_STRING);
                $produto->cor = $_POST['cor'];
            }
        }

        if (!empty($_POST['valor_custo'])) {
            $_POST['valor_custo'] = filter_input(INPUT_POST, 'valor_custo', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            #$_POST['valor_custo'] = filter_input(INPUT_POST, 'valor_custo', FILTER_VALIDATE_FLOAT);
            $produto->valor_custo = $_POST['valor_custo'];
        }
        if (empty($_POST['valor_venda'])) { die($msg); } else {
            $filtro++;
            $_POST['valor_venda'] = filter_input(INPUT_POST, 'valor_venda', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            #$_POST['valor_venda'] = filter_input(INPUT_POST, 'valor_venda', FILTER_VALIDATE_FLOAT);
            $produto->valor_venda = $_POST['valor_venda'];
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

    $cliente->monitor = 1;
    $produto->monitor = 1;
    $pedido->finalizado = 0;
    $pedido->monitor = 1;

        if ($filtro == 8) {
            if ($idcliente = $cliente->insert()) {
                $pedido->idcliente = $idcliente;
                $count = 1;
            } else {
                die(var_dump($db->errorInfo()));
            }

            if ($idproduto = $produto->insert()) {
                $produtoPedido->idproduto = $idproduto;
                $count++;
            } else {
                die(var_dump($db->errorInfo()));
            }

            if ($idpedido = $pedido->insert()) {
                $produtoPedido->idpedido = $idpedido;
                $count++;
            } else {
                die(var_dump($db->errorInfo()));
            }

            if ($produtoPedido->insert()) {
                $count++;
            } else {
                die(var_dump($db->errorInfo()));
            }

            if ($count == 4) {
                #echo'true';
                $py_idpedido = md5('idpedido');
                echo'<url>pedido?'.$py_idpedido.'='.$idpedido.'</url>';
            }
        } else {
            die('Vari&aacute;vel de controle nula.');
        }
        
    unset($database,$db,$cliente,$produto,$pedido,$msg,$filtro);
?>