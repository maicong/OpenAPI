<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
header("Content-type: text/html;charset=utf-8");
error_reporting(E_ERROR | E_PARSE);
@set_time_limit(0);
$HeaderTime=time();
define('ROOTPATH', substr(dirname(__FILE__), 0, -7));
PHP_VERSION >= '5.1' && date_default_timezone_set('Asia/Shanghai');
session_cache_limiter('private, must-revalidate'); 
@ini_set('session.auto_start',0); 
if(PHP_VERSION < '4.1.0') {
	$_GET         = &$HTTP_GET_VARS;
	$_POST        = &$HTTP_POST_VARS;
	$_COOKIE      = &$HTTP_COOKIE_VARS;
	$_SERVER      = &$HTTP_SERVER_VARS;
	$_ENV         = &$HTTP_ENV_VARS;
	$_FILES       = &$HTTP_POST_FILES;
}
require_once ROOTPATH.'include/mysql_class.php';
define('MAGIC_QUOTES_GPC', get_magic_quotes_gpc());
isset($_REQUEST['GLOBALS']) && exit('Access Error');
require_once ROOTPATH.'include/global.func.php';
foreach(array('_COOKIE', '_POST', '_GET') as $_request) {
	foreach($$_request as $_key => $_value) {
		$_key{0} != '_' && $$_key = daddslashes($_value,0,0,1);
		$_M['from'][$_key] = daddslashes($_value,0,0,1);
	}
}
$met_cookie=array();
$settings=array();
$db_settings=array();
$db_settings = parse_ini_file(ROOTPATH.'config/config_db.php');
@extract($db_settings);
$db = new dbmysql();
$db->dbconn($con_db_host,$con_db_id,$con_db_pass,$con_db_name);
$query="select * from {$tablepre}config where name='met_tablename' and lang='metinfo'";
$mettable=$db->get_one($query);
$mettables=explode('|',$mettable[value]);
foreach($mettables as $key=>$val){
	$tablename='met_'.$val;	
	$$tablename=$tablepre.$val;
	$_M['table'][$tablename] = $tablepre.$val;
}
require_once ROOTPATH.'include/cache.func.php';
require_once ROOTPATH.'config/config.inc.php';
met_cooike_start();
$metmemberforce==$met_member_force;
if($metmemberforce==$met_member_force){
	change_met_cookie('metinfo_member_name',"force");
	change_met_cookie('metinfo_member_pass',"force");
	change_met_cookie('metinfo_member_type',"256");
	save_met_cookie();
}
$_M['user']['cookie'] = $met_cookie;
if($met_member_use!=0){
	$metinfo_member_id     =(get_met_cookie('metinfo_admin_id')=="")?get_met_cookie('metinfo_member_id'):get_met_cookie('metinfo_admin_id');
	$metinfo_member_name     =(get_met_cookie('metinfo_admin_name')=="")?get_met_cookie('metinfo_member_name'):get_met_cookie('metinfo_admin_name');
	$metinfo_member_pass     =(get_met_cookie('metinfo_admin_pass')=="")?get_met_cookie('metinfo_member_pass'):get_met_cookie('metinfo_admin_pass');
	$metinfo_member_type     =(get_met_cookie('metinfo_admin_type')=="")?get_met_cookie('metinfo_member_type'):'256';
	$metinfo_admin_name      =get_met_cookie('metinfo_admin_name');
	if($metinfo_member_name=='' or  $metinfo_member_pass=='')$metinfo_member_type=0;
}else{
	$metinfo_member_type="256";
}
(!MAGIC_QUOTES_GPC) && $_FILES = daddslashes($_FILES);
$REQUEST_URI  = $_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'];
$t_array = explode(' ',microtime());
$P_S_T	 = $t_array[0] + $t_array[1];
$met_obstart == 1 && function_exists('ob_gzhandler') ? ob_start('ob_gzhandler') :ob_start();
ob_start();
$referer?$forward=$referer:$forward=$_SERVER['HTTP_REFERER'];
$m_now_time     = time();
$m_now_date     = date('Y-m-d H:i:s',$m_now_time);
$m_now_counter  = date('Ymd',$m_now_time);
$m_now_month    = date('Ym',$m_now_time);
$m_now_year     = date('Y',$m_now_time);
$m_user_agent   =  $_SERVER['HTTP_USER_AGENT'];
$m_user_ip = $_SERVER['REMOTE_ADDR'];
$m_user_ip  = preg_match('/^([0-9]{1,3}\.){3}[0-9]{1,3}$/',$m_user_ip) ? $m_user_ip : 'Unknown';
$PHP_SELF = $_SERVER['PHP_SELF'] ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME'];
$mobilesql="";
if(file_exists(ROOTPATH.'include/mobile.php')&&$met_wap&&trim(file_get_contents(ROOTPATH.'include/mobile.php'))!='metinfo'){
require_once ROOTPATH.'include/mobile.php';
}else{
if($index=='index'&&$met_wap)wapjump();
}
require_once ROOTPATH.'include/lang.php';
$index_url=$met_index_url[$lang];
if($met_mobileok&&$met_wap_url){
$index_url=$met_wap_url;
}
$met_chtmtype=".".$met_htmtype;
if($met_webhtm != 0){//判断是否开启静态
	$met_htmtype=($lang==$met_index_type)?".".$met_htmtype:"_".$lang.".".$met_htmtype;
}else{
	$met_htmtype = ".".$met_htmtype;
}
$langmark='lang='.$lang;
switch($met_title_type){
    case 0:
		$webtitle = '';
		break;
    case 1:
		$webtitle = $met_keywords;
		break;
	case 2:
		$webtitle = $met_webname;
		break;
	case 3:
		$webtitle = $met_keywords.'-'.$met_webname;
}
$met_title=$webtitle;
if($index=='index'){
$met_title=$met_webname?($met_keywords?$met_keywords.'-'.$met_webname:$met_webname):$met_keywords;
$met_title=$met_hometitle!=''?$met_hometitle:$met_title;
}

