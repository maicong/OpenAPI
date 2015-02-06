<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
require_once '../login/login_check.php';
require_once ROOTPATH.'include/jmail.php';
if($action=='email'){
	if(!get_extension_funcs('openssl')&&stripos($smtp,'.gmail.com')!==false){$metinfo='<span style="color:#f00;">'.$lang_setbasicTip14.'</span>';echo $metinfo;die();}
	if(!get_extension_funcs('openssl')&&$met_fd_way=='ssl'){$metinfo='<span style="color:#f00;">'.$lang_setbasicTip15.'</span>';echo $metinfo;die();}
	if(!function_exists('fsockopen')&&!function_exists('pfsockopen')&&!function_exists('stream_socket_client')){
		$metinfo='<span style="color:#f00;">'.$lang_basictips1.'</span>';
		$metinfo.='<span style="color:#090;">'.$lang_basictips2.'</span>';
	}else{
		ini_set("max_execution_time", "30000");
		require_once ROOTPATH.'include/jmail.php';
		/*jmailsend('发件人账号','发件人姓名','收件人帐号','邮件标题','内容','邮箱账号','邮箱密码','smtp服务器');*/
		if($usename&&$fromname&&$password&&$smtp){
			$password=$password=='passwordhidden'?$met_fd_password:$password;
			$emailok=jmailsend($usename,$fromname,$usename,$lang_basictips3,$lang_basictips4,$usename,$password,$smtp);
		}
		if(!$emailok){
			$metinfo='<span style="color:#f00;">'.$lang_basictips5.'</span>';
			$metinfo.='<span style="color:#090;">'.$lang_basictips6.'</span>';
		}
		else{
			$metinfo='<span style="color:#090">'.$lang_basictips7.'</span>';
		}		
	}
	echo $metinfo;
	die();
}
if($met_fd_way == ssl)$met_admin_smtp_ssl = "checked";
if($met_fd_way == tls)$met_admin_smtp_tls = "checked";
if($action=='modify'){
	if($met_fd_password=='passwordhidden'){
		$query="select * from $met_config where name='met_fd_password' and lang='$lang'";
		$value=$db->get_one($query);
		$met_fd_password=$value[value];
	}
	require_once $depth.'../include/config.php';

	metsave('../system/universal.php?anyid='.$anyid.'&lang='.$lang);
}else{
	$met_recycle1[$met_recycle]="checked='checked'";
	if($met_img_rename==1)$met_img_rename1="checked='checked'";
	if($met_deleteimg==1)$met_deleteimg1="checked='checked'";
	if($met_url_type==1)$met_url_type_yes="checked";
	if($met_url_type==0)$met_url_type_no="checked";
	$met_smspass1[$met_smspass]="checked='checked'";
	$css_url="../templates/".$met_skin."/css";
	$img_url="../templates/".$met_skin."/images";
	include template('system/universal');
	footer();
}
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>