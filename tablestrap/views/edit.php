<?php
	$this->set_css($this->default_theme_path.'/tablestrap/css/bootstrap.min.css');
	$this->set_css($this->default_theme_path.'/tablestrap/css/bootstrap-theme.min.css');
	$this->set_css($this->default_theme_path.'/tablestrap/css/datatables.css');
    $this->set_js_lib($this->default_javascript_path.'/jquery_plugins/jquery.form.min.js');
	$this->set_js_config($this->default_theme_path.'/tablestrap/js/datatables-edit.js');
	$this->set_css($this->default_css_path.'/ui/simple/'.grocery_CRUD::JQUERY_UI_CSS);
	$this->set_js_lib($this->default_javascript_path.'/jquery_plugins/ui/'.grocery_CRUD::JQUERY_UI_JS);

	$this->set_css($this->default_theme_path.'/tablestrap/css/pnotify.custom.min.css');
	$this->set_js($this->default_theme_path.'/tablestrap/js/pnotify.custom.min.js');
?>

<style>
	.form-control{
		max-width: 500px !important;
	}

	.ui-button{
		margin-top: 5px;
	}
</style>

<div class="panel panel-default">
	<div class="panel-heading">
    	<h3 class="panel-title"><?php echo $this->l('list_record'); ?> <?php echo $subject?></h3>
  	</div>

	<?php echo form_open( $update_url, 'method="post" id="crudForm" enctype="multipart/form-data"'); ?>
		<div id='report-error' class='report-div error'></div>
		<div id='report-success' class='report-div success'></div>

		<table class="table table-striped">
			<?php foreach ($fields as $field){ ?>
				<tr id="<?php echo $field->field_name; ?>_field_box">
					<th id="<?php echo $field->field_name; ?>_display_as_box">
						<?php echo $input_fields[$field->field_name]->display_as . 
						($input_fields[$field->field_name]->required ? "*" : ""); ?>
					</th>
					<td id="<?php echo $field->field_name; ?>_input_box">
						<?php echo $input_fields[$field->field_name]->input?>
					</td>
				</tr>
			<?php } ?>
		</table>

		<!-- Start of hidden inputs -->
		<?php
				foreach($hidden_fields as $hidden_field){
					echo $hidden_field->input;
				}
			?>
		<!-- End of hidden inputs -->
		<?php if ($is_ajax) { ?><input type="hidden" name="is_ajax" value="true" /><?php }?>

		<div class="panel-footer">
			<input  id="form-button-save" type='submit' value='<?php echo $this->l('form_update_changes'); ?>' class='btn btn-success'/>
			<?php 	if(!$this->unset_back_to_list) { ?>
				<input type='button' value='<?php echo $this->l('form_update_and_go_back'); ?>' class='btn btn-success' id="save-and-go-back-button"/>
				<input type='button' value='<?php echo $this->l('form_cancel'); ?>' class='btn btn-warning' id="cancel-button" />
			<?php }?>
			<div class='small-loading' id='FormLoading'><?php echo $this->l('form_update_loading'); ?></div>
		</div>
	</form>
</div>	

<script>
	var validation_url = '<?php echo $validation_url?>';
	var list_url = '<?php echo $list_url?>';

	var message_alert_edit_form = "<?php echo $this->l('alert_edit_form')?>";
	var message_update_error = "<?php echo $this->l('update_error')?>";
	var message_loading = "<?php echo $this->l('form_update_loading'); ?>";
</script>