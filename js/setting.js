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

$(show_pic_option);

function show_pic_option () {
	var cookie_val = $.cookie (COOKIE_NAME);
	if (cookie_val === null) {
		$.cookie (COOKIE_NAME, true, options);
	}
	else if (cookie_val === 'false') {
		$("#show_pic").attr("checked", false);
	}

	$("#show_pic").click(function (){
		$.cookie (COOKIE_NAME, $("#show_pic").attr("checked"), options);
	});

	if ($.cookie('mediaPre') === null) {
		$.cookie('mediaPre', true, { expires: 30 });
		$("#mediaPreSelect").attr('checked', true);
	}

	if($.cookie('mediaPre') === 'true'){
		$("#mediaPreSelect").attr('checked', true);
	}else{
		$("#mediaPreSelect").attr('checked', false);;
	}

	$("#mediaPreSelect").click(function(){
		if($(this).attr('checked') == true){
			$.cookie('mediaPre', true, { expires: 30 });
		}else{
			$.cookie('mediaPre', false, { expires: 30 });
		}				
	});
	
	if ($.cookie('proxify') === null) {
		$.cookie('proxify', true, { expires: 30 });
		$("#proxify").attr('checked', true);
	}
	
	if($.cookie('proxify') === 'true'){
		$("#proxify").attr('checked', true);
	}else{
		$("#proxify").attr('checked', false);;
	}
	
	$("#proxify").click(function(){
		if($(this).attr('checked') == true){
			$.cookie('proxify', true, { expires: 30 });
		}else{
			$.cookie('proxify', false, { expires: 30 });
		}				
	});
}
