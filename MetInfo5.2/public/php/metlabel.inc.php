<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
//Head部分
function metlabel_html5($closure=1,$iehack=1,$mobile=0){
	global $met_title,$show,$m_now_year,$navurl,$met_js_access,$met_skin_css,$img_url,$met_webname,$metcms_v,$appscriptcss,$met_ch_lang,$lang,$met_ch_mark,$met_url,$metinfouiok,$classnow,$class_list,$met_headstat;
	global $met_wap,$met_wap_tpa,$met_wap_tpb,$met_webhtm,$met_wap_url,$module,$metinfonow,$met_member_force,$met_weburl,$met_wapshowtype;
	global $_M;
	$metinfo="<!DOCTYPE HTML>\n";
	$metinfo.="<html>\n";
	$metinfo.="<head>\n";
	if($mobile){
		$metinfo.="<meta name='renderer' content='webkit'>\n";
        $metinfo.="<meta charset='utf-8' />\n";
        $metinfo.="<title>{$met_title}</title>\n";
        $metinfo.="<meta content=\"width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0\" name=\"viewport\" />\n";
        $metinfo.="<meta content=\"yes\" name=\"apple-mobile-web-app-capable\" />\n";
        $metinfo.="<meta content=\"black\" name=\"apple-mobile-web-app-status-bar-style\" />\n";
        $metinfo.="<meta content=\"telephone=no\" name=\"format-detection\" />\n";
        $metinfo.="<link href=\"{$navurl}favicon.ico\" rel=\"apple-touch-icon-precomposed\" />\n";
        $metinfo.="<link href=\"{$navurl}favicon.ico\" rel=\"shortcut icon\" type=\"image/x-icon\" />\n";
		if($metinfouiok==1)$metinfo.="<link rel=\"stylesheet\" type=\"text/css\" href=\"{$navurl}public/ui/mobile/css/metinfo.css\" id=\"metuimodule\" data-module =\"{$class_list[$classnow][module]}\" />\n";
	}else{
		$metinfo.="<meta name=\"renderer\" content=\"webkit\">\n"; 
        $metinfo.="<meta charset=\"utf-8\" />\n"; 
        $metinfo.="<meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge,chrome=1\">\n";
		if($metinfonow==$met_member_force&&$met_webhtm&&$met_wap&&($met_wap_tpa||$met_wap_tpb)&&isset($met_wapshowtype)){
			$mobile_prefix=request_uri();
			$allidlist=explode('&metmemberforce=',$mobile_prefix);
			$mobile_prefix=$allidlist[0];
			if($met_wap_tpb&&$met_wap_url)$mobile_prefix=str_replace($met_weburl,$met_wap_url,$mobile_prefix);
			$metinfo.="<script type=\"text/javascript\">var met_wap_tpa={$met_wap_tpa},met_wap_tpb={$met_wap_tpb},met_wap_url='{$met_wap_url}',mobile_lang='{$lang}',mobile_prefix='{$mobile_prefix}';</script>\n";
			$metinfo.="<script src=\"{$navurl}public/js/mobile.js\" type=\"text/javascript\"></script>\n";
		}
		$metinfo.="<title>".$met_title."</title>\n";
		$metinfo.="<meta name=\"description\" content=\"".$show['description']."\" />\n";
		$metinfo.="<meta name=\"keywords\" content=\"".$show['keywords']."\" />\n";
		$metinfo.="<meta name=\"generator\" content=\"MetInfo {$metcms_v}\" />\n";
		$metinfo.="<link href=\"".$navurl."favicon.ico\" rel=\"shortcut icon\" />\n";
		if($met_js_access)$metinfo.=$met_js_access."\n";
		if($met_skin_css=='')$met_skin_css='metinfo.css';
		if($metinfouiok==1)$metinfo.="<link rel=\"stylesheet\" type=\"text/css\" href=\"{$navurl}public/ui/met/css/metinfo_ui.css\" id=\"metuimodule\" data-module =\"{$class_list[$classnow][module]}\" />\n";
		$metinfo.="<link rel=\"stylesheet\" type=\"text/css\" href=\"".$img_url."css/".$met_skin_css."\" />\n";
		$metinfo.="<script src=\"{$navurl}public/js/jQuery1.7.2.js\" type=\"text/javascript\"></script>\n";
		if($metinfouiok==1)$metinfo.="<script src=\"{$navurl}public/ui/met/js/metinfo_ui.js\" type=\"text/javascript\"></script>\n";
		if($met_ch_lang and $lang==$met_ch_mark)$metinfo.="<script src=\"".$met_url."js/ch.js\" type=\"text/javascript\"></script>\n";
		if($appscriptcss)$metinfo.="{$appscriptcss}\n";
		//接口代码
		if($_M['html_plugin']['head_script'])$metinfo.="{$_M['html_plugin']['head_script']}";
		//结束
		if($iehack){
		$metinfo.="<!--[if IE]>\n";
		$metinfo.="<script src=\"{$navurl}public/js/html5.js\" type=\"text/javascript\"></script>\n";
		$metinfo.="<![endif]-->";
		if(!$met_headstat=="")$metinfo.="\n$met_headstat";
		}
	}
	if($closure)$metinfo.="\n</head>";
	return $metinfo;
}
//网站默认样式
function metlabel_style($closure=1){
	global $lang_fontfamily,$lang_fontsize,$lang_backgroundcolor,$lang_fontcolor,$lang_urlcolor,$lang_hovercolor;
	if($lang_fontfamily<>''||$lang_fontsize<>''||$lang_backgroundcolor<>''||$lang_fontcolor<>''||$lang_urlcolor<>''||$lang_hovercolor<>''){
		$metinfo.="<style type=\"text/css\">\n";
		$metinfo.="body{\n";
		$lang_fontfamily=str_replace("&quot;","\"",$lang_fontfamily);
		if($lang_fontfamily<>'')$metinfo.=" font-family:".$lang_fontfamily.";\n";
		if($lang_fontsize<>'')$metinfo.="	font-size:".$lang_fontsize.";\n"; 
		if($lang_backgroundcolor<>'')$metinfo.="	background:".$lang_backgroundcolor."; \n";
		if($lang_fontcolor<>'')$metinfo.="	color:".$lang_fontcolor.";\n";
		$metinfo.="}\n";
		if($lang_fontcolor<>'' or $lang_fontfamily<>''){
		   $metinfo.="table td{";
		   if($lang_fontfamily<>'')$metinfo.="font-family:".$lang_fontfamily.";"; 
		   if($lang_fontcolor<>'')$metinfo.="color:".$lang_fontcolor.";";
		   $metinfo.="}\n";
		}
		if($lang_fontcolor<>'' or $lang_fontfamily<>''){
		   $metinfo.="table th{";
		   if($lang_fontfamily<>'')$metinfo.="font-family:".$lang_fontfamily.";"; 
		   if($lang_fontcolor<>'')$metinfo.="color:".$lang_fontcolor.";";
		   $metinfo.="}\n";
		}
		if($lang_urlcolor<>'')$metinfo.="a{color:".$lang_urlcolor.";}\n";
		if($lang_hovercolor<>'')$metinfo.="a:hover{color:".$lang_hovercolor.";}\n";
		if($closure)$metinfo.="</style>\n";
		return $metinfo;
	}
}
function metlabel_flash(){
	global $methtml_flash,$met_flasharray,$classnow,$met_flashimg,$navurl;
	if($met_flasharray[$classnow][type]){
		if($met_flasharray[$classnow][type]==1){
		switch($met_flasharray[$classnow][imgtype]){
			case 6:
					$metinfo.="\n<link href='{$navurl}public/banner/banner6/css.css' rel='stylesheet' type='text/css' />\n";
					$metinfo.="<script src='{$navurl}public/banner/banner6/jquery.bxSlider.min.js'></script>";
					$metinfo.="<div class='flash flash6' style='width:".$met_flasharray[$classnow][x]."px; height:".$met_flasharray[$classnow][y]."px;'>\n";
					$metinfo.="<ul id='slider6' class='list-none'>\n";
					foreach($met_flashimg as $key=>$val){
						$val[img_link]=str_replace('%26','&',$val[img_link]);
						$metinfo.="<li><a href='".$val[img_link]."' target='_blank' title='{$val[img_title]}'>\n";
						$metinfo.="<img src='".$val[img_path]."' alt='".$val[img_title]."' width='{$met_flasharray[$classnow][x]}' height='{$met_flasharray[$classnow][y]}'></a></li>\n"; 
					}
					$metinfo.="</ul>\n";
					$metinfo.="</div>\n";
					$metinfo.="<script type='text/javascript'>$(document).ready(function(){ $('#slider6').bxSlider({ mode:'vertical',autoHover:true,auto:true,pager: true,pause: 5000,controls:false});});</script>";
			break;
			case 8:
					$metinfo.="\n<link rel='stylesheet' href='{$navurl}public/banner/jq-flexslider/flexslider.css' type='text/css'>\n";
					$metinfo.="<script src='{$navurl}public/banner/jq-flexslider/jquery.flexslider-min.js'></script>";
					$thisflash_x=$met_flasharray[$classnow][x]-8;
					$thisflash_y=$met_flasharray[$classnow][y]-8;
					$metinfo.="<div class='flash'><div class='flexslider flexslider_flash flashfld'><ul class='slides list-none'>";
					foreach($met_flashimg as $key=>$val){
						$val[img_link]=str_replace('%26','&',$val[img_link]);
						$metinfo.="<li><a href='".$val[img_link]."' target='_blank' title='{$val[img_title]}'>\n";
						$metinfo.="<img src='".$val[img_path]."' alt='".$val[img_title]."' width='{$met_flasharray[$classnow][x]}' height='{$met_flasharray[$classnow][y]}'></a></li>\n"; 
					}
					$metinfo.="</ul></div></div>";
					$metinfo.="<script type='text/javascript'>$(document).ready(function(){ $('.flashfld').flexslider({ animation: 'slide',controlNav:false});});</script>";
			break;
			default:
				if(!$hd)$metinfo=$methtml_flash;
			break;
		}
		}else{
			$metinfo=$methtml_flash;
		}
		return $metinfo;
	}
}
function metlabel_foot(){
	global $met_footright,$met_footstat,$met_footaddress,$met_foottel,$met_footother,$met_foottext,$_M;
	if($met_footright<>"" or $met_footstat<>"")$metinfo.="<p>".$met_footright." ".$met_footstat."</p>\n";
	if($met_footaddress<>"")$metinfo.="<p>".$met_footaddress."</p>\n";
	if($met_foottel<>"")$metinfo.="<p>".$met_foottel."</p>\n";
	if($met_footother<>"")$metinfo.="<p>".$met_footother."</p>\n";
	if($met_foottext<>"")$metinfo.="<p>".$met_foottext."</p>\n";
	//接口代码
	if($_M['html_plugin']['foot_script'])$metinfo.="{$_M['html_plugin']['foot_script']}";
	//结束
	return $metinfo;
}
//顶部导航函数
function metlabel_nav($type=1,$label='',$z,$l,$home=1){
	global $index_url,$lang_home,$nav_list,$nav_list2,$nav_list3,$navdown,$lang,$db,$met_column,$lang_cvtitle,$index,$class_list,$cv;
	
	if($recruitment=$db->get_one("SELECT * FROM $met_column WHERE bigclass='0' and module='6' and lang='$lang'")){
		if(!$nav_list2[$recruitment[id]][0][url]){
			$nav_list2[$recruitment[id]][0][id]=$recruitment[id];
			$nav_list2[$recruitment[id]][0][name]=$recruitment[name];
			$nav_list2[$recruitment[id]][0][url]=$class_list[$recruitment[id]][url];
			$nav_list2[$recruitment[id]][1][id]='10004';
			$nav_list2[$recruitment[id]][1][name]=$lang_cvtitle;
			$nav_list2[$recruitment[id]][1][url]=$cv['url'];
		}
	}
	
	if($z){
		$navnum=count($nav_list)+1;
		$width=($z/$navnum)-$l+($l/$navnum);
		$dwidth=array();
		if(strstr($width,".")){
			$width=sprintf("%.1f",$width);
			$y=explode('.',$width);
			$f=(int)$y[0];
			$k='0.'.$y[1];
			$k=round($k*$navnum);
			for($i=0;$i<$navnum;$i++){
				$m=$k<1?$f:$f+1;
				$dwidth[$i]=$m;
				$k=$k-1;
			}
		}else{
			for($i=0;$i<$navnum;$i++){
				$dwidth[$i]=$width;
			}
		}
	}
	$style0=$dwidth[0]?"style='width:{$dwidth[0]}px;'":'';
	$cdown=$navdown==10001?"class='navdown'":'';
	switch($type){
		case 1:
			$metinfo ='<ul class="list-none">';
			if($home){
			$metinfo.="<li id=\"nav_10001\" {$style0} {$cdown}>";
			$metinfo.="<a href='{$index_url}' title='{$lang_home}' class='nav'><span>{$lang_home}</span></a>";
			$metinfo.="</li>";
			}
			$p=0;
			foreach($nav_list as $key=>$val){
			$p++;
			$stylei=$dwidth[$p]?"style='width:{$dwidth[$p]}px;'":'';
			$cdown=$val['id']==$navdown?"class='navdown'":'';
			$metinfo.=$label;
			$metinfo.="<li id='nav_{$val[id]}' {$stylei} {$cdown}>";
			$metinfo.="<a href='{$val[url]}' {$val[new_windows]} title='{$val[name]}' class='hover-none nav'><span>{$val[name]}</span></a>";
			$metinfo.="</li>";
			}
			$metinfo.="</ul>";
			break;
		case 2:
			$metinfo ='<ul class="list-none">';
			if($home){
			$metinfo.="<li id=\"nav_10001\" {$style0} {$cdown}>";
			$metinfo.="<a href='{$index_url}' title='{$lang_home}' class='nav'><span>{$lang_home}</span></a>";
			$metinfo.="</li>";
			}
			$p=0;
			foreach($nav_list as $key=>$val){
			$p++;
			$stylei=$dwidth[$p]?"style='width:{$dwidth[$p]}px;'":'';
			$cdown=$val['id']==$navdown?"class='navdown'":'';
			$metinfo.=$label;
			$metinfo.="<li id='nav_{$val[id]}' {$stylei} {$cdown}>";
			$metinfo.="<a href='{$val[url]}' {$val[new_windows]} title='{$val[name]}' class='hover-none nav'><span>{$val[name]}</span></a>";
			if(count($nav_list2[$val['id']])){
				$metinfo.="<dl>";
				foreach($nav_list2[$val['id']] as $key=>$val2){
					$metinfo.="<dd><a href='{$val2[url]}' title='{$val2[name]}' {$val2[new_windows]}>{$val2[name]}</a></dd>";
				}
				$metinfo.="</dl>";
			}
			$metinfo.="</li>";
			}
			$metinfo.="</ul>";
			break;
		case 3:
			$metinfo ='<ul class="list-none">';
			if($home){
			$metinfo.="<li id=\"nav_10001\" {$style0}>";
			$metinfo.="<a href='{$index_url}' title='{$lang_home}' class='nav'><span>{$lang_home}</span></a>";
			$metinfo.="</li>";
			}
			$p=0;
			foreach($nav_list as $key=>$val){
			$p++;
			$stylei=$dwidth[$p]?"style='width:{$dwidth[$p]}px;'":'';
			$cdown=$val['id']==$navdown?"class='navdown'":'';
			$metinfo.=$label;
			$metinfo.="<li id='nav_{$val[id]}' {$stylei} {$cdown}>";
			$metinfo.="<a href='{$val[url]}' {$val[new_windows]} title='{$val[name]}' class='hover-none nav'><span>{$val[name]}</span></a>";
			if(count($nav_list2[$val['id']])){
				$metinfo.="<dl>";
				foreach($nav_list2[$val['id']] as $key=>$val2){
					$metinfo.="<dd><a href='{$val2[url]}' title='{$val2[name]}' {$val2[new_windows]}>{$val2[name]}</a>";
					if(count($nav_list3[$val2['id']])){
					$metinfo.="<p>";
						foreach($nav_list3[$val2['id']] as $key=>$val3){
							$metinfo.="<a href='{$val3[url]}' title='{$val3[name]}' {$val3[new_windows]}>{$val3[name]}</a>";
						}
					$metinfo.="</p>";
					}
					$metinfo.="</dd>";
				}
				$metinfo.="</dl>";
			}
			$metinfo.="</li>";
			}
			$metinfo.="</ul>";
			break;
	}
	
	return $metinfo;
}
function metlable_lang($dt,$tp=1){
	global $methtml_sethome,$methtml_addfavorite,$index_hadd_ok,$app_file,$met_adminfile;
	$metinfo=methtml_lang($dt,$tp);
	if($index_hadd_ok)$metinfo=$metinfo==''?$methtml_sethome.$dt.$methtml_addfavorite:$methtml_sethome.$dt.$methtml_addfavorite.$dt.$metinfo;
	$file_site = explode('|',$app_file[4]);
	foreach($file_site as $keyfile=>$valflie){
		if(file_exists(ROOTPATH."$met_adminfile".$valflie)&&!is_dir(ROOTPATH."$met_adminfile".$valflie)&&((file_get_contents(ROOTPATH."$met_adminfile".$valflie))!='metinfo')){require_once ROOTPATH."$met_adminfile".$valflie;}
	}
	return $metinfo;
}
//内页左侧栏目标签
function metlabel_sidebar($title=0,$msow=0){
	global $class_list,$classnow,$nav_list2,$class1,$is_memberleft;
	$thismod=$class_list[$classnow]['module'];
	if($title){
		$metinfo=$class_list[$class1]['name'];
		if($thismod==11 || $thismod==10)$metinfo=$class_list[$classnow]['name'];
	}else{
		if(	$is_memberleft != 1){
			$metinfo=$thismod==11?methtml_advsearch():($thismod==10?membernavlist(1):($nav_list2[$class1]!=''?metlabel_navnow(2,'','','','',$msow):0));
			if($thismod>99)$metinfo=metlabel_navnow(2,'','','','',$msow);
		}else{
			$metinfo = membernavlist(1);
		}
	}
	return $metinfo;
}
//侧边导航函数
function metlabel_navnow($type=1,$label='',$indexnum,$listyy=0,$listmax=8,$msow=0){
	global $sidedwon2,$met_config,$sidedwon3,$index_url,$nav_list,$nav_list2,$nav_list3,$class1,$class_list,$module_list1,$class_index,$classlistall,$lang,$db,$met_column;
	$class=$indexnum?$class_index[$indexnum]['id']:$class1;
	if($indexnum&&strstr($indexnum,"-")){
		$hngy5=explode('-',$indexnum);
		if($hngy5[1]=='cm')$class=$hngy5[0];
	}
	$mod=$class_index[$indexnum]['module'];
	if($class_list[$class1]['module']>99 && $class_list[$class1]['module']<1001 && !$indexnum){
		$mod=$class_list[$class1]['module']==100?3:5;
		$type=3;
	}
	
	$module=metmodname($mod);
	$cdown=$sidedwon2==10001?"class='on'":'';
	switch($type){
		case 1:
			$metinfo ='<ul class="list-none navnow">';
			$i=0;
			foreach($nav_list2[$class] as $key=>$val){
				$cdown=$val['id']==$sidedwon2?"class='on'":'';
				$i++;
				if($i!=1)$metinfo.=$label;
				$metinfo.="<li id='navnow1_{$val[id]}' {cdown}>";
				$metinfo.="<a href='{$val[url]}' {$val[new_windows]} title='{$val[name]}' class='nav'><span>{$val[name]}</span></a>";
				$metinfo.="</li>";
			}
			$metinfo.="</ul>";
			return $metinfo;
			break;
		case 2:
			$i=0;
			$cdown_id=0;
			if(strstr($_SERVER['REQUEST_URI'],"cv.php")){
				foreach($nav_list2[$class] as $key=>$val){
					if(strstr($val[url],"job/cv.php")){
						$cdown_id=$val[id];
					}
					
				}
			}
			foreach($nav_list2[$class] as $key=>$val){
				if($val[display]==1){
					continue;
				}
				
				$cdown=$val['id']==$sidedwon2?"class='on'":'';
				if($cdown_id==$val[id]){
					$cdown="class='on'";
				}
				if($cdown_id&&$cdown_id!=$val[id]){
					$cdown="";
				}
				$metinfo.='<dl class="list-none navnow">';
				$i++;
				if($i!=1)$metinfo.=$label;
				$lst3cun=count($nav_list3[$val['id']]);
				$zm=$lst3cun?'':'class="zm"';
				$metinfo.="<dt id='part2_{$val[id]}' {$cdown}>";
				$metinfo.="<a href='{$val[url]}' {$val[new_windows]} title='{$val[name]}' {$zm}><span>{$val[name]}</span></a>";
				$metinfo.="</dt>";
				$modlist=($listyy && $listmax)?methtml_getarray($val['id'],'','',$mod,$listmax,0,0,1,0,0,1):"";
				foreach($modlist as $key=>$list){
					$classlistall[$module][$val['id']][]=$list;
				}
				if($lst3cun){
					$msows=$msow==2?'style="display:none;"':'';
					$metinfo.='<dd class="sub" '.$msows.'>';
					foreach($nav_list3[$val['id']] as $key=>$val2){
						$cdown=$val2['id']==$sidedwon3?"class='on'":'';
						$metinfo.="<h4 id='part3_{$val2[id]}' {$cdown}>";
						$metinfo.="<a href='{$val2[url]}' {$val2[new_windows]} title='{$val2[name]}' class='nav'><span>{$val2[name]}</span></a>";
						$modlist=($listyy && $listmax)?methtml_getarray($val2['id'],'','',$mod,$listmax,0,0,1,0,0,1):"";
						foreach($modlist as $key=>$list){
							$classlistall[$module][$val2['id']][]=$list;
						}
						if(count($classlistall[$module][$val2['id']]) && $listyy && $listmax){
							$metinfo.="<p>";
							$i=0;
							foreach($classlistall[$module][$val2['id']] as $key=>$val3){
								$i++;
								$metinfo.="<a href='{$val3[url]}' target='_blank' title='{$val3[title]}'><span>{$val3[title]}</span></a>";	
								if($i>=$listmax)break;
							}
							$metinfo.="</p>";
						}
						$metinfo.="</h4>";
					}
					$metinfo.="</dd>";
				}elseif($listyy && $listmax && count($classlistall[$module][$val['id']])>0){
					$metinfo.="<dd class='sub'>";
					$metinfo.="<p>";
					$i=0;
					foreach($classlistall[$module][$val['id']] as $key=>$val3){
						$i++;
						$metinfo.="<a href='{$val3[url]}' target='_blank' title='{$val3[title]}'><span>{$val3[title]}</span></a>";	
						if($i>=$listmax)break;
					}
					$metinfo.="</p>";
					$metinfo.="</dd>";
				}
				$metinfo.="</dl>";
			}
			
			return $metinfo;
			break;
		case 3:
			foreach($module_list1[$mod] as $key=>$val0){
				$class=$val0[id];
				$metinfo.="<h2><a href='{$val0[url]}' title='{$val0[name]}' {$val0[new_windows]}>{$val0[name]}</a></h2>";
				$i=0;
				$cdown_id=0;
				if(strstr($_SERVER['REQUEST_URI'],"cv.php")){
					foreach($nav_list2[$class] as $key=>$val){
						if(strstr($val[url],"job/cv.php")){
							$cdown_id=$val[id];
						}
						
					}
				}
				foreach($nav_list2[$class] as $key=>$val){
					$cdown=$val['id']==$sidedwon2?"class='on'":'';
					if($cdown_id==$val[id]){
						$cdown="class='on'";
					}
					if($cdown_id&&$cdown_id!=$val[id]){
						$cdown="";
					}
					$metinfo.='<dl class="list-none navnow">';
					$i++;
					if($i!=1)$metinfo.=$label;
					$metinfo.="<dt id='part2_{$val[id]}' {$cdown}>";
					$metinfo.="<a href='{$val[url]}' {$val[new_windows]} title='{$val[name]}' class='nav'><span>{$val[name]}</span></a>";
					$metinfo.="</dt>";
					if(count($nav_list3[$val['id']])){
						$msows=$msow==2?'style="display:none;"':'';
						$metinfo.='<dd class="sub" '.$msows.'>';
						foreach($nav_list3[$val['id']] as $key=>$val2){
							$cdown=$val2['id']==$sidedwon3?"class='on'":'';
							$metinfo.="<h4 id='part3_{$val2[id]}' {$cdown}>";
							$metinfo.="<a href='{$val2[url]}' {$val2[new_windows]} title='{$val2[name]}' class='nav'><span>{$val2[name]}</span></a>";
							$modlist=($listyy && $listmax)?methtml_getarray($val2['id'],'','',$mod,$listmax,0,0,1):"";
							foreach($modlist as $key=>$list){
								$classlistall[$module][$val2['id']][]=$list;
							}
							if(count($classlistall[$module][$val2['id']]) && $listyy && $listmax){
								$metinfo.="<p>";
								$i=0;
								foreach($classlistall[$module][$val2['id']] as $key=>$val3){
									$i++;
									$metinfo.="<a href='{$val3[url]}' target='_blank' title='{$val3[title]}'><span>{$val3[title]}</span></a>";	
									if($i>=$listmax)break;
								}
								$metinfo.="</p>";
							}
							$metinfo.="</h4>";
						}
						$metinfo.="</dd>";
					}elseif($listyy && $listmax){
						$metinfo.="<dd class='sub'>";
						$metinfo.="<p>";
						$i=0;
						$modlist=($listyy && $listmax)?methtml_getarray($val['id'],'','',$mod,$listmax,0,0,1):"";
						foreach($modlist as $key=>$list){
							$classlistall[$module][$val['id']][]=$list;
						}
						foreach($classlistall[$module][$val['id']] as $key=>$val3){
							$i++;
							$metinfo.="<a href='{$val3[url]}' target='_blank' title='{$val3[title]}'><span>{$val3[title]}</span></a>";	
							if($i>=$listmax)break;
						}
						$metinfo.="</p>";
						$metinfo.="</dd>";
					}
					$metinfo.="</dl>";
				}
			}
			
			return $metinfo;
			break;
	}
}
//模块列表信息调用函数
function metlabel_list($listtype='text',$mark,$type,$order,$module,$time=0,$titleok=1,$bian=1,$listmx,$txtmax,$imgwidth){
	global $class_index,$index,$lang,$class_list,$metblank;
	global $index_news_no,$index_product_no,$index_download_no,$index_img_no,$index_job_no;
	$modules=$mark?$class_index[$mark]['module']:$module;
	$modules=$modules?$modules:2;
	$marktype=0;
	if($mark&&strstr($mark,"-")){
		$hngy5=explode('-',$mark);
		if($hngy5[1]=='cm'){
			$mark=$hngy5[0];
			$modules=$class_list[$mark]['module'];
			$marktype=1;
			$module=$modules;
		}
		if($hngy5[1]=='md'){
			$mark='';
			$modules=$hngy5[0];
			$module=metmodname($hngy5[0]);
		}
	}
	$listarray=methtml_getarray($mark,$type,$order,$module,$listmx,'','',$marktype,$txtmax);
	
	switch($listtype){
		case 'img':
			$metinfo.="<ol class='list-none metlist'>";
			$i=0;
			foreach($listarray as $key=>$val){
			$val[imgurls]=str_replace("../http://", "http://", $val[imgurls]);
			$i++;
			if($imgwidth){
				$val[img_y]=round($imgwidth * $val[img_y] / $val[img_x]);
				$val[img_x]=$imgwidth;
			}
			$metinfo.="<li class='list'>";
			$metinfo.="<a href='{$val[url]}' title='{$val[title]}' {$metblank} class='img'><img src='{$val[imgurls]}' alt='{$val[title]}' title='{$val[title]}' width='{$val[img_x]}' height='{$val[img_y]}' /></a>";
if($titleok)$metinfo.="<h3 style='width:{$val[img_x]}px;'><a href='{$val[url]}' title='{$val[title]}' {$metblank}>{$val[title]}</a></h3>";
			$metinfo.="</li>";
			}
			$metinfo.="</ol>";
			break;
		case 'text':
			$metinfo.="<ol class='list-none metlist'>";
			$i=0;
			foreach($listarray as $key=>$val){
			$i++;$top='';
			if($i==1)$top='top';
			$metinfo.="<li class='list {$top}'>";
	if($bian){$a='[';$b=']';}
   if($time)$metinfo.="<span class='time'>{$a}{$val[updatetime]}{$b}</span>";
			$metinfo.="<a href='{$val[url]}' title='{$val[title]}' {$metblank}>{$val[title]}</a>{$val[hot]}{$val[news]}{$val[top]}";
			$metinfo.="</li>";
			}
			if($modules==1)$metinfo.=$marktype==1?$class_list[$mark]['description']:$class_index[$mark]['description'];
			$metinfo.="</ol>";
		    break;
	}
	return $metinfo;
}

