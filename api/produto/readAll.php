<?php
    session_start();

    // include database and object files

    include_once '../config/database.php';
    include_once '../objects/produto.php';

        // user control
            
        if (empty($_SESSION['key'])) {
            header ('location:../../');
        }
     
    // get database connection

    $database = new Database();
    $db = $database->getConnection();
     
    // prepare object

    $produto = new Produto($db);

    // config control

    $produto->monitor = 1;

    // retrieve query

    $sql = $produto->readAll();
    
        // check if more than 0 record found

        if ($sql->rowCount() > 0) {
            $produto_arr['produto'] = array();
        
                while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
                    extract($row);

                        // format description

                        if ($descricao == 'Tela') {
                            $tipo = '-';
                            $tamanho = '-';
                            $cor = '-';
                        } elseif ($descricao == 'Manutenção Tela') {
                            $descricao = 'Manuten&ccedil;&atilde;o de Tela';
                            $tipo = '-';
                            $tamanho = '-';
                            $cor = '-';
                        }
                    
                    // format valor

                    $valor_custo = number_format($valor_custo, 2, '.', ',');
                    $valor_venda = number_format($valor_venda, 2, '.', ',');

                    $produto_item = array(
                        'status' => true,
                        'idproduto' => $idproduto,
                        'descricao' => $descricao,
                        'tipo' => $tipo,
                        'tamanho' => $tamanho,
                        'cor' => $cor,
                        'valor_custo' => $valor_custo,
                        'valor_venda' => $valor_venda
                    );

                    array_push($produto_arr['produto'], $produto_item);
                }
        
            echo json_encode($produto_arr['produto']);
        } else {
            $produto_arr = array('status' => false);
            echo json_encode($produto_arr);
        }
