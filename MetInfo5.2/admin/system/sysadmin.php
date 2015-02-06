<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved.
require_once '../login/login_check.php';
function getOS($strAgent){
$os = false;
if(eregi('win',$strAgent) && strpos($strAgent,'95')){
$os = 'Windows 95';
} else if(eregi('win 9x',$strAgent) && strpos($strAgent,'4.90')){
$os = 'Windows ME';
} else if(eregi('win',$strAgent) && eregi('98',$strAgent)){
$os = 'Windows 98';
} else if(eregi('win',$strAgent) && eregi('nt 6.0',$strAgent)){
$os = 'Windows Vista';
} else if(eregi('win',$strAgent) && eregi('nt 5.2',$strAgent)){
$os = 'Windows 2003 Server';
} else if(eregi('win',$strAgent) && eregi('nt 5.1',$strAgent)){
$os = 'Windows XP';
} else if(eregi('win',$strAgent) && eregi('nt 5',$strAgent)){
$os = 'Windows 2000';
} else if(eregi('win',$strAgent) && eregi('nt',$strAgent)){
$os = 'Windows NT';
} else if(eregi('win',$strAgent) && eregi('32',$strAgent)){
$os = 'Windows 32';
} else if(eregi('linux',$strAgent)){
$os = 'Linux';
} else if(eregi('unix',$strAgent)){
$os = 'Unix';
} else if(eregi('sun',$strAgent) && eregi('os',$strAgent)){
$os = 'SunOS';
} else if(eregi('ibm',$strAgent) && eregi('os',$strAgent)){
$os = 'IBM OS/2';
} else if(eregi('mac',$strAgent) && eregi('pc',$strAgent)){
$os = 'Macintosh';
} else if(eregi('powerpc',$strAgent)){
$os = 'PowerPC';
} else if(eregi('aix',$strAgent)){
$os = 'AIX';
} else if(eregi('HPUX',$strAgent)){
$os = 'HPUX';
} else if(eregi('netbsd',$strAgent)){
$os = 'NetBSD';
} else if(eregi('bsd',$strAgent)){
$os = 'BSD';
} else if(eregi('OSF1',$strAgent)){
$os = 'OSF1';
} else if(eregi('IRIX',$strAgent)){
$os = 'IRIX';
} else if(eregi('FreeBSD',$strAgent)){
$os = 'FreeBSD';
} else if(eregi('teleport',$strAgent)){
$os = 'teleport';
} else if(eregi('flashget',$strAgent)){
$os = 'flashget';
} else if(eregi('webzip',$strAgent)){
$os = 'webzip';
} else if(eregi('offline',$strAgent)){
$os = 'offline';
} else{
$os = 'Unknown OS';
}
return $os;
}
$Agent = $_SERVER['HTTP_USER_AGENT'];
$met_sever1 = $_SERVER['SERVER_SOFTWARE'];
$xitong = getOS($Agent);
$css_url="../templates/".$met_skin."/css";
$img_url="../templates/".$met_skin."/images";
$admin_list = $db->get_one("SELECT * FROM $met_admin_table WHERE admin_id='$metinfo_admin_name'");
foreach($admin_list as $_key => $_value) {
	if($_key!='lang')$$_key = daddslashes($_value);
}
//start
$st=statime("Y-m-d");
$et=statime("Y-m-d");
$query="select * from {$met_visit_summary} WHERE stattime ='{$st}' and stattime ='{$et}'";
$visit= $db->get_one($query);
$visit[pv]=$visit[pv]?$visit[pv]:0;
$visit[alone]=$visit[alone]?$visit[alone]:0;
$visit[ip]=$visit[ip]?$visit[ip]:0;
$per_visit=sprintf("%.2f",($visit['pv']/$visit['alone']));
$ztime=statime("Y-m-d","-1 day");
$visit_summaryz=$db->get_one("SELECT * FROM {$met_visit_summary} WHERE stattime='{$ztime}'");
if(!$visit_summaryz){
	$visit_summaryz['pv']=0;
	$visit_summaryz['alone']=0;
	$visit_summaryz['ip']=0;
	$visit_summaryz['per']='0.00';
}else{
$visit_summaryz['per']=sprintf("%.2f",($visit_summaryz['pv']/$visit_summaryz['alone']));
}
$qtime=statime("Y-m-d","-2 day");
$visit_summaryq=$db->get_one("SELECT * FROM {$met_visit_summary} WHERE stattime='{$qtime}'");
if(!$visit_summaryq){
	$visit_summaryq['pv']=0;
	$visit_summaryq['alone']=0;
	$visit_summaryq['ip']=0;
	$visit_summaryq['per']='0.00';
}else{
$visit_summaryq['per']=sprintf("%.2f",($visit_summaryq['pv']/$visit_summaryq['alone']));
}

