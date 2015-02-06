<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
require_once '../login/login_check.php';
$adminfile=$url_array[count($url_array)-2];
if($action=="delete"){
	$filename=$filename=='update'?$filename:'install';
	if($filename=='update')@chmod('../../update/install.lock',0777);
	function deldirs($dir){
	  $dh=opendir($dir);
	  while ($file=readdir($dh)) {
		if($file!="." && $file!="..") {
		  $fullpath=$dir."/".$file;
		  if(!is_dir($fullpath)) {
			  unlink($fullpath);
		  } else {
			  deldir($fullpath);
		  }
		}
	}

	  closedir($dh);
	  if($dir!='../../upload'){
		if(rmdir($dir)) {
		return true;
		} else {
		return false;
		}
		}
	} 
	$dir='../../'.$filename;
	deldirs($dir);
	metsave('../system/safe.php?anyid='.$anyid.'&lang='.$lang);
}
if($action=="modify"){
	if($met_adminfile!=""&&$met_adminfile!=$adminfile){
		$met_adminfile_temp=$met_adminfile;
		$met_adminfile_code=authcode($met_adminfile,'ENCODE', $met_webkeys);
		require_once $depth.'../include/config.php';
		Header("Location: ../index.php?lang=".$lang."&action=renameadmin&adminmodify=1&met_adminfile=".$met_adminfile_temp);
	}else{
		require_once $depth.'../include/config.php';
		metsave('../system/safe.php?anyid='.$anyid.'&lang='.$lang);
	}
}else{
$localurl="http://";
$localurl.=$_SERVER['HTTP_HOST'].$_SERVER["PHP_SELF"];
$localurl_a=explode("/",$localurl);
$localurl_count=count($localurl_a);
$localurl_admin=$localurl_a[$localurl_count-3];
$localurl_admin=$localurl_admin."/system/safe";
$localurl_real=explode($localurl_admin,$localurl);
$localurl=$localurl_real[0];
if(!is_dir('../../install'))$installstyle="display:none;";
if(!is_dir('../../update'))$updatestyle="display:none;";
$met_login_code1[$met_login_code]="checked='checked'";
$met_memberlogin_code1[$met_memberlogin_code]="checked='checked'";
$met_automatic_upgrade1[$met_automatic_upgrade]="checked";


$css_url="../templates/".$met_skin."/css";
$img_url="../templates/".$met_skin."/images";
include template('system/set_safe');
footer();
}
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>