<?php
if(!defined('LINKSYNC_EPARCEL_URL1'))
	define('LINKSYNC_EPARCEL_URL1','stg-api.linksync.com');
if(!defined('LINKSYNC_EPARCEL_URL2'))
	define('LINKSYNC_EPARCEL_URL2','stg-api.linksync.com');
if(!defined('LINKSYNC_WSDL'))
	define('LINKSYNC_WSDL','/linksync/linksyncService');
if(!defined('LINKSYNC_DEBUG'))
	define('LINKSYNC_DEBUG',1);
	
class LinksynceparcelApi
{
	public static function getWebserviceUrl($next = false)
	{
		$url = 'https://';
		
		if($next)
		{
			$url .= LINKSYNC_EPARCEL_URL1;
		}
		else
		{
			$url .= LINKSYNC_EPARCEL_URL2;
		}
		$url .= LINKSYNC_WSDL;
		return $url;
	}
	
	public static function seteParcelMerchantDetails()
	{
		try
		{
			if(LINKSYNC_DEBUG == 1)
			{
				$client = new SoapClient(self::getWebserviceUrl(true).'?WSDL',array('trace'=>1));
			}
			else
			{
				$client = new SoapClient(self::getWebserviceUrl(true).'?WSDL');
			}
			
			$laid = get_option('linksynceparcel_laid');
			$merchant_location_id = get_option('linksynceparcel_merchant_location_id');
			$post_charge_to_account = get_option('linksynceparcel_post_charge_to_account');
			$sftp_username = get_option('linksynceparcel_sftp_username');
			$sftp_password = get_option('linksynceparcel_sftp_password');
			$lps_username = get_option('linksynceparcel_lps_username');
			$lps_password = get_option('linksynceparcel_lps_password');
			$operation_mode = get_option('linksynceparcel_operation_mode');
			$merchant_id = get_option('linksynceparcel_merchant_id');
			$lodgement_facility = get_option('linksynceparcel_lodgement_facility');
			
			if($operation_mode == 1)
			{
				$operation_mode = 'live';
			}
			else
			{
				$operation_mode = 'test';
			}
			
			$label_logo = '';
			if(get_option('linksynceparcel_label_logo') && file_exists(linksynceparcel_DIR.'assets/images/'.get_option('linksynceparcel_label_logo')))
			{
				$label_logo = @file_get_contents(linksynceparcel_DIR.'assets/images/'.get_option('linksynceparcel_label_logo'));
			}
			
			$stdClass = $client->seteParcelMerchantDetails($laid,$merchant_location_id, $post_charge_to_account,$sftp_username,$sftp_password, $operation_mode, '', $merchant_id, $lodgement_facility, $label_logo, $lps_username, $lps_password, linksynceparcel_SITE_URL ); 

			if($stdClass)
			{
				if(LINKSYNC_DEBUG == 1)
				{
					LinksynceparcelHelper::log('seteParcelMerchantDetails Request: '.$client->__getLastRequest());
					LinksynceparcelHelper::log('seteParcelMerchantDetails Response: '.$client->__getLastResponse());
				}
				return $stdClass;
			}
			if(LINKSYNC_DEBUG == 1 && $client)
			{
				LinksynceparcelHelper::log('seteParcelMerchantDetails Request: '.$client->__getLastRequest());
				LinksynceparcelHelper::log('seteParcelMerchantDetails Response: '.$client->__getLastResponse());
			}
		}
		catch(Exception $e)
		{
			if(LINKSYNC_DEBUG == 1 && $client)
			{
				LinksynceparcelHelper::log('seteParcelMerchantDetails Request: '.$client->__getLastRequest());
				LinksynceparcelHelper::log('seteParcelMerchantDetails Response: '.$client->__getLastResponse());
			}
			throw $e;
		}
	}
	
