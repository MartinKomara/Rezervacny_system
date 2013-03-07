$(function(){
	
	$("#form").validate({
		rules: {
			nick: {
				required: true
			},
			priezvisko: {
				required: false
			},
			meno: {
				required: false
			},
			heslo: {
				required: true,
				minlength: 6
			},
			e_mail: {
				required: true,
				email: true
			}
		},
		messages: {
			heslo: {
				required: 'Zadajte heslo',
				minlength: 'Heslo musí mať aspoň 6 znakov'
			},
			nick: {
				required: 'Zadajte prezývku'
			},
			e_mail: 'Zadajte správny tvar e-mailu',
			
		},
		success: function(label) {
			label.html('OK').removeClass('error').addClass('ok');
			setTimeout(function(){
				label.fadeOut(500);
			}, 2000)
		}
	});
	
});