$member_index_url="index.php?lang=".$lang;
$member_register_url="register.php?lang=".$lang;
//接口
if($_M['plugin']['doweb']){
	define('IN_MET', true);
	if(file_exists(ROOTPATH.'app/system/include/class/mysql.class.php')){
		require_once ROOTPATH.'app/system/include/class/mysql.class.php';
		$db_settings = array();
		$db_settings = parse_ini_file(ROOTPATH.'config/config_db.php');
		@extract($db_settings);
		DB::dbconn($con_db_host, $con_db_id, $con_db_pass, $con_db_name);
		foreach($_M['plugin']['doweb'] as $key => $val){
				$applistfile=ROOTPATH.'app/app/'.$val.'/plugin/'.'plugin_'.$val.'.class.php';
				$_M['url']['own'] = $_M['url']['site'].'app/app/'.$val.'/';
				if(file_exists($applistfile)&&!is_dir($applistfile)&&((file_get_contents($applistfile))!='metinfo')){
					require_once $applistfile;
					$newclass=str_replace('.class.php', '', 'plugin_'.$val);
					$newclass=new $newclass;
					call_user_func(array($newclass,  'doweb'));
				}
		}
		$_M['url']['own'] = '';
		DB::close();
	}
}
//结束
if($met_oline!=1){
	$file_site = explode('|',$app_file[1]);
	foreach($file_site as $keyfile=>$valflie){
		if(file_exists(ROOTPATH."$met_adminfile".$valflie)&&!is_dir(ROOTPATH."$met_adminfile".$valflie)&&((file_get_contents(ROOTPATH."$met_adminfile".$valflie))!='metinfo')){require_once ROOTPATH."$met_adminfile".$valflie;}
	}
}
if (!$search && !$action){jump_pseudo();}
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>