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
	$(".delete_btn").live("click",function(e){
		e.preventDefault();
		onDelete($(this), "favor");
	});
	$(".retw_btn").live("click", function(e){
		e.preventDefault();
		onNwRT($(this));
	});	
});
