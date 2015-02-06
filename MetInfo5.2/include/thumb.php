<?php
require_once './common.inc.php';
require_once '../'.$met_adminfile.'/include/upfile.class.php';
header("Content-type: image/jpeg");
//$thumb_src.'dir=原图路径&x=长&y=宽'
$ext1 = explode("/", $dir);
$count = count($ext1);
$count1 = $ext1[$count-1];
$ext2 = explode(".", $count1);
$ext3 = $ext2[1];
$path1 = $ext2[0];
$dir1 = '../upload/thumb_src/'.$x.'_'.$y.'/'.$path1.'.'.$ext3;
if(file_exists($dir1)){
	readfile("$dir1");
}else{
	$f = new upfile();
	$imgurls = $f->createthumb($dir,$x,$y);
	readfile($imgurls);
}
?>