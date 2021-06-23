<?php
    // include database and object files

    include_once '../config/database.php';
    include_once '../objects/usuario.php';

        // encrypt password by openssl

        function encrypt($data, $key) {
            $len = strlen($key);
            
                if ($len < 16) {
                    $key = str_repeat($key, ceil(16 / $len));
                    #if ($m = strlen($data) % 8)
                    $m = strlen($data) % 8;
                    $data .= str_repeat("\x00",  8 - $m);
                    $val = openssl_encrypt($data, 'AES-256-OFB', $key, 0, $key);
                    $val = base64_encode($val);
                } else {
                    die('N&atilde;o foi poss&iacute;vel criptografar o acesso');
                }

            return $val;
        }

    // get database connection

    $database = new Database();
    $db = $database->getConnection();

    // prepare object

    $usuario = new Usuario($db);

    // vars to control this script

    $msg = "Campo obrigat&oacute;rio vazio.";
    $enigma = base64_encode('?');

        // filtering the inputs

        if (empty($_POST['rand'])) { die('Vari&aacute;vel de controle nula.'); }
        if (empty($_POST['nome'])) { die($msg); } else {
            $filtro = 1;
            #$_POST['nome'] = str_replace("'","&#39;",$_POST['nome']);
            #$_POST['nome'] = str_replace('"','&#34;',$_POST['nome']);
            #$_POST['nome'] = str_replace('%','&#37;',$_POST['nome']);
            $_POST['nome'] = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING);
            $usuario->nome = $_POST['nome'];
        }
        if (empty($_POST['usuario'])) { die($msg); } else {
            $filtro++;
            $_POST['usuario'] = filter_input(INPUT_POST, 'usuario', FILTER_DEFAULT);
            $usuario->usuario = encrypt(base64_decode($_POST['usuario']), $enigma);
        }
        if (empty($_POST['senha'])) { die($msg); } else {
            $filtro++;
            $_POST['senha'] = filter_input(INPUT_POST, 'senha', FILTER_DEFAULT);
            $usuario->senha = encrypt(base64_decode($_POST['senha']), $enigma);
        }
        if (empty($_POST['email'])) { die($msg); } else {
            $filtro++;
            $_POST['email'] = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
            $_POST['email'] = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
            
                if (!$_POST['email']) {
                    die('O email informado &eacute; inv&aacute;lido.');
                } else {
                    $usuario->email = $_POST['email'];
                }
            
            #$usuario->email = $_POST['email'];
        }

    $usuario->monitor = 1;

        if ($filtro == 4) {
            if ($usuario->install()) {
                echo'true';
                rename('../../appInstall.php', '../../appInstallDone.php');
            } else {
                die(var_dump($db->errorInfo()));
            }
        } else {
            die('Vari&aacute;vel de controle nula.');
        }

    unset($database,$db,$usuario,$msg,$enigma);
?>