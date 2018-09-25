/* 
*
* Easy front-end framework
*
* Copyright (c) 2011 Alen Grakalic
* http://easyframework.com/license.php
*
* supported by Templatica (http://templatica.com)
* and Css Globe (http://cssglobe.com)
* 
* built to be used with jQuery library
* http://jquery.com
* 
* update: Jan 4th 2011
* 
*/


/* 
*
* Easy front-end framework
*
* Copyright (c) 2011 Alen Grakalic
* http://easyframework.com/license.php
*
* supported by Templatica (http://templatica.com)
* and Css Globe (http://cssglobe.com)
* 
* built to be used with jQuery library
* http://jquery.com
* 
* update: Jan 17th 2011
* 
*/


(function($){
					
  $.easy = {
	  
		notification: function(options){
			//
		},
		
		//JavaScript cookies
		//
		cookie: function(action,name,value,days){

			var set = function(name,value,days){
				if(days) {
					var date = new Date();
					date.setTime(date.getTime()+(days*24*60*60*1000));
					var expires = '; expires='+date.toGMTString();
				}
				else var expires = '';
				document.cookie = name+'='+value+expires+'; path=/';
			};
			
			var get = function(name){
				var nameEQ = name + '=';
				var ca = document.cookie.split(';');
				for(var i=0;i < ca.length;i++) {
					var c = ca[i];
					while (c.charAt(0)==' ') c = c.substring(1,c.length);
					if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
				}
				return null;
			};
			
			var remove = function(name){
				set(name,'',-1);
			};
			
			return eval(action+'("'+name+'","'+value+'","'+days+'")');
			
		},
		
		// navigation
		// adds drop down menu functionality
		navigation: function(options) {
			
			var defaults = {  
				selector: '#nav_top li',
				className: 'over'
			};  
			
			if(typeof options == 'string') defaults.selector = options;
			var options = $.extend(defaults, options); 
			return $(options.selector).each(function(){
				
				$(this).hover(function(){
					$('ul:first',this).fadeIn(100);
					$(this).addClass(options.className);
				 }, function(){
					$('ul',this).hide();
					$(this).removeClass(options.className);
					}
				);	
				
			});			
		},
		
		
		// tooltip
		//
		tooltip: function(options){
			
			var defaults = {	
				selector: '.tooltip',	//.tooltip
				xOffset: 10,		
				yOffset: 25,
				clickRemove: false,
				id: 'easy_tooltip',
				content: '',
				useElement: ''
			}; 
			
			if(typeof options == 'string') defaults.selector = options;
			var options = $.extend(defaults, options); 
			var content;
	
			return $(options.selector).each(function(){ 		
																							 
				var title = $(this).attr('title');				
				$(this).hover(function(e){											 							   
					content = (options.content != '') ? options.content : title;
					content = (options.useElement != '') ? $('#' + options.useElement).html() : content;
					$(this).attr('title','');									  				
					if (content != '' && content != undefined){			
						$('body').append('<div id="'+ options.id +'">'+ content +'</div>');		
						$('#' + options.id)
							.css({'position':'absolute','display':'none'})
							.css('top',(e.pageY - options.yOffset) + 'px')
							.css('left',(e.pageX + options.xOffset) + 'px')	
							.fadeIn('fast')
					};
				},
				function(){	
					$('#' + options.id).remove();
					$(this).attr('title',title);
				});	
				$(this).mousemove(function(e){
					var x = ((e.pageX + options.xOffset + $(this).width()) < $(window).width()) ? (e.pageX + options.xOffset) : (e.pageX - options.xOffset - $(this).width() - 16);
					$('#' + options.id)
						.css('top',(e.pageY - options.yOffset) + 'px')
						.css('left',(x+'px'))					
				});	
				if(options.clickRemove){
					$(this).mousedown(function(e){
						$('#' + options.id).remove();
						$(this).attr('title',title);
					});				
				};
				
			});
		},
		
		
		// popup
		// lightbox-like script that enables you to open images or URLs in an iframe
		popup: function(options){
				
			var defaults = {  
				selector: '.popup',
				popupId: 'easy_popup',
				preloadText: 'Loading...',
				errorText: 'There has been a problem with your request, please click outside this window to close it.',
				closeText: 'Close',
				prevText: '&laquo; Previous',
				nextText: 'Next &raquo;',
				opacity: .7,
				hiddenClass: 'hidden',
				callback: function(){}
			};  
			
			if(typeof options == 'string') defaults.selector = options;
			var options = $.extend(defaults, options); 
			return $(options.selector).each(function(i){
				
				// variables needed for handling DOM objects in a popup
				var DOMobj, DOMobjparent;
				var DOMobjvisible = true;
				
				var tagName = this.tagName.toLowerCase();
			
				// gallery
				if($(this).hasClass('gallery')){
					var classNames = $(this).attr('class');
					classNames = classNames.split(' ').join('');
					$.data(this,'gallery',classNames);
					eval('if((typeof ' + classNames + '_arr == "undefined")) ' + classNames + '_arr= new Array()');
					eval(classNames + '_arr').push($(this));
					$.data(this,'index',eval(classNames + '_arr').length-1);
				};
				
				if($.browser.opera) $.support.opacity = true;
				var ie6 = $.browser.msie && $.browser.version.substr(0,1)<7;
				var opera95 = $.browser.opera && $.browser.version<=9.5;
				
				var w,h,w2,h2;
				
				var cw,ch;
				cw = ch = 0;
				
				var showOk = false;
				
				var init = function(){
					
					w = $(window).width(); 
					h = $(document).height();
					w2 = $(window).width()/2; 
					h2 = $(window).height()/2;
					
					// create transparent background
					if($('#'+ options.popupId).length == 0){
						$('<div id="'+ options.popupId +'"></div>').appendTo('body')
						.css({'width':w,
							 'height':h,
							 'position':'absolute',
							 'top':'0',
							 'left':'0',
							 'z-index':'10000',
							 'opacity': options.opacity}).click(function(){remove();});			
					};
					
					// create preloader
					$('<div id="'+ options.popupId +'_preloader">'+ options.preloadText +'</div>').appendTo('body');
					set($('#'+ options.popupId + '_preloader'));
			
					// create popup content outer
					$('<div id="'+ options.popupId +'_content"></div>')
						.appendTo('body')
						.css({'visibility':'hidden','position':'absolute','top':'-10000px','left':'-10000px'});
					
					// create popup content inner
					$('<div id="'+ options.popupId +'_inner"></div>')
						.appendTo('#'+ options.popupId + '_content')
						.css({'overflow':'auto','height':'100%'});
					
					//close button
					$('<small id="'+ options.popupId +'_close">'+ options.closeText  +'</small>')
						.appendTo('#'+ options.popupId +'_inner')
						.click(function(){remove();});
						
				};
				
				var show = function(cw,ch){
					$('#'+ options.popupId +'_preloader').remove();
					var content = $('#'+ options.popupId +'_content');			
					if (cw != 0) $(content).css('width',cw);
					if (ch != 0) $(content).css('height',ch);					
					
					if($(content).width() > ( $(window).width() - 50 ) ) { $(content).css( 'width', $(window).width() - 50 ) }
					if($(content).height() > ( $(window).height() - 50 ) ) { $(content).css( 'height', $(window).height() - 50 ) }
										
					set($('#'+ options.popupId + '_content'));
					$('#'+ options.popupId + '_content').css('visibility','visible');
				};
				
				var set = function(obj){
					$(obj).css({
					 'text-align':'left',
					 'float':'left',
					 'position':'fixed',
					 'z-index':'10001',
					 'visible':'hidden'});
					var left = w2 - $(obj).width()/2;
					var top = h2 - $(obj).height()/2;
					$(obj).css({'left':left,'top':top,'display':'none'}).fadeIn('1000');
					if(ie6) $(obj).css({'position':'absolute','top':(top + $(window).scrollTop()) + 'px'});
					if(opera95) $(obj).css({'position':'absolute','top':(document.body['clientHeight']/2 - $(obj).height()/2 + $(window).scrollTop() ) + 'px'});
					$('.caption',obj).css({
						'width': $(obj).width()+'px',
						'display':'block'
					});
					if(ie6) $('embed, object, select').css('visibility', 'hidden');
				};

				var error = function(){
					$('#'+ options.popupId + '_content').text(options.errorText);
					show();
				};
				
				var remove = function(){
					if (!DOMobjvisible) $(DOMobj).addClass(options.hiddenClass).appendTo(DOMobjparent);
					$('#'+ options.popupId).remove();
					$('#'+ options.popupId + '_content').remove();
					$('#'+ options.popupId +'_preloader').remove();
					if(ie6) $('embed, object, select').css('visibility', 'visible');
					options.callback();
				};
		 
				if(tagName != 'a'){
					remove();
					init();
					// show element
					DOMobj = this;
					DOMobjvisible = $(DOMobj).is(":visible");
					DOMobjparent = $(DOMobj).parent();
					if(DOMobjvisible) DOMobj = $(DOMobj).clone();
					$(DOMobj).removeClass(options.hiddenClass).appendTo('#'+ options.popupId +'_inner').show();											
					show();
				}else{	
					// on anchor click
					$(this).bind('click',function(e){
						e.preventDefault();
															
						remove();
						
						init();
						
						var href = $(this).attr('href');
						var extension = href.substr(href.lastIndexOf('.')).toLowerCase();
						var content;
				
						// FLASH
						if($(this).hasClass('flash')){
							var flash = '<object width="100%" height="100%"><param name="allowfullscreen" value="true" /><param name="allowscriptaccess" value="always" /><param name="movie" value="'+ href +'" /><embed src="'+ href +'" type="application/x-shockwave-flash" allowfullscreen="true" allowscriptaccess="always" width="100%" height="100%"></embed></object>';
							$(flash).appendTo('#'+ options.popupId +'_inner');
							cw=600;ch=400;
							showOk = true;
						} else {
							// IMAGE
							if(extension == '.jpg' || extension == '.jpeg' || extension == '.gif' || extension == '.png' || extension == '.bmp'){								
								var img = new Image();
								$(img)
									.error(function(){ error(); })								
									.appendTo('#'+ options.popupId +'_inner');	
								img.onload = function(){ 									
									show();
									img.onload=function(){};
								};	
								img.src = href + '?' + (new Date()).getTime() + ' =' + (new Date()).getTime();								
							} 
							// DOM ELEMENT
							else if(href.charAt(0) == '#') {
								DOMobj = $(href).get(0);
								DOMobjvisible = $(DOMobj).is(":visible");
								DOMobjparent = $(DOMobj).parent();
								if(DOMobjvisible) DOMobj = $(DOMobj).clone();
								$(DOMobj).removeClass(options.hiddenClass).appendTo('#'+ options.popupId +'_inner').show();	
								showOk = true;
							} 
							// URL 
							else {
								$('<iframe frameborder="0" scrolling="auto" style="width:100%;height:100%" src="'+ href +'" />').appendTo('#'+ options.popupId +'_inner');
								cw=900;ch=500;
								showOk = true;
							};			
						};
									
						var rel = $(this).attr('rel').split(';');
						$.each(rel,function(i){
							if (rel[i].indexOf('width') != -1) cw = rel[i].split(':')[1];
							if (rel[i].indexOf('height') != -1) ch = rel[i].split(':')[1];
						});	
						
						// caption
						if($(this).attr('title') != '' ){
							$('<span class="caption">'+ $(this).attr('title') +'</span>').appendTo('#'+ options.popupId +'_inner').hide()
						};
						
						if(showOk) show(cw,ch);	
						
						// gallery navigation
						if($(this).hasClass('gallery')){
							var arr = $.data(this,'gallery');arr = eval(arr + '_arr');
							var index = $.data(this,'index');
							if(arr.length > 1){
							$('<small id="'+ options.popupId +'_counter">'+ (index+1) + '/'+ arr.length +'</small>').appendTo('#'+ options.popupId +'_inner');
							$('<small id="'+ options.popupId +'_gallery"></small>').appendTo('#'+ options.popupId +'_inner');						
								if(index != 0){
									$('<span id="'+ options.popupId +'_prev">'+ options.prevText +'</span>').appendTo('#'+ options.popupId +'_gallery')
									.click(function(){
										remove();
										var obj = arr[index-1];$(obj).trigger('click');
									})
								}	
								if(index < arr.length-1){
									$('<span id="'+ options.popupId +'_next">'+ options.nextText +'</span>').appendTo('#'+ options.popupId +'_gallery')
									.click(function(){
										remove();
										var obj = arr[index+1];$(obj).trigger('click');
									})
								}						
							}
	
						};
						
					});
				};				
			});	
		},
		
		// external
		// opens external links in new window
		external: function(options) {
			
			var defaults = {  
				selector: 'a'
			};  
			
			if(typeof options == 'string') defaults.selector = options;
			var options = $.extend(defaults, options); 
			
			var hostname = window.location.hostname;
			hostname = hostname.replace('www.','').toLowerCase();
			
			return $(options.selector).each(function(){
			
				var href = $(this).attr('href').toLowerCase();
				if(href.indexOf('http://')!=-1 && href.indexOf(hostname)==-1){			
					$(this).attr('target','_blank');
					$(this).addClass('external');
				};
			
			});			
		},
	
		
		// rotate
		// simple content rotation
		rotate: function(options) {
			
			var defaults = {  
				selector: '.rotate',
				initPause: 0,
				pause: 5000,
				randomize: false ,
				callback: function(){}
			};  
			
			if(typeof options == 'string') defaults.selector = options;
			var options = $.extend(defaults, options); 
			return $(options.selector).each(function(){
						
				var obj = $(this);								
				var length = $(obj).children().length; 
				var temp = 0;		
							
				function getRan(){
					var ran = Math.floor(Math.random()*length) + 1;
					return ran;
				};
				
				function show(){	
					if (options.randomize){
						var ran = getRan();
							while (ran == temp){
							ran = getRan();
						}; 
						temp = ran;		
					} else {
						temp = (temp == length) ? 1 : temp+1 ; 
					};
					$(obj).children().hide();	
					$(obj).children(':nth-child('+ temp +')').fadeIn('slow',function(){ options.callback(); });	
				};
				
				function init(){
					show(); 
					setInterval(show,options.pause);		
				};
				
				if (length > 1) setTimeout(init,options.initPause);		
			
			});			
		},
		
	
		// cycle
		// simple content rotation
		cycle: function(options) {
			
			var defaults = {  
				selector: '.cycle',
				effect: 'fade',
				initPause: 0,
				pause: 5000,
				callback: function(){}
			};  
			
			if(typeof options == 'string') defaults.selector = options;
			var options = $.extend(defaults, options); 
			return $(options.selector).each(function(){
						
				var obj = $(this);								
				var length = $(obj).children().length;
				var temp = 0;
				var prev = -1;
				var z = 1;
				var h = $(obj).children(':nth-child(1)').height();
				var w = $(obj).children(':nth-child(1)').width();
				
				var position = ( $(obj).css('position') == 'absolute' ) ? 'absolute' : 'relative';
				$(obj).css({'position':position,'overflow':'hidden'}).height(h).width(w);
				
				$(obj).children()
					.hide()
					.css({
						'position':'absolute',
						'top':'0',
						'left':'0'
						});

				function show(){	
					temp = (temp == length) ? 1 : temp+1; 
					prev = (temp == 1) ? length : temp-1; 
					tempObj = $(obj).children(':nth-child(' + temp + ')');
					prevObj = $(obj).children(':nth-child(' + prev + ')');
					if(options.effect == 'slideUp'){
						$(prevObj).animate({top:h*(-1)},function(){ 
							$(prevObj).hide(); 
							$(tempObj)
							.css({'z-index':z,'top':h})
							.show()
							.animate({top:0});	
						});						
					} else { // fade in
						$(tempObj).css('z-index',z).fadeIn('slow',function(){
							$(prevObj).fadeOut('slow',function(){ options.callback(); });	
						});
					}
					z++;
				};
				
				function init(){
					show(); 
					setInterval(show,options.pause);		
				};
				
				setTimeout(init,options.initPause);			
			
			});			
		},	
		
		// jump
		// scroll function
		jump: function(options) {
			
			var defaults = {  
				selector: 'a.jump',
				speed: 500
			};  
			
			if(typeof options == 'string') defaults.selector = options;
			var options = $.extend(defaults, options); 
			return $(options.selector).click(function(){
																		
				var target = $($(this).attr('href'));
				var offset = $(target).offset().top;
				$('html,body').animate({scrollTop: offset}, options.speed, 'linear');
				
			});			
		},
		
	
		// showhide
		// simple way to show and hide elements
		showhide: function(options) {
		
			var defaults = {  
				selector: '.toggle'
			};  
			
			if(typeof options == 'string') defaults.selector = options;
			var options = $.extend(defaults, options); 
			return $(options.selector).each(function(){
					var target;
					if( $(this).hasClass('prev') ){
						target = $(this).prev().hide();
					} else if ( $(this).hasClass('id') ){
						// this applies only on anchors with #id in href
						target = $(this).attr('href');
						target = $(target).hide();
					} else {
						target = $(this).next().hide();
					};
					$(this).css('cursor','pointer');
					$(this).toggle(
						function(){
							$(this).addClass('expanded');
							$(target).slideDown();
						},
						function(){
							$(target).slideUp();
							$(this).removeClass('expanded');
						}
					);
					
			});			
		},
		
		
		// forms
		// form validation
		forms: function(options) {
			
			var defaults = {  
				selector: 'form',
				err: 'This is required',
				errEmail: 'Valid email address is required',
				errUrl: 'URL is required',
				errPhone: 'Phone number is required',
				notValidClass: 'notvalid',
				validCallback: function(obj){},
				notValidCallback: function(obj){},
				ajax: false,
				ajaxParams: {}
			};  
			
			function check(obj){
				if ($(obj).val() == '' || checkLabel(obj)){
					var errormsg = ( $(obj).attr('title') != '' ) ? $(obj).attr('title') : options.err;
					error(obj,errormsg);
				};
			};
			
			function checkCompare(source,target){
				if ($(source).val() != $(target).val()){
					var errormsg = ( $(source).attr('title') != '' ) ? $(source).attr('title') : options.err;
					error(source,errormsg);
				};
			};
			
			function checkRegEx(obj,type){
				var regEx,err;
				switch(type){
				case 'url':
					regEx = /(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/;
					err = options.errUrl;
					break;
				case 'phone':
					var regEx = /[\d\s_-]/;
					err = options.errPhone;
					break;
				default:
					regEx = /^[^@]+@[^@]+.[a-z]{2,}$/;
					err = options.errEmail;
				};
				var val = $(obj).val();
				if (val.search(regEx) == -1 || checkLabel(obj)){
					var errormsg = ( $(obj).attr('title') != '' ) ? $(obj).attr('title') : err;
					error(obj,errormsg);
				};
			};	
			
			function checkPassword(obj){
				var classNames = $(obj).attr('class');
				var passwords = $(':password[class="'+ classNames +'"], :password[class="'+ classNames +' '+ options.notValidClass +'"]');
				var index = $(passwords).index(obj);
				if(index != 0){
					return checkCompare(obj,$(passwords).get(0));
				} else {
					return check(obj);
				};
			};	
			
			function checkLabel(obj){
				var text = $('label[for='+$(obj).attr('id')+']').text();
				return (text == $(obj).val());
			};	
			
			function error(obj,errormsg){
				var parent = $(obj).parent();
				parent.append('<span class="error">'+ errormsg +'</span>');
				$('span.error',parent).hide().fadeIn('fast');
				$(obj).addClass(options.notValidClass);
				valid = false;				
			};
			
			$('input.label,textarea.label').each(function(){
				var text = $('label[for='+$(this).attr('id')+']').text();
				$('label[for='+$(this).attr('id')+']').css('display','none');
				$(this).val(text);
				$(this).focus(function(){ if($(this).val()==text) $(this).val(''); });
				$(this).blur(function(){ if($(this).val()=='') $(this).val(text); });											
			});			
			
			if(typeof options == 'string') defaults.selector = options;
			var options = $.extend(defaults, options); 
			
			return $(options.selector).each(function(){
				var form = this;
				$(form).submit(function(){
					
					$('.error',form).remove();
					$('.' + options.notValidClass,form).removeClass(options.notValidClass);
					valid = true;
					
					$(':text.required',form).each(function(){														
						if( $(this).hasClass('email') ){
							checkRegEx(this,'email');
						} else if ( $(this).hasClass('url') ){
							checkRegEx(this,'url');
						} else if ( $(this).hasClass('phone') ){
							checkRegEx(this,'phone');
						} else {
							check(this);
						};	
					});
					$(':password.required',form).each(function(){														
						checkPassword(this);	
					});
					$('textarea.required',form).each(function(){														
						check(this);	
					});
					$(':checkbox.required',form).each(function(){														
						if (!$(this).attr('checked')){
							var errormsg = ( $(this).attr('title') != '' ) ? $(this).attr('title') : options.err;
							error(this,errormsg);
						}
					});		
					if(valid){
						$('.label',form).each(function(){														
							if (checkLabel(this)) $(this).val('');	
						});
					};
	
					//callback
					if (valid) {
						options.validCallback();
					} else {							
						options.notValidCallback();
					};
					
					// check for AJAX 
					if(options.ajax){
						// if data parameter is not defined then fetch all form data
						if(options.ajaxParams.data == undefined) options.ajaxParams.data = values(form);
						if(valid) $.ajax( options.ajaxParams )
						return false;
					} else {
						// if no AJAX is used then submit form						
						return valid;	
					}
						
				});	
			
			});			
		},
	
		// accordion
		accordion: function(options) {
			
			var defaults = {  
				selector: '.accordion',
				parent: 'li',
				source: 'h3',
				target: 'p'
			};  
			
			if(typeof options == 'string') defaults.selector = options;
			var options = $.extend(defaults, options); 
			return $(options.selector).each(function(){
										
				var obj = this;	
	
				$(options.parent,this).each(function(){
					var target = $(options.target,this);
					$(options.target,this).hide();
					$(options.source,this).css({'cursor':'pointer'}).click(function(){
						$(options.target,options.selector).slideUp();
						if( !$(target).is(':visible') ) $(target).slideDown();
					});
					
				});
		
			});			
		},	
	
		// tabs
		tabs: function(options) {
			
			var defaults = {  
				selector: '.tabs',
				selectedClass: 'selected'
			};  
			
			if(typeof options == 'string') defaults.selector = options;
			var options = $.extend(defaults, options); 
			return $(options.selector).each(function(){
										
				var obj = this;	
				var targets = Array();
	
				function show(i){
					$.each(targets,function(index,value){
						$(value).hide();
					})
					$(targets[i]).fadeIn();
					$(obj).children().removeClass(options.selectedClass);
					selected = $(obj).children().get(i);
					$(selected).addClass(options.selectedClass);
				};
	
				$('a',this).each(function(i){	
					targets.push($(this).attr('href'));
					$(this).click(function(e){
						e.preventDefault();
						show(i);
					});
				});
				
				show(0);
	
			});			
		}
		
	};
		
})(jQuery);  