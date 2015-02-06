<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
$depth='../';
require_once $depth.'../login/login_check.php';
$query="select * from $met_app where download=1 order by id DESC";
$result = $db->query($query);
while($list= $db->fetch_array($result)){
	$app[$list[no]]=$list;
}

$query="select * from $met_app where download=0 order by id DESC";
$result = $db->query($query);
while($list= $db->fetch_array($result)){
	$app_dl[$list[no]]=$list;
}
//接口函数
$query="select * from $met_applist order by id DESC";
$result = $db->query($query);
while($list = $db->fetch_array($result)){
	$list[download] = 1;
	$list[appver] = 1;
	$app[$list[no]]=$list;
}
//结束
foreach($app_dl as $key=>$val){
	if($val[addtime]+2592000>$m_now_time&&$app[$key][download]==0)$new_app=1;
}
$REFERERs=explode('/',$_SERVER['HTTP_REFERER']);
$filename=explode('?',$REFERERs[count($REFERERs)-1]);
if($filename[0]=='turnover.php'){
	header('location:dlapp.php?lang='.$lang.'&anyid='.$anyid);
}
if(!$app&&!$cs){
	header('location:dlapp.php?lang='.$lang.'&anyid='.$anyid);
}

$listclass[1]='class="now"';
$css_url=$depth."../templates/".$met_skin."/css";
$img_url=$depth."../templates/".$met_skin."/images";
include template('app/dlapp/index');
footer();
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>