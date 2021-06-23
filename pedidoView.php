<?php
    // clear cache
    
    header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");

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
<div class="modal-header">
    <h4 class="modal-title">
        <span>Visualizar o pedido</span>
    </h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
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
                                    </tr>';

                    $soma = $soma + $row2->subtotal;
                }

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
                                    <th style="width:50%">Subtotal</th>
                                    <td>R$ ' . number_format($soma, 2, '.', ',') . '</td>
                                </tr>
                                <tr>
                                    <th>Desconto</th>
                                    <td>R$ '.number_format($total_desconto, 2, '.', ',') . ' ' . $row->desconto . '</td>
                                </tr>
                                <tr>
                                    <th>Total</th>
                                    <td>R$ ' . number_format($total, 2, '.', ',') . '</td>
                                </tr>
                                <tr>
                                    <th>Pagamento</th>
                                    <td>' . $row->forma_pg . ' ' . $row->parcela . '</td>
                                </tr>
                                <tr>
                                    <th>Parcela</th>
                                    <td>R$ ' . number_format($total_parcelado, 2, '.', ',') . '</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>';
            } else {
                echo'<p class="p-divider text-center lead">Nenhum item foi adicionado ao pedido</p>';
            }
        }
    ?>
</div>
<div class="modal-footer justify-content-between">
    <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
    <a class="btn btn-primary" title="Imprimir o pedido" href="pedidoPrint?e47a3aa7704b671a27108d49f146b8fc=<?php echo $_GET[''.$py_idpedido.'']; ?>">Imprimir</a>
</div>
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