$SERVER_SIGNATURE1=$_SERVER['SERVER_SIGNATURE'];
$mysql1=mysql_get_server_info();
$feedback = $db->counter($met_feedback, " where readok=0 and lang='$lang' ", "*");
$message = $db->counter($met_message, " where readok=0 and lang='$lang' ", "*"); 
$link = $db->counter($met_link, " where show_ok=0 and lang='$lang' ", "*");
$member = $db->counter($met_admin_table, " where admin_approval_date is null and lang='$lang' and usertype<3 ", "*");
$admin_power = $db->get_one("select admin_type from $met_admin_table where admin_id='$metinfo_admin_name'");
$admin_power = $admin_power['admin_type'];
$sysadminFriendlyLink = $db->get_one("select field from $met_admin_column where url='seo/link/index.php'");
$sysadminFriendlyLink = $sysadminFriendlyLink['field'];
$sysadminMember = $db->get_one("select field from $met_admin_column where url='member/index.php'");
$sysadminMember = $sysadminMember['field'];
$new_metcms_v=!$met_newcmsv?$lang_metcmsnew1:$lang_metcmsnew2;
$new_metcms_v='<font style="color:#390; padding-left:15px;">'.$new_metcms_v.'</font>';

//商业授权
require_once ROOTPATH.'include/export.func.php';
$authurlself=$met_weburl;
$authinfo = $db->get_one("SELECT * FROM $met_otherinfo where id=1");
if(!$authinfo){
	metsave('-1',$lang_dataerror);
}
if($authinfo[authcode]=='')$authinfo[authcode]="{$lang_authTip4}";
$time=time();
$met_file='/authorize.php';
$authinfo=$db->get_one("SELECT * FROM $met_otherinfo where id=1");
if($authinfo['info1']&&$autcod){
	if($authinfo['info1']=='NOUSER'){
		echo '';
		die;
	}
	else{
		$authinfo['info2']=is_numeric($authinfo['info2'])?$authinfo['info2']:2147483647;
		if($time<=$authinfo['info2']){
			echo $authinfo['info1'];
			die();
		}
	}		
}
if($authinfo['authcode']&&$authinfo['authpass']){
	$post_data = array('met_code'=>$authinfo['authcode'],'met_key'=>$authinfo['authpass']);
	$info=curl_post($post_data,30);
	if($info=='no host'){
		$user['domain']=$lang_hosterror;
		$user['webname']=$lang_hosterror;
		$user['type']=$lang_hosterror;
		$user['buytime']=$lang_hosterror;
		$user['lifetime']=$lang_hosterror;
		$user['service']=$lang_hosterror;
	}else{
		$usertemp=explode('|',$info);
		if($usertemp[0]!='NOUSER'){
			$user['domain']=$usertemp[1];
			$user['webname']=$usertemp[3];
			$user['type']=$usertemp[2];
			$user['buytime']=date('Y-m-d',$usertemp[4]);
			$user['lifetime']=$user['lifetime']?date('Y-m-d',$usertemp[5]):'永久';
			$user['service']=$usertemp[6];
		}
	}
}
else{
	$usertemp[0]='NOUSER';
}
$info1=$usertemp[0]=='NOUSER'?$usertemp[0]:$user['type'];
$info2=$time<=$usertemp[5]?$usertemp[5]:2147483647;
$db->query("update $met_otherinfo set info1='$info1',info2='$info2' where id=1");
if($autcod){
	echo $user['type'];
	die;
}
$rooturl="..";
include template('system/sysadmin');
footer();
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>