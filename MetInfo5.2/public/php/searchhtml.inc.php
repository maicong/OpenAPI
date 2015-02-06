<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved.
//search banner
function methtml_search($classok=1,$searchtype=0,$classid=0,$module,$searchimg){
global $searchurl,$lang_searchall,$nav_search,$lang_search,$lang,$img_url,$class_index;
$metinfo="";
$metinfo.="<form method=\"POST\" name=\"myform1\" action=\"".$searchurl."\"  target=\"_self\">";
$metinfo.="<input type=\"hidden\" name=\"lang\" value='$lang'/>&nbsp;";
$metinfo.="<input type=\"hidden\" name=\"searchtype\" value='$searchtype'/>&nbsp;";
if($module)$metinfo.="<input type=\"hidden\" name=\"module\" value='$module'/>&nbsp;";
if($classid)$metinfo.="<input type=\"hidden\" name='".$class_index[$classid][classtype]."' value='".$class_index[$classid][id]."'/>&nbsp;";
$metinfo.="<span class='navsearch_input'><input type=\"text\" name=\"searchword\" size='20'/></span>&nbsp;";
if($classok){
$metinfo.="<span class='navsearch_class'><select name=\"class1\">";
$metinfo.="<option value=''>".$lang_searchall."</option>";
foreach($nav_search as $key=>$val){
$metinfo.="<option value='".$val[id]."'>".$val[name]."</option>";
}
$metinfo.="</select>&nbsp;";
}
if($searchimg<>''){
$metinfo.="<input class='searchimage' type='image' src='".$img_url.$searchimg."' />";
}else{
$metinfo.="<input type='submit' name='Submit' value='".$lang_search."' class=\"searchgo\" />";
}
$metinfo.="</form>";
return $metinfo;
}

//提取字符串中的数字
function findNum($str=''){
	$str=trim($str);
	if(empty($str)){return '';}
	$temp=array('1','2','3','4','5','6','7','8','9','0');
	$result='';
	for($i=0;$i<strlen($str);$i++){
		if(in_array($str[$i],$temp)){
			$result.=$str[$i];
		}
	}
	return $result;
}

