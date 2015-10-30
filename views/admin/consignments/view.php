<style>
#new-buttons-set .button{
	font-size:11px !important;
}
</style>
<div class="entry-edit wp-core-ui" id="eparcel_sales_order_view">
	    <input type="hidden" id="createConsignmentHidden" name="createConsignmentHidden" value="0"/>
    	<div class="box_ls" id="presets">
		<?php if($order_status != 'completed'){?>
       			
        Articles&nbsp;&nbsp; <input type="text" id="number_of_articles" name="number_of_articles" size="4" value="1" class="validate-number" style="text-align:center; padding:3px" />			
        &nbsp;&nbsp;&nbsp;&nbsp;
        Article Type&nbsp;&nbsp; 
        <select id="articles_type" name="articles_type" class="required-entry2" style="padding:3px" >
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
            <option value="Custom">Custom</option>
        </select>
        &nbsp;&nbsp;&nbsp;&nbsp;
		<?php
            }
         ?>
    	<input type="submit" name="createConsignment" value="Create Consignment" onclick="return submitForm2()" class="button-primary button create-consignment1 scalable save submit-button <?php if($order_status == 'completed'){ echo 'disabled';}?>" <?php if($order_status == 'completed'){ echo 'disabled="disabled"';}?>/>
    
</div>

<div class="box_ls custom_articles_template" style="display:none">
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
        <input size="10" type="text" style="text-align:center" id="article_weight" name="article[weight]" class="required-entry positive-number" label="Weight" value="<?php echo get_option('linksynceparcel_default_article_weight')?>"/>
    </span>
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
    <input type="submit" name="createConsignment"  value="Create Consignment" onclick="return submitForm()" class="button-primary button scalable save submit-button <?php if($order_status == 'completed'){ echo 'disabled';}?>" <?php if($order_status == 'completed'){ echo 'disabled="disabled"';}?>/>
    
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
 
<div class="box_ls consignment-fields" style="display:none">
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
	  <?php
		$opt_drop = 'hide-tr';
		$opt_drop_val = 0;
		if(get_option('linksynceparcel_signature_required') == 1) {
			$opt_drop = 'show-tr';
			$opt_drop_val = get_option('linksynceparcel_safe_drop');
		}
	  ?>
	  <tr class="safe-drop-row <?php echo $opt_drop; ?>">
        <td>Safe Drop</td>
        <td>
			<select id="safe_drop" name="safe_drop" style="width:140px">
				<option value="1" <?php echo ($opt_drop_val==1?'selected':'')?>>Yes</option>
				<option value="0" <?php echo ($opt_drop_val!=1?'selected':'')?>>No</option>
			</select>
		</td>
      </tr>
	  <tr>
        <td>Export Declaration Number</td>
        <td>
			<input type="text" id="export_declaration_number" name="export_declaration_number" value="" />
		</td>
      </tr>
	  <tr>
        <td>Order value as Declared Value</td>
        <td>
			<?php 
				$declared_checked = '';
				if (get_option('linksynceparcel_declared_value') == 1){ 
					$declared_checked = 'checked="checked"';
				}
			?>
			<input type="checkbox" id="declared_value" name="declared_value" value="1" <?php echo $declared_checked; ?>>
		</td>
      </tr>
	<?php 
		$declared_text = '';
		$declared_text_option = 'hide-tr';
		if ($declared_checked == ''){ 
			$declared_text = get_option('linksynceparcel_declared_value_text');
			$declared_text_option = 'show-tr';
		}
	?>
	  <tr class="declared_value_text_field <?php echo $declared_text_option; ?>">
        <td></td>
        <td>
			<input type="number" id="declared_value_text" name="declared_value_text" value="<?php echo $declared_text; ?>">
		</td>
      </tr>
	  <tr>
        <td>Has Commercial Value</td>
        <td>
		<?php
			$commercial_checked = '';
			if (get_option('linksynceparcel_has_commercial_value') == 1){ 
				$commercial_checked = 'checked="checked"';
			}
		?>
			<input type="checkbox" id="has_commercial_value" name="has_commercial_value" value="1" <?php echo $commercial_checked; ?>>
		</td>
      </tr>
	  <tr>
		<td>Product Classification</td>
		<td>
			<?php
				$product_classification_disable = '';
				$product_classification_value = get_option('linksynceparcel_product_classification');
				if($commercial_checked != '') {
					$product_classification_disable = 'disabled="true"';
					$product_classification_value = '991';
				}
			?>
			<select id="product_classification" name="product_classification" <?php echo $product_classification_disable; ?>>
				<option value="991" <?php if ($product_classification_value == '991'){ echo 'selected="selected"'; }?>>Other</option>
				<option value="32" <?php if ($product_classification_value == '32'){ echo 'selected="selected"'; }?>>Commercial</option>
				<option value="31" <?php if ($product_classification_value == '31'){ echo 'selected="selected"'; }?>>Gift</option>
				<option value="91" <?php if ($product_classification_value == '91'){ echo 'selected="selected"'; }?>>Document</option>
			</select>
		</td>
	  </tr>
	  <?php 
		$product_classification_text = '';
		$product_classification_option = 'hide-tr';
		if ($product_classification_value == '991'){ 
			$product_classification_text = get_option('linksynceparcel_product_classification_text');
			$product_classification_option = 'show-tr';
		}
	?>
	<tr id="product_classification_text" class="<?php echo $product_classification_option; ?>">
		<td>Classification Explanation</td>
		<td>
			<input type="text" class="product_classification_text" name="product_classification_text" value="<?php echo $product_classification_text; ?>">
		</td>
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

