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
    include_once 'api/objects/usuario.php';
        
        // check for active user
        
        if (empty($_SESSION['key'])) {
            header ('location:./');
        }

        // uncrypt password

        function decrypt($data, $key) {
            $len = strlen($key);
            
                if ($len < 16) {
                    $key = str_repeat($key, ceil(16 / $len));
                    $data = base64_decode($data);
                    $val = openssl_decrypt($data, 'AES-256-OFB', $key, 0, $key);
                    $val =  str_replace(' ', '', $val);
                } else {
                    die('N&atilde;o foi poss&iacute;vel descriptografar o login');
                }
            
            return $val;
        }
    
    // get database connection

    $database = new Database();
    $db = $database->getConnection();

    // prepare objects
    
    $usuario = new Usuario($db);

    // GET variables

    $py_idusuario = md5('idusuario');
    $py_usuario = md5('usuario');
    $usuario->idusuario = $_GET[''.$py_idusuario.''];
    #$usuario->nome = $_GET[''.$py_usuario.''];

    // key for unlock pass

    $enigma = base64_encode('?');

        // retrieve query

        if ($sql = $usuario->readSingle()) {
            if ($sql->rowCount() > 0) {
                $row = $sql->fetch(PDO::FETCH_OBJ);
?>
<form class="form-edit-usuario">
    <div class="modal-header">
        <h4 class="modal-title">
            <span>Editar usu&aacute;rio</span>
            <span class="text-muted">
                <small>(<i class="fas fa-bell"></i> Campo obrigat&oacute;rio)</small>
            </span>
        </h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <input type="hidden" name="rand" id="rand_edit" value="<?php echo md5(mt_rand()); ?>">
        <input type="hidden" name="idusuario" id="idusuario_edit" value="<?php echo $row->idusuario; ?>">
        <input type="hidden" name="usuario_previous" id="usuario_previous_edit" value="<?php echo $_GET[''.$py_usuario.'']; ?>">

        <div class="row form-group g-3 align-items-center">
            <div class="col-2">
                <label class="text text-danger" for="nome">Nome</label>
            </div>
            <div class="col-10">
                <input type="text" name="nome" id="nome_edit" maxlength="200" class="form-control" value="<?php echo $row->nome; ?>" placeholder="Nome" required>
            </div>
        </div>
        <div class="row form-group g-3 align-items-center">
            <div class="col-2">
                <label class="text text-danger" for="usuario">Usu&aacute;rio</label>
            </div>
            <div class="col-10">
                <input type="text" name="usuario" id="usuario_edit" maxlength="10" class="form-control" value="<?php echo decrypt($row->usuario, $enigma); ?>" placeholder="Usu&aacute;rio" required>
            </div>
        </div>
        <div class="row form-group g-3 align-items-center">
            <div class="col-2">
                <label class="text text-danger" for="senha">Senha</label>
            </div>
            <div class="col-10">
                <input type="password" name="senha" id="senha_edit" maxlength="10" class="form-control" value="<?php echo decrypt($row->senha, $enigma); ?>" autocomplete="senha" spellcheck="false" autocorrect="off" autocapitalize="off" placeholder="Senha" required>
            </div>
        </div>
        <div class="row form-group g-3 align-items-center">
            <div class="col-2">
                <label class="text text-danger" for="senha">Email</label>
            </div>
            <div class="col-10">
                <input type="email" name="email" id="email_edit" maxlength="100" class="form-control" value="<?php echo $row->email; ?>" placeholder="Email" required>
            </div>
        </div>
    </div>
    <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
        <button type="submit" class="btn btn-primary btn-edit-usuario">Salvar</button>
    </div>
</form>
<script defer>
    $(document).ready(function() {
        const fade = 150, delay = 100,
            Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2000
            });

        // SHOW PASS

        $('#senha_edit').password();
        
        // EDITAR USU??RIO

        $('.form-edit-usuario').submit(function(e) {
            e.preventDefault();

            $.post('api/usuario/update.php', $(this).serialize(), function(data) {
                $('.btn-edit-usuario').html('<img src="dist/img/rings.svg" class="loader-svg">').fadeTo(fade, 1);

                switch(data) {
                case 'reload':
                    Toast.fire({icon: 'success',title: 'Usu&aacute;rio editado.'}).then((result) => {
                        window.setTimeout("location.href='sair'", delay);
                    });
                    break;

                case 'true':
                    Toast.fire({icon: 'success',title: 'Usu&aacute;rio editado.'}).then((result) => {
                        window.setTimeout("location.href='usuario'", delay);
                    });
                    break;                

                default:
                    Toast.fire({icon: 'error',title: data});
                    break;
                }

                $('.btn-edit-usuario').html('Salvar').fadeTo(fade, 1);
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
                        <p>O usu&aacute;rio n??o foi encontrado.</p>
                    </blockquote>';
                }
            }
?>