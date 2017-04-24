<?php
if(empty($_POST['module_id']) || !is_numeric($_POST['module_id']))
{
    skip('publish.php','error','選擇版ID錯誤!');
}
$query="select * from fm_s_module WHERE id={$_POST['module_id']}";
$result=execute($link,$query);
if(mysqli_num_rows($result)!=1)
{
    skip('publish.php','error','請選擇一個討論版!');
}
if(empty($_POST['title']) || mb_strlen($_POST['title'])>255)
{
    skip('publish.php','error','標題錯誤!');
}
?>