var UPDATE_INTERVAL;
var PAUSE_UPDATE = false;
var PAUSE_TIMELINE = false;
function register() {
	window.open("signup/index.php", "registerwindow", "height=450, width=550, toolbar=no, menubar=no, scrollbars=no, resizable=yes, location=yes, status=yes")
};
function leaveWord(num) {
	if(!num){
		num = 140;
	}
	var leave = num-$("#textbox").val().length;
	if ($("#sent_id").val()) {
		leave -= $("#sent_id").val().length + 3;
	}
	if (leave < 0) {
		$("#tip").html("<b>-" + (-leave) + "</b>");
		$("#tip b").css("color","#C00");
		$("#tweeting_button").addClass('btn-disabled');
	} else {
		$("#tip").html("<b>" + leave + "</b>");
		$("#tweeting_button").removeClass('btn-disabled');
		if (leave > 40) { 
			$("#tip b").css("color","#CCC");
		} else if(leave > 20) {
			$("#tip b").css("color","#CAA");
		} else if(leave > 10) {
			$("#tip b").css("color","#C88");
		} else {
			$("#tip b").css("color","#C44");
		}
	}
	if(leave === 140) {
		$("#in_reply_to").val("");
		$("#tweeting_button").addClass('btn-disabled');
	}
}
var formHTML = '<span id="tip"><b>140</b></span><form action="index.php" method="post">';
formHTML += '<textarea name="status" id="textbox"></textarea>';
formHTML += '<input type="hidden" id="in_reply_to" name="in_reply_to" value="0" />';
formHTML += '<div id="tweeting_controls"><a class="a-btn a-btn-m btn-disabled" id="tweeting_button" tabindex="2" href="#" title="Ctrl+Enter also works!"><span>Send</span></a></div></form>';
function rabrTweet(objs) {
	if(typeof objs === 'undefined'){
		var objs = $('#statuses .timeline .source a');
	}else{
		var objs = objs.find('.source a');
	}
	for (i = 0; i < objs.length; i++) {
		if (/embr/i.test($(objs[i]).text())) {
			$(objs[i]).css("color", "#33CCFF");
		}
	}
}
var getConversation = function (obj) {
	var url = $(obj).attr("href");
	var target = $(obj).parent().parent().parent().parent();
	$.ajax({
			url: url,
			type: "GET",
			dataType: "text",
			success: function(msg){
				if ($.trim(msg).indexOf("</li>") > 0) {
					$(msg).appendTo(target);
				}else{
					updateSentTip('Get thread failed.', 5000, 'failure');
				}
				target.removeClass("loading");
			},
			error: function(msg){
				updateSentTip('Get thread failed.', 5000, 'failure');
				target.removeClass("loading");
			}
		});
}
$(function () {
		$(".ajax_reply").live("click", function (e) {
				var obj = $(this).parent().parent().parent().parent();
				var thread = obj.find(".ajax_form");
				if (thread.size() > 0) {
					thread.slideToggle("fast");
				} else {
					obj.addClass("loading");
					getConversation($(this));
				}
				e.preventDefault();
			});
	});
$(function(){
		$("#statuses .trans_btn").live("click", function(){
				var tBody = $(this).parent().parent();
				if(tBody.find(".trans_body").size() !== 0){
					return;
				}
				var id = $.trim(tBody.find('.status_id').text());
				var text = $.trim(tBody.find('.tweet').text());
				var lang = $.cookie('transLang');
				if(lang === null){
					lang = 'zh';
				}
				tBody.parent().addClass('loading');
				translate(text, id, lang, 'transCallback');
			});
		$("#statuses .trans_close").live("click", function(){
				$(this).parent().parent().parent().parent().find(".translated").remove();
			});
		$("#translateMy").live("click", function(){
				var orig = $("#textbox").val();
					ORIG_TEXT = orig;
				var lang = $.cookie('myLangs')
				if(lang === null){
					lang = 'en';
				}
				$('#tip').addClass('loading');
				translate(orig, '', lang, 'transMyCallback');
			});
		$("#transRecover").live("click", function(){
			$("#textbox").val(ORIG_TEXT);
			$("#transRecover").fadeOut('fast');
			});
	});