//会员侧导航
function membernavlist($type=0){
	global $lang,$lang_memberIndex3,$lang_memberIndex4,$lang_memberIndex5,$lang_memberIndex6,$lang_memberIndex7,$lang_memberIndex10,$app_file,$met_adminfile,$met_mermber_metinfo_news_left_class,$db,$met_admin_table,$met_weburl,$met_adminfile,$metinfo_member_name,$met_ifmember_left,$class_list,$navigation;
	$class=$met_mermber_metinfo_news_left_class?$met_mermber_metinfo_news_left_class:'membernavlist';/*兼容以前模板*/
	$admin_list = $db->get_one("SELECT * FROM $met_admin_table WHERE admin_id='$metinfo_member_name' ");
	
	if($type==1){
		$metinfo.="<dl class='$class'>";
		$metinfo.="<dt><a href='../member/basic.php?lang={$lang}' title='{$lang_memberIndex3}'>{$lang_memberIndex3}</a></dt>";
		foreach($navigation as $key=>$val){
			if($val[columnid]){
				$column = $class_list[$val[columnid]];
				$val['foldername'] = $val['foldername'] ? $val['foldername'] : $column['foldername'];
				$val['filename'] = $val['filename'] ? $val['filename'] : 'index.php';
				$metinfo.="<dt><a href='../{$val['foldername']}/{$val['filename']}' title='{$column['name']}'>{$column['name']}</a></dt>";
			}else{
				$metinfo.="<dt><a href='../{$val['foldername']}/{$val['filename']}' title='{$val['title']}'>{$val['title']}</a></dt>";
			}
		}
		if($admin_list[usertype]==3){
				
		}else{
			$metinfo.="<dt><a href='../member/editor.php?lang={$lang}' title='{$lang_memberIndex4}'>{$lang_memberIndex4}</a></dt>";	
		}
		//$metinfo.="<dt><a href='editor.php?lang={$lang}' title='{$lang_memberIndex4}'>{$lang_memberIndex4}</a></dt>";
		$metinfo.="<dt><a href='../member/feedback.php?lang={$lang}' title='{$lang_memberIndex5}'>{$lang_memberIndex5}</a></dt>";
		$metinfo.="<dt><a href='../member/message.php?lang={$lang}' title='{$lang_memberIndex6}'>{$lang_memberIndex6}</a></dt>";
		$metinfo.="<dt><a href='../member/cv.php?lang={$lang}' title='{$lang_memberIndex7}'>{$lang_memberIndex7}</a></dt>";
		$file_site = explode('|',$app_file[3]);
		foreach($file_site as $keyfile=>$valflie){
			if(file_exists(ROOTPATH."$met_adminfile".$valflie)&&!is_dir(ROOTPATH."$met_adminfile".$valflie)&&((file_get_contents(ROOTPATH."$met_adminfile".$valflie))!='metinfo')){require ROOTPATH."$met_adminfile".$valflie;}
		}
		$metinfo.="<dt><a href='../member/login_out.php?lang={$lang}' title='{$lang_memberIndex10}'>{$lang_memberIndex10}</a></dt>";
		$metinfo.="</dl>";
	}else{
		$metinfo.="<ul class='$class'>";
		$metinfo.="<li><a href='../member/basic.php?lang={$lang}' title='{$lang_memberIndex3}'>{$lang_memberIndex3}</a></li>";
		foreach($navigation as $key=>$val){
			if($val[columnid]){
				$column = $class_list[$val[columnid]];
				$val['foldername'] = $val['foldername'] ? $val['foldername'] : $column['foldername'];
				$val['filename'] = $val['filename'] ? $val['filename'] : 'index.php';
				$metinfo.="<li><a href='../{$val['foldername']}/{$val['filename']}' title='{$column['name']}'>{$column['name']}</a></li>";
			}else{
				$metinfo.="<li><a href='../{$val['foldername']}/{$val['filename']}' title='{$val['title']}'>{$val['title']}</a></li>";
			}
		}
		$metinfo.="<li><a href='../member/editor.php?lang={$lang}' title='{$lang_memberIndex4}'>{$lang_memberIndex4}</a></li>";
		$metinfo.="<li><a href='../member/feedback.php?lang={$lang}' title='{$lang_memberIndex5}'>{$lang_memberIndex5}</a></li>";
		$metinfo.="<li><a href='../member/message.php?lang={$lang}' title='{$lang_memberIndex6}'>{$lang_memberIndex6}</a></li>";
		$metinfo.="<li><a href='../member/cv.php?lang={$lang}' title='{$lang_memberIndex7}'>{$lang_memberIndex7}</a></li>";
		$file_site = explode('|',$app_file[3]);
		foreach($file_site as $keyfile=>$valflie){
			if(file_exists(ROOTPATH."$met_adminfile".$valflie)&&!is_dir(ROOTPATH."$met_adminfile".$valflie)&&((file_get_contents(ROOTPATH."$met_adminfile".$valflie))!='metinfo')){require ROOTPATH."$met_adminfile".$valflie;}
		}
		$metinfo.="<li><a href='../member/login_out.php?lang={$lang}' title='{$lang_memberIndex10}'>{$lang_memberIndex10}</a></li>";
		$metinfo.="</ul>";
	}
	return $metinfo;
}
//文章模块列表函数
function metlabel_news($time=1,$desc=0,$dlen,$dt=1,$n=0){
	global $news_list,$metblank,$id;
	$metinfo.="<ul class='list-none metlist'>";
	$i=0;
	foreach($news_list as $key=>$val){
		if(!$n || $id!=$val[id]){
			$i++;$top='';
			if($dlen)$val['description']=utf8substr($val['description'],0,$dlen);
			if($i==1)$top='top';
			$metinfo.="<li class='list {$top}'>";
			if($dt){$a='[';$b=']';}
			if($time)$metinfo.="<span>{$a}{$val[updatetime]}{$b}</span>";
			$metinfo.="<a href='{$val[url]}' title='{$val[title]}' {$metblank}>{$val[title]}</a>{$val[hot]}{$val[news]}{$val[top]}";
			if($desc&&$val['description']!='')$metinfo.="<p>{$val[description]}</p>";
			$metinfo.="</li>";	
		}
	}
	$metinfo.="</ul>";
	return $metinfo;
}



