<?php
error_reporting(E_ALL ^ E_NOTICE);
include_once '../sealed/init.php';
require_once '../lib/utils/funcoes.php';
require_once('../sealed/BO/config_BO.php');
require_once('../sealed/BO/usuario_expiracaoBO.php');
require_once('../sealed/BO/usuarioBO.php');
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

$filter = array(
    'revenda_clientes' => array(
        'filter' => FILTER_VALIDATE_INT
    ),
    'uso_auto_respostas' => array(
        'filter' => FILTER_VALIDATE_INT
    ),
    'uso_respostas_lote' => array(
        'filter' => FILTER_VALIDATE_INT
    ),
    'user_id' => array(
        'filter' => FILTER_VALIDATE_INT
    ),
    'page' => array(
        'filter' => FILTER_VALIDATE_INT
    )
);

$filterLimite = array(
    'user_id' => array(
        'filter' => FILTER_VALIDATE_REGEXP,
        'options' => array("regexp" => "/^[\w\W]{1,150}$/")
    ),
    'limite_auto_resposta' => array(
        'filter' => FILTER_VALIDATE_REGEXP,
        'options' => array("regexp" => "/^[0-9]{1,20}$/")
    ),
    'limite_atendentes' => array(
        'filter' => FILTER_VALIDATE_REGEXP,
        'options' => array("regexp" => "/^[0-9]{1,20}$/")
    ),
    'dias_auto_resposta' => array(
        'filter' => FILTER_VALIDATE_REGEXP,
        'options' => array("regexp" => "/^[0-9]{1,20}$/")
    ),
    'dias_login' => array(
        'filter' => FILTER_VALIDATE_REGEXP,
        'options' => array("regexp" => "/^[0-9]{1,20}$/")
    ),
    'data' => array(
        'filter' => FILTER_VALIDATE_REGEXP,
        'options' => array("regexp" => "/^[0-9]{2}\/[0-9]{2}\/[0-9]{4}$/")
    )
);

$response = array();
$dataPost = filter_input_array(INPUT_POST, $filter);
$dataPostLimite = filter_input_array(INPUT_POST, $filterLimite);
$dataGet = filter_input_array(INPUT_GET, $filterGET);

