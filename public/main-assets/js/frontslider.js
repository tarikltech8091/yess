
(function($){
    Drupal.behaviors.frontSlider = {
		attach : function(context,settings){
			$(function() {
        $('.ei-slider').eislideshow({


					animation			: 'center',

					easing				: 'easeOutExpo',

					titleeasing			: 'easeOutExpo',

					autoplay			: true,

					speed				: 1000,

					slideshow_interval	: 30000,

					titlespeed			: 1000



				});

        $('.ei-slider-thumbs li a').hover(function(){

        	 $(this).next('img').stop().animate({opacity: 1,'bottom' : '11'}, 400);

        },

        function(){$(this).next('img').stop().animate({opacity: 0, 'bottom' : '25'}, 300);

        });

        $('.ei-slider').fadeIn(500);
				
      });
      
		}
    }
}(jQuery));






