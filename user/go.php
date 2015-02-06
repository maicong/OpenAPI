<?php
header("Content-Type:text/html;charset=UTF-8");
require_once ("conn.php");
if( isset($_SERVER['HTTP_REFERER']) ) {
    $url_array = explode('://', $_SERVER['HTTP_REFERER']);
    $url = explode('/', $url_array[1]);
    if($_SERVER['SERVER_NAME'] != $url[0]) {
        exit('Access Denied ! You are not coming from the site !');
    }
} else {
    exit('Access Denied ! ');
}
function check_remote_file_exists($url)
{
$curl = curl_init($url);
curl_setopt($curl, CURLOPT_NOBODY, true);
curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET'); 
$result = curl_exec($curl);
$found = false;
if ($result !== false)
{
$statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
if ($statusCode == 200)
{
$found = true;
}
}
curl_close($curl);
return $found;
}
if(isset($_GET["p"])){
	if ($_GET["p"] == "url"&&isset($_POST["url"])) { 
	$url = htmlspecialchars($_POST['url']);
	$check = preg_match('/^((http|https):\/\/)?(\w(\:\w)?@)?([0-9a-z_-]+\.)*?([a-z0-9-]+\.[a-z]{2,6}(\.[a-z]{2})?(\:[0-9]{2,6})?)((\/[^?#<>\/\\*":]*)+(\?[^#]*)?(#.*)?)?$/i', $url); 
		if($check==0 || check_remote_file_exists($url) == false){
			echo "网址有误";
		}else{
			include_once 'phpQuery.php'; 
			phpQuery::newDocumentFile($url); 
			$title = pq("title")->text();
			echo mb_strimwidth(trim($title),0,24,'...','utf-8');
		}
	}
	if ($_GET["p"] == "qq"&&isset($_POST["qq"])) { 
	$qq = htmlspecialchars($_POST['qq']);
	$str = file_get_contents("http://base.qzone.qq.com/fcg-bin/cgi_get_portrait.fcg?uins=".$qq);
	$c = iconv("gb2312","utf-8//IGNORE",$str);
	$c = mb_convert_encoding($str, "UTF-8","GBK"); 
	$json = mb_substr(trim($c),17,-1, 'utf-8');
	$jsonstr = json_decode($json,true);
	if(!empty($jsonstr[$qq][6])){
		echo $jsonstr[$qq][6];
	}else{
		echo "木有找到";
	}
	}
}
?>