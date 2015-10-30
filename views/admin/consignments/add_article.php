<?php
$use_order_weight = (int)get_option('linksynceparcel_use_order_weight');
$use_dimension = (int)get_option('linksynceparcel_use_dimension');
$weight = 0;
if($use_order_weight == 1)
{
	$weight = LinksynceparcelHelper::getOrderWeight($order);
	if($weight == 0)
	{
		$default_article_weight = get_option('linksynceparcel_default_article_weight');
		if($default_article_weight)
		{
			$weight = $default_article_weight;
		}
	}
	$weightPerArticle = LinksynceparcelHelper::getAllowedWeightPerArticle();
}

$selected = false;
$selectedWeight = 0;
if($weight <= $weightPerArticle)
{
	$upCheck = $weightPerArticle - $weight;
	if(LinksynceparcelHelper::presetMatch($presets,$weight))
	{
		$selectedWeight = $weight;
	}
	else
	{
		for($i=.01;$i<=$upCheck;$i = $i + 0.01)
		{
			$newUpWeight = $weight + $i;
			if(LinksynceparcelHelper::presetMatch($presets,$newUpWeight))
			{
				$selectedWeight = ''.$newUpWeight.'';
				break;
			}
		}
	}
}
?>

<div class="entry-edit wp-core-ui">
    <h3>Add an Article for Consignment #<?php echo $consignment->consignment_number?></h3>
</div>

<div class="entry-edit wp-core-ui" id="eparcel_sales_order_view">
    <form name="edit_form" id="edit_form" method="post" action="<?php echo admin_url('admin.php?page=linksynceparcel&subpage=add-article&action=save&order_id='.$order->id.'&consignment_number='.$consignment->consignment_number); ?>">
    	<input id="number_of_articles" name="number_of_articles" size="4" value="1" type="hidden"/>
    	<?php if($use_dimension == 1): ?>
    	<div class="box" id="presets">
        Article Type&nbsp;&nbsp; 
        <select id="articles_type" name="articles_type" class="required-entry2" style="padding:3px" >
            <?php
            foreach($presets as $preset)
            {
                ?>
                <option value="<?php echo $preset->name.'<=>'.$preset->weight.'<=>'.$preset->height.'<=>'.$preset->width.'<=>'.$preset->length?>"
                		<?php 
							if($preset->weight == $selectedWeight && !$selected)
							{
								echo 'selected="selected"'; 
								$selected = true;
							}
							?>
                >
                    <?php echo $preset->name. ' ('.$preset->weight.'kg - '.$preset->height.'x'.$preset->width.'x'.$preset->length.')'?>
                </option>
                <?php
            }
            ?>
            <option value="Custom" <?php echo ($weight > $weightPerArticle) ? 'selected="selected"' : ''?>>Custom</option>
        </select>
        &nbsp;&nbsp;&nbsp;&nbsp;
    	<input type="submit" name="createConsignment" value="Add Article" onclick="return submitForm2()" class="button-primary button create-consignment1 scalable save submit-button <?php if($order_status == 'completed'){ echo 'disabled';}?>" <?php if($order_status == 'completed'){ echo 'disabled="disabled"';}?>/>
        &nbsp;&nbsp;
        <button onclick="setLocation('<?php echo admin_url('post.php?post='.$order->id.'&action=edit')?>')" class="scalable back button cancel-button" type="button" >
        	<span><span><span>Cancel</span></span></span>
    	</button>
    
		</div>
        <?php else: ?>
		<input type="hidden" id="articles_type" name="articles_type" value="Custom"/>
    	<?php endif; ?>

    <div class="box custom_articles_template" style="display:none">
        <h3 style="margin:10px 0">Article</h3>
        <span class="field-row1">
            <label class="normal" for="article_description">
             Description:<span class="required">*</span>
            </label>
            <input id="article_description" type="text" name="article[description]" class="required-entry" value="Article"/>
        </span><br /><br />
        <span class="field-row1"> 
            <label class="normal" for="article_weight">
             Weight (Kgs):<span class="required">*</span>
            </label>
            <?php if($use_order_weight == 1): ?>
            <input size="10" type="text" style="text-align:center" id="article_weight" name="article[weight]" class="required-entry positive-number  maximum-value" label="Weight" value="<?php echo ($weight > $weightPerArticle) ? $weightPerArticle : $weight?>"/>
            <?php else: ?>
                <input size="10" style="text-align:center" id="article_weight" name="article[weight]" class="required-entry positive-number" label="Weight" value="<?php echo get_option('linksynceparcel_default_article_weight')?>"/>
            <?php endif; ?>
        </span>
        <?php if($use_dimension == 1): ?>
        <span class="field-row1">
            <label class="normal" for="article_height">
            Height (cm):
            </label>
            <input size="10" type="text" style="text-align:center" id="article_height" class="positive-number" label="Height" name="article[height]"  value="<?php echo get_option('linksynceparcel_default_article_height')?>"/>
        </span>
        <span class="field-row1">
            <label class="normal" for="article_width">
             Width (cm):
            </label>
            <input size="10" type="text" style="text-align:center" id="article_width" class="positive-number" label="Width" name="article[width]" value="<?php echo get_option('linksynceparcel_default_article_width')?>"/>
        </span>
        <span class="field-row1">
            <label class="normal" for="article_length">
            Length (cm):
            </label>
            <input size="10" type="text" style="text-align:center" id="article_length" class="positive-number" label="Length" name="article[length]" value="<?php echo get_option('linksynceparcel_default_article_length')?>"/>
        </span>
        <?php else: ?>
            <input type="hidden" name="article[height]" value="0"/>
            <input type="hidden" name="article[width]" value="0"/>
            <input type="hidden" name="article[length]" value="0"/>
        <?php endif; ?>
    </div>

