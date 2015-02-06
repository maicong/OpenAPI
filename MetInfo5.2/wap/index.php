<?php 
$index="wap";
require_once '../include/common.inc.php';
if(!$met_wap)okinfo('../index.php?lang='.$lang,$lang_metwapok);
require_once 'wap.php';
if(!$met_wap_logo)$met_wap_logo=$met_logo;
if(!$wap_description)$wap_description=$met_description;
include waptemplate($temp);
wapfooter();
?> 