<?php
include_once '../sealed/init.php';
require_once('../sealed/BO/usuarioBO.php');
require_once('../sealed/BO/users_informacaoBO.php');
require_once('../sealed/BO/revendedorBO.php');
require_once('../sealed/BO/plano_assinaturaBO.php');
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
$filterPostUserInfo = array(
    'nome' => array(
        'filter' => FILTER_VALIDATE_REGEXP,
        'options' => array("regexp" => "/^[\w\W]{3,255}$/")
    ),
    'tpPessoa' => array(
        'filter' => FILTER_VALIDATE_REGEXP,
        'options' => array("regexp" => "/^[F|J]$/")
    ),
    'cpf' => array(
        'filter' => FILTER_VALIDATE_REGEXP,
        'options' => array("regexp" => "/^[0-9]{3}.[0-9]{3}.[0-9]{3}-[0-9]{2}$/")
    ),
    'rg' => array(
        'filter' => FILTER_VALIDATE_REGEXP,
        'options' => array("regexp" => "/^[a-z0-9]{0,20}$/i")
    ),
    'data_nascimento' => array(
        'filter' => FILTER_VALIDATE_REGEXP,
        'options' => array("regexp" => "/^[0-9]{2}\/[0-9]{2}\/[0-9]{4}$/")
    ),
    'cnpj' => array(
        'filter' => FILTER_VALIDATE_REGEXP,
        'options' => array("regexp" => "/^[0-9]{2}.[0-9]{3}.[0-9]{3}\/[0-9]{4}-[0-9]{2}$/")
    ),
    'razao_social' => array(
        'filter' => FILTER_VALIDATE_REGEXP,
        'options' => array("regexp" => "/^[\w\W]{5,255}$/")
    ),
    'inscricao_estadual' => array(
        'filter' => FILTER_VALIDATE_REGEXP,
        'options' => array("regexp" => "/^([0-9]{0,20}|ISENTO)$/")
    ),
    'inscricao_municipal' => array(
        'filter' => FILTER_VALIDATE_REGEXP,
        'options' => array("regexp" => "/^([0-9]{0,20}|ISENTO)$/")
    ),
    'data_fundacao' => array(
        'filter' => FILTER_VALIDATE_REGEXP,
        'options' => array("regexp" => "/^[0-9]{2}\/[0-9]{2}\/[0-9]{4}$/")
    ),
    'email' => array(
        'filter' => FILTER_VALIDATE_EMAIL,
    ),
    'observacao' => array(
        'filter' => FILTER_VALIDATE_REGEXP,
        'options' => array("regexp" => "/^[\w\W]{1,1000}$/")
    ),
    'repetir' => array(
        'filter' => FILTER_VALIDATE_REGEXP,
        'options' => array("regexp" => "/^[\w\W]{5,255}$/")
    ),
    'users_id' => array(
        'filter' => FILTER_VALIDATE_INT
    ),
    'revenda_id' => array(
        'filter' => FILTER_VALIDATE_INT
    ),
    'planos_assinatura_id' => array(
        'filter' => FILTER_VALIDATE_INT
    ),
    'page' => array(
        'filter' => FILTER_VALIDATE_INT
    )
);

$filterPostUser = array(
    'id' => array(
        'filter' => FILTER_VALIDATE_INT
    ),
    'phone' => array(
        'filter' => FILTER_VALIDATE_REGEXP,
        'options' => array("regexp" => "/^[\w\W]{5,255}$/")
    ),
    'login' => array(
        'filter' => FILTER_VALIDATE_REGEXP,
        'options' => array("regexp" => "/^[\w\W]{5,255}$/")
    ),
    'password' => array(
        'filter' => FILTER_VALIDATE_REGEXP,
        'options' => array("regexp" => "/^[\w\W]{5,255}$/")
    ),
    'passwlogin' => array(
        'filter' => FILTER_VALIDATE_REGEXP,
        'options' => array("regexp" => "/^[\w\W]{5,255}$/")
    )
);

