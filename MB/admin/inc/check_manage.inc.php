<?php
//驗證f_module 等等輸入
if(empty($_POST['name']) || mb_strlen($_POST['name'])>32)
{
    skip("manage_add.php","error","管理員名稱不能為空值或大於32字元");
}
if(empty($_POST['password']) || mb_strlen($_POST['password'])<6)
{
    skip("manage_add.php","error","管理員密碼不能為空值或小於6字元");
}
$_POST=escape($link,$_POST); //如有特殊字元轉譯
$query="select * from fm_manage WHERE name='{$_POST['name']}'";
$result=execute($link,$query);
if(mysqli_num_rows($result))
{
    skip("manage_add.php","error","此管理員名稱已存在!");
}
if(!isset($_POST['level']))
{
    $_POST['level']=1;
}
elseif ($_POST['level']!='1')
{
    $_POST['level']=1;
}
elseif($_POST['level']!='2')
{
    $_POST['level']=2;
}
else
{
    $_POST['level']=1;
}
?>