	public static function setReturnAddress()
	{
		try
		{
			if(LINKSYNC_DEBUG == 1)
			{
				$client = new SoapClient(self::getWebserviceUrl(true).'?WSDL',array('trace'=>1));
			}
			else
			{
				$client = new SoapClient(self::getWebserviceUrl(true).'?WSDL');
			}
			
			$returnAddress = array();
			$returnAddress['returnAddressLine1'] = trim(get_option('linksynceparcel_return_address_line1'));
			$returnAddress['returnAddressLine2'] = trim(get_option('linksynceparcel_return_address_line2'));
			$returnAddress['returnAddressLine3'] = trim(get_option('linksynceparcel_return_address_line3'));
			$returnAddress['returnAddressLine4'] = trim(get_option('linksynceparcel_return_address_line4'));
			$returnAddress['returnCountryCode'] = 'AU';
			$returnAddress['returnName'] = trim(get_option('linksynceparcel_return_address_name'));
			$returnAddress['returnPostcode'] = trim(get_option('linksynceparcel_return_address_postcode'));
			$returnAddress['returnStateCode'] = trim(get_option('linksynceparcel_return_address_statecode'));
			$returnAddress['returnSuburb'] = trim(get_option('linksynceparcel_return_address_suburb'));
			$returnAddress['returnCompanyName'] = trim(get_option('linksynceparcel_return_business_name'));
			$returnAddress['returnEmailAddress'] = trim(get_option('linksynceparcel_return_email_address'));
			$returnAddress['returnPhoneNumber'] = trim(get_option('linksynceparcel_return_phone_number'));
			$laid = get_option('linksynceparcel_laid');
			
			$stdClass = $client->setReturnAddress($laid,$returnAddress); 

			if($stdClass)
			{
				if(LINKSYNC_DEBUG == 1)
				{
					LinksynceparcelHelper::log('setReturnAddress Request: '.$client->__getLastRequest());
					LinksynceparcelHelper::log('setReturnAddress Response: '.$client->__getLastResponse());
				}
				return $stdClass;
			}
			if(LINKSYNC_DEBUG == 1 && $client)
			{
				LinksynceparcelHelper::log('setReturnAddress Request: '.$client->__getLastRequest());
				LinksynceparcelHelper::log('setReturnAddress Response: '.$client->__getLastResponse());
			}
		}
		catch(Exception $e)
		{
			if(LINKSYNC_DEBUG == 1 && $client)
			{
				LinksynceparcelHelper::log('setReturnAddress Request: '.$client->__getLastRequest());
				LinksynceparcelHelper::log('setReturnAddress Response: '.$client->__getLastResponse());
			}
			throw $e;
		}
	}
	
	public static function sendLog()
	{
		try
		{
			if(LINKSYNC_DEBUG == 1)
			{
				$client = new SoapClient(self::getWebserviceUrl(true).'?WSDL',array('trace'=>1));
			}
			else
			{
				$client = new SoapClient(self::getWebserviceUrl(true).'?WSDL');
			}
			
			$laid = get_option('linksynceparcel_laid');
			$filename = linksynceparcel_DIR.'/log/linksynceparcel_log_'.date('Ymdhis').'.zip';
			
			if(LinksynceparcelHelper::createZip(linksynceparcel_DIR.'/log/linksynceparcel.log',$filename))
			{
				$stdClass = $client->sendLogFile($laid,file_get_contents($filename)); 
	
				if($stdClass)
				{
					if(LINKSYNC_DEBUG == 1)
					{
						LinksynceparcelHelper::log('Send Log File  Response: '.$client->__getLastResponse());
					}
					return $stdClass;
				}
				
				if(LINKSYNC_DEBUG == 1 && $client)
				{
					LinksynceparcelHelper::log('Send Log File  Request: '.$client->__getLastRequest());
					LinksynceparcelHelper::log('Send Log File  Response: '.$client->__getLastResponse());
				}
			}
			else
			{
				throw new Exception('Failed to create archive file');
			}
		}
		catch(Exception $e)
		{
			if(LINKSYNC_DEBUG == 1 && $client)
			{
				LinksynceparcelHelper::log('Send Log File  Request: '.$client->__getLastRequest());
				LinksynceparcelHelper::log('Send Log File  Response: '.$client->__getLastResponse());
			}
			throw $e;
		}
	}
	
