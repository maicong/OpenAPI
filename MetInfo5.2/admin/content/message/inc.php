<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
$depth='../';
require_once $depth.'../login/login_check.php';
require_once ROOTPATH.'public/php/searchhtml.inc.php';
$fnam=$db->get_one("SELECT * FROM $met_column WHERE id='$class1' and lang='$lang'");
$query = "select * from $met_parameter where lang='$lang' and module='7' and type='1'";
$menus=$db->query($query);
while($list = $db->fetch_array($menus)) {
	$fd_paraall[]=$list;
}
$query = "select * from $met_parameter where lang='$lang' and module='7' and type='3'";
$menus=$db->query($query);
while($list = $db->fetch_array($menus)) {
	$fd_paraalls[]=$list;
}
if($action=="modify"){
	$columnid=$fnam['id'];
	if($met_fd_ok){
		$query = "update $met_column SET display = '0' where id ='$columnid' ";
		$db->query($query);
	}else{
		$query = "update $met_column SET display = '1' where id ='$columnid' ";
		$db->query($query);
	}
	require_once $depth.'../include/config.php';
	$htmjs = onepagehtm('message','message').'$|$';
	$htmjs.= classhtm($class1,0,0);
	metsave('../content/message/inc.php?lang='.$lang.'&class1='.$class1.'&anyid='.$anyid,'',$depth,$htmjs);
}else{
	foreach($settings_arr as $key=>$val){
		if($val['columnid']==$fnam['id'])$$val['name']=$val['value'];
	}
	$met_fd_ok1[$met_fd_ok]="checked='checked'";
	$met_fd_email1=($met_fd_email)?"checked":"";
	$met_fd_back1=($met_fd_back)?"checked":"";
	$met_fd_type1=($met_fd_type)?"checked":"";
	$met_fd_sms_back1=($met_fd_sms_back)?"checked":"";
	$met_sms_back1=($met_sms_back)?"checked='checked'":"";
	$m_list = $db->get_one("SELECT * FROM $met_column WHERE module='7' and lang='$lang'");
	$class1 = $m_list['id'];
	$listclass='';
	$listclass[3]='class="now"';
	$css_url=$depth."../templates/".$met_skin."/css";
	$img_url=$depth."../templates/".$met_skin."/images";
	include template('content/message/inc');
	footer();
}
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>