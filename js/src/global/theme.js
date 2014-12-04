$ = require('jquery');

 /**
	 *
	 * Allow the user to close the
	 * video window by clicking any
	 * on the overlay
	 * @params void
	 * @returns void
	 *
	 */
	$('.modal').click(function(e){
		 
		if (e.target !== this) return;
		 
		$(this).removeClass('visible');
		 
		$iframe = $(this).find('iframe');
		 
		if ( $iframe.length > 0 ){
			$iframe[0].contentWindow.postMessage('{"event":"command","func":"pauseVideo","args":""}', '*');
		}
		 
	});
	
	/**
	 *
	 * Targets ie browsers
	 * Used only for minor styling changes
	 *
	 */
	var isMSIE = /*@cc_on!@*/1;
	
	if (!isMSIE) {
			$('html').addClass('ie');
	}
	
	/**
	 *
	 * Simple fade carousel
	 *
	 */
	this.delayedStart = false;
	 
	var carousel = function(){
	
		if (this.delayedStart){
		 
			var $carousel = $('.carousel');
			
			$carousel.each(function(){
				
				var $visible = $(this).find('.shown');
				
				$(this).find('.item').removeClass('shown');
				
				$visible.next().addClass('shown');
				
				if ($visible.next().length == 0) $(this).find('.item').first().addClass('shown');
				
			 });
			
		}
		 
		this.delayedStart = true;
		 
		setTimeout(carousel, 3000);
	 }
	 
	carousel();