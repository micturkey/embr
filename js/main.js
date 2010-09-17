$(function(){
	document.getElementById("submit_btn").disabled=false;	
	leaveWord();
	$("#textbox").focus();
	$("#textbox").keydown(leaveWord).keyup(leaveWord).keydown(function(event){
		if (event.ctrlKey && event.keyCode==13) {
			$("form:first").submit();
		}
		});
	
	$(".submit_btn").click(function(){
		document.getElementById("submit_btn").disabled=true;	
	});
	
	$(".rt_btn").live("click", function(){
		$("#textbox").val("RT @" + $(this).parent().find(".status_word").text());
		$("#textbox").focus();
		leaveWord();
	});
	
	$(".replie_btn").live("click", function(){
		var replie_id = $(this).parent().find(".status_word").find(".user_name").text();
		$("#textbox").val("@" + replie_id + " ");
		$("#textbox").focus();
		$("#in_reply_to").val($(this).parent().find(".status_id").text());
		leaveWord();
	});
});

function leaveWord() {
	var leave = 140-$("#textbox").val().length;
	if (leave < 0) {
		$("#tip").css("color","#CC0000");
		$("#tip b").css("color","#CC0000");
		$("#tip").html("<b>" + (-leave) + "</b>");
	} else {
		$("#tip").css("color","#000000");
		$("#tip b").css("color","#000000");
		$("#tip").html("<b>" + leave + "</b>");
	}
}