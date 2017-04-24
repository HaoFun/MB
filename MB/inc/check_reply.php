<?php
if(empty($_POST['content']) || mb_strlen($_POST['content'])<5)
{
    skip($_SERVER['REQUEST_URI'],'error','回文內容過短!');
}
?>