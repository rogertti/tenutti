<?php
    // include database and object files
    
    include_once '../config/database.php';
    include_once '../objects/caixa.php';

    // get database connection

    $database = new Database();
    $db = $database->getConnection();

    // prepare object
    
    $caixa = new Caixa($db);

    // set variables
    
    $py_idcaixa = md5('idcaixa');
    $caixa->idcaixa = $_GET[''.$py_idcaixa.''];
    $caixa->monitor = 0;

        if ($caixa->delete()) {
            echo'true';
        } else {
            die(var_dump($db->errorInfo()));
        }
   
    unset($database,$db,$caixa,$py_idcaixa);