<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
/*子级栏目*/
function listjs($module){
	global $db,$met_column,$lang,$lang_modClass2,$lang_modClass3,$metinfo_admin_pop;
	$query = "SELECT * FROM $met_column where module='$module' and lang='$lang'";
	$result = $db->query($query);
	while($list = $db->fetch_array($result)) {
		$clist[]=$list;
	}
	$i=0;
	$listjs = "<script language = 'JavaScript'>\n";
	$listjs.= "var onecount;\n";
	$listjs.= "lev = new Array();\n";
	foreach($clist as $key=>$vallist){
		$admin_column_power="admin_pop".$vallist[id];
		global $$admin_column_power;
		if(!($metinfo_admin_pop=='metinfo'||$$admin_column_power=='metinfo')&&($vallist[classtype]==1||$vallist['releclass']))continue;
		$vallist[name]=str_replace("'","\\'",$vallist[name]);
		if($vallist['releclass']){
			$listjs.= "lev[".$i."] = new Array('".$vallist[name]."','0','".$vallist[id]."','".$vallist[access]."');\n";
			$i=$i+1;
		}
		else{
				$listjs.= "lev[".$i."] = new Array('".$vallist[name]."','".$vallist[bigclass]."','".$vallist[id]."','".$vallist[access]."');\n";
				$i=$i+1;
		}
	}
	$j=$i;
	$listjs.= "lev[".$j."] = new Array('".$lang_modClass2."','0','','0');\n";
	$j++;
	$listjs.= "lev[".$j."] = new Array('".$lang_modClass3."','0','','0');\n";
	$j++;
	$listjs.= "lev[".$j."] = new Array('----------','0','0','-1');\n";
	$listjs.= "onecount=".$i.";\n";
	$listjs.= "</script>";
	return $listjs;
}
/*para参数处理*/
function para_list_with($mod_list){
	global $db,$lang_modnull,$lang_imagename,$met_class,$class1,$class2,$class3,$met_list,$met_parameter,$lang;
	$query = "select * from $met_parameter where lang='$lang' and module='".$met_class[$class1]['module']."' and (class1='0' or (class1='$class1' and class2='$class2' and class3=0) or (class1='$class1' and class2='$class2' and class3='$class3') or (class1='$class1' and class2=0 and class3=0)) order by no_order";
	$result = $db->query($query);
	while($list = $db->fetch_array($result)){
		if($list[type]==2 or $list[type]==4 or $list[type]==6){
			$query1 = "select * from $met_list where lang='$lang' and bigid='".$list[id]."' order by no_order";
			$result1 = $db->query($query1);
			while($list1 = $db->fetch_array($result1)){
				$paravalue[$list[id]][]=$list1;
			}
		}
		$para_list[]=$list;
	}

	foreach($para_list as $key=>$val){
		$mrok='';
		$para='para'.$val[id];
		switch($val['type']){
			case 1:
				if($val['wr_ok']){
					$mrok='nonull';
					$val['name']='<span class="bi_tian">*</span>'.$val['name'];
				}
				$val['inputcont']="<input name='para{$val[id]}' type='text' class='text {$mrok}' value='{$mod_list[$para]}'>";
			break;
			case 2:
				if($val['wr_ok']){
					$mrok='class="noselect"';
					$val['name']='<span class="bi_tian">*</span>'.$val['name'];
				}
				$val['inputcont'] ="<select name='para{$val[id]}' {$mrok}>";
				$val['inputcont'].="<option value=''>{$lang_modnull}</option>";
				foreach($paravalue[$val[id]] as $key=>$val1){
					$selected='';
					if($mod_list[$para]==$val1['info']) $selected="selected=selected";
					$val['inputcont'].="<option value='{$val1[info]}' {$selected}>{$val1[info]}</option>";
				}
				$val['inputcont'].="</select>";
			break;
			case 3:
				if($val['wr_ok']){
					$mrok='nonull';
					$val['name']='<span class="bi_tian">*</span>'.$val['name'];
				}
				$val['inputcont'] ="<textarea name='para{$val[id]}' class='textarea gen {$mrok}' cols='60' rows='5'>{$mod_list[$para]}</textarea>";
			break;
			case 4:
				if($val['wr_ok']){
					$mrok='nonull';
					$val['name']='<span class="bi_tian">*</span>'.$val['name'];
				}
				$val['inputcont']='';
				$i=0;
				$nowinfo="-".$mod_list[$para]."-";
				foreach($paravalue[$val[id]] as $key=>$val1){
					$i++;$checked='';
					if(strstr($nowinfo, "-".$val1['info']."-"))$checked='checked';
					$val['inputcont'].="
						<label class='{$mrok}'>
							<input name='para{$val[id]}_{$i}' type='checkbox' class='checkbox' value='{$val1[info]}' {$checked} />{$val1[info]}
						</label>
					";
				}
			break;
			case 5:
				if($val['wr_ok']){
					$mrok='nonull';
					$val['name']='<span class="bi_tian">*</span>'.$val['name'];
				}
				$paraname=$para.'name';
				$val['inputcont']="
					<div style='height:30px;'>
						<input name='para{$val[id]}name' type='text' class='text med' value='{$mod_list[$paraname]}'>
						<span class='tips'>{$lang_imagename}</span>
					</div>
					<input name='para{$val[id]}' type='text' class='text {$mrok}' value='{$mod_list[$para]}' />
					<input name='met_upsql_{$val[id]}' type='file' id='mod_upload_{$val[id]}' />
					<script type='text/javascript'>
					$(document).ready(function(){
						metuploadify('#mod_upload_{$val[id]}','upfile','para{$val[id]}');
					});
					</script>
				";
			break;
			case 6:
				if($val['wr_ok']){
					$mrok='nonull';
					$val['name']='<span class="bi_tian">*</span>'.$val['name'];
				}
				$val['inputcont']='';
				$i=0;
				foreach($paravalue[$val[id]] as $key=>$val2){
					$i++;$checked='';
					if($action=="add" && $i==1)$checked='checked';
					if($mod_list[$para]==$val2['info'])$checked='checked';
					$val['inputcont'].="
						<label class={$mrok}>
						<input name='para{$val[id]}' type='radio' class='radio' value='{$val2[info]}' {$checked} />{$val2[info]}
						</label>
					";
				}
			break;
		}
		$para_lists[] = $val;
	}
	return $para_lists;
}

