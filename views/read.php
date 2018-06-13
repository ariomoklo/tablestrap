<?php $this->set_css($this->default_theme_path.'/tablestrap/css/bootstrap.min.css');?>
<?php $this->set_css($this->default_theme_path.'/tablestrap/css/bootstrap-theme.min.css');?>

<div class="panel panel-default">
	<div class="panel-heading">
    	<h3 class="panel-title"><?php echo $this->l('list_record'); ?> <?php echo $subject?></h3>
  	</div>

	<!-- Start of hidden inputs -->
		<?php
			foreach($hidden_fields as $hidden_field){
				echo $hidden_field->input;
			}
		?>
	<!-- End of hidden inputs -->
	<?php if ($is_ajax) { ?><input type="hidden" name="is_ajax" value="true" /><?php }?>
	<div class='line-1px'></div>
	<div id='report-error' class='report-div error'></div>
	<div id='report-success' class='report-div success'></div>

	<div class="table-responsive">
		<table class="table table-striped">
			<?php foreach ($fields as $field){ ?>
				<tr class='form-field-box' id="<?php echo $field->field_name; ?>_field_box">
					<th class='form-display-as-box' style="width:25% !important;" id="<?php echo $field->field_name; ?>_display_as_box">
						<?php echo $input_fields[$field->field_name]->display_as . 
						($input_fields[$field->field_name]->required ? "*" : ""); ?>
					</th>
					<td class='form-input-box' id="<?php echo $field->field_name; ?>_input_box">
						<?php echo $input_fields[$field->field_name]->input?>
					</td>
				</tr>
			<?php } ?>
		</table>
	</div>
	<div class="panel-footer">
    	<a href="<?php echo $list_url?>" class="btn btn-default">
			<?php echo $this->l('form_back_to_list'); ?>
		</a>
  	</div>
</div>