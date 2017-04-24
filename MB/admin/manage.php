<?PHP
include_once '../inc/config.inc.php';
include_once '../inc/mysql.inc.php';
include_once '../inc/tool.inc.php';
$link=connect();
include_once 'inc/is_manage_login.inc.php';  //驗證後台管理員是否登入、驗證後台管理者權限
$title_name['title']="Gentry鞋靴櫃_管理員列表";
$css['css']=array('style/public.css');
?>

<?PHP
include 'inc/header.inc.php';
?>
    <div id="main">
        <div class="title">管理員列表</div>
            <table class="list">
                <tr>
                    <th>名稱</th>
                    <th>管理權限</th>
                    <th>創建日期</th>
                    <th>修改</th>
                    <th>刪除</th>
                </tr>
                <?PHP
                $query="select * from fm_manage";
                $result=execute($link,$query);
                while ($data=mysqli_fetch_assoc($result))
                {
                    if($data['manage_level']==1)
                    {
                        $data['manage_level']='一般管理員';
                    }
                    elseif ($data['manage_level']==2)
                    {
                        $data['manage_level']='ROOT管理員';
                    }
                    else
                    {
                        $data['manage_level']='一般管理員';
                    }
                    $url=urlencode("manage_delete.php?id={$data['id']}");//urlencode 將?id....的編碼
                    $return_url=urlencode($_SERVER['REQUEST_URI']);  //當前頁面位置
                    $message="真的要刪除內容---{$data['name']}嗎?";
                    $delete_url="confirm.php?url={$url}&return_url={$return_url}&message={$message}"; //將url、eturn_url、message透過點選刪除傳送出去
                    $html=<<<START
                <tr>
                    <td>{$data['name']}</td>
                    <td>{$data['manage_level']}</td>
                    <td>{$data['create_time']}</td>
                    <td><a href="manage_update.php?id={$data['id']}">[修改]</a></td>
                    <td><a href="{$delete_url}">[刪除]</a></td>
                </tr>
START;
                    echo $html;
                }
                ?>
            </table>
    </div>
<?PHP
include 'inc/footer.inc.php';
?>