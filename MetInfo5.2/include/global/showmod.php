<?php
require_once substr(dirname(__FILE__), 0, -6).'common.inc.php';
if(!is_numeric($id)){okinfo('../404.html');exit();}
if($dbname!=$met_download&&$dbname!=$met_img&&$dbname!=$met_news&&$dbname!=$met_product){okinfo('../404.html');exit();}
$news=$db->get_one("select * from $dbname where id=$id and lang='$lang' and (recycle='0' or recycle='-1')");
if(!$news){okinfo('../404.html');exit();}
$news['updatetime_order']=$news['updatetime'];
$news['updatetime'] = date($met_contenttime,strtotime($news['updatetime']));
if(strstr($news['imgurls'], "http://")){
	$news['imgurls']=($news['imgurls']<>"")?$news['imgurls']:$news['imgurls'];
}else{	
	$news['imgurls']=($news['imgurls']<>"")?$news['imgurls']:'../'.$met_agents_img;
	}
if(strstr($news['imgurl'], "http://")){
	$news['imgurl']=($news['imgurl']<>"")?$news['imgurl']:$news['imgurl'];
}else{	
	$news['imgurl']=($news['imgurl']<>"")?$news['imgurl']:'../'.$met_agents_img;
}
$class1=$news['class1'];
$class2=$news['class2'];
$class3=$news['class3'];	
$metaccess=$news['access'];
if($imgproduct=='download'){
	if(intval($news['downloadaccess'])>0&&$met_member_use){
		$news['downloadurl']="down.php?id=$news[id]&lang=$lang";
	}
}
require_once '../include/head.php';
$is_correct_file=explode('/',$PHP_SELF);
if($class_list[$class1]['foldername']!=$is_correct_file[count($is_correct_file)-2]){okinfo('../404.html');exit();}
$class1_info=$class_list[$class1]['releclass']?$class_list[$class_list[$class1]['releclass']]:$class_list[$class1];
$class2_info=$class_list[$class1]['releclass']?$class_list[$class1]:$class_list[$class2];
$class3_info=$class_list[$class3];
if($pagemark>2 && $pagemark<6)$mdmendy=1;
if($mdmendy){
	$query1 = "select * from $met_plist where module='$pagemark' and listid='$id'";
	$result1 = $db->query($query1);
	while($list1 = $db->fetch_array($result1)){
		$nowpara1="para".$list1['paraid'];
		$news[$nowpara1]=$list1['info'];
		$metparaaccess=$metpara[$list1['paraid']]['access'];
		if($metpara[$list1['paraid']]['type']==5){
			$fltp=metfiletype($list1['info']);
			$fltp=$fltp?'met_annex_'.$fltp:'';
			if(!$list1['imgname']){
				$listinfos=explode('/',$list1['info']);
				$listinfoss=explode('.',$listinfos[3]);
				$list1['imgname']=$listinfoss[0];
			}
			$news[$nowpara1]="<a href='{$list1['info']}' {$metblank} class='met_annex {$fltp}' title='{$list1['imgname']}'>{$list1['imgname']}</a>";
			$news[$nowpara1.'s']=$list1['info'];
		}
		if(intval($metparaaccess)>0&&$met_member_use){
			$paracode=authcode($news[$nowpara1], 'ENCODE', $met_member_force);
			$paracode=codetra($paracode,1); 
			$news[$nowpara1]="<script language='javascript' src='../include/access.php?metuser=para&metaccess=".$metparaaccess."&lang=".$lang."&listinfo=".$paracode."&paratype=".$metpara[$list1['paraid']]['type']."'></script>";
		}
		$nowparaname="";
		$nowparaname=$nowpara1."name";
		$news[$nowparaname]=($list1['imgname']<>"")?$list1['imgname']:$metpara[$list1['paraid']]['name'];
	}
}
if($dataoptimize[$pagemark]['nextlist']){
	if($met_pnorder==1){
		$csql="class1='$class1' and class2='$class2' and class3='$class3'";
		$cpnorder=$class3?$class_list[$class3]['list_order']:($class2?$class_list[$class2]['list_order']:$class_list[$class1]['list_order']);
	}
	else{
		$csql="class1='$class1'";
		$cpnorder=$class_list[$class1]['list_order'];
	}
	$acc_sql=$met_member_use==2?"(access<='$metinfo_member_type') and":" ";
	$pn_sql=pn_order($cpnorder,$news);
	if($cpnorder<4){
		$allnews=$db->get_all("select * from $dbname where $csql and lang='$lang' and (recycle='0' or recycle='-1') and $acc_sql $pn_sql[2]");
		$allnum=count($allnews);
		if($allnum>1){
			foreach($allnews as $keyall=>$valall){
				if($valall['id']==$id){
					if(is_array($allnews[$keyall-1])){
						if($keyall-1>=0){$prenews=$allnews[$keyall-1];}
					}
					if(is_array($allnews[$keyall+1])){
						if($keyall+1<=$allnum){$nextnews=$allnews[$keyall+1];}
					}
				}
			}
		}
	}
	if(!is_array($prenews))$prenews=$db->get_one("select * from $dbname where $csql and lang='$lang' and (recycle='0' or recycle='-1') and $acc_sql $pn_sql[0] limit 0,1");
	if(!is_array($nextnews))$nextnews=$db->get_one("select * from $dbname where $csql and lang='$lang' and (recycle='0' or recycle='-1') and $acc_sql $pn_sql[1] limit 0,1");
}
if($dataoptimize[$pagemark]['otherlist']){	
	$serch_sql=" where lang='$lang' and class1=$class1 ";
	if($class2)$serch_sql .= " and class2=$class2";
	if($class3)$serch_sql .= " and class3=$class3"; 
	if($met_member_use==2)$serch_sql .= " and access<=$metinfo_member_type";
	$order_sql=$class3?list_order($class_list[$class3]['list_order']):($class2?list_order($class_list[$class2]['list_order']):list_order($class_list[$class1]['list_order']));
    $query = "SELECT * FROM $dbname $serch_sql and (recycle='0' or recycle='-1') $order_sql LIMIT 0, $listnum";
    $result = $db->query($query);
	while($list= $db->fetch_array($result)){
		if($dataoptimize[$pagemark]['classname']){
			$list['class1_name']=$class_list[$list['class1']]['name'];
			$list['class1_url']=$class_list[$list['class1']]['url'];
			$list['class2_name']=$list['class2']?$class_list[$list['class2']]['name']:$list['class1_name'];
			$list['class2_url']=$list['class2']?$class_list[$list['class2']]['url']:$list['class1_url'];
			$list['class3_name']=$list['class3']?$class_list[$list['class3']]['name']:($list['class2']?$class_list[$list['class2']]['name']:$list['class1_name']);
			$list['class3_url']=$list['class3']?$class_list[$list['class3']]['url']:($list['class2']?$class_list[$list['class2']]['url']:$list['class1_url']);
			$list['classname']=$class2?$list['class3_name']:$list['class2_name'];
			$list['classurl']=$class2?$list['class3_url']:$list['class2_url'];
		}
		$list['top']=$list['top_ok']?"<img class='listtop' src='".$img_url."top.gif"."' />":"";
		$list['hot']=$list['top_ok']?"":(($list['hits']>=$met_hot)?"<img class='listhot' src='".$img_url."hot.gif"."' />":"");
		$list['news']=$list['top_ok']?"":((((strtotime($m_now_date)-strtotime($list['updatetime']))/86400)<$met_newsdays)?"<img class='listnews' src='".$img_url."news.gif"."' />":"");
		$pagename1=$list['updatetime'];
		$list['updatetime'] = date($met_listtime,strtotime($list['updatetime']));
		if(strstr($list['imgurls'], "http://")){
			$list['imgurls']=($list['imgurls']<>"")?$list['imgurls']:$list['imgurls'];
		}else{	
			$list['imgurls']=($list['imgurls']<>"")?$list['imgurls']:'../'.$met_agents_img;
		}
		if(strstr($list['imgurl'], "http://")){
			$list['imgurl']=($list['imgurl']<>"")?$list['imgurl']:$list['imgurl'];
		}else{	
			$list['imgurl']=($list['imgurl']<>"")?$list['imgurl']:'../'.$met_agents_img;
		}
		if($dataoptimize[$pagemark]['para'][$pagemark]){
			$query1 = "select * from $met_plist where module='$pagemark' and listid='$list[id]'";
			$result1 = $db->query($query1);
			while($list1 = $db->fetch_array($result1)){
				$nowpara1="para".$list1['paraid'];
				$list[$nowpara1]=$list1['info'];
				$metparaaccess=$metpara[$list1['paraid']]['access'];
				if(intval($metparaaccess)>0&&$met_member_use){
					$paracode=authcode($list[$nowpara1], 'ENCODE', $met_member_force);
					$paracode=codetra($paracode,1); 
					$list[$nowpara1]="<script language='javascript' src='../include/access.php?metuser=para&metaccess=".$metparaaccess."&lang=".$lang."&listinfo=".$paracode."&paratype=".$metpara[$list1['paraid']]['type']."'></script>";
				}
				$nowparaname="";
				$nowparaname=$nowpara1."name";
				$list[$nowparaname]=($list1['imgname']<>"")?$list1['imgname']:$metpara[$list1['paraid']]['name'];
			}
		}
		if($met_webhtm){
			switch($met_htmpagename){
				case 0:
					$htmname=$showname.$list[id];	
					break;
				case 1:
					$list['updatetime1'] = date('Ymd',strtotime($pagename1));
					$htmname=$list['updatetime1'].$list['id'];	
					break;
				case 2:
					$htmname=$class_list[$list['class1']]['foldername'].$list['id'];	
					break;
			}
			$htmname=($list['filename']<>"" and $metadmin['pagename'])?$list['filename']:$htmname;	
		}	
		$phpname=$showname.'.php?'.$langmark."&id=".$list['id'];
		$panyid = $list['filename']!=''?$list['filename']:$list['id'];
		$met_ahtmtype = $list['filename']<>''?$met_chtmtype:$met_htmtype;
		$list['url']=$met_pseudo?$panyid.'-'.$lang.'.html':($met_webhtm?$htmname.$met_ahtmtype:$phpname);
		if($prenews['id']==$list['id'])$preinfo=$list;  
		if($nextnews['id']==$list['id'])$nextinfo=$list;
		if($list['img_ok'] == 1){
			$md_list_new[]=$list;
			if($list['class1']!=0)$md_class_new[$list['class1']][]=$list;
			if($list['class2']!=0)$md_class_new[$list['class2']][]=$list;
			if($list['class3']!=0)$md_class_new[$list['class3']][]=$list;
		}
		if($list['com_ok'] == 1){
			$md_list_com[]=$list;
			if($list['class1']!=0)$md_class_com[$list['class1']][]=$list;
			if($list['class2']!=0)$md_class_com[$list['class2']][]=$list;
			if($list['class3']!=0)$md_class_com[$list['class3']][]=$list;
		}
		if($list['class1']!=0)$md_class[$list['class1']][]=$list;
		if($list['class2']!=0)$md_class[$list['class2']][]=$list;
		if($list['class3']!=0)$md_class[$list['class3']][]=$list;
		if($list['classother']!=''){
			$total_class=array();
			$list['classother']=trim($list['classother'],'|');
			$total_class=explode('|',$list['classother']);
			foreach($total_class as $key=>$val){
				$val=trim($val,'-');
				$total_classother=explode('-',$val);
				$classother1[$key]=$total_classother[0];
				$classother2[$key]=$total_classother[1];
				$classother3[$key]=$total_classother[2];				
			}
			foreach($classother1 as $val){
				if($val!=0&&!array_key_exists($val,$md_class))$md_class[$val][]=$list;					
			}
			foreach($classother2 as $val){
				if($val!=0&&!array_key_exists($val,$md_class))$md_class[$val][]=$list;
			}
			foreach($classother3 as $val){
				if($val!=0&&!array_key_exists($val,$md_class))$md_class[$val][]=$list;					
			}			
		}
		if($classnow==$class2){
			foreach($md_class as $key=>$val){
				if($key==$class1||$key==$class2){
					$md_class1[$key]=$val;
				}
				foreach($nav_list3[$class2] as $v){
					if($key==$v[id]){
						$md_class1[$key]=$val;
					}
				}
			}
		}else if($classnow==$class3){
			foreach($md_class as $key=>$val){
				if($key==$class1||$key==$class2||$key==$class3){
					$md_class1[$key]=$val;
				}
			}
		}else if($classnow==$class1){
			$md_class1=$md_class;
		}
		$md_class=$md_class1;
		$md_list[]=$list;
	}
}
if($dataoptimize[$pagemark]['nextlist']){
    switch($met_htmpagename){
		case 0:
			$prehtmname=$showname;	
			$nexthtmname=$showname;
			break;
		case 1:
			$prehtmname = date('Ymd',strtotime($prenews['addtime']));	
			$nexthtmname = date('Ymd',strtotime($nextnews['addtime']));
			break;
		case 2:
			$prehtmname=$class_list[$prenews['class1']]['foldername'];
			$nexthtmname=$class_list[$nextnews['class1']]['foldername'];		
			break;
	}
	$preid = $prenews['filename']!=''?$prenews['filename']:$prenews['id'];
	$nextid = $nextnews['filename']!=''?$nextnews['filename']:$nextnews['id'];
	$phpname=$showname.'.php?'.$langmark."&id=";
	$met_ahtmtypep = $prenews['filename']<>''?$met_chtmtype:$met_htmtype;
	$met_ahtmtypen = $nextnews['filename']<>''?$met_chtmtype:$met_htmtype;
	if($prenews)$prenews['url']=$met_pseudo?$preid.'-'.$lang.'.html':($met_webhtm?($prenews['filename']?$preid.$met_ahtmtypep:$prehtmname.$prenews['id'].$met_ahtmtypep):$phpname.$prenews['id']);
    if($nextnews)$nextnews['url']=$met_pseudo?$nextid.'-'.$lang.'.html':($met_webhtm?($nextnews['filename']?$nextid.$met_ahtmtypep:$nexthtmname.$nextnews['id'].$met_ahtmtypen):$phpname.$nextnews['id']);
	$preinfo=$prenews;
	$nextinfo=$nextnews;
}
$class2=$class_list[$class1]['releclass']?$class1:$class2;
$class1=$class_list[$class1]['releclass']?$class_list[$class1]['releclass']:$class1;	
$show['description']= $news['description']?$news['description']:$met_description;
$show['keywords']   = $news['keywords']?$news['keywords']:$met_keywords;
$met_title          = $news['ctitle']?$news['ctitle']:($met_title?$news['title'].'-'.$met_title:$news['title']);
$nav_x['name']      = $nav_x['name']." > ".$news['title'];
$class_concent=$metadmin[fujiatype]?'':'<div id="metinfo_additional">'.($class_list[$news[class3]]['content']?$class_list[$news[class3]]['content']:($class_list[$news[class2]]['content']?$class_list[$news[class2]]['content']:$class_list[$news[class1]]['content'])).'</div>';
if($news[tag]){
	if(!$lang_tagweb)$lang_tagweb='TAG';
	$tagstr="<br /><span>{$lang_tagweb}:&nbsp";
	$tags=explode('|',$news[tag]);
	foreach($tags as $key=>$val){
		if($met_pseudo||$met_tag_pseudo){$tagstr.="&nbsp<a href=\"../tag/{$val}-{$lang}\" target=\"_blank\">$val</a>";}
		else{$tagstr.="&nbsp<a href=\"../search/search.php?class1=&class2=&class3=&searchtype=0&searchword={$val}&lang={$lang}\" target=\"_blank\">$val</a>";}
	}
	$class_concent.=$tagstr.'</span>';
}
$news['content'].=$class_concent;
$news['content1'].=$class_concent;
$news['content2'].=$class_concent;
$news['content3'].=$class_concent;
$news['content4'].=$class_concent;
$news['content']=contentshow('<div>'.$news['content'].'</div>');
$news['content1']=contentshow('<div>'.$news['content1'].'</div>');
$news['content2']=contentshow('<div>'.$news['content2'].'</div>');
$news['content3']=contentshow('<div>'.$news['content3'].'</div>');
$news['content4']=contentshow('<div>'.$news['content4'].'</div>');
$news['url']=request_uri();
if($metinfonow==$met_member_force and $met_webhtm){
	$html_filenamex=str_replace("\\",'',$html_filename);
	$html_filenamex=unescape($html_filenamex);
	$news['url']=$met_weburl.$class_list[$class1]['foldername'].'/'.$html_filenamex.$met_htmtype;
}
if($pagemark==3||$pagemark==5){
	if($news['displayimg']!=''){
		$displayimg=explode('|',$news['displayimg']);
		$pg=count($displayimg);
		for($i=0;$i<$pg;$i++){
			$newdisplay=explode('*',$displayimg[$i]);
			$displaylist[$i]['title']=$newdisplay[0];
			$displaylist[$i]['imgurl']=$newdisplay[1];
			$imgurl_diss=explode('/',$displaylist[$i]['imgurl']);
			$displaylist[$i][imgurl_dis]=$imgurl_diss[0].'/'.$imgurl_diss[1].'/'.$imgurl_diss[2].'/thumb_dis/'.$imgurl_diss[count($imgurl_diss)-1];
			$filename=stristr(PHP_OS,"WIN")?@iconv("utf-8","gbk",$displaylist[$i][imgurl_dis]):$displaylist[$i][imgurl_dis];
			$displaylist[$i][imgurl_dis]=file_exists($filename)?$displaylist[$i][imgurl_dis]:$displaylist[$i]['imgurl'];
		}
	}
}
if($news['classother']){
	$met_pnorder=0;
	//$lang_sidebarjstype=1;
	$csnow='x';
	$class3='x';
	$class_list[$classnow][name]=$class1_info[name];
	$navdown='';
}
require_once '../public/php/methtml.inc.php';
if($news['classother']){
	$nav_x[name]="<a href=".$news['url']." >".$news['title']."</a>";
}
?>