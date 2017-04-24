<?php
include_once 'inc/config.inc.php';
include_once 'inc/mysql.inc.php';
include_once 'inc/tool.inc.php';
$title_name['title']="Gentry鞋靴櫃_帖子";
$css['css']=array('style/public.css','style/show.css');
$link=connect();
$member_id=is_login($link); //header.inc.php內需要判斷是否有登入

if(!isset($_GET['id']) || !is_numeric($_GET['id']))
{
    skip('index.php','error','帖子參數錯誤!');
}
$query="select fc.id,fc.module_id,fc.title,fc.content,fc.time,fc.member_id,fc.times,fm.name,fm.photo from fm_content fc,fm_member fm WHERE  fc.id={$_GET['id']} and fc.member_id = fm.id ORDER BY id ASC";
$result_content=execute($link,$query);
if(mysqli_num_rows($result_content)!=1)
{
    skip('index.php','error','帖子參數錯誤!');
}

$query="update fm_content set times=times+1 where id={$_GET['id']}"; //這兩行如點擊帖子則人氣會+1
execute($link,$query);

$data_content=mysqli_fetch_assoc($result_content);
$data_content['times']=$data_content['times']+1;  //因查詢順序問題，times無法即時更新到最新，故這邊先+1 與資料庫值會一樣
$data_content['title']=htmlspecialchars($data_content['title']); //htmlspecialchars將HTML標籤轉義
//$data_content['content']=htmlspecialchars($data_content['content']);
//$data_content['content']=nl2br($data_content['content']); //nl2br 將字串有換行的加上<br>
$query="select * from fm_s_module WHERE id={$data_content['module_id']}";
$result_s_module=execute($link,$query);
$data_s_module=mysqli_fetch_assoc($result_s_module);

$query="select * from fm_f_module WHERE id={$data_s_module['f_module_id']}";
$result_f_module=execute($link,$query);
$data_f_module=mysqli_fetch_assoc($result_f_module);

$query="select count(*) from fm_reply WHERE content_id={$_GET['id']}"; //回文帖子數量
$count_reply=num($link,$query);
?>

<?php
include 'inc/header.inc.php';
?>
    <div id="position" class="auto">
        <a href="index.php">首頁</a> &gt; <a href="list_f.php?id=<?PHP echo $data_f_module['id']?>"><?PHP echo $data_f_module['module_name']?></a> &gt; <a href="list_s.php?id=<?PHP echo $data_s_module['id']?>"><?PHP echo $data_s_module['module_name'];?></a> &gt;<a href="show.php?id=<?PHP echo $_GET['id']; ?>"> <?PHP echo $data_content['title'];?></a>
    </div>
    <div id="main" class="auto">
        <div class="wrapContent">
            <div class="left">
                <div class="face">
                    <a href="member.php?id=<?PHP
                    echo $data_content['member_id']; ?>">
                        <img width="130" height="130" src="<?PHP if($data_content['photo']!=''){echo $data_content['photo'];}else {echo 'style/coge.jpg';}?>" />
                    </a>
                </div>
                <div class="name">
                    <a href=""><?PHP echo $data_content['name']?></a>
                </div>
            </div>
            <div class="right">
                <div class="title">
                    <h2><?PHP echo $data_content['title'];?></h2>
                    <span>人氣：<?PHP echo $data_content['times'];?>&nbsp;|&nbsp;回覆：
                        <?PHP
                        $query="select count(*) from fm_reply WHERE content_id={$_GET['id']}";//查詢回覆的數量
                        $total_reply=num($link,$query);
                        echo $total_reply;
                        ?>
                    </span>
                    <div style="clear:both;"></div>
                </div>
                <div class="pubdate">
                    <span class="date"><?PHP echo $data_content['time']?></span>
                    <span class="floor">樓主大大</span>
                </div>
                <div class="content">
                    <?PHP echo $data_content['content']?>
                </div>
            </div>
            <div style="clear:both;"></div>
        </div>
        <?PHP
        $query="select fm.name,fm.photo,fr.member_id,fr.time,fr.id,fr.content,fr.quote_id from fm_reply fr,fm_member fm WHERE fr.member_id=fm.id and fr.content_id={$_GET['id']}  order by fr.id asc ";
        $result_reply=execute($link,$query);
        $i=1;//計算回文的樓數
        while($data_reply=mysqli_fetch_assoc($result_reply))
        {
        //$data_reply['content'] = $data_reply['content']; //因導入CKEditor 這邊改為不透過nl2br與htmlspecialchars 過濾 //nl2br(htmlspecialchars($data_reply['content']));

        ?>
        <div class="wrapContent">
            <div class="left">
                <div class="face">
                    <a href="member.php?id=<?PHP echo $data_reply['member_id'];?>">
                        <img width="130" height="130" src="<?PHP if ($data_reply['photo'] != '') {
                            echo $data_reply['photo'];
                        } else {
                            echo 'style/coge.jpg';
                        } ?>"/>
                    </a>
                </div>
                <div class="name">
                    <a href=""><?PHP echo $data_reply['name'] ?></a>
                </div>
            </div>
            <div class="right">

                <div class="pubdate">
                    <span class="date">回文時間：<?PHP echo $data_reply['time'] ?></span>
                    <span class="floor"><?PHP echo $floor = $i++ ?>樓&nbsp;|&nbsp;<a
                            href="quote.php?id=<?PHP echo $_GET['id'] ?>&reply_id=<?PHP echo $data_reply['id'] ?>">引言</a></span>
                </div>
                <div class="content">
                    <?PHP
                    if ($data_reply['quote_id'])//確認是否有引言他人的留言
                    {
                        $query="select count(*) from fm_reply WHERE content_id={$_GET['id']} and id<={$data_reply['quote_id']}";//計算回文的樓數，透過兩個條件查詢樓，在這一條回覆之前(包括這一條在內)的所有回覆的數量，就能得知現在是幾樓了
                        $floor=num($link,$query);
                        $query="select fr.content,fm.name from fm_reply fr,fm_member fm WHERE fr.id={$data_reply['quote_id']} and fr.content_id={$_GET['id']} and fr.member_id=fm.id";
                        $result_quote=execute($link,$query);
                        $data_quote=mysqli_fetch_assoc($result_quote);
                        $data_quote['content']=nl2br($data_quote['content']);   //要引言的內容過濾一下! 因導入CKEditor 這邊改為不透過htmlspecialchars 過濾
                    ?>
                    <div class="quote">
                        <h2>引言<?PHP echo $floor;?>樓 <?PHP echo $data_quote['name'];?>發文的</h2>
                        <?PHP echo $data_quote['content'];?>
                    </div>
                    <?PHP
                    }
                    ?>

                    <?PHP echo $data_reply['content']?>
                </div>
            </div>
            <div style="clear:both;"></div>
        </div>
        <?PHP
        }
        ?>
        <div class="wrap1">
            <button class="btn showreply" onclick="javascript:location.href='reply.php?id=<?PHP echo $_GET['id']?>'">回文</button>
            <div style="clear:both;"></div>
        </div>
    </div>



<?PHP
include 'inc/footer.inc.php';
?>