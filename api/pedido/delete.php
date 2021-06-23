<?php
    // include database and object files
    
    include_once '../config/database.php';
    include_once '../objects/pedido.php';

    // get database connection

    $database = new Database();
    $db = $database->getConnection();

    // prepare object
    
    $pedido = new Pedido($db);

    // set variables
    
    $py_idpedido = md5('idpedido');
    $pedido->idpedido = $_GET[''.$py_idpedido.''];
    $pedido->monitor = 0;

        if ($pedido->delete()) {
            echo'true';
        } else {
            die(var_dump($db->errorInfo()));
        }
   
    unset($database,$db,$pedido,$py_idpedido);