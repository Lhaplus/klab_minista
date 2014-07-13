
/* general dialog */
$(document).on('click', '#close_general_dialog', function(){
	$('#general_dialog').hide();
	$('#overlay_dialog').hide();
});
function open_general_dialog(message){
	$('#general_dialog h2').text(message);
	$('#general_dialog').show();
	allocate_dialog('general_dialog');
	$('#overlay_dialog').show();
}

/* open dialog */
function open_dialog(dialog_id){
	$('#' + dialog_id).show();
	allocate_dialog(dialog_id);
	$('#overlay_dialog').show();
}

function close_dialog(dialog_id){
	$('#' + dialog_id + ' .validation_message').html('');
	$('#' + dialog_id).hide();
	$('#overlay_dialog').hide();
}

// allocate dialog
function allocate_dialog(dialog_id){
	var height = $('#' + dialog_id + ' .dialog_main').height();
	var width  = $('#' + dialog_id + ' .dialog_main').width();
	$('#' + dialog_id + ' .dialog_main').css('margin-top',  -(height) / 2);
	$('#' + dialog_id + ' .dialog_main').css('margin-left', -(width)  / 2);
	$('#' + dialog_id + ' h2').css('width', width + 10);	
}
