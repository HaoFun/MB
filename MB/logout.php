<?PHP
include_once 'inc/config.inc.php';
include_once 'inc/mysql.inc.php';
include_once 'inc/tool.inc.php';
$link=connect();
$member_id=is_login($link);
if(!$member_id)
{
    skip('index.php','error','沒有登入，不需要登出!');
}
setcookie("login[name]","",time()-3600);
setcookie("login[password]","",time()-3600);
skip('index.php','ok','登出成功!');
?>
