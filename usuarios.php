<?php
error_reporting(E_ALL ^ E_NOTICE);
include_once '../sealed/init.php';
require_once('../sealed/BO/usuarioBO.php');
require_once('../sealed/controler/paginador.php');
include_once "../lib/utils/funcoes.php";

$filterGET = array(
    'action' => array(
        'filter' => FILTER_VALIDATE_REGEXP,
        'options' => array("regexp" => "/^(excluir|imprimir)$/")
    ),
    'id' => array(
        'filter' => FILTER_VALIDATE_INT
    ),
    'page' => array(
        'filter' => FILTER_VALIDATE_INT
    ),
    'id' => array(
        'filter' => FILTER_VALIDATE_INT
    )
);
$dataGet = filter_input_array(INPUT_GET, $filterGET);
/**
 * autenticações 
 */
if (isset($_SESSION['admin_id'])) {
    require_once('../sealed/BO/usuarioBO.php');
    usuarioBO::checkExpireLogin();
    usuarioBO::checkSession();
} elseif (isset($_SESSION['revenda_id'])) {
    require_once('../sealed/BO/revendedorBO.php');
    revendedorBO::checkExpireLogin();
    revendedorBO::checkSession();
} else {
    header("Location:login.php");
}
/**
 * autenticações 
 */

try {
    $count = usuarioBO::getListaCount();
    if (!$dataGet['page']) {
        $dataGet['page'] = 1;
    }
    $paginador = new paginador($dataGet['page'], $count, 20, '', array('pesquisa' => $inputGET['pesquisa']));
    $dadosusers = usuarioBO::getListaUsuarios($paginador->getPage());
} catch (Exception $err) {
    $response['error'][] = $err->getMessage();
}
if (FUNCOES::isAjax()) {
    print json_encode($response);
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title><?= TITLE; ?></title>
        <link rel="shortcut icon" href="./favicon.ico">
        <meta name="description" content="overview &amp; stats" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link href="../public/assets/js/bootstrap-modal.js" rel="stylesheet" />
        <link href="../public/assets/css/" rel="stylesheet" />
        <link href="../public/assets/css/bootstrap.min.css" rel="stylesheet" />
        <link href="../public/assets/css/bootstrap-responsive.min.css" rel="stylesheet" />
        <script src="../js/jquery.min.js"></script>
        <script src="../public/assets/js/bootstrap.min.js"></script>
        <script src="assets/bootstrap/js/bootbox.min.js"></script>

        <script>
            $(window).on('beforeunload', function () {// PRESTES A SER DESCARREGADO....
                $('#loading').modal('show');
            });

            $(window).load(function () {  // DEPOIS DE CARRREGAR
                $('#loading').modal('hide');
            });

        </script>
    </head>

    <body>

        <div class="modal fade" id="loading" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <!--<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>-->
                        <h4 class="modal-title">Aguarde</h4>
                    </div>
                    <div class="modal-body">
                        ...
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
        <!-- Modal -->
        <div class="container-fluid">
            <div class="row">
                <div class="modal hide" id="myModal">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">x</button>
                        <h3>Logout</h3>
                    </div>
                    <form method="post" class="noAjax" action='logout.php' name="login_form">
                    <div class="modal-body">
                        Deseja realmente sair?
                       
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary">Sair</button>
                        <button class="btn" data-dismiss="modal" aria-hidden="true">Cancelar</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>

        <?php include './includes/header.php';?>

        <div class="main-container container-fluid">

            <div id="alerta">
                <?php
                if (isset($response)) {
                    if (!empty($response['error'])) {
                        ?>
                        <div class="alert alert-danger fade in" role="alert">
                            <?php echo implode('<br>', $response['error']); ?>
                        </div>
                        <?php
                    }
                    if (!empty($response['success'])) {
                        ?>
                        <div class="alert alert-success fade in" role="alert">
                            <?php echo implode('<br>', $response['success']); ?>
                        </div>
                        <?php
                    }
                }
                ?>
            </div>

            <div id="paginador_info_clientes">
                <?php echo $paginador->getInfo(); ?>
            </div>
            
            <div class="main-content">
                <div class="breadcrumbs" id="breadcrumbs">
                    <ol class="breadcrumb">
                        <li><a href="./">admin</a><span class="divider">/</span></li>
                        <li class="active">usuários</li>
                    </ol>

                    <table class="table table-hover table-striped" >
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Login</th>
                                <th>Whatsapp</th>
                               
                            </tr>
                        </thead>
                        <tbody>

                            <?php
                            $cont = 1;
                            if ($dadosusers) {
                                foreach ($dadosusers as $dado) {
                                  
                                    ?>
                                    <tr>
                                        <td><?= $cont ?></td>
                                        <td><?= $dado->login ?></td>
                                        <td><?= ($dado->phone) ?></td>
                                        <td class="right-align" width='60px'>
                                            <a data-toggle="tooltip" data-placement="left" title="Atividades" href="atividades.php?user_id=<?= $dado->id ?>&page=<?= $dataGet['page']; ?>&login=<?= urlencode($dado->login); ?>" role="button" class="btn btn-default btn btn-mini align-right"><i class="icon-eye-open"></i></a>
                                            <a data-toggle="tooltip" data-placement="left" title="Configurações" class="btn btn-default btn btn-mini align-right" href="config.php?user_id=<?= $dado->id ?>&page=<?= $dataGet['page']; ?>" role="button"  data-toggle="modal"><i class="icon-check"></i></a>
<!--                                            <a data-toggle="tooltip" title="Editar"  role="button" class="btn btn-mini" href="usuario_editar.php?user_id=<?= $dado->id ?>&page=<?= $dataGet['page']; ?>" data-toggle="modal"><i class="icon-edit"></i></a>-->
<!--                                            <a data-toggle="tooltip" title="Excluir" onclick=""  role="button" class="btn btn-danger btn-mini AjaxConfirm"  data-toggle="modal"><i class="icon-trash"></i></a>-->
                                        </td>
                                    </tr>
                                    <?php
                                    $cont++;
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>

                <div class="text-center" id="paginador_clientes">
                    <?php
                    echo $paginador->getPagi();
                    ?>
                </div>

                <div class="page-content">
                    <div class="row-fluid">

                        <!--PAGE CONTENT ENDS-->
                    </div><!--/.span-->
                </div><!--/.row-fluid-->
            </div><!--/.page-content-->


        </div><!--/.main-content-->
    </div><!--/.main-container-->
    <script src="../js/gerenciador.js"></script>

</body>
</html>
