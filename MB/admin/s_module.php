<?PHP
include_once '../inc/config.inc.php';
include_once '../inc/mysql.inc.php';
include_once '../inc/tool.inc.php';
$link=connect();
include_once 'inc/is_manage_login.inc.php';  //驗證後台管理員是否登入、驗證後台管理者權限
$title_name['title']="Gentry鞋靴櫃_子版列表";
$css['css']=array('style/public.css');
if(isset($_POST['submit']))
{
    foreach ($_POST['sort'] as $key => $value)
    {
        if(!is_numeric($value) || !is_numeric($key))
        {
            skip("f_module.php","error","順序只能為數字格式");
        }
        $query[]="update fm_s_module set sort={$value} where id={$key}";//將sort的query語句設為陣列型態
    }
    if(execute_multi($link,$query,$error))//透過mysqli_multi_query多條查詢
    {
        skip("s_module.php","ok","修改成功");
    }
    else
    {
        skip("s_module.php","error",$error);  //如有錯誤則將錯誤提示出來!
    }
}
?>

<?PHP
include 'inc/header.inc.php';
?>
    <div id="main">
        <div class="title">子版列表</div>
        <form method="post">
        <table class="list">
            <tr>
                <th>排序</th>
                <th>版列表</th>
                <th>所屬父版</th>
                <th>版主</th>
                <th>操作</th>
            </tr>
            <?PHP
            $query="select fsm.id,fsm.module_name,ffm.module_name as f_module_name,fsm.member_id,fsm.sort from fm_s_module as fsm,fm_f_module as ffm WHERE fsm.f_module_id=ffm.id ORDER BY ffm.id";
            $result=execute($link,$query);
            while ($data=mysqli_fetch_assoc($result))
            {
                $url=urlencode("s_module_delete.php?id={$data['id']}");//urlencode 將?id....的編碼
                $return_url=urlencode($_SERVER['REQUEST_URI']);  //當前頁面位置
                $message="真的要刪除內容---{$data['module_name']}嗎?";
                $delete_url="confirm.php?url={$url}&return_url={$return_url}&message={$message}"; //將url、eturn_url、message透過點選刪除傳送出去
$html=<<<START
                <tr>
                    <td><input class="sort" type="text" name="sort[{$data['id']}]" value={$data['sort']}></td>
                    <td>{$data['module_name']}</td>   <!--子版名稱-->
                    <td>{$data['f_module_name']}</td> <!--父版名稱-->
                    <td>{$data['member_id']}</td>
                    <td><a href="../list_s.php?id={$data['id']}">[訪問]</a>&nbsp;&nbsp;<a href="s_module_update.php?id={$data['id']}">[修改]</a>&nbsp;&nbsp;<a href={$delete_url}>[刪除]</a></td>
                </tr>
START;
                echo $html;
            }
            ?>
        </table>
        <input class="btn" type="submit" name="submit" value="順序修改" />
        </form>
    </div>
<?PHP
include 'inc/footer.inc.php';
?>