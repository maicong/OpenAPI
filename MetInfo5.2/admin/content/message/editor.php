<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
$depth='../';
require_once $depth.'../login/login_check.php';
require_once '../global.func.php';
require_once ROOTPATH.'public/php/searchhtml.inc.php';
$backurl="../content/message/editor.php?anyid={$anyid}&lang={$lang}&class1={$class1}&id={$id}&customerid={$customerid}";
if($action=="editor"){
	$query = "update $met_message SET
						  useinfo            = '$useinfo',
						  readok             = '$readok',
						  access			 = '$access'
						  where id='$id'";
	$db->query($query);
	$htmjs = classhtm('message',0,0);
	$message_contents=$db->get_one("select * from $met_config where lang='$lang' and name='met_message_fd_content'");
	$message_content=$db->get_one("select * from $met_parameter where lang='$lang' and id='$message_contents[value]' and module='7'");
	$message_content1=$db->get_one("select * from $met_mlist where lang='$lang' and module='7' and listid='$id' and imgname='$message_content[name]'");
	$message_info="para".$message_content1[paraid];
	$message_info=$$message_info;
	$query = "update $met_mlist SET
						  info			 = '$message_info'
						  where listid='$id' and imgname='$message_content[name]'";
	$db->query($query);
while($list = $db->fetch_array($result)){
	$paravalue[]=$list;
}
foreach($paravalue as $key=>$val){
	
}
	metsave($backurl,'',$depth,$htmjs);
}else{
	$message_contents=$db->get_one("select * from $met_config where lang='$lang' and name='met_message_fd_content'");
	$message_content=$db->get_one("select * from $met_parameter where lang='$lang' and id='$message_contents[value]' and module='7'");
	$message_content1=$db->get_one("select * from $met_mlist where lang='$lang' and module='7' and listid='$id' and imgname='$message_content[name]'");
	$query1 = "SELECT * FROM $met_mlist WHERE lang='$lang' and module='7' and listid='$id' and paraid!='$message_content1[paraid]' order by id";
	$result1 = $db->query($query1);
	while($list1 = $db->fetch_array($result1)){
			$para_list[]=$list1;
	}
	$message_list=$db->get_one("select * from $met_message where id='$id'");
	$message_list['customerid']=metidtype($message_list['customerid']);
	if(!$message_list)metsave('-1',$lang_dataerror,$depth);
	$lev=$met_module[7][0][access];
	$list_access['access']=$message_list['access'];
	require '../access.php';
	$met_readok=($message_list[readok])?"checked='checked'":"";
	$css_url=$depth."../templates/".$met_skin."/css";
	$img_url=$depth."../templates/".$met_skin."/images";
	include template('content/message/editor');
	footer();
}
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>