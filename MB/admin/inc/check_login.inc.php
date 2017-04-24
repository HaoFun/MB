<?php
//驗證f_module 等等輸入
if(empty($_POST['name']) || mb_strlen($_POST['name'])>32)
{
    skip("login.php","error","管理員名稱錯誤");
}
if(empty($_POST['password']) || mb_strlen($_POST['password'])<6)
{
    skip("login.php","error","管理員密碼錯誤");
}
if(strtolower($_POST['vcode'])!=strtolower($_SESSION['vcode']))
{
    skip('login.php','error','驗證碼錯誤!');
}
?>