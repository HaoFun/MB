<?PHP
include_once '../inc/config.inc.php';
include_once '../inc/mysql.inc.php';
include_once '../inc/tool.inc.php';
$link=connect();
include_once 'inc/is_manage_login.inc.php';  //驗證後台管理員是否登入、驗證後台管理者權限
$title_name['title']="Gentry鞋靴櫃_用戶列表";
$css['css']=array('style/public.css');
?>

<?PHP
include 'inc/header.inc.php';
?>
    <div id="main">
        <div class="title">用戶列表</div>
        <table class="list">
            <tr>
                <th>名稱</th>
                <th>創建日期</th>
                <th>刪除</th>
                <?PHP
                $query="select * from fm_member ORDER BY id ASC ";
                $member_result=execute($link,$query);
                while ($member_data=mysqli_fetch_assoc($member_result))
                {
                    $url=urlencode("member_delete.php?id={$member_data['id']}");//urlencode 將?id....的編碼
                    $return_url=urlencode($_SERVER['REQUEST_URI']);  //當前頁面位置
                    $message="真的要刪除內容---{$member_data['name']}嗎?";
                    $delete_url="confirm.php?url={$url}&return_url={$return_url}&message={$message}"; //將url、eturn_url、message透過點選刪除傳送出去
$html=<<<START
                <tr>
                    <td>{$member_data['name']}</td>
                    <td>{$member_data['register_time']}</td>
                    <td><a href="{$delete_url}">[刪除]</a></td>
                </tr>
START;
                    echo $html;
                }
                ?>
            </tr>
        </table>
    </div>
<?PHP
include 'inc/footer.inc.php';
?>