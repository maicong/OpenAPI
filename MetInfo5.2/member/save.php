<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved.
require_once '../include/common.inc.php';

if($action=="add"){
 if($met_memberlogin_code==1){
	 require_once ROOTPATH.'member/captcha.class.php';
	 $Captcha= new  Captcha();
	 if(!$Captcha->CheckCode($code)){
	 echo("<script type='text/javascript'> alert('$lang_membercode'); window.history.back();</script>");
		exit;
	 }
 } 
$yhid=metdetrim($yhid);
$admin_if=$db->get_one("SELECT * FROM $met_admin_table WHERE admin_id='$yhid'");
if($admin_if||!$yhid){
okinfo('javascript:history.back();',$lang_js15);
}
require_once '../include/jmail.php';
$checkid=0;
if($met_member_login==1){
	$checkid=1;
}
if($met_member_login==2)
{
$array = explode("-",$m_now_date);
$year = $array[0];
$month = $array[1];
$array = explode(":",$array[2]);
$minute = $array[1];
$second = $array[2];
$array = explode(" ",$array[0]);
$day = $array[0];
$hour = $array[1];
$timestamp = mktime($hour,$minute,$second,$month,$day,$year);

$from=$met_fd_usename;
$fromname=$met_fd_fromname;
$to=$email;
$usename=$met_fd_usename;
$usepassword=$met_fd_password;
$smtp=$met_fd_smtp;

$check=md5($timestamp);
$met_webname1=$met_webname;
$title=$met_webname1.$lang_js16;
$body="$yhid,<br><br> {$met_memberemail}<br><br><b>{$lang_js17}</b>{$lang_js18}[<a href='{$met_weburl}member/register_include.php?username=$yhid&code=$check&lang=$lang'>{$lang_js16} {$met_weburl}member/register_include.php?username=$yhid&code=$check&lang=$lang</a>] {$lang_js19}<br><div align='right'>$fromname</div> ";
jmailsend($from,$fromname,$to,$title,$body,$usename,$usepassword,$smtp);
}
/*短信提醒*/
if($met_nurse_member){
require_once ROOTPATH.'include/export.func.php';
if(maxnurse()<$met_nurse_max){
$domain = strdomain($met_weburl);
$message="您网站[{$domain}]有新会员注册了！请尽快登录网站后台查看。";
sendsms($met_nurse_member_tel,$message,4);
}
}
/**/
$query="select * from $met_admin_array where lang='$lang' order by user_webpower asc";
$usertypes=$db->get_all($query);
if($usertypes[0][id]==3)die();
$pass1=md5($mm);

 $query = "INSERT INTO $met_admin_table SET
                      admin_id           = '$yhid',
                      admin_pass         = '$pass1',
					  admin_email        = '$email',
					  admin_modify_ip    = '$m_user_ip',
					  admin_register_date= '$m_now_date',
					  usertype			 = '{$usertypes[0][id]}',
					  lang               = '$lang',
					  checkid            = '$checkid'";
         $db->query($query);
$use_id=$db->get_one("SELECT * FROM $met_admin_table WHERE admin_id='$yhid'");
$query = "select * from $met_parameter where lang='$lang' and module='10' and wr_oks='1'";
$result = $db->query($query);
while($list = $db->fetch_array($result)){
	$paravalue[]=$list;
	$fd_para[]=$list;
}
for($x=0;$x<count($fd_para);$x++){
	$fd_para[$x][para]="para".$fd_para[$x][id];
}
require_once '../feedback/uploadfile_save.php';
foreach($paravalue as $key=>$val){
	if($val[type]!=4){
	    $infos ="para".$val[id];
		$info=$$infos;
		if($val[type]==5){$info="../upload/file/$info";}
		$query = "INSERT INTO $met_plist SET
                      listid         = '$use_id[id]',
					  info           = '$info',
					  paraid         = '$val[id]',
					  module         = '10',
					  imgname        = '$val[name]',
					  lang           = '$lang'";
		$db->query($query);
	}else{
		$query1 = "select * from $met_list where lang='$lang' and bigid='$val[id]'";
		$result1 = $db->query($query1);
		while($list1 = $db->fetch_array($result1)){
			$paravalue1[]=$list1;
		}
		$i=1;
		$infos="";
		foreach($paravalue1 as $key=>$val1){
			$paras4_name="para".$val[id]."_".$i;
			$para_name=$$paras4_name;
			if($infos){
			if($para_name){
			$infos=$infos."、".$para_name;
			}
			}else{
			if($para_name){
			$infos=$para_name;
			}
			}
			$i=$i+1;
		}
		$query = "INSERT INTO $met_plist SET
                      listid         = '$use_id[id]',
					  paraid         = '$val[id]',
					  info           = '$infos',
					  module         = '10',
					  imgname        = '$val[name]',
					  lang           = '$lang'";
		$db->query($query);
	}
}
if($met_member_login==2){
	okinfo('login.php?lang='.$lang,$lang_js20);
	exit();
}elseif($met_member_login==3){	 
	okinfo('login.php?lang='.$lang, $lang_js25);
exit();
}
	okinfo('login.php?lang='.$lang,$lang_js21);
}

