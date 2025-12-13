/**
 * Flat Series XE Layout Javascript
 * Copyright (C) ISIZU. All rights reserved.
 **/

(function($)
{
	/* Fix HTML5 placeholder */
	if ($.fn.placeholder)
	{
		$("input[placeholder], textarea[placeholder]").placeholder();
	}
	
	/* Active Camera Slideshow */
	if ($.fn.camera)
	{
		var cameraObj = $("#fs-camera-slide");
		if (cameraObj.length > 0)
		{
			var args = new Object();
			args.loader     = "none";
			args.alignment  = li.camera_slide_alignment;
			args.thumbnails = li.use_camera_slide_thumb;
			
			if (!li.camera_slide_advance)
			{
				args.autoAdvance       = false;
				args.mobileAutoAdvance = false;
				args.navigation        = false;
			}
			if (li.layout_type == "mainpage")
			{
				args.height = "500px";
			}
			else
			{
				args.height = "200px";
			}
			cameraObj.camera(args);
		}
	}
	
	if ($.isFunction(Cufon))
	{
			Cufon.replace(".cufon-normal, .readTitle > h1 > a, .boardHeader h1", {
			fontFamily : "NanumGothic",
			hover      : true
		});
		Cufon.replace(".cufon-shadow", {
			fontFamily : "NanumGothic",
			hover      : true,
			textShadow : "rgba(0, 0, 0, 0.75) 1px 1px"
		});
	}
	
	/* Function of top menu */
	$("header .submenu-wrap .submenu > li.active").show();
	$("header .menu-wrap .menu > li").on("mouseenter", this, function(e)
	{
		var id        = $(this).attr("id").split("-");
		var child     = $("header .submenu-wrap .submenu > li[id=pid-" + id[1] + "]");
		var currChild = $("header .submenu-wrap .submenu > li.active");
		
		if (child.attr("id") != currChild.attr("id"))
		{
			currChild.stop(true, true).removeClass("active").hide();
			child    .stop(true, true).   addClass("active").fadeIn(300);
		}
	});
	
	/* Function of sitemap */
	$(".sitemap-btn").on("click", this, function(e)
	{
		var child = $(this).children(".fa");
		
		child.toggleClass("fa-angle-up");
		child.toggleClass("fa-angle-down");
		
		if ((li.expand_sitemap && $.cookie("disp_fs_sitemap") != "N") || (!li.expand_sitemap && $.cookie("disp_fs_sitemap") == "Y"))
		{
			$.cookie("disp_fs_sitemap", "N", {path: "/"});
		}
		else
		{
			$.cookie("disp_fs_sitemap", "Y", {path: "/"});
		}
		
		$(".sitemap-wrap").stop().slideToggle(750, "easeOutBounce");
	});
	
	/* Function of To Top */
	$(".top-btn").on("click", this, function(e)
	{
		$("html, body").animate({scrollTop : 0}, 500, "easeInOutExpo");
	});
	
	$(window).on("scroll", this, function(e)
	{
		var that = $(this);
		var obj  = $(".top-btn");
		
		if (that.scrollTop() > 0)
		{
			obj.stop().fadeIn(500);
		}
		else
		{
			obj.stop().fadeOut(500);
		}
	});
})(jQuery);