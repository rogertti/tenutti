<!-- Navbar -->

<nav class="main-header navbar navbar-expand navbar-white navbar-light">

    <!-- Left navbar links -->

    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <!--<li class="nav-item d-none d-sm-inline-block">
            <a href="index3.html" class="nav-link">Home</a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="#" class="nav-link">Contact</a>
        </li>-->
    </ul>

    <!-- Search Form -->

    <form class="form-inline ml-3 form-search d-none">
        <div class="input-group input-group-sm">
            <input type="search" name="search_keyword" id="search_keyword"
                class="form-control form-control-navbar toggle-search" title="Busque pela data (dd/mm/aaaa);
                Busque pelo cliente (#cliente);
                Busque por qualquer solicita&ccedil;&atilde;o" placeholder="Buscador geral" aria-label="Buscador geral">

            <div class="input-group-append">
                <button class="btn btn-navbar" type="submit">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>
    </form>

    <div class="div-search-close">
        <a title="Fechar o buscador"><span class="fas fa-times-circle fa-lg icon-search-close"></span></a>
    </div>

    <!-- Right navbar links -->

    <ul class="navbar-nav ml-auto">
        <li class="nav-item">
            <a class="nav-link a-usuario" href="usuario"><mark>Hi <span><?php echo $_SESSION['name']; ?>!</span></mark></a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="backup" title="Back up" role="button" data-toggle="tooltip" data-original-title="Back up do app">
                <i class="fas fa-database"></i>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link a-logout-app" href="sair" title="Sair" role="button" data-toggle="tooltip" data-original-title="Sair do app">
                <i class="fas fa-sign-out-alt"></i>
            </a>
        </li>
    </ul>
</nav>
<!-- /.navbar -->