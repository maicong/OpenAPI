<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved.
require_once '../login/login_check.php';
if($type=='lang'){
	$metinfo.= '<ol>';
	$i=0;
foreach($met_langok as $key=>$val){
	$i++;
	$cls='';
	$c2 = $i%2==0?'class="c2"':'';
	if($langadminok=="metinfo" or (strstr($langadminok,"-".$val[mark]."-"))){
		$val[name_r]=utf8substr($val[name], 0, 4);
		$val[flag]=$val[flag]?$val[flag]:$val[mark].'.gif';
		$metinfo.="<li lang='{$val[mark]}' title='{$lang_langtips2}{$val[name]}' {$c2} flag='{$val[flag]}' lname='{$val[name_r]}' ldname='{$val[name]}'><span><img src='../public/images/flag/{$val[flag]}' />{$val[name]}</span></li>";
	}
}
	$metinfo.="<li title='{$lang_langadd}' id='addlang'>{$lang_langadd}</li>";
	$metinfo.= '</ol>';
	echo 'SUC|';
	echo $metinfo;
}
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>