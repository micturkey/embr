$(function(){
	$('.bg_input').ColorPicker({ 
		onBeforeShow: function () {
			$(this).ColorPickerSetColor(this.value);
		},
		onSubmit: function(hsb, hex, rgb, el) {
			$(el).val("#" + hex);
			$(el).ColorPickerHide();
		}
	}).bind('keyup', function(){
		$(this).ColorPickerSetColor(this.value);
	});

	$('#reset_link').bind('click', function(e){
		e.preventDefault();
		if(confirm("You will lose all customized settings!")){
			$.cookie('myCSS', '');
			$.cookie('fontsize', '');
			$.cookie('bodyBg', '');
			location.reload();
		}
	});
	
	$("#AvatarUpload").click(function (e) {
		e.preventDefault();
		ProfileImageUpload();
	});	

	var style = {
		"Twitter Default":{myCSS:"/*default*/ "}, 
		"Dark Rabr":{myCSS:"@import url(themes/1.css);"}, 
		"Monokai Python":{myCSS:"@import url(themes/2.css);"}, 
		"Old Times":{myCSS:"@import url(themes/3.css);"}, 
		"Pink":{myCSS:"@import url(themes/4.css);"},
		"Warm @lgsoltek":{myCSS:"@import url(themes/5.css);"},
		"Cold @lgsoltek":{myCSS:"@import url(themes/6.css);"},
		"Green":{myCSS:"@import url(themes/7.css);"},
		"Shine":{myCSS:"@import url(themes/8.css);"},
		"Flew":{myCSS:"@import url(themes/9.css);"},
		"Golden":{myCSS:"@import url(themes/10.css);"},
		"#red":{myCSS:"@import url(themes/11.css);"},
		"Storm":{myCSS:"@import url(themes/12.css);"},
		"City":{myCSS:"@import url(themes/13.css);"},
		"Cosmos":{myCSS:"@import url(themes/14.css);"},
		"Pride (Rainbow)":{myCSS:"@import url(themes/15.css); /* Have a gay day! */"},
		"Drop Bombs":{myCSS:"@import url(themes/16.css);"},
		"Minimal":{myCSS:"@import url(themes/minimal.css);"},
	};

	$.each(style, function (i,o) {
		$("#styleSelect").append('<option value="' + i + '">' + i + '</option>');
	});
	$("#styleSelect").change(function(){
		if ($(this).val() != "n/a") {
			$.each(style[$(this).val()], function (i,o) {
				$("#"+i).val(o);
			});
		}
	});
});

$(function(){
	checkbox('showpic',"#showpic",true);
	checkbox('mediaPre',"#mediaPreSelect",true);
	checkbox('p_avatar',"#proxifyAvatar",false);
	selectbox('homeInterval',"#homeInterval");
	selectbox('updatesInterval',"#updatesInterval");
	selectbox('fontsize',"#fontsize");
});
function checkbox(c,id,d){
	if ($.cookie (c) === null) {
		$.cookie (c, d, { expires: 30 });
	} else if ($.cookie (c) === 'true') {
		$(id).attr('checked', true);
	} else {
		$(id).attr('checked', false);
	}
	$(id).click(function (){
		$.cookie(c, $(id).attr("checked"), { expires: 30 });
	});
}
function selectbox(c,id){
	if($.cookie(c)){
		$(id).setSelectedValue($.cookie(c));
	}
}

function ProfileImageUpload() {
	updateSentTip("Uploading your profile image...", 10000, "ing");
	$.ajaxFileUpload({
			url: 'ajax/uploadImage.php?do=profile',
			timeout: 60000,
			secureuri: false,
			fileElementId: 'profile_image',
			dataType: 'json',
			success: function (data, status) {
				if (typeof(console) !== 'undefined' && console != null) {
					console.info(data);
				}
				if (typeof(data.result) != 'undefined' && data.result == "success") {
					$.ajax({
						url: '../ajax/updateProfile.php',
						type: "GET",
						dataType: "json",
						success: function(msg){
							freshProfile();
							$(".settings > img").attr("src",$.cookie("imgurl"));
						}
					});
					updateSentTip("Your profile image has been uploaded!", 3000, "success");
				} else {
					updateSentTip("Failed to upload, please try again.", 3000, "failure");
				}
			},
			error: function (data, status, e) {
				updateSentTip("Failed to upload, please try again.", 3000, "failure");
			}
		})
	return false;
}