	public static function isAddressValid($address)
	{ 
		try
		{
			$city = trim($address['city']);
			$state = trim($address['state']);
			$postcode = trim($address['postcode']);

			if(LINKSYNC_DEBUG == 1)
			{
				$client = new SoapClient(self::getWebserviceUrl(true).'?WSDL',array('trace'=>1));
			}
			else
			{
				$client = new SoapClient(self::getWebserviceUrl(true).'?WSDL');
			}
			
			$laid = get_option('linksynceparcel_laid');
			$addressParams = array('suburb' => $city, 'postcode' => $postcode, 'stateCode' => $state);
			$stdClass = $client->isAddressValid($laid,$addressParams); 
			if($stdClass)
			{
				if(LINKSYNC_DEBUG == 1)
				{
					LinksynceparcelHelper::log('isAddressValid Request: '.$client->__getLastRequest());
					LinksynceparcelHelper::log('isAddressValid Response: '.$client->__getLastResponse());
				}
				return 1;
			}
		}
		catch(Exception $e)
		{
			if(LINKSYNC_DEBUG == 1 && $client)
			{
				LinksynceparcelHelper::log('isAddressValid Request: '.$client->__getLastRequest());
				LinksynceparcelHelper::log('isAddressValid Response: '.$client->__getLastResponse());
			}
			return $e->getMessage();
		}
	}
	
	public static function createConsignment($article,$loop=0,$chargeCode=false)
	{
		if($loop < 2)
		{
			try
			{
				if(LINKSYNC_DEBUG == 1)
				{
					$client = new SoapClient(self::getWebserviceUrl(true).'?WSDL',array('trace'=>1));
				}
				else
				{
					$client = new SoapClient(self::getWebserviceUrl(true).'?WSDL');
				}
				
				LinksynceparcelHelper::log('Articles: '.preg_replace('/\s+/', ' ', trim($article)));
			
				$chargeCodeData = LinksynceparcelHelper::getEParcelChargeCodes();
				$codeData = $chargeCodeData[$chargeCode];
				
				$service = get_option('linksynceparcel_'. $codeData['key'] .'_label');
				$labelType = explode('_', $service);
				$arg3 = $labelType[0];
				$arg4 = ($labelType[1]==0)?'false':'true';
				$arg5 = get_option('linksynceparcel_'. $codeData['key'] .'_left_offset');
				$arg6 = get_option('linksynceparcel_'. $codeData['key'] .'_right_offset');
				$laid = get_option('linksynceparcel_laid');
				
				$stdClass = $client->createConsignment2($laid,$article,linksynceparcel_SITE_URL,$arg3,$arg4,$arg5,$arg6); 
	
				if($stdClass)
				{
					if(LINKSYNC_DEBUG == 1)
					{
						//LinksynceparcelHelper::log('createConsignment Request: '.$client->__getLastRequest());
						LinksynceparcelHelper::log('createConsignment Response: '.$client->__getLastResponse());
					}
					return $stdClass;
				}
			}
			catch(Exception $e)
			{
				if(LINKSYNC_DEBUG == 1 && $client)
				{
					LinksynceparcelHelper::log('createConsignment Request: '.$client->__getLastRequest());
					LinksynceparcelHelper::log('createConsignment Response: '.$client->__getLastResponse());
				}
				LinksynceparcelHelper::log('createConsignment Error catch from API class: '.$e->getMessage());
				throw $e;
			}
		}
	}
	
