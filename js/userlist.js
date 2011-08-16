$(function () {
	$(".rank_img img").tipsy({gravity: 's'});
	$(".rank_img img").live("click", function (e) {
		$(".right_menu").show();
		$(this).parent().parent().parent().find(".right_menu").css("display", "block");
		e.preventDefault();
	});
	$('body').click(function () {
		$(".right_menu").hide();
	});
	$('.status_author li a').live("click",function () {
		$(".right_menu").hide();
	});
	$(".ul_mention").live("click", function (e) {
		e.preventDefault();
		ulmention($(this), e);
	});
	$(".ul_dm").live("click", function (e) {
		e.preventDefault();
		uldm($(this), e);
	});
	$(".ul_follow").live("click", function (e) {
		e.preventDefault();
		var $this = $(this);
		var id = $this.parent().parent().parent().find(".rank_screenname").text();
		updateSentTip("Following " + id + "...", 5000, "ing");
		$.ajax({
			url: "ajax/relation.php",
			type: "POST",
			data: "action=create&id=" + id,
			success: function (msg) {
				if (msg.indexOf("success") >= 0) {
					$this.parent().parent().parent().addClass("reply");
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
	$(".ul_unfollow").live("click", function (e) {
		e.preventDefault();
		var $this = $(this);
		var id = $this.parent().parent().parent().find(".rank_screenname").text();
		if (confirm("Are you sure to unfollow " + id + " ?")) {
			updateSentTip("Unfollowing " + id + "...", 5000, "ing");
			$.ajax({
				url: "ajax/relation.php",
				type: "POST",
				data: "action=destory&id=" + id,
				success: function (msg) {
					if (msg.indexOf("success") >= 0) {
						$this.parent().parent().parent().addClass("filter");
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
	$(".ul_block").live("click", function (e) {
		e.preventDefault();
		var $this = $(this);
		var id = $this.parent().parent().parent().find(".rank_screenname").text();
		if (confirm("Are you sure to block " + id + " ?")) {
			updateSentTip("Blocking " + id + "...", 5000, "ing");
			$.ajax({
				url: "ajax/relation.php",
				type: "POST",
				data: "action=block&id=" + id,
				success: function (msg) {
					if (msg.indexOf("success") >= 0) {
						$this.parent().parent().parent().fadeOut("normal");
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
$(".ul_spam").live("click", function (e) {
	e.preventDefault();
	var $this = $(this);
	var id = $this.parent().parent().parent().find(".rank_screenname").text();
	if (confirm("Are you sure to report " + id + " ?")) {
		updateSentTip("Reporting " + id + " as a spammer...", 5000, "ing");
		$.ajax({
			url: "ajax/reportSpam.php",
			type: "POST",
			data: "spammer=" + id,
			success: function (msg) {
				if (msg.indexOf("success") >= 0) {
					$this.parent().parent().parent().fadeOut("normal");
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
function ulmention($this, e) {
	var replie_id = $this.parent().parent().parent().find(".rank_screenname").text();
	if ($("#textbox").length > 0) {
			var text = "@" + replie_id;
			scroll(0, 0);
			$("#textbox").focus();
			$("#textbox").val($("#textbox").val() + text + ' ');
			leaveWord();
		} else {
			$("#statuses h2").before('<h2>Mention</h2>' + formHTML);
			formFunc();
			var text = "@" + replie_id;
			scroll(0, 0);
			$("#textbox").focus();
			$("#textbox").val($("#textbox").val() + text + ' ');
			leaveWord();
		}
}
function uldm($this, e) {
	var replie_id = $this.parent().parent().parent().find(".rank_screenname").text();
	if ($("#textbox").length > 0) {
			var text = "D " + replie_id;
			scroll(0, 0);
			$("#textbox").focus();
			$("#textbox").val($("#textbox").val() + text + ' ');
			leaveWord();
		} else {
			$("#statuses h2").before('<h2>Send direct message</h2>' + formHTML);
			formFunc();
			var text = "D " + replie_id;
			scroll(0, 0);
			$("#textbox").focus();
			$("#textbox").val($("#textbox").val() + text + ' ');
			leaveWord();
		}
}
$(document).ready(function(){
	$.ajax({
		url: '../ajax/updateProfile.php',
		type: "GET",
		success: function(){
			freshProfile();
		}
	});
});