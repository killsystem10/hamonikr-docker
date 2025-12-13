

/** Dropdown **/
jQuery(function($){
	$("ul.sf-menu").supersubs({ 
        minWidth:    12,   // minimum width of sub-menus in em units 
        maxWidth:    27,   // maximum width of sub-menus in em units 
        extraWidth:  1     // extra width can ensure lines don't sometimes turn over 
                           // due to slight rounding differences and font-family 
	}).superfish();         

	  
	  $(".loginbt").click(
			function(){ 
			$("#cv2_login_area").fadeIn('slow'); 		
			}
		);
		
		$("#login_close,.idmail li,.btnJoin,btnSJoin").click(
			function(){ 
			$("#cv2_login_area").fadeOut('slow'); 		
			}
		);
		
		$('.language>.toggle').click(function(){
		$('.selectLang').toggle();
		});
		

/** Blockqoute **/

		$('blockquote').quovolver();
		
		// 퀵메뉴
		$(window).scroll(function(){
		  var st = jQuery(window).scrollTop(); 
		  var bt = st + 0 +"px";
		 $('#rquick').stop();
		 $('#rquick').animate({top:bt},700);
		 $('#lquick').stop();
		 $('#lquick').animate({top:bt},700);
		 });
});




/** Cycle Slider 1 **/

(function($){ 
	$(function(){
		/** Add the next and previous buttons with JavaScript to gracefully degrade */
		var cycle_container = $('#slide-container');
		cycle_container.append('<div id="cycle-next"></div><div id="cycle-prev"></div>');
		cycle_start(cycle_container, 0);
		/** Restart the slideshow when someone resizes the browser to ensure that sliding distance matches the correct viewport */
		$(window).resize(function(){
			var current_slide = cycle_container.find('.slide:visible').index();
			if(window.console&&window.console.log) { console.log('current_slide'+current_slide); }
			cycle_container.cycle('destroy');
			new_window_width = $(window).width();
			cycle_container.find('.slide').width(new_window_width);
			cycle_start(cycle_container, current_slide);
		});
	});
	/** Cycle configurations */
	function cycle_start(container, index){
		var window_width = $(window).width();
		container.find('.slide').width(window_width);
		if (container.length > 0){
			container.cycle({
				timeout: 5000,
				speed: 600,
				pager: '#cycle-pager',
				slideExpr: '.slide',
				fx: 'scrollHorz',
				easeIn: 'linear',
				easeOut: 'swing',
				startingSlide: index,
				pagerAnchorBuilder: cycle_paginate
			});
		}
	}
	function cycle_paginate(ind, el) {
		return '<a href="#slide-'+ind+'"><span>'+ind+'</span></a>';
	}
})(jQuery);




/** Cycle Slider 2 **/

(function($){ 
	$(function(){
		/** Add the next and previous buttons with JavaScript to gracefully degrade */
		var cycle_container = $('#slide-container-2');
		cycle_container.append('<div id="cycle-next"></div><div id="cycle-prev"></div>');
		cycle_start(cycle_container, 0);
		/** Restart the slideshow when someone resizes the browser to ensure that sliding distance matches the correct viewport */
		$(window).resize(function(){
			var current_slide = cycle_container.find('.slide:visible').index();
			if(window.console&&window.console.log) { console.log('current_slide'+current_slide); }
			cycle_container.cycle('destroy');
			new_window_width = $(window).width();
			cycle_container.find('.slide').width(new_window_width);
			cycle_start(cycle_container, current_slide);
		});
	});
	/** Cycle configurations */
	function cycle_start(container, index){
		var window_width = $(window).width();
		container.find('.slide').width(window_width);
		if (container.length > 0){
			container.cycle({
				timeout: 5000,
				speed: 600,
				pager: '#cycle-pager',
				slideExpr: '.slide',
				fx: 'fade',
				easeIn: 'linear',
				easeOut: 'swing',
				startingSlide: index,
				pagerAnchorBuilder: cycle_paginate
			});
		}
	}
	function cycle_paginate(ind, el) {
		return '<a href="#slide-'+ind+'"><span>'+ind+'</span></a>';
	}
})(jQuery);


/** Cycle Slider Loginbox **/

