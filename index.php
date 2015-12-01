<?php
error_reporting(E_ALL ^ E_NOTICE);
include_once '../sealed/init.php';
require_once('../sealed/BO/usuarioBO.php');
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
    ),
    'action' => array(
        'filter' => FILTER_SANITIZE_STRING
    )
);

$filter = array(
    'page' => array(
        'filter' => FILTER_VALIDATE_INT
    )
);
$dataPost = filter_input_array(INPUT_POST, $filter);
$dataGet = filter_input_array(INPUT_GET, $filterGET);

try {
    if ($dataGet['action'] == 'a_receber') {
        require_once ( '../lib/fpdf/fpdf.php');
        require_once('../sealed/BO/financeiroBO.php');
        $relatorio = "a_receber";
        $dadosExportarPDF = financeiroBO::getFinanceiro($relatorio, 5000);
        require_once('../sealed/controler/pdf.php');
        exit();
    } elseif ($dataGet['action'] == 'vencidos') {
        require_once ( '../lib/fpdf/fpdf.php');
        require_once('../sealed/BO/financeiroBO.php');
        $relatorio = "vencidos";
        $dadosExportarPDF = financeiroBO::getFinanceiro($relatorio,5000);
        require_once('../sealed/controler/pdf.php');
        exit();
    }elseif ($dataGet['action'] == 'usuarios') {
        require_once ( '../lib/fpdf/fpdf.php');
        $relatorio = "usuarios";
        $dadosExportarPDF = usuarioBO::getListaUsuarios(5000);
        require_once('../sealed/controler/pdf.php');
        exit();
    }
} catch (Exception $ex) {
    $response['error'][] = $ex->getMessage();
    exit();
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
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
        <script src="../public/assets/js/bootstrap.min.js"></script>
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
        <?php include './includes/header.php'; ?>

        <div class="main-container container-fluid">
            <div class="main-content">
                <div class="breadcrumbs" id="breadcrumbs">
                    <ul class="breadcrumb">
                        <li class="active">admin</li>
                    </ul><!--.breadcrumb-->
                </div>

                <div class="page-content">
                    <div class="row-fluid">
                        <div class="well" style="text-align: center">
                            <span class="label">
                                <h3>Bem vindo ao Admin SacWeb
                                    <?php ?> !!!
                                </h3>
                            </span>
                        </div>
                        <!--PAGE CONTENT ENDS-->
                    </div><!--/.span-->
                </div><!--/.row-fluid-->
            </div><!--/.page-content-->
        </div><!--/.main-content-->
    </div><!--/.main-container-->


    <script>


        $(document).load(function () {


        });
    </script>

</script>



<script src="../js/gerenciador.js"></script>

</body>
</html>
