$(document).ready(function(){
		formFunc();
		$(".rt_btn").live("click", function(e){
				e.preventDefault();
				onRT($(this));
			});

		$(".replie_btn").live("click", function(e){
				e.preventDefault();
				onReplie($(this),e);
			});
		$(".favor_btn").live("click", function(e){
				e.preventDefault();
				onFavor($(this));
			});
		$(".delete_btn").live("click", function(e){
				e.preventDefault();
				onDelete($(this), "Tweet");
			});
		$("#submit_btn").click(function(e){
				updateStatus();
				e.preventDefault();
			});
	});
