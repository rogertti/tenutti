<?php
    class Produto {
        // database connection

        private $conn;
     
        // object properties

        public $idproduto;
        public $descricao;
        public $tipo;
        public $tamanho;
        public $cor;
        public $valor_custo;
        public $valor_venda;
        public $observacao;
        public $monitor;
     
        // constructor with $db as database connection

        public function __construct($db) {
            $this->conn = $db;
        }

        // check for same record on insert

        function productInsertExist() {
            $sql = $this->conn->prepare("SELECT idproduto FROM produto WHERE descricao = :descricao AND tipo = :tipo AND tamanho = :tamanho AND cor = :cor AND valor_venda = :valor_venda");
            $sql->bindParam(':descricao', $this->descricao, PDO::PARAM_STR);
            $sql->bindParam(':tipo', $this->tipo, PDO::PARAM_STR);
            $sql->bindParam(':tamanho', $this->tamanho, PDO::PARAM_STR);
            $sql->bindParam(':cor', $this->cor, PDO::PARAM_STR);
            $sql->bindParam(':valor_venda', $this->valor_venda, PDO::PARAM_STR);
            $sql->execute();

                if ($sql->rowCount() > 0) {
                    return true;
                } else {
                    return false;
                }
        }

        // check for same record on update

        function productUpdateExist() {
            $sql = $this->conn->prepare("SELECT idproduto FROM produto WHERE descricao = :descricao AND tipo = :tipo AND tamanho = :tamanho AND cor = :cor AND valor_venda = :valor_venda AND idproduto <> :idproduto");
            $sql->bindParam(':descricao', $this->descricao, PDO::PARAM_STR);
            $sql->bindParam(':tipo', $this->tipo, PDO::PARAM_STR);
            $sql->bindParam(':tamanho', $this->tamanho, PDO::PARAM_STR);
            $sql->bindParam(':cor', $this->cor, PDO::PARAM_STR);
            $sql->bindParam(':valor_venda', $this->valor_venda, PDO::PARAM_STR);
            $sql->bindParam(':idproduto', $this->idproduto, PDO::PARAM_INT);
            $sql->execute();

                if ($sql->rowCount() > 0) {
                    return true;
                } else {
                    return false;
                }
        }

        // read all records

        function readAll() {
            $sql = $this->conn->prepare("SELECT idproduto,descricao,tipo,tamanho,cor,valor_custo,valor_venda,observacao FROM produto WHERE monitor = :monitor ORDER BY descricao,tipo,tamanho,cor");
            $sql->bindParam(':monitor', $this->monitor, PDO::PARAM_STR);
            $sql->execute();

            return $sql;
        }

        // read single record

        function readSingle() {
            $sql = $this->conn->prepare("SELECT idproduto,descricao,tipo,tamanho,cor,valor_custo,valor_venda,observacao FROM produto WHERE idproduto = :idproduto ORDER BY descricao,tipo,tamanho,cor");
            $sql->bindParam(':idproduto', $this->idproduto, PDO::PARAM_INT);
            $sql->execute();

            return $sql;
        }

        // insert record

        function insert() {
            if ($this->productInsertExist()) {
                die('Esse produto j&aacute; est&aacute; cadastrado.');
            } else {
                $sql = $this->conn->prepare("INSERT INTO produto (descricao, tipo, tamanho, cor, valor_custo, valor_venda, observacao, monitor) VALUES (:descricao, :tipo, :tamanho, :cor, :valor_custo, :valor_venda, :observacao, :monitor)");
                $sql->bindParam(':descricao', $this->descricao, PDO::PARAM_STR);
                $sql->bindParam(':tipo', $this->tipo, PDO::PARAM_STR);
                $sql->bindParam(':tamanho', $this->tamanho, PDO::PARAM_STR);
                $sql->bindParam(':cor', $this->cor, PDO::PARAM_STR);
                $sql->bindParam(':valor_custo', $this->valor_custo, PDO::PARAM_STR);
                $sql->bindParam(':valor_venda', $this->valor_venda, PDO::PARAM_STR);
                $sql->bindParam(':observacao', $this->observacao, PDO::PARAM_STR);
                $sql->bindParam(':monitor', $this->monitor, PDO::PARAM_INT);
                #$sql->execute();

                    if ($sql->execute()) {
                        return $this->conn->lastInsertId();
                    }

                #return $sql;
            }
        }

        // update record

        function update() {
            if ($this->productUpdateExist()) {
                die('Esse produto j&aacute; est&aacute; cadastrado.');
            } else {
                $sql = $this->conn->prepare("UPDATE produto SET descricao = :descricao, tipo = :tipo, tamanho = :tamanho, cor = :cor, valor_custo = :valor_custo, valor_venda = :valor_venda, observacao = :observacao WHERE idproduto = :idproduto");
                $sql->bindParam(':descricao', $this->descricao, PDO::PARAM_STR);
                $sql->bindParam(':tipo', $this->tipo, PDO::PARAM_STR);
                $sql->bindParam(':tamanho', $this->tamanho, PDO::PARAM_STR);
                $sql->bindParam(':cor', $this->cor, PDO::PARAM_STR);
                $sql->bindParam(':valor_custo', $this->valor_custo, PDO::PARAM_STR);
                $sql->bindParam(':valor_venda', $this->valor_venda, PDO::PARAM_STR);
                $sql->bindParam(':observacao', $this->observacao, PDO::PARAM_STR);
                $sql->bindParam(':idproduto', $this->idproduto, PDO::PARAM_INT);
                $sql->execute();

                return $sql;
            }
        }

        // delete record

        function delete() {
            $sql = $this->conn->prepare("UPDATE produto SET monitor = :monitor WHERE idproduto = :idproduto");
            $sql->bindParam(':monitor', $this->monitor, PDO::PARAM_STR);
            $sql->bindParam(':idproduto', $this->idproduto, PDO::PARAM_INT);
            $sql->execute();

            return $sql;
        }
    }