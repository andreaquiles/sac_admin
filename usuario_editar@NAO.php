<?php
error_reporting(E_ALL ^ E_NOTICE);
include_once '../sealed/init.php';
include_once "../lib/utils/funcoes.php";
require_once('../sealed/BO/usuarioBO.php');
$user = new User();
usuarioBO::checkExpireLogin();
usuarioBO::checkSession();


$filterGET = array(
    'user_id' => array(
        'filter' => FILTER_VALIDATE_INT
    ),
    'page' => array(
        'filter' => FILTER_VALIDATE_INT
    )
);

$filter = array(
    'id' => array(
        'filter' => FILTER_VALIDATE_INT
    ),
    'phone' => array(
        'filter' => FILTER_VALIDATE_REGEXP,
        'options' => array("regexp" => "/^[\w\W]{1,150}$/")
    ),
    'login' => array(
        'filter' => FILTER_VALIDATE_REGEXP,
        'options' => array("regexp" => "/^[\w\W]{1,150}$/")
    ),
    'password' => array(
        'filter' => FILTER_VALIDATE_REGEXP,
        'options' => array("regexp" => "/^[\w\W]{1,150}$/")
    ),
    'passwlogin' => array(
        'filter' => FILTER_VALIDATE_REGEXP,
        'options' => array("regexp" => "/^[\w\W]{1,10000}$/")
    ),
    'page' => array(
        'filter' => FILTER_VALIDATE_INT
    )
);
$dataPost = filter_input_array(INPUT_POST, $filter);
$dataGet = filter_input_array(INPUT_GET, $filterGET);
if ($dataPost && !empty($_FILES["myfile"]["name"])) {
    $fileName = FUNCOES::Upload($_FILES, 'pictures/', FUNCOES::getExtensionImages());
    $dataPost['image'] = $fileName;
}
try {
    if ($dataPost) {
        $page = $dataPost['page'];
        unset($dataPost['page']);
        usuarioBO::salvarUsuario($dataPost, 'users', $dataPost['id']);
        $response['success'][] = 'Usuário alterado com sucesso!';
        $response['link'] = "usuarios.php?page=" . $page;
    } else {
        $dado = usuarioBO::getUsuario2($dataGet['user_id']);
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

            <div class="main-content">
                <div class="breadcrumbs" id="breadcrumbs">
                    <ul class="breadcrumb">
                        <li><a href="./">admin</a><span class="divider">/</span></li></li>
                        <li><a href="usuarios.php">usuários</a><span class="divider">/</span></li></li>
                        <li class="active">editar</li>
                    </ul><!--.breadcrumb-->
                </div>

                <div class="page-content">
                    <div class="row-fluid">
                        <div class="well">

                            <form class="form-inline" method='POST' action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data">
                                <div class="control-group">
                                    <label class="control-label" >
                                        <?php if ($dado->image) { ?>
                                            <img src="<?= "../public/" . $dado->image; ?>" width="60" height="60" class="img-rounded">
                                        <?php } else { ?>
                                            <img src="" width="60" height="60" class="img-rounded">
                                        <?php } ?>    
                                    </label>
                                    <div class="controls">
                                        <div class="">
                                            <h1> <small><?= $dado->login; ?></small></h1>
                                        </div>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="inputPassword">Telefone do Whats</label>
                                    <div class="controls">
                                        <input type="text" name="phone" value="<?= $dado->phone; ?>"  >
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="inputPassword">Senha do Whats</label>
                                    <div class="controls">
                                        <input type="text"  class="span3" name="password"  value="<?= $dado->password; ?>" >
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="inputPassword">Login</label>
                                    <div class="controls">
                                        <input type="text" name="login" value="<?= $dado->login; ?>"  >
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="inputPassword">Senha de login</label>
                                    <div class="controls">
                                        <input type="text" name="passwlogin"  value="<?= $dado->passwlogin; ?>"  >
                                    </div>
                                </div>

                                <input type=hidden name="id" value="<?= $dado->id; ?>">
                                <input type=hidden name="page" value="<?= $dataGet['page']; ?>">

                                <div class="text-right">
                                    <button type="submit" class="btn btn-success">Salvar</button>
                                </div>
                            </form>
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
