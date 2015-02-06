<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved.
$depth='../';
require_once $depth.'../login/login_check.php';
$filename=namefilter($filename);
$filenameold=namefilter($filenameold);
if($filename_okno){
	$metinfo=1;
	if($filename!=''){
		$filenameok = $db->get_one("SELECT * FROM $met_job WHERE filename='$filename'");
		if($filenameok)$metinfo=0;
		if(is_numeric($filename) && $filename!=$id && $met_pseudo){
			$filenameok1 = $db->get_one("SELECT * FROM {$met_job} WHERE id='{$filename}'");
			if($filenameok1)$metinfo=2;
		}
	}
	echo $metinfo;
	die;
}  
if($action=="add"){
	if($filename!=''){
		$filenameok = $db->get_one("SELECT * FROM $met_job WHERE filename='$filename'");
		if($filenameok)metsave('-1',$lang_modFilenameok,$depth);
	}
	if(!$description){
		$description=strip_tags($content);
		$description=str_replace("&nbsp;",'',$description); 
		$description=str_replace(" ","",$description);
		$description=str_replace("\n", '', $description); 
		$description=str_replace("\r", '', $description); 
		$description=str_replace("\t", '', $description);
		$description=mb_substr($description,0,200,'utf-8');
	}
	$query = "INSERT INTO $met_job SET
						position           = '$position',
						count              = '$count',
						place              = '$place',
						deal               = '$deal',
						content            = '$content',
						useful_life        = '$useful_life',
						addtime            = '$addtime',
						access			   = '$access',
						lang			   = '$lang',
						no_order		   = '$no_order',
						filename           = '$filename',
						email              = '$email',
						wap_ok             = '$wap_ok',
						top_ok             = '$top_ok',
						displaytype        = '$displaytype'";
			 $db->query($query);	 
	$later_job=$db->get_one("select * from $met_job where lang='$lang' order by id desc");
	$id=$later_job[id];
	$htmjs =indexhtm().'$|$';
	$htmjs.=contenthtm($class1,$id,'showjob',$filename,0,'job',$addtime).'$|$';
	$htmjs.=classhtm($class1,0,0);
	$gent='../../sitemap/index.php?lang='.$lang.'&htmsitemap='.$met_member_force;
	metsave('../content/job/index.php?anyid='.$anyid.'&lang='.$lang.'&class1='.$class1,'',$depth,$htmjs,$gent);
}

if($action=="editor"){
	if($filename!='' && $filename != $filenameold){
		$filenameok = $db->get_one("SELECT * FROM $met_job WHERE filename='$filename'");
		if($filenameok)metsave('-1',$lang_modFilenameok,$depth);
	}
	$query = "update $met_job SET 
						  position           = '$position',
						  place              = '$place',
						  deal               = '$deal',
						  content            = '$content',
						  count              = '$count',
						  useful_life        = '$useful_life',
						  addtime            = '$addtime',
						  access			 = '$access',
						  no_order		     = '$no_order',
						  displaytype        = '$displaytype',";
	if($metadmin[pagename])$query .= "
						  filename       	 = '$filename',";
						  $query .= "
						  email              = '$email',
						  wap_ok             = '$wap_ok',
						  top_ok             = '$top_ok'
						  where id='$id'";
	$db->query($query);
	$htmjs =indexhtm().'$|$';
	$htmjs.=contenthtm($class1,$id,'showjob',$filename,0,'job',$addtime).'$|$';
	$htmjs.=classhtm($class1,0,0);
	if($filenameold<>$filename and $metadmin[pagename])deletepage($met_class[$class1][foldername],$id,'showjob',$updatetimeold,$filenameold);
	$turl='../content/job/index.php?anyid='.$anyid.'&lang='.$lang.'&class1='.$class1.'&modify='.$id.'&page='.$page;
	$gent='../../sitemap/index.php?lang='.$lang.'&htmsitemap='.$met_member_force;
	metsave($turl,'',$depth,$htmjs,$gent);
}
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>
