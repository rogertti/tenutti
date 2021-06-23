<?php
    require_once 'appConfig.php';
    include_once 'api/config/database.php';
    include_once 'api/objects/caixa.php';

        // user control

        if (empty($_SESSION['key'])) {
            header ('location:./');
        }

        // create ticket

        function createCode() {
            if (PHP_VERSION >= 7) {
                $bytes = random_bytes(5);
                $bytes = strtoupper(bin2hex($bytes));
            } else {
                $bytes = openssl_random_pseudo_bytes(ceil(20 / 2));
                $bytes = strtoupper(substr(bin2hex($bytes), 10));
            }

            return $bytes;
        }

    // get timestamp

    $timestamp = new DateTime();
    
    // get database connection

    $database = new Database();
    $db = $database->getConnection();

    // prepare objects
    
    $caixa = new Caixa($db);

    // datepicker control

    $getmes = md5('mes');
    $getano = md5('ano');

        if (isset($_GET[''.$getmes.''])) {
            $mes = $_GET[''.$getmes.''];
        } else {
            $mes = date('m');
        }

        if (isset($_GET['left'])) {
            if ($mes == '12') {
                $ano = $_GET[''.$getano.''] - 1;
            } else {
                $ano = $_GET[''.$getano.''];
            }
        }

        if (isset($_GET['right'])) {
            if ($mes == '01') {
                $ano = $_GET[''.$getano.''] + 1;
            } else {
                $ano = $_GET[''.$getano.''];
            }
        }

        if (isset($_GET['pick'])) {
            $ano = $_GET[''.$getano.''];
        }

        if ((!isset($_GET['left'])) and (!isset($_GET['right'])) and (!isset($_GET['pick']))) {
            $ano = date('Y');
        }

        function mes_extenso($fmes) {
            switch ($fmes) {
                case '01': $fmes = 'Janeiro'; break;
                case '02': $fmes = 'Fevereiro'; break;
                case '03': $fmes = 'Mar&ccedil;o'; break;
                case '04': $fmes = 'Abril'; break;
                case '05': $fmes = 'Maio'; break;
                case '06': $fmes = 'Junho'; break;
                case '07': $fmes = 'Julho'; break;
                case '08': $fmes = 'Agosto'; break;
                case '09': $fmes = 'Setembro'; break;
                case '10': $fmes = 'Outubro'; break;
                case '11': $fmes = 'Novembro'; break;
                case '12': $fmes = 'Dezembro'; break;
            }

            return $fmes;
        }

    $mesleft = $mes - 1;
    $mesright = $mes + 1;

        if (strlen($mesleft) == 1) {
            $mesleft = '0'.$mesleft;

                if ($mesleft == '00') {
                    $mesleft = '12';
                }
        }

        if (strlen($mesright) == 1) {
            $mesright = '0'.$mesright;

                if ($mesright == '13') {
                    $mesright = '01';
                }
        } else {
            if ($mesright == '13') {
                $mesright = '01';
            }
        }

    $menu = 4;
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
    <!-- Icheck -->
    <link rel="stylesheet" href="plugins/icheck-1.0.3/skins/all.css">
    <!-- DatePicker -->
    <link rel="stylesheet" href="plugins/datepicker/css/datepicker.min.css">
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
                                <span>Caixa</span>
                                <span class="float-right">
                                    <a href="#" class="btn btn-primary" title="Clique para adicionar um lan&ccedil;amento" data-toggle="modal" data-target="#modal-new-caixa">
                                        <i class="fas fa-wallet"></i> Novo lan&ccedil;amento
                                    </a>
                                </span>
                                <span></span>
                            </h1>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>

            <!-- Main content -->
            <section class="content">
                <!-- Default box -->
                <div class="card">
                    <div class="card-body">
                        <div class="div-load-page d-none"></div>

                        <?php
                            // Filter URL

                            $lefturl = 'caixa?' . $getmes . '=' . $mesleft . '&' . $getano . '=' . $ano . '&left=1';
                            #$lefturl = filter_var($lefturl, FILTER_SANITIZE_ENCODED);
                            $righturl = 'caixa?' . $getmes . '=' . $mesright . '&' . $getano . '=' . $ano . '&right=1';
                            #$righturl = filter_var($righturl, FILTER_SANITIZE_ENCODED);
                        ?>

                        <div class="div-time">
                            <div class="div-time-left text-center">
                                <!--<a class="lead" href="inicio?<?php echo $getmes; ?>=<?php echo $mesleft; ?>&<?php echo $getano; ?>=<?php echo $ano; ?>&left=1" title="M&ecirc;s anterior">-->
                                <a class="lead" href="<?php echo $lefturl; ?>" title="M&ecirc;s anterior">
                                    <i class="fas fa-arrow-left"></i>
                                </a>
                            </div>
                            <div class="div-time-center">
                                <p class="lead text-center">
                                    <input type="text" class="col-12 date-pick text-center" value="<?php echo mes_extenso($mes); ?> de <?php echo $ano; ?>" readonly>
                                </p>
                            </div>
                            <div class="div-time-right text-center">
                                <!--<a class="lead" href="inicio?<?php echo $getmes; ?>=<?php echo $mesright; ?>&<?php echo $getano; ?>=<?php echo $ano; ?>&right=1" title="Pr&oacute;ximo m&ecirc;s">-->
                                <a class="lead" href="<?php echo $righturl; ?>" title="Pr&oacute;ximo m&ecirc;s">
                                    <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>

                        <table class="table table-bordered table-hover table-data d-none">
                            <thead>
                                <tr>
                                    <th>Data</th>
                                    <th>Pedido</th>
                                    <th>Descri&ccedil;&atilde;o</th>
                                    <th>Tipo</th>
                                    <th>Valor</th>
                                    <th style="max-width: 100px;width: 90px;"></th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Data</th>
                                    <th>Pedido</th>
                                    <th>Descri&ccedil;&atilde;o</th>
                                    <th>Tipo</th>
                                    <th class="th-total"></th>
                                    <th style="max-width: 100px;width: 90px;"></th>
                                </tr>
                            </tfoot>
                        </table>

                        <blockquote class="blockquote-data d-none">
                            <h5>Nada encontrado</h5>
                            <p>Nenhum lan&ccedil;amento aberto.</p>
                        </blockquote>
                    </div>
                </div>
                <!-- /.card -->
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

        <div class="modal fade" id="modal-new-caixa">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form class="form-new-caixa">
                        <div class="modal-header">
                            <h4 class="modal-title">
                                <span>Novo lan&ccedil;amento</span>
                            </h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="rand" id="rand" value="<?php echo md5(mt_rand()); ?>">
                            <input type="hidden" name="codigo" id="codigo" value="<?php $codigo = createCode(); echo $codigo; ?>">
                            <input type="hidden" name="datado" id="datado" value="<?php echo $timestamp->format('Y-m-d H:i:s'); ?>">

                            <div class="row form-group g-3 align-items-center">
                                <div class="col-12">
                                    <h6 class="text-dark">Data: <?php echo date('d/m/Y'); ?></h6>
                                </div>
                            </div>
                            <div class="row form-group g-3 align-items-center">
                                <div class="col-2">
                                    <label class="text text-danger" for="tipo">Tipo</label>
                                </div>
                                <div class="col-10">
                                    <span class="form-icheck"><input type="radio" name="tipo" value="1" checked> Positivo</span>
                                    <span class="form-icheck"><input type="radio" name="tipo" value="0"> Negativo</span>
                                </div>
                            </div>
                            <div class="row form-group g-3 align-items-center">
                                <div class="col-2">
                                    <label class="text text-danger" for="descricao">Descri&ccedil;&atilde;o</label>
                                </div>
                                <div class="col-10">
                                    <input type="text" name="descricao" id="descricao" maxlength="200" class="form-control" title="Informe a descri&ccedil;&atilde;o" placeholder="Descri&ccedil;&atilde;o" required>
                                </div>
                            </div>
                            <div class="row form-group g-3 align-items-center">
                                <div class="col-2">
                                    <label class="text text-danger" for="valor">Valor</label>
                                </div>
                                <div class="col-10">
                                    <input type="text" name="valor" id="valor" maxlength="20" class="form-control col-6" title="Informe o valor" placeholder="Valor" required>
                                </div>
                            </div>
                            <div class="row form-group g-3 align-items-center">
                                <div class="col-2">
                                    <label class="text text-danger" for="pago">Pago</label>
                                </div>
                                <div class="col-10">
                                    <span class="form-icheck"><input type="radio" name="pago" value="1" checked> Sim</span>
                                    <span class="form-icheck"><input type="radio" name="pago" value="0"> N&atilde;o</span>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                            <button type="submit" class="btn btn-primary btn-new-caixa">Salvar</button>
                        </div>
                    </form>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>

        <div class="modal fade" id="modal-edit-caixa">
            <div class="modal-dialog">
                <div class="modal-content"></div>
            </div>
        </div>

        <div class="modal fade" id="modal-view-pedido">
            <div class="modal-dialog modal-xl">
                <div class="modal-content"></div>
            </div>
        </div>
        <!-- /.modal -->

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
    <!-- Icheck -->
    <script src="plugins/icheck-1.0.3/icheck.min.js"></script>
    <!-- InputMask -->
    <script src="plugins/inputmask/jquery.inputmask.bundle.min.js"></script>
    <!-- DatePicker -->
    <script src="plugins/datepicker/js/datepicker.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="plugins/sweetalert2/sweetalert2.min.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/adminlte.min.js"></script>
    <!-- Custom App -->
    <script defer src="dist/js/main.js"></script>
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

            // PULL DATA

            (function pullData() {
                $.ajax({
                    type: 'GET',
                    url: 'api/caixa/readAll.php?<?php echo $getmes; ?>=<?php echo $mes; ?>&<?php echo $getano; ?>=<?php echo $ano; ?>',
                    dataType: 'json',
                    cache: false,
                    beforeSend: function(result) {                        
                        $('.div-load-page').removeClass('d-none').html('<p class="lead text-center"><i class="fas fa-cog fa-spin"></i></p>');
                    },
                    error: function(result) {
                        Swal.fire({icon: 'error',html: result.responseText,showConfirmButton: false});
                    },
                    success: function(data) {
                        if (!data[0]) {
                            $('.div-load-page').addClass('d-none');
                            $('.table-data').addClass('d-none');
                            $('.blockquote-data').removeClass('d-none');
                        } else {
                            if (data[0].status == true) {
                                let total_recebido, total_receber, total_pago, total_pagar, total_geral, desc_valor_pago, response = '', arr = data.length - 1;
                                
                                    for(let i in data) {
                                        if (i == arr) {
                                            total_recebido = data[i].total_recebido;
                                            total_receber = data[i].total_receber;
                                            total_pago = data[i].total_pago;
                                            total_pagar = data[i].total_pagar;
                                            //total_pago = Math.abs(data[i].total_pago);
                                            //total_pagar = Math.abs(data[i].total_pagar);
                                            total_geral = total_recebido - Math.abs(total_pago);

                                                if (total_recebido > 0) {
                                                    total_recebido = new Intl.NumberFormat('pt-BR', {style: 'currency', currency: 'BRL'}).format(total_recebido);
                                                    total_recebido = '<span class="text text-success">' + total_recebido + '</span>';
                                                } else if (total_recebido < 0) {
                                                    total_recebido = new Intl.NumberFormat('pt-BR', {style: 'currency', currency: 'BRL'}).format(total_recebido);
                                                    total_recebido = '<span class="text text-danger">' + total_recebido + '</span>';
                                                } else {
                                                    total_recebido = new Intl.NumberFormat('pt-BR', {style: 'currency', currency: 'BRL'}).format(total_recebido);
                                                    total_recebido = '<span class="text">' + total_recebido + '</span>';
                                                }

                                                if (total_receber > 0) {
                                                    total_receber = new Intl.NumberFormat('pt-BR', {style: 'currency', currency: 'BRL'}).format(total_receber);
                                                    total_receber = '<span class="text text-success">' + total_receber + '</span>';
                                                } else if (total_receber < 0) {
                                                    total_receber = new Intl.NumberFormat('pt-BR', {style: 'currency', currency: 'BRL'}).format(total_receber);
                                                    total_receber = '<span class="text text-danger">' + total_receber + '</span>';
                                                } else {
                                                    total_receber = new Intl.NumberFormat('pt-BR', {style: 'currency', currency: 'BRL'}).format(total_receber);
                                                    total_receber = '<span class="text">' + total_receber + '</span>';
                                                }

                                                if (total_pago > 0) {
                                                    total_pago = new Intl.NumberFormat('pt-BR', {style: 'currency', currency: 'BRL'}).format(total_pago);
                                                    total_pago = '<span class="text text-success">' + total_pago + '</span>';
                                                } else if (total_pago < 0) {
                                                    total_pago = new Intl.NumberFormat('pt-BR', {style: 'currency', currency: 'BRL'}).format(total_pago);
                                                    total_pago = '<span class="text text-danger">' + total_pago + '</span>';
                                                } else {
                                                    total_pago = new Intl.NumberFormat('pt-BR', {style: 'currency', currency: 'BRL'}).format(total_pago);
                                                    total_pago = '<span class="text">' + total_pago + '</span>';
                                                }

                                                if (total_pagar > 0) {
                                                    total_pagar = new Intl.NumberFormat('pt-BR', {style: 'currency', currency: 'BRL'}).format(total_pagar);
                                                    total_pagar = '<span class="text text-success">' + total_pagar + '</span>';
                                                } else if (total_pagar < 0) {
                                                    total_pagar = new Intl.NumberFormat('pt-BR', {style: 'currency', currency: 'BRL'}).format(total_pagar);
                                                    total_pagar = '<span class="text text-danger">' + total_pagar + '</span>';
                                                } else {
                                                    total_pagar = new Intl.NumberFormat('pt-BR', {style: 'currency', currency: 'BRL'}).format(total_pagar);
                                                    total_pagar = '<span class="text">' + total_pagar + '</span>';
                                                }

                                                if (total_geral > 0) {
                                                    total_geral = new Intl.NumberFormat('pt-BR', {style: 'currency', currency: 'BRL'}).format(total_geral);
                                                    total_geral = '<span class="text text-success">' + total_geral + '</span>';
                                                } else if (total_geral < 0) {
                                                    total_geral = new Intl.NumberFormat('pt-BR', {style: 'currency', currency: 'BRL'}).format(total_geral);
                                                    total_geral = '<span class="text text-danger">' + total_geral + '</span>';
                                                } else {
                                                    total_geral = new Intl.NumberFormat('pt-BR', {style: 'currency', currency: 'BRL'}).format(total_geral);
                                                    total_geral = '<span class="text">' + total_geral + '</span>';
                                                }
                                        } else {
                                            if (data[i].ref_pedido !== '-') {
                                                data[i].ref_pedido = '<a class="a-view-pedido" href="pedidoView.php?e47a3aa7704b671a27108d49f146b8fc=' + data[i].ref_pedido + '" title="Visualizar o pedido"><i class="fas fa-eye"></i> ' + data[i].codigo_pedido + '</a>';
                                            }

                                            if (data[i].tipo == 1) {
                                                data[i].tipo = '<span class="badge badge-success badge-option">Positivo</span>';
                                            } else if (data[i].tipo == 0) {
                                                data[i].tipo = '<span class="badge badge-danger badge-option">Negativo</span>';
                                            }

                                            if (data[i].valor_pago !== null) {
                                                if (data[i].valor_pago > 0) {
                                                    desc_valor_pago = data[i].valor - data[i].valor_pago;
                                                    desc_valor_pago = new Intl.NumberFormat('pt-BR', {style: 'currency', currency: 'BRL'}).format(desc_valor_pago);
                                                    data[i].valor_pago = new Intl.NumberFormat('pt-BR', {style: 'currency', currency: 'BRL'}).format(data[i].valor_pago);
                                                    data[i].valor = '<span><del>R$ ' + data[i].valor + '</del> <cite class="text-success">(Recebido: ' + data[i].valor_pago + ')</cite> <cite class="text-danger">(Receber: ' + desc_valor_pago + ')</cite></span>';
                                                } else {
                                                    if (data[i].pago == 1) {
                                                        data[i].valor = '<del>R$ ' + data[i].valor + '</del>';
                                                    } else if (data[i].pago == 0) {
                                                        data[i].valor = 'R$ ' + data[i].valor;
                                                    }
                                                }
                                            } else {
                                                if (data[i].pago == 1) {
                                                    data[i].valor = '<del>R$ ' + data[i].valor + '</del>';
                                                } else if (data[i].pago == 0) {
                                                    data[i].valor = 'R$ ' + data[i].valor;
                                                }
                                            }

                                            if (data[i].pago == 1) {
                                                response += '<tr>'
                                                + '<td><del>' + data[i].datado + '</del></td>'
                                                + '<td><del>' + data[i].ref_pedido + '</del></td>'
                                                + '<td><del>' + data[i].descricao + '</del></td>'
                                                + '<td><del>' + data[i].tipo + '</del></td>'
                                                + '<td>' + data[i].valor + '</td>'
                                                + '<td class="td-action">'
                                                + '<span class="bg bg-warning"><a class="fas fa-pen a-edit-caixa" href="caixaEdit.php?d8c061e25fddba886966afd66a379931=' + data[i].idcaixa + '" title="Editar o lan&ccedil;amento"></a></span>'
                                                + '<span class="bg bg-danger"><a class="fas fa-trash a-delete-caixa" id="d8c061e25fddba886966afd66a379931-' + data[i].idcaixa + '" href="#" title="Excluir o lan&ccedil;amento"></a></span>'
                                                + '</td></tr>';
                                            } else if (data[i].pago == 0) {
                                                response += '<tr>'
                                                + '<td>' + data[i].datado + '</td>'
                                                + '<td>' + data[i].ref_pedido + '</td>'
                                                + '<td>' + data[i].descricao + '</td>'
                                                + '<td>' + data[i].tipo + '</td>'
                                                + '<td>' + data[i].valor + '</td>'
                                                + '<td class="td-action">'
                                                + '<span class="bg bg-warning"><a class="fas fa-pen a-edit-caixa" href="caixaEdit.php?d8c061e25fddba886966afd66a379931=' + data[i].idcaixa + '" title="Editar o lan&ccedil;amento"></a></span>'
                                                + '<span class="bg bg-danger"><a class="fas fa-trash a-delete-caixa" id="d8c061e25fddba886966afd66a379931-' + data[i].idcaixa + '" href="#" title="Excluir o lan&ccedil;amento"></a></span>'
                                                + '</td></tr>';
                                            }
                                        }
                                    }            
                                
                                $('.th-total').html(
                                    'Recebido: ' + total_recebido + 
                                    '<br> Receber: ' + total_receber + 
                                    '<br> Pago: ' + total_pago + 
                                    '<br> Pagar: ' + total_pagar +
                                    '<br><br> Em Caixa: ' + total_geral
                                );
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
                    // deixar desativado porque choca com o modal pedido view
                    //complete: setTimeout(function() { pullData(); }, timeout),
                    timeout: timeout
                });
            })();

            // ICHECK

            $("input[type='radio']").show(function () {
                $("input[type='radio']").iCheck({
                    radioClass: 'iradio_minimal'
                });
            });

            // INPUTMASK

            $('#valor').inputmask({
                'alias': 'numeric',
                'groupSeparator': ',',
                'autoGroup': true,
                'digits': 2,
                'digitsOptional': false,
                'prefix': '',
                'placeholder': '0'
            });

            // DATEPICKER

            $(".date-pick").show(function () {
                $(".date-pick").datepicker({
                    language: 'pt-BR',
                    format: "mm yyyy",
                    startView: 1,
                    minViewMode: 1
                }).on('hide', function (e) {
                    let dt = e.target.value.split(' ');
                    location.href = "caixa?<?php echo $getmes; ?>=" + dt[0] + "&<?php echo $getano; ?>=" + dt[1] + "&pick=1";
                });
            });

            // MODAL

            $('.table-data').on('click', '.a-edit-caixa', function (e) {
                e.preventDefault();
                $('#modal-edit-caixa').modal('show').find('.modal-content').load($(this).attr('href'));
            });

            $('.table-data').on('click', '.a-view-pedido', function (e) {
                e.preventDefault();
                $('#modal-view-pedido').modal('show').find('.modal-content').load($(this).attr('href'));
            });

            // NOVO PEDIDO

            $('.form-new-caixa').submit(function (e) {
                e.preventDefault();

                $.post('api/caixa/insert.php', $(this).serialize(), function(data) {
                    $('.btn-new-caixa').html('<img src="dist/img/rings.svg" class="loader-svg">').fadeTo(fade, 1);

                    switch (data) {
                        case 'true':
                            Toast.fire({
                                icon: 'success',
                                title: 'Lan&ccedil;amento adicionado.'
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

                    $('.btn-new-caixa').html('Salvar').fadeTo(fade, 1);
                });

                return false;
            });

            // DELETE PEDIDO

            $('.table-data').on('click', '.a-delete-caixa', function (e) {
                e.preventDefault();

                let click = this.id.split('-'),
                    py = click[0],
                    id = click[1];

                swalButton.fire({
                    icon: 'question',
                    title: 'Excluir o lan&ccedil;amento',
                    showCancelButton: true,
                    confirmButtonText: 'Sim',
                    cancelButtonText: 'Não',
                }).then((result) => {
                    if (result.value == true) {
                        $.ajax({
                            type: 'GET',
                            url: 'api/caixa/delete.php?' + py + '=' + id,
                            dataType: 'json',
                            cache: false,
                            beforeSend: function (result) {
                                $('#search-result').empty().append('<p style="position: relative;top: 15px;" class="lead"><i class="fas fa-cog fa-spin"></i> Processando...</p>');
                            },
                            error: function (result) {
                                Swal.fire({
                                    icon: 'error',
                                    html: result.responseText,
                                    showConfirmButton: false
                                });
                            },
                            success: function (data) {
                                if (data == true) {
                                    Toast.fire({
                                        icon: 'success',
                                        title: 'Lan&ccedil;amento exclu&iacute;do'
                                    }).then((result) => {
                                        window.setTimeout("location.href='caixa'", delay);
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