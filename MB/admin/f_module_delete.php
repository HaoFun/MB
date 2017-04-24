<?php
include_once '../inc/config.inc.php';
include_once '../inc/mysql.inc.php';
include_once '../inc/tool.inc.php';
$link=connect();
include_once 'inc/is_manage_login.inc.php';  //驗證後台管理員是否登入、驗證後台管理者權限
if(!isset($_GET['id'])  || !is_numeric($_GET['id'])) //判斷ID是否存在  //判斷ID是否為數字或數字字串
{
    skip("f_module.php","error","ID錯誤!!");
}
$query="select * from fm_s_module WHERE f_module_id={$_GET['id']}";
$result=execute($link,$query);
if(mysqli_num_rows($result))
{
    skip("f_module.php","error","該版下存在子版，請先刪除子版!");
}
$query="delete from fm_f_module where id={$_GET['id']}";
execute($link,$query);
if(mysqli_affected_rows($link)==1) //判斷上一次操作刪除是否有變動成功
{
    skip("f_module.php","ok","刪除成功!!");
}
else
{
    skip("f_module.php","error","刪除失敗!!");
}
?>