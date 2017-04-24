<?PHP
include_once '../inc/config.inc.php';
include_once '../inc/mysql.inc.php';
include_once '../inc/tool.inc.php';
$link=connect();
include_once 'inc/is_manage_login.inc.php';  //驗證後台管理員是否登入、驗證後台管理者權限
if(isset($_POST['submit']))
{
    include 'inc/check_manage.inc.php';  //驗證傳入值是否正確
    $query="insert into fm_manage(name,password,create_time,manage_level) VALUES ('{$_POST['name']}',md5('{$_POST['password']}'),NOW() + interval 8 HOUR ,{$_POST['manage_level']})";
    execute($link,$query);
    if(mysqli_affected_rows($link)==1)
    {
        skip("manage.php","ok","新增成功!!");
    }
    else
    {
        skip("manage.php","error","新增失敗!!");
    }
}
$title_name['title']="Gentry鞋靴櫃_管理員新增";
$css['css']=array('style/public.css');
?>
<?PHP
include 'inc/header.inc.php';
?>
<div id="main">
    <div class="title">新增管理員</div>
    <form method="post">
        <table class="au">
            <tr>
                <td>管理員名稱</td>
                <td><input name="name" type="text" autocomplete="off" /></td>
            </tr>
            <tr>
                <td>密碼</td>
                <td><input name="password"  type="password" autocomplete="off" /></td>
                <td>
                    密碼不能少於六位
                </td>
            </tr>
            <tr>
                <td>權限等級</td>
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
        <input class="btn" type="submit" name="submit" value="確認" />
    </form>
</div>
<?PHP
include 'inc/footer.inc.php';
?>
