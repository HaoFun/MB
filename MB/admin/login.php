<?PHP
include_once '../inc/config.inc.php';
include_once '../inc/mysql.inc.php';
include_once '../inc/tool.inc.php';
$link=connect();
if(is_manage_login($link))   //如後台重複登入，則跳轉到index.php
{
    if(@!$_SERVER['HTTP_REFERER'])
    {
        $_SERVER['HTTP_REFERER']='index.php';
    }
    skip($_SERVER['HTTP_REFERER'],'error','已經登入，請勿重複登入');
}

$title_name['title']="Gentry鞋靴櫃_管理員登入";
$css['css']=array('style/public.css','style/login.css');
if(isset($_POST['submit']))
{
    include_once 'inc/check_login.inc.php';
    $_POST=escape($link,$_POST);
    $query="select * from fm_manage WHERE name='{$_POST['name']}' and password=md5('{$_POST['password']}')";
    $result=execute($link,$query);
    if(mysqli_num_rows($result)==1)
    {
        $data_manage=mysqli_fetch_assoc($result);
        $_SESSION['manage']['name']=$data_manage['name'];
        $_SESSION['manage']['password']=$data_manage['password'];
        $_SESSION['manage']['id']=$data_manage['id'];
        $_SESSION['manage']['manage_level']=$data_manage['manage_level'];
        skip('index.php','ok','登入成功!');
    }
    else
    {
        skip('login.php','error','登入失敗!');
    }
}
?>

<?PHP
include 'inc/header.inc.php';
?>
<div id="main">
    <div class="title">管理員登入</div>
    <form method="post">
        <label>用戶名：<input class="text" type="text" name="name" /></label>
        <label>密　碼：<input class="text" type="password" name="password" /></label>
        <label>驗證碼：<input class="text" type="text" name="vcode" /></label>
        <label><img class="vcode" src="../show_code.php" /></label>
        <label><input class="submit" type="submit" name="submit" value="登入" /></label>
    </form>
    <div style="clear:both;"></div>
</div>


<?PHP
include 'inc/footer.inc.php';
?>