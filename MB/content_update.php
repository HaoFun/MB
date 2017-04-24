<?PHP
include_once 'inc/config.inc.php';
include_once 'inc/mysql.inc.php';
include_once 'inc/tool.inc.php';
$title_name['title']="Gentry鞋靴櫃_會員管理";
$css['css']=array('style/publish.css','style/public.css');
$link=connect();
$member_id=is_login($link); //header.inc.php內需要判斷是否有登入
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
$query="select * from fm_content where id={$_GET['id']}"; //查詢帖子發布者ID為使用者的帖子
$result_content=execute($link,$query);
if(mysqli_num_rows($result_content)==1)
{
    $data_content=mysqli_fetch_assoc($result_content);
    $data_content['title']=htmlspecialchars($data_content['title']);
    if(check_user($data_content['member_id'],$member_id,$is_manage_login))//確認帖子發文者是否為目前登入者
    {
        if(isset($_POST['submit']))
        {
            include "inc/check_publish.inc.php";   //確認修改帖子的規格
            $_POST=escape($link,$_POST);
            $_POST['content']=nl2br($_POST['content']);
            $query="update fm_content set module_id={$_POST['module_id']},title='{$_POST['title']}',content='{$_POST['content']}',time=NOW() + interval 8 HOUR  WHERE id={$_GET['id']}";
            execute($link,$query);
            if(isset($_GET['return_url']))
            {
                $return_url=$_GET['return_url'];
            }
            else
            {
                $return_url="member.php?id={$member_id}";
            }
            if(mysqli_affected_rows($link)==1)  //判斷SQL是否有變動
            {
                skip($return_url,'ok','修改成功');
            }
            else
            {
                skip($return_url,'error','修改失敗!請重試!');
            }
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

<?php
include 'inc/header.inc.php';
?>

<div id="position" class="auto">
    <a href="index.php">首頁</a> &gt; 帖子修改
</div>
<div id="publish">
    <form method="post">
        <select name="module_id">
            <option value=-1>請選擇一個討論版</option>
            <?php
            $query="select * from fm_f_module order BY sort desc";
            $result_f=execute($link,$query);
            while ($data_f=mysqli_fetch_assoc($result_f)) //輸出父版列表 optgroup
            {
                echo "<optgroup label='{$data_f['module_name']}'>";
                $query="select * from fm_s_module where f_module_id={$data_f['id']} order BY sort desc";
                $result_s=execute($link,$query);
                while ($data_s=mysqli_fetch_assoc($result_s)) //輸出子版列表 option
                {
                    if($data_content['module_id']==$data_s['id'])
                    {
                        echo "<option selected='selected' value='{$data_s['id']}'>{$data_s['module_name']}</option>"; //驗證點擊發文傳過來的ID值，設置該ID option選取
                    }
                    else
                    {
                        echo "<option value='{$data_s['id']}'>{$data_s['module_name']}</option>";
                    }
                }
                echo "</optgroup>";
            }
            ?>
        </select>
        <input class="title" placeholder="請輸入標題" value="<?PHP echo $data_content['title'];?>" name="title" type="text" autocomplete="off"/>
        <textarea name="content" id="content" rows="10" cols="80"><?PHP echo $data_content['content'];?></textarea>
        <script>
            CKFinder.setupCKEditor();
            CKEDITOR.replace( 'content', {
                //輸入客製化條件
                //設定內容編輯器寬度
            });
        </script>
        <input class="publish" type="submit" name="submit" value="修改" />
        <div style="clear:both;"></div>
    </form>
</div>

<?PHP
include 'inc/footer.inc.php';
?>

