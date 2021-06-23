<?php
    require_once 'appConfig.php';

        if (empty($_SESSION['key'])) {
            header ('location:./');
        }

    $menu = 2;
    #echo md5('idcliente');
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
        <!-- SweetAlert2 -->
        <link rel="stylesheet" href="plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
        <!-- AdminLTE App -->
        <link rel="stylesheet" href="dist/css/adminlte.min.css">
        <!-- Custom App -->
        <link rel="stylesheet" href="dist/css/main.css">
        <!-- Google Font: Source Sans Pro -->
        <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
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
                                    <span>Clientes</span>
                                    <span class="float-right">
                                      <a href="#" class="btn btn-primary" title="Clique para cadastrar um novo cliente" data-toggle="modal" data-target="#modal-new-cliente">
                                        <i class="fas fa-user-tie"></i> Novo cliente
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
                                    <th>Nome</th>
                                    <th>Documento</th>
                                    <th>Celular</th>
                                    <th style="max-width: 100px;width: 90px;"></th>
                                </thead>
                                <tbody>
                                </tbody>
                                <tfoot>
                                    <th>Nome</th>
                                    <th>Documento</th>
                                    <th>Celular</th>
                                    <th style="max-width: 100px;width: 90px;"></th>
                                </tfoot>
                            </table>

                            <blockquote class="blockquote-data d-none">
                                <h5>Nada encontrado</h5>
                                <p>Nenhum cliente cadastrado.</p>
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
            <div class="modal fade" id="modal-new-cliente">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <form class="form-new-cliente">
                            <div class="modal-header">
                                <h4 class="modal-title">
                                    <span>Novo cliente</span>
                                </h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                          <div class="modal-body">
                                <div class="row">
                                  <div class="col-6">
                                      <input type="hidden" name="rand" id="rand" value="<?php echo rand(); ?>">
                                    
                                    <div class="row form-group g-3 align-items-center">
                                        <div class="col-2">
                                            <label class="text text-danger" for="pessoa">Pessoa</label>
                                        </div>
                                        <div class="col-10">
                                            <span class="form-icheck"><input type="radio" name="pessoa" id="fisica" value="F" checked> F&iacute;sica</span>
                                            <span class="form-icheck"><input type="radio" name="pessoa" id="juridica" value="J"> Jur&iacute;dica</span>
                                        </div>
                                    </div>
                                    <div class="row form-group g-3 align-items-center">
                                        <div class="col-2">
                                            <label class="text text-danger" for="nome">Nome</label>
                                        </div>
                                        <div class="col-10">
                                            <input type="text" name="nome" id="nome" maxlength="200" class="form-control" title="Informe o nome do cliente" placeholder="Nome do cliente" required>
                                        </div>
                                    </div>
                                    <div class="row form-group g-3 align-items-center">
                                        <div class="col-2">
                                            <label class="label-documento" for="cpf">CPF</label>
                                        </div>
                                        <div class="col-5">
                                            <input type="text" name="cpf" id="cpf" class="form-control" maxlength="14" title="Informe o CPF do cliente" placeholder="CPF">
                                            <input type="text" name="cnpj" id="cnpj" class="form-control d-none" maxlength="18" title="Informe o CNPJ do cliente" placeholder="CNPJ">
                                        </div>
                                        <div class="col-5">
                                            <cite class="msg-documento text-danger"></cite>
                                        </div>
                                    </div>
                                    <div class="row form-group g-3 align-items-center">
                                        <div class="col-2">
                                            <label for="telefone">Telefone</label>
                                        </div>
                                        <div class="col-10">
                                            <input type="tel" name="telefone" id="telefone" maxlength="13" class="form-control col-4" title="Informe o telefone do cliente" placeholder="Telefone">
                                        </div>
                                    </div>
                                    <div class="row form-group g-3 align-items-center">
                                        <div class="col-2">
                                            <label for="celular">Celular</label>
                                        </div>
                                        <div class="col-10">
                                            <input type="tel" name="celular" id="celular" maxlength="14" class="form-control col-4" title="Informe o celular do cliente" placeholder="Celular">
                                        </div>
                                    </div>
                                    <div class="row form-group g-3 align-items-center">
                                        <div class="col-2">
                                            <label for="email">Email</label>   
                                        </div>
                                        <div class="col-10">
                                            <input type="email" name="email" id="email" maxlength="100" class="form-control" title="Informe o email do cliente" placeholder="Email">
                                        </div>
                                    </div>
                                  </div>
                                  <div class="col-6">
                                    <div class="row form-group g-3 align-items-center">
                                        <div class="col-2">
                                            <label for="cep">CEP</label>
                                        </div>
                                        <div class="col-3">
                                            <input type="text" name="cep" id="cep" maxlength="9" class="form-control" title="Informe o CEP" placeholder="CEP">
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
                                            <input type="text" name="endereco" id="endereco" maxlength="200" class="form-control" title="Informe o Endere&ccedil;o do cliente" placeholder="Endere&ccedil;o">
                                        </div>
                                    </div>
                                    <div class="row form-group g-3 align-items-center">
                                        <div class="col-2">
                                            <label for="bairro">Bairro</label>    
                                        </div>
                                        <div class="col-10">
                                            <input type="text" name="bairro" id="bairro" maxlength="100" class="form-control col-5" title="Informe o bairro do endere&ccedil;o" placeholder="Bairro">
                                        </div>
                                    </div>
                                    <div class="row form-group g-3 align-items-center">
                                        <div class="col-2">
                                            <label for="cidade">Cidade</label>
                                        </div>
                                        <div class="col-10">
                                            <input type="text" name="cidade" id="cidade" maxlength="100" class="form-control col-5" title="Informe a cidade do endere&ccedil;o" placeholder="Cidade">
                                        </div>
                                    </div>
                                    <div class="row form-group g-3 align-items-center">
                                        <div class="col-2">
                                            <label for="estado">Estado</label>
                                        </div>
                                        <div class="col-10">
                                            <select name="estado" id="estado" class="selectpicker show-tick form-control" title="Selecione o estado" placeholder="Estado" data-width="" data-size="7" required>
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
                                                <option value="TO">TO</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row form-group g-3 align-items-center">
                                        <div class="col-2">
                                            <label for="observacao">Observa&ccedil;&atilde;o</label>
                                        </div>
                                        <div class="col-10">
                                            <textarea name="observacao" id="observacao" class="form-control" title="Se tiver alguma observa&ccedil;&atilde;o" placeholder="Observa&ccedil;&atilde;o"></textarea>
                                        </div>
                                    </div>
                                  </div>
                              </div>
                          </div>
                              <div class="modal-footer justify-content-between">
                                  <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                                  <button type="submit" class="btn btn-primary btn-new-cliente">Salvar</button>
                              </div>
                        </form>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!-- /.modal -->

            <div class="modal fade" id="modal-edit-cliente">
                <div class="modal-dialog modal-xl">
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
        <!-- Icheck -->
        <script src="plugins/icheck-1.0.3/icheck.min.js"></script>
        <!-- Select Picker -->
        <script src="plugins/bootstrap-select-1.13.14/js/bootstrap-select.min.js"></script>
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
                        url: 'api/cliente/readAll.php',
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
                                            response += '<tr>'
                                            + '<td>' + data[i].nome + '</td>'
                                            + '<td>' + data[i].documento + '</td>'
                                            + '<td>' + data[i].celular + '</td>'
                                            + '<td class="td-action">'
                                            + '<span class="bg bg-warning"><a class="fas fa-pen a-edit-cliente" href="clienteEdit.php?2a826b467343694898894908cd75fafe=' + data[i].idcliente + '" title="Editar os dados do cliente"></a></span>'
                                            + '<span class="bg bg-danger"><a class="fas fa-trash a-delete-cliente" id="2a826b467343694898894908cd75fafe-' + data[i].idcliente + '" href="#" title="Excluir o cadastro do cliente"></a></span>'
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

                // MASK
        
                $('#cpf').inputmask('999.999.999-99');
                $('#cnpj').inputmask('99.999.999/9999-99');
                $('#cep').inputmask('99999-999');
                $('#telefone').inputmask('(99)9999-9999');
                $('#celular').inputmask('(99)99999-9999');

                // ICHECK

                $("input[type='radio']").show(function () {
                    $("input[type='radio']").iCheck({
                        radioClass: 'iradio_minimal'
                    });
                });
                
                $("#fisica").on("ifChecked", function(event){
                    $(".label-pessoa").html('Nome');
                    $(".label-documento").html('CPF');

                    $("#nome").attr("placeholder", "Nome");
                    //$("#cpf").attr("required", "");
                    //$("#cnpj").removeAttr("required", "");

                    $("#cpf, .msg-cpf").removeClass("d-none");
                    $("#cnpj, .msg-cnpj").addClass("d-none");

                    $("#nome, #cnpj, .msg-cnpj").val("");
                });

                $("#juridica").on("ifChecked", function(event){
                    $(".label-pessoa").html('Razão Social');
                    $(".label-documento").html('CNPJ');

                    $("#nome").attr("placeholder", "Razão Social");
                    //$("#cnpj").attr("required", "");
                    //$("#cpf").removeAttr("required", "");

                    $("#cnpj, .msg-cnpj").removeClass("d-none");
                    $("#cpf, .msg-cpf").addClass("d-none");

                    $("#nome, #cpf, .msg-cpf").val("");
                });

                // SELECT PICKER

                $('#estado').selectpicker();

                // CHECK CPF

                function checkCPF() {
                    $.post("components/loadCPF.php",{
                        cpf: $.trim($("#cpf").val())
                    }, function (data) {
                        if (data == "true") {
                            $("#cpf").css("background", "transparent");
                            $(".msg-documento").css("display", "none");
                        } else {
                            $("#cpf").focus();
                            $("#cpf").css("background", "transparent");
                            $(".msg-documento").html("Inv&aacute;lido");
                        }
                    })
                }

                    if (autobusca === true) {
                        $("#cpf").keyup(function(){
                            if ($("#cpf").val().length == 14){
                                if ($("#cpf").val().match(/_/g)) {
                                } else {
                                    checkCPF();
                                    $("#cpf").css("background", "transparent url('dist/img/rings-black.svg') right center no-repeat");
                                }
                            }
                        });
                    }

                // CHECK CNPJ

                function checkCNPJ() {
                    $.post("components/loadCNPJ.php",{
                        cnpj: $.trim($("#cnpj").val())
                    }, function (data) {
                        if (data == "true") {
                            $("#cnpj").css("background", "transparent");
                            $(".msg-documento").css("display", "none");
                        }
                        else {
                            $("#cnpj").focus();
                            $("#cnpj").css("background", "transparent");
                            $(".msg-documento").html("Inv&aacute;lido");
                        }
                    })
                }

                    if (autobusca === true) {
                        $("#cnpj").keyup(function(){
                            if ($("#cnpj").val().length == 18){
                                if ($("#cnpj").val().match(/_/g)) {
                                }
                                else {
                                    checkCNPJ();
                                    $("#cnpj").css("background", "transparent url('dist/img/rings-black.svg') right center no-repeat");
                                }
                            }
                        });
                    }

                // CHECK CEP

                function searchCEP(showmap) {
                    $.post("components/loadCEP.php",{
                        cep: $.trim($("#cep").val())
                    }, function (data) {
                        var rs = $.parseJSON(data);

                            if (rs.resultado === '1') {
                                $("#endereco").val(rs.tipo_logradouro + ' ' + rs.logradouro + ', ');
                                $("#bairro").val(rs.bairro);
                                $("#cidade").val(rs.cidade);
                                $("#estado").val(rs.uf);
                                $("#cep").css("background", "transparent");
                                $(".msg-cep").css("display", "none");
                            } else {
                                $("#cep").focus();
                                $("#cep").css("background", "transparent");
                                $(".msg-cep").html("Inv&aacute;lido");
                            }
                    })
                }

                    if (autobusca === true) {
                        $("#cep").on("keyup",function(){
                            if ($("#cep").val().length >= 9){
                                if ($("#cep").val().match(/_/g)) {
                                }
                                else {
                                    searchCEP(showmap);
                                    $("#cep").css("background","transparent url('dist/img/rings-black.svg') right center no-repeat");
                                }
                            }
                        });
                    }

                // MODAL
                
                $('.table-data').on('click', '.a-edit-cliente', function(e) {
                    e.preventDefault();
                    $('#modal-edit-cliente').modal('show').find('.modal-content').load($(this).attr('href'));
                });

                // NOVO CLIENTE

                $('.form-new-cliente').submit(function(e) {
                    e.preventDefault();

                    $.post('api/cliente/insert.php', $(this).serialize(), function(data) {
                        $('.btn-new-cliente').html('<img src="dist/img/rings.svg" class="loader-svg">').fadeTo(fade, 1);

                        switch (data) {
                        case 'true':
                            Toast.fire({icon: 'success',title: 'Cliente cadastrado.'}).then((result) => {
                                window.setTimeout("location.href='cliente'", delay);
                            });
                            break;

                        default:
                            Toast.fire({icon: 'error',title: data});
                            break;
                        }

                        $('.btn-new-cliente').html('Salvar').fadeTo(fade, 1);
                    });

                    return false;
                });

                // DELETE CLIENTE

                $('.table-data').on('click', '.a-delete-cliente', function(e) {
                    e.preventDefault();
                    
                    let click = this.id.split('-'),
                        py = click[0],
                        id = click[1];
                    
                    swalButton.fire({
                        icon: 'question',
                        title: 'Excluir o cadastro do cliente',
                        showCancelButton: true,
                        confirmButtonText: 'Sim',
                        cancelButtonText: 'Não',
                    }).then((result) => {
                        if (result.value == true) {
                            $.ajax({
                                type: 'GET',
                                url: 'api/cliente/delete.php?' + py + '=' + id,
                                dataType: 'json',
                                cache: false,
                                error: function(result) {
                                    Swal.fire({icon: 'error',html: result.responseText,showConfirmButton: false});
                                },
                                success: function(data) {
                                    if (data == true) {
                                        Toast.fire({icon: 'success',title: 'Cliente exclu&iacute;do.'}).then((result) => {
                                            window.setTimeout("location.href='cliente'", delay);
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