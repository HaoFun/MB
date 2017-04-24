<?php
include_once '../inc/config.inc.php';
include_once '../inc/mysql.inc.php';
include_once '../inc/tool.inc.php';
$title_name['title']="Gentry鞋靴櫃_管理員修改";
$css['css']=array('style/public.css');
$link=connect();
include_once 'inc/is_manage_login.inc.php';  //驗證後台管理員是否登入、驗證後台管理者權限
$query="select * from fm_manage WHERE id={$_GET['id']}"; //通過GET方式獲取要修改的id
$result=execute($link,$query);
if(!isset($_GET['id']) || !is_numeric($_GET['id']))
{
    skip("manage.php","error","管理員ID名稱錯誤");
}
if(!mysqli_num_rows($result))  //驗證查詢該id是否存在，不存在則跳轉
{
    skip("manage.php","error","管理員名稱不存在");
}
if(isset($_POST['submit']))
{
    if(empty($_POST['password']) || mb_strlen($_POST['password'])<6)
    {
        skip("manage_update.php","error","管理員密碼不能為空值或小於6字元");
    }
    $_POST=escape($link,$_POST); //如有特殊字元轉譯
    $result=execute($link,$query);
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
    $query="select * from fm_manage where id={$_GET['id']}";
    $result_password=execute($link,$query);
    if($data_password=mysqli_fetch_assoc($result_password)) //判斷輸入的原密碼是否正確
    {
       if(md5($_POST['old_password'])!=$data_password['password'])
       {
           skip("manage.php","error","管理員原密碼錯誤");
       }
    }
    else
    {
        skip("manage.php","error","管理員原密碼錯誤");
    }
    $query="update fm_manage set password=md5('{$_POST['password']}'),manage_level={$_POST['manage_level']} WHERE id={$_GET['id']}";
    execute($link,$query);
    if(mysqli_affected_rows($link)==1)
    {
        skip("manage.php","ok","修改成功!");
    }
    else
    {
        skip("manage.php","error","修改失敗!");
    }
}
$query="select * from fm_manage WHERE id={$_GET['id']}";
$result=execute($link,$query);
$data=mysqli_fetch_assoc($result);
?>

<?PHP
include 'inc/header.inc.php';
?>
<div id="main">
    <div class="title">管理員-<?PHP echo $data['name']; ?></div>
    <form method="post">
        <table class="au">
            <tr>
                <td>請輸入原密碼</td>
                <td><input name="old_password" value="" type="text" autocomplete="off" /></td>
            </tr>
            <tr>
                <td>修改密碼</td>
                <td><input name="password" value="" type="password" autocomplete="off" /></td>
            </tr>
            <tr>
                <td>修改權限</td>
                <td>
                    <select name="manage_level">
                        <option value="1">一般管理員</option>
                        <option value="2">ROOT管理員</option>
                    </select>
                </td>
                <td>

                </td>
            </tr>
        </table>
        <input class="btn" type="submit" name="submit" value="修改確認" />
    </form>
</div>

<?PHP
include 'inc/footer.inc.php';
?>

