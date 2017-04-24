<?php
include_once '../inc/config.inc.php';
include_once '../inc/mysql.inc.php';
include_once '../inc/tool.inc.php';
$title_name['title']="Gentry鞋靴櫃_父版修改";
$css['css']=array('style/public.css');
$link=connect();
include_once 'inc/is_manage_login.inc.php';  //驗證後台管理員是否登入、驗證後台管理者權限
$query="select * from fm_f_module WHERE id={$_GET['id']}"; //通過GET方式獲取要修改的id
$result=execute($link,$query);
if(!isset($_GET['id']) || !is_numeric($_GET['id']))
{
    skip("f_module.php","error","版ID名稱錯誤");
}
if(!mysqli_num_rows($result))  //驗證查詢該id是否存在，不存在則跳轉
{
    skip("f_module.php","error","版名稱不存在");
}
if(isset($_POST['submit']))
{
    $check_flag='update';
    include 'inc/check_f_module.inc.php';  //驗證修改後傳入f_module值是否正確
    $query="update fm_f_module set module_name='{$_POST['module_name']}',sort={$_POST['sort']} WHERE id={$_GET['id']}";
    execute($link,$query);
    if(mysqli_affected_rows($link)==1)
    {
        skip("f_module.php","ok","修改成功!");
    }
    else
    {
        skip("f_module.php","error","修改失敗!");
    }
}
$data=mysqli_fetch_assoc($result);
?>

<?PHP
include 'inc/header.inc.php';
?>
<div id="main">
    <div class="title">修改父版-<?PHP echo $data['module_name']; ?></div>
    <form method="post">
        <table class="au">
            <tr>
                <td>修改版名稱</td>
                <td><input name="module_name" value="<?PHP echo $data['module_name']; ?>" type="text" autocomplete="off" /></td>
                <td>

                </td>
            </tr>
            <tr>
                <td>排序</td>
                <td><input name="sort" value="<?PHP echo $data['sort']; ?>" type="text" autocomplete="off" /></td>
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

