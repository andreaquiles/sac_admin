<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<div class="navbar">
    <div class="navbar-inner">
        <div class="container-fluid">

            <ul class="nav">
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        Menu<b class="caret"></b>
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <?php if (isset($_SESSION['admin_id'])) { ?>
                                <a href="<?= "./revenda.php" ?>"><?= MENU_REVENDAS ?></a>
                                <a href="<?= "./usuario.php" ?>"><?= MENU_USUARIOS ?></a>
                                <a href="<?= "./plano_assinatura.php" ?>"><?= MENU_PLANO_ASSINATURA ?></a>
                                <a href="<?= "./financeiro.php" ?>"><?= MENU_FINANCEIRO ?></a>
                            <?php } elseif (isset($_SESSION['revenda_id'])) { ?>
                                <a href="<?= "./usuario.php" ?>"><?= MENU_USUARIOS ?></a>
                                <a href="<?= "./plano_assinatura.php" ?>"><?= MENU_PLANO_ASSINATURA ?></a>
                                <a href="<?= "./financeiro.php" ?>"><?= MENU_FINANCEIRO ?></a>
                            <?php } ?>
                        </li>
                    </ul>   
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Relatórios <span class="caret"></span></a>
                    <ul class="dropdown-menu">

                        <?php if (isset($_SESSION['admin_id']) || isset($_SESSION['revenda_id'])) { ?>
<!--                            <li><a href="a_receber.php"><?= RELATORIO_A_RECEBER ?></a></li>
                            <li><a href="vencidos.php"><?= RELATORIO_A_VENCIDOS ?></a></li>-->
                        <?php } ?>
                            <li><a href="usuario_atrazo.php?page=<?= $dataGet['page'] ?>"><?= CLIENTES_COM_ATRASO ?></a></li>
                     </ul>
<!--                    <li><a href="index.php?action=a_receber&page=<?= $dataGet['page'] ?>" target="_blank"><?= RELATORIO_A_RECEBER ?></a></li>
                        <li><a href="index.php?action=vencidos&page=<?= $dataGet['page'] ?>" target="_blank"><?= RELATORIO_A_VENCIDOS ?></a></li>-->
                    </ul>

                </li>
            </ul>
            <?php ?>
            <ul class="nav pull-right">
                <li class="light-blue">
                    <a data-toggle="dropdown" href="#" class="dropdown-toggle">
                        <span class="label label-info" >
                            <?= $_SESSION['admin_login'];
                            ?>            
                        </span>

                        <i class="icon-caret-down"></i>
                    </a>
                    <ul class="user-menu pull-right dropdown-menu dropdown-yellow dropdown-caret dropdown-closer">
                        <!--                            <li class="divider"></li>-->
                        <!--href="logout.php"-->
                        <li>
                            <a href="#myModal" role="button" class="btn" data-toggle="modal">Logout</a>
                        </li>
                    </ul>
                </li>
            </ul><!--/.ace-nav-->


        </div><!--/.container-fluid-->
    </div><!--/.navbar-inner-->
</div>
