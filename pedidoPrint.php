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
    $pedido->idpedido = $_GET[''.$py_idpedido.''];
    $produtoPedido->idpedido = $_GET[''.$py_idpedido.''];
    $produto->monitor = 1;

    // menu cotrol

    $menu = 1;

        // retrieve query
        
        if ($sql = $pedido->readSingle()) {
            if ($sql->rowCount() > 0) {
                $row = $sql->fetch(PDO::FETCH_OBJ);

                // format date to dd/mm/yyyy

                $ano = substr($row->datado, 0, 4);
                $mes = substr($row->datado, 5, 2);
                $dia = substr($row->datado, 8, 2);
                $row->datado = $dia . '/' . $mes . '/' . $ano;

                if (!empty($row->bairro)) {
                    $row->bairro = $row->bairro . ', ';
                }

                if (!empty($row->telefone)) {
                    $row->telefone = $row->telefone . ' - ';
                }
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo $cfg['head_title']; ?></title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700">
</head>

<body>
    <div class="wrapper">
        
    <!-- Main content -->

        <section class="invoice">
           
            <!-- title row -->
            
            <div class="row">
                <div class="col-12">
                    <h2 class="page-header">
                        <i class="fas fa-globe"></i> Tenutti
                        <small class="float-right"></small>
                    </h2>
                </div>
            </div>

            <!-- info row -->
            
            <div class="row invoice-info">
                <div class="col-sm-4 invoice-col">
                    De
                    <address>
                        <strong>Tenutti, MEI.</strong><br>
                        795 Folsom Ave, Suite 600<br>
                        San Francisco, CA 94107<br>
                        (47)3123-5432<br>
                        contato@tenutti.com
                    </address>
                </div>
                
                <div class="col-sm-4 invoice-col">
                    Para
                    <address>
                        <strong><?php echo $row->cliente; ?></strong><br>
                        <?php echo $row->endereco; ?><br>
                        <?php echo $row->bairro . $row->cidade; ?><br>
                        <?php echo $row->telefone . $row->celular; ?><br>
                        <?php echo $row->email; ?>
                    </address>
                </div>
                
                <div class="col-sm-4 invoice-col">
                    Pedido<br>
                    <b>C&oacute;digo:</b> <?php echo $row->codigo; ?><br>
                    <b>Data:</b> <?php echo $row->datado; ?>
                </div>
                
            </div>

            <!-- /.row -->

            <!-- Table row -->

            <?php
                if ($sql2 = $produtoPedido->readAll()) {
                    if ($sql2->rowCount() > 0) {
                        $soma = 0;

                        echo'
                        <div class="row">
                            <div class="col-12 table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <th>Descri&ccedil;&atilde;o</th>
                                        <th>Tipo</th>
                                        <th>Tamanho</th>
                                        <th>Cor</th>
                                        <th>Venda</th>
                                        <th>Qtde</th>
                                        <th>Subtotal</th>
                                    </thead>
                                    <tbody>';
                                    
                                    while ($row2 = $sql2->fetch(PDO::FETCH_OBJ)) {
                                        /*if ($row2->cor == 'Amarelo') { $row2->cor = '<span class="badge badge-option text-uppercase" style="color: #555;background-color: yellow;">' . $row2->cor . '</span>'; }
                                        if ($row2->cor == 'Azul') { $row2->cor = '<span class="badge badge-option text-uppercase" style="color: white;background-color: blue;">' . $row2->cor . '</span>'; }
                                        if ($row2->cor == 'Branco') { $row2->cor = '<span class="badge badge-option text-uppercase" style="color: #aaa;border: 1px solid #bbb;">' . $row2->cor . '</span>'; }
                                        if ($row2->cor == 'Cinza') { $row2->cor = '<span class="badge badge-option text-uppercase" style="color: white;background-color: #ccc;">' . $row2->cor . '</span>'; }
                                        if ($row2->cor == 'Mescla') { $row2->cor = '<span class="badge badge-option text-uppercase" style="color: white;background-color: grey;">' . $row2->cor . '</span>'; }
                                        if ($row2->cor == 'Preto') { $row2->cor = '<span class="badge badge-option text-uppercase" style="color: white;background-color: black;">' . $row2->cor . '</span>'; }
                                        if ($row2->cor == 'Rosa') { $row2->cor = '<span class="badge badge-option text-uppercase" style="color: #888;background-color: pink;">' . $row2->cor . '</span>'; }
                                        if ($row2->cor == 'Roxo') { $row2->cor = '<span class="badge badge-option text-uppercase" style="color: white;background-color: purple;">' . $row2->cor . '</span>'; }
                                        if ($row2->cor == 'Verde') { $row2->cor = '<span class="badge badge-option text-uppercase" style="color: white;background-color: green;">' . $row2->cor . '</span>'; }
                                        if ($row2->cor == 'Vermelho') { $row2->cor = '<span class="badge badge-option text-uppercase" style="color: white;background-color: red;">' . $row2->cor . '</span>'; }*/

                                        echo'
                                        <tr>
                                            <td>'.$row2->produto.'</td>
                                            <td>'.$row2->tipo.'</td>
                                            <td>'.$row2->tamanho.'</td>
                                            <td>'.$row2->cor.'</td>
                                            <td>R$ '.number_format($row2->valor_venda, 2, '.', ',').'</td>
                                            <td>'.$row2->quantidade.'</td>
                                            <td>R$ '.number_format($row2->subtotal, 2, '.', ',').'</td>
                                        </tr>';

                                        $soma = $soma + $row2->subtotal;
                                    }

                                    echo'
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- /.row -->';

                        // calculando o desconto, se houver

                        $total = $soma * $row->desconto;
                        $total_desconto = $total / 100;
                        $total = $total / 100;
                        $total = $soma - $total;

                            // se existir desconto, mostra
                
                            if (!empty($row->desconto)) {
                                $row->desconto = '<i>(' . $row->desconto . '%)</i>';
                            } else {
                                $row->desconto = '';
                            }

                            // se a forma de pagamento for crédito, mastra as parcelas

                            if ($row->forma_pg == 'Crédito') {
                                // calcula o valor das parcelas

                                $total_parcelado = $total / $row->parcela;
                                $row->parcela = '<i>(' . $row->parcela . 'X)</i>';
                                
                            } else {
                                $row->parcela = '';
                            }
                    }
                }
            ?>

            <div class="row">

                <!-- accepted payments column -->
                
                <div class="col-8">
                    <!--<p class="lead">Payment Methods:</p>
                    <img src="dist/img/credit/visa.png" alt="Visa">
                    <img src="dist/img/credit/mastercard.png" alt="Mastercard">
                    <img src="dist/img/credit/american-express.png" alt="American Express">
                    <img src="dist/img/credit/paypal2.png" alt="Paypal">

                    <p class="text-muted well well-sm shadow-none" style="margin-top: 10px;">
                        Etsy doostang zoodles disqus groupon greplin oooj voxy zoodles, weebly ning heekya handango
                        imeem plugg dopplr
                        jibjab, movity jajah plickers sifteo edmodo ifttt zimbra.
                    </p>-->
                </div>
                
                <div class="col-4">
                    <p class="lead">Valores finais:</p>

                    <div class="table-responsive">
                        <table class="table">
                            <tr>
                                <th style="width:50%">Subtotal</th>
                                <td>R$ <?php echo number_format($soma, 2, '.', ','); ?></td>
                            </tr>
                            <tr>
                                <th>Desconto</th>
                                <td>R$ <?php echo number_format($total_desconto, 2, '.', ',') . ' ' . $row->desconto; ?></td>
                            </tr>
                            <tr>
                                <th>Total</th>
                                <td>R$ <?php echo number_format($total, 2, '.', ','); ?></td>
                            </tr>
                            <tr>
                                <th>Pagamento</th>
                                <td><?php echo $row->forma_pg .' '. $row->parcela; ?></td>
                            </tr>
                            <tr>
                                <th>Parcela</th>
                                <td><?php echo number_format($total_parcelado, 2, '.', ','); ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
                
            </div>

            <!-- /.row -->
        
        </section>
        
        <!-- /.content -->

    </div>

    <!-- ./wrapper -->
    
    <!-- jQuery -->
    <script src="plugins/jquery/jquery.min.js"></script>
    <script>
        window.onafterprint = function(e) {
            $(window).off('mousemove', window.onafterprint);
            window.setTimeout("location.href='inicio'", 1);
        };

        window.print();

        setTimeout(function() {
            $(window).one('mousemove', window.onafterprint);
        }, 1);
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

    unset($total,$total_desconto,$soma);
?>