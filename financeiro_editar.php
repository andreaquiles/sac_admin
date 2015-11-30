<?php
include_once '../sealed/init.php';
require_once('../sealed/BO/usuarioBO.php');
require_once('../sealed/BO/financeiroBO.php');
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


$filter = array(
    'data_vencimento' => array(
        'filter' => FILTER_SANITIZE_STRING
    ),
    'valor' => array(
        'filter' => FILTER_SANITIZE_STRING
    ),
    'users_id' => array(
        'filter' => FILTER_VALIDATE_INT
    ),
    'financeiro_id' => array(
        'filter' => FILTER_VALIDATE_INT
    )
);


$filterGET = array(
    'action' => array(
        'filter' => FILTER_VALIDATE_REGEXP,
        'options' => array("regexp" => "/^(excluir|imprimir)$/")
    ),
    'page' => array(
        'filter' => FILTER_VALIDATE_INT
    ),
    'id' => array(
        'filter' => FILTER_VALIDATE_INT
    )
);
//
$data_org = filter_input_array(INPUT_POST);
$data = filter_input_array(INPUT_POST, $filter);
$dataGet = filter_input_array(INPUT_GET, $filterGET);


//
try {
    $usuarios = usuarioBO::getListaCombo();
    if ($data) {
        $response = array();
        $data['valor'] = FUNCOES::formatoDecimal($data['valor']);
        if (empty($data['valor']) || $data['valor']== 0.00 ) {
            $response['error'][] = 'Preencher valor';
        } elseif (empty($data['data_vencimento'])) {
            $response['error'][] = 'Preencher data de vencimento';
        } elseif (empty($data['users_id'])) {
            $response['error'][] = 'Preencher usuário';
        } else {
            /**
             * salvar plano
             */
            if (!$dataGet['page']) {
                $page = 1;
            } else {
                $page = $data['page'];
            }

            if (empty($response['error'])) {
                $id = ($data['financeiro_id']);
                unset($data['page']);
                unset($data['financeiro_id']);
                $data['data_vencimento'] = FUNCOES::formatarDatatoMYSQL($data['data_vencimento']);
                /**
                 * atualizar financeiro
                 */
                if ($id) {
                    financeiroBO::salvar($data, 'financeiro', $id);
                    $response['success'][] = 'Financeiro  atualizado com sucesso!!';
                }
                /**
                 * inserir financeiro
                 */ else {
                    financeiroBO::salvar($data, 'financeiro');
                    $response['success'][] = 'Financeiro inserido com sucesso!!';
                }
                $response['link'][] = "financeiro.php?page=$page";
            }
        }
    }
    /**
     * Editar plano
     */
    if ($dataGet['id']) {
        try {
            $data = financeiroBO::getFinanceiroEspecifico($dataGet['id']);
            $data['valor'] = FUNCOES::formatoDecimalHTML($data['valor']);
            $data['data_vencimento'] = FUNCOES::formatarDatatoHTML($data['data_vencimento']);
        } catch (Exception $err) {
            $response['error'][] = $err->getMessage();
        }
    }
} catch (Exception $ex) {
    $response['error'][] = $ex->getMessage();
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
        <link href="assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <script src="assets/js/jquery.min.js"></script>
        <script src="assets/bootstrap/js/bootstrap.min.js"></script>
        <script src="assets/js/jquery.forms/jquery.forms.js"></script>
        <script src="assets/js/jquery.maskMoney.min.js"></script>
        <script src="assets/js/jquery.numeric.min.js"></script>
        <script src="../js/bootstrap-datepicker.js"></script>
        <script src="../js/locales/bootstrap-datepicker.pt-BR.js"></script>
        <link href="../css/datepicker3.css" rel="stylesheet" type="text/css"/>
        <script src="assets/js/autocomplete.js"></script>
        <link href="assets/css/autocomplete.css" rel="stylesheet">
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
            <ol class="breadcrumb">
                <li><a href="index.php">Home</a></li>
                <li><a href="financeiro.php">Financeiro</a></li>
            </ol>
            <div class="well">
                <form role="form" method="post">
                    <div class="row">
                        <div class="col-sm-12">
                            <input type="hidden"  name="financeiro_id" value="<?php echo $dataGet['id']; ?>">
                            <input type="hidden" name="page" value="<?php echo $dataGet['page']; ?>">
                            <div class="row">
                                <div class="form-group col-sm-3">
                                    <label for="cnpj">Valor R$</label>
                                    <input type="text" class="form-control" name="valor" data-toggle="maskMoney"  value="<?php echo $data['valor']; ?>" >
                                </div>
                                <div class="form-group col-sm-3">
                                    <label for="data_vencimento">Vencimento</label>
                                    <input type="text" data-toggle="datepicker" class="form-control" name="data_vencimento" value="<?= $data['data_vencimento'] ?>" >
                                </div>
                                <div class="form-group col-sm-6">
                                    <label for="data_fundacao">Usuários</label>
                                    <input type="hidden"  name="users_id" value="<?php echo $data['users_id']; ?>"  >
                                    <input class="form-control" name="nome_procurado" value="<?php echo $data['nome']; ?>">
                                </div>
                            </div>
                        </div>
                        <div class="text-right">
                            <a href="<?php echo ''; ?>" class="btn btn-danger">Cancelar</a>
                            <button type="submit" class="btn btn-success">Salvar</button>
                        </div>
                    </div>
                </form>

            </div>


        </div>

        <div id="footer" class="navbar-default">
            <div class="container">
            </div>
        </div>

        <script src="assets/js/gerenciador.min.js"></script>
        <script src="assets/js/plano_assinatura_editar.min.js"></script>
    </body>
</html>