	public static function modifyConsignment($article,$consignmentNumber)
	{
		try
		{
			if(LINKSYNC_DEBUG == 1)
			{
				$client = new SoapClient(self::getWebserviceUrl(true).'?WSDL',array('trace'=>1));
			}
			else
			{
				$client = new SoapClient(self::getWebserviceUrl(true).'?WSDL');
			}
			
			LinksynceparcelHelper::log('Modified Articles: '.preg_replace('/\s+/', ' ', trim($article)));
			
			$laid = get_option('linksynceparcel_laid');
			
			$stdClass = $client->modifyConsignment2($laid,$consignmentNumber,$article);

			if($stdClass)
			{
				if(LINKSYNC_DEBUG == 1)
				{
					//LinksynceparcelHelper::log('modifyConsignment Request: '.$client->__getLastRequest());
					LinksynceparcelHelper::log('modifyConsignment Response: '.$client->__getLastResponse());
				}
				return $stdClass;
			}
			if(LINKSYNC_DEBUG == 1 && $client)
			{
				LinksynceparcelHelper::log('modifyConsignment Request: '.$client->__getLastRequest());
				LinksynceparcelHelper::log('modifyConsignment Response: '.$client->__getLastResponse());
			}
		}
		catch(Exception $e)
		{
			if(LINKSYNC_DEBUG == 1 && $client)
			{
				LinksynceparcelHelper::log('modifyConsignment Request: '.$client->__getLastRequest());
				LinksynceparcelHelper::log('modifyConsignment Response: '.$client->__getLastResponse());
			}
			throw $e;
		}
	}
	
	public static function unAssignConsignment($consignmentNumber)
	{
		try
		{
			if(LINKSYNC_DEBUG == 1)
			{
				$client = new SoapClient(self::getWebserviceUrl(true).'?WSDL',array('trace'=>1));
			}
			else
			{
				$client = new SoapClient(self::getWebserviceUrl(true).'?WSDL');
			}
			
			$laid = get_option('linksynceparcel_laid');
			
			$stdClass = $client->unAssignConsignment($laid,$consignmentNumber);

			if($stdClass)
			{
				if(LINKSYNC_DEBUG == 1)
				{
					LinksynceparcelHelper::log('unAssignConsignment Request: '.$client->__getLastRequest());
					LinksynceparcelHelper::log('unAssignConsignment Response: '.$client->__getLastResponse());
				}
				return $stdClass;
			}
			if(LINKSYNC_DEBUG == 1 && $client)
			{
				LinksynceparcelHelper::log('unAssignConsignment Request: '.$client->__getLastRequest());
				LinksynceparcelHelper::log('unAssignConsignment Response: '.$client->__getLastResponse());
			}
		}
		catch(Exception $e)
		{
			if(LINKSYNC_DEBUG == 1 && $client)
			{
				LinksynceparcelHelper::log('unAssignConsignment Request: '.$client->__getLastRequest());
				LinksynceparcelHelper::log('unAssignConsignment Response: '.$client->__getLastResponse());
			}
			throw $e;
		}
	}
	
	public static function deleteConsignment($consignmentNumber)
	{
		try
		{
			if(LINKSYNC_DEBUG == 1)
			{
				$client = new SoapClient(self::getWebserviceUrl(true).'?WSDL',array('trace'=>1));
			}
			else
			{
				$client = new SoapClient(self::getWebserviceUrl(true).'?WSDL');
			}
			
			$laid = get_option('linksynceparcel_laid');
			
			$stdClass = $client->deleteConsignment($laid,$consignmentNumber);

			if($stdClass)
			{
				if(LINKSYNC_DEBUG == 1)
				{
					LinksynceparcelHelper::log('deleteConsignment Request: '.$client->__getLastRequest());
					LinksynceparcelHelper::log('deleteConsignment Response: '.$client->__getLastResponse());
				}
				return $stdClass;
			}
			if(LINKSYNC_DEBUG == 1 && $client)
			{
				LinksynceparcelHelper::log('deleteConsignment Request: '.$client->__getLastRequest());
				LinksynceparcelHelper::log('deleteConsignment Response: '.$client->__getLastResponse());
			}
		}
		catch(Exception $e)
		{
			if(LINKSYNC_DEBUG == 1 && $client)
			{
				LinksynceparcelHelper::log('deleteConsignment Request: '.$client->__getLastRequest());
				LinksynceparcelHelper::log('deleteConsignment Response: '.$client->__getLastResponse());
			}
			throw $e;
		}
	}
	
