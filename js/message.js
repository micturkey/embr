//message function
$(function () {
	formFunc();
	$(".msg_delete_btn").click(function(e){
		e.preventDefault();
		
		var $this = $(this);
		var message_id = $.trim($this.parent().parent().find(".status_id").text());
		var confirm = window.confirm("Are you sure to delete this message?");
		
		if (confirm) {
			updateSentTip("Deleting message...", 5000, "ing");
			$.ajax({
				url: "ajax/delete.php",
				type: "POST",
				data: "message_id=" + message_id,
				success: function(msg) {
					if (msg.indexOf("success") >= 0) {
						$this.parent().parent().parent().remove();
						updateSentTip("Message deleted.", 3000, "success");
					} else {
						updateSentTip("Failed to delete this message!", 3000, "failure");
					}
				},
				error: function(msg) {
					updateSentTip("Failed to delete this message!", 3000, "failure");
				}
			});
		}
	});

	$(".msg_replie_btn").click(function(e){
		e.preventDefault();
		$("#sent_id").val($(this).parent().parent().find(".status_word").find(".user_name").text());
		$("#textbox").focus();
	});
});
$(document).ready(function () {
	var url=location.href;
	ar = url.substr(url.indexOf("?")+1).split("&");
	if(ar.length > 0){
		var id=ar[0].indexOf("id=");
		$("h2:first").html('To <input type="text" style="border: 1px solid rgb(167, 166, 170); margin: 0px 0px 6px; padding: 2px; height: 14px; width: 120px; font-size: 13px;" name="sent_id" id="sent_id" />');
	} else{
		$("h2:first").html('To <input type="text" style="border: 1px solid rgb(167, 166, 170); margin: 0px 0px 6px; padding: 2px; height: 14px; width: 120px; font-size: 13px;" name="sent_id" id="sent_id" value="'+id+'"/>');
	}
	$("a[tabindex='2'] span").text("Send");
});