<?php
include_once '../inc/config.inc.php';
if(!isset($_GET['message']) || !isset($_GET['url']) || !isset($_GET['return_url']))
{
    exit();//判斷如message or url or return_url不存在後續不再執行
}
?>
<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="utf-8" />
    <title>確認頁面</title>
    <meta name="keywords" content="確認頁面" />
    <meta name="description" content="確認頁面" />
    <link rel="stylesheet" type="text/css" href="style/remind.css" />
</head>
<body>
<div class="notice"><span class="pic ask"></span><?PHP echo $_GET['message']; //urlencode編碼的數據，透過$_GET後，會自動解碼?> <a href="<?PHP echo $_GET['url'];?>">確認</a>    <a href="<?PHP echo $_GET['return_url']; ?>">取消</a></div>
</body>
</html>