if($action=="editor"){
require_once 'login_check.php';
if($metinfo_member_name!=$useid){
	okinfo('javascript:history.back();',$lang_js1);
}
$query = "update $met_admin_table SET
                      admin_id           = '$useid',
					  admin_modify_ip    = '$m_user_ip',
					  admin_mobile       = '$mobile',
					  admin_email        = '$email'";

if($pass1){
$pass1=md5($pass1);
$query .=", admin_pass         = '$pass1'";
}
$query .="  where admin_id='$useid'";
$db->query($query);
$uses_id=$db->get_one("SELECT * FROM $met_admin_table WHERE admin_id='$useid'");
$query = "select * from $met_parameter where lang='$lang' and module='10'";
$result = $db->query($query);
while($list = $db->fetch_array($result)){
	$paravalue[]=$list;
	$fd_para[]=$list;
}
for($x=0;$x<count($fd_para);$x++){
	$fd_para[$x][para]="para".$fd_para[$x][id];
}
require_once '../feedback/uploadfile_save.php';
foreach($paravalue as $key=>$val){
	if($val[type]!=4){
	    $info ="para".$val[id];
		$info=$$info;
		if($val[type]==5){
		if($info){
			$info="../upload/file/$info";
		}else{
			$attachment=$db->get_one("SELECT * FROM $met_plist WHERE listid='$uses_id[id]' and paraid='$val[id]' and module='10' and lang='$lang'");
			$info=$attachment[info];
		}
		
		}
		if($db->get_one("SELECT * FROM $met_plist WHERE listid='$uses_id[id]' and paraid='$val[id]' and module='10' and lang='$lang'")){
		$query = "update $met_plist SET	info='$info' where listid='$uses_id[id]' and paraid='$val[id]' and module='10' and lang='$lang'";
		$db->query($query);
		}else{
		$query = "INSERT INTO $met_plist SET info='$infos',listid='$uses_id[id]',paraid='$val[id]',module='10',lang='$lang'";
		$db->query($query);
		}
	}else{
		$query1 = "select * from $met_list where lang='$lang' and bigid='$val[id]'";
		$result1 = $db->query($query1);
		while($list1 = $db->fetch_array($result1)){
			$paravalues[]=$list1;
		}
		$i=1;
		$infos="";
		for($j=0;$j<count($paravalues);$j++){
			$paras4_name="para".$val[id]."_".$i;
			$para_name=$$paras4_name;
			if($infos){
			if($para_name){
			$infos=$infos."、".$para_name;
			}
			}else{
			if($para_name){
			$infos=$para_name;
			}
			}
			$i=$i+1;
		}
		if($db->get_one("SELECT * FROM $met_plist WHERE listid='$uses_id[id]' and paraid='$val[id]' and module='10' and lang='$lang'")){
		$query = "update $met_plist SET	info='$infos' where listid='$uses_id[id]' and paraid='$val[id]' and module='10' and lang='$lang'";
		$db->query($query);
		}else{
		$query = "INSERT INTO $met_plist SET info='$infos',listid='$uses_id[id]',paraid='$val[id],module='10',lang='$lang'";
		$db->query($query);
		}
	}
}
okinfo('basic.php?lang='.$lang,$lang_js21);
}
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>
