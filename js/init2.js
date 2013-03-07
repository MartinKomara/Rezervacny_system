$(document).ready(function(){

	$("#form").validate({
		rules: {
			nazov: {
				required: true
			},
			miesto: {
				required: true
			},
			krajina: {
				required: true
			},
			datum: {
				required: true
			}
		},
		messages: {
			nazov: {
				required: 'Zadajte názov'
			},
			miesto: {
				required: 'Zadajte miesto'
			},
			krajina: {
				required: 'Zadajte krajinu'
			}
		},
		success: function(label) {
			label.html('OK').removeClass('error').addClass('ok');
			setTimeout(function(){
				label.fadeOut(500);
			}, 2000)
		}
	});
	
});
