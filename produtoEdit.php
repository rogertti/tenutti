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
    include_once 'api/objects/produto.php';

        // check for active user

        if (empty($_SESSION['key'])) {
            header ('location:./');
        }
    
    // get database connection

    $database = new Database();
    $db = $database->getConnection();

    // prepare object

    $produto = new Produto($db);

    // GET variables

    $py_idproduto = md5('idproduto');
    #echo $_GET[''.$py_idproduto.'']; exit;
    $produto->idproduto = $_GET[''.$py_idproduto.''];

        // retrieve query
        
        if ($sql = $produto->readSingle()) {
            if ($sql->rowCount() > 0) {
                $row = $sql->fetch(PDO::FETCH_OBJ);
?>
<form class="form-edit-produto">
    <div class="modal-header">
        <h4 class="modal-title">
            <span>Editar o cadastro do produto</span>
        </h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <input type="hidden" name="rand" id="rand_edit" value="<?php echo rand(); ?>">
        <input type="hidden" name="idproduto" id="idproduto_edit" value="<?php echo $row->idproduto; ?>">
        
        <div class="row form-group g-3 align-items-center">
            <div class="col-2">
                <label class="text text-danger" for="descricao">Descri&ccedil;&atilde;o</label>
            </div>
            <div class="col-10">
                <!--<input type="text" name="descricao" id="descricao_edit" maxlength="200" class="form-control" title="Informe a descri&ccedil;&atilde;o do produto" placeholder="Descri&ccedil;&atilde;o" required>-->
                <select name="descricao" id="descricao_edit" class="selectpicker show-tick form-control" title="Selecione a Descri&ccedil;&atilde;o" placeholder="Descri&ccedil;&atilde;o" data-width="" data-size="7" required>
                <?php
                    if ($row->descricao == 'Camiseta') { echo'<option value="Camiseta" selected>Camiseta</option>'; } else { echo'<option value="Camiseta">Camiseta</option>'; }
                    if ($row->descricao == 'Moleton') { echo'<option value="Moleton" selected>Moleton</option>'; } else { echo'<option value="Moleton">Moleton</option>'; }
                    if ($row->descricao == 'Serviço') { echo'<option value="Servi&ccedil;o" selected>Servi&ccedil;o</option>'; } else { echo'<option value="Servi&ccedil;o">Servi&ccedil;o</option>'; }
                    if ($row->descricao == 'Tela') { echo'<option value="Tela" selected>Tela</option>'; } else { echo'<option value="Tela">Tela</option>'; }
                    if ($row->descricao == 'Manutenção Tela') { echo'<option value="Manuten&ccedil;&atilde;o Tela" selected>Manuten&ccedil;&atilde;o de Tela</option>'; } else { echo'<option value="Manuten&ccedil;&atilde;o Tela">Manuten&ccedil;&atilde;o de Tela</option>'; }
                ?>
                </select>
            </div>
        </div>
        <?php
            if (($row->descricao != 'Tela') or ($row->descricao != 'Manutenção Tela')) {
        ?>
        <div class="row form-group g-3 align-items-center div-tipo">
            <div class="col-2">
                <label class="text text-danger" for="tipo">Tipo</label>
            </div>
            <div class="col-10">
                <select name="tipo" id="tipo_edit" class="selectpicker show-tick form-control" title="Selecione o Tipo" placeholder="Tipo" data-width="" data-size="7" required>
                <?php
                    if ($row->tipo == 'Babylong') { echo'<option value="Babylong" selected>Babylong</option>'; } else { echo'<option value="Babylong">Babylong</option>'; }
                    if ($row->tipo == 'Blusao') { echo'<option value="Blusao" selected>Blus&atilde;o</option>'; } else { echo'<option value="Blusao">Blus&atilde;o</option>'; }
                    if ($row->tipo == 'Canguru') { echo'<option value="Canguru" selected>Canguru</option>'; } else { echo'<option value="Canguru">Canguru</option>'; }
                    if ($row->tipo == 'Estonada') { echo'<option value="Estonada" selected>Estonada</option>'; } else { echo'<option value="Estonada">Estonada</option>'; }
                    if ($row->tipo == 'Estonada Babylong') { echo'<option value="Estonada Babylong" selected>Estonada Babylong</option>'; } else { echo'<option value="Estonada Babylong">Estonada Babylong</option>'; }
                    if ($row->tipo == 'Premium') { echo'<option value="Premium" selected>Premium</option>'; } else { echo'<option value="Premium">Premium</option>'; }                      
                    if ($row->tipo == 'Premium Gola V') { echo'<option value="Premium Gola V" selected>Premium Gola V</option>'; } else { echo'<option value="Premium Gola V">Premium Gola V</option>'; }
                ?>
                </select>
            </div>
        </div>
        <div class="row form-group g-3 align-items-center div-tamanho">
            <div class="col-2">
                <label class="text text-danger" for="tamanho">Tamanho</label>
            </div>
            <div class="col-10">
                <select name="tamanho" id="tamanho_edit" class="selectpicker show-tick form-control" title="Selecione o Tamanho" placeholder="Tamanho" data-width="" data-size="7" required>
                <?php
                    if ($row->tamanho == '3P') { echo'<option value="3P" selected>3P</option>'; } else { echo'<option value="3P">3P</option>'; }
                    if ($row->tamanho == 'PP') { echo'<option value="PP" selected>PP</option>'; } else { echo'<option value="PP">PP</option>'; }
                    if ($row->tamanho == 'P') { echo'<option value="P" selected>P</option>'; } else { echo'<option value="P">P</option>'; }
                    if ($row->tamanho == 'M') { echo'<option value="M" selected>M</option>'; } else { echo'<option value="M">M</option>'; }
                    if ($row->tamanho == 'G') { echo'<option value="G" selected>G</option>'; } else { echo'<option value="G">G</option>'; }
                    if ($row->tamanho == 'GG') { echo'<option value="GG" selected>GG</option>'; } else { echo'<option value="GG">GG</option>'; }
                    if ($row->tamanho == '3G') { echo'<option value="3G" selected>3G</option>'; } else { echo'<option value="3G">3G</option>'; }
                    if ($row->tamanho == '4G') { echo'<option value="4G" selected>4G</option>'; } else { echo'<option value="4G">4G</option>'; }
                    if ($row->tamanho == '5G') { echo'<option value="5G" selected>5G</option>'; } else { echo'<option value="5G">5G</option>'; }
                    if ($row->tamanho == '6G') { echo'<option value="6G" selected>6G</option>'; } else { echo'<option value="6G">6G</option>'; }                 
                ?>
                </select>
            </div>
        </div>
        <div class="row g-3 form-group align-items-center div-cor">
            <div class="col-2">
                <label class="col-form-label text text-danger" for="cor">Cor</label>
            </div>
            <div class="col-10">
                <select name="cor" id="cor_edit" class="selectpicker show-tick form-control" title="Selecione a Cor" placeholder="Cor" data-width="" data-size="7" required>
                <?php
                    if ($row->cor == 'Amarelo') { echo'<option value="Amarelo" data-content="<span class='."'badge badge-option'".' style='."'color: #555;background-color: yellow;'".'>AMARELO</span>" selected>Amarelo</option>'; } else { echo'<option value="Amarelo" data-content="<span class='."'badge badge-option'".' style='."'color: #555;background-color: yellow;'".'>AMARELO</span>">Amarelo</option>'; }
                    if ($row->cor == 'Azul') { echo'<option value="Azul" data-content="<span class='."'badge badge-option'".' style='."'color: white;background-color: blue;'".'>AZUL</span>" selected>Azul</option>'; } else { echo'<option value="Azul" data-content="<span class='."'badge badge-option'".' style='."'color: white;background-color: blue;'".'>AZUL</span>">Azul</option>'; }
                    if ($row->cor == 'Branco') { echo'<option value="Branco" data-content="<span class='."'badge badge-option'".' style='."'color: #aaa;border: 1px solid #bbb;'".'>BRANCO</span>" selected>Branco</option>'; } else { echo'<option value="Branco" data-content="<span class='."'badge badge-option'".' style='."'color: #aaa;border: 1px solid #bbb;'".'>BRANCO</span>">Branco</option>'; }
                    if ($row->cor == 'Cinza') { echo'<option value="Cinza" data-content="<span class='."'badge badge-option'".' style='."'background-color: #ccc;'".'>CINZA</span>" selected>Cinza</option>'; } else { echo'<option value="Cinza" data-content="<span class='."'badge badge-option'".' style='."'background-color: #ccc;'".'>CINZA</span>">Cinza</option>'; }
                    if ($row->cor == 'Mescla') { echo'<option value="Mescla" data-content="<span class='."'badge badge-option'".' style='."'color: black;background-color: grey;'".'>MESCLA</span>" selected>Mescla</option>'; } else { echo'<option value="Mescla" data-content="<span class='."'badge badge-option'".' style='."'color: black;background-color: grey;'".'>MESCLA</span>">Mescla</option>'; }
                    if ($row->cor == 'Preto') { echo'<option value="Preto" data-content="<span class='."'badge badge-option'".' style='."'color: white;background-color: black;'".'>PRETO</span>" selected>Preto</option>'; } else { echo'<option value="Preto" data-content="<span class='."'badge badge-option'".' style='."'color: white;background-color: black;'".'>PRETO</span>">Preto</option>'; }
                    if ($row->cor == 'Rosa') { echo'<option value="Rosa" data-content="<span class='."'badge badge-option'".' style='."'color: #888;background-color: pink;'".'>ROSA</span>" selected>Rosa</option>'; } else { echo'<option value="Rosa" data-content="<span class='."'badge badge-option'".' style='."'color: #888;background-color: pink;'".'>ROSA</span>">Rosa</option>'; }
                    if ($row->cor == 'Roxo') { echo'<option value="Roxo" data-content="<span class='."'badge badge-option'".' style='."'color: white;background-color: purple;'".'>ROXO</span>" selected>Roxo</option>'; } else { echo'<option value="Roxo" data-content="<span class='."'badge badge-option'".' style='."'color: white;background-color: purple;'".'>ROXO</span>">Roxo</option>'; }
                    if ($row->cor == 'Verde') { echo'<option value="Verde" data-content="<span class='."'badge badge-option'".' style='."'color: white;background-color: green;'".'>VERDE</span>" selected>Verde</option>'; } else { echo'<option value="Verde" data-content="<span class='."'badge badge-option'".' style='."'color: white;background-color: green;'".'>VERDE</span>">Verde</option>'; }
                    if ($row->cor == 'Vermelho') { echo'<option value="Vermelho" data-content="<span class='."'badge badge-option'".' style='."'color: white;background-color: red;'".'>VERMELHO</span>" selected>Vermelho</option>'; } else { echo'<option value="Vermelho" data-content="<span class='."'badge badge-option'".' style='."'color: white;background-color: red;'".'>VERMELHO</span>">Vermelho</option>'; }
                ?>
                </select>
            </div>
        </div>
        <?php
            } else {
        ?>
        <div class="row form-group g-3 align-items-center d-none div-tipo">
            <div class="col-2">
                <label class="text text-danger" for="tipo">Tipo</label>
            </div>
            <div class="col-10">
                <select name="tipo" id="tipo_edit" class="selectpicker show-tick form-control" title="Selecione o Tipo" placeholder="Tipo" data-width="" data-size="7">
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
        <div class="row form-group g-3 align-items-center d-none div-tamanho">
            <div class="col-2">
                <label class="text text-danger" for="tamanho">Tamanho</label>
            </div>
            <div class="col-10">
                <select name="tamanho" id="tamanho_edit" class="selectpicker show-tick form-control" title="Selecione o Tamanho" placeholder="Tamanho" data-width="" data-size="7">
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
        <div class="row g-3 form-group align-items-center d-none div-cor">
            <div class="col-2">
                <label class="col-form-label text text-danger" for="cor">Cor</label>
            </div>
            <div class="col-10">
                <select name="cor" id="cor_edit" class="selectpicker show-tick form-control" title="Selecione a Cor" placeholder="Cor" data-width="" data-size="7">
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
        <?php
            }
        ?>
        <div class="row form-group g-3 align-items-center">
            <div class="col-2">
                <label class="text" for="valor_custo">Custo</label>
            </div>
            <div class="col-10">
                <input type="text" name="valor_custo" id="valor_custo_edit" value="<?php echo number_format($row->valor_custo, 2, '.', ','); ?>" maxlength="20" class="form-control col-6" title="Informe o valor de custo" placeholder="Valor de custo">
            </div>
        </div>
        <div class="row form-group g-3 align-items-center">
            <div class="col-2">
                <label class="text text-danger" for="valor_venda">Venda</label>
            </div>
            <div class="col-10">
                <input type="text" name="valor_venda" id="valor_venda_edit" value="<?php echo number_format($row->valor_venda, 2, '.', ','); ?>" maxlength="20" class="form-control col-6" title="Informe o valor de venda" placeholder="Valor de venda" required>
            </div>
        </div>
        <div class="form-group">
            <label for="observacao">Observa&ccedil;&atilde;o</label>
            <textarea name="observacao" id="observacao_edit" class="form-control" title="Se tiver alguma observa&ccedil;&atilde;o" placeholder="Observa&ccedil;&atilde;o"><?php echo $row->observacao; ?></textarea>
        </div>
    </div>
    <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
        <button type="submit" class="btn btn-primary btn-edit-produto">Salvar</button>
    </div>
</form>
<script defer>
    $(document).ready(function() {
        const fade = 150, delay = 100, timeout = 60000, showmap = false, autobusca = true,
            Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 1000
            });

        // SELECT PICKER

        $('#descricao_edit, #tipo_edit, #tamanho_edit, #cor_edit').selectpicker();

        $('#descricao_edit').change(() => {
            let option = $('#descricao_edit option').filter(':selected').val();
            
                switch (option) {
                    case 'Serviço':
                        $(".div-tipo").addClass('d-none');
                        $("#tipo_edit").removeAttr('required', true).val('');
                        $(".div-tamanho").addClass('d-none');
                        $("#tamanho_edit").removeAttr('required', true).val('');
                        $(".div-cor").addClass('d-none');
                        $("#cor_edit").removeAttr('required', true).val('');
                        break;
                    case 'Tela':
                        $(".div-tipo").addClass('d-none');
                        $("#tipo_edit").removeAttr('required', true).val('');
                        $(".div-tamanho").addClass('d-none');
                        $("#tamanho_edit").removeAttr('required', true).val('');
                        $(".div-cor").addClass('d-none');
                        $("#cor_edit").removeAttr('required', true).val('');
                        break;
                    case 'Manutenção Tela':
                        $(".div-tipo").addClass('d-none');
                        $("#tipo_edit").removeAttr('required', true).val('');
                        $(".div-tamanho").addClass('d-none');
                        $("#tamanho_edit").removeAttr('required', true).val('');
                        $(".div-cor").addClass('d-none');
                        $("#cor_edit").removeAttr('required', true).val('');
                        break;
                    default:
                        $(".div-tipo").removeClass('d-none');
                        $("#tipo_edit").attr('required', true).val('')
                        $(".div-tamanho").removeClass('d-none');
                        $("#tamanho_edit").attr('required', true).val('')
                        $(".div-cor").removeClass('d-none');
                        $("#cor_edit").attr('required', true).val('');
                        break;
                }
        });

        // INPUTMASK

        $('#valor_custo_edit, #valor_venda_edit').inputmask({
            'alias': 'numeric',
            'groupSeparator': ',',
            'autoGroup': true,
            'digits': 2,
            'digitsOptional': false,
            'prefix': '',
            'placeholder': '0'
        });

        // EDITA PRODUTO

        $('.form-edit-produto').submit(function(e) {
            e.preventDefault();

            $.post('api/produto/update.php', $(this).serialize(), function(data) {
                $('.btn-edit-produto').html('<img src="dist/img/rings.svg" class="loader-svg">').fadeTo(fade, 1);

                switch(data) {
                case 'true':
                    Toast.fire({icon: 'success',title: 'Produto editado.'}).then((result) => {
                        window.setTimeout("location.href='produto'", delay);
                    });
                    break;

                default:
                    Toast.fire({icon: 'error',title: data});
                    break;
                }

                $('.btn-edit-produto').html('Salvar').fadeTo(fade, 1);
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
                    <p>O produto não foi encontrado.</p>
                </blockquote>';
            }
        } else {
            die(var_dump($db->errorInfo()));
        }
?>