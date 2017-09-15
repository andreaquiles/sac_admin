<?php
$request = trim(filter_input(INPUT_GET, 'request'));
$action = filter_input(INPUT_GET, 'action');
$nomeGet = filter_input(INPUT_GET, 'value');
require_once('../../sealed/BO/usuarioBO.php');
require_once('../../sealed/BO/revendedorBO.php');
$filter = array(
    'acao' => array(
        'filter' => FILTER_VALIDATE_REGEXP,
        'options' => array("regexp" => "/^[\w\W]{1,20}$/")
    )
);
$dataGet = filter_input_array(INPUT_POST, $filter);

/**
 * autenticações 
 */
if (isset($_SESSION['admin_id'])) {
    usuarioBO::checkExpireLogin();
    usuarioBO::checkSession();
} elseif (isset($_SESSION['revenda_id'])) {
    revendedorBO::checkExpireLogin();
    revendedorBO::checkSession();
} else {     
    header("Location:login.php");
}
/**
 * autenticações 
 */

switch ($dataGet['acao']) {
    
    /**
     * EXPIRAÇÃO
     */
    case 'load_expiracao':
        require_once '../../sealed/BO/usuario_expiracaoBO.php';
        $filter = array(
            'user_id' => array(
                'filter' => FILTER_VALIDATE_INT
            )
        );
        $dataPost = filter_input_array(INPUT_POST, $filter);
        try {
            $dado = usuario_expiracaoBO::getExpiracaoEspecifica($dataPost['user_id']);
            $response['dado'] = $dado;
        } catch (Exception $ex) {
            $response['error'] = "Erro: " . $ex->getMessage();
        }
        echo json_encode($response);
        break;
        
}
