<?php
    class Pedido {
        // database connection

        private $conn;
     
        // object properties

        public $idpedido;
        public $idcliente;
        public $codigo;
        public $datado;
        public $desconto;
        public $forma_pg;
        public $parcela;
        public $finalizado;
        public $monitor;
     
        // constructor with $db as database connection

        public function __construct($db) {
            $this->conn = $db;
        }

        // check for same record on insert

        function orderInsertExist() {
            $sql = $this->conn->prepare("SELECT idpedido FROM pedido WHERE codigo = :codigo");
            $sql->bindParam(':codigo', $this->codigo, PDO::PARAM_STR);
            $sql->execute();

                if ($sql->rowCount() > 0) {
                    return true;
                } else {
                    return false;
                }
        }

        // check for same record on update

        function orderUpdateExist() {
            $sql = $this->conn->prepare("SELECT idpedido FROM pedido WHERE codigo = :codigo AND idpedido <> :idpedido");
            $sql->bindParam(':codigo', $this->codigo, PDO::PARAM_STR);
            $sql->bindParam(':idpedido', $this->idpedido, PDO::PARAM_INT);
            $sql->execute();

                if ($sql->rowCount() > 0) {
                    return true;
                } else {
                    return false;
                }
        }

        // read all records

        function readAll() {
            #$sql = $this->conn->prepare("SELECT pedido.idpedido, pedido.codigo, pedido.datado, cliente.idcliente, cliente.nome AS cliente, cliente.celular FROM pedido INNER JOIN cliente ON cliente.idcliente = pedido.cliente_idcliente WHERE pedido.datado LIKE :datado AND pedido.monitor = :monitor AND pedido.finalizado = :finalizado ORDER BY pedido.datado, cliente.nome, pedido.codigo");
            $sql = $this->conn->prepare("SELECT pedido.idpedido, pedido.codigo, pedido.datado, pedido.finalizado, cliente.idcliente, cliente.nome AS cliente, cliente.celular FROM pedido INNER JOIN cliente ON cliente.idcliente = pedido.cliente_idcliente WHERE pedido.datado LIKE :datado AND pedido.monitor = :monitor ORDER BY pedido.datado, cliente.nome, pedido.codigo");
            $sql->bindParam(':datado', $this->datado, PDO::PARAM_STR);
            #$sql->bindParam(':finalizado', $this->finalizado, PDO::PARAM_INT);
            $sql->bindParam(':monitor', $this->monitor, PDO::PARAM_INT);
            $sql->execute();

            return $sql;
        }

        // read single record

        function readSingle() {
            #$sql = $this->conn->prepare("SELECT cliente.idcliente, cliente.nome AS cliente, cliente.telefone, cliente.celular, produto.idproduto, produto.descricao AS produto, produto.tipo, produto.tamanho, produto.cor, produto.valor_venda, pedido.idpedido, pedido.codigo, pedido.datado FROM pedido INNER JOIN cliente ON cliente.idcliente = pedido.cliente_idcliente INNER JOIN produto_no_pedido ON produto_no_pedido.pedido_idpedido = pedido.idpedido INNER JOIN produto ON produto.idproduto = produto_no_pedido.produto_idproduto WHERE idpedido = :idpedido");
            $sql = $this->conn->prepare("SELECT pedido.idpedido, pedido.codigo, pedido.datado, pedido.desconto, pedido.forma_pg, pedido.parcela, cliente.idcliente, cliente.nome AS cliente, cliente.endereco, cliente.bairro, cliente.cidade, cliente.telefone, cliente.celular, cliente.email FROM pedido INNER JOIN cliente ON cliente.idcliente = pedido.cliente_idcliente WHERE idpedido = :idpedido");
            $sql->bindParam(':idpedido', $this->idpedido, PDO::PARAM_INT);
            $sql->execute();

            return $sql;
        }

        // insert record

        function insert() {
            if ($this->orderInsertExist()) {
                die('Esse pedido j&aacute; est&aacute; cadastrado.');
            } else {
                $sql = $this->conn->prepare("INSERT INTO pedido (cliente_idcliente, codigo, datado, desconto, forma_pg, finalizado, monitor) VALUES (:idcliente, :codigo, :datado, :desconto, :forma_pg, :finalizado, :monitor)");
                $sql->bindParam(':idcliente', $this->idcliente, PDO::PARAM_INT);
                $sql->bindParam(':codigo', $this->codigo, PDO::PARAM_STR);
                $sql->bindParam(':datado', $this->datado, PDO::PARAM_STR);
                $sql->bindParam(':desconto', $this->desconto, PDO::PARAM_INT);
                $sql->bindParam(':forma_pg', $this->forma_pg, PDO::PARAM_STR);
                $sql->bindParam(':finalizado', $this->finalizado, PDO::PARAM_INT);
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
            if ($this->orderUpdateExist()) {
                die('Esse pedido j&aacute; est&aacute; cadastrado.');
            } else {
                $sql = $this->conn->prepare("UPDATE pedido SET cliente_idcliente = :idcliente WHERE idpedido = :idpedido");
                $sql->bindParam(':idcliente', $this->idcliente, PDO::PARAM_INT);
                $sql->bindParam(':idpedido', $this->idpedido, PDO::PARAM_INT);
                $sql->execute();

                return $sql;
            }
        }

        // delete record

        function delete() {
            $sql = $this->conn->prepare("UPDATE pedido SET monitor = :monitor WHERE idpedido = :idpedido");
            $sql->bindParam(':monitor', $this->monitor, PDO::PARAM_STR);
            $sql->bindParam(':idpedido', $this->idpedido, PDO::PARAM_INT);
            $sql->execute();

            return $sql;
        }
    }