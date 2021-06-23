<?php
    class Caixa {
        // database connection and table name
        private $conn;
     
        // object properties
        public $idcaixa;
        public $codigo;
        public $ref_pedido;
        public $datado;
        public $tipo;
        public $descricao;
        public $valor;
        public $pago;
        public $valor_pago;
        public $monitor;
     
        // constructor with $db as database connection
        public function __construct($db) {
            $this->conn = $db;
        }

        // check for same record on insert
        
        function cashInsertExist() {
            $sql = $this->conn->prepare("SELECT idcaixa FROM caixa WHERE codigo = :codigo AND descricao <> :descricao");
            $sql->bindParam(':codigo', $this->codigo, PDO::PARAM_STR);
            $sql->bindParam(':descricao', $this->descricao, PDO::PARAM_STR);
            $sql->execute();

                if ($sql->rowCount() > 0) {
                    return true;
                } else {
                    return false;
                }
        }

        // check for same record on update

        function cashUpdateExist() {
            $sql = $this->conn->prepare("SELECT idcaixa FROM caixa WHERE codigo = :codigo AND descricao <> :descricao AND idcaixa <> :idcaixa");
            $sql->bindParam(':codigo', $this->codigo, PDO::PARAM_STR);
            $sql->bindParam(':descricao', $this->descricao, PDO::PARAM_STR);
            $sql->bindParam(':idcaixa', $this->idcaixa, PDO::PARAM_INT);
            $sql->execute();

                if ($sql->rowCount() > 0) {
                    return true;
                } else {
                    return false;
                }
        }

        // read all records

        function readAll() {
            $sql = $this->conn->prepare("SELECT idcaixa, codigo, ref_pedido, datado, tipo, descricao, valor, pago, valor_pago FROM caixa WHERE datado LIKE :datado AND monitor = :monitor ORDER BY tipo DESC, datado, descricao, valor, pago, codigo");
            $sql->bindParam(':datado', $this->datado, PDO::PARAM_STR);
            $sql->bindParam(':monitor', $this->monitor, PDO::PARAM_STR);
            $sql->execute();

            return $sql;
        }

        // read single record

        function readSingle() {
            $sql = $this->conn->prepare("SELECT idcaixa, codigo, ref_pedido, datado, tipo, descricao, valor, pago, valor_pago FROM caixa WHERE idcaixa = :idcaixa ORDER BY tipo DESC, datado, descricao, valor, pago, codigo");
            $sql->bindParam(':idcaixa', $this->idcaixa, PDO::PARAM_INT);
            $sql->execute();

            return $sql;
        }

        // insert record

        function insert() {
            if ($this->cashInsertExist()) {
                die('Esse lan&ccedil;amento j&aacute; est&aacute; cadastrado.');
            } else {
                $sql = $this->conn->prepare("INSERT INTO caixa (codigo, ref_pedido, datado, tipo, descricao, valor, pago, monitor) VALUES (:codigo, :ref_pedido, :datado, :tipo, :descricao, :valor, :pago, :monitor)");
                $sql->bindParam(':codigo', $this->codigo, PDO::PARAM_STR);
                $sql->bindParam(':ref_pedido', $this->ref_pedido, PDO::PARAM_STR);
                $sql->bindParam(':datado', $this->datado, PDO::PARAM_STR);
                $sql->bindParam(':tipo', $this->tipo, PDO::PARAM_INT);
                $sql->bindParam(':descricao', $this->descricao, PDO::PARAM_STR);
                $sql->bindParam(':valor', $this->valor, PDO::PARAM_STR);
                $sql->bindParam(':pago', $this->pago, PDO::PARAM_INT);
                $sql->bindParam(':monitor', $this->monitor, PDO::PARAM_INT);
                $sql->execute();

                return $sql;
            }
        }

        // update record

        function update() {
            if ($this->cashUpdateExist()) {
                die('Esse lan&ccedil;amento j&aacute; est&aacute; cadastrado.');
            } else {
                $sql = $this->conn->prepare("UPDATE caixa SET tipo = :tipo, descricao = :descricao, valor = :valor, pago = :pago, valor_pago = :valor_pago WHERE idcaixa = :idcaixa");
                #$sql->bindParam(':codigo', $this->codigo, PDO::PARAM_STR);
                #$sql->bindParam(':datado', $this->datado, PDO::PARAM_STR);
                $sql->bindParam(':tipo', $this->tipo, PDO::PARAM_STR);
                $sql->bindParam(':descricao', $this->descricao, PDO::PARAM_STR);
                $sql->bindParam(':valor', $this->valor, PDO::PARAM_STR);
                $sql->bindParam(':pago', $this->pago, PDO::PARAM_STR);
                $sql->bindParam(':valor_pago', $this->valor_pago, PDO::PARAM_STR);
                $sql->bindParam(':idcaixa', $this->idcaixa, PDO::PARAM_INT);
                $sql->execute();

                return $sql;
            }
        }

        // delete record

        function delete() {
            $sql = $this->conn->prepare("UPDATE caixa SET monitor = :monitor WHERE idcaixa = :idcaixa");
            $sql->bindParam(':monitor', $this->monitor, PDO::PARAM_STR);
            $sql->bindParam(':idcaixa', $this->idcaixa, PDO::PARAM_INT);
            $sql->execute();

            return $sql;
        }
    }