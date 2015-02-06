(function($){var jspath=$('script').last().attr('src');var basepath='';if(jspath.indexOf('/')!=-1){basepath+=jspath.substr(0,jspath.lastIndexOf('/')+1);}$.fn.fixpng=function(options){function _fix_img_png(el,emptyGIF){var images=$('img[src*="png"]',el||document),png;images.each(function(){png=this.src;width=this.width;height=this.height;this.src=emptyGIF;this.width=width;this.height=height;this.style.filter="progid:DXImageTransform.Microsoft.AlphaImageLoader(src='"+png+"',sizingMethod='scale')";});}function _fix_bg_png(el){var bg=$(el).css('background-image');if(/url\([\'\"]?(.+\.png)[\'\"]?\)/.test(bg)){var src=RegExp.$1;$(el).css('background-image','none');$(el).css("filter","progid:DXImageTransform.Microsoft.AlphaImageLoader(src='"+src+"',sizingMethod='scale')");}}if($.browser.msie&&$.browser.version<7){return this.each(function(){var opts={scope:'',emptyGif:basepath+'blank.gif'};$.extend(opts,options);switch(opts.scope){case'img':_fix_img_png(this,opts.emptyGif);break;case'all':_fix_img_png(this,opts.emptyGif);_fix_bg_png(this);break;default:_fix_bg_png(this);break;}});}}})(jQuery);function timehide(dom,time){if(!dom.is(':hidden')){t=setTimeout(function(){dom.hide();},time);dom.hover(function(){clearTimeout(t);},function(){timehide(dom,time);});}}
function mhover(dom,cs){if(dom)dom.hover(function(){$(this).addClass(cs);},function(){$(this).removeClass(cs);});}
function cknav(d){if(d instanceof jQuery){if(d.attr("id")!='top_quick_a'){$('#topnav a').removeClass("onnav");d.addClass("onnav");$("ul[id^='ul_']").hide();var u=String(d.attr('id'));u=u.split('_');u=u[1];$.cookie('conav',null);$.cookie('conav',u,{path:'/'});$("#ul_"+u).show();u=$("#ul_"+u).find('li a').eq(0);frameget(u);}}else{cknav($('#nav_'+d));}}
function frameget(u){if(u.attr('target')){if(u.attr('target')=='_blank')return false;$("#leftnav a").removeClass("on");$("#main").attr("src",u.attr('href'));$("#main").src=$("#main").src;u.addClass("on");var l=u.attr('id');l=l.split('_');$.cookie('coul',null);$.cookie('coul',l[2],{path:'/'});}}
function qielang(lg, lt, msg) {
    var l = lg.attr('lang');
	var mh = lt.height();
    lt.append(msg);
	$("#jiazaizhuangtai").hide();
    var m = $('div.langlist li');
    mhover(m, 'hv');
    m.each(function() {
        var d = $(this);
        d.attr('title') == l ? d.addClass('dlang') : d.removeClass('dlang');
    });
	lt.width(lt.width());
    expandtan(lt,mh);
    $('div.langlist li').click(function() {
        var d = $(this);
		if(d.attr('id')!='addlang'){
			var t = d.attr('lang');
			var l = lg.attr('lang');
			if (t != l) {
				var h = $('#leftnav li a.on').attr('href');
				if (!h) {
					h = 'system/sysadmin.php?anyid=8&';
				} else {
					h = h.split('lang=');
					h = h[0];
				}
				var n = $("#leftnav a[id^='nav_']");
				n.each(function() {
					var a = $(this).attr('href');
					a = a.split('lang=');
					a = a[0] + 'lang=' + t;
					$(this).attr('href', a);
				});
				lg.attr('lang', t);
				lg.attr('title', user_msg['langtips1']+d.text());
				var hr = h + 'lang=' + t;
				$('#main').attr('src', hr);
				var limg = d.attr('flag');
				var dhm = d.attr('lname');
					dhm = '<span><img src="../public/images/flag/'+limg+'">'+dhm+'</span>';
				lg.empty().append(dhm);
				m.removeClass('dlang');
				d.addClass('dlang');
				if (t != l) $.cookie('clang', t);
				//adminp(l, t);
			}
			lt.hide();
		}
    });
}
function adminp(l,t){if(l==t){}else{window.location.href='index.php?lang='+t;}}
function commercial_license(){$.ajax({url:'system/authcode.php?lang='+lang+'&autcod=1&cs=1&action_ajax=1',type:"GET",success:function(data){if(data==0){var txt='未'+'购'+'买'+'商'+'业'+'授'+'权';if(!window.atop){data=txt;}else{data=atop==''?txt:atop;}}
$(".top-"+"r-t").append('<div id="met'+'info'+'_lice'+'nse"></div>');var met=$("#metinfo_license");met.html('<a href="javascript:;" title="'+data+'" id="license_isok">'+data+'</a>');met.find('a').css({'color':'#f2fb02'});$('#license_isok').live('click',function(){$('#nav_1').click();$('#nav_1_15').click();});}});}

var conav = $.cookie('conav');
var coul = $.cookie('coul');
if (conav) {
	cknav(conav);
	if (coul) frameget($('#nav_' + conav + '_' + coul));
}
//页面高度计算
function ymresize(){
	var jiluht=document.documentElement.clientHeight;
	var berger=jiluht-98;
	if(berger<500)berger=500;
	$("#metcmsbox").attr('jiluht',berger);
	$('#metleft').height(berger);
	$('#metleft .floatl_box').height(berger);
	$('#metright').height(berger);
	$('#metright iframe').height(berger);
}
ymresize();
//
$(window).resize(function() {
	//ymresize();
});
//
 $('#topnav a').click(function() {
	cknav($(this));
});
$("#leftnav a").click(function() {
	frameget($(this));
});
//展开动画
function expandtan(dm,mh){
	var h = dm.height();
	var m = mh?mh:0;
	dm.height(m);
	dm.animate({ height: h+"px"}, 200);
	/*
	var k = 0;
	dm.find('li').each(function(){
		if($(this).find('span').width()>k)k=$(this).find('span').width();
	});
	dm.find('li').width(k);
	$('#addlang').width(k-21);
	*/
}
var lg = $('#langcig span.title');
var lt = lg.next('div.langlist');
/*
$("#langcig span.title").click(function(){
	if(lt.is(':hidden')){
		lt.empty().append('<div style="text-align:center;">' + user_msg['jsx1'] + '</div>');
		$.ajax({
			type: "POST",
			url: "include/return.php?type=lang",
			success: function(msg) {
				var msgs=msg.split('|');
				if(msgs[0]=='SUC'){
					if (msgs[1] != '') {
						qielang(lg,lt, msgs[1]);
						expandtan(lt);
					}
				}
			}
		});
		$("#langcig").addClass('nowt');
		//lt.show();
	}else{
		$("#langcig").removeClass('nowt');
		lt.empty().hide();
	}
});
*/
$("#langcig").hover(
	function() {
		lt.empty().append('<div style="text-align:center;" id="jiazaizhuangtai">' + user_msg['jsx1'] + '</div>');
		lt.show();
		$.ajax({
			type: "POST",
			url: "include/return.php?type=lang",
			success: function(msg) {
				var msgs=msg.split('|');
				if(msgs[0]=='SUC'){
					if (msgs[1] != '') {
						qielang(lg,lt, msgs[1]);
					}
				}
			}
		});
		$("#langcig").addClass('nowt');
		//lt.show();
	},
	function(){
		$("#langcig").removeClass('nowt');
		lt.stop(true,true);
		lt.empty().hide();
	}
);

$('#met_logo').click(function() {
	$.cookie('conav', 1);
	$.cookie('coul', 8);
});
$('#mydata').click(function() {
	$.cookie('conav', 5);
	$.cookie('coul', 64);
});
$('#qthome').click(function() {
	var h = '../index.php?lang=';
	var l = $('#langcig span.title').attr('lang') ? $('#langcig span.title').attr('lang') : lang;
	$(this).attr('href', h + l);
});
$('#outhome').click(function() {
	$.cookie('conav', null);
	$.cookie('clang', null);
});
 $(".langkkkbox").hover(function() {
	$(this).addClass('now');
},
function() {
	$(this).removeClass('now');
});
$('#addlang').live("click",function(){
	$.cookie('conav', 5);
	$.cookie('coul', 10);
	$.cookie('addlang', 1, {path: '/'});
	window.location.reload();
});

/*快捷提交*/
Array.prototype.unique = function() {
	var o = {};
	for (var i = 0, j = 0; i < this.length; ++i) {
		if (o[this[i]] === undefined) {
			o[this[i]] = j++;
		}
	}
	this.length = 0;
	for (var key in o) {
		this[o[key]] = key;
	}
	return this;
};
var keys = [];
$(document).keydown(function(event) {
	keys.push(event.keyCode);
	keys.unique();
}).keyup(function(event) {
	if (keys.length > 2) keys = [];
	keys.push(event.keyCode);
	keys.unique();
	if (keys.join('') == '1713') {
		var input = $(window.frames["main"].document).find("input[type='submit']");
		if (input.size() == 0 ) {
			input = $(window.frames["main"].document).find("input");
			input.each(function(){
				if($(this).attr('type')=='submit'){
					input = $(this);
				}
			});
		}
		if (input.size() > 0) {
			if (!input.attr('disabled')) {
				input.click();
			}
		}
	}
	keys = [];
});
/*快捷提交截至*/
$(document).ready(function() {
	commercial_license();
});
/*顶部导航宽度*/
function topwidth(tm){
	var navk = $("#topnav").width();
	$("#langcig span.title img").hide();
	var ritk = $('ol.rnav').width()+21;
	$("#langcig span.title img").show();
	//if((navk+ritk)>$(".top-r-box").width()){
		var ls = $("#topnav li").size();
		var kd = parseInt(($(".top-r-box").width() - ritk - 5)/ls);
		if(kd>130)kd=130;
		tm = tm?tm:0;
		$("#topnav li").animate({ width: kd+'px'}, tm);
	//}
}
topwidth();

$(function(){
	var navH = $("#leftnav").offset().top; 
	$(window).scroll(function(){
		var scroH = $(this).scrollTop();  
		if(scroH>=navH){ 
			var  keH= $(".floatl_box").height()-$("#leftnav").height()-$(".left_footer").height();
			if(scroH>=(navH+keH)){
				$("#leftnav").css({"position":"absolute","top":keH}); 
			}else{
				$("#leftnav").css({"position":"fixed","top":0,'width':'154px'}); 
			}	
		}else if(scroH<navH){ 
			$("#leftnav").css({"position":"static"}); 
		} 
	}) 
})