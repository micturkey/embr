$(document).ready(function(){
	$(".delete_btn").click(function(e){
		e.preventDefault();
		
		var $this = $(this);
		var message_id = $.trim($this.parent().parent().find(".status_id").text());
		var confirm = window.confirm("Are you sure to delete this message?");
		
		if (confirm) {
			updateSentTip("Deleting message...");
			$.ajax({
				url: "ajax/delete.php",
				type: "POST",
				data: "message_id=" + message_id,
				success: function(msg) {
					if (msg.indexOf("success") >= 0) {
						$this.parent().parent().parent().remove();
						updateSentTip("Message deleted.");
					} else {
						updateSentTip("Failed to delete this message!");
					}
				},
				error: function(msg) {
					updateSentTip("Failed to delete this message!");
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
