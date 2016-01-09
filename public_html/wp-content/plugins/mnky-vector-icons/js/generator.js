jQuery(document).ready(function($) {

	// Custom popup box
	$("#mnky-generator-button").click(function(){
		$("#mnky-generator-wrap, #mnky-generator-overlay").show();
	});
	
	$("#mnky-generator-close").click(function(){
		$("#mnky-generator-wrap, #mnky-generator-overlay").hide();
	});
	
	// Icon pack select
	$('#mnky-generator-select-pack').change(function() {
		if($(this).val() == 'fontawesome-icons-list') {
			$('ul.fontawesome-icon-list').show();
			$('ul.zocial-icon-list, ul.loop-icon-list, ul.moon-icon-list').hide();
		} 
		if($(this).val() == 'zocial-icons-list'){
			$('ul.zocial-icon-list').show();
			$('ul.fontawesome-icon-list, ul.loop-icon-list, ul.moon-icon-list').hide();
		}
		if($(this).val() == 'loop-icons-list'){
			$('ul.loop-icon-list').show();
			$('ul.zocial-icon-list, ul.fontawesome-icon-list, ul.moon-icon-list').hide();
		}
		if($(this).val() == 'moon-icons-list'){
			$('ul.moon-icon-list').show();
			$('ul.zocial-icon-list, ul.fontawesome-icon-list, ul.loop-icon-list').hide();
		}
	});
	
	// Font size
    var aFontsSizeArray = new Array('12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '24', '26', '28', '30', '33', '36', '39', '42', '45', '48', '51', '54', '59', '65', '70', '75', '80', '85', '90', '95', '110', '130', '150', '160', '170', '180', '190', '200', '210', '220', '230');
   
   $('#mnky-generator-size-slider').slider({
        value: 4,
        min: 0,
        max: 41,
        step: 1,
        slide: function(event, ui) {
            var sFontSizeArray = aFontsSizeArray[ui.value];
            $('#mnky-generator-icon-size').val(sFontSizeArray + 'px');
        }
    });
    $('#mnky-generator-icon-size').val((aFontsSizeArray[$('#mnky-generator-size-slider').slider('value')]) + 'px');
		
	// Color pickers
	$('.mnky-generator-select-color').each(function(index) {
		$(this).find('.mnky-generator-select-color-wheel').filter(':first').farbtastic('.mnky-generator-select-color-value:eq(' + index + ')');
		$(this).find('.mnky-generator-select-color-value').focus(function() {
			$('.mnky-generator-select-color-wheel:eq(' + index + ')').show();
		});
		$(this).find('.mnky-generator-select-color-value').blur(function() {
			$('.mnky-generator-select-color-wheel:eq(' + index + ')').hide();
		});
	});
		
	// Get HTML code
	$('#mnky-generator-show-html').click( function() { 
		var icon_name = $('.mnky-generator-icon-select input:checked').val();
		var icon_color = $('.mnky-generator-select-color-value').val();
		var icon_size = $('#mnky-generator-icon-size').val();
		if ($('.mnky-generator-select-style').val() !== '' ) { 
			var icon_style = ' ' + $('.mnky-generator-select-style').val(); 
		} else {
			var icon_style = '';
		}
		if ($('.mnky-generator-select-hover').val() !== '' ) {
			var icon_hover = ' hover-' + $('.mnky-generator-select-hover').val();
		} else {
			var icon_hover = '';
		}
		$('#mnky-shortcode-html').show(); 
		$('#mnky-shortcode-html textarea').text('<i class="' + icon_name + icon_style + icon_hover + '" style="color:' + icon_color  + '; font-size:' + icon_size + ';"></i>'); 
	});
	
	$("#mnky-generator-close-html").click(function(){
		$("#mnky-shortcode-html").hide();
	});

	// Insert shortcode
	$('#mnky-generator-insert').live('click', function(event) {
		$('.mnky-generator-icon-select input:checked').addClass("mnky-generator-attr");
		$('.mnky-generator-icon-select input:not(:checked)').removeClass("mnky-generator-attr");
		
		var queried_shortcode = 'icon';
		var su_compatibility_mode_prefix = $('#mnky-compatibility-mode').val();
		$('#mnky-generator-result').val('[' + su_compatibility_mode_prefix + queried_shortcode);
		$('.mnky-generator-attr').each(function() {
			if ( $(this).val() !== '' ) {
				$('#mnky-generator-result').val( $('#mnky-generator-result').val() + ' ' + $(this).attr('name') + '="' + $(this).val() + '"' );
			}
		});
		$('#mnky-generator-result').val($('#mnky-generator-result').val() + ']');

		var shortcode = jQuery('#mnky-generator-result').val();

		// Insert into widget
		if ( typeof window.su_generator_target !== 'undefined' ) {
			jQuery('textarea#' + window.su_generator_target).val( jQuery('textarea#' + window.su_generator_target).val() + shortcode);
			tb_remove();
		}

		// Insert into editor
		else {
			window.send_to_editor(shortcode);
		}
		
		$("#mnky-generator-wrap, #mnky-generator-overlay").hide();

		// Prevent default action
		event.preventDefault();
		return false;
	});
});