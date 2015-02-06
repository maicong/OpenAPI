<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
require_once '../login/login_check.php';
if($action=='modify'){
	if($met_pseudo&&$met_webhtm>0){
		metsave('-1',$lang_rewritefinfo2);
		die;
	}
	require_once $depth.'../include/config.php';
	require_once 'pseudo.php';
	$met_webhtm=$met_pseudo?3:($met_webhtm?$met_webhtm:0);
	$query = "update $met_lang SET met_webhtm = '$met_webhtm' where lang='$lang'";
	$db->query($query);
	metsave('../seo/setpseudo.php?lang='.$lang.'&anyid='.$anyid.'&cs='.$cs);
}else{
	$cs=isset($cs)?$cs:1;
	$listclass[$cs]='class="now"';
	$met_pseudo1[$met_pseudo]='checked';
	$css_url="../templates/".$met_skin."/css";
	$img_url="../templates/".$met_skin."/images";
	include template('seo/setpseudo');
	footer();
}
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>