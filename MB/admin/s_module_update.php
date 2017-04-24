<?PHP
include_once '../inc/config.inc.php';
include_once '../inc/mysql.inc.php';
include_once '../inc/tool.inc.php';
$title_name['title']="Gentry鞋靴櫃_子版修改";
$css['css']=array('style/public.css');
$link=connect();
include_once 'inc/is_manage_login.inc.php';  //驗證後台管理員是否登入、驗證後台管理者權限
if(!isset($_GET['id']) || !is_numeric($_GET['id']))
{
    skip("s_module.php","error","版ID名稱錯誤");
}
$query="select * from fm_s_module WHERE id={$_GET['id']}"; //通過GET方式獲取要修改的id
$result=execute($link,$query);
if(!mysqli_num_rows($result))  //驗證查詢該id是否存在，不存在則跳轉
{
    skip("s_module.php","error","版名稱不存在");
}
$data=mysqli_fetch_assoc($result);
if(isset($_POST['submit']))
{
    $check_flag='update';
    include 'inc/check_s_module.inc.php';  //驗證修改後傳入f_module值是否正確
    //update fm_s_module set f_module_id={$_POST['f_module_id']},module_name='{$_POST['module_name']}',info='{$_POST['info']},member_id={$POST['member_id']},sort={$_POST['sort']} where id={$_GET['id']}'
    $query="update fm_s_module set f_module_id={$_POST['f_module_id']},module_name='{$_POST['module_name']}',info='{$_POST['info']}',member_id={$_POST['member_id']},sort={$_POST['sort']} where id={$_GET['id']}";
    execute($link,$query);
    if(mysqli_affected_rows($link)==1)
    {
        skip("s_module.php","ok","修改成功!");
    }
    else
    {
        skip("s_module.php","error","修改失敗!");
    }
}
?>

<?PHP
include 'inc/header.inc.php';
?>
<div id="main">
    <div class="title">修改子版-<?PHP echo $data['module_name']; ?></div>
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
                            if($data_f_name['id']==$data['f_module_id'])//判斷修改的子版所屬父版ID，將此父版ID設為selected，再將其餘父版設為其他option選項
                            {
                                echo "<option selected='selected' value='{$data_f_name['id']}'>{$data_f_name['module_name']}</option>";
                            }
                            else
                            {
                                echo "<option value='{$data_f_name['id']}'>{$data_f_name['module_name']}</option>";
                            }
                        }
                        ?>
                    </select>
                </td>
                <td>

                </td>
            </tr>
            <tr>
                <td>子版名稱</td>
                <td><input name="module_name" value="<?PHP echo $data['module_name']; ?>" type="text" autocomplete="off"/></td>
                <td>

                </td>
            </tr>
            <tr>
                <td>版簡介</td>
                <td>
                    <textarea name="info" ><?PHP echo $data['info']; ?></textarea>
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

