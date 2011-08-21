$(function(){
		formFunc();
		$(".rt_btn").live("click", function(e){
				e.preventDefault();
				onRT($(this));
			});

		$(".retw_btn").live("click", function(e){
				e.preventDefault();
				onNwRT($(this));
			});

		$(".replie_btn").live("click", function(e){
				e.preventDefault();
				onReplie($(this),e);
			});

		$(".favor_btn").live("click", function(e){
				e.preventDefault();
				onFavor($(this));
			});
		$('.unfav_btn').live("click", function(e){
			e.preventDefault();
			UnFavor($(this));
		});
		$(".delete_btn").live("click", function(e){
				e.preventDefault();
				onDelete($(this));
			});
			
		$(".msg_replie_btn").live("click", function(e){
				e.preventDefault();
				onReplieDM($(this));
			});
			
		$(".msg_delete_btn").live("click", function(e){
				e.preventDefault();
				onDeleteMsg($(this));
			});

		$(".rt_undo,.unrt_btn").live("click", function(e){
				e.preventDefault();
				onUndoRt($(this));
			});

		$("#submit_btn").click(function(e){
				updateStatus();
				e.preventDefault();
			});
	});
