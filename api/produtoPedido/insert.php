<?php
    // include database and object files

    include_once '../config/database.php';
    include_once '../objects/produto.php';
    include_once '../objects/produtoPedido.php';

    // get database connection

    $database = new Database();
    $db = $database->getConnection();

    // prepare object

    $produto = new Produto($db);
    $produtoPedido = new ProdutoPedido($db);

    // vars to control this script

    $msg = "Campo obrigat&oacute;rio vazio.";

        // filtering the inputs

        if (empty($_POST['rand'])) { die('Vari&aacute;vel de controle nula.'); }
        if (empty($_POST['idpedido'])) { die('Vari&aacute;vel de controle nula.'); } else {
            $_POST['idpedido'] = filter_input(INPUT_POST, 'idpedido', FILTER_SANITIZE_NUMBER_INT);
            $produtoPedido->idpedido = $_POST['idpedido'];
        }
        
        if ($_POST['produto_existe'] == 1) {
            if (empty($_POST['produto'][0])) { die($msg); } else {
                $filtro = 1;
                $produto = explode('-', $_POST['produto'][0]);
                $produtoPedido->idproduto = filter_var($produto[0], FILTER_SANITIZE_NUMBER_INT);
            }
            if (empty($_POST['quantidade'][0])) { die($msg); } else {
                $filtro++;
                $produtoPedido->quantidade = filter_var($_POST['quantidade'][0], FILTER_SANITIZE_NUMBER_INT);
            }
            if (empty($_POST['subtotal'][0])) { die($msg); } else {
                $filtro++;
                $subtotal = explode('-', $_POST['subtotal'][0]);
                $produtoPedido->subtotal = $subtotal[0];
                $produtoPedido->subtotal = filter_var($produtoPedido->subtotal, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            }
        } elseif ($_POST['produto_existe'] == 0) {
            if (empty($_POST['produto'][1])) { die($msg); } else {
                $filtro_zero = 1;
                $produto->descricao = $_POST['produto'][1];
                $produto->descricao = filter_var($produto->descricao, FILTER_SANITIZE_STRING);
            }

            if (($produto->descricao == 'Serviço') or ($produto->descricao == 'Tela') or ($produto->descricao == 'Manutenção Tela')) {
                $filtro_zero = 4;
                $produto->tipo = '';
                $produto->tamanho = '';
                $produto->cor = '';
            } else {
                if (empty($_POST['tipo'])) { die($msg); } else {
                    $filtro_zero++;
                    $_POST['tipo'] = filter_input(INPUT_POST, 'tipo', FILTER_SANITIZE_STRING);
                    $produto->tipo = $_POST['tipo'];
                }
                if (empty($_POST['tamanho'])) { die($msg); } else {
                    $filtro_zero++;
                    $_POST['tamanho'] = filter_input(INPUT_POST, 'tamanho', FILTER_SANITIZE_STRING);
                    $produto->tamanho = $_POST['tamanho'];
                }
                if (empty($_POST['cor'])) { die($msg); } else {
                    $filtro_zero++;
                    $_POST['cor'] = filter_input(INPUT_POST, 'cor', FILTER_SANITIZE_STRING);
                    $produto->cor = $_POST['cor'];
                }
            }

            if (!empty($_POST['valor_custo'])) {
                $_POST['valor_custo'] = filter_input(INPUT_POST, 'valor_custo', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $produto->valor_custo = $_POST['valor_custo'];
            }
            if (empty($_POST['valor_venda'][1])) { die($msg); } else {
                $filtro_zero++;
                $valor_venda = explode('-', $_POST['valor_venda'][1]);
                $produto->valor_venda = $valor_venda[0];
                $produto->valor_venda = filter_var($produto->valor_venda, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            }
            if (empty($_POST['quantidade'][1])) { die($msg); } else {
                $filtro_zero++;
                $produtoPedido->quantidade = filter_var($_POST['quantidade'][1], FILTER_SANITIZE_NUMBER_INT);
            }
            if (empty($_POST['subtotal'][1])) { die($msg); } else {
                $filtro_zero++;
                $subtotal = explode('-', $_POST['subtotal'][1]);
                $produtoPedido->subtotal = $subtotal[0];
                $produtoPedido->subtotal = filter_var($produtoPedido->subtotal, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            }
        } else {
            die('Vari&aacute;vel de controle nula.');
        }

    $produto->monitor = 1;

        if (!empty($filtro) and ($filtro == 3)) {
            if ($produtoPedido->insert()) {
                $py_idpedido = md5('idpedido');
                echo'<url>pedido?'.$py_idpedido.'='.$produtoPedido->idpedido.'</url>';
            } else {
                die(var_dump($db->errorInfo()));
            }
        } elseif (!empty($filtro_zero) and ($filtro_zero == 7)) {
            if ($idproduto = $produto->insert()) {
                $produtoPedido->idproduto = $idproduto;
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
                $py_idpedido = md5('idpedido');
                echo'<url>pedido?'.$py_idpedido.'='.$produtoPedido->idpedido.'</url>';
            }
        } else {
            die('Vari&aacute;vel de controle nula.');
        }

    unset($database,$db,$produto,$msg,$filtro,$filtro_zero,$py_idpedido,$count);
