<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 

define('IN_ADMIN', true);
//接口
if(@$_GET['c']){
	$M_MODULE='admin';
	if(@$_GET['m'])$M_MODULE=$_GET['m'];
	if(@$_GET['n']){
		@define('M_NAME', $_GET['n']);
		@define('M_MODULE', $M_MODULE);
		@define('M_CLASS', $_GET['c']);
		@define('M_ACTION', $_GET['a']);
		require_once '../app/app/entrance.php';
	}
	else{
		define('M_NAME', '');
		define('M_MODULE', $M_MODULE);
		define('M_CLASS', $_GET['c']);
		define('M_ACTION', $_GET['a']);
		require_once '../app/system/entrance.php';
	}
}
//结束
$admin_index=TRUE;
require_once 'login/login_check.php';
if($action=="renameadmin"){
	$adminfile=$url_array[count($url_array)-2];
	if($met_adminfile!=""&&$met_adminfile!=$adminfile){
		$oldname='../'.$adminfile;
		$newname='../'.$met_adminfile;
		if(rename($oldname,$newname)){
			echo "<script type='text/javascript'> alert('{$lang_authTip11}'); document.write('{$lang_authTip12}'); top.location.href='{$newname}'; </script>";
			die();
		}else{
			echo "<script type='text/javascript'> alert('{$lang_adminwenjian}'); top.location.reload(); </script>";
			die();
		}
	}
}
if(file_exists('./update/patch.php')){
	require_once './update/patch.php';
}
$authinfo = $db->get_one("SELECT * FROM $met_otherinfo where id=1");
$appaddok=$db->get_one("SELECT * FROM $met_app where name!=''");
$appaddok=$appaddok?1:0;
$appaddok=$met_apptime==0?0:$appaddok;
$css_url="templates/".$met_skin."/css";
$img_url="templates/".$met_skin."/images";
if($metinfo_mobile){
Header("Location: mobile/index.php");
exit;
}else{
include template('index');
}
footer();
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>