</div>

<?php $consignments = LinksynceparcelHelper::getConsignments($order->id);?>
<?php foreach($consignments as $consignment):?>
<?php $articles = LinksynceparcelHelper::getArticles($order->id, $consignment->consignment_number);?>
<br />
<div class="entry-edit" style="border:1px solid silver; padding:10px">
	<div class="entry-edit-head" style="height: 40px; background-color: silver;">
		<span class="icon-head head-products" style="font-weight: bold; font-size: 14px; float: left; margin-top: 10px; padding-left: 5px;">
		Consigment: <?php echo $consignment->consignment_number?>
		<?php if($consignment->despatched):?>
			(despatched) - <a href="<?php echo admin_url('admin.php?page=linksynceparcel&subpage=manifests&action=list-consignments&manifest_number='.$consignment->manifest_number); ?>">manifest <?php echo $consignment->manifest_number?></a>
		<?php endif;?>
		</span>
		<span id="new-buttons-set" style="float: right; margin-right: 4px; margin-top: 5px;">
		<a class="button" target="_blank" href="http://auspost.com.au/track/track.html?id=<?php echo $consignment->consignment_number?>">Track</a>
        <?php if(!empty($consignment->label)):?>
        <a class="button print_label" lang="<?php echo $consignment->consignment_number?>" target="_blank" 
        	href="<?php echo linksynceparcel_URL.'assets/label/consignment/'.$consignment->consignment_number.'.pdf?'.time()?>" 
         	type="button" title="Print Label">
             <?php echo ($consignment->is_label_printed ? 'Reprint' : 'Print');?> Label
        </a>
        <?php endif;?>
        <?php if(!empty($consignment->print_return_labels)):?>
			<?php if(file_exists(linksynceparcel_DIR.'assets/label/returnlabels/'.$consignment->consignment_number.'.pdf')):?>
                <a class="button print_return_label" lang="<?php echo $consignment->consignment_number?>" target="_blank" 
                    href="<?php echo linksynceparcel_URL.'assets/label/returnlabels/'.$consignment->consignment_number.'.pdf?'.time()?>" 
                    type="button">
                     <?php echo ($consignment->is_return_label_printed ? 'Reprint' : 'Print');?> Return Label
                </a>
            <?php endif;?>
         <?php endif;?>
		<?php if(!$consignment->despatched):?>
			<?php if(count($articles) < 20):?>
			<button class="button" onclick="setLocation('<?php echo admin_url('admin.php?page=linksynceparcel&subpage=add-article&order_id='.$order->id.'&consignment_number='.$consignment->consignment_number); ?>')" type="button">Add Articles</button>
			<?php endif;?>
			<button class="button" onclick="setLocation('<?php echo admin_url('admin.php?page=linksynceparcel&order_id='.$order->id.'&subpage=edit-consignment&consignment_number='.$consignment->consignment_number); ?>')" type="button">Edit Consignment</button>
			<button class="button" onclick="setLocationConfirmDialog('<?php echo admin_url('admin.php?page=linksynceparcel&order_id='.$order->id.'&action=delete_consignment&consignment_number='.$consignment->consignment_number); ?>')" type="button">Delete Consignment</button>
		<?php endif;?>
        </span>
	</div>
	
	<div class="box_ls">
		<table width="100%" border="0" cellspacing="6" cellpadding="6" class="tablecustom">
		  <tr>
			<td width="40%">Partial Delivery allowed?</td>
			<td><?php echo ($consignment->partial_delivery_allowed==1?'Yes':'No')?></td>
		  </tr>
		  
		  <?php if(LinksynceparcelHelper::isCashToCollect($order->id)): ?>
		  <tr>
			<td>Cash to collect</td>
			<td><?php echo $consignment->cash_to_collect?></td>
		  </tr>
		  <?php endif; ?>
		  
		  <tr>
			<td>Delivery signature required?</td>
			<td><?php echo ($consignment->delivery_signature_allowed==1?'Yes':'No')?></td>
		  </tr>
		  <tr>
			<td>Shipment contains dangerous goods?</td>
			<td><?php echo ($consignment->contains_dangerous_goods==1?'Yes':'No')?></td>
		  </tr>
		  <tr>
			<td>Print return labels?</td>
			<td><?php echo ($consignment->print_return_labels==1?'Yes':'No')?></td>
		  </tr>
		  <tr>
			<td>Australia Post email notification?</td>
			<td><?php echo ($consignment->email_notification==1?'Yes':'No')?></td>
		  </tr>
		  <tr>
			<td>Notify Customers?</td>
			<td><?php echo ($consignment->notify_customers==1?'Yes':'No')?></td>
		  </tr>
		</table>
		<br />
		<table cellspacing="1" class="data order-tables" width="100%" style="background-color: rgb(238, 238, 238); padding: 1px;">
		<thead style="font-size: 11px; text-align: left;">
			<tr class="headings">
				<th height="25px" bgcolor="#EEEEEE">Article Number</th>
				<th bgcolor="#EEEEEE">Description</th>
				<th bgcolor="#EEEEEE" class="a-center" style="text-align:center">Weight (Kgs)</th>
				<th bgcolor="#EEEEEE" class="a-center" style="text-align:center">Width (cm)</th>
				<th bgcolor="#EEEEEE" class="a-center" style="text-align:center">Height (cm)</th>
				<th bgcolor="#EEEEEE" class="a-center" style="text-align:center">Length (cm)</th>
				<th bgcolor="#EEEEEE" class="a-center" style="text-align:center">Transit Cover</th>
				<th bgcolor="#EEEEEE" class="a-right" style="text-align:center">Transit Value</th>
				<th bgcolor="#EEEEEE" class="a-right" style="text-align:center">Action</th>
			</tr>
		</thead>
		<tbody class="even" style="font-size: 11px;">
		<?php foreach($articles as $article):?>
			<tr class="border">
				<td height="32px" bgcolor="#FBFBFB" class="a-left"><?php echo $article->article_number?></td>
			  <td bgcolor="#FBFBFB" class="a-left"><?php echo stripslashes($article->article_description)?></td>
			  <td bgcolor="#FBFBFB" class="a-center" style="text-align:center"><?php echo $article->actual_weight?></td>
			  <td bgcolor="#FBFBFB" class="a-center" style="text-align:center"><?php echo $article->width?></td>
			  <td bgcolor="#FBFBFB" class="a-center" style="text-align:center"><?php echo $article->height?></td>
			  <td bgcolor="#FBFBFB" class="a-center" style="text-align:center"><?php echo $article->length?></td>
			  <td bgcolor="#FBFBFB" class="a-center" style="text-align:center"><?php echo $article->is_transit_cover_required?></td>
			  <td bgcolor="#FBFBFB" class="a-right" style="text-align:center"><?php echo (is_numeric($article->transit_cover_amount) ? number_format($article->transit_cover_amount,2) : 'NA') ?></td>
				<td bgcolor="#FBFBFB"  style="text-align:center">
				<?php if(!$consignment->despatched):?>
			    <button style="font-size: 11px;" class="button" onclick="setLocation('<?php echo admin_url('admin.php?page=linksynceparcel&order_id='.$order->id.'&subpage=edit-article&consignment_number='.$consignment->consignment_number.'&article_number='.$article->article_number); ?>')" type="button">Edit</button>
					<button class="button" onclick="setLocationConfirmDialog('<?php echo admin_url('admin.php?page=linksynceparcel&order_id='.$order->id.'&action=delete_article&consignment_number='.$consignment->consignment_number.'&article_number='.$article->article_number); ?>')" type="button" style="font-size: 11px;">Delete</button>
				<?php else:?>
					N/A
				<?php endif;?>
				</td>
			</tr>
		<?php endforeach;?>
		   </tbody>
		</table>           

  </div>
	<div class="clear"></div>
