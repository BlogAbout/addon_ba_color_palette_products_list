jQuery(function($) {
	$('.jshop .ba_color_palette_dots .dot_link').hover(
		function() {
			$(this).find('.dot_info').fadeIn();
		}, function() {
			$(this).find('.dot_info').fadeOut();
		}
	);
	
	$('.jshop .ba_color_palette').each(function() {
		$(this).find('.color_palette_image').css('opacity', 0);
		$(this).find('.color_palette_image:first').css('opacity', 1);
	});
	
	if (color_palette_change_type == 1) {
		color_palette_event = 'mouseover';
	} else {
		color_palette_event = 'click';
	}
	
	$(document)
		.on('click', '.jshop .ba_color_palette_dots .dot_link', function(e) {
			e.preventDefault();
		})
		.on(color_palette_event, '.jshop .ba_color_palette_dots .dot_link', function(e) {
			
			if (!$(this).hasClass('active')) {
				$(this).siblings('.dot_link').removeClass('active');
				$(this).addClass('active');
				var page = $(this).attr('data-page');
				var current_element = $(this).parents('.ba_color_palette_dots').siblings('.ba_color_palette');
				var next_element = $(this).parents('.ba_color_palette_dots').siblings('.ba_color_palette').find('.color_palette_image[data-page="' + page + '"]');
				morePhotosFade(current_element, next_element);
			}
		});
	
	function morePhotosFade(elem, nextElement) {
		var current;
		if ($(elem).find('.color_palette_image').length > 1) {
			if ($(elem).find('.color_palette_image.show')) {
				current = $(elem).find('.color_palette_image.show');
			} else {
				current = $(elem).find('.color_palette_image:first');
			}
			
			nextElement.css('opacity', 0)
				.addClass('show')
				.animate({opacity: 1.0}, 500);
			
			current.animate({opacity: 0.0}, 500)
				.removeClass('show');
		}
	}
});