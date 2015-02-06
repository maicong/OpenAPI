<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
$depth='../';
require_once $depth.'../login/login_check.php';
if($action=='add'){
	$num = $lp+1;
    $newlist = "
		<div class=\"v52fmbx_dlbox newlist\">
			<dl>
				<dt class='addimgdt'>
					<p>{$lang_setflashName}{$lang_marks}</p>
					<p>{$lang_modimgurl}{$lang_marks}</p>
				</dt>
				<dd style='position:relative;'>
					<div style='margin-bottom:10px;'>
						<input name='displayname{$lp}' type='text' class='text med' value='' />
					</div>
					<input name='displayimg{$lp}' type='text' class='text' value='' />
					<input name='met_upsql{$lp}' type='file' id='displayimg_upload{$lp}' />
					<script type='text/javascript'>
						metuploadify('#displayimg_upload{$lp}','big_wate_img','displayimg{$lp}','','3');
					</script>
					<a href='javascript:;' onclick='imgnumfu();deletdisplayimg($(this));' class='displayimg_del'>{$lang_delete}</a>
				</dd>
			</dl>
		</div>
			";
	echo $newlist;
}
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>