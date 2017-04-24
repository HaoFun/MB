<?php
//驗證f_module 等等輸入
if(empty($_POST['module_name']) || mb_strlen($_POST['module_name'])>60)
{
    skip("f_module_add.php","error","新增版名稱錯誤或大於60字元");
}
if(!is_numeric($_POST['sort']))
{
    skip("f_module_add.php","error","排序只支援數字格式");
}
$_POST=escape($link,$_POST); //如有特殊字元轉譯
switch ($check_flag)
{
    case 'add':
        $query="select * from fm_f_module WHERE module_name='{$_POST['module_name']}'";
        $result=execute($link,$query);
        if(mysqli_num_rows($result))
        {
            skip("f_module_add.php","error","新增版的名稱已經存在了，名稱不能重複");
        }
        break;
    case 'update':
        $query="select * from fm_f_module WHERE module_name='{$_POST['module_name']} and id!={$_GET['id']} '";
        $result=execute($link,$query);
        if(mysqli_num_rows($result))
        {
            skip("f_module.php","error","修改後版的名稱已經存在了，不能重複名稱");
        }
        break;
    default:
        skip("f_module.php","error","{$check_flag}操作錯誤");
}
?>