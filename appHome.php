<?php
    require_once 'appConfig.php';
    include_once 'api/config/database.php';
    include_once 'api/objects/cliente.php';
    include_once 'api/objects/produto.php';
    include_once 'api/objects/pedido.php';

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

    $cliente = new Cliente($db);
    $produto = new Produto($db);
    $pedido = new Pedido($db);

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

    $menu = 1;
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
                                <span>Pedidos</span>
                                <span class="float-right">
                                    <a href="#" class="btn btn-primary" title="Clique para adicionar um pedido" data-toggle="modal" data-target="#modal-new-pedido">
                                        <i class="fas fa-file-invoice"></i> Novo pedido
                                    </a>
                                    <a href="#" class="btn btn-dark" title="Clique para adicionar um pedido" data-toggle="modal" data-target="#modal-new-pedido-zero">
                                        <i class="fas fa-file-invoice"></i> Novo pedido do zero
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

                        <div class="div-time">
                            <div class="div-time-left text-center">
                                <a class="lead" href="inicio?<?php echo $getmes; ?>=<?php echo $mesleft; ?>&<?php echo $getano; ?>=<?php echo $ano; ?>&left=1" title="M&ecirc;s anterior">
                                    <i class="fas fa-arrow-left"></i>
                                </a>
                            </div>
                            <div class="div-time-center">
                                <p class="lead text-center">
                                    <input type="text" class="col-12 date-pick text-center" value="<?php echo mes_extenso($mes); ?> de <?php echo $ano; ?>" readonly>
                                </p>
                            </div>
                            <div class="div-time-right text-center">
                                <a class="lead" href="inicio?<?php echo $getmes; ?>=<?php echo $mesright; ?>&<?php echo $getano; ?>=<?php echo $ano; ?>&right=1" title="Pr&oacute;ximo m&ecirc;s">
                                    <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>

                        <table class="table table-bordered table-hover table-data d-none">
                            <thead>
                                <tr>
                                    <th>C&oacute;digo</th>
                                    <th>Data</th>
                                    <th>Cliente</th>
                                    <th>Celular</th>
                                    <th style="max-width: 100px;width: 100px;"></th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>C&oacute;digo</th>
                                    <th>Data</th>
                                    <th>Cliente</th>
                                    <th>Celular</th>
                                    <th style="max-width: 100px;width: 100px;"></th>
                                </tr>
                            </tfoot>
                        </table>

                        <blockquote class="blockquote-data d-none">
                            <h5>Nada encontrado</h5>
                            <p>Nenhum pedido aberto.</p>
                        </blockquote>
                    </div>
                </div>
                <!-- /.card -->
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

        <div class="modal fade" id="modal-new-pedido">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form class="form-new-pedido">
                        <div class="modal-header">
                            <h4 class="modal-title">
                                <span>Novo pedido</span>
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
                                <div class="col-2">
                                    <label class="text text-danger" for="cliente">Cliente</label>
                                </div>
                                <div class="col-10">
                                    <select name="cliente" id="cliente" class="selectpicker show-tick form-control" title="Selecione o cliente" placeholder="Cliente" data-live-search="true" data-width="" data-size="7" required>
                                    <?php
                                        $cliente->monitor = 1;
                                        $sql = $cliente->readAll();

                                            if ($sql->rowCount() > 0) {
                                                echo'<option value="" selected>Selecione o cliente</option>';

                                                    while($row = $sql->fetch(PDO::FETCH_OBJ)) {
                                                        echo'<option title="'.$row->nome.'" data-subtext="'.$row->cpf_cnpj.' - '.$row->celular.'" value="'.$row->idcliente.'">'.$row->nome.'</option>';
                                                    }
                                            } else {
                                                echo'<option value="" selected>Nenhum cliente cadastrado</option>';
                                            }
                                    ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row form-group g-3 align-items-center">
                                <div class="col-2">
                                    <label class="text text-danger" for="produto">Produto</label>
                                </div>
                                <div class="col-10">
                                    <select name="produto" id="produto" class="selectpicker show-tick form-control" title="Selecione o produto" placeholder="Produto" data-live-search="true" data-width="" data-size="7" required>
                                    <?php
                                        $produto->monitor = 1;
                                        $sql = $produto->readAll();

                                            if ($sql->rowCount() > 0) {
                                                echo'<option value="" selected>Selecione o produto</option>';

                                                    while($row = $sql->fetch(PDO::FETCH_OBJ)) {
                                                        echo'<option title="'.$row->descricao.' '.$row->tipo.' '.$row->tamanho.' '.$row->cor.' R$ '.number_format($row->valor_venda, 2, '.', ',').'" data-subtext="'.$row->tipo.' '.$row->tamanho.' '.$row->cor.' R$ '.number_format($row->valor_venda, 2, '.', ',').'" value="'.$row->idproduto.'-'.$row->valor_venda.'">'.$row->descricao.'</option>';
                                                    }
                                            } else {
                                                echo'<option value="" selected>Nenhum produto cadastrado</option>';
                                            }
                                    ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row form-group g-3 align-items-center">
                                <div class="col-2">
                                    <label class="text" for="valor_venda">Valor</label>
                                </div>
                                <div class="col-10">
                                    <input type="text" name="valor_venda" id="valor_venda" maxlength="20" class="form-control col-6" title="Valor de venda do produto" placeholder="Valor de venda" readonly>
                                </div>
                            </div>
                            <div class="row form-group g-3 align-items-center">
                                <div class="col-2">
                                    <label class="text text-danger" for="quantidade">Quantidade</label>
                                </div>
                                <div class="col-10">
                                    <input type="number" name="quantidade" id="quantidade" maxlength="2" class="form-control col-6" title="Informe a quantidade desse produto" placeholder="Quantidade" required>
                                </div>
                            </div>
                            <div class="row form-group g-3 align-items-center">
                                <div class="col-2">
                                    <label class="text" for="subtotal">Subtotal</label>
                                </div>
                                <div class="col-10">
                                    <input type="text" name="subtotal" id="subtotal" maxlength="20" class="form-control col-6" title="Subtotal" placeholder="Subtotal" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                            <button type="submit" class="btn btn-primary btn-new-pedido">Salvar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modal-new-pedido-zero">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form class="form-new-pedido-zero">
                        <div class="modal-header">
                            <h4 class="modal-title">
                                <span>Novo pedido do zero</span>
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
                                <div class="col-2">
                                    <label class="text text-danger" for="cliente">Cliente</label>
                                </div>
                                <div class="col-10">
                                    <input type="text" name="cliente" id="cliente" maxlength="200" class="form-control" title="Informe o nome do cliente" placeholder="Nome do cliente" required>
                                </div>
                            </div>
                            <div class="row form-group g-3 align-items-center">
                                <div class="col-2">
                                    <label class="text text-danger" for="produto">Produto</label>
                                </div>
                                <div class="col-10">
                                    <select name="produto" id="produto_zero" class="selectpicker show-tick form-control" title="Selecione o Produto" placeholder="Descri&ccedil;&atilde;o do produto" data-width="" data-size="7" required>
                                        <option value="" selected>Selecione o Produto</option>
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
                                    <input type="text" name="valor_venda" id="valor_venda_zero" maxlength="20" class="form-control col-6" title="Informe o valor de venda" placeholder="Valor de venda" required>
                                </div>
                            </div>
                            <div class="row form-group g-3 align-items-center">
                                <div class="col-2">
                                    <label class="text text-danger" for="quantidade">Quantidade</label>
                                </div>
                                <div class="col-10">
                                    <input type="number" name="quantidade" id="quantidade_zero" maxlength="2" class="form-control col-6" title="Informe a quantidade desse produto" placeholder="Quantidade" required>
                                </div>
                            </div>
                            <div class="row form-group g-3 align-items-center">
                                <div class="col-2">
                                    <label class="text" for="subtotal">Subtotal</label>
                                </div>
                                <div class="col-10">
                                    <input type="text" name="subtotal" id="subtotal_zero" maxlength="20" class="form-control col-6" title="Subtotal" placeholder="Subtotal" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                            <button type="submit" class="btn btn-primary btn-new-pedido-zero">Salvar</button>
                        </div>
                    </form>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>

        <div class="modal fade" id="modal-edit-pedido">
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
    <!-- Select Picker -->
    <script src="plugins/bootstrap-select-1.13.14/js/bootstrap-select.min.js"></script>
    <!-- DatePicker -->
    <script src="plugins/datepicker/js/datepicker.min.js"></script>
    <!-- InputMask -->
    <script src="plugins/inputmask/jquery.inputmask.bundle.min.js"></script>
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
                    url: 'api/pedido/readAll.php?<?php echo $getmes; ?>=<?php echo $mes; ?>&<?php echo $getano; ?>=<?php echo $ano; ?>',
                    //url: 'api/pedido/readAll.php',
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
                                let response = '';

                                    for(let i in data) {
                                        if (data[i].finalizado == 1) {
                                            response += '<tr>'
                                            + '<td><del>' + data[i].codigo + '</del></td>'
                                            + '<td><del>' + data[i].datado + '</del></td>'
                                            + '<td><del>' + data[i].cliente + '</del></td>'
                                            + '<td><del>' + data[i].celular + '</del></td>'
                                            + '<td class="td-action">'
                                            //+ '<span class="bg bg-info"><a class="fas fa-tag a-item-pedido" href="pedido?e47a3aa7704b671a27108d49f146b8fc=' + data[i].idpedido + '" title="Itens do pedido"></a></span>'
                                            //+ '<span class="bg bg-warning"><a class="fas fa-pen a-edit-pedido" href="pedidoEdit?e47a3aa7704b671a27108d49f146b8fc=' + data[i].idpedido + '" title="Editar o pedido"></a></span>'
                                            + '<span class="bg bg-info"><a class="fas fa-eye a-view-pedido" href="pedidoView?e47a3aa7704b671a27108d49f146b8fc=' + data[i].idpedido + '" title="Visualizar o pedido"></a></span>'
                                            + '<span class="bg bg-danger"><a class="fas fa-trash a-delete-pedido" id="e47a3aa7704b671a27108d49f146b8fc-' + data[i].idpedido + '" href="#" title="Excluir o pedido"></a></span>'
                                            + '</td></tr>';
                                        } else if (data[i].finalizado == 0) {
                                            response += '<tr>'
                                            + '<td>' + data[i].codigo + '</td>'
                                            + '<td>' + data[i].datado + '</td>'
                                            + '<td>' + data[i].cliente + '</td>'
                                            + '<td>' + data[i].celular + '</td>'
                                            + '<td class="td-action">'
                                            + '<span class="bg bg-info"><a class="fas fa-tag a-item-pedido" href="pedido?e47a3aa7704b671a27108d49f146b8fc=' + data[i].idpedido + '" title="Itens do pedido"></a></span>'
                                            + '<span class="bg bg-warning"><a class="fas fa-pen a-edit-pedido" href="pedidoEdit?e47a3aa7704b671a27108d49f146b8fc=' + data[i].idpedido + '" title="Editar o pedido"></a></span>'
                                            + '<span class="bg bg-danger"><a class="fas fa-trash a-delete-pedido" id="e47a3aa7704b671a27108d49f146b8fc-' + data[i].idpedido + '" href="#" title="Excluir o pedido"></a></span>'
                                            + '</td></tr>';
                                        } else {
                                            
                                        }
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
                                //$('.div-time').removeClass('d-none');
                                $('.table-data').addClass('d-none');
                                $('.blockquote-data').removeClass('d-none');
                            }
                        }
                    },
                    complete: setTimeout(function() { pullData(); }, timeout),
                    timeout: timeout
                });
            })();

            // SELECT PICKER

            $('#cliente, #produto, #tipo, #tamanho, #cor').selectpicker();

            $('#produto').change(() => {
                let option = $('#produto option').filter(':selected').val(), idproduto, valor_venda;
                option = option.split('-'), idproduto = option[0], valor_venda = option[1];
                
                $('#valor_venda').val(valor_venda);
            });

            $('#quantidade').keyup(() => {
                let valor_venda = $('#valor_venda').val(), quantidade = $('#quantidade').val(), subtotal;
                subtotal = quantidade * valor_venda;

                $('#subtotal').val(subtotal);
            });

            $('#produto_zero').change(() => {
                /*let option = $('#produto_zero option').filter(':selected').val(), idproduto, valor_venda;
                option = option.split('-'), idproduto = option[0], valor_venda = option[1];
                
                $('#valor_venda_zero').val(valor_venda);*/

                let option = $('#produto_zero option').filter(':selected').val();
                    
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

            $('#quantidade_zero').keyup(() => {
                let valor_venda = $('#valor_venda_zero').val(), quantidade = $('#quantidade_zero').val(), subtotal;
                subtotal = quantidade * valor_venda;

                $('#subtotal_zero').val(subtotal);
            });

            // INPUTMASK

            $('#valor_custo, #valor_venda, #valor_venda_zero, #subtotal, #subtotal_zero').inputmask({
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
                    location.href = "inicio?<?php echo $getmes; ?>=" + dt[0] + "&<?php echo $getano; ?>=" + dt[1] + "&pick=1";
                });
            });

            // MODAL

            $('.table-data').on('click', '.a-edit-pedido', function (e) {
                e.preventDefault();
                $('#modal-edit-pedido').modal('show').find('.modal-content').load($(this).attr('href'));
            });

            $('.table-data').on('click', '.a-view-pedido', function (e) {
                e.preventDefault();
                $('#modal-view-pedido').modal('show').find('.modal-content').load($(this).attr('href'));
            });

            // NOVO PEDIDO

            $('.form-new-pedido').submit(function (e) {
                e.preventDefault();

                $.post('api/pedido/insert.php', $(this).serialize(), function(data) {
                    $('.btn-new-pedido').html('<img src="dist/img/rings.svg" class="loader-svg">').fadeTo(fade, 1);

                    /*switch (data) {
                        case 'true':
                            Toast.fire({
                                icon: 'success',
                                title: 'Pedido aberto.'
                            }).then((result) => {
                                window.setTimeout("location.href='pedido'", delay);
                            });
                            break;

                        default:
                            Toast.fire({
                                icon: 'error',
                                title: data
                            });
                            break;
                    }*/

                    if (data.match(/<url>/g)) {
                        let url_pedido = data.replace('<url>', '');
                        url_pedido = url_pedido.replace('</url>', '');

                        Toast.fire({
                            icon: 'success',
                            title: 'Pedido aberto.'
                        }).then((result) => {
                            window.setTimeout("location.href='" + url_pedido + "'", delay);
                        });
                    } else {
                        Toast.fire({
                            icon: 'error',
                            title: data
                        });
                    }

                    $('.btn-new-pedido').html('Salvar').fadeTo(fade, 1);
                });

                return false;
            });

            // NOVO PEDIDO DO ZERO

            $('.form-new-pedido-zero').submit(function (e) {
                e.preventDefault();

                $.post('api/combo/insert.php', $(this).serialize(), function(data) {
                    $('.btn-new-pedido-zero').html('<img src="dist/img/rings.svg" class="loader-svg">').fadeTo(fade, 1);

                    /*switch (data) {
                        case 'true':
                            Toast.fire({
                                icon: 'success',
                                title: 'Pedido aberto.'
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
                    }*/

                    if (data.match(/<url>/g)) {
                        let url_pedido = data.replace('<url>', '');
                        url_pedido = url_pedido.replace('</url>', '');

                        Toast.fire({
                            icon: 'success',
                            title: 'Pedido aberto.'
                        }).then((result) => {
                            window.setTimeout("location.href='" + url_pedido + "'", delay);
                        });
                    } else {
                        Toast.fire({
                            icon: 'error',
                            title: data
                        });
                    }

                    $('.btn-new-pedido-zero').html('Salvar').fadeTo(fade, 1);
                });

                return false;
            });

            // DELETE PEDIDO

            $('.table-data').on('click', '.a-delete-pedido', function (e) {
                e.preventDefault();

                let click = this.id.split('-'),
                    py = click[0],
                    id = click[1];

                swalButton.fire({
                    icon: 'question',
                    title: 'Excluir a pedido',
                    showCancelButton: true,
                    confirmButtonText: 'Sim',
                    cancelButtonText: 'Não',
                }).then((result) => {
                    if (result.value == true) {
                        $.ajax({
                            type: 'GET',
                            url: 'api/pedido/delete.php?' + py + '=' + id,
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
                                        title: 'Pedido exclu&iacute;do.'
                                    }).then((result) => {
                                        window.setTimeout("location.href='inicio'", delay);
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