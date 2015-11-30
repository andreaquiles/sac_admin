<?php
include_once '../sealed/init.php';
require_once('../sealed/BO/usuarioBO.php');
require_once('../sealed/BO/revendedorBO.php');
require_once '../sealed/controler/paginador.php';
$user = new User();
usuarioBO::checkExpireLogin();
usuarioBO::checkSession();
include_once "../lib/utils/funcoes.php";

$filterGET = array(
    'action' => array(
        'filter' => FILTER_VALIDATE_REGEXP,
        'options' => array("regexp" => "/^(excluir)$/")
    ),
    'id' => array(
        'filter' => FILTER_VALIDATE_INT
    ),
    'page' => array(
        'filter' => FILTER_VALIDATE_INT
    ),
    'revenda_id' => array(
        'filter' => FILTER_VALIDATE_INT
    )
);

$argsPost = array(
    'action' => array(
        'filter' => FILTER_VALIDATE_REGEXP,
        'options' => array("regexp" => "/^(excluir)$/")
    ),
    'ids' => array(
        'filter' => FILTER_VALIDATE_INT,
        'flags' => FILTER_REQUIRE_ARRAY,
    ),
    'page' => array(
        'filter' => FILTER_VALIDATE_INT,
    )
);
$inputPOST = filter_input_array(INPUT_POST, $argsPost);
$dataGet = filter_input_array(INPUT_GET, $filterGET);

try {
    $count = revendedorBO::getRevendasCount();
    if (!$dataGet['page']) {
        $dataGet['page'] = 1;
    }
    $paginador = new paginador($dataGet['page'], $count, 20, '');
    $dados = revendedorBO::getRevendas($paginador->getPage());
    /**
     * action via post EXCLUIR
     */
    if (isset($dataGet['action'])) {
        if ($dataGet['action'] == 'excluir') {
            if (isset($dataGet['revenda_id'])) {
                try {
                    $result = revendedorBO::deletar($dataGet['revenda_id']);
                    if ($result == TRUE) {
                        $response['success'][] = 'Revenda excluída com sucesso!';
                        $response['link'] = 'revenda.php?page=' . $dataGet['page'];
                    } else {
                        $response['error'][] = "Revenda já está vinculado a um usuário !!";
                    }
                } catch (Exception $err) {
                    $response['error'][] = $err->getMessage();
                }
            }
        }
    }

    if (!$dataGet['page']) {
        $dataGet['page'] = 1;
    }
} catch (Exception $e) {
    $response['error'][] = $e->getMessage();
}
if (FUNCOES::isAjax()) {
    print json_encode($response);
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title><?= TITLE; ?></title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">
        <!-- Bootstrap -->
        <!-- Latest compiled and minified CSS -->
        <link href="assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <script src="assets/js/jquery.min.js"></script>
        <script src="assets/bootstrap/js/bootstrap.min.js"></script>
        <script src="assets/bootstrap/js/bootbox.min.js"></script>

        <style>

            #footer {
                background-color: #F5F5F5;
                bottom: 0;
                height: 50px;
                position: relative;
                width: 100%;
            }
        </style>
    </head>
    <body>
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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

        <?php include 'includes/header_admin.php'; ?>


        <div class="container-fluid">
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


            <ol class="breadcrumb">
                <li><a href="./">Home</a></li>
                <li class="active">Revenda</li>
            </ol>
            <div  style="padding: 5px;">
                <a  href="revenda_editar.php" role="button" class="btn btn-primary"> <span class="glyphicon glyphicon-plus-sign"></span>
                    <b>Nova Revenda</b>
                </a>
            </div>
            <div class="well" style="background-color: #FFF">
                <table class="table table-hover table-striped" >
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Descrição</th>
                            <th>Email</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $cont = 1;
                        if ($dados) {

                            foreach ($dados as $dado) {
                                if ($dado->nome) {
                                    $descricao = $dado->nome;
                                } else {
                                    $descricao = $dado->razao;
                                }
                                ?>
                                <tr <?php //echo $dado['data_encerramento'] ? 'class="danger"' : ''   ?> >
                                    <td class="" style="width:10px;"> 
                                        <input name="page" type="hidden"  value="<?= $dataGet['page']; ?>">
                                        <?= $cont; ?>
                                    </td>
                                    <td style="width:150px;"><?= $descricao; ?></td>
                                    <td style="width:100px;"><span class="label label-default"><?= $dado->email; ?></span></td>
                                    <td style="width:65px;" class="text-right">
                                        <a class="btn btn-default btn-xs" data-toggle="tooltip" title="Editar" 
                                           href="revenda_editar.php?revenda_id=<?= $dado->revenda_id; ?>&page=<?= $dataGet['page']; ?>">
                                            <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
                                        </a>
                                        <a class="btn btn-danger btn-xs AjaxConfirm" data-toggle="tooltip" title="Excluir" 
                                           href="revenda.php?action=excluir&revenda_id=<?= $dado->revenda_id; ?>&page=<?= $dataGet['page']; ?>">
                                            <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                                        </a>
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
        </div>



        <div id="footer" class="navbar-default">
            <div class="container">
            </div>
        </div>
        <script src="assets/js/gerenciador.min.js"></script>
    </body>
</html>
