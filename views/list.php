<table cellpadding="0" cellspacing="0" border="0" class="display groceryCrudTable table table-striped" id="<?php echo uniqid(); ?>">
	<thead>
		<tr>
			<?php foreach($columns as $column){?>
				<th><?php echo $column->display_as; ?></th>
			<?php }?>
			<?php if(!$unset_delete || !$unset_edit || !$unset_read || !empty($actions)){?>
			<th class='actions'><?php echo $this->l('list_actions'); ?></th>
			<?php }?>
		</tr>
	</thead>
	<tbody>
		<?php foreach($list as $num_row => $row){ ?>
		<tr id='row-<?php echo $num_row?>'>
			<?php foreach($columns as $column){?>
				<td><?php echo $row->{$column->field_name}?></td>
			<?php }?>
			<?php if(!$unset_delete || !$unset_edit || !$unset_read || !empty($actions)){?>
			<td class='actions'>
				<?php if(!empty($row->action_urls)){
					foreach($row->action_urls as $action_unique_id => $action_url){
						$action = $actions[$action_unique_id]; ?>
						<a href="<?php echo $action_url; ?>" class="edit_button btn btn-xs btn-info" role="button">
							<span class="glyphicon glyphicon-<?php echo $action->css_class; ?> <?php echo $action_unique_id;?>"></span>
							<?php echo $action->label?>
						</a>
					<?php }
				} ?>

				<?php if(!$unset_read){?>
					<a href="<?php echo $row->read_url?>" class="edit_button btn btn-xs btn-info" role="button">
						<span class="glyphicon glyphicon-info-sign"></span>
						<?php echo $this->l('list_view');?>
					</a>
				<?php }?>

                <?php if(!$unset_clone){?>
                    <a href="<?php echo $row->clone_url?>" class="edit_button btn btn-xs btn-info hidden-xs" role="button">
                        <span class="glyphicon glyphicon-duplicate"></span>
                        <?php echo $this->l('list_clone'); ?>
                    </a>
                <?php }?>

				<?php if(!$unset_edit){?>
					<a href="<?php echo $row->edit_url?>" class="edit_button btn btn-xs btn-info hidden-xs" role="button">
						<span class="glyphicon glyphicon-pencil"></span>
						<?php echo $this->l('list_edit'); ?>
					</a>
				<?php }?>

				<?php if(!$unset_delete){?>
					<a onclick = "javascript: return delete_row('<?php echo $row->delete_url?>', '<?php echo $num_row?>')"
						href="javascript:void(0)" class="delete_button btn btn-xs btn-danger hidden-xs" role="button">
						<span class="glyphicon glyphicon-trash"></span>
						<?php echo $this->l('list_delete'); ?>
					</a>
				<?php }?>
			</td>
			<?php }?>
		</tr>
		<?php }?>
	</tbody>
	<tfoot>
		<tr>
			<?php foreach($columns as $column){?>
				<th><input type="text" class="form-control filter-column-input" style="width:100%;" name="<?php echo $column->field_name; ?>" placeholder="<?php echo $this->l('list_search').' '.$column->display_as; ?>" class="search_<?php echo $column->field_name; ?>" /></th>
			<?php }?>
			<?php if(!$unset_delete || !$unset_edit || !$unset_read || !empty($actions)){?>
				<th>
					<button class="btn btn-success refresh-data btn-block" role="button" data-url="<?php echo $ajax_list_url; ?>">
						<span class="glyphicon glyphicon-refresh"></span> Clear Filter
					</button>
				</th>
			<?php }?>
		</tr>
	</tfoot>
</table>