//产品模块联合查询函数
function metlabel_conjunctive($field,$price,$region,$img){
	global $db,$lang,$met_parameter,$met_plist,$met_weburl,$quirys,$prices,$product_para,$para_select,$class1,$lang_cvall;
	if(!$class1){
		$class1=10001;
	}
	foreach($product_para as $key12=>$val){
		if($val[type]!=2&&$val[type]!=6&&$val[type]!=4&&$val[id]!=$price){
			unset($product_para[$key12]);
		}
		if($val[id]==$price){
			$prices="paraprice_".$val[id];
			global $$prices;
		}
	}
	foreach($product_para as $key=>$val){
		if($val[type]==4){
			$inquiry="para".$val[id];
			global $$inquiry;
		}else{
			$inquiry="para".$val[id];
			global $$inquiry;
		}
	}
	$img_list = array();
	if(!is_numeric($img) && $img != ''){
		$img_list = explode('|',$img);
	}
	if($field){
		$para_product_list = explode('|',$field);
		foreach($para_product_list as $key=>$val){
			$product_para_id=array();
			foreach($product_para as $key=>$val6){
				if($val6[id]==$val){
					$product_para_id[]=$product_para[$key];
				}
			}
			foreach($product_para_id as $key=>$val1){
				if($val==$price){
						$product_merit=array();
						$product_merit=explode('|',$region);	
				}else{
					$product_merit=array();
					foreach($para_select[$val1[id]] as $key=>$val7){
						$product_merit[]=$val7[info];
					}
				}
				$quiryx='&search=search&class1='.$class1;
				foreach($product_para as $key=>$val5){
					if($val5[id]==$price){
						$quiry="paraprice_".$val5[id];
						$quirys=$$quiry;
					}else{
					if($val5[type]==4){
						$quiry="para".$val5[id];
						$quirys=$$quiry;
					}else{
						$quiry="para".$val5[id];
						$quirys=$$quiry;
					}
					}
					if($val5[id]==$val1[id]){
						$quirys='';
					}
					$quiryx.="&".$quiry."=".$quirys;
				}
				$conjunctive[$val1[name]][all][id]=$val1[id];
				$conjunctive[$val1[name]][all][type]=$val1[type];
				$conjunctive[$val1[name]][all][info]=$lang_cvall;
				$conjunctive[$val1[name]][all][url]=$met_weburl."product/product.php?lang=".$lang.$quiryx;
				foreach($product_merit as $key=>$val4){
				$conjunctive[$val1[name]][$val4][info]=$val4;
				$conjunctive[$val1[name]][$val4][id]=$val1[id];
				$conjunctive[$val1[name]][$val4][type]=$val1[type];
				$quiryx='&search=search&class1='.$class1;
				foreach($product_para as $key=>$val5){
					if($val5[id]==$price){
						$quiry="paraprice_".$val5[id];
						$quirys=$$quiry;
					}else{
					if($val5[type]==4){
						$quiry="para".$val5[id];
						$quirys=$$quiry;
					}else{
						$quiry="para".$val5[id];
						$quirys=$$quiry;
					}
					}
					if($val5[id]==$val1[id]){
						$quirys=$val4;
					}
					$quiryx.="&".$quiry."=".$quirys;
				}
				$conjunctive[$val1[name]][$val4][url]=$met_weburl."product/product.php?lang=".$lang.$quiryx;
				}
				
			}
		}
	}else{
			foreach($product_para as $key=>$val1){
				if($val1[id]==$price){
						$product_merit=explode('|',$region);
				}else{
					$product_merit=array();
					foreach($para_select[$val1[id]] as $key=>$val7){
						$product_merit[]=$val7[info];
					}
				}
				$quiryx='&search=search&class1='.$class1;
				foreach($product_para as $key=>$val5){
					if($val5[id]==$price){
						$quiry="paraprice_".$val5[id];
						$quirys=$$quiry;
					}else{
						if($val5[type]==4){
							$quiry="para".$val5[id];
							$quirys=$$quiry;
						}else{
							$quiry="para".$val5[id];
							$quirys=$$quiry;
						}
					}
					if($val5[id]==$val1[id]){
						$quirys='';
					}
					$quiryx.="&".$quiry."=".$quirys;
				}
				$conjunctive[$val1[name]][all][id]=$val1[id];
				$conjunctive[$val1[name]][all][type]=$val1[type];
				$conjunctive[$val1[name]][all][info]="$lang_cvall";
				$conjunctive[$val1[name]][all][url]=$met_weburl."product/product.php?lang=".$lang.$quiryx;
				foreach($product_merit as $key=>$val4){
					$conjunctive[$val1[name]][$val4][id]=$val1[id];
					$conjunctive[$val1[name]][$val4][info]=$val4;
					$conjunctive[$val1[name]][$val4][type]=$val1[type];
					$quiryx='&search=search&class1='.$class1;
					foreach($product_para as $key=>$val5){
						if($val5[id]==$price){
							$quiry="paraprice_".$val5[id];
							$quirys=$$quiry;
						}else{
						if($val5[type]==4){
							$quiry="para".$val5[id];
							$quirys=$$quiry;
						}else{
							$quiry="para".$val5[id];
							$quirys=$$quiry;
						}
						}
						if($val5[id]==$val1[id]){
							$quirys=$val4;
						}
						$quiryx.="&".$quiry."=".$quirys;
					}
					$conjunctive[$val1[name]][$val4][url]=$met_weburl."product/product.php?lang=".$lang.$quiryx;				
				}
				
			}
			
		
	}
	$metinfo.="<style>
				.mark a{color:red;}
				.list-search {list-style: none;padding: 0px;margin: 0px;}
				.list-search dd{float:left; margin-right:20px;}
				.list-search dt{float:left; margin-right:20px;}
				.list-search a{white-space: pre-wrap;}
				</style>";
	$metinfo.="<ul class='list-search'>";
	foreach($conjunctive as $key=>$val){
		$metinfo.="<li>";
		$metinfo.="<dl><dt>{$key}:</dt>";
		$k = 0;
		$j = 0;
		foreach($conjunctive[$key] as $key1=>$val1){	
			$class_mark="";
			if($conjunctive[$key][$key1][info]){
				if($val1[type]==4){
					$para_mark="para".$val1[id];
				}else{
					$para_mark="para".$val1[id];
				}
				if($val1[id]==$price){
					$para_mark=$$prices;
				}else{
					$para_mark=$$para_mark;
				}
				if($para_mark){
					if($conjunctive[$key][$key1][info]==$para_mark){
						$class_mark="class='mark'";
					}else{
						$class_mark="";
					}
				}else{
					if($conjunctive[$key][$key1][info]==$lang_cvall){
						$class_mark="class='mark'";
					}else{
						$class_mark="";
					}
				}
				foreach($img_list as $key3=>$val3){
					$j++;
					$img_flist = explode('-',$val3);
					$img_id[] = $img_flist[0];
					$img_url[$key][$j] = $img_flist[1]; 
				}
				$img_id = array_unique($img_id);			
				if(!is_numeric($img) && $img != ''){				
					foreach($img_id as $key2=>$val8){				
						if($val8 == $conjunctive[$key][$key1][id] && $img_url[$key][$k] !=''){
							$metinfo.="<dd  {$class_mark}><a href='{$conjunctive[$key][$key1][url]}' title='{$conjunctive[$key][$key1][info]}' >"."<img src='{$img_url[$key][$k]}'></a></dd>";
						}else{
							$metinfo.="<dd  {$class_mark}><a href='{$conjunctive[$key][$key1][url]}' title='{$conjunctive[$key][$key1][info]}' >{$conjunctive[$key][$key1][info]}</a></dd>";
						}
						$k++;
					}
				}else{
					$metinfo.="<dd  {$class_mark}><a href='{$conjunctive[$key][$key1][url]}' title='{$conjunctive[$key][$key1][info]}' >{$conjunctive[$key][$key1][info]}</a></dd>";
				}
			}
		}
		$metinfo.="</dl><div class='clear'></div></li>";
	}
	$metinfo.="</ul>";
	return $metinfo;
}

