<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved.
require_once '../login/login_check.php';

if(!isset($checkid)) $checkid=0;

if($action=="add"){
$admin_if=$db->get_one("SELECT * FROM $met_admin_table WHERE admin_id='$useid'");
if($admin_if)metsave('-1',$lang_loginUserMudb1);
 $pass1=md5($pass1);
 $query = "INSERT INTO $met_admin_table SET
                      admin_id           = '$useid',
                      admin_pass         = '$pass1',
					  admin_email        = '$email',
					  admin_mobile       = '$mobile',
					  admin_register_date= '$m_now_date',
					  admin_approval_date= '$m_now_date',
					  usertype			 = '$usertype',
					  checkid            = '$checkid',
					  lang               = '$lang'";
         $db->query($query);
$use_id=$db->get_one("SELECT * FROM $met_admin_table WHERE admin_id='$useid'");
$query = "select * from $met_parameter where lang='$lang' and module='10'";
$result = $db->query($query);
while($list = $db->fetch_array($result)){
	$paravalue[]=$list;
}
foreach($paravalue as $key=>$val){
	if($val[type]!=4){
	    $info ="para".$val[id];
		$info=$$info;
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
	metsave('../member/index.php?lang='.$lang.'&anyid='.$anyid);
}

if($action=="editor"){
if(isset($checkid) && $checkid==1) $approval_date=$m_now_date;
else $approval_date='';
$query = "update $met_admin_table SET
                      admin_id           = '$useid',
					  admin_email        = '$email',
					  admin_mobile       = '$mobile',
					  admin_approval_date= '$approval_date',
					  usertype			 = '$usertype',
					  checkid            = '$checkid'";

if($pass1){
$pass1=md5($pass1);
$query .=", admin_pass         = '$pass1'";
}
$query .="  where id='$id'";
$db->query($query);
$query = "select * from $met_parameter where lang='$lang' and module='10'";
$result = $db->query($query);
while($list = $db->fetch_array($result)){
	$paravalue[]=$list;
}
foreach($paravalue as $key=>$val){
	if($val[type]!=4){
	    $info ="para".$val[id];
		$info=$$info;
		if($val[type]==5){
			if(!$info){
				$para_fj=$db->get_one("SELECT * FROM $met_plist WHERE listid='$id' and paraid='$val[id]' and module='10' and lang='$lang'");
				$info=$para_fj[info];
			}
		}
		if($db->get_one("SELECT * FROM $met_plist WHERE listid='$id' and paraid='$val[id]' and module='10' and lang='$lang'")){
		$query = "update $met_plist SET	info='$info' where listid='$id' and paraid='$val[id]' and module='10' and lang='$lang'";
		$db->query($query);
		}else{
		$query = "INSERT INTO $met_plist SET info='$info',listid='$id',paraid='$val[id]',imgname='$val[name]',module='10',lang='$lang'";
		$db->query($query);
		}
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
		if($db->get_one("SELECT * FROM $met_plist WHERE listid='$id' and paraid='$val[id]' and module='10' and lang='$lang'")){
		$query = "update $met_plist SET	info='$infos' where listid='$id' and paraid='$val[id]' and module='10' and lang='$lang'";
		$db->query($query);
		}else{
		$query = "INSERT INTO $met_plist SET info='$infos',listid='$id',paraid='$val[id]',imgname='$val[name]',module='10',lang='$lang'";
		$db->query($query);
		}
	}
}
metsave('../member/index.php?lang='.$lang.'&anyid='.$anyid);
}
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>
