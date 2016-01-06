<?php
$request = trim(filter_input(INPUT_GET, 'request'));
$action = filter_input(INPUT_GET, 'action');
$nomeGet = filter_input(INPUT_GET, 'value');
require_once('../../sealed/BO/usuarioBO.php');
require_once('../../sealed/BO/revendedorBO.php');

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

switch ($action) {
    case '_usuario' :
        try {
            $dados = usuarioBO::ajax_autocomplete_usuarios($request);
            if ($dados) {
                foreach ($dados as $campo) {
                    $id = $campo->users_id;
                    $nome = filter_var($campo->nome,FILTER_SANITIZE_STRING);
                     if (empty($nomeGet)) {
                         $value = $campo->users_id;
                     }else{
                         $value = filter_var($campo->nome,FILTER_SANITIZE_STRING);
                     }
                    ?>
                    <li onselect="this.setText('<?php echo $nome; ?>', '<?php echo $value; ?>').setValue('<?php echo $value; ?>', '')">
                        <?php echo str_ireplace($request, '<strong>' . $request . '</strong>', $nome); ?>
                    </li> 
                    <?php 
                }

                if (!$dados){
                    ?>
                    <li onselect="this.setText('<?php echo $request; ?>', '');">
                        <?php echo str_ireplace($request, '<strong>' . $request . '</strong>', $request); ?>
                       <span style="color: red;font-weight: bold;font-style: italic;" >nome não encontrado </span>
                    </li>
                    <?php
                }
            } else {
                ?>
                <li onselect="this.setText('<?php echo $request; ?>', '');"> 
                    <?php echo str_ireplace($request, '<strong>' . $request . '</strong>', $request); ?>
                    <span style="color: red;font-weight: bold;font-style: italic;" >nome não encontrado </span>
                </li>
                <?php
            }
        } catch (Exception $ex) {
            ?>
            <li onselect="this.setText('', 'erro');">
                <span style="color: red;font-weight: bold;font-style: italic;" >
                    Erro: <?= substr(" erro no Mysql !!! )" . $ex->getMessage(), 0, 50); ?> 
                </span>
                <?php echo str_ireplace($request, '<strong>' . $request . '</strong>', $request); ?>
            </li>
            <?php
        }
        break;
        
        case '_phone' :
        try {
            
            $dados = usuarioBO::ajax_autocomplete_usuarios_phone($request);
            if ($dados) {
                foreach ($dados as $campo) {
                    $id = $campo->id;
                    $phone = $campo->phone;
                    ?>
                    <li onselect="this.setText('<?php echo $phone; ?>', '').setValue('<?php echo $phone; ?>')"><?php echo str_ireplace($request, '<strong>' . $request . '</strong>', $phone); ?></li>
                    <?php
                }

                if (!$dados) {
                    ?>
                    <li onselect="this.setText('<?php echo $request; ?>', '');">
                        <?php echo str_ireplace($request, '<strong>' . $request . '</strong>', $request); ?>
                       <span style="color: red;font-weight: bold;font-style: italic;" >whatsapp não encontrado </span>
                    </li>
                    <?php
                }
            } else {
                ?>
                <li onselect="this.setText('<?php echo $request; ?>', '');"> 
                    <?php echo str_ireplace($request, '<strong>' . $request . '</strong>', $request); ?>
                    <span style="color: red;font-weight: bold;font-style: italic;" >whatsapp não encontrado </span>
                </li>
                <?php
            }
        } catch (Exception $ex) {
            ?>
            <li onselect="this.setText('', 'erro');">
                <span style="color: red;font-weight: bold;font-style: italic;" >
                    Erro: <?= substr(" erro no Mysql !!! )" . $ex->getMessage(), 0, 50); ?> 
                </span>
                <?php echo str_ireplace($request, '<strong>' . $request . '</strong>', $request); ?>
            </li>
            <?php
        }
        break;
        
}