$filterPostEndereco = array(
    'rua' => array(
        'filter' => FILTER_VALIDATE_REGEXP,
        'options' => array("regexp" => "/^[\w\W]{1,1000}$/")
    ),
    'numero' => array(
        'filter' => FILTER_VALIDATE_REGEXP,
        'options' => array("regexp" => "/^[\w\W]{1,20}$/")
    ),
    'complemento' => array(
        'filter' => FILTER_VALIDATE_REGEXP,
        'options' => array("regexp" => "/^[\w\W]{1,500}$/")
    ),
    'bairro' => array(
        'filter' => FILTER_VALIDATE_REGEXP,
        'options' => array("regexp" => "/^[\w\W]{1,500}$/")
    ),
    'cidade' => array(
        'filter' => FILTER_VALIDATE_REGEXP,
        'options' => array("regexp" => "/^[\w\W]{1,500}$/")
    ),
    'bairro' => array(
        'filter' => FILTER_VALIDATE_REGEXP,
        'options' => array("regexp" => "/^[\w\W]{1,500}$/")
    ),
    'cidade' => array(
        'filter' => FILTER_VALIDATE_REGEXP,
        'options' => array("regexp" => "/^[\w\W]{1,500}$/")
    ),
    'estado' => array(
        'filter' => FILTER_VALIDATE_REGEXP,
        'options' => array("regexp" => "/^[\w\W]{1,10}$/")
    ),
    'cep' => array(
        'filter' => FILTER_VALIDATE_REGEXP,
        'options' => array("regexp" => "/^[\w\W]{1,10}$/")
    ),
    'tel_fixo' => array(
        'filter' => FILTER_VALIDATE_REGEXP,
        'options' => array("regexp" => "/^[\w\W]{1,20}$/")
    ),
    'celular' => array(
        'filter' => FILTER_VALIDATE_REGEXP,
        'options' => array("regexp" => "/^[\w\W]{1,20}$/")
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

$filterFinanceiro = array(
    'data_vencimento' => array(
        'filter' => FILTER_VALIDATE_REGEXP,
        'options' => array("regexp" => "/^[0-9]{2}\/[0-9]{2}\/[0-9]{4}$/")
    ),
);
//
$data_org = filter_input_array(INPUT_POST);
$datauser = filter_input_array(INPUT_POST, $filterPostUser);
$data = filter_input_array(INPUT_POST, $filterPostUserInfo);
$data_endereco = filter_input_array(INPUT_POST, $filterPostEndereco);
$data_financeiro = filter_input_array(INPUT_POST, $filterFinanceiro);
$dataGet = filter_input_array(INPUT_GET, $filterGET);

//
try {
    $revendas = revendedorBO::getRevendas(1000);
    $planos = plano_assinaturaBO::getListaCombo();
    if ($data) {
        $response = array();
        if (empty($data['nome'])) {
            $response['error'][] = 'Preencher nome';
        }
//        else if ($data['email'] == NULL) {
//            $response['error'][] = 'E-mail Inválido!';
//        } 
//        else if ($datauser['login'] == NULL) {
//            $response['error'][] = 'Preencher login';
//        } 
//        else if ($datauser['passwlogin'] == NULL) {
//            $response['error'][] = 'Preencher senha corretamanete (mínimo 5 caracteres)';
//        } 
//        else if ($data['planos_assinatura_id'] == NULL) {
//            $response['error'][] = 'Preencher plano de assinatura';
//        } 
//        else if ($data['data_vencimento'] == NULL) {
//            $response['error'][] = 'Data de Vencimento Inválido!';
//        }
//        else if ($datauser['phone'] == NULL) {
//            $response['error'][] = 'Preencher whatsapp';
//        }
        else if ($data['tpPessoa'] == NULL) {
            $response['error'][] = 'Pessoa Tipo Inválido!';
        } else if (!empty($data_org['cpf']) && $data['cpf'] == NULL) {
            $response['error'][] = 'CPF Inválido!';
        } else if (!empty($data_org['rg']) && $data['rg'] == NULL) {
            $response['error'][] = 'RG Inválido!';
        } else if (!empty($data_org['data_nascimento']) && $data['data_nascimento'] == NULL) {
            $response['error'][] = 'Data Nascimento Inválido!';
        } else if (!empty($data_org['cnpj']) && $data['cnpj'] == NULL) {
            $response['error'][] = 'CNPJ Inválido!';
        } else if (!empty($data_org['razao_social']) && $data['razao_social'] == NULL) {
            $response['error'][] = 'Razão Social Inválido!';
        } else if (!empty($data_org['inscricao_estadual']) && $data['inscricao_estadual'] == NULL) {
            $response['error'][] = 'Inscrição Estadual Inválido!';
        } else if (!empty($data_org['inscricao_municipal']) && $data['inscricao_municipal'] == NULL) {
            $response['error'][] = 'Inscrição Municipal Inválido!';
        } else if (!empty($data_org['data_fundacao']) && $data['data_fundacao'] == NULL) {
            $response['error'][] = 'Data Fundacao Inválido!';
        } else {
            if (!$dataGet['page']) {
                $page = 1;
            } else {
                $page = $data['page'];
            }
            /**
             * salvar fornecedor
             */
            if ($data['revenda_id'] == '' || empty($data['revenda_id'])) {
                $data['revenda_id'] = NULL;
            }
            $data_financeiro['data_vencimento'] = FUNCOES::formatarDatatoMYSQL($data_financeiro['data_vencimento']);
            unset($data['page']);
            if ($data['tpPessoa'] == 'F') {
                /**
                 * ((((((((((((((((((PESSOA FISICA)))))))))))))))
                 */
                unset($data['cnpj']);
                unset($data['razao_social']);
                unset($data['inscricao_estadual']);
                unset($data['inscricao_municipal']);
                unset($data['data_fundacao']);
                /**
                 * verificações cpf e email existente
                 */
                $especifico = users_informacaoBO::getCpfCnpj($data['cpf']);
                if (empty($datauser['id']) && !empty($especifico['cpf'])) {
                    /**
                     * INSERT fornecedor
                     */
                    $response['error'][] = 'CPF do Usuário já cadastrado !!!';
                } elseif (!empty($datauser['id']) && !empty($data['cpf']) && users_informacaoBO::checkCpfDiff($data['cpf'], $datauser['id'])) {
                    /**
                     * UPDATE fornecedor
                     */
                    $response['error'][] = 'CPF do Usuário já cadastrado !!!';
                }
                $data['data_nascimento'] = FUNCOES::formatarDatatoMYSQL($data['data_nascimento']);
            } else {
                /**
                 * ((((((((((((((((((((PESSOA JURIDICA))))))))))))))))))
                 */
                unset($data['cpf']);
                unset($data['rg']);
                unset($data['data_nascimento']);
                $especifico = users_informacaoBO::getCpfCnpj($data['cnpj']);
                if ((empty($datauser['id'])) && !empty($especifico)) {
                    /**
                     * INSERT cliente
                     */
                    $response['error'][] = 'CNPJ do Usuário  já cadastrada !!!';
                } elseif (!empty($datauser['id']) && !empty($data['cnpj']) && users_informacaoBO::checkCnpjDiff($data['cnpj'], $datauser['id'])) {
                    /**
                     * UPDATE cliente
                     */
                    $response['error'][] = 'CNPJ do Usuário  já cadastrada !!!';
                }
                $data['data_fundacao'] = FUNCOES::formatarDatatoMYSQL($data['data_fundacao']);
            }
            /**
             * verificações de senhas e checar email existente
             */
            if (!empty($data['email']) && empty($response['error'])) {
                $checkemail = users_informacaoBO::checkEmail($data['email']);
                if (empty($datauser['id']) && !empty($checkemail)) {
                    /**
                     * INSERT fornecedor
                     */
                    $response['error'][] = 'Email do Cliente já existente !!!';
                } elseif ($datauser['id'] && users_informacaoBO::checkEmailDiff($data['email'], $datauser['id'])) {
                    /**
                     * UPDATE fornecedor
                     */
                    $response['error'][] = 'Email do Cliente já cadastrado !!!';
                }
            }

//            if ($datauser['passwlogin'] != $data['repetir'] && empty($response['error'])) {
//                $response['error'][] = 'Senhas não conferem !!!';
//            }
            if (empty($response['error'])) {
                unset($data['repetir']);
                if ($datauser['id']) {
                    /**
                       * (((((((((((((((((( atualizar usuario ))))))))))))))))))))))))))
                     */
                    $users_id = ($datauser['id']);
                    $endereco = serialize($data_endereco);
                    $data['endereco'] = $endereco;
                    unset($data['id']);
                    usuarioBO::salvarUsuario($datauser, 'users', $users_id);
                    $id = users_informacaoBO::checkUsersInfo($datauser['id']);
                     /**
                     *  Lança no Financeiro
                     */
                    if ($data['planos_assinatura_id']) {
                        $plano = plano_assinaturaBO::getPlanoEspecifico($data['planos_assinatura_id']);
                        $data_financeiro['valor'] = FUNCOES::formatoDecimalPercentual($plano['valor']);
                        $data_financeiro['valor']*= (1 + (FUNCOES::formatoDecimalPercentual($plano['percentual_admin']) / 100));
                        $data_financeiro['users_id'] = $users_id;
                        $financeiro_id = financeiroBO::getFinanceiroEspecificoPorUser($users_id);
                        financeiroBO::salvar($data_financeiro, 'financeiro',$financeiro_id['financeiro_id']);
                    }else{
                        $data['planos_assinatura_id'] = NULL;
                    }
                    /**
                     * 
                     */
                    if ($id->users_id) {
                        users_informacaoBO::salvar($data, 'users_informacao', $users_id);
                    } else {
                        users_informacaoBO::salvar($data, 'users_informacao');
                    }
                   
                    $response['success'][] = 'Usuário atualizado com sucesso!!';
                } else {
                    /**
                     * (((((((((((((((((( inserir usuario  )))))))))))))))))))))))
                     */
                    $endereco = serialize($data_endereco);
                    $data['endereco'] = $endereco;
                    unset($data['id']);
                    unset($datauser['id']);
                    $id = usuarioBO::salvarUsuario($datauser, 'users');
                    $data['users_id'] = $id;
                    /**
                     *  lança no Financeiro
                     */
                    if ($data['planos_assinatura_id']) {
                        $plano = plano_assinaturaBO::getPlanoEspecifico($data['planos_assinatura_id']);
                        $data_financeiro['valor'] = FUNCOES::formatoDecimalPercentual($plano['valor']);
                        $data_financeiro['valor']*= (1 + (FUNCOES::formatoDecimalPercentual($plano['percentual_admin']) / 100));
                        $data_financeiro['users_id'] = $id;
                        financeiroBO::salvar($data_financeiro, 'financeiro');
                    }else{
                        $data['planos_assinatura_id'] = NULL;
                    }
                    
                    users_informacaoBO::salvar($data, 'users_informacao');
                    $response['success'][] = 'Usuário inserido com sucesso!!';
                }
                $response['link'][] = "usuario.php?page=$page";
            }
        }
    }
    /**
     * Editar cliente
     */
    if ($dataGet['id']) {
        try {
            $data = users_informacaoBO::getUsuarioInfo($dataGet['id']);
            $endereco = unserialize($data['endereco']);
            $data['data_nascimento'] = FUNCOES::formatarDatatoHTML($data['data_nascimento']);
            $data['data_fundacao'] = FUNCOES::formatarDatatoHTML($data['data_fundacao']);
            if ($data['data_vencimento']) {
                $data['data_vencimento'] = FUNCOES::formatarDatatoHTML($data['data_vencimento']);
            }
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
        <script src="assets/bootstrap/js/bootbox.min.js"></script>
        <script src="assets/js/jquery.maskedinput.min.js"></script>
        <script src="assets/js/typeahead.js"></script>
        <script src="assets/js/tipo_pessoa.min.js"></script>
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
                <li><a href="usuario.php">Usuários</a></li>
            </ol>
            <div class="well">
                <form role="form" method="post">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group form-group-lg">
                                <label for="nome">Nome</label>
                                <input type="text" class="form-control input-lg" name="nome" placeholder="" value="<?php echo $data['nome']; ?>" maxlength="100" >
                                <input type="hidden"  name="id" value="<?php echo $dataGet['id']; ?>">
                                <input type="hidden"  name="users_id" value="<?php echo $dataGet['id']; ?>">
                                <input type="hidden" name="page" value="<?php echo $dataGet['page']; ?>">
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-3">
                                    <label for="razao_social">Email</label>
                                    <input type="text" class="form-control" name="email" placeholder="" value="<?php echo $data['email']; ?>" >
                                </div>
                                <div class="form-group col-sm-5">
                                    <label for="razao_social">Login</label>
                                    <input type="text" class="form-control" name="login" placeholder="" value="<?php echo $data['login']; ?>" >
                                </div>
                                <div class="form-group col-sm-4">
                                    <label for="cnpj">Senha</label>
                                    <input type="text" class="form-control" name="passwlogin" placeholder="" value="<?php echo $data['passwlogin']; ?>" >
                                </div>
                                <!--                                <div class="form-group col-sm-3">
                                                                    <label for="data_fundacao">Repetir senha</label>
                                                                    <input type="password" class="form-control" name="repetir" placeholder="" value="" >
                                                                </div>-->
<?php if (isset($_SESSION['admin_id'])) { ?>
                                    <div class="form-group col-sm-2">
                                        <label for="data_fundacao">Revenda</label>
                                        <select class="form-control" name="revenda_id">
                                            <option value="" selected="">Admin</option>
    <?php
    if (is_array($revendas)) {
        foreach ($revendas as $revenda) {
            if ($revenda->nome) {
                $descricao = $revenda->nome;
            } else {
                $descricao = $revenda->razao;
            }
            ?>
                                                    <option value="<?= $revenda->revenda_id ?>" <?php echo $data['revenda_id'] == $revenda->revenda_id ? ' selected' : ''; ?>><?= $descricao ?></option>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
<?php } elseif (isset($_SESSION['revenda_id'])) { ?>
                                    <input type="hidden"  name="revenda_id" value="<?= $_SESSION['revenda_id']; ?>">
                                <?php } ?>
                                <div class="form-group col-sm-2">
                                    <label for="data_fundacao">Plano de assinatura</label>
                                    <select class="form-control" name="planos_assinatura_id">
                                        <option value="" selected="">selecione</option>
<?php
if (is_array($planos)) {
    foreach ($planos as $plano) {
        ?>
                                                <option value="<?= $plano->planos_assinatura_id ?>" <?php echo $data['planos_assinatura_id'] == $plano->planos_assinatura_id ? ' selected' : ''; ?>><?= $plano->descricao ?></option>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group col-sm-2">
                                    <label for="data_vencimento">Vencimento</label>
                                    <input type="text" data-toggle="datepicker" class="form-control" name="data_vencimento" value="<?= $data['data_vencimento'] ?>" >
                                </div>
                            </div>
                            <div class="panel panel-default">
                                <h4 class="panel-title" style="padding: 4px;">
                                    <span class="glyphicon glyphicon-info-sign"> </span> Endereço 
                                </h4>
                                <div class="panel-body">
                                    <div class="form-group col-sm-6">
                                        <label for="razao_social">Rua</label>
                                        <input type="text" class="form-control" name="rua" placeholder="" value="<?php echo $endereco['rua']; ?>">
                                    </div>
                                    <div class="form-group col-sm-2">
                                        <label for="razao_social">Número</label>
                                        <input type="text" class="form-control" name="numero" placeholder="" value="<?php echo $endereco['numero']; ?>">
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <label for="razao_social">Complemento</label>
                                        <input type="text" class="form-control" name="complemento" placeholder="" value="<?php echo $endereco['complemento']; ?>">
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <label for="razao_social">Bairro</label>
                                        <input type="text" class="form-control" name="bairro" placeholder="" value="<?php echo $endereco['bairro']; ?>">
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <label for="razao_social">Cidade</label>
                                        <input type="text" class="form-control" name="cidade" placeholder="" value="<?php echo $endereco['cidade']; ?>">
                                    </div>
                                    <div class="form-group col-sm-2">
                                        <label for="razao_social">Estado</label>
                                        <input type="text" class="form-control" name="estado" placeholder="" value="<?php echo $endereco['estado']; ?>">
                                    </div>
                                    <div class="form-group col-sm-2">
                                        <label for="razao_social">Cep</label>
                                        <input type="text" class="form-control" name="cep" placeholder="" value="<?php echo $endereco['cep']; ?>">
                                    </div>
                                    <div class="form-group col-sm-2">
                                        <label for="razao_social">Telefone fixo</label>
                                        <input type="text" class="form-control" name="tel_fixo" placeholder="" value="<?php echo $endereco['tel_fixo']; ?>">
                                    </div>
                                    <div class="form-group col-sm-2">
                                        <label for="razao_social">Celular</label>
                                        <input type="text" class="form-control" name="celular" placeholder="" value="<?php echo $endereco['celular']; ?>">
                                    </div>
                                    <div class="form-group col-sm-2">
                                        <label for="razao_social">Whatsapp</label>
                                        <input type="text" class="form-control" name="phone" placeholder="" value="<?php echo $data['phone']; ?>">
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label for="razao_social">Hash</label>
                                        <input type="text" class="form-control" name="password" placeholder="" value="<?php echo $data['password']; ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                                <div class="panel panel-default">
                                    <div class="panel-heading" role="tab" id="headingOne">
                                        <h4 class="panel-title">
                                            <a data-toggle="collapse" href="#campos_extras"><span class="glyphicon glyphicon-circle-arrow-down"></span> Informar Mais Campos</a> <small>(Opcional)</small>
                                        </h4>
                                    </div>

                                    <div id="campos_extras" class="collapse 
<?php
echo $data['inscricao_estadual'] ||
 $data['inscricao_municipal'] ||
 $data['razao_social'] ||
 $data['cnpj'] ||
 $data['cpf'] ||
 $data['rg'] ? 'in' : ''
?>"
                                         aria-labelledby="headingOne">
                                        <div class="panel-body">
                                            <div class="row">

<?php
/*
 * Pessoa Física
 */
?>
                                                <div class="form-group col-sm-3">
                                                    <label for="tpPessoa">Pessoa Tipo</label>
                                                    <select class="form-control" name="tpPessoa">
                                                        <option value="J" <?php echo $data['tpPessoa'] == 'J' ? 'selected' : ''; ?>>Jurídica</option>
                                                        <option value="F" <?php echo $data['tpPessoa'] == 'F' || empty($data['tpPessoa']) ? 'selected' : ''; ?>>Física</option>
                                                    </select>
                                                </div>
                                                <div class="pFisica">
                                                    <div class="form-group col-sm-3 ">
                                                        <label for="cpf">CPF</label>
                                                        <input type="text" class="form-control" name="cpf" placeholder="" value="<?php echo $data['cpf']; ?>">
                                                    </div>
                                                    <div class="form-group col-sm-3">
                                                        <label for="razao_social">RG</label>
                                                        <input type="text" class="form-control" name="rg" placeholder="" value="<?php echo $data['rg']; ?>">
                                                    </div>
                                                    <div class="form-group col-sm-3">
                                                        <label for="data_nascimento">Data nascimento</label>
                                                        <input type="text" class="form-control" name="data_nascimento" placeholder="" value="<?php echo $data['data_nascimento']; ?>">
                                                    </div>
                                                </div>

<?php
/*
 * Pessoa Jurídica
 */
?>
                                                <div class="pJuridica" style="display: none;">
                                                    <div class="form-group col-sm-5">
                                                        <label for="razao_social">Razão social</label>
                                                        <input type="text" class="form-control" name="razao_social" placeholder="" value="<?php echo $data['razao_social']; ?>">
                                                    </div>
                                                    <div class="form-group col-sm-4">
                                                        <label for="cnpj">CNPJ</label>
                                                        <input type="text" class="form-control" name="cnpj" placeholder="" value="<?php echo $data['cnpj']; ?>">
                                                    </div>
                                                    <div class="form-group col-sm-4">
                                                        <label for="data_fundacao">Data Fundação</label>
                                                        <input type="text" class="form-control" name="data_fundacao" placeholder="" value="<?php echo $data['data_fundacao']; ?>">
                                                    </div>
                                                    <div class="form-group col-sm-4">
                                                        <label for="inscricao_estadual">Inscrição Estadual <small>(Somente Numeros)</small></label>
                                                        <div class="input-group">
                                                            <input type="text" class="form-control" name="inscricao_estadual" placeholder="" value="<?php echo $data['inscricao_estadual']; ?>" <?php echo ($data['inscricao_estadual'] == 'ISENTO') ? 'readonly' : ''; ?>>
                                                            <div class="input-group-btn">
                                                                <button type="button" class="btn btn-primary inscricao_estadual"><?php echo ($data['inscricao_estadual'] == 'ISENTO') ? 'NÃO ISENTO' : 'ISENTO'; ?></button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-sm-4">
                                                        <label for="inscricao_municipal">Inscrição Municipal <small>(Somente Numeros)</small></label>
                                                        <div class="input-group">
                                                            <input type="text" class="form-control" name="inscricao_municipal" placeholder="" value="<?php echo $data['inscricao_municipal']; ?>" <?php echo ($data['inscricao_municipal'] == 'ISENTO') ? 'readonly' : ''; ?>>
                                                            <div class="input-group-btn">
                                                                <button type="button" class="btn btn-primary inscricao_municipal"><?php echo ($data['inscricao_municipal'] == 'ISENTO') ? 'NÃO ISENTO' : 'ISENTO'; ?></button>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
<?php
/*
 * Endereço
 */
?>

                                <?php
                                /*
                                 * Observações
                                 */
                                ?>
                                <div class="panel panel-default">
                                    <div class="panel-heading" role="tab" id="headingOne">
                                        <h4 class="panel-title">
                                            <a data-toggle="collapse" href="#observacao_cliente"><span class="glyphicon glyphicon-circle-arrow-down"></span> Informar Observações</a> <small>(Opcional)</small>
                                        </h4>
                                    </div>

                                    <div id="observacao_cliente" class="collapse <?php echo (!empty($data['observacao'])) ? 'in' : ''; ?>">
                                        <div class="panel-body">
                                            <div class="form-group">
                                                <label for="observacao">Observações</label>
                                                <textarea class="form-control" name="observacao" rows="6"><?php echo $data['observacao']; ?></textarea>
                                            </div>
                                        </div>
                                    </div>
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
<?php
if ($data['tpPessoa'] == 'J') {
    echo '<script> $(\'.pFisica\').hide();$(\'.pJuridica\').show();</script>';
} else {
    echo '<script> $(\'.pJuridica\').hide(); $(\'.pFisica\').show();</script>';
}
?>
        <script src="assets/js/gerenciador.min.js"></script>
    </body>
</html>
