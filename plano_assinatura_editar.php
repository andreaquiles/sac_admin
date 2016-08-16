<?php
include_once '../sealed/init.php';
require_once('../sealed/BO/plano_assinaturaBO.php');
require_once('../sealed/BO/revendedorBO.php');
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
    'descricao' => array(
        'filter' => FILTER_SANITIZE_STRING
    ),
    'qtde_autorespostas' => array(
        'filter' => FILTER_VALIDATE_INT
    ),
    'qtde_atendentes' => array(
        'filter' => FILTER_VALIDATE_INT
    ),
    'qtde_contatos' => array(
        'filter' => FILTER_VALIDATE_INT
    ),
    'qtde_msg_mes' => array(
        'filter' => FILTER_VALIDATE_INT
    ),
    'valor' => array(
        'filter' => FILTER_SANITIZE_STRING
    ),
    'percentual_admin' => array(
        'filter' => FILTER_VALIDATE_FLOAT
    ),
    'id' => array(
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
    $revendas = revendedorBO::getRevendas(1000);
    if ($data) {
        $response = array();
        if (empty($data['descricao'])) {
            $response['error'][] = 'Preencher descrição';
        } else {
            /**
             * salvar plano
             */
            if (!$dataGet['page']) {
                $page = 1;
            } else {
                $page = $data['page'];
            }
            $id =  ($data['id']);
            unset($data['page']);
            unset($data['id']);
            $data['valor'] = FUNCOES::formatoDecimal($data['valor']);
            if (empty($response['error'])) {
                /**
                 * atualizar plano
                 */
                if ($id) {
                    plano_assinaturaBO::salvar($data, 'planos_assinatura', $id);
                    $response['success'][] = 'Plano de assinatura  atualizado com sucesso!!';
                }
                /**
                 * inserir usuario
                 */ else {
                    plano_assinaturaBO::salvar($data, 'planos_assinatura');
                    $response['success'][] = 'Plano de assinatura inserido com sucesso!!';
                }
                $response['link'][] = "plano_assinatura.php?page=$page";
            }
        }
    }
    /**
     * Editar plano
     */
    if ($dataGet['id']) {
        try {
            $data = plano_assinaturaBO::getPlanoEspecifico($dataGet['id']);
            $data['valor'] = FUNCOES::formatoDecimalHTML($data['valor']);
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
                <li><a href="plano_assinatura.php">Planos de assinatura</a></li>
            </ol>
            <div class="well">
                <form role="form" method="post">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group form-group-lg">
                                <label for="nome">Descrição</label>
                                <input type="text" class="form-control input-lg" name="descricao" placeholder="" value="<?php echo $data['descricao']; ?>" maxlength="100" >
                                <input type="hidden"  name="id" value="<?php echo $dataGet['id']; ?>">
                                <input type="hidden" name="page" value="<?php echo $dataGet['page']; ?>">
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-2">
                                    <label for="razao_social">Qtde. auto respostas</label>
                                    <input type="text" class="form-control" name="qtde_autorespostas" placeholder="" value="<?php echo $data['qtde_autorespostas']; ?>" >
                                </div>
                                <div class="form-group col-sm-2">
                                    <label for="razao_social">Qtde. atendentes</label>
                                    <input type="text" class="form-control" name="qtde_atendentes" placeholder="" value="<?php echo $data['qtde_atendentes']; ?>" >
                                </div>
                                 <div class="form-group col-sm-2">
                                    <label for="razao_social">Qtde. contatos</label>
                                    <input type="text" class="form-control" name="qtde_contatos" placeholder="" value="<?php echo $data['qtde_contatos']; ?>" >
                                </div>
                                 <div class="form-group col-sm-2">
                                    <label for="razao_social">Qtde. mensagens mês</label>
                                    <input type="text" class="form-control" name="qtde_msg_mes" placeholder="" value="<?php echo $data['qtde_msg_mes']; ?>" >
                                </div>
                                <div class="form-group col-sm-2">
                                    <label for="cnpj">Valor R$</label>
                                    <input type="text" class="form-control" name="valor" data-toggle="maskMoney"  value="<?php echo $data['valor']; ?>" >
                                </div>
                                <div class="form-group col-sm-2">
                                    <label for="razao_social">% Repassado ao ADMIN</label>
                                    <input type="text" class="form-control numeric" name="percentual_admin"  value="<?php echo $data['percentual_admin']; ?>" >
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