var translate = function(text, context, lang, callback) {
	var a = "http://www.google.com/uds/Gtranslate";
	a += "?callback=" + callback;
	a += "&context=" + context;
	a += "&q=" + encodeURIComponent(text);
	a += "&key=notsupplied";
	a += "&v=1.0";
	a += "&nocache=1240207680396";
	a += "&langpair=%7C" + lang;
	$.getScript(a);
};
var transMyCallback = function(content, translation){
	if(translation.translatedText !== null){
		$('#tip').removeClass('loading');
		$("#transArea").hide();
		$("#textbox").val(translation.translatedText);
		$("#transRecover").fadeIn('fast');
	}
};
var transCallback = function(content, translation){
	if(translation.translatedText !== null){
		var lang = $.cookie('transLang')
		if(lang === null){
			lang = 'zh';
		}
		var langTxt = $.cookie('fullLang');
		if(langTxt === null){
			langTxt = $('#transArea select[name=langs] option[value=' + lang + ']').text();
		}
		var html = '<div class="translated"><a href="javascript:void(0);" title="Hide Translation" class="trans_close">(Hide)</a><span class="trans_header"><strong>Translation <small>(from ' + translation.detectedSourceLanguage;
		html += ' to ' + langTxt + ')</small> : </strong></span>';
		html += '<span class="trans_body">' + translation.translatedText + '</span></div>';
		var li, target;
		if(typeof INTERVAL_COOKIE !== 'undefined'){
			li = $("#statuses ol:visible li:has(.status_id)").filter(":contains(" + content + ")");
			target = li.find(".status_word:first");
		}else{
			li = $("#statuses li:has(.status_id)").filter(":contains(" + content + ")");
			target = li.find(".status_word:first");
		}
		$(html).appendTo(target);
		li.removeClass("loading");
	}
};
$(window).load(function(){
		var scrollTo = function (top, duration, callback) {
			var w = $(window);
			var FPS = 50;
			var currentTop = w.scrollTop();
			var offset = (currentTop - top) / (duration * FPS / 1000);
			var n = 0;
			var prevTop = currentTop;
			var t = setInterval(function () {
					if ((prevTop - top) * (currentTop - top) <= 0) {
						clearInterval(t);
						currentTop = prevTop = top;
						w.scrollTop(top);
						if (callback) callback();
					} else {
						prevTop = currentTop;
						w.scrollTop(currentTop -= offset);
					}
				}, 1000 / FPS);
		}
		var scrollToTop = function(){
			scrollTo(0, 200, function () {
					scrollTo(30, 50, function () {
							scrollTo(0, 50);
						});
				});
		};
		var scrollToBottom = function(){
			var height = document.body.clientHeight;
			scrollTo(height, 200, function () {
					scrollTo(height + 30, 50, function () {
							scrollTo(height, 50);
						});
				});

		};
		$('body').dblclick(function () {
				scrollToTop();
				$("#textbox").focus();
			});
		$('#content').dblclick(function (e) {
				e.stopPropagation();
			});
		var hkFadeIn = function(text){
			$("#shortcutTip").fadeIn("fast").html(text);
		};
		var hkFadeOut = function(){
			setTimeout(function () {$("#shortcutTip").fadeOut("fast");}, 2000);
		};
		// hotkeys
		var hotkeyHandler = function(code){
			switch(code){
			case 82: // R - refresh
				hkFadeIn("Refresh");
				update();
				hkFadeOut();
				break;
			case 67: // C - focus textbox
			case 85: // U
				hkFadeIn("Compose");
				scrollTo(0, 1, function () {
						$("#textbox").focus();
					});
				hkFadeOut();
				break;
			case 66: // B - scroll to bottom
				hkFadeIn("Boom!");
				scrollToBottom();
				hkFadeOut();
				break;
			case 84: // T - scroll to top
				hkFadeIn("Whiz!");
				scrollToTop();
				hkFadeOut();
				break;
			case 83: // S - search
				hkFadeIn("Search");
				$("#sidepost").animate({backgroundColor: "#FF6347"}, 500, function(){
						$("#header_search_query").focus();
						$("#sidepost").animate({backgroundColor: $("#side_base").css("background-color")}, 1000);
					});
				hkFadeOut();
				break;
			}
		};
		$(document).keydown(function(e){
				var tag = e.target.tagName;
				if(tag === "BODY" || tag === "HTML"){
					if(!e.altKey && !e.shiftKey && !e.ctrlKey && !e.metaKey){
						hotkeyHandler(e.keyCode);
					}
				}
			});
	});
$(document).ready(function () {
		$("<div id='sentTip' style='display:none;' />").prependTo("#header .wrapper");
	});
