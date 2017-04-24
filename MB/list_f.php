<?PHP
include_once 'inc/config.inc.php';
include_once 'inc/mysql.inc.php';
include_once 'inc/tool.inc.php';
$css['css']=array('style/list.css','style/public.css');
$link=connect();
$member_id=is_login($link); //header.inc.php內需要判斷是否有登入
$is_manage_login=is_manage_login($link); //判斷後台管理者是否有登入
if(!isset($_GET['id']) || !is_numeric($_GET['id']))
{
    skip('index.php','error','版ID錯誤!');
}
$query="select * from fm_f_module WHERE id={$_GET['id']}"; //查詢fm_f_module是否有$_GET傳來的ID
$result_f=execute($link,$query);
if(mysqli_num_rows($result_f)==0)
{
    skip('index.php','error','版ID錯誤!');
}
$data_f=mysqli_fetch_assoc($result_f);

$query="select * from fm_s_module WHERE f_module_id={$_GET['id']}";//查詢fm_s_module中f_module_id 等於$_GET
$result_s=execute($link,$query);
$id_s='';//子版的module_id
$s_list='';//子版的列表module_name
while ($data_s=mysqli_fetch_assoc($result_s))
{
    $id_s.=$data_s['id'].",";
    $s_list.="<a style='color: #ff322e' href='list_s.php?id={$data_s['id']}'>{$data_s['module_name']}</a> ";//顯示的子版名稱!
}
$id_s=trim($id_s,',');
if($id_s=='')  //這邊如果$id_s是空，則將0賦給$id_s，不然後面算數量的SQL語句會錯誤!
{
    $id_s="0";
}
$query="select count(*) from fm_content WHERE module_id in({$id_s})";//總帖子數量
$count_all=num($link,$query);

$query="select count(*) from fm_content WHERE module_id in({$id_s}) and time>CURDATE()";//今日新增的數量
$count_today=num($link,$query);
$title_name['title']="Gentry鞋靴櫃_".$data_f['module_name'];
?>

<?php
include 'inc/header.inc.php';
?>
<div id="position" class="auto">
    <a href="index.php">首頁</a> &gt; <a href="list_f.php?id=<?PHP echo $data_f['id']; ?>"><?PHP echo $data_f['module_name']; ?></a>
