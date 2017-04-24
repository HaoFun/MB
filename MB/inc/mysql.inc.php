<?PHP
//mysql資料庫連接
function connect($host=DB_HOST,$user=DB_USER,$password=DB_PASSWORD,$database=DB_DATABASE,$port=DB_PORT)
{
    $link=@mysqli_connect($host,$user,$password,$database,$port);  //@將錯誤屏蔽掉
    if(mysqli_connect_errno()) //返回上一次連接錯誤的錯誤代碼
    {
        exit(mysqli_connect_error()); //如果有錯誤，輸出錯誤訊息
    }
    mysqli_set_charset($link,'utf8'); //設置編碼
    return $link;
}

//執行一條SQL語句查詢，返回結果或布林值   mysqli_query()  執行資料庫查詢
function execute($link,$query)
{
    $result=mysqli_query($link,$query);
    if(mysqli_errno($link))
    {
        exit(mysqli_error($link));
    }
    return $result;
}

//執行一個SQL語句查詢，只返回布林值   mysqli_real_query()
function execute_bool($link,$query)
{
    $bool=mysqli_real_query($link,$query);
    if(mysqli_errno($link))
    {
        exit(mysqli_error($link));
    }
    return $bool;
}

/*
 *以下為使用範例，如有錯誤會將錯誤訊息報錯
$arr_sqls=array
(
    'select * from fm_father_module',
    'select * from fm_father_module',
    'select * from fm_father_module',
    'select * from fm_father_module1'
);
var_dump(execute_multi($link,$arr_sqls,$error));
var_dump($error);
*/
//一次執行多條SQL語句  mysqli_multi_query()

function execute_multi($link,$arr_sqls,&$error)  //&$error 同樣的值會受影響
{
    $sqls=implode(';',$arr_sqls).';';   //implode(';',$arr_sqls).';'; 為在數組中 元素之間放置; 以及最後結尾再補上一個;
    if(mysqli_multi_query($link,$sqls)){   //mysqli_multi_query() 執行一個或多個資料庫查詢
        $data=array();
        $i=0;//計數
        do
        {
            if($result=mysqli_store_result($link))    //返回最後一個查詢的結果
            {
                $data[$i]=mysqli_fetch_all($result);  //從結果集中取得所有作為關聯數組、或數字數組、或者兩者都有
                mysqli_free_result($result);          //mysqli_free_result() 釋放結果內存
            }
            else
            {
                $data[$i]=null;
            }
            $i++;
            if(!mysqli_more_results($link)) break;   //mysqli_more_results() 檢查一個多查詢是否有更多的結果
        }
        while (mysqli_next_result($link));          //為mysqli_multi_query() 準備下一個結果集
        if($i==count($arr_sqls))
        {
            return $data;
        }
        else
        {
            $error="SQL語句執行錯誤：<br />&nbsp;數組下標為{$i}的語句:{$arr_sqls[$i]}執行錯誤<br />&nbsp;錯誤原因：".mysqli_error($link);
            return false;
        }
    }
    else
    {
        $error='執行錯誤!請檢察語句是否正確<br />錯誤原因：'.mysqli_error($link);
        return false;
    }
}

//獲取紀錄   mysqli_fetch_row()
function num($link,$sql_count)
{
    $result=execute($link,$sql_count);  //先查詢資料
    $count=mysqli_fetch_row($result);   //再將查詢的資料 mysqli_fetch_row() 返回一個數字數組
    return $count[0];                   //返回數量$count[0]
}

//資料入庫之前進行轉譯，確認資料能夠順利入庫，使用遞歸
function escape($link,$data)
{
    if(is_string($data))
    {
        return mysqli_real_escape_string($link,$data);
    }
    if(is_array($data))
    {
        foreach ($data as $key=>$value)
        {
            $data[$key]=escape($link,$value);
        }
    }
    return $data;
}

//關閉與資料庫的連接     mysqli_close()
function close($link)
{
    mysqli_close($link);
}
?>