<div id="custom_articles" style="display:none">
    
    <div id="custom_articles_container">
    </div>
    <br />
    <br />
    <button onclick="backToPreset()" class="scalable back backToPreset button" type="button" title="Back">
        <span><span><span>Back to Preset</span></span></span>
    </button>
    &nbsp;&nbsp;
    <input type="submit" name="createConsignment"  value="Add Article" onclick="return submitForm()" class="button-primary button scalable save submit-button <?php if($order_status == 'completed'){ echo 'disabled';}?>" <?php if($order_status == 'completed'){ echo 'disabled="disabled"';}?>/>
    &nbsp;&nbsp;
    <button onclick="setLocation('<?php echo admin_url('post.php?post='.$order->id.'&action=edit')?>')" class="scalable back button" type="button" >
        <span><span><span>Cancel</span></span></span>
    </button>
    
</div>

<?php if($order_status != 'completed'){?>
 <div>
    <br />
    <a href="javascript:void(0)" class="edit-consignments-defaults" style="text-decoration:none"><span style="font-size:13px; color:#F60">Edit Consignment Defaults</span></a>
    <br />
    <br />
 </div>
<?php
    }
 ?>
 
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
            <option value="1" <?php echo ($consignment->partial_delivery_allowed==1?'selected':'')?>>Yes</option>
            <option value="0" <?php echo ($consignment->partial_delivery_allowed!=1?'selected':'')?>>No</option>
        </select>
         <?php endif; ?>
        </td>
      </tr>
      
      <?php if(LinksynceparcelHelper::isCashToCollect($order->id)): ?>
      <tr>
        <td>Cash to collect</td>
        <td><input id="cash_to_collect" name="cash_to_collect" type="text" value="<?php echo $consignment->cash_to_collect?>" /></td>
      </tr>
      <?php endif; ?>
      
      <tr>
        <td>Delivery signature required?</td>
        <td><select id="delivery_signature_allowed" name="delivery_signature_allowed" style="width:140px">>
            <option value="1" <?php echo ($consignment->delivery_signature_allowed==1?'selected':'')?>>Yes</option>
            <option value="0" <?php echo ($consignment->delivery_signature_allowed!=1?'selected':'')?>>No</option>
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
            <option value="1" <?php echo ($consignment->contains_dangerous_goods==1?'selected':'')?>>Yes</option>
            <option value="0" <?php echo ($consignment->contains_dangerous_goods!=1?'selected':'')?>>No</option>
        </select></td>
      </tr>
      <tr>
        <td>Print return labels?</td>
        <td><select id="print_return_labels" name="print_return_labels" style="width:140px">>
            <option value="1" <?php echo ($consignment->print_return_labels==1?'selected':'')?>>Yes</option>
            <option value="0" <?php echo ($consignment->print_return_labels!=1?'selected':'')?>>No</option>
        </select></td>
      </tr>
      <tr>
        <td>Australia Post email notification?</td>
        <td><select id="email_notification" name="email_notification" style="width:140px">>
            <option value="1" <?php echo ($consignment->email_notification==1?'selected':'')?>>Yes</option>
            <option value="0" <?php echo ($consignment->email_notification!=1?'selected':'')?>>No</option>
        </select></td>
      </tr>
      <tr>
        <td>Notify Customers?</td>
        <td><select id="notify_customers" name="notify_customers" style="width:140px">>
            <option value="1" <?php echo ($consignment->notify_customers==1?'selected':'')?>>Yes</option>
            <option value="0" <?php echo ($consignment->notify_customers!=1?'selected':'')?>>No</option>
        </select></td>
      </tr>
    </table>
