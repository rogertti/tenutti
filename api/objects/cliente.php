<?php
    class Cliente {
        // database connection

        private $conn;
     
        // object properties

        public $idcliente;
        public $nome;
        public $documento;
        public $cep;
        public $endereco;
        public $bairro;
        public $cidade;
        public $estado;
        public $telefone;
        public $celular;
        public $email;
        public $observacao;
        public $monitor;
     
        // constructor with $db as database connection

        public function __construct($db) {
            $this->conn = $db;
        }

        // check for same record on insert

        function clientInsertExist() {
            if (strlen($this->documento) != 0) {
                $sql = $this->conn->prepare("SELECT idcliente FROM cliente WHERE documento = :documento");
                $sql->bindParam(':documento', $this->documento, PDO::PARAM_STR);
                $sql->execute();

                    if ($sql->rowCount() > 0) {
                        return true;
                    } else {
                        return false;
                    }
            } else {
                $sql = $this->conn->prepare("SELECT idcliente FROM cliente WHERE nome = :nome");
                $sql->bindParam(':nome', $this->nome, PDO::PARAM_STR);
                $sql->execute();

                    if ($sql->rowCount() > 0) {
                        return true;
                    } else {
                        return false;
                    }
            }
        }

        // check for same record on update

        function clientUpdateExist() {
            if (strlen($this->documento) != 0) {
                $sql = $this->conn->prepare("SELECT idcliente FROM cliente WHERE documento = :documento AND idcliente <> :idcliente");
                $sql->bindParam(':documento', $this->documento, PDO::PARAM_STR);
                $sql->bindParam(':idcliente', $this->idcliente, PDO::PARAM_INT);
                $sql->execute();

                    if ($sql->rowCount() > 0) {
                        return true;
                    } else {
                        return false;
                    }
            } else {
                $sql = $this->conn->prepare("SELECT idcliente FROM cliente WHERE nome = :nome AND idcliente <> :idcliente");
                $sql->bindParam(':nome', $this->nome, PDO::PARAM_STR);
                $sql->bindParam(':idcliente', $this->idcliente, PDO::PARAM_INT);
                $sql->execute();

                    if ($sql->rowCount() > 0) {
                        return true;
                    } else {
                        return false;
                    }
            }    
        }

        // read all records

        function readAll() {
            $sql = $this->conn->prepare("SELECT idcliente,nome,documento,cep,endereco,bairro,cidade,estado,telefone,celular,email,observacao,monitor FROM cliente WHERE monitor = :monitor ORDER BY nome,documento,email,endereco");
            $sql->bindParam(':monitor', $this->monitor, PDO::PARAM_STR);
            $sql->execute();

            return $sql;
        }

        // read single record

        function readSingle() {
            $sql = $this->conn->prepare("SELECT idcliente,nome,documento,cep,endereco,bairro,cidade,estado,telefone,celular,email,observacao FROM cliente WHERE idcliente = :idcliente ORDER BY nome,documento,email,endereco");
            $sql->bindParam(':idcliente', $this->idcliente, PDO::PARAM_INT);
            $sql->execute();

            return $sql;
        }

        // insert record

        function insert() {
            if ($this->clientInsertExist()) {
                die('Esse cliente j&aacute; est&aacute; cadastrado.');
            } else {
                $sql = $this->conn->prepare("INSERT INTO cliente (nome,documento,cep,endereco,bairro,cidade,estado,telefone,celular,email,observacao,monitor) VALUES (:nome,:documento,:cep,:endereco,:bairro,:cidade,:estado,:telefone,:celular,:email,:observacao,:monitor)");
                $sql->bindParam(':nome', $this->nome, PDO::PARAM_STR);
                $sql->bindParam(':documento', $this->documento, PDO::PARAM_STR);
                $sql->bindParam(':cep', $this->cep, PDO::PARAM_STR);
                $sql->bindParam(':endereco', $this->endereco, PDO::PARAM_STR);
                $sql->bindParam(':bairro', $this->bairro, PDO::PARAM_STR);
                $sql->bindParam(':cidade', $this->cidade, PDO::PARAM_STR);
                $sql->bindParam(':estado', $this->estado, PDO::PARAM_STR);
                $sql->bindParam(':telefone', $this->telefone, PDO::PARAM_STR);
                $sql->bindParam(':celular', $this->celular, PDO::PARAM_STR);
                $sql->bindParam(':email', $this->email, PDO::PARAM_STR);
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
            if ($this->clientUpdateExist()) {
                die('Esse cliente j&aacute; est&aacute; cadastrado.');
            } else {
                $sql = $this->conn->prepare("UPDATE cliente SET nome = :nome, documento = :documento, cep = :cep, endereco = :endereco, bairro = :bairro, cidade = :cidade, estado = :estado, telefone = :telefone, celular = :celular, email = :email, observacao = :observacao WHERE idcliente = :idcliente");
                $sql->bindParam(':nome', $this->nome, PDO::PARAM_STR);
                $sql->bindParam(':documento', $this->documento, PDO::PARAM_STR);
                $sql->bindParam(':cep', $this->cep, PDO::PARAM_STR);
                $sql->bindParam(':endereco', $this->endereco, PDO::PARAM_STR);
                $sql->bindParam(':bairro', $this->bairro, PDO::PARAM_STR);
                $sql->bindParam(':cidade', $this->cidade, PDO::PARAM_STR);
                $sql->bindParam(':estado', $this->estado, PDO::PARAM_STR);
                $sql->bindParam(':telefone', $this->telefone, PDO::PARAM_STR);
                $sql->bindParam(':celular', $this->celular, PDO::PARAM_STR);
                $sql->bindParam(':email', $this->email, PDO::PARAM_STR);
                $sql->bindParam(':observacao', $this->observacao, PDO::PARAM_STR);
                $sql->bindParam(':idcliente', $this->idcliente, PDO::PARAM_INT);
                $sql->execute();
                
                return $sql;
            }
        }

        // delete record

        function delete() {
            $sql = $this->conn->prepare("UPDATE cliente SET monitor = :monitor WHERE idcliente = :idcliente");
            $sql->bindParam(':monitor', $this->monitor, PDO::PARAM_STR);
            $sql->bindParam(':idcliente', $this->idcliente, PDO::PARAM_INT);
            $sql->execute();

            return $sql;
        }
    }