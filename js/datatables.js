var default_per_page = typeof default_per_page !== 'undefined' ? default_per_page : 25;
var oTable = null;
var oTableArray = [];
var oTableMapping = [];

// HTML5 Storage. You can ignore this.
function supports_html5_storage()
{
	try {
		JSON.parse("{}");
		return 'localStorage' in window && window['localStorage'] !== null;
	} catch (e) {
		return false;
	}
}

var use_storage = supports_html5_storage();

var aButtons = [];
var mColumns = [];

$(document).ready(function() {

	// extracting column name from th
	$('table.groceryCrudTable thead tr th').each(function(index){
		if(!$(this).hasClass('actions'))
		{
			mColumns[index] = index;
		}
	});

	//For mutliplegrids disable bStateSave as it is causing many problems
	if ($('.groceryCrudTable').length > 1) {
		use_storage = false;
	}

	// For each groceryCrud Table, Create New Datatables
	$('.groceryCrudTable').each(function(index){
		if (typeof oTableArray[index] !== 'undefined') {
			return false;
		}

		// Save table unique id and connect it to table index
		oTableMapping[$(this).attr('id')] = index;
		// Save Datatables Instance paired with table index
		oTableArray[index] = loadDataTable(this);
	});

	loadListenersForDatatables();

	$('a.ui-button').on("mouseover mouseout", function(event) {
		  if ( event.type == "mouseover" ) {
			  $(this).addClass('ui-state-hover');
		  } else {
			  $(this).removeClass('ui-state-hover');
		  }
	});

	$('th.actions').unbind('click');
	$('th.actions>div .DataTables_sort_icon').remove();

} );

function loadListenersForDatatables() {

	// filter function for each column
	$(".filter-column-input").keyup( function () {

		chosen_table = datatables_get_chosen_table($(this).closest('.groceryCrudTable'));
		chosen_table.fnFilter( this.value, chosen_table.find("tfoot input").index(this) );

		if(use_storage)
		{
			var search_values_array = [];

			chosen_table.find("tfoot tr th").each(function(index,value){
				search_values_array[index] = $(this).children(':first').val();
			});

			localStorage.setItem( 'datatables_search_'+ unique_hash ,'["' + search_values_array.join('","') + '"]');
		}
	} );

	var search_values = localStorage.getItem('datatables_search_'+ unique_hash);

	if( search_values !== null)
	{
		$.each($.parseJSON(search_values),function(num,val){
			if(val !== '')
			{
				$(".groceryCrudTable tfoot tr th:eq("+num+")").children(':first').val(val);
			}
		});
	}

	// clear filter event
	$('.clear-filtering').click(function(){
		localStorage.removeItem( 'DataTables_' + unique_hash);
		localStorage.removeItem( 'datatables_search_'+ unique_hash);

		chosen_table = datatables_get_chosen_table($(this).closest('.groceryCrudTable'));
		
		chosen_table.fnFilterClear();
		$(this).closest('.groceryCrudTable').find("tfoot tr th input").val("");
	});

	$('.refresh-data').click(function(){
		// Retrieve Old Table Information
		var this_container = $(this).closest('.refresh-holder');		
		var oldUniqid = this_container.find('.groceryCrudTable').attr('id');
		var oldIndex  = oTableMapping[oldUniqid];
		// Create New Container
		var new_container = $("<div/>").addClass('refresh-holder');
		// Clear Table html5 Storage
		localStorage.removeItem( 'DataTables_' + unique_hash);
		localStorage.removeItem( 'datatables_search_'+ unique_hash);

		// Replace Old Container with New one.
		this_container.after(new_container);
		this_container.fadeOut("fast", function() {
			this_container.remove();
		});

		// Ask GroceryCRUD for new Table
		$.ajax({
			url: $(this).attr('data-url'),
			success: function(my_output){
				// Install new Table
				new_container.html(my_output);

				// Replace Old Table Information with new Table UniqueId
				oTableArray[oldIndex] = loadDataTable(new_container.find('table'));
				oTableMapping[new_container.find('.groceryCrudTable').attr('id')] = oldIndex;
				// Remove Old Table UniqueId from Array
				delete oTableMapping[oldUniqid];

				// Clear Filter Input and assign new listeners
				new_container.find("tfoot tr th input").val("");
				loadListenersForDatatables();
			}
		});
	});
}

