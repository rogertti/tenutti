<?php
    // include database and object files
    
    include_once '../config/database.php';
    include_once '../objects/cliente.php';
     
    // get database connection
    
    $database = new Database();
    $db = $database->getConnection();
     
    // prepare object

    $cliente = new Cliente($db);
    
    // config control

    $cliente->monitor = 1;

    // retrieve query
    
    $sql = $cliente->readAll();
    
        // check if more than 0 record found

        if ($sql->rowCount() > 0) {
            $cliente_arr['cliente'] = array();
        
                while($row = $sql->fetch(PDO::FETCH_ASSOC)) {
                    extract($row);

                        if (empty($documento)) { $documento = '-'; }
                        if (empty($celular)) { $celular = '-'; }
                    
                    $cliente_item = array(
                        'status' => true,
                        'idcliente' => $idcliente,
                        'nome' => $nome,
                        'documento' => $documento,
                        'celular' => $celular,
                    );

                    array_push($cliente_arr['cliente'], $cliente_item);
                }
        
            echo json_encode($cliente_arr['cliente']);
        } else {
            $cliente_arr = array('status' => false);
            echo json_encode($cliente_arr);
        }