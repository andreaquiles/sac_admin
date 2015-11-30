<?php
$request = trim(filter_input(INPUT_GET, 'request'));
$action = filter_input(INPUT_GET, 'action');

switch ($action) {
    case '_usuario' :
        try {
            require_once('../../sealed/BO/usuarioBO.php');
            $dados = usuarioBO::ajax_autocomplete_usuarios($request);
            if ($dados) {
                foreach ($dados as $campo) {
                    $id = $campo->users_id;
                    $nome = $campo->nome;
                    $barras = "";
                    ?>
                    <li onselect="this.setText('<?php echo $nome; ?>', '<?php echo $id; ?>').setValue('<?php echo $id; ?>', '<?php echo $barras; ?>')"><?php echo str_ireplace($request, '<strong>' . $request . '</strong>', $nome); ?></li>
                    <?php
                }

                if (!$dados) {
                    ?>
                    <li onselect="this.setText('<?php echo $request; ?>', '');">
                        <?php echo str_ireplace($request, '<strong>' . $request . '</strong>', $request); ?>
                       <span style="color: red;font-weight: bold;font-style: italic;" >não encontrado </span>
                    </li>
                    <?php
                }
            } else {
                ?>
                <li onselect="this.setText('<?php echo $request; ?>', '');"> 
                    <?php echo str_ireplace($request, '<strong>' . $request . '</strong>', $request); ?>
                    <span style="color: red;font-weight: bold;font-style: italic;" >não encontrado </span>
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
