<?php
    class ProdutoPedido {
        // database connection

        private $conn;
     
        // object properties

        public $idpedido;
        public $idproduto;
        public $quantidade;
        public $desconto;
        public $forma_pg;
        public $parcela;
        public $subtotal;
     
        // constructor with $db as database connection

        public function __construct($db) {
            $this->conn = $db;
        }

        // check for same record on insert

        function itemInsertExist() {
            $sql = $this->conn->prepare("SELECT pedido_idpedido FROM produto_no_pedido WHERE pedido_idpedido = :idpedido AND produto_idproduto = :idproduto");
            $sql->bindParam(':idpedido', $this->idpedido, PDO::PARAM_INT);
            $sql->bindParam(':idproduto', $this->idproduto, PDO::PARAM_INT);
            $sql->execute();

                if ($sql->rowCount() > 0) {
                    return true;
                } else {
                    return false;
                }
        }

        // read single record

        function readAll() {
            $sql = $this->conn->prepare("SELECT produto.idproduto, produto.descricao AS produto, produto.tipo, produto.tamanho, produto.cor, produto.valor_venda, produto_no_pedido.quantidade, produto_no_pedido.subtotal FROM pedido INNER JOIN produto_no_pedido ON produto_no_pedido.pedido_idpedido = pedido.idpedido INNER JOIN produto ON produto.idproduto = produto_no_pedido.produto_idproduto WHERE idpedido = :idpedido");
            $sql->bindParam(':idpedido', $this->idpedido, PDO::PARAM_INT);
            $sql->execute();

            return $sql;
        }

        // insert record

        function insert() {
            if ($this->itemInsertExist()) {
                die('Esse item j&aacute; est&aacute; cadastrado.');
            } else {
                $sql = $this->conn->prepare("INSERT INTO produto_no_pedido (pedido_idpedido, produto_idproduto, quantidade, subtotal) VALUES (:idpedido, :idproduto, :quantidade, :subtotal)");
                $sql->bindParam(':idpedido', $this->idpedido, PDO::PARAM_INT);
                $sql->bindParam(':idproduto', $this->idproduto, PDO::PARAM_INT);
                $sql->bindParam(':quantidade', $this->quantidade, PDO::PARAM_INT);
                $sql->bindParam(':subtotal', $this->subtotal, PDO::PARAM_STR);
                $sql->execute();

                return $sql;
            }
        }

        // finish record

        function finish() {
            $sql = $this->conn->prepare("UPDATE pedido SET desconto = :desconto, forma_pg = :forma_pg, parcela = :parcela, finalizado = :finalizado WHERE idpedido = :idpedido");
            $sql->bindParam(':desconto', $this->desconto, PDO::PARAM_INT);
            $sql->bindParam(':forma_pg', $this->forma_pg, PDO::PARAM_STR);
            $sql->bindParam(':parcela', $this->parcela, PDO::PARAM_INT);
            $sql->bindParam(':finalizado', $this->finalizado, PDO::PARAM_INT);
            $sql->bindParam(':idpedido', $this->idpedido, PDO::PARAM_INT);
            $sql->execute();

            return $sql;
        }

        // delete record

        function delete() {
            $sql = $this->conn->prepare("DELETE FROM produto_no_pedido WHERE pedido_idpedido = :idpedido AND produto_idproduto = :idproduto");
            $sql->bindParam(':idpedido', $this->idpedido, PDO::PARAM_INT);
            $sql->bindParam(':idproduto', $this->idproduto, PDO::PARAM_INT);
            $sql->execute();

            return $sql;
        }
    }