//产品模块列表函数
function metlabel_product($z,$w,$l,$n=0){
	global $product_list,$metblank,$met_img_style,$met_img_x,$met_img_y,$met_product_page,$class1,$class2,$class3,$search,$nav_list2,$nav_list3,$weburly,$id,$met_agents_img,$class_list,$module_listall,$db,$lang,$met_parameter,$met_plist,$prices,$action,$product_para;
	
	foreach($product_para as $key=>$val){
		$inquiry="inquiry_".$val[id];
		global $$inquiry;
	}
	
			
	$met_img_x=$met_img_style?met_imgxy(1,'product'):$met_img_x;
	$met_img_y=$met_img_style?met_imgxy(2,'product'):$met_img_y;
	$metinfo.="<ul class='list-none metlist'>";
	$listarray=$product_list;
	$metok=0;
	if($met_product_page && $search<>'search'){
		if($class2 && count($nav_list3[$class2]) && !$class3){
			$listarray=$nav_list3[$class2];
			$metok=1;
		}
		if(!$class2 && count($nav_list2[$class1]) && $class1 && !$class3){
			$listarray=$nav_list2[$class1];
			$metok=1;
		}
		if($class_list[$class1]['module']=='100'){
			$listarray=array();
			foreach($module_listall[3] as $key=>$val){
				if($val['classtype']==1||$val['releclass']!=0){
					$listarray[]=$val;
				}
			}
			$metok=1;
		}
	}
	//dump($listarray);
	
	if($z){
		$l=$l?$l:floor($z/$w);
		$margin=(($z/$l)-$w)/2;
		$margin=$margin<0?(($z/(floor($z/$w)))-$w)/2:$margin;
		$dwidth=array();
		if(strstr($margin,".")){
			$margin=sprintf("%.1f",$margin);
			$y=explode('.',$margin);
			$f=(int)$y[0];
			$k='0.'.$y[1];
			$k=intval($k*$l);
			for($i=0;$i<$l;$i++){
				$m=$k<1?$f:$f+1;
				$dwidth[$i]=$m;
				$k=$k-1;
			}
		}else{
			for($i=0;$i<$l;$i++){
				$dwidth[$i]=$margin;
			}
		}
	}
	$i=0;
	foreach($listarray as $key=>$val){
		if(!$n || $id!=$val[id]){
			if($metok){
				$val['title']=$val['name'];
				$val['imgurls']=$val['columnimg']==''?$weburly.$met_agents_img:$val['columnimg'];
			}
			$style=$dwidth[$i]?"style='width:{$w}px; margin-left:{$dwidth[$i]}px; margin-right:{$dwidth[$i]}px;'":'';
			$metinfo.="<li class='list' {$style}>";
			$metinfo.="<a href='{$val[url]}' title='{$val[title]}' {$metblank} class='img'><img src='{$val[imgurls]}' alt='{$val[title]}' title='{$val[title]}' width='{$met_img_x}' height='{$met_img_y}' /></a>";
			$metinfo.="<h3><a href='{$val[url]}' title='{$val[title]}' {$metblank}>{$val[title]}</a></h3>";
			$metinfo.="</li>";
			$i++;
			if($i==$l)$i=0;
		}
	}
	$metinfo.="</ul>";
	return $metinfo;
}
//图片模块列表函数
function metlabel_img($z,$w,$l,$n=0){
	global $img_list,$metblank,$met_img_style,$met_img_x,$met_img_y,$met_img_page,$class1,$class2,$class3,$search,$nav_list2,$nav_list3,$weburly,$id,$class_list,$module_listall;
	$met_img_x=$met_img_style?met_imgxy(1,'img'):$met_img_x;
	$met_img_y=$met_img_style?met_imgxy(2,'img'):$met_img_y;
	$metinfo.="<ul class='list-none metlist'>";
	$listarray=$img_list;
	$metok=0;
	if($met_img_page && $search<>'search'){
		if($class2 && count($nav_list3[$class2]) && !$class3){
			$listarray=$nav_list3[$class2];
			$metok=1;
		}
		if(!$class2 && count($nav_list2[$class1]) && $class1 && !$class3){
			$listarray=$nav_list2[$class1];
			$metok=1;
		}
		if($class_list[$class1]['module']=='101'){
			$listarray=array();
			foreach($module_listall[5] as $key=>$val){
				if($val['classtype']==1||$val['releclass']!=0){
					$listarray[]=$val;
				}
			}
			$metok=1;
		}
	}
	if($z){
		$l=$l?$l:floor($z/$w);
		$margin=(($z/$l)-$w)/2;
		$margin=$margin<0?(($z/(floor($z/$w)))-$w)/2:$margin;
		$dwidth=array();
		if(strstr($margin,".")){
			$margin=sprintf("%.1f",$margin);
			$y=explode('.',$margin);
			$f=(int)$y[0];
			$k='0.'.$y[1];
			$k=intval($k*$l);
			for($i=0;$i<$l;$i++){
				$m=$k<1?$f:$f+1;
				$dwidth[$i]=$m;
				$k=$k-1;
			}
		}else{
			for($i=0;$i<$l;$i++){
				$dwidth[$i]=$margin;
			}
		}
	}
	$i=0;
	foreach($listarray as $key=>$val){
		if(!$n || $id!=$val[id]){
			if($metok){
				$val['title']=$val['name'];
				$val['imgurls']=$val['columnimg']==''?$weburly.$met_agents_img:$val['columnimg'];
			}
			$style=$dwidth[$i]?"style='width:{$w}px; margin-left:{$dwidth[$i]}px; margin-right:{$dwidth[$i]}px;'":'';
			$metinfo.="<li class='list' {$style}>";
			$metinfo.="<a href='{$val[url]}' title='{$val[title]}' {$metblank} class='img'><img src='{$val[imgurls]}' alt='{$val[title]}' title='{$val[title]}' width='{$met_img_x}' height='{$met_img_y}' /></a>";
			$metinfo.="<h3><a href='{$val[url]}' title='{$val[title]}' {$metblank}>{$val[title]}</a></h3>";
			$metinfo.="</li>";
			$i++;
			if($i==$l)$i=0;
		}
	}
	$metinfo.="</ul>";
	return $metinfo;
}
//下载模块列表函数
function metlabel_download(){
	global $download_list,$metblank,$lang_Detail,$lang_Download,$lang_FileSize,$lang_Hits,$lang_UpdateTime;
	$i=0;
	foreach($download_list as $key=>$val){
	$i++;$top='';
	if($i==1)$top='top';
	$fiz=sprintf("%.2f",$val['filesize']/1024);
	$val['filesize']=$fiz>1?$fiz:$val['filesize'];
	$bd=$fiz>1?'Mb':'Kb';
		$metinfo.="<dl class='list-none metlist {$top}'>";
		$metinfo.="<dt>";
		$metinfo.="<a href='{$val[url]}' title='{$val[title]}' {$metblank}>{$val[title]}</a>";
		$metinfo.="</dt>";
		$metinfo.="<dd>";
		$metinfo.="<div>";
		$metinfo.="<a href='{$val[url]}' {$metblank} title='{$lang_Detail}'>{$lang_Detail}</a> - ";
		$metinfo.="<a href='{$val[downloadurl]}' class='down' {$metblank} title='{$lang_Download}'>{$lang_Download}</a>";
		$metinfo.="</div>";
		$metinfo.="<span><b>{$lang_FileSize}</b>：{$val[filesize]} {$bd}</span>";
		$metinfo.="<span><b>{$lang_Hits}</b>：{$val[hits]}</span>";
		$metinfo.="<span><b>{$lang_UpdateTime}</b>：{$val[updatetime]}</span>";
		$metinfo.="</dd>";
		$metinfo.="</dl>";
	}
	return $metinfo;
}
//招聘模块列表函数
function metlabel_job($type){
	global $job_list,$metblank,$lang_cvtitle,$lang_Detail,$lang_AddDate,$lang_WorkPlace,$lang_PersonNumber,$lang_Position,$lang_several;
	if($type==1){
		foreach($job_list as $key=>$val){
			$i++;$top='';
			if($i==1)$top='top';
			$val['count']=$val['count']?$val['count']:$lang_several;
			$metinfo.="<dl class='list-none metlist'>";
			$metinfo.="<dt><a href='{$val[url]}' title='{$val[position]}' {$metblank}>{$val[position]}</a></dt>";
			$metinfo.="<dd class='list {$top}'>";
			$metinfo.="<div class='mis'><span>{$lang_AddDate}：{$val[addtime]}</span>";
			$metinfo.="<span>{$lang_WorkPlace}：{$val[place]}</span>";
			$metinfo.="<span>{$lang_PersonNumber}：{$val[count]}</span></div>";
			$metinfo.="<div class='editor'>{$val[content]}</span></div>";
			$metinfo.="<div class='dtail'><span><a href='{$val[cv]}' title='{$lang_cvtitle}' {$metblank}>{$lang_cvtitle}</a></span>";
			$metinfo.="<span><a href='{$val[url]}' title='{$lang_Detail}' {$metblank}>{$lang_Detail}</a></span></div>";
			$metinfo.="</dl>";
		}
	}else{
		$metinfo.="<dl class='list-none metlist'>";
		$metinfo.="<dt>";
		$metinfo.="<span>{$lang_cvtitle}</span>";
		$metinfo.="<span>{$lang_Detail}</span>";
		$metinfo.="<span>{$lang_AddDate}</span>";
		$metinfo.="<span>{$lang_WorkPlace}</span>";
		$metinfo.="<span>{$lang_PersonNumber}</span>";
		$metinfo.="{$lang_Position}";
		$metinfo.="</dt>";
		$i=0;
		foreach($job_list as $key=>$val){
		$i++;$top='';
		if($i==1)$top='top';
		$val['count']=$val['count']?$val['count']:$lang_several;
			$metinfo.="<dd class='list {$top}'>";
			$metinfo.="<span><a href='{$val[cv]}' title='{$lang_cvtitle}' {$metblank}>{$lang_cvtitle}</a></span>";
			$metinfo.="<span><a href='{$val[url]}' title='{$lang_Detail}' {$metblank}>{$lang_Detail}</a></span>";
			$metinfo.="<span>{$val[addtime]}</span>";
			$metinfo.="<span>{$val[place]}</span>";
			$metinfo.="<span>{$val[count]}</span>";
			$metinfo.="<a href='{$val[url]}' title='{$val[position]}' {$metblank}>{$val[position]}</a>";
		}
		$metinfo.="</dl>";
	}
	return $metinfo;
}
//留言提交表单函数
function messagelabel_table($dy){
	global $lang,$message_list,$lang_Submit,$lang_Reset,$lang_Publish,$lang_Reply,$fromurl,$m_user_ip,$id,$title;
	global $met_memberlogin_code,$lang_memberImgCode,$lang_memberTip1,$met_adminfile,$navurl,$settings_arr;
	global $db,$met_parameter,$met_admin_array,$met_member_use,$metinfo_member_type,$met_list,$met_class,$class_list,$met_product,$lang_Choice,$lang_Empty;
	if($fid)$id=$fid;
	if(!$title){
		foreach($settings_arr as $key=>$val){
			if($val['columnid']==$id && $val['name']=='met_fdtable'){
				$title=$val['value'];
			}
		}
	}
	
	$query = "SELECT * FROM $met_parameter where lang='$lang' and  module=7 order by no_order";	
	$result = $db->query($query);
	while($list= $db->fetch_array($result)){
		if($list[type]==2 or $list[type]==4 or $list[type]==6){
			$listinfo=$db->get_one("select * from $met_list where bigid='$list[id]' and no_order=99999");
			$listinfoid=intval(trim($listinfo[info]));
			if($listinfo){
				$listmarknow='metinfo';
				$classtype=($listinfo[info]=='metinfoall')?$listinfoid:($met_class[$listinfoid][releclass]?'class1':'class'.$class_list[$listinfoid][classtype]);
				$query1 = "select * from $met_product where lang='$lang' and $classtype='$listinfoid' and recycle='0' order by updatetime desc";
				$result1 = $db->query($query1);
				$i=0;
				while($list1 = $db->fetch_array($result1)){
					$list1[info]=$list1[title];
					$i++;
					$list1[no_order]=$i;
					$paravalue[$list[id]][]=$list1;
				}
			}else{
				$query1 = "select * from $met_list where lang='$lang' and bigid='".$list[id]."' order by no_order";
				$result1 = $db->query($query1);
				while($list1 = $db->fetch_array($result1)){
					$paravalue[$list[id]][]=$list1;
				}
			}
		}
		if($list[wr_ok]=='1')$list[wr_must]="*";
		switch($list[type]){
			case 1:
				$list[input]="<input name='para$list[id]' type='text' size='30' class='input-text' />";
				break;
			case 2:
				$list[input]="<select name='para$list[id]'><option selected='selected' value=''>{$lang_Choice}</option>";
				foreach($paravalue[$list[id]] as $key=>$val){
					$list[input]=$list[input]."<option value='$val[info]'>$val[info]</option>";
				}
				$list[input]=$list[input]."</select>";
				break;
			case 3:
				$list[input]="<textarea name='para$list[id]' class='textarea-text' cols='50' rows='5'></textarea>";
				break;
			case 4:
				$i=0;
				foreach($paravalue[$list[id]] as $key=>$val){
					$i++;
					$list[input]=$list[input]."<input name='para$list[id]_$i' class='checboxcss' id='para$i$list[id]' type='checkbox' value='$val[info]' /><label for='para$i$list[id]'>$val[info]</label>&nbsp;&nbsp;";
				}
				$list[input]=$list[input]."<input name='para$list[id]' type='hidden' value='$i' />";
				$lagernum[$list[id]]=$i;
				break;
			case 5:
				$list[input]="<input name='para$list[id]' type='file' class='input' size='20' >";
				break;
			case 6:
				$i=0;
				foreach($paravalue[$list[id]] as $key=>$val){
					$checked='';
					$i++;
					if($i==1)$checked="checked='checked'";
					$list[input]=$list[input]."<input name='para$list[id]' type='radio' id='para$i$list[id]' value='$val[info]' $checked /><label for='para$i$list[id]'>$val[info]</label>  ";
				}
			break;
		}
		$fd_para[]=$list;
		if($list[wr_ok])$fdwr_list[]=$list;
	}
	
		$fdjs="<script language='javascript'>";
		$fdjs=$fdjs."function metmessagesubmit1(){ ";
		foreach($fdwr_list as $key=>$val){
			if($val[type]==1 or $val[type]==2 or $val[type]==3 or $val[type]==5){
				$fdjs=$fdjs."var length = document.myform.para$val[id].value.replace(/(^\s*)|(\s*$)/g, '');\n";
				$fdjs=$fdjs."if (length == 0) {\n";
				$fdjs=$fdjs."alert('$val[name] {$lang_Empty}');\n";
				$fdjs=$fdjs."document.myform.para$val[id].focus();\n";
				$fdjs=$fdjs."return false;}\n";
			}elseif($val[type]==4){
				$lagerinput="";
				for($j=1;$j<=count($paravalue[$val[id]]);$j++){
					$lagerinput=$lagerinput."document.myform.para$val[id]_$j.checked ||";
				}
				$lagerinput=$lagerinput."false\n";
				$fdjs=$fdjs."if(!($lagerinput)){\n";
				$fdjs=$fdjs."alert('$val[name] {$lang_Empty}');\n";
				$fdjs=$fdjs."document.myform.para$val[id]_1.focus();\n";
				$fdjs=$fdjs."return false;}\n";
			}
		}
		$fdjs=$fdjs."}</script>";
		$lujin='';
		if($dy)$lujin=$navurl.'message/';
		if($mobile){
			$metinfo1 =array();
			foreach($fd_para as $key=>$val){
				$wr_ok = $val[wr_ok]?'required':'';
				$metinfo='';
				switch($val[type]){
					case 1:
						$val[type_class]='input';
						$val[type_html]="<input name='para{$val[id]}' type='text' class='input-text {$wr_ok}' placeholder='{$val[name]}' />";
					break;
					case 2:
						$val[type_class]='select';
						$metinfo.="<span class='name'>{$val[name]}</span>";
						$metinfo.="<select name='para{$val[id]}'><option value=''>{$lang_Choice}</option>";
						foreach($paravalue[$val[id]] as $key=>$val1){
						$metinfo.="<option value='{$val1[info]}'>{$val1[info]}</option>";
						}
						$metinfo.="</select>";
						$val[type_html]=$metinfo;
					break;
					case 3:
						$val[type_class]='textarea';
						$val[type_html]="<textarea name='para{$val[id]}' class='textarea-text' placeholder='{$val[name]}'></textarea>";
					break;
					case 4:
						$val[type_class]='radio';
						$metinfo.="<span class='name'>{$val[name]}</span>";
						$i=0;
						foreach($paravalue[$val[id]] as $key=>$val1){
						$i++;
						$metinfo.="<label><input name='para{$val[id]}_{$i}' type='checkbox' value='{$val1[info]}' />{$val1[info]}</label>";
						}
						$metinfo.="<input name='para{$val[id]}' type='hidden' value='{$i}' />";
						$val[type_html]=$metinfo;
					break;
					case 6:
						$val[type_class]='radio';
						$metinfo.="<span class='name'>{$val[name]}</span>";
						$i=0;
						foreach($paravalue[$val[id]] as $key=>$val2){
						$i++;
						$checked=$i==1?'checked':'';
						$metinfo.="<label><input name='para{$val[id]}' type='radio' value='{$val2[info]}' {$checked} />{$val2[info]}</label>";
						}
						$val[type_html]=$metinfo;
					break;
				}
				$metinfo1[]=$val;
			}
			$metinfo = $metinfo1;
		}else{
			$metinfo =$fdjs;
			$metinfo.="<form enctype='multipart/form-data' method='POST' name='myform' onSubmit='return metmessagesubmit1();' action='{$lujin}message.php?action=add' target='_self'>\n";
			$metinfo.="<table class='feedback_table' >\n";
			foreach($fd_para as $key=>$val){
				$metinfo.="<tr>\n";
				$metinfo.="<td class='text'>".$val[name]."</td>\n";
				$metinfo.="<td class='input'>".$val[input]."<span class='info'>{$val[wr_must]}</span><span>{$val[description]}</span></td>\n";
				$metinfo.="</tr>\n";
			}
			if($met_memberlogin_code==1){
				$metinfo.="<tr><td class='text'>".$lang_memberImgCode."</td>\n";
				$metinfo.="<td class='input'><input name='code' onKeyUp='pressCaptcha(this)' type='text' class='code' id='code' size='6' maxlength='8' style='width:50px' />";
				$metinfo.="<img align='absbottom' src='{$navurl}member/ajax.php?action=code'  onclick=this.src='{$navurl}member/ajax.php?action=code&'+Math.random() style='cursor: pointer;' title='".$lang_memberTip1."'/>";
				$metinfo.="</td>\n";
				$metinfo.="</tr>\n";
			}
			$metinfo.="<tr><td class='text'></td>\n";
			$metinfo.="<td class='submint'>\n";
			$metinfo.="<input type='hidden' name='fdtitle' value='".$title."' />\n";
			$metinfo.="<input type='hidden' name='lang' value='".$lang."' />\n";
			$metinfo.="<input type='hidden' name='ip' value='".$m_user_ip."' />\n";
			$metinfo.="<input type='hidden' name='totnum' value='".count($fd_para)."' />\n";
			$metinfo.="<input type='hidden' name='id' value='".$id."' />\n";
			if($fid)$metinfo.="<input type='hidden' name='fid_url' value='1' />\n";//5.0.4
			$metinfo.="<input type='submit' name='Submit' value='".$lang_Submit."' class='submit button orange'></td></tr>\n";
			$metinfo.="</table>\n";
			$metinfo.="</form>\n";
		}
	
	return $metinfo;
}

