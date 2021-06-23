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

        // check for active user

        if (empty($_SESSION['key'])) {
            header ('location:./');
        }
    
    // get database connection

    $database = new Database();
    $db = $database->getConnection();

    // prepare object

    $cliente = new Cliente($db);

    // GET variables

    $py_idcliente = md5('idcliente');
    $cliente->idcliente = $_GET[''.$py_idcliente.''];

        // retrieve query

        if ($sql = $cliente->readSingle()) {
            if ($sql->rowCount() > 0) {
                $row = $sql->fetch(PDO::FETCH_OBJ);
?>
<form class="form-edit-cliente">
    <div class="modal-header">
        <h4 class="modal-title">
            <span>Editar o cadastro do cliente</span>
        </h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-6">
                <input type="hidden" name="rand" id="rand_edit" value="<?php echo rand(); ?>">
                <input type="hidden" name="idcliente" id="idcliente_edit" value="<?php echo $row->idcliente; ?>">
            
                <?php
                    switch (strlen($row->documento)) {
                        case 14:
                ?>
                <div class="row form-group g-3 align-items-center">
                    <div class="col-2">
                        <label class="text text-danger" for="pessoa">Pessoa</label>
                    </div>
                    <div class="col-10">
                        <span class="form-icheck"><input type="radio" name="pessoa" id="fisica_edit" value="F" checked> F&iacute;sica</span>
                        <span class="form-icheck"><input type="radio" name="pessoa" id="juridica_edit" value="J"> Jur&iacute;dica</span>
                    </div>
                </div>
                <div class="row form-group g-3 align-items-center">
                    <div class="col-2">
                        <label class="text text-danger" for="nome">Nome</label>
                    </div>
                    <div class="col-10">
                        <input type="text" name="nome" id="nome_edit" value="<?php echo $row->nome; ?>" maxlength="200" class="form-control" title="Informe o nome do cliente" placeholder="Nome do cliente" required>
                    </div>
                </div>
                <div class="row form-group g-3 align-items-center">
                    <div class="col-2">
                        <label class="label-documento" for="cpf">CPF</label>
                    </div>
                    <div class="col-5">
                        <input type="text" name="cpf" id="cpf_edit" class="form-control" value="<?php echo $row->documento; ?>" maxlength="14" title="Informe o CPF do cliente" placeholder="CPF">
                        <input type="text" name="cnpj" id="cnpj_edit" class="form-control d-none" maxlength="18" title="Informe o CNPJ do cliente" placeholder="CNPJ">
                    </div>
                    <div class="col-5">
                        <cite class="msg-documento text-danger"></cite>
                    </div>
                </div>
                <?php
                            break;
                        case 18:
                ?>
                <div class="row form-group g-3 align-items-center">
                    <div class="col-2">
                        <label class="text text-danger" for="pessoa">Pessoa</label>
                    </div>
                    <div class="col-10">
                        <span class="form-icheck"><input type="radio" name="pessoa" id="fisica_edit" value="F"> F&iacute;sica</span>
                        <span class="form-icheck"><input type="radio" name="pessoa" id="juridica_edit" value="J" checked> Jur&iacute;dica</span>
                    </div>
                </div>
                <div class="row form-group g-3 align-items-center">
                    <div class="col-2">
                        <label class="text text-danger" for="nome">Nome</label>
                    </div>
                    <div class="col-10">
                        <input type="text" name="nome" id="nome_edit" value="<?php echo $row->nome; ?>" maxlength="200" class="form-control" title="Informe o nome do cliente" placeholder="Nome do cliente" required>
                    </div>
                </div>
                <div class="row form-group g-3 align-items-center">
                    <div class="col-2">
                        <label class="label-documento" for="cpf">CPF</label>
                    </div>
                    <div class="col-5">
                        <input type="text" name="cpf" id="cpf_edit" class="form-control d-none" maxlength="14" title="Informe o CPF do cliente" placeholder="CPF">
                        <input type="text" name="cnpj" id="cnpj_edit" class="form-control" value="<?php echo $row->documento; ?>" maxlength="18" title="Informe o CNPJ do cliente" placeholder="CNPJ">
                    </div>
                    <div class="col-5">
                        <cite class="msg-documento text-danger"></cite>
                    </div>
                </div>
                <?php
                            break;
                        default:
                ?>
                <div class="row form-group g-3 align-items-center">
                    <div class="col-2">
                        <label class="text text-danger" for="pessoa">Pessoa</label>
                    </div>
                    <div class="col-10">
                        <span class="form-icheck"><input type="radio" name="pessoa" id="fisica_edit" value="F" checked> F&iacute;sica</span>
                        <span class="form-icheck"><input type="radio" name="pessoa" id="juridica_edit" value="J"> Jur&iacute;dica</span>
                    </div>
                </div>
                <div class="row form-group g-3 align-items-center">
                    <div class="col-2">
                        <label class="text text-danger" for="nome">Nome</label>
                    </div>
                    <div class="col-10">
                        <input type="text" name="nome" id="nome_edit" value="<?php echo $row->nome; ?>" maxlength="200" class="form-control" title="Informe o nome do cliente" placeholder="Nome do cliente" required>
                    </div>
                </div>
                <div class="row form-group g-3 align-items-center">
                    <div class="col-2">
                        <label class="label-documento" for="cpf">CPF</label>
                    </div>
                    <div class="col-5">
                        <input type="text" name="cpf" id="cpf_edit" class="form-control" value="<?php echo $row->documento; ?>" maxlength="14" title="Informe o CPF do cliente" placeholder="CPF">
                        <input type="text" name="cnpj" id="cnpj_edit" class="form-control d-none" maxlength="18" title="Informe o CNPJ do cliente" placeholder="CNPJ">
                    </div>
                    <div class="col-5">
                        <cite class="msg-documento text-danger"></cite>
                    </div>
                </div>
                <?php
                    }
                ?>
                <div class="row form-group g-3 align-items-center">
                    <div class="col-2">
                        <label for="telefone">Telefone</label>
                    </div>
                    <div class="col-10">
                        <input type="tel" name="telefone" id="telefone_edit" value="<?php echo $row->telefone; ?>" maxlength="13" class="form-control col-4" title="Informe o telefone do cliente" placeholder="Telefone">
                    </div>
                </div>
                <div class="row form-group g-3 align-items-center">
                    <div class="col-2">
                        <label for="celular">Celular</label>
                    </div>
                    <div class="col-10">
                        <input type="tel" name="celular" id="celular_edit" value="<?php echo $row->celular; ?>" maxlength="14" class="form-control col-4" title="Informe o celular do cliente" placeholder="Celular">
                    </div>
                </div>
                <div class="row form-group g-3 align-items-center">
                    <div class="col-2">
                        <label for="email">Email</label>   
                    </div>
                    <div class="col-10">
                        <input type="email" name="email" id="email_edit" value="<?php echo $row->email; ?>" maxlength="100" class="form-control" title="Informe o email do cliente" placeholder="Email">
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="row form-group g-3 align-items-center">
                    <div class="col-2">
                        <label for="cep">CEP</label>
                    </div>
                    <div class="col-3">
                        <input type="text" name="cep" id="cep_edit" value="<?php echo $row->cep; ?>" maxlength="9" class="form-control" title="Informe o CEP" placeholder="CEP">
                    </div>
                    <div class="col-7">
                        <cite class="msg-cep text-danger"></cite>
                    </div>
                </div>
                <div class="row form-group g-3 align-items-center">
                    <div class="col-2">
                        <label for="endereco">Endere&ccedil;o</label>    
                    </div>
                    <div class="col-10">
                        <input type="text" name="endereco" id="endereco_edit" value="<?php echo $row->endereco; ?>" maxlength="200" class="form-control" title="Informe o Endere&ccedil;o do cliente" placeholder="Endere&ccedil;o">
                    </div>
                </div>
                <div class="row form-group g-3 align-items-center">
                    <div class="col-2">
                        <label for="bairro">Bairro</label>    
                    </div>
                    <div class="col-10">
                        <input type="text" name="bairro" id="bairro_edit" value="<?php echo $row->bairro; ?>" maxlength="100" class="form-control col-5" title="Informe o bairro do endere&ccedil;o" placeholder="Bairro">
                    </div>
                </div>
                <div class="row form-group g-3 align-items-center">
                    <div class="col-2">
                        <label for="cidade">Cidade</label>
                    </div>
                    <div class="col-10">
                        <input type="text" name="cidade" id="cidade_edit" value="<?php echo $row->cidade; ?>" maxlength="100" class="form-control col-5" title="Informe a cidade do endere&ccedil;o" placeholder="Cidade">
                    </div>
                </div>
                <div class="row form-group g-3 align-items-center">
                    <div class="col-2">
                        <label for="estado">Estado</label>
                    </div>
                    <div class="col-10">
                        <select name="estado" id="estado_edit" class="selectpicker show-tick form-control" title="Selecione o estado" placeholder="Estado" data-width="" data-size="7" required>
                        <?php
                            if ($row->estado == 'AC') {echo'<option value="AC" selected="selected">AC</option>';} else {echo'<option value="AC">AC</option>'; $a = 1;}
                            if ($row->estado == 'AL') {echo'<option value="AL" selected="selected">AL</option>';} else {echo'<option value="AL">AL</option>'; $a++;}
                            if ($row->estado == 'AM') {echo'<option value="AM" selected="selected">AM</option>';} else {echo'<option value="AM">AM</option>'; $a++;}
                            if ($row->estado == 'AP') {echo'<option value="AP" selected="selected">AP</option>';} else {echo'<option value="AP">AP</option>'; $a++;}
                            if ($row->estado == 'BA') {echo'<option value="BA" selected="selected">BA</option>';} else {echo'<option value="BA">BA</option>'; $a++;}
                            if ($row->estado == 'CE') {echo'<option value="CE" selected="selected">CE</option>';} else {echo'<option value="CE">CE</option>'; $a++;}
                            if ($row->estado == 'DF') {echo'<option value="DF" selected="selected">DF</option>';} else {echo'<option value="DF">DF</option>'; $a++;}
                            if ($row->estado == 'ES') {echo'<option value="ES" selected="selected">ES</option>';} else {echo'<option value="ES">ES</option>'; $a++;}
                            if ($row->estado == 'GO') {echo'<option value="GO" selected="selected">GO</option>';} else {echo'<option value="GO">GO</option>'; $a++;}
                            if ($row->estado == 'MA') {echo'<option value="MA" selected="selected">MA</option>';} else {echo'<option value="MA">MA</option>'; $a++;}
                            if ($row->estado == 'MG') {echo'<option value="MG" selected="selected">MG</option>';} else {echo'<option value="MG">MG</option>'; $a++;}
                            if ($row->estado == 'MS') {echo'<option value="MS" selected="selected">MS</option>';} else {echo'<option value="MS">MS</option>'; $a++;}
                            if ($row->estado == 'MT') {echo'<option value="MT" selected="selected">MT</option>';} else {echo'<option value="MT">MT</option>'; $a++;}
                            if ($row->estado == 'PA') {echo'<option value="PA" selected="selected">PA</option>';} else {echo'<option value="PA">PA</option>'; $a++;}
                            if ($row->estado == 'PB') {echo'<option value="PB" selected="selected">PB</option>';} else {echo'<option value="PB">PB</option>'; $a++;}
                            if ($row->estado == 'PE') {echo'<option value="PE" selected="selected">PE</option>';} else {echo'<option value="PE">PE</option>'; $a++;}
                            if ($row->estado == 'PI') {echo'<option value="PI" selected="selected">PI</option>';} else {echo'<option value="PI">PI</option>'; $a++;}
                            if ($row->estado == 'PR') {echo'<option value="PR" selected="selected">PR</option>';} else {echo'<option value="PR">PR</option>'; $a++;}
                            if ($row->estado == 'RJ') {echo'<option value="RJ" selected="selected">RJ</option>';} else {echo'<option value="RJ">RJ</option>'; $a++;}
                            if ($row->estado == 'RN') {echo'<option value="RN" selected="selected">RN</option>';} else {echo'<option value="RN">RN</option>'; $a++;}
                            if ($row->estado == 'RO') {echo'<option value="RO" selected="selected">RO</option>';} else {echo'<option value="RO">RO</option>'; $a++;}
                            if ($row->estado == 'RR') {echo'<option value="RR" selected="selected">RR</option>';} else {echo'<option value="RR">RR</option>'; $a++;}
                            if ($row->estado == 'RS') {echo'<option value="RS" selected="selected">RS</option>';} else {echo'<option value="RS">RS</option>'; $a++;}
                            if ($row->estado == 'SC') {echo'<option value="SC" selected="selected">SC</option>';} else {echo'<option value="SC">SC</option>'; $a++;}
                            if ($row->estado == 'SE') {echo'<option value="SE" selected="selected">SE</option>';} else {echo'<option value="SE">SE</option>'; $a++;}
                            if ($row->estado == 'SP') {echo'<option value="SP" selected="selected">SP</option>';} else {echo'<option value="SP">SP</option>'; $a++;}
                            if ($row->estado == 'TO') {echo'<option value="TO" selected="selected">TO</option>';} else {echo'<option value="TO">TO</option>'; $a++;}

                            if ($a == 27) {
                                echo'
                                <option value="AC">AC</option>
                                <option value="AL">AL</option>
                                <option value="AM">AM</option>
                                <option value="AP">AP</option>
                                <option value="BA">BA</option>
                                <option value="CE">CE</option>
                                <option value="DF">DF</option>
                                <option value="ES">ES</option>
                                <option value="GO">GO</option>
                                <option value="MA">MA</option>
                                <option value="MG">MG</option>
                                <option value="MS">MS</option>
                                <option value="MT">MT</option>
                                <option value="PA">PA</option>
                                <option value="PB">PB</option>
                                <option value="PE">PE</option>
                                <option value="PI">PI</option>
                                <option value="PR">PR</option>
                                <option value="RJ">RJ</option>
                                <option value="RN">RN</option>
                                <option value="RO">RO</option>
                                <option value="RR">RR</option>
                                <option value="RS">RS</option>
                                <option value="SC">SC</option>
                                <option value="SE">SE</option>
                                <option value="SP">SP</option>
                                <option value="TO">TO</option>';
                            }
                        ?>
                        </select>
                    </div>
                </div>
                <div class="row form-group g-3 align-items-center">
                    <div class="col-2">
                        <label for="observacao">Observa&ccedil;&atilde;o</label>
                    </div>
                    <div class="col-10">
                        <textarea name="observacao" id="observacao_edit" class="form-control" title="Se tiver alguma observa&ccedil;&atilde;o" placeholder="Observa&ccedil;&atilde;o"><?php echo $row->observacao; ?></textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
        <button type="submit" class="btn btn-primary btn-edit-cliente">Salvar</button>
    </div>
</form>
<script defer>
    $(document).ready(function() {
        const fade = 150, delay = 100, timeout = 60000, showmap = false, autobusca = true,
            Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2000
            });
        
        // MASK
        
        $('#cpf_edit').inputmask('999.999.999-99');
        $('#cnpj_edit').inputmask('99.999.999/9999-99');
        $('#cep_edit').inputmask('99999-999');
        $('#telefone_edit').inputmask('(99)9999-9999');
        $('#celular_edit').inputmask('(99)99999-9999');

        // ICHECK

        $("input[type='radio']").show(function () {
            $("input[type='radio']").iCheck({
                radioClass: 'iradio_minimal'
            });
        });
        
        $("#fisica_edit").on("ifChecked", function(event){
            $(".label-pessoa").html('Nome');
            $(".label-documento").html('CPF <cite class="msg-documento badge badge-danger"></cite>');

            $("#nome_edit").attr("placeholder", "Nome");
            //$("#cpf_edit").attr("required", "");
            //$("#cnpj_edit").removeAttr("required", "");

            $("#cpf_edit, .msg-cpf").removeClass("d-none");
            $("#cnpj_edit, .msg-cnpj").addClass("d-none");

            //$("#nome_edit, #cnpj_edit, .msg-cnpj").val("");
            $("#cnpj_edit, .msg-cnpj").val("");
        });

        $("#juridica_edit").on("ifChecked", function(event){
            $(".label-pessoa").html('Razão Social');
            $(".label-documento").html('CNPJ <cite class="msg-documento badge badge-danger"></cite>');

            $("#nome_edit").attr("placeholder", "Razão Social");
            //$("#cnpj_edit").attr("required", "");
            //$("#cpf_edit").removeAttr("required", "");

            $("#cnpj_edit, .msg-cnpj").removeClass("d-none");
            $("#cpf_edit, .msg-cpf").addClass("d-none");

            //$("#nome_edit, #cpf_edit, .msg-cpf").val("");
            $("#cpf_edit, .msg-cpf").val("");
        });

        // SELECT PICKER

        $('#estado_edit').selectpicker();

        // CHECK CPF

        function checkCPF() {
            $.post("components/loadCPF.php",{
                cpf: $.trim($("#cpf_edit").val())
            }, function (data) {
                    if (data == "true") {
                        $("#cpf_edit").css("background", "transparent");
                        $(".msg-documento").css("display", "none");
                    } else {
                        $("#cpf_edit").focus();
                        $("#cpf_edit").css("background", "transparent");
                        $(".msg-documento").html("CPF inv&aacute;lido");
                    }
            })
        }

            if (autobusca === true) {
                $("#cpf_edit").keyup(function(){
                    if ($("#cpf_edit").val().length == 14){
                        if ($("#cpf_edit").val().match(/_/g)) {
                        } else {
                            checkCPF();
                            $("#cpf_edit").css("background", "transparent url('dist/img/rings-black.svg') right center no-repeat");
                        }
                    }
                });
            }

        // CHECK CNPJ

        function checkCNPJ() {
            $.post("components/loadCNPJ.php",{
                cnpj: $.trim($("#cnpj_edit").val())
            }, function (data) {
                    if (data == "true") {
                        $("#cnpj_edit").css("background", "transparent");
                        $(".msg-documento").css("display", "none");
                    }
                    else {
                        $("#cnpj_edit").focus();
                        $("#cnpj_edit").css("background", "transparent");
                        $(".msg-documento").html("CNPJ inv&aacute;lido");
                    }
            })
        }

            if (autobusca === true) {
                $("#cnpj_edit").keyup(function(){
                    if ($("#cnpj_edit").val().length == 18){
                        if ($("#cnpj_edit").val().match(/_/g)) {
                        }
                        else {
                            checkCNPJ();
                            $("#cnpj_edit").css("background", "transparent url('dist/img/rings-black.svg') right center no-repeat");
                        }
                    }
                });
            }

        // CHECK CEP

        function searchCEP(showmap) {
            $.post("components/loadCEP.php",{
                cep: $.trim($("#cep_edit").val())
            }, function (data) {
                var rs = $.parseJSON(data);

                    if (rs.resultado === '1') {
                        $("#endereco_edit").val(rs.tipo_logradouro + ' ' + rs.logradouro + ', ');
                        $("#bairro_edit").val(rs.bairro);
                        $("#cidade_edit").val(rs.cidade);
                        $("#estado_edit").val(rs.uf);
                        $("#cep_edit").css("background", "transparent");
                        $(".msg-cep").css("display", "none");
                    } else {
                        $("#cep_edit").focus();
                        $("#cep_edit").css("background", "transparent");
                        $(".msg-cep").html("CEP inv&aacute;lido");
                    }
            })
        }

            if (autobusca === true) {
                $("#cep_edit").on("keyup",function(){
                    if ($("#cep_edit").val().length >= 9){
                        if ($("#cep_edit").val().match(/_/g)) {
                        }
                        else {
                            searchCEP(showmap);
                            $("#cep_edit").css("background","transparent url('dist/img/rings-black.svg') right center no-repeat");
                        }
                    }
                });
            }
        
        // EDITAR CLIENTE

        $('.form-edit-cliente').submit(function(e) {
            e.preventDefault();

            $.post('api/cliente/update.php', $(this).serialize(), function(data) {
                $('.btn-edit-cliente').html('<img src="dist/img/rings.svg" class="loader-svg">').fadeTo(fade, 1);

                switch (data) {
                case 'true':
                    Toast.fire({icon: 'success', title: 'Cliente editado.'}).then((result) => {
                        window.setTimeout("location.href='cliente'", delay);
                    });
                    break;

                default:
                    Toast.fire({icon: 'error', title: data});
                    break;
                }

                $('.btn-edit-cliente').html('Salvar').fadeTo(fade, 1);
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
                    <p>O cliente n&atilde;o foi encontrado.</p>
                </blockquote>';
            }
        } else {
            die(var_dump($db->errorInfo()));
        }
?>