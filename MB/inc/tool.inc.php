<?php
//skip()確認頁面
function skip($url,$pic,$message)
{
$html=<<<START
<!DOCTYPE html>
<html lang="zh-TW">
<head>
<meta charset="utf-8" />
<meta http-equiv="refresh" content="1;URL={$url}" />
<title>跳轉頁面</title>
<link rel="stylesheet" type="text/css" href="style/remind.css" />
</head>
<body>
<div class="notice"><span class="pic {$pic}"></span>{$message} <a href="{$url}">正在跳轉中</a></div>
</body>
</html>
START;
echo $html;
exit();
}

function is_login($link) //驗證前台用戶是否登入
{
    if(isset($_COOKIE['login']['name']) && isset($_COOKIE['login']['password']))
    {
        $query="select * from fm_member where name='{$_COOKIE['login']['name']}' and password ='{$_COOKIE['login']['password']}'";
        $result=execute($link,$query);
        if(mysqli_num_rows($result)==1)
        {
            $data=mysqli_fetch_assoc($result);
            return $data['id'];
        }
        else
        {
            return false;
        }
    }
    else
    {
       return false;
    }
}

function check_user($member_id,$content_member_id,$is_manage_login='') //判斷使用者
{
    if($member_id==$content_member_id  || $is_manage_login)
    {
        return true;
    }
    else
    {
        return false;
    }
}

function is_manage_login($link) //驗證後台用戶是否登入
{
    if(isset($_SESSION['manage']['name']) && isset($_SESSION['manage']['password']))
    {
        $query="select * from fm_manage where name='{$_SESSION['manage']['name']}' and password ='{$_SESSION['manage']['password']}'";
        $result=execute($link,$query);
        if(mysqli_num_rows($result)==1)
        {
            $data=mysqli_fetch_assoc($result);
            return $data['id'];
        }
        else
        {
            return false;
        }
    }
    else
    {
        return false;
    }
}
?>