<?php
//驗證s_module 等等輸入
if(!is_numeric($_POST['f_module_id']))
{
    skip("s_module_add.php","error","尚未選擇所屬父版");
}
$query="select * from fm_f_module WHERE id={$_POST['f_module_id']}";
$result=execute($link,$query);
if(mysqli_num_rows($result)==0)
{
    skip("s_module_add.php","error","選擇所屬父版不存在");//驗證父版ID是否存在
}
if(empty($_POST['module_name']) || mb_strlen($_POST['module_name'])>60)
{
    skip("s_module_add.php","error","新增版名稱錯誤或大於60字元");
}
$_POST=escape($link,$_POST);//如有特殊字元轉譯
switch ($check_flag)
{
    case 'add':
        $query="select * from fm_s_module WHERE module_name='{$_POST['module_name']}'";
        $result=execute($link,$query);
        if(mysqli_num_rows($result))
        {
            skip("s_module_add.php","error","新增版的名稱已經存在了，名稱不能重複");
        }
        break;
    case 'update':
        $query="select * from fm_s_module WHERE module_name='{$_POST['module_name']} and id!={$_GET['id']} '";
        $result=execute($link,$query);
        if(mysqli_num_rows($result))
        {
            skip("s_module.php","error","修改後版的名稱已經存在了，不能重複名稱");
        }
        break;
    default:
        skip("s_module.php","error","{$check_flag}操作錯誤");
}
if(mb_strlen($_POST['info'])>255)
{
    skip("s_module_add.php","error","版簡介內容不能大於255字元");
}
if(!is_numeric($_POST['sort']))
{
    skip("s_module_add.php","error","排序只支援數字格式");
}
?>