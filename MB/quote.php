<?php
include_once 'inc/config.inc.php';
include_once 'inc/mysql.inc.php';
include_once 'inc/tool.inc.php';
$title_name['title']="Gentry鞋靴櫃_帖子回文";
$css['css']=array('style/public.css','style/publish.css');
$link=connect();
if(!$member_id=is_login($link)) //判斷是否有登入
{
    skip('login.php','error','請登入!再回文!');
}

if(!isset($_GET['id']) || !is_numeric($_GET['id']))
{
    skip($_SERVER['REQUEST_URI'],'error','回覆帖子參數錯誤!');
}
$query="select fc.id,fc.title,fc.time,fm.name from fm_content fc,fm_member fm WHERE  fc.id={$_GET['id']} and fc.member_id = fm.id ORDER BY fc.time DESC";
$result_content=execute($link,$query);
if(mysqli_num_rows($result_content)!=1)
{
    skip('index.php','error','您要引言回覆的帖子不存在!');
}

if(!isset($_GET['reply_id']) || !is_numeric($_GET['reply_id']))
{
    skip('index.php','error','您要引言回覆的帖子不存在!');
}
$query="select fr.content,fm.name from fm_reply fr,fm_member fm WHERE fr.id={$_GET['reply_id']} and fr.content_id={$_GET['id']} and fr.member_id=fm.id";
$result_reply=execute($link,$query);
if(mysqli_num_rows($result_reply)!=1)
{
    skip('index.php','error','您要引言回覆的帖子不存在!');
}
$data_reply=mysqli_fetch_assoc($result_reply);
$data_reply['content']=nl2br($data_reply['content']);   //要引言的內容過濾一下!

$data_content=mysqli_fetch_assoc($result_content);
$data_content['title']=htmlspecialchars($data_content['title']); //htmlspecialchars將HTML標籤轉義

$query="select count(*) from fm_reply WHERE content_id={$_GET['id']} and id<={$_GET['reply_id']}";//計算回文的樓數，透過兩個條件查詢樓，在這一條回覆之前(包括這一條在內)的所有回覆的數量，就能得知現在是幾樓了
$floor=num($link,$query);

if(isset($_POST['submit']))
{
    include 'inc/check_reply.php';
    $_POST=escape($link,$_POST);
    $_POST['content']=nl2br($_POST['content']);
    $query="insert into fm_reply(content_id,quote_id,content,time,member_id) VALUES ({$_GET['id']},{$_GET['reply_id']},'{$_POST['content']}',NOW() + interval 8 HOUR,{$member_id})";
    execute($link,$query);
    if(mysqli_affected_rows($link)==1)
    {
        skip("show.php?id={$_GET['id']}",'ok','回覆帖子成功!');
    }
    else
    {
        skip($_SERVER['REQUEST_URI'],'error','回覆帖子失敗!請再試一次!');
    }
}
?>

<?php
include 'inc/header.inc.php';
?>
<div id="position" class="auto">
    <a href="index.php">首頁</a> &gt; 回文帖子 <?PHP echo $data_content['title']?>
</div>
<div id="publish">
    <div><?PHP echo $data_content['name']?>: <?PHP echo $data_content['title']?></div>
    <div class="quote">
        <p class="title">引言<?PHP echo $floor; ?>樓 <?PHP echo $data_reply['name']?> 發文的: </p>
        <?PHP echo $data_reply['content']; ?>
    </div>
    <form method="post">
        <textarea name="content" id="content" rows="10" cols="80"></textarea>
        <script>
            CKFinder.setupCKEditor();
            CKEDITOR.replace( 'content', {
                //輸入客製化條件
                //設定內容編輯器寬度
            });
        </script>
        <input class="reply" type="reset" name="reset" value="清空" />
        <input class="reply" type="submit" name="submit" value="回文" />
        <div style="clear:both;"></div>
    </form>
</div>

<?PHP
include 'inc/footer.inc.php';
?>
