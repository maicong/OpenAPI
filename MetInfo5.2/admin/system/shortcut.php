<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
require_once '../login/login_check.php';
if($action=='del'||$action=='delete'||$action=='editor'||$action=='hidden'){
	if($action=='del'){
		$allidlist=explode(',',$allid);	
		foreach($allidlist as $key=>$val){
			if($shortcut_list[$val][protect]==0)unset($shortcut_list[$val]);
		}
	}
	if($action=='delete'){
		if($shortcut_list[$id][protect]==0)unset($shortcut_list[$id]);
	}
	if($action=='editor'){
		$allidlist=explode(',',$allid);	
		foreach($allidlist as $key=>$val){
			if($val!=''){
				$no_order='no_order_'.$val;
				$shortcut_list[$val][list_order]=$$no_order;
			}
		}
	}
	if($action=='hidden'){
		if($shortcut_list[$id][protect]==1){
			$shortcut_list[$id][hidden]=$hidden;
		}
	}
	foreach($shortcut_list as $key=>$val){
		$shortcut_list[$key][name]=$shortcut_list[$key][lang];
	}
	change_met_cookie('metinfo_admin_shortcut',$shortcut_list);
	save_met_cookie();
	$query="update $met_admin_table set admin_shortcut='".json_encode($shortcut_list)."' where admin_id='$metinfo_admin_name'";
	$db->query($query);
	echo '<script type="text/javascript">parent.window.location.reload();</script>';
	die();
}
$css_url="../templates/".$met_skin."/css";
$img_url="../templates/".$met_skin."/images";
include template('system/shortcut');
footer();
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>