if ($dataPost) {
    try {
        if ($dataPostLimite) {
            $response = array();
            if ($dataPostLimite['data'] == NULL) {
                $response['error'][] = 'Data Inválida!';
            }
            if (empty($response['error'])) {
                try {
                    $dataPostLimite['data'] = FUNCOES::formatarDatatoMYSQL($dataPostLimite['data']) . ' ' . date('H:i:s');
                    usuario_expiracaoBO::salvarExpiracao($dataPostLimite, 'users_expiracao', $dataPostLimite['user_id']);
                    $response['success'][] = 'Registro alterado com sucesso!';
                } catch (Exception $ex) {
                    $response['error'][] = $ex->getMessage();
                }
            }
        }
        $page = $dataPost['page'];
        unset($dataPost['page']);
        $row = config_BO::getEspecifica($dataPost['user_id']);
        if (!$row) {
            config_BO::salvar($dataPost, 'config');
        } else {
            config_BO::salvar($dataPost, 'config', $dataPost['user_id']);
        }
        $response['success'][] = 'Configuração efetuada com sucesso!!!';
        $response['link'] = "usuarios.php?page=" . $page;
    } catch (Exception $ex) {
        $response['error'][] = $ex->getMessage();
    }
}
if ($dataGet) {
    try {
        $dado = config_BO::getEspecifica($dataGet['user_id']);
    } catch (Exception $ex) {
        $response['error'][] = $ex->getMessage();
    }
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
                    <form method="post" action='logout.php' name="login_form">
                        <div class="modal-body">
                            Deseja realmente sair?
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Sair</button>
                            <button class="btn" data-dismiss="modal" aria-hidden="true">Cancelar</button>
                        </div>
                    </form>
                </div>
                <!--            ANDRE AQUILES DAQUI-->
                <div class="modal hide" id="myModal_2">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">x</button>
                        <h3>Auto Respostas</h3>
                    </div>
                    <div class="modal-body">
                        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                            <div class="control-group">
                                <label class="control-label" for="form-field-1">Chave</label>

                                <div class="controls">
                                    <input type="text" name="chave" class="input-xlarge" placeholder="" required>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="form-field-1">Resposta</label>

                                <div class="controls">
                                    <textarea rows="3" class="input-xlarge" name="resposta" required></textarea>
                                </div>
                            </div>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-primary">Salvar</button>
                        <button class="btn" data-dismiss="modal" aria-hidden="true">Cancelar</button>
                    </div>
                    </form>
                </div>
                <!--        AQUI-->
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

            </div>
            <div class="main-content">

                <div class="breadcrumbs" id="breadcrumbs">
                    <ul class="breadcrumb">
                        <li><a href="<?= $_SERVER['PHP_SELF'] ?>">admin</a><span class="divider">/</span></li></li>
                        <li><a href="usuarios.php">usuários</a><span class="divider">/</span></li></li>
                        <li class="active">configurações</li>
                    </ul><!--.breadcrumb-->

                    <div class="page-content">

                        <div class="row-fluid">

                            <div class="well">
                                <h3><?= $dado->login; ?></h3>
                                <form class="" method='POST' action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data">
                                    <div class="well">
                                        <label class="control-label" for="inputPassword">Revenda de Clientes</label>
                                        <div class="controls">
                                            <label class="radio inline">
                                                <input type="radio" name="revenda_clientes" id="optionsRadios1" value="0" <?php echo $dado->revenda_clientes ? '' : 'checked'; ?>>
                                                Bloquear
                                            </label>
                                            <label class="radio inline">
                                                <input type="radio" name="revenda_clientes" id="optionsRadios2" value="1" <?php echo $dado->revenda_clientes ? 'checked' : ''; ?>>
                                                Desbloquear
                                            </label>
                                        </div>
                                    </div>
                                    <div class="well">
                                        <label class="control-label" for="inputPassword">Uso de auto respostas</label>
                                        <div class="controls">
                                            <label class="radio inline">
                                                <input type="radio" name="uso_auto_respostas" id="optionsRadios1" value="0" <?php echo $dado->uso_auto_respostas ? '' : 'checked'; ?>>
                                                Bloquear
                                            </label>
                                            <label class="radio inline">
                                                <input type="radio" name="uso_auto_respostas" id="optionsRadios2" value="1" <?php echo $dado->uso_auto_respostas ? 'checked' : ''; ?>>
                                                Desbloquear
                                            </label>
                                        </div>
                                    </div>
                                    <div class="well">
                                        <label class="control-label" for="inputPassword">Usar respostas em lote</label>
                                        <div class="controls">
                                            <label class="radio inline">
                                                <input type="radio" name="uso_respostas_lote" id="optionsRadios1" value="0" <?php echo $dado->uso_respostas_lote ? '' : 'checked'; ?>>
                                                Bloquear
                                            </label>
                                            <label class="radio inline">
                                                <input type="radio" name="uso_respostas_lote" id="optionsRadios2" value="1" <?php echo $dado->uso_respostas_lote ? 'checked' : ''; ?>>
                                                <input type=hidden name="user_id" value="<?= $dataGet['user_id']; ?>">
                                                Desbloquear
                                            </label>
                                            <input type=hidden name="page" value="<?= $dataGet['page']; ?>">
                                        </div>
                                    </div>
                                    <div class="form-inline">
                                        <div class="row-fluid">
                                            <fieldset>
                                                <legend>Expirações</legend>
                                                <div class="span2">
                                                    <label>Nº auto respostas</label>
                                                    <div class="controls">
                                                        <input class="input-block-level" type="text" class="input-block-level" name="limite_auto_resposta"  value="" >
                                                    </div>
                                                </div>
                                                <div class="span2">
                                                    <label>Nº atendentes</label>
                                                    <div class="controls">
                                                        <input class="input-block-level" type="text"  name="limite_atendentes"  value="<?= $dado->limite_atendentes; ?>" >
                                                    </div>
                                                </div>
                                                <div class="span2">
                                                    <label>Dias auto respostas</label>
                                                    <div class="controls">
                                                        <input class="input-block-level" type="text"  name="dias_auto_resposta"  value="<?= $dado->dias_auto_resposta; ?>" >
                                                    </div>
                                                </div>
                                                <div class="span2">
                                                    <label>Data</label>
                                                    <div class="controls">
                                                        <input type="text" data-toggle="datepicker" class="selector form-control input-block-level" name="data" placeholder="dd/mm/yyyy" value="<?= $dado->date; ?>" >
                                                    </div>
                                                </div>
                                                <div class="span2">
                                                    <label>Dias de login</label>
                                                    <div class="controls">
                                                        <input class="input-block-level" type="text"  name="dias_login"  value="<?= $dado->dias_login; ?>" >
                                                    </div>
                                                </div>
                                            </fieldset>
                                        </div>

                                    </div>


                                    <div class="text-right">
                                        <button type="submit" class="btn btn-success">Salvar</button>
                                    </div>
                                </form>


                                <!--PAGE CONTENT ENDS-->
                            </div><!--/.span-->
                        </div><!--/.row-fluid-->
                    </div><!--/.page-content-->




                    <div class="page-content">
                        <div class="row-fluid">

                            <!--PAGE CONTENT ENDS-->
                        </div><!--/.span-->
                    </div><!--/.row-fluid-->
                </div><!--/.page-content-->
            </div>


        </div><!--/.main-content-->
    </div><!--/.main-container-->
    <script src="../js/gerenciador.js"></script>

</body>
</html>
