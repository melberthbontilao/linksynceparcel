<?php
class LinksynceparcelValidator
{
	public static function validateConfiguration($data)
	{
		$errors = array();
		if(empty($data['laid']))
			$errors[] = 'LAID is a required field.';
		if(empty($data['merchant_location_id']))
			$errors[] = 'eParcel Merchant Location ID is a required field.';
		if(empty($data['post_charge_to_account']))
			$errors[] = 'eParcel Post Charge to Account is a required field.';
		if(empty($data['merchant_id']))
			$errors[] = 'Merchant Id is a required field.';
		if(empty($data['lodgement_facility']))
			$errors[] = 'Lodgement facility is a required field.';
		if(empty($data['sftp_username']))
			$errors[] = 'SFTP username is a required field.';
		if(empty($data['sftp_password']))
			$errors[] = 'SFTP password is a required field.';
		if(empty($data['lps_username']))
			$errors[] = 'LPS username is a required field.';
		if(empty($data['lps_password']))
			$errors[] = 'LPS password is a required field.';
		if(empty($data['return_address_name']))
			$errors[] = 'Return Address Name is a required field.';
		if(empty($data['return_address_line1']))
			$errors[] = 'Return Address Line 1 is a required field.';
		if(empty($data['return_address_postcode']))
			$errors[] = 'Return Address Postcode is a required field.';
		else if(!is_numeric($data['return_address_postcode']) || $data['return_address_postcode'] < 0)
			$errors[] = 'Return Address Postcode is invalid.';
		else if(strlen($data['return_address_postcode']) < 4 || strlen($data['return_address_postcode']) > 4)
			$errors[] = 'Return Address Postcode should be in 4 digits.';
		if(empty($data['return_address_statecode']))
			$errors[] = 'Return Address State code is a required field.';
		if(empty($data['return_address_suburb']))
			$errors[] = 'Return Address Suburb is a required field.';
		if(!empty($data['default_insurance_value']) && (!is_numeric($data['default_insurance_value']) || $data['default_insurance_value'] < 0))
			$errors[] = 'Default Insurance value is invalid.';
			
		if(isset($data['allowance_value']) && !empty($data['allowance_value']) && (!is_numeric($data['allowance_value']) || $data['allowance_value'] < 0))
			$errors[] = 'Packaging Allowance Value is invalid.';
			
		if(!empty($data['default_article_weight']) && (!is_numeric($data['default_article_weight']) || $data['default_article_weight'] < 0))
			$errors[] = 'Default Article Weight is invalid.';
		if(!empty($data['default_article_height']) && (!is_numeric($data['default_article_height']) || $data['default_article_height'] < 0))
			$errors[] = 'Default Article Height is invalid.';
		if(!empty($data['default_article_width']) && (!is_numeric($data['default_article_width']) || $data['default_article_width'] < 0))
			$errors[] = 'Default Article Width is invalid.';
		if(!empty($data['default_article_length']) && (!is_numeric($data['default_article_length']) || $data['default_article_length'] < 0))
			$errors[] = 'Default Article Length is invalid.';
		if(!empty($data['from_email_address']) && !is_email($data['from_email_address']))
			$errors[] = 'From email address is invalid.';
		if(isset($data['declared_value']) && $data['declared_value']==0) {
			if(!empty($data['declared_value_text']) && (!is_numeric($data['declared_value_text']) || $data['declared_value_text'] < 0))
				$errors[] = 'Order value as Declared Value is invalid.';
		}
		if(!empty($data['has_commercial_value']) && $data['has_commercial_value']==1) {
			if(empty($data['product_classification_text']))
				$errors[] = 'Classification Explanation is a required field.';
		}
		if(!empty($data['hs_tariff']) && ($data['hs_tariff'] >= 12 || $data['hs_tariff'] <= 6))
				$errors[] = 'HS Tariffs must be between 6 - 12 digits.';
			
		if(empty($data['default_contents']))
			$errors[] = 'Default Contents is a required field.';
			
		if(count($errors) > 0)
			return $errors;
		return false;
	}
	
	public static function validateArticlePresets($data)
	{
		$errors = array();
		if(empty($data['name']))
			$errors[] = 'Preset Name is a required field.';
		if(empty($data['weight']))
			$errors[] = 'Weight is a required field.';
		else if(!is_numeric($data['weight']) || $data['weight'] < 0)
			$errors[] = 'Weight is invalid.';
		if(!is_numeric($data['height']) || $data['height'] < 0)
			$errors[] = 'Height is invalid.';
		if(!is_numeric($data['width']) || $data['width'] < 0)
			$errors[] = 'Width is invalid.';
		if(!is_numeric($data['length']) || $data['length'] < 0)
			$errors[] = 'Length is invalid.';
		if(count($errors) > 0)
			return $errors;
		return false;
	}
	
	public static function validateAssignShippingTypes($data)
	{
		$errors = array();
		if(empty($data['method']))
			$errors[] = 'Method is a required field.';
		if(empty($data['charge_code']))
			$errors[] = 'Charge code is a required field.';
		if(count($errors) > 0)
			return $errors;
		return false;
	}
	
	
}
?>