var updateSentTip = function(message, duration, className){
	var sentTip = $("#sentTip");
	var bgColor = $("body").css("background-color");
	sentTip.html(message).removeClass().addClass(className);
	sentTip.css({
			"border-style": "solid",
			"border-width": "1px",
			"border-color": bgColor
		});
	sentTip.slideDown("fast");
	window.setTimeout(function () {
			sentTip.slideUp('fast');
		}, duration);
	return sentTip;
};
var formFunc = function(){
	leaveWord();
	$("#textbox").keyup(function () {
			leaveWord();
		}).bind("keyup", "keydown", function(event){
				if (event.ctrlKey && event.keyCode == 13) {
					if (PAUSE_UPDATE !== true) {
						updateStatus();
					} else {
						return 0;
					}
				}
			});
	$("#tweeting_button").click(function (e) {
		e.preventDefault();
		if ($("#textbox").val().length >0 ) {
			updateStatus();
		}		
		});		
	};
	//update button core

	var updateStatus = function(){
		$('#tip').addClass('loading');
		$('#tip b').css("opacity","0.1");
		PAUSE_UPDATE = true;

		var text = $("#textbox").val();
				
		if($("#sent_id").val()) {
			var dm = "D " + $("#sent_id").val();
			text = dm + ' ' + text; 
		}
		
		var wordsCount = text.length;
		if (wordsCount > 140) {
			$.cookie('recover', text, {'expire': 30});
			if(window.confirm("Your tweet is longer than 140 words! truncated? (you can restore later using restore button.)")){
				text = text.substr(0,137) + '...' ;
			}
		} 
		if (wordsCount = 0) {
			updateSentTip("Your cannot send an empty tweet!", 3000, "failure");
			return false;
		} else {
			$.ajax({
					url: "ajax/update.php",
					type: "POST",
					data: {
						"status": text,
						"in_reply_to": $("#in_reply_to").val()
					},
					success: function (msg) {
						if ($.trim(msg).indexOf("</li>") > 0) {
							$.cookie('recover', text, {'expire': 30});
							$('#tip').removeClass('loading');
							if ( (text.substring(0,2)).toUpperCase() == "D "){ //exclude the DMs. the exam of user_name is omitted.
								updateSentTip("Your DM has been sent!", 3000, "success");
								$("#textbox").val("");
								$("#sent_id").val("");
								$("#tip").html("<b>140</b>");
								leaveWord();
							} else {
							updateSentTip("Your status has been updated!", 3000, "success");
							$("#textbox").val("");
							$("#tip").html("<b>140</b>");
							leaveWord();
							if(typeof INTERVAL_COOKIE !== "undefined"){
								var source = $(msg).prependTo($("#allTimeline"));
								source.hide().slideDown('fast');
								var statusid = $.trim($(msg).find('.status_id').text());
								var statusText = $.trim($(msg).find('.tweet').html());
								rabrTweet(source);
								$(".mine").slideDown("fast");
								$("#full_status").fadeIn("fast");
								$("#currently .status-text").hide().text(limitation(text)).fadeIn("fast");
								$("#latest_meta").hide().html("<a target=\"_blank\" href=\"status.php?id=" + statusid + "\">less than 5 seconds ago</a>").fadeIn("fast");
								$("#currently .full-text").hide().html(statusText);
								$("#full_meta").hide().html("<a target=\"_blank\" href=\"status.php?id=" + statusid + "\">less than 5 seconds ago</a>");
								$("#full_meta a, .full-text a").click(function (e) {e.stopPropagation();});
								previewMedia(source);
								freshProfile();
							}
							}
						} else {
							$.cookie('recover', text, {'expire': 30});
							$('#tip').removeClass('loading');
							updateSentTip("Update failed. Please try again.", 3000, "failure");
							$('#tweeting_button').removeClass('btn-disabled');
						}
						PAUSE_UPDATE = false;
					},
					error: function (msg) {
						$.cookie('recover', text, {'expire': 30});
						$('#tip').removeClass('loading');
						updateSentTip("Update failed. Please try again.", 3000, "failure");
						$('#tweeting_button').removeClass('btn-disabled');
						PAUSE_UPDATE = false;
						leaveWord();
					}
				});
		}
		
		
	};
	function onFavor($this) {
		var status_id = $.trim($this.parent().parent().find(".status_id").text());
		updateSentTip("Adding this tweet to your favorites...", 5000, "ing");
		$.ajax({
				url: "ajax/addfavor.php",
				type: "POST",
				data: "status_id=" + status_id,
				success: function (msg) {
					if (msg.indexOf("success") >= 0) {
						updateSentTip("Favorite added successfully.", 3000, "success");
						$this.parent().parent().parent().addClass('faved');
					} else {
						updateSentTip("Add failed. Please try again.", 3000, "failure");
					}
				},
				error: function (msg) {
					updateSentTip("Add failed. Please try again.", 3000, "failure");
				}
			});
	}
	function onReplie($this, e) {
		var replie_id = $this.parent().parent().find(".status_word").find(".user_name").text();
		var in_reply_id = $this.parent().parent().find(".status_id").text();
		var text = "@" + replie_id;
		var mode = "In reply to ";
		if (e.ctrlKey) {
			mode = "Reply to all: ";
			var temp = {
				text: true
			};
			var mentionArray = [text];
			var mentions = $this.parent().parent().find('a[href^="user.php"][innerHTML^=@]');
			$.each(mentions, function () {
					var t = $(this).text();
					if (!(t in temp)) {
						temp[t] = true;
						mentionArray.push(t);
					}
					text = mentionArray.join(' ');
				});
		}
		if (e.altKey) {
			mode = "Non-conversational reply to ";
			in_reply_id = "";
		}
		scroll(0, 0);
		$("#textbox").focus();
		$("#textbox").val($("#textbox").val() + text + ' ');
		$("#in_reply_to").val(in_reply_id);
		$("#full_status").hide();
		$("#latest_meta").html("").hide();
		$("#full_meta").hide();
		$("#currently .full-text").hide();
		$("#latest_meta").hide();
		$("#currently .status-text").html(mode + text);
		leaveWord();
	}
	function onRT($this) {
		var replie_id = $this.parent().parent().find(".status_word").find(".user_name").text();
		scroll(0, 0);
		$("#textbox").focus();
		$("#textbox").val("RT @" + replie_id + ":" + $this.parent().parent().find(".status_word").text().replace(replie_id, ""));
		$("#full_status").hide();
		$("#latest_meta").hide();
		$("#full_meta").hide();
		$("#currently .full-text").hide();
		$("#latest_meta").hide();
		$("#currently .status-text").html("Retweet @" + replie_id + "'s tweet with comment.");
		leaveWord();
	}
	function onReplieDM($this, e) {
		var replie_id = $this.parent().parent().find(".status_word").find(".user_name").text();
		var text = "D " + replie_id;
		scroll(0, 0);
		$("#textbox").focus();
		$("#textbox").val($("#textbox").val() + text + ' ');
		$("#in_reply_to").val($this.parent().parent().find(".status_id").text());
		$("#full_status").hide();
		$("#latest_meta").hide();
		$("#full_meta").hide();
		$("#currently .full-text").hide();
		$("#latest_meta").hide();
		$("#currently .status-text").html("Reply direct message to @" + replie_id);
		leaveWord();
	}
	function onNwRT($this) {
		var statusBody = $this.parent().parent();
		var status_id = statusBody.find(".status_id").text();
		var div = "#" + statusBody.parent().parent().attr('id');
		var btnDiv = div + "Btn";
		if (confirm("Are you sure to retweet this?")) {
			updateSentTip("Retweeting tweet...", 5000, "ing");
			$.ajax({
					url: "ajax/retweet.php",
					type: "post",
					data: "status_id=" + status_id,
					success: function (msg) {
						if (msg.length >= 0) {
							statusBody.parent().addClass("retweet");
							statusBody.find(".source").hide();
							statusBody.find(".status_info").append("<span class=\"rt_source\">Retweeted by you from <a rel=\"nofollow\" href=\"http://code.google.com/p/embr/\">embr</a></span>").fadeIn("fast");
							statusBody.find(".date").hide();
							statusBody.find(".status_info").append("<span class=\"rt_undo\" title=\"Your followers will no longer see the tweet as retweeted by you.\">&nbsp;<a href=\"javascript:void(0);\">(Undo)</a><span class=\"rt_id\" style=\"display: none;\">" + msg + "</span></span>").fadeIn("fast");
							updateSentTip("This tweet has been retweeted!", 3000, "success");
							$(".rt_undo").tipsy({
									gravity: 's'
								});
						} else {
							if (msg === "duplicated") {
								updateSentTip("You have retweeted this tweet!", 3000, "failure");
							} else {
								updateSentTip("Failed to retweet!", 3000, "failure");
							}
						}
					},
					error: function (msg) {
						updateSentTip("Retweet failed. Please try again.", 3000, "failure");
					}
				});
		}
	}
	function onDelete($this, type) {
		var status_id = $.trim($this.parent().parent().find(".status_id").text());
		$this.parent().parent().parent().css("background-color", "#FF3300");
		var confirm = window.confirm("Are you sure to delete this tweet?");
		if (confirm) {
			updateSentTip("Deleting tweet...", 5000, "ing");
			var postData = (type == "Tweet") ? "status_id=" : "favor_id=";
			$.ajax({
					url: "ajax/delete.php",
					type: "POST",
					data: postData + status_id,
					success: function (msg) {
						if (msg.indexOf("success") >= 0) {
							$this.parent().parent().parent().css("background-color", "#FF3300");
							$this.parent().parent().parent().fadeOut("fast");
							updateSentTip("Your tweet has been destroyed!", 3000, "success");
						} else {
							$this.parent().parent().parent().css("background-color", "");
							updateSentTip("Delete failed. Please try again.", 3000, "failure");
						}
					},
					error: function (msg) {
						$this.parent().parent().parent().css("background-color", "");
						updateSentTip("Delete failed. Please try again.", 3000, "failure");
					}
				});
		}
		$this.parent().parent().parent().css("background-color", "");
	}
	function onUndoRt($this) {
		var status_id = $.trim($this.parent().find(".rt_id").text());
		var statusBody = $this.parent().parent().parent();
		statusBody.css("background-color", "#FF3300");
		var confirm = window.confirm("Are you sure to undo this retweet?");
		if (confirm) {
			updateSentTip("Undoing retweet...", 5000, "ing");
			$.ajax({
					url: "ajax/delete.php",
					type: "POST",
					data: "status_id=" + status_id,
					success: function (msg) {
						if (msg.indexOf("success") >= 0) {
							statusBody.css("background-color", "#FF3300");
							statusInfo = $this.parent().parent();
							if (statusInfo.find(".rt_source").size() === 1) {
								statusInfo.find(".source").show();
								statusInfo.find(".date").show();
								statusInfo.find(".rt_source").remove();
								statusInfo.find(".rt_undo").remove();
								statusBody.removeClass("retweet");
								statusBody.css("background-color", "");
							} else {
								statusBody.fadeOut("fast");
							}
							updateSentTip("Your retweet has been undo!", 3000, "success");
						} else {
							statusBody.css("background-color", "");
							updateSentTip("Undo failed. Please try again.", 3000, "failure");
						}
					},
					error: function (msg) {
						statusBody.css("background-color", "");
						updateSentTip("Undo failed. Please try again.", 3000, "failure");
					}
				});
		}
		statusBody.css("background-color", "");
	}
	function onDeleteMsg($this) {
		var message_id = $.trim($this.parent().parent().find(".status_id").text());
		$this.parent().parent().parent().css("background-color", "#FF3300");
		var confirm = window.confirm("Are you sure to delete this message?");
		if (confirm) {
			updateSentTip("Deleting message...", 5000, "ing");
			$.ajax({
					url: "ajax/delete.php",
					type: "POST",
					data: "message_id=" + message_id,
					success: function (msg) {
						if (msg.indexOf("success") >= 0) {
							$this.parent().parent().parent().css("background-color", "#FF3300");
							$this.parent().parent().parent().fadeOut("fast");
							updateSentTip("Message deleted.", 3000, "success");
						} else {
							$this.parent().parent().parent().css("background-color", "");
							updateSentTip("Failed to delete this message!", 3000, "failure");
						}
					},
					error: function (msg) {
						$this.parent().parent().parent().css("background-color", "");
						updateSentTip("Failed to delete this message!", 3000, "failure");
					}
				});
		}
		$this.parent().parent().parent().css("background-color", "");
	}
	function shortUrlDisplay() {
		var stringVar = "";
		stringVar = document.getElementById("textbox").value;
		if (stringVar.length === 0) {
			updateSentTip("There's no URL in your tweet to shorten!", 3000, "failure");
		} else {
			var str = '';
			var regexp = /http(s)?:\/\/([\w\-]+\.)+[\w\-]+(\/[\w\-\.\/?\%\!\&=\+\~\:\#\;\,]*)?/ig;
			var l_urls = '';
			str = stringVar.match(regexp);
			if (str !== null) {
				unshorten = 0;
				for (idx = 0; idx < str.length; idx++) {
					regexp2 = /(http:\/\/j.mp\/[\S]+)|(http:\/\/bit.ly\/[\S]+)|(http:\/\/zi.mu\/[\S]+)|(http:\/\/orz.se\/[\S]+)/gi;
					if (!str[idx].match(regexp2)) {
						l_urls += str[idx] + "|";
					}
					else {
						unshorten++;
					}
				}
				if (unshorten) {
					updateSentTip(unshorten + " URL(s) are maintained!", 3000, "failure");
				}
				if (l_urls != "") {
					$('#tip').addClass('loading');
					$.post("ajax/shorturl.php", {
							long_urls: l_urls
						}, function (data) {
							getShortUrl(data);
						});
				}
			}
		}
	}
	function getShortUrl(res) {
		var retstr = res;
		target_layer = 'textbox';
		var url_arry, s_url, l_url, part;
		var err_cnt = 0;
		url_arry = retstr.split('^');
		for (i = 0; i < url_arry.length; i++) {
			part = url_arry[i].split('|');
			if (part.length == 2) {
				s_url = part[0];
				l_url = part[1];
			}
			if (s_url) {
				stringVar = document.getElementById(target_layer).value;
				stringVar = stringVar.replace(l_url, s_url);
				document.getElementById(target_layer).value = stringVar + "";
				leaveWord();
				$('#tip').removeClass('loading');
				updateSentTip("Successfully shortened your URLs!", 3000, "success");
			}	else {
				err_cnt++;
			}
		}
		if (err_cnt > 0) {
			updateSentTip("Failed to shorten URLs, please try again.", 3000, "failure");
		}
	}

	function shortenTweet() {
		var tweet = $.trim($("#textbox").val());
		if (tweet.length === 0) {
			updateSentTip("There's nothing to shorten!", 3000, "failure");
		} else {
			$('#tip').addClass('loading');
			$.ajax({
					url: "ajax/shortenTweet.php",
					type: "POST",
					data: "text=" + tweet,
					success: function(msg){
						if(msg !== 'error'){
							$("#textbox").val(msg);
							leaveWord();
							$('#tip').removeClass('loading');
							updateSentTip("Your tweet has been shortened!", 5000, "success");
						}else{
							updateSentTip("Failed to shorten your tweet.", 5000, "failure");
						}
					},
					error: function(msg){
						updateSentTip("Failed to shorten your tweet.", 5000, "failure");
					}
				});
			/* JSON FAILS!
			 $.getJSON("http://tweetshrink.com/shrink?text=" + tweet, function(data){
			 $("#textbox").val(data.text);
			 leaveWord();
			 updateSentTip("Reduced " + data.difference + " letters!" , 5000, "success");
		 });
		 */
		}
	}
	$(function () {
			$("#latest_status").toggle(
				function () {
					$("#currently .status-text, #latest_meta").css("display", "none");
					$("#currently .full-text, #full_meta").css("display", "inline");
				}, function () {
					$("#currently .status-text, #latest_meta").css("display", "inline");
					$("#currently .full-text, #full_meta").css("display", "none");
				});
			$("#full_meta a, .full-text a").click(function (e) {
					e.stopPropagation();
				});
		});
	var limitation = function (text) {
		if (text.length > 60) {
			text = text.substr(0, 60) + " ...";
		}
		return text;
	};
	$(function () {
			if (document.location.href.indexOf("index") > 0 || document.location.href.indexOf("all") > 0) {
				var temp = $("#currently .status-text").text();
				if (temp.length > 60) {
					temp = temp.substr(0, 60) + " ...";
				}
				$("#currently .status-text").text(temp);
			}
		});
	
	function updateTrends() {
		$.ajax({
				url: "ajax/updateTrends.php",
				type: "GET",
				success: function (msg) {
					if ($.trim(msg).indexOf("</ul>" > 0)) {
						$("#trend_entries").html(msg);
					}
					$("#trends_title").removeClass().addClass("open");
					$("#trend_entries").slideDown("fast");
				}
			});
	}
	$(function () {
			$("#trends_title").toggle(
				function () {
					$("#trends_title").removeClass().addClass("loading");
					updateTrends();
				}, function () {
					$("#trend_entries").slideUp("fast");
					$("#trends_title").removeClass();
				});
		});
	function updateFollowing() {
		$.ajax({
				url: "ajax/updateFollowing.php",
				type: "GET",
				success: function (msg) {
					if ($.trim(msg).indexOf("</span>" > 0)) {
						$("#following_list").html(msg);
					}
					$("#following_title").removeClass().addClass("open");
					$("#following_list").slideDown("fast");
				}
			});
	}
	$(function () {
			$("#following_title").toggle(
				function () {
					$("#following_title").removeClass().addClass("loading");
					updateFollowing();
				}, function () {
					$("#following_list").slideUp("fast");
					$("#following_title").removeClass();
				});
		});
	function updateAPIQuota() {
		$.ajax({
			url: "ajax/apiQuota.php",
			type: "GET",
			success: function (msg) {
				$("#apiquota_list").html(msg);
			}
		});
	}
	
	$(function () {
			$("#apiquota_title").toggle(
				function () {
					$("#apiquota_title").removeClass().addClass("loading");
					updateAPIQuota();
					$("#apiquota_title").removeClass().addClass("open");
					$("#apiquota_list").slideDown("fast");
				}, function () {
					$("#apiquota_list").slideUp("fast");
					$("#apiquota_title").removeClass();
				});
		});
	$(function () {
			$(".status_author img, .rank_img img").live("click", function (e) {
					$(".right_menu").hide();
					$(this).parent().parent().find(".right_menu").css("display", "block");
					e.preventDefault();
				});
			$('body').click(function () {
					$(".right_menu").hide();
				});
			$('.status_author li a').click(function () {
					$(".right_menu").hide();
				});
			$(".rm_mention").live("click", function (e) {
					e.preventDefault();
					rmmention($(this), e);
				});
			$(".rm_dm").live("click", function (e) {
					e.preventDefault();
					rmdm($(this), e);
				});
			$(".rm_follow").live("click", function (e) {
					e.preventDefault();
					var $this = $(this);
					var id = $this.parent().parent().parent().find(".status_word").find(".user_name").text();
					updateSentTip("Following " + id + "...", 5000, "ing");
					$.ajax({
							url: "ajax/relation.php",
							type: "POST",
							data: "action=create&id=" + id,
							success: function (msg) {
								if (msg.indexOf("success") >= 0) {
									updateSentTip("You have followed " + id + "!", 3000, "success");
								} else {
									updateSentTip("Failed to follow " + id + ", please try again.", 3000, "failure");
								}
							},
							error: function (msg) {
								updateSentTip("Failed to follow " + id + ", please try again.", 3000, "failure");
							}
						});
				});
			$(".rm_unfollow").live("click", function (e) {
					e.preventDefault();
					var $this = $(this);
					var id = $this.parent().parent().parent().find(".status_word").find(".user_name").text();
					if (confirm("Are you sure to unfollow " + id + " ?")) {
						updateSentTip("Unfollowing " + id + "...", 5000, "ing");
						$.ajax({
								url: "ajax/relation.php",
								type: "POST",
								data: "action=destory&id=" + id,
								success: function (msg) {
									if (msg.indexOf("success") >= 0) {
										updateSentTip("You have unfollowed " + id + "!", 3000, "success");
									} else {
										updateSentTip("Failed to unfollow " + id + ", please try again.", 3000, "failure");
									}
								},
								error: function (msg) {
									updateSentTip("Failed to unfollow " + id + ", please try again.", 3000, "failure");
								}
							});
					}
				});
			$(".rm_block").live("click", function (e) {
					e.preventDefault();
					var $this = $(this);
					var id = $this.parent().parent().parent().find(".status_word").find(".user_name").text();
					if (confirm("Are you sure to block " + id + " ?")) {
						updateSentTip("Blocking " + id + "...", 5000, "ing");
						$.ajax({
								url: "ajax/relation.php",
								type: "POST",
								data: "action=block&id=" + id,
								success: function (msg) {
									if (msg.indexOf("success") >= 0) {
										updateSentTip("You have blocked " + id + "!", 3000, "success");
									} else {
										updateSentTip("Failed to block " + id + ", please try again.", 3000, "failure");
									}
								},
								error: function (msg) {
									updateSentTip("Failed to block " + id + ", please try again.", 3000, "failure");
								}
							});
					}
				});
		})
	$(".rm_spam").live("click", function (e) {
			e.preventDefault();
			var $this = $(this);
			var id = $this.parent().parent().parent().find(".status_word").find(".user_name").text();
			if (confirm("Are you sure to report " + id + " ?")) {
				updateSentTip("Reporting " + id + " as a spammer...", 5000, "ing");
				$.ajax({
						url: "ajax/reportSpam.php",
						type: "POST",
						data: "spammer=" + id,
						success: function (msg) {
							if (msg.indexOf("success") >= 0) {
								updateSentTip("Successfully reported!", 3000, "success");
							} else {
								updateSentTip("Failed to report " + id + ", please try again.", 3000, "failure");
							}
						},
						error: function (msg) {
							updateSentTip("Failed to report " + id + ", please try again.", 3000, "failure");
						}
					});
			}
		});
	function rmmention($this, e) {
		var replie_id = $this.parent().parent().parent().find(".status_word").find(".user_name").text();
		var in_reply_id = $this.parent().parent().parent().find(".status_id").text();
		var text = "@" + replie_id;
		var mode = "In reply to ";
		scroll(0, 0);
		$("#textbox").focus();
		$("#textbox").val($("#textbox").val() + text + ' ');
		$("#in_reply_to").val(in_reply_id);
		$("#full_status").hide();
		$("#latest_meta").html("").hide();
		$("#full_meta").hide();
		$("#currently .full-text").hide();
		$("#latest_meta").hide();
		$("#currently .status-text").html(mode + text);
		leaveWord();
	}
	function rmdm($this, e) {
		var replie_id = $this.parent().parent().parent().find(".status_word").find(".user_name").text();
		var text = "D " + replie_id;
		scroll(0, 0);
		$("#textbox").focus();
		$("#textbox").val($("#textbox").val() + text + ' ');
		$("#in_reply_to").val($this.parent().parent().parent().find(".status_id").text());
		$("#full_status").hide();
		$("#latest_meta").hide();
		$("#full_meta").hide();
		$("#currently .full-text").hide();
		$("#latest_meta").hide();
		$("#currently .status-text").html("Reply direct message to @" + replie_id);
		leaveWord();
	}
	function sidebarFollow(id) {
		updateSentTip("Following " + id + "...", 5000, "ing");
		$.ajax({
				url: "ajax/relation.php",
				type: "POST",
				data: "action=create&id=" + id,
				success: function (msg) {
					if (msg.indexOf("success") >= 0) {
						updateSentTip("You have followed " + id + "!", 3000, "success");
						$.cookie('followus', 1, {
								expire: 30
							});
						$.cookie('whofollowedus', $("#sideid").html(), {
								expire: 30
							});
						$("#follow_us").fadeOut("fast");
					} else {
						updateSentTip("Failed to follow " + id + ", please try again.", 3000, "failure");
					}
				},
				error: function (msg) {
					updateSentTip("Failed to follow " + id + ", please try again.", 3000, "failure");
				}
			});
	};
	
	//sidebar functions
	
	$(function () {
			$("#sidebarTip").toggle(
				function () {
					$('#sidebarTip_more').slideDown('fast');
					$('#indicator').html('[-]');
				}, function () {
					$('#sidebarTip_more').slideUp('fast');
					$('#indicator').html('[+]');
				});
			$("#profileRefresh").click(function(e) {
				e.preventDefault();
				var that = $(this);
				if (!that.hasClass('refreshing')) {
					that.addClass('refreshing').html("<img src=\"img/ajax.gif\" />");
					$.ajax({
						url: "ajax/updateProfile.php",
						type: "GET",
						dataType: "json",
						success: function(msg) {
							if (msg.statuses >= 0) { 
								$(".count").eq(0).text(msg.friends).end()
									.eq(1).text(msg.followers).end()
									.eq(2).text(msg.listed);
								$("#update_count").text(msg.statuses);
								$("#side_name").text(msg.name);
								$("[herf='profile.php']:first").html("<img id=\"sideimg\" src=\" " + msg.imgurl + "\" />");
								updateSentTip("Profile updated successfully!", 3000, "success");
							}
							else {
								updateSentTip("Failed to update your profile!", 3000, "failure");
							}
							that.removeClass('refreshing').html("<img src=\"img/refresh.png\" />");
						},
						error: function (msg) {
							updateSentTip("Failed to update your profile!", 3000, "failure");
						},
						complete: function() {
							that.removeClass('refreshing').html("<img src=\"img/refresh.png\" />");
						}
					});
				}
			});
		});

	//init global functions
	$(document).ready(function () {
			rabrTweet();
			$("#statuses .mine").live("mouseout", function (e) {
					$(this).removeClass("mine").addClass("myTweet");
				});
			$("#primary_nav li a").bind("click", function () {
					$("#primary_nav li a").each(function (i, o) {
							if ($(this).hasClass("active")) {
								$(this).removeClass()
							}
						});
					$(this).removeClass().addClass("active").css("background", "transparent url('../img/spinner.gif') no-repeat scroll 173px center")
				});
			$("#statuses .big-retweet-icon, #func_set .func_btn, #profileRefresh").tipsy({
					gravity: 's'
				});
			$('#symbols span').tipsy({
					gravity: $.fn.tipsy.autoNS
				});
			if ($.cookie('followus') == 1 && $.cookie('whofollowedus') == $("#sideid").html()) {
				$("#follow_us").hide();
			}
			$(".timeline img").lazyload({threshold : 100, effect : "fadeIn"});
		});
	var freshProfile = function(){
		$('#update_count').text($.cookie('statuses_count'));
		$('#user_stats a[href*=friends] span.count').text(parseInt($.cookie('friends_count')));
		$('#user_stats a[href*=followers] span.count').text(parseInt($.cookie('followers_count')));
		$('#user_stats a[href*=lists] span.count').text(parseInt($.cookie('listed_count')));
	};
	var markReply = function(obj){
		obj.each(function (i, o) {
				if ($(this).find("> span").find('.tweet').text().toLowerCase().indexOf("@" + $("#sideid").text().toLowerCase()) > -1) {
					$(this).addClass("reply");
				}
			});
	};