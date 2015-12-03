<?php
include_once '../sealed/init.php';
require_once('../sealed/BO/usuarioBO.php');
require_once '../sealed/controler/paginador.php';
require_once('../sealed/BO/financeiroBO.php');
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

$inputPOST = filter_input_array(INPUT_POST, $argsPost);
$dataGet = filter_input_array(INPUT_GET, $filterGET);


try {
    $count = financeiroBO::getFinanceiroCount("a_receber");
    if (!$dataGet['page']) {
        $dataGet['page'] = 1;
    }

    $paginador = new paginador($dataGet['page'], $count, 20, '', array('pesquisa' => $inputGET['pesquisa']));
    $dados_receber = financeiroBO::getFinanceiro("a_receber", $paginador->getPage());
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
                <li class="active">Relatórios</li>
                <li class="active">A Receber</li>
               
            </ol>
            <ol class="breadcrumb" >
                <a  href="financeiro_editar.php" role="button" class="btn btn-primary"> <span class="glyphicon glyphicon-plus-sign"></span>
                    <b>Novo Financeiro</b>
                </a>
                <a class="btn btn-danger" data-toggle="tooltip" title="PDF" 
                   href="index.php?action=a_receber&page=<?= $dataGet['page'] ?>" target="_blank">
                    <span class="glyphicon glyphicon-download" aria-hidden="true"></span> Download
                </a>
                 <div class="form-group col-sm-2 pull-right">
                    <select class="form-control" name="planos_assinatura_id">
                        <option value="financeiro">Todos</option>
                        <option value="a_receber" selected="">A Receber</option>
                        <option value="vencidos">Vencidos</option>
                    </select>
                </div>
            </ol>

            <div class="well" style="background-color: #FFF">
                <table class="table table-hover table-striped" >
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Usúario</th>
                             <th>Vencimento</th>
                            <th>Valor R$</th>
                           
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $cont = 1;
                        if ($dados_receber) {
                            foreach ($dados_receber as $dado) {
                                ?>
                                <tr <?php //echo $dado['data_encerramento'] ? 'class="danger"' : ''     ?> >
                                    <td class="" style="width:10px;"> 
                                        <input name="page" type="hidden"  value="<?= $dataGet['page']; ?>">
                                        <?= $cont; ?>
                                    </td>
                                    <td style="width:250px;"><?= $dado->login; ?></td>
                                    <td style="width:80px;"><span class="label label-default"><?= FUNCOES::formatarDatatoHTML($dado->data_vencimento); ?></span></td>
                                    <td style="width:80px;"><span class="label label-default"><?= FUNCOES::formatoDecimalHTML($dado->valor); ?></span></td>
                                    <td style="width:65px;" class="text-right">
                                        <a class="btn btn-default btn-xs" data-toggle="tooltip" title="Editar" 
                                           href="financeiro_editar.php?id=<?= $dado->financeiro_id; ?>&page=<?= $dataGet['page']; ?>&pgname=a_receber">
                                            <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
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
        <script>
            $('select').on('change', function () {
                location.href = this.value+'.php';
            });
        </script>
    </body>
</html>
