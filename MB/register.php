<?php
include_once 'inc/config.inc.php';
include_once 'inc/mysql.inc.php';
include_once 'inc/tool.inc.php';
$title_name['title']="Gentry鞋靴櫃_註冊";
$css['css']=array('style/public.css','style/register.css');
$link=connect();
$member_id=is_login($link);
if($member_id)
{
    skip('index.php','error','已經登入了!請勿重複註冊!');
}

if(isset($_POST['submit']))
{
    include 'inc/check_register.inc.php';
    $query="insert into fm_member(name,password,register_time) VALUES ('{$_POST['name']}',md5('{$_POST['password']}'),NOW() + interval 8 HOUR)";
    execute($link,$query);
    if (mysqli_affected_rows($link)==1)
    {
        setcookie("login[name]",$_POST['name']);
        setcookie("login[password]",md5($_POST['password'])); //將用戶登入訊息設為cookie內，密碼md5()
        skip('index.php','ok','註冊成功!');
    }
    else
    {
        skip('register.php','error','註冊失敗，請重新註冊!');
    }
}
?>

<?PHP
include_once 'inc/header.inc.php';
?>
<div id="register" class="auto">
    <h2>歡迎註冊為 Gentry鞋靴櫃 會員</h2>
    <form method="post">
        <label>用戶帳號：<input type="text"  name="name" autocomplete="off"/><span>用戶名稱不可大於32字</span></label>
        <label>密碼：<input type="password" name="password" autocomplete="off"/><span>密碼不可少於6字</span></label>
        <label>確認密碼：<input type="password" name="confirm_password" autocomplete="off"/><span>請再輸入一次密碼</span></label>
        <label>驗證碼：<input name="vcode" type="text" autocomplete="off" /><span>請輸入驗證碼</span></label>
        <img class="vcode" src="show_code.php" />
        <div style="clear:both;"></div>
        <input class="btn" type="reset" name="reset" value="清除" />
        <input class="btn_check" type="submit" name="submit" value="確定註冊" />

    </form>
</div>
<?PHP
include_once 'inc/footer.inc.php';
?>