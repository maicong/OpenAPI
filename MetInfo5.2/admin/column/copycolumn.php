<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
require_once '../login/login_check.php';
require_once './global.func.php';
if(!$copylang){
	metsave('../column/index.php?anyid='.$anyid.'&lang='.$lang,$lang_copyotherlang3,$depth);	
}
$allidlist=explode(',',$allid);
$adnum = count($allidlist)-1;
$allid=','.$allid;
//判断是否是一级栏目
for($i=0;$i<$adnum;$i++){
	$valid=$allidlist[$i];
	if($met_class[$valid][classtype]==3){
		$first_column=$met_class[$met_class[$valid][bigclass]][bigclass];
	}
	if($met_class[$valid][classtype]==2){
		$first_column=$met_class[$valid][bigclass];
	}
	if($met_class[$valid][classtype]==1){
		$first_column=$valid;
	}
	if(strpos($allid,",{$first_column},")===false){
		metsave('../column/index.php?anyid='.$anyid.'&lang='.$lang,$lang_copyotherlang5,$depth);	
		break;
	}
}

$metinfo='';
$lang_now=$lang;
$lang=$copylang;
$cpoy_list_id[0]=0;
$cpoy_bigclass_id[0]=0;
$is_column='';
//复制栏目
for($i=0;$i<$adnum;$i++){
	$query="select * from $met_column where id='$allidlist[$i]'";
	$column=$db->get_one($query);
	$query="select * from $met_column where foldername='$column[foldername]' and lang='$copylang' and (classtype='1' or releclass!='0')";
	$foldername_now=$db->get_one($query);
	if($foldername_now&&$cpoy_insert_id[$foldername_now[id]]!=1){
		if($column[module]==0){
			$query="select * from $met_column where out_url='$column[out_url]' and module='$column[module]' and lang=''";
		}else if($column[module]==101){
			
		}else if($column[module]==100){
			
		}else{
			if($column[classtype]==1||$column[releclass]!=0){
				$is_column.=','.$column[name];
			}
			continue;
		}
	}
	$column[foldername_copy]=$column[foldername];
	$column[name]=str_replace('\'','\'\'',$column[name]);
	$column[foldername_copy]=str_replace('\'','\'\'',$column[foldername_copy]);
	$column[ctitle]=str_replace('\'','\'\'',$column[ctitle]);
	$column[keywords]=str_replace('\'','\'\'',$column[keywords]);
	$column[content]=str_replace('\'','\'\'',$column[content]);
	$column[new_windows]=str_replace('\'','\'\'',$column[new_windows]);
	$query="INSERT INTO $met_column set 
			name                     ='$column[name]',
			foldername               ='$column[foldername_copy]',
			filename                 ='',
			bigclass                 ='',
			samefile                 ='',
			module                   ='$column[module]',
			no_order                 ='$column[no_order]',
			wap_ok                   ='$column[wap_ok]',
			if_in                    ='$column[if_in]',
			nav                      ='$column[nav]',
			ctitle                   ='$column[ctitle]',
			keywords                 ='$column[keywords]',
			content                  ='$column[content]',
			description              ='$column[description]',
			list_order               ='$column[list_order]',
			new_windows              ='$column[new_windows]',
			classtype                ='$column[classtype]',
			out_url                  ='$column[out_url]',
			index_num                ='$column[index_num]',
			access                   ='$column[access]',
			indeximg                 ='$column[indeximg]',
			columnimg                ='$column[columnimg]',
			isshow                   ='$column[isshow]',
			lang                     ='$copylang',
			namemark                 ='$column[namemark]',
			releclass                ='$column[releclass]'
	";
	$db->query($query);
	$insert_id=$db->insert_id();
	$cpoy_list_id[$column[id]]=$insert_id;
	$cpoy_bigclass_id[$insert_id]=$column[bigclass];
	$cpoy_insert_id[$insert_id]=1;
	if($column[classtype]==1||$column[releclass]!=0){
		$content_class1_tmp[id]=$column[id];
		$content_class1_tmp[module]=$column[module];
		$content_class1[]=$content_class1_tmp;
		if($column[module]!=6)column_copyconfig($column[foldername_copy],$column[module],$insert_id);
		if($column[module]==6||$column[module]==7||$column[module]==8){
			$query="select * from $met_config where columnid='$column[id]' and flashid='0'";
			$config_para=$db->get_all($query);
			foreach($config_para as $key=>$val){
				$para_name=$val[name];
				$query="update $met_config set value='$val[value]' where columnid='{$insert_id}' and flashid='0' and name='{$para_name}'";
				$db->query($query);
			}
		}
		if($column[module]==3||$column[module]==4||$column[module]==5||$column[module]==6||$column[module]==8){
			$query = "select * from $met_parameter where (class1='0' or class1='$column[id]') and module='$column[module]' and lang='$lang_now'";
			$para_list=$db->get_all($query);
			foreach($para_list as $key=>$val){
				$val[name]=str_replace('\'','\'\'',$val[name]);
				$val[description]=str_replace('\'','\'\'',$val[description]);
				if($val[class1])$val[class1]=$insert_id;
				$query="insert into $met_parameter set name='$val[name]',description='$val[description]',no_order='$val[no_order]',type='$val[type]',access='$val[access]',wr_ok='$val[wr_ok]',class1='$val[class1]',module='$val[module]',lang='$copylang'";
				$db->query($query);
				$copy_para_list[$val[id]]=$db->insert_id();
				if($val[type]==2||$val[type]==4||$val[type]==6){
					$query="select * from $met_list where bigid='$val[id]'";
					$slist_list=$db->get_all($query);
					$paraid=$db->insert_id();
					foreach($slist_list as $key1=>$val1){
						$val1[info]=str_replace('\'','\'\'',$val1[info]);
						$query="insert into $met_list set bigid='{$copy_para_list[$val[id]]}',info='$val1[info]',no_order='$val1[no_order]',lang='$copylang'";
						$db->query($query);
					}
				}
			}
		}
	}
}
foreach($cpoy_bigclass_id as $key=>$val){
	$query="update $met_column set bigclass='$cpoy_list_id[$val]' where id='$key'";
	$db->query($query);
}
//复制内容
if($copycontent){
	foreach($content_class1 as $key=>$val){
		$table_name=moduledb($val[module]);
		switch($val[module]){
			case 2:
				$query = "SELECT * FROM {$met_news} where class1='$val[id]'";
				$result = $db->query($query);
				while($list= $db->fetch_array($result)){
					$list[content]=str_replace('\'','\'\'',$list[content]);
					$list[title]=str_replace('\'','\'\'',$list[title]);
					$list[ctitle]=str_replace('\'','\'\'',$list[ctitle]);
					$list[keywords]=str_replace('\'','\'\'',$list[keywords]);
					$list[description]=str_replace('\'','\'\'',$list[description]);
					$query = "insert into {$met_news} set title='$list[title]',ctitle='$list[ctitle]',keywords='$list[keywords]',description='$list[description]',content='$list[content]',class1='{$cpoy_list_id[$list[class1]]}',class2='{$cpoy_list_id[$list[class2]]}',class3='{$cpoy_list_id[$list[class3]]}',no_order='$list[no_order]',wap_ok='$list[wap_ok]',img_ok='$list[img_ok]',imgurl='$list[imgurl]',imgurls='$list[imgurls]',com_ok='$list[com_ok]',issue='$list[issue]',hits='$list[hits]',updatetime='$list[updatetime]',addtime='$list[addtime]',access='$list[access]',top_ok='$list[top_ok]',lang='{$copylang}',recycle='$list[recycle]'";
					$db->query($query);
				}
			break;
			case 3:
				$query = "SELECT * FROM {$met_product} where class1='$val[id]'";
				$result = $db->query($query);
				while($list= $db->fetch_array($result)){
					$list[content]=str_replace('\'','\'\'',$list[content]);
					$list[content1]=str_replace('\'','\'\'',$list[content1]);
					$list[content2]=str_replace('\'','\'\'',$list[content2]);
					$list[content3]=str_replace('\'','\'\'',$list[content3]);
					$list[content4]=str_replace('\'','\'\'',$list[content4]);
					$list[title]=str_replace('\'','\'\'',$list[title]);
					$list[ctitle]=str_replace('\'','\'\'',$list[ctitle]);
					$list[keywords]=str_replace('\'','\'\'',$list[keywords]);
					$list[description]=str_replace('\'','\'\'',$list[description]);
					$query = "insert into {$met_product} set title='$list[title]',ctitle='$list[ctitle]',keywords='$list[keywords]',description='$list[description]',content='$list[content]',class1='{$cpoy_list_id[$list[class1]]}',class2='{$cpoy_list_id[$list[class2]]}',class3='{$cpoy_list_id[$list[class3]]}',no_order='$list[no_order]',wap_ok='$list[wap_ok]',new_ok='$list[new_ok]',imgurl='$list[imgurl]',imgurls='$list[imgurls]',displayimg='$list[displayimg]',com_ok='$list[com_ok]',hits='$list[hits]',updatetime='$list[updatetime]',addtime='$list[addtime]',issue='$list[issue]',access='$list[access]',top_ok='$list[top_ok]',lang='{$copylang}',content1='$list[content1]',content2='$list[content2]',content3='$list[content3]',content4='$list[content4]',contentinfo='$list[contentinfo]',contentinfo1='$list[contentinfo1]',contentinfo2='$list[contentinfo2]',contentinfo3='$list[contentinfo3]',contentinfo4='$list[contentinfo4]',recycle='$list[recycle]'";
					$db->query($query);
					$insert_contents_id=$db->insert_id();
					$query="select * from {$met_plist} where listid='{$list[id]}'";
					$plist=$db->get_all($query);
					foreach($plist as $key2=>$val2){
						if($copy_para_list[$val2[paraid]]){
							$query="insert into {$met_plist} set listid='{$insert_contents_id}',paraid='{$copy_para_list[$val2[paraid]]}',info='$val2[info]',lang='$copylang',imgname='$val2[imgname]',module='$val2[module]'";
							$db->query($query);
						}
					}
				}
			break;
			case 4:
				$query = "SELECT * FROM {$met_download} where class1='$val[id]'";
				$result = $db->query($query);
				while($list= $db->fetch_array($result)){
					$list[content]=str_replace('\'','\'\'',$list[content]);
					$list[title]=str_replace('\'','\'\'',$list[title]);
					$list[ctitle]=str_replace('\'','\'\'',$list[ctitle]);
					$list[keywords]=str_replace('\'','\'\'',$list[keywords]);
					$list[description]=str_replace('\'','\'\'',$list[description]);
					$query = "insert into {$met_download} set title='$list[title]',ctitle='$list[ctitle]',keywords='$list[keywords]',description='$list[description]',content='$list[content]',class1='{$cpoy_list_id[$list[class1]]}',class2='{$cpoy_list_id[$list[class2]]}',class3='{$cpoy_list_id[$list[class3]]}',no_order='$list[no_order]',new_ok='$list[new_ok]',wap_ok='$list[wap_ok]',downloadurl='$list[downloadurl]',filesize='$list[filesize]',com_ok='$list[com_ok]',hits='$list[hits]',updatetime='$list[updatetime]',addtime='$list[addtime]',issue='$list[issue]',access='$list[access]',top_ok='$list[top_ok]',downloadaccess='$list[downloadaccess]',lang='{$copylang}',recycle='$list[recycle]'";
					$db->query($query);
					$insert_contents_id=$db->insert_id();
					$query="select * from {$met_plist} where listid='{$list[id]}'";
					$plist=$db->get_all($query);
					foreach($plist as $key2=>$val2){
						if($copy_para_list[$val2[paraid]]){
							$query="insert into {$met_plist} set listid='{$insert_contents_id}',paraid='{$copy_para_list[$val2[paraid]]}',info='$val2[info]',lang='$copylang',imgname='$val2[imgname]',module='$val2[module]'";
							$db->query($query);
						}
					}
				}
			break;
			case 5:
				$query = "SELECT * FROM {$met_img} where class1='$val[id]'";
				$result = $db->query($query);
				while($list= $db->fetch_array($result)){
					$list[content]=str_replace('\'','\'\'',$list[content]);
					$list[content1]=str_replace('\'','\'\'',$list[content1]);
					$list[content2]=str_replace('\'','\'\'',$list[content2]);
					$list[content3]=str_replace('\'','\'\'',$list[content3]);
					$list[content4]=str_replace('\'','\'\'',$list[content4]);
					$list[title]=str_replace('\'','\'\'',$list[title]);
					$list[ctitle]=str_replace('\'','\'\'',$list[ctitle]);
					$list[keywords]=str_replace('\'','\'\'',$list[keywords]);
					$list[description]=str_replace('\'','\'\'',$list[description]);
					$query = "insert into {$met_img} set title='$list[title]',ctitle='$list[ctitle]',keywords='$list[keywords]',description='$list[description]',content='$list[content]',class1='{$cpoy_list_id[$list[class1]]}',class2='{$cpoy_list_id[$list[class2]]}',class3='{$cpoy_list_id[$list[class3]]}',no_order='$list[no_order]',wap_ok='$list[wap_ok]',new_ok='$list[new_ok]',imgurl='$list[imgurl]',imgurls='$list[imgurls]',displayimg='$list[displayimg]',com_ok='$list[com_ok]',hits='$list[hits]',updatetime='$list[updatetime]',addtime='$list[addtime]',issue='$list[issue]',access='$list[access]',top_ok='$list[top_ok]',lang='{$copylang}',content1='$list[content1]',content2='$list[content2]',content3='$list[content3]',content4='$list[content4]',contentinfo='$list[contentinfo]',contentinfo1='$list[contentinfo1]',contentinfo2='$list[contentinfo2]',contentinfo3='$list[contentinfo3]',contentinfo4='$list[contentinfo4]',recycle='$list[recycle]'";
					$db->query($query);
					$insert_contents_id=$db->insert_id();
					$query="select * from {$met_plist} where listid='{$list[id]}'";
					$plist=$db->get_all($query);
					foreach($plist as $key2=>$val2){
						if($copy_para_list[$val2[paraid]]){
							$query="insert into {$met_plist} set listid='{$insert_contents_id}',paraid='{$copy_para_list[$val2[paraid]]}',info='$val2[info]',lang='$copylang',imgname='$val2[imgname]',module='$val2[module]'";
							$db->query($query);
						}
					}
				}
			break;
			case 6:
				$query = "SELECT * FROM {$met_job} where lang='$lang_now'";
				$result = $db->query($query);
				while($list= $db->fetch_array($result)){	
					$list[position]=str_replace('\'','\'\'',$list[position]);
					$list[place]=str_replace('\'','\'\'',$list[place]);
					$list[deal]=str_replace('\'','\'\'',$list[deal]);
					$list[content]=str_replace('\'','\'\'',$list[content]);
					$query = "INSERT INTO {$met_job} SET position='$list[position]',count='$list[count]',place='$list[place]',deal='$list[deal]',addtime='$list[addtime]',useful_life='$list[useful_life]',content='$list[content]',access='$list[access]', top_ok='$list[top_ok]',wap_ok='$list[wap_ok]',filename='',email='$list[email]',no_order='$list[no_order]',lang='$copylang'";
			        $db->query($query);
				}
			break;
			case 7:
				$query = "SELECT * FROM {$met_message} where lang='$lang_now'";
				$result = $db->query($query);
				while($list= $db->fetch_array($result)){
					$list[customerid]=str_replace('\'','\'\'',$list[customerid]);
					$query = "INSERT INTO {$met_message} SET ip='$list[ip]',addtime='$list[addtime]',readok='$list[readok]',useinfo='$list[useinfo]',lang='$copylang',access='$list[access]',customerid='$list[customerid]'";
			        $db->query($query);
				}
				$query = "SELECT * FROM {$met_parameter} where lang='$lang_now' and module=7 ORDER BY id";
				$result = $db->query($query);
				while($parameter= $db->fetch_array($result)){
					$query = "INSERT INTO {$met_parameter} SET name='$parameter[name]',description='$parameter[description]',no_order='$parameter[no_order]',type='$parameter[type]',access='$parameter[access]',wr_ok='$parameter[wr_ok]',class1='$parameter[class1]',class2='$parameter[class2]',class3='$parameter[class3]',module='$parameter[module]',lang='$copylang',wr_oks='$parameter[wr_oks]'";
					$db->query($query);
				}
				$query = "SELECT id FROM $met_message where lang='{$copylang}' ORDER BY id";
				$result = $db->query($query);
				while($list1= $db->fetch_array($result)){
					$mlist_id[]=$list1[id];
				}
				$query = "SELECT id FROM $met_parameter where lang='{$copylang}' and module=7 ORDER BY id";
				$result = $db->query($query);
				while($list2= $db->fetch_array($result)){
					$paraid_id[]=$list2[id];
				}
				$query = "SELECT * FROM {$met_mlist} where lang='$lang_now' and module=7 order by listid and id";
				$result = $db->query($query);
				$count= 0 ;
				$i = 0;
				$j = 0;
				while($mlist= $db->fetch_array($result)){
					$count+=1;
					$query = "INSERT INTO {$met_mlist} SET listid='$mlist_id[$i]',paraid='$paraid_id[$j]',info='$mlist[info]',lang='$copylang',imgname='$mlist[imgname]',module=7";
					$db->query($query);
					if($count == count($paraid_id))$i+=1;
					$j+=1;
					if($j==count($paraid_id))$j=0;
				}
			break;
			case 8:
				$query = "SELECT * FROM {$met_feedback} where class1='$val[id]'";
				$result = $db->query($query);
				while($list= $db->fetch_array($result)){
					$list[fdtitle]=str_replace('\'','\'\'',$list[fdtitle]);
					$list[fromurl]=str_replace('\'','\'\'',$list[fromurl]);
					$list[useinfo]=str_replace('\'','\'\'',$list[useinfo]);
					$list[customerid]=str_replace('\'','\'\'',$list[customerid]);
					$query = "INSERT INTO {$met_feedback} SET class1='$insert_id',fdtitle='$list[fdtitle]',fromurl='$list[fromurl]',ip='$list[ip]',addtime='$list[addtime]',readok='$list[readok]',useinfo='$list[useinfo]',customerid='$list[customerid]',lang='$copylang'";
			        $db->query($query);
				}
			break;
			case 9:
				$query = "SELECT * FROM {$met_link} where lang='$lang_now'";
				$result = $db->query($query);
				while($list= $db->fetch_array($result)){
					$list[webname]=str_replace('\'','\'\'',$list[webname]);
					$list[weburl]=str_replace('\'','\'\'',$list[weburl]);
					$list[weblogo]=str_replace('\'','\'\'',$list[weblogo]);
					$list[info]=str_replace('\'','\'\'',$list[info]);
					$list[contact]=str_replace('\'','\'\'',$list[contact]);
					$query = "INSERT INTO {$met_link} SET webname='$list[webname]',weburl='$list[weburl]',weblogo='$list[weblogo]',link_type='$list[link_type]',info='$list[info]',contact='$list[contact]',orderno='$list[orderno]',com_ok='$list[com_ok]',show_ok='$list[show_ok]',addtime='$list[addtime]',lang='$copylang',ip='$list[ip]'";
			        $db->query($query);
				}
			break;
		}
	}
}
if($is_column){
	$is_column=trim($is_column,',');
	$re=$is_column.$lang_copyotherlang4;
}
$lang=$lang_now;
metsave('../column/index.php?anyid='.$anyid.'&lang='.$lang,$re,$depth);	
die;
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>