</div>
<div id="main" class="auto">
    <div id="left">
        <div class="box_wrap">
            <h3><?PHP echo $data_f['module_name']; ?></h3>
            <div class="num">
                今日新帖：<span><?PHP echo$count_today; ?></span>&nbsp;&nbsp;&nbsp;
                帖子：<span><?PHP echo$count_all; ?></span>
                <div class="moderator"> 子版： <?PHP echo $s_list; ?></div>
            </div>
        </div>
        <div style="clear:both;"></div>
        <ul class="postsList">
            <?php
            /*
            select * from fm_content,fm_member,fm_s_module where fm_content.module_id in({$id_s}) and fm_content.member_id=fm_member.id and
            fm_content.module_id=fm_s_module.id
            */

            $query="select fm_content.title,fm_content.id,fm_content.times,fm_content.time,fm_content.member_id,fm_member.name,fm_member.photo,fm_s_module.module_name,fm_s_module.id fmsid 
            from fm_content,fm_member,fm_s_module where 
            fm_content.module_id in({$id_s}) 
            and fm_content.member_id=fm_member.id 
            and fm_content.module_id=fm_s_module.id  ORDER BY fm_content.id DESC ";//照fm_content id從大到小排序
            $result_content=execute($link,$query);
            while ($data_content=mysqli_fetch_assoc($result_content))
            {
                $data_content['title']=htmlspecialchars($data_content['title']);


                $query="select time from fm_reply where content_id={$data_content['id']} ORDER BY id desc limit 1"; //查詢最後回覆帖子的時間
                $result_last_reply=execute($link,$query);
                if(mysqli_num_rows($result_last_reply)==0)
                {
                    $last_time=$data_content['time'];  //$last_time 最後回覆帖子時間!
                }
                else
                {
                    $data_last_reply=mysqli_fetch_assoc($result_last_reply);
                    $last_time=$data_last_reply['time'];  //$last_time 最後回覆帖子時間!
                }
                $query="select count(*) from fm_reply WHERE content_id={$data_content['id']}"; //$total_reply 查詢全部回覆帖子的數量!
                $total_reply=num($link,$query);
            ?>
                <li>
                    <div class="smallPic">
                        <a href="member.php?id=<?PHP echo $data_content['member_id'];?>">
                            <img width="50" height="50" src="<?PHP if($data_content['photo']!=''){echo $data_content['photo'];}else {echo 'style/coge.jpg';}?>">
                        </a>
                    </div>
                    <div class="subject">
                        <div class="titleWrap"><a href="list_s.php?id=<?PHP echo $data_content['fmsid']?>">[<?PHP echo $data_content['module_name']; ?>]</a>&nbsp;&nbsp;<h2><a href="show.php?id=<?PHP echo $data_content['id'];?>"><?PHP echo $data_content['title']; ?></a></h2></div>
                        <p>
                            樓主：<?PHP echo $data_content['name']; ?>&nbsp;&nbsp;&nbsp;&nbsp;最後造訪：<?PHP echo $last_time; ?><br>
                            <?PHP
                            if(check_user($member_id,$data_content['member_id'],$is_manage_login)) //is_manage_login 後台管理者
                            {
                                $return_url=urlencode($_SERVER['REQUEST_URI']);  //當前頁面位置
                                $url=urlencode("content_delete.php?id={$data_content['id']}&return_url=$return_url");//urlencode 將?id....的編碼
                                $message="真的要刪除內容---{$data_content['title']}嗎?";
                                $delete_url="confirm.php?url={$url}&return_url={$return_url}&message={$message}"; //將url、eturn_url、message透過點選刪除傳送出去
                                echo "<a style='color: #000' href='content_update.php?id={$data_content['id']}&return_url={$return_url}'>編輯</a> <a style='color: #000' href='{$delete_url}'>刪除</a>";
                            }
                            ?>
                        </p>
                    </div>
                    <div class="count">
                        <p>
                            回覆<br/><span><?php echo $total_reply;?></span>
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
        <div class="pages_wrap">
            <button class="publish" onclick="javascript:location.href='publish.php?f_module_id=<?PHP echo $_GET['id']; ?>'">發文</button>
            <div style="clear:both;"></div>
        </div>
    </div>
    <div id="right">
        <div class="classList">
            <div class="title">討論版列表</div>
            <ul class="listWrap">
                <?PHP
                $query="select * from fm_f_module ORDER BY sort DESC";
                $result_f_right=execute($link,$query);
                while ($data_f_right=mysqli_fetch_assoc($result_f_right)) //右邊列表父版輸出循環
                {
                ?>
                <li>
                    <h2><a href="list_f.php?id=<?PHP echo $data_f_right['id']?>"><?php echo $data_f_right['module_name'];?></a></h2>
                    <ul>
                        <?PHP
                        $query="select * from fm_s_module WHERE f_module_id={$data_f_right['id']} ORDER BY sort DESC";
                        $result_s_right=execute($link,$query);
                        while ($data_s_right=mysqli_fetch_assoc($result_s_right)) //右邊列表子版輸出循環
                        {
                        ?>
                            <li><h3><a href="list_s.php?id=<?php echo $data_s_right['id'];?>"><?php echo $data_s_right['module_name'];?></a></h3></li>
                        <?php
                        }
                        ?>
                    </ul>
                </li>
                <?php
                }
                ?>
            </ul>
        </div>
    </div>
    <div style="clear:both;"></div>
</div>

<?PHP
include 'inc/footer.inc.php';
?>
