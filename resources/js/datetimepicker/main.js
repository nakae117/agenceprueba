require('./jquery.datetimepicker.full')

$(document).ready(function() {
	$.datetimepicker.setLocale('es')

	$('#datetimepicker').datetimepicker({
		timepicker: false,
		format: 'm/Y',
		formatDate: 'Y/m'
	})
})