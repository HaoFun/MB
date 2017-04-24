<?PHP
function upload($save_path,$custom_upload_max_filesize,$key,$type=array('jpg','jpeg','png','gif','bmp'))  //$custom_upload_max_filesize 圖片大小上限
{
    $return_data=array();
    $phpini_bytes=20971520; //20MB

    $custom_unit=strtoupper(substr($custom_upload_max_filesize,-1)); //自訂上傳文件限制的大小的單位
    $custom_number=substr($custom_upload_max_filesize,0,-1);         //自訂上傳文件限制的大小
    $custom_multiple=get_multiple($custom_unit);                     //轉換成bytes
    $custom_bytes=$custom_number*$custom_multiple;
    /*
    bytes
    k
    m
    g
     */
    if($custom_bytes>$phpini_bytes)
    {
        $return_data['error']='自訂上傳文件的大小不可大於PHP設定!';
        $return_data['return']=false;
        return $return_data;
    }
    $arr_errors=array
    (
        1=>'上傳文件超過PHP.INI中upload_max_filesize大小',
        2=>'上傳文件超過HTML表單中max_file_size大小',
        3=>'文件只有部分被上傳',
        4=>'沒有文件被上傳',
        6=>'找不到臨時資料夾',
        7=>'文件寫入失敗'
    );
    if(!isset($_FILES[$key]['error']))   //判斷有沒有$_FILES傳進來
    {
        $return_data['error']='$_FILES不存在!';
        $return_data['return']=false;
        return $return_data;
    }
    if($_FILES[$key]['error']!=0)    //判斷有沒有$_FILES錯誤
    {
        $return_data['error']=$arr_errors[$_FILES[$key]['error']];
        $return_data['return']=false;
        return $return_data;
    }
    if(!is_uploaded_file($_FILES[$key]['tmp_name']))  //判斷文件是否透過HTML_POST傳入
    {
        $return_data['error']='傳送方式不合法!請重試!';
        $return_data['return']=false;
        return $return_data;
    }
    if($_FILES[$key]['size']>$custom_bytes)
    {
        $return_data['error']='傳入的圖檔太大了!不可超過'.$custom_upload_max_filesize;
        $return_data['return']=false;
        return $return_data;
    }
    $arr_filename=pathinfo($_FILES[$key]['name']); //pathinfo 將$_FILES[$key]['name']分割
    if(!isset($arr_filename['extension'])) //判斷上傳的文件，副檔名是否存在!
    {
        $arr_filename['extension']='';
    }
    if(!in_array($arr_filename['extension'],$type)) //判斷上傳文件的副檔名是否符合圖片格式
    {
        $return_data['error']='傳入的圖檔的副檔名必須是'.implode(',',$type);  //implode將陣列 以,隔開成字串
        $return_data['return']=false;
        return $return_data;
    }
    if(!file_exists($save_path))  //判斷儲存圖片的目錄是否存在
    {
        if(!mkdir($save_path,0777,true))  //mkdir創建目錄    判斷是否創建成功!
        {
            $return_data['error']='創建圖片庫失敗!';
            $return_data['return']=false;
            return $return_data;
        }
    }
    $new_filename=str_replace('.','',uniqid(mt_rand(100000,999999),true)); //圖片檔案重新命名的名稱
    if($arr_filename['extension']!='')
    {
        $new_filename.=".{$arr_filename['extension']}"; //將名稱與副檔名組合
    }
    $save_path=rtrim($save_path,'/').'/'; //這邊有用到rtrim預防忘記加/，故先移除再重新加上/
    if(!move_uploaded_file($_FILES[$key]['tmp_name'],$save_path.$new_filename)) //判斷文件是否移動成功!
    {
        $return_data['error']='臨時移動文件失敗!';
        $return_data['return']=false;
        return $return_data;
    }
    $return_data['save_path']=$save_path.$new_filename;
    $return_data['filename']=$new_filename;
    $return_data['return'] = true;
    return $return_data;
}
function get_multiple($unit)
{
    switch ($unit) //判斷上傳文件的單位!
    {
        case 'K':
            $multiple=1024;
            return $multiple;
        case 'M':
            $multiple=1024*1024;
            return $multiple;
        case 'G':
            $multiple=1024*1024*1024;
            return $multiple;
        default:
            return false;
    }
}
?>
