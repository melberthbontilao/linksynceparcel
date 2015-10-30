<?php
$use_order_weight = (int)get_option('linksynceparcel_use_order_weight');
$use_dimension = (int)get_option('linksynceparcel_use_dimension');
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
$exactArticles = (int)($weight / $weightPerArticle);
$totalArticles = $exactArticles;
$reminderWeight = fmod ($weight, $weightPerArticle);
if($reminderWeight > 0)
{
	$totalArticles++;
}
?>
<form name="edit_form" id="edit_form" method="post" action="<?php echo admin_url('admin.php?page=linksynceparcel&subpage=edit-consignment&action=save&order_id='.$order->id.'&consignment_number='.$consignment->consignment_number); ?>">

<div class="entry-edit wp-core-ui">
    <h3>Edit Consignment #<?php echo $consignment->consignment_number?></h3>
    <?php $articles = LinksynceparcelHelper::getArticles($order->id, $consignment->consignment_number);?>
    <?php $i=0;?>
    <input id="number_of_articles2" type="hidden" name="number_of_articles" value="<?php echo count($articles)?>" />
    <input id="articles_type" type="hidden" name="articles_type" value="Custom" />
    
    <?php if( ($use_order_weight == 1) && ($weight > $weightPerArticle) ):?>
        <h3>Total Order Weight: <strong><?php echo $weight?></strong></h3>
    <?php endif;?>
    
    <?php foreach($articles as $article):?>
    <div id="custom_articles">
        <h4 style="margin:10px 0">Article <?php echo $i+1?></h4>
        <span class="field-row1">
            <label class="normal" for="article_description">
             Description:<span class="required">*</span>
            </label>
            <input id="article_description<?php echo $i+1?>" type="text" name="article<?php echo $i+1?>[description]" class="required-entry" value="<?php echo $article->article_description?>"/>
        </span><br /><br />
        <span class="field-row1"> 
            <label class="normal" for="article_weight">
             Weight (Kgs):<span class="required">*</span>
            </label>
            <input size="10" type="text" style="text-align:center" id="article_weight<?php echo $i+1?>" name="article<?php echo $i+1?>[weight]" class="required-entry positive-number article_weight maximum-value" label="Weight" value="<?php echo $article->actual_weight?>"/>
        </span>
        <?php if($use_dimension == 1): ?>
        <span class="field-row1">
            <label class="normal" for="article_height">
            Height (cm):
            </label>
            <input size="10" type="text" style="text-align:center" id="article_height<?php echo $i+1?>" class="positive-number" label="Height" name="article<?php echo $i+1?>[height]"  value="<?php echo $article->height?>"/>
        </span>
        <span class="field-row1">
            <label class="normal" for="article_width">
             Width (cm):
            </label>
            <input size="10" type="text" style="text-align:center" id="article_width<?php echo $i+1?>" class="positive-number" label="Width" name="article<?php echo $i+1?>[width]" value="<?php echo $article->width?>"/>
        </span>
        <span class="field-row1">
            <label class="normal" for="article_length">
            Length (cm):
            </label>
            <input size="10" type="text" style="text-align:center" id="article_length<?php echo $i+1?>" class="positive-number" label="Length" name="article<?php echo $i+1?>[length]" value="<?php echo $article->length?>"/>
        </span>
        <?php else: ?>
            <input type="hidden" name="article<?php echo $i+1?>[height]" value="0"/>
            <input type="hidden" name="article<?php echo $i+1?>[width]" value="0"/>
            <input type="hidden" name="article<?php echo $i+1?>[length]" value="0"/>
            <input type="hidden" name="article<?php echo $i+1?>[article_number]" value="<?php echo $article->article_number?>"/>
        <?php endif; ?>
    </div>
		<?php $i++; ?>
	<?php endforeach;?>
    <div class="box consignment-fields">
      <h4 style="margin-bottom:6px">Consignment Fields</h4>
        <table width="100%" border="0" cellspacing="0" cellpadding="0" class="tablecustom">
          <tr>
            <td width="30%" height="35">Partial Delivery allowed?</td>
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
            <td height="35">Cash to collect</td>
            <td><input id="cash_to_collect" name="cash_to_collect" type="text" value="<?php echo $consignment->cash_to_collect?>" /></td>
          </tr>
          <?php endif; ?>
          
          <tr>
            <td height="35">Delivery signature required?</td>
            <td><select id="delivery_signature_allowed" name="delivery_signature_allowed" style="width:140px">>
                <option value="1" <?php echo ($consignment->delivery_signature_allowed==1?'selected':'')?>>Yes</option>
                <option value="0" <?php echo ($consignment->delivery_signature_allowed!=1?'selected':'')?>>No</option>
            </select></td>
          </tr>
          <tr>
            <td height="35">Transit cover required?</td>
            <td><select id="transit_cover_required" name="transit_cover_required" style="width:140px">>
                <option value="1" <?php echo (get_option('linksynceparcel_insurance')==1?'selected':'')?>>Yes</option>
                <option value="0" <?php echo (get_option('linksynceparcel_insurance')!=1?'selected':'')?>>No</option>
            </select></td>
          </tr>
          <tr>
            <td height="35">Transit cover Amount</td>
            <td><input id="transit_cover_amount" type="text" size="14" class="positive-number" label="Transit cover amount" name="transit_cover_amount" value="<?php echo get_option('linksynceparcel_default_insurance_value')?>" /></td>
          </tr>
          <tr>
            <td height="35">Shipment contains dangerous goods?</td>
            <td><select id="contains_dangerous_goods" name="contains_dangerous_goods" style="width:140px">>
                <option value="1" <?php echo ($consignment->contains_dangerous_goods==1?'selected':'')?>>Yes</option>
                <option value="0" <?php echo ($consignment->contains_dangerous_goods!=1?'selected':'')?>>No</option>
            </select></td>
          </tr>
          <tr>
            <td height="35">Print return labels?</td>
            <td><select id="print_return_labels" name="print_return_labels" style="width:140px">>
                <option value="1" <?php echo ($consignment->print_return_labels==1?'selected':'')?>>Yes</option>
                <option value="0" <?php echo ($consignment->print_return_labels!=1?'selected':'')?>>No</option>
            </select></td>
          </tr>
          <tr>
            <td height="35">Australia Post email notification?</td>
            <td><select id="email_notification" name="email_notification" style="width:140px">>
                <option value="1" <?php echo ($consignment->email_notification==1?'selected':'')?>>Yes</option>
                <option value="0" <?php echo ($consignment->email_notification!=1?'selected':'')?>>No</option>
            </select></td>
          </tr>
          <tr>
            <td height="35">Notify Customers?</td>
            <td><select id="notify_customers" name="notify_customers" style="width:140px">>
                <option value="1" <?php echo ($consignment->notify_customers==1?'selected':'')?>>Yes</option>
                <option value="0" <?php echo ($consignment->notify_customers!=1?'selected':'')?>>No</option>
            </select></td>
          </tr>
        </table>
    </div>
    
	<div style="margin-top:15px">
        <input type="submit" name="updateConsignment"  value="Update Consignment" onclick="return submitForm()" class="button-primary button scalable save submit-button"/>
            &nbsp;&nbsp;
        <button onclick="setLocation('<?php echo admin_url('post.php?post='.$order->id.'&action=edit')?>')" class="scalable back button" type="button" >
            <span><span><span>Cancel</span></span></span>
        </button>
    </div>
</div>
</form>
<script>
<?php if($use_order_weight == 1): ?>
var weight = '<?php echo $weight?>';
var weightPerArticle = '<?php echo $weightPerArticle?>';
<?php endif; ?>
$jEparcel = jQuery.noConflict();

function setLocation(url)
{
	window.location.href = url;
}

function submitForm()
{
	var valid = true;
	
	$jEparcel('.required-entry').each(function(){
		var value = $jEparcel.trim($jEparcel(this).val());
		if(value.length == 0 && valid)
		{
			valid = false;
		}
	});
	if(!valid)
	{
		alert('Please enter all the mandatory fields');
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
	
	var totalInputWeight = 0;
	$jEparcel('.article_weight').each(function(){
		var value = $jEparcel.trim($jEparcel(this).val());
		value = parseFloat(value);
		totalInputWeight += value;
	});

	if(totalInputWeight < weight)
	{
		if(!confirm('Combined article weight is less than the total order weight. Do you want to continue?'))
			return false;
		$jEparcel('#edit_form').submit();
	}
	else
	{
		$jEparcel('#edit_form').submit();
	}
	<?php else: ?>
		$jEparcel('#edit_form').submit();
	<?php endif; ?>
}
</script>