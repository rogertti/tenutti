<?php
    // require and includes

    require_once 'appConfig.php';
    include_once 'api/config/database.php';
    include_once 'api/objects/produto.php';
    include_once 'api/objects/produtoPedido.php';
    include_once 'api/objects/pedido.php';

        // check for active user

        if (empty($_SESSION['key'])) {
            header ('location:./');
        }
    
    // get database connection

    $database = new Database();
    $db = $database->getConnection();

    // prepare object

    $produto = new Produto($db);
    $produtoPedido = new ProdutoPedido($db);
    $pedido = new Pedido($db);

    // GET variables

    $py_idpedido = md5('idpedido');
    #echo $_GET[''.$py_idpedido.'']; exit;
    $pedido->idpedido = $_GET[''.$py_idpedido.''];
    $produtoPedido->idpedido = $_GET[''.$py_idpedido.''];
    $produto->monitor = 1;

    // menu cotrol

    $menu = 0;

        // retrieve query
        
        if ($sql = $pedido->readSingle()) {
            if ($sql->rowCount() > 0) {
                $row = $sql->fetch(PDO::FETCH_OBJ);

                // format date to dd/mm/yyyy

                $ano = substr($row->datado, 0, 4);
                $mes = substr($row->datado, 5, 2);
                $dia = substr($row->datado, 8, 2);
                $row->datado = $dia . '/' . $mes . '/' . $ano;
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
                                <span>Pedido</span>
                                <span class="float-right">
                                    <a href="#" class="btn btn-primary" title="Clique para adicionar um item ao pedido" data-toggle="modal" data-target="#modal-new-item">
                                        <i class="fas fa-file-invoice"></i> Add item ao pedido
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
                        <div class="row">
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table class="table">
                                        <tr>
                                            <td><mark>C&oacute;digo:</mark> <?php echo $row->codigo; ?></td>
                                            <td colspan="2"><mark>Data:</mark> <?php echo $row->datado; ?></td>
                                        </tr>

                                        <tr>
                                            <td><mark>Cliente:</mark> <?php echo $row->cliente; ?></td>
                                            <td><mark>Telefone:</mark> <?php echo $row->telefone; ?></td>
                                            <td><mark>Celular:</mark> <?php echo $row->celular; ?></td>
                                        </tr>

                                        <tr>
                                            <td><mark>Endere&ccedil;o:</mark> <?php echo $row->endereco; ?></td>
                                            <td><mark>Bairro:</mark> <?php echo $row->bairro; ?></td>
                                            <td><mark>Cidade:</mark> <?php echo $row->cidade; ?></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <?php
                            if ($sql2 = $produtoPedido->readAll()) {
                                if ($sql2->rowCount() > 0) {
                                    $soma = 0;

                                    echo'
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="table-responsive">
                                                <table class="table table-hover table-data">
                                                    <thead>
                                                        <th>Descri&ccedil;&atilde;o</th>
                                                        <th>Tipo</th>
                                                        <th>Tamanho</th>
                                                        <th>Cor</th>
                                                        <th>Venda</th>
                                                        <th>Qtde</th>
                                                        <th>Subtotal</th>
                                                        <th style="max-width: 100px;width: 90px;"></th>
                                                    </thead>
                                                    <tbody>';

                                    while ($row2 = $sql2->fetch(PDO::FETCH_OBJ)) {
                                        if ($row2->cor == 'Amarelo') { $row2->cor = '<span class="badge badge-option text-uppercase" style="color: #555;background-color: yellow;">' . $row2->cor . '</span>'; }
                                        if ($row2->cor == 'Azul') { $row2->cor = '<span class="badge badge-option text-uppercase" style="color: white;background-color: blue;">' . $row2->cor . '</span>'; }
                                        if ($row2->cor == 'Branco') { $row2->cor = '<span class="badge badge-option text-uppercase" style="color: #aaa;border: 1px solid #bbb;">' . $row2->cor . '</span>'; }
                                        if ($row2->cor == 'Cinza') { $row2->cor = '<span class="badge badge-option text-uppercase" style="color: white;background-color: #ccc;">' . $row2->cor . '</span>'; }
                                        if ($row2->cor == 'Mescla') { $row2->cor = '<span class="badge badge-option text-uppercase" style="color: white;background-color: grey;">' . $row2->cor . '</span>'; }
                                        if ($row2->cor == 'Preto') { $row2->cor = '<span class="badge badge-option text-uppercase" style="color: white;background-color: black;">' . $row2->cor . '</span>'; }
                                        if ($row2->cor == 'Rosa') { $row2->cor = '<span class="badge badge-option text-uppercase" style="color: #888;background-color: pink;">' . $row2->cor . '</span>'; }
                                        if ($row2->cor == 'Roxo') { $row2->cor = '<span class="badge badge-option text-uppercase" style="color: white;background-color: purple;">' . $row2->cor . '</span>'; }
                                        if ($row2->cor == 'Verde') { $row2->cor = '<span class="badge badge-option text-uppercase" style="color: white;background-color: green;">' . $row2->cor . '</span>'; }
                                        if ($row2->cor == 'Vermelho') { $row2->cor = '<span class="badge badge-option text-uppercase" style="color: white;background-color: red;">' . $row2->cor . '</span>'; }

                                        echo'
                                                        <tr>
                                                            <td>'.$row2->produto.'</td>
                                                            <td>'.$row2->tipo.'</td>
                                                            <td>'.$row2->tamanho.'</td>
                                                            <td>'.$row2->cor.'</td>
                                                            <td>R$ '.number_format($row2->valor_venda, 2, '.', ',').'</td>
                                                            <td>'.$row2->quantidade.'</td>
                                                            <td>R$ '.number_format($row2->subtotal, 2, '.', ',').'</td>
                                                            <td class="td-action">
                                                                <span class="bg bg-danger"><a class="fas fa-trash a-delete-item" id="e8c7475fdc16097f93c00b2f67694119-' . $row2->idproduto . '-e47a3aa7704b671a27108d49f146b8fc-' . $_GET[''.$py_idpedido.''] . '" href="#" title="Excluir o item do pedido"></a></span>
                                                            </td>
                                                        </tr>';

                                        $soma = $soma + $row2->subtotal;
                                    }

                                    echo'
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-8"></div>
                                        <div class="col-4">
                                            <div class="table-responsive">
                                                <table class="table">
                                                    <tr>
                                                        <th class="text-right" style="max-width: 36%; width: 36%;">Total</th>
                                                        <th><span class="text text-success">R$ ' . number_format($soma, 2, '.', ',') . '</span></th>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                    <span class="span-data float-right">
                                        <a href="#" class="btn btn-info" title="Clique para fechar o pedido" data-toggle="modal" data-target="#modal-final-pedido">
                                            <i class="fas fa-file-invoice"></i> Finalizar
                                        </a>
                                    </span>';

                                    // calculando o desconto, se houver

                                    $total = $soma * $row->desconto;
                                    $total_desconto = $total / 100;
                                    $total = $total / 100;
                                    $total = $soma - $total;
                                } else {
                                    echo'<p class="p-divider text-center lead">Nenhum item foi adicionado ao pedido</p>';
                                }
                            }
                        ?>
                    </div>
                </div>
                <!-- /.card -->
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

        <div class="modal fade" id="modal-new-item">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form class="form-new-item">
                        <div class="modal-header">
                            <h4 class="modal-title">
                                <span>Novo item</span>
                            </h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="rand" id="rand" value="<?php echo md5(mt_rand()); ?>">
                            <input type="hidden" name="idpedido" id="idpedido" value="<?php echo $pedido->idpedido; ?>">

                            <div class="row form-group g-3 align-items-center">
                                <div class="col-2">
                                    <label class="text text-danger" for=""></label>
                                </div>
                                <div class="col-10">
                                    <span class="form-icheck"><input type="radio" name="produto_existe" id="produto_existe_1" value="1" checked> Existente</span>
                                    <span class="form-icheck"><input type="radio" name="produto_existe" id="produto_existe_0" value="0"> N&atilde;o Existente</span>
                                </div>
                            </div>

                            <!-- produto_existe_1 -->

                            <div class="produto_existe_1">
                                <div class="row form-group g-3 align-items-center">
                                    <div class="col-2">
                                        <label class="text text-danger" for="produto">Produto</label>
                                    </div>
                                    <div class="col-10">
                                        <select name="produto[]" id="produto" class="selectpicker show-tick form-control" title="Selecione o produto" placeholder="Produto" data-live-search="true" data-width="" data-size="7" required>
                                        <?php
                                            $sql3 = $produto->readAll();

                                                if ($sql3->rowCount() > 0) {
                                                    echo'<option value="" selected>Selecione o produto</option>';

                                                        while($row3 = $sql3->fetch(PDO::FETCH_OBJ)) {
                                                            echo'<option title="'.$row3->descricao.' '.$row3->tipo.' '.$row3->tamanho.' '.$row3->cor.' R$ '.number_format($row3->valor_venda, 2, '.', ',').'" data-subtext="'.$row3->tipo.' '.$row3->tamanho.' '.$row3->cor.' R$ '.number_format($row3->valor_venda, 2, '.', ',').'" value="'.$row3->idproduto.'-'.$row3->valor_venda.'">'.$row3->descricao.'</option>';
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
                                        <input type="text" name="valor_venda[]" id="valor_venda" maxlength="20" class="form-control col-6" title="Valor de venda do produto" placeholder="Valor de venda" readonly>
                                    </div>
                                </div>
                                <div class="row form-group g-3 align-items-center">
                                    <div class="col-2">
                                        <label class="text text-danger" for="quantidade">Quantidade</label>
                                    </div>
                                    <div class="col-10">
                                        <input type="number" name="quantidade[]" id="quantidade" maxlength="2" class="form-control col-6" title="Informe a quantidade desse produto" placeholder="Quantidade" required>
                                    </div>
                                </div>
                                <div class="row form-group g-3 align-items-center">
                                    <div class="col-2">
                                        <label class="text" for="subtotal">Subtotal</label>
                                    </div>
                                    <div class="col-10">
                                        <input type="text" name="subtotal[]" id="subtotal" maxlength="20" class="form-control col-6" title="Subtotal" placeholder="Subtotal" readonly>
                                    </div>
                                </div>
                            </div>

                            <!-- /.produto_existe_1 -->

                            <!-- produto_existe_0 -->

                            <div class="produto_existe_0 d-none">
                                <div class="row form-group g-3 align-items-center">
                                    <div class="col-2">
                                        <label class="text text-danger" for="produto">Produto</label>
                                    </div>
                                    <div class="col-10">
                                        <select name="produto[]" id="produto_zero" class="selectpicker show-tick form-control" title="Selecione o Produto" placeholder="Descri&ccedil;&atilde;o do produto" data-width="" data-size="7">
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
                                        <select name="tipo" id="tipo" class="selectpicker show-tick form-control" title="Selecione o Tipo" placeholder="Tipo" data-width="" data-size="7">
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
                                        <select name="tamanho" id="tamanho" class="selectpicker show-tick form-control" title="Selecione o Tamanho" placeholder="Tamanho" data-width="" data-size="7">
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
                                        <select name="cor" id="cor" class="selectpicker show-tick form-control" title="Selecione a Cor" placeholder="Cor" data-width="" data-size="7">
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
                                        <input type="text" name="valor_venda[]" id="valor_venda_zero" maxlength="20" class="form-control col-6" title="Informe o valor de venda" placeholder="Valor de venda">
                                    </div>
                                </div>
                                <div class="row form-group g-3 align-items-center">
                                    <div class="col-2">
                                        <label class="text text-danger" for="quantidade">Quantidade</label>
                                    </div>
                                    <div class="col-10">
                                        <input type="number" name="quantidade[]" id="quantidade_zero" maxlength="2" class="form-control col-6" title="Informe a quantidade desse produto" placeholder="Quantidade">
                                    </div>
                                </div>
                                <div class="row form-group g-3 align-items-center">
                                    <div class="col-2">
                                        <label class="text" for="subtotal">Subtotal</label>
                                    </div>
                                    <div class="col-10">
                                        <input type="text" name="subtotal[]" id="subtotal_zero" maxlength="20" class="form-control col-6" title="Subtotal" placeholder="Subtotal" readonly>
                                    </div>
                                </div>
                            </div>

                            <!-- /.produto_existe_0 -->
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                            <button type="submit" class="btn btn-primary btn-new-item">Salvar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modal-final-pedido">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <form class="form-final-pedido">
                        <div class="modal-header">
                            <h4 class="modal-title">
                                <span>Finalizar o pedido</span>
                            </h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="rand" id="rand" value="<?php echo md5(mt_rand()); ?>">
                            <input type="hidden" name="idpedido" id="idpedido" value="<?php echo $pedido->idpedido; ?>">
                            <input type="hidden" name="codigo" id="codigo" value="<?php echo $row->codigo; ?>">

                            <div class="row form-group g-3 align-items-center">
                                <div class="col-4">
                                    <label class="text" for="subtotal">Subtotal <mark>R$</mark></label>
                                </div>
                                <div class="col-8">
                                    <input type="text" name="subtotal" id="subtotal_final" value="<?php echo number_format($soma, 2, '.', ','); ?>" maxlength="20" class="form-control" title="Subtotal do pedido" placeholder="Subtotal" readonly>
                                </div>
                            </div>
                            <div class="row form-group g-3 align-items-center">
                                <div class="col-4">
                                    <label class="text text-danger" for="tamanho">Pagamento</label>
                                </div>
                                <div class="col-8">
                                    <select name="forma_pg" id="forma_pg" class="selectpicker show-tick form-control" title="Selecione a forma de pagamento" placeholder="Selecione a forma de pagamento" data-width="" data-size="7" required>
                                        <!--<option value="" selected>Forma de pagamento</option>-->
                                        <?php
                                            if ($row->forma_pg == 'Crédito') { echo'<option value="Cr&eacute;dito" selected>Cr&eacute;dito</option>'; } else { echo'<option value="Cr&eacute;dito">Cr&eacute;dito</option>'; }
                                            if ($row->forma_pg == 'Débito') { echo'<option value="D&eacute;bito" selected>D&eacute;bito</option>'; } else { echo'<option value="D&eacute;bito">D&eacute;bito</option>'; }
                                            if ($row->forma_pg == 'Dinheiro') { echo'<option value="Dinheiro" selected>Dinheiro</option>'; } else { echo'<option value="Dinheiro">Dinheiro</option>'; }
                                            if ($row->forma_pg == 'PIX') { echo'<option value="PIX" selected>PIX</option>'; } else { echo'<option value="PIX">PIX</option>'; }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <?php
                                if ($row->parcela == 0) {
                            ?>
                            <div class="row form-group g-3 align-items-center div-parcela d-none">
                                <div class="col-4">
                                    <label class="text text-danger" for="parcela">Parcelas</label>
                                </div>
                                <div class="col-8">
                                    <input type="number" name="parcela" id="parcela" maxlength="2" class="form-control" title="N&uacute;mero de parcelas" placeholder="Parcelas">
                                </div>
                            </div>
                            <?php } else { ?>
                            <div class="row form-group g-3 align-items-center div-parcela">
                                <div class="col-4">
                                    <label class="text text-danger" for="parcela">Parcelas</label>
                                </div>
                                <div class="col-8">
                                    <input type="number" name="parcela" id="parcela" maxlength="2" value="<?php echo $row->parcela; ?>" class="form-control" title="N&uacute;mero de parcelas" placeholder="Parcelas" required>
                                </div>
                            </div>
                            <?php } ?>
                            <div class="row form-group g-3 align-items-center">
                                <div class="col-4">
                                    <label class="text" for="desconto">Desconto <mark>%</mark></label>
                                </div>
                                <div class="col-8">
                                    <input type="number" name="desconto" id="desconto" value="<?php echo $row->desconto; ?>" maxlength="3" class="form-control" title="Desconto" placeholder="Desconto">
                                </div>
                            </div>
                            <div class="row form-group g-3 align-items-center">
                                <div class="col-4">
                                    <label class="text" for="total">Total <mark>R$</mark></label>
                                </div>
                                <div class="col-8">
                                    <input type="text" name="total" id="total" value="<?php echo number_format($total, 2, '.', ','); ?>" maxlength="20" class="form-control" title="Total do pedido" placeholder="Total" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                            <button type="submit" class="btn btn-primary btn-final-pedido">Salvar</button>
                        </div>
                    </form>
                </div>
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

            // ICHECK

            $("input[type='radio']").show(function () {
                $("input[type='radio']").iCheck({
                    radioClass: 'iradio_minimal'
                });
            });

            $("#produto_existe_1").on("ifChecked", function(event) {
                $(".produto_existe_1").removeClass('d-none');
                $(".produto_existe_0").addClass('d-none');

                $("#produto").attr('required', true).selectpicker('val', '');
                $("#valor_venda").val('');
                $("#quantidade").attr('required', true).val('');
                $("#subtotal").val('');

                $("#produto_zero").removeAttr('required', true).selectpicker('val', '');
                $("#tipo").removeAttr('required', true).selectpicker('val', '');
                $("#tamanho").removeAttr('required', true).selectpicker('val', '');
                $("#cor").removeAttr('required', true).selectpicker('val', '');
                $("#valor_custo").removeAttr('required', true).val('');
                $("#valor_venda_zero").removeAttr('required', true).val('');
                $("#quantidade_zero").removeAttr('required', true).val('');
                $("#subtotal_zero").val('');
            });

            $("#produto_existe_0").on("ifChecked", function(event) {
                $(".produto_existe_0").removeClass('d-none');
                $(".produto_existe_1").addClass('d-none');

                $("#produto").removeAttr('required', true).selectpicker('val', '');
                $("#valor_venda").val('');
                $("#quantidade").removeAttr('required', true).val('');
                $("#subtotal").val('');

                $("#produto_zero").attr('required', true).selectpicker('val', '');
                $("#tipo").attr('required', true).selectpicker('val', '');
                $("#tamanho").attr('required', true).selectpicker('val', '');
                $("#cor").attr('required', true).selectpicker('val', '');
                $("#valor_venda_zero").attr('required', true).val('');
                $("#quantidade_zero").attr('required', true).val('');
                $("#subtotal_zero").val('');
            });

            // SELECT PICKER

            $('#produto, #produto_zero, #tipo, #tamanho, #cor, #forma_pg').selectpicker();

            // INPUTMASK

            $('#valor_custo, #valor_venda, #valor_venda_zero, #subtotal, #subtotal_zero, #subtotal_final, #total').inputmask({
                'alias': 'numeric',
                'groupSeparator': ',',
                'autoGroup': true,
                'digits': 2,
                'digitsOptional': false,
                'prefix': '',
                'placeholder': '0'
            });

            $('#produto').change(() => {
                let option = $('#produto option').filter(':selected').val(), idproduto, valor_venda;
                option = option.split('-'), idproduto = option[0], valor_venda = option[1];
                
                $('#valor_venda').val(valor_venda);
                $('#quantidade').val('');
                $('#subtotal').val('');
            });

            $('#forma_pg').change(() => {
                let option = $('#forma_pg option').filter(':selected').val();
                
                    if (option === 'Crédito') {
                        $('.div-parcela').removeClass('d-none');
                        $("#parcela").attr('required', true).val('');
                    } else {
                        $('.div-parcela').addClass('d-none');
                        $("#parcela").removeAttr('required', true).val('');
                    }
            });

            $('#quantidade').keyup(() => {
                let valor_venda = $('#valor_venda').val(), quantidade = $('#quantidade').val(), subtotal;
                subtotal = quantidade * valor_venda;

                $('#subtotal').val(subtotal);
            });

            $('#produto_zero').change(() => {
                /*let option = $('#produto_zero option').filter(':selected').val(), idproduto, valor_venda;
                option = option.split('-'), idproduto = option[0], valor_venda = option[1];
                
                $('#valor_venda_zero').val(valor_venda);
                $('#quantidade_zero').val('');
                $('#subtotal_zero').val('');*/

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

            $('#desconto').keyup(() => {
                let subtotal = $('#subtotal_final').val().replace(',',''), desconto = $('#desconto').val(), total_desconto;
                total_desconto = subtotal * desconto;
                total_desconto = total_desconto / 100;
                total_desconto = subtotal - total_desconto;
                $('#total').val(total_desconto);
            });

            // INSERT ITEM

            $('.form-new-item').submit(function(e) {
                e.preventDefault();

                $.post('api/produtoPedido/insert.php', $(this).serialize(), function(data) {
                    $('.btn-new-item').html('<img src="dist/img/rings.svg" class="loader-svg">').fadeTo(fade, 1);

                    if (data.match(/<url>/g)) {
                        let url_pedido = data.replace('<url>', '');
                        url_pedido = url_pedido.replace('</url>', '');

                        Toast.fire({
                            icon: 'success',
                            title: 'Item adicionado.'
                        }).then((result) => {
                            window.setTimeout("location.href='" + url_pedido + "'", delay);
                        });
                    } else {
                        Toast.fire({
                            icon: 'error',
                            title: data
                        });
                    }

                    $('.btn-new-item').html('Salvar').fadeTo(fade, 1);
                });

                return false;
            });

            // DELETE ITEM

            $('.table-data').on('click', '.a-delete-item', function(e) {
                e.preventDefault();
                
                let click = this.id.split('-'),
                    pyproduto = click[0],
                    idproduto = click[1],
                    pypedido = click[2],
                    idpedido = click[3];
                
                swalButton.fire({
                    icon: 'question',
                    title: 'Excluir o item do pedido',
                    showCancelButton: true,
                    confirmButtonText: 'Sim',
                    cancelButtonText: 'Não',
                }).then((result) => {
                    if (result.value == true) {
                        $.ajax({
                            type: 'GET',
                            url: 'api/produtoPedido/delete.php?' + pyproduto + '=' + idproduto + '&' + pypedido + '=' + idpedido,
                            dataType: 'json',
                            cache: false,
                            error: function(result) {
                                Swal.fire({icon: 'error',html: result.responseText,showConfirmButton: false});
                            },
                            success: function(data) {
                                if (data == true) {
                                    Toast.fire({icon: 'success',title: 'Item exclu&iacute;do.'}).then((result) => {
                                        window.setTimeout("location.href='pedido?" + pypedido + "=" + idpedido + "'", delay);
                                    });
                                }
                            }
                        });
                    }
                });
            });

            // FINISH PEDIDO

            $('.form-final-pedido').submit(function(e) {
                e.preventDefault();

                $.post('api/produtoPedido/finish.php', $(this).serialize(), function(data) {
                    $('.btn-final-pedido').html('<img src="dist/img/rings.svg" class="loader-svg">').fadeTo(fade, 1);

                    if (data.match(/<url>/g)) {
                        let url_pedido = data.replace('<url>', '');
                        url_pedido = url_pedido.replace('</url>', '');

                        Toast.fire({
                            icon: 'success',
                            title: 'Pedido finalizado.'
                        }).then((result) => {
                            window.setTimeout("location.href='" + url_pedido + "'", delay);
                        });
                    } else {
                        Toast.fire({
                            icon: 'error',
                            title: data
                        });
                    }

                    $('.btn-final-pedido').html('Salvar').fadeTo(fade, 1);
                });

                return false;
            });
        });
    </script>
</body>

</html>
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