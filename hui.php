<?php
header('Content-type:text/html;charset=utf-8');
date_default_timezone_set('Asia/Shanghai');
$appkey =array('26358','26364','26363','26390','26391') ;
$sign = array('fa9cfa89713d1d613e201c0d02849bd4','c86a73d8a68ef289f8c751583ab2ff92','0cd65d05ae108617d2967e70fc191443','1aa901e8b4b203a8876f398bc6181e29','19182c4b234cee42a8376b2797c18cc1');
$t = date('i',time());
$n = floor($t/12);

$u_str = "http://api.k780.com/?app=finance.rate&scur=USD&tcur=CNY&appkey=".$appkey[$n]."&sign=".$sign[$n];

$url = $u_str;

$params = array(
      "stock" => "",//股票名称
);
$paramstring = http_build_query($params);

$ch = curl_init();
curl_setopt( $ch , CURLOPT_URL , $url);
curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch,CURLOPT_HEADER,0);
$response = curl_exec( $ch );

$arr=split(',',$response);
$arr1 = array();

for($i=2;$i<sizeof($arr);$i++){
  $arr1[$i-2] = $arr[$i]; 
}

$r_arr = array();

for($i=0;$i<sizeof($arr1);$i++){
  if($i==sizeof($arr1)-1)
  {
    $t_arr=split(':',$arr1[$i]);
    $str=str_replace('"','',$t_arr[0]);
   
    $sec = split('"',$t_arr[3]);
    $str1=str_replace('"','',$t_arr[1].':'.$t_arr[2].':'.$sec[0]);
    $r_arr[$str] = $str1;
  }else{
    $t_arr=split(':',$arr1[$i]);
    $str=str_replace('"','',$t_arr[0]);
    $str1=str_replace('"','',$t_arr[1]);
    $r_arr[$str] = $str1;
  }
  
}
$fp = fopen("data.txt", "r");
//echo fgets($fp);
$fstr = split(',', fgets($fp));
fclose($fp);
$r_arr['today'] = $fstr[0];
$r_arr['yeday'] = $fstr[1];
$fp1 = fopen("data1.txt", "w");
if($fp1)
{
  $hour = (int)date('H',time());
  $minute = date('i',time());
  $n = 60*(int)$hour+(int)$minute;
  $t = date('H:i:s',time());
  $r_str = $n.','.$t.','.$r_arr['rate'];
  $flag=fwrite($fp1,$r_str);
  //fwrite($fp1,"\r\n");
  if(!$flag) 
  { 
    echo "写入文件失败<br>"; 
  }  
}
fclose($fp1);
echo json_encode($r_arr);

?>  