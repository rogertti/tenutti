<?php
    // include database and object files

    include_once '../config/database.php';
    include_once '../objects/cliente.php';

    // get database connection

    $database = new Database();
    $db = $database->getConnection();

    // prepare object

    $cliente = new Cliente($db);

    // vars to control this script

    $msg = "Campo obrigat&oacute;rio vazio.";

        // filtering the inputs

        if (empty($_POST['rand'])) { die('Vari&aacute;vel de controle nula.'); }
        if (empty($_POST['nome'])) { die($msg); } else {
            $filtro = 1;
            #$_POST['nome'] = str_replace("'","&#39;",$_POST['nome']);
            #$_POST['nome'] = str_replace('"','&#34;',$_POST['nome']);
            #$_POST['nome'] = str_replace('%','&#37;',$_POST['nome']);
            $_POST['nome'] = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING);
            $cliente->nome = ucwords($_POST['nome']);
        }

        if ($_POST['pessoa'] == 'F') {
            $_POST['cpf'] = filter_input(INPUT_POST, 'cpf', FILTER_SANITIZE_STRING);
            $cliente->documento = $_POST['cpf'];
        } elseif ($_POST['pessoa'] == 'J') {
            $_POST['cnpj'] = filter_input(INPUT_POST, 'cnpj', FILTER_SANITIZE_STRING);
            $cliente->documento = $_POST['cnpj'];
        } else {
            die('Vari&aacute;vel de controle nula.');
        }

        if (!empty($_POST['cep'])) {
            $_POST['cep'] = filter_input(INPUT_POST, 'cep', FILTER_SANITIZE_STRING);
            $cliente->cep = $_POST['cep'];
        }
        if (!empty($_POST['endereco']))  {
            $_POST['endereco'] = filter_input(INPUT_POST, 'endereco', FILTER_SANITIZE_STRING);
            $cliente->endereco = $_POST['endereco'];
        }
        if (!empty($_POST['bairro'])) {
            $_POST['bairro'] = filter_input(INPUT_POST, 'bairro', FILTER_SANITIZE_STRING);
            $cliente->bairro = $_POST['bairro'];
        }
        if (!empty($_POST['cidade'])) {
            $_POST['cidade'] = filter_input(INPUT_POST, 'cidade', FILTER_SANITIZE_STRING);
            $cliente->cidade = $_POST['cidade'];
        }
        if (!empty($_POST['estado'])) {
            $_POST['estado'] = filter_input(INPUT_POST, 'estado', FILTER_SANITIZE_STRING);
            $cliente->estado = $_POST['estado'];
        }
        if (!empty($_POST['telefone'])) {
            $_POST['telefone'] = filter_input(INPUT_POST, 'telefone', FILTER_SANITIZE_STRING);
            $cliente->telefone = $_POST['telefone'];
        }
        if (!empty($_POST['celular'])) {
            $_POST['celular'] = filter_input(INPUT_POST, 'celular', FILTER_SANITIZE_STRING);
            $cliente->celular = $_POST['celular'];
        }
        if (!empty($_POST['email'])) {
            $_POST['email'] = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
            $_POST['email'] = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
            
                if (!$_POST['email']) {
                    die('O email informado &eacute; inv&aacute;lido.');
                } else {
                    $cliente->email = $_POST['email'];
                }
            
            #$cliente->email = $_POST['email'];
        }
        if (!empty($_POST['observacao'])) {
            $_POST['observacao'] = filter_input(INPUT_POST, 'observacao', FILTER_SANITIZE_STRING);
            $cliente->observacao = $_POST['observacao'];
        }

    $cliente->monitor = 1;

        if ($filtro == 1) {
            if ($cliente->insert()) {
                echo'true';
            } else {
                die(var_dump($db->errorInfo()));
            }
        } else {
            die('Vari&aacute;vel de controle nula.');
        }

    unset($database,$db,$cliente,$msg,$filtro);