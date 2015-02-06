<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
$depth='../';
require_once $depth.'../login/login_check.php';
$backurl="../content/download/index.php?anyid={$anyid}&lang=$lang&class1=$class1&class2=$class2&class3=$class3";
if($action=='copy'){
	$query= "select * from $met_column where id='$copyclass1'";
	$result1=$db->get_one($query);
	if(!$result1){
		metsave('-1',$lang_dataerror,$depth);
		exit();
	}
	$allidlist=explode(',',$allid);
	$k=count($allidlist)-1;
	for($i=0;$i<$k; $i++){
		$query = "select * from {$met_download} where id='{$allidlist[$i]}'";
		$original[]=$db->get_one($query);
	}
	foreach($original as $key=>$val){
		$originalclass1[]=$val['class1'];
	}
	$para2id=$result1[id];
	foreach($originalclass1 as $key=>$val){
		if($lang==$copylang&&$result1[id]!=$val){
			$query = "update $met_parameter set class1='0' where class1='$val' and lang='$lang'";
			$db->query($query);
		}
	}
	foreach($originalclass1 as $key=>$val){
		$sql=" or class1='$val' ";
	}
	$query = "select * from $met_parameter where module='$result1[module]' and ( class1='0' $sql ) and lang='$lang' order by no_order ASC,id ASC";
	$para1=$db->get_all($query);

	$query = "select * from $met_parameter where module='$result1[module]' and ( class1='0' or class1='$para2id' ) and lang='$copylang' order by no_order ASC,id ASC";
	$para2=$db->get_all($query);	

	$paralist=array();
	foreach($para1 as $key=>$val){
		$paralist[$val[id]]=$para2[$key][id];
	}
	foreach($original as $key=>$val){
		$val[content]=str_replace('\'','\'\'',$val[content]);
		$query = "insert into {$met_download} set title='$val[title]',ctitle='$val[ctitle]',keywords='$val[keywords]',description='$val[description]',content='$val[content]',class1='{$copyclass1}',class2='{$copyclass2}',class3='{$copyclass3}',no_order='$val[no_order]',new_ok='$val[new_ok]',wap_ok='$val[wap_ok]',downloadurl='$val[downloadurl]',filesize='$val[filesize]',com_ok='$val[com_ok]',hits='$val[hits]',updatetime='$val[updatetime]',addtime='$val[addtime]',issue='$val[issue]',access='$val[access]',top_ok='$val[top_ok]',downloadaccess='$val[downloadaccess]',lang='{$copylang}',recycle='$val[recycle]',displaytype='$val[displaytype]',tag='$val[tag]'";
		$db->query($query);
		$insert_id=$db->insert_id();
		$query="select * from {$met_plist} where listid='{$val[id]}'";
		$plist=$db->get_all($query);
		foreach($plist as $key2=>$val2){
			if($paralist[$val2[paraid]]){
				$query="insert into {$met_plist} set listid='{$insert_id}',paraid='{$paralist[$val2[paraid]]}',info='$val2[info]',lang='$copylang',imgname='$val2[imgname]',module='$val2[module]'";
				$db->query($query);
			}
		}
	}
	metsave($backurl,'',$depth);
}elseif($action=="moveto"){
	$allidlist=explode(',',$allid);
	$k=count($allidlist)-1;
	$query= "select * from $met_column where id='$moveclass1'";
	$result1=$db->get_one($query);
	if(!$result1){
		metsave('-1',$lang_dataerror,$depth);
		exit();
	}
	for($i=0;$i<$k; $i++){
		$query = "select * from {$met_download} where id='{$allidlist[$i]}'";
		$original[]=$db->get_one($query);
	}
	foreach($original as $key=>$val){
		$originalclass1[]=$val['class1'];
	}
	$para2id=$result1[id];
	foreach($originalclass1 as $key=>$val){
		if($lang==$movelang&&$result1[id]!=$val){
			$query = "update $met_parameter set class1='0' where class1='$val' and lang='$lang'";
			$db->query($query);
		}
	}
	foreach($originalclass1 as $key=>$val){
		$sql=" or class1='$val' ";
	}
	$query = "select * from $met_parameter where module='$result1[module]' and ( class1='0' $sql ) and lang='$lang' order by no_order ASC,id ASC";
	$para1=$db->get_all($query);

	$query = "select * from $met_parameter where module='$result1[module]' and ( class1='0' or class1='$result1[id]' ) and lang='$movelang' order by no_order ASC,id ASC";
	$para2=$db->get_all($query);	
	$paralist=array();
	foreach($para1 as $key=>$val){
		$paralist[$val[id]]=$para2[$key][id];
	}
	foreach($original as $key=>$val){
		$filname= '';
		if($movelang!=$lang)$filname = "filename = '',";
		$query = "update {$met_download} SET class1='$moveclass1',class2='$moveclass2',class3='$moveclass3',access='$access',{$filname}lang='$movelang' where id='$val[id]'";
		$db->query($query);
		$query="select * from {$met_plist} where listid='{$val[id]}'";
		$plist=$db->get_all($query);
		foreach($plist as $key2=>$val2){
			if($paralist[$val2[paraid]]){
				$query="update {$met_plist} set paraid='{$paralist[$val2[paraid]]}',lang='$movelang' where id='{$val2[id]}'";
				$db->query($query);
			}
		}
		
	}
	metsave($backurl,'',$depth);
}else{
	$admin_list = $db->get_one("SELECT * FROM $met_download WHERE id='$id'");
	if(!$admin_list)metsave('-1',$lang_loginNoid,$depth);
	$query = "update $met_download SET ";
	if(isset($new_ok)){
		$new_ok=$new_ok==1?0:1;
		$query = $query."new_ok             = '$new_ok',";
	}
	if(isset($com_ok)){
		$com_ok=$com_ok==1?0:1;
		$query = $query."com_ok             = '$com_ok',";
	}
	if(isset($displaytype)){
		$displaytype=$displaytype==1?0:1;
		$query = $query."displaytype             = '$displaytype',";
	}
	if(isset($top_ok)){
		$top_ok=$top_ok==1?0:1;
		$query = $query."top_ok             = '$top_ok',";
	}
	if(isset($wap_ok)){
		$wap_ok=$wap_ok==1?0:1;
		$query = $query."wap_ok             = '$wap_ok',";
	}
	$query = $query."id='$id' where id='$id'";
	$db->query($query);
	metsave("../content/download/index.php?anyid={$anyid}&lang=$lang&class1=$class1&class2=$class2&class3=$class3&page=$page".'&modify='.$id.'&page='.$page,'',$depth);
}
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>
