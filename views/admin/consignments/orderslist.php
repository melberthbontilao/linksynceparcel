<link href="//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/themes/ui-darkness/jquery-ui.css" rel="stylesheet">
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>
<div class="wrap woocommerce">
	<?php
	$error = get_option('linksynceparcel_consignment_error');
	if($error)
	{
		LinksynceparcelHelper::addError($error);
		delete_option('linksynceparcel_consignment_error');
	}
	$success = get_option('linksynceparcel_consignment_success');
	if($success)
	{
		LinksynceparcelHelper::addSuccess($success);
		delete_option('linksynceparcel_consignment_success');
	}
	?>
    <?php echo $pluginMessage ?>
<form method="get" id="mainform" action="<?php echo admin_url('admin.php'); ?>" enctype="multipart/form-data" onsubmit="return submitConsignmentForm()">
    	<input type="hidden" name="page" value="linksynceparcel" />
        <?php 
        if ( ! empty( $_REQUEST['orderby'] ) )
			echo '<input type="hidden" name="orderby" value="' . esc_attr( $_REQUEST['orderby'] ) . '" />';
		else
			echo '<input type="hidden" name="orderby" value="order_id" />';
		if ( ! empty( $_REQUEST['order'] ) )
			echo '<input type="hidden" name="order" value="' . esc_attr( $_REQUEST['order'] ) . '" />';
		else
			echo '<input type="hidden" name="order" value="desc" />';
		?>
        <div class="icon32 icon32-woocommerce-settings" id="icon-woocommerce"><br /></div>
        <h2>
        	Consignments
        </h2>
        <?php 
		$checker = $myListTable->checkNonLinksync();
		if(isset($checker) && $checker > 0) {
			$myListTable->prepare_items(); 
			$myListTable->display(); 
		} else {
		?>
			<div class="update-nag notice">
				<p>Linksync eParcel requires at least one shipping type. <a href="<?php echo home_url('/')?>wp-admin/admin.php?page=linksynceparcel&subpage=assign-shipping-types&action=add">Click here</a> to assing shipping types.</p>
			</div>
		<?php
		}
		?>
    </form>
</div>
<script>
jQuery(document).ready(function(){

	jQuery("#dialog").dialog({
		autoOpen: false,
		width:'400px'
	});
	
	jQuery("#dialog2").dialog({
		autoOpen: false,
		width:'400px'
	});
	
	jQuery('input#_wpnonce').next().remove();
	jQuery('.datepicker').datepicker({
		dateFormat: "yy-mm-dd"
	});
	
	jQuery("#dialog_submit").click(function(e) {
		if(!jQuery("#dialog_checkbox").prop('checked'))
		{
			alert('Please acknowledge to submit test manifest');
			e.preventDefault();
		}
	});
});

function setLocationConfirmDialog(url)
{
	if(!jQuery('#despatchManifest').hasClass('disabled'))
	{
		var mode = '<?php echo trim(get_option('linksynceparcel_operation_mode'))?>';		
		if(mode == 1)
		{
			jQuery("#dialog2").dialog("open");
		}
		else
		{
			jQuery("#dialog").dialog("open");
		}
	}
}

function setLocation(url)
{
	window.location.href = url;
}

function submitConsignmentForm()
{
	var action = jQuery('select[name="action"]').val();
	if(action == -1)
	{
		action = jQuery('select[name="action2"]').val();
	}
	if(action == 'massUnassignConsignment' || action == 'massDeleteConsignment' || action == 'massMarkDespatched')
	{
		return confirm('Are you sure?');
	}
	return true;
}
</script>
<style>
input.new-input{
	font-size:11px;
	width:90px;
}
#post-query-submit{
	float: right;
}
.column-number_of_articles,.column-is_return_label_printed,.column-is_address_valid,.column-is_next_manifest,.column-is_label_printed{
	text-align:center;
}
th.manage-column {
	vertical-align:top;
}
</style>

<div id="dialog" title="Submit Test Manifest" style="display:none">
<form action="<?php echo admin_url('admin.php?page=linksynceparcel&action=despatchManifest')?>" method="post">
<p>You are in test mode. Test mode enables you to use and test all features of the linksync eParcel without actually submitting a manifest to Australia Post on despatch of a manifest.</p>
<label> <input id="dialog_checkbox" name="dialog_checkbox" type="checkbox"> I acknowledge this is only a test. </label>
<br /><br /><br/>
<input id="dialog_submit" type="submit" value="Submit" style="float:right" class="button">
</form>
</div>

<div id="dialog2" title="Submit Manifest" style="display:none">
<form action="<?php echo admin_url('admin.php?page=linksynceparcel&action=despatchManifest')?>" method="post">
<p>You are about to submit your manifest to Australia Post. Once your manifest is despatched, you won't be able to modify it, or the associated consignments..</p>
<br /><br/>
<input id="dialog_submit2" type="submit" value="Submit" style="float:right" class="button">
</form>
</div>
