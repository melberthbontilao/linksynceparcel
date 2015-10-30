<?php if(!LinksynceparcelHelper::isSoapInstalled()) { ?>
	<br /><br />
	<span style="color:red"><strong>PHP Soap extension is not enabled on your server, contact your web hoster to enable this extension.</strong></span>
<?php }else {?>
<style>
.comment{
	font-size:12px;
}
</style>
<link rel="stylesheet" type="text/css" href="<?php echo linksynceparcel_URL?>assets/css/linksynctooltip.css" />
<script type="text/javascript" src="<?php echo linksynceparcel_URL?>assets/js/linksynctooltip.js"></script>
<div class="wrap woocommerce">
    <form method="post" id="mainform" action="<?php echo admin_url('admin.php?page=linksynceparcel&subpage=configuration'); ?>" enctype="multipart/form-data">
        <div class="icon32 icon32-woocommerce-settings" id="icon-woocommerce"><br /></div>
        <h2><img src="<?php echo linksynceparcel_URL?>assets/images/logo.png"/>&nbsp;<?php _e('eParcel Configuration','linksynceparcel'); ?></h2>
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
                    <td width="20%" valign="top"><?php _e('API Key','linksynceparcel'); ?></td>
                    <td align="left" colspan="2">
                       <input type="text" size="40" name="linksynceparcel[laid]" value="<?php echo LinksynceparcelHelper::getFormValue('laid',get_option('linksynceparcel_laid'))?>">
                       <img src="<?php echo linksynceparcel_URL?>assets/images/icon-tooltip.png" class="tooltip" title="The linksync Application ID (LAID) is a unique API Key that's created when you link two apps via the linksync dashboard. You need a valid API Key for this linksync module to work."/>
                       <br />
                        <span class="comment">Note that once you save your API Key it will be permanently linked to (display site URL). If you change the URL of the site, or want to use the API Key on a different site, you’ll need to <a target="_blank" href="https://www.linksync.com/help/support-request">contact linksync support</a> to have them reset the Site URL.</span>
                   </td>
              </tr>
              <tr>
                    <td width="20%" valign="top"><?php _e('eParcel Merchant Location ID','linksynceparcel'); ?></td>
                    <td align="left" colspan="2">
                       <input type="text" size="40" name="linksynceparcel[merchant_location_id]" value="<?php echo LinksynceparcelHelper::getFormValue('merchant_location_id',get_option('linksynceparcel_merchant_location_id'))?>">
                       <img src="<?php echo linksynceparcel_URL?>assets/images/icon-tooltip.png" class="tooltip" title="Issued by Australia Post. A unique three or five alphanumeric character identifier for your geographic merchant location where eParcels are prepared and/or lodged."/>
                       <br />
                        <span class="comment"><?php _e('Changing the Merchant Location ID will result in any consignments not dispatched to become invalid.','linksynceparcel'); ?></span>
                </td>
              </tr>
              <tr>
                    <td width="20%" valign="top"><?php _e('eParcel Post Charge to Account','linksynceparcel'); ?></td>
                    <td align="left" colspan="2">
                       <input type="text" size="40" name="linksynceparcel[post_charge_to_account]" value="<?php echo LinksynceparcelHelper::getFormValue('post_charge_to_account',get_option('linksynceparcel_post_charge_to_account'))?>">
                       <img src="<?php echo linksynceparcel_URL?>assets/images/icon-tooltip.png" class="tooltip" title="Issued by Australia Post. Merchant account to which Australia Post 'post' the charges against for invoicing purposes."/>
                </td>
              </tr>
                <tr>
                    <td width="20%" valign="top"><?php _e('Merchant ID','linksynceparcel'); ?></td>
                    <td align="left" colspan="2">
                       <input type="text" size="40" name="linksynceparcel[merchant_id]" value="<?php echo LinksynceparcelHelper::getFormValue('merchant_id',get_option('linksynceparcel_merchant_id'))?>">
                       <img src="<?php echo linksynceparcel_URL?>assets/images/icon-tooltip.png" class="tooltip" title="Issued by Australia Post. Merchant ID with Australia Post, for inclusion on the Manifest Summary Report. You can find this ID on Manifest Summary reports produced using the eParcel Portal."/>
                  </td>
              </tr>
              <tr>
                    <td width="20%" valign="top"><?php _e('Lodgement facility','linksynceparcel'); ?></td>
                    <td align="left" colspan="2">
                       <input type="text" size="40" name="linksynceparcel[lodgement_facility]" value="<?php echo LinksynceparcelHelper::getFormValue('lodgement_facility',get_option('linksynceparcel_lodgement_facility'))?>">
                       <img src="<?php echo linksynceparcel_URL?>assets/images/icon-tooltip.png" class="tooltip" title="Issued by Australia Post. The Lodgement Facility associated with your Merchant Location ID, for inclusion on the Manifest Summary Report."/>
                </td>
              </tr>
               <tr>
                    <td width="20%" valign="top"><?php _e('SFTP username','linksynceparcel'); ?></td>
                    <td align="left" colspan="2">
                       <input type="text" size="40" name="linksynceparcel[sftp_username]" value="<?php echo LinksynceparcelHelper::getFormValue('sftp_username',get_option('linksynceparcel_sftp_username'))?>">
                       <img src="<?php echo linksynceparcel_URL?>assets/images/icon-tooltip.png" class="tooltip" title="Issued by Australia Post. Your username for uploading manifests to Australia Post SFTP service."/>
                 </td>
              </tr>
               <tr>
                    <td width="20%" valign="top"><?php _e('SFTP password','linksynceparcel'); ?></td>
                    <td align="left" colspan="2">
                       <input type="text" size="40" name="linksynceparcel[sftp_password]" value="<?php echo LinksynceparcelHelper::getFormValue('sftp_password',get_option('linksynceparcel_sftp_password'))?>">
                       <img src="<?php echo linksynceparcel_URL?>assets/images/icon-tooltip.png" class="tooltip" title="Issued by Australia Post. The password associated with your username for uploading manifests to Australia Post SFTP service."/>
                 </td>
              </tr>
			  
			  <tr>
                    <td width="20%" valign="top"><?php _e('LPS username','linksynceparcel'); ?></td>
                    <td align="left" colspan="2">
                       <input type="text" size="40" name="linksynceparcel[lps_username]" value="<?php echo LinksynceparcelHelper::getFormValue('lps_username',get_option('linksynceparcel_lps_username'))?>">
                       <img src="<?php echo linksynceparcel_URL?>assets/images/icon-tooltip.png" class="tooltip" title="The LPS username relate to the Australia Post Label Printing Service - please contact linksync for further information."/>
                 </td>
              </tr>
               <tr>
                    <td width="20%" valign="top"><?php _e('LPS password','linksynceparcel'); ?></td>
                    <td align="left" colspan="2">
                       <input type="text" size="40" name="linksynceparcel[lps_password]" value="<?php echo LinksynceparcelHelper::getFormValue('lps_password',get_option('linksynceparcel_lps_password'))?>">
                       <img src="<?php echo linksynceparcel_URL?>assets/images/icon-tooltip.png" class="tooltip" title="The LPS password relate to the Australia Post Label Printing Service - please contact linksync for further information."/>
                 </td>
              </tr>
             
              <tr>
                <td width="20%" valign="top"><?php _e('Operation Mode','linksynceparcel'); ?></td>
                <td align="left" colspan="2">
                    <select name="linksynceparcel[operation_mode]">
                        <option value="0" <?php if (LinksynceparcelHelper::getFormValue('operation_mode',get_option('linksynceparcel_operation_mode')) != 1){ echo 'selected="selected"'; }?>><?php _e('Test','linksynceparcel'); ?></option>
                        <option value="1" <?php if (LinksynceparcelHelper::getFormValue('operation_mode',get_option('linksynceparcel_operation_mode')) == 1){ echo 'selected="selected"'; }?>><?php _e('Live','linksynceparcel'); ?></option>
                  </select>
                  <img src="<?php echo linksynceparcel_URL?>assets/images/icon-tooltip.png" class="tooltip" title="Test mode enables you to use and test all features of the linksync eParcel for WooCommerce module without actually submitting a manifest to Australia Post on despatch of a manifest. Live mode will upload your manifest to Australia Post SFTP server on despatch of a manifest."/>
                </td>
              </tr>
              
              <tr>
                    <td width="20%" valign="top"></td>
                    <td align="left" valign="top">
                       <input type="file" name="label_logo">
                       <?php 
					   if(get_option('linksynceparcel_label_logo') && file_exists(linksynceparcel_DIR.'assets/images/'.get_option('linksynceparcel_label_logo')))
					   {
						?>
                        <img src="<?php echo linksynceparcel_URL.'assets/images/'.get_option('linksynceparcel_label_logo')?>" width="30" height="30" />
                        <?php
					   }
					   ?>
                       <img src="<?php echo linksynceparcel_URL?>assets/images/icon-tooltip.png" class="tooltip" title="Use this option if you want to display your own logo on eParcel labels. The logo will not be used if you are printing labels to pre-printed eParcel labels."/>
                        <?php 
					   if(get_option('linksynceparcel_label_logo') && file_exists(linksynceparcel_DIR.'assets/images/'.get_option('linksynceparcel_label_logo')))
					   {
						?>
                        <br />
                       <input type="checkbox" value="1" name="delete_label_logo" <?php if (isset($_REQUEST['delete_label_logo'])){ echo 'checked="checked"'; }?>/><span style="font-size:12px">delete</span>
                        <?php
					   }
					   ?>
                       
                       <br />
                       <span class="comment"><?php _e('Image should be 160px wide by 65px tall PNG format.','linksynceparcel'); ?></span>
                </td>
              </tr>
              <tr>
                    <td width="20%" valign="top"><?php _e('Return Contact Name','linksynceparcel'); ?></td>
                    <td align="left" colspan="2">
                       <input type="text" size="40" name="linksynceparcel[return_address_name]" value="<?php echo LinksynceparcelHelper::getFormValue('return_address_name',get_option('linksynceparcel_return_address_name'))?>">
                       <img src="<?php echo linksynceparcel_URL?>assets/images/icon-tooltip.png" class="tooltip" title="The company name you want to appear on the 'Sender' section of the eParcel labels and 'Customer Address' on Manifest Summary Reports, as well as the 'Delivery Address' on Return Labels."/>
                </td>
              </tr>
			  <tr>
                    <td width="20%" valign="top"><?php _e('Return Business Name','linksynceparcel'); ?></td>
                    <td align="left" colspan="2">
                       <input type="text" size="40" name="linksynceparcel[return_business_name]" value="<?php echo LinksynceparcelHelper::getFormValue('return_business_name',get_option('linksynceparcel_return_business_name'))?>">
                       <img src="<?php echo linksynceparcel_URL?>assets/images/icon-tooltip.png" class="tooltip" title="The business name you want to appear in the return address information on labels."/>
                </td>
              </tr>
			  <tr>
                    <td width="20%" valign="top"><?php _e('Return Email Address','linksynceparcel'); ?></td>
                    <td align="left" colspan="2">
                       <input type="text" size="40" name="linksynceparcel[return_email_address]" value="<?php echo LinksynceparcelHelper::getFormValue('return_email_address',get_option('linksynceparcel_return_email_address'))?>">
                       <img src="<?php echo linksynceparcel_URL?>assets/images/icon-tooltip.png" class="tooltip" title="The email address you want to appear in the return address information on labels."/>
                </td>
              </tr>
			  <tr>
                    <td width="20%" valign="top"><?php _e('Return Phone Number','linksynceparcel'); ?></td>
                    <td align="left" colspan="2">
                       <input type="text" size="40" name="linksynceparcel[return_phone_number]" value="<?php echo LinksynceparcelHelper::getFormValue('return_phone_number',get_option('linksynceparcel_return_phone_number'))?>">
                       <img src="<?php echo linksynceparcel_URL?>assets/images/icon-tooltip.png" class="tooltip" title="The phone number you want to appear in the return address information on labels."/>
                </td>
              </tr>
              <tr>
                    <td width="20%" valign="top"><?php _e('Return Address Line 1','linksynceparcel'); ?></td>
                    <td align="left" colspan="2">
                       <input type="text" size="40" name="linksynceparcel[return_address_line1]" value="<?php echo LinksynceparcelHelper::getFormValue('return_address_line1',get_option('linksynceparcel_return_address_line1'))?>">
                       <img src="<?php echo linksynceparcel_URL?>assets/images/icon-tooltip.png" class="tooltip" title="The address you want to appear on the 'Sender' section of the eParcel labels and 'Customer Address' on Manifest Summary Reports, as well as the 'Delivery Address' on Return Labels."/>
                </td>
              </tr>
               <tr>
                    <td width="20%" valign="top"><?php _e('Return Address Line 2','linksynceparcel'); ?></td>
                    <td align="left" colspan="2">
                       <input type="text" size="40" name="linksynceparcel[return_address_line2]" value="<?php echo LinksynceparcelHelper::getFormValue('return_address_line2',get_option('linksynceparcel_return_address_line2'))?>">
                 </td>
              </tr>
               <tr>
                    <td width="20%" valign="top"><?php _e('Return Address Line 3','linksynceparcel'); ?></td>
                    <td align="left" colspan="2">
                       <input type="text" size="40" name="linksynceparcel[return_address_line3]" value="<?php echo LinksynceparcelHelper::getFormValue('return_address_line3',get_option('linksynceparcel_return_address_line3'))?>">
                 </td>
              </tr>
               <tr>
                    <td width="20%" valign="top"><?php _e('Return Address Line 4','linksynceparcel'); ?></td>
                    <td align="left" colspan="2">
                       <input type="text" size="40" name="linksynceparcel[return_address_line4]" value="<?php echo LinksynceparcelHelper::getFormValue('return_address_line4',get_option('linksynceparcel_return_address_line4'))?>">
                 </td>
              </tr>
              <tr>
                    <td width="20%" valign="top"><?php _e('Return Address Suburb','linksynceparcel'); ?></td>
                    <td align="left" colspan="2">
                       <input type="text" size="40" name="linksynceparcel[return_address_suburb]" value="<?php echo LinksynceparcelHelper::getFormValue('return_address_suburb',get_option('linksynceparcel_return_address_suburb'))?>">
                       <img src="<?php echo linksynceparcel_URL?>assets/images/icon-tooltip.png" class="tooltip" title="The suburb you want to appear on the 'Sender' section of the eParcel labels and 'Customer Address' on Manifest Summary Reports, as well as the 'Delivery Address' on Return Labels."/>
                </td>
              </tr>
              <tr>
                    <td width="20%" valign="top"><?php _e('Return Address Postcode','linksynceparcel'); ?></td>
                    <td align="left" colspan="2">
                       <input type="text" size="40" name="linksynceparcel[return_address_postcode]" value="<?php echo LinksynceparcelHelper::getFormValue('return_address_postcode',get_option('linksynceparcel_return_address_postcode'))?>">
                       <img src="<?php echo linksynceparcel_URL?>assets/images/icon-tooltip.png" class="tooltip" title="Enter 4 digit post code. The postcode you want to appear on the 'Sender' section of the eParcel labels and 'Customer Address' on Manifest Summary Reports, as well as the 'Delivery Address' on Return Labels."/>
                </td>
              </tr>
              <tr>
                    <td width="20%" valign="top"><?php _e('Return Address State code','linksynceparcel'); ?></td>
                    <td align="left" colspan="2">
                       <select name="linksynceparcel[return_address_statecode]">
                       		<option value="" <?php if (LinksynceparcelHelper::getFormValue('return_address_statecode',get_option('linksynceparcel_return_address_statecode')) == ''){ echo 'selected="selected"'; }?>>
								<?php _e('Please select','linksynceparcel'); ?>
                            </option>
                            <?php foreach($states as $code => $state) {?>
                            <option value="<?php echo $code?>" <?php if (LinksynceparcelHelper::getFormValue('return_address_statecode',get_option('linksynceparcel_return_address_statecode')) == $code){ echo 'selected="selected"'; }?>>
                                <?php echo $state?>
                            </option>
                            <?php } ?>
                      </select>
                      <img src="<?php echo linksynceparcel_URL?>assets/images/icon-tooltip.png" class="tooltip" title="The State you want to appear on the 'Sender' section of the eParcel labels and 'Customer Address' on Manifest Summary Reports, as well as the 'Delivery Address' on Return Labels."/>
                </td>
              </tr>
              <tr>
                    <td width="20%" valign="top"><?php _e('Insurance','linksynceparcel'); ?></td>
                    <td align="left" colspan="2">
                       <select name="linksynceparcel[insurance]">
                            <option value="0" <?php if (LinksynceparcelHelper::getFormValue('insurance',get_option('linksynceparcel_insurance')) != 1){ echo 'selected="selected"'; }?>><?php _e('No','linksynceparcel'); ?></option>
                            <option value="1" <?php if (LinksynceparcelHelper::getFormValue('insurance',get_option('linksynceparcel_insurance')) == 1){ echo 'selected="selected"'; }?>><?php _e('Yes','linksynceparcel'); ?></option>
                      </select>
                       <img src="<?php echo linksynceparcel_URL?>assets/images/icon-tooltip.png" class="tooltip" title="Add insurance to consignment articles by default? This default can be overridden when creating consignments/articles."/>
                </td>
              </tr>
              <tr>
                    <td width="20%" valign="top"><?php _e('Default Insurance value','linksynceparcel'); ?></td>
                    <td align="left" colspan="2">
                       <input type="text" size="40" name="linksynceparcel[default_insurance_value]" value="<?php echo LinksynceparcelHelper::getFormValue('default_insurance_value',get_option('linksynceparcel_default_insurance_value'))?>">
                       <img src="<?php echo linksynceparcel_URL?>assets/images/icon-tooltip.png" class="tooltip" title="Default insurance value to add to consignment articles. This default can be overridden when creating consignments/articles."/>
                </td>
              </tr>
              <tr>
                    <td width="20%" valign="top"><?php _e('Signature Required','linksynceparcel'); ?></td>
                    <td align="left" colspan="2">
                        <select name="linksynceparcel[signature_required]">
                            <option value="0" <?php if (LinksynceparcelHelper::getFormValue('signature_required',get_option('linksynceparcel_signature_required')) != 1){ echo 'selected="selected"'; }?>><?php _e('No','linksynceparcel'); ?></option>
                            <option value="1" <?php if (LinksynceparcelHelper::getFormValue('signature_required',get_option('linksynceparcel_signature_required')) == 1){ echo 'selected="selected"'; }?>><?php _e('Yes','linksynceparcel'); ?></option>
                      </select>
                      <img src="<?php echo linksynceparcel_URL?>assets/images/icon-tooltip.png" class="tooltip" title="Default setting for 'Signature Required' on consignments. This default can be overridden when creating consignments. Note: if set to 'No', customers will be prompted to confirm that they authorise their delivery to be left if no one is available to sign for it, and then be required to enter special instructions. eg 'leave at side door' - these instructions will show on labels associated with the consignment for the order."/>
                </td>
              </tr>
			  <tr>
                    <td width="20%" valign="top"><?php _e('Safe Drop','linksynceparcel'); ?></td>
                    <td align="left" colspan="2">
                        <select name="linksynceparcel[safe_drop]">
                            <option value="0" <?php if (LinksynceparcelHelper::getFormValue('safe_drop',get_option('linksynceparcel_safe_drop')) != 1){ echo 'selected="selected"'; }?>><?php _e('No','linksynceparcel'); ?></option>
                            <option value="1" <?php if (LinksynceparcelHelper::getFormValue('safe_drop',get_option('linksynceparcel_safe_drop')) == 1){ echo 'selected="selected"'; }?>><?php _e('Yes','linksynceparcel'); ?></option>
                      </select>
                      <img src="<?php echo linksynceparcel_URL?>assets/images/icon-tooltip.png" class="tooltip" title='Your customers can interact "in flight" with their "Signature on Delivery" domestic eParcel service and request that Australia Post leave the parcel in a Safe Place (Authority To Leave) without a signature. Select No to disable this option.'/>
                </td>
              </tr>
                  <tr>
                    <td width="20%" valign="top"><?php _e('Print Return Labels','linksynceparcel'); ?></td>
                    <td align="left" colspan="2">
                        <select name="linksynceparcel[print_return_labels]">
                            <option value="0" <?php if (LinksynceparcelHelper::getFormValue('print_return_labels',get_option('linksynceparcel_print_return_labels')) != 1){ echo 'selected="selected"'; }?>><?php _e('No','linksynceparcel'); ?></option>
                            <option value="1" <?php if (LinksynceparcelHelper::getFormValue('print_return_labels',get_option('linksynceparcel_print_return_labels')) == 1){ echo 'selected="selected"'; }?>><?php _e('Yes','linksynceparcel'); ?></option>
                      </select>
                      <img src="<?php echo linksynceparcel_URL?>assets/images/icon-tooltip.png" class="tooltip" title="Default setting for printing return labels for consignments. This default can be overridden when creating consignments."/>
                    </td>
              </tr>
                  <tr>
                    <td width="20%" valign="top"><?php _e('Partial Delivery Allowed','linksynceparcel'); ?></td>
                    <td align="left" colspan="2">
                        <select name="linksynceparcel[partial_delivery_allowed]">
                            <option value="0" <?php if (LinksynceparcelHelper::getFormValue('partial_delivery_allowed',get_option('linksynceparcel_partial_delivery_allowed')) != 1){ echo 'selected="selected"'; }?>><?php _e('No','linksynceparcel'); ?></option>
                            <option value="1" <?php if (LinksynceparcelHelper::getFormValue('partial_delivery_allowed',get_option('linksynceparcel_partial_delivery_allowed')) == 1){ echo 'selected="selected"'; }?>><?php _e('Yes','linksynceparcel'); ?></option>
                      </select>
                      <img src="<?php echo linksynceparcel_URL?>assets/images/icon-tooltip.png" class="tooltip" title="Default setting for specifying if partial delivery is allowed for consignments. This default can be overridden when creating consignments."/>
                    </td>
              </tr>
                  <tr>
                    <td width="20%" valign="top"><?php _e('Australia Post email notification','linksynceparcel'); ?></td>
                    <td align="left" colspan="2">
                        <select name="linksynceparcel[post_email_notification]">
                            <option value="0" <?php if (LinksynceparcelHelper::getFormValue('post_email_notification',get_option('linksynceparcel_post_email_notification')) != 1){ echo 'selected="selected"'; }?>><?php _e('No','linksynceparcel'); ?></option>
                            <option value="1" <?php if (LinksynceparcelHelper::getFormValue('post_email_notification',get_option('linksynceparcel_post_email_notification')) == 1){ echo 'selected="selected"'; }?>><?php _e('Yes','linksynceparcel'); ?></option>
                      </select>
                      <img src="<?php echo linksynceparcel_URL?>assets/images/icon-tooltip.png" class="tooltip" title="Default setting for using Australia Post's email notification service on consignments. This default can be overridden when creating consignments."/>
                    </td>
              </tr>
				<tr>
                    <td width="20%" valign="top"><?php _e('Order value as Declared Value','linksynceparcel'); ?></td>
					<td align="left" colspan="2">
						<?php 
							$declared_checked = '';
							if (LinksynceparcelHelper::getFormValue('declared_value',get_option('linksynceparcel_declared_value')) == 1){ 
								$declared_checked = 'checked="checked"';
							}
						?>
						<input type="checkbox" id="declared_value" name="linksynceparcel[declared_value]" value="1" <?php echo $declared_checked; ?>>
					</td>
				</tr>
				<?php 
					$declared_text = '';
					$declared_text_option = 'hide-tr';
					if ($declared_checked == ''){ 
						$declared_text = LinksynceparcelHelper::getFormValue('declared_value_text',get_option('linksynceparcel_declared_value_text'));
						$declared_text_option = 'show-tr';
					}
				?>
				<tr id="declared_value_text" class="<?php echo $declared_text_option; ?>">
                    <td width="20%" valign="top"></td>
					<td align="left" colspan="2">
						<input type="number" class="declared_value_text" name="linksynceparcel[declared_value_text]" value="<?php echo $declared_text; ?>"><br />
						<span class="comment"><?php _e("Please input integer.",'linksynceparcel'); ?></span>
					</td>
				</tr>
				<tr>
                    <td width="20%" valign="top"><?php _e('Has Commercial Value','linksynceparcel'); ?></td>
					<td align="left" colspan="2">
						<?php
							$commercial_checked = '';
							if (LinksynceparcelHelper::getFormValue('has_commercial_value',get_option('linksynceparcel_has_commercial_value')) == 1){ 
								$commercial_checked = 'checked="checked"';
							}
						?>
						<input type="checkbox" id="has_commercial_value" name="linksynceparcel[has_commercial_value]" value="1" <?php echo $commercial_checked; ?>>
					</td>
				</tr>
				<tr>
                    <td width="20%" valign="top"><?php _e('Default Product Classification','linksynceparcel'); ?></td>
                    <td align="left" colspan="2">
						<?php
							$product_classification_disable = '';
							$product_classification_value = LinksynceparcelHelper::getFormValue('product_classification',get_option('linksynceparcel_product_classification'));
							if($commercial_checked != '') {
								$product_classification_disable = 'disabled="true"';
								$product_classification_value = '991';
							}
						?>
                        <select id="product_classification" name="linksynceparcel[product_classification]" <?php echo $product_classification_disable; ?>>
                            <option value="991" <?php if ($product_classification_value == '991'){ echo 'selected="selected"'; }?>><?php _e('Other','linksynceparcel'); ?></option>
                            <option value="32" <?php if ($product_classification_value == '32'){ echo 'selected="selected"'; }?>><?php _e('Commercial','linksynceparcel'); ?></option>
							<option value="31" <?php if ($product_classification_value == '31'){ echo 'selected="selected"'; }?>><?php _e('Gift','linksynceparcel'); ?></option>
							<option value="91" <?php if ($product_classification_value == '91'){ echo 'selected="selected"'; }?>><?php _e('Document','linksynceparcel'); ?></option>
						</select>
					</td>
				</tr>
				<?php 
					$product_classification_text = '';
					$product_classification_option = 'hide-tr';
					if ($product_classification_value == '991'){ 
						$product_classification_text = LinksynceparcelHelper::getFormValue('product_classification_text',get_option('linksynceparcel_product_classification_text'));
						$product_classification_option = 'show-tr';
					}
				?>
				<tr id="product_classification_text" class="<?php echo $product_classification_option; ?>">
                    <td width="20%" valign="top" ><?php _e('Classification Explanation','linksynceparcel'); ?></td>
					<td align="left" colspan="2">
						<input type="text" class="product_classification_text" name="linksynceparcel[product_classification_text]" value="<?php echo $product_classification_text; ?>">
					</td>
				</tr>
				<tr>
                    <td width="20%" valign="top"><?php _e('Default Country of Origin','linksynceparcel'); ?></td>
                    <td align="left" colspan="2">
                       <select name="linksynceparcel[country_origin]">
                       		<option value="" <?php if (LinksynceparcelHelper::getFormValue('country_origin',get_option('linksynceparcel_country_origin')) == ''){ echo 'selected="selected"'; }?>>
								<?php _e('Please select','linksynceparcel'); ?>
                            </option>
                            <?php foreach($countries as $code => $name) {?>
                            <option value="<?php echo $code; ?>" <?php if (LinksynceparcelHelper::getFormValue('country_origin',get_option('linksynceparcel_country_origin')) == $code){ echo 'selected="selected"'; }?>>
                                <?php echo $name; ?>
                            </option>
                            <?php } ?>
                      </select>
					</td>
				</tr>
				<tr>
                    <td width="20%" valign="top" ><?php _e('Default HS Tariff','linksynceparcel'); ?></td>
					<td align="left" colspan="2">
						<input type="number" name="linksynceparcel[hs_tariff]" value="<?php echo LinksynceparcelHelper::getFormValue('hs_tariff',get_option('linksynceparcel_hs_tariff')); ?>" min="7" max="11"><br/>
						<span class="comment"><a target="_blank" href="http://www.foreign-trade.com/reference/hscode.htm"><?php _e("Click here for HS Tariff list",'linksynceparcel'); ?></a></span>
					</td>
				</tr>
				<tr>
                    <td width="20%" valign="top" ><?php _e('Default Contents','linksynceparcel'); ?></td>
					<td align="left" colspan="2">
						<input type="text" name="linksynceparcel[default_contents]" value="<?php echo LinksynceparcelHelper::getFormValue('default_contents',get_option('linksynceparcel_default_contents')); ?>">
					</td>
				</tr>
				<tr>
					<td width="25%" valign="top" ><strong><?php _e('Service','linksynceparcel'); ?></strong></td>
					<td width="25%" valign="top" ><strong><?php _e('Label Type','linksynceparcel'); ?></strong></td>
					<td width="25%" valign="top" ><strong><?php _e('Left Offset','linksynceparcel'); ?></strong></td>
					<td width="25%" valign="top" ><strong><?php _e('Right Offset','linksynceparcel'); ?></strong></td>
				</tr>
				<tr>
					<td width="25%" valign="top" ><?php _e('Parcel Post','linksynceparcel'); ?></td>
					<td width="25%" align="left" >
						<select name="linksynceparcel[parcel_post_label]">
						<?php
							$parcel_post_label = LinksynceparcelHelper::getFormValue('parcel_post_label',get_option('linksynceparcel_parcel_post_label'));
						?>
                            <option value="A4-4pp_1" <?php if($parcel_post_label=='A4-4pp_1'){ echo "selected='selected'"; }?>> A4 plain </option>
                            <option value="A4-4pp_0" <?php if($parcel_post_label=='A4-4pp_0'){ echo "selected='selected'"; }?>> A4 pre-printed </option>
                            <option value="THERMAL LABEL-1PP_1" <?php if($parcel_post_label=='THERMAL LABEL-1PP_1'){ echo "selected='selected'"; }?>> Single plain </option>
                            <option value="THERMAL LABEL-1PP_0" <?php if($parcel_post_label=='THERMAL LABEL-1PP_0'){ echo "selected='selected'"; }?>> Single pre-printed </option>
                    	</select>
					</td>
					<td width="25%" align="left" >
					<?php
						$parcel_post_left_offset = LinksynceparcelHelper::getFormValue('parcel_post_left_offset',get_option('linksynceparcel_parcel_post_left_offset'));
					?>
						<input type="number" name="linksynceparcel[parcel_post_left_offset]" value="<?php echo $parcel_post_left_offset; ?>" min="-999" max="999">&nbsp;mm
					</td>
					<td width="25%" align="left" >
					<?php
						$parcel_post_right_offset = LinksynceparcelHelper::getFormValue('parcel_post_right_offset',get_option('linksynceparcel_parcel_post_right_offset'));
					?>
						<input type="number" name="linksynceparcel[parcel_post_right_offset]" value="<?php echo $parcel_post_right_offset; ?>" min="-999" max="999">&nbsp;mm
					</td>
				</tr>
				<tr>
					<td width="25%" valign="top" ><?php _e('Express Post','linksynceparcel'); ?></td>
					<td width="25%" align="left" >
						<select name="linksynceparcel[express_post_label]">
						<?php
							$express_post_label = LinksynceparcelHelper::getFormValue('express_post_label',get_option('linksynceparcel_express_post_label'));
						?>
                            <option value="A4-3pp_1" <?php if($express_post_label=='A4-3pp_1'){ echo "selected='selected'"; }?>> A4 plain </option>
                            <option value="A4-3pp_0" <?php if($express_post_label=='A4-3pp_0'){ echo "selected='selected'"; }?>> A4 pre-printed </option>
                            <option value="THERMAL LABEL-1PP_0" <?php if($express_post_label=='THERMAL LABEL-1PP_0'){ echo "selected='selected'"; }?>> Single pre-printed </option>
                    	</select>
					</td>
					<td width="25%" align="left" >
					<?php
						$express_post_left_offset = LinksynceparcelHelper::getFormValue('express_post_left_offset',get_option('linksynceparcel_express_post_left_offset'));
					?>
						<input type="number" name="linksynceparcel[express_post_left_offset]" value="<?php echo $express_post_left_offset; ?>" min="-999" max="999">&nbsp;mm
					</td>
					<td width="25%" align="left" >
					<?php
						$express_post_right_offset = LinksynceparcelHelper::getFormValue('express_post_right_offset',get_option('linksynceparcel_express_post_right_offset'));
					?>
						<input type="number" name="linksynceparcel[express_post_right_offset]" value="<?php echo $express_post_right_offset; ?>" min="-999" max="999">&nbsp;mm
					</td>
				</tr>
				<tr>
					<td width="25%" valign="top" ><?php _e('Int. Economy Air','linksynceparcel'); ?></td>
					<td width="25%" align="left" >
						<select name="linksynceparcel[int_economy_label]">
						<?php
							$int_economy_label = LinksynceparcelHelper::getFormValue('int_economy_label',get_option('linksynceparcel_int_economy_label'));
						?>
                            <option value="A4-4pp_1" <?php if($int_economy_label=='A4-4pp_1'){ echo "selected='selected'"; }?>> A4 plain </option>
                            <option value="THERMAL LABEL-1PP_1" <?php if($int_economy_label=='THERMAL LABEL-1PP_1'){ echo "selected='selected'"; }?>> Single plain </option>
                    	</select>
					</td>
					<td width="25%" align="left" >
					<?php
						$int_economy_left_offset = LinksynceparcelHelper::getFormValue('int_economy_left_offset',get_option('linksynceparcel_int_economy_left_offset'));
					?>
						<input type="number" name="linksynceparcel[int_economy_left_offset]" value="<?php echo $int_economy_left_offset; ?>" min="-999" max="999">&nbsp;mm
					</td>
					<td width="25%" align="left" >
					<?php
						$int_economy_right_offset = LinksynceparcelHelper::getFormValue('int_economy_right_offset',get_option('linksynceparcel_int_economy_right_offset'));
					?>
						<input type="number" name="linksynceparcel[int_economy_right_offset]" value="<?php echo $int_economy_right_offset; ?>" min="-999" max="999">&nbsp;mm
					</td>
				</tr>
				<tr>
					<td width="25%" valign="top" ><?php _e('Int. Express Courier','linksynceparcel'); ?></td>
					<td width="25%" align="left" >
						<select name="linksynceparcel[int_express_courier_label]">
						<?php
							$int_express_courier_label = LinksynceparcelHelper::getFormValue('int_express_courier_label',get_option('linksynceparcel_int_express_courier_label'));
						?>
                            <option value="A4-4pp_1" <?php if($int_express_courier_label=='A4-4pp_1'){ echo "selected='selected'"; }?>> A4 plain </option>
                            <option value="THERMAL LABEL-1PP_1" <?php if($int_express_courier_label=='THERMAL LABEL-1PP_1'){ echo "selected='selected'"; }?>> Single plain </option>
                    	</select>
					</td>
					<td width="25%" align="left" >
					<?php
						$int_express_courier_left_offset = LinksynceparcelHelper::getFormValue('int_express_courier_left_offset',get_option('linksynceparcel_int_express_courier_left_offset'));
					?>
						<input type="number" name="linksynceparcel[int_express_courier_left_offset]" value="<?php echo $int_express_courier_left_offset; ?>" min="-999" max="999">&nbsp;mm
					</td>
					<td width="25%" align="left" >
					<?php
						$int_express_courier_right_offset = LinksynceparcelHelper::getFormValue('int_express_courier_right_offset',get_option('linksynceparcel_int_express_courier_right_offset'));
					?>
						<input type="number" name="linksynceparcel[int_express_courier_right_offset]" value="<?php echo $int_express_courier_right_offset; ?>" min="-999" max="999">&nbsp;mm
					</td>
				</tr>
				<tr>
					<td width="25%" valign="top" ><?php _e('Int. Express Post','linksynceparcel'); ?></td>
					<td width="25%" align="left" >
						<select name="linksynceparcel[int_express_post_label]">
						<?php
							$int_express_post_label = LinksynceparcelHelper::getFormValue('int_express_post_label',get_option('linksynceparcel_int_express_post_label'));
						?>
                            <option value="A4-4pp_1" <?php if($int_express_post_label=='A4-4pp_1'){ echo "selected='selected'"; }?>> A4 plain </option>
                            <option value="THERMAL LABEL-1PP_1" <?php if($int_express_post_label=='THERMAL LABEL-1PP_1'){ echo "selected='selected'"; }?>> Single plain </option>
                    	</select>
					</td>
					<td width="25%" align="left" >
					<?php
						$int_express_post_left_offset = LinksynceparcelHelper::getFormValue('int_express_post_left_offset',get_option('linksynceparcel_int_express_post_left_offset'));
					?>
						<input type="number" name="linksynceparcel[int_express_post_left_offset]" value="<?php echo $int_express_post_left_offset; ?>" min="-999" max="999">&nbsp;mm
					</td>
					<td width="25%" align="left" >
					<?php
						$int_express_post_right_offset = LinksynceparcelHelper::getFormValue('int_express_post_right_offset',get_option('linksynceparcel_int_express_post_right_offset'));
					?>
						<input type="number" name="linksynceparcel[int_express_post_right_offset]" value="<?php echo $int_express_post_right_offset; ?>" min="-999" max="999">&nbsp;mm
					</td>
				</tr>
				<tr>
					<td width="25%" valign="top" ><?php _e('Int. Pack & Track','linksynceparcel'); ?></td>
					<td width="25%" align="left" >
						<select name="linksynceparcel[int_pack_track_label]">
						<?php
							$int_pack_track_label = LinksynceparcelHelper::getFormValue('int_pack_track_label',get_option('linksynceparcel_int_pack_track_label'));
						?>
                            <option value="A4-4pp_1" <?php if($int_pack_track_label=='A4-4pp_1'){ echo "selected='selected'"; }?>> A4 plain </option>
                            <option value="THERMAL LABEL-1PP_1" <?php if($int_pack_track_label=='THERMAL LABEL-1PP_1'){ echo "selected='selected'"; }?>> Single plain </option>
                    	</select>
					</td>
					<td width="25%" align="left" >
					<?php
						$int_pack_track_left_offset = LinksynceparcelHelper::getFormValue('int_pack_track_left_offset',get_option('linksynceparcel_int_pack_track_left_offset'));
					?>
						<input type="number" name="linksynceparcel[int_pack_track_left_offset]" value="<?php echo $int_pack_track_left_offset; ?>" min="-999" max="999">&nbsp;mm
					</td>
					<td width="25%" align="left" >
					<?php
						$int_pack_track_right_offset = LinksynceparcelHelper::getFormValue('int_pack_track_right_offset',get_option('linksynceparcel_int_pack_track_right_offset'));
					?>
						<input type="number" name="linksynceparcel[int_pack_track_right_offset]" value="<?php echo $int_pack_track_right_offset; ?>" min="-999" max="999">&nbsp;mm
					</td>
				</tr>
				<tr>
					<td width="25%" valign="top" ><?php _e('Int. Registered','linksynceparcel'); ?></td>
					<td width="25%" align="left" >
						<select name="linksynceparcel[int_registered_label]">
						<?php
							$int_registered_label = LinksynceparcelHelper::getFormValue('int_registered_label',get_option('linksynceparcel_int_registered_label'));
						?>
                            <option value="A4-4pp_1" <?php if($int_registered_label=='A4-4pp_1'){ echo "selected='selected'"; }?>> A4 plain </option>
                            <option value="THERMAL LABEL-1PP_1" <?php if($int_registered_label=='THERMAL LABEL-1PP_1'){ echo "selected='selected'"; }?>> Single plain </option>
                    	</select>
					</td>
					<td width="25%" align="left" >
					<?php
						$int_registered_left_offset = LinksynceparcelHelper::getFormValue('int_registered_left_offset',get_option('linksynceparcel_int_registered_left_offset'));
					?>
						<input type="number" name="linksynceparcel[int_registered_left_offset]" value="<?php echo $int_registered_left_offset; ?>" min="-999" max="999">&nbsp;mm
					</td>
					<td width="25%" align="left" >
					<?php
						$int_registered_right_offset = LinksynceparcelHelper::getFormValue('int_registered_right_offset',get_option('linksynceparcel_int_registered_right_offset'));
					?>
						<input type="number" name="linksynceparcel[int_registered_right_offset]" value="<?php echo $int_registered_right_offset; ?>" min="-999" max="999">&nbsp;mm
					</td>
				</tr>
				<tr>
					<td colspan="4" valign="top" ><a target="_blank" href="https://www.linksync.com/help/labeltypes">Click here for an explanation of each Label Type.</a></td>
				</tr>
                <tr>
                    <td width="20%" valign="top"><?php _e('Label Format','linksynceparcel'); ?></td>
                    <td align="left" colspan="2">
                    	<select name="linksynceparcel[label_format]">
                            <?php foreach($formats as $format) {?>
                            <option value="<?php echo $format?>" <?php if (LinksynceparcelHelper::getFormValue('label_format',get_option('linksynceparcel_label_format')) == $format){ echo 'selected="selected"'; }?>>
                                <?php echo $format?>
                            </option>
                            <?php } ?>
                    	</select>
                       <img src="<?php echo linksynceparcel_URL?>assets/images/icon-tooltip.png" class="tooltip" title="Chose the format used for creating shipping and return labels. Options are:<br/>&nbsp;SP - single plain - one A6 sized label per 'page'<br/>&nbsp;SPP - single pre-printed -one A6 sized label per 'page'<br/>&nbsp;MP - multi plain - four A6 sized labels per A4 'page'<br/>&nbsp;MPP - multi pre-printed - four A6 sized labels per A4 'page'"/>
                  </td>
              </tr>
              
              <tr>
                    <td width="20%" valign="top"><?php _e('Copy order notes to label?','linksynceparcel'); ?></td>
                    <td align="left" colspan="2">
                        <select name="linksynceparcel[copy_order_notes]">
                            <option value="0" <?php if (LinksynceparcelHelper::getFormValue('copy_order_notes',get_option('linksynceparcel_copy_order_notes')) != 1){ echo 'selected="selected"'; }?>><?php _e('No','linksynceparcel'); ?></option>
                            <option value="1" <?php if (LinksynceparcelHelper::getFormValue('copy_order_notes',get_option('linksynceparcel_copy_order_notes')) == 1){ echo 'selected="selected"'; }?>><?php _e('Yes','linksynceparcel'); ?></option>
                      </select><br />
                        <span class="comment"><?php _e("If set to yes, then order notes will be copied to label",'linksynceparcel'); ?></span>
                </td>
              </tr>
              
              <tr>
                    <td width="20%" valign="top"><?php _e('Use order total weight','linksynceparcel'); ?></td>
                    <td align="left" colspan="2">
                        <select name="linksynceparcel[use_order_weight]" id="use_order_weight">
                            <option value="0" <?php if (LinksynceparcelHelper::getFormValue('use_order_weight',get_option('linksynceparcel_use_order_weight')) != 1){ echo 'selected="selected"'; }?>><?php _e('No','linksynceparcel'); ?></option>
                            <option value="1" <?php if (LinksynceparcelHelper::getFormValue('use_order_weight',get_option('linksynceparcel_use_order_weight')) == 1){ echo 'selected="selected"'; }?>><?php _e('Yes','linksynceparcel'); ?></option>
                      </select>
                      <img src="<?php echo linksynceparcel_URL?>assets/images/icon-tooltip.png" class="tooltip" title="If your products in WooCommerce have weights associated with them, you can use this option to use the total weight of the combined items on an order as the weight for the consignment. Aust Post eParcel requires that all weights are in KG, so if your product weight is entered in grams, lbs or oz (per WooCommerce Product settings), the combined order weight will be converted to KG for consignment and articles weights."/>
                  </td>
              </tr>
              
               <tr class="use_order_weight_1" style="<?php if (LinksynceparcelHelper::getFormValue('use_order_weight',get_option('linksynceparcel_use_order_weight')) != 1){?>display:none<?php }?>">
                    <td width="20%" valign="top"><?php _e('Packaging Allowance Type','linksynceparcel'); ?></td>
                    <td align="left" colspan="2">
                        <select name="linksynceparcel[allowance_type]">
                            <option value="F" <?php if (LinksynceparcelHelper::getFormValue('allowance_type',get_option('linksynceparcel_allowance_type')) != 'P'){ echo 'selected="selected"'; }?>><?php _e('Fixed','linksynceparcel'); ?></option>
                            <option value="P" <?php if (LinksynceparcelHelper::getFormValue('allowance_type',get_option('linksynceparcel_allowance_type')) == 'P'){ echo 'selected="selected"'; }?>><?php _e('Percentage','linksynceparcel'); ?></option>
                      </select>
                      <img src="<?php echo linksynceparcel_URL?>assets/images/icon-tooltip.png" class="tooltip" title="Use this option if you want to add additional weight to your order total to allow for packaging. Use 'Fixed' if you want to add a set weight to each order, or use Percentage to add an allowance based on a percentage of the total weight for each order."/>
                  </td>
              </tr>
              
               <tr class="use_order_weight_1" style="<?php if (LinksynceparcelHelper::getFormValue('use_order_weight',get_option('linksynceparcel_use_order_weight')) != 1){?>display:none<?php }?>">
                    <td width="20%" valign="top"><?php _e('Packaging Allowance Value','linksynceparcel'); ?></td>
                    <td align="left" colspan="2">
                       <input type="text" size="40" name="linksynceparcel[allowance_value]" value="<?php echo LinksynceparcelHelper::getFormValue('allowance_value',get_option('linksynceparcel_allowance_value'))?>">
                       <img src="<?php echo linksynceparcel_URL?>assets/images/icon-tooltip.png" class="tooltip" title="Depending on the option you choose for 'Packaging Allowance Type', this value will determine what value to add to the total weight of the combined items on an order. If you select Fixed, then input the additional weight you want to add in KG. eg. .25 for .25 kg/250 grams. If you select percentage, then input the percentage you want to add to an order eg. 5 for 5%. Leave this field empty if you don't want to apply a packaging allowance to orders."/>
                </td>
              </tr>
              
              
              <tr class="use_order_weight_0" style="<?php if (LinksynceparcelHelper::getFormValue('use_order_weight',get_option('linksynceparcel_use_order_weight')) == 1){?>display:none<?php }?>">
                    <td width="20%" valign="top"><?php _e('Default Article Weight (Kgs)','linksynceparcel'); ?></td>
                    <td align="left" colspan="2">
                       <input type="text" size="40" name="linksynceparcel[default_article_weight]" value="<?php echo LinksynceparcelHelper::getFormValue('default_article_weight',get_option('linksynceparcel_default_article_weight'))?>">
                       <img src="<?php echo linksynceparcel_URL?>assets/images/icon-tooltip.png" class="tooltip" title="Set a default Article Weight. This value is used as the default value when selecting 'Custom' article type, and can be overridden when creating articles. Leave blank if you don't want to set a default."/>
                </td>
              </tr>

              
              <tr>
                    <td width="20%" valign="top"><?php _e('Use article dimensions','linksynceparcel'); ?></td>
                    <td align="left" colspan="2">
                        <select name="linksynceparcel[use_dimension]" id="use_dimension">
                            <option value="0" <?php if (LinksynceparcelHelper::getFormValue('use_dimension',get_option('linksynceparcel_use_dimension')) != 1){ echo 'selected="selected"'; }?>><?php _e('No','linksynceparcel'); ?></option>
                            <option value="1" <?php if (LinksynceparcelHelper::getFormValue('use_dimension',get_option('linksynceparcel_use_dimension')) == 1){ echo 'selected="selected"'; }?>><?php _e('Yes','linksynceparcel'); ?></option>
                      </select>
                      <img src="<?php echo linksynceparcel_URL?>assets/images/icon-tooltip.png" class="tooltip" title="If you're on a dead-weight of cubic-weight contract with Australia Post, then you may not be required to enter dimensions for each article you ship with eParcel. If that's the case, then set this option to No. If you are required to enter dimensions for articles, then set this option to Yes."/>
                  </td>
              </tr>
              
              
              <tr class="use_dimension" style="<?php if (LinksynceparcelHelper::getFormValue('use_dimension',get_option('linksynceparcel_use_dimension')) != 1){?>display:none<?php }?>">
                    <td width="20%" valign="top"><?php _e('Default Article Height (cm)','linksynceparcel'); ?></td>
                    <td align="left" colspan="2">
                       <input type="text" size="40" name="linksynceparcel[default_article_height]" value="<?php echo LinksynceparcelHelper::getFormValue('default_article_height',get_option('linksynceparcel_default_article_height'))?>">
                       <img src="<?php echo linksynceparcel_URL?>assets/images/icon-tooltip.png" class="tooltip" title="Set a default Article Height. This value is used as the default value when selecting 'Custom' article type, and can be overridden when creating articles. Leave blank if you don't want to set a default."/>
                </td>
              </tr>
              <tr class="use_dimension" style="<?php if (LinksynceparcelHelper::getFormValue('use_dimension',get_option('linksynceparcel_use_dimension')) != 1){?>display:none<?php }?>">
                    <td width="20%" valign="top"><?php _e('Default Article Width (cm)','linksynceparcel'); ?></td>
                    <td align="left" colspan="2">
                       <input type="text" size="40" name="linksynceparcel[default_article_width]" value="<?php echo LinksynceparcelHelper::getFormValue('default_article_width',get_option('linksynceparcel_default_article_width'))?>">
                       <img src="<?php echo linksynceparcel_URL?>assets/images/icon-tooltip.png" class="tooltip" title="Set a default Article Width. This value is used as the default value when selecting 'Custom' article type, and can be overridden when creating articles. Leave blank if you don't want to set a default."/>
                </td>
              </tr>
              <tr class="use_dimension" style="<?php if (LinksynceparcelHelper::getFormValue('use_dimension',get_option('linksynceparcel_use_dimension')) != 1){?>display:none<?php }?>">
                    <td width="20%" valign="top"><?php _e('Default Article Length (cm)','linksynceparcel'); ?></td>
                    <td align="left" colspan="2">
                       <input type="text" size="40" name="linksynceparcel[default_article_length]" value="<?php echo LinksynceparcelHelper::getFormValue('default_article_length',get_option('linksynceparcel_default_article_length'))?>">
                       <img src="<?php echo linksynceparcel_URL?>assets/images/icon-tooltip.png" class="tooltip" title="Set a default Article Length. This value is used as the default value when selecting 'Custom' article type, and can be overridden when creating articles. Leave blank if you don't want to set a default."/>
                </td>
              </tr>

				<tr>
                    <td width="20%" valign="top"><?php _e('Display order status on Consignment View','linksynceparcel'); ?></td>
                    <td align="left" colspan="2">
                       <select name="linksynceparcel[display_order_status]">
                             <option value="0" <?php if (LinksynceparcelHelper::getFormValue('display_order_status',get_option('linksynceparcel_display_order_status')) != 1){ echo 'selected="selected"'; }?>><?php _e('No','linksynceparcel'); ?></option>
                            <option value="1" <?php if (LinksynceparcelHelper::getFormValue('display_order_status',get_option('linksynceparcel_display_order_status')) == 1){ echo 'selected="selected"'; }?>><?php _e('Yes','linksynceparcel'); ?></option>
                      </select>
                      <img src="<?php echo linksynceparcel_URL?>assets/images/icon-tooltip.png" class="tooltip" title="Set this option to Yes if you want the WooCommerce order status for all orders to be displayed in the consignment view."/>
                </td>
              </tr> 
              
              <tr>
                    <td width="20%" valign="top"><?php _e('Choose the statuses to show in the consignment view?','linksynceparcel'); ?></td>
                    <td align="left" colspan="2">
                       <select name="linksynceparcel[display_choosen_status]" id="display_choosen_status">
                             <option value="0" <?php if (LinksynceparcelHelper::getFormValue('display_choosen_status',get_option('linksynceparcel_display_choosen_status')) != 1){ echo 'selected="selected"'; }?>><?php _e('No','linksynceparcel'); ?></option>
                            <option value="1" <?php if (LinksynceparcelHelper::getFormValue('display_choosen_status',get_option('linksynceparcel_display_choosen_status')) == 1){ echo 'selected="selected"'; }?>><?php _e('Yes','linksynceparcel'); ?></option>
                      </select>
                      <img src="<?php echo linksynceparcel_URL?>assets/images/icon-tooltip.png" class="tooltip" title="By default, orders that have a status of 'Processing' or have an open consignment against them are displayed in the Consignment View. If you'd like to choose different order statuses to display in the Consignment View then set this option to Yes."/>
                </td>
              </tr>
              
              <tr class="display_choosen_status" style="<?php if (LinksynceparcelHelper::getFormValue('display_choosen_status',get_option('linksynceparcel_display_choosen_status')) != 1){?>display:none<?php }?>">
                    <td width="20%" valign="top"><?php _e('Select the statuses','linksynceparcel'); ?></td>
                    <td align="left" colspan="2">
                    <?php 
						$chosen_statuses = get_option('linksynceparcel_chosen_statuses');
						if (isset($_REQUEST['linksynceparcel']['chosen_statuses']))
						{
							$post_chosen_statuses = $_REQUEST['linksynceparcel']['chosen_statuses'];
						}
					?>
                       <select name="linksynceparcel[chosen_statuses][]" multiple="multiple" size="6">
                       <?php if($is_greater_than_21){?>
                            <?php foreach($statuses as $term_id => $status) {?>
                            <option value="<?php echo $term_id?>" 
							<?php 
							if (isset($_REQUEST['linksynceparcel']['chosen_statuses']))
							{
								if($post_chosen_statuses && in_array($term_id,$post_chosen_statuses))
								{
									echo 'selected="selected"';
								}
							}
							else
							{
								if($chosen_statuses && in_array($term_id,$chosen_statuses))
								{
									echo 'selected="selected"';
								}
							}
							?>
							><?php echo $status?></option>
                            <?php } ?>
                       <?php }else{?>
                            <?php foreach($statuses as $status) {?>
                            <option value="<?php echo $status->slug?>" 
                            <?php 
							if (isset($_REQUEST['linksynceparcel']['chosen_statuses']))
							{
								if($post_chosen_statuses && in_array($status->slug,$post_chosen_statuses))
								{
									echo 'selected="selected"';
								}
							}
							else
							{
								if($chosen_statuses && in_array($status->slug,$chosen_statuses))
								{
									echo 'selected="selected"';
								}
							}
							?>
                            ><?php echo $status->name?></option>
                            <?php } ?>
                      <?php }?>
                      </select>
                      <img src="<?php echo linksynceparcel_URL?>assets/images/icon-tooltip.png" class="tooltip" title="Select one or more order statuses to display in the Consignment View. Only orders with matching Order Statuses will be displayed in the Consignment View."/>
                </td>
              </tr>
              
              <tr>
                    <td width="20%" valign="top"><?php _e('Change order status on despatch of Manifest','linksynceparcel'); ?></td>
                    <td align="left" colspan="2">
                       <select name="linksynceparcel[change_order_status]">
                       <?php if($is_greater_than_21){?>
                       		 <option value="" <?php if (LinksynceparcelHelper::getFormValue('change_order_status',get_option('linksynceparcel_change_order_status')) == ''){ echo 'selected="selected"'; }?>>
								<?php _e('Please select','linksynceparcel'); ?>
                            </option>
                            <?php foreach($statuses as $term_id => $status) {?>
                            <option value="<?php echo $term_id?>" <?php if (LinksynceparcelHelper::getFormValue('change_order_status',get_option('linksynceparcel_change_order_status')) == $term_id){ echo 'selected="selected"'; }?>><?php echo $status?></option>
                            <?php } ?>
                       <?php }else{?>
                            <option value="" <?php if (LinksynceparcelHelper::getFormValue('change_order_status',get_option('linksynceparcel_change_order_status')) == ''){ echo 'selected="selected"'; }?>>
								<?php _e('Please select','linksynceparcel'); ?>
                            </option>
                            <?php foreach($statuses as $status) {?>
                            <option value="<?php echo $status->term_id?>" <?php if (LinksynceparcelHelper::getFormValue('change_order_status',get_option('linksynceparcel_change_order_status')) == $status->term_id){ echo 'selected="selected"'; }?>><?php echo $status->name?></option>
                            <?php } ?>
                      <?php }?>
                      </select>
                      <img src="<?php echo linksynceparcel_URL?>assets/images/icon-tooltip.png" class="tooltip" title="Use this option to change the order status when a manifest is despatched."/>
                </td>
              </tr> 
              
              
                <tr>
                    <td width="20%" valign="top"><?php _e('Notify Customers on despatch of Manifest','linksynceparcel'); ?></td>
                    <td align="left" colspan="2">
                        <select name="linksynceparcel[notify_customers]">
                            <option value="0" <?php if (LinksynceparcelHelper::getFormValue('notify_customers',get_option('linksynceparcel_notify_customers')) != 1){ echo 'selected="selected"'; }?>><?php _e('No','linksynceparcel'); ?></option>
                            <option value="1" <?php if (LinksynceparcelHelper::getFormValue('notify_customers',get_option('linksynceparcel_notify_customers')) == 1){ echo 'selected="selected"'; }?>><?php _e('Yes','linksynceparcel'); ?></option>
                      </select>
                      <img src="<?php echo linksynceparcel_URL?>assets/images/icon-tooltip.png" class="tooltip" title="Use this option to notify customers of tracking numbers when a manifest is despatched. This default can be overridden when creating consignments."/>
                  </td>
              </tr>
              
                <tr>
                    <td width="20%" valign="top"><?php _e('From email address','linksynceparcel'); ?></td>
                    <td align="left" colspan="2">
                       <input type="text" size="40" name="linksynceparcel[from_email_address]" value="<?php echo LinksynceparcelHelper::getFormValue('from_email_address',get_option('linksynceparcel_from_email_address'))?>">
                       <img src="<?php echo linksynceparcel_URL?>assets/images/icon-tooltip.png" class="tooltip" title="The 'from' email address to be used when notifying customers of tracking information when a manifest is despatched."/>
                  </td>
              </tr>
              <tr>
                    <td width="20%" valign="top"><?php _e('Subject','linksynceparcel'); ?></td>
                    <td align="left" colspan="2">
                       <input type="text" size="40" name="linksynceparcel[subject]" value="<?php echo LinksynceparcelHelper::getFormValue('subject',get_option('linksynceparcel_subject'))?>">
                       <br />
                       <span class="comment"><?php _e("You can use the [TrackingNumber],[OrderNumber], [CustomerFirstname] dynamic variables",'linksynceparcel'); ?></span>
                </td>
              </tr>
              <tr>
                    <td width="20%" valign="top"><?php _e('Email Body','linksynceparcel'); ?></td>
                    <td align="left" colspan="2">
                    	<?php wp_editor(LinksynceparcelHelper::getFormValue('linksynceparcel_email_body',get_option('linksynceparcel_email_body'),''), 'linksynceparcel_email_body', array('textarea_rows'=>4), false);  ?>
                       <br />
                       <span class="comment"><?php _e("You can use the [TrackingNumber],[OrderNumber], [CustomerFirstname] dynamic variables",'linksynceparcel'); ?></span>
                </td>
              </tr>
              <tr>
                    <td width="20%" valign="top"><?php _e('Send Log','linksynceparcel'); ?></td>
                    <td align="left" valign="top">
                        <input type="button" id="sendlog" name="sendlog" value="<?php _e('Send Log','linksynceparcel'); ?>" class="button-primary" onclick="sendLog()" />
                        <br />
                        <span class="comment"><?php _e("To be used if instructed by linksync support",'linksynceparcel'); ?></span>
                </td>
              </tr>
              <tr>
                    <td width="20%" valign="top"><?php _e('Enable Mark as Despatched Action on Consignment UI','linksynceparcel'); ?></td>
                    <td align="left" colspan="2">
                        <select name="linksynceparcel[mark_despatch]">
                            <option value="0" <?php if (LinksynceparcelHelper::getFormValue('mark_despatch',get_option('linksynceparcel_mark_despatch')) != 1){ echo 'selected="selected"'; }?>><?php _e('No','linksynceparcel'); ?></option>
                            <option value="1" <?php if (LinksynceparcelHelper::getFormValue('mark_despatch',get_option('linksynceparcel_mark_despatch')) == 1){ echo 'selected="selected"'; }?>><?php _e('Yes','linksynceparcel'); ?></option>
						</select><br />
						<span class="comment"><?php _e("To be used if instructed by linksync support",'linksynceparcel'); ?></span>
					</td>
				</tr>
          </table>
        </fieldset>
        <br />
        <input type="submit" name="submitConfiguration" value="<?php _e('Save','linksynceparcel'); ?>" class="button-primary" />
    </form>
</div>
<style>
.hide-tr { display: none; }
.show-tr { display: table-row; }
</style>
<script>
jQuery(document).ready(function() {
	jQuery('.tooltip').linksynctooltip({
		theme: 'linksynctooltip-shadow',
		contentAsHTML: true
	});	
	
	jQuery('#use_order_weight').change(function(){
		var val = jQuery(this).val();
		if(val == 1)
		{
			jQuery('.use_order_weight_1').show();
			jQuery('.use_order_weight_0').hide();
		}
		else
		{
			jQuery('.use_order_weight_0').show();
			jQuery('.use_order_weight_1').hide();
		}
	});
	
	jQuery('#use_dimension').change(function(){
		var val = jQuery(this).val();
		if(val == 1)
		{
			jQuery('.use_dimension').show();
		}
		else
		{
			jQuery('.use_dimension').hide();
		}
	});
	
	jQuery('#display_choosen_status').change(function(){
		var val = jQuery(this).val();
		if(val == 1)
		{
			jQuery('.display_choosen_status').show();
		}
		else
		{
			jQuery('.display_choosen_status').hide();
		}
	});
	
	
	jQuery('#declared_value').change(function() {
		var $this_val = jQuery('#declared_value:checked').length > 0;
		if(!$this_val) {
			jQuery('#declared_value_text').removeClass('hide-tr');
			jQuery('#declared_value_text').addClass('show-tr');
			jQuery('.declared_value_text').val('');
		} else {
			jQuery('#declared_value_text').removeClass('show-tr');
			jQuery('#declared_value_text').addClass('hide-tr');
		}
	});
	
	jQuery('#has_commercial_value').change(function() {
		var $this_val = jQuery('#has_commercial_value:checked').length > 0;
		if($this_val) {
			jQuery('#product_classification').attr('disabled', true);
			jQuery('#product_classification').val('991');
			jQuery('#product_classification_text').removeClass("hide-tr");
			jQuery('#product_classification_text').addClass("show-tr");
		} else {
			jQuery('#product_classification').attr('disabled', false);
		}
	});
	
	jQuery('#product_classification').change(function() {
		var $this_val = jQuery('#product_classification').val();
		if($this_val == '991') {
			jQuery('#product_classification_text').removeClass("hide-tr");
			jQuery('#product_classification_text').addClass("show-tr");
		} else {
			jQuery('#product_classification_text').removeClass("show-tr");
			jQuery('#product_classification_text').addClass("hide-tr");
			jQuery('.product_classification_text').val("");
		}
	});
});
function sendLog()
{
	$jEparcel("#sendlog").val('Sending...');
	$jEparcel.ajax({
		type: "POST",
		url: '<?php echo admin_url('admin.php?page=linksynceparcel&subpage=sendlog&ajax=true'); ?>',
		success: function(data){
			$jEparcel("#sendlog").val('Send Log');
			data = $jEparcel.trim(data);
			if(data.length > 0)
			{
				alert(data);
			}
		}
	});
}
</script>
<?php }?>