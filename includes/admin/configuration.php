<?php
class LinksynceparcelAdminConfiguration
{
	public static function output()
	{
		global $is_greater_than_21;
		if (isset($_POST['submitConfiguration'])) 
		{
			$data = $_POST['linksynceparcel'];
			$data['email_body'] = $_POST['linksynceparcel_email_body'];
			if(!isset($data['declared_value'])) {
				$data['declared_value'] = 0;
			}
			if(!isset($data['has_commercial_value'])) {
				$data['has_commercial_value'] = 0;
			}
			$errors = LinksynceparcelValidator::validateConfiguration($data);
			if($errors)
			{
				$error = implode('<br/>',$errors);
			}
			else
			{
				if (isset($_REQUEST['delete_label_logo']))
				{
					if(get_option('linksynceparcel_label_logo') && file_exists(linksynceparcel_DIR.'assets/images/'.get_option('linksynceparcel_label_logo')))
					{
						@unlink(linksynceparcel_DIR.'assets/images/'.get_option('linksynceparcel_label_logo'));
						$data['label_logo'] = '';
					}
				}
				
				LinksynceparcelHelper::saveConfiguration($data);
				
				LinksynceparcelHelper::saveLabelLogo();
				$result = __( 'Configuration have been saved successfully.', 'linksynceparcel' );
				
				try
				{
					LinksynceparcelApi::seteParcelMerchantDetails();
					$result .= '<br/>'.__( 'eParcel Merchant Details have been set successfully.', 'linksynceparcel' );
				}
				catch(Exception $e)
				{
					$message = 'Updating Merchant Details, Error:'.$e->getMessage();
					$error = $message;
					LinksynceparcelHelper::log($message);
				}
				
				try
				{
					LinksynceparcelApi::setReturnAddress();
					$result .= '<br/>'. __( 'Return Address have been set successfully.', 'linksynceparcel' );
				}
				catch(Exception $e)
				{
					$message = 'Set Return Address, Error:'.$e->getMessage();
					$error = ($error ? '<br>':'').$message;
					LinksynceparcelHelper::log($message);
				}
			}
		}
				
		$statuses = LinksynceparcelHelper::getOrderStatuses();
		$states = LinksynceparcelHelper::getStates();
		$countries = LinksynceparcelHelper::getWooCountries();
		$formats = LinksynceparcelHelper::getLabelFormats();
		
		include_once(linksynceparcel_DIR.'views/admin/configuration.php');
	}
}
?>