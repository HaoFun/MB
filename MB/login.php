<?PHP
include_once 'inc/config.inc.php';
include_once 'inc/mysql.inc.php';
include_once 'inc/tool.inc.php';
$title_name['title']="Gentry鞋靴櫃_登入";
$css['css']=array('style/public.css','style/register.css');
$link=connect();
$member_id=is_login($link);
if($member_id)
{
    skip('index.php','error','已經登入了!請勿重複登入!');
}
if(isset($_POST['submit']))
{
    include "inc/check_login.inc.php";
    $_POST=escape($link,$_POST);
    $query="select * from fm_member where name='{$_POST['name']}' and password=md5('{$_POST['password']}')";
    $result=execute($link,$query);
    if(mysqli_num_rows($result)==1)
    {
        setcookie("login[name]",$_POST['name'],time()+$_POST['time']);
        setcookie("login[password]",md5($_POST['password']),time()+$_POST['time']); //將用戶登入訊息設為cookie內，密碼md5()
        skip('index.php','ok','登入成功!');
    }
    else
    {
        skip('login.php','error','登入失敗，請重新登入!');
    }
}
?>

<?php
include_once 'inc/header.inc.php';
?>
<div id="register" class="auto">
    <h2>歡迎登入</h2>
    <form method="post">
        <label>用戶帳號：<input type="text"  name="name" autocomplete="off" /><span>請輸入帳號</span></label>
        <label>密碼：<input type="password" name="password" autocomplete="off"/><span>請輸入密碼</span></label>
        <label>驗證碼：<input name="vcode" type="text" autocomplete="off" /><span>請輸入驗證碼</span></label>
        <img class="vcode" src="show_code.php" />
        <label class="label_time">自動登入：
            <select name="time" class="time">
                <option value="3600">一小時</option>
                <option value="86400">一天</option>
                <option value="604800">一週</option>
                <option value="2592000">一個月</option>
            </select>
        </label>
        <div style="clear:both;"></div>
        <input class="btn" type="reset" name="reset" value="清除" />
        <input class="btn_check" type="submit" name="submit" value="確定登入" />
    </form>
</div>
<?PHP
include_once 'inc/footer.inc.php';
?>