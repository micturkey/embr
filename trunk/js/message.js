//message function
var uniq=function(a){var b=[],c={},d,e,f=a.length;if(f<2){return a}for(d=0;d<f;d++){e=a[d];if(c[e]!==1){b.push(e);c[e]=1}}return b}
$(function () {
	formFunc();
	$("#sent_id").autocomplete(uniq($(".user_name").text().split(" ")));
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