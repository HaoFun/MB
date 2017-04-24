<?PHP
include_once '../inc/config.inc.php';
include_once '../inc/mysql.inc.php';
include_once '../inc/tool.inc.php';
$title_name['title']="Gentry鞋靴櫃_網站設置";
$css['css']=array('style/public.css');
$link=connect();
include_once 'inc/is_manage_login.inc.php';  //驗證後台管理員是否登入、驗證後台管理者權限
$query="select * from fm_info where id =1";
$result_info=execute($link,$query);
$data_info=mysqli_fetch_assoc($result_info);
if(isset($_POST['submit']))
{
    $_POST=escape($link,$_POST);
    $query="update fm_info set title='{$_POST['web_title_name']}',keywords='{$_POST['web_keyword']}',description='{$_POST['web_info']}' where id=1";
    execute($link,$query);
    if(mysqli_affected_rows($link)==1)
    {
        skip('index.php','ok','設置成功');
    }
    else
    {
        skip('web_set.php','ok','設置失敗');
    }
}
?>

<?PHP
include 'inc/header.inc.php';
?>
<div id="main">
    <div class="title">網站設置</div>
    <form method="post">
        <table class="au">
            <tr>
                <td>網站標題</td>
                <td><input name="web_title_name" type="text" value="<?PHP echo $data_info['title']?>"></td>
            </tr>
            <tr>
                <td>關鍵字</td>
                <td><input name="web_keyword" type="text" autocomplete="off"  value="<?PHP echo $data_info['keywords']?>"/></td>

            </tr>
            <tr>
                <td>網站描述</td>
                <td>
                    <textarea name="web_info"><?PHP echo $data_info['description']?></textarea>
                </td>
            </tr>
        </table>
        <input class="btn" type="submit" name="submit" value="設置" />
    </form>
</div>
<?PHP
include 'inc/footer.inc.php';
?>

