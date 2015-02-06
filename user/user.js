jQuery(document).ready(function() {
	$("#uurl").blur('input', function (){
	url = $("#uurl").val();
	reurl = /^((http|https):\/\/)?(\w(\:\w)?@)?([0-9a-z_-]+\.)*?([a-z0-9-]+\.[a-z]{2,6}(\.[a-z]{2})?(\:[0-9]{2,6})?)((\/[^?#<>\/\\*":]*)+(\?[^#]*)?(#.*)?)?$/i; 
	if(!reurl.test(url)) {
		$("#iurl").text("网址有误");
	}else{
		$.ajax({
			type: 'POST',
			url: 'go.php?p=url',
			data: "url="+url,
			dataType: 'text',
			beforeSend:function(){
				$("#iurl").html("<img src='./images/loading.gif'>");
			},
			success: function(e){
				$("#iurl").text(e);
			},
			error:function(){
				$("#iurl").text("获取失败");
			}
			});
	}
	});
	$("#uqq").blur('input', function (){
	qq = $("#uqq").val();
	reqq = /^[1-9]{1}[0-9]{4,12}$/i; 
	if(!reqq.test(qq)) {
		$("#iqq").text("QQ号码有误");
	}else{
		$.ajax({
			type: 'POST',
			url: 'go.php?p=qq',
			data: "qq="+qq,
			dataType: 'text',
			beforeSend:function(){
				$("#iqq").html("<img src='./images/loading.gif'>");
			},
			success: function(e){
				$("#iqq").text(e);
			},
			error:function(){
				$("#iqq").text("获取失败");
			}
			});
	}
	});
	$("#gogogo").submit(function(){
	name = $("#uname").val();
	url = $("#uurl").val();
	qq = $("#uqq").val();
	if(name==""){
		$("#iname").addClass("iiie");
		$("#iname").text("← ←不要留空哦");
		return false;
	}
	else if(url==""){
		$("#iurl").addClass("iiie");
		$("#iurl").text("← ←不要留空哦");
		return false;
	}
	else if(qq==""){
		$("#iqq").addClass("iiie");
		$("#iqq").text("← ←不要留空哦");
		return false;
	}
	else{
		$("#submit").attr('disabled', true);
		$("#submit").text("提交中...");
	}
	});
});