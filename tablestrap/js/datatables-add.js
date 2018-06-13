$(function(){
	// Original GroceryCRUD. I think this is for toggle pull form.
	// I am not using this thought.
	$('.ptogtitle').click(function(){
		if($(this).hasClass('vsble'))
		{
			$(this).removeClass('vsble');
			$('#main-table-box').slideDown("slow");
		}
		else
		{
			$(this).addClass('vsble');
			$('#main-table-box').slideUp("slow");
		}
	});

	var save_and_close = false;
	
	// Original GroceryCRUD. currently do not know what this function do
	var reload_datagrid = function () {
		$('.refresh-data').trigger('click');
	};

	// Original GroceryCRUD. save and close button
	$('#save-and-go-back-button').click(function(){
		save_and_close = true;

		$('#crudForm').trigger('submit');
	});

	// Original GroceryCRUD. I change the notification to pnotify
	$('#crudForm').submit(function(){
		$(this).ajaxSubmit({
			url: validation_url,
			dataType: 'json',
			beforeSend: function(){
				new PNotify(message_loading);
			},
			success: function(data){
				PNotify.removeAll();
				if(data.success){
					$('#crudForm').ajaxSubmit({
						dataType: 'text',
						cache: 'false',
						beforeSend: function(){
							new PNotify(message_loading);
						},
						success: function(result){
							PNotify.removeAll();
							data = $.parseJSON( result );
							if(data.success){
								if(save_and_close){
									if ($('#save-and-go-back-button').closest('.ui-dialog').length === 0) {
										window.location = data.success_list_url;
									} else {
										$(".ui-dialog-content").dialog("close");
										new PNotify({
											title: 'Success!',
											text: data.success_message,
											type: 'success'
										});
									}

									return true;
								}

								$('.field_error').removeClass('field_error');
								new PNotify({
									title: 'Success!',
									text: data.success_message,
									type: 'success'
								});

								clearForm();
							} else {
								new PNotify({
									title: 'Oh No!',
									text: message_insert_error,
									type: 'error'
								});
							}
						}
					});
				} else {
					$('.field_error').removeClass('field_error');
					new PNotify({
						title: 'Oh No!',
						text: data.error_message,
						type: 'error'
					});

					$.each(data.error_fields, function(index,value){
						$('#crudForm input[name='+index+']').addClass('field_error');
					});
				}
			}
		});

		return false;
	});

	// Original GroceryCRUD. Currently do not know what for, I think for changing button look ?
	$('.ui-input-button').button();
	$('.gotoListButton').button({
		icons: {
			primary: "ui-icon-triangle-1-w"
		}
	});

	// Original GroceryCRUD. For cancel button. I change confirm message to pnotify confirm
	if( $('#cancel-button').closest('.ui-dialog').length === 0 ) {
		$('#cancel-button').click(function(){
			notice = new PNotify({
				title: 'Confirmation Needed',
				text: message_alert_add_form,
				icon: 'glyphicon glyphicon-question-sign',
				hide: false,
				confirm: {
					confirm: true
				},
				buttons: {
					closer: false,
					sticker: false
				},
				history: {
					history: false
				},
				addclass: 'stack-modal',
				stack: {'dir1': 'down', 'dir2': 'right', 'modal': true}
			});

			notice.get().on('pnotify.confirm', function() {
				window.location = list_url;
			});

			return false;
		});

	}
});

// Original GroceryCrud. Clearing Form Field.
function clearForm(){
	$('#crudForm').find(':input').each(function() {
		switch(this.type) {
			case 'password':
			case 'select-multiple':
			case 'select-one':
			case 'text':
			case 'textarea':
				$(this).val('');
				break;

			case 'checkbox':
			case 'radio':
				this.checked = false;
				break;
		}
	});

	/* Clear upload inputs  */
	$('.open-file,.gc-file-upload,.hidden-upload-input').each(function(){
		$(this).val('');
	});

	$('.upload-success-url').hide();
	$('.fileinput-button').fadeIn("normal");
	/* -------------------- */

	$('.remove-all').each(function(){
		$(this).trigger('click');
	});

	// Original GroceryCRUD was using $(this).trigger("liszt:updated");. I found that they used JQuery Chosen.
	// so I changed this event from "liszt:updated" to "chosen:updated". it is work better for me.
	$('.chosen-multiple-select, .chosen-select, .ajax-chosen-select').each(function(){
		$(this).trigger("chosen:updated");
	});

}