<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved.  
require_once '../login/login_check.php';
require_once 'global.func.php';
if($action=="editor"){
	if($name=='')metsave('-1',$lang_js11);
	if($if_in==1 and $out_url=='' and $module<1000)metsave('-1',$lang_modOuturl);
	if($module==1 &&$isshow==0 && !($met_class2[$id]||$met_class3[$id]))metsave('-1',$lang_columnerr8);
	$filename=namefilter($filename);
	$filenameold=namefilter($filenameold);
	$indeximg =$metadmin[categorymarkimage]?$indeximg:'';
	$columnimg=$metadmin[categoryimage]?$columnimg:'';
	if($new_windows==0){
		$new_windows=null;
	}
	if($new_windows==1){
		$new_windows="target=''_blank''";
	}
	if($if_in==0){
		if($filename!='' && $filename!=$filenameold){
			$filenameok = $db->get_one("SELECT * FROM {$met_column} WHERE filename='{$filename}' and foldername='$foldername' and id!='$id'");
			if($filenameok)metsave('-1',$lang_modFilenameok);
			if(is_numeric($filename) && $filename!=$id && $met_pseudo){
				$filenameok1 = $db->get_one("SELECT * FROM {$met_column} WHERE id='{$filename}' and foldername='$foldername'");
				if($filenameok1)metsave('-1',$lang_jsx30);
			}
		}
		$filedir="../../".$foldername;  
		if(!file_exists($filedir))@mkdir($filedir,0777); 		
		if(!file_exists($filedir))metsave('-1',$lang_modFiledir);
		column_copyconfig($foldername,$module,$id);
		if($met_member_use)require_once 'check.php';
		$query = "update $met_column SET 
				  name               = '$name',
				  namemark           = '$namemark',
				  out_url            = '',
				  keywords           = '$keywords',
				  description        = '$description',
				  no_order           = '$no_order',
				  wap_ok             = '$wap_ok',
				  list_order         = '$list_order',
				  new_windows        = '$new_windows', 
				  bigclass           = '$bigclass',
				  releclass          = '$releclass',
				  nav                = '$nav',
				  ctitle             = '$ctitle',
				  if_in              = '$if_in',
				  filename           = '$filename',
				  foldername         = '$foldername',
				  module             = '$module',
				  index_num          = '$index_num',					  
				  classtype          = '$classtype',					  
				  access      		 = '$access',
				  indeximg			 = '$indeximg',
				  columnimg			 = '$columnimg',
				  display           = '$displays',
				  lang			     = '$lang',";
	if($module>=2&&$module<=5){
		$query .="content            = '$content',";
	}				  
		$query .="isshow			 =  $isshow
				  where id='$id'"; 
		$db->query($query);
		foreach ($met_class2[$id] as $val) {//控制下级栏目随上级栏目设置是否在前台显示
			$query="update $met_column SET 
					display= $displays
					where id=$val[id]";
			$db->query($query);
			foreach ($met_class3[$val[id]] as $val1) {
			$query="update $met_column SET 
					display= $displays
					where id=$val1[id]";
			$db->query($query);
			}
		}
	}elseif($if_in==1){
		$query = "update $met_column SET 
				  name               = '$name',
				  namemark           = '$namemark',";
	if($module<1000){	
		$query.= "out_url            = '$out_url',";
	}
	    $query.= "no_order           = '$no_order',
				  wap_ok             = '$wap_ok',
				  new_windows        = '$new_windows',
				  keywords           = '$keywords',
				  description        = '$description',
				  ctitle             = '$ctitle',
				  bigclass           = '$bigclass',
				  releclass          = '$releclass',
				  nav                = '$nav',
				  if_in              = '$if_in',
				  foldername         = '$foldername',
				  module             = '$module',
				  index_num          = '$index_num',					  
				  classtype          = '$classtype',
				  indeximg			 = '$indeximg',
				  lang			     = '$lang',
				  columnimg			 = '$columnimg',
				  ";
	if($module>1000){			  
		$query.= "keywords           = '$keywords',
				  ctitle             = '$ctitle',
				  description        = '$description',";
	}			  
		$query.= "display           = '$displays'
				  where id='$id'"; 
		$db->query($query);
	}
	if($module==9){
		require_once $depth.'../include/config.php';
	}
	file_unlink("../../cache/column_$lang.inc.php");
	$gent='../../sitemap/index.php?lang='.$lang.'&htmsitemap='.$met_member_force;
	metsave('../column/index.php?anyid='.$anyid.'&lang='.$lang,'','','',$gent);	
}
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>
