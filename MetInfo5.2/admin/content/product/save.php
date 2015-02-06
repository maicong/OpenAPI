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
		$sql="class1='$class1'";
		foreach($column_pop as $key=>$val){
			if($key!=$lang){
				foreach($val as $key1=>$val1){
					if($val1['foldername']==$met_class[$class1]['foldername'])$sql.=" or class1='$val1[id]'";
				}
			}
		}
		$filenameok = $db->get_one("SELECT * FROM $met_product WHERE ($sql) and filename='$filename'");
		if($filenameok)$metinfo=0;
		if(is_numeric($filename) && $filename!=$id && $met_pseudo){
			$filenameok1 = $db->get_one("SELECT * FROM {$met_product} WHERE id='{$filename}' and class1='$class1'");
			if($filenameok1)$metinfo=2;
		}
	}
	echo $metinfo;
	die();
}  
$save_type=$action=="add"?1:($filename!=$filenameold?2:0);
if($filename!='' && $save_type){
		$sql="class1='$class1'";
		foreach($column_pop as $key=>$val){
			if($key!=$lang){
				foreach($val as $key1=>$val1){
					if($val1['foldername']==$met_class[$class1]['foldername'])$sql.=" or class1='$val1[id]'";
				}
			}
		}
		$sql1=$save_type==2?" and id!=$id":'';
		$filenameok = $db->get_one("SELECT * FROM $met_product WHERE ($sql) {$sql1} and filename='$filename'");
		if($filenameok)metsave('-1',$lang_modFilenameok,$depth);
}
$module=$met_class[$class1][module];
$query = "select * from $met_parameter where lang='$lang' and module='".$met_class[$class1][module]."' and (class1=$class1 or class1=0) order by no_order";
$result = $db->query($query);
while($list = $db->fetch_array($result)){
	if($list[type]==4){
		$query1 = " where lang='$lang' and bigid='".$list[id]."'";
		$total_list[$list[id]] = $db->counter($met_list, "$query1", "*");
	}
	$para_list[]=$list;
}
if($imgnum>0){
	for($i=0;$i<$imgnum;$i++){
		$displayimg = "displayimg".$i;
		$displayname = "displayname".$i;
		$$displayname=str_replace(array('|','*'),'_',$$displayname);
		if($$displayname||$$displayimg){
			if($i==0){
				$displayimglist=$$displayname.'*'.$$displayimg;
			}else{
				$displayimglist=$displayimglist.'|'.$$displayname.'*'.$$displayimg;
			}
		}
	}
} 
$displayimg = $displayimglist;
$classother=$classothers?'|'.implode('|',$classothers).'|':'';
if($action=="add"){
	if(!$description){
		$description=strip_tags($content);
		$description=str_replace(" ","",$description);
		$description=str_replace("&nbsp;",'',$description); 
		$description=str_replace("\n",'',$description); 
		$description=str_replace("\r",'',$description); 
		$description=str_replace("\t",'',$description);
		$description=mb_substr($description,0,200,'utf-8');
	}
	$access=$access<>""?$access:0;
	$query = "INSERT INTO $met_product SET
						  title              = '$title',
						  ctitle             = '$ctitle',
						  keywords           = '$keywords',
						  description        = '$description',
						  content            = '$content',
						  class1             = '$class1',
						  class2             = '$class2',
						  class3             = '$class3',
						  classother         = '$classother',
						  new_ok             = '$new_ok',
						  imgurl             = '$imgurl',
						  imgurls            = '$imgurls',
						  displayimg         = '$displayimg',
						  com_ok             = '$com_ok',
						  wap_ok             = '$wap_ok',
						  issue              = '$issue',
						  hits               = '$hits', 
						  addtime            = '$addtime', 
						  updatetime         = '$updatetime',
						  access          	 = '$access',
						  filename           = '$filename',
						  no_order       	 = '$no_order',
						  lang          	 = '$lang',
						  displaytype        = '$displaytype',
						  tag                = '$tag',";
	if($metadmin[productother])$query .="
						  contentinfo         = '$contentinfo',
						  contentinfo1        = '$contentinfo1',
						  contentinfo2        = '$contentinfo2',
						  contentinfo3        = '$contentinfo3',
						  contentinfo4        = '$contentinfo4',
						  content1            = '$content1',
						  content2            = '$content2',
						  content3            = '$content3',
						  content4            = '$content4',
						  ";
				 $query .="top_ok             = '$top_ok'";
			$db->query($query);
	$later_product=$db->get_one("select * from $met_product where updatetime='$updatetime' and lang='$lang'");
	$id=$later_product[id];
	foreach($para_list as $key=>$val){
		if($val[type]!=4){
			$para="para".$val[id];
			$para=$$para;
			if($val[type]==5){
				$paraname="para".$val[id]."name";
				$paraname=$$paraname;
			}
		}else{
			$para="";
			for($i=1;$i<=$total_list[$val[id]];$i++){
				$paraa="para".$val[id]."_".$i;
				$parab=$$paraa;
				$para=($parab<>"")?$para.$parab."-":$para;
			}
			$para=substr($para, 0, -1);
		}
		$query = "INSERT INTO $met_plist SET
			listid   ='$id',
			paraid   ='$val[id]',
			info     ='$para',
			imgname  ='$paraname',
			module   ='$module',
			lang     ='$lang'";
		$db->query($query);
		$paraname="";
	}
	$htmjs =contenthtm($class1,$id,'showproduct',$filename,0,'',$addtime).'$|$';
	$htmjs.=indexhtm().'$|$';
	$htmjs.=classhtm($class1,$class2,$class3);
	$turl  ="../content/product/index.php?anyid=$anyid&lang=$lang&class1=$reclass1&class2=$reclass2&class3=$reclass3";
	$gent='../../sitemap/index.php?lang='.$lang.'&htmsitemap='.$met_member_force;
	metsave($turl,'',$depth,$htmjs,$gent);
}
if($action=="editor"){
	if($class_other != 1){
		$classother = '';
	}
	$query = "update $met_product SET 
						  title              = '$title',
						  ctitle             = '$ctitle',
						  keywords           = '$keywords',
						  description        = '$description',
						  content            = '$content',
					      tag                = '$tag',
						  class1             = '$class1',
						  class2             = '$class2',
						  class3             = '$class3',
						  classother         = '$classother',
						  imgurl             = '$imgurl',
						  imgurls            = '$imgurls',
						  displayimg         = '$displayimg',
						  displaytype        = '$displaytype',";
	if($metadmin[productnew])$query .= "					  
						  new_ok             = '$new_ok',";
	if($metadmin[productcom])$query .= "	
						  com_ok             = '$com_ok',";
						  $query .= "
						  wap_ok             = '$wap_ok',
						  issue              = '$issue',
						  hits               = '$hits', 
						  addtime            = '$addtime', 
						  updatetime         = '$updatetime',";
	if($met_member_use)  $query .= "
						  access			 = '$access',";
	if($metadmin[pagename])$query .= "
						  filename       	 = '$filename',
						  no_order       	 = '$no_order',";
	if($metadmin[productother])$query .="
						  contentinfo         = '$contentinfo',
						  contentinfo1        = '$contentinfo1',
						  contentinfo2        = '$contentinfo2',
						  contentinfo3        = '$contentinfo3',
						  contentinfo4        = '$contentinfo4',
						  content1            = '$content1',
						  content2            = '$content2',
						  content3            = '$content3',
						  content4            = '$content4',
						  ";
						  $query .= "
						  top_ok             = '$top_ok',
						  lang               = '$lang'
						  where id='$id'";
	$db->query($query);
	foreach($para_list as $key=>$val){
		if($val[type]!=4){
		  $paras="para".$val[id];
		  $para=$$paras;
		   if($val[type]==5){
			 $paraname="para".$val[id]."name";
			 $paraname=$$paraname;
			 }
		}else{
		  $para="";
		  for($i=1;$i<=$total_list[$val[id]];$i++){
		  $paraa="para".$val[id]."_".$i;
		  $parab=$$paraa;
		  $para=($parab<>"")?$para.$parab."-":$para;
		  }
		  $para=substr($para, 0, -1);
		}
		$now_list=$db->get_one("select * from $met_plist where listid='$id' and  paraid='$val[id]'");
		if($now_list){
		$query = "update $met_plist SET
						  info     ='$para',
						  imgname  ='$paraname',
						  lang     ='$lang'
						  where listid='$id' and  paraid='$val[id]'";
		}else{
		$query = "INSERT INTO $met_plist SET
						  listid   ='$id',
						  paraid   ='$val[id]',
						  info     ='$para',
						  imgname  ='$paraname',
						  module   ='$module',
						  lang     ='$lang'";	
		 }
			 $db->query($query);
	   $paraname="";
	}
	$htmjs =contenthtm($class1,$id,'showproduct',$filename,0,'',$addtime).'$|$';
	$htmjs.=indexhtm().'$|$';
	$htmjs.=classhtm($class1,$class2,$class3);
	if($filenameold<>$filename and $metadmin[pagename])deletepage($met_class[$class1][foldername],$id,'showproduct',$updatetimeold,$filenameold);
	$classnow=$class3?$class3:($class2?$class2:$class1);
	//if(($addtime != $updatetime && $met_class[$classnow]['list_order']<2) || $top_ok==1)$page=0;
	$turl  ="../content/product/index.php?anyid=$anyid&lang=$lang&class1=$reclass1&class2=$reclass2&class3=$reclass3&modify=$id&page=$page";
	$gent='../../sitemap/index.php?lang='.$lang.'&htmsitemap='.$met_member_force;
	metsave($turl,'',$depth,$htmjs,$gent);
}
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>
