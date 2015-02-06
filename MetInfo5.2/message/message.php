<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved.
require_once '../include/common.inc.php';
$ip=$m_user_ip;
$message_column=$db->get_one("select * from $met_column where module='7' and lang='$lang'");
$metaccess=$message_column[access];
$class1=$message_column[id];
foreach($settings_arr as $key=>$val){
	if($val['columnid']==$class1){
		$tingname    =$val['name'].'_'.$val['columnid'];
		$$val['name']=$$tingname;
	}
}
require_once ROOTPATH.'include/head.php';
$class1_info=$class_list[$class1][releclass]?$class_list[$class_list[$class1][releclass]]:$class_list[$class1];
$class2_info=$class_list[$class1][releclass]?$class_list[$class1]:$class_list[$class2];
$navtitle=$message_column['name'];
if($action=="add"){
	if(!$met_fd_ok)okinfo('javascript:history.back();',"{$lang_Feedback5}");
	if($met_memberlogin_code==1){
		require_once ROOTPATH.'member/captcha.class.php';
		$Captcha= new  Captcha();
		if(!$Captcha->CheckCode($code)){
		echo("<script type='text/javascript'> alert('$lang_membercode'); window.history.back();</script>");
			exit;
		}
	} 
	$addtime=$m_now_date;
	$ipok=$db->get_one("select * from $met_message where ip='$ip' order by addtime desc");
	$time1 = strtotime($ipok[addtime]);
	$time2 = strtotime($m_now_date);
	$timeok= (float)($time2-$time1);
	$timeok2=(float)($time2-$_COOKIE['submit']);
	if($timeok<=$met_fd_time&&$timeok2<=$met_fd_time){
		$fd_time="{$lang_Feedback1} ".$met_fd_time." {$lang_Feedback2}";
		okinfo('javascript:history.back();',$fd_time);
	}
	$pname="para".$met_message_fd_class;
	$pname=$$pname;
	$email="para".$met_message_fd_email;
	$email=$$email;
	$tel="para".$met_message_fd_sms;
	$tel=$$tel;
	$info="para".$met_message_fd_content;
	$info=$$info;
	$pname=strip_tags($pname);
	$email=strip_tags($email);
	$tel=strip_tags($tel);
	$contact=strip_tags($contact);
	$fdstr = $met_fd_word; 
	$fdarray=explode("|",$fdstr);
	$fdarrayno=count($fdarray);
	$fdok=false;
	$content=$content."-".$pname."-".$tel."-".$email."-".$info;
	for($i=0;$i<$fdarrayno;$i++){
		if(strstr($content, $fdarray[$i])){
			$fdok=true;
			$fd_word=$fdarray[$i];
			break;
		}
	}

	$fd_word=" {$lang_Feedback3} [".$fd_word."]";

	if($fdok==true)okinfo('javascript:history.back();',$fd_word);
	setcookie('submit',$time2);
	$from=$met_fd_usename;
	$fromname=$met_fd_fromname;
	$to=$met_fd_to;
	$usename=$met_fd_usename;
	$usepassword=$met_fd_password;
	$smtp=$met_fd_smtp;
	require_once '../include/jmail.php';
	if($met_fd_back==1 and $email!=""){
		jmailsend($from,$fromname,$email,$met_fd_title,$met_fd_content,$usename,$usepassword,$smtp);
	}
	/*短信提醒*/
	if($met_nurse_massge){
		require_once ROOTPATH.'include/export.func.php';
		if(maxnurse()<$met_nurse_max){
			$domain = strdomain($met_weburl);
			$message="您网站[{$domain}]收到了新的留言[{$title}]:".utf8substr($info,0,9)."，请尽快登录网站后台查看";
			sendsms($met_nurse_massge_tel,$message,4);
		}
	}
	/**/
	if($tel&&$met_fd_sms_back){
		require_once ROOTPATH.'include/export.func.php';
		sendsms($tel,$met_fd_sms_content,1);
	}
	/**/
	$customerid=$metinfo_member_name!=''?$metinfo_member_name:0;
	$query = "INSERT INTO $met_message SET
						  ip                 = '$ip',
						  addtime            = '$addtime',
						  lang               = '$lang', 
						  customerid 		 = '$customerid'";
	$db->query($query);
	$fname=$db->get_one("select * from $met_column where module='7' and lang='$lang'");
	$met_ahtmtype = $fname['filename']<>''?$met_chtmtype:$met_htmtype;
	$msfilename=$fname['filename']<>''?$fname['filename'].'_1':($met_htmlistname?"message_list_1":"index_list_1");
	$returnurl=$met_pseudo?'index-'.$lang.'.html':($met_webhtm==2?$msfilename.$met_ahtmtype:"index.php?lang=".$lang);
	$use_id=$db->get_one("SELECT * FROM $met_message WHERE ip='$ip' and addtime='$addtime'");
	$query = "select * from $met_parameter where lang='$lang' and module='7'";
	$result = $db->query($query);
	while($list = $db->fetch_array($result)){
		$paravalue[]=$list;
		$fd_para[]=$list;
	}
	for($x=0;$x<count($fd_para);$x++){
		$fd_para[$x][para]="para".$fd_para[$x][id];
	}
	require_once '../feedback/uploadfile_save.php';
	foreach($paravalue as $key=>$val){
		if($val[type]!=4){
			$infos ="para".$val[id];
			$info=$$infos;
			if($val['wr_ok'] == 1){
				if($info == ''){
					$last_page=$_SERVER[HTTP_REFERER];
					okinfo($last_page,$val['name'].$lang_Empty);
				}
			}
			if($val[type]==5){$info="../upload/file/$info";}
			$query = "INSERT INTO $met_mlist SET
						  listid         = '$use_id[id]',
						  info           = '$info',
						  paraid         = '$val[id]',
						  module         = '7',
						  imgname        = '$val[name]',
						  lang           = '$lang'";
			$db->query($query);
		}else{
			$query1 = "select * from $met_list where lang='$lang' and bigid='$val[id]'";
			$result1 = $db->query($query1);
			while($list1 = $db->fetch_array($result1)){
				$paravalue1[]=$list1;
			}
			$i=1;
			$infos="";
			foreach($paravalue1 as $key=>$val1){
				$paras4_name="para".$val[id]."_".$i;
				$para_name=$$paras4_name;
				if($infos){
					if($para_name){
						$infos=$infos."-".$para_name;
					}
				}else{
					if($para_name){
						$infos=$para_name;
					}
				}
				$i=$i+1;
			}
			if($val['wr_ok'] == 1){
				if($infos == ''){
					$last_page=$_SERVER[HTTP_REFERER];
					okinfo($last_page,$val['name'].$lang_Empty);
				}
			}
			$query = "INSERT INTO $met_mlist SET
						  listid         = '$use_id[id]',
						  paraid         = '$val[id]',
						  info           = '$infos',
						  module         = '7',
						  imgname        = '$val[name]',
						  lang           = '$lang'";
			$db->query($query);
		}
	}
	if($met_fd_email==1){
		$fromurl=$_SERVER['HTTP_REFERER'];
		$query1 = "select * from $met_mlist where lang='$lang' and module='7' and listid=$use_id[id] order by id";
		$result1 = $db->query($query1);
		while($list1 = $db->fetch_array($result1)){
			$email_list[]=$list1;
		}
		$body = '';
		foreach($email_list as $val){
			$body.="<b>$val[imgname]</b>:$val[info]<br />";
		}
		$title=$pname."{$lang_MessageInfo1}";
		jmailsend($from,$fromname,$to,$title,$body,$usename,$usepassword,$smtp,$email);
	}
	okinfo($returnurl,"{$lang_MessageInfo2}");

}else{
	$class2=$class_list[$class1][releclass]?$class1:$class2;
	$class1=$class_list[$class1][releclass]?$class_list[$class1][releclass]:$class1;
	$class_info=$class2?$class2_info:$class1_info;
	if($class2!=""){
		$class_info[name]=$class2_info[name]."--".$class1_info[name];
	}
	$show[description]=$class_info[description]?$class_info[description]:$met_description;
    $show[keywords]=$class_info[keywords]?$class_info[keywords]:$met_keywords;
	$met_title=$met_title?$navtitle.'-'.$met_title:$navtitle;
	 
	$message[listurl]=$met_pseudo?'index-'.$lang.'.html':(($met_webhtm==2)?($met_htmlistname?"message_list_1":"index_list_1").$met_htmtype:"index.php?lang=".$lang);
	if(count($nav_list2[$message_column[id]])){
		$k=count($nav_list2[$class1]);
		$nav_list2[$class1][$k]=$class1_info;
		$nav_list2[$class1][$k][name]=$lang_messageview;
		$k++;
		$nav_list2[$class1][$k]=array('url'=>$addmessage_url,'name'=>$lang_messageadd);
	}else{
		$k=count($nav_list2[$class1]);
		if(!$k){
			$nav_list2[$class1][0]=array('url'=>$addmessage_url,'name'=>$lang_messageadd);
			$nav_list2[$class1][1]=$class1_info;
			$nav_list2[$class1][1][name]=$lang_messageview;
		}
	}
	require_once '../public/php/methtml.inc.php';
	$methtml_message = metlabel_messageold();
	include template('message');
	footer();
}

# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>