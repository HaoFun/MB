<?php
include_once '../inc/config.inc.php';
include_once '../inc/mysql.inc.php';
include_once '../inc/tool.inc.php';
$link=connect();
if(!is_manage_login($link))   //如後台重複登入，則跳轉到index.php
{
    header('Location:login.php');
}
session_unset();   //銷毀session
session_destroy();
setcookie(session_name(),'',time()-3600,'/'); //銷毀保存在客戶端的session id
header('Location:login.php'); //登出後跳轉到後台首頁
?>