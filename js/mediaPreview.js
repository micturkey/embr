// Flash preview
TUDOU_EMBED = '<br /><embed src="http://www.tudou.com/v/src_id" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" wmode="opaque" width="420" height="363"></embed>';
XIAMI_EMBED = '<br /><embed src="http://www.xiami.com/widget/0_src_id/singlePlayer.swf" type="application/x-shockwave-flash" width="257" height="33" wmode="transparent"></embed>';
YOUKU_EMBED = '<br /><embed src="http://player.youku.com/player.php/sid/src_id/v.swf" quality="high" width="420" height="363" align="middle" allowScriptAccess="allways" mode="transparent" type="application/x-shockwave-flash"></embed>';
YOUTUBE_EMBED = '<br /><embed src="http://www.youtube.com/e/src_id?enablejsapi=1&version=3&playerapiid=ytplayer" quality="high" width="420" height="363" align="middle" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true"></embed>';
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

function get_img_processor(type) {

	if (type.indexOf('phodroid.com') == 0) { //ugly fix for bloody Phodroid (which's the worst image hosting service ON EARTH) by @luosheng
		return {
		reg: /^http:\/\/(?:www\.)?phodroid\.com\/([\d\w\/]+)/,
		func: function (url_key, url_elem) {
		var src = "http://phodroid.s3.amazonaws.com/" + url_key[1] + ".jpg";
		append_image(src, url_elem);
		}
		};
	}
	
	if (type.indexOf('instagr.am') == 0) { 
		return {
			reg: /^http:\/\/(?:www\.)?instagr\.am\/([\d\w\/]+)/,
			func: function (url_key, url_elem) {
				var src = "http://instagr.am/" + url_key[1] + "media/?size=t";
				append_image(src, url_elem);
			}
		};
	}

	switch (type) {
	case "twitgoo.com":
		proc = {
			reg: /^http:\/\/(?:www\.)?twitgoo\.com\/([\d\w]+)/,
			func: function (url_key, url_elem) {
				var src = "img.php?imgurl=http://twitgoo.com/show/thumb/" + url_key[1];
				append_image(src, url_elem);
			}
		};
		return proc;
	case "yfrog.com":
		proc = {
			reg: /^http:\/\/(?:www\.)?yfrog\.com\/([\d\w]+)/,
			func: function (url_key, url_elem) {
				var src = url_key[0] + ".th.jpg";
				append_image(src, url_elem);
			}
		};
		return proc;
	case "twitpic.com":
		proc = {
			reg: /^http:\/\/(?:www\.)?twitpic\.com\/([\d\w]+)/,
			func: function (url_key, url_elem) {
				var src = "img.php?imgurl=http://twitpic.com/show/thumb/" + url_key[1];
				append_image(src, url_elem);
			}
		};
		return proc;
	case "img.ly":
		proc = {
			reg: /^http:\/\/(?:www\.)?img\.ly\/([\d\w]+)/,
			func: function (url_key, url_elem) {
				var src = "img.php?imgurl=http://img.ly/show/thumb/" + url_key[1];
				append_image(src, url_elem);
			}
		};
		return proc;
	case "ow.ly/i":
		proc = {
			reg: /^http:\/\/(?:www\.)?ow\.ly\/i\/([\d\w]+)/,
			func: function (url_key, url_elem) {
				var src = "http://static.ow.ly/photos/thumb/" + url_key[1] + ".jpg";
				append_image(src, url_elem);
			}
		};
		return proc;
	case "pic.gd":
		proc = {
			reg: /^http:\/\/(?:www\.)?pic\.gd\/([\d\w]+)/,
			func: function (url_key, url_elem) {
				var src = "http://api.plixi.com/api/TPAPI.svc/imagefromurl?size=thumbnail&url=" + url_key[0];
				append_image(src, url_elem);
			}
		};
		return proc;
	case "tweetphoto.com":
		proc = {
			reg: /^http:\/\/(?:www\.)?tweetphoto\.com\/([\d\w]+)/,
			func: function (url_key, url_elem) {
				var src = "http://api.plixi.com/api/TPAPI.svc/imagefromurl?size=thumbnail&url=" + url_key[0];
				append_image(src, url_elem);
			}
		};
		return proc;
	case "plixi.com/p":
		proc = {
			reg: /^http:\/\/(?:www\.)?plixi\.com\/p\/([\d\w]+)/,
			func: function (url_key, url_elem) {
				var src = "http://api.plixi.com/api/tpapi.svc/imagefromurl?size=thumbnail&url=http://plixi.com/p/" + url_key[1];
				append_image(src, url_elem);
			}
		};
		return proc;
	case "ts1.in":
		proc = {
			reg: /^http:\/\/(?:www\.)?ts1\.in\/(\d+)/,
			func: function (url_key, url_elem) {
				var src = "http://ts1.in/thumb/" + url_key[1];
				append_image(src, url_elem);
			}
		};
		return proc;
	case "hellotxt.com":
		proc = {
			reg: /^http:\/\/(?:www\.)?hellotxt.com\/i\/([\d\w]+)/,
			func: function (url_key, url_elem) {
				var src = "http://hellotxt.com/image/" + url_key[1] + ".s.jpg"
				append_image(src, url_elem);
			}
		};
		return proc;
	case "twitxr.com":
		proc = {
			reg: /^http:\/\/(?:www\.)?twitxr.com\/[^ ]+\/updates\/([\d]+)/,
			func: function (url_key, url_elem) {
				var src = 'http://twitxr.com/thumbnails/' + url_key[1].substr(-2, 2) + '/' + url_key[1] + '_th.jpg';
				append_image(src, url_elem);
			}
		};
		return proc;
	case "moby.to":
		proc = {
			reg: /^(http:\/\/(?:www\.)?moby\.to\/[A-Za-z0-9]+)/,
			func: function (url_key, url_elem) {
				var src = "http://api.mobypicture.com?s=small&format=plain&k=OozRuDDauQlucrZ3&t=" + url_key[1];
				append_image(src, url_elem);
			}
		};
		return proc;
	case "grab.by":
		proc = {
			reg: /^(http:\/\/(?:www\.)?grab\.by\/[A-Za-z0-9]+)/,
			func: function (url_key, url_elem) {
				var src = url_key[1];
				append_image(src, url_elem);
			}
		};
		return proc;
	case "flic.kr/p": 
		proc = {
			reg: /^http:\/\/(?:www\.)?flic\.kr\/p\/([A-Za-z0-9]+)/,
			func: function (url_key, url_elem) {
				function base58_decode(snipcode) {
					var alphabet = '123456789abcdefghijkmnopqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ';
					var num = snipcode.length;
					var decoded = 0;
					var multi = 1;
					for (var i = (num - 1); i >= 0; i--) {
						decoded = decoded + multi * alphabet.indexOf(snipcode[i]);
						multi = multi * alphabet.length;
					}
					return decoded;
				}
				var id = base58_decode(url_key[1]);
				var apiKey = '4ef2fe2affcdd6e13218f5ddd0e2500d';
				var url = "http://api.flickr.com/services/rest/?method=flickr.photos.getInfo&api_key=" + apiKey + "&photo_id=" + id;
				$.getJSON(url + "&format=json&jsoncallback=?", function (data) {
					if (data.stat == "ok") {
						var imgsrc = "img.php?imgurl=http://farm" + data.photo.farm + ".static.flickr.com/" + data.photo.server + "/" + data.photo.id + "_" + data.photo.secret + "_m.jpg";
						append_image(imgsrc, url_elem);
					}
				});
			}
		};
		return proc;
	default:
		return null;
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
		/http[s]?:\/\/(?:www\.)?([\S]+)\/[\S]*/i.exec(obj.attr("href"));
		var img_processor = get_img_processor(RegExp.$1);
		if (img_processor === null) {
			return null;
		}
		if ((img_url_key = img_processor.reg.exec(obj.attr("href"))) != null) {
			obj.attr("alt", "image");
			img_processor.func(img_url_key, obj);
		}
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