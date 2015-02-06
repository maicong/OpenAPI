<?php
header("Content-Type:text/html;charset=utf-8"); 
//数据库链接文件
$dbname = 'FKnDzFOcABdsxhrwKXnZ';
$host = getenv('HTTP_BAE_ENV_ADDR_SQL_IP');
$port = getenv('HTTP_BAE_ENV_ADDR_SQL_PORT');
$user = getenv('HTTP_BAE_ENV_AK');
$pwd = getenv('HTTP_BAE_ENV_SK');
$conn=@mysql_connect("{$host}:{$port}",$user,$pwd,true) or die('数据库连接失败！');
@mysql_select_db($dbname,$conn) or die('没有找到数据库！');
mysql_query("set names 'utf-8'");
require_once('sqlguolv.php');
?>