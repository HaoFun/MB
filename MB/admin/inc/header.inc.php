<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="utf-8" />
    <title><?PHP echo $title_name['title']; ?></title>
    <meta name="keywords" content="後台介面" />
    <meta name="description" content="後台介面" />
    <link href="../style/kapico.ico" rel="SHORTCUT ICON">
    <?PHP
    foreach ($css['css'] as $value)
    {
        echo "<link rel='stylesheet' type='text/css' href='{$value}' />";
    }
    ?>
</head>
<body>
<div id="top">
    <div class="logo">
        管理中心
    </div>
    <ul class="nav">
        <li><a href="http://www.gentry.com.tw/" target="_blank">紳士時尚</a></li>
        <li><a href="https://www.meermin.es/" target="_blank">Meermin</a></li>
    </ul>
    <div class="login_info">
        <a target="_blank" href="../index.php" style="color:#000000;">網站首頁</a>&nbsp;|&nbsp;
        <?PHP
        if(@$_SESSION['manage']['name'])
        {
$LOGIN=<<<START
            <a href="index.php" style="color:#000000;">{$_SESSION['manage']['name']}</a>&nbsp;|&nbsp;
            <a href="logout.php" style="color:#000000;">登出</a>&nbsp;|&nbsp;
START;
            echo $LOGIN;
        }
        else
        {
$LOGIN=<<<START
            <a href="login.php" style="color:#000000;">登入</a>&nbsp;|&nbsp;
START;
            echo $LOGIN;
        }
        ?>

    </div>
</div>
<div id="sidebar">
    <ul>
        <li>
            <div class="small_title">系統</div>
            <ul class="child">
                <li><a <?PHP if(basename($_SERVER['SCRIPT_NAME'])=="index.php"){echo "class='current'";} ?>href="index.php">系統訊息</a></li>
                <li><a <?PHP if(basename($_SERVER['SCRIPT_NAME'])=="manage.php"){echo "class='current'";} ?> href="manage.php">管理員</a></li>
                <li><a <?PHP if(basename($_SERVER['SCRIPT_NAME'])=="manage_add.php"){echo "class='current'";} ?> href="manage_add.php">新增管理員</a></li>
                <?PHP
                if(basename($_SERVER['SCRIPT_NAME'])=='manage_update.php')
                {
                    echo "<li><a class='current'>修改管理員</a></li>"; //當點選修改後，頁面上新增這條li
                }
                ?>
                <li><a <?PHP if(basename($_SERVER['SCRIPT_NAME'])=="web_set.php"){echo "class='current'";} ?>href="web_set.php">網站設置</a></li>
            </ul>
        </li>
        <li><!--  class="current" -->
            <div class="small_title">內容管理</div>
            <ul class="child">
                <li><a <?PHP if(basename($_SERVER['SCRIPT_NAME'])=="f_module.php"){echo "class='current'";} ?> href="f_module.php">父版列表</a></li>
                <li><a <?PHP if(basename($_SERVER['SCRIPT_NAME'])=="f_module_add.php"){echo "class='current'";} ?>href="f_module_add.php">新增父版</a></li>
                <?PHP
                if(basename($_SERVER['SCRIPT_NAME'])=='f_module_update.php')
                {
                    echo "<li><a class='current'>修改父版</a></li>"; //當點選修改後，頁面上新增這條li
                }
                ?>
                <li><a <?PHP if(basename($_SERVER['SCRIPT_NAME'])=="s_module.php"){echo "class='current'";} ?>href="s_module.php">子版列表</a></li>
                <li><a <?PHP if(basename($_SERVER['SCRIPT_NAME'])=="s_module_add.php"){echo "class='current'";} ?>href="s_module_add.php">新增子版</a></li>
                <?PHP
                if(basename($_SERVER['SCRIPT_NAME'])=='s_module_update.php')
                {
                    echo "<li><a class='current'>修改子版</a></li>"; //當點選修改後，頁面上新增這條li
                }
                ?>
                <li><a target="_blank" href="../index.php">帖子管理</a></li>
            </ul>
        </li>
        <li>
            <div class="small_title">用戶管理</div>
            <ul class="child">
                <li><a <?PHP if(basename($_SERVER['SCRIPT_NAME'])=="member_data.php"){echo "class='current'";} ?> href="member_data.php">用戶列表</a></li>
            </ul>
        </li>
    </ul>
</div>