// Install Datatables to designed table. Not much changed from original GroceryCRUD Datatables Theme
function loadDataTable(this_datatables) {
	return $(this_datatables).dataTable({
		"bJQueryUI": true,
		"sPaginationType": "numbers",
		"bStateSave": use_storage,
        "fnStateSave": function (oSettings, oData) {
            localStorage.setItem( 'DataTables_' + unique_hash, JSON.stringify(oData) );
        },
    	"fnStateLoad": function (oSettings) {
            return JSON.parse( localStorage.getItem('DataTables_'+unique_hash) );
    	},
		"iDisplayLength": default_per_page,
		"aaSorting": datatables_aaSorting,
		"fnInitComplete" : function () {
            $('.DTTT_button_text').attr('download', '');
            $('.DTTT_button_text').attr('href', export_url);
		},
		"oLanguage":{
		    "sProcessing":   list_loading,
		    "sLengthMenu":   show_entries_string,
		    "sZeroRecords":  list_no_items,
		    "sInfo":         displaying_paging_string,
		    "sInfoEmpty":   list_zero_entries,
		    "sInfoFiltered": filtered_from_string,
		    "sSearch":       search_string+":",
		    "oPaginate": {
		        "sFirst":    paging_first,
		        "sPrevious": paging_previous,
		        "sNext":     paging_next,
		        "sLast":     paging_last
		    }
		},
		"bDestory": true,
		"bRetrieve": true,
		"fnDrawCallback": function() {
            //If there is no thumbnail this means that the fancybox library doesn't exist
            if ($('.image-thumbnail').length > 0) {
                $('.image-thumbnail').fancybox({
                    'transitionIn': 'elastic',
                    'transitionOut': 'elastic',
                    'speedIn': 600,
                    'speedOut': 200,
                    'overlayShow': false
                });
            }
			add_edit_button_listener();
            $('.DTTT_button_text').attr('href', export_url);
		},
		"sDom": '<"table-responsive"<"spacer visible-xs-block"><"col-md-3"l><"col-md-9 hidden-xs"f>t><"panel-footer"ip>',
	    "oTableTools": {
	        "sSwfPath": base_url+"assets/grocery_crud/themes/datatables/extras/TableTools/media/swf/copy_csv_xls_pdf.swf"
	    }
	});
}

// Get Datatable Instance. Original from GroceryCRUD Datatables Theme
function datatables_get_chosen_table(table_as_object){
	chosen_table_index = oTableMapping[table_as_object.attr('id')];
	return oTableArray[chosen_table_index];
}

// Process Delete Button. Using Pnotify by sciactive for Confirmation Dialog and Success Message
function delete_row(delete_url , row_id){
	notice = new PNotify({
		title: 'Confirmation Needed',
		text: message_alert_delete,
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
		$.ajax({
			url: delete_url,
			dataType: 'json',
			success: function(data){
				if(data.success){
					new PNotify({
						title: 'Success!',
						text: data.success_message,
						type: 'success'
					});

					chosen_table = datatables_get_chosen_table($('tr#row-'+row_id).closest('.groceryCrudTable'));

					$('tr#row-'+row_id).addClass('row_selected');
					var anSelected = fnGetSelected( chosen_table );
					chosen_table.fnDeleteRow( anSelected[0] );
				} else {
					new PNotify({
						title: 'Oh No!',
						text: data.error_message,
						type: 'error'
					});
				}
			}
		});
	});

	return false;
}

// Get Selected Datatable. Original from GroceryCRUD Datatables Theme
function fnGetSelected( oTableLocal ){
	var aReturn = new Array();
	var aTrs = oTableLocal.fnGetNodes();

	for ( var i=0 ; i<aTrs.length ; i++ ){
		if ( $(aTrs[i]).hasClass('row_selected') ){
			aReturn.push( aTrs[i] );
		}
	}
	return aReturn;
}