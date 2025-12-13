jQuery(function($){
	$(window).load(function(){

		var bodyTop = $('.body').offset().top;
		var bodyTop = bodyTop - 80;
		var bodyHeight = $('.body').outerHeight();
		var subHeight = $('.e1').outerHeight();
		var conHeight = $('.content').outerHeight();
		$(window).on('scroll', function () {
			
			var footerTop = $('.footer').offset().top;
			var footerHeight = $('.footer').outerHeight();
			var lnbHeight = $('.lnb_menu').outerHeight();
			var sub_sectionHeight = $('.sub_section').outerHeight();
			var QuickSub = bodyTop + lnbHeight;
			var winHeight = $(window).height();
			var QuickBottomSub = bodyTop + lnbHeight + sub_sectionHeight - winHeight + 80;
			var QuickFooter = footerTop - winHeight -20;
			if  (conHeight >= subHeight){
					$(".e1").css('min-height',bodyHeight);
				if  (winHeight >= sub_sectionHeight){
					if  ($(window).scrollTop() >= QuickSub){
						$('.sub_section').addClass('quick_sub_section');
					}
					
					if  ($(window).scrollTop() <= QuickSub){
						$('.sub_section').removeClass('quick_sub_section');
					 }
				} else {
					if  ($(window).scrollTop() >= QuickBottomSub){
						$('.sub_section').addClass('quickBottom_sub_section');
						if  ($(window).scrollTop() >= QuickFooter){
							$('.sub_section').removeClass('quickBottom_sub_section');
							$('.in_e1').addClass('absolute_e1');
						}
						if  ($(window).scrollTop() < QuickFooter){
							$('.sub_section').addClass('quickBottom_sub_section');
							$('.in_e1').removeClass('absolute_e1');
						}
						if  ($(window).scrollTop() < 1000){
							$('.in_e1').removeClass('absolute_e1');
						}
					}
					
					if  ($(window).scrollTop() <= QuickBottomSub){
						$('.sub_section').removeClass('quickBottom_sub_section');
					 }
				}
			}

		});
		
	});
});