<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
$met_oline=1;
require_once 'common.inc.php';
if($met_online_type<>3){
	$met_url   = $navurl.'public/';
	$cache_online = met_cache('online_'.$lang.'.inc.php');
	if(!$cache_online){$cache_online=cache_online();}
	foreach($cache_online as $key=>$list){
		$online_list[]=$list;
		if($list['qq']!="")$qq_list[]=$list;
		if($list['msn']!="")$msn_list[]=$list;
		if($list['taobao']!="")$taobao_list[]=$list;
		if($list['alibaba']!="")$alibaba_list[]=$list;
		if($list['skype']!="")$skype_list[]=$list;
	}
	$metinfo='<div id="onlinebox" class="onlinebox onlinebox_'.$met_online_skin.' onlinebox_'.$met_online_skin.'_'.$met_online_color.'" style="display:none;">';
	if($met_online_skin<3){
	$metinfo.='<div class="onlinebox-showbox">';
	$metinfo.='<span>'.$lang_Online.'</span>';
	$metinfo.='</div>';
	$metinfo.='<div class="onlinebox-conbox" style="display:none;">';
	}
	$stit=$met_online_skin<3?"title='{$lang_Online_tips}'":'';
	$metinfo.='		<div class="onlinebox-top" '.$stit.'>';
	$metinfo.='<a href="javascript:;" onclick="return onlineclose();" class="onlinebox-close" title="'.$lang_Close.'"></a><span>'.$lang_Online.'</span>';
	$metinfo.='		</div>';
	$metinfo.='		<div class="onlinebox-center">';
	$metinfo.='			<div class="onlinebox-center-box">';
	//online content
	foreach($online_list as $key=>$val){
		$metinfo.="<dl>";
		if(!$met_onlinenameok)$metinfo.="<dt>".$val[name]."</dt>";
		$metinfo.="<dd>";
		if($val[qq]!=""){
			$metinfo.='<a href="http://wpa.qq.com/msgrd?v=3&uin='.$val[qq].'&site=qq&menu=yes" target="_blank"><img alt="QQ'.$val[name].'" border="0" src="http://wpa.qq.com/pa?p=2:'.$val[qq].':'.$met_qq_type.'" title="QQ'.$val[name].'" /></a>';
		}
		if($val[msn]!="")$metinfo.='<span class="met_msn"><a href="msnim:chat?contact='.$val[msn].'"><img border="0" alt="MSN'.$val[name].'" src="'.$met_url.'images/msn/msn_'.$met_msn_type.'.gif"/></a></span>';
		if($val[taobao]!="")$metinfo.='<span class="met_taobao"><a target="_blank" href="http://www.taobao.com/webww/ww.php?ver=3&touid='.$val[taobao].'&siteid=cntaobao&status='.$met_taobao_type.'&charset=utf-8"><img border="0" src="http://amos.alicdn.com/online.aw?v=2&uid='.$val[taobao].'&site=cntaobao&s='.$met_taobao_type.'&charset=utf-8" alt="'.$val[name].'" /></a></span>';
		if($val[alibaba]!=""){
			$span="";
			if($met_alibaba_type==11){
				$span="<span class='met_alibaba'>$val[alibaba]</span>";
			}
			$metinfo.='<div><a target="_blank" href="http://amos.alicdn.com/msg.aw?v=2&uid='.$val[alibaba].'&site=cnalichn&s='.$met_alibaba_type.'&charset=UTF-8"><img border="0" src="http://amos.alicdn.com/online.aw?v=2&uid='.$val[alibaba].'&site=cnalichn&s='.$met_alibaba_type.'&charset=UTF-8" alt="'.$val[name].'" />'.$span.'</a></div>';
		}
		if($val[skype]!="")$metinfo.='<span><a href="callto://'.$val[skype].'"><img src="'.$met_url.'images/skype/skype_'.$met_skype_type.'.gif" border="0"></a></span>';
		$metinfo.="</dd>"; 
		$metinfo.="</dl>"; 
		$metinfo.='<div class="clear"></div>'; 
	}	 
	//online over
	$metinfo.='			</div>';
	$metinfo.='		</div>';
	if($met_onlinetel!=""){
	$metinfo.='		<div class="onlinebox-bottom">';
	$metinfo.='			<div class="onlinebox-bottom-box"><div class="online-tbox">';
	$metinfo.=$met_onlinetel;
	$metinfo.='			</div></div>';
	$metinfo.='		</div>';
	}
	$metinfo.='<div class="onlinebox-bottom-bg"></div>';
	if($met_online_skin<3)$metinfo.='</div>';
	$metinfo.='</div>';
	$_REQUEST['jsoncallback'] = strip_tags($_REQUEST['jsoncallback']);
	if($_REQUEST['jsoncallback']){
		$metinfo=str_replace("'","\'",$metinfo);
		$metinfo=str_replace('"','\"',$metinfo);
		$metinfo=preg_replace("'([\r\n])[\s]+'", "", $metinfo);
		echo $_REQUEST['jsoncallback'].'({"metcms":"'.$metinfo.'"})';
	}else{
		echo $metinfo;
	}
	die();
}
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>