// Global Const
var INTERVAL_COOKIE = 'homeInterval';
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
		onReplie($(this),e);
	});
	$(".favor_btn").live("click", function (e) {
		e.preventDefault();
		onFavor($(this));
	});
	$(".delete_btn").live("click", function (e) {
		e.preventDefault();
		onDelete($(this), "Tweet");
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
		$(".new").each(function () {
			$(".new").remove();
		});
	}
	setUpdateInterval();
});
var setUpdateInterval = function () {
	if (!location.href.split("?")[1] || location.href.split("?")[1] == "p=1") {
		var interval = parseFloat($.cookie(INTERVAL_COOKIE));
		if (interval === 0.0) {
			return false;
		}
		interval = interval > 0 ? interval : 1;
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
		PAUSE_TIMELINE = true;
		updateSentTip('Retrieving new tweets...', 5000, 'ing');
		if ($.cookie("intervalChanged") === "true") {
			clearInterval(UPDATE_INTERVAL);
			$.cookie("intervalChanged", "")
			setUpdateInterval();
		}
		$("#statuses .timeline li.mine").removeClass("mine").addClass("myTweet");
		var since_id = $("#statuses .timeline li:not(.myTweet):not(#ajax_statuses li):first").find(".status_id").text();
		$.ajax({
			url: "ajax/updateTimeline.php",
			type: "GET",
			dataType: "text",
			data: "since_id=" + since_id,
			success: function (msg) {
				if ($.trim(msg).indexOf("</li>") > 0) {
					$("#allTimeline li.myTweet").fadeIn("fast");
					var source = $(msg).prependTo($(".timeline"));
					var num = 0;
					if (document.title.match(/\d+/) != null) {
						num = parseInt(document.title.match(/\d+/));
					}
					document.title = "(" + (num + $(msg).length - 1) + ") " + document.title.replace(/(\([0-9]+\))/g, "");
					markReply($('#allTimeline > li'));
					filterEle();
					rabrTweet(source);
					if($(".new").length == 1) {
						$(".new").css("display","block");
						$(".new").slideDown("fast");
					} else {
						$(".tweetcount").filter(":last").text(num + $(msg).length - 1);
					}
					$(".big-retweet-icon").tipsy({
						gravity: 's'
					});
					previewMedia(source);
				} else {
					updateSentTip('No new tweets', 3000, 'failure');
				}
				PAUSE_TIMELINE = false;
			},
			error: function (msg) {
				PAUSE_TIMELINE = false;
			}
		}); 
	}
}
