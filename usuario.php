<?php
include_once '../sealed/init.php';
require_once('../sealed/BO/usuarioBO.php');
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
    'busca' => array(
        'filter' => FILTER_SANITIZE_STRING
    ),
    'login' => array(
        'filter' => FILTER_DEFAULT,
    ),
    'phone' => array(
        'filter' => FILTER_SANITIZE_STRING
    ),
    'data' => array(
        'filter' => FILTER_SANITIZE_STRING
    ),
    'pgnome' => array(
        'filter' => FILTER_SANITIZE_STRING
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

if (!$dataGet['page']) {
    $dataGet['page'] = 1;
}

try {

    if ($inputPOST['action'] == 'excluir') {
        if (isset($inputPOST['ids'])) {
            $params = is_array($dataGet) ? "?&" . http_build_query($dataGet) : '';
            try {
                usuarioBO::deletarUsers($inputPOST['ids']);
                $response['success'][] = 'Registros excluídos com sucesso!';
                $response['link'] = $_SERVER['PHP_SELF'] . $params;
            } catch (Exception $err) {
                $response['error'][] = $err->getMessage();
            }
        }
    }
    if (empty($dataGet['busca'])) {
        $input = array('busca' => $dataGet['busca'], 'login' => $dataGet['login'], 'phone' => $dataGet['phone']);
        $count = usuarioBO::getListaCount();
        $paginador = new paginador($dataGet['page'], $count, 20, '', $input);
        $dadosusuarios = usuarioBO::getListaUsuarios($paginador->getPage());
    } else {
        $input = array('busca' => $dataGet['busca'], 'login' => $dataGet['login'], 'phone' => $dataGet['phone']);
        $count = usuarioBO::getListaCountPesquisa($input);
        $paginador = new paginador($dataGet['page'], $count, 20, '', $input);
        $dadosusuarios = usuarioBO::getListaUsuariosPesquisa($input, $paginador->getPage());
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
        <script src="assets/js/autocomplete.js"></script>
        <link href="assets/css/autocomplete.css" rel="stylesheet">
<!--        <script src="../js/bootstrap-datepicker.js"></script>
        <script src="../js/locales/bootstrap-datepicker.pt-BR.js"></script>
        <link href="../css/datepicker3.css" rel="stylesheet" type="text/css"/>-->

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
                <li class="active">Usuários</li>
            </ol>


            <ol class="breadcrumb" >
                <a  href="usuarios_editar.php" role="button" class="btn btn-primary"> <span class="glyphicon glyphicon-plus-sign"></span>
                    <b>Novo Usuário</b>
                </a>
                <a class="btn btn-default excluir" >
                    <span class="glyphicon glyphicon-trash excluir" aria-hidden="true"></span> Excluir
                </a>
                <a class="btn btn-danger" data-toggle="tooltip" title="PDF" 
                   href="index.php?action=usuarios&<?= http_build_query($dataGet) ?>" target="_blank">
                    <span class="glyphicon glyphicon-download" aria-hidden="true"></span> Download
                </a>
                <div class="form-group pull-right">
                    <form class="form-inline pull-right noAjax" method="get">
                        <div class="form-group">
                            <select class="form-control" name="busca">
                                <option value="login"  <?php if ($dataGet['busca'] == "login") echo "selected"; ?> >Login</option>
                                <option value="phone" <?php if ($dataGet['busca'] == "phone") echo "selected"; ?> >Whatsapp</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" name="login"  placeholder="" value="<?= $dataGet['login'] ?>">
                            <input type="text" class="form-control" name="phone"  placeholder="" value="<?= $dataGet['phone'] ?>">
                        </div>
                        <button type="submit" class="btn btn-success">
                            <span class="glyphicon glyphicon-search" aria-hidden="true"></span>  Pesquisar
                        </button>
                    </form>
                </div>
                <div class="form-group col-sm-2 pull-right" style="">
                    <select class="form-control" name="planos_assinatura_id">
                        <option value="usuario" selected="">Todos</option>
                        <option value="usuarios_novos">Usuários novos</option>
                        <option value="usuario_atraso">Usuários com atraso</option>
                        <option value="usuario_bloqueados">Usuários bloqueados</option>
                        <option value="usuarios_expirar">Usuários expirados</option>
                    </select>
                </div>
            </ol>
            <table class="table table-hover table-striped" >
                <thead>
                    <tr>
                        <th>#</th>
                        <th>ID</th>
                        <th>Usuário</th>
                        <th>Whatsapp</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $cont = 1;
                    if ($dadosusuarios) {
                        foreach ($dadosusuarios as $dado) {
                            ?>
                            <tr <?php echo $dado->bloqueado ? 'class=""' : '' ?> >
                                <td class="" width='7px'> 
                                    <input name="selecao" value="<?php echo $dado->id; ?>" type="checkbox">
                                    <input name="page" type="hidden"  value="<?= $dataGet['page']; ?>">
                                </td>
        <!--                                <td class="" style="width:10px;"> 
                                    <input name="page" type="hidden"  value="<?= $dataGet['page']; ?>">
                                    <?//= $cont; ?>
                                </td>-->
                                <td style="width:20px;"><span class="label label-default"><?= $dado->id; ?></span></td>
                                <td style="width:250px;"><?= $dado->login; ?></td>
                                <td style="width:100px;">
                                    <?php if (!$dado->bloqueado) { ?>
                                        <span class="label label-default"><?= $dado->phone; ?></span>
                                    <?php } else { ?>
                                        <span class="label label-danger" style="text-decoration: line-through;"><?= $dado->phone; ?></span>
                                    <?php } ?>
                                </td>
                                <td style="width:100px;" class="text-right">
                                    <a class="btn btn-default btn-xs" data-toggle="tooltip" title="Editar" 
                                       href="usuarios_editar.php?id=<?= $dado->id; ?>&page=<?= $dataGet['page']; ?>&pgname=usuario">
                                        <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
                                    </a>
                                    <a class="btn btn-default btn-xs" data-toggle="tooltip" title="Atividades" 
                                       href="atividades.php?user_id=<?= $dado->id; ?>&page=<?= $dataGet['page']; ?>&login=<?= urlencode($dado->login); ?>">
                                        <span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>
                                    </a>
                                    <a class="btn btn-default btn-xs " data-toggle="tooltip" title="Configurações" 
                                       href="config.php?user_id=<?= $dado->id; ?>&page=<?= $dataGet['page']; ?>">
                                        <span class="glyphicon glyphicon-wrench" aria-hidden="true"></span>
                                    </a>
                                    <!--<a class="btn btn-danger btn-xs AjaxConfirm" data-toggle="tooltip" title="Excluir" 
                                     href="usuario.php?action=excluir&id=<?= $dado->id; ?>&page=<?= $dataGet['page']; ?>">
                                      <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                                  </a>-->
                                </td>
                            </tr>
                            <?php
                            $cont++;
                        }
                    }
                    ?>
                </tbody>
            </table>
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
        <script>
            $('select[name=planos_assinatura_id]').change(function () {
                location.href = this.value + '.php';
            });
            $('input[name=phone]').hide();
            $('select[name=busca]').change(function () {
                if (this.value === 'phone') {
                    $('input[name=phone]').show();
                    $('input[name=login]').hide();
                    $('input[name=login]').val("");
                } else if (this.value === 'login') {
                    
                    $('input[name=login]').show();
                    $('input[name=phone]').hide();
                    $('input[name=phone]').val("");
                } else {
                    $('input[name=login]').hide();
                    $('input[name=phone]').hide();
                    $('input[name=phone]').val("");
                    $('input[name=login]').val("");
                }
            });
        </script>
        <?php if ($dataGet['phone']) { ?>
            <script>
                $('input[name=phone]').show();
                $('input[name=login]').hide();
            </script>
        <?php } ?>
            <script src="assets/js/usuario.min.js"></script>
    </body>
</html>
