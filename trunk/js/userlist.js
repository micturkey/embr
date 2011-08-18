$(function () {
	$('body').click(function (e) {
			$(".right_menu").hide();
		});
	$(".rank_img img").live("click",function () {
		var $this = $(this);
		var id = getid($this);
		$this.addClass("loading");
		$.ajax({
			url: 'ajax/relation.php',
			type: "POST",
			data: "action=show&id=" + id,
			success: function(msg){
				var html = '<ul class="right_menu round"><li><a class="ul_mention" href="#"><i></i>Mention</a></li>';
				var r = parseInt(msg);
				switch(r){
					case 1:
					html += '<li><a class="ul_dm" href="#"><i></i>Message</a></li>';
					case 2:
					html += '<li><a class="ul_unfollow" href="#"><i></i>Unfollow</a></li><li><a class="ul_block" href="#"><i></i>Block</a></li>';
					break;
					case 3:
					html += '<li><a class="ul_dm" href="#"><i></i>Message</a></li><li><a class="ul_follow" href="#"><i></i>Follow</a></li><li><a class="ul_block" href="#"><i></i>Block</a></li>';
					break;
				}
				html += '<li><a class="ul_spam" href="#"><i></i>Report Spam</a></li><li><a href="user.php?id='+id+'">View Full Profile</a></ul>';
				$this.parent().after(html);
				$this.removeClass("loading");
			},
			error: function(){
				return;
			}
		});	
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
		var id = getid($this.parent());
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
		var id = getid($this.parent());;
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
		var id = getid($this.parent());;
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
	var id = getid($this.parent());
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
	var replie_id = getid($this.parent());;
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
	var replie_id = getid($this.parent());
	if ($("#textbox").length > 0) {
		var text = "D " + replie_id;
	} else {
		$("#statuses h2").before('<h2>Send direct message</h2>' + formHTML);
		formFunc();
		var text = "D " + replie_id;
	}
	scroll(0, 0);
	$("#textbox").focus();
	$("#textbox").val($("#textbox").val() + text + ' ');
	leaveWord();
}
var getid = function ($this) {
	return $this.parent().parent().find(".rank_screenname").text();
}
$(document).ready(function(){
	$.ajax({
		url: '../ajax/updateProfile.php',
		type: "GET",
		success: function(){
			freshProfile();
		}
	});
	$(".rank_img img").tipsy({gravity: 's'});
});