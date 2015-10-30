<div class="wrap woocommerce">
    <form method="post" id="mainform" action="<?php echo admin_url('admin.php?page=linksynceparcel&subpage=assign-shipping-types&action=add'); ?>" enctype="multipart/form-data">
        <div class="icon32 icon32-woocommerce-settings" id="icon-woocommerce"><br /></div>
        <h2>
        	<?php _e('Add Assign Shipping Type','linksynceparcel'); ?>
        	<a class="add-new-h2" href="<?php echo admin_url('admin.php?page=linksynceparcel&subpage=assign-shipping-types'); ?>">Back</a>
		</h2>
        <?php 
		
        if(isset($result)) { 
            echo '<h3 style="color:green">'.$result.'</h3>'; 
        }
		if(isset($error)) { 
            echo '<h4 style="color:red">'.$error.'</h4>'; 
        }
        ?>
        <fieldset style="border:1px solid">
            <table width="100%" border="0" cellspacing="0" cellpadding="6">
            	<tr>
                    <td width="20%" valign="top"><?php _e('Shipping Type','linksynceparcel'); ?></td>
                    <td align="left">
                       <select name="linksynceparcel[shipping_type]" style="width:200px" id="shipping_type">
                             <option value="code" <?php if (LinksynceparcelHelper::getFormValue('shipping_type') != 'desc'){ echo 'selected="selected"'; }?>><?php _e('Shipping Method','linksynceparcel'); ?></option>
                        	<option value="desc" <?php if (LinksynceparcelHelper::getFormValue('shipping_type') == 'desc'){ echo 'selected="selected"'; }?>><?php _e('Shipping Description','linksynceparcel'); ?></option>
                       </select>
                   </td>
              </tr>
                 <tr class="shipping_code" style="<?php if (LinksynceparcelHelper::getFormValue('shipping_type') == 'desc'){?>display:none<?php }?>">
                    <td width="20%" valign="top"><?php _e('Shipping Methods','linksynceparcel'); ?></td>
                    <td align="left">
                       <select name="linksynceparcel[method]" style="width:200px">
                        	<option value="" <?php if (LinksynceparcelHelper::getFormValue('method') == ''){ echo 'selected="selected"'; }?>>
								<?php _e('Please select','linksynceparcel'); ?>
                            </option>
                            <?php 
							foreach($methods as $code => $method)
							{
								if( !( preg_match('/tablerate/',$code) || preg_match('/table_rate/',$code) ) )
								{
							?>
                            <option value="<?php echo $code?>" <?php if (LinksynceparcelHelper::getFormValue('method') == $code){ echo 'selected="selected"'; }?>>
                                <?php echo $method->method_title?>
                            </option>
                            <?php
								}
							}
							?>
                       </select>
                       <br />
                       <span style="margin-left:3px; font-size:11px;">
                       NOTE - when you create a new shipping type for your site IT WILL NOT BE AVAILABLE HERE UNTIL THERE ARE ORDERS USING THE NEW SHIPPING METHOD. <br /> &nbsp;We suggestion you create a test order using the new shipping method and then return to this screen to assign the Charge Code to it.</span>
                   </td>
              </tr>
              
              <tr class="shipping_desc" style="<?php if (LinksynceparcelHelper::getFormValue('shipping_type') != 'desc'){?>display:none<?php }?>">
                    <td width="20%" valign="top"><?php _e('Shipping Description','linksynceparcel'); ?></td>
                    <td align="left">
                       <select name="linksynceparcel[method2]" style="width:200px">
                        	<option value="" <?php if (LinksynceparcelHelper::getFormValue('method') == ''){ echo 'selected="selected"'; }?>>
								<?php _e('Please select','linksynceparcel'); ?>
                            </option>
                            <?php foreach($shipping_titles as $shipping_title) {?>
                            <option value="<?php echo $shipping_title->code?>" <?php if (LinksynceparcelHelper::getFormValue('method') == $shipping_title->code){ echo 'selected="selected"'; }?>>
                                <?php echo $shipping_title->code?>
                            </option>
                            <?php } ?>
                       </select>
                   </td>
              </tr>
              
              <tr>
                    <td width="20%" valign="top"><?php _e('Charge Code','linksynceparcel'); ?></td>
                    <td align="left">
                     	<select name="linksynceparcel[charge_code]" style="width:200px">
                        	<option value="" <?php if (LinksynceparcelHelper::getFormValue('charge_code') == ''){ echo 'selected="selected"'; }?>>
								<?php _e('Please select','linksynceparcel'); ?>
                            </option>
                            <?php 
							foreach($chargeCodes as $code => $codeLabel) {?>
                            <option value="<?php echo $code?>" <?php if (LinksynceparcelHelper::getFormValue('charge_code') == $code){ echo 'selected="selected"'; }?>>
                                <?php echo $code .' - '. $codeLabel['name']; ?>
                            </option>
                            <?php } ?>
                       </select>
                   </td>
              </tr>
          </table>
		</fieldset>
        <br />
        <input type="submit" name="save" value="<?php _e('Save','linksynceparcel'); ?>" class="button-primary" />
    </form>
</div>
<script>
jQuery(document).ready(function() {
	var type = jQuery('#shipping_type').val();
	if(type == 'desc')
	{
		jQuery('.shipping_desc').show();
		jQuery('.shipping_code').hide();
	}
	else
	{
		jQuery('.shipping_desc').hide();
		jQuery('.shipping_code').show();
	}
		
	jQuery('#shipping_type').change(function(){
		var val = jQuery(this).val();
		if(val == 'desc')
		{
			jQuery('.shipping_desc').show();
			jQuery('.shipping_code').hide();
		}
		else
		{
			jQuery('.shipping_desc').hide();
			jQuery('.shipping_code').show();
		}
	});
});
</script>