</div>
</form>
</div>

<script>
<?php if($use_order_weight == 1): ?>
var weightPerArticle = '<?php echo $weightPerArticle?>';
<?php endif; ?>
$jEparcel = jQuery.noConflict();
$jEparcel(document).ready(function(){

	$jEparcel('.edit-consignments-defaults').click(function(){
		$jEparcel('.consignment-fields').slideToggle();
	});
	
	if($jEparcel('#articles_type').val() == 'Custom')
	{
		$jEparcel('.create-consignment1').hide(); 
		$jEparcel('.backToPreset').hide(); 
		$jEparcel('#custom_articles').show(); 
		$jEparcel('.cancel-button').hide(); 
			
		var number_of_articles = $jEparcel('#number_of_articles').val();
		for(var i=1; i<=number_of_articles; i++)
		{
			var box = $jEparcel('.custom_articles_template').clone(); 
			box.removeClass('custom_articles_template');
			box.find('h3').html(box.find('h3').html()+' '+i);
			box.find('#article_description').val(box.find('#article_description').val()+' '+i);
			box.show();
			$jEparcel('#custom_articles_container').append(box);
		}
	}
	
	$jEparcel('#articles_type').change(function(){
		$jEparcel('.backToPreset').hide(); 
		if($jEparcel(this).val() == 'Custom')
		{
			$jEparcel('.create-consignment1').hide();
			$jEparcel('#presets').show(); 
			$jEparcel('#custom_articles').show(); 
			$jEparcel('.cancel-button').hide(); 
			
			var number_of_articles = $jEparcel('#number_of_articles').val();
			for(var i=1; i<=number_of_articles; i++)
			{
				var box = $jEparcel('.custom_articles_template').clone(); 
				box.removeClass('custom_articles_template');
				box.find('h3').html(box.find('h3').html()+' '+i);
				box.find('#article_description').val(box.find('#article_description').val()+' '+i);
				box.show();
				$jEparcel('#custom_articles_container').append(box);
			}
		}
		else
		{
			$jEparcel('#presets').show();
			$jEparcel('#custom_articles').hide(); 
			$jEparcel('#custom_articles_container').html(''); 
			$jEparcel('.create-consignment1').show();
			$jEparcel('.cancel-button').show(); 
		}
	});
});

function backToPreset()
{
	$jEparcel('#presets').show();
	$jEparcel('#custom_articles').hide(); 
	$jEparcel('#custom_articles_container').html(''); 
	$jEparcel('#articles_type').val($jEparcel('#articles_type > option:first').attr('value'));
}

function setLocationConfirmDialog(url)
{
	if(!confirm('Are you sure?'))
		return false;
	setLocation(url);
}

function setLocation(url)
{
	window.location.href = url;
}

function submitForm()
{
	var valid = true;
	
	var value = $jEparcel.trim($jEparcel('#articles_type').val());
	if(value.length == 0 && valid)
	{
		valid = false;
		alert('Please select article type');
		return false;
	}
	
	$jEparcel('#custom_articles_container .required-entry').each(function(){
		var value = $jEparcel.trim($jEparcel(this).val());
		if(value.length == 0 && valid)
		{
			valid = false;
		}
	});
	if(!valid)
	{
		alert('Please enter/select all the mandatory fields');
		return false;
	}
	
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
	
	if(!valid)
	{
		return false;
	}
	<?php if($use_order_weight == 1): ?>
	$jEparcel('.maximum-value').each(function(){
		var value = $jEparcel.trim($jEparcel(this).val());
		var label = $jEparcel(this).attr('label');
		value = parseFloat(value);
		if(value > weightPerArticle)
		{
			alert('Allowed weight per article is '+ weightPerArticle);
			valid = false;
		}
		
	});
	if(!valid)
	{
		return false;
	}
	else
	{
		$jEparcel('#edit_form').submit();
	}
	<?php else: ?>
		$jEparcel('#edit_form').submit();
	<?php endif; ?>
}

function submitForm2()
{
	$jEparcel('#edit_form').submit();
}
</script>