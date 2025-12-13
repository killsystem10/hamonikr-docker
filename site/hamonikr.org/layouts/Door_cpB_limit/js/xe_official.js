jQuery(function($){
// Main Menu
	var sMenu = $('ul.menu');
	var aItem = sMenu.find('>li>a');
	var spItem = sMenu.find('>li>a>span.hover_line');
	var sItem = sMenu.find('>li');
	var shItem = sMenu.find('>li.active');
	var sshItem = sMenu.find('>li.highlight');
	var ssItem = sMenu.find('li');
	var secondItem = sMenu.find('second_ul');
	var sshhItem = sMenu.find('>li>div');
	var secondUl = sMenu.find('>li>div>ul>li>ul');
	var aaItem = sshhItem.find('a');
	var LastLi = sMenu.find('li').last();
	var lastEvent = null;
	
	function sMenuSlide(){
		
		var t = $(this);
		
		t.next().children().find('li').removeClass('highlight');
		secondUl.stop(true,true).slideUp('fast').addClass('sub2');
		if (t.next().hasClass('sub1')) {
		sshhItem.stop(true,true).slideUp('fast').addClass('sub1');
		t.next('div').stop(true,true).slideDown('fast').removeClass('sub1');
		sItem.removeClass('highlight');
		t.parent('li').addClass('highlight');
		} else if(!t.next('div').length) {
		sshhItem.stop(true,true).slideUp('fast').addClass('sub1');
		sItem.removeClass('highlight');
		t.parent('li').addClass('highlight');}

		
	}
	aItem.mouseover(sMenuSlide).focus(sMenuSlide);

		function HBhighlight(){
			var tt = $(this);
			
		tt.parent().parent().children('li').removeClass('highlight');
		tt.next('ul').children('li').removeClass('highlight');
		tt.parent('li').addClass('highlight');		
		if (tt.next().hasClass('sub2')) {
		tt.parent().parent().children().children('ul').stop(true,true).slideUp(50).addClass('sub2');
		tt.next('ul').stop(true,true).slideDown('fast').removeClass('sub2');
		} else if(!tt.next('ul').length) {
		tt.parent().parent().children().children('ul').stop(true,true).slideUp(50).addClass('sub2');
		}
			
	}
	aaItem.mouseover(HBhighlight).focus(HBhighlight);

	function slideUp_menu(){
		sshhItem.stop(true,true).slideUp('fast').addClass('sub1');
		secondUl.stop(true,true).slideUp('fast').addClass('sub2');
		ssItem.removeClass('highlight');
			
			

		}
	sItem.mouseleave(slideUp_menu);

	function clear_sss(){
		if (!shItem.hasClass('highlight')) {
			sMenu.children('li').removeClass('highlight');
			shItem.addClass('highlight');
		}
		sshhItem.stop(true,true).slideUp('fast').addClass('sub1');
		sMenu.children('li').removeClass('highlight');
		shItem.addClass('highlight');
		}
	sMenu.mouseleave(clear_sss);
	LastLi.focusout(clear_sss);

	
//전체메뉴
		function gMenuToggle(){
			var t = $(this);
			if (t.hasClass('offAll')) {
				$('div.all_Menu').slideDown(200);
				$('a.ViweAll').removeClass('offAll');
				
			} else {
				$('div.all_Menu').slideUp(200);
				$('a.ViweAll').addClass('offAll');
			
			}; 
			return false;
		};
		$('a.ViweAll').click(gMenuToggle);

	var lMenu = $('ul.locNav');
    var lItem = lMenu.find('>li');
    var llItem = lMenu.find('>li>ul>li');
    var lastEvent = null;
    function lMenuToggle(){
        var t = $(this);
        if (t.next('ul').is(':hidden') || t.next('ul').length == 0) {
            lItem.find('>ul').slideUp(200);
            lItem.find('button').removeClass('on');
            t.next('ul').slideDown(200);
            t.addClass('on');            
        } else {
        	lItem.find('>ul').slideUp(200);
            lItem.find('button').removeClass('on');
        
        }; 
		$('.lnb_All').addClass('none_act_lnb');
    };
    lItem.find('>button').click(lMenuToggle);
	if (!$('.foot_absolute').children('a').hasClass('ds_dw')) {
		$('.xe').css('display','none');

	}

// 빵조각
	$('ul.breadclumb').find('>li').last().addClass('last_breadclumb');
 
		$('.to_top').click(function(){
			$("html, body").animate({ scrollTop: 0 }, 200);
			return false;
	   });
	$('.act_search').click(function(){
			$('.in_select').toggle();
			var sItem = $('.wrap_in_select');
			var sTop = $('.wrap_in_select').scrollTop();
			var sHeight = $('.wrap_in_select>.in_select').outerHeight();
			sItem.removeClass('position_top');
			if (sItem.offset().top + sItem.outerHeight() + sHeight > $(window).scrollTop() + $(window).height()) {
				sItem.addClass('position_top');
			}

			return false;
		});
		$('.in_select').find('li').last().focusout(function(){
			$('.in_select').css('display','none');
		});
// Language Select
	$('.language .toggle').click(function(){
		$('.selectLang').toggle();
	});	 
// 탭메뉴
		function tabMenuTogglem(){
    	  var thisBtnm = $(this);
    	  var targetBoxm = $('#' + thisBtnm.attr('name'));
		  var allBoxm = $('.sub_menu');
    	  var targetLim = $('.first_item');
		  var target_Lim = thisBtnm.parent('.first_item');
    	  if (!thisBtnm.parent('li').hasClass('on')) {
				targetLim.removeClass('on');
				allBoxm.removeClass('menuTab_on');
    	  	    targetBoxm.addClass('menuTab_on');
    	  	    target_Lim.addClass('on');
    	  };
			 if(thisBtnm.attr('href') === '#'){
			return false;
			};
		}
    
    $('.tab_first_a').click(tabMenuTogglem).focus(tabMenuTogglem);
// 모바일 토글메뉴
		function TotalToggle(){
			
			if ($('.mm_mobile_menu').hasClass('none_mobile_menu')) { 
				$('.mm_mobile_menu').removeClass('none_mobile_menu');
				$('.fix_mobile').css('display','block');
				$('#scrollUp').css('display','none');
				$('.mm_mobile_menu').stop().animate({width: '300px'}, 200, 'swing');
			} else {
				$('.mm_mobile_menu').addClass('none_mobile_menu');
				$('.fix_mobile').css('display','none');
				$('#scrollUp').css('display','block');
				$('.mm_mobile_menu').stop().animate({width: '0'}, 200, 'swing');
			}
			return false;
		}
		$('.mobile_menu_act').click(TotalToggle);

		var gItem = $('li.mm-list-li');
		var lastEvent = null;
		function ggMenuToggle(){
			var t = $(this);
			if (t.next('ul').is(':hidden') || t.next('ul').length == 0) {
				gItem.find('>ul').slideUp(200);
				gItem.find('button').removeClass('hover');
				t.next('ul').slideDown(200);
				t.addClass('hover');            
			} else {
				gItem.find('>ul').slideUp(200);
				gItem.find('button').removeClass('hover');
			
			}; 
		};
		gItem.find('>button').click(ggMenuToggle);
// 모바일 검색
		function TopSearchToggle(){
		if ($('.mobile_top_search').hasClass('none_top_search')) {
			$('.mobile_top_search').stop(true,true).slideDown('fast').removeClass('none_top_search').find('.mm_iText').focus();
		} else {
		$('.mobile_top_search').stop(true,true).slideUp(100).addClass('none_top_search');
			
		}
		return false;
	 }
	$('.mobile_menu_search').click(TopSearchToggle);

	// 탭메뉴
		function SubtabMenuToggle(){
    	  var thisBtn = $(this);
    	  var targetBox = $('#' + thisBtn.attr('name'));
		  var targetUl = thisBtn.parent('li').parent('ul');
		  var allBox = thisBtn.parent('li').parent('ul').parent('div').next('div').children('div');
    	  var targetLi = thisBtn.parent('li').parent('ul').children('li');
		  var target_Li = thisBtn.parent('li');

    	  if (!thisBtn.parent('li').hasClass('on')) {
				targetLi.removeClass('on');
				allBox.removeClass('wrapTab_on');
    	  	    targetBox.addClass('wrapTab_on');
    	  	    target_Li.addClass('on');
    	  };
		  if(thisBtn.attr('href') === '#'){
			return false;
		}; 
    }
    $('.tab_a').click(SubtabMenuToggle).focus(SubtabMenuToggle);
	$('.e1 .tab_a').mouseover(SubtabMenuToggle);
});