(function($){ 
	$(function(){
		/** Add the next and previous buttons with JavaScript to gracefully degrade */
		var cycle_container = $('#slide-container-loginbox');
		cycle_container.append('<div id="cycle-next"></div><div id="cycle-prev"></div>');
		cycle_start(cycle_container, 0);
		/** Restart the slideshow when someone resizes the browser to ensure that sliding distance matches the correct viewport */
		$(window).resize(function(){
			var current_slide = cycle_container.find('.slide:visible').index();
			if(window.console&&window.console.log) { console.log('current_slide'+current_slide); }
			cycle_container.cycle('destroy');
			new_window_width = 456;
			cycle_container.find('.slide').width(new_window_width);
			cycle_start(cycle_container, current_slide);
		});
	});
	/** Cycle configurations */
	function cycle_start(container, index){
		var window_width = 456;
		container.find('.slide').width(window_width);
		if (container.length > 0){
			container.cycle({
				timeout: 5000,
				speed: 600,
				pager: '#cycle-pager',
				slideExpr: '.slide',
				fx: 'fade',
				easeIn: 'linear',
				easeOut: 'swing',
				startingSlide: index,
				pagerAnchorBuilder: cycle_paginate
			});
		}
	}
	function cycle_paginate(ind, el) {
		return '<a href="#slide-'+ind+'"><span>'+ind+'</span></a>';
	}
})(jQuery);




/** Filterable Portfolio **/

(function($){ 
$(document).ready(function(){
	
	var items = $('#portfolio-stage li'),
		itemsByTags = {};
	
	// Looping though all the li items:
	
	items.each(function(i){
		var elem = $(this),
			tags = elem.data('tags').split(',');
		
		// Adding a data-id attribute. Required by the Quicksand plugin:
		elem.attr('data-id',i);
		
		$.each(tags,function(key,value){
			
			// Removing extra whitespace:
			value = $.trim(value);
			
			if(!(value in itemsByTags)){
				// Create an empty array to hold this item:
				itemsByTags[value] = [];
			}
			
			// Each item is added to one array per tag:
			itemsByTags[value].push(elem);
		});
		
	});

	// Creating the "Everything" option in the menu:
	createList('Everything',items);

	// Looping though the arrays in itemsByTags:
	$.each(itemsByTags,function(k,v){
		createList(k,v);
	});
	
	$('#filter a').live('click',function(e){
		var link = $(this);
		
		link.addClass('active').siblings().removeClass('active');
		
		// Using the Quicksand plugin to animate the li items.
		// It uses data('list') defined by our createList function:
		
		$('#portfolio-stage').quicksand(link.data('list').find('li'));
		e.preventDefault();
	});
	
	$('#filter a:first').click();
	
	function createList(text,items){
		
		// This is a helper function that takes the
		// text of a menu button and array of li items
		
		// Creating an empty unordered list:
		var ul = $('<ul>',{'class':'hidden'});
		
		$.each(items,function(){
			// Creating a copy of each li item
			// and adding it to the list:
			
			$(this).clone().appendTo(ul);
		});

		ul.appendTo('#portfolio');

		// Creating a menu item. The unordered list is added
		// as a data parameter (available via .data('list'):
		
		var a = $('<a>',{
			html: text,
			href:'#',
			data: {list:ul}
		}).appendTo('#filter');
	}
});




/** Contact Form **/

$(document).ready(function () {
    $('form#contact-form').submit(function () {
        $('form#contact-form .error').remove();
        var hasError = false;
        $('.requiredField').each(function () {
            if (jQuery.trim($(this).val()) == '') {
                var labelText = $(this).prev('label').text();
                $(this).parent().append('<div class="error">Must enter ' + labelText + '</div>');
                $(this).addClass('inputError');
                hasError = true;
            } else if ($(this).hasClass('email')) {
                var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
                if (!emailReg.test(jQuery.trim($(this).val()))) {
                    var labelText = $(this).prev('label').text();
                    $(this).parent().append('<div class="error">Invalid ' + labelText + '</div>');
                    $(this).addClass('inputError');
                    hasError = true;
                }
            }
        });
        if (!hasError) {
            $('form#contact-form input.submit').fadeOut('normal', function () {
                $(this).parent().append('');
            });
            var formInput = $(this).serialize();
            $.post($(this).attr('action'), formInput, function (data) {
                $('form#contact-form').slideUp("fast", function () {
                    $(this).before('<div class="success">Your email was sent. We will contact you ASAP.</div>');
                });
            });
        }

        return false;

    });
});




/** Pretty Hover **/

$(document).ready(function(){									
	$('.zoom-wrapper').hover(
		function(){
			$(this).find('a img').animate({opacity: ".6"}, 500);		
			$(this).find('.zoom').animate({top:"-150px"}, 500);			
		}, 
		function(){
			$(this).find('a img').animate({opacity: "1.0"}, 500);					
			$(this).find('.zoom').animate({top:"85px"}, 500);
		});			
});

})(jQuery);
	
	

/** Pretty Photo **/

