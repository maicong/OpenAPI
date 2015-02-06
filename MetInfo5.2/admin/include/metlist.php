<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
$met_weburls=explode('/',$met_weburl);
$url_now=$_SERVER['SERVER_NAME']?$_SERVER['SERVER_NAME']:$_SERVER['HTTP_HOST'];
$domain=$met_weburl;
if($url_now!=$met_weburls[2]){
	$domain=str_replace($met_weburls[2],$url_now,$met_weburl);
}
$sidebarcolumn=$db->get_all("select * from $met_admin_column order by type desc,list_order");
foreach($sidebarcolumn as $key=>$val){
	$val['name'] = get_word($val['name']);
	if((($val[name]=='lang_indexcode')||($val[name]=='lang_indexebook')||($val[name]=='lang_indexbbs')||($val[name]=='lang_indexskinset'))&&$met_agents_type>1)continue;
	if((($val[name]=='lang_webnanny')||($val[name]=='lang_smsfuc'))&&$met_agents_sms==0)continue;
	if(($val[name]=='lang_dlapptips2')&&$met_agents_app==0)continue;
	if(strstr($val['name'],"lang_")){
		if(strstr($val['name'],"|lang_")){
			$linshi = '';
			$linshi = explode('|',$val['name']);
			$val['name']=$$linshi[0].$$linshi[1];
		}else{
			$val['name']=$$val['name'];
		}
	}
	if(strstr($val['info'],"lang_")){
		$val['info']=$$val['info'];
	}
	switch($val['type']){
		case 1:
			$metinfocolumn[]=$val;
		break;
		case 2:
			$purview='admin_pops'.$val['field'];
			$purview=$val['field']==0?'metinfo':$$purview;
			if($metinfo_admin_pop=="metinfo" || $purview=='metinfo'){
				if(strstr($val['url'],"http://")){
					$val['property']='target="_blank"';
				}else{
					$val['property']="target='main' id='nav_{$val[bigclass]}_{$val[id]}'";
					if($val['url']=='/interface/info.php'){
						$val['property']="target='_blank' id='nav_{$val[bigclass]}_{$val[id]}'";
						$val['url']=$domain.$met_adminfile.$val['url'];
					}
					if(strstr($val['url'],"?")){
						$val['url'].='&anyid='.$val['id'].'&lang='.$lang;
					}else{
						$val['url'].='?anyid='.$val['id'].'&lang='.$lang;
					}
				}
				$sidebarcolumns[]=$val;
				$letplace[$val['id']]=$val;
				if($val['name']==$lang_shortcut && $val['bigclass']=='1'){
					$shortcut=$val;
				}else{
					if($val['name']!=$lang_indexbasicinfo){
						$ad_navlist2[$val['bigclass']][]=$val;
					}
				}
			}
		break;
		case 3:
			$purview='admin_pops'.$val['field'];
			$purview=$val['field']==0?'metinfo':$$purview;
			if($metinfo_admin_pop=="metinfo" || $purview=='metinfo'){
				$val['url']='../'.$val['url'].'?anyid='.$val['bigclass'].'&lang='.$lang;
				$ad_navlist3[$val['bigclass']][]=$val;
			}
	}
}
$i=0;
foreach($sidebarcolumns as $key=>$val){
	if($val['bigclass']==2)$i++;
}
if($i==1)$metinfocolumn[1]['display']=1;
$sidebarcolumn=$sidebarcolumns;
foreach($metinfocolumn as $key=>$val){
	$toplace[$val['id']]=$val;
}

foreach ($metinfo_admin_shortcut as $key=>$val){
	$key_ok[$key]=$key;
	foreach($val as $key1=>$val1){
		if($key1=='list_order')$list_ok[$key] = $val1;
		$my_shortcut[$key1]=urldecode($val1);
	}
	$shortcut_list[]=$my_shortcut;
}
if(!$shortcut_list){
	$shortcut_list[0]=array('name'=>'lang_skinbaseset','url'=>'system/basic.php?anyid=9&lang=cn','bigclass'=>'1','field'=>'s1001','type'=>'2','list_order'=>'10','protect'=>'1','hidden'=>'0');
	$shortcut_list[1]=array('name'=>'lang_indexcolumn','url'=>'column/index.php?anyid=25&lang=cn','bigclass'=>'1','field'=>'s1201','type'=>'2','list_order'=>'0','protect'=>'1','hidden'=>'0');
	$shortcut_list[2]=array('name'=>'lang_unitytxt_75','url'=>'interface/skin_editor.php?anyid=18&lang=cn','bigclass'=>'1','field'=>'s1101','type'=>'2','list_order'=>'0','protect'=>'1','hidden'=>'0');
	$shortcut_list[3]=array('name'=>'lang_tmptips','url'=>"interface/info.php?anyid=24&lang=cn",'bigclass'=>'1','field'=>'s1101','type'=>'2','list_order'=>'0','protect'=>'1','hidden'=>'0');
	change_met_cookie('metinfo_admin_shortcut',$shortcut_list);
	save_met_cookie();
	$query="update $met_admin_table set admin_shortcut='".json_encode($shortcut_list)."' where admin_id='$metinfo_admin_name'";
	$db->query($query);
}
array_multisort($list_ok,SORT_NUMERIC,SORT_DESC,$key_ok,SORT_ASC,$shortcut_list);
foreach($shortcut_list as $key=>$val){
	$shortcut_list[$key][lang]=$shortcut_list[$key][name];
	$shortcut_list[$key][name]=$$shortcut_list[$key][name]?$$shortcut_list[$key][name]:urldecode($shortcut_list[$key][name]);
}
foreach($shortcut_list as $key=>$val){
	$my_shortcut=$val;
	$admin_column_power="admin_pop".$val[field];
	if(!($metinfo_admin_pop=='metinfo'||$$admin_column_power=='metinfo')){
		unset($shortcut_list[$key]);
		continue;
	}
	if($val['url']=='interface/info.php?anyid=24&lang=cn'){
		$val['property']='target="_blank" id="nav_1_'.$key.'"';
	}else{
		$val['property']="target='main' id='nav_1_".$key."'";
	}
	$val['url']=preg_replace('/lang=[^&]+/',"lang=$lang",$val['url']);
	if(!$val[hidden])$ad_navlist2[1][]=$val;
	
}
$ad_navlist2[1][]=$shortcut;
if($met_agents_type>=2){
	foreach($ad_navlist2[1] as $key=>$val){
		if($val[lang]=='lang_tmptips'){
			unset($ad_navlist2[1][$key]);
		}
	}
}
$cs=isset($cs)?$cs:1;
$listclass[$cs]='class="now"';
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>