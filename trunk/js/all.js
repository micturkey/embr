var INTERVAL_COOKIE = 'updatesInterval';
$(function () {
		formFunc();
		$(".rt_btn").live("click", function (e) {
				e.preventDefault();
				onRT($(this));
			});
		$(".retw_btn").live("click", function (e) {
				e.preventDefault();
				onNwRT($(this));
			});
		$(".replie_btn").live("click", function (e) {
				e.preventDefault();
				onReplie($(this), e);
			});
		$(".favor_btn").live("click", function (e) {
				e.preventDefault();
				onFavor($(this));
			});
		$(".delete_btn").live("click", function (e) {
				e.preventDefault();
				onDelete($(this), "Tweet");
			});
		$(".msg_replie_btn").live("click", function (e) {
				e.preventDefault();
				onReplieDM($(this));
			});
		$(".msg_delete_btn").live("click", function (e) {
				e.preventDefault();
				onDeleteMsg($(this));
			});
		$(".rt_undo").live("click", function (e) {
				e.preventDefault();
				onUndoRt($(this));
			});
		markReply($("#allTimeline > li"));
		$("#submit_btn").click(function (e) {
				updateStatus();
				e.preventDefault();
			});
		document.onclick = function () {
			document.title = document.title.replace(/(\([0-9]+\))/g, "");
			$(".allHighLight").text($(".allHighLight").text().replace(/(\([0-9]+\))/g, ""));
			$("#statuses .new").each(function () {
					$("#statuses .new").remove();
				});
		}
		$("#allReplies").hide();
		$("#allMessage").hide();
		$("#allTimelineBtn").click(function () {
				$("#allTimeline").show();
				$("#allTimeline img").lazyload({threshold : 100, effect : "fadeIn"});
				$("#allReplies").hide();
				$("#allMessage").hide();
				$("#allTimelineBtn").addClass("allHighLight");
				if ($("#allRepliesBtn").hasClass("allHighLight")) $("#allRepliesBtn").removeClass("allHighLight");
				else $("#allMessageBtn").removeClass("allHighLight");
				$("#allTimelineBtn").text($("#allTimelineBtn").text().replace(/(\([0-9]+\))/g, ""));
			})
		$("#allRepliesBtn").click(function () {
				$("#allTimeline").hide();
				$("#allReplies").show();
				$("#allReplies img").lazyload({threshold : 100, effect : "fadeIn"});
				$("#allMessage").hide();
				$("#allRepliesBtn").addClass("allHighLight");
				if ($("#allTimelineBtn").hasClass("allHighLight")) $("#allTimelineBtn").removeClass("allHighLight");
				else $("#allMessageBtn").removeClass("allHighLight");
				$("#allRepliesBtn").text($("#allRepliesBtn").text().replace(/(\([0-9]+\))/g, ""));
			})
		$("#allMessageBtn").click(function () {
				$("#allTimeline").hide();
				$("#allReplies").hide();
				$("#allMessage").show();
				$("#allMessage img").lazyload({threshold : 100, effect : "fadeIn"});
				$("#allMessageBtn").addClass("allHighLight");
				if ($("#allRepliesBtn").hasClass("allHighLight")) $("#allRepliesBtn").removeClass("allHighLight");
				else $("#allTimelineBtn").removeClass("allHighLight");
				$("#allMessageBtn").text($("#allMessageBtn").text().replace(/(\([0-9]+\))/g, ""));
			})
		$(function () {
				setUpdateInterval();
			});
	});
var setUpdateInterval = function () {
	if (!location.href.split("?")[1] || location.href.split("?")[1] == "p=1") {
		var interval = parseFloat($.cookie(INTERVAL_COOKIE));
		if (interval === 0.0) {
			return false;
		}
		interval = interval > 0 ? interval : 3;
		UPDATE_INTERVAL = setInterval(function () {
				update();
			}, interval * 1000 * 60);
	}
};

function update() {
	if (PAUSE_UPDATE === true) {
		window.setTimeout(update, 5000);
	} else if (PAUSE_TIMELINE === true) {
		return 0;
	} else {
		if($.cookie("intervalChanged") === "true"){
			clearInterval(UPDATE_INTERVAL);
			$.cookie("intervalChanged", "")
			setUpdateInterval();
		}
		updateSentTip('Retrieving new tweets...', 5000, 'ing');
		updateFunc("timeline");
		updateFunc("replies");
		updateFunc("message");
	}
}

function updateFunc(type, name, pw) {
	PAUSE_TIMELINE = true;
	var div, url, btnDiv, param;
	switch (type) {
	case "timeline":
		div = "#allTimeline";
		btnDiv = "#allTimelineBtn";
		url = "ajax/updateTimeline.php";
		$(div + " li.mine").removeClass("mine").addClass("myTweet");
		break;
	case "replies":
		div = "#allReplies";
		btnDiv = "#allRepliesBtn";
		url = "ajax/updateReplies.php";
		break;
	case "message":
		div = "#allMessage";
		btnDiv = "#allMessageBtn";
		url = "ajax/updateMessage.php";
		break;
	}
	var since_id = 0;
	if (div === "#allTimeline") {
		since_id = $(div + " li:not(.myTweet):not(#ajax_statuses li):first").find("span.status_id").text();
	} else {
		since_id = $(div + " li:first-child").find("span.status_id").text();
	}
	param = "?since_id=" + since_id;
	$.ajax({
			url: url + param,
			type: "GET",
			dataType: "text",
			data: "since_id=" + since_id,
			success: function (msg) {
				if ($.trim(msg).indexOf("</li>") > 0) {
					var source = $(msg).prependTo(div);
					if (div === "#allTimeline") {
						$(div + " li.myTweet:not(:first)").removeClass("myTweet");
						$("#allTimeline li.myTweet").fadeOut("fast");
						markReply($('#allTimeline > li'));
					}
					var num = 0;
					var navNum = 0;
					if (document.title.match(/\d+/) != null) {
						num = parseInt(document.title.match(/\d+/));
					}
					var len = $(msg).length - 1;
					document.title = "(" + (num + len) + ") " + document.title.replace(/(\([0-9]+\))/g, "");
					if ($(btnDiv).text().match(/\d+/) != null) {
						navNum = parseInt($(btnDiv).text().match(/\d+/));
					}
					$(btnDiv).text($(btnDiv).text().replace(/(\([0-9]+\))/g, "") + "(" + (navNum + len) + ")");
					filterEle();
					embrTweet(source);
					$(".new").slideDown("fast");
					$(".big-retweet-icon").tipsy({
							gravity: 's'
						});
					previewMedia(source);
				}
				PAUSE_TIMELINE = false;
			},
			error: function (msg) {
				PAUSE_TIMELINE = false;
			}
		});
}