jQuery(function($) {
	$("area[rel^='prettyPhoto']").prettyPhoto();
	
	$(".gallery:first a[rel^='prettyPhoto']").prettyPhoto({animation_speed:'normal',theme:'light_square',slideshow:3000, autoplay_slideshow: false});
	$(".gallery:gt(0) a[rel^='prettyPhoto']").prettyPhoto({animation_speed:'fast',slideshow:10000, hideflash: true});

	$("#custom_content a[rel^='prettyPhoto']:first").prettyPhoto({
		custom_markup: '<div id="map_canvas" style="width:260px; height:265px"></div>',
		changepicturecallback: function(){ initialize(); }
	});

	$("#custom_content a[rel^='prettyPhoto']:last").prettyPhoto({
		custom_markup: '<div id="bsap_1259344" class="bsarocks bsap_d49a0984d0f377271ccbf01a33f2b6d6"></div><div id="bsap_1237859" class="bsarocks bsap_d49a0984d0f377271ccbf01a33f2b6d6" style="height:260px"></div><div id="bsap_1251710" class="bsarocks bsap_d49a0984d0f377271ccbf01a33f2b6d6"></div>',
		changepicturecallback: function(){ _bsap.exec(); }
	});
});




/** Accordion **/

jQuery(function($) {
	//Set default open/close settings
	$('.acc_container').hide(); //Hide/close all containers
	/*$('.acc_trigger:first').addClass('active').next().show();*/ //Add "active" class to first trigger, then show/open the immediate next container
	
	//On Click
	$('.acc_trigger').click(function(){
		if( $(this).next().is(':hidden') ) { //If immediate next container is closed...
			$('.acc_trigger').removeClass('active').next().slideUp(); //Remove all "active" state and slide up the immediate next container
			$(this).toggleClass('active').next().slideDown(); //Add "active" state to clicked trigger and slide down the immediate next container
		}
		return false; //Prevent the browser jump to the link anchor
	});
});




/** Roundabout slider **/

jQuery(function($) {
      $('ul#myRoundabout').roundabout({

      });
});







/** Tabs **/

jQuery(function($) {
	$(document).ready(function() {
	
		//When page loads...
		$(".tab_content").hide(); //Hide all content
		$("ul.tabs li:first").addClass("active").show(); //Activate first tab
		$(".tab_content:first").show(); //Show first tab content
	
		//On Click Event
		$("ul.tabs li").click(function() {
	
			$("ul.tabs li").removeClass("active"); //Remove any "active" class
			$(this).addClass("active"); //Add "active" class to selected tab
			$(".tab_content").hide(); //Hide all tab content
	
			var activeTab = $(this).find("a").attr("href"); //Find the href attribute value to identify the active tab + content
			$(activeTab).fadeIn(); //Fade in the active ID content
			return false;
		});
		
		/** user option hover **/
		 $(".tiva_user").hover(function(){
			$("#member_option:not(:animated)").fadeIn("fast");
			},function(){
			$("#member_option:not(:animated)").fadeOut("fast"); 		 
		
		});
	
	});
});


jQuery(function($) {
	$(document).ready(function() {

		// 마우스오버 전체메뉴표시		
		 $("#gnbwarp").hover(function(){
	     	$("#openmenu-wrapper:not(:animated)").slideDown("fast");
			},function(){
   		    $("#openmenu-wrapper:not(:animated)").slideUp("fast"); 		 
	
		});
		
		// 사이드메뉴 배경화면	
		 $("#sidemenu li").hover(function(){
	     	$(".shbg",this).fadeIn("fast");
			},function(){
   		    $(".shbg",this).fadeOut("fast"); 		 
	
		});
		
		// 사이드메뉴 상단 유틸메뉴
		 $("#side_util_tap .member").toggle(function(){
			 
				$("#side_util_cont2,#side_util_cont3").hide();
				$("#side_util_tap .newco,#side_util_tap .newdoc").removeClass("active");
				$("#side_util_cont1").show("blind"); 
				$("#side_util_tap .member").addClass("active");

			 },function(){
				 
				 $("#side_util_cont1").hide("blind");
				 $("#side_util_tap .member").removeClass("active");
				 
		});
		
		
		 $("#side_util_tap .newdoc").toggle(function(){
			 
				$("#side_util_cont1,#side_util_cont3").hide();
				$("#side_util_tap .member,#side_util_tap .newco").removeClass("active");
				$("#side_util_cont2").show("blind"); 
				$("#side_util_tap .newdoc").addClass("active");

			 },function(){
				 
				 $("#side_util_cont2").hide("blind");
				 $("#side_util_tap .newdoc").removeClass("active");
		});
		
		
		 $("#side_util_tap .newco").toggle(function(){
			 
				$("#side_util_cont2,#side_util_cont1").hide();
				$("#side_util_tap .member,#side_util_tap .newdoc").removeClass("active");
				$("#side_util_cont3").show("blind"); 
				$("#side_util_tap .newco").addClass("active");

			 },function(){
				 
				 $("#side_util_cont3").hide("blind");
				 $("#side_util_tap .newco").removeClass("active");
		});
		

	});
});


