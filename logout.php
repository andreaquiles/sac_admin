<?php
//if(!isset($_SESSION)){}
session_start();
$_SESSION['admin_id'] = NULL;
$_SESSION['admin_login'] = NULL;
$_SESSION["sessiontimeadmin"] = NULL;


$_SESSION['revenda_id'] = NULL;
$_SESSION['admin_login'] = NULL;
$_SESSION["sessiontimerevenda"] = NULL;
session_write_close();
header("Location:login.php");
exit;
