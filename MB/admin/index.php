<?PHP
include_once '../inc/config.inc.php';
include_once '../inc/mysql.inc.php';
include_once '../inc/tool.inc.php';
$link=connect();
$title_name['title']="Gentry鞋靴櫃_後台首頁";
$css['css']=array('style/public.css');
include_once 'inc/is_manage_login.inc.php';  //驗證後台管理員是否登入、驗證後台管理者權限

//查詢管理員資訊
$query="select * from fm_manage WHERE id={$_SESSION['manage']['id']}";
$result_manage=execute($link,$query);
$data_manage=mysqli_fetch_assoc($result_manage);
if($data_manage['manage_level']==1)
{
    $data_manage['manage_level']='一般管理員';
}
elseif($data_manage['manage_level']==2)
{
    $data_manage['manage_level']='ROOT管理員';
}
else
{
    $data_manage['manage_level']='';
}

$query="select count(*) from fm_f_module";
$count_f=num($link,$query);

$query="select count(*) from fm_s_module";
$count_s=num($link,$query);

$query="select count(*) from fm_content";
$count_content=num($link,$query);

$query="select count(*) from fm_reply";
$count_reply=num($link,$query);

$query="select count(*) from fm_member";
$count_member=num($link,$query);

$query="select count(*) from fm_manage";
$count_manage=num($link,$query);
?>

<?PHP
include 'inc/header.inc.php';
?>
    <div id="main">
        <div class="title">系統訊息</div>
        <div class="explain">
            <ul>
                <li>您好! <?PHP echo $data_manage['name']?></li>
                <li>權限：<?PHP echo $data_manage['manage_level']?></li>
                <li>帳號創立時間：<?PHP echo $data_manage['create_time']?></li>
            </ul>
        </div>
        <div class="explain">
            <ul >
                <li>父版[<?PHP echo $count_f;?>]　　子版[<?PHP echo $count_s;?>]　　帖子[<?PHP echo $count_content;?>]　　回覆[<?PHP echo $count_reply;?>]　　會員數[<?PHP echo $count_member;?>]　　管理者[<?PHP echo $count_manage;?>]</li>
            </ul>
        </div>
        <div class="explain">
            <ul>
                <li>虛擬主機：<?PHP echo PHP_OS;?> </li>
                <li>主機伺服器： <?PHP echo $_SERVER['SERVER_SOFTWARE'];?></li>
                <li>Mysql版本：<?PHP echo mysqli_get_server_info($link);?></li>
                <li>最大文件上傳：<?PHP echo ini_get('upload_max_filesize');?></li>
                <li>記憶體限制：<?PHP echo ini_get('memory_limit');?></li>
            </ul>
        </div>
    </div>
<?PHP
include 'inc/footer.inc.php';
?>