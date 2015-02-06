<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
require_once '../login/login_check.php';
require_once '../content/global.func.php';

$admin_list = $db->get_one("SELECT * FROM $met_admin_table WHERE id='$id'");
if(!$admin_list){
metsave('-1',$lang_dataerror);
}
$query1 = "SELECT * FROM $met_parameter WHERE lang='$lang' and module='10' order by no_order";
$result1 = $db->query($query1);
while($list1 = $db->fetch_array($result1)){
		$para_list[]=$list1;
}
$para_member_list=para_list_withs($para_list,$id,10);
$lev=0;
$menbermanage=1;
$met_member_use=1;
$list_access['access']=$admin_list['usertype'];
require '../content/access.php';
$checkid=($admin_list['checkid'])?"checked=checked":"";

$css_url="../templates/".$met_skin."/css";
$img_url="../templates/".$met_skin."/images";
include template('member/member_editor');
footer();
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>