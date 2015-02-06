<?php
require_once ("conn.php");
$name=$_POST["uname"];
$url=$_POST["uurl"];
$qq=$_POST["uqq"];
$other=$_POST["uother"];
$sql = "insert into user(name,url,qq,txt) values('$name','$url','$qq','$other')";
?>
<!doctype html>
<!--麦葱酱辛苦码的代码，阁下请不要随便乱扒-->
<!--Maicong so hard write the code , Please don't dig up-->
<!--英语捉急，请用谷歌翻译-->
<!--English is ......, Please use Google Translator-->
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="author" content="麦田一根葱">
<meta name="robots" content="nofollow">
<meta name="robots" content="noarchive">
<title>BYMT客户网站收集</title>
<meta name="keywords" content="BYMT" />
<meta name="description" content="………………" />
<link rel="stylesheet" type="text/css" href="http://libs.baidu.com/bootstrap/2.3.2/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="http://cdnjs.bootcss.com/ajax/libs/twitter-bootstrap/2.3.2/css/bootstrap-responsive.min.css">
<link rel="stylesheet" type="text/css" href="style.css">
<!--[if lt IE 9]>
<script type="text/javascript" src="//cdnjs.bootcss.com/ajax/libs/html5shiv/3.6.2/html5shiv.js"></script>
<![endif]-->
<meta charset="utf-8">
</head>
<body>
<div class="container">
	<h1 class="text-center">BYMT客户网站收集</h1>
	<p class="text-center muted">
	<?php if($_POST["submit"]){ 
	if (!isset($_POST["uname"])&&!isset($_POST["uurl"])&&!isset($_POST["uqq"])) { 
	?>
	<small class="iiie">前三项输入不能为空</small>
	<?php }if(mysql_query($sql,$conn)){ ?>
	<small class="iiis">提交成功</small>
	<?php }else{ ?>
	<small class="iiie">提交失败</small>
	<?php  } }else{ ?>
	<small>请大家将自己的网站网址提供给我，我收集一下</small>
	<?php } ?>
	</p>
	<form class="form-horizontal" name="gogogo" id="gogogo" method="post">
		<div class="control-group">
			<label class="control-label " for="uname">网站名称</label>
			<div class="controls">
				<input type="text" name="uname" id="uname" placeholder="网站名称" required /><span class="iiii" id="iname"></span>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="uurl">网站网址</label>
			<div class="controls">
				<input type="text" name="uurl" id="uurl" placeholder="请加上http://" pattern="((http|https)://)+([\w-]+\.)+[\w-]+(/[\w- ./?%&=]*)?"required /><span class="iiii" id="iurl"></span>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="uqq">Q Q号码</label>
			<div class="controls">
				<input type="text" name="uqq" id="uqq" placeholder="Q Q号码" pattern="^[1-9]{1}[0-9]{4,12}$" required /><span class="iiii" id="iqq"></span>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="uother">备 注</label>
			<div class="controls">
				<textarea rows="3" name="uother" id="uother" placeholder="可以为空"></textarea><span class="oooo" id="iother"></span>
			</div>
		</div>
		<div class="control-group">
		<div class="controls">
				<button type="submit" id="submit" class="btn btn-info w100">提 交</button>
				<button type="reset" class="btn w100">重 写</button>
		</div>
		</div>
	</form>
</div>
<script type="text/javascript" src="http://upcdn.b0.upaiyun.com/libs/jquery/jquery-1.8.3.min.js"></script>
<script type="text/javascript" src="http://libs.baidu.com/bootstrap/2.3.2/js/bootstrap.min.js"></script> 
<script type="text/javascript" src="user.js"></script> 
</body>
</html>