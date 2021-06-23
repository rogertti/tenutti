<?php
    // include database and object files
    
    include_once '../config/database.php';
    include_once '../objects/produto.php';

    // get database connection

    $database = new Database();
    $db = $database->getConnection();

    // prepare object
    
    $produto = new Produto($db);

    // set variables
    
    $py_idproduto = md5('idproduto');
    $produto->idproduto = $_GET[''.$py_idproduto.''];
    $produto->monitor = 0;

        if ($produto->delete()) {
            echo'true';
        } else {
            die(var_dump($db->errorInfo()));
        }
   
    unset($database,$db,$produto,$py_idproduto);
