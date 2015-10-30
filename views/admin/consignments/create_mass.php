<?php
$use_order_weight = (int)get_option('linksynceparcel_use_order_weight');
$use_dimension = (int)get_option('linksynceparcel_use_dimension');
?>
<div class="entry-edit wp-core-ui">
    <h3>Create Consignment</h3>
</div>

<div class="entry-edit wp-core-ui" id="eparcel_sales_order_view">
    <form name="edit_form" id="edit_form" method="post" action="<?php echo admin_url('admin.php?page=linksynceparcel&subpage=create-mass-consignment'); ?>">
    	<div class="box" id="presets">
        <input id="number_of_articles" name="number_of_articles" size="4" value="1" type="hidden"/>
        <?php
		foreach($orders as $order)
		{
		?>
		<input name="order[]" value="<?php echo $order?>" type="hidden"/>
		<?php
		}
		?>
        Article Type&nbsp;&nbsp; 
        <select id="articles_type" name="articles_type" class="required-entry2" style="padding:3px" >
        	<?php if($use_order_weight == 1):?>
            	<option value="order_weight">Use Order Weight</option>
            <?php endif;?>
            <?php if($use_dimension == 1):?>
            <?php
            foreach($presets as $preset)
            {
                ?>
                <option value="<?php echo $preset->name.'<=>'.$preset->weight.'<=>'.$preset->height.'<=>'.$preset->width.'<=>'.$preset->length?>">
                    <?php echo $preset->name. ' ('.$preset->weight.'kg - '.$preset->height.'x'.$preset->width.'x'.$preset->length.')'?>
                </option>
                <?php
            }
            ?>
            <?php endif;?>
        </select>
        &nbsp;&nbsp;&nbsp;&nbsp;

    	<input type="submit" name="createConsignment" value="Create Consignment" onclick="return submitForm2()" class="button-primary button create-consignment1 scalable save submit-button"/>
        &nbsp;&nbsp;
        <button onclick="setLocation('<?php echo admin_url('admin.php?page=linksynceparcel')?>')" class="scalable back button" type="button" >
        	<span><span><span>Cancel</span></span></span>
    	</button>
</div>

<div>
    <br />
    <a href="javascript:void(0)" class="edit-consignments-defaults" style="text-decoration:none"><span style="font-size:13px; color:#F60">Edit Consignment Defaults</span></a>
    <br />
    <br />
</div>

<div class="box consignment-fields" style="display:none">
    <h3>Consignment Fields</h3>
    <table width="100%" border="0" cellspacing="6" cellpadding="6" class="tablecustom">
      <tr>
        <td width="30%">Partial Delivery allowed?</td>
        <td>
        <?php if(LinksynceparcelHelper::isDisablePartialDeliveryMethod($order->id)): ?>
        <select id="partial_delivery_allowed" name="partial_delivery_allowed" disabled="disabled" style="width:140px">>
            <option value="0">No</option>
        </select>
        <?php else: ?>
        <select id="partial_delivery_allowed" name="partial_delivery_allowed"  style="width:140px">
            <option value="1" <?php echo (get_option('linksynceparcel_partial_delivery_allowed')==1?'selected':'')?>>Yes</option>
            <option value="0" <?php echo (get_option('linksynceparcel_partial_delivery_allowed')!=1?'selected':'')?>>No</option>
        </select>
         <?php endif; ?>
        </td>
      </tr>
      
      <?php if(LinksynceparcelHelper::isCashToCollect($order->id)): ?>
      <tr>
        <td>Cash to collect</td>
        <td><input id="cash_to_collect" name="cash_to_collect" type="text" /></td>
      </tr>
      <?php endif; ?>
      
      <tr>
        <td>Delivery signature required?</td>
        <td><select id="delivery_signature_allowed" name="delivery_signature_allowed" style="width:140px">>
            <option value="1" <?php echo (get_option('linksynceparcel_signature_required')==1?'selected':'')?>>Yes</option>
            <option value="0" <?php echo (get_option('linksynceparcel_signature_required')!=1?'selected':'')?>>No</option>
        </select></td>
      </tr>
      <tr>
        <td>Transit cover required?</td>
        <td><select id="transit_cover_required" name="transit_cover_required" style="width:140px">>
            <option value="1" <?php echo (get_option('linksynceparcel_insurance')==1?'selected':'')?>>Yes</option>
            <option value="0" <?php echo (get_option('linksynceparcel_insurance')!=1?'selected':'')?>>No</option>
        </select></td>
      </tr>
      <tr>
        <td>Transit cover Amount</td>
        <td><input id="transit_cover_amount" type="text" size="14" class="positive-number" label="Transit cover amount" name="transit_cover_amount" value="<?php echo get_option('linksynceparcel_default_insurance_value')?>" /></td>
      </tr>
      <tr>
        <td>Shipment contains dangerous goods?</td>
        <td><select id="contains_dangerous_goods" name="contains_dangerous_goods" style="width:140px">>
            <option value="1">Yes</option>
            <option value="0" selected>No</option>
        </select></td>
      </tr>
      <tr>
        <td>Print return labels?</td>
        <td><select id="print_return_labels" name="print_return_labels" style="width:140px">>
            <option value="1" <?php echo (get_option('linksynceparcel_print_return_labels')==1?'selected':'')?>>Yes</option>
            <option value="0" <?php echo (get_option('linksynceparcel_print_return_labels')!=1?'selected':'')?>>No</option>
        </select></td>
      </tr>
      <tr>
        <td>Australia Post email notification?</td>
        <td><select id="email_notification" name="email_notification" style="width:140px">>
            <option value="1" <?php echo (get_option('linksynceparcel_post_email_notification')==1?'selected':'')?>>Yes</option>
            <option value="0" <?php echo (get_option('linksynceparcel_post_email_notification')!=1?'selected':'')?>>No</option>
        </select></td>
      </tr>
      <tr>
        <td>Notify Customers?</td>
        <td><select id="notify_customers" name="notify_customers" style="width:140px">>
            <option value="1" <?php echo (get_option('linksynceparcel_notify_customers')==1?'selected':'')?>>Yes</option>
            <option value="0" <?php echo (get_option('linksynceparcel_notify_customers')!=1?'selected':'')?>>No</option>
        </select></td>
      </tr>
    </table>
</div>
</form>
</div>

<script>
$jEparcel = jQuery.noConflict();
$jEparcel(document).ready(function(){
	$jEparcel('.edit-consignments-defaults').click(function(){
		$jEparcel('.consignment-fields').slideToggle();
	});
});
function setLocation(url)
{
	window.location.href = url;
}
function submitForm2()
{
	var valid = true;
	$jEparcel('.positive-number').each(function(){
		var value = $jEparcel.trim($jEparcel(this).val());
		var label = $jEparcel(this).attr('label');
		if(isNaN(value))
		{
			alert(label +' should be a number');
			valid = false;
		}
		
		value = parseInt(value);
		if(value < 0)
		{
			alert(label +' should be a postive number');
			valid = false;
		}
		
	});
	
	if(valid)
	{
		$jEparcel('#edit_form').submit();
	}
	else
	{
		return false;
	}
}
</script>