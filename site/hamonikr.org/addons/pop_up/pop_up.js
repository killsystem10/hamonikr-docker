/*
 * jQuery popup
 *
 * Author : zirho
 *
 * Copyright (c) 2009 http://www.wingtech.co.kr
 * Dual licensed under the MIT (MIT-LICENSE.txt)
 * and GPL (GPL-LICENSE.txt) licenses.
 *
 * http://www.wingtech.co.kr
 *
 * Depends:
 *	jquery.js
 */


(function(jQuery) {

	jQuery.fn.xe_popup = function(options) {

		// build main options before element iteration
		var main_opts = jQuery.extend({}, jQuery.fn.xe_popup.defaults, options);

		// iterate and reformat each matched element
		return this.each(function() {
			
			var jQuerythis = jQuery(this);
			var opts = jQuery.metadata ? jQuery.extend({}, main_opts, jQuerythis.metadata()) : main_opts;
	
			if (!getCookie(opts.id) || getCookie(opts.id) != "no"){

				var popupbody = jQuery("<div></div>");

				if(opts.popup_type == 'content'){
					popupbody.html(opts.content);
					jQuerythis.show("fast");
				}else if(opts.popup_type == 'url'){
					popupbody.load(opts.url, function(){jQuerythis.show("fast")});
				}
				
				var popupcloser = jQuery("<div class='popupcloser' style='cursor:move;'></div>");
				var popupclosercheck = jQuery("<div style='cursor:pointer; border:0px red solid; width:25px; height:33px; display: -moz-inline-stack; display: inline-block; zoom: 1; *display: inline; vertical-align:top;'><input type='checkbox' style='margin:8px 0 0 8px;' /></div>");
				var popupclosertext = jQuery("<div style='padding-top:8px; overflow:hidden; border:0px red solid; width:"+ (parseInt(opts.width) - 61)+"px; height:33px; display: -moz-inline-stack; display: inline-block; zoom: 1; *display: inline; vertical-align:top;'>"+opts.exp_days + "일 동안 다시보지 않기"+"</div>");
				var closebutton = jQuery("<div class='iePngFix' style='cursor:pointer; background:url(\"./addons/pop_up/x.png\") no-repeat; border:0px red solid; width:33px; height:33px; display: -moz-inline-stack; display: inline-block; zoom: 1; *display: inline; vertical-align:top;'></div>");

				var popup = jQuerythis;
				var target = jQuery("body");

				
				popup.css('width',opts.width)
					.css('height',parseInt(opts.height) + parseInt(opts.closerheight))
					.css('top',opts.top)
					.css('left',opts.left)
					.css('border','2px #CECECE solid')
					.css('position','absolute')
					.css('z-index','999')
					.css('background','white')
					.css('display','none');

				if (opts.linkto){
					popupbody.css('cursor','pointer');
					if (opts.linkto_newwindow == 'true')
						popupbody.click(function(){window.open(opts.linkto); jQuerythis.hide("fast");});
					else
						popupbody.click(function(){document.location.href=opts.linkto;});
				}

				popupbody.css('width',opts.width)
					.css('height',opts.height)
					.css('overflow',"hidden")
					.css('cursor','hand')
					.css('position','relative')
					.css('z-index','999');
				popupcloser
					.css('background','#1a1a1a')
					.css('padding', '0')
					.css('color', 'white')
					.css('width',opts.width)
					.css('overflow','hidden')
					.css('vertical-align', 'middle')
					.css('height', opts.closerheight);

				popupclosercheck.attr('id', opts.id);
				popupclosercheck.click(function(){setCookie(opts.id, "no", opts.exp_days); jQuerythis.hide("fast");});

				closebutton.click(function(){jQuerythis.hide("fast");});

				popupcloser.append(popupclosercheck);
				popupcloser.append(popupclosertext);
				popupcloser.append(closebutton);

				popup.draggable();
				popup.append(popupbody);
				popup.append(popupcloser);
				target.append(popup);
			}

			function setCookie( id, value, exp_days )
			{
				 var todayDate = new Date();
				 todayDate.setDate( todayDate.getDate() + exp_days );
				 document.cookie = id + "=" + escape( value ) + "; path=/; expires=" + todayDate.toGMTString() + ";"
			}

			function getCookie(name)
			{
				var value=null, search=name+"=";
				if (document.cookie.length > 0) {
					var offset = document.cookie.indexOf(search);
					if (offset != -1) {
						offset += search.length;
						var end = document.cookie.indexOf(";", offset);
						if (end == -1) end = document.cookie.length;
						value = unescape(document.cookie.substring(offset, end));
					}
				}
				return value;
			}
		});
	};

	//
	// plugin defaults
	//
	jQuery.fn.xe_popup.defaults = {
		id: 'xe_popup',
		popup_type: 'content',
		content: '',
		open_type: 'inner',
		exp_days: '1',
		width: '0px',
		height: '0px',
		top: '0px',
		left: '0px',
		url: '/shopxe/example_popup',
		linkto: '',
		linkto_newwindow: '',
		popupname: 'xe_popup',
		closerheight: '35px'
	};
//
// end of closure
//
})(jQuery);
