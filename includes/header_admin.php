<?php ?>

<div class="modal" id="loading2" tabindex="-1" role="dialog" aria-hidden="true">
    <div style="text-align: center; margin-top: 15%">
        <img src="includes/images/loader_page.gif" width="110px" height="110px">
<!--  <span class="glyphicon glyphicon-refresh glyphicon-refresh-animate" style="font-size: 50px; color: #fff; font-weight: bold"></span>-->
    </div>
</div>
<div class="modal fade" id="myModal8" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Logout</h4>
            </div>
            <div class="modal-body">
                <p>Deseja realmente sair ?</p>
            </div>
            <form method="post" class="noAjax"  action="logout.php" name="login_form">
                <input type="hidden" name="sair" value="sair">
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Sair</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<nav class="navbar navbar-default">
    <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Menu <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <?php if (isset($_SESSION['admin_id'])) { ?>
                            <li><a href="revenda.php"><?= MENU_REVENDAS ?></a></li>
                            <li><a href="usuario.php"><?= MENU_USUARIOS ?></a></li>
                            <li><a href="plano_assinatura.php"><?= MENU_PLANO_ASSINATURA ?></a></li>
                            <li><a href="financeiro.php"><?= MENU_FINANCEIRO ?></a></li>
                        <?php } elseif (isset($_SESSION['revenda_id'])) { ?>
                            <li><a href="usuario.php"><?= MENU_USUARIOS ?></a></li>
                            <li><a href="plano_assinatura.php"><?= MENU_PLANO_ASSINATURA ?></a></li>
                            <li><a href="financeiro.php"><?= MENU_FINANCEIRO ?></a></li>
                        <?php } ?>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Relatórios <span class="caret"></span></a>
                    <ul class="dropdown-menu">

                        <?php if (isset($_SESSION['admin_id']) || isset($_SESSION['revenda_id'])) { ?>
<!--                            <li><a href="a_receber.php"><?= RELATORIO_A_RECEBER ?></a></li>
                                <li><a href="vencidos.php"><?= RELATORIO_A_VENCIDOS ?></a></li>-->
                        <?php } ?>
                        <?php //if (isset($_SESSION['admin_id'])) { ?>
                            <li><a href="usuario_atrazo.php?page=<?= $dataGet['page'] ?>" ><?= CLIENTES_COM_ATRASO ?></a></li>
                        <?php //} ?>

                         

                    </ul>
                </li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="label label-primary"><?= $_SESSION['admin_login']; ?></span> <b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li><a href="#myModal8" data-toggle="modal">Logout</a></li>
                    </ul>
                </li>
            </ul>

            <ul class="nav navbar-nav navbar-right">
                <!--                        <li><a href="#">Link</a></li>
                                        <li class="dropdown">
                                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Dropdown <span class="caret"></span></a>
                                            <ul class="dropdown-menu">
                                                <li><a href="#">Action</a></li>
                                                <li><a href="#">Another action</a></li>
                                                <li><a href="#">Something else here</a></li>
                                                <li role="separator" class="divider"></li>
                                                <li><a href="#">Separated link</a></li>
                                            </ul>
                                        </li>-->
            </ul>

        </div><!-- /.navbar-collapse -->

    </div><!-- /.container-fluid -->
</nav>
