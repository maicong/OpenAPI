<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
$depth='../';
require_once $depth.'../login/login_check.php';
require_once 'global.func.php';
$listclass='';
$listclass[2]='class="now"';
$rurls='../system/database/recovery.php?anyid='.$anyid.'&lang='.$lang;
if($action=='delete'){
	if(substr_count(trim($filenames),'../'))die('met2');
	$prefix=$filenames;
	$sqlfiles = glob('../../databack/*.sql');
	foreach($sqlfiles as $id=>$sqlfile){
		$sqlfile=str_ireplace('../../databack/','',$sqlfile);
		if(stripos($sqlfile,$prefix)!==false){
			if(fileext($sqlfile)=='sql'){
				$filenamearray=explode(".sql",$sqlfile);
				@unlink('../../databack/'.$sqlfile);
				@unlink('../../databack/sql/'.$met_agents_backup.'_'.$filenamearray[0].".zip");
			}
		}
	}
	metsave($rurls,'',$depth);
}else if($action=='daoru'){
	$query="select admin_op from $met_admin_table where admin_id='{$metinfo_admin_name}'";
	$admin_op = $db->get_one($query);
	if(strstr($admin_op['admin_op'],'metinfo') === false){
		echo "<script type='text/javascript'> alert('$lang_jsx38');location.href='recovery.php?anyid={$anyid}&lang={$lang}&cs=2'; </script>";
		die();
	}	
	$fileid = $fileid ? $fileid : 1;
	$filename = $pre.$fileid.'.sql';
	$filepath = '../../databack/'.$filename;
	if(file_exists($filepath)){
		$sql = file_get_contents($filepath);
		if(substr($sql,28,5)!=$metcms_v && substr($sql,28,6)!=$metcms_v)metsave($rurls,$lang_dataerr1,$depth);
		if($action == 'daoru'){
			if(stristr($sql,'INSERT INTO met_admin_table')){
				echo "<script>
				function import1(text){
					if(confirm(text)){
						location.href='./recovery.php?anyid=$anyid&pre=$pre&dosubmit=1&dosubmit1=0&lang=$lang';
					}else{
						location.href='./recovery.php?anyid=$anyid&pre=$pre&dosubmit=1&dosubmit1=1&lang=$lang';
					}
				}
				import1('$lang_js72');
				</script>";	
			}else{
				header("location:./recovery.php?anyid=$anyid&pre=$pre&dosubmit=1&lang=$lang");
			}
		}
	}
}else if($dosubmit){
	$fileid = $fileid ? $fileid : 1;
	$filename = $pre.$fileid.'.sql';
	$filepath = '../../databack/'.$filename;
	if(file_exists($filepath)){
		$sql = file_get_contents($filepath);
		if(substr($sql,28,5)!=$metcms_v && substr($sql,28,6)!=$metcms_v)metsave($rurls,$lang_dataerr1,$depth);
		sql_execute($sql,0,$dosubmit1);
		$fileid++;
		save_met_cookie();
		metsave($rurls."&pre=".$pre."&fileid=".$fileid."&dosubmit=1&adminmodify=1&database_met=1","{$lang_setdbDBFile} {$filename} {$lang_setdbImportOK}{$lang_setdbImportcen}",$depth,'','',1);
	}else{	
		require_once '../../column/global.func.php';
		$query="select * from $met_column where ((module<=5 and module>0) or (module=8)) and (classtype=1 or releclass!=0)";
		$result= $db->get_all($query);
		sitemap_robots();
		$sysflie=array(1=>'about',2=>'news',3=>'product',4=>'download',5=>'img',6=>'job',7=>'message',8=>'feedback');
		foreach($result as $key=>$val){
			if(array_search($val[foldername],$sysflie)===false){
				if(!file_exists(ROOTPATH.$val['foldername']))@mkdir(ROOTPATH.$val['foldername'], 0777); 
				column_copyconfig($val['foldername'],$val['module'],$val['id']);
			}
		}
		deltree(ROOTPATH.'cache');	
		$adminfile=$url_array[count($url_array)-2];
		if($met_adminfile!=""&&$met_adminfile!=$adminfile){
			$oldname='../../../'.$adminfile;
			$newname='../../../'.$met_adminfile;
			if(rename($oldname,$newname)){
				echo "<script type='text/javascript'> alert('{$lang_setdbDBRestoreOK}'); document.write('{$lang_authTip12}'); top.location.href='{$newname}'; </script>";
				die();
			}else{
				echo "<script type='text/javascript'> alert('{$lang_setdbDBRestoreOK}.{$lang_adminwenjian}'); top.location.reload(); </script>";
				die();
			}
		}
		$gent='../../include/404.php?lang='.$lang.'&metinfonow='.$met_member_force;
		metsave($rurls,$lang_setdbDBRestoreOK,$depth,'','',2,$gent);
	}
}else{
	$sqlfiles = glob('../../databack/*.sql');
	if(is_array($sqlfiles)){
		 $prepre = '';
		 $info = $infos = array();
		 foreach($sqlfiles as $id=>$sqlfile){
			preg_match("/([a-z0-9_]+_[0-9]{8}_[0-9a-zA-Z]{6}_)([a-z0-9]+)\.sql/i",basename($sqlfile),$num);
			$info['filename'] = basename($sqlfile);
			$info['filesize'] = round(filesize($sqlfile)/(1024*1024), 2);
			$info['maketime'] = date('Y-m-d H:i:s', filemtime($sqlfile));
			$info['pre'] = $num[1];
			$info['number'] = $num[2];
			if(!$id) $prebgcolor = '#E4EDF9';
			if($info['pre'] == $prepre){
				$info['bgcolor'] = $prebgcolor;
			}else{
				$info['bgcolor'] = $prebgcolor == '#E4EDF9' ? '#F1F3F5' : '#E4EDF9';
			}
			$prebgcolor = $info['bgcolor'];
			$prepre = $info['pre'];
			$infos[] = $info;
		}
	}
	foreach($infos as $key=>$val){
		$val['time']=strtotime($val['maketime']);
		$infos1[]=$val;
	}
	function array_sort($arr,$keys,$type='asc'){ 
		$keysvalue = $new_array = array();
		foreach ($arr as $k=>$v){
			$keysvalue[$k] = $v[$keys];
		}
		if($type == 'asc'){
			asort($keysvalue);
		}else{
			arsort($keysvalue);
		}
		reset($keysvalue);
		foreach ($keysvalue as $k=>$v){
			$new_array[$k] = $arr[$k];
		}
		return $new_array; 
	} 
	foreach($infos1 as $key=>$val){
		if($val['number']==1){
			$infos2[$val['pre']]=$val;
		}else{
			$infos3[]=$val;
		}
		
	}
	foreach($infos3 as $key=>$val){
		$infos2[$val[pre]][number]++;
		$infos2[$val[pre]][filesize]+=$val[filesize];
	}
	$infos=array_sort($infos2,'time','we');
	foreach($infos as $key=>$val){
		$fp = @fopen('../../databack/'.$val['filename'],"rb");
		$str = @fgets($fp);
		@fclose($fp);
		$infos[$key]['ver']=trim(str_replace('#MetInfo.cn Created version:','',$str));
	}
	foreach($infos as $key=>$val){
		$infos[$key]['filename']=$key.'1';
		$info_num=1;
		while(file_exists('../../databack/'.$key.$info_num.'.sql')){
			$info_num++;
		}
		if($info_num-1!=$val['number']){
			$infos[$key]['error']='1';
		}else{
			$infos[$key]['error']='0';
		}	
	}
	foreach($infos as $key=>$val){
		if($val[ver]!=$metcms_v){
			$val['error']=2;
			unset($infos[$key]);
			$infos[$key]=$val;
		}
	}
	if($met_agents_type>1){
		$lang_dataexplain2=str_replace('met',$met_agents_backup,$lang_dataexplain2);
	}
	$css_url=$depth."../templates/".$met_skin."/css";
	$img_url=$depth."../templates/".$met_skin."/images";
	include_once template('system/database/recovery');footer();
}
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>