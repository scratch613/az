$(function(){
// On document ready	
	$('#openlogin').click(function(e){
		e.stopPropagation();
		$('.loginfield').addClass('opened');
		$('.loginform').show();
		$('.rememberform').hide();
		$('.closelogin').show();
		
		$('.rememberform .links').show();
		$('#form_remember .error').html('');

	});
	
	$('#rememberpass').click(function(e){
		e.stopPropagation();
		$('.loginform').hide();
		$('.rememberform').show();
	});
	
	$('.closelogin').click(function(e){
		e.stopPropagation();
		$('.loginfield').removeClass('opened');
		$('.loginform').hide();
		$('.rememberform').hide();
		$('.closelogin').hide();
	});
	
	
	$('a.submit').click(function(e){
		e.stopPropagation();
		var form = $(e.currentTarget).parents('form');
		if (form) {
			form.submit();
		}
	});

	$('.remembersubmit').click( function(){
		jQuery.post('/site/remember', $('#form_remember').serialize(), function(data){
			if (data.error) {
				$('#form_remember .error').html(data.error);
			} else {
				$('#form_remember .error').html(data.message);
				$('.rememberform .links').hide();
			}
			
		}, 'json');
		
		
		
		
	});
	
	$('#form_remember').on('submit', function () {
		
		return false;
	})
	


})




