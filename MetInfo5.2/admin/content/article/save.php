<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
$depth='../';
require_once $depth.'../login/login_check.php';
$filename=namefilter($filename);
$filenameold=namefilter($filenameold);
if($filename_okno){
	$metinfo=1;
	if($filename!=''){
		$sql="class1='$class1'";
		foreach($column_pop as $key=>$val){
			if($key!=$lang){
				foreach($val as $key1=>$val1){
					if($val1['foldername']==$met_class[$class1]['foldername'])$sql.=" or class1='$val1[id]'";
				}
			}
		}
		$filenameok = $db->get_one("SELECT * FROM $met_news WHERE ($sql) and filename='$filename'");
		if($filenameok)$metinfo=0;
		if(is_numeric($filename) && $filename!=$id && $met_pseudo){
			$filenameok1 = $db->get_one("SELECT * FROM {$met_news} WHERE id='{$filename}' and class1='$class1'");
			if($filenameok1)$metinfo=2;
		}
	}
	echo $metinfo;
	die;
}  
$save_type=$action=="add"?1:($filename!=$filenameold?2:0);
if($filename!='' && $save_type){
		$sql="class1='$class1'";
		foreach($column_pop as $key=>$val){
			if($key!=$lang){
				foreach($val as $key1=>$val1){
					if($val1['foldername']==$met_class[$class1]['foldername'])$sql.=" or class1='$val1[id]'";
				}
			}
		}
		$sql1=$save_type==2?" and id!=$id":'';
		$filenameok = $db->get_one("SELECT * FROM $met_news WHERE ($sql) {$sql1} and filename='$filename'");
		if($filenameok)metsave('-1',$lang_modFilenameok,$depth);
}
if(!$imgurl&&!$imgurls){
	$imgauto=preg_match('/<img.*src=\\\\"(.*?)\\\\".*?>/i',$content,$out);
	$filenameimg=explode("images/",$out[1]);
	$filenameimg=$filenameimg[count($filenameimg)-1];
	$new_big_img='../../../upload/images/'.$filenameimg;
	$new_big_img=str_ireplace("/watermark","",$new_big_img);
	$new_big_img_iconv=stristr(PHP_OS,"WIN")?@iconv("utf-8","gbk",$new_big_img):$new_big_img;
	if($filenameimg&&file_exists($new_big_img_iconv)){
		require_once ROOTPATH_ADMIN.'include/upfile.class.php';
		$f = new upfile($met_img_type,"../../../upload/images/",$met_img_maxsize,'',1);
		$f->savename=str_ireplace("watermark/","",$filenameimg);
		$imgurls = $f->createthumb($new_big_img,$met_newsimg_x,$met_newsimg_y);
		if($met_thumb_wate==1){
			require_once ROOTPATH_ADMIN.'include/watermark.class.php';
			$img = new Watermark();
			if($met_wate_class==2){
				$img->met_image_pos = $met_watermark;
				$img->met_image_name = $depth.$met_wate_img;
			}else {
				$img->met_text = $met_text_wate;
				$img->met_text_color = $met_text_color;
				$img->met_text_angle = $met_text_angle;
				$img->met_text_pos   = $met_watermark;
				$img->met_text_font = $depth.$met_text_fonts;
				$img->met_text_size  = $met_text_size;
			}
			$img->save_file =$imgurls;
			$img->create($imgurls);
		}
		$imgurl='../upload/images/'.$filenameimg;
		$imgurls=str_replace('../../','',$imgurls);
	}
}

