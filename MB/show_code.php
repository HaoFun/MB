<?php
session_start();
include_once 'inc/vcode.inc.php';
$_SESSION['vcode']=vcode();  //將驗證碼存入session內
?>