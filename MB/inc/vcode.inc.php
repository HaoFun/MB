<?php
function vcode($width=120,$height=40,$fontSize=26,$countElement=4,$countPixel=500,$countLine=20)
{
//驗證碼  簡易版
    header( "Content-type: image/jpeg" );
    $element=array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
    $string='';
    for($i=0;$i<$countElement;$i++)
    {
        $string.=$element[rand(0,count($element)-1)];
    }
    $img=imagecreatetruecolor($width,$height);//新建一個彩色圖像
    $colorBg=imagecolorallocate($img,rand(200,255),rand(200,255),rand(200,255));//分配顏色
    $colorBorder=imagecolorallocate($img,0,0,0);//分配顏色
    $colorString=imagecolorallocate($img,rand(10,80),rand(10,80),rand(10,80));//分配顏色
    imagefill($img,0,0,$colorBg);//注入顏色
    //imagerectangle($img,0,0,$width-1,$height-1,$colorBorder);   //邊框
    for($i=0;$i<=$countPixel;$i++)
    {
        imagesetpixel($img,rand(0,$width-2),rand(0,$height-2),imagecolorallocate($img,rand(100,200),rand(100,200),rand(100,200)));
    }
    for($i=0;$i<=$countLine;$i++)
    {
        imageline($img, rand(0, $width / 2), rand(0, $height), rand($width / 2, $width), rand(0, $height), imagecolorallocate($img, rand(100, 200), rand(100, 200), rand(100, 200)));
    }
    //imagestring($img,5,0,0,'abcd',$colorString);
    imagettftext($img,$fontSize,rand(-5,5),rand(5,15),rand(30,33),$colorString,'font/msjhbd.ttc',$string); //輸出字元
    imagejpeg($img);
    imagedestroy($img);//釋放資源
    return $string;
}
?>