if($action=="add"){
$access=$access<>""?$access:"0";
if(!$description){
	$description=strip_tags($content);
	$description=str_replace("&nbsp;",'',$description); 
	$description=str_replace(" ","",$description);
	$description=str_replace("\n", '', $description); 
	$description=str_replace("\r", '', $description); 
	$description=str_replace("\t", '', $description);
	$description=mb_substr($description,0,200,'utf-8');
}
$query = "INSERT INTO $met_news SET
                      title              = '$title',
                      ctitle             = '$ctitle',
					  keywords           = '$keywords',
					  description        = '$description',
					  content            = '$content',
					  class1             = '$class1',
					  class2             = '$class2',
					  class3             = '$class3',
					  img_ok             = '$img_ok',
					  imgurl             = '$imgurl',
					  imgurls            = '$imgurls',
				      com_ok             = '$com_ok',
				      wap_ok             = '$wap_ok',
					  issue              = '$issue',
					  hits               = '$hits', 
					  addtime            = '$addtime', 
					  updatetime         = '$updatetime',
					  access          	 = '$access',
					  filename       	 = '$filename',
					  no_order       	 = '$no_order',
					  lang          	 = '$lang',
					  top_ok             = '$top_ok',
					  displaytype        = '$displaytype',
					  tag                = '$tag'";
         $db->query($query);
$later_news=$db->get_one("select * from $met_news where updatetime='$updatetime' and lang='$lang'");
$id=$later_news[id];
$htmjs = contenthtm($class1,$id,'shownews',$filename,0,'',$addtime).'$|$';
$htmjs.= indexhtm().'$|$';
$htmjs.= classhtm($class1,$class2,$class3);
$turl  ="../content/article/index.php?anyid=$anyid&lang=$lang&class1=$reclass1&class2=$reclass2&class3=$reclass3";
$gent='../../sitemap/index.php?lang='.$lang.'&htmsitemap='.$met_member_force;
metsave($turl,'',$depth,$htmjs,$gent);
}
if($description){
	$description_type=$db->get_one("select * from $met_news where id='$id'");
	$description1=strip_tags($description_type[content]);
	$description1=str_replace("&nbsp;",'',$description1); 
	$description1=str_replace(" ","",$description1);
	$description1=str_replace("\n", '', $description1); 
	$description1=str_replace("\r", '', $description1); 
	$description1=str_replace("\t", '', $description1);
	$description1=mb_substr($description1,0,200,'utf-8');
	if($description1==$description){
		$description=strip_tags($content);
		$description=str_replace("&nbsp;",'',$description); 
		$description=str_replace(" ","",$description);
		$description=str_replace("\n", '', $description); 
		$description=str_replace("\r", '', $description); 
		$description=str_replace("\t", '', $description);
		$description=mb_substr($description,0,200,'utf-8');
	}
}
if($action=="editor"){
$query = "update $met_news SET 
                      title              = '$title',
                      ctitle             = '$ctitle',
					  keywords           = '$keywords',
					  description        = '$description',
					  content            = '$content',
					  tag                = '$tag',
                      class1             = '$class1',
					  class2             = '$class2',
					  class3             = '$class3',
					  displaytype        = '$displaytype',";
if($metadmin[newsimage])$query .= "					  
					  img_ok             = '$img_ok',
					  imgurl             = '$imgurl',
					  imgurls            = '$imgurls',";
if($metadmin[newscom])$query .= "	
				      com_ok             = '$com_ok',";
					  $query .= "
					  wap_ok             = '$wap_ok',
					  issue              = '$issue',
					  hits               = '$hits', 
					  addtime            = '$addtime', 
					  updatetime         = '$updatetime',";
if($met_member_use)  $query .= "
					  access			 = '$access',";
if($metadmin[pagename])  $query .= "
					  filename       	 = '$filename',";
					  $query .= "
					  top_ok             = '$top_ok',
					  no_order       	 = '$no_order',
					  lang               = '$lang'
					  where id='$id'";
$db->query($query);
$htmjs = contenthtm($class1,$id,'shownews',$filename,0,'',$addtime).'$|$';
$htmjs.= indexhtm().'$|$';
$htmjs.= classhtm($class1,$class2,$class3);
if($filenameold<>$filename and $metadmin[pagename])deletepage($met_class[$class1][foldername],$id,'shownews',$updatetimeold,$filenameold);
$classnow=$class3?$class3:($class2?$class2:$class1);
$turl  ="../content/article/index.php?anyid=$anyid&lang=$lang&class1=$reclass1&class2=$reclass2&class3=$reclass3&modify=$id&page=$page";
$gent='../../sitemap/index.php?lang='.$lang.'&htmsitemap='.$met_member_force;
metsave($turl,'',$depth,$htmjs,$gent);
}
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>
