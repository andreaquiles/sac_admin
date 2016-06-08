<?php
include_once '../sealed/init.php';
require_once('../sealed/BO/usuarioBO.php');
require_once('../sealed/BO/usuario_expiracaoBO.php');
require_once '../sealed/controler/paginador.php';
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

$inputPOST = filter_input_array(INPUT_POST, $argsPost);
$dataGet = filter_input_array(INPUT_GET, $filterGET);
$dataPostLimite = filter_input_array(INPUT_POST, $filterLimite);


try {
    if ($dataPostLimite) {
        $response = array();
        if ($dataPostLimite['data'] == NULL) {
            $response['error'][] = 'Data Inválida!';
        }
        if (empty($response['error'])) {
            try {
                $dataPostLimite['data'] = FUNCOES::formatarDatatoMYSQL($dataPostLimite['data']) . ' ' . date('H:i:s');
                $rowGetExpiracao = usuario_expiracaoBO::getExpiracaoEspecifica($dataPostLimite['user_id']);
                if ($rowGetExpiracao->users_expiracao_id) {
                    usuario_expiracaoBO::salvarExpiracao($dataPostLimite, 'users_expiracao', $dataPostLimite['user_id']);
                } else {
                    usuario_expiracaoBO::salvarExpiracao($dataPostLimite, 'users_expiracao');
                }
                $response['success'][] = 'Registro alterado com sucesso!';
            } catch (Exception $ex) {
                $response['error'][] = $ex->getMessage();
            }
        }
    } else {
        $count = usuarioDAO::getUsersNovosCount();
        if (!$dataGet['page']) {
            $dataGet['page'] = 1;
        }
        $paginador = new paginador($dataGet['page'], $count, 20, '', array('pesquisa' => $inputGET['pesquisa']));
        $dados_expirar = usuarioDAO::getUsuariosNovos($paginador->getPage());
    }
    /**
     * action via post EXCLUIR
     */
    if (isset($dataGet['action'])) {
        if ($dataGet['action'] == 'excluir') {
            if (isset($dataGet['id'])) {
                try {
                    $result = usuarioBO::deletar($dataGet['id']);
                    if ($result == true) {
                        $response['success'][] = 'Usuário excluído com sucesso!';
                        $response['link'] = 'usuario.php?page=' . $dataGet['page'];
                    } else {
                        //$response['error'][] = "Revenda já está vinculado a uma cotação !!";
                    }
                } catch (Exception $err) {
                    $response['error'][] = $err->getMessage();
                }
            }
        }
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
        <script src="../js/bootstrap-datepicker.js"></script>
        <script src="../js/locales/bootstrap-datepicker.pt-BR.js"></script>
        <link href="../css/datepicker3.css" rel="stylesheet" type="text/css"/>
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
        <?php include './modals.php'; ?>
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
                <li class="active">Usuários expirados</li>
            </ol>
            <ol class="breadcrumb" >
                <a  href="usuarios_editar.php" role="button" class="btn btn-primary"> <span class="glyphicon glyphicon-plus-sign"></span>
                    <b>Novo Usuário</b>
                </a>
                <a class="btn btn-danger" data-toggle="tooltip" title="PDF" 
                   href="index.php?action=usuarios_novos&page=<?= $dataGet['page'] ?>" target="_blank">
                    <span class="glyphicon glyphicon-download" aria-hidden="true"></span> Download
                </a>
                <div class="form-group col-sm-2 pull-right">
                    <select class="form-control" name="planos_assinatura_id">
                        <option value="usuario">Todos</option>
                        <option value="usuarios_novos" selected="">Usuários novos</option>
                        <option value="usuario_atraso" >Usuários com atraso</option>
                        <option value="usuario_bloqueados">Usuários bloqueados</option>
                        <option value="usuarios_expirar">Usuários expirados</option>
                    </select>
                </div>
            </ol>
            <div class="well" style="background-color: #FFF">
                <table class="table table-hover table-striped" >
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Usúario</th>
                            <th>Whatsapp</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $cont = 1;
                        $dtAtual = date("Y-m-d");
                        if ($dados_expirar) {
                            foreach ($dados_expirar as $dado) {
                                ?>
                                <tr  <?php echo ($dado->DiffDate < 0 ) ? 'class="danger"' : '' ?> >
                                    <td class="" style="width:10px;"> 
                                        <input name="page" type="hidden"  value="<?= $dataGet['page']; ?>">
                                        <?= $cont; ?>
                                    </td>
                                    <td style="width:150px;"><?=
                                        $dado->login;
                                        echo ' <span class="label label-success">' . ($dado->DiffDate) . ' dias cadastrados </span>';
                                        ?>
                                    </td>
                                    <td style="width:100px;"><span class="label label-default"><?= ($dado->phone); ?></span></td>

                                    <td style="width:100px;" class="text-right">
                                        <a class="btn btn-default btn-xs" data-toggle="tooltip" title="Editar" 
                                           href="usuarios_editar.php?id=<?= $dado->id; ?>&page=<?= $dataGet['page']; ?>&pgname=usuario_atraso">
                                            <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
                                        </a>
                                        <a class="btn btn-default btn-xs" data-toggle="tooltip" title="Atividades" 
                                           href="atividades.php?user_id=<?= $dado->id; ?>&page=<?= $dataGet['page']; ?>&login=<?= urlencode($dado->login); ?>">
                                            <span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>
                                        </a>
<!--                                        <a class="btn btn-default btn-xs " data-toggle="tooltip" title="Configurações" 
                                           href="config.php?user_id=<?//= $dado->id; ?>&page=<?//= $dataGet['page']; ?>">
                                            <span class="glyphicon glyphicon-wrench" aria-hidden="true"></span>
                                        </a>-->
                                        <button class="btn btn-primary btn-xs"
                                                data-rel="tooltip"
                                                onclick="EditarExp('<?= $dado->id; ?>')"
                                                data-placement="bottom"
                                                title="Adicionar novo contato"
                                                href="#myModal" data-toggle="modal"
                                                >
                                            <span class="glyphicon glyphicon-wrench" aria-hidden="true"></span>
                                        </button>

                                        <a class="btn btn-danger btn-xs AjaxConfirm" data-toggle="tooltip" title="Excluir" 
                                           href="usuario.php?action=excluir&id=<?= $dado->id; ?>&page=<?= $dataGet['page']; ?>">
                                            <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                                        </a>
                                    </td>
                                </tr>
                                <?php
                                $cont++;
                                // }
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
        <script src="assets/js/usuario_novo.min.js"></script>
        <script>
        $('select[name=planos_assinatura_id]').change(function () {
            location.href = this.value + '.php';
        });
        </script>
    </body>
</html>
