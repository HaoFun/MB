<?php
include_once 'inc/config.inc.php';
include_once 'inc/mysql.inc.php';
include_once 'inc/tool.inc.php';
$title_name['title']="Gentry鞋靴櫃_發文";
$css['css']=array('style/publish.css','style/public.css');
$link=connect();
if(!$member_id=is_login($link)) //判斷是否有登入
{
    skip('login.php','error','請登入!');
}
if(isset($_POST['submit']))
{
    include "inc/check_publish.inc.php";
    $_POST=escape($link,$_POST);
    $_POST['content']=nl2br($_POST['content']);
    $query="insert into fm_content(module_id,title,content,time,member_id) VALUES({$_POST['module_id']},'{$_POST['title']}','{$_POST['content']}',NOW() + interval 8 HOUR ,{$member_id})";
    execute($link,$query);
    if(mysqli_affected_rows($link)==1)
    {
        skip('index.php','ok','發文成功');
    }
    else
    {
        skip('publish.php','error','發文失敗!請重試!');
    }
}
?>
<?PHP
include 'inc/header.inc.php';
?>

<div id="position" class="auto">
    <a href="index.php">首頁</a> &gt; 發文
</div>
<div id="publish">
    <form method="post">
        <select name="module_id">
            <option value=-1>請選擇一個討論版</option>
                <?php
                    $where='';
                    if(isset($_GET['f_module_id']) && is_numeric($_GET['f_module_id']))
                    {
                       $where="where id={$_GET['f_module_id']}";
                    }
                    $query="select * from fm_f_module {$where} order BY sort desc";
                    $result_f=execute($link,$query);
                    while ($data_f=mysqli_fetch_assoc($result_f)) //輸出父版列表 optgroup
                    {
                        echo "<optgroup label='{$data_f['module_name']}'>";
                        $query="select * from fm_s_module where f_module_id={$data_f['id']} order BY sort desc";
                        $result_s=execute($link,$query);
                        while ($data_s=mysqli_fetch_assoc($result_s)) //輸出子版列表 option
                        {
                            if(isset($_GET['s_module_id']) && $_GET['s_module_id']==$data_s['id'])
                            {
                                echo "<option selected='selected' value='{$data_s['id']}'>{$data_s['module_name']}</option>"; //驗證點擊發文傳過來的ID值，設置該ID option選取
                            }
                            else
                            {
                                echo "<option value='{$data_s['id']}'>{$data_s['module_name']}</option>";
                            }
                            //$data_show[]=array($data_s['id']=>$data_s['module_name']);
                        }
                        //var_dump( $data_show);exit();
                        echo "</optgroup>";
                    }
                ?>
        </select>
        <input class="title" placeholder="請輸入標題" name="title" type="text" autocomplete="off"/>
        <textarea name="content" id="content" rows="10" cols="80"></textarea>
        <script>
            CKFinder.setupCKEditor();
            CKEDITOR.replace( 'content', {
                //輸入客製化條件
                //設定內容編輯器寬度
            });
        </script>
        <input class="reply" type="submit" name="submit" value="發文" onclick = 'processData()'/>
        <div style="clear:both;"></div>
    </form>
</div>

<?PHP
include 'inc/footer.inc.php';
?>
