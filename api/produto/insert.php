<?php
    // include database and object files

    include_once '../config/database.php';
    include_once '../objects/produto.php';

    // get database connection

    $database = new Database();
    $db = $database->getConnection();

    // prepare object

    $produto = new Produto($db);

    // vars to control this script

    $msg = "Campo obrigat&oacute;rio vazio.";

        // filtering the inputs

        if (empty($_POST['rand'])) { die('Vari&aacute;vel de controle nula.'); }
        if (empty($_POST['descricao'])) { die($msg); } else {
            $filtro = 1;
            #$_POST['descricao'] = str_replace("'", "&#39;", $_POST['descricao']);
            #$_POST['descricao'] = str_replace('"', '&#34;', $_POST['descricao']);
            #$_POST['descricao'] = str_replace('%', '&#37;', $_POST['descricao']);
            $_POST['descricao'] = filter_input(INPUT_POST, 'descricao', FILTER_SANITIZE_STRING);
            $produto->descricao = ucwords($_POST['descricao']);
        }

        if (($produto->descricao == 'Serviço') or ($produto->descricao == 'Tela') or ($produto->descricao == 'Manutenção Tela')) {
            $filtro = 4;
            $produto->tipo = '';
            $produto->tamanho = '';
            $produto->cor = '';
        } else {
            if (empty($_POST['tipo'])) {
                die($msg);
            } else {
                $filtro++;
                $_POST['tipo'] = filter_input(INPUT_POST, 'tipo', FILTER_SANITIZE_STRING);
                $produto->tipo = $_POST['tipo'];
            }
            if (empty($_POST['tamanho'])) {
                die($msg);
            } else {
                $filtro++;
                $_POST['tamanho'] = filter_input(INPUT_POST, 'tamanho', FILTER_SANITIZE_STRING);
                $produto->tamanho = $_POST['tamanho'];
            }
            if (empty($_POST['cor'])) {
                die($msg);
            } else {
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
        if (!empty($_POST['observacao'])) {
            $_POST['observacao'] = filter_input(INPUT_POST, 'observacao', FILTER_SANITIZE_STRING);
            $produto->observacao = $_POST['observacao'];
        }
        
    $produto->monitor = 1;

        if ($filtro == 5) {
            if ($produto->insert()) {
                echo'true';
            } else {
                die(var_dump($db->errorInfo()));
            }
        } else {
            die('Vari&aacute;vel de controle nula.');
        }

    unset($database,$db,$produto,$msg);
