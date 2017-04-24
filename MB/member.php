<?PHP
include_once 'inc/config.inc.php';
include_once 'inc/mysql.inc.php';
include_once 'inc/tool.inc.php';
$css['css']=array('style/list.css','style/public.css');
$link=connect();
$member_id=is_login($link); //header.inc.php內需要判斷是否有登入
$is_manage_login=is_manage_login($link); //判斷後台管理者是否有登入
if(!isset($_GET['id']) || !is_numeric($_GET['id'])) //判斷傳過來的ID值格式是否正確
{
    skip('index.php','error','會員ID錯誤!');
}
$query="select * from fm_member WHERE id={$_GET['id']}";
$result_member=execute($link,$query);
if(mysqli_num_rows($result_member)!=1) //判斷會員是否存在
{
    skip('index.php','error','會員ID不存在!');
}
$data_member=mysqli_fetch_assoc($result_member);

$query="select count(*) from fm_content WHERE member_id={$_GET['id']}";//查詢總帖子數量
$count_all=num($link,$query);

$title_name['title']="Gentry鞋靴櫃st_會員_".$data_member['name'];
?>

<?php
include 'inc/header.inc.php';
?>

<div id="position" class="auto">
    <a href="index.php">首頁</a> &gt; <?PHP echo $data_member['name'];?>
</div>
<div id="main" class="auto">
    <div id="left">
        <ul class="postsList">
            <?PHP
            //查詢所以該使用者發布的文章，並根據時機大到小排列
            $query="select fm_content.title,fm_content.id,fm_content.times,fm_content.member_id,fm_content.time,fm_member.name,fm_member.photo,fm_content.content 
            from fm_content,fm_member where 
            fm_content.member_id={$_GET['id']}
            and fm_content.member_id=fm_member.id ORDER BY fm_content.time DESC";
            $result_content=execute($link,$query);
            //var_dump($result_content);
            while ($data_content=mysqli_fetch_assoc($result_content)) //當有一筆資料就輸出li一次
            {
                $data_content['title']=htmlspecialchars($data_content['title']);
                $data_content['content']=nl2br(htmlspecialchars($data_content['content']));

                $query="select time from fm_reply where content_id={$data_content['id']} ORDER BY id desc limit 1"; //查詢最後回覆帖子的時間
                $result_last_reply=execute($link,$query);
                if(mysqli_num_rows($result_last_reply)==0)
                {
                    $last_time=$data_content['time'];  //$last_time 最後回覆帖子時間!
                }
                else
                {
                    $data_last_reply=mysqli_fetch_assoc($result_last_reply);
                    $last_time=$data_last_reply['time'];   //$last_time 最後回覆帖子時間!
                }
                $query="select count(*) from fm_reply WHERE content_id={$data_content['id']}";  //$total_reply 查詢全部回覆帖子的數量!
                $total_reply=num($link,$query);
            ?>
            <li>
                <div class="smallPic">
                        <img width="50" height="50" src="<?PHP if($data_content['photo']!=''){echo $data_content['photo'];}else {echo 'style/coge.jpg';}?>" />
                </div>
                <div class="subject">
                    <div class="titleWrap"><h2><a href="show.php?id=<?PHP echo $data_content['id']?>"><?PHP echo $data_content['title'];?></a></h2></div>
                    <p>
                        發文：<?PHP echo $data_content['name']; ?>&nbsp;&nbsp;最後造訪:<?PHP echo $last_time; ?><br>
                        <?PHP
                        if(check_user($member_id,$data_content['member_id'],$is_manage_login)) //is_manage_login 後台管理者
                        {
                            $return_url=urlencode($_SERVER['REQUEST_URI']);  //當前頁面位置
                            $url=urlencode("content_delete.php?id={$data_content['id']}&return_url=$return_url");//urlencode 將?id....的編碼
                            $message="真的要刪除內容---{$data_content['title']}嗎?";
                            $delete_url="confirm.php?url={$url}&return_url={$return_url}&message={$message}"; //將url、eturn_url、message透過點選刪除傳送出去
                            echo "<a style='color: #ff322e' href='content_update.php?id={$data_content['id']}&return_url={$return_url}'>編輯</a> <a style='color: #ff322e' href='{$delete_url}'>刪除</a>";
                        }
                        ?>&nbsp;&nbsp;&nbsp;
                    </p>
                </div>
                <div class="count">
                    <p>
                        回覆<br /><span><?php echo $total_reply;?></span>
                    </p>
                    <p>
                        瀏覽<br/><span><?php echo $data_content['times']; ?></span>
                    </p>
                </div>
                <div style="clear:both;"></div>
            </li>
            <?PHP
            }
            ?>
        </ul>
    </div>
    <div id="right">
        <div class="member_big">
            <dl>
                <dt>
                    <img width="170" height="170" src="<?PHP if($data_member['photo']!=''){echo $data_member['photo'];}else {echo 'style/coge.jpg';}?>" />
                </dt>
                <dd class="name"><?PHP echo $data_member['name'];?></dd>
                <dd>帖子總數：<?PHP echo $count_all;?></dd>
                <?PHP
                if(check_user($member_id,$data_member['id']))
                {
                ?>
                    <dd><a  href='member_photp_update.php'>修改頭像</a> | <a  href="member_update.php">修改密碼</a></dd>
                <?PHP
                }
                ?>
            </dl>
            <div style="clear:both;"></div>
        </div>
    </div>
    <div style="clear:both;"></div>
</div>
<?PHP
include 'inc/footer.inc.php';
?>
