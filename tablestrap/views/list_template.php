<?php
	$this->set_css($this->default_theme_path.'/tablestrap/css/bootstrap.min.css');
	$this->set_css($this->default_theme_path.'/tablestrap/css/bootstrap-theme.min.css');
	$this->set_css($this->default_theme_path.'/tablestrap/css/datatables-bootstrap.min.css');
	$this->set_css($this->default_theme_path.'/tablestrap/css/pnotify.custom.min.css');
	$this->set_css($this->default_theme_path.'/tablestrap/css/datatables.css');
	$this->set_css($this->default_theme_path.'/tablestrap/extras/TableTools/media/css/TableTools.css');
	$this->set_js_lib($this->default_javascript_path.'/'.grocery_CRUD::JQUERY);

    if ($dialog_forms) {
        $this->set_js_lib($this->default_javascript_path.'/jquery_plugins/jquery.noty.js');
        $this->set_js_lib($this->default_javascript_path.'/jquery_plugins/config/jquery.noty.config.js');
        $this->set_js_lib($this->default_javascript_path.'/common/lazyload-min.js');
    }

    $this->set_js_lib($this->default_javascript_path.'/common/list.js');

	$this->set_js_lib($this->default_theme_path.'/tablestrap/js/jquery.dataTables.min.js');
	$this->set_js_lib($this->default_theme_path.'/tablestrap/js/datatables-bootstrap.min.js');
	$this->set_js($this->default_theme_path.'/tablestrap/js/pnotify.custom.min.js');
	$this->set_js($this->default_theme_path.'/tablestrap/js/datatables.js');
	$this->set_js($this->default_theme_path.'/tablestrap/extras/TableTools/media/js/ZeroClipboard.js');
	$this->set_js($this->default_theme_path.'/tablestrap/extras/TableTools/media/js/TableTools.min.js');
?>
<script type='text/javascript'>
	var base_url = '<?php echo base_url();?>';
	var subject = '<?php echo addslashes($subject); ?>';

	var unique_hash = '<?php echo $unique_hash; ?>';

	var displaying_paging_string = "<?php echo str_replace( array('{start}','{end}','{results}'),
		array('_START_', '_END_', '_TOTAL_'),
		$this->l('list_displaying')
	   ); ?>";
	var filtered_from_string 	= "<?php echo str_replace('{total_results}','_MAX_',$this->l('list_filtered_from') ); ?>";
	var show_entries_string 	= "<?php echo str_replace('{paging}','_MENU_',$this->l('list_show_entries') ); ?>";
	var search_string 			= "<?php echo $this->l('list_search'); ?>";
	var list_no_items 			= "<?php echo $this->l('list_no_items'); ?>";
	var list_zero_entries 			= "<?php echo $this->l('list_zero_entries'); ?>";

	var list_loading 			= "<?php echo $this->l('list_loading'); ?>";

	var paging_first 	= "<?php echo $this->l('list_paging_first'); ?>";
	var paging_previous = "<?php echo $this->l('list_paging_previous'); ?>";
	var paging_next 	= "<?php echo $this->l('list_paging_next'); ?>";
	var paging_last 	= "<?php echo $this->l('list_paging_last'); ?>";

	var message_alert_delete = "<?php echo $this->l('alert_delete'); ?>";

	var default_per_page = <?php echo $default_per_page;?>;	
	var export_url = "<?php echo $export_url;?>";
	<?php
	//A work around for method order_by that doesn't work correctly on datatables theme
	//@todo remove PHP logic from the view to the basic library
	$ordering = 0;
	$sorting = 'asc';
	if(!empty($order_by))
	{
		foreach($columns as $num => $column) {
			if($column->field_name == $order_by[0]) {
				$ordering = $num;
				$sorting = isset($order_by[1]) && $order_by[1] == 'asc' || $order_by[1] == 'desc' ? $order_by[1] : $sorting ;
			}
		}
	}
	?>

	var datatables_aaSorting = [[ <?php echo $ordering; ?>, "<?php echo $sorting;?>" ]];

</script>

<style>
	.spacer{		
		margin-top:10px;
	}
</style>

<div class="grocerycrud-container panel panel-default">

	<!-- <div class="loader-three-dots">
		<div class="dot-windmill"></div>
	</div> -->

	<div class="dataTablesContainer">
		<div class="panel-body">
			<?php if(!$unset_add){?>
				<a role="button" class="add_button btn btn-default hidden-xs" href="<?php echo $add_url?>">
					<span class="glyphicon glyphicon-plus"></span>
					<span class="ui-button-text"><?php echo $this->l('list_add'); ?> <?php echo $subject?></span>
				</a>
			<?php }?>

			<div class="pull-right">
				<?php if(!$unset_export){?>
					<a role="button" class="add_button btn btn-default" href="<?php echo $export_url?>">
						<span class="glyphicon glyphicon-save-file"></span>
						<?php echo $this->l('list_export');?>
					</a>
				<?php }?>
				<?php if(!$unset_export){?>
					<a role="button" class="add_button btn btn-default" href="<?php echo $print_url?>">
						<span class="glyphicon glyphicon-print"></span>
						<?php echo $this->l('list_print');?>
					</a>
				<?php }?>
			</div>

			<div id='list-report-error' class='alert alert-danger' role="alert" style="margin-top:10px; display:none;"></div>
			<div id='list-report-success' class="alert alert-success" role="alert"
				style='<?php echo is_null($success_message) ? "display:none;":"display:block;";?> margin-top:10px;'>
				<?php if($success_message !== null){?>
					<p><?php echo $success_message; ?></p>
				<?php } ?>
			</div>
		</div>

		<div class="refresh-holder">
			<?php echo $list_view?>
		</div>
	</div>
</div>