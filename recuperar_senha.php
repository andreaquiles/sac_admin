<?php
error_reporting(E_ALL ^ E_NOTICE);
include_once '../sealed/init.php';
include_once "../sealed/BO/usuarioBO.php";
include_once "../sealed/BO/revendedorBO.php";
include_once "../lib/utils/funcoes.php";

$filterGET = array(
    'nid' => array(
        'filter' => FILTER_VALIDATE_INT
    ),
    'nivel' => array(
        'filter' => FILTER_SANITIZE_STRING
    ),
    'token' => array(
        'filter' => FILTER_SANITIZE_STRING
    )
);

$filter = array(
    'senha' => array(
        'filter' => FILTER_VALIDATE_REGEXP,
        'options' => array("regexp" => "/^[\w\W]{5,30}$/")
    ),
    'nid' => array(
        'filter' => FILTER_VALIDATE_INT
    ),
    'nivel' => array(
        'filter' => FILTER_SANITIZE_STRING
    )
);
$dataPost = filter_input_array(INPUT_POST, $filter);
$dataGet = filter_input_array(INPUT_GET, $filterGET);

try {
    if ($dataPost) {
        if ($dataPost['senha'] == NULL) {
            $response['error'][] = 'Preencher senha corretamente (mínimo 5 caracteres)';
        } else {
            if ($dataPost['nivel'] == 'admin') {
                $data['senha'] = FUNCOES::cryptografar($dataPost['senha']);
                usuarioBO::salvarUsuario($data, 'usuarios', $dataPost['nid']);
                $response['success'][] = 'Senha alterada com sucesso !!!';
            }elseif ($dataPost['nivel'] == 'revenda') {
                $data['senha'] = FUNCOES::cryptografar($dataPost['senha']);
                revendedorBO::salvar($data, 'revenda', $dataPost['nid']);
                $response['success'][] = 'Senha alterada com sucesso !!!';
            }elseif ($dataPost['nivel'] == 'usuario') {
                 $data['passwlogin'] = $dataPost['senha'];
                 usuarioBO::salvarUsuario($data, 'users', $dataPost['nid']);
                 $response['success'][] = 'Senha alterada com sucesso !!!';
            }
        }
    } elseif ($dataGet['nivel'] == "admin") {
        $dado = usuarioBO::getUsuarios($dataGet['nid']);
        $token = FUNCOES::cryptografar($dataGet['nid'] . $dado->senha);
        if ($token !== $dataGet['token']) {
            throw new Exception('Parâmetros incorretos !!!');
        }
    }elseif ($dataGet['nivel'] == "revenda") {
        $dado = revendedorBO::getRevendedor($dataGet['nid']);
        $token = FUNCOES::cryptografar($dataGet['nid'] . $dado['senha']);
        if ($token !== $dataGet['token']) {
            throw new Exception('Parâmetros incorretos !!!');
        }
    }elseif ($dataGet['nivel'] == "usuario") {
        $dado = usuarioBO::getUsuario($dataGet['nid']);
        $token = FUNCOES::cryptografar($dataGet['nid'] . $dado->passwlogin);
        if ($token !== $dataGet['token']) {
            throw new Exception('Parâmetros incorretos !!!');
        }
    } else {
        throw new Exception('Parâmetros incorretos !!!');
    }
} catch (Exception $e) {
    $response['error'][] = $e->getMessage();
}
//}
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
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
        <script src="../public/assets/js/bootstrap.min.js"></script>
        <script src="../public/assets/js/jquery.forms.js"></script>
        <script src="../public/assets/js/bootbox.min.js"></script>
        <script src="../public/assets/js/manager.js"></script>
        <link href="../public/assets/css/bootstrap.min.css" rel="stylesheet">
        <style>

            #login img{
                margin: 10px 0;
            }
            #login .center {
                text-align: center;
            }

            #login .login {
                max-width: 300px;
                margin: 35px auto;
            }

            #login .login-form{
                padding:0px 25px;
            }
        </style>
    </head>
    <body>
        <div id="login" class="container">
            <div class="row-fluid">
                <div class="span12">
                    <div class="login well well-small">
                        <div id="alerta" style="text-align: center">
                            <?php
                            if (isset($response['error'])) {
                                if (!empty($response['error'])) {
                                    ?>
                                    <div class="alert alert-danger fade in" role="alert">
                                        <?php
                                        echo implode('<br>', $response['error']);
                                        exit();
                                        ?>
                                    </div>
                                    <?php
                                }
                            }
                            ?>
                        </div>
                        <div class="center">
                            <img src="http://placehold.it/250x100&text=Logo" alt="logo"> 
                        </div>
                        <form action=""  class="login-form" id="UserLoginForm" method="post" accept-charset="utf-8">
                            <div class="control-group">
                                <div class="input-prepend">
                                    <span class="add-on"><i class="icon-user"></i></span>
                                    <input name="nivel"  type="hidden" value="<?= $dataGet['nivel'] ?>">
                                    <input name="nid"  type="hidden" value="<?= $dataGet['nid'] ?>">
                                    <input name="senha" required="required" placeholder="senha" maxlength="20" type="password" id=""> 
                                </div>
                                <!--                                <div class="input-prepend">
                                                                    <span class="add-on"><i class="icon-user"></i></span>
                                                                    <input name="repetir_senha" required="required" placeholder="repetir senha" maxlength="20" type="password" id=""> 
                                                                </div>-->
                            </div>
                            <div class="control-group" >
                                <input class="btn btn-success btn-large btn-block" type="submit" value="Enviar">
                            </div>

                        </form>
                    </div><!--/.login-->
                </div><!--/.span12-->
            </div><!--/.row-fluid-->

        </div>
    </body>
</html>
