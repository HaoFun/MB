<?php

if(!is_manage_login($link)) //判斷後台管理者是否有登入
{
    header('Location:login.php');
    exit();
}

//管理員登入後如，再新增、移除、修改管理員頁面中，會判斷權限是否符合最高權限
if(basename($_SERVER['SCRIPT_NAME'])=="manage_delete.php" || basename($_SERVER['SCRIPT_NAME'])=="manage_add.php" || basename($_SERVER['SCRIPT_NAME'])=="manage_update.php")
{
    if($_SESSION['manage']['manage_level']!=2)  //判斷後台管理者權限!
    {
        if(!$_SERVER['HTTP_REFERER'])
        {
            $_SERVER['HTTP_REFERER']='index.php';
        }
        skip($_SERVER['HTTP_REFERER'],'error','權限不足!');
    }
}
?>