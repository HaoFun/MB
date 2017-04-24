<?php
include_once 'inc/config.inc.php';
include_once 'inc/mysql.inc.php';
include_once 'inc/tool.inc.php';
$title_name['title']="Gentry鞋靴櫃_首頁";
$css['css']=array('style/index.css','style/public.css');
$link=connect();
$member_id=is_login($link); //header.inc.php內需要判斷是否有登入

?>
<?php
include 'inc/header.inc.php';
?>
<div id="hot" class="auto">
    <div class="title">熱門動態</div>
    <ul class="newlist">
        <li><a href="style/title.jpg" target="_blank">鞋靴櫃!Demo......</a></li>
    </ul>
    <div style="clear:both;"></div>
</div>
<?PHP
$query="select * from fm_f_module ORDER BY sort desc";
$result_f=execute($link,$query);
while($data_f=mysqli_fetch_assoc($result_f)) //循環輸出父版
{
?>
<div class="box auto">
    <div class="title">
        <a href="list_f.php?id=<?PHP echo $data_f['id']?>" class="f_name_color"><?PHP echo $data_f['module_name'] ?></a> <!--透過點擊跳轉list_f.php?id=-->
    </div>
    <div class="classList">
        <?PHP
            $query="select * from fm_s_module WHERE f_module_id={$data_f['id']} ORDER BY sort desc";
            $result_s=execute($link,$query);
            if(mysqli_num_rows($result_s))
            {
                while($data_s=mysqli_fetch_assoc($result_s))  //循環輸出子版
                {
                    $query="select count(*) from fm_content where module_id={$data_s['id']} and time > CURDATE()"; //查詢今日新帖
                    $count_today=num($link,$query);
                    $query="select count(*) from fm_content where module_id={$data_s['id']}";  //查詢所有帖
                    $count_all=num($link,$query);
$html=<<<START
                    <div class="childBox new">
                        <h2><a href="list_s.php?id={$data_s['id']}">{$data_s['module_name']}</a> <span>(今日新帖 {$count_today})</span></h2>
                        文章數：{$count_all}<br>
                    </div>
START;
                    echo $html;
                }
            }
            else
            {
                echo "<div style='padding:10px 0;'>暫無子版內容</div>";
            }
        ?>
        <div style="clear:both;"></div>
    </div>
</div>
<?PHP
}
?>

<?PHP
include 'inc/footer.inc.php';
?>
