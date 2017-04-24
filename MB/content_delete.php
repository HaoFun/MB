<?PHP
include_once 'inc/config.inc.php';
include_once 'inc/mysql.inc.php';
include_once 'inc/tool.inc.php';
$title_name['title']="Gentry鞋靴櫃_會員管理";
$css['css']=array('style/list.css','style/public.css');
$link=connect();
$is_manage_login=is_manage_login($link); //判斷後台管理者是否有登入
$member_id=is_login($link);//判斷前台是否有登入
if(!$member_id && !$is_manage_login) //判斷是否有登入
{
    skip('login.php','error','請登入!');
}
if(!isset($_GET['id']) || !is_numeric($_GET['id'])) //判斷傳過來的ID值格式是否正確
{
    skip('index.php','error','帖子ID錯誤!');
}
$query="select member_id from fm_content where id={$_GET['id']}"; //查詢帖子發布者ID為使用者的帖子
$result_content=execute($link,$query);
if(mysqli_num_rows($result_content)==1)
{
    $data_content=mysqli_fetch_assoc($result_content);
    if(check_user($data_content['member_id'],$member_id,$is_manage_login))
    {
        $query="delete from fm_content WHERE id={$_GET['id']}";
        execute($link,$query);
        if(isset($_GET['return_url']))
        {
            $return_url=$_GET['return_url'];
        }
        else
        {
            $return_url="member.php?id={$member_id}";
        }
        if(mysqli_affected_rows($link)==1)
        {
            skip($return_url,'ok','刪除成功!');
        }
        else
        {
            skip($return_url,'刪除失敗，再試一次!');
        }
    }
    else
    {
        skip("member.php?id={$member_id}",'error','這篇文不屬於您發布的，無法刪除!');
    }
}
else
{
    skip("member.php?id={$member_id}",'error','帖子ID錯誤!');
}


?>