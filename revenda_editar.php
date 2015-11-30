<?php
include_once '../sealed/init.php';
require_once('../sealed/BO/usuarioBO.php');
require_once('../sealed/BO/revendedorBO.php');
$user = new User();
usuarioBO::checkExpireLogin();
usuarioBO::checkSession();
include_once "../lib/utils/funcoes.php";


$filterPOST = array(
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
    'senha' => array(
        'filter' => FILTER_VALIDATE_REGEXP,
        'options' => array("regexp" => "/^[\w\W]{5,255}$/")
    ),
    'observacao' => array(
        'filter' => FILTER_VALIDATE_REGEXP,
        'options' => array("regexp" => "/^[\w\W]{1,1000}$/")
    ),
    'repetir' => array(
        'filter' => FILTER_VALIDATE_REGEXP,
        'options' => array("regexp" => "/^[\w\W]{5,255}$/")
    ),
    'revenda_id' => array(
        'filter' => FILTER_VALIDATE_INT
    ),
    'page' => array(
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
    'revenda_id' => array(
        'filter' => FILTER_VALIDATE_INT
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
    ),
    'whatsapp' => array(
        'filter' => FILTER_VALIDATE_REGEXP,
        'options' => array("regexp" => "/^[\w\W]{1,20}$/")
    )
);
//
$data_org = filter_input_array(INPUT_POST);
$data = filter_input_array(INPUT_POST, $filterPOST);
$data_endereco = filter_input_array(INPUT_POST, $filterPostEndereco);
$dataGet = filter_input_array(INPUT_GET, $filterGET);

//
try {
    if ($data) {
        $response = array();
        if (empty($data['nome'])) {
            $response['error'][] = 'Preencher nome';
        } else if ($data_endereco['rua'] == NULL) {
            $response['error'][] = 'Preencher rua';
        } else if ($data_endereco['numero'] == NULL) {
            $response['error'][] = 'Preencher numero';
        } else if ($data_endereco['bairro'] == NULL) {
            $response['error'][] = 'Preencher bairro';
        } else if ($data_endereco['cidade'] == NULL) {
            $response['error'][] = 'Preencher cidade';
        } else if ($data_endereco['estado'] == NULL) {
            $response['error'][] = 'Preencher estado';
        } else if ($data_endereco['cep'] == NULL) {
            $response['error'][] = 'Preencher cep';
        } else if ($data_endereco['tel_fixo'] == NULL) {
            $response['error'][] = 'Preencher telefone';
        } else if ($data_endereco['celular'] == NULL) {
            $response['error'][] = 'Preencher celular';
        } else if ($data_endereco['whatsapp'] == NULL) {
            $response['error'][] = 'Preencher whatsapp';
        } else if ($data['email'] == NULL) {
            $response['error'][] = 'E-mail Inválido!';
        } else if ($data['senha'] == NULL) {
            $response['error'][] = 'Preencher senha corretamanete (mínimo 5 caracteres)';
        } else if ($data['tpPessoa'] == NULL) {
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
            /**
             * salvar fornecedor
             */
            $page = $data['page'];
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
                $especifico = revendedorBO::getCpfCnpj($data['cpf']);
                if (empty($data['revenda_id']) && !empty($especifico['cpf'])) {
                    /**
                     * INSERT fornecedor
                     */
                    $response['error'][] = 'CPF da Revenda já cadastrado !!!';
                } elseif ($data['revenda_id'] && revendedorBO::checkCpfDiff($data['cpf'], $data['revenda_id'])) {
                    /**
                     * UPDATE fornecedor
                     */
                    $response['error'][] = 'CPF Revenda já cadastrado !!!';
                }
                $data['data_nascimento'] = FUNCOES::formatarDatatoMYSQL($data['data_nascimento']);
            } else {
                /**
                 * ((((((((((((((((((((PESSOA JURIDICA))))))))))))))))))
                 */
                unset($data['cpf']);
                unset($data['rg']);
                unset($data['data_nascimento']);
                $especifico = revendedorBO::getCpfCnpj($data['cnpj']);
                if ((empty($data['revenda_id'])) && !empty($especifico)) {
                    /**
                     * INSERT fornecedor
                     */
                    $response['error'][] = 'CNPJ da Revenda  já cadastrada !!!';
                } elseif ($data['revenda_id'] && revendedorBO::checkCnpjDiff($data['cnpj'], $data['revenda_id'])) {
                    /**
                     * UPDATE fornecedor
                     */
                    $response['error'][] = 'CNPJ da Revenda  já cadastrada !!!';
                }
                $data['data_fundacao'] = FUNCOES::formatarDatatoMYSQL($data['data_fundacao']);
            }
            /**
             * verificações de senhas e checar email existente
             */
            if (!empty($data['email']) && empty($response['error'])) {
                $checkemail = revendedorBO::checkEmail($data['email']);
                if (empty($data['revenda_id']) && !empty($checkemail)) {
                    /**
                     * INSERT fornecedor
                     */
                    $response['error'][] = 'Email de Revenda já existente !!!';
                } elseif ($data['revenda_id'] && revendedorBO::checkEmailDiff($data['email'], $data['revenda_id'])) {
                    /**
                     * UPDATE fornecedor
                     */
                    $response['error'][] = 'Email do Fornecedor já cadastrado !!!';
                }
            }

            if ($data['senha'] != $data['repetir'] && empty($response['error'])) {
                $response['error'][] = 'Senhas não conferem !!!';
            }
            if (empty($response['error'])) {
                $senhaOriginal = $data['senha'];
                $data['senha'] = FUNCOES::cryptografar($data['senha']);
                unset($data['repetir']);

                if ($data['revenda_id']) {
                    /**
                     * atualizar fornecedor
                     */
                    $revenda_id = ($data['revenda_id']);
                    $endereco = serialize($data_endereco);
                    $data['endereco'] = $endereco;
                    unset($data['revenda_id']);
                    revendedorBO::salvar($data, 'revenda', $revenda_id);
                    $response['success'][] = 'Revenda atualizado com sucesso!!';
                } else {
                    /**
                     * inserir fornecedor
                     */
                    $endereco = serialize($data_endereco);
                    $data['endereco'] = $endereco;
                    unset($data['revenda_id']);
                    $revenda_id = revendedorBO::salvar($data, 'revenda');
                    $response['success'][] = 'Revenda inserido com sucesso!!';
                }
                $response['link'][] = "revenda.php?page=$page";
            }
        }
    }
    /**
     * Editar fornecedor
     */
    if ($dataGet['revenda_id']) {
        $data = revendedorBO::getRevendedor($dataGet['revenda_id']);
        $endereco = unserialize($data['endereco']);
        $data['data_nascimento'] = FUNCOES::formatarDatatoHTML($data['data_nascimento']);
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
            <ol class="breadcrumb">
                <li><a href="index.php">Home</a></li>
                <li><a href="revenda.php">Revendas</a></li>
            </ol>
            <div class="well">
                <form role="form" method="post">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group form-group-lg">
                                <label for="nome">Nome</label>
                                <input type="text" class="form-control input-lg" name="nome" placeholder="" value="<?php echo $data['nome']; ?>" maxlength="100" >
                                <input type="hidden"  name="revenda_id" value="<?php echo $data['revenda_id']; ?>">
                                <input type="hidden" name="page" value="<?php echo $dataGet['page']; ?>">
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <label for="razao_social">Email</label>
                                    <input type="text" class="form-control" name="email" placeholder="" value="<?php echo $data['email']; ?>" >
                                </div>
                                <div class="form-group col-sm-3">
                                    <label for="cnpj">Senha</label>
                                    <input type="password" class="form-control" name="senha" placeholder="" value="" >
                                </div>
                                <div class="form-group col-sm-3">
                                    <label for="data_fundacao">Repetir senha</label>
                                    <input type="password" class="form-control" name="repetir" placeholder="" value="" >
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
                                        <input type="text" class="form-control" name="whatsapp" placeholder="" value="<?php echo $endereco['whatsapp']; ?>">
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
