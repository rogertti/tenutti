<?php
    // clear cache
    
    header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");
    //header("Content-Type: application/xml; charset=utf-8");

    // require and includes

    require_once 'appConfig.php';
    include_once 'api/config/database.php';
    include_once 'api/objects/cliente.php';
    #include_once 'api/objects/produto.php';
    include_once 'api/objects/pedido.php';

        // check for active user

        if (empty($_SESSION['key'])) {
            header ('location:./');
        }
    
    // get database connection

    $database = new Database();
    $db = $database->getConnection();

    // prepare object

    $cliente = new Cliente($db);
    #$produto = new Produto($db);
    $pedido = new Pedido($db);

    // GET variables

    $py_idpedido = md5('idpedido');
    $pedido->idpedido = $_GET[''.$py_idpedido.''];
    #$produto->monitor = 1;
    $cliente->monitor = 1;

        // retrieve query
        
        if ($sql = $pedido->readSingle()) {
            if ($sql->rowCount() > 0) {
                $row = $sql->fetch(PDO::FETCH_OBJ);
?>
<form class="form-edit-pedido">
    <div class="modal-header">
        <h4 class="modal-title">
            <span>Editar o pedido</span>
        </h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <input type="hidden" name="rand" id="rand_edit" value="<?php echo md5(mt_rand()); ?>">
        <input type="hidden" name="idpedido" id="idpedido_edit" value="<?php echo $row->idpedido; ?>">

        <div class="row form-group g-3 align-items-center">
            <div class="col-2">
                <label class="text text-danger" for="cliente">Cliente</label>
            </div>
            <div class="col-10">
                <select name="cliente" id="cliente_edit" class="selectpicker show-tick form-control" title="Selecione o cliente" placeholder="Cliente" data-live-search="true" data-width="" data-size="7" required>
                <?php
                    $sql2 = $cliente->readAll();

                        if ($sql2->rowCount() > 0) {
                            while($row2 = $sql2->fetch(PDO::FETCH_OBJ)) {
                                if ($row2->idcliente == $row->idcliente) {
                                    echo'<option title="'.$row2->nome.'" data-subtext="'.$row2->documento.' - '.$row2->celular.'" value="'.$row2->idcliente.'" selected>'.$row2->nome.'</option>';
                                } else {
                                    echo'<option title="'.$row2->nome.'" data-subtext="'.$row2->documento.' - '.$row2->celular.'" value="'.$row2->idcliente.'">'.$row2->nome.'</option>';
                                }
                            }
                        } else {
                            echo'<option value="" selected>Nenhum cliente cadastrado</option>';
                        }
                ?>
                </select>
            </div>
        </div>
        <!--<div class="row form-group g-3 align-items-center">
            <div class="col-2">
                <label class="text text-danger" for="produto">Produto</label>
            </div>
            <div class="col-10">
                <select name="produto" id="produto_edit" class="selectpicker show-tick form-control" title="Selecione o produto" placeholder="Produto" data-live-search="true" data-width="" data-size="7" required>
                <?php
                    /*$sql3 = $produto->readAll();

                        if ($sql3->rowCount() > 0) {
                            while($row3 = $sql3->fetch(PDO::FETCH_OBJ)) {
                                if ($row3->idproduto == $row->idproduto) {
                                    echo'<option title="'.$row3->descricao.' '.$row3->tipo.' '.$row3->tamanho.' '.$row3->cor.'" data-subtext="'.$row3->tipo.' '.$row3->tamanho.' '.$row3->cor.'" value="'.$row3->idproduto.'" selected>'.$row3->descricao.'</option>';
                                } else {
                                    echo'<option title="'.$row3->descricao.' '.$row3->tipo.' '.$row3->tamanho.' '.$row3->cor.'" data-subtext="'.$row3->tipo.' '.$row3->tamanho.' '.$row3->cor.'" value="'.$row3->idproduto.'">'.$row3->descricao.'</option>';
                                }
                            }
                        } else {
                            echo'<option value="" selected>Nenhum produto cadastrado</option>';
                        }*/
                ?>
                </select>
            </div>
        </div>-->
    </div>
    <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
        <button type="submit" class="btn btn-primary btn-edit-pedido">Salvar</button>
    </div>
</form>
<script defer>
    $(document).ready(function () {
        const fade = 150,
            delay = 100,
            timeout = 60000,
            swalButton = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-danger',
                    cancelButton: 'btn btn-secondary'
                },
                buttonsStyling: true
            }),
            Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 1000
            });

        // SELECT PICKER

        $('#cliente_edit, #produto_edit').selectpicker();

        // EDITAR PEDIDO

        $('.form-edit-pedido').submit(function (e) {
            e.preventDefault();

            $.post('api/pedido/update.php', $(this).serialize(), function(data) {
                $('.btn-edit-pedido').html('<img src="dist/img/rings.svg" class="loader-svg">').fadeTo(fade, 1);

                switch (data) {
                    case 'true':
                        Toast.fire({
                            icon: 'success',
                            title: 'Pedido editado.'
                        }).then((result) => {
                            window.setTimeout("location.href='inicio'", delay);
                        });
                        break;

                    default:
                        Toast.fire({
                            icon: 'error',
                            title: data
                        });
                        break;
                }

                $('.btn-edit-pedido').html('Salvar').fadeTo(fade, 1);
            });

            return false;
        });
    });
</script>
<?php
            } else {
                echo'
                <blockquote class="quote-danger">
                    <h5>Erro</h5>
                    <p>O pedido não foi encontrado.</p>
                </blockquote>';
            }
        } else {
            die(var_dump($db->errorInfo()));
        }
?>