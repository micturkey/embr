$(function(){
		$("#analyze").click(function(){
				var user_1 = $.trim($("#user_1").val());
				var user_2 = $.trim($("#user_2").val());
				if(user_1 === $.trim($("#sideid").text())){
					$("#descr").animate({opacity: 0}, 200, function(){
					$("#descr").html("Due to the the limitation of API, you cannot enter your name in the first blank!").css("color", "#83161F").animate({opacity: 1}, 100);
					});
					return;
				};
				if(user_1 !== '' & user_2 !== ''){
					$("#analyze").val("").addClass("loading");
					$.ajax({
							url: "ajax/getRelationship.php" + "?target=" + user_1 + "&source=" + user_2,
							type: "GET",
							dataType: "text",
							data: "target=" + user_1 + "&source=" + user_2,
							success: function(msg) {
								msg = $.trim(msg);
								if(msg.length > 0 && msg !== "error") {
									$("#analyze").val("Analyze").removeClass("loading");
									$("#descr").html("Rabr Nexus is a tool helps you analyze relationships between you or the others.").css("color", "#333333");
									$("#result").animate({opacity: 0}, 500, function(){
									$("#result").removeClass("intro").html(msg).animate({opacity: 1}, 500);
									});
								}else{
									$("#descr").animate({opacity: 0}, 200, function(){
									$("#descr").html("Something went wrong, please try again!").css("color", "#83161F").animate({opacity: 1}, 100);
					});
								}
							},
							error: function() {
									$("#descr").animate({opacity: 0}, 200, function(){
									$("#descr").html("Something went wrong, please try again!").css("color", "#83161F").animate({opacity: 1}, 100);
					});
							}
						});
				}
			});
	});