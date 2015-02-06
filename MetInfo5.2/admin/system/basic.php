<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
require_once '../login/login_check.php';
if($met_fd_way == ssl)$met_admin_smtp_ssl = "checked";
if($met_fd_way == tls)$met_admin_smtp_tls = "checked";
if($action=='modify'){
	if($cs==1){
		$met_weburl = ereg_replace(" ","",$met_weburl);
		if(substr($met_weburl,-1,1)!="/")$met_weburl.="/";
		if(!strstr($met_weburl,"http://"))$met_weburl="http://".$met_weburl;
	}
	require_once $depth.'../include/config.php';
	if($cs==1){
		$query = "update $met_lang SET met_weburl = '$met_weburl' where lang='$lang'";
		$db->query($query);
	}
	sitemap_robots();
	$db->query("update $met_otherinfo set info1='',info2='' where id=1");
	$gent='../../include/404.php?lang='.$lang.'&metinfonow='.$met_member_force;
	metsave('../system/basic.php?anyid='.$anyid.'&lang='.$lang.'&cs='.$cs.'&linkapi=1','','','',$gent);
}else{
	$localurl="http://";
	$localurl.=$_SERVER['HTTP_HOST'].$_SERVER["PHP_SELF"];
	$localurl_a=explode("/",$localurl);
	$localurl_count=count($localurl_a);
	$localurl_admin=$localurl_a[$localurl_count-3];
	$localurl_admin=$localurl_admin."/system/basic";
	$localurl_real=explode($localurl_admin,$localurl);
	$localurl=$localurl_real[0];
	if($met_weburl=="")$met_weburl=$localurl;
	$css_url="../templates/".$met_skin."/css";
	$img_url="../templates/".$met_skin."/images";
	if($linkapi==1){
	$email=$admin_list['admin_group']==10000?$admin_list['admin_email']:'';
	$tel=$admin_list['admin_group']==10000?$admin_list['admin_mobile']:'';
	$linkapijs="<script type=\"text/javascript\">
	$.ajax({
		url: 'http://api.metinfo.cn/record_install.php?url={$met_weburl}&email={$email}&webname={$met_webname}&webkeywords={$met_keywords}&tel={$tel}&version={$metcms_v}&softtype=1',
		type: \"POST\"
	});
	</script>
	";
	}
	include template('system/set_basic');
	footer();
}
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>