<?PHP
include_once '../inc/config.inc.php';
include_once '../inc/mysql.inc.php';
include_once '../inc/tool.inc.php';
$link=connect();
include_once 'inc/is_manage_login.inc.php';  //驗證後台管理員是否登入、驗證後台管理者權限
if(isset($_POST['submit']))
{
    $check_flag='add';
    include 'inc/check_f_module.inc.php';  //驗證傳入f_module值是否正確
    $query="insert into fm_f_module(module_name,sort) VALUES('{$_POST['module_name']}','{$_POST['sort']}')";//插入mysql
    execute($link,$query);
    if(mysqli_affected_rows($link)==1)
    {
        skip("f_module.php","ok","新增成功!!");
    }
    else
    {
        skip("f_module_add.php","error","新增失敗!!");
    }
}
$title_name['title']="Gentry鞋靴櫃_父版新增";
$css['css']=array('style/public.css');
?>
<?PHP
include 'inc/header.inc.php';
?>
<div id="main">
    <div class="title">新增父版</div>
    <form method="post">
    <table class="au">
        <tr>
            <td>新增版名稱</td>
            <td><input name="module_name" type="text" autocomplete="off" /></td>
            <td>

            </td>
        </tr>
        <tr>
            <td>排序</td>
            <td><input name="sort" value="0" type="text" autocomplete="off" /></td>
            <td>

            </td>
        </tr>
    </table>
    <input class="btn" type="submit" name="submit" value="確認" />
    </form>
</div>
<?PHP
include 'inc/footer.inc.php';
?>
