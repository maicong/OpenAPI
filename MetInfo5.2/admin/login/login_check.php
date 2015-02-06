<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
error_reporting(E_ERROR | E_WARNING | E_PARSE);
if($depth!=''&&$depth!='../'&&$depth!='../../'){die();}
if(!isset($depth))$depth='';
$commonpath=$depth.'include/common.inc.php';
$commonpath=$admin_index?$commonpath:'../'.$commonpath;
define('SQL_DETECT',1);
require_once $commonpath;
$turefile=$url_array[count($url_array)-2];
if($met_adminfile!=$turefile&&$adminmodify!=1){
	$met_adminfile=$turefile;
	$turefile=authcode($turefile,'ENCODE',$met_webkeys);
	$query="update $met_config set value='$turefile' where name='met_adminfile' and lang='metinfo'";
	$db->query($query);
}
$login_name=daddslashes($login_name,0,1);
$metinfo_admin_name=daddslashes($metinfo_admin_name,0,1);
if($action=="login"){
	$metinfo_admin_name     = $login_name;
	$metinfo_admin_pass     = $login_pass;
	$metinfo_admin_pass=md5($metinfo_admin_pass);
	/*code*/
	if($met_login_code==1){
		require_once $depth.'../include/captcha.class.php';
		$Captcha= new  Captcha();
		if(!$Captcha->CheckCode($code)){
			echo("<script type='text/javascript'>alert('$lang_logincodeerror');location.href='login.php?langset=$langset';</script>");
			exit;
		}
	}	
	$admincp_list = $db->get_one("SELECT * FROM $met_admin_table WHERE admin_id='$metinfo_admin_name' and usertype='3' ");	
	if (!$admincp_list){
	    echo("<script type='text/javascript'> alert('{$lang_loginname}');location.href='login.php';</script>");
	    exit;
	}else if($admincp_list['admin_pass']!=$metinfo_admin_pass){
		echo("<script type='text/javascript'> alert('$lang_loginpass');location.href='login.php';</script>");
		exit;
	}else{
		login_met_cookie($metinfo_admin_name);
		met_cooike_start();		
		change_met_cookie('metinfo_admin_name',$metinfo_admin_name);
		change_met_cookie('metinfo_admin_pass',$metinfo_admin_pass);
		change_met_cookie('metinfo_admin_id',$admincp_list['id']);
		change_met_cookie('metinfo_admin_type',$admincp_list['usertype']);
		change_met_cookie('metinfo_admin_pop',$admincp_list['admin_type']);
		change_met_cookie('metinfo_admin_time',$m_now_time);
		change_met_cookie('metinfo_admin_lang',$admincp_list['langok']);
		change_met_cookie('metinfo_admin_shortcut',json_decode($admincp_list['admin_shortcut']));
		if($_GET[langset]!=''){
			$_GET[langset]=daddslashes($_GET[langset],0,1);
			change_met_cookie('languser',$_GET[langset]);
			save_met_cookie();
		}
		save_met_cookie();
		$query="update $met_admin_table set 
		admin_modify_date='$m_now_date',
		admin_login=admin_login+1,
		admin_modify_ip='$m_user_ip'
		WHERE admin_id = '$metinfo_admin_name'";
		$db->query($query);
	}
	$adminlang=explode('-',$admincp_list[langok]);
	if($admincp_list[langok]<>'metinfo' and (!strstr($admincp_list[langok],"-".$met_index_type."-")))$lang=$adminlang[1];
	$filejs = ROOTPATH_ADMIN.'include/metvar.js';
	$strlen = file_put_contents($filejs, $js);
	if($metinfo_mobile){
		Header("Location: ../index.php");
	}else{
		$flag=0;
		$re_urls=explode('?',$re_url);
		$re_urlss=explode('/',$re_urls[0]);
		foreach($re_urlss as $key=>$val){
			if($val==$met_adminfile){
				$flag=1;
			}
			if($flag==1&&$val){
				$filedir.='/'.$val;
			}
		}
		if($re_url&&file_exists('../..'.$filedir)&&$filedir){
			Header("Location: $re_url");
			met_setcookie("re_url",$re_url,time()-3600);
			exit;
		}else{
			if($re_url)met_setcookie("re_url",$re_url,time()-3600);
			echo "<script type='text/javascript'> var nowurl=parent.location.href; var metlogin=(nowurl.split('login')).length-1; if(metlogin==0)location.href='../system/sysadmin.php?anyid=8&lang=$lang'; if(metlogin!=0)location.href='../index.php?lang=$lang';</script>";
		}	
	}
}else{
	if(!$metinfo_admin_name||!$metinfo_admin_pass){
		if($admin_index){
			met_cooike_unset();
			met_setcookie("re_url",$re_url,time()-3600);
			Header("Location: login/login.php");
		}else{
			if(!$re_url){
				$re_url=$_SERVER[HTTP_REFERER];
				$HTTP_REFERERs=explode('?',$_SERVER[HTTP_REFERER]);
				$admin_file_len1=strlen("/$met_adminfile/");
				$admin_file_len2=strlen("/$met_adminfile/index.php");
				if(strrev(substr(strrev($HTTP_REFERERs[0]),0,$admin_file_len1))=="/$met_adminfile/"||strrev(substr(strrev($HTTP_REFERERs[0]),0,$admin_file_len2))=="/$met_adminfile/index.php"||!$HTTP_REFERERs[0]){
					$re_url="http://$_SERVER[SERVER_NAME]$_SERVER[REQUEST_URI]";
				}
			}
			if(!$_COOKIE[re_url]&&!strstr($re_url, "return.php"))met_setcookie("re_url",$re_url,time()+3600);
			met_cooike_unset();
			Header("Location: ".$depth."../login/login.php");
		}
		exit;
	}else{
		$admincp_ok = $db->get_one("SELECT * FROM $met_admin_table WHERE admin_id='$metinfo_admin_name' and admin_pass='$metinfo_admin_pass' and usertype='3'");
		if(!$admincp_ok){
			if($admin_index){
				met_cooike_unset();
				met_setcookie("re_url",$re_url,time()-3600);
				Header("Location: login/login.php");
			}else{
				if(!$re_url){
					$re_url=$_SERVER[HTTP_REFERER];
					$HTTP_REFERERs=explode('?',$_SERVER[HTTP_REFERER]);
					$admin_file_len1=strlen("/$met_adminfile/");
					$admin_file_len2=strlen("/$met_adminfile/index.php");
					if(strrev(substr(strrev($HTTP_REFERERs[0]),0,$admin_file_len1))=="/$met_adminfile/"||strrev(substr(strrev($HTTP_REFERERs[0]),0,$admin_file_len2))=="/$met_adminfile/index.php"||!$HTTP_REFERERs[0]){
						$re_url="http://$_SERVER[SERVER_NAME]$_SERVER[REQUEST_URI]";
					}
				}
				if(!strstr($re_url, "return.php")){
				if(!$_COOKIE[re_url])met_setcookie("re_url",$re_url,time()+3600);
				}
				met_cooike_unset();
				Header("Location: ".$depth."../login/login.php");
			}
			exit;
		}
		/*power start*/
		if(ADMIN_POWER!="metinfo"){
			if(!strstr($admincp_ok[admin_op], "metinfo")){
				if(strstr($_SERVER['REQUEST_URI'], "delete.php")){
					if(!strstr($admincp_ok[admin_op], "del"))okinfo('javascript:window.history.back();',$lang_logindelete);
				}
				if(strstr($_SERVER['REQUEST_URI'], "changeState.php")){
					if(!strstr($admincp_ok[admin_op], "editor"))okinfo('javascript:window.history.back();',$lang_loginedit);
				}
				if(strstr($_SERVER['REQUEST_URI'], "/htm.php")){
					if(!strstr($admincp_ok[admin_op], "editor"))okinfo('javascript:window.history.back();',$lang_loginedit);
				}
				switch($action){
					case "add";
						if(!strstr($_SERVER['REQUEST_URI'], "/content.php")){
						if(!strstr($admincp_ok[admin_op], "add"))okinfo('javascript:window.history.back();',$lang_loginadd);
						}
						break;
					case "editor";
						if(!strstr($_SERVER['REQUEST_URI'], "/content.php")){
						if(!strstr($admincp_ok[admin_op], "editor"))okinfo('javascript:window.history.back();',$lang_loginedit);
						}
						break;
					case "modify";
						if(!strstr($admincp_ok[admin_op], "editor"))okinfo('javascript:window.history.back();',$lang_loginedit);
						break;
					case "Modify";
						if(!strstr($admincp_ok[admin_op], "editor"))okinfo('javascript:window.history.back();',$lang_loginedit);
						break;
					case "del";
						if(!strstr($admincp_ok[admin_op], "del"))okinfo('javascript:window.history.back();',$lang_logindelete);
						break;
					case "delete";
						if(!strstr($admincp_ok[admin_op], "del"))okinfo('javascript:window.history.back();',$lang_logindelete);
						break;
				}
				if(!strstr($_SERVER['REQUEST_URI'], "olupdate.php")){					
					if(($admincp_ok[admin_op]=='---' or $admincp_ok[admin_op]=='') and $action<>'' and $action<>'list' and !$action_ajax and (!strstr($_SERVER['REQUEST_URI'], "/content.php")) )okinfo('javascript:window.history.back();',$lang_loginall);
			    	}			    	
			}
			if(strstr($_SERVER['REQUEST_URI'], "olupdate.php")&&strpos($met_host, 'api.metinfo.cn')){
				$first=strpos($met_host, '/');
				$first=$first?$first+1:0;
				$met_host=substr($met_host,$first);
			}
		}
		$adminlang=explode('-',$admincp_ok[langok]);
		if($admincp_ok[langok]<>'metinfo' and (!strstr($admincp_ok[langok],$lang)))okinfo('javascript:window.history.back();',$lang_loginalllang);
		/*power end*/
	}
}
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>
