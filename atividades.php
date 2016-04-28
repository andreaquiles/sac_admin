<?php
error_reporting(E_ALL ^ E_NOTICE);
include_once '../sealed/init.php';
require_once('../sealed/BO/usuarioBO.php');
require_once('../sealed/BO/mensagensBO.php');
require_once('../sealed/controler/paginador.php');
include_once "../lib/utils/funcoes.php";

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

$filterGET = array(
    'user_id' => array(
        'filter' => FILTER_VALIDATE_INT
    ),
    'page' => array(
        'filter' => FILTER_VALIDATE_INT
    )
);

$argsPost = array(
    'action' => array(
        'filter' => FILTER_VALIDATE_REGEXP,
        'options' => array("regexp" => "/^(excluir|imprimir)$/")
    ),
    'ids' => array(
        'filter' => FILTER_VALIDATE_INT,
        'flags' => FILTER_REQUIRE_ARRAY,
    ),
    'page' => array(
        'filter' => FILTER_VALIDATE_INT,
    )
);

$dataGet = filter_input_array(INPUT_GET, $filterGET);
$inputPOST = filter_input_array(INPUT_POST, $argsPost);


if (isset($inputPOST['action'])) {
    if ($inputPOST['action'] == 'excluir') {
        if (isset($inputPOST['ids'])) {
            try {
                mensagensBO::deletarMessages($inputPOST['ids']);
                $response['success'][] = 'Registros excluídos com sucesso!';
            } catch (Exception $err) {
                $response['error'][] = $err->getMessage();
            }
        }
    }
}


try {
    $count = mensagensBO::getMensagensUSERCount($dataGet['user_id']);
    if (!$dataGet['page']) {
        $dataGet['page'] = 1;
    }
    $paginador = new paginador($dataGet['page'], $count, 20, '', array('user_id' => $dataGet['user_id']));
    $dados = mensagensBO::getMensagensUSER($dataGet['user_id'], $paginador->getPage());
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
        <link href="../public/assets/css" rel="stylesheet" />
        <link href="../public/assets/css/bootstrap.min.css" rel="stylesheet" />
        <link href="../public/assets/css/bootstrap-responsive.min.css" rel="stylesheet" />
        <script src="../js/jquery.min.js"></script>
        <script src="../public/assets/js/bootstrap.min.js"></script>

        <script>
            $(window).on('beforeunload', function () {// PRESTES A SER DESCARREGADO....
                $('#loading').modal('show');
            });

            $(window).load(function () {  // DEPOIS DE CARRREGAR
                $('#loading').modal('hide');
            });

            var excluir = function (auto_resposta_id) {
                bootbox.confirm("<br>Deseja relamente excluir o registro ?<br><br>", function (result) {
                    if (result)
                        location.href = '?auto_resposta_id=' + auto_resposta_id;
                });
            }
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
                    <div class="modal-body">
                        Deseja realmente sair?
                        <form method="post" class="noAjax" action='logout.php' name="login_form">
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary">Sair</button>
                        <button class="btn" data-dismiss="modal" aria-hidden="true">Cancelar</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>

        <?php include './includes/header.php'; ?>

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
                        <li><a href="index.php">Home</a><span class="divider">/</span></li></li>
                        <li><a href="usuario.php">Usuários</a><span class="divider">/</span></li></li>
                    <li class="active"><?= $_GET['login'];?></li>
                   
                    </ol>
                    <ol class="breadcrumb">
                        <button class="btn btn-danger btn excluir"><i class="icon-trash"></i> Excluir</button>
                    </ol>

                    <table class="table table-hover table-striped" >
                        <thead>
                            <tr>
                                <th></th>
                                <th>Horário</th>
                                <th>Atendente</th>
                                <th>Contato</th>
                                <th>Mensagem</th>

                            </tr>
                        </thead>
                        <tbody>

                            <?php
                            $cont = 1;
                            if ($dados) {
                                foreach ($dados as $dado) {
                                    ?>
                                    <tr>
                                        <td class="checkbox-inline" width='10px'> 
                                            <input name="selecao" value="<?php echo $dado->id; ?>" type="checkbox">
                                            <input name="page" type="hidden"  value="<?= $dataGet['page']; ?>">
                                        </td>
                                        <td class="left-align" width='150px'><span class="label"><?= $dado->data ?></span></td>
                                       <td class="left-align" width='230px'><?php
                                            if ($dado->enviado && $dado->login == 'master') {
                                                echo '<span class="label label-important">' . $dado->login . '</span>';
                                            } elseif ($dado->enviado && $dado->login != 'master') {
                                                echo '<span class="label label-info">' . $dado->login . '</span>';
                                            } elseif($dado->enviado){
                                                echo $dado->login;
                                            }
                                            ?>
                                        </td>
                                        <td class="left-align" width='230px'><?php
                                            if ($dado->enviado == NULL || empty($dado->enviado)) {
                                                echo '<span class="label label-inverse">'.$dado->login.'</span>';
                                            } 
                                            ?>
                                        </td>
                                        <td><?php
                                            if ($dado->type == "text" || $dado->type == NULL) {
                                                echo substr($dado->msg, 0, 100);
                                            } elseif ($dado->type == "image") {
                                                echo "<span class=\"label\">imagem</span>";
                                            } elseif ($dado->type == "audio") {
                                                echo "<span class=\"label\">audio</span>";
                                            } elseif ($dado->type == "video") {
                                                echo "<span class=\"label\">vídeo</span>";
                                            }
                                            ?></td>
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
