<?php
    session_start();

    // include database and object files
    
    include_once '../config/database.php';
    include_once '../objects/caixa.php';

        // user control
            
        if (empty($_SESSION['key'])) {
            header ('location:../../');
        }
     
    // get database connection

    $database = new Database();
    $db = $database->getConnection();
     
    // prepare object
    
    $caixa = new Caixa($db);
    
    // config control

    $getmes = md5('mes');
    $getano = md5('ano');
    $caixa->datado = $_GET[''.$getano.''].'-'.$_GET[''.$getmes.''].'%';
    #$caixa->datado = 'ano-mes%';
    $caixa->monitor = 1;
    
    // query caixa

    $sql = $caixa->readAll();
    
        // check if more than 0 record found
        
        if ($sql->rowCount() > 0) {
            $caixa_arr['caixa'] = array();
            $total_recebido = 0;
            $total_receber = 0;
            $total_pago = 0;
            $total_pagar = 0;
        
                while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
                    extract($row);
                    
                        // format ref_pedido

                        if (empty($ref_pedido)) {
                            $ref_pedido = '-';
                        }

                    // format date to dd/mm/yyyy

                    $ano = substr($datado, 0, 4);
                    $mes = substr($datado, 5, 2);
                    $dia = substr($datado, 8, 2);
                    $datado = $dia . '/' . $mes . '/' . $ano;

                        // format tipo

                        if ($tipo == 1) {
                            if ($pago == 1) {
                                if (empty($valor_pago) or $valor_pago == 0) {
                                    $total_recebido = $total_recebido + $valor;
                                } else {
                                    $total_recebido = $total_recebido + $valor_pago;
                                    $total_receber = $valor - $valor_pago;
                                }
                            } elseif ($pago == 0) {
                                $total_receber = $total_receber + $valor;
                            }
                        } elseif ($tipo == 0) {
                            if ($pago == 1) {
                                if (empty($valor_pago)) {
                                    $total_pago = $total_pago - $valor;
                                } else {
                                    $total_pago = $total_pago - $valor;
                                    //$total_pagar = $total_pago - $valor_pago;
                                }
                            } elseif ($pago == 0) {
                                $total_pagar = $total_pagar - $valor;
                            }
                        }

                    // format valor

                    $valor = number_format($valor, 2, '.', ',');

                    // get order code

                    $codigo_pedido = substr($descricao, -10);

                    $caixa_item = array(
                        'status' => true,
                        'idcaixa' => $idcaixa,
                        'codigo' => $codigo,
                        'ref_pedido' => $ref_pedido,
                        'codigo_pedido' => $codigo_pedido,
                        'datado' => $datado,
                        'tipo' => $tipo,
                        'descricao' => $descricao,
                        'valor' => $valor,
                        'pago' => $pago,
                        'valor_pago' => $valor_pago
                    );

                    array_push($caixa_arr['caixa'], $caixa_item);
                }

            $caixa_item = array(
                'total_recebido' => $total_recebido,
                'total_receber' => $total_receber,
                'total_pago' => $total_pago,
                'total_pagar' => $total_pagar
            );
            
            array_push($caixa_arr['caixa'], $caixa_item);
        
            echo json_encode($caixa_arr['caixa']);
        } else {
            $caixa_arr = array('status' => false);
            echo json_encode($caixa_arr);
        }
