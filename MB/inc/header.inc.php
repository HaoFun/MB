<?PHP
//查詢網站標題 關鍵字 描述等等
$query="select * from fm_info where id =1";
$result_info=execute($link,$query);
$data_info=mysqli_fetch_assoc($result_info);
?>
<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="utf-8" />
    <title><?PHP echo $title_name['title']; ?>_<?PHP echo $data_info['title']?></title>
    <meta name="keywords" content="<?PHP echo $data_info['keywords']?>" />
    <meta name="description" content="<?PHP echo $data_info['description']?>" />
    <link href="../style/kapico.ico" rel="SHORTCUT ICON">
    <script src="ckeditor/ckeditor.js"></script>
    <script src="ckfinder/ckfinder.js"></script>
    <?PHP
    foreach ($css['css'] as $value)
    {
        echo "<link rel='stylesheet' type='text/css' href='{$value}' />";
    }
    ?>
    <script>
        function processData()   //CJEditor   CKEDITOR.instances.content.getData()用來抓取HTML編輯器內的內容
        {
            // getting data
            var data = CKEDITOR.instances.content.getData()
            form.submit();
        }
    </script>
</head>
<body>
<div class="header_wrap">
    <div id="header" class="auto">
        <div class="logo">　Gentry鞋靴櫃</div>
        <div class="nav">
            <a class="hover" href="index.php">首頁</a>
            <a href="http://www.gentry.com.tw/" target="_blank">紳士時尚</a>
            <a href="https://www.meermin.es/" target="_blank">Meermin</a>
        </div>
        <div class="search">
            <form action="search.php" method="get">
                <input class="keyword" type="text" name="keyword" placeholder="搜搜文" value="<?PHP if(@isset($_GET['keyword'])){echo $_GET['keyword'];} //將搜尋的關鍵字設為默認值?>"/>
                <input class="submit" type="submit" value="" />
            </form>
        </div>
        <div class="login">
            <?php
            if(isset($member_id) && $member_id) //判斷$member_id是否存在及$member_id是否為true
            {
$login_check=<<<START
            <a href="member.php?id=$member_id">歡迎  {$_COOKIE['login']['name']}&nbsp;</a>
            <a href="logout.php">登出</a>
START;
                echo $login_check;
            }
            else
            {
$login_check=<<<START
            <a href="login.php">登入</a>&nbsp;
            <a href="register.php">註冊</a>
START;
                echo $login_check;
            }
            ?>
        </div>
    </div>
</div>
<div style="margin-top:55px;"></div>
<?PHP

?>