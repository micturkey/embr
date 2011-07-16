// Flash preview
TUDOU_EMBED = '<br /><embed src="http://www.tudou.com/v/src_id" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" wmode="opaque" width="420" height="363"></embed>';
XIAMI_EMBED = '<br /><embed src="http://www.xiami.com/widget/0_src_id/singlePlayer.swf" type="application/x-shockwave-flash" width="257" height="33" wmode="transparent"></embed>';
YOUKU_EMBED = '<br /><embed src="http://player.youku.com/player.php/sid/src_id/v.swf" quality="high" width="420" height="363" align="middle" allowScriptAccess="allways" mode="transparent" type="application/x-shockwave-flash"></embed>';
YOUTUBE_EMBED = '<br /><embed src="' + document.location.protocol + '//www.youtube.com/e/src_id?enablejsapi=1&version=3&playerapiid=ytplayer" quality="high" width="420" height="363" align="middle" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true"></embed>';
EMBED_FRAME = '';

function getFlashReg(sSite) {
	switch (sSite) {
	case 'www.xiami.com':
		EMBED_FRAME = XIAMI_EMBED;
		return /[\S]+\.xiami\.com\/song\/([\d]+)[\S]*/i;
		break;
	case 'www.tudou.com':
		EMBED_FRAME = TUDOU_EMBED;
		return /[\S]+.tudou.[\S]+\/([\w-]+)[\S]*/i;
		break;
	case 'v.youku.com':
		EMBED_FRAME = YOUKU_EMBED;
		return /[\S]+.youku.com\/v_show\/id_([\w-]+)[\S]*(.html)/i;
		break;
	case 'youtu.be':
		EMBED_FRAME = YOUTUBE_EMBED;
		return /youtu.be\/([\w-_?]+)[\S]*/i;
		break;
	case 'www.youtube.com':
		EMBED_FRAME = YOUTUBE_EMBED;
		return /[\S]+.youtube.[\S]+\/watch\?v=([\w-_?]+)[\S]*/i;
		break;
	default:
		return null;
	}
}
var previewFlash = function (obj) {
	var reg = /http:\/\/([\w]*[\.]*[\w]+\.[\w]+)\//i;
	var embed = "";
	if (reg.exec(obj.text().toLowerCase()) !== null) {
		var re = getFlashReg(RegExp.$1);
		if (re !== null) {
			if (re.exec(obj.text()) !== null) {
				embed = EMBED_FRAME.replace(/src_id/, RegExp.$1);
				$(embed).appendTo(obj.parent().parent().find(".tweet"));
			}
		}
	}
}
function append_image(src, elem) {
	var img = $('<img style="padding:3px;border:1px solid #ccc;max-width:420px;max-height:420px;" />').attr("src", src);
	var link = $(elem).clone().empty().append(img);
	$(elem).parent().after($('<div class="thumb_pic" style="display:block;margin:5px 0px;" />').append(link));
}
var previewImg = function (obj) {
	if (obj.attr("rel") === "noreferrer") {
		/(https?\:\/\/[\S]*\.(jpg|png|gif))/.exec(obj.attr("href"));
		if(RegExp.$2.length > 0){
			append_image(RegExp.$1, obj);
			return;
		}
		$.embedly(obj.attr("href"), {"success": function (oembed, dict) {
      append_image('img.php?imgurl='+oembed.thumbnail_url, obj);
    }});
	}
}
var previewMedia = function (objs) {
	$(objs).find(".tweet a:not(:hidden), .unshorturl").each(function () {
		if ($.cookie('showpic') === 'true') {
			previewImg($(this));
		}
		if ($.cookie('mediaPre') === 'true') {
			previewFlash($(this));
		}
	});
}
// Check if jQuery's loaded
function GM_wait() {
	if (typeof $ == 'undefined') {
		window.setTimeout(GM_wait, 100);
	}
	else {
		$(document).ready(function () {
			previewMedia($('.timeline'));
		});
	}
}
GM_wait();