function para_list_withs($member_list,$id,$mun_module){
global $db,$lang_modnull,$lang_imagename,$lang_marks,$met_class,$class1,$class2,$class3,$met_list,$met_parameter,$lang,$met_plist,$met_mlist,$lang_Empty,$met_language,$lang_clickview;
$paras_Empty=$db->get_one("SELECT * FROM $met_language WHERE name='Empty' and lang='$lang'");
$paras_email=$db->get_one("SELECT * FROM $met_language WHERE name='js13' and no_order='13' and lang='$lang'");
	if($mun_module==10){
	$fdjs="function isValidEmail(email)
			{
			var result=email.match(/^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/);
			if(result==null) return false;
			return true;
			}";
			}
	$fdjs=$fdjs."function Checkmember(){ ";
	if($mun_module==10){
	$fdjs.="if(document.myform.email.value == '') 
         { alert('email{$lang_Empty}');
		   document.myform.email.focus();
		   document.myform.email.select(); 
           return false;
         }";
	$fdjs.="if(!isValidEmail(document.myform.email.value))
         { alert('{$paras_email[value]}');
           document.myform.email.focus();
		   document.myform.email.select(); 
		   return false;
         }";
	}
foreach($member_list as $key=>$val){
	if($val[type]==4&&$val[wr_ok]==1){
		$query2 = "select * from $met_list where lang='$lang' and bigid='$val[id]'";
		$result2 = $db->query($query2);
		$paravalue1 = array();
		while($list2 = $db->fetch_array($result2)){
			$paravalue1[]=$list2;
		}
		$i=1;
		$infos="";
		$lagerinput="";
		foreach($paravalue1 as $key=>$val1){
			$lagerinput=$lagerinput."document.myform.para$val[id]_$i.checked ||";
			$i=$i+1;
			}
		$lagerinput=$lagerinput."false\n";
		$fdjs.="if(!($lagerinput)) 
         { alert('{$val[name]}{$paras_Empty[value]}');
           return false;
         }";
		}
	}

$fdjs.="}";
$parahtml="";
foreach($member_list as $key=>$val){
$paras_name="para".$val[id];
if($val[type]==1){
	if($mun_module==7){
	$paras_value=$db->get_one("SELECT * FROM $met_mlist WHERE listid='$id' and paraid='$val[id]' and module='$mun_module'");
	}else{
	$paras_value=$db->get_one("SELECT * FROM $met_plist WHERE listid='$id' and paraid='$val[id]' and module='$mun_module'");
	}
	if($val[wr_ok]==1){
		$para_class="text nonull";
		$para_class1="<font color='#FF0000'>*</font>";
	}else{
		$para_class="text";
		$para_class1="";
	}
	$parahtml.="<div class='v52fmbx_dlbox'>
		<dl>
			<dt>{$para_class1}{$val[name]}{$lang_marks}</dt>
			<dd>
				<input name='$paras_name' type='text' class='$para_class' value='$paras_value[info]' />
			</dd>
		</dl>
		</div>";
}
if($val[type]==2){
	$sum=count($para_xial)*2;
	for($i=0;$i<$sum;$i++)
		{
		   unset($para_xial[$i]);
		}
	if($val[wr_ok]==1){
		$para_class="text nonull";
		$para_class1="<font color='#FF0000'>*</font>";
	}else{
		$para_class="text";
		$para_class1="";
	}
	if($mun_module==7){
	$paras_value=$db->get_one("SELECT * FROM $met_mlist WHERE listid='$id' and paraid='$val[id]' and module='$mun_module'");
	}else{
	$paras_value=$db->get_one("SELECT * FROM $met_plist WHERE listid='$id' and paraid='$val[id]' and module='$mun_module'");
	}
	$query = "SELECT * FROM $met_list WHERE lang='$lang' and bigid='$val[id]'";
	$result = $db->query($query);
	while($list2 = $db->fetch_array($result)){
			$para_xial[]=$list2;
	}
	$parahtml.="<div class='v52fmbx_dlbox'>
		<dl>
			<dt>{$para_class1}{$val[name]}{$lang_marks}</dt>
			<dd>
				<select id='$paras_name' name='$paras_name' >";
	$i=1;
	foreach($para_xial as $key=>$val1){
		if($paras_value[info]==$val1[info]){
			$pitchon[$i]="selected='selected'";
		}
		$parahtml.="<option value='$val1[info]' $pitchon[$i]>$val1[info]</option>";
		$i=$i+1;
	}
	$parahtml.="</select>
				</dd>
					</dl>
					</div>";
	$pitchon=array();
}
if($val[type]==3){
	if($mun_module==7){
	$paras_value=$db->get_one("SELECT * FROM $met_mlist WHERE listid='$id' and paraid='$val[id]' and module='$mun_module'");
	}else{
	$paras_value=$db->get_one("SELECT * FROM $met_plist WHERE listid='$id' and paraid='$val[id]' and module='$mun_module'");
	}
	if($val[wr_ok]==1){
		$para_class="textarea gen nonull";
		$para_class1="<font color='#FF0000'>*</font>";
	}else{
		$para_class="textarea gen";
		$para_class1="";
	}
	$parahtml.="<div class='v52fmbx_dlbox'>
		<dl>
			<dt>{$para_class1}{$val[name]}{$lang_marks}</dt>
			<dd>
				<textarea name='$paras_name' class='$para_class'>$paras_value[info]</textarea>
			</dd>
		</dl>
		</div>";
}
if($val[type]==4){
	$sum=count($para_duox)*2;
	for($i=0;$i<$sum;$i++)
		{
		   unset($para_duox[$i]);
		}
	if($val[wr_ok]==1){
		$para_class="text nonull";
		$para_class1="<font color='#FF0000'>*</font>";
	}else{
		$para_class="text";
		$para_class1="";
	}
	if($mun_module==7){
	$paras_value=$db->get_one("SELECT * FROM $met_mlist WHERE listid='$id' and paraid='$val[id]' and module='$mun_module'");
	}else{
	$paras_value=$db->get_one("SELECT * FROM $met_plist WHERE listid='$id' and paraid='$val[id]' and module='$mun_module'");
	}
	$para_value=explode("、",$paras_value[info]);
	$query = "SELECT * FROM $met_list WHERE lang='$lang' and bigid='$val[id]'";
	$result = $db->query($query);
	while($list3 = $db->fetch_array($result)){
			$para_duox[]=$list3;
	}
	$parahtml.="<div class='v52fmbx_dlbox'>
		<dl>
			<dt>{$para_class1}{$val[name]}{$lang_marks}</dt>
			<dd>
			    ";
	$i=1;
	foreach($para_duox as $key=>$val2){
	foreach($para_value as $key=>$val21){
		if($i==$val21){
			$pitchon1[$i]="checked";
		}
		}
		$paras_names="para".$val[id]."_".$i;
		$parahtml.="<label><input name='$paras_names' type='checkbox' class='checkbox' value='$i' {$pitchon1[$i]}>$val2[info]</label>";
		$i=$i+1;
	}
	$parahtml.="</dd>
					</dl>
					</div>";
	$pitchon1=array();
}
if($val[type]==5){
	if($mun_module==7){
	$paras_value=$db->get_one("SELECT * FROM $met_mlist WHERE listid='$id' and paraid='$val[id]' and module='$mun_module'");
	}else{
	$paras_value=$db->get_one("SELECT * FROM $met_plist WHERE listid='$id' and paraid='$val[id]' and module='$mun_module'");
	}
	
	$paras_names=$$paras_name;
	if($val[wr_ok]==1){
		$para_class="text nonull";
		$para_class1="<font color='#FF0000'>*</font>";
	}else{
		$para_class="text";
		$para_class1="";
	}
	if(!$paras_names){
		$paras_names=$paras_value[info];
	}
	if($mun_module==10){
		if($paras_names){
		$parahtml.="<div class='v52fmbx_dlbox'>
		<dl>
			<dt>{$para_class1}{$val[name]}{$lang_marks}</dt>
			<dd>
				
				<input name='$paras_name' type='text' class='$para_class' value='{$paras_names}' />
					<input name='met_upsql_{$val[id]}' type='file' id='mod_upload_{$val[id]}' />
					<a href='../{$paras_names}' target='_blank'>{$lang_clickview}</a>
					<script type='text/javascript'>
					$(document).ready(function(){
						metuploadify('#mod_upload_{$val[id]}','upfile','$paras_name');
					});
					</script>
			</dd>
		</dl>
</div>";
		
		}else{
		$parahtml.="<div class='v52fmbx_dlbox'>
		<dl>
			<dt>{$para_class1}{$val[name]}{$lang_marks}</dt>
			<dd>
				
				<input name='$paras_name' type='text' class='$para_class' value='{$paras_names}' />
					<input name='met_upsql_{$val[id]}' type='file' id='mod_upload_{$val[id]}' />
					<script type='text/javascript'>
					$(document).ready(function(){
						metuploadify('#mod_upload_{$val[id]}','upfile','$paras_name');
					});
					</script>
			</dd>
		</dl>
</div>";
		}
	}else{
		$parahtml.="<div class='v52fmbx_dlbox'>
		<dl>
			<dt>{$para_class1}{$val[name]}{$lang_marks}</dt>
			<dd>
				
				<input name='$paras_name' type='text' class='$para_class' value='{$paras_names}' />
					<input name='met_upsql_{$val[id]}' type='file' id='mod_upload_{$val[id]}' />
					<script type='text/javascript'>
					$(document).ready(function(){
						metuploadify('#mod_upload_{$val[id]}','upfile','$paras_name');
					});
					</script>
			</dd>
		</dl>
</div>";
	}
}
if($val[type]==6){
	$sum=count($para_danx)*2;
	for($i=0;$i<$sum;$i++)
		{
		   unset($para_danx[$i]);
		}
	if($val[wr_ok]==1){
		$para_class="text nonull";
		$para_class1="<font color='#FF0000'>*</font>";
	}else{
		$para_class="text";
		$para_class1="";
	}
	if($mun_module==7){
	$paras_value=$db->get_one("SELECT * FROM $met_mlist WHERE listid='$id' and paraid='$val[id]' and module='$mun_module'");
	}else{
	$paras_value=$db->get_one("SELECT * FROM $met_plist WHERE listid='$id' and paraid='$val[id]' and module='$mun_module'");
	}
	$query = "SELECT * FROM $met_list WHERE lang='$lang' and bigid='$val[id]'";
	$result = $db->query($query);
	while($list5 = $db->fetch_array($result)){
			$para_danx[]=$list5;
	}
	$parahtml.="<div class='v52fmbx_dlbox'>
		<dl>
			<dt>{$para_class1}{$val[name]}{$lang_marks}</dt>
			<dd>
			    ";
	$i=1;
	if(!$paras_value[info]){
			$pitchon2[1]="checked";
		}
	foreach($para_danx as $key=>$val3){
		if($val3[info]==$paras_value[info]){
			$pitchon2[$i]="checked";
		}	
		$parahtml.="<label><input name='$paras_name' type='radio' value='$val3[info]' $pitchon2[$i]> {$val3[info]}</label>";
		$i=$i+1;
	}
	$parahtml.="</dd>
					</dl>
					</div>";
	$pitchon2=array();
}
}
	$member_list_para[1]=$parahtml;
	$member_list_para[2]=$fdjs;
	return $member_list_para;
}
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>