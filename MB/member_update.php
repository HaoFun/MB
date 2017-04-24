<?php
include_once 'inc/config.inc.php';
include_once 'inc/mysql.inc.php';
include_once 'inc/tool.inc.php';
$title_name['title']="Gentry鞋靴櫃_會員密碼修改";
$css['css']=array('style/public.css','style/register.css');
$link=connect();
if(!$member_id=is_login($link)) //判斷是否有登入
{
    skip('login.php','error','請登入!');
}
$query="select * from fm_member WHERE id=$member_id";
$result_member=execute($link,$query);
if(mysqli_num_rows($result_member)!=1) //判斷會員是否存在
{
    skip('index.php','error','會員ID不存在!');
}
$data_member=mysqli_fetch_assoc($result_member);
if(isset($_POST['submit']))
{
    if(empty($_POST['old_password']) || empty($_POST['password1']) || empty($_POST['password2']) || mb_strlen($_POST['old_password'])<6 ||
        mb_strlen($_POST['password1'])<6 || mb_strlen($_POST['password2'])<6)
    {
        skip("member_update.php","error","密碼錯誤!密碼不能為空值或小於6字元");
    }
    $query="select * from fm_member where id={$member_id}";
    $result_old_password=execute($link,$query);
    if($data_old_password=mysqli_fetch_assoc($result_old_password)) //判斷輸入的原密碼是否正確
    {
        if(md5($_POST['old_password'])!=$data_old_password['password'])
        {
            skip("member_update.php","error","原密碼輸入錯誤");
        }
    }
    else
    {
        skip("member_update.php","error","原密碼輸入錯誤");
    }
    if($_POST['password1']!=$_POST['password2'])
    {
        skip("member_update.php","error","兩次密碼輸出不相同!");
    }
    $_POST=escape($link,$_POST); //如有特殊字元轉譯
    $query="update fm_member set password=md5('{$_POST['password1']}') WHERE id={$member_id}";
    execute($link,$query);
    if(mysqli_affected_rows($link)==1)
    {
        skip("member_update.php","ok","修改成功!");
    }
    else
    {
        skip("member_update.php","error","修改失敗!");
    }
}
?>

<?PHP
include 'inc/header.inc.php';
?>
<div id="register" class="auto">
    <h2>登入會員為-<?PHP echo $data_member['name']; ?></h2>
    <form method="post">
        <label>請輸入原密碼：<input type="password"  name="old_password" autocomplete="off"/><span></span></label>
        <label>請輸入更改的密碼：<input type="password" name="password1" autocomplete="off"/><span></span></label>
        <label>請再輸一次密碼：<input type="password" name="password2" autocomplete="off"/><span></span></label>
        <input class="btn" type="submit" name="submit" value="更改確認" />
    </form>
</div>

<?PHP
include 'inc/footer.inc.php';
?>

