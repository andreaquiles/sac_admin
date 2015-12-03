<?php
error_reporting(E_ALL ^ E_NOTICE);
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
        'options' => array("regexp" => "/^[\w\W]{1,30}$/")
    ),
    'repetir_senha' => array(
        'filter' => FILTER_VALIDATE_REGEXP,
        'options' => array("regexp" => "/^[\w\W]{1,30}$/")
    )
);
$dataPost = filter_input_array(INPUT_POST, $filter);
$dataGet = filter_input_array(INPUT_GET, $filterGET);

if ($dataPost) {
    $tipo = $dataPost['tipo'];
    unset($dataPost['tipo']);
    try {
        
         
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
                                <input class="btn btn-success btn-large btn-block" type="submit" value="Enviar">
                                <div class="control-group" style="">
                                    <p><a class="btn btn-lg btn-link btn-block"  href="login.php">Voltar</a></p>
                                </div>
                            </div>

                        </form>
                    </div><!--/.login-->
                </div><!--/.span12-->
            </div><!--/.row-fluid-->

        </div>
    </body>
</html>