	public static function getLabelsByConsignments($consignments)
	{
		try
		{
			if(LINKSYNC_DEBUG == 1)
			{
				$client = new SoapClient(self::getWebserviceUrl(true).'?WSDL',array('trace'=>1));
			}
			else
			{
				$client = new SoapClient(self::getWebserviceUrl(true).'?WSDL');
			}
			
			$laid = get_option('linksynceparcel_laid');
			$labelType = get_option('linksynceparcel_label_format');
			
			$stdClass = $client->getLabelsByConsignments($laid,explode(',',$consignments),$labelType); 

			if($stdClass)
			{
				if(LINKSYNC_DEBUG == 1)
				{
					LinksynceparcelHelper::log('getLabelsByConsignments  Request: '.$client->__getLastRequest());
					//LinksynceparcelHelper::log('getLabelsByConsignments  Response: '.$client->__getLastResponse());
				}
				return $stdClass;
			}
			
			if(LINKSYNC_DEBUG == 1 && $client)
			{
				LinksynceparcelHelper::log('getLabelsByConsignments  Request: '.$client->__getLastRequest());
				LinksynceparcelHelper::log('getLabelsByConsignments  Response: '.$client->__getLastResponse());
			}
		}
		catch(Exception $e)
		{
			if(LINKSYNC_DEBUG == 1 && $client)
			{
				LinksynceparcelHelper::log('getLabelsByConsignments  Request: '.$client->__getLastRequest());
				LinksynceparcelHelper::log('getLabelsByConsignments  Response: '.$client->__getLastResponse());
			}
			throw $e;
		}
	}
	
	public static function getReturnLabelsByConsignments($consignments)
	{
		try
		{
			if(LINKSYNC_DEBUG == 1)
			{
				$client = new SoapClient(self::getWebserviceUrl(true).'?WSDL',array('trace'=>1));
			}
			else
			{
				$client = new SoapClient(self::getWebserviceUrl(true).'?WSDL');
			}
			
			$laid = get_option('linksynceparcel_laid');
			$labelType = get_option('linksynceparcel_label_format');
			
			$stdClass = $client->getReturnLabelsByConsignments($laid,explode(',',$consignments),$labelType); 

			if($stdClass)
			{
				if(LINKSYNC_DEBUG == 1)
				{
					LinksynceparcelHelper::log('getReturnLabelsByConsignments  Request: '.$client->__getLastRequest());
					//LinksynceparcelHelper::log('getReturnLabelsByConsignments  Response: '.$client->__getLastResponse());
				}
				return $stdClass;
			}
			
			if(LINKSYNC_DEBUG == 1 && $client)
			{
				LinksynceparcelHelper::log('getReturnLabelsByConsignments  Request: '.$client->__getLastRequest());
				LinksynceparcelHelper::log('getReturnLabelsByConsignments  Response: '.$client->__getLastResponse());
			}
		}
		catch(Exception $e)
		{
			if(LINKSYNC_DEBUG == 1 && $client)
			{
				LinksynceparcelHelper::log('getReturnLabelsByConsignments  Request: '.$client->__getLastRequest());
				LinksynceparcelHelper::log('getReturnLabelsByConsignments  Response: '.$client->__getLastResponse());
			}
			throw $e;
		}
	}
	
