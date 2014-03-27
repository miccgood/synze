$(function(){
    $('.datetime-input').datetimepicker({
    	timeFormat: 'hh:mm:ss',
		dateFormat: js_date_format,
		showButtonPanel: true,
		changeMonth: true,
		changeYear: true,
                maskInput: true,           // disables the text input mask
                pickDate: true,            // disables the date picker
                pickTime: true,            // disables de time picker
                pick12HourFormat: false,   // enables the 12-hour format time picker
                pickSeconds: true,         // disables seconds in the time picker
                startDate: -Infinity,      // set a minimum date
                endDate: Infinity  
    });
    
	$('.datetime-input-clear').button();
	
	$('.datetime-input-clear').click(function(){
		$(this).parent().find('.datetime-input').val("");
		return false;
	});	

});