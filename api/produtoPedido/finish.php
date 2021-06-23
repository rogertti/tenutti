<?php
    ini_set('display_errors', 'On');
    date_default_timezone_set('America/Sao_Paulo');
    setlocale(LC_MONETARY, 'pt_BR');

    // require and includes

    include_once '../config/database.php';
    include_once '../objects/caixa.php';
    include_once '../objects/produtoPedido.php';

    // get database connection

    $database = new Database();
    $db = $database->getConnection();

    // prepare object

    $caixa = new Caixa($db);
    $produtoPedido = new ProdutoPedido($db);

    // vars to control this script

    $timestamp = new DateTime();
    $msg = "Campo obrigat&oacute;rio vazio.";

        // filtering the inputs

        if (empty($_POST['rand'])) { die('Vari&aacute;vel de controle nula.'); }
        if (empty($_POST['idpedido'])) { die('Vari&aacute;vel de controle nula.'); } else {
            $_POST['idpedido'] = filter_input(INPUT_POST, 'idpedido', FILTER_SANITIZE_NUMBER_INT);
            $produtoPedido->idpedido = $_POST['idpedido'];
        }
        if (empty($_POST['codigo'])) { die('Vari&aacute;vel de controle nula.'); } else {
            $caixa->codigo = filter_input(INPUT_POST, 'codigo', FILTER_SANITIZE_STRING);
            #$caixa->ref_pedido = filter_input(INPUT_POST, 'codigo', FILTER_SANITIZE_STRING);
            $caixa->ref_pedido = $_POST['idpedido'];
        }
        if (empty($_POST['forma_pg'])) { die($msg); } else {
            $filtro = 1;
            $produtoPedido->forma_pg = filter_input(INPUT_POST, 'forma_pg', FILTER_SANITIZE_STRING);

                if ($produtoPedido->forma_pg == 'Crédito') {
                    $produtoPedido->parcela = filter_input(INPUT_POST, 'parcela', FILTER_SANITIZE_NUMBER_INT);
                } else {
                    $produtoPedido->parcela = 0;
                }
        }
        if (!empty($_POST['desconto'])) {
            #$filtro++;
            $produtoPedido->desconto = filter_input(INPUT_POST, 'desconto', FILTER_SANITIZE_NUMBER_INT);
        }

    $produtoPedido->finalizado = 1;

    //$caixa->datado = $timestamp->format('Y-m-d H:i:s');
    $caixa->tipo = 1;
    $caixa->descricao = 'Pedido ' . $caixa->codigo;
    //$caixa->valor = filter_var($_POST['total'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $caixa->pago = 0;
    $caixa->monitor = 1;

        if ($filtro == 1) {
            if ($produtoPedido->finish()) {
                $count = 1;
            } else {
                die(var_dump($db->errorInfo()));
            }

            // se a forma de pagamento for crédito, será lançado no caixa a quantidade de parcelas
            // em forma de registros, o valor deve ser dividivo em parcelas e a data deve ser incrementada

            if ($produtoPedido->forma_pg == 'Crédito') {
                $caixa->valor = filter_var($_POST['total'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $caixa->valor = $caixa->valor / $produtoPedido->parcela;
                $a = 0;

                    for ($i = 1; $i <= $produtoPedido->parcela; $i++) {
                        if ($i == 1) {
                            $caixa->datado = $timestamp->format('Y-m-d H:i:s');
                            
                                if ($caixa->insert()) {
                                    //$count++;
                                } else {
                                    die(var_dump($db->errorInfo()));
                                }
                        } else {
                            $month = date('m') + $a;
        
                                if ($month > 12) {
                                    $month = $month % 12;
                                    $year = date('Y') + 1;
                                } else {
                                    $year = date('Y');
                                }
                            
                            $caixa->datado = $timestamp->format(''.$year.'-'.$month.'-d H:i:s');

                                if ($i != $_POST['parcela']) {
                                    if ($caixa->insert()) {
                                        //$count++;
                                    } else {
                                        die(var_dump($db->errorInfo()));
                                    }
                                } else {
                                    if ($caixa->insert()) {
                                        $count++;
                                    } else {
                                        die(var_dump($db->errorInfo()));
                                    }
                                }
                        }

                        $a++;
                    }
            } else {
                $caixa->datado = $timestamp->format('Y-m-d H:i:s');
                $caixa->valor = filter_var($_POST['total'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                    
                    if ($caixa->insert()) {
                        $count++;
                    } else {
                        die(var_dump($db->errorInfo()));
                    }
            }
            
            /*if ($caixa->insert()) {
                $count++;
            } else {
                die(var_dump($db->errorInfo()));
            }*/

            if ($count == 2) {
                $py_idpedido = md5('idpedido');
                echo'<url>pedidoPrint?'.$py_idpedido.'='.$produtoPedido->idpedido.'</url>';
            }
        } else {
            die('Vari&aacute;vel de controle nula.');
        }

    unset($database,$db,$produtoPedido,$caixa,$msg,$filtro);