	public static function getManifest()
	{
		try
		{
			if(LINKSYNC_DEBUG == 1)
			{
				$client = new SoapClient(self::getWebserviceUrl(true).'?WSDL',array('trace'=>1));
			}
			else
			{
				$client = new SoapClient(self::getWebserviceUrl(true).'?WSDL');
			}
			
			$laid = get_option('linksynceparcel_laid');
			
			$stdClass = $client->getManifest($laid); 

			if($stdClass)
			{
				if(LINKSYNC_DEBUG == 1)
				{
					LinksynceparcelHelper::log('getManifest Request: '.$client->__getLastRequest());
					//LinksynceparcelHelper::log('getManifest Response: '.$client->__getLastResponse());
				}
				return $stdClass;
			}
			
			if(LINKSYNC_DEBUG == 1 && $client)
			{
				LinksynceparcelHelper::log('getManifest Request: '.$client->__getLastRequest());
				LinksynceparcelHelper::log('getManifest Response: '.$client->__getLastResponse());
			}
		}
		catch(Exception $e)
		{
			if(LINKSYNC_DEBUG == 1 && $client)
			{
				LinksynceparcelHelper::log('getManifest Request: '.$client->__getLastRequest());
				LinksynceparcelHelper::log('getManifest Response: '.$client->__getLastResponse());
			}
			throw $e;
		}
	}
	
	public static function printManifest($manifestNumber)
	{
		try
		{
			if(LINKSYNC_DEBUG == 1)
			{
				$client = new SoapClient(self::getWebserviceUrl(true).'?WSDL',array('trace'=>1));
			}
			else
			{
				$client = new SoapClient(self::getWebserviceUrl(true).'?WSDL');
			}
			
			$laid = get_option('linksynceparcel_laid');
			$stdClass = $client->printManifest($laid,$manifestNumber); 
			if($stdClass)
			{
				if(LINKSYNC_DEBUG == 1)
				{
					LinksynceparcelHelper::log('printManifest  Request: '.$client->__getLastRequest());
					//LinksynceparcelHelper::log('printManifest  Response: '.$client->__getLastResponse());
				}
				return $stdClass;
			}
			
			if(LINKSYNC_DEBUG == 1 && $client)
			{
				LinksynceparcelHelper::log('printManifest  Request: '.$client->__getLastRequest());
				LinksynceparcelHelper::log('printManifest  Response: '.$client->__getLastResponse());
			}
		}
		catch(Exception $e)
		{
			if(LINKSYNC_DEBUG == 1 && $client)
			{
				LinksynceparcelHelper::log('printManifest  Request: '.$client->__getLastRequest());
				LinksynceparcelHelper::log('printManifest  Response: '.$client->__getLastResponse());
			}
			throw $e;
		}
	}
	
	public static function assignConsignmentToManifest($consignmentNumber)
	{
		try
		{
			if(LINKSYNC_DEBUG == 1)
			{
				$client = new SoapClient(self::getWebserviceUrl(true).'?WSDL',array('trace'=>1));
			}
			else
			{
				$client = new SoapClient(self::getWebserviceUrl(true).'?WSDL');
			}
			
			$laid = get_option('linksynceparcel_laid');
			
			$stdClass = $client->assignConsignmentToManifest($laid,$consignmentNumber);

			if($stdClass)
			{
				if(LINKSYNC_DEBUG == 1)
				{
					LinksynceparcelHelper::log('assignConsignmentToManifest Request: '.$client->__getLastRequest());
					LinksynceparcelHelper::log('assignConsignmentToManifest Response: '.$client->__getLastResponse());
				}
				return $stdClass;
			}
			if(LINKSYNC_DEBUG == 1 && $client)
			{
				LinksynceparcelHelper::log('assignConsignmentToManifest Request: '.$client->__getLastRequest());
				LinksynceparcelHelper::log('assignConsignmentToManifest Response: '.$client->__getLastResponse());
			}
		}
		catch(Exception $e)
		{
			if(LINKSYNC_DEBUG == 1 && $client)
			{
				LinksynceparcelHelper::log('assignConsignmentToManifest Request: '.$client->__getLastRequest());
				LinksynceparcelHelper::log('assignConsignmentToManifest Response: '.$client->__getLastResponse());
			}
			throw $e;
		}
	}
	
