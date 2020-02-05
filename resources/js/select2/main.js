require('./js/select2')

$(document).ready(function() {
	$('.select2').select2({
		placeholder: "Selecciona los consultores"
	})
	$('.select2').removeAttr('style')
})