<?PHP
include_once '../inc/config.inc.php';
include_once '../inc/mysql.inc.php';
include_once '../inc/tool.inc.php';
$title_name['title']="Gentry鞋靴櫃_子版新增";
$css['css']=array('style/public.css');
$link=connect();
include_once 'inc/is_manage_login.inc.php';  //驗證後台管理員是否登入、驗證後台管理者權限
if(isset($_POST['submit']))
{
    $check_flag='add';
    include 'inc/check_s_module.inc.php';  //驗證傳入s_module值是否正確
    $query="insert into fm_s_module(f_module_id,module_name,info,member_id,sort) VALUES ({$_POST['f_module_id']},'{$_POST['module_name']}','{$_POST['info']}','{$_POST['member_id']}',{$_POST['sort']})";
    execute($link,$query);
    if(mysqli_affected_rows($link)==1)
    {
        skip("s_module.php","ok","新增成功!!");
    }
    else
    {
        skip("s_module_add.php","error","新增失敗!!");
    }
}
?>

<?PHP
include 'inc/header.inc.php';
?>
<div id="main">
    <div class="title">新增子版</div>
    <form method="post">
        <table class="au">
            <tr>
                <td>所屬父版名稱</td>
                <td>
                    <select name="f_module_id">
                        <option value="0">------請選擇一個父版------</option>
                        <?PHP
                        $query="select * from fm_f_module";
                        $result=execute($link,$query);
                        while($data_f_name=mysqli_fetch_assoc($result))
                        {
                            echo "<option value='{$data_f_name['id']}'>{$data_f_name['module_name']}</option>";
                        }
                        ?>
                    </select>
                </td>
                <td>

                </td>
            </tr>
            <tr>
                <td>新增版名稱</td>
                <td><input name="module_name" type="text" autocomplete="off"/></td>
                <td>

                </td>
            </tr>
            <tr>
                <td>版簡介</td>
                <td>
                    <textarea name="info"></textarea>
                </td>
                <td>

                </td>
            </tr>
            <tr>
                <td>版主名稱</td>
                <td>
                    <select name="member_id">
                        <option value="0">------請選擇一個會員------</option>
                        <?PHP
                        $query="select * from fm_member";
                        $result=execute($link,$query);
                        while($data_member_name=mysqli_fetch_assoc($result))
                        {
                        echo "<option value='{$data_member_name['id']}'>{$data_member_name['name']}</option>";
                        }
                        ?>
                    </select>
                </td>
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

