$(function(){
	formFunc();
	$(".rt_btn").live("click", function(e){
		e.preventDefault();
		onRT($(this));
	});
	$(".replie_btn").live("click", function(e){
		e.preventDefault();
		onReplie($(this));
	});
	$(".delete_btn").click(function(e){
		e.preventDefault();
		onDelete($(this), "收藏");
	});
	$(".retw_btn").live("click", function(e){
		e.preventDefault();
		onNwRT($(this));
	});	
});
