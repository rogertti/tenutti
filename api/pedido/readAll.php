<?php
    session_start();

    // include database and object files
    
    include_once '../config/database.php';
    include_once '../objects/pedido.php';

        // user control
        
        if (empty($_SESSION['key'])) {
            header ('location:../../');
        }
     
    // get database connection

    $database = new Database();
    $db = $database->getConnection();
     
    // prepare object
    
    $pedido = new Pedido($db);
    
    // config control

    $getmes = md5('mes');
    $getano = md5('ano');
    
    // var to control

    $pedido->datado = $_GET[''.$getano.''].'-'.$_GET[''.$getmes.''].'%';
    #$pedido->finalizado = 0;
    $pedido->monitor = 1;
    
    // retrieve query

    $sql = $pedido->readAll();
    
        // check if more than 0 record found
        
        if ($sql->rowCount() > 0) {
            $pedido_arr['pedido'] = array();
        
                while($row = $sql->fetch(PDO::FETCH_ASSOC)) {
                    extract($row);

                    // format date to dd/mm/yyyy

                    $ano = substr($datado, 0, 4);
                    $mes = substr($datado, 5, 2);
                    $dia = substr($datado, 8, 2);
                    $datado = $dia . '/' . $mes . '/' . $ano;
                    
                    if (empty($celular)) {
                        $celular = '-';
                    }

                    $pedido_item = array(
                        'status' => true,
                        'idpedido' => $idpedido,
                        'codigo' => $codigo,
                        'datado' => $datado,
                        'finalizado' => $finalizado,
                        'idcliente' => $idcliente,
                        'cliente' => $cliente,
                        'celular' => $celular
                    );

                    array_push($pedido_arr['pedido'], $pedido_item);
                }
        
            echo json_encode($pedido_arr['pedido']);
        } else {
            $pedido_arr = array('status' => false);
            echo json_encode($pedido_arr);
        }
?>