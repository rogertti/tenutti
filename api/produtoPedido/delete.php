<?php
    // include database and object files
    
    include_once '../config/database.php';
    include_once '../objects/pedido.php';
    include_once '../objects/produtoPedido.php';

    // get database connection

    $database = new Database();
    $db = $database->getConnection();

    // prepare object
    
    $pedido = new Pedido($db);
    $produtoPedido = new ProdutoPedido($db);

    // set variables
    
    $py_idproduto = md5('idproduto');
    $produtoPedido->idproduto = $_GET[''.$py_idproduto.''];
    $py_idpedido = md5('idpedido');
    $produtoPedido->idpedido = $_GET[''.$py_idpedido.''];

        if ($produtoPedido->delete()) {
            echo'true';
        } else {
            die(var_dump($db->errorInfo()));
        }
   
    unset($database,$db,$produto,$py_idproduto);
