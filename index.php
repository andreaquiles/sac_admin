<?php
ob_start(); 
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
    header('Location:login.php');
}
/**
 * autenticações 
 */
$filterGET = array(
    'action' => array(
        'filter' => FILTER_SANITIZE_STRING
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
        'filter' => FILTER_SANITIZE_STRING
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
    if ($dataGet['action'] == 'a_receber') {
        require_once ( '../lib/fpdf/fpdf.php');
        require_once('../sealed/BO/financeiroBO.php');
        $relatorio = "a_receber";
        $dadosExportarPDF = financeiroBO::getFinanceiro($relatorio, LIMIT_REGISTROS_RELATORIOS);
        require_once('../sealed/controler/pdf.php');
        exit();
    } elseif ($dataGet['action'] == 'vencidos') {
        require_once ( '../lib/fpdf/fpdf.php');
        require_once('../sealed/BO/financeiroBO.php');
        $relatorio = "vencidos";
        $dadosExportarPDF = financeiroBO::getFinanceiro($relatorio, LIMIT_REGISTROS_RELATORIOS);
        require_once('../sealed/controler/pdf.php');
        exit();
    } elseif ($dataGet['action'] == 'usuarios') {
        require_once ( '../lib/fpdf/fpdf.php');
        $relatorio = "usuarios";
        if (empty($dataGet['busca'])) {
            $dadosExportarPDF = usuarioBO::getListaUsuarios(LIMIT_REGISTROS_RELATORIOS);
        } else {
            $dadosExportarPDF = usuarioBO::getListaUsuariosPesquisa($dataGet, LIMIT_REGISTROS_RELATORIOS);
        }
        require_once('../sealed/controler/pdf.php');
        exit();
    } elseif ($dataGet['action'] == 'usuarios_atraso') {
        require_once ( '../lib/fpdf/fpdf.php');
        require_once('../sealed/BO/financeiroBO.php');
        $relatorio = "usuarios_atraso";
        $dadosExportarPDF = financeiroBO::getFinanceiro("vencidos", LIMIT_REGISTROS_RELATORIOS);
        require_once('../sealed/controler/pdf.php');
        exit();
    } elseif ($dataGet['action'] == 'usuarios_bloqueados') {
        require_once ( '../lib/fpdf/fpdf.php');
        //require_once('../sealed/BO/financeiroBO.php');
        $relatorio = "usuarios_bloqueados";
        $dadosExportarPDF = usuarioBO::getListaUsuariosBloqueados(LIMIT_REGISTROS_RELATORIOS);
        require_once('../sealed/controler/pdf.php');
        exit();
    } elseif ($dataGet['action'] == 'usuarios_expirado') {
        require_once ( '../lib/fpdf/fpdf.php');
        //require_once('../sealed/BO/financeiroBO.php');
        $relatorio = "usuarios_expirados";
        $dadosExportarPDF = usuarioBO::getUsuariosExpirar(LIMIT_REGISTROS_RELATORIOS);
        require_once('../sealed/controler/pdf.php');
        exit();
    } elseif ($dataGet['action'] == 'usuarios_novos') {
        require_once ( '../lib/fpdf/fpdf.php');
        //require_once('../sealed/BO/financeiroBO.php');
        $relatorio = "usuarios_novos";
        $dadosExportarPDF = usuarioDAO::getUsuariosNovos(LIMIT_REGISTROS_RELATORIOS);
        require_once('../sealed/controler/pdf.php');
        exit();
    }
} catch (Exception $ex) {
    $response['error'][] = $ex->getMessage();
    exit();
}

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

            </div>

            <ol class="breadcrumb">
                <li class="active">Admin</li>
            </ol>

            <div class="jumbotron">
                <h2 style="text-align: center"><span class="label label-default">Bem vindo ao Admin SacWeb!!!</span></h2>
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