//Product and Image and download module  parameter search function
function methtml_parasearch($type,$para1=100,$para2=100,$para4=100,$para6=100,$paraimg,$classid=0,$class1x=0,$class2=0,$class3=0,$title=0,$content=0,$searchtype){
global $module_listall,$module_list1,$module_list2,$module_list3,$product_paralist,$download_paralist,$img_paralist,$para_select,$class_index,$lang_Title,$lang_Content,$class1,$class_list;
global $lang_AllBigclass,$navurl,$lang,$lang_AllThirdclass,$lang_AllSmallclass,$lang_search,$lang_Nolimit,$img_url,$metinfo_member_type,$met_member_use;
  $module=($type=='img')?5:(($type=='download')?4:3);
  $class1tmp=$class1;
  if($class_list[$class1][module]!=$module)$class1=10001;
  $type_para=($type=='img')?$img_paralist:(($type=='download')?$download_paralist:$product_paralist);
if(intval($classid)==0 || $class1x || $class2 || $class3){
  if($type=='')$type='product';
  $metinfo.="<script language = 'JavaScript'>\n";
 if($class1x&&$class2){
  $metinfo.="var ".$type."_onecount;\n";
  $metinfo.=$type."_subcat = new Array();\n";
$j=0;
foreach($module_list2[$module] as $key=>$val){  
  $metinfo.=$type."_subcat[".$j."] = new Array('".$val[id]."','".$val[bigclass]."','".$val[name]."');\n";
$j++;
}
  $metinfo.=$type."_onecount=".$j.";\n";
 }
 if($class2&&$class3){
  $metinfo.="var ".$type."_onecount2;\n";
  $metinfo.=$type."_subcat2 = new Array();\n";
$j=0;
foreach($module_list3[$module] as $key=>$val){      
  $metinfo.=$type."_subcat2[".$j."] = new Array('".$val[id]."','".$val[bigclass]."','".$val[name]."');\n";
$j++;
}
  $metinfo.=$type."_onecount2=".$j.";\n";
 }
 if($class1x&&$class2){
  $metinfo.="function ".$type."_changelocation(locationid){\n";
  $metinfo.="document.".$type."_myformsearch.class2.length = 1;\n"; 
  $metinfo.="var locationid=locationid;\n";
  $metinfo.="var i;\n";
  $metinfo.="for (i=0;i < ".$type."_onecount; i++)\n";
  $metinfo.="{\n";
  $metinfo.="  if (".$type."_subcat[i][1] == locationid)\n";
  $metinfo.="   { \n";
  $metinfo.="       document.".$type."_myformsearch.class2.options[document.".$type."_myformsearch.class2.length] = new Option(".$type."_subcat[i][2], ".$type."_subcat[i][0]);\n";
  $metinfo.="    }}}\n"; 
 }
 if($class2&&$class3){
  $metinfo.="function ".$type."_changelocation2(locationid){\n";
  $metinfo.="document.".$type."_myformsearch.class3.length = 1;\n"; 
  $metinfo.="var locationid=locationid;\n";
  $metinfo.="var i;\n";
  $metinfo.=" for (i=0;i < ".$type."_onecount2; i++)\n";
  $metinfo.=" {\n";
  $metinfo.="   if (".$type."_subcat2[i][1] == locationid)\n";
  $metinfo.="    { \n";
  $metinfo.="       document.".$type."_myformsearch.class3.options[document.".$type."_myformsearch.class3.length] = new Option(".$type."_subcat2[i][2], ".$type."_subcat2[i][0]);\n";
  $metinfo.="    } }}\n"; 
 }
  $metinfo.="</script>\n";
}
  $metinfo.="<ul>\n";
  $metinfo.="<form method='get' name='".$type."_myformsearch' action='".$navurl.$type."/".$type.".php' >\n";
  $metinfo.="<input type='hidden' name='search' value='search' />\n";
  $metinfo.="<input type='hidden' name='lang' value='".$lang."' />\n";
if(!$class1x)$metinfo.="<input type='hidden' name='class1' value='".$class1."' />\n";
  $metinfo.="<input type='hidden' name='searchtype' value='".$searchtype."' />\n";
if(intval($classid)==0 || $class1x || $class2 || $class3){
 if($class1x){
  $metinfo.="<li>\n";
  $metinfo.="<span class='parasearch_class1'>\n";
  $metinfo.="<select name='class1' ";
 if($class2)$metinfo.="onChange='".$type."_changelocation(document.".$type."_myformsearch.class1.options[document.".$type."_myformsearch.class1.selectedIndex].value)' "; 
  $metinfo.="size='1'>\n";
  $metinfo.="<option selected value=''>".$lang_AllBigclass."</option>\n";
foreach($module_list1[$module] as $key=>$val){
  if(intval($classid)&&$class_index[$classid][classtype]=='class1'){
    if($val[index_num]==$classid)$metinfo.="<option value='".$val[id]."'>".$val[name]."</option>\n";
   }else{
   $metinfo.="<option value='".$val[id]."'>".$val[name]."</option>\n";
   }
}
  $metinfo.="</select> \n";
  $metinfo.="</span>\n";
  $metinfo.="</li>\n";
 }
 if($class2){
  $metinfo.="<li>\n";
  $metinfo.="<span class='parasearch_class2'>\n";
  $metinfo.="<select name='class2' ";
 if($class3)$metinfo.="onChange='".$type."_changelocation2(document.".$type."_myformsearch.class2.options[document.".$type."_myformsearch.class2.selectedIndex].value)'";
  $metinfo.="size='1'>\n";
  $metinfo.="<option selected value=''>".$lang_AllSmallclass."</option>\n";
 if(!$class1x){
   foreach($module_list2[$module] as $key=>$val){
   if(intval($classid)&&$class_index[$classid][classtype]=='class1'){
     if($val[bigclass]==$class_index[$classid][id])$metinfo.="<option value='".$val[id]."'>".$val[name]."</option>\n";
   }elseif(intval($classid)&&$class_index[$classid][classtype]=='class2'){
     if($val[index_num]==$classid)$metinfo.="<option value='".$val[id]."'>".$val[name]."</option>\n";
    }else{
	$metinfo.="<option value='".$val[id]."'>".$val[name]."</option>\n";
	}
   }
 }
  $metinfo.="</select>\n";
  $metinfo.="</span>\n";
  $metinfo.="</li>\n";
  }
 if($class3){
  $metinfo.="<li>\n";
  $metinfo.="<span class='parasearch_class3'>\n";
  $metinfo.="<select name='class3' >\n";
  $metinfo.="<option selected value=''>".$lang_AllThirdclass."</option>\n";
  if(!$class2){
   foreach($module_list3[$module] as $key=>$val){
   if(intval($classid)&&$class_index[$classid][classtype]=='class2'){
     if($val[bigclass]==$class_index[$classid][id])$metinfo.="<option value='".$val[id]."'>".$val[name]."</option>\n";
   }elseif(intval($classid)&&$class_index[$classid][classtype]=='class3'){
     if($val[index_num]==$classid)$metinfo.="<option value='".$val[id]."'>".$val[name]."</option>\n";
    }else{
	$metinfo.="<option value='".$val[id]."'>".$val[name]."</option>\n";
	}
   }
 } 
  $metinfo.="</select>\n"; 
  $metinfo.="</span>\n";
  $metinfo.="</li>\n";
  }
}
if(intval($classid))$metinfo.="<input type='hidden' name='".$class_index[$classid][classtype]."' value='".$class_index[$classid][id]."' />\n";
if($title)$metinfo.="<li><span class='parasearch_title'>".$lang_Title."</span><span class='parasearch_input'><input type='text' name='title'  /></span></li>\n";
if($content)$metinfo.="<li><span class='parasearch_title'>".$lang_Content."</span><span class='parasearch_input'><input type='text' name='content'  /></span></li>\n";
if(intval($para1) or intval($para2) or intval($para4) or intval($para6)){
$k1=0;
$k2=0;
$k4=0;
$k6=0;
foreach($type_para as $key=>$val){
if($metinfo_member_type>=intval($val[access])&&$met_member_use){
switch($val[type]){
 case 1:
  $k1++;
  if((!intval($para1)) or $k1>intval($para1)){
     continue;
   }else{
   $metinfo.="<li><span class='parasearch_title'>".$val[name]."</span><span class='parasearch_input'><input type='text' name='".$val[para]."'  /></span></li>\n";
    }
 break;
 case 2:
     $k2++;
  if((!intval($para2)) or $k2>intval($para2)){
     continue;
   }else{
  $metinfo.="<li><span class='parasearch_title'>".$val[name]."</span><span class='parasearch_input'><select name='".$val[para]."'>\n";
  $metinfo.="<option value=''>".$lang_Nolimit."</option>\n";
foreach($para_select[$val[id]] as $key=>$val1){
  $metinfo.="<option value='".$val1[info]."'>".$val1[info]."</option>\n";
}
  $metinfo.="</select></span></li>\n";
  }
 break;
 case 4:
     $k4++;
  if((!intval($para4)) or $k4>intval($para4)){
     continue;
   }else{
   $metinfo.="<li><span class='parasearch_title'>".$val[name]."</span><span class='parasearch_checkbox'>\n";
foreach($para_select[$val[id]] as $key=>$val1){
  $metinfo.="<input name='".$val[para]."_".$val1[id]."' type='checkbox' value='".$val1[info]."' >".$val1[info]." ";
}
   $metinfo.="</span></li>\n";
  }
 break;
 case 6:
     $k6++;
  if((!intval($para6)) or $k4>intval($para6)){
     continue;
   }else{
   $metinfo.="<li><span class='parasearch_title'>".$val[name]."</span><span class='parasearch_radio'>\n";
  $i=0;
foreach($para_select[$val[id]] as $key=>$val1){
  $i++;
  $metinfo.="<input name='".$val[para]."' type='radio' value='".$val1[info]."' >".$val1[info]." ";
}
   $metinfo.="</span></li>\n";
  }
 break;
}
}
}}
  $metinfo.="<li><span class='parasearch_search'>";
if($paraimg<>''){
  $metinfo.="<input class='searchimage' type='image' src='".$img_url.$paraimg."' />";
}else{
  $metinfo.="<input type='submit'  value='".$lang_search."' class='searchgo'/>";
}
  $metinfo.="</span></li>\n";
  $metinfo.="</form>\n";
  $metinfo.="</ul>\n";
  $class1=$class1tmp;
  return $metinfo;
}

