$(function(){
	$(".rt_btn").live('click',function(e){
		e.preventDefault();
		if ($("#textbox").length > 0) {
			onRT($(this));
		} else {
			$("#browse_title").after(formHTML);
			formFunc();
			onRT($(this));
		}
	});
	$(".replie_btn").live('click',function(e){
		e.preventDefault();
		if ($("#textbox").length > 0) {
			onReplie($(this));
		} else {
			$("#browse_title").after(formHTML);
			formFunc();
			onReplie($(this),e);
		}
	});
	$(".favor_btn").live("click", function(e){
		e.preventDefault();
		onFavor($(this));
	});
	$('.unfav_btn').live("click", function(e){
		e.preventDefault();
		UnFavor($(this));
	});
	$(".retw_btn").live("click", function(e){
		e.preventDefault();
		onNwRT($(this));
	});
	$(".rt_undo,.unrt_btn").live("click", function(e){
		e.preventDefault();
		onUndoRt($(this));
	});
});