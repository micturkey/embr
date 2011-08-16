$(function(){
	formHTML = "<h2>What are you doing?</h2>" + formHTML + "<div class=\"clear\"></div>";
	$(".rt_btn").live("click",function(e){
		e.preventDefault();
		if ($("#textbox").length > 0) {
			onRT($(this));
		} else {
			$("#search_form").after(formHTML);
			formFunc();
			onRT($(this));
		}
	});
	$(".replie_btn").live("click",function(e){
		e.preventDefault();
		if ($("#textbox").length > 0) {
			onReplie($(this),e);
		} else {
			$("#search_form").after(formHTML);
			formFunc();
			onReplie($(this),e);
		}
	});
	$(".favor_btn").live("click", function(e){
		e.preventDefault();
		onFavor($(this));
	});
	$(".retw_btn").live("click", function(e){
		e.preventDefault();
		onNwRT($(this));
	});
	$("#submit_btn").live("click", function(e){
		updateStatus();
		e.preventDefault();
	});
	updateTrends();
});