//adv search
function methtml_advsearch($module,$classid,$class1=1,$class2=1,$class3=1,$searchimg,$searchtype){
global $nav_list_2,$nav_list_3,$lang_Keywords,$searchurl,$lang_AllBigclass,$nav_search,$lang_AllSmallclass,$lang_AllThirdclass,$lang_Title,$lang_And,$lang_Content,$lang,$lang_search;
global $module_listall,$module_list1,$module_list2,$module_list3,$class_index,$nav_list2,$nav_list3,$class_list,$img_url,$searchword;
 $metinfo.="<script language = 'JavaScript'>\n";
if($class1&&$class2){
    $metinfo.="var onecount;\n";
    $metinfo.="subcat = new Array();\n";
 $j=0;
 $navsearch2=$module?$module_list2[$module]:$nav_list_2;
 $navsearch2=$classid?$nav_list2[$class_index[$classid][id]]:$navsearch2;
 foreach($navsearch2 as $key=>$val){    
    $metinfo.="subcat[".$j."] = new Array('".$val[id]."','".$val[bigclass]."','".$val[name]."');\n";
 $j++;
 }
    $metinfo.="onecount=".$j.";\n";
}
if($class2&&$class3){
    $metinfo.="var onecount2;\n";
    $metinfo.="subcat2 = new Array();\n";
 $j=0;
 $navsearch3=$module?$module_list3[$module]:$nav_list_3;
 $navsearch3=$classid?$module_list3[$class_index[$classid][module]]:$navsearch3;
 foreach($navsearch3 as $key=>$val){       
    $metinfo.="subcat2[".$j."] = new Array('".$val[id]."','".$val[bigclass]."','".$val[name]."');\n";
 $j++;
 }
    $metinfo.="onecount2=".$j.";\n";
}
if($class1&&$class2){
    $metinfo.="function changelocation(locationid)\n";
    $metinfo.="{\n";
    $metinfo.="document.myformsearch.class2.length = 1;\n";
    $metinfo.="var locationid=locationid;\n";
    $metinfo.="var i;\n";
    $metinfo.="for (i=0;i < onecount; i++)\n";
    $metinfo.="{\n";
    $metinfo.="    if (subcat[i][1] == locationid)\n";
    $metinfo.="    {\n";
    $metinfo.="          document.myformsearch.class2.options[document.myformsearch.class2.length] = new Option(subcat[i][2], subcat[i][0]);\n";
    $metinfo.="     }}} \n";
}
if($class2&&$class3){
    $metinfo.="function changelocation2(locationid)\n";
    $metinfo.="{\n";
    $metinfo.="document.myformsearch.class3.length = 1;\n";
    $metinfo.="var locationid=locationid;\n";
    $metinfo.="var i;\n";
    $metinfo.="for (i=0;i < onecount2; i++)\n";
    $metinfo.="{\n";
    $metinfo.="  if (subcat2[i][1] == locationid)\n";
    $metinfo.=" { \n";
    $metinfo.="         document.myformsearch.class3.options[document.myformsearch.class3.length] = new Option(subcat2[i][2], subcat2[i][0]);\n";
    $metinfo.="     }}} \n";
}
    $metinfo.="function select1(){\n";
    $metinfo.="if($(\"input[name='searchword']\").val()=='{$lang_Keywords}')document.myformsearch.searchword.value='';\n";
    $metinfo.="} \n";  
    $metinfo.="function Checksearch(){\n";
    $metinfo.="if(document.myformsearch.searchword.value=='".$lang_Keywords."'){\n";
    $metinfo.="document.myformsearch.searchword.value='';\n";
    $metinfo.="}}\n";
    $metinfo.="</script>\n";
    $metinfo.="<form method='Get' name='myformsearch' onSubmit='return Checksearch();'  action='".$searchurl."'>\n";       
    $metinfo.="<ul class='searchnavlist'>\n";
if($class1){	
    $metinfo.="<li><span class='advsearch_class1'><select name='class1' ";
 if($class2)$metinfo.="onChange='changelocation(document.myformsearch.class1.options[document.myformsearch.class1.selectedIndex].value)'";
    $metinfo.=" size='1'>\n";
    $metinfo.="<option selected value=''>".$lang_AllBigclass."</option>\n";
 $navsearchlist1=$module?$module_list1[$module]:$nav_search;
 foreach($navsearchlist1 as $key=>$val){
    $metinfo.="<option value='".$val[id]."'>".$val[name]."</option>\n";
 }
    $metinfo.="</select></span></li>\n"; 
}
if($class2){
    $metinfo.="<li><span class='advsearch_class2'><select name='class2' ";
 if($class3)$metinfo.="onChange='changelocation2(document.myformsearch.class2.options[document.myformsearch.class2.selectedIndex].value)' ";
    $metinfo.="size='1'>\n";
    $metinfo.="<option selected value=''>".$lang_AllSmallclass."</option>\n";
 if(!$class1){
  $navsearchlist2=$module?$module_list2[$module]:$nav_list_2;
  $navsearchlist2=$classid?$nav_list2[$class_index[$classid][id]]:$navsearchlist2;
  foreach($navsearchlist2 as $key=>$val){
    $metinfo.="<option value='".$val[id]."'>".$val[name]."</option>\n";
   }
  }
    $metinfo.="</select></span></li>\n"; 
}
if($class3){
    $metinfo.="<li><span class='advsearch_class3'><select name='class3' size='1'><option selected value=''>".$lang_AllThirdclass."</option>\n";
 if(!$class2){
  $navsearchlist3=$module?$module_list3[$module]:$nav_list_3;
  $navsearchlist3=$classid?$nav_list3[$class_index[$classid][id]]:$navsearchlist3;
  foreach($navsearchlist3 as $key=>$val){
    $metinfo.="<option value='".$val[id]."'>".$val[name]."</option>\n";
   }
  }	
    $metinfo.="</select></span></li>\n";
}
if($searchtype==""){
    $metinfo.="<li><span class='advsearch_searchtype'><select name='searchtype' size='1'>\n";
    $metinfo.="<option value='0' selected>".$lang_Title."&nbsp;".$lang_And."&nbsp;".$lang_Content."</option>\n";
    $metinfo.="<option value='1'>".$lang_Title."</option>\n";
    $metinfo.="<option value='2'>".$lang_Content."</option>\n";
    $metinfo.="</select></span></li>\n";
}else{
    $metinfo.="<input type='hidden' name='searchtype' value='".$searchtype."' />\n";
}
	if($searchword)$lang_Keywords=$searchword;
    $metinfo.="<li><span class='advsearch_searchword'><input id='searchword' type='text' class='input-text' name='searchword' value='".$lang_Keywords."' maxlength='50' onmousedown='select1()'></span></li>\n"; 
    $metinfo.="<input type='hidden' name='lang' value='".$lang."' />\n";
	if($module)$metinfo.="<input type=\"hidden\" name=\"module\" value='$module'/>&nbsp;";
	if($classid){
	switch($class_index[$classid][classtype]){
	case 'class1':
	$metinfo.="<input type='hidden' name='".$class_index[$classid][classtype]."' value='".$class_index[$classid][id]."' />\n";
	break;
	case 'class2':
	$metinfo.="<input type='hidden' name='class1' value='".$class_index[$classid][bigclass]."' />\n";
	$metinfo.="<input type='hidden' name='".$class_index[$classid][classtype]."' value='".$class_index[$classid][id]."' />\n";
	break;
	case 'class3':
	$metinfo.="<input type='hidden' name='class1' value='".$class_list[$class_index[$classid][bigclass]][bigclass]."' />\n";
	$metinfo.="<input type='hidden' name='class2' value='".$class_index[$classid][bigclass]."' />\n";
	$metinfo.="<input type='hidden' name='".$class_index[$classid][classtype]."' value='".$class_index[$classid][id]."' />\n";
	break;
	}}
    $metinfo.="<li><span class='advsearch_search'>";
	if($searchimg<>''){
      $metinfo.="<input class='searchimage' type='image' src='".$img_url.$searchimg."' />";
    }else{
      $metinfo.="<input type='submit'  value='".$lang_search."' class='searchgo button orange'/>";
    }
    $metinfo.="</select></span></li>\n";
	$metinfo.="</ul>\n";
    $metinfo.="</form>\n";	
	return $metinfo;
}
//按栏目分类得到url
function title($class1,$anyid,$lang){
	global $met_class1,$met_class2,$met_weburl,$met_adminfile,$met_content_type;
	if($met_content_type==1){
	foreach ($met_class1 as $val) {
		$idall[]=$val[id];
	}
	if(!in_array($class1, $idall)&&$class1!=0){
		foreach ($met_class2 as $val1) {
		foreach ($val1 as $val2) {
			if($val2[id]==$class1){
				$classid1=$val2[bigclass];
				$classname1=$met_class1[$classid1][name];
				$module1=$met_class1[$classid1][module];
				$classname2=$val2[name];
			}
		}
		}
	$title="<a href='{$met_weburl}{$met_adminfile}/content/content.php?anyid={$anyid}&lang={$lang}&module=$module1&class1={$classid1}'>{$classname1}</a>><a href='JavaScript:;'>$classname2</a>";
	}	
	}
	return $title;
}
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>