	public static function getVersionNumber()
	{
		try
		{
			if(LINKSYNC_DEBUG == 1)
			{
				$client = new SoapClient(self::getWebserviceUrl(true).'?WSDL',array('trace'=>1));
			}
			else
			{
				$client = new SoapClient(self::getWebserviceUrl(true).'?WSDL');
			}
			
			$laid = get_option('linksynceparcel_laid');
			$stdClass = $client->getVersionNumber($laid); 

			if($stdClass)
			{
				if(LINKSYNC_DEBUG == 1)
				{
					LinksynceparcelHelper::log('getVersionNumber Request: '.$client->__getLastRequest());
					LinksynceparcelHelper::log('getVersionNumber Response: '.$client->__getLastResponse());
				}
				return $stdClass;
			}
		}
		catch(Exception $e)
		{
			if(LINKSYNC_DEBUG == 1 && $client)
			{
				LinksynceparcelHelper::log('getVersionNumber Request: '.$client->__getLastRequest());
				LinksynceparcelHelper::log('getVersionNumber Response: '.$client->__getLastResponse());
			}
			return $e->getMessage();
		}
	}
	
	public static function getNotDespatchedConsignments()
	{
		try
		{
			if(LINKSYNC_DEBUG == 1)
			{
				$client = new SoapClient(self::getWebserviceUrl(true).'?WSDL',array('trace'=>1));
			}
			else
			{
				$client = new SoapClient(self::getWebserviceUrl(true).'?WSDL');
			}
			
			$laid = get_option('linksynceparcel_laid');
			
			$stdClass = $client->getNotDespatchedConsignments($laid); 

			if($stdClass)
			{
				if(LINKSYNC_DEBUG == 1)
				{
					LinksynceparcelHelper::log('getNotDespatchedConsignments  Request: '.$client->__getLastRequest());
					LinksynceparcelHelper::log('getNotDespatchedConsignments  Response: '.$client->__getLastResponse());
				}
				return $stdClass->consignments;
			}
			
			if(LINKSYNC_DEBUG == 1 && $client)
			{
				LinksynceparcelHelper::log('getNotDespatchedConsignments  Request: '.$client->__getLastRequest());
				LinksynceparcelHelper::log('getNotDespatchedConsignments  Response: '.$client->__getLastResponse());
			}
		}
		catch(Exception $e)
		{
			if(LINKSYNC_DEBUG == 1 && $client)
			{
				@LinksynceparcelHelper::log('getNotDespatchedConsignmentsResponse  Request: '.$client->__getLastRequest());
				@LinksynceparcelHelper::log('getNotDespatchedConsignmentsResponse  Response: '.$client->__getLastResponse());
			}
			return $e->getMessage();
		}
	}
	
	public static function despatchManifest()
	{
		try
		{
			if(LINKSYNC_DEBUG == 1)
			{
				$client = new SoapClient(self::getWebserviceUrl(true).'?WSDL',array('trace'=>1));
			}
			else
			{
				$client = new SoapClient(self::getWebserviceUrl(true).'?WSDL');
			}
			
			$laid = get_option('linksynceparcel_laid');
			
			$stdClass = $client->despatchManifest($laid); 

			if($stdClass)
			{
				if(LINKSYNC_DEBUG == 1)
				{
					LinksynceparcelHelper::log('despatchManifest  Request: '.$client->__getLastRequest());
					LinksynceparcelHelper::log('despatchManifest  Response: '.$client->__getLastResponse());
				}
				return $stdClass;
			}
			
			if(LINKSYNC_DEBUG == 1 && $client)
			{
				LinksynceparcelHelper::log('despatchManifest  Request: '.$client->__getLastRequest());
				LinksynceparcelHelper::log('despatchManifest  Response: '.$client->__getLastResponse());
			}
		}
		catch(Exception $e)
		{
			if(LINKSYNC_DEBUG == 1 && $client)
			{
				LinksynceparcelHelper::log('despatchManifest  Request: '.$client->__getLastRequest());
				LinksynceparcelHelper::log('despatchManifest  Response: '.$client->__getLastResponse());
			}
			throw $e;
		}
	}
}
?>