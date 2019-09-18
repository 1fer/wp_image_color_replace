jQuery(function($){
	    
	$('#input_color').each( function( i, elem ) {

		window.hueb_1 = new Huebee( elem, {
			notation: 'hex',
			saturations: 2,

		});

	});  
	$('.color-input').each( function( i, elem ) {

		window.hueb = new Huebee( elem, {
			notation: 'hex',
			saturations: 2,

		});

	});
		
	$('body').on('mousemove', '.upload_image_button img', function(e) {
		if (event.shiftKey) {
		if(!this.canvas) {
				this.canvas = $('<canvas />')[0];
				this.canvas.width = this.width;
				this.canvas.height = this.height;
				this.canvas.getContext('2d').drawImage(this, 0, 0, this.width, this.height);
		}
		
		var pixelData = this.canvas.getContext('2d').getImageData(event.offsetX, event.offsetY, 1, 1).data;

		var hex = rgbToHex(pixelData[0], pixelData[1], pixelData[2] );

		window.hueb_1.setColor(hex);
	}
});

function componentToHex(c) {
  var hex = c.toString(16);
  return hex.length == 1 ? "0" + hex : hex;
}

function rgbToHex(r, g, b) {
  return "#" + componentToHex(r) + componentToHex(g) + componentToHex(b);
}
	/*
	 * Select/Upload image(s) event
	 */
	$('body').on('click', '.upload_image_button', function(e){
		e.preventDefault();
 
    		var button = $(this),
    		    custom_uploader = wp.media({
			title: 'Insert image',
			library : {
				// uncomment the next line if you want to attach image to the current post
				// uploadedTo : wp.media.view.settings.post.id, 
				type : 'image'
			},
			button: {
				text: 'Use this image' // button label text
			},
			multiple: false // for multiple image selection set to true
		}).on('select', function() { // it also has "open" and "close" events 
			var attachment = custom_uploader.state().get('selection').first().toJSON();
			$(button).removeClass('button').html('<img class="true_pre_image" src="' + attachment.url + '" style="max-width:95%;display:block;" />').next().val(attachment.id).next().show();
			/* if you sen multiple to true, here is some code for getting the image IDs
			var attachments = frame.state().get('selection'),
			    attachment_ids = new Array(),
			    i = 0;
			attachments.each(function(attachment) {
 				attachment_ids[i] = attachment['id'];
				console.log( attachment );
				i++;
			});
			*/
		})
		.open();
	});
 
	/*
	 * Remove image event
	 */
	$('body').on('click', '.remove_image_button', function(){
		$(this).hide().prev().val('').prev().addClass('button').html('Upload image');
		return false;
	});
 

	$('body').on('click', '#create-images', function(e){
		
		e.preventDefault();

		$.ajax({
			url: icr.url,
			type: 'POST',
			data: 'action=create_image',
			success: function(data) {
				$('#icr-settings .form-table tr:first-child').
					after("<tr class='xk'><th>Result</th><td><img src='"+data+"'></td></tr>");
				$('#icr-settings .form-table tr:first-child').css({
					"margin-right" : "0",
					"width" : "calc(50% - 27,5px)"
				})
			}
		});
		
	});
});


'use strick';

var App = function($){

	let offerAjax = {
			init : function($){ 

			} 
	}

	return {
		init : function($){
			offerAjax.init($);
		}
	}

}

jQuery(document).ready(function($){
	App.init($);
})