<?php
include_once 'inc/config.inc.php';
include_once 'inc/mysql.inc.php';
include_once 'inc/tool.inc.php';
$css['css']=array('style/list.css','style/public.css');
$link=connect();
$member_id=is_login($link); //header.inc.php內需要判斷是否有登入
$is_manage_login=is_manage_login($link); //判斷後台管理者是否有登入
$title_name['title']="Gentry鞋靴櫃_搜尋".$data_s['module_name'];
if(!isset($_GET['keyword']))
{
    $_GET['keyword']='';
}
$_GET['keyword']=trim($_GET['keyword']); //去除空格
$_GET['keyword']=escape($link,$_GET['keyword']); //轉義
//依據get進來的條件，查找並計算相對應的筆數
$query="select count(*) from fm_content WHERE title like '%{$_GET['keyword']}%'";
$count_all=num($link,$query);
?>

<?php
include 'inc/header.inc.php';
?>
<div id="position" class="auto">
    <a href="index.php">首頁</a> &gt; 搜尋
</div>
<div id="main" class="auto">
    <div id="left">
        <div class="box_wrap">
            <h3>搜尋的條件為<?PHP echo $_GET['keyword']; ?>　搜尋到<?PHP echo $count_all;?>筆</h3>
        </div>
        <div style="clear:both;"></div>
        <ul class="postsList">
            <?php
            //查找get關鍵字中，符合的文章標題，並查找相對應的ID、NAME、點擊數等等
            $query="select fm_content.title,fm_content.id,fm_content.times,fm_content.member_id,fm_content.time,fm_member.name,fm_member.photo
            from fm_content,fm_member where 
            fm_content.title LIKE '%{$_GET['keyword']}%'
            and fm_content.member_id=fm_member.id ORDER BY fm_content.time DESC";
            $result_content=execute($link,$query);
            while ($data_content=mysqli_fetch_assoc($result_content))
            {
                $data_content['title']=htmlspecialchars($data_content['title']);
                $data_content['title_color']=str_replace($_GET['keyword'],"<span style='color: #ff322e'>{$_GET['keyword']}</span>",$data_content['title']);//將搜尋到的關鍵字加上樣式

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
                        <a href="member.php?id=<?PHP echo $data_content['member_id'];?>">
                            <!--判斷該文章發布者的照片如為空用默認的圖片-->
                            <img width="50" height="50" src="<?PHP if($data_content['photo']!=''){echo $data_content['photo'];}else {echo 'style/coge.jpg';}?>">
                        </a>
                    </div>
                    <div class="subject">
                        <div class="titleWrap">&nbsp;&nbsp;<h2><a href="show.php?id=<?PHP echo $data_content['id'];?>"><?PHP echo $data_content['title_color']; ?></a></h2></div>
                        <p>
                            發文：<?PHP echo $data_content['name']; ?>&nbsp;&nbsp;最後造訪:<?PHP echo $last_time; ?>&nbsp;&nbsp;&nbsp;&nbsp;<br>
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
            <?PHP
            //$query="select * from fm_s_module where id={$_GET['id']}";
            //$result_f_name=execute($link,$query);
            //$data_f_name=mysqli_fetch_assoc($result_f_name);
            ?>
            <div style="clear:both;"></div>
        </div>
    </div>
    <div id="right">
        <div class="classList">
            <div class="title">討論版列表</div>
            <ul class="listWrap">
                <?PHP
                //查找所以父板
                $query="select * from fm_f_module ORDER BY sort DESC";
                $result_f_right=execute($link,$query);
                while ($data_f_right=mysqli_fetch_assoc($result_f_right)) //右邊列表父板輸出循環
                {
                    ?>
                    <li>
                        <h2><a href="list_f.php?id=<?PHP echo $data_f_right['id']?>"><?php echo $data_f_right['module_name'];?></a></h2>
                        <ul>
                            <?PHP
                            //查找所以該父板下的子板
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

