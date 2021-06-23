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
    include_once 'api/objects/produto.php';
    include_once 'api/objects/caixa.php';

        // check for active user

        if (empty($_SESSION['key'])) {
            header ('location:./');
        }
    
    // get database connection

    $database = new Database();
    $db = $database->getConnection();

    // prepare object

    $caixa = new Caixa($db);

    // GET variables

    $py_idcaixa = md5('idcaixa');
    #echo $_GET[''.$py_idcaixa.'']; exit;
    $caixa->idcaixa = $_GET[''.$py_idcaixa.''];

        // retrieve query
        
        if ($sql = $caixa->readSingle()) {
            if ($sql->rowCount() > 0) {
                $row = $sql->fetch(PDO::FETCH_OBJ);
?>
<form class="form-edit-caixa">
    <div class="modal-header">
        <h4 class="modal-title">
            <span>Editar lan&ccedil;amento</span>
        </h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <input type="hidden" name="rand" id="rand_edit" value="<?php echo md5(mt_rand()); ?>">
        <input type="hidden" name="idcaixa" id="idcaixa_edit" value="<?php echo $row->idcaixa; ?>">
        <input type="hidden" name="tipo_mask" id="tipo_mask_edit" value="<?php echo $row->tipo; ?>">

        <div class="row form-group g-3 align-items-center">
            <div class="col-12">
                <!--<h6 class="text-dark">Data: <?php echo $row->datado; ?></h6>-->
            </div>
        </div>
        <div class="row form-group g-3 align-items-center">
            <div class="col-2">
                <label class="text text-danger" for="tipo">Tipo</label>
            </div>
            <div class="col-10">
            <?php
                switch ($row->tipo) {
                    case 1:
                        echo'
                        <span class="form-icheck"><input type="radio" name="tipo" value="1" checked> Positivo</span>
                        <span class="form-icheck"><input type="radio" name="tipo" value="0"> Negativo</span>';
                        break;
                    case 0:
                        echo'
                        <span class="form-icheck"><input type="radio" name="tipo" value="1"> Positivo</span>
                        <span class="form-icheck"><input type="radio" name="tipo" value="0" checked> Negativo</span>';
                        break;
                }
            ?>
            </div>
        </div>
        <div class="row form-group g-3 align-items-center">
            <div class="col-2">
                <label class="text text-danger" for="descricao">Descri&ccedil;&atilde;o</label>
            </div>
            <div class="col-10">
                <input type="text" name="descricao" id="descricao_edit" value="<?php echo $row->descricao; ?>" maxlength="200" class="form-control" title="Informe a descri&ccedil;&atilde;o" placeholder="Descri&ccedil;&atilde;o" required>
            </div>
        </div>
        <div class="row form-group g-3 align-items-center">
            <div class="col-2">
                <label class="text text-danger" for="valor">Valor</label>
            </div>
            <div class="col-10">
                <input type="text" name="valor" id="valor_edit" value="<?php echo number_format($row->valor, 2, '.', ','); ?>" maxlength="20" class="form-control col-6" title="Informe o valor" placeholder="Valor" required>
            </div>
        </div>
        <div class="row form-group g-3 align-items-center">
            <div class="col-2">
                <label class="text text-danger" for="pago">Pago</label>
            </div>
            <div class="col-10">
            <?php
                /*switch ($row->pago) {
                    case 1:
                        echo'
                        <span class="form-icheck"><input type="radio" name="pago" id="pago_edit_1" value="1" checked> Sim</span>
                        <span class="form-icheck"><input type="radio" name="pago" id="pago_edit_0" value="0"> N&atilde;o</span>';
                        break;
                    case 0:
                        echo'
                        <span class="form-icheck"><input type="radio" name="pago" id="pago_edit_1" value="1"> Sim</span>
                        <span class="form-icheck"><input type="radio" name="pago" id="pago_edit_0" value="0" checked> N&atilde;o</span>';
                        break;
                }*/
                if ($row->pago == 1) {
                    if ($row->valor_pago > 0) {
                        echo'
                        <span class="form-icheck"><input type="radio" name="pago" id="pago_edit_1" value="1" checked> Sim</span>
                        <span class="form-icheck"><input type="radio" name="pago" id="pago_edit_0" value="0"> N&atilde;o</span>';
                    } else {
                        echo'
                        <span class="form-icheck"><input type="radio" name="pago" value="1" checked> Sim</span>
                        <span class="form-icheck"><input type="radio" name="pago" value="0"> N&atilde;o</span>';
                    }
                } else if ($row->pago == 0) {
                    if (!empty($row->ref_pedido)) {
                        echo'
                        <span class="form-icheck"><input type="radio" name="pago" id="pago_edit_1" value="1"> Sim</span>
                        <span class="form-icheck"><input type="radio" name="pago" id="pago_edit_0" value="0" checked> N&atilde;o</span>';
                    } else {
                        echo'
                        <span class="form-icheck"><input type="radio" name="pago" value="1"> Sim</span>
                        <span class="form-icheck"><input type="radio" name="pago" value="0" checked> N&atilde;o</span>';
                    }
                }
            ?>
            </div>
        </div>
        <div class="row form-group g-3 align-items-center div-valor-pago d-none">
            <div class="col-2">
                <label class="text" for="valor">Valor Pago</label>
            </div>
            <div class="col-10">
                <input type="text" name="valor_pago" id="valor_pago_edit" value="<?php echo number_format($row->valor_pago, 2, '.', ','); ?>" maxlength="20" class="form-control col-6" title="Informe o valor pago" placeholder="Valor Pago">
            </div>
        </div>
    </div>
    <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
        <button type="submit" class="btn btn-primary btn-edit-caixa">Salvar</button>
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

        // ICHECK

        $("input[type='radio']").show(function () {
            $("input[type='radio']").iCheck({
                radioClass: 'iradio_minimal'
            });
        });

        $("#pago_edit_0").on("ifChecked", function(event) {
            $(".div-valor-pago").addClass('d-none');
            $("#valor_pago_edit").val('0.00');
        });

        $("#pago_edit_1").on("ifChecked", function(event) {
            $(".div-valor-pago").removeClass('d-none');
            $("#valor_pago_edit").val(<?php echo $row->valor_pago; ?>);
        });

        // INPUTMASK

        $('#valor_edit, #valor_pago_edit').inputmask({
            'alias': 'numeric',
            'groupSeparator': ',',
            'autoGroup': true,
            'digits': 2,
            'digitsOptional': false,
            'prefix': '',
            'placeholder': '0'
        });

        <?php
            if (!empty($row->ref_pedido)) {
        ?>
        
        // MANIPULE REF PEDIDO

        $("input[name='tipo']").iCheck('disable');
        $('#descricao_edit').removeAttr('required', true).attr('readonly', true);
        $('#valor_edit').removeAttr('required', true).attr('readonly', true);

        <?php        
            }

            if (($row->pago == 1) and ($row->valor_pago > 0)) {
        ?>
        
        $(".div-valor-pago").removeClass('d-none');

        <?php
            }
        ?>

        // EDIT PEDIDO

        $('.form-edit-caixa').submit(function (e) {
            e.preventDefault();

            $.post('api/caixa/update.php', $(this).serialize(), function(data) {
                $('.btn-edit-caixa').html('<img src="dist/img/rings.svg" class="loader-svg">').fadeTo(fade, 1);

                switch (data) {
                    case 'true':
                        Toast.fire({
                            icon: 'success',
                            title: 'Lan&ccedil;amento editado.'
                        }).then((result) => {
                            window.setTimeout("location.href='caixa'", delay);
                        });
                        break;

                    default:
                        Toast.fire({
                            icon: 'error',
                            title: data
                        });
                        break;
                }

                $('.btn-edit-caixa').html('Salvar').fadeTo(fade, 1);
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
                    <p>O lan&ccedil;amento n&atilde;o foi encontrado.</p>
                </blockquote>';
            }
        } else {
            die(var_dump($db->errorInfo()));
        }
?>