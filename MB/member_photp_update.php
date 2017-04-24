<?PHP
include_once 'inc/config.inc.php';
include_once 'inc/mysql.inc.php';
include_once 'inc/tool.inc.php';
include_once 'inc/upload.inc.php';
$title_name['title']="Gentry鞋靴櫃_會員管理";
$css['css']=array('style/photo_update.css','style/public.css');
$link=connect();
if(!$member_id=is_login($link)) //判斷是否有登入
{
    skip('login.php','error','請登入!');
}
$query="select * from fm_member WHERE id=$member_id";
$result_member=execute($link,$query);
if(mysqli_num_rows($result_member)!=1) //判斷會員是否存在
{
    skip('index.php','error','會員ID不存在!');
}
$data_member=mysqli_fetch_assoc($result_member);
if(isset($_POST['submit']))
{
    $save_path='uploads'.date('/Y/m/d');  //保存圖片的位置
    $upload=upload($save_path,'5M','photo');
    if($upload['return'])
    {
        $query="update fm_member set photo='{$upload['save_path']}' WHERE id={$member_id}";
        execute($link,$query);
        if(mysqli_affected_rows($link)==1)
        {
            skip("member.php?id={$member_id}",'ok','更新成功!');
        }
        else
        {
            skip($_SERVER['REQUEST_URI'],'error','頭像更換失敗，請重試!');
        }
    }
    else
    {
        skip($_SERVER['REQUEST_URI'],'error',$upload['error']);  //如錯誤則顯示錯誤訊息
    }
}
?>

<?php
include 'inc/header.inc.php';
?>

<div id="main">
    <h2>修改頭像</h2>
    <div class="h3_photo">
        <h3>原來頭像：</h3>
        <img width="200" height="200" src="<?PHP if($data_member['photo']!=''){echo $data_member['photo'];}else {echo 'style/coge.jpg';}?>" />
    </div>
    <div style="clear:both;"></div>
    <div style="margin:15px 0 0 0;">
        <form method="post" enctype="multipart/form-data">
            <input class="file" type="file" name="photo" /><br /><br />
            <input class="submit" type="submit" name="submit" value="保存" />
        </form>
    </div>
    <div style="clear:both;"></div>
</div>


<?PHP
include 'inc/footer.inc.php';
?>