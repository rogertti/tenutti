<?php
    require_once 'appConfig.php';

        if (empty($_SESSION['key'])) {
            header ('location:./');
        }

    $menu = 3;
    #echo md5('idproduto');
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title><?php echo $cfg['head_title']; ?></title>
        <!-- Tell the browser to be responsive to screen width -->
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- Favicon -->
        <link rel="icon" type="image/png" href="dist/img/favicon.png">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
        <!-- Ionicons -->
        <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
        <!-- DataTables -->
        <link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
        <link rel="stylesheet" href="plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
        <!-- Select Picker -->
        <link rel="stylesheet" href="plugins/bootstrap-select-1.13.14/css/bootstrap-select.min.css">
        <!-- Icheck -->
        <link rel="stylesheet" href="plugins/icheck-1.0.3/skins/all.css">
        <!-- SweetAlert2 -->
        <link rel="stylesheet" href="plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
        <!-- AdminLTE App -->
        <link rel="stylesheet" href="dist/css/adminlte.min.css">
        <!-- Custom App -->
        <link rel="stylesheet" href="dist/css/main.css">
        <!-- Google Font: Source Sans Pro -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700">
    </head>
    <body class="hold-transition layout-navbar-fixed sidebar-mini sidebar-collapse text-sm">

        <!-- Site wrapper -->
        <div class="wrapper">
            <?php
                include_once 'appNavbar.php';
                include_once 'appSidebar.php';
            ?>

            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <?php include_once 'appSearch.php'; ?>

                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-12">
                                <h1>
                                    <span>Produtos</span>
                                    <span class="float-right">
                                      <a href="#" class="btn btn-primary" title="Clique para cadastrar um novo produto" data-toggle="modal" data-target="#modal-new-produto">
                                        <i class="fas fa-tag"></i> Novo produto
                                      </a>
                                    </span>
                                    <span></span>
                                </h1>
                            </div>
                        </div>
                    </div>
                    <!-- /.container-fluid -->
                </section>

                <!-- Main content -->
                <section class="content">
                    <!-- Default box -->
                    <div class="card">
                        <div class="card-body">
                            <div class="div-load-page d-none"></div>
                          
                            <table class="table table-bordered table-hover table-data d-none">
                                <thead>
                                    <th>Descri&ccedil;&atilde;o</th>
                                    <th>Tipo</th>
                                    <th>Tamanho</th>
                                    <th>Cor</th>
                                    <th>Custo</th>
                                    <th>Venda</th>
                                    <th style="max-width: 100px;width: 90px;"></th>
                                </thead>
                                <tbody>
                                </tbody>
                                <tfoot>
                                    <th>Descri&ccedil;&atilde;o</th>
                                    <th>Tipo</th>
                                    <th>Tamanho</th>
                                    <th>Cor</th>
                                    <th>Custo</th>
                                    <th>Venda</th>
                                    <th style="max-width: 100px;width: 90px;"></th>
                                </tfoot>
                            </table>

                            <blockquote class="blockquote-data d-none">
                                <h5>Nada encontrado</h5>
                                <p>Nenhum produto cadastrado.</p>
                            </blockquote>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </section>
                <!-- /.content -->
            </div>
            <!-- /.content-wrapper -->

            <!-- Modal -->
            <div class="modal fade" id="modal-new-produto">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form class="form-new-produto">
                            <div class="modal-header">
                                <h4 class="modal-title">
                                    <span>Novo produto</span>
                                </h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="rand" id="rand" value="<?php echo rand(); ?>">
                                
                                <div class="row form-group g-3 align-items-center">
                                    <div class="col-2">
                                        <label class="text text-danger" for="descricao">Descri&ccedil;&atilde;o</label>
                                    </div>
                                    <div class="col-10">
                                        <!--<input type="text" name="descricao" id="descricao" maxlength="200" class="form-control" title="Informe a descri&ccedil;&atilde;o do produto" placeholder="Descri&ccedil;&atilde;o" required>-->
                                        <select name="descricao" id="descricao" class="selectpicker show-tick form-control" title="Selecione a Descri&ccedil;&atilde;o" placeholder="Descri&ccedil;&atilde;o" data-width="" data-size="7" required>
                                            <option value="" selected>Selecione a Descri&ccedil;&atilde;o</option>
                                            <option value="Camiseta">Camiseta</option>
                                            <option value="Moleton">Moleton</option>
                                            <option value="Servi&ccedil;o">Servi&ccedil;o</option>
                                            <option value="Tela">Tela</option>
                                            <option value="Manuten&ccedil;&atilde;o Tela">Manuten&ccedil;&atilde;o de Tela</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row form-group g-3 align-items-center div-tipo">
                                    <div class="col-2">
                                        <label class="text text-danger" for="tipo">Tipo</label>
                                    </div>
                                    <div class="col-10">
                                        <select name="tipo" id="tipo" class="selectpicker show-tick form-control" title="Selecione o Tipo" placeholder="Tipo" data-width="" data-size="7" required>
                                            <option value="" selected>Selecione o Tipo</option>
                                            <option value="Babylong">Babylong</option>
                                            <option value="Blusao">Blus&atilde;o</option>
                                            <option value="Canguru">Canguru</option>
                                            <option value="Estonada">Estonada</option>
                                            <option value="Estonada Babylong">Estonada Babylong</option>
                                            <option value="Premium">Premium</option>
                                            <option value="Premium Gola V">Premium Gola V</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row form-group g-3 align-items-center div-tamanho">
                                    <div class="col-2">
                                        <label class="text text-danger" for="tamanho">Tamanho</label>
                                    </div>
                                    <div class="col-10">
                                        <select name="tamanho" id="tamanho" class="selectpicker show-tick form-control" title="Selecione o Tamanho" placeholder="Tamanho" data-width="" data-size="7" required>
                                            <option value="" selected>Selecione o Tamanho</option>
                                            <option value="3P">3P</option>
                                            <option value="PP">PP</option>
                                            <option value="P">P</option>
                                            <option value="M">M</option>
                                            <option value="G">G</option>
                                            <option value="GG">GG</option>
                                            <option value="3G">3G</option>
                                            <option value="4G">4G</option>
                                            <option value="5G">5G</option>
                                            <option value="6G">6G</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row g-3 form-group align-items-center div-cor">
                                    <div class="col-2">
                                        <label class="col-form-label text text-danger" for="cor">Cor</label>
                                    </div>
                                    <div class="col-10">
                                        <select name="cor" id="cor" class="selectpicker show-tick form-control" title="Selecione a Cor" placeholder="Cor" data-width="" data-size="7" required>
                                            <option value="" selected>Selecione a Cor</option>
                                            <option value="Amarelo" data-content="<span class='badge badge-option' style='color: #555;background-color: yellow;'>AMARELO</span>">Amarelo</option>
                                            <option value="Azul" data-content="<span class='badge badge-option' style='color: white;background-color: blue;'>AZUL</span>">Azul</option>
                                            <option value="Branco" data-content="<span class='badge badge-option' style='color: #aaa;border: 1px solid #bbb;'>BRANCO</span>">Branco</option>
                                            <option value="Cinza" data-content="<span class='badge badge-option' style='color: black;background-color: #ccc;'>CINZA</span>">Cinza</option>
                                            <option value="Mescla" data-content="<span class='badge badge-option' style='color: black;background-color: grey;'>MESCLA</span>">Mescla</option>
                                            <option value="Preto" data-content="<span class='badge badge-option' style='color: white;background-color: black;'>PRETO</span>">Preto</option>
                                            <option value="Rosa" data-content="<span class='badge badge-option' style='color: #888;background-color: pink;'>ROSA</span>">Rosa</option>
                                            <option value="Roxo" data-content="<span class='badge badge-option' style='color: white;background-color: purple;'>ROXO</span>">Roxo</option>
                                            <option value="Verde" data-content="<span class='badge badge-option' style='color: white;background-color: green;'>VERDE</span>">Verde</option>
                                            <option value="Vermelho" data-content="<span class='badge badge-option' style='color: white;background-color: red;'>VERMELHO</span>">Vermelho</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row form-group g-3 align-items-center">
                                    <div class="col-2">
                                        <label class="text" for="valor_custo">Custo</label>
                                    </div>
                                    <div class="col-10">
                                        <input type="text" name="valor_custo" id="valor_custo" maxlength="20" class="form-control col-6" title="Informe o valor de custo" placeholder="Valor de custo">
                                    </div>
                                </div>
                                <div class="row form-group g-3 align-items-center">
                                    <div class="col-2">
                                        <label class="text text-danger" for="valor_venda">Venda</label>
                                    </div>
                                    <div class="col-10">
                                        <input type="text" name="valor_venda" id="valor_venda" maxlength="20" class="form-control col-6" title="Informe o valor de venda" placeholder="Valor de venda" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="observacao">Observa&ccedil;&atilde;o</label>
                                    <textarea name="observacao" id="observacao" class="form-control" title="Se tiver alguma observa&ccedil;&atilde;o" placeholder="Observa&ccedil;&atilde;o"></textarea>
                                </div>
                            </div>
                            <div class="modal-footer justify-content-between">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                                <button type="submit" class="btn btn-primary btn-new-produto">Salvar</button>
                            </div>
                        </form>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!-- /.modal -->

            <div class="modal fade" id="modal-edit-produto">
                <div class="modal-dialog">
                    <div class="modal-content"></div>
                </div>
            </div>

            <?php include_once 'appFootbar.php'; ?>
        </div>
        <!-- ./wrapper -->

        <!-- jQuery -->
        <script src="plugins/jquery/jquery.min.js"></script>
        <!-- Bootstrap 4 -->
        <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
        <!-- DataTables -->
        <script src="plugins/datatables/jquery.dataTables.min.js"></script>
        <script src="plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
        <script src="plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
        <script src="plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
        <!-- InputMask -->
        <script src="plugins/inputmask/jquery.inputmask.bundle.min.js"></script>
        <!-- Select Picker -->
        <script src="plugins/bootstrap-select-1.13.14/js/bootstrap-select.min.js"></script>
        <!-- Icheck -->
        <script src="plugins/icheck-1.0.3/icheck.min.js"></script>
        <!-- SweetAlert2 -->
        <script src="plugins/sweetalert2/sweetalert2.min.js"></script>
        <!-- AdminLTE App -->
        <script src="dist/js/adminlte.min.js"></script>
        <!-- Custom App -->
        <script defer src="dist/js/main.js"></script>
        <script defer>
            $(document).ready(function() {
                const fade = 150, delay = 100, timeout = 60000, showmap = false, autobusca = true,
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

                // PULL DATA

                (function pullData() {
                    $.ajax({
                        type: 'GET',
                        url: 'api/produto/readAll.php',
                        dataType: 'json',
                        cache: false,
                        beforeSend: function(result) {                        
                            $('.div-load-page').removeClass('d-none').html('<p class="lead text-center"><i class="fas fa-cog fa-spin"></i></p>');
                        },
                        error: function(result) {
                            Swal.fire({icon: 'error',html: result.statusText,showConfirmButton: false});
                        },
                        success: function(data) {
                            if (!data[0]) {
                                $('.div-load-page').addClass('d-none');
                                $('.table-data').addClass('d-none');
                                $('.blockquote-data').removeClass('d-none');
                            } else {
                                if (data[0].status == true) {
                                    let cor, response = '';

                                        for (let i in data) {
                                            if (data[i].cor === 'Amarelo') { data[i].cor = '<span class="badge badge-option text-uppercase" style="color: #555;background-color: yellow;">' + data[i].cor + '</span>'; }
                                            if (data[i].cor === 'Azul') { data[i].cor = '<span class="badge badge-option text-uppercase" style="color: white;background-color: blue;">' + data[i].cor + '</span>'; }
                                            if (data[i].cor === 'Branco') { data[i].cor = '<span class="badge badge-option text-uppercase" style="color: #aaa;border: 1px solid #bbb;">' + data[i].cor + '</span>'; }
                                            if (data[i].cor === 'Cinza') { data[i].cor = '<span class="badge badge-option text-uppercase" style="color: white;background-color: #ccc;">' + data[i].cor + '</span>'; }
                                            if (data[i].cor === 'Mescla') { data[i].cor = '<span class="badge badge-option text-uppercase" style="color: white;background-color: grey;">' + data[i].cor + '</span>'; }
                                            if (data[i].cor === 'Preto') { data[i].cor = '<span class="badge badge-option text-uppercase" style="color: white;background-color: black;">' + data[i].cor + '</span>'; }
                                            if (data[i].cor === 'Rosa') { data[i].cor = '<span class="badge badge-option text-uppercase" style="color: #888;background-color: pink;">' + data[i].cor + '</span>'; }
                                            if (data[i].cor === 'Roxo') { data[i].cor = '<span class="badge badge-option text-uppercase" style="color: white;background-color: purple;">' + data[i].cor + '</span>'; }
                                            if (data[i].cor === 'Verde') { data[i].cor = '<span class="badge badge-option text-uppercase" style="color: white;background-color: green;">' + data[i].cor + '</span>'; }
                                            if (data[i].cor === 'Vermelho') { data[i].cor = '<span class="badge badge-option text-uppercase" style="color: white;background-color: red;">' + data[i].cor + '</span>'; }

                                            response += '<tr>'
                                            + '<td>' + data[i].descricao + '</td>'
                                            + '<td>' + data[i].tipo + '</td>'
                                            + '<td>' + data[i].tamanho + '</td>'
                                            + '<td>' + data[i].cor + '</td>'
                                            + '<td>' + data[i].valor_custo + '</td>'
                                            + '<td>' + data[i].valor_venda + '</td>'
                                            + '<td class="td-action">'
                                            + '<span class="bg bg-warning"><a class="fas fa-pen a-edit-produto" href="produtoEdit.php?e8c7475fdc16097f93c00b2f67694119=' + data[i].idproduto + '" title="Editar os dados do produto"></a></span>'
                                            + '<span class="bg bg-danger"><a class="fas fa-trash a-delete-produto" id="e8c7475fdc16097f93c00b2f67694119-' + data[i].idproduto + '" href="#" title="Excluir o cadastro do produto"></a></span>'
                                            + '</td></tr>';
                                        }

                                    $('.div-load-page').addClass('d-none');
                                    $('.blockquote-data').addClass('d-none');
                                    $('.table-data').removeClass('d-none');
                                    $('.table-data tbody').html(response);

                                    // TOOLTIP

                                    $('div a, td span a, span a').tooltip({boundary: 'window'});

                                    // DATATABLE

                                    $('.table-data').DataTable({
                                        "paging": true,
                                        "lengthChange": false,
                                        "searching": true,
                                        "ordering": true,
                                        "info": true,
                                        "autoWidth": false,
                                        "responsive": true,
                                        "destroy": true
                                    });
                                } else {
                                    $('.div-load-page').addClass('d-none');
                                    $('.table-data').addClass('d-none');
                                    $('.blockquote-data').removeClass('d-none');
                                }
                            }
                        },
                        complete: setTimeout(function() { pullData(); }, timeout),
                        timeout: timeout
                    });
                })();
                
                // MODAL
                
                $('.table-data').on('click', '.a-edit-produto', function(e) {
                    e.preventDefault();
                    $('#modal-edit-produto').modal('show').find('.modal-content').load($(this).attr('href'));
                });

                // SELECT PICKER

                $('#descricao, #tipo, #tamanho, #cor').selectpicker();

                $('#descricao').change(() => {
                    let option = $('#descricao option').filter(':selected').val();
                    
                        switch (option) {
                            case 'Serviço':
                                $(".div-tipo").addClass('d-none');
                                $("#tipo").removeAttr('required', true).val('');
                                $(".div-tamanho").addClass('d-none');
                                $("#tamanho").removeAttr('required', true).val('');
                                $(".div-cor").addClass('d-none');
                                $("#cor").removeAttr('required', true).val('');
                                break;
                            case 'Tela':
                                $(".div-tipo").addClass('d-none');
                                $("#tipo").removeAttr('required', true).val('');
                                $(".div-tamanho").addClass('d-none');
                                $("#tamanho").removeAttr('required', true).val('');
                                $(".div-cor").addClass('d-none');
                                $("#cor").removeAttr('required', true).val('');
                                break;
                            case 'Manutenção Tela':
                                $(".div-tipo").addClass('d-none');
                                $("#tipo").removeAttr('required', true).val('');
                                $(".div-tamanho").addClass('d-none');
                                $("#tamanho").removeAttr('required', true).val('');
                                $(".div-cor").addClass('d-none');
                                $("#cor").removeAttr('required', true).val('');
                                break;
                            default:
                                $(".div-tipo").removeClass('d-none');
                                $("#tipo").attr('required', true).val('')
                                $(".div-tamanho").removeClass('d-none');
                                $("#tamanho").attr('required', true).val('')
                                $(".div-cor").removeClass('d-none');
                                $("#cor").attr('required', true).val('');
                                break;
                        }
                });

                // INPUTMASK

                $('#valor_custo, #valor_venda').inputmask({
                    'alias': 'numeric',
                    'groupSeparator': ',',
                    'autoGroup': true,
                    'digits': 2,
                    'digitsOptional': false,
                    'prefix': '',
                    'placeholder': '0'
                });

                // NOVO PRODUTO

                $('.form-new-produto').submit(function(e) {
                    e.preventDefault();

                    $.post('api/produto/insert.php', $(this).serialize(), function(data) {
                        $('.btn-new-produto').html('<img src="dist/img/rings.svg" class="loader-svg">').fadeTo(fade, 1);

                        switch(data) {
                        case 'true':
                            Toast.fire({icon: 'success',title: 'Produto cadastrado.'}).then((result) => {
                                window.setTimeout("location.href='produto'", delay);
                            });
                            break;

                        default:
                            Toast.fire({icon: 'error',title: data});
                            break;
                        }

                        $('.btn-new-produto').html('Salvar').fadeTo(fade, 1);
                    });

                    return false;
                });

                // DELETE PRODUTO

                $('.table-data').on('click', '.a-delete-produto', function(e) {
                    e.preventDefault();
                    
                    let click = this.id.split('-'),
                        py = click[0],
                        id = click[1];
                    
                    swalButton.fire({
                        icon: 'question',
                        title: 'Excluir o cadastro do produto',
                        showCancelButton: true,
                        confirmButtonText: 'Sim',
                        cancelButtonText: 'Não',
                    }).then((result) => {
                        if (result.value == true) {
                            $.ajax({
                                type: 'GET',
                                url: 'api/produto/delete.php?' + py + '=' + id,
                                dataType: 'json',
                                cache: false,
                                error: function(result) {
                                    Swal.fire({icon: 'error',html: result.responseText,showConfirmButton: false});
                                },
                                success: function(data) {
                                    if (data == true) {
                                        Toast.fire({icon: 'success',title: 'Produto exclu&iacute;do.'}).then((result) => {
                                            window.setTimeout("location.href='produto'", delay);
                                        });
                                    }
                                }
                            });
                        }
                    });
                });
            });
        </script>
    </body>
</html>