</div>
<?php endforeach;?>

<style>
.hide-tr {
	display: none;
}
.show-tr {
	display: table-row;
}
</style>
<script>
$jEparcel = jQuery.noConflict();
$jEparcel(document).ready(function(){
	$jEparcel('#delivery_signature_allowed').on('change', function() {
		if($jEparcel(this).val() == 1) {
			$jEparcel('.safe-drop-row').removeClass('hide-tr');
			$jEparcel('.safe-drop-row').addClass('show-tr');
		} else {
			$jEparcel('.safe-drop-row').removeClass('show-tr');
			$jEparcel('.safe-drop-row').addClass('hide-tr');
			$jEparcel('#consignments_safe_drop').val(0);
		}
	});
	$jEparcel("#declared_value").on('change', function() {
		var $this_val = $jEparcel('#declared_value:checked').length > 0;
		if($this_val) {
			$jEparcel('.declared_value_text_field').removeClass('show-tr');
			$jEparcel('.declared_value_text_field').addClass('hide-tr');
		} else {
			$jEparcel('.declared_value_text_field').removeClass('hide-tr');
			$jEparcel('.declared_value_text_field').addClass('show-tr');
		}
	});
	$jEparcel('#has_commercial_value').change(function() {
		var $this_val = $jEparcel('#has_commercial_value:checked').length > 0;
		if($this_val) {
			$jEparcel('#product_classification').attr('disabled', true);
			$jEparcel('#product_classification').val('991');
			$jEparcel('#product_classification_text').removeClass("hide-tr");
			$jEparcel('#product_classification_text').addClass("show-tr");
		} else {
			$jEparcel('#product_classification').attr('disabled', false);
		}
	});
	
	$jEparcel('#product_classification').change(function() {
		var $this_val = $jEparcel('#product_classification').val();
		if($this_val == '991') {
			$jEparcel('#product_classification_text').removeClass("hide-tr");
			$jEparcel('#product_classification_text').addClass("show-tr");
		} else {
			$jEparcel('#product_classification_text').removeClass("show-tr");
			$jEparcel('#product_classification_text').addClass("hide-tr");
			$jEparcel('.product_classification_text').val("");
		}
	});
	$jEparcel('.edit-consignments-defaults').click(function(){
		$jEparcel('.consignment-fields').slideToggle();
	});
	
	$jEparcel('#number_of_articles').blur(function(){
		var value = $jEparcel.trim($jEparcel(this).val());

		if(value.length == 0)
		{
			alert('Articles should not be empty');
			$jEparcel(this).val(1);
		}
		if(isNaN(value))
		{
			alert('Articles should be a number');
			$jEparcel(this).val(1);
		}
		
		value = parseInt(value);
		if(value < 0)
		{
			alert('Articles should be a postive number');
			$jEparcel(this).val(1);
		}
		
		if(value > 100)
		{
			alert('Articles can be 1-100 per request');
			$jEparcel(this).val(1);
		}
		
		if($jEparcel('#articles_type > option').length == 1)
		{
			var number_of_articles = $jEparcel('#number_of_articles').val();
			var boxes = $jEparcel('#custom_articles_container > div.box_ls').length;
			if(boxes > number_of_articles)
			{
				for(;boxes>number_of_articles; boxes--)
				{
					$jEparcel('#custom_articles_container > div.box_ls:nth-child('+boxes+')').remove();
				}
			}
			else
			{
				var i=1 ;
				i = i + boxes;
				for(;i<=number_of_articles; i++)
				{
					var box_ls = $jEparcel('.custom_articles_template').clone(); 
					box_ls.removeClass('custom_articles_template');
					box_ls.find('h3').html(box_ls.find('h3').html()+' '+i);
					box_ls.find('#article_description').attr('name','article'+i+'[description]');
					box_ls.find('#article_description').val(box_ls.find('#article_description').val()+' '+i);
					box_ls.find('#article_weight').attr('name','article'+i+'[weight]');
					box_ls.find('#article_height').attr('name','article'+i+'[height]');
					box_ls.find('#article_width').attr('name','article'+i+'[width]');
					box_ls.find('#article_length').attr('name','article'+i+'[length]');
					box_ls.show();
					$jEparcel('#custom_articles_container').append(box_ls);
				}
			}
		}
	});
	
	if($jEparcel('#articles_type').val() == 'Custom')
	{
		$jEparcel('.create-consignment1').hide(); 
		$jEparcel('.backToPreset').hide(); 
		$jEparcel('#custom_articles').show(); 
			
		var number_of_articles = $jEparcel('#number_of_articles').val();
		for(var i=1; i<=number_of_articles; i++)
		{
			var box_ls = $jEparcel('.custom_articles_template').clone(); 
			box_ls.removeClass('custom_articles_template');
			box_ls.find('h3').html(box_ls.find('h3').html()+' '+i);
			box_ls.find('#article_description').attr('name','article'+i+'[description]');
			box_ls.find('#article_description').val(box_ls.find('#article_description').val()+' '+i);
			box_ls.find('#article_weight').attr('name','article'+i+'[weight]');
			box_ls.find('#article_height').attr('name','article'+i+'[height]');
			box_ls.find('#article_width').attr('name','article'+i+'[width]');
			box_ls.find('#article_length').attr('name','article'+i+'[length]');
			box_ls.show();
			$jEparcel('#custom_articles_container').append(box_ls);
		}
	}
	
	$jEparcel('#articles_type').change(function(){
		if($jEparcel(this).val() == 'Custom')
		{
			$jEparcel('#presets').hide(); 
			$jEparcel('#custom_articles').show(); 
			
			var number_of_articles = $jEparcel('#number_of_articles').val();
			for(var i=1; i<=number_of_articles; i++)
			{
				var box_ls = $jEparcel('.custom_articles_template').clone(); 
				box_ls.removeClass('custom_articles_template');
				box_ls.find('h3').html(box_ls.find('h3').html()+' '+i);
				box_ls.find('#article_description').attr('name','article'+i+'[description]');
				box_ls.find('#article_description').val(box_ls.find('#article_description').val()+' '+i);
				box_ls.find('#article_weight').attr('name','article'+i+'[weight]');
				box_ls.find('#article_height').attr('name','article'+i+'[height]');
				box_ls.find('#article_width').attr('name','article'+i+'[width]');
				box_ls.find('#article_length').attr('name','article'+i+'[length]');
				box_ls.show();
				$jEparcel('#custom_articles_container').append(box_ls);
			}
		}
		else
		{
			$jEparcel('#presets').show();
			$jEparcel('#custom_articles').hide(); 
			$jEparcel('#custom_articles_container').html(''); 
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
	$jEparcel('#createConsignmentHidden').val(1);
	
	var valid = true;
	
	var value = $jEparcel.trim($jEparcel('#articles_type').val());
	if(value.length == 0 && valid)
	{
		valid = false;
		alert('Please select article type');
		return false;
	}
	
	if($jEparcel('#product_classification_text').val() == '') {
		
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
	
	if(valid)
	{
		$('#createConsignmentHidden').val(1);
		$jEparcel('#post').submit();
	}
	else
	{
		return false;
	}
}

function submitForm2()
{
	$jEparcel('#createConsignmentHidden').val(1);
	
	var valid = true;
	
	var value = $jEparcel.trim($jEparcel('#articles_type').val());
	if(value.length == 0 && valid)
	{
		valid = false;
		alert('Please select article type');
		return false;
	}
	
	$jEparcel('.required-entry2').each(function(){
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
	
	if(valid)
	{
		$jEparcel('#post').submit();
	}
	else
	{
		return false;
	}
}
</script>