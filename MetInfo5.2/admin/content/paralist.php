<?php
require_once '../login/login_check.php';
require_once './global.func.php';

$table=moduledb($module);
if($id){
	$contentslist=$db->get_one("select * from $table where id='$id'");
	if($contentslist){
		$query = "select * from $met_plist where module='$module' and listid='$id'";
		$result = $db->query($query);
		while($list = $db->fetch_array($result)){
			$nowpara="para".$list[paraid];
			$contentslist[$nowpara]=$list[info];
			$nowparaname="";
			if($list[imgname]<>"")$nowparaname=$nowpara."name";$contentslist[$nowparaname]=$list[imgname];
		}
	}	
}
$str='';
$para_list=para_list_with($contentslist);
foreach($para_list as $key=>$val){
	$str.="
	<div class='v52fmbx_dlbox' name='paralist'>
	<dl>
			<dt>{$val[name]}{$lang_marks}</dt>
			<dd>{$val[inputcont]}</dd>
	</dl>
	</div>
	";
}
echo $str;
?>