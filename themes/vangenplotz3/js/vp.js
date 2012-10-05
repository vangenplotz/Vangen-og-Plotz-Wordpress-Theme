(function($) {
	var breakPointEm = 50,
		isMobile = true,
		htmlDocument = $('html'),
		orbitOptions = {
		     animation: 'horizontal-push',                  // fade, horizontal-slide, vertical-slide, horizontal-push
		     animationSpeed: 800,                // how fast animtions are
		     timer: false, 			 // true or false to have the timer
		     advanceSpeed: 4000, 		 // if timer is enabled, time between transitions 
		     pauseOnHover: false, 		 // if you hover pauses the slider
		     startClockOnMouseOut: false, 	 // if clock should start on MouseOut
		     startClockOnMouseOutAfter: 1000, 	 // how long after MouseOut should the timer start again
		     directionalNav: true, 		 // manual advancing directional navs
		     captions: true, 			 // do you want captions?
		     captionAnimation: 'fade', 		 // fade, slideOpen, none
		     captionAnimationSpeed: 800, 	 // if so how quickly should they animate in
		     bullets: true,			 // true or false to activate the bullet navigation
		     bulletThumbs: false,		 // thumbnails for the bullets
		     bulletThumbLocation: '',		 // location from this file where thumbs will be
		     afterSlideChange: function(){} 	 // empty function 
		};
	$(window).load(function() {
		var id, hammer, hammerProjects,
			orbitInside = $( '#orbit-inside' ).get(0),
			orbitInsideProjects;
		$('#orbit-inside-projects').orbit(orbitOptions);
		orbitInsideProjects = $( '#orbit-inside-projects' ).get(0);
		
		moveSliderElementsOffCanvas( '#orbit-inside-projects .content' );
		moveSliderElementsOffCanvas( '#orbit-inside > img, #orbit-inside > a, #orbit-inside > div');
		
		if( typeof( orbitInside ) !== 'undefined' ) {
			hammer = new Hammer( orbitInside );
		}
		if( typeof( orbitInsideProjects ) !== 'undefined' ) {
			hammerProject = new Hammer( orbitInsideProjects );
		}
		
		$( '#orbit-inside, #orbit-inside-projects' ).live( 'touchmove', function( event ) {
			event.stopImmediatePropagation();
		});
		
		// Disable the dragging of images on desktop browsers
		// This will interfere with slide images when swiping to navigate
	    $( ".orbit-slide" ).bind( "dragstart", function() { 
	        return false; 
	    });

		// Catch drag-event and connect to slider navigation
	    // Ondragend we will move to the next/prev slide if dragged at least 100px
		if( typeof( orbitInside ) !== 'undefined' ) {
			hammer.ondragend = function(ev) {
				if( Math.abs( ev.distance ) > 100 ) {
		            if( ev.direction === 'right' ) {
						$(orbitInside).siblings('.slider-nav').children( '.left' ).click();
		            } else if( ev.direction === 'left' ) {
		                $(orbitInside).siblings('.slider-nav').children( '.right' ).click();
		            }
		        }
		    };
		}
		if( typeof( orbitInsideProjects ) !== 'undefined' ) {
			hammerProject.ondragend = function(ev) {
				if( Math.abs( ev.distance ) > 100 ) {
		            if( ev.direction === 'right' ) {
						$(orbitInsideProjects).siblings('.slider-nav').children( '.left' ).click();
		            } else if( ev.direction === 'left' ) {
		                $(orbitInsideProjects).siblings('.slider-nav').children( '.right' ).click();
		            }
		        }
		    };
		}
	});
	
	function moveSliderElementsOffCanvas( element ) {
		var projectsFirstElement = true,
			leftValue = 0;
		
		$( element ).each(function() {
			that = $( this );
			if( !that.hasClass('fluid-placeholder') ) {
				leftValuePercent = leftValue + '%';
				that.css('left', leftValuePercent);
				if(projectsFirstElement) {
					that.css('opacity', 1);
					projectsFirstElement = false;
				}
				else {
					that.css('opacity', 0);
				}
				leftValue += 100;
			}
		});
	}
})(jQuery);