//留言列表函数
function metlabel_messagelist(){
	global $lang,$message_list,$lang_SubmitContent,$lang_Reply,$total_count,$from_record,$db,$met_config,$met_mlist,$met_message_fd_class,$met_message_fd_content;
	$c=$total_count-$from_record;
	foreach($message_list as $key=>$val){
	$messagesNames1=$db->get_one("select * from $met_mlist where listid='$val[id]' and paraid='$met_message_fd_class' and lang='$lang'");
	$messagesNames2=$db->get_one("select * from $met_mlist where listid='$val[id]' and paraid='$met_message_fd_content' and lang='$lang'");
	$metinfo.="<dl class='list-none metlist'>\n";
	$metinfo.="<dt class='title'><span class='tt'>{$c}<sup>#</sup></span><span class='name'>{$messagesNames1[info]}</span><span class='time'>{$lang_Publish} {$val[addtime]}</span></dt>\n";
	$metinfo.="<dd class='info'><span class='tt'>{$lang_SubmitContent}</span><span class='text'>{$messagesNames2[info]}</span></dd>\n";
	$metinfo.="<dd class='reinfo'><span class='tt'>{$lang_Reply}</span><span class='text'>{$val[useinfo]}</span></dd>\n";
	$metinfo.="</dl>\n";
	$i--;
	$c--;
	}
	return $metinfo;
}
//反馈提交表单函数
function metlabel_feedback($fid,$mobile){
	global $lang,$message_list,$lang_Submit,$lang_Reset,$lang_Publish,$lang_Reply,$fromurl,$m_user_ip,$id,$title;
	global $met_memberlogin_code,$lang_memberImgCode,$lang_memberTip1,$met_adminfile,$navurl,$settings_arr;
	global $db,$met_parameter,$met_admin_array,$met_member_use,$metinfo_member_type,$met_list,$met_class,$class_list,$met_product,$lang_Choice,$lang_Empty;
	if($fid)$id=$fid;
	if(!$title){
		foreach($settings_arr as $key=>$val){
			if($val['columnid']==$id && $val['name']=='met_fdtable'){
				$title=$val['value'];
			}
		}
	}
	$query = "SELECT * FROM $met_parameter where lang='$lang' and  module=8 and class1='$id' order by no_order";
	if($met_member_use)$query = "select * from $met_parameter where (access in(select id from $met_admin_array where user_webpower<='$metinfo_member_type') or access=0) and lang='$lang' and module=8 and class1='$id' order by no_order;";
	$result = $db->query($query);
	while($list= $db->fetch_array($result)){
	 if($list[type]==2 or $list[type]==4 or $list[type]==6){
		$listinfo=$db->get_one("select * from $met_list where bigid='$list[id]' and no_order=99999");
		$listinfoid=intval(trim($listinfo[info]));
		if($listinfo){
		$listmarknow='metinfo';
		$classtype=($listinfo[info]=='metinfoall')?$listinfoid:($met_class[$listinfoid][releclass]?'class1':'class'.$class_list[$listinfoid][classtype]);
		$query1 = "select * from $met_product where lang='$lang' and $classtype='$listinfoid' and recycle='0' order by updatetime desc";
	   $result1 = $db->query($query1);
	   $i=0;
	   while($list1 = $db->fetch_array($result1)){
		 $list1[info]=$list1[title];
		 $i++;
		 $list1[no_order]=$i;
	   $paravalue[$list[id]][]=$list1;
	   }
		}else{
	   $query1 = "select * from $met_list where lang='$lang' and bigid='".$list[id]."' order by no_order";
	   $result1 = $db->query($query1);
	   while($list1 = $db->fetch_array($result1)){
	   $paravalue[$list[id]][]=$list1;
	   }
	   }}
	if($list[wr_ok]=='1')$list[wr_must]="*";
	switch($list[type]){
	case 1:
	$list[input]="<input name='para$list[id]' type='text' size='30' class='input-text' />";
	break;
	case 2:
	$list[input]="<select name='para$list[id]'><option selected='selected' value=''>{$lang_Choice}</option>";
	foreach($paravalue[$list[id]] as $key=>$val){
	$list[input]=$list[input]."<option value='$val[info]'>$val[info]</option>";
	}
	$list[input]=$list[input]."</select>";
	break;
	case 3:
	$list[input]="<textarea name='para$list[id]' class='textarea-text' cols='50' rows='5'></textarea>";
	break;
	case 4:
	$i=0;
	foreach($paravalue[$list[id]] as $key=>$val){
	$i++;
	$list[input]=$list[input]."<input name='para$list[id]_$i' class='checboxcss' id='para$i$list[id]' type='checkbox' value='$val[info]' /><label for='para$i$list[id]'>$val[info]</label>&nbsp;&nbsp;";
	}
	$list[input]=$list[input]."<input name='para$list[id]' type='hidden' value='$i' />";
	$lagernum[$list[id]]=$i;
	break;
	case 5:
	$list[input]="<input name='para$list[id]' type='file' class='input' size='20' >";
	break;
	case 6:
	$i=0;
	foreach($paravalue[$list[id]] as $key=>$val){
	$checked='';
	$i++;
	if($i==1)$checked="checked='checked'";
	$list[input]=$list[input]."<input name='para$list[id]' type='radio' id='para$i$list[id]' value='$val[info]' $checked /><label for='para$i$list[id]'>$val[info]</label>  ";
	 }
	break;
	}
	$fd_para[]=$list;
	if($list[wr_ok])$fdwr_list[]=$list;
	}
	$fdjs="<script language='javascript'>";
	$fdjs=$fdjs."function Checkfeedback(){ ";
	foreach($fdwr_list as $key=>$val){
	if($val[type]==1 or $val[type]==2 or $val[type]==3 or $val[type]==5){
	$fdjs=$fdjs."var length = document.myform.para$val[id].value.replace(/(^\s*)|(\s*$)/g, '');\n";
	$fdjs=$fdjs."if (length == 0) {\n";
	$fdjs=$fdjs."alert('$val[name] {$lang_Empty}');\n";
	$fdjs=$fdjs."document.myform.para$val[id].focus();\n";
	$fdjs=$fdjs."return false;}\n";
	}elseif($val[type]==4){
	 $lagerinput="";
	 for($j=1;$j<=count($paravalue[$val[id]]);$j++){
	 $lagerinput=$lagerinput."document.myform.para$val[id]_$j.checked ||";
	 }
	 $lagerinput=$lagerinput."false\n";
	 $fdjs=$fdjs."if(!($lagerinput)){\n";
	 $fdjs=$fdjs."alert('$val[name] {$lang_Empty}');\n";
	 $fdjs=$fdjs."document.myform.para$val[id]_1.focus();\n";
	 $fdjs=$fdjs."return false;}\n";
	}
	}
	$fdjs=$fdjs."}</script>";
	$lujin='';
	if($fid)$lujin=$navurl.'feedback/';
	if($mobile){
		$metinfo1 =array();
		foreach($fd_para as $key=>$val){
			$wr_ok = $val[wr_ok]?'required':'';
			$metinfo='';
			switch($val[type]){
				case 1:
					$val[type_class]='input';
					$val[type_html]="<input name='para{$val[id]}' type='text' class='input-text {$wr_ok}' placeholder='{$val[name]}' />";
				break;
				case 2:
					$val[type_class]='select';
					$metinfo.="<span class='name'>{$val[name]}</span>";
					$metinfo.="<select name='para{$val[id]}'><option value=''>{$lang_Choice}</option>";
					foreach($paravalue[$val[id]] as $key=>$val1){
					$metinfo.="<option value='{$val1[info]}'>{$val1[info]}</option>";
					}
					$metinfo.="</select>";
					$val[type_html]=$metinfo;
				break;
				case 3:
					$val[type_class]='textarea';
					$val[type_html]="<textarea name='para{$val[id]}' class='textarea-text' placeholder='{$val[name]}'></textarea>";
				break;
				case 4:
					$val[type_class]='radio';
					$metinfo.="<span class='name'>{$val[name]}</span>";
					$i=0;
					foreach($paravalue[$val[id]] as $key=>$val1){
					$i++;
					$metinfo.="<label><input name='para{$val[id]}_{$i}' type='checkbox' value='{$val1[info]}' />{$val1[info]}</label>";
					}
					$metinfo.="<input name='para{$val[id]}' type='hidden' value='{$i}' />";
					$val[type_html]=$metinfo;
				break;
				case 6:
					$val[type_class]='radio';
					$metinfo.="<span class='name'>{$val[name]}</span>";
					$i=0;
					foreach($paravalue[$val[id]] as $key=>$val2){
					$i++;
					$checked=$i==1?'checked':'';
					$metinfo.="<label><input name='para{$val[id]}' type='radio' value='{$val2[info]}' {$checked} />{$val2[info]}</label>";
					}
					$val[type_html]=$metinfo;
				break;
			}
			$metinfo1[]=$val;
		}
		$metinfo = $metinfo1;
	}else{
     $metinfo =$fdjs;
     $metinfo.="<form enctype='multipart/form-data' method='POST' name='myform' onSubmit='return Checkfeedback();' action='{$lujin}index.php?action=add&lang=".$lang."' target='_self'>\n";
     $metinfo.="<table class='feedback_table' >\n";
    foreach($fd_para as $key=>$val){
     $metinfo.="<tr>\n";
     $metinfo.="<td class='text'>".$val[name]."</td>\n";
     $metinfo.="<td class='input'>".$val[input]."<span class='info'>{$val[wr_must]}</span><span>{$val[description]}</span></td>\n";
     $metinfo.="</tr>\n";
    }
if($met_memberlogin_code==1){  
     $metinfo.="<tr><td class='text'>".$lang_memberImgCode."</td>\n";
     $metinfo.="<td class='input'><input name='code' onKeyUp='pressCaptcha(this)' type='text' class='code' id='code' size='6' maxlength='8' style='width:50px' />";
     $metinfo.="<img align='absbottom' src='{$navurl}member/ajax.php?action=code'  onclick=this.src='{$navurl}member/ajax.php?action=code&'+Math.random() style='cursor: pointer;' title='".$lang_memberTip1."'/>";
     $metinfo.="</td>\n";
     $metinfo.="</tr>\n";
}
	 $metinfo.="<tr><td class='text'></td>\n";
	 $metinfo.="<td class='submint'>\n";
     $metinfo.="<input type='hidden' name='fdtitle' value='".$title."' />\n";
     $metinfo.="<input type='hidden' name='lang' value='".$lang."' />\n";
     $metinfo.="<input type='hidden' name='ip' value='".$m_user_ip."' />\n";
	 $metinfo.="<input type='hidden' name='totnum' value='".count($fd_para)."' />\n";
	 $metinfo.="<input type='hidden' name='id' value='".$id."' />\n";
	 if($fid)$metinfo.="<input type='hidden' name='fid_url' value='1' />\n";//5.0.4
     $metinfo.="<input type='submit' name='Submit' value='".$lang_Submit."' class='submit button orange'></td></tr>\n";
     $metinfo.="</table>\n";
     $metinfo.="</form>\n";
	}
	return $metinfo;
}
//会员提交表单函数
function metlabel_member($fid,$mobile){ 
	global $lang,$message_list,$lang_Submit,$lang_Reset,$lang_Publish,$lang_Reply,$fromurl,$m_user_ip,$id,$title;
	global $met_memberlogin_code,$lang_memberImgCode,$lang_memberTip1,$met_adminfile,$navurl,$settings_arr;
	global $db,$lang_memberRegisterName,$lang_js7,$lang_js26,$lang_memberPassword,$lang_js8,$lang_js9,$lang_js10,$lang_js12,$lang_js11,$lang_js13,$lang_memberName,$lang_membereditorPs,$lang_membereditorPs1,$lang_Email,$lang_memberbasicCompanyName,$met_parameter,$met_admin_array,$met_member_use,$metinfo_member_type,$met_list,$met_class,$class_list,$met_product,$lang_Choice,$lang_Empty,$lang_memberRegister;
	if($fid)$id=$fid;
	if(!$title){
		foreach($settings_arr as $key=>$val){
			if($val['columnid']==$id && $val['name']=='met_fdtable'){
				$title=$val['value'];
			}
		}
	}
	$query = "SELECT * FROM $met_parameter where lang='$lang' and  module=10 and wr_oks='1' order by no_order";
	if($met_member_use)$query = "select * from $met_parameter where (access in(select id from $met_admin_array where user_webpower<='$metinfo_member_type') or access=0) and lang='$lang' and module=10 and wr_oks='1' order by no_order;";
	$result = $db->query($query);
	while($list= $db->fetch_array($result)){
	 if($list[type]==2 or $list[type]==4 or $list[type]==6){
		$listinfo=$db->get_one("select * from $met_list where bigid='$list[id]' and no_order=99999");
		$listinfoid=intval(trim($listinfo[info]));
		if($listinfo){
		$listmarknow='metinfo';
		$classtype=($listinfo[info]=='metinfoall')?$listinfoid:($met_class[$listinfoid][releclass]?'class1':'class'.$class_list[$listinfoid][classtype]);
		$query1 = "select * from $met_product where lang='$lang' and $classtype='$listinfoid' order by updatetime desc";
	   $result1 = $db->query($query1);
	   $i=0;
	   while($list1 = $db->fetch_array($result1)){
		 $list1[info]=$list1[title];
		 $i++;
		 $list1[no_order]=$i;
	   $paravalue[$list[id]][]=$list1;
	   }
		}else{
	   $query1 = "select * from $met_list where lang='$lang' and bigid='".$list[id]."' order by no_order";
	   $result1 = $db->query($query1);
	   while($list1 = $db->fetch_array($result1)){
	   $paravalue[$list[id]][]=$list1;
	   }
	   }}
	if($list[wr_ok]=='1')$list[wr_must]="*";
	switch($list[type]){
	case 1:
	$list[input]="<input name='para$list[id]' type='text' size='30' class='input_text' />";
	break;
	case 2:
	$list[input]="<select name='para$list[id]'><option selected='selected' value=''>{$lang_Choice}</option>";
	foreach($paravalue[$list[id]] as $key=>$val){
	$list[input]=$list[input]."<option value='$val[info]'>$val[info]</option>";
	}
	$list[input]=$list[input]."</select>";
	break;
	case 3:
	$list[input]="<textarea name='para$list[id]' class='textarea_text' cols='50' rows='5'></textarea>";
	break;
	case 4:
	$i=0;
	foreach($paravalue[$list[id]] as $key=>$val){
	$i++;
	$list[input]=$list[input]."<input name='para$list[id]_$i' class='checkbox_checboxcss' id='para$i$list[id]' type='checkbox' value='$i' /><label for='para$i$list[id]'>$val[info]</label>&nbsp;&nbsp;";
	}
	$list[input]=$list[input]."<input name='para$list[id]' type='hidden' value='$i' />";
	$lagernum[$list[id]]=$i;
	break;
	case 5:
	$list[input]="<input name='para$list[id]' type='file' class='input' size='20' >";
	break;
	case 6:
	$i=0;
	foreach($paravalue[$list[id]] as $key=>$val){
	$checked='';
	$i++;
	if($i==1)$checked="checked='checked'";
	$list[input]=$list[input]."<input name='para$list[id]' class='radio_radiocss' type='radio' id='para$i$list[id]' value='$val[info]' $checked /><label for='para$i$list[id]'>$val[info]</label>  ";
	 }
	break;
	}
	$fd_para[]=$list;
	if($list[wr_ok])$fdwr_list[]=$list;
	}
	$fdjs="<script language='javascript'>";
	$fdjs=$fdjs."function Checkmember(){ ";
    $fdjs.="if(trim(document.myform.yhid.value) == '') 
         { alert('{$lang_memberRegisterName}');
		   document.myform.yhid.focus();
		   document.myform.yhid.select(); 
           return false;
         }
	   if(trim(document.myform.yhid.value).length<3) 
         { alert('{$lang_js7}');
		   document.myform.yhid.focus();
		   document.myform.yhid.select(); 
           return false;
         }

	   if(trim(document.myform.yhid.value).length >15)
	   {	alert('{$lang_js26}');
			document.myform.yhid.focus();
			document.myform.yhid.select();
			return false; 
	   }
		 
        else if(trim(document.myform.mm.value)=='')
         { alert('{$lang_memberPassword}');
           document.myform.mm.focus();
		   document.myform.mm.select(); 
		   return false;
         }
		  else if ((document.myform.mm.value).length<6)
          {alert('{$lang_js8}');
		   document.myform.mm.focus();
		   document.myform.mm.select(); 
           return false;
          }
		 else if(trim(document.myform.mm1.value)=='')
         { alert('{$lang_js9}');
           document.myform.mm.focus();
		   document.myform.mm.select(); 
		   return false;
         }
		 else if(document.myform.mm1.value!=document.myform.mm.value)
         { alert('{$lang_js10}');
           document.myform.mm.focus();
		   document.myform.mm.select(); 
		   return false;
         }
	   else if(trim(document.myform.email.value)=='')
         { alert('{$lang_js12}');
           document.myform.email.focus();
		   document.myform.email.select(); 
		   return false;
         }
	  else if(!isValidEmail(document.myform.email.value))
         { alert('{$lang_js13}');
           document.myform.email.focus();
		   document.myform.email.select(); 
		   return false;
         }";
	foreach($fdwr_list as $key=>$val){
	if($val[type]==1 or $val[type]==2 or $val[type]==3 or $val[type]==5){
	$fdjs=$fdjs."if (document.myform.para$val[id].value.length == 0) {\n";
	$fdjs=$fdjs."alert('$val[name] {$lang_Empty}');\n";
	$fdjs=$fdjs."document.myform.para$val[id].focus();\n";
	$fdjs=$fdjs."return false;}\n";
	}elseif($val[type]==4){
	 $lagerinput="";
	 for($j=1;$j<=count($paravalue[$val[id]]);$j++){
	 $lagerinput=$lagerinput."document.myform.para$val[id]_$j.checked ||";
	 }
	 $lagerinput=$lagerinput."false\n";
	 $fdjs=$fdjs."if(!($lagerinput)){\n";
	 $fdjs=$fdjs."alert('$val[name] {$lang_Empty}');\n";
	 $fdjs=$fdjs."document.myform.para$val[id]_1.focus();\n";
	 $fdjs=$fdjs."return false;}\n";
	}
	}
	$fdjs=$fdjs."}</script>";
	$lujin='';
	if($fid)$lujin=$navurl.'member/';
	if($mobile){
		$metinfo1 =array();
		foreach($fd_para as $key=>$val){
			$wr_ok = $val[wr_ok]?'required':'';
			$metinfo='';
			switch($val[type]){
				case 1:
					$val[type_class]='input';
					$val[type_html]="<input name='para{$val[id]}' type='text' class='input-text {$wr_ok}' placeholder='{$val[name]}' />";
				break;
				case 2:
					$val[type_class]='select';
					$metinfo.="<span class='name'>{$val[name]}</span>";
					$metinfo.="<select name='para{$val[id]}'><option value=''>{$lang_Choice}</option>";
					foreach($paravalue[$val[id]] as $key=>$val1){
					$metinfo.="<option value='{$val1[info]}'>{$val1[info]}</option>";
					}
					$metinfo.="</select>";
					$val[type_html]=$metinfo;
				break;
				case 3:
					$val[type_class]='textarea';
					$val[type_html]="<textarea name='para{$val[id]}' class='textarea-text' placeholder='{$val[name]}'></textarea>";
				break;
				case 4:
					$val[type_class]='radio';
					$metinfo.="<span class='name'>{$val[name]}</span>";
					$i=0;
					foreach($paravalue[$val[id]] as $key=>$val1){
					$i++;
					$metinfo.="<label><input name='para{$val[id]}_{$i}' type='checkbox' value='{$val1[info]}' />{$val1[info]}</label>";
					}
					$metinfo.="<input name='para{$val[id]}' type='hidden' value='{$i}' />";
					$val[type_html]=$metinfo;
				break;
				case 6:
					$val[type_class]='radio';
					$metinfo.="<span class='name'>{$val[name]}</span>";
					$i=0;
					foreach($paravalue[$val[id]] as $key=>$val2){
					$i++;
					$checked=$i==1?'checked':'';
					$metinfo.="<label><input name='para{$val[id]}' type='radio' value='{$val2[info]}' {$checked} />{$val2[info]}</label>";
					}
					$val[type_html]=$metinfo;
				break;
			}
			$metinfo1[]=$val;
		}
		$metinfo = $metinfo1;
	}else{
     $metinfo =$fdjs;
     $metinfo.="<form enctype='multipart/form-data' method='POST' name='myform' onSubmit='return Checkmember();' action='save.php?action=add&lang=".$lang."' target='_self'>\n";
     $metinfo.="<table cellpadding=\"1\" cellspacing=\"1\">\n";
	 $metinfo.="<tr>
	<td class='zhuce_text'><span class='reqd'>*</span><span>{$lang_memberName}</span></td>
	<td class='zhuce_input'><input id='yhid' name='yhid' type='text' size='30' class='input_text' /></td>
	</tr>
	<tr>
	<td class='zhuce_text'><span class='reqd'>*</span><span>{$lang_membereditorPs}</span></td>
	<td class='zhuce_input'><input name='mm' id='mm' type='password' size='30' class='input_text' /></td>
	</tr>
	<tr>
	<td class='zhuce_text'><span class='reqd'>*</span><span>{$lang_membereditorPs1}</span></td>
	<td class='zhuce_input'><input  name='mm1' id='mm1' type='password' size='30' class='input_text' /></td>
	</tr>
	<tr>
	<td class='zhuce_text'><span class='reqd'>*</span><span>{$lang_Email}</span></td>
    <td class='zhuce_input'><input id='email' name='email' type='text' size='30' class='input_text' /></td>
	</tr>
	";
    foreach($fd_para as $key=>$val){
     $metinfo.="<tr>\n";
     $metinfo.="<td class='zhuce_text'><span class='reqd'>{$val[wr_must]}</span>".$val[name]."</td>\n";
     $metinfo.="<td class='zhuce_input'>".$val[input]."<span>{$val[description]}</span></td>\n";
     $metinfo.="</tr>\n";
    }
if($met_memberlogin_code==1){  
     $metinfo.="<tr><td class='zhuce_text'><span class='reqd'>*</span>".$lang_memberImgCode."</td>\n";
     $metinfo.="<td class='zhuce_input'><input name='code' onKeyUp='pressCaptcha(this)' type='text' class='input_text' id='code' size='6' maxlength='8' style='width:50px' />";
     $metinfo.="<img align='absbottom' src='{$navurl}member/ajax.php?action=code'  onclick=this.src='{$navurl}member/ajax.php?action=code&'+Math.random() style='cursor: pointer;' title='".$lang_memberTip1."'/>";
     $metinfo.="</td>\n";
     $metinfo.="</tr>\n";
}
	 $metinfo.="<tr><td class='zhuce_text'></td>\n";
	 $metinfo.="<td class='zhuce_subimt'>\n";
     $metinfo.="<input type='hidden' name='fdtitle' value='".$title."' />\n";
     $metinfo.="<input type='hidden' name='lang' value='".$lang."' />\n";
     $metinfo.="<input type='hidden' name='ip' value='".$m_user_ip."' />\n";
	 $metinfo.="<input type='hidden' name='totnum' value='".count($fd_para)."' />\n";
	 $metinfo.="<input type='hidden' name='id' value='".$id."' />\n";
	 if($fid)$metinfo.="<input type='hidden' name='fid_url' value='1' />\n";//5.0.4
     $metinfo.="<input type='submit' name='Submit' value='".$lang_memberRegister."' class='submit button orange'></td></tr>\n";
     $metinfo.="</table>\n";
     $metinfo.="</form>\n";
	}
	return $metinfo;
}
//留言提交表单函数
function metlabel_message($fid,$mobile){
	global $lang,$message_list,$lang_Submit,$lang_Reset,$lang_Publish,$lang_Reply,$fromurl,$m_user_ip,$id,$title;
	global $met_memberlogin_code,$lang_memberImgCode,$lang_memberTip1,$met_adminfile,$navurl,$settings_arr;
	global $db,$met_parameter,$met_admin_array,$met_member_use,$metinfo_member_type,$met_list,$met_class,$class_list,$met_product,$lang_Choice,$lang_Empty;
	if($fid)$id=$fid;
	if(!$title){
		foreach($settings_arr as $key=>$val){
			if($val['columnid']==$id && $val['name']=='met_fdtable'){
				$title=$val['value'];
			}
		}
	}
	$query = "SELECT * FROM $met_parameter where lang='$lang' and  module=7 and class1='$id' order by no_order";
	if($met_member_use)$query = "select * from $met_parameter where (access in(select id from $met_admin_array where user_webpower<='$metinfo_member_type') or access=0) and lang='$lang' and module=7 and class1='$id' order by no_order;";
	$result = $db->query($query);
	while($list= $db->fetch_array($result)){
	 if($list[type]==2 or $list[type]==4 or $list[type]==6){
		$listinfo=$db->get_one("select * from $met_list where bigid='$list[id]' and no_order=99999");
		$listinfoid=intval(trim($listinfo[info]));
		if($listinfo){
		$listmarknow='metinfo';
		$classtype=($listinfo[info]=='metinfoall')?$listinfoid:($met_class[$listinfoid][releclass]?'class1':'class'.$class_list[$listinfoid][classtype]);
		$query1 = "select * from $met_product where lang='$lang' and $classtype='$listinfoid' and recycle='0' order by updatetime desc";
	   $result1 = $db->query($query1);
	   $i=0;
	   while($list1 = $db->fetch_array($result1)){
		 $list1[info]=$list1[title];
		 $i++;
		 $list1[no_order]=$i;
	   $paravalue[$list[id]][]=$list1;
	   }
		}else{
	   $query1 = "select * from $met_list where lang='$lang' and bigid='".$list[id]."' order by no_order";
	   $result1 = $db->query($query1);
	   while($list1 = $db->fetch_array($result1)){
	   $paravalue[$list[id]][]=$list1;
	   }
	   }}
	if($list[wr_ok]=='1')$list[wr_must]="*";
	switch($list[type]){
	case 1:
	$list[input]="<input name='para$list[id]' type='text' size='30' class='input-text' />";
	break;
	case 2:
	$list[input]="<select name='para$list[id]'><option selected='selected' value=''>{$lang_Choice}</option>";
	foreach($paravalue[$list[id]] as $key=>$val){
	$list[input]=$list[input]."<option value='$val[info]'>$val[info]</option>";
	}
	$list[input]=$list[input]."</select>";
	break;
	case 3:
	$list[input]="<textarea name='para$list[id]' class='textarea-text' cols='50' rows='5'></textarea>";
	break;
	case 4:
	$i=0;
	foreach($paravalue[$list[id]] as $key=>$val){
	$i++;
	$list[input]=$list[input]."<input name='para$list[id]_$i' class='checboxcss' id='para$i$list[id]' type='checkbox' value='$val[info]' /><label for='para$i$list[id]'>$val[info]</label>&nbsp;&nbsp;";
	}
	$list[input]=$list[input]."<input name='para$list[id]' type='hidden' value='$i' />";
	$lagernum[$list[id]]=$i;
	break;
	case 5:
	$list[input]="<input name='para$list[id]' type='file' class='input' size='20' >";
	break;
	case 6:
	$i=0;
	foreach($paravalue[$list[id]] as $key=>$val){
	$checked='';
	$i++;
	if($i==1)$checked="checked='checked'";
	$list[input]=$list[input]."<input name='para$list[id]' type='radio' id='para$i$list[id]' value='$val[info]' $checked /><label for='para$i$list[id]'>$val[info]</label>  ";
	 }
	break;
	}
	$fd_para[]=$list;
	if($list[wr_ok])$fdwr_list[]=$list;
	}
	$fdjs="<script language='javascript'>";
	$fdjs=$fdjs."function metmessagesubmit1(){ ";
	foreach($fdwr_list as $key=>$val){
	if($val[type]==1 or $val[type]==2 or $val[type]==3 or $val[type]==5){
	$fdjs=$fdjs."var length = document.myform.para$val[id].value.replace(/(^\s*)|(\s*$)/g, '');\n";
	$fdjs=$fdjs."if (length == 0) {\n";
	$fdjs=$fdjs."alert('$val[name] {$lang_Empty}');\n";
	$fdjs=$fdjs."document.myform.para$val[id].focus();\n";
	$fdjs=$fdjs."return false;}\n";
	}elseif($val[type]==4){
	 $lagerinput="";
	 for($j=1;$j<=count($paravalue[$val[id]]);$j++){
	 $lagerinput=$lagerinput."document.myform.para$val[id]_$j.checked ||";
	 }
	 $lagerinput=$lagerinput."false\n";
	 $fdjs=$fdjs."if(!($lagerinput)){\n";
	 $fdjs=$fdjs."alert('$val[name] {$lang_Empty}');\n";
	 $fdjs=$fdjs."document.myform.para$val[id]_1.focus();\n";
	 $fdjs=$fdjs."return false;}\n";
	}
	}
	$fdjs=$fdjs."}</script>";
	$lujin='';
	if($fid)$lujin=$navurl.'message/';
	if($mobile){
		$metinfo1 =array();
		foreach($fd_para as $key=>$val){
			$wr_ok = $val[wr_ok]?'required':'';
			$metinfo='';
			switch($val[type]){
				case 1:
					$val[type_class]='input';
					$val[type_html]="<input name='para{$val[id]}' type='text' class='input-text {$wr_ok}' placeholder='{$val[name]}' />";
				break;
				case 2:
					$val[type_class]='select';
					$metinfo.="<span class='name'>{$val[name]}</span>";
					$metinfo.="<select name='para{$val[id]}'><option value=''>{$lang_Choice}</option>";
					foreach($paravalue[$val[id]] as $key=>$val1){
					$metinfo.="<option value='{$val1[info]}'>{$val1[info]}</option>";
					}
					$metinfo.="</select>";
					$val[type_html]=$metinfo;
				break;
				case 3:
					$val[type_class]='textarea';
					$val[type_html]="<textarea name='para{$val[id]}' class='textarea-text' placeholder='{$val[name]}'></textarea>";
				break;
				case 4:
					$val[type_class]='radio';
					$metinfo.="<span class='name'>{$val[name]}</span>";
					$i=0;
					foreach($paravalue[$val[id]] as $key=>$val1){
					$i++;
					$metinfo.="<label><input name='para{$val[id]}_{$i}' type='checkbox' value='{$val1[info]}' />{$val1[info]}</label>";
					}
					$metinfo.="<input name='para{$val[id]}' type='hidden' value='{$i}' />";
					$val[type_html]=$metinfo;
				break;
				case 6:
					$val[type_class]='radio';
					$metinfo.="<span class='name'>{$val[name]}</span>";
					$i=0;
					foreach($paravalue[$val[id]] as $key=>$val2){
					$i++;
					$checked=$i==1?'checked':'';
					$metinfo.="<label><input name='para{$val[id]}' type='radio' value='{$val2[info]}' {$checked} />{$val2[info]}</label>";
					}
					$val[type_html]=$metinfo;
				break;
			}
			$metinfo1[]=$val;
		}
		$metinfo = $metinfo1;
	}else{
     $metinfo =$fdjs;
     $metinfo.="<form enctype='multipart/form-data' method='POST' name='myform' onSubmit='return metmessagesubmit1();' action='{$lujin}message.php?action=add&lang=".$lang."' target='_self'>\n";
     $metinfo.="<table class='feedback_table' >\n";
    foreach($fd_para as $key=>$val){
     $metinfo.="<tr>\n";
     $metinfo.="<td class='text'>".$val[name]."</td>\n";
     $metinfo.="<td class='input'>".$val[input]."<span class='info'>{$val[wr_must]}</span><span>{$val[description]}</span></td>\n";
     $metinfo.="</tr>\n";
    }
if($met_memberlogin_code==1){  
     $metinfo.="<tr><td class='text'>".$lang_memberImgCode."</td>\n";
     $metinfo.="<td class='input'><input name='code' onKeyUp='pressCaptcha(this)' type='text' class='code' id='code' size='6' maxlength='8' style='width:50px' />";
     $metinfo.="<img align='absbottom' src='{$navurl}member/ajax.php?action=code'  onclick=this.src='{$navurl}member/ajax.php?action=code&'+Math.random() style='cursor: pointer;' title='".$lang_memberTip1."'/>";
     $metinfo.="</td>\n";
     $metinfo.="</tr>\n";
}
	 $metinfo.="<tr><td class='text'></td>\n";
	 $metinfo.="<td class='submint'>\n";
     $metinfo.="<input type='hidden' name='fdtitle' value='".$title."' />\n";
     $metinfo.="<input type='hidden' name='lang' value='".$lang."' />\n";
     $metinfo.="<input type='hidden' name='ip' value='".$m_user_ip."' />\n";
	 $metinfo.="<input type='hidden' name='totnum' value='".count($fd_para)."' />\n";
	 $metinfo.="<input type='hidden' name='id' value='".$id."' />\n";
	 if($fid)$metinfo.="<input type='hidden' name='fid_url' value='1' />\n";//5.0.4
     $metinfo.="<input type='submit' name='Submit' value='".$lang_Submit."' class='submit button orange'></td></tr>\n";
     $metinfo.="</table>\n";
     $metinfo.="</form>\n";
	}
	return $metinfo;
}
//留言提交表单函数（兼容metv5以前模板，不建议使用，建议使用metlabel_message()）
function metlabel_messageold($fid,$mobile){
	global $lang,$message_list,$lang_Submit,$lang_Reset,$lang_Publish,$lang_Reply,$fromurl,$m_user_ip,$id,$title,$lang_SubmitInfo;
	global $met_memberlogin_code,$lang_memberImgCode,$lang_memberTip1,$met_adminfile,$navurl,$settings_arr;
	global $db,$met_parameter,$met_admin_array,$met_member_use,$metinfo_member_type,$met_list,$met_class,$class_list,$met_product,$lang_Choice,$lang_Empty;		
	if($fid)$id=$fid;
	if(!$title){
		foreach($settings_arr as $key=>$val){
			if($val['columnid']==$id && $val['name']=='met_fdtable'){
				$title=$val['value'];
			}
		}
	}
	$query = "SELECT * FROM $met_parameter where lang='$lang' and  module=7 and class1='$id' order by no_order";
	if($met_member_use)$query = "select * from $met_parameter where (access in(select id from $met_admin_array where user_webpower<='$metinfo_member_type') or access=0) and lang='$lang' and module=7 and class1='$id' order by no_order;";
	$result = $db->query($query);
	while($list= $db->fetch_array($result)){
	 if($list[type]==2 or $list[type]==4 or $list[type]==6){
		$listinfo=$db->get_one("select * from $met_list where bigid='$list[id]' and no_order=99999");
		$listinfoid=intval(trim($listinfo[info]));
		if($listinfo){
		$listmarknow='metinfo';
		$classtype=($listinfo[info]=='metinfoall')?$listinfoid:($met_class[$listinfoid][releclass]?'class1':'class'.$class_list[$listinfoid][classtype]);
		$query1 = "select * from $met_product where lang='$lang' and $classtype='$listinfoid' and recycle='0' order by updatetime desc";
	   $result1 = $db->query($query1);
	   $i=0;
	   while($list1 = $db->fetch_array($result1)){
		 $list1[info]=$list1[title];
		 $i++;
		 $list1[no_order]=$i;
	   $paravalue[$list[id]][]=$list1;
	   }
		}else{
	   $query1 = "select * from $met_list where lang='$lang' and bigid='".$list[id]."' order by no_order";
	   $result1 = $db->query($query1);
	   while($list1 = $db->fetch_array($result1)){
	   $paravalue[$list[id]][]=$list1;
	   }
	   }}
	if($list[wr_ok]=='1')$list[wr_must]="*";
	switch($list[type]){
	case 1:
	$list[input]="<input name='para$list[id]' type='text' size='30' class='input-text' />";
	break;
	case 2:
	$list[input]="<select name='para$list[id]'><option selected='selected' value=''>{$lang_Choice}</option>";
	foreach($paravalue[$list[id]] as $key=>$val){
	$list[input]=$list[input]."<option value='$val[info]'>$val[info]</option>";
	}
	$list[input]=$list[input]."</select>";
	break;
	case 3:
	$list[input]="<textarea name='para$list[id]' class='textarea-text' cols='50' rows='5'></textarea>";
	break;
	case 4:
	$i=0;
	foreach($paravalue[$list[id]] as $key=>$val){
	$i++;
	$list[input]=$list[input]."<input name='para$list[id]_$i' class='checboxcss' id='para$i$list[id]' type='checkbox' value='$val[info]' /><label for='para$i$list[id]'>$val[info]</label>&nbsp;&nbsp;";
	}
	$list[input]=$list[input]."<input name='para$list[id]' type='hidden' value='$i' />";
	$lagernum[$list[id]]=$i;
	break;
	case 5:
	$list[input]="<input name='para$list[id]' type='file' class='input' size='20' >";
	break;
	case 6:
	$i=0;
	foreach($paravalue[$list[id]] as $key=>$val){
	$checked='';
	$i++;
	if($i==1)$checked="checked='checked'";
	$list[input]=$list[input]."<input name='para$list[id]' type='radio' id='para$i$list[id]' value='$val[info]' $checked /><label for='para$i$list[id]'>$val[info]</label>  ";
	 }
	break;
	}
	$fd_para[]=$list;
	if($list[wr_ok])$fdwr_list[]=$list;
	}
	$fdjs="<script language='javascript'>";
	$fdjs=$fdjs."function metmessagesubmit1(){ ";
	foreach($fdwr_list as $key=>$val){
	if($val[type]==1 or $val[type]==2 or $val[type]==3 or $val[type]==5){
	$fdjs=$fdjs."var length = document.myform.para$val[id].value.replace(/(^\s*)|(\s*$)/g, '');\n";
	$fdjs=$fdjs."if (length == 0) {\n";
	$fdjs=$fdjs."alert('$val[name] {$lang_Empty}');\n";
	$fdjs=$fdjs."document.myform.para$val[id].focus();\n";
	$fdjs=$fdjs."return false;}\n";
	}elseif($val[type]==4){
	 $lagerinput="";
	 for($j=1;$j<=count($paravalue[$val[id]]);$j++){
	 $lagerinput=$lagerinput."document.myform.para$val[id]_$j.checked ||";
	 }
	 $lagerinput=$lagerinput."false\n";
	 $fdjs=$fdjs."if(!($lagerinput)){\n";
	 $fdjs=$fdjs."alert('$val[name] {$lang_Empty}');\n";
	 $fdjs=$fdjs."document.myform.para$val[id]_1.focus();\n";
	 $fdjs=$fdjs."return false;}\n";
	}
	}
	$fdjs=$fdjs."}</script>";
	$lujin='';
	if($fid)$lujin=$navurl.'message/';
	if($mobile){
		$metinfo1 =array();
		foreach($fd_para as $key=>$val){
			$wr_ok = $val[wr_ok]?'required':'';
			$metinfo='';
			switch($val[type]){
				case 1:
					$val[type_class]='input';
					$val[type_html]="<input name='para{$val[id]}' type='text' class='input-text {$wr_ok}' placeholder='{$val[name]}' />";
				break;
				case 2:
					$val[type_class]='select';
					$metinfo.="<span class='name'>{$val[name]}</span>";
					$metinfo.="<select name='para{$val[id]}'><option value=''>{$lang_Choice}</option>";
					foreach($paravalue[$val[id]] as $key=>$val1){
					$metinfo.="<option value='{$val1[info]}'>{$val1[info]}</option>";
					}
					$metinfo.="</select>";
					$val[type_html]=$metinfo;
				break;
				case 3:
					$val[type_class]='textarea';
					$val[type_html]="<textarea name='para{$val[id]}' class='textarea-text' placeholder='{$val[name]}'></textarea>";
				break;
				case 4:
					$val[type_class]='radio';
					$metinfo.="<span class='name'>{$val[name]}</span>";
					$i=0;
					foreach($paravalue[$val[id]] as $key=>$val1){
					$i++;
					$metinfo.="<label><input name='para{$val[id]}_{$i}' type='checkbox' value='{$val1[info]}' />{$val1[info]}</label>";
					}
					$metinfo.="<input name='para{$val[id]}' type='hidden' value='{$i}' />";
					$val[type_html]=$metinfo;
				break;
				case 6:
					$val[type_class]='radio';
					$metinfo.="<span class='name'>{$val[name]}</span>";
					$i=0;
					foreach($paravalue[$val[id]] as $key=>$val2){
					$i++;
					$checked=$i==1?'checked':'';
					$metinfo.="<label><input name='para{$val[id]}' type='radio' value='{$val2[info]}' {$checked} />{$val2[info]}</label>";
					}
					$val[type_html]=$metinfo;
				break;
			}
			$metinfo1[]=$val;
		}
		$metinfo = $metinfo1;
	}else{
     $metinfo =$fdjs;
     $metinfo.="<form method='POST' name='myform' onSubmit='return metmessagesubmit1();' action='{$lujin}message.php?action=add&lang=".$lang."' target='_self'>\n";
	$metinfo.="<table width='90%' cellpadding='2' cellspacing='1' bgcolor='#F2F2F2' align='center' class='message_table'>\n";
	foreach($fd_para as $key=>$val){
		$metinfo.="<tr class='message_tr'>\n";
		$metinfo.="<td width='20%' height='25' align='right' bgcolor='#FFFFFF' class='message_td1'>".$val[name]."&nbsp;</td>\n";
		$metinfo.="<td width='70%' bgcolor='#FFFFFF' class='message_input'>".$val[input]."<span class='message_info'>{$val[wr_must]}</span><span>{$val[description]}</span></td>\n";
		$metinfo.="</tr>\n";
	}
	if($met_memberlogin_code==1){
		$metinfo.="<tr class='message_tr'><td align='right' bgcolor='#FFFFFF' class='message_td1'>".$lang_memberImgCode."</td>\n";
		$metinfo.="<td bgcolor='#FFFFFF' class='message_input'><input name='code' onKeyUp='pressCaptcha(this)' type='text' class='code' id='code' size='6' maxlength='8' style='width:50px' />";
		$metinfo.="<img align='absbottom' src='../member/ajax.php?action=code'  onclick=this.src='../member/ajax.php?action=code&'+Math.random() style='cursor: pointer;' title='".$lang_memberTip1."'/>";
		$metinfo.="</td>\n";
		$metinfo.="</tr>\n";
	}
	$metinfo.="<input type='hidden' name='fdtitle' value='".$title."' />\n";
	$metinfo.="<input type='hidden' name='totnum' value='".count($fd_para)."' />\n";
	$metinfo.="<input type='hidden' name='id' value='".$id."' />\n";
	$metinfo.="<tr class='message_tr'><td colspan='3' bgcolor='#FFFFFF' class='message_submint' align='center'>\n";
	$metinfo.="<input type='hidden' name='fromurl' value='".$fromurl."' />\n";
	$metinfo.="<input type='hidden' name='ip' value='".$m_user_ip."' />\n";
	$metinfo.="<input type='hidden' name='lang' value='".$lang."' />\n";
	if($fid)$metinfo.="<input type='hidden' name='fid_url' value='1' />\n";//5.0.4
	$metinfo.="<input type='submit' name='Submit' value='".$lang_SubmitInfo."' class='tj'>\n";
	$metinfo.="<input type='reset' name='Submit' value='".$lang_Reset."' class='tj'></td></tr>\n";
	$metinfo.="</table>\n";
	$metinfo.="</form>\n";	 
	}
	return $metinfo;
}
//友情链接提交表单函数
function metlabel_addlink($tt=1){
	global $lang_Info4,$lang_LinkInfo2,$lang_LinkInfo3,$lang_OurWebName,$met_linkname,$lang_OurWebUrl,$met_weburl,$lang_OurWebLOGO,$met_logo,$lang_OurWebKeywords,$met_keywords,$lang_YourWebName,$lang_YourWebUrl,$lang_LinkType,$lang_TextLink,$lang_PictureLink,$lang_YourWebLOGO,$lang_YourWebKeywords,$lang_Contact,$lang_Submit,$lang_Reset,$lang;
	global $met_memberlogin_code,$lang_memberImgCode,$lang_memberTip1,$met_adminfile;
	$metinfo.="<form method='POST' name='myform' onSubmit='return addlinksubmit(\"{$lang_LinkInfo2}\",\"{$lang_LinkInfo3}\");' action='addlink.php?action=add' target='_self'>\n";
	$metinfo.="<table class='addlink_table'>\n";
	if($tt)$metinfo.="<tr><td class='title' colspan='2'>{$lang_Info4}</td></tr>\n";
	$metinfo.="<tr><td class='text'>{$lang_OurWebName}</td>\n";
	$metinfo.="<td class='input'>{$met_linkname}</td></tr>\n";
	$metinfo.="<tr><td class='text'>{$lang_OurWebUrl}</td>\n";
	$metinfo.="<td class='input'>{$met_weburl}</td></tr>\n";
	$metinfo.="<tr><td class='text'>{$lang_OurWebLOGO}</td>\n";
	$metinfo.="<td class='input'><img src='{$met_logo}' alt='{$lang_OurWebName}' title='{$lang_OurWebName}' /></td></tr>\n";
	$metinfo.="<tr><td class='text'>{$lang_OurWebKeywords}</td>\n";
	$metinfo.="<td class='input'>{$met_keywords}</td></tr>\n";
	$metinfo.="<tr><td class='text'>{$lang_YourWebName}</td>\n";
	$metinfo.="<td class='input'><input name='webname' type='text' class='input-text' size='30' /><span class='info'>*</span></td></tr>\n";
	$metinfo.="<tr><td class='text'>{$lang_YourWebUrl}</td>\n";
	$metinfo.="<td class='input'><input name='weburl' type='text' class='input-text' size='30' value='http://' /><span class='info'>*</span></td></tr>\n";
	$metinfo.="<tr><td class='text'>{$lang_LinkType}</td>\n";
	$metinfo.="<td class='input'><input name='link_type' type='radio' value='0' id='textlinkradio' checked='checked' /><label for='textlinkradio'>{$lang_TextLink}</label>  <input name='link_type' type='radio' value='1' id='imglinkradio' /><label for='imglinkradio'>{$lang_PictureLink}</label><span class='info'>*</span></td></tr>\n";
	$metinfo.="<tr><td class='text'>{$lang_YourWebLOGO}</td>\n";
	$metinfo.="<td class='input'><input name='weblogo' type='text' class='input-text' size='30' value='http://'/></td></tr>\n";
	$metinfo.="<tr><td class='text'>{$lang_YourWebKeywords}</td>\n";
	$metinfo.="<td class='input'><input name='info' type='text' class='input-text' size='30' /></td></tr>\n";
	$metinfo.="<tr><td class='text'>{$lang_Contact}</td>\n";
	$metinfo.="<td class='input'><textarea name='contact' cols='50' class='textarea-text' rows='6'></textarea></td></tr>\n";
if($met_memberlogin_code==1){  
     $metinfo.="<tr><td class='text'>".$lang_memberImgCode."</td>\n";
     $metinfo.="<td class='input'><input name='code' onKeyUp='pressCaptcha(this)' type='text' class='code' id='code' size='6' maxlength='8' style='width:50px' />";
     $metinfo.="<img align='absbottom' src='../member/ajax.php?action=code'  onclick=this.src='../member/ajax.php?action=code&'+Math.random() style='cursor: pointer;' title='".$lang_memberTip1."'/>";
     $metinfo.="</td>\n";
     $metinfo.="</tr>\n";
}
	$metinfo.="<tr><td class='text'></td>\n";
	$metinfo.="<td class='submint'>\n";
	$metinfo.="<input type='submit' name='Submit' value='".$lang_Submit."' class='submit button orange'>\n";
	$metinfo.="<input type='hidden' name='lang' value='".$lang."'></tr>\n";
	$metinfo.="</table>\n";
	$metinfo.="</form>\n";
	return $metinfo;
}
//在线应聘提交表单函数
function metlabel_cv($mobile=0){
	global $fdjs,$lang,$lang_Nolimit,$lang_memberPosition,$selectjob,$cv_para,$paravalue,$met_memberlogin_code,$lang_memberImgCode,$lang_memberTip1,$lang_Submit,$lang_Reset,$met_adminfile;
	if($mobile){
		$cv_para1=array();
		foreach($cv_para as $key=>$val){
			$metinfo="";
			switch($val[type]){
				case 1:
					$val[type_class]='input';
					$val[type_html]="<input name='{$val[para]}' type='text' class='input-text {$wr_ok}' placeholder='{$val[name]}' />";
				break;
				case 2:
					$val[type_class]='select';
					$metinfo.="<span class='name'>{$val[name]}</span>";
					$metinfo.="<select name='para{$val[id]}'><option value=''>{$lang_Nolimit}</option>";
					foreach($paravalue[$val[id]] as $key=>$val1){
					$metinfo.="<option value='{$val1[info]}'>{$val1[info]}</option>";
					}
					$metinfo.="</select>";
					$val[type_html]=$metinfo;
				break;
				case 3:
					$val[type_class]='textarea';
					$val[type_html]="<textarea name='{$val[para]}' class='textarea-text' placeholder='{$val[name]}'></textarea>";
				break;
				case 4:
					$val[type_class]='radio';
					$metinfo.="<span class='name'>{$val[name]}</span>";
					$i=0;
					foreach($paravalue[$val[id]] as $key=>$val1){
					$i++;
					$metinfo.="<label><input name='para{$val[id]}_{$i}' type='checkbox' value='{$val1[info]}' />{$val1[info]}</label>";
					}
					$val[type_html]=$metinfo;
				break;
				case 6:
					$val[type_class]='radio';
					$metinfo.="<span class='name'>{$val[name]}</span>";
					$i=0;
					foreach($paravalue[$val[id]] as $key=>$val2){
					$i++;
					$checked=$i==1?'checked':'';
					$metinfo.="<label><input name='para{$val[id]}' type='radio' value='{$val2[info]}' {$checked} />{$val2[info]}</label>";
					}
					$val[type_html]=$metinfo;
				break;
			}
			$cv_para1[]=$val;
		}
		$metinfo = $cv_para1;
	}else{
     $metinfo.=$fdjs;
     $metinfo.="<form  enctype='multipart/form-data' method='POST' onSubmit='return Checkcv();' name='myform' action='save.php?action=add' target='_self'>\n";
     $metinfo.="<input type='hidden' name='lang' value='".$lang."' />\n";
     $metinfo.="<table class='cv_table'>\n";
     $metinfo.="<tr><td class='text'>".$lang_memberPosition."</td>\n";
     $metinfo.="<td class='input'><select name='jobid' id='jobid'>".$selectjob."</select><span class='info'>*</span></td></tr>\n";
    foreach($cv_para as $key=>$val){
     switch($val[type]){
	 case 1:
     $metinfo.="<tr><td class='text'>".$val[name]."</td>\n";
     $metinfo.="<td class='input'><input name='".$val[para]."' type='text' class='input-text' size='40'><span class='info'>".$val[wr_must]."<span>{$val[description]}</span></span></td></tr>\n";
	 break;
	 case 2:
	 $tmp="<select name='para$val[id]'>";
     $tmp=$tmp."<option value=''>{$lang_Nolimit}</option>";
     foreach($paravalue[$val[id]] as $key=>$val1){
      $tmp=$tmp."<option value='$val1[info]' $selected >$val1[info]</option>";
      }
     $tmp=$tmp."</select>";;
     $metinfo.="<tr><td class='text'>".$val[name]."</td>\n";
     $metinfo.="<td class='input'>".$tmp."<span class='info'>".$val[wr_must]."<span>{$val[description]}</span></span></td></tr>\n";
	 break;
	 case 3:
     $metinfo.="<tr><td class='text'>".$val[name]."</td>\n";
     $metinfo.="<td class='input'><textarea name='".$val[para]."' class='textarea-text' cols='60' rows='5'></textarea><span class='info'>".$val[wr_must]."<span>{$val[description]}</span></span></td></tr>\n";
     break;
	 case 4:
	 $tmp1="";
     $i=0;
     foreach($paravalue[$val[id]] as $key=>$val1){
     $i++;
     $tmp1=$tmp1."<input name='para$val[id]_$i' type='checkbox' id='para$val[id]_$i' value='$val1[info]' ><label for='para$val[id]_$i'>{$val1[info]}</label>  ";
     }
     $metinfo.="<tr><td class='text'>".$val[name]."</td>\n";
     $metinfo.="<td class='input'>".$tmp1."<span class='info'>".$val[wr_must]."<span>{$val[description]}</span></span></td></tr>\n";
     break;
	 case 5:
     $metinfo.="<tr><td class='text'>".$val[name]."</td>\n";
     $metinfo.="<td class='input'><input name='".$val[para]."' type='file' class='input-file' size='20' /><span class='info'>".$val[wr_must]."<span>{$val[description]}</span></span></td></tr>\n";
	 break;
	 case 6:
	 $tmp2="";
     $i=0;
     foreach($paravalue[$val[id]] as $key=>$val2){
     $checked='';
     $i++;
     if($i==1)$checked="checked='checked'";
     $tmp2=$tmp2."<input name='para$val[id]' type='radio' id='para$val[id]_$i' value='$val2[info]' $checked /><label for='para$val[id]_$i'>$val2[info]</label>  ";
     }
     $metinfo.="<tr><td class='text'>".$val[name]."</td>\n";
     $metinfo.="<td class='input'>".$tmp2."<span class='info'>".$val[wr_must]."</span><span>{$val[description]}</span></td></tr>\n";
	 break;
    }
   }
if($met_memberlogin_code==1){  
     $metinfo.="<tr><td class='text'>".$lang_memberImgCode."</td>\n";
     $metinfo.="<td class='input'><input name='code' onKeyUp='pressCaptcha(this)' type='text' class='code' id='code' size='6' maxlength='8' style='width:50px' />";
     $metinfo.="<img align='absbottom' src='../member/ajax.php?action=code'  onclick=this.src='../member/ajax.php?action=code&'+Math.random() style='cursor: pointer;' title='".$lang_memberTip1."'/>";
     $metinfo.="</td>\n";
     $metinfo.="</tr>\n";
}	  
     $metinfo.="<tr><td class='text'></td>\n";
     $metinfo.="<td class='submint'><input type='submit' name='Submit' value='".$lang_Submit."' class='submit button orange' /></td>\n";
     $metinfo.="</tr>";		
     $metinfo.="</table>";
     $metinfo.="</form>";
	}
	return $metinfo;
}
//网站地图
function sitemaplist(){
	global $db,$nav_listall,$m_now_date,$met_sitemap_not1,$met_sitemap_not2,$lang,$met_langok,$met_index_url,$met_webname,$met_weburl;
	global $met_config,$langmark,$class_list,$met_index_type,$met_pseudo,$met_webhtm,$met_htmtype,$met_htmpagename,$met_listhtmltype,$met_htmlistname,$met_chtmtype,$metadmin;
	
	if($met_index_type==$lang){
		$met_chtmtype='.'.$met_htmtype;
		$met_htmtype='.'.$met_htmtype;
	}else{
		$met_chtmtype='_'.$lang.'.'.$met_htmtype;
		$met_htmtype='_'.$lang.'.'.$met_htmtype;
	}
	$langmark="lang=$lang";
	include ROOTPATH.'include\lang.php';
	$indexar=array('title'=>$met_webname,'url'=>$met_index_url[$lang],'updatetime'=>date("Y-m-d"),'priority'=>1);
	$nav_listall=array();
	include ROOTPATH.'include/global/pseudo.php';
	$sitemaplist[]=$indexar;
	foreach($nav_listall as $key=>$val){
		$no1ok=$val[nav]?1:($met_sitemap_not1 && !$val['bigclass']?0:1);
		$no2ok=$val[if_in]==0?1:($met_sitemap_not2?0:1);
		if($val[module]!=10 && $val[module]!=11 && $no1ok && $no2ok && $val[isshow]==1){
			$val[updatetime]=date("Y-m-d",strtotime($m_now_date));
			$val[title]=$val[name];
			$val[url]=str_replace('../','',$val[url]);
			$val[url]=$met_weburl.$val[url];
			$sitemaplist[]=$val;
		}
	}
	foreach(methtml_getarray('','all','time','news',50000) as $key=>$val){
		$val[url]=str_replace('..//','',$val[url]);
		$val[url]=str_replace('../','',$val[url]);
		$val[url]=$met_weburl.$val[url];
		$val['updatetime']=$val['updatetime_original'];
		$sitemaplist[]=$val;
	}
	foreach(methtml_getarray('','all','time','product',50000) as $key=>$val){
		$val[url]=str_replace('..//','',$val[url]);
		$val[url]=str_replace('../','',$val[url]);
		$val[url]=$met_weburl.$val[url];
		$val['updatetime']=$val['updatetime_original'];
		$sitemaplist[]=$val;
	}
	foreach(methtml_getarray('','all','time','download',50000) as $key=>$val){
		$val[url]=str_replace('..//','',$val[url]);
		$val[url]=str_replace('../','',$val[url]);
		$val[url]=$met_weburl.$val[url];
		$val['updatetime']=$val['updatetime_original'];
		$sitemaplist[]=$val;
	}
	foreach(methtml_getarray('','all','time','img',50000) as $key=>$val){
		$val[url]=str_replace('..//','',$val[url]);
		$val[url]=str_replace('../','',$val[url]);
		$val[url]=$met_weburl.$val[url];
		$val['updatetime']=$val['updatetime_original'];
		$sitemaplist[]=$val;
	}
	foreach(methtml_getarray('','all','time','job',50000) as $key=>$val){
		$val[url]=str_replace('..//','',$val[url]);
		$val[url]=str_replace('../','',$val[url]);
		$val[url]=$met_weburl.$val[url];
		$val[title]=$val[position];
		$val[updatetime]=$val[addtime];
		$sitemaplist[]=$val;
	}
	return $sitemaplist;
}
$csnow=$csnow?$csnow:$classnow;
$methtml_flash=metlabel_flash();
$file_site = explode('|',$app_file[2]);
foreach($file_site as $keyfile=>$valflie){
	if(file_exists(ROOTPATH."$met_adminfile".$valflie)&&!is_dir(ROOTPATH."$met_adminfile".$valflie)&&((file_get_contents(ROOTPATH."$met_adminfile".$valflie))!='metinfo')){require_once ROOTPATH."$met_adminfile".$valflie;}
}
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>