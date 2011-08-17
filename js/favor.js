$(function(){
	formFunc();
	$(".rt_btn").live("click", function(e){
		e.preventDefault();
		onRT($(this));
	});
	$(".replie_btn").live("click", function(e){
		e.preventDefault();
		onReplie($(this),e);
	});
	$(".unfav_btn").live("click",function(e){
		e.preventDefault();
		UnFavor($(this));
	});
	$(".retw_btn").live("click", function(e){
		e.preventDefault();
		onNwRT($(this));
	});	
});
