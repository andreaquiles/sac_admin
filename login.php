<?php
error_reporting(E_ALL ^ E_NOTICE);
include_once "../sealed/BO/usuarioBO.php";
include_once "../sealed/BO/revendedorBO.php";
include_once "../lib/utils/funcoes.php";

$filter = array(
    'email' => array(
        'filter' => FILTER_VALIDATE_REGEXP,
        'options' => array("regexp" => "/^[\w\W]{1,255}$/")
    ),
    'senha' => array(
        'filter' => FILTER_VALIDATE_REGEXP,
        'options' => array("regexp" => "/^[\w\W]{1,255}$/")
    ),
    'tipo' => array(
        'filter' => FILTER_VALIDATE_REGEXP,
        'options' => array("regexp" => "/^[\w\W]{1,30}$/")
    )
);
$dataPost = filter_input_array(INPUT_POST, $filter);
if ($dataPost) {
    $tipo = $dataPost['tipo'];
    unset($dataPost['tipo']);
    try {
        if ($tipo == "admin") {
            usuarioBO::getLoginAdmin($dataPost);
            $response['success'][] = "aguarde...";
            $response['link'][] = "index.php";
        } elseif ($tipo == "revenda") {
            revendedorBO::getLoginRevenda($dataPost);
            $response['success'][] = "aguarde...";
            $response['link'][] = "index.php";
        } else {
            $response['error'][] = "a fazer !!!";
        }
    } catch (Exception $e) {
        $response['error'][] = $e->getMessage();
    }
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
        <script src="../js/jquery.min.js"></script>
        <script src="../public/assets/js/bootstrap-modal.js"></script>
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
                text-align: center;
                width: 1000px;
            }

            #login .login-form{
                padding:0px 25px;
            }
            .container, .container {
                width: 350px;
            }
        </style>
    </head>
    <body>
        <div id="login" class="container">
            <div class="row-fluid">
                <div class="span12">
                    <div class="login well">
                        <div id="alerta">
                            <?php
                            if (isset($response['error'])) {
                                if (!empty($response['error'])) {
                                    ?>
                                    <div class="alert alert-danger fade in" role="alert">
                                        <?php echo implode('<br>', $response['error']); ?>
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
                                    <input name="email" required="required" placeholder="email" maxlength="255" type="email" id="UserUsername"> 
                                </div>
                            </div>
                            <div class="control-group">
                                <div class="input-prepend">
                                    <span class="add-on"><i class="icon-lock"></i></span>
                                    <input name="senha" required="required" placeholder="senha" type="password" id="UserPassword"> 
                                </div>

                            </div>

                            <div class="control-group" style="margin-left:-1.5em;">
                                <div class="input-prepend">
                                    <label class="checkbox inline">
                                        <input type="radio" name="tipo" id="inlineCheckbox1" value="admin" checked=""><span style="margin-left: 1px" class="label">Admin</span>
                                    </label>
                                    <label class="checkbox inline">
                                        <input type="radio" name="tipo"  id="inlineCheckbox2" value="revenda"><span style="margin-left: 1px" class="label">Revenda</span>
                                    </label>
                                    <label class="checkbox inline">
                                        <input type="radio" name="tipo"  id="inlineCheckbox3" value="usuario"><span style="margin-left: 1px" class="label">Usuário</span>
                                    </label>
                                </div>
                            </div>

                            <div class="control-group" >
                                <input class="btn btn-success btn-large btn-block" type="submit" value="Entrar">
                                <div class="control-group" style="">
                                    <p><a class="btn btn-lg btn-link btn-block"  href="recuperar_email.php">Recuperar senha</a></p>
                                </div>
                            </div>
                        </form>
                    </div><!--/.login-->
                </div>
            </div>

        </div>
    </body>
</html>
