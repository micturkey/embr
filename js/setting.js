$(function(){
	checkbox('showpic',"#showpic",true);
	checkbox('mediaPre',"#mediaPreSelect",true);
	checkbox('p_avatar',"#proxifyAvatar",false);
	selectbox('homeInterval',"#homeInterval",function(){
		$.cookie('intervalChanged','true',{expires:365});
	});
	selectbox('updatesInterval',"#updatesInterval",function(){
		$.cookie('intervalChanged','true',{expires:365});
	});
	selectbox('fontsize',"#fontsize");
	$('.bg_input').ColorPicker({ 
		onBeforeShow: function () {
			$(this).ColorPickerSetColor(this.value);
		},
		onSubmit: function(hsb, hex, rgb, el) {
			$(el).val("#" + hex);
			$(el).ColorPickerHide();
			$.cookie('bodyBg',"#" + hex,{expires:365});
			location.reload();
			updateSentTip('Setting saved successfully!',3000,'success');
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
			$.cookie('showpic','true');
			$.cookie('mediaPre','true');
			$.cookie('p_avatar','false');
			$.cookie('homeInterval',1);
			$.cookie('updatesInterval',3);
			location.reload();
			updateSentTip('Setting Reset successfully!',3000,'success');
		}
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
				$.cookie('myCSS',o,{expires:365});
				location.reload();
				updateSentTip('Themes Saved Successfully!',3000,'success');
			});
		}
	});
	$("textarea#myCSS").change(function(){
		$.cookie('myCSS',$(this).text(),{expires:365});
		location.reload();
		updateSentTip('Themes saved successfully!',3000,'success');
	});
	$("#AvatarUpload").click(function (e) {
		e.preventDefault();
		ProfileImageUpload();
	});
	$("#saveProfile").click(function(e){
		e.preventDefault();
		$.ajax({
			url: 'ajax/updateProfile.php',
			type: 'POST',
			data: {
				'name': $('input[name="name"]').val(),
				'url' : $('input[name="url"]').val(),
				'location': $('input[name="location"]').val(),
				'description': $('textarea[name="description"]').text()
			},
			success: function(msg) {
				if (msg == 'success') {
					freshProfile();
					updateSentTip ('Profile updated successfully!',3000,'success');
				} else {
					updateSentTip ('Fail to update your profile, please try again',3000,'failure');
				}
			},
			error: function() {
				updateSentTip ('Fail to update your profile, please try again',3000,'failure');
			}
		});
	});
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
		$.cookie(c,$(id).attr("checked"),{expires:365});
		updateSentTip('Setting saved successfully!',1000,'success');
	});
}
function selectbox(c,id,extra){
	if($.cookie(c) != undefined){
		$(id).setSelectedValue($.cookie(c));
	}
	$('select'+id).change(function (){
		$.cookie(c,$('select'+id+' option:selected').val(),{expires:365});
		if (extra != undefined) extra();
		updateSentTip('Setting saved successfully!',1000,'success');
	})
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
