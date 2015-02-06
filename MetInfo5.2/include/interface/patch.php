<?php
require_once '../common.inc.php';
require_once ROOTPATH.'include/export.func.php';
if($action=='patch'){
	$met_file='/dl/patch.php';
	$post_data = array('ver'=>$metcms_v,'patch'=>$met_patch);
	$difilelist=curl_post($post_data,10);
	if($difilelist!='nohost'){
		$difilelists=explode('*',$difilelist);
		$met_file='/dl/olupdate_curl.php';	
		foreach($difilelists as $key=>$val){
			$difilelistss=explode('|',$val);
			$met_patch=$difilelistss[0];
			unset($difilelistss[0]);
			foreach($difilelistss as $key1=>$val1){
				$val2=readmin($val1,$met_adminfile,2);
				filetest("../../$val2");
				$re=dlfile("v$metcms_v/$val1","../../$val2");
				if($re!=1){
					echo $re;
					die();
				}
			}
			if(file_exists("../../$met_adminfile/update/v{$metcms_v}_{$met_patch}.php")){
				require_once "../../$met_adminfile/update/v{$metcms_v}_{$met_patch}.php";
			}
			@unlink("../../$met_adminfile/update/v{$metcms_v}_{$met_patch}.php");
			$query="update $met_config set value='$met_patch' where name='met_patch'";
			$db->query($query);
		}
		echo 1;
	}
	else{
		echo 2;
	}
	die();
}
?>