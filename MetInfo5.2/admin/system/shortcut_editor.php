<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
require_once '../login/login_check.php';
if($action=='modify'){
	$shortcut=array();
	$query="select * from $met_language where value='$name' and lang='$lang'";
	$lang_shortcut=$db->get_one($query);
	$shortcut['name']=$lang_shortcut?'lang_'.$lang_shortcut['name']:urlencode($name);
	$shortcut['url']=$url;
	$shortcut['bigclass']=$bigclass;
	$shortcut['field']=$field;
	$shortcut['type']='2';
	$shortcut['list_order']=$list_order;
	$shortcut['protect']='0';
	$shortcut['hidden']='0';
	foreach($shortcut_list as $key=>$val){
		$shortcut_list[$key][name]=$shortcut_list[$key][lang];
	}
	$shortcut_list[]=$shortcut;
	change_met_cookie('metinfo_admin_shortcut',$shortcut_list);
	save_met_cookie();
	$query="update $met_admin_table set admin_shortcut='".json_encode($shortcut_list)."' where admin_id='$metinfo_admin_name'";
	$db->query($query);
	echo '<script type="text/javascript">parent.window.location.reload();</script>';
	die();
	//metsave('../system/shortcut.php?anyid='.$anyid.'&lang='.$lang.'&cs='.$cs);
}else{
	$query="select * from $met_app where download=1";
	$app=$db->get_all($query);
	$css_url="../templates/".$met_skin."/css";
	$img_url="../templates/".$met_skin."/images";
	include template('system/shortcut_editor');
	footer();
}
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>