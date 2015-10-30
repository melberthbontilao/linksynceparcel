<?php
require(linksynceparcel_DIR.'helpers/LinksynceparcelValidator.php');
require(linksynceparcel_DIR.'helpers/LinksynceparcelApi.php');
class LinksynceparcelHelper
{
	public static function createTables()
	{
		global $wpdb;
		
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		
   		$table_name = $wpdb->prefix . "linksynceparcel_address_valid"; 
		$sql = "CREATE TABLE IF NOT EXISTS $table_name (
			  order_id int(10) PRIMARY KEY,
			  is_address_valid TINYINT(1) DEFAULT '0'
		  );";
		dbDelta( $sql );
		
		$table_name = $wpdb->prefix . "linksynceparcel_consignment"; 
		$sql = "CREATE TABLE $table_name (
			 	`order_id` int(11) NOT NULL default '0',
				`consignment_number` varchar(128) CHARACTER SET utf8 NOT NULL,
				`add_date` varchar(40) CHARACTER SET utf8 NOT NULL,
				`modify_date` varchar(40) CHARACTER SET utf8 NOT NULL,
				`delivery_signature_allowed` varchar(255) CHARACTER SET utf8 ,
				`print_return_labels` varchar(255) CHARACTER SET utf8 NOT NULL,
				`contains_dangerous_goods` varchar(255) CHARACTER SET utf8 NOT NULL,
				`partial_delivery_allowed` varchar(255) CHARACTER SET utf8 NOT NULL,
				`cash_to_collect` varchar(255) CHARACTER SET utf8 NOT NULL,
				`despatched` tinyint(1) NOT NULL DEFAULT '0',
				`label` varchar(255) CHARACTER SET utf8,
				`manifest_number` varchar(255) CHARACTER SET utf8,
				`is_next_manifest` tinyint(1) NOT NULL DEFAULT '0',
				`is_label_printed` tinyint(1) NOT NULL DEFAULT '0', 
				`is_label_created` tinyint(1) NOT NULL DEFAULT '0',
				`email_notification` tinyint(1) DEFAULT '0',
				`notify_customers` tinyint(1) DEFAULT '0',
				`is_return_label_printed` tinyint(1) DEFAULT '0',
				`chargecode` varchar(255) CHARACTER SET utf8
		  ) ENGINE=InnoDB  DEFAULT CHARSET=utf8;";
		dbDelta( $sql );
		
		$table_name = $wpdb->prefix . "linksynceparcel_article"; 
		$sql = "CREATE TABLE $table_name (
			 	`order_id` int(11) NOT NULL default '0',
				`consignment_number` varchar(255) CHARACTER SET utf8 NOT NULL,
				`article_number` varchar(255) CHARACTER SET utf8 NOT NULL,
				`actual_weight` varchar(255) CHARACTER SET utf8 NOT NULL,
				`article_description` varchar(255) CHARACTER SET utf8 NOT NULL,
				`cubic_weight` varchar(255) CHARACTER SET utf8 NOT NULL,
				`height` varchar(255) CHARACTER SET utf8 NOT NULL,
				`is_transit_cover_required` varchar(255) CHARACTER SET utf8 NOT NULL,
				`transit_cover_amount` varchar(255) CHARACTER SET utf8 NOT NULL,
				`length` varchar(40) CHARACTER SET utf8 NOT NULL,
				`width` varchar(255) CHARACTER SET utf8
		  ) ENGINE=InnoDB  DEFAULT CHARSET=utf8;";
		dbDelta( $sql );
		
		$table_name = $wpdb->prefix . "linksynceparcel_manifest"; 
		$sql = "CREATE TABLE $table_name (
			 	`manifest_id` int(11) NOT NULL auto_increment primary key,																		   
				`manifest_number` varchar(255) CHARACTER SET utf8 NOT NULL,
				`despatch_date` varchar(40) CHARACTER SET utf8 NOT NULL,
				`label` varchar(255) CHARACTER SET utf8 NOT NULL,
				`number_of_articles` int(11) NOT NULL,
				`number_of_consignments` int(11) NOT NULL
		  ) ENGINE=InnoDB  DEFAULT CHARSET=utf8;";
		dbDelta( $sql );
		
		$table_name = $wpdb->prefix . "linksynceparcel_article_preset"; 
		$sql = "CREATE TABLE $table_name (
			 	`id` int(11) NOT NULL auto_increment,
				`name` varchar(255) CHARACTER SET utf8 NOT NULL,
				`weight` varchar(40) CHARACTER SET utf8 NOT NULL,
				`width` varchar(40) CHARACTER SET utf8 NOT NULL,
				`height` varchar(40) CHARACTER SET utf8 NOT NULL,
				`length` varchar(40) CHARACTER SET utf8 NOT NULL,
				`status` tinyint(1) NOT NULL DEFAULT '1',
				PRIMARY KEY  (`id`)
		  ) ENGINE=InnoDB  DEFAULT CHARSET=utf8;";
		dbDelta( $sql );
		
		$table_name = $wpdb->prefix . "linksynceparcel_nonlinksync"; 
		$sql = "CREATE TABLE $table_name (
			 	`id` int(11) NOT NULL auto_increment,
				`method` varchar(255) CHARACTER SET utf8 NOT NULL,
				`charge_code` varchar(255) CHARACTER SET utf8 NOT NULL,
				PRIMARY KEY  (`id`)
		  ) ENGINE=InnoDB  DEFAULT CHARSET=utf8;";

		dbDelta( $sql );
		
		self::upgradeTables();
	}
	
	public static function upgradeTables()
	{
		global $wpdb;
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		
		$table_name = $wpdb->prefix . "linksynceparcel_consignment"; 
		
		$sql = "SHOW INDEX FROM $table_name WHERE Key_name = 'con_order_id_despatched'";
		$indexes = $wpdb->query( $sql );
		if(!$indexes)
		{
			$sql = "ALTER TABLE $table_name ADD INDEX `con_order_id_despatched` ( `order_id` , `despatched` ),  ADD INDEX `con_consignment_number` ( `consignment_number`), ADD INDEX `con_manifest_number` ( `manifest_number`)";
			$wpdb->query( $sql );
		}
		
		$table_name = $wpdb->prefix . "linksynceparcel_article"; 
		$sql = "SHOW INDEX FROM $table_name WHERE Key_name = 'art_consignment_number'";
		$indexes = $wpdb->query( $sql );
		if(!$indexes)
		{
			$sql = "ALTER TABLE $table_name ADD INDEX `art_consignment_number` ( `consignment_number`)";
			$wpdb->query( $sql );
		}
		
		$table_name = $wpdb->prefix . "linksynceparcel_manifest"; 
		$sql = "SHOW INDEX FROM $table_name WHERE Key_name = 'con_manifest_number'";
		$indexes = $wpdb->query( $sql );
		if(!$indexes)
		{
			$sql = "ALTER TABLE $table_name ADD INDEX `con_manifest_number` ( `manifest_number`)";
			$wpdb->query( $sql );
		}
		
		$table_name = $wpdb->prefix . "linksynceparcel_consignment"; 
		
		$sql = "SELECT * FROM information_schema.COLUMNS WHERE  TABLE_SCHEMA = '".DB_NAME."' AND TABLE_NAME = '$table_name' AND COLUMN_NAME = 'weight'";
		$records = $wpdb->query( $sql );
		if(!$records)
		{
			$sql = "ALTER TABLE $table_name ADD weight varchar(20)";
			$wpdb->query( $sql );
		}
		
		$table_name = $wpdb->prefix . "linksynceparcel_nonlinksync"; 
		$sql = "SELECT * FROM information_schema.COLUMNS WHERE  TABLE_SCHEMA = '".DB_NAME."' AND TABLE_NAME = '$table_name' AND COLUMN_NAME = 'shipping_type'";
		$records = $wpdb->query( $sql );
		if(!$records)
		{
			$sql = "ALTER TABLE $table_name ADD shipping_type varchar(20)";
			$wpdb->query( $sql );
		}
	
	}

	public static function dropTables()
	{
		global $wpdb;
		$wpdb->query( "DROP TABLE IF EXISTS " . $wpdb->prefix . "linksynceparcel_address_valid" );
		$wpdb->query( "DROP TABLE IF EXISTS " . $wpdb->prefix . "linksynceparcel_consignment" );
		$wpdb->query( "DROP TABLE IF EXISTS " . $wpdb->base_prefix . "linksynceparcel_article" );
		$wpdb->query( "DROP TABLE IF EXISTS " . $wpdb->base_prefix . "linksynceparcel_manifest" );
		$wpdb->query( "DROP TABLE IF EXISTS " . $wpdb->prefix . "linksynceparcel_article_preset" );
		$wpdb->query( "DROP TABLE IF EXISTS " . $wpdb->prefix . "linksynceparcel_nonlinksync" );
	}
	
	public static function getAddressValid($order_id)
	{
		global $wpdb;
		$table_name = $wpdb->prefix . "linksynceparcel_address_valid"; 
		$sql = "SELECT * FROM $table_name WHERE order_id = $order_id";
		$results = $wpdb->get_results($sql);
		foreach($results as $result)
		{
			return $result;
		}
		return false;
	}
	
	public static function updateAddressValid($order_id, $is_address_valid)
	{
		global $wpdb;
		$table_name = $wpdb->prefix . "linksynceparcel_address_valid"; 
		if(self::getAddressValid($order_id))
		{
			$wpdb->update(
				$table_name,
				array(
					'is_address_valid' => $is_address_valid
				),
				array( 'order_id' => $order_id )
			);
		}
		else
		{
			$wpdb->insert(
				$table_name,
				array(
					'order_id' => $order_id,
					'is_address_valid' => $is_address_valid
				)
			);
		}
	}
	
	public static function isOrderAddressValid($order_id, $force=false,$address=false)
	{
		$valid = LinksynceparcelHelper::getAddressValid($order_id);
		if(!$force && isset($valid->is_address_valid) && $valid->is_address_valid)
			return 1;
			
		$data = array();
		if($address)
		{
			$data['city'] = $address['_shipping_city'];
			$data['state'] = $address['_shipping_state'];
			$data['postcode'] = $address['_shipping_postcode'];
		}
		else
		{
			$address = get_post_meta($order_id);
			$data['city'] = $address['_shipping_city'][0];
			$data['state'] = $address['_shipping_state'][0];
			$data['postcode'] = $address['_shipping_postcode'][0];
		}
			
		$status = LinksynceparcelApi::isAddressValid($data);
		if($status == 1)
		{
			self::updateAddressValid($order_id, 1);
		}
		else
		{
			self::updateAddressValid($order_id, 0);
		}
		return $status;
	}
	
	public static function saveDefaultConfiguration()
	{
		global $is_greater_than_21;
		
		$label_format = get_option( 'linksynceparcel_label_format');
		if(empty($label_format))
		{
			$defaults = array();
			$defaults['active'] = 1;
			$defaults['mode'] = 0;
			$defaults['insurance'] = 0;
			$defaults['signature_required'] = 1;
			$defaults['safe_drop'] = 1;
			$defaults['print_return_labels'] = 0;
			$defaults['partial_delivery_allowed'] = 0;
			$defaults['post_email_notification'] = 0;
			$defaults['label_format'] = 'mpp';
			$defaults['apply_to_all'] = 0;
			$defaults['notify_customers'] = 0;
			$defaults['subject'] = 'Order [OrderNumber] tracking number';
			$defaults['email_body'] = "<p>Hello [CustomerFirstname],</p><p>&nbsp;</p><p>Your order [OrderNumber] will be despatched today, and once shipped, you'll be able to use the following link to track it:&nbsp;</p><p>&nbsp;</p><p><a href='http://auspost.com.au/track/track.html?id=[TrackingNumber]'>http://auspost.com.au/track/track.html?id=[TrackingNumber]</a></p>";
			$defaults['mark_despatch'] = 0;
			$defaults['use_order_weight'] = 0;
			$defaults['display_order_status'] = 0;
			$defaults['display_choosen_status'] = 0;
			$defaults['use_dimension'] = 1;
			$defaults['product_unit'] = 'kgs';
			$defaults['copy_order_notes'] = 0;
			$defaults['declared_value'] = 1;
			
			if($is_greater_than_21)
			{
				$defaults['chosen_statuses'] = array('wc-pending','wc-processing','wc-on-hold');
			}
			else
			{
				$defaults['chosen_statuses'] = array('pending','processing','on-hold');
			}
			
			foreach($defaults as $key => $val)
			{
				update_option( 'linksynceparcel_'.$key, $val);
			}
		}
	}
	
	public static function saveConfiguration($values)
	{
		foreach($values as $key => $val)
		{
			update_option( 'linksynceparcel_'.$key, $val);
		}
	}
	
	public static function getStates()
	{
		$states = array();
		$states['ACT'] = 'ACT';
		$states['NSW'] = 'NSW';
		$states['NT'] = 'NT';
		$states['QLD'] = 'QLD';
		$states['SA'] = 'SA';
		$states['TAS'] = 'TAS';
		$states['VIC'] = 'VIC';
		$states['WA'] = 'WA';
		return $states;
	}
	
	public static function getWooCountries() {
		return WC()->countries->countries;
	}
	
	public static function getCountries() {
		$countries_url = 'http://data.okfn.org/data/core/country-list/r/data.json';
		$json_data = self::fetchDataTags($countries_url);
		$countries_data = self::decodeString($json_data);
		if(!empty($countries_data)) {
			$countries = array();
			foreach($countries_data as $country_key => $country) {
				$countries[$country->Code] = $country->Name;
			}
			return $countries;
		}
		return false;
	}
	
	public static function fetchDataTags($url) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 20);
		$result = curl_exec($ch);
		curl_close($ch); 
		return $result;
	}
	
	public static function decodeString($string) {
		return json_decode($string);
	}
	
	public static function wpbo_get_woo_version_number()
	{
		if (!function_exists( 'get_plugins' ) )
			require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		
		$plugin_folder = get_plugins( '/' . 'woocommerce' );
		$plugin_file = 'woocommerce.php';
		
		if ( isset( $plugin_folder[$plugin_file]['Version'] ) )
		{
			return $plugin_folder[$plugin_file]['Version'];
		}
		else
		{
			return NULL;
		}
	}
	
	public static function woocommerce_version_check($current_version,$version = '2.2' )
	{
		if( version_compare($current_version, $version, ">=" ) )
		{
			return true;
		}
		return false;
	}
	
	public static function getOrderStatuses()
	{
		$current_version = self::wpbo_get_woo_version_number();
		if(self::woocommerce_version_check($current_version))
		{
			return wc_get_order_statuses();
		}
		else
		{
			return (array)get_terms('shop_order_status', array('hide_empty' => 0, 'orderby' => 'id'));
		}
	}
	
	public static function saveLabelLogo()
	{
		if(is_uploaded_file($_FILES['label_logo']['tmp_name']))
		{
			$filename = linksynceparcel_DIR.'assets/images/label_logo.png';
			$image = wp_get_image_editor($_FILES['label_logo']['tmp_name']);
			if (!is_wp_error($image))
			{
				$image->resize(160, 65, true);
				$image->save($filename);
				update_option('linksynceparcel_label_logo', 'label_logo.png');
			}
		}
	}
	
	public static function log($content, $date = true)
	{
		$filename = linksynceparcel_DIR.'/log/linksynceparcel.log';
		$fp = fopen($filename, 'a+');
		if($date)
		{
			fwrite($fp, date("Y-m-d H:i:s").": ");
		}
		fwrite($fp, print_r($content, TRUE));
		fwrite($fp, "\n");
		fclose($fp);
	}
	
	public static function isZipArchiveInstalled()
	{
		if(class_exists('ZipArchive'))
		{
			return 1;
		}
		else
		{
			return 0;
		}
	}
	
	public static function createZip($file,$archiveFile) 
	{
		$ziph = new ZipArchive();
		if(file_exists($archiveFile))
		{
			if($ziph->open($archiveFile, ZIPARCHIVE::CHECKCONS) !== TRUE)
			{
				throw new Exception("Unable to Open $archiveFile");
			}
		}
		else
		{
		  	if($ziph->open($archiveFile, ZIPARCHIVE::CM_PKWARE_IMPLODE) !== TRUE)
		  	{
				throw new Exception("Could not Create $archiveFile");
		  	}
		}
		
		if(file_exists($file))
		{
			if(is_readable($file))
			{
			  	if(!$ziph->addFile($file,'linksynceparcel.log'))
				{
				  throw new Exception("Error archiving $file in $archiveFile");
				}
			}
			else
			{
				throw new Exception("Error archiving $file is not readable");
			}
		}
		else
		{
			throw new Exception("Error archiving $file is not exist");
		}		
		$ziph->close();
		return true;
	}
	
	public static function getFormValue($index, $default='', $main='linksynceparcel')
	{
		$value = '';
		if(!empty($main))
		{
			$data = isset($_REQUEST[$main]) ? $_REQUEST[$main] : array();
			$value = isset($data[$index]) ? trim($data[$index]) : '';
		}
		else
		{
			$value = isset($_REQUEST[$index]) ? trim($_REQUEST[$index]) : '';
		}
		
		if($value === 0)
			return $value;
		else
			return stripslashes(empty($value) ? $default : $value);
	}
	
	public static function getEParcelChargeCodes() {
		$chargeCode = array(
			'B1' 	=> array(
				'key'			=> 'parcel_post',
				'name'			=> 'eParcel Standard',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 60,
				'labelType' 	=> 'Parcel Post',
				'template' 		=> 'EPARCEL'
			), 
			'B2' 	=> array(
				'key'			=> 'parcel_post',
				'name'			=> 'eParcel Standard',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 60,
				'labelType' 	=> 'Parcel Post',
				'template' 		=> 'EPARCEL'
			), 
			'B3' 	=> array(
				'key'			=> 'parcel_post',
				'name'			=> 'eParcel Standard',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 60,
				'labelType' 	=> 'Parcel Post',
				'template' 		=> 'EPARCEL'
			), 
			'B4' 	=> array(
				'key'			=> 'parcel_post',
				'name'			=> 'eParcel Standard',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 60,
				'labelType' 	=> 'Parcel Post',
				'template' 		=> 'EPARCEL'
			), 
			'B5' 	=> array(
				'key'			=> 'parcel_post',
				'name'			=> 'eParcel Standard',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 60,
				'labelType' 	=> 'Parcel Post',
				'template' 		=> 'EPARCEL'
			), 
			'B96' 	=> array(
				'key'			=> 'parcel_post',
				'name'			=> 'eParcel Standard',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 60,
				'labelType' 	=> 'Parcel Post',
				'template' 		=> 'EPARCEL'
			), 
			'B97' 	=> array(
				'key'			=> 'parcel_post',
				'name'			=> 'eParcel Standard',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 60,
				'labelType' 	=> 'Parcel Post',
				'template' 		=> 'EPARCEL'
			), 
			'B98' 	=> array(
				'key'			=> 'parcel_post',
				'name'			=> 'eParcel Standard',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 60,
				'labelType' 	=> 'Parcel Post',
				'template' 		=> 'EPARCEL'
			), 
			'D1' 	=> array(
				'key'			=> 'parcel_post',
				'name'			=> 'eParcel Standard',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 60,
				'labelType' 	=> 'Parcel Post',
				'template' 		=> 'EPARCEL'
			), 
			'DE1' 	=> array(
				'key'			=> 'parcel_post',
				'name'			=> 'eParcel Standard',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 60,
				'labelType' 	=> 'Parcel Post',
				'template' 		=> 'EPARCEL'
			), 
			'DE2' 	=> array(
				'key'			=> 'parcel_post',
				'name'			=> 'eParcel Standard',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 60,
				'labelType' 	=> 'Parcel Post',
				'template' 		=> 'EPARCEL'
			), 
			'DE4' 	=> array(
				'key'			=> 'parcel_post',
				'name'			=> 'eParcel Standard',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 60,
				'labelType' 	=> 'Parcel Post',
				'template' 		=> 'EPARCEL'
			), 
			'DE5' 	=> array(
				'key'			=> 'parcel_post',
				'name'			=> 'eParcel Standard',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 60,
				'labelType' 	=> 'Parcel Post',
				'template' 		=> 'EPARCEL'
			), 
			'DE6' 	=> array(
				'key'			=> 'parcel_post',
				'name'			=> 'eParcel Standard',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 60,
				'labelType' 	=> 'Parcel Post',
				'template' 		=> 'EPARCEL'
			), 
			'MED1'	=> array(
				'key'			=> 'parcel_post',
				'name'			=> 'eParcel Standard',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 60,
				'labelType' 	=> 'Parcel Post',
				'template' 		=> 'EPARCEL'
			), 
			'MED2'	=> array(
				'key'			=> 'parcel_post',
				'name'			=> 'eParcel Standard',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 60,
				'labelType' 	=> 'Parcel Post',
				'template' 		=> 'EPARCEL'
			), 
			'S1'	=> array(
				'key'			=> 'parcel_post',
				'name'			=> 'eParcel Standard',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 60,
				'labelType' 	=> 'Parcel Post',
				'template' 		=> 'EPARCEL'
			), 
			'S10'	=> array(
				'key'			=> 'parcel_post',
				'name'			=> 'eParcel Standard',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 60,
				'labelType' 	=> 'Parcel Post',
				'template' 		=> 'EPARCEL'
			), 
			'S2'	=> array(
				'key'			=> 'parcel_post',
				'name'			=> 'eParcel Standard',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 60,
				'labelType' 	=> 'Parcel Post',
				'template' 		=> 'EPARCEL'
			), 
			'S3'	=> array(
				'key'			=> 'parcel_post',
				'name'			=> 'eParcel Standard',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 60,
				'labelType' 	=> 'Parcel Post',
				'template' 		=> 'EPARCEL'
			),
			'S4'	=> array(
				'key'			=> 'parcel_post',
				'name'			=> 'eParcel Standard',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 60,
				'labelType' 	=> 'Parcel Post',
				'template' 		=> 'EPARCEL'
			), 
			'S5'	=> array(
				'key'			=> 'parcel_post',
				'name'			=> 'eParcel Standard',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 60,
				'labelType' 	=> 'Parcel Post',
				'template' 		=> 'EPARCEL'
			), 
			'S6'	=> array(
				'key'			=> 'parcel_post',
				'name'			=> 'eParcel Standard',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 60,
				'labelType' 	=> 'Parcel Post',
				'template' 		=> 'EPARCEL'
			), 
			'S7'	=> array(
				'key'			=> 'parcel_post',
				'name'			=> 'eParcel Standard',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 60,
				'labelType' 	=> 'Parcel Post',
				'template' 		=> 'EPARCEL'
			), 
			'S8'	=> array(
				'key'			=> 'parcel_post',
				'name'			=> 'eParcel Standard',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 60,
				'labelType' 	=> 'Parcel Post',
				'template' 		=> 'EPARCEL'
			), 
			'S9'	=> array(
				'key'			=> 'parcel_post',
				'name'			=> 'eParcel Standard',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 60,
				'labelType' 	=> 'Parcel Post',
				'template' 		=> 'EPARCEL'
			), 
			'SV1'	=> array(
				'key'			=> 'parcel_post',
				'name'			=> 'eParcel Standard',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 60,
				'labelType' 	=> 'Parcel Post',
				'template' 		=> 'EPARCEL'
			),
			'SV2'	=> array(
				'key'			=> 'parcel_post',
				'name'			=> 'eParcel Standard',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 60,
				'labelType' 	=> 'Parcel Post',
				'template' 		=> 'EPARCEL'
			),
			'W5'	=> array(
				'key'			=> 'parcel_post',
				'name'			=> 'eParcel Standard',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 60,
				'labelType' 	=> 'Parcel Post',
				'template' 		=> 'EPARCEL'
			), 
			'W6'	=> array(
				'key'			=> 'parcel_post',
				'name'			=> 'eParcel Standard',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 60,
				'labelType' 	=> 'Parcel Post',
				'template' 		=> 'EPARCEL'
			),
			'X1'	=> array(
				'key'			=> 'express_post',
				'name'			=> 'Express Post eParcel',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 61,
				'labelType' 	=> 'Express Post',
				'template' 		=> 'EPARCEL EXPRESS'
			), 
			'X2'	=> array(
				'key'			=> 'express_post',
				'name'			=> 'Express Post eParcel',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 61,
				'labelType' 	=> 'Express Post',
				'template' 		=> 'EPARCEL EXPRESS'
			), 
			'X5'	=> array(
				'key'			=> 'express_post',
				'name'			=> 'Express Post eParcel',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 61,
				'labelType' 	=> 'Express Post',
				'template' 		=> 'EPARCEL EXPRESS'
			), 
			'X6'	=> array(
				'key'			=> 'express_post',
				'name'			=> 'Express Post eParcel',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 61,
				'labelType' 	=> 'Express Post',
				'template' 		=> 'EPARCEL EXPRESS'
			),
			'XB1'	=> array(
				'key'			=> 'express_post',
				'name'			=> 'Express Post eParcel',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 61,
				'labelType' 	=> 'Express Post',
				'template' 		=> 'EPARCEL EXPRESS'
			), 
			'XB2'	=> array(
				'key'			=> 'express_post',
				'name'			=> 'Express Post eParcel',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 61,
				'labelType' 	=> 'Express Post',
				'template' 		=> 'EPARCEL EXPRESS'
			),
			'XB3'	=> array(
				'key'			=> 'express_post',
				'name'			=> 'Express Post eParcel',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 61,
				'labelType' 	=> 'Express Post',
				'template' 		=> 'EPARCEL EXPRESS'
			), 
			'XB4'	=> array(
				'key'			=> 'express_post',
				'name'			=> 'Express Post eParcel',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 61,
				'labelType' 	=> 'Express Post',
				'template' 		=> 'EPARCEL EXPRESS'
			), 
			'XB5'	=> array(
				'key'			=> 'express_post',
				'name'			=> 'Express Post eParcel',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 61,
				'labelType' 	=> 'Express Post',
				'template' 		=> 'EPARCEL EXPRESS'
			), 
			'XDE5'	=> array(
				'key'			=> 'express_post',
				'name'			=> 'Express Post eParcel',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 61,
				'labelType' 	=> 'Express Post',
				'template' 		=> 'EPARCEL EXPRESS'
			),
			'XW5'	=> array(
				'key'			=> 'express_post',
				'name'			=> 'Express Post eParcel',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 61,
				'labelType' 	=> 'Express Post',
				'template' 		=> 'EPARCEL EXPRESS'
			),
			'XW6'	=> array(
				'key'			=> 'express_post',
				'name'			=> 'Express Post eParcel',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 61,
				'labelType' 	=> 'Express Post',
				'template' 		=> 'EPARCEL EXPRESS'
			),
			'Xs'	=> array(
				'key'			=> 'express_post',
				'name'			=> 'Express Post eParcel',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 61,
				'labelType' 	=> 'Express Post',
				'template' 		=> 'EPARCEL EXPRESS'
			),
			'3E03'	=> array(
				'key'			=> 'parcel_post',
				'name'			=> 'Parcel Post',
				'serviceCode' 	=> 9,
				'prodCode' 		=> 91,
				'labelType' 	=> 'Parcel Post',
				'template' 		=> 'EPARCEL'
			), 
			'7E03'	=> array(
				'key'			=> 'parcel_post',
				'name'			=> 'Parcel Post',
				'serviceCode' 	=> 9,
				'prodCode' 		=> 91,
				'labelType' 	=> 'Parcel Post',
				'template' 		=> 'EPARCEL'
			),  
			'3E05'	=> array(
				'key'			=> 'parcel_post',
				'name'			=> 'Parcel Post + Signature',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 93,
				'labelType' 	=> 'Parcel Post',
				'template' 		=> 'EPARCEL'
			),  
			'7E05'	=> array(
				'key'			=> 'parcel_post',
				'name'			=> 'Parcel Post + Signature',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 93,
				'labelType' 	=> 'Parcel Post',
				'template' 		=> 'EPARCEL'
			), 
			'3E33'	=> array(
				'key'			=> 'parcel_post',
				'name'			=> 'Parcel Post',
				'serviceCode' 	=> 9,
				'prodCode' 		=> 91,
				'labelType' 	=> 'Parcel Post',
				'template' 		=> 'EPARCEL'
			), 
			'7E33'	=> array(
				'key'			=> 'parcel_post',
				'name'			=> 'Parcel Post',
				'serviceCode' 	=> 9,
				'prodCode' 		=> 91,
				'labelType' 	=> 'Parcel Post',
				'template' 		=> 'EPARCEL'
			),  
			'3E35'	=> array(
				'key'			=> 'parcel_post',
				'name'			=> 'Parcel Post + Signature',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 93,
				'labelType' 	=> 'Parcel Post',
				'template' 		=> 'EPARCEL'
			), 
			'7E35'	=> array(
				'key'			=> 'parcel_post',
				'name'			=> 'Parcel Post + Signature',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 93,
				'labelType' 	=> 'Parcel Post',
				'template' 		=> 'EPARCEL'
			), 
			'3E53'	=> array(
				'key'			=> 'parcel_post',
				'name'			=> 'Parcel Post',
				'serviceCode' 	=> 9,
				'prodCode' 		=> 91,
				'labelType' 	=> 'Parcel Post',
				'template' 		=> 'EPARCEL'
			), 
			'7E53'	=> array(
				'key'			=> 'parcel_post',
				'name'			=> 'Parcel Post',
				'serviceCode' 	=> 9,
				'prodCode' 		=> 91,
				'labelType' 	=> 'Parcel Post',
				'template' 		=> 'EPARCEL'
			), 
			'3E55'	=> array(
				'key'			=> 'parcel_post',
				'name'			=> 'Parcel Post + Signature',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 93,
				'labelType' 	=> 'Parcel Post',
				'template' 		=> 'EPARCEL'
			),  
			'7E55'	=> array(
				'key'			=> 'parcel_post',
				'name'			=> 'Parcel Post + Signature',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 93,
				'labelType' 	=> 'Parcel Post',
				'template' 		=> 'EPARCEL'
			), 
			'3E83'	=> array(
				'key'			=> 'parcel_post',
				'name'			=> 'Parcel Post',
				'serviceCode' 	=> 9,
				'prodCode' 		=> 91,
				'labelType' 	=> 'Parcel Post',
				'template' 		=> 'EPARCEL'
			), 
			'7E83'	=> array(
				'key'			=> 'parcel_post',
				'name'			=> 'Parcel Post',
				'serviceCode' 	=> 9,
				'prodCode' 		=> 91,
				'labelType' 	=> 'Parcel Post',
				'template' 		=> 'EPARCEL'
			), 
			'3E85'	=> array(
				'key'			=> 'parcel_post',
				'name'			=> 'Parcel Post + Signature',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 93,
				'labelType' 	=> 'Parcel Post',
				'template' 		=> 'EPARCEL'
			), 
			'7E85'	=> array(
				'key'			=> 'parcel_post',
				'name'			=> 'Parcel Post + Signature',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 93,
				'labelType' 	=> 'Parcel Post',
				'template' 		=> 'EPARCEL'
			), 
			'2A33'	=> array(
				'key'			=> 'parcel_post',
				'name'			=> '500g Parcel Post Satchel',
				'serviceCode' 	=> 9,
				'prodCode' 		=> 91,
				'labelType' 	=> 'Parcel Post',
				'template' 		=> 'EPARCEL'
			),
			'2A35'	=> array(
				'key'			=> 'parcel_post',
				'name'			=> '500g Parcel Post Satchel + Sig',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 93,
				'labelType' 	=> 'Parcel Post',
				'template' 		=> 'EPARCEL'
			),
			'2B33'	=> array(
				'key'			=> 'parcel_post',
				'name'			=> '500g Parcel Post Satchel',
				'serviceCode' 	=> 9,
				'prodCode' 		=> 91,
				'labelType' 	=> 'Parcel Post',
				'template' 		=> 'EPARCEL'
			),
			'2B35'	=> array(
				'key'			=> 'parcel_post',
				'name'			=> '500g Parcel Post Satchel + Sig',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 93,
				'labelType' 	=> 'Parcel Post',
				'template' 		=> 'EPARCEL'
			),
			'2C33'	=> array(
				'key'			=> 'parcel_post',
				'name'			=> '500g Parcel Post Satchel',
				'serviceCode' 	=> 9,
				'prodCode' 		=> 91,
				'labelType' 	=> 'Parcel Post',
				'template' 		=> 'EPARCEL'
			),
			'2C35'	=> array(
				'key'			=> 'parcel_post',
				'name'			=> '500g Parcel Post Satchel + Sig',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 93,
				'labelType' 	=> 'Parcel Post',
				'template' 		=> 'EPARCEL'
			),
			'2D33'	=> array(
				'key'			=> 'parcel_post',
				'name'			=> '500g Parcel Post Satchel',
				'serviceCode' 	=> 9,
				'prodCode' 		=> 91,
				'labelType' 	=> 'Parcel Post',
				'template' 		=> 'EPARCEL'
			),
			'2D35'	=> array(
				'key'			=> 'parcel_post',
				'name'			=> '500g Parcel Post Satchel + Sig',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 93,
				'labelType' 	=> 'Parcel Post',
				'template' 		=> 'EPARCEL'
			),
			'2G33'	=> array(
				'key'			=> 'express_post',
				'name'			=> '500g Express Post Satchel',
				'serviceCode' 	=> 9,
				'prodCode' 		=> 87,
				'labelType' 	=> 'Express Post',
				'template' 		=> 'EPARCEL EXPRESS'
			),
			'2G35'	=> array(
				'key'			=> 'express_post',
				'name'			=> '500g Express Post Satchel + Sig',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 96,
				'labelType' 	=> 'Express Post',
				'template' 		=> 'EPARCEL EXPRESS'
			),
			'2H33'	=> array(
				'key'			=> 'express_post',
				'name'			=> '500g Express Post Satchel',
				'serviceCode' 	=> 9,
				'prodCode' 		=> 87,
				'labelType' 	=> 'Express Post',
				'template' 		=> 'EPARCEL EXPRESS'
			),
			'2H35'	=> array(
				'key'			=> 'express_post',
				'name'			=> '500g Express Post Satchel + Sig',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 96,
				'labelType' 	=> 'Express Post',
				'template' 		=> 'EPARCEL EXPRESS'
			),
			'2I33'	=> array(
				'key'			=> 'express_post',
				'name'			=> '500g Express Post Satchel',
				'serviceCode' 	=> 9,
				'prodCode' 		=> 87,
				'labelType' 	=> 'Express Post',
				'template' 		=> 'EPARCEL EXPRESS'
			),
			'2I35'	=> array(
				'key'			=> 'express_post',
				'name'			=> '500g Express Post Satchel + Sig',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 96,
				'labelType' 	=> 'Express Post',
				'template' 		=> 'EPARCEL EXPRESS'
			),
			'2J33'	=> array(
				'key'			=> 'express_post',
				'name'			=> '500g Express Post Satchel',
				'serviceCode' 	=> 9,
				'prodCode' 		=> 87,
				'labelType' 	=> 'Express Post',
				'template' 		=> 'EPARCEL EXPRESS'
			),
			'2J35'	=> array(
				'key'			=> 'express_post',
				'name'			=> '500g Express Post Satchel + Sig',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 96,
				'labelType' 	=> 'Express Post',
				'template' 		=> 'EPARCEL EXPRESS'
			),
			'3B03'	=> array(
				'key'			=> 'parcel_post',
				'name'			=> 'Parcel Post',
				'serviceCode' 	=> 9,
				'prodCode' 		=> 91,
				'labelType' 	=> 'Parcel Post',
				'template' 		=> 'EPARCEL'
			),
			'3B05'	=> array(
				'key'			=> 'parcel_post',
				'name'			=> 'Parcel Post + Signature',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 93,
				'labelType' 	=> 'Parcel Post',
				'template' 		=> 'EPARCEL'
			),
			'3C03'	=> array(
				'key'			=> 'parcel_post',
				'name'			=> 'Parcel Post',
				'serviceCode' 	=> 9,
				'prodCode' 		=> 91,
				'labelType' 	=> 'Parcel Post',
				'template' 		=> 'EPARCEL'
			),
			'3C05'	=> array(
				'key'			=> 'parcel_post',
				'name'			=> 'Parcel Post + Signature',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 93,
				'labelType' 	=> 'Parcel Post',
				'template' 		=> 'EPARCEL'
			),
			'3C33'	=> array(
				'key'			=> 'parcel_post',
				'name'			=> 'Parcel Post',
				'serviceCode' 	=> 9,
				'prodCode' 		=> 91,
				'labelType' 	=> 'Parcel Post',
				'template' 		=> 'EPARCEL'
			),
			'3C35'	=> array(
				'key'			=> 'parcel_post',
				'name'			=> 'Parcel Post + Signature',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 93,
				'labelType' 	=> 'Parcel Post',
				'template' 		=> 'EPARCEL'
			),
			'3C53'	=> array(
				'key'			=> 'parcel_post',
				'name'			=> 'Parcel Post',
				'serviceCode' 	=> 9,
				'prodCode' 		=> 91,
				'labelType' 	=> 'Parcel Post',
				'template' 		=> 'EPARCEL'
			),
			'3C55'	=> array(
				'key'			=> 'parcel_post',
				'name'			=> 'Parcel Post + Signature',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 93,
				'labelType' 	=> 'Parcel Post',
				'template' 		=> 'EPARCEL'
			),
			'3C83'	=> array(
				'key'			=> 'parcel_post',
				'name'			=> 'Parcel Post',
				'serviceCode' 	=> 9,
				'prodCode' 		=> 91,
				'labelType' 	=> 'Parcel Post',
				'template' 		=> 'EPARCEL'
			),
			'3C85'	=> array(
				'key'			=> 'parcel_post',
				'name'			=> 'Parcel Post + Signature',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 93,
				'labelType' 	=> 'Parcel Post',
				'template' 		=> 'EPARCEL'
			),
			'3D03'	=> array(
				'key'			=> 'parcel_post',
				'name'			=> 'Parcel Post',
				'serviceCode' 	=> 9,
				'prodCode' 		=> 91,
				'labelType' 	=> 'Parcel Post',
				'template' 		=> 'EPARCEL'
			),
			'3D05'	=> array(
				'key'			=> 'parcel_post',
				'name'			=> 'Parcel Post + Signature',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 93,
				'labelType' 	=> 'Parcel Post',
				'template' 		=> 'EPARCEL'
			),
			'3D33'	=> array(
				'key'			=> 'parcel_post',
				'name'			=> 'Parcel Post',
				'serviceCode' 	=> 9,
				'prodCode' 		=> 91,
				'labelType' 	=> 'Parcel Post',
				'template' 		=> 'EPARCEL'
			),
			'3D35'	=> array(
				'key'			=> 'parcel_post',
				'name'			=> 'Parcel Post + Signature',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 93,
				'labelType' 	=> 'Parcel Post',
				'template' 		=> 'EPARCEL'
			),
			'3D53'	=> array(
				'key'			=> 'parcel_post',
				'name'			=> 'Parcel Post',
				'serviceCode' 	=> 9,
				'prodCode' 		=> 91,
				'labelType' 	=> 'Parcel Post',
				'template' 		=> 'EPARCEL'
			),
			'3D55'	=> array(
				'key'			=> 'parcel_post',
				'name'			=> 'Parcel Post + Signature',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 93,
				'labelType' 	=> 'Parcel Post',
				'template' 		=> 'EPARCEL'
			),
			'3D83'	=> array(
				'key'			=> 'parcel_post',
				'name'			=> 'Parcel Post',
				'serviceCode' 	=> 9,
				'prodCode' 		=> 91,
				'labelType' 	=> 'Parcel Post',
				'template' 		=> 'EPARCEL'
			),
			'3D85'	=> array(
				'key'			=> 'parcel_post',
				'name'			=> 'Parcel Post + Signature',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 93,
				'labelType' 	=> 'Parcel Post',
				'template' 		=> 'EPARCEL'
			),
			'3H03'	=> array(
				'key'			=> 'express_post',
				'name'			=> 'Express Post',
				'serviceCode' 	=> 9,
				'prodCode' 		=> 87,
				'labelType' 	=> 'Express Post',
				'template' 		=> 'EPARCEL EXPRESS'
			),
			'3H05'	=> array(
				'key'			=> 'express_post',
				'name'			=> 'Express Post + Signature',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 96,
				'labelType' 	=> 'Express Post',
				'template' 		=> 'EPARCEL EXPRESS'
			),
			'3I03'	=> array(
				'key'			=> 'express_post',
				'name'			=> 'Express Post',
				'serviceCode' 	=> 9,
				'prodCode' 		=> 87,
				'labelType' 	=> 'Express Post',
				'template' 		=> 'EPARCEL EXPRESS'
			),
			'3I05'	=> array(
				'key'			=> 'express_post',
				'name'			=> 'Express Post + Signature',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 96,
				'labelType' 	=> 'Express Post',
				'template' 		=> 'EPARCEL EXPRESS'
			),
			'3I33'	=> array(
				'key'			=> 'express_post',
				'name'			=> 'Express Post',
				'serviceCode' 	=> 9,
				'prodCode' 		=> 87,
				'labelType' 	=> 'Express Post',
				'template' 		=> 'EPARCEL EXPRESS'
			),
			'3I35'	=> array(
				'key'			=> 'express_post',
				'name'			=> 'Express Post + Signature',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 96,
				'labelType' 	=> 'Express Post',
				'template' 		=> 'EPARCEL EXPRESS'
			),
			'3I53'	=> array(
				'key'			=> 'express_post',
				'name'			=> 'Express Post',
				'serviceCode' 	=> 9,
				'prodCode' 		=> 87,
				'labelType' 	=> 'Express Post',
				'template' 		=> 'EPARCEL EXPRESS'
			),
			'3I55'	=> array(
				'key'			=> 'express_post',
				'name'			=> 'Express Post + Signature',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 96,
				'labelType' 	=> 'Express Post',
				'template' 		=> 'EPARCEL EXPRESS'
			),
			'3I83'	=> array(
				'key'			=> 'express_post',
				'name'			=> 'Express Post',
				'serviceCode' 	=> 9,
				'prodCode' 		=> 87,
				'labelType' 	=> 'Express Post',
				'template' 		=> 'EPARCEL EXPRESS'
			),
			'3I85'	=> array(
				'key'			=> 'express_post',
				'name'			=> 'Express Post + Signature',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 96,
				'labelType' 	=> 'Express Post',
				'template' 		=> 'EPARCEL EXPRESS'
			),
			'3J03'	=> array(
				'key'			=> 'express_post',
				'name'			=> 'Express Post',
				'serviceCode' 	=> 9,
				'prodCode' 		=> 87,
				'labelType' 	=> 'Express Post',
				'template' 		=> 'EPARCEL EXPRESS'
			),
			'3J05'	=> array(
				'key'			=> 'express_post',
				'name'			=> 'Express Post + Signature',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 96,
				'labelType' 	=> 'Express Post',
				'template' 		=> 'EPARCEL EXPRESS'
			),
			'3J33'	=> array(
				'key'			=> 'express_post',
				'name'			=> 'Express Post',
				'serviceCode' 	=> 9,
				'prodCode' 		=> 87,
				'labelType' 	=> 'Express Post',
				'template' 		=> 'EPARCEL EXPRESS'
			),
			'3J35'	=> array(
				'key'			=> 'express_post',
				'name'			=> 'Express Post + Signature',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 96,
				'labelType' 	=> 'Express Post',
				'template' 		=> 'EPARCEL EXPRESS'
			),
			'3J53'	=> array(
				'key'			=> 'express_post',
				'name'			=> 'Express Post',
				'serviceCode' 	=> 9,
				'prodCode' 		=> 87,
				'labelType' 	=> 'Express Post',
				'template' 		=> 'EPARCEL EXPRESS'
			),
			'3J55'	=> array(
				'key'			=> 'express_post',
				'name'			=> 'Express Post + Signature',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 96,
				'labelType' 	=> 'Express Post',
				'template' 		=> 'EPARCEL EXPRESS'
			),
			'3J83'	=> array(
				'key'			=> 'express_post',
				'name'			=> 'Express Post',
				'serviceCode' 	=> 9,
				'prodCode' 		=> 87,
				'labelType' 	=> 'Express Post',
				'template' 		=> 'EPARCEL EXPRESS'
			),
			'3J85'	=> array(
				'key'			=> 'express_post',
				'name'			=> 'Express Post + Signature',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 96,
				'labelType' 	=> 'Express Post',
				'template' 		=> 'EPARCEL EXPRESS'
			),
			'3K03'	=> array(
				'key'			=> 'express_post',
				'name'			=> 'Express Post',
				'serviceCode' 	=> 9,
				'prodCode' 		=> 87,
				'labelType' 	=> 'Express Post',
				'template' 		=> 'EPARCEL EXPRESS'
			),
			'3K05'	=> array(
				'key'			=> 'express_post',
				'name'			=> 'Express Post + Signature',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 96,
				'labelType' 	=> 'Express Post',
				'template' 		=> 'EPARCEL EXPRESS'
			),
			'3K33'	=> array(
				'key'			=> 'express_post',
				'name'			=> 'Express Post',
				'serviceCode' 	=> 9,
				'prodCode' 		=> 87,
				'labelType' 	=> 'Express Post',
				'template' 		=> 'EPARCEL EXPRESS'
			),
			'3K35'	=> array(
				'key'			=> 'express_post',
				'name'			=> 'Express Post + Signature',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 96,
				'labelType' 	=> 'Express Post',
				'template' 		=> 'EPARCEL EXPRESS'
			),
			'3K53'	=> array(
				'key'			=> 'express_post',
				'name'			=> 'Express Post',
				'serviceCode' 	=> 9,
				'prodCode' 		=> 87,
				'labelType' 	=> 'Express Post',
				'template' 		=> 'EPARCEL EXPRESS'
			),
			'3K55'	=> array(
				'key'			=> 'express_post',
				'name'			=> 'Express Post + Signature',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 96,
				'labelType' 	=> 'Express Post',
				'template' 		=> 'EPARCEL EXPRESS'
			),
			'3K83'	=> array(
				'key'			=> 'express_post',
				'name'			=> 'Express Post',
				'serviceCode' 	=> 9,
				'prodCode' 		=> 87,
				'labelType' 	=> 'Express Post',
				'template' 		=> 'EPARCEL EXPRESS'
			),
			'3K85'	=> array(
				'key'			=> 'express_post',
				'name'			=> 'Express Post + Signature',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 96,
				'labelType' 	=> 'Express Post',
				'template' 		=> 'EPARCEL EXPRESS'
			),
			'4A33'	=> array(
				'key'			=> 'parcel_post',
				'name'			=> '1kg Parcel Post Satchel',
				'serviceCode' 	=> 9,
				'prodCode' 		=> 91,
				'labelType' 	=> 'Parcel Post',
				'template' 		=> 'EPARCEL'
			),
			'4A35'	=> array(
				'key'			=> 'parcel_post',
				'name'			=> '1kg Parcel Post Satchel + Sig',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 93,
				'labelType' 	=> 'Parcel Post',
				'template' 		=> 'EPARCEL'
			),
			'4B33'	=> array(
				'key'			=> 'parcel_post',
				'name'			=> '1kg Parcel Post Satchel',
				'serviceCode' 	=> 9,
				'prodCode' 		=> 91,
				'labelType' 	=> 'Parcel Post',
				'template' 		=> 'EPARCEL'
			),
			'4B35'	=> array(
				'key'			=> 'parcel_post',
				'name'			=> '1kg Parcel Post Satchel + Sig',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 93,
				'labelType' 	=> 'Parcel Post',
				'template' 		=> 'EPARCEL'
			),
			'4C33'	=> array(
				'key'			=> 'parcel_post',
				'name'			=> '1kg Parcel Post Satchel',
				'serviceCode' 	=> 9,
				'prodCode' 		=> 91,
				'labelType' 	=> 'Parcel Post',
				'template' 		=> 'EPARCEL'
			),
			'4C35'	=> array(
				'key'			=> 'parcel_post',
				'name'			=> '1kg Parcel Post Satchel + Sig',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 93,
				'labelType' 	=> 'Parcel Post',
				'template' 		=> 'EPARCEL'
			),
			'4D33'	=> array(
				'key'			=> 'parcel_post',
				'name'			=> '1kg Parcel Post Satchel',
				'serviceCode' 	=> 9,
				'prodCode' 		=> 91,
				'labelType' 	=> 'Parcel Post',
				'template' 		=> 'EPARCEL'
			),
			'4D35'	=> array(
				'key'			=> 'parcel_post',
				'name'			=> '1kg Parcel Post Satchel + Sig',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 93,
				'labelType' 	=> 'Parcel Post',
				'template' 		=> 'EPARCEL'
			),
			'4I33'	=> array(
				'key'			=> 'express_post',
				'name'			=> '1kg Express Post Satchel',
				'serviceCode' 	=> 9,
				'prodCode' 		=> 87,
				'labelType' 	=> 'Express Post',
				'template' 		=> 'EPARCEL EXPRESS'
			),
			'4I35'	=> array(
				'key'			=> 'express_post',
				'name'			=> '1kg Express Post Satchel + Sig',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 96,
				'labelType' 	=> 'Express Post',
				'template' 		=> 'EPARCEL EXPRESS'
			),
			'4J33'	=> array(
				'key'			=> 'express_post',
				'name'			=> '1kg Express Post Satchel',
				'serviceCode' 	=> 9,
				'prodCode' 		=> 87,
				'labelType' 	=> 'Express Post',
				'template' 		=> 'EPARCEL EXPRESS'
			),
			'4J35'	=> array(
				'key'			=> 'express_post',
				'name'			=> '1kg Express Post Satchel + Sig',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 96,
				'labelType' 	=> 'Express Post',
				'template' 		=> 'EPARCEL EXPRESS'
			),
			'7B03'	=> array(
				'key'			=> 'parcel_post',
				'name'			=> 'Parcel Post',
				'serviceCode' 	=> 9,
				'prodCode' 		=> 91,
				'labelType' 	=> 'Parcel Post',
				'template' 		=> 'EPARCEL'
			),
			'7B05'	=> array(
				'key'			=> 'parcel_post',
				'name'			=> 'Parcel Post + Signature',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 93,
				'labelType' 	=> 'Parcel Post',
				'template' 		=> 'EPARCEL'
			),
			'7B33'	=> array(
				'key'			=> 'parcel_post',
				'name'			=> 'Parcel Post',
				'serviceCode' 	=> 9,
				'prodCode' 		=> 91,
				'labelType' 	=> 'Parcel Post',
				'template' 		=> 'EPARCEL'
			),
			'7B35'	=> array(
				'key'			=> 'parcel_post',
				'name'			=> 'Parcel Post + Signature',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 93,
				'labelType' 	=> 'Parcel Post',
				'template' 		=> 'EPARCEL'
			),
			'7B53'	=> array(
				'key'			=> 'parcel_post',
				'name'			=> 'Parcel Post',
				'serviceCode' 	=> 9,
				'prodCode' 		=> 91,
				'labelType' 	=> 'Parcel Post',
				'template' 		=> 'EPARCEL'
			),
			'7B55'	=> array(
				'key'			=> 'parcel_post',
				'name'			=> 'Parcel Post + Signature',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 93,
				'labelType' 	=> 'Parcel Post',
				'template' 		=> 'EPARCEL'
			),
			'7B83'	=> array(
				'key'			=> 'parcel_post',
				'name'			=> 'Parcel Post',
				'serviceCode' 	=> 9,
				'prodCode' 		=> 91,
				'labelType' 	=> 'Parcel Post',
				'template' 		=> 'EPARCEL'
			),
			'7B85'	=> array(
				'key'			=> 'parcel_post',
				'name'			=> 'Parcel Post + Signature',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 93,
				'labelType' 	=> 'Parcel Post',
				'template' 		=> 'EPARCEL'
			),
			'7C03'	=> array(
				'key'			=> 'parcel_post',
				'name'			=> 'Parcel Post',
				'serviceCode' 	=> 9,
				'prodCode' 		=> 91,
				'labelType' 	=> 'Parcel Post',
				'template' 		=> 'EPARCEL'
			),
			'7C05'	=> array(
				'key'			=> 'parcel_post',
				'name'			=> 'Parcel Post + Signature',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 93,
				'labelType' 	=> 'Parcel Post',
				'template' 		=> 'EPARCEL'
			),
			'7C33'	=> array(
				'key'			=> 'parcel_post',
				'name'			=> 'Parcel Post',
				'serviceCode' 	=> 9,
				'prodCode' 		=> 91,
				'labelType' 	=> 'Parcel Post',
				'template' 		=> 'EPARCEL'
			),
			'7C35'	=> array(
				'key'			=> 'parcel_post',
				'name'			=> 'Parcel Post + Signature',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 93,
				'labelType' 	=> 'Parcel Post',
				'template' 		=> 'EPARCEL'
			),
			'7C53'	=> array(
				'key'			=> 'parcel_post',
				'name'			=> 'Parcel Post',
				'serviceCode' 	=> 9,
				'prodCode' 		=> 91,
				'labelType' 	=> 'Parcel Post',
				'template' 		=> 'EPARCEL'
			),
			'7C55'	=> array(
				'key'			=> 'parcel_post',
				'name'			=> 'Parcel Post + Signature',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 93,
				'labelType' 	=> 'Parcel Post',
				'template' 		=> 'EPARCEL'
			),
			'7C83'	=> array(
				'key'			=> 'parcel_post',
				'name'			=> 'Parcel Post',
				'serviceCode' 	=> 9,
				'prodCode' 		=> 91,
				'labelType' 	=> 'Parcel Post',
				'template' 		=> 'EPARCEL'
			),
			'7C85'	=> array(
				'key'			=> 'parcel_post',
				'name'			=> 'Parcel Post + Signature',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 93,
				'labelType' 	=> 'Parcel Post',
				'template' 		=> 'EPARCEL'
			),
			'7D03'	=> array(
				'key'			=> 'parcel_post',
				'name'			=> 'Parcel Post',
				'serviceCode' 	=> 9,
				'prodCode' 		=> 91,
				'labelType' 	=> 'Parcel Post',
				'template' 		=> 'EPARCEL'
			),
			'7D05'	=> array(
				'key'			=> 'parcel_post',
				'name'			=> 'Parcel Post + Signature',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 93,
				'labelType' 	=> 'Parcel Post',
				'template' 		=> 'EPARCEL'
			),
			'7D33'	=> array(
				'key'			=> 'parcel_post',
				'name'			=> 'Parcel Post',
				'serviceCode' 	=> 9,
				'prodCode' 		=> 91,
				'labelType' 	=> 'Parcel Post',
				'template' 		=> 'EPARCEL'
			),
			'7D35'	=> array(
				'key'			=> 'parcel_post',
				'name'			=> 'Parcel Post + Signature',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 93,
				'labelType' 	=> 'Parcel Post',
				'template' 		=> 'EPARCEL'
			),
			'7D53'	=> array(
				'key'			=> 'parcel_post',
				'name'			=> 'Parcel Post',
				'serviceCode' 	=> 9,
				'prodCode' 		=> 91,
				'labelType' 	=> 'Parcel Post',
				'template' 		=> 'EPARCEL'
			),
			'7D55'	=> array(
				'key'			=> 'parcel_post',
				'name'			=> 'Parcel Post + Signature',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 93,
				'labelType' 	=> 'Parcel Post',
				'template' 		=> 'EPARCEL'
			),
			'7D83'	=> array(
				'key'			=> 'parcel_post',
				'name'			=> 'Parcel Post',
				'serviceCode' 	=> 9,
				'prodCode' 		=> 91,
				'labelType' 	=> 'Parcel Post',
				'template' 		=> 'EPARCEL'
			),
			'7D85'	=> array(
				'key'			=> 'parcel_post',
				'name'			=> 'Parcel Post + Signature',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 93,
				'labelType' 	=> 'Parcel Post',
				'template' 		=> 'EPARCEL'
			),
			'7H03'	=> array(
				'key'			=> 'express_post',
				'name'			=> 'Express Post',
				'serviceCode' 	=> 9,
				'prodCode' 		=> 87,
				'labelType' 	=> 'Express Post',
				'template' 		=> 'EPARCEL EXPRESS'
			),
			'7H05'	=> array(
				'key'			=> 'express_post',
				'name'			=> 'Express Post + Signature',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 96,
				'labelType' 	=> 'Express Post',
				'template' 		=> 'EPARCEL EXPRESS'
			),
			'7H33'	=> array(
				'key'			=> 'express_post',
				'name'			=> 'Express Post',
				'serviceCode' 	=> 9,
				'prodCode' 		=> 87,
				'labelType' 	=> 'Express Post',
				'template' 		=> 'EPARCEL EXPRESS'
			),
			'7H35'	=> array(
				'key'			=> 'express_post',
				'name'			=> 'Express Post + Signature',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 96,
				'labelType' 	=> 'Express Post',
				'template' 		=> 'EPARCEL EXPRESS'
			),
			'7H53'	=> array(
				'key'			=> 'express_post',
				'name'			=> 'Express Post',
				'serviceCode' 	=> 9,
				'prodCode' 		=> 87,
				'labelType' 	=> 'Express Post',
				'template' 		=> 'EPARCEL EXPRESS'
			),
			'7H55'	=> array(
				'key'			=> 'express_post',
				'name'			=> 'Express Post + Signature',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 96,
				'labelType' 	=> 'Express Post',
				'template' 		=> 'EPARCEL EXPRESS'
			),
			'7H83'	=> array(
				'key'			=> 'express_post',
				'name'			=> 'Express Post',
				'serviceCode' 	=> 9,
				'prodCode' 		=> 87,
				'labelType' 	=> 'Express Post',
				'template' 		=> 'EPARCEL EXPRESS'
			),
			'7H85'	=> array(
				'key'			=> 'express_post',
				'name'			=> 'Express Post + Signature',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 96,
				'labelType' 	=> 'Express Post',
				'template' 		=> 'EPARCEL EXPRESS'
			),
			'7I03'	=> array(
				'key'			=> 'express_post',
				'name'			=> 'Express Post',
				'serviceCode' 	=> 9,
				'prodCode' 		=> 87,
				'labelType' 	=> 'Express Post',
				'template' 		=> 'EPARCEL EXPRESS'
			),
			'7I05'	=> array(
				'key'			=> 'express_post',
				'name'			=> 'Express Post + Signature',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 96,
				'labelType' 	=> 'Express Post',
				'template' 		=> 'EPARCEL EXPRESS'
			),
			'7I33'	=> array(
				'key'			=> 'express_post',
				'name'			=> 'Express Post',
				'serviceCode' 	=> 9,
				'prodCode' 		=> 87,
				'labelType' 	=> 'Express Post',
				'template' 		=> 'EPARCEL EXPRESS'
			),
			'7I35'	=> array(
				'key'			=> 'express_post',
				'name'			=> 'Express Post + Signature',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 96,
				'labelType' 	=> 'Express Post',
				'template' 		=> 'EPARCEL EXPRESS'
			),
			'7I53'	=> array(
				'key'			=> 'express_post',
				'name'			=> 'Express Post',
				'serviceCode' 	=> 9,
				'prodCode' 		=> 87,
				'labelType' 	=> 'Express Post',
				'template' 		=> 'EPARCEL EXPRESS'
			),
			'7I55'	=> array(
				'key'			=> 'express_post',
				'name'			=> 'Express Post + Signature',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 96,
				'labelType' 	=> 'Express Post',
				'template' 		=> 'EPARCEL EXPRESS'
			),
			'7I83'	=> array(
				'key'			=> 'express_post',
				'name'			=> 'Express Post',
				'serviceCode' 	=> 9,
				'prodCode' 		=> 87,
				'labelType' 	=> 'Express Post',
				'template' 		=> 'EPARCEL EXPRESS'
			),
			'7I85'	=> array(
				'key'			=> 'express_post',
				'name'			=> 'Express Post + Signature',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 96,
				'labelType' 	=> 'Express Post',
				'template' 		=> 'EPARCEL EXPRESS'
			),
			'7J03'	=> array(
				'key'			=> 'express_post',
				'name'			=> 'Express Post',
				'serviceCode' 	=> 9,
				'prodCode' 		=> 87,
				'labelType' 	=> 'Express Post',
				'template' 		=> 'EPARCEL EXPRESS'
			),
			'7J05'	=> array(
				'key'			=> 'express_post',
				'name'			=> 'Express Post + Signature',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 96,
				'labelType' 	=> 'Express Post',
				'template' 		=> 'EPARCEL EXPRESS'
			),
			'7J33'	=> array(
				'key'			=> 'express_post',
				'name'			=> 'Express Post',
				'serviceCode' 	=> 9,
				'prodCode' 		=> 87,
				'labelType' 	=> 'Express Post',
				'template' 		=> 'EPARCEL EXPRESS'
			),
			'7J35'	=> array(
				'key'			=> 'express_post',
				'name'			=> 'Express Post + Signature',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 96,
				'labelType' 	=> 'Express Post',
				'template' 		=> 'EPARCEL EXPRESS'
			),
			'7J53'	=> array(
				'key'			=> 'express_post',
				'name'			=> 'Express Post',
				'serviceCode' 	=> 9,
				'prodCode' 		=> 87,
				'labelType' 	=> 'Express Post',
				'template' 		=> 'EPARCEL EXPRESS'
			),
			'7J55'	=> array(
				'key'			=> 'express_post',
				'name'			=> 'Express Post + Signature',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 96,
				'labelType' 	=> 'Express Post',
				'template' 		=> 'EPARCEL EXPRESS'
			),
			'7J83'	=> array(
				'key'			=> 'express_post',
				'name'			=> 'Express Post',
				'serviceCode' 	=> 9,
				'prodCode' 		=> 87,
				'labelType' 	=> 'Express Post',
				'template' 		=> 'EPARCEL EXPRESS'
			),
			'7J85'	=> array(
				'key'			=> 'express_post',
				'name'			=> 'Express Post + Signature',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 96,
				'labelType' 	=> 'Express Post',
				'template' 		=> 'EPARCEL EXPRESS'
			),
			'7K03'	=> array(
				'key'			=> 'express_post',
				'name'			=> 'Express Post',
				'serviceCode' 	=> 9,
				'prodCode' 		=> 87,
				'labelType' 	=> 'Express Post',
				'template' 		=> 'EPARCEL EXPRESS'
			),
			'7K05'	=> array(
				'key'			=> 'express_post',
				'name'			=> 'Express Post + Signature',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 96,
				'labelType' 	=> 'Express Post',
				'template' 		=> 'EPARCEL EXPRESS'
			),
			'7K33'	=> array(
				'key'			=> 'express_post',
				'name'			=> 'Express Post',
				'serviceCode' 	=> 9,
				'prodCode' 		=> 87,
				'labelType' 	=> 'Express Post',
				'template' 		=> 'EPARCEL EXPRESS'
			),
			'7K35'	=> array(
				'key'			=> 'express_post',
				'name'			=> 'Express Post + Signature',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 96,
				'labelType' 	=> 'Express Post',
				'template' 		=> 'EPARCEL EXPRESS'
			),
			'7K53'	=> array(
				'key'			=> 'express_post',
				'name'			=> 'Express Post',
				'serviceCode' 	=> 9,
				'prodCode' 		=> 87,
				'labelType' 	=> 'Express Post',
				'template' 		=> 'EPARCEL EXPRESS'
			),
			'7K55'	=> array(
				'key'			=> 'express_post',
				'name'			=> 'Express Post + Signature',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 96,
				'labelType' 	=> 'Express Post',
				'template' 		=> 'EPARCEL EXPRESS'
			),
			'7K83'	=> array(
				'key'			=> 'express_post',
				'name'			=> 'Express Post',
				'serviceCode' 	=> 9,
				'prodCode' 		=> 87,
				'labelType' 	=> 'Express Post',
				'template' 		=> 'EPARCEL EXPRESS'
			),
			'7K85'	=> array(
				'key'			=> 'express_post',
				'name'			=> 'Express Post + Signature',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 96,
				'labelType' 	=> 'Express Post',
				'template' 		=> 'EPARCEL EXPRESS'
			),
			'7N33'	=> array(
				'key'			=> 'parcel_post',
				'name'			=> 'Parcel Post Wine',
				'serviceCode' 	=> 9,
				'prodCode' 		=> 91,
				'labelType' 	=> 'Parcel Post',
				'template' 		=> 'EPARCEL'
			),
			'7N35'	=> array(
				'key'			=> 'parcel_post',
				'name'			=> 'Parcel Post Wine + Signature',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 93,
				'labelType' 	=> 'Parcel Post',
				'template' 		=> 'EPARCEL'
			),
			'7N83'	=> array(
				'key'			=> 'parcel_post',
				'name'			=> 'Parcel Post Wine',
				'serviceCode' 	=> 9,
				'prodCode' 		=> 91,
				'labelType' 	=> 'Parcel Post',
				'template' 		=> 'EPARCEL'
			),
			'7N85'	=> array(
				'key'			=> 'parcel_post',
				'name'			=> 'Parcel Post Wine + Signature',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 93,
				'labelType' 	=> 'Parcel Post',
				'template' 		=> 'EPARCEL'
			),
			'7O33'	=> array(
				'key'			=> 'parcel_post',
				'name'			=> 'Parcel Post Wine',
				'serviceCode' 	=> 9,
				'prodCode' 		=> 91,
				'labelType' 	=> 'Parcel Post',
				'template' 		=> 'EPARCEL'
			),
			'7O35'	=> array(
				'key'			=> 'parcel_post',
				'name'			=> 'Parcel Post Wine + Signature',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 93,
				'labelType' 	=> 'Parcel Post',
				'template' 		=> 'EPARCEL'
			),
			'7O83'	=> array(
				'key'			=> 'parcel_post',
				'name'			=> 'Parcel Post Wine',
				'serviceCode' 	=> 9,
				'prodCode' 		=> 91,
				'labelType' 	=> 'Parcel Post',
				'template' 		=> 'EPARCEL'
			),
			'7O85'	=> array(
				'key'			=> 'parcel_post',
				'name'			=> 'Parcel Post Wine + Signature',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 93,
				'labelType' 	=> 'Parcel Post',
				'template' 		=> 'EPARCEL'
			),
			'7P33'	=> array(
				'key'			=> 'parcel_post',
				'name'			=> 'Parcel Post Wine',
				'serviceCode' 	=> 9,
				'prodCode' 		=> 91,
				'labelType' 	=> 'Parcel Post',
				'template' 		=> 'EPARCEL'
			),
			'7P35'	=> array(
				'key'			=> 'parcel_post',
				'name'			=> 'Parcel Post Wine + Signature',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 93,
				'labelType' 	=> 'Parcel Post',
				'template' 		=> 'EPARCEL'
			),
			'7P83'	=> array(
				'key'			=> 'parcel_post',
				'name'			=> 'Parcel Post Wine',
				'serviceCode' 	=> 9,
				'prodCode' 		=> 91,
				'labelType' 	=> 'Parcel Post',
				'template' 		=> 'EPARCEL'
			),
			'7P85'	=> array(
				'key'			=> 'parcel_post',
				'name'			=> 'Parcel Post Wine + Signature',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 93,
				'labelType' 	=> 'Parcel Post',
				'template' 		=> 'EPARCEL'
			),
			'7T33'	=> array(
				'key'			=> 'express_post',
				'name'			=> 'Express Post Wine',
				'serviceCode' 	=> 9,
				'prodCode' 		=> 87,
				'labelType' 	=> 'Express Post',
				'template' 		=> 'EPARCEL EXPRESS'
			),
			'7T35'	=> array(
				'key'			=> 'express_post',
				'name'			=> 'Express Post Wine + Signature',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 96,
				'labelType' 	=> 'Express Post',
				'template' 		=> 'EPARCEL EXPRESS'
			),
			'7T83'	=> array(
				'key'			=> 'express_post',
				'name'			=> 'Express Post Wine',
				'serviceCode' 	=> 9,
				'prodCode' 		=> 87,
				'labelType' 	=> 'Express Post',
				'template' 		=> 'EPARCEL EXPRESS'
			),
			'7T85'	=> array(
				'key'			=> 'express_post',
				'name'			=> 'Express Post Wine + Signature',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 96,
				'labelType' 	=> 'Express Post',
				'template' 		=> 'EPARCEL EXPRESS'
			),
			'7U33'	=> array(
				'key'			=> 'express_post',
				'name'			=> 'Express Post Wine',
				'serviceCode' 	=> 9,
				'prodCode' 		=> 87,
				'labelType' 	=> 'Express Post',
				'template' 		=> 'EPARCEL EXPRESS'
			),
			'7U35'	=> array(
				'key'			=> 'express_post',
				'name'			=> 'Express Post Wine + Signature',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 96,
				'labelType' 	=> 'Express Post',
				'template' 		=> 'EPARCEL EXPRESS'
			),
			'7U83'	=> array(
				'key'			=> 'express_post',
				'name'			=> 'Express Post Wine',
				'serviceCode' 	=> 9,
				'prodCode' 		=> 87,
				'labelType' 	=> 'Express Post',
				'template' 		=> 'EPARCEL EXPRESS'
			),
			'7U85'	=> array(
				'key'			=> 'express_post',
				'name'			=> 'Express Post Wine + Signature',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 96,
				'labelType' 	=> 'Express Post',
				'template' 		=> 'EPARCEL EXPRESS'
			),
			'7V33'	=> array(
				'key'			=> 'express_post',
				'name'			=> 'Express Post Wine',
				'serviceCode' 	=> 9,
				'prodCode' 		=> 87,
				'labelType' 	=> 'Express Post',
				'template' 		=> 'EPARCEL EXPRESS'
			),
			'7V35'	=> array(
				'key'			=> 'express_post',
				'name'			=> 'Express Post Wine + Signature',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 96,
				'labelType' 	=> 'Express Post',
				'template' 		=> 'EPARCEL EXPRESS'
			),
			'7V83'	=> array(
				'key'			=> 'express_post',
				'name'			=> 'Express Post Wine',
				'serviceCode' 	=> 9,
				'prodCode' 		=> 87,
				'labelType' 	=> 'Express Post',
				'template' 		=> 'EPARCEL EXPRESS'
			),
			'7V85'	=> array(
				'key'			=> 'express_post',
				'name'			=> 'Express Post Wine + Signature',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 96,
				'labelType' 	=> 'Express Post',
				'template' 		=> 'EPARCEL EXPRESS'
			),
			'8A33'	=> array(
				'key'			=> 'parcel_post',
				'name'			=> '3kg Parcel Post Satchel',
				'serviceCode' 	=> 9,
				'prodCode' 		=> 91,
				'labelType' 	=> 'Parcel Post',
				'template' 		=> 'EPARCEL'
			),
			'8A35'	=> array(
				'key'			=> 'parcel_post',
				'name'			=> '3kg Parcel Post Satchel + Sig',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 93,
				'labelType' 	=> 'Parcel Post',
				'template' 		=> 'EPARCEL'
			),
			'8B33'	=> array(
				'key'			=> 'parcel_post',
				'name'			=> '3kg Parcel Post Satchel',
				'serviceCode' 	=> 9,
				'prodCode' 		=> 91,
				'labelType' 	=> 'Parcel Post',
				'template' 		=> 'EPARCEL'
			),
			'8B35'	=> array(
				'key'			=> 'parcel_post',
				'name'			=> '3kg Parcel Post Satchel + Sig',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 93,
				'labelType' 	=> 'Parcel Post',
				'template' 		=> 'EPARCEL'
			),
			'8C33'	=> array(
				'key'			=> 'parcel_post',
				'name'			=> '3kg Parcel Post Satchel',
				'serviceCode' 	=> 9,
				'prodCode' 		=> 91,
				'labelType' 	=> 'Parcel Post',
				'template' 		=> 'EPARCEL'
			),
			'8C35'	=> array(
				'key'			=> 'parcel_post',
				'name'			=> '3kg Parcel Post Satchel + Sig',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 93,
				'labelType' 	=> 'Parcel Post',
				'template' 		=> 'EPARCEL'
			),
			'8D33'	=> array(
				'key'			=> 'parcel_post',
				'name'			=> '3kg Parcel Post Satchel',
				'serviceCode' 	=> 9,
				'prodCode' 		=> 91,
				'labelType' 	=> 'Parcel Post',
				'template' 		=> 'EPARCEL'
			),
			'8D35'	=> array(
				'key'			=> 'parcel_post',
				'name'			=> '3kg Parcel Post Satchel + Sig',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 93,
				'labelType' 	=> 'Parcel Post',
				'template' 		=> 'EPARCEL'
			),
			'8G33'	=> array(
				'key'			=> 'express_post',
				'name'			=> '3kg Express Post Satchel',
				'serviceCode' 	=> 9,
				'prodCode' 		=> 87,
				'labelType' 	=> 'Express Post',
				'template' 		=> 'EPARCEL EXPRESS'
			),
			'8G35'	=> array(
				'key'			=> 'express_post',
				'name'			=> '3kg Express Post Satchel + Sig',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 96,
				'labelType' 	=> 'Express Post',
				'template' 		=> 'EPARCEL EXPRESS'
			),
			'8H33'	=> array(
				'key'			=> 'express_post',
				'name'			=> '3kg Express Post Satchel',
				'serviceCode' 	=> 9,
				'prodCode' 		=> 87,
				'labelType' 	=> 'Express Post',
				'template' 		=> 'EPARCEL EXPRESS'
			),
			'8H35'	=> array(
				'key'			=> 'express_post',
				'name'			=> '3kg Express Post Satchel + Sig',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 96,
				'labelType' 	=> 'Express Post',
				'template' 		=> 'EPARCEL EXPRESS'
			),
			'8I33'	=> array(
				'key'			=> 'express_post',
				'name'			=> '3kg Express Post Satchel',
				'serviceCode' 	=> 9,
				'prodCode' 		=> 87,
				'labelType' 	=> 'Express Post',
				'template' 		=> 'EPARCEL EXPRESS'
			),
			'8I35'	=> array(
				'key'			=> 'express_post',
				'name'			=> '3kg Express Post Satchel + Sig',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 96,
				'labelType' 	=> 'Express Post',
				'template' 		=> 'EPARCEL EXPRESS'
			),
			'8J33'	=> array(
				'key'			=> 'express_post',
				'name'			=> '3kg Express Post Satchel',
				'serviceCode' 	=> 9,
				'prodCode' 		=> 87,
				'labelType' 	=> 'Express Post',
				'template' 		=> 'EPARCEL EXPRESS'
			),
			'8J35'	=> array(
				'key'			=> 'express_post',
				'name'			=> '3kg Express Post Satchel + Sig',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 96,
				'labelType' 	=> 'Express Post',
				'template' 		=> 'EPARCEL EXPRESS'
			),
			'9A33'	=> array(
				'key'			=> 'parcel_post',
				'name'			=> '5kg Parcel Post Satchel',
				'serviceCode' 	=> 9,
				'prodCode' 		=> 91,
				'labelType' 	=> 'Parcel Post',
				'template' 		=> 'EPARCEL'
			),
			'9A35'	=> array(
				'key'			=> 'parcel_post',
				'name'			=> '5kg Parcel Post Satchel + Sig',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 93,
				'labelType' 	=> 'Parcel Post',
				'template' 		=> 'EPARCEL'
			),
			'9B33'	=> array(
				'key'			=> 'parcel_post',
				'name'			=> '5kg Parcel Post Satchel',
				'serviceCode' 	=> 9,
				'prodCode' 		=> 91,
				'labelType' 	=> 'Parcel Post',
				'template' 		=> 'EPARCEL'
			),
			'9B35'	=> array(
				'key'			=> 'parcel_post',
				'name'			=> '5kg Parcel Post Satchel + Sig',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 93,
				'labelType' 	=> 'Parcel Post',
				'template' 		=> 'EPARCEL'
			),
			'9C33'	=> array(
				'key'			=> 'parcel_post',
				'name'			=> '5kg Parcel Post Satchel',
				'serviceCode' 	=> 9,
				'prodCode' 		=> 91,
				'labelType' 	=> 'Parcel Post',
				'template' 		=> 'EPARCEL'
			),
			'9C35'	=> array(
				'key'			=> 'parcel_post',
				'name'			=> '5kg Parcel Post Satchel + Sig',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 93,
				'labelType' 	=> 'Parcel Post',
				'template' 		=> 'EPARCEL'
			),
			'9D33'	=> array(
				'key'			=> 'parcel_post',
				'name'			=> '5kg Parcel Post Satchel',
				'serviceCode' 	=> 9,
				'prodCode' 		=> 91,
				'labelType' 	=> 'Parcel Post',
				'template' 		=> 'EPARCEL'
			),
			'9D35'	=> array(
				'key'			=> 'parcel_post',
				'name'			=> '5kg Parcel Post Satchel + Sig',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 93,
				'labelType' 	=> 'Parcel Post',
				'template' 		=> 'EPARCEL'
			),
			'9G33' 	=> array(
				'key'			=> 'express_post',
				'name'			=> '5kg Express Post Satchel',
				'serviceCode' 	=> 9,
				'prodCode' 		=> 87,
				'labelType' 	=> 'Express Post',
				'template' 		=> 'EPARCEL EXPRESS'
			),
			'9G35'	=> array(
				'key'			=> 'express_post',
				'name'			=> '5kg Express Post Satchel + Sig',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 96,
				'labelType' 	=> 'Express Post',
				'template' 		=> 'EPARCEL EXPRESS'
			),
			'9H33'	=> array(
				'key'			=> 'express_post',
				'name'			=> '5kg Express Post Satchel',
				'serviceCode' 	=> 9,
				'prodCode' 		=> 87,
				'labelType' 	=> 'Express Post',
				'template' 		=> 'EPARCEL EXPRESS'
			),
			'9H35'	=> array(
				'key'			=> 'express_post',
				'name'			=> '5kg Express Post Satchel + Sig',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 96,
				'labelType' 	=> 'Express Post',
				'template' 		=> 'EPARCEL EXPRESS'
			),
			'9I33'	=> array(
				'key'			=> 'express_post',
				'name'			=> '5kg Express Post Satchel',
				'serviceCode' 	=> 9,
				'prodCode' 		=> 87,
				'labelType' 	=> 'Express Post',
				'template' 		=> 'EPARCEL EXPRESS'
			),
			'9I35'	=> array(
				'key'			=> 'express_post',
				'name'			=> '5kg Express Post Satchel + Sig',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 96,
				'labelType' 	=> 'Express Post',
				'template' 		=> 'EPARCEL EXPRESS'
			),
			'9J33'	=> array(
				'key'			=> 'express_post',
				'name'			=> '5kg Express Post Satchel',
				'serviceCode' 	=> 9,
				'prodCode' 		=> 87,
				'labelType' 	=> 'Express Post',
				'template' 		=> 'EPARCEL EXPRESS'
			),
			'9J35'	=> array(
				'key'			=> 'express_post',
				'name'			=> '5kg Express Post Satchel + Sig',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 96,
				'labelType' 	=> 'Express Post',
				'template' 		=> 'EPARCEL EXPRESS'
			),
			'AIR1' 	=> array(
				'key'			=> 'int_economy',
				'name'			=> 'Int. Economy Air',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 0,
				'labelType' 	=> 'Int. Economy Air',
				'template' 		=> 'ECONOMY AIRMAIL'
			),
			'AIR2'	=> array(
				'key'			=> 'int_economy',
				'name'			=> 'Int. Economy Air',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 0,
				'labelType' 	=> 'Int. Economy Air',
				'template' 		=> 'ECONOMY AIRMAIL'
			),
			'AIR3'	=> array(
				'key'			=> 'int_economy',
				'name'			=> 'Int. Economy Air',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 0,
				'labelType' 	=> 'Int. Economy Air',
				'template' 		=> 'ECONOMY AIRMAIL'
			),
			'AIR4'	=> array(
				'key'			=> 'int_economy',
				'name'			=> 'Int. Economy Air',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 0,
				'labelType' 	=> 'Int. Economy Air',
				'template' 		=> 'ECONOMY AIRMAIL'
			),
			'AIR5'	=> array(
				'key'			=> 'int_economy',
				'name'			=> 'Int. Economy Air',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 0,
				'labelType' 	=> 'Int. Economy Air',
				'template' 		=> 'ECONOMY AIRMAIL'
			),
			'AIR6'	=> array(
				'key'			=> 'int_economy',
				'name'			=> 'Int. Economy Air',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 0,
				'labelType' 	=> 'Int. Economy Air',
				'template' 		=> 'ECONOMY AIRMAIL'
			),
			'AIR7'	=> array(
				'key'			=> 'int_economy',
				'name'			=> 'Int. Economy Air',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 0,
				'labelType' 	=> 'Int. Economy Air',
				'template' 		=> 'ECONOMY AIRMAIL'
			),
			'AIR8'	=> array(
				'key'			=> 'int_economy',
				'name'			=> 'Int. Economy Air',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 0,
				'labelType' 	=> 'Int. Economy Air',
				'template' 		=> 'ECONOMY AIRMAIL'
			),
			'AIR9'	=> array(
				'key'			=> 'int_economy',
				'name'			=> 'Int. Economy Air',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 0,
				'labelType' 	=> 'Int. Economy Air',
				'template' 		=> 'ECONOMY AIRMAIL'
			),
			'ECD1'	=> array(
				'key'			=> 'int_express_courier',
				'name'			=> 'Int. Express Courier',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 0,
				'labelType' 	=> 'Int. Express Courier',
				'template' 		=> 'EXPRESS COURIER INTERNATIONAL'
			),
			'ECD2'	=> array(
				'key'			=> 'int_express_courier',
				'name'			=> 'Int. Express Courier',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 0,
				'labelType' 	=> 'Int. Express Courier',
				'template' 		=> 'EXPRESS COURIER INTERNATIONAL'
			),
			'ECD3'	=> array(
				'key'			=> 'int_express_courier',
				'name'			=> 'Int. Express Courier',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 0,
				'labelType' 	=> 'Int. Express Courier',
				'template' 		=> 'EXPRESS COURIER INTERNATIONAL'
			),
			'ECD4'	=> array(
				'key'			=> 'int_express_courier',
				'name'			=> 'Int. Express Courier',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 0,
				'labelType' 	=> 'Int. Express Courier',
				'template' 		=> 'EXPRESS COURIER INTERNATIONAL'
			),
			'ECD5'	=> array(
				'key'			=> 'int_express_courier',
				'name'			=> 'Int. Express Courier',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 0,
				'labelType' 	=> 'Int. Express Courier',
				'template' 		=> 'EXPRESS COURIER INTERNATIONAL'
			),
			'ECD6'	=> array(
				'key'			=> 'int_express_courier',
				'name'			=> 'Int. Express Courier',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 0,
				'labelType' 	=> 'Int. Express Courier',
				'template' 		=> 'EXPRESS COURIER INTERNATIONAL'
			),
			'ECD7'	=> array(
				'key'			=> 'int_express_courier',
				'name'			=> 'Int. Express Courier',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 0,
				'labelType' 	=> 'Int. Express Courier',
				'template' 		=> 'EXPRESS COURIER INTERNATIONAL'
			),
			'ECD8'	=> array(
				'key'			=> 'int_express_courier',
				'name'			=> 'Int. Express Courier',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 0,
				'labelType' 	=> 'Int. Express Courier',
				'template' 		=> 'EXPRESS COURIER INTERNATIONAL'
			),
			'ECD9'	=> array(
				'key'			=> 'int_express_courier',
				'name'			=> 'Int. Express Courier',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 0,
				'labelType' 	=> 'Int. Express Courier',
				'template' 		=> 'EXPRESS COURIER INTERNATIONAL'
			),
			'ECM1'	=> array(
				'key'			=> 'int_express_courier',
				'name'			=> 'Int. Express Courier',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 0,
				'labelType' 	=> 'Int. Express Courier',
				'template' 		=> 'EXPRESS COURIER INTERNATIONAL'
			),
			'ECM2'	=> array(
				'key'			=> 'int_express_courier',
				'name'			=> 'Int. Express Courier',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 0,
				'labelType' 	=> 'Int. Express Courier',
				'template' 		=> 'EXPRESS COURIER INTERNATIONAL'
			),
			'ECM3'	=> array(
				'key'			=> 'int_express_courier',
				'name'			=> 'Int. Express Courier',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 0,
				'labelType' 	=> 'Int. Express Courier',
				'template' 		=> 'EXPRESS COURIER INTERNATIONAL'
			),
			'ECM4'	=> array(
				'key'			=> 'int_express_courier',
				'name'			=> 'Int. Express Courier',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 0,
				'labelType' 	=> 'Int. Express Courier',
				'template' 		=> 'EXPRESS COURIER INTERNATIONAL'
			),
			'ECM5'	=> array(
				'key'			=> 'int_express_courier',
				'name'			=> 'Int. Express Courier',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 0,
				'labelType' 	=> 'Int. Express Courier',
				'template' 		=> 'EXPRESS COURIER INTERNATIONAL'
			),
			'ECM6'	=> array(
				'key'			=> 'int_express_courier',
				'name'			=> 'Int. Express Courier',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 0,
				'labelType' 	=> 'Int. Express Courier',
				'template' 		=> 'EXPRESS COURIER INTERNATIONAL'
			),
			'ECM7'	=> array(
				'key'			=> 'int_express_courier',
				'name'			=> 'Int. Express Courier',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 0,
				'labelType' 	=> 'Int. Express Courier',
				'template' 		=> 'EXPRESS COURIER INTERNATIONAL'
			),
			'ECM8'	=> array(
				'key'			=> 'int_express_courier',
				'name'			=> 'Int. Express Courier',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 0,
				'labelType' 	=> 'Int. Express Courier',
				'template' 		=> 'EXPRESS COURIER INTERNATIONAL'
			),
			'ECM9'	=> array(
				'key'			=> 'int_express_courier',
				'name'			=> 'Int. Express Courier',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 0,
				'labelType' 	=> 'Int. Express Courier',
				'template' 		=> 'EXPRESS COURIER INTERNATIONAL'
			),
			'EPI1'	=> array(
				'key'			=> 'int_express_post',
				'name'			=> 'Int. Express Post',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 0,
				'labelType' 	=> 'Int. Express Post',
				'template' 		=> 'EXPRESS POST INTERNATIONAL'
			),
			'EPI2'	=> array(
				'key'			=> 'int_express_post',
				'name'			=> 'Int. Express Post',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 0,
				'labelType' 	=> 'Int. Express Post',
				'template' 		=> 'EXPRESS POST INTERNATIONAL'
			),
			'EPI3'	=> array(
				'key'			=> 'int_express_post',
				'name'			=> 'Int. Express Post',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 0,
				'labelType' 	=> 'Int. Express Post',
				'template' 		=> 'EXPRESS POST INTERNATIONAL'
			),
			'EPI4'	=> array(
				'key'			=> 'int_express_post',
				'name'			=> 'Int. Express Post',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 0,
				'labelType' 	=> 'Int. Express Post',
				'template' 		=> 'EXPRESS POST INTERNATIONAL'
			),
			'EPI5'	=> array(
				'key'			=> 'int_express_post',
				'name'			=> 'Int. Express Post',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 0,
				'labelType' 	=> 'Int. Express Post',
				'template' 		=> 'EXPRESS POST INTERNATIONAL'
			),
			'EPI6'	=> array(
				'key'			=> 'int_express_post',
				'name'			=> 'Int. Express Post',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 0,
				'labelType' 	=> 'Int. Express Post',
				'template' 		=> 'EXPRESS POST INTERNATIONAL'
			),
			'EPI7'	=> array(
				'key'			=> 'int_express_post',
				'name'			=> 'Int. Express Post',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 0,
				'labelType' 	=> 'Int. Express Post',
				'template' 		=> 'EXPRESS POST INTERNATIONAL'
			),
			'EPI8'	=> array(
				'key'			=> 'int_express_post',
				'name'			=> 'Int. Express Post',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 0,
				'labelType' 	=> 'Int. Express Post',
				'template' 		=> 'EXPRESS POST INTERNATIONAL'
			),
			'EPI9'	=> array(
				'key'			=> 'int_express_post',
				'name'			=> 'Int. Express Post',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 0,
				'labelType' 	=> 'Int. Express Post',
				'template' 		=> 'EXPRESS POST INTERNATIONAL'
			),
			'PTI1'	=> array(
				'key'			=> 'int_pack_track',
				'name'			=> 'Int. Pack & Track',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 0,
				'labelType' 	=> 'Int. Pack & Track',
				'template' 		=> 'PACK AND TRACK INTERNATIONAL'
			),
			'PTI2'	=> array(
				'key'			=> 'int_pack_track',
				'name'			=> 'Int. Pack & Track',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 0,
				'labelType' 	=> 'Int. Pack & Track',
				'template' 		=> 'PACK AND TRACK INTERNATIONAL'
			),
			'PTI3'	=> array(
				'key'			=> 'int_pack_track',
				'name'			=> 'Int. Pack & Track',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 0,
				'labelType' 	=> 'Int. Pack & Track',
				'template' 		=> 'PACK AND TRACK INTERNATIONAL'
			),
			'PTI4'	=> array(
				'key'			=> 'int_pack_track',
				'name'			=> 'Int. Pack & Track',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 0,
				'labelType' 	=> 'Int. Pack & Track',
				'template' 		=> 'PACK AND TRACK INTERNATIONAL'
			),
			'PTI5'	=> array(
				'key'			=> 'int_pack_track',
				'name'			=> 'Int. Pack & Track',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 0,
				'labelType' 	=> 'Int. Pack & Track',
				'template' 		=> 'PACK AND TRACK INTERNATIONAL'
			),
			'PTI6'	=> array(
				'key'			=> 'int_pack_track',
				'name'			=> 'Int. Pack & Track',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 0,
				'labelType' 	=> 'Int. Pack & Track',
				'template' 		=> 'PACK AND TRACK INTERNATIONAL'
			),
			'PTI7'	=> array(
				'key'			=> 'int_pack_track',
				'name'			=> 'Int. Pack & Track',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 0,
				'labelType' 	=> 'Int. Pack & Track',
				'template' 		=> 'PACK AND TRACK INTERNATIONAL'
			),
			'PTI8'	=> array(
				'key'			=> 'int_pack_track',
				'name'			=> 'Int. Pack & Track',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 0,
				'labelType' 	=> 'Int. Pack & Track',
				'template' 		=> 'PACK AND TRACK INTERNATIONAL'
			),
			'PTI9'	=> array(
				'key'			=> 'int_pack_track',
				'name'			=> 'Int. Pack & Track',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 0,
				'labelType' 	=> 'Int. Pack & Track',
				'template' 		=> 'PACK AND TRACK INTERNATIONAL'
			),
			'RPI1'	=> array(
				'key'			=> 'int_registered',
				'name'			=> 'Int. Registered',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 0,
				'labelType' 	=> 'Int. Registered',
				'template' 		=> 'INTERNATIONAL REGISTERED'
			),
			'RPI2'	=> array(
				'key'			=> 'int_registered',
				'name'			=> 'Int. Registered',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 0,
				'labelType' 	=> 'Int. Registered',
				'template' 		=> 'INTERNATIONAL REGISTERED'
			),
			'RPI3'	=> array(
				'key'			=> 'int_registered',
				'name'			=> 'Int. Registered',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 0,
				'labelType' 	=> 'Int. Registered',
				'template' 		=> 'INTERNATIONAL REGISTERED'
			),
			'RPI4'	=> array(
				'key'			=> 'int_registered',
				'name'			=> 'Int. Registered',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 0,
				'labelType' 	=> 'Int. Registered',
				'template' 		=> 'INTERNATIONAL REGISTERED'
			),
			'RPI5'	=> array(
				'key'			=> 'int_registered',
				'name'			=> 'Int. Registered',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 0,
				'labelType' 	=> 'Int. Registered',
				'template' 		=> 'INTERNATIONAL REGISTERED'
			),
			'RPI6'	=> array(
				'key'			=> 'int_registered',
				'name'			=> 'Int. Registered',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 0,
				'labelType' 	=> 'Int. Registered',
				'template' 		=> 'INTERNATIONAL REGISTERED'
			),
			'RPI7'	=> array(
				'key'			=> 'int_registered',
				'name'			=> 'Int. Registered',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 0,
				'labelType' 	=> 'Int. Registered',
				'template' 		=> 'INTERNATIONAL REGISTERED'
			),
			'RPI8'	=> array(
				'key'			=> 'int_registered',
				'name'			=> 'Int. Registered',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 0,
				'labelType' 	=> 'Int. Registered',
				'template' 		=> 'INTERNATIONAL REGISTERED'
			),
			'RPI9'	=> array(
				'key'			=> 'int_registered',
				'name'			=> 'Int. Registered',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 0,
				'labelType' 	=> 'Int. Registered',
				'template' 		=> 'INTERNATIONAL REGISTERED'
			)
		);
		return $chargeCode;
	}
	
	public static function getChargeCodeValues($none=false)
	{
		$chargeCodes = self::getEParcelChargeCodes();
		$options = array();
		if($none)
		{
			$options['None'] = array(
				'name'			=> 'None',
				'serviceCode' 	=> 0,
				'prodCode' 		=> 0,
				'labelType' 	=> '',
				'template' 		=> ''
			);
		}
		
		foreach($chargeCodes as $chargeCode => $codeLabels)
		{
			$options[$chargeCode] = $codeLabels;
		}
		return $options;
	}
	
	public static function getExpressPostCodes()
	{
		$codes1 = array('X1','X2','X5','X6','XB1','XB2','XB3','XB4','XB5','XDE5','XW5','XW6','XS','7J55');
		$codes2 = array('2G33','2G35','2H33','2H35','2I33','2I35','2J33','2J35','3H03','3H05','3I03','3I05','3I33','3I35','3I53','3I55','3I83','3I85','3J03','3J05','3J33','3J35','3J53','3J55','3J83','3J85','3K03','3K05','3K33','3K35','3K53','3K55','3K83','3K85','4I33','4I35','4J33','4J35','7H03','7H05','7H33','7H35','7H53','7H55','7H83','7H85','7I03','7I05','7I33','7I35','7I53','7I55','7I83','7I85','7J03','7J05','7J33','7J35','7J53','7J55','7J83','7J85','7K03','7K05','7K33','7K35','7K53','7K55','7K83','7K85','7T33','7T35','7T83','7T85','7U33','7U35','7U83','7U85','7V33','7V35','7V83','7V85','8G33','8G35','8H33','8H35','8I33','8I35','8J33','8J35','9G33','9G35','9H33','9H35','9I33','9I35','9J33','9J35');
		return array_merge($codes1,$codes2);
	}
	
	public static function getLinksynceparcelStandardCodes()
	{
		$codes1 = array('B1','B2','B3','B4','B5','B96','B97','B98','D1','DE1','DE2','DE4','DE5','DE6','MED1','MED2','S1','S10','S2','S3','S4','S5','S6','S7','S8','S9','SV1','SV2','W5','W6','7D55');
		$codes2 = array('3E03','7E03','3E05','7E05','3E33','7E33','3E35','7E35','3E53','7E53','3E55','7E55','3E83','7E83','3E85','7E85','2A33','2A35','2B33','2B35','2C33','2C35','2D33','2D35','3B03','3B05','3C03','3C05','3C33','3C35','3C53','3C55','3C83','3C85','3D03','3D05','3D33','3D35','3D53','3D55','3D83','3D85','4A33','4A35','4B33','4B35','4C33','4C35','4D33','4D35','7B03','7B05','7B33','7B35','7B53','7B55','7B83','7B85','7C03','7C05','7C33','7C35','7C53','7C55','7C83','7C85','7D03','7D05','7D33','7D35','7D53','7D55','7D83','7D85','7N33','7N35','7N83','7N85','7O33','7O35','7O83','7O85','7P33','7P35','7P83','7P85','8A33','8A35','8B33','8B35','8C33','8C35','8D33','8D35','9A33','9A35','9B33','9B35','9C33','9C35','9D33','9D35');
		return array_merge($codes1,$codes2);
	}
	
	public static function isExpressPostCode($code)
	{
		$codes = self::getExpressPostCodes();
		return in_array($code,$codes);
	}
	
	public static function isLinksynceparcelStandardCode($code)
	{
		$codes = self::getLinksynceparcelStandardCodes();
		return in_array($code,$codes);
	}
	
	public static function getShippingMethod($id_order)
	{
		$order = new WC_Order( $id_order );
		$methods = $order->get_shipping_methods(); 
		foreach($methods as $method)
		{
			return $method['method_id'];
		}
	}
	
	public static function getOrderChargeCode($id_order,$consignment_number='')
	{
		$method = self::getShippingMethod($id_order);
		if(preg_match('/table_rate/i',$method))
		{
			$method = 'table_rate';
		}
		
		$allowedChargeCodes = self::getEParcelChargeCodes();

		if(!empty($consignment_number))
		{
			$charge_code = self::getConsignmentSpecific($consignmentNumber,'chargecode');
			if(!empty($charge_code))
			{
				return $charge_code;
			}
		}
	
		$charge_code = self::getNonlinksyncShippingTypeChargecode($method);
		if($charge_code && array_key_exists($charge_code,$allowedChargeCodes))
		{
			return $charge_code;
		}
		
		$order = new WC_Order( $id_order );
		$method = $order->get_shipping_method();
		$charge_code = self::getNonlinksyncShippingTypeChargecode($method);
		if($charge_code && array_key_exists($charge_code,$allowedChargeCodes))
		{
			return $charge_code;
		}
		
		return false;
	}
	
	public static function getChargeCode($order, $consignmentNumber='')
	{
		$chargeCode = self::getOrderChargeCode($order->id, $consignmentNumber);
		return $chargeCode;
	}
	
	public static function getNonlinksyncShippingTypeChargecode($method)
	{
		global $wpdb;
		$table_name = $wpdb->prefix . "linksynceparcel_nonlinksync"; 
		$sql = "SELECT * FROM $table_name WHERE method = '".@mysql_escape_string($method)."'";
		$results = $wpdb->get_results($sql);
		foreach($results as $result)
		{
			return $result->charge_code;
		}
		return false;
	}
	
	public static function getConsignments($id_order)
	{
		global $wpdb;
		$table_name = $wpdb->prefix . "linksynceparcel_consignment"; 
		$query = "SELECT * FROM {$table_name} WHERE order_id = '{$id_order}'";
		return $wpdb->get_results($query);
	}
	
	public static function getOpenConsignments($id_order)
	{
		global $wpdb;
		$table_name = $wpdb->prefix . "linksynceparcel_consignment"; 
		$query = "SELECT * FROM {$table_name} WHERE order_id = '{$id_order}' AND despatched != 1";
		return $wpdb->get_results($query);
	}
	
	public static function getConsignment($consignmentNumber)
	{
		global $wpdb;
		$table_name = $wpdb->prefix . "linksynceparcel_consignment"; 
		$sql = "SELECT * FROM $table_name WHERE consignment_number = '$consignmentNumber'";
		$results = $wpdb->get_results($sql);
		foreach($results as $result)
		{
			return $result;
		}
		return false;
	}
	
	public static function getConsignmentSpecific($consignmentNumber,$field)
	{
		global $wpdb;
		$table_name = $wpdb->prefix . "linksynceparcel_consignment"; 
		$sql = "SELECT $field FROM $table_name WHERE consignment_number = '$consignmentNumber'";
		$results = $wpdb->get_results($sql);
		foreach($results as $result)
		{
			return $result->$field;
		}
		return false;
	}
	
	public static function isCashToCollect($id_order)
	{
		$allowedChargeCodes = array('CS1', 'CS2', 'CS3', 'CS4', 'CS5', 'CS6', 'CS7', 'CS8', 'CX1', 'CX2');
		
		$chargeCode = self::getOrderChargeCode($id_order);
		if(in_array($chargeCode,$allowedChargeCodes))
		{
			return true;
		}
		return false; 
	}
	
	public static function isDisablePartialDeliveryMethod($id_order)
	{
		$allowedChargeCodes = array('PR', 'XPR');
		
		$chargeCode = self::getOrderChargeCode($id_order);
		if(in_array($chargeCode,$allowedChargeCodes))
		{
			return true;
		}
		return false; 
	}
	
	public static function getLabelFormats()
	{
		$formats = array();
		$formats['sp'] = 'sp';
		$formats['spp'] = 'spp';
		$formats['mp'] = 'mp';
		$formats['mpp'] = 'mpp';
		return $formats;
    }
	
	public static function isSoapInstalled()
	{
		if(class_exists('SoapClient'))
		{
			return 1;
		}
		else
		{
			return 0;
		}
	}
	
	public static function addSuccess($message)
	{
		add_action( 'admin_notices', 'success_notice',10,2 );
		do_action('admin_notices', $message,'');
	}
	
	public static function addError($message)
	{
		add_action( 'admin_notices', 'error_notice',10,2 );
		do_action('admin_notices', '',$message);
	}
	
	public static function prepareArticle($data,$order,$consignment_number='')
	{
		$articleData = self::prepareArticleData($data,$order,$consignment_number);
		$content = $articleData['content'];
		$chargeCode = $articleData['charge_code'];
		return array('content' => $content, 'charge_code' => $chargeCode);
	}
	
	public static function prepareArticleBulk($data,$order)
	{
		$articleData = self::prepareArticleDataBulk($data,$order);
		$content = $articleData['content'];
		$chargeCode = $articleData['charge_code'];
		return array('content' => $content, 'charge_code' => $chargeCode);
	}

	public static function prepareArticleData($data,$order,$consignment_number='',$shipCountry=false)
	{		
		$isInternational = self::isInternationalDelivery($shipCountry);
		$address = get_post_meta($order->id);
		$returnAddress = self::prepareReturnAddress();
		$deliveryInfo = self::prepareDeliveryAddress($address,$order);
		$chargeCode = self::getChargeCode($order,$consignment_number);

		if($isInternational) {
			$articlesAllInfo = self::prepareAllArticles($data, $order);
			$search = array(
				'[[articles]]',
				'[[DELIVERY-ADDRESS]]',
				'[[RETURN-ADDRESS]]',
				'[[cashToCollect]]',
				'[[cashToCollectAmount]]',
				'[[ORDER-ID]]',
				'[[CHARGE-CODE]]',
				'[[printReturnLabels]]',
				'[[emailNotification]]',
				'[[DELIVERY-SIGNATURE]]',
				'[[deliverPartConsignment]]',
				'[[DANGER-GOODS]]'
			);
			
			$replace = array(
				$articlesInfo['info'],
				$deliveryInfo,
				$returnAddress,
				(isset($data['cash_to_collect']) ? 'Y' : 'N'),
				(isset($data['cash_to_collect']) ? number_format($data['cash_to_collect'],2) : ''),
				$order->id,
				$chargeCode,
				($data['print_return_labels'] ? 'true' : 'false'),
				($data['email_notification'] ? 'Y' : 'N'),
				($data['delivery_signature_allowed'] ? 'true' : 'false'),
				($data['partial_delivery_allowed'] ? 'Y' : 'N'),
				($data['contains_dangerous_goods'] ? 'true' : 'false')
			);
			
			$template = file_get_contents(linksynceparcel_DIR.'assets/xml/international-articles-template.xml');
		} else {
			$articlesInfo = self::prepareArticles($data, $order);
			$search = array(
				'[[articles]]',
				'[[RETURN-ADDRESS]]',
				'[[DELIVERY-ADDRESS]]',
				'[[CUSTOMER-EMAIL]]',
				'[[DELIVERY-SIGNATURE]]',
				'[[ORDER-ID]]',
				'[[CHARGE-CODE]]',
				'[[SHIPMENT-ID]]',
				'[[DANGER-GOODS]]',
				'[[printReturnLabels]]',
				'[[deliverPartConsignment]]',
				'[[cashToCollect]]',
				'[[cashToCollectAmount]]',
				'[[emailNotification]]'
			);
			
			$replace = array(
				$articlesInfo['info'],
				$returnAddress,
				$deliveryInfo,
				$address['_billing_email'][0],
				($data['delivery_signature_allowed'] ? 'true' : 'false'),
				self::getIncrementId($order),
				$chargeCode,
				$order->id,
				($data['contains_dangerous_goods'] ? 'true' : 'false'),
				($data['print_return_labels'] ? 'true' : 'false'),
				($data['partial_delivery_allowed'] ? 'Y' : 'N'),
				(isset($data['cash_to_collect']) ? '<cashToCollect>Y</cashToCollect>' : '<cashToCollect>N</cashToCollect>'),
				(isset($data['cash_to_collect']) ? '<cashToCollectAmount>'.number_format($data['cash_to_collect'],2).'</cashToCollectAmount>' : ''),
				($data['email_notification'] ? 'Y' : 'N')
			);
			
			$template = file_get_contents(linksynceparcel_DIR.'assets/xml/articles-template.xml');
		}
		
		$articleData = str_replace($search, $replace, $template);
		return array('content' => $articleData, 'charge_code' => $chargeCode, 'total_weight' => $articlesInfo['total_weight']);
	}
	
	public static function prepareOrderWeightArticleData($data,$order,$consignment_number='')
	{
		$address = get_post_meta($order->id);
		$returnAddress = self::prepareReturnAddress();
		$deliveryInfo = self::prepareDeliveryAddress($address,$order);
		$articlesInfo = self::prepareOrderWeightArticles($data, $order);
		
		$search = array(
			'[[articles]]',
			'[[RETURN-ADDRESS]]',
			'[[DELIVERY-ADDRESS]]',
			'[[CUSTOMER-EMAIL]]',
			'[[DELIVERY-SIGNATURE]]',
			'[[ORDER-ID]]',
			'[[CHARGE-CODE]]',
			'[[SHIPMENT-ID]]',
			'[[DANGER-GOODS]]',
			'[[printReturnLabels]]',
			'[[deliverPartConsignment]]',
			'[[cashToCollect]]',
  			'[[cashToCollectAmount]]',
			'[[emailNotification]]'
		);

		$chargeCode = self::getChargeCode($order,$consignment_number);
			
		$replace = array(
			$articlesInfo['info'],
			$returnAddress,
			$deliveryInfo,
			$address['_billing_email'][0],
			($data['delivery_signature_allowed'] ? 'true' : 'false'),
			self::getIncrementId($order),
			$chargeCode,
			$order->id,
			($data['contains_dangerous_goods'] ? 'true' : 'false'),
			($data['print_return_labels'] ? 'true' : 'false'),
			($data['partial_delivery_allowed'] ? 'Y' : 'N'),
			(isset($data['cash_to_collect']) ? '<cashToCollect>Y</cashToCollect>' : '<cashToCollect>N</cashToCollect>'),
			(isset($data['cash_to_collect']) ? '<cashToCollectAmount>'.number_format($data['cash_to_collect'],2).'</cashToCollectAmount>' : ''),
			($data['email_notification'] ? 'Y' : 'N')
		);
		$template = file_get_contents(linksynceparcel_DIR.'assets/xml/articles-template.xml');
		$articleData = str_replace($search, $replace, $template);
		return array('content' => $articleData, 'charge_code' => $chargeCode, 'total_weight' => $articlesInfo['total_weight']);
	}
	
	public static function prepareArticleDataBulk($data,$order)
	{
		$address = get_post_meta($order->id);
		$returnAddress = self::prepareReturnAddress();
		$deliveryInfo = self::prepareDeliveryAddress($address,$order);
		$articlesInfo = self::prepareArticlesBulk($data, $order);
		
		$search = array(
			'[[articles]]',
			'[[RETURN-ADDRESS]]',
			'[[DELIVERY-ADDRESS]]',
			'[[CUSTOMER-EMAIL]]',
			'[[DELIVERY-SIGNATURE]]',
			'[[ORDER-ID]]',
			'[[CHARGE-CODE]]',
			'[[SHIPMENT-ID]]',
			'[[DANGER-GOODS]]',
			'[[printReturnLabels]]',
			'[[deliverPartConsignment]]',
			'[[cashToCollect]]',
  			'[[cashToCollectAmount]]',
			'[[emailNotification]]'
		);

		$chargeCode = self::getChargeCode($order);
		$replace = array(
			$articlesInfo['info'],
			$returnAddress,
			$deliveryInfo,
			$address['_billing_email'][0],
			($data['delivery_signature_allowed'] ? 'true' : 'false'),
			self::getIncrementId($order),
			$chargeCode,
			$order->id,
			($data['contains_dangerous_goods'] ? 'true' : 'false'),
			($data['print_return_labels'] ? 'true' : 'false'),
			($data['partial_delivery_allowed'] ? 'Y' : 'N'),
			(isset($data['cash_to_collect']) ? '<cashToCollect>Y</cashToCollect>' : '<cashToCollect>N</cashToCollect>'),
			(isset($data['cash_to_collect']) ? '<cashToCollectAmount>'.number_format($data['cash_to_collect'],2).'</cashToCollectAmount>' : ''),
			($data['email_notification'] ? 'Y' : 'N')
		);
		$template = file_get_contents(linksynceparcel_DIR.'assets/xml/articles-template.xml');
		$articleData = str_replace($search, $replace, $template);
		return array('content' => $articleData, 'charge_code' => $chargeCode, 'total_weight' => $articlesInfo['total_weight']);
	}
	
	public static function prepareReturnAddress()
	{
		$returnAddressLine2 = trim(get_option('linksynceparcel_return_address_line2'));
		if(!empty($returnAddressLine2))
		{
			$returnAddressLine2 = '<returnAddressLine2>'.self::xmlData($returnAddressLine2).'</returnAddressLine2>';
		}
		else
		{
			$returnAddressLine2 = '<returnAddressLine2 />';
		}
		
		$returnAddressLine3 = trim(get_option('linksynceparcel_return_address_line3'));
		if(!empty($returnAddressLine3))
		{
			$returnAddressLine3 = '<returnAddressLine3>'.self::xmlData($returnAddressLine3).'</returnAddressLine3>';
		}
		else
		{
			$returnAddressLine3 = '<returnAddressLine3 />';
		}
		
		$returnAddressLine4 = trim(get_option('linksynceparcel_return_address_line4'));
		if(!empty($returnAddressLine4))
		{
			$returnAddressLine4 = '<returnAddressLine4>'.self::xmlData($returnAddressLine4).'</returnAddressLine4>';
		}
		else
		{
			$returnAddressLine4 = '<returnAddressLine4 />';
		}
		
		$search = array(
			'[[returnAddressLine1]]',
			'[[returnName]]',
			'[[returnPostcode]]',
			'[[returnStateCode]]',
			'[[returnSuburb]]',
			'[[returnAddressLine2]]',
			'[[returnAddressLine3]]',
			'[[returnAddressLine4]]'
		);

		$replace = array(
			self::xmlData(trim(get_option('linksynceparcel_return_address_line1'))),
			self::xmlData(trim(get_option('linksynceparcel_return_address_name'))),
			trim(get_option('linksynceparcel_return_address_postcode')),
			trim(get_option('linksynceparcel_return_address_statecode')),
			self::xmlData(trim(get_option('linksynceparcel_return_address_suburb'))),
			trim($returnAddressLine2),
			trim($returnAddressLine3),
			trim($returnAddressLine4)   
		);
		
		$template = file_get_contents(linksynceparcel_DIR.'assets/xml/article-return-address-template.xml');
		return str_replace($search, $replace, $template);
	}
	
	public static function isInternationalDelivery($shipCountry) {
		if($shipCountry != 'AU') {
			if(self::checkAssignShippingType()) {
				return true;
			}
		}
		return false;
	}
	
	public static function checkAssignShippingType() {
		global $wpdb;
		$table = $wpdb->prefix ."linksynceparcel_nonlinksync";
		$sql = "SELECT * FROM ". $table ." WHERE method='international_delivery'";
		$row = $wpdb->get_row($sql);
		return !empty($row)?true:false;
	}
	
	public static function xmlData($text)
	{
		$text = trim($text);
		$text = str_replace('&','&amp;',$text);
		$search = array("<",">",'"',"'");
		$replace = array("&lt;","&gt;","&quot;","&apos;");
		return str_replace($search, $replace, $text);
	}
	
	public static function getNotes($order_id)
	{
		$note = '';
		$args = array(
			'status' => 'approve',
			'post_id' => $order_id
		);
		$comments = get_comments($args);
		foreach($comments as $comment)
		{
			$note .= $comment->comment_content . '<br />';
		}
		return $note;
	}
	
	public static function prepareDeliveryAddress($address,$order)
	{
		$street1 = '<deliveryAddressLine1>'.self::xmlData($address['_shipping_address_1'][0]).'</deliveryAddressLine1>';
		$street2 = '<deliveryAddressLine2/>';
		$street3 = '<deliveryAddressLine3/>';
		$street4 = '<deliveryAddressLine4/>';
		
		if(isset($address['_shipping_address_2'][0]) && !empty($address['_shipping_address_2'][0]))
		{
			$street2 = '<deliveryAddressLine2>'.self::xmlData($address['_shipping_address_2'][0]).'</deliveryAddressLine2>';
		}
		
		$city = $address['_shipping_city'][0];
		$state = 'NA';
		if($address['_shipping_state'][0])
		{
			$state = $address['_shipping_state'][0];
		}
		$postalCode = $address['_shipping_postcode'][0];
		$company = empty($address['_shipping_company'][0]) ? '<deliveryCompanyName/>' : '<deliveryCompanyName>'.self::xmlData($address['_shipping_company'][0]).'</deliveryCompanyName>';
		$firstname = $address['_shipping_first_name'][0].' '.$address['_shipping_last_name'][0];
		$email = $address['_billing_email'][0];
		$phone = $address['_billing_phone'][0];
		
		$instructions = false;
		if(trim(get_option('linksynceparcel_copy_order_notes')) == 1)
		{
			$instructions = $order->customer_message;
		}
		
		$search = array(
			'[[deliveryCompanyName]]',
			'[[deliveryName]]',
			'[[deliveryEmailAddress]]',
			'[[deliveryAddressLine1]]',
			'[[deliveryAddressLine2]]',
			'[[deliveryAddressLine3]]',
			'[[deliveryAddressLine4]]',
			'[[deliverySuburb]]',
			'[[deliveryStateCode]]',
			'[[deliveryPostcode]]',
			'[[deliveryPhoneNumber]]',
			'[[deliveryInstructions]]'
		);

		$replace = array(
			trim($company),
			self::xmlData(trim($firstname)),
			trim($email),
			trim($street1),
			trim($street2),
			trim($street3),
			trim($street4),
			self::xmlData(trim($city)),
			trim($state),
			trim($postalCode),
			trim($phone),
			($instructions ? '<deliveryInstructions>'.self::xmlData(($instructions)).'</deliveryInstructions>' : '<deliveryInstructions />')
		);
		
		$template = file_get_contents(linksynceparcel_DIR.'assets/xml/article-delivery-address-template.xml');
		return str_replace($search, $replace, $template);
	}
	
	public static function prepareArticles($data, $order,$consignment_number='',$isInternational=false)
	{
		$articlesInfo = '';
		$total_weight = 0;
		$number_of_articles = $data['number_of_articles'];
		$start_index = $data['start_index'];
		$end_index = $data['end_index'];
		$articles = array();
		
		for($i=$start_index;$i<=$end_index;$i++)
		{
			if($data['articles_type'] == 'Custom')
			{
        		$article = $data['article'.$i];
			}
			else
			{
				$articles_type = $data['articles_type'];
				$articles = explode('<=>',$articles_type);
				
				$article = array();
				$article['description'] = $articles[0];
				$article['weight'] = $articles[1];
				$article['height'] = $articles[2];
				$article['length'] = $articles[3];
				$article['width'] = $articles[4];
				
				$use_order_total_weight = (int)get_option('linksynceparcel_use_order_weight');
				if($use_order_total_weight == 1)
				{
					$weight = LinksynceparcelHelper::getOrderWeight($order);
					$weightPerArticle = LinksynceparcelHelper::getAllowedWeightPerArticle();
					if($weight == 0)
					{
						$default_article_weight = get_option('linksynceparcel_default_article_weight');
						if($default_article_weight)
						{
							$weight = $default_article_weight;
						}
					}
					$exactArticles = (int)($weight / $weightPerArticle);
					$totalArticles = $exactArticles;
					$reminderWeight = fmod ($weight, $weightPerArticle);
					if($reminderWeight > 0)
					{
						$totalArticles++;
					}
					
					if($totalArticles == 0)
					{
						$totalArticles = 1;
					}
					
					if($weight > $weightPerArticle)
					{
						$weight = $weightPerArticle;
					}
					if($reminderWeight > 0 && $i == $totalArticles)
					{
						$weight = $reminderWeight;
					}
					$article['weight'] = $weight;
				}
			}
			
			$article['weight'] = number_format($article['weight'],2,'.', '');
			$total_weight += $article['weight'];
			
			if($isInternational) {
				$search = array(
					'[[articleDescription]]',
					'[[actualWeight]]',
					'[[width]]',
					'[[height]]',
					'[[length]]',
					'[[isTransitCoverRequired]]',
					'[[transitCoverAmount]]'
				);

				$replace = array(
					self::xmlData(trim($article['description'])),
					trim($article['weight']),
					trim($article['width']),
					trim($article['height']),
					trim($article['length']),
					($data['transit_cover_required'] ? 'Y' : 'N'),
					($data['transit_cover_required'] ? trim($data['transit_cover_amount']) : 0)
				);
				
				$template = file_get_contents(linksynceparcel_DIR.'assets/xml/international-article-template.xml');
			} else {
				$search = array(
					'[[actualWeight]]',
					'[[articleDescription]]',
					'[[height]]',
					'[[length]]',
					'[[width]]',
					'[[isTransitCoverRequired]]',
					'[[transitCoverAmount]]',
					'[[articleNumber]]'
				);

				$replace = array(
					trim($article['weight']),
					self::xmlData(trim($article['description'])),
					trim($article['height']),
					trim($article['length']),
					'<width>'.trim($article['width']).'</width>',
					($data['transit_cover_required'] ? 'Y' : 'N'),
					($data['transit_cover_required'] ? trim($data['transit_cover_amount']) : 0),
					(isset($article['article_number']) ? '<articleNumber>'.trim($article['article_number']).'</articleNumber>' : '')
				);
				
				$template = file_get_contents(linksynceparcel_DIR.'assets/xml/article-template.xml');
			}
			
			$articlesInfo .= str_replace($search, $replace, $template);
		}
		return array('info' => $articlesInfo, 'total_weight' => $total_weight);
	}
	
	public static function prepareAllArticles($data,$order) {
		$articlesInfo = self::prepareArticles($data,$order,'',true);
		$isInsurance = get_option('linksynceparcel_insurance');
		$insuranceValue = get_option('linksynceparcel_default_insurance_value');
		$search = array(
			'[[preparedarticle]]',
			'[[isInsuranceRequired]]',
			'[[insuranceValue]]',
			'[[classificationExplanation]]',
			'[[exportDeclarationNumber]]',
			'[[productClassification]]',
			'[[hasCommercialValue]]',
			'[[deliveryFailureDetails]]',
			'[[contents]]'
		);

		$replace = array(
			$articlesInfo['info'],
			($isInsurance==0)? 'false' : 'true',
			trim($insuranceValue),
		);
		
		$template = file_get_contents(linksynceparcel_DIR.'assets/xml/international-articleall-template.xml');
	}
	
	public static function prepareOrderWeightArticles($data, $order,$consignment_number='')
	{
		$articlesInfo = '';
		$number_of_articles = $data['number_of_articles'];
		$start_index = $data['start_index'];
		$end_index = $data['end_index'];
		$articles = array();
		
		$totalWeight = 0;
		
		for($i=$start_index;$i<=$end_index;$i++)
		{
			if($data['articles_type'] == 'Custom')
			{
        		$article = $data['article'.$i];
			}
			else
			{
				$articles_type = $data['articles_type'];
				$articles = explode('<=>',$articles_type);
				
				$article = array();
				$article['description'] = $articles[0];
				$article['weight'] = $articles[1];
				$article['height'] = $articles[2];
				$article['length'] = $articles[3];
				$article['width'] = $articles[4];
			}
		
			$search = array(
				'[[actualWeight]]',
				'[[articleDescription]]',
				'[[height]]',
				'[[length]]',
				'[[width]]',
				'[[isTransitCoverRequired]]',
				'[[transitCoverAmount]]',
				'[[articleNumber]]'
			);
			
			$article['weight'] = number_format($article['weight'],2,'.', '');
			
			$totalWeight += $article['weight'];
		
			$replace = array(
				trim($article['weight']),
				self::xmlData(trim($article['description'])),
				0,
				0,
				'<width>0</width>',
				($data['transit_cover_required'] ? 'Y' : 'N'),
				($data['transit_cover_required'] ? trim($data['transit_cover_amount']) : 0),
				(isset($article['article_number']) ? '<articleNumber>'.trim($article['article_number']).'</articleNumber>' : '')
			);
			
			$template = file_get_contents(linksynceparcel_DIR.'assets/xml/article-template.xml');
			$articlesInfo .= str_replace($search, $replace, $template);
		}
		return array('info' => $articlesInfo, 'total_weight' => $totalWeight);
	}
	
	public static function prepareArticlesBulk($data, $order)
	{
		$articlesInfo = '';
		$total_weight = 0;
		$articles_type = $data['articles_type'];
		if($articles_type == 'order_weight')
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
			$exactArticles = (int)($weight / $weightPerArticle);
			$totalArticles = $exactArticles;
			$reminderWeight = fmod ($weight, $weightPerArticle);
			if($reminderWeight > 0)
			{
				$totalArticles++;
			}
			
			if($totalArticles == 0)
			{
				$totalArticles = 1;
			}
			
			for($i=1;$i<=$totalArticles;$i++)
			{
				$article = array();
				$article['description'] = 'Article '.$i;
				
				if($reminderWeight > 0 && $i == $totalArticles)
				{
					$article['weight'] = $reminderWeight;
				}
				else
				{
					$article['weight'] = $weightPerArticle;
				}
				$article['height'] = 0;
				$article['length'] = 0;
				$article['width'] = 0;
				
				$search = array(
					'[[actualWeight]]',
					'[[articleDescription]]',
					'[[height]]',
					'[[length]]',
					'[[width]]',
					'[[isTransitCoverRequired]]',
					'[[transitCoverAmount]]',
					'[[articleNumber]]'
				);
			
				$article['weight'] = number_format($article['weight'],2,'.', '');
				
				$total_weight += $article['weight'];
				
				$replace = array(
					$article['weight'],
					self::xmlData($article['description']),
					$article['height'],
					$article['length'],
					'<width>'.$article['width'].'</width>',
					($data['transit_cover_required'] ? 'Y' : 'N'),
					($data['transit_cover_required'] ? $data['transit_cover_amount'] : 0),
					''
				);
				
				$template = file_get_contents(linksynceparcel_DIR.'assets/xml/article-template.xml');
				$articlesInfo .= str_replace($search, $replace, $template);
			}
		}
		else
		{
			$articles = explode('<=>',$articles_type);
			
			$use_order_total_weight = (int)get_option('linksynceparcel_use_order_weight');
			if($use_order_total_weight == 1)
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
				$exactArticles = (int)($weight / $weightPerArticle);
				$totalArticles = $exactArticles;
				$reminderWeight = fmod ($weight, $weightPerArticle);
				if($reminderWeight > 0)
				{
					$totalArticles++;
				}
				
				if($totalArticles == 0)
				{
					$totalArticles = 1;
				}
				
				for($i=1;$i<=$totalArticles;$i++)
				{
					$article = array();
					$article['description'] = $articles[0];
					$article['height'] = $articles[2];
					$article['length'] = $articles[3];
					$article['width'] = $articles[4];
					
					if($reminderWeight > 0 && $i == $totalArticles)
					{
						$article['weight'] = $reminderWeight;
					}
					else
					{
						$article['weight'] = $weightPerArticle;
					}
					
					$search = array(
						'[[actualWeight]]',
						'[[articleDescription]]',
						'[[height]]',
						'[[length]]',
						'[[width]]',
						'[[isTransitCoverRequired]]',
						'[[transitCoverAmount]]',
						'[[articleNumber]]'
					);
				
					$article['weight'] = number_format($article['weight'],2,'.', '');
					$total_weight += $article['weight'];
					
					$replace = array(
						$article['weight'],
						self::xmlData($article['description']),
						$article['height'],
						$article['length'],
						'<width>'.$article['width'].'</width>',
						($data['transit_cover_required'] ? 'Y' : 'N'),
						($data['transit_cover_required'] ? $data['transit_cover_amount'] : 0),
						''
					);
					
					$template = file_get_contents(linksynceparcel_DIR.'assets/xml/article-template.xml');
					$articlesInfo .= str_replace($search, $replace, $template);
				}
			}
			else
			{
				$article = array();
				$article['description'] = $articles[0];
				$article['weight'] = $articles[1];
				$article['height'] = $articles[2];
				$article['length'] = $articles[3];
				$article['width'] = $articles[4];
				
				$search = array(
					'[[actualWeight]]',
					'[[articleDescription]]',
					'[[height]]',
					'[[length]]',
					'[[width]]',
					'[[isTransitCoverRequired]]',
					'[[transitCoverAmount]]',
					'[[articleNumber]]'
				);
				
				$article['weight'] = number_format($article['weight'],2,'.', '');
				$total_weight += $article['weight'];
			
				$replace = array(
					trim($article['weight']),
					self::xmlData(trim($article['description'])),
					trim($article['height']),
					trim($article['length']),
					'<width>'.trim($article['width']).'</width>',
					($data['transit_cover_required'] ? 'Y' : 'N'),
					($data['transit_cover_required'] ? trim($data['transit_cover_amount']) : 0),
					''
				);
				
				$template = file_get_contents(linksynceparcel_DIR.'assets/xml/article-template.xml');
				$articlesInfo .= str_replace($search, $replace, $template);
			}
		}
		return array('info' => $articlesInfo, 'total_weight' => $total_weight);
	}
	
	public static function getIncrementId($order)
	{
		return $order->get_order_number();
	}
	
	public static function insertConsignment($order_id,$consignmentNumber,$data,$manifestNumber,$chargeCode,$total_weight)
	{
		global $wpdb;
		$table_name = $wpdb->prefix . "linksynceparcel_consignment"; 
		$timestamp = time();
		$date = date('Y-m-d H:i:s', $timestamp);
		$query = "INSERT {$table_name} SET order_id = '{$order_id}', consignment_number='{$consignmentNumber}', add_date='".$date."', delivery_signature_allowed = '".$data['delivery_signature_allowed']."', print_return_labels='".$data['print_return_labels']."', contains_dangerous_goods='".$data['contains_dangerous_goods']."', partial_delivery_allowed = '".$data['partial_delivery_allowed']."', cash_to_collect='".(isset($data['cash_to_collect'])?$data['cash_to_collect']:'')."', email_notification = '".$data['email_notification']."', notify_customers = '".$data['notify_customers']."', chargecode = '".$chargeCode."', weight = '".$total_weight."'";
		$manifestNumber = trim($manifestNumber);
		if(strtolower($manifestNumber) != 'unassinged')
		{
			$query .= ", manifest_number = '".$manifestNumber."', is_next_manifest = 1";
		}
		$wpdb->query($query);
	}
	
	public static function updateConsignment($order_id,$consignmentNumber,$data,$manifestNumber,$chargeCode,$total_weight)
	{
		global $wpdb;
		$table_name = $wpdb->prefix . "linksynceparcel_consignment"; 
		$timestamp = time();
		$date = date('Y-m-d H:i:s', $timestamp);
		$query = "UPDATE {$table_name} SET delivery_signature_allowed = '".$data['delivery_signature_allowed']."', print_return_labels='".$data['print_return_labels']."', contains_dangerous_goods='".$data['contains_dangerous_goods']."', partial_delivery_allowed = '".$data['partial_delivery_allowed']."', cash_to_collect='".(isset($data['cash_to_collect'])?$data['cash_to_collect']:'')."', email_notification = '".$data['email_notification']."', notify_customers = '".$data['notify_customers']."', chargecode = '".$chargeCode."', label = '', is_label_printed=0, is_label_created=0, weight = '".$total_weight."'";
		
		$manifestNumber = trim($manifestNumber);
		if(strtolower($manifestNumber) != 'unassinged')
		{
			$query .= ", manifest_number = '".$manifestNumber."', is_next_manifest = 1";
		}
		else
		{
			$query .= ", manifest_number = '', is_next_manifest = 0";
		}
		$query .= " WHERE consignment_number='{$consignmentNumber}'"; 
		$wpdb->query($query);
		
		$filename = $consignmentNumber.'.pdf';
		$filepath = linksynceparcel_DIR.'assets/label/consignment/'.$filename;
		if(file_exists($filepath))
		{
			unlink($filepath);
		}
		
		$filepath = linksynceparcel_DIR.'assets/label/returnlabels/'.$filename;
		if(file_exists($filepath))
		{
			unlink($filepath);
		}
	}
	
	public static function getArticles($id_order, $consignment_number)
	{
		global $wpdb;
		$table_name = $wpdb->prefix . "linksynceparcel_article";
		$query = "SELECT * FROM {$table_name} WHERE order_id = '{$id_order}' AND consignment_number='{$consignment_number}'";
		return $wpdb->get_results($query);
	}
	
	public static function getArticle($articleNumber)
	{
		global $wpdb;
		$table_name = $wpdb->prefix . "linksynceparcel_article";
		$query = "SELECT * FROM {$table_name} WHERE article_number='{$articleNumber}'";
		$articles = $wpdb->get_results($query);
		foreach($articles as $article)
		{
			return $article;
		}
	}
	
	public static function deleteArticle($order_id,$consignmentNumber, $articleNumber)
	{
		global $wpdb;
		$table_name = $wpdb->prefix . "linksynceparcel_article";
		$deleteToBeArticle = self::getArticle($articleNumber);
		$query = "DELETE FROM {$table_name} WHERE order_id = '{$order_id}' AND consignment_number='{$consignmentNumber}' AND article_number='{$articleNumber}'";
		$wpdb->query($query);
		return $deleteToBeArticle;
	}
	
	public static function updateArticles($order_id, $consignmentNumber, $articles, $data,$content)
	{
		global $wpdb;
		$table_name = $wpdb->prefix . "linksynceparcel_article";
		$query = "DELETE FROM {$table_name} WHERE consignment_number='{$consignmentNumber}'";
		$wpdb->query($query);
		
		try
		{
			$query = '';
			$articleNumbers = $articles->article;
			LinksynceparcelHelper::log('articles : '.print_r($articles, true));
			$xml = simplexml_load_string($content);
			if($xml)
			{
				$j = 0;
				foreach($xml->articles->article as $article)
				{
					$articleNumber = (is_array($articleNumbers) ? $articleNumbers[$j++] : $articleNumbers);
					$actualWeight = $article->actualWeight;
					$articleDescription = $article->articleDescription;
					$cubicWeight = $article->cubicWeight;
					$height = $article->height;
					$isTransitCoverRequired = $article->isTransitCoverRequired;
					$length = $article->length;
					$width = $article->width;
					$transitCoverAmount = $article->transitCoverAmount;
					
					$query = "INSERT {$table_name} SET order_id = '{$order_id}', consignment_number='{$consignmentNumber}',  actual_weight='{$actualWeight}', article_description='".@mysql_escape_string(self::xmlData($articleDescription))."', article_number='{$articleNumber}', cubic_weight='{$cubicWeight}', height='{$height}', width='{$width}', is_transit_cover_required='{$isTransitCoverRequired}', length='{$length}', transit_cover_amount='{$transitCoverAmount}';";
					$wpdb->query($query);
				}
			}
		}
		catch(Exception $e)
		{
			throw new Exception($e->getMessage());
		}
	}
	
	public static function insertBulkArticles($order_id, $consignmentNumber, $articles, $data)
	{
		global $wpdb;
		$table_name = $wpdb->prefix . "linksynceparcel_article";
		try
		{
			$query = '';
			$articleNumbers = $articles->article;
	
			$articles_type = $data['articles_type'];
			$articleTemp = explode('<=>',$articles_type);
			
			$article = array();
			$article['description'] = $articleTemp[0];
			$article['weight'] = $articleTemp[1];
			$article['height'] = $articleTemp[2];
			$article['length'] = $articleTemp[3];
			$article['width'] = $articleTemp[4];
			
			$actualWeight = $article['weight'];
			$articleDescription = $article['description'];
			$articleNumber = (is_array($articleNumbers) ? $articleNumbers[$j] : $articleNumbers);
			$cubicWeight = 0;
			$height = $article['height'];
			$length = $article['length'];
			$width = $article['width'];
			$isTransitCoverRequired = ($data['transit_cover_required'] ? 'Y' : 'N');
			$transitCoverAmount = ($data['transit_cover_required'] ? $data['transit_cover_amount'] : 0);
			
			$query .= "INSERT {$table_name} SET order_id = '{$order_id}', consignment_number='{$consignmentNumber}',  actual_weight='{$actualWeight}', article_description='".@mysql_escape_string($articleDescription)."', article_number='{$articleNumber}', cubic_weight='{$cubicWeight}', height='{$height}', width='{$width}', is_transit_cover_required='{$isTransitCoverRequired}', length='{$length}', transit_cover_amount='{$transitCoverAmount}';";

			$wpdb->query($query);
		}
		catch(Exception $e)
		{
			throw new Exception($e->getMessage());
		}
	}
	
	
	
	public static function getManifest($manifest_number)
	{
		global $wpdb;
		$table_name = $wpdb->prefix . "linksynceparcel_manifest";
		$query = "SELECT * FROM {$table_name} WHERE manifest_number = '{$manifest_number}'";
		$results = $wpdb->get_results($query);
		foreach($results as $result)
		{
			return $result;
		}
		return false;
	}
	
	public static function deleteManifest()
	{
		global $wpdb;
		$table_name = $wpdb->prefix . "linksynceparcel_consignment";
		$query = "SELECT * FROM {$table_name} WHERE despatched = 0 and is_next_manifest = 1";
		$results = $wpdb->get_results($query);
		if(count($results) == 0)
		{
			$table_name = $wpdb->prefix . "linksynceparcel_manifest";
			$query = "DELETE FROM {$table_name} WHERE despatch_date is null or despatch_date = '' order by manifest_number desc limit 1";
			$wpdb->query($query);
		}
		update_option('linksynceparcel_manifest_sync', 1);
	}
	
	public static function deleteManifest2($manifestNumber)
	{
		global $wpdb;
		if(!empty($manifestNumber))
		{
			$table_name = $wpdb->prefix . "linksynceparcel_consignment";
			$query = "SELECT * FROM {$table_name} WHERE manifest_number = '$manifestNumber'";
			$results = $wpdb->get_results($query);
			if(count($results) == 0)
			{
				$table_name = $wpdb->prefix . "linksynceparcel_manifest";
				$query = "DELETE FROM {$table_name} WHERE manifest_number = '$manifestNumber'";
				$wpdb->query($query);
			}
		}
	}
	
	public static function insertManifest($manifestNumber,$numberOfArticles=0,$numberOfConsignments=0)
	{
		global $wpdb;
		$table_name = $wpdb->prefix . "linksynceparcel_manifest";
		if(!self::getManifest($manifestNumber))
		{
			$manifestNumber = trim($manifestNumber);
			if(strtolower($manifestNumber) != 'unassinged')
			{
				$query = "INSERT {$table_name} SET manifest_number='{$manifestNumber}', number_of_articles = '{$numberOfArticles}', number_of_consignments='{$numberOfConsignments}'";
				$wpdb->query($query);
			}
		}
		update_option('linksynceparcel_manifest_sync', 1);
	}
	
	public static function updateManifest($manifestNumber,$numberOfArticles,$numberOfConsignments)
	{
		global $wpdb;
		$table_name = $wpdb->prefix . "linksynceparcel_manifest";
		if(self::getManifest($manifestNumber))
		{
			$query = "UPDATE {$table_name} SET number_of_articles = '{$numberOfArticles}', number_of_consignments='{$numberOfConsignments}' WHERE manifest_number='{$manifestNumber}'";
			$wpdb->query($query);
		}
		else
		{
			$query = "INSERT {$table_name} SET number_of_articles = '{$numberOfArticles}', manifest_number='{$manifestNumber}', number_of_consignments='{$numberOfConsignments}'";
			$wpdb->query($query);
		}
	}
	
	public static function labelCreate($consignmentNumber)
    {
		try
		{
			$labelContent = LinksynceparcelApi::getLabelsByConsignments($consignmentNumber);
			if($labelContent)
			{
				$filename = $consignmentNumber.'.pdf';
				$filepath = linksynceparcel_DIR.'assets/label/consignment/'.$filename;
				$handle = fopen($filepath,'wb');
				fwrite($handle, $labelContent);
				fclose($handle);
				self::updateConsignmentTable($consignmentNumber,'label',$filename);
				self::updateConsignmentTable($consignmentNumber,'is_label_created',1);
			}
		}
		catch(Exception $e)
		{
			;//log
		}
	}
	
	public static function returnLabelCreate($consignmentNumber)
    {
		try
		{
			$print_return_labels = self::getConsignmentSpecific($consignmentNumber,'print_return_labels');
			if($print_return_labels)
			{
				$labelContent = LinksynceparcelApi::getReturnLabelsByConsignments($consignmentNumber);
				if($labelContent)
				{
					$filename = $consignmentNumber.'.pdf';
					$filepath = linksynceparcel_DIR.'assets/label/returnlabels/'.$filename;
					$handle = fopen($filepath,'wb');
					fwrite($handle, $labelContent);
					fclose($handle);
					self::updateConsignmentTable($consignmentNumber,'is_return_label_printed',0);
				}
			}
		}
		catch(Exception $e)
		{
			;//log
		}
	}
	
	public static function updateConsignmentTable($consignmentNumber,$columnName, $value)
	{
		global $wpdb;
		$table_name = $wpdb->prefix . "linksynceparcel_consignment";
		$query = "UPDATE {$table_name} SET {$columnName} = '{$value}' WHERE consignment_number='{$consignmentNumber}'";
		$wpdb->query($query);
	}
	
	public static function updateConsignmentTableByManifest($manifestNumber,$columnName, $value)
	{
		global $wpdb;
		$table_name = $wpdb->prefix . "linksynceparcel_consignment";
		$query = "UPDATE {$table_name} SET {$columnName} = '{$value}' WHERE manifest_number='{$manifestNumber}'";
		$wpdb->query($query);
	}
	
	public static function getOrdersByManifest($manifestNumber)
	{
		global $wpdb;
		$table_name = $wpdb->prefix . "linksynceparcel_consignment";
		$query = "SELECT order_id FROM {$table_name} WHERE manifest_number = '{$manifestNumber}' group by order_id";
		return $wpdb->get_results($query);
	}
	
	public static function deleteConsignment($consignmentNumber)
	{
		global $wpdb;
		$table_name = $wpdb->prefix . "linksynceparcel_consignment";
		$query = "DELETE FROM {$table_name} WHERE consignment_number='{$consignmentNumber}'";
		$wpdb->query($query);
		
		$table_name = $wpdb->prefix . "linksynceparcel_article";
		$query = "DELETE FROM {$table_name} WHERE consignment_number='{$consignmentNumber}'";
		$wpdb->query($query);
	}
	
	public static function prepareModifiedArticle($order,$consignment_number)
	{
		$articleData = self::prepareModifiedArticleData($order,$consignment_number);
		$content = $articleData['content'];
		$chargeCode = $articleData['charge_code'];
		return array('content' => $content, 'charge_code' => $chargeCode);
	}
	
	public static function prepareModifiedArticleData($order,$consignment_number)
	{
		$address = get_post_meta($order->id);
		$returnAddress = self::prepareReturnAddress();
		$deliveryInfo = self::prepareDeliveryAddress($address,$order);
		$articlesInfo = self::prepareModifiedArticles($order,$consignment_number);
		$consignment = self::getConsignment($consignment_number);
		
		$search = array(
			'[[articles]]',
			'[[RETURN-ADDRESS]]',
			'[[DELIVERY-ADDRESS]]',
			'[[CUSTOMER-EMAIL]]',
			'[[DELIVERY-SIGNATURE]]',
			'[[ORDER-ID]]',
			'[[CHARGE-CODE]]',
			'[[SHIPMENT-ID]]',
			'[[DANGER-GOODS]]',
			'[[printReturnLabels]]',
			'[[deliverPartConsignment]]',
			'[[cashToCollect]]',
  			'[[cashToCollectAmount]]',
			'[[emailNotification]]'
		);

		$chargeCode = self::getChargeCode($order,$consignment_number);
			
		$replace = array(
			$articlesInfo['info'],
			$returnAddress,
			$deliveryInfo,
			$address['_billing_email'][0],
			($consignment->delivery_signature_allowed ? 'true' : 'false'),
			self::getIncrementId($order),
			$chargeCode,
			$order->id,
			($consignment->contains_dangerous_goods ? 'true' : 'false'),
			($consignment->print_return_labels ? 'true' : 'false'),
			($consignment->partial_delivery_allowed ? 'Y' : 'N'),
			( (isset($consignment->cash_to_collect) && !empty($consignment->cash_to_collect) ) ? '<cashToCollect>Y</cashToCollect>' : '<cashToCollect>N</cashToCollect>'),
			( (isset($consignment->cash_to_collect) && !empty($consignment->cash_to_collect) ) ? '<cashToCollectAmount>'.number_format($consignment->cash_to_collect,2).'</cashToCollectAmount>' : ''),
			($consignment->email_notification ? 'Y' : 'N')
		);
		$template = file_get_contents(linksynceparcel_DIR.'assets/xml/articles-template.xml');
		$content = str_replace($search, $replace, $template);
		return array('content' => $content, 'charge_code' => $chargeCode, 'total_weight' => $articlesInfo['total_weight']);
	}
	
	public static function prepareModifiedArticles($order,$consignment_number)
	{
		$articlesInfo = '';
		$total_weight = 0;
		$articles = LinksynceparcelHelper::getArticles($order->id, $consignment_number);
		foreach($articles as $article)
		{
			$search = array(
				'[[actualWeight]]',
				'[[articleDescription]]',
				'[[height]]',
				'[[length]]',
				'[[width]]',
				'[[isTransitCoverRequired]]',
				'[[transitCoverAmount]]',
				'[[articleNumber]]'
			);
			
			$default_width = 0;
			$use_article_dimensions = (int)get_option('linksynceparcel_use_dimension');
			if($use_article_dimensions == 1)
			{
				$default_width = get_option('linksynceparcel_default_article_width');
			}
			
			$total_weight += $article->actual_weight;
		
			$replace = array(
				trim($article->actual_weight),
				trim($article->article_description),
				trim($article->height),
				trim($article->length),
				($article->width ? '<width>'.trim($article->width).'</width>' : '<width>'.trim(get_option('linksynceparcel_default_article_width')).'</width>'),
				$article->is_transit_cover_required,
				(($article->is_transit_cover_required == 'Y') ? trim($article->transit_cover_amount) : 0),
				'<articleNumber>'.trim($article->article_number).'</articleNumber>'
			);
			
			$template = file_get_contents(linksynceparcel_DIR.'assets/xml/article-template.xml');
			$articlesInfo .= str_replace($search, $replace, $template);
		}
		return array('info' => $articlesInfo, 'total_weight' => $total_weight);
	}
	
	public static function prepareAddArticle($data,$order,$consignmentNumber)
	{
		$articleData = self::prepareAddArticleData($data,$order,$consignmentNumber);
		$content = $articleData['content'];
		$chargeCode = $articleData['charge_code'];
		return array('content' => $content, 'charge_code' => $chargeCode);
	}
	
	public static function prepareAddArticleData($data, $order,$consignment_number)
	{
		$address = get_post_meta($order->id);
		$returnAddress = self::prepareReturnAddress();
		$deliveryInfo = self::prepareDeliveryAddress($address,$order);
		$articlesInfo = self::prepareAddArticles($order,$data, $consignment_number);
		
		$search = array(
			'[[articles]]',
			'[[RETURN-ADDRESS]]',
			'[[DELIVERY-ADDRESS]]',
			'[[CUSTOMER-EMAIL]]',
			'[[DELIVERY-SIGNATURE]]',
			'[[ORDER-ID]]',
			'[[CHARGE-CODE]]',
			'[[SHIPMENT-ID]]',
			'[[DANGER-GOODS]]',
			'[[printReturnLabels]]',
			'[[deliverPartConsignment]]',
			'[[cashToCollect]]',
  			'[[cashToCollectAmount]]',
			'[[emailNotification]]'
		);
		
		$chargeCode = self::getChargeCode($order,$consignment_number);

		$replace = array(
			$articlesInfo['info'],
			$returnAddress,
			$deliveryInfo,
			$address['_billing_email'][0],
			($data['delivery_signature_allowed'] ? 'true' : 'false'),
			self::getIncrementId($order),
			$chargeCode,
			$order->id,
			($data['contains_dangerous_goods'] ? 'true' : 'false'),
			($data['print_return_labels'] ? 'true' : 'false'),
			($data['partial_delivery_allowed'] ? 'Y' : 'N'),
			(isset($data['cash_to_collect']) ? '<cashToCollect>Y</cashToCollect>' : '<cashToCollect>N</cashToCollect>'),
			(isset($data['cash_to_collect']) ? '<cashToCollectAmount>'.number_format($data['cash_to_collect'],2).'</cashToCollectAmount>' : ''),
			($data['email_notification'] ? 'Y' : 'N')
		);
		$template = file_get_contents(linksynceparcel_DIR.'assets/xml/articles-template.xml');
		$content = str_replace($search, $replace, $template);
		return array('content' => $content, 'charge_code' => $chargeCode, 'total_weight' => $articlesInfo['total_weight']);
	}
	
	public static function prepareAddArticles($order,$data, $consignment_number)
	{
		$articlesInfo = '';
		$total_weight = 0;
		
		$articles = LinksynceparcelHelper::getArticles($order->id, $consignment_number);
		foreach($articles as $article)
		{
			$search = array(
				'[[actualWeight]]',
				'[[articleDescription]]',
				'[[height]]',
				'[[length]]',
				'[[width]]',
				'[[isTransitCoverRequired]]',
				'[[transitCoverAmount]]',
				'[[articleNumber]]'
			);
			
			$total_weight += $article->actual_weight;
			
			$default_width = 0;
			$use_article_dimensions = (int)get_option('linksynceparcel_use_dimension');
			if($use_article_dimensions == 1)
			{
				$default_width = get_option('linksynceparcel_default_article_width');
			}
		
			$replace = array(
				trim($article->actual_weight),
				trim($article->article_description),
				trim($article->height),
				trim($article->length),
				'<width>'.trim($article->width).'</width>',
				$article->is_transit_cover_required,
				( ($article->is_transit_cover_required == 'Y') ? trim($article->transit_cover_amount) : 0),
				'<articleNumber>'.trim($article->article_number).'</articleNumber>'
			);
			
			$template = file_get_contents(linksynceparcel_DIR.'assets/xml/article-template.xml');
			$articlesInfo .= str_replace($search, $replace, $template);
		}
		
		$search = array(
				'[[actualWeight]]',
				'[[articleDescription]]',
				'[[height]]',
				'[[length]]',
				'[[width]]',
				'[[isTransitCoverRequired]]',
				'[[transitCoverAmount]]',
				'[[articleNumber]]'
		);

		if($data['articles_type'] == 'Custom')
		{
			$article = $data['article'];
		}
		else
		{
			$articles_type = $data['articles_type'];
			$articles = explode('<=>',$articles_type);
			
			$article = array();
			$article['description'] = $articles[0];
			$article['weight'] = $articles[1];
			$article['height'] = $articles[2];
			$article['length'] = $articles[3];
			$article['width'] = $articles[4];
			
			$use_order_total_weight = (int)get_option('linksynceparcel_use_order_weight');;
			if($use_order_total_weight == 1)
			{
				$weight = LinksynceparcelHelper::getOrderWeight($order);
				$weightPerArticle = LinksynceparcelHelper::getAllowedWeightPerArticle();
				if($weight == 0)
				{
					$default_article_weight = get_option('linksynceparcel_default_article_width');
					if($default_article_weight)
					{
						$weight = $default_article_weight;
					}
				}
				if($weight > $weightPerArticle)
				{
					$weight = $weightPerArticle;
				}
				$article['weight'] = $weight;
			}
		}
		
		$article['weight'] = number_format($article['weight'],2,'.', '');
		$total_weight += $article['weight'];
	
		$replace = array(
			trim($article['weight']),
			self::xmlData(trim($article['description'])),
			trim($article['height']),
			trim($article['length']),
			'<width>'.trim($article['width']).'</width>',
			($data['transit_cover_required'] ? 'Y' : 'N'),
			($data['transit_cover_required'] ? trim($data['transit_cover_amount']) : 0),
			''
		);
		
		$template = file_get_contents(linksynceparcel_DIR.'assets/xml/article-template.xml');
		$articlesInfo .= str_replace($search, $replace, $template);
		
		return array('info' => $articlesInfo, 'total_weight' => $total_weight);
	}
	
	public static function prepareUpdateArticle($data,$order,$consignmentNumber)
	{
		$articleData = self::prepareUpdateArticleData($data,$order,$consignmentNumber);
		$content = $articleData['content'];
		$chargeCode = $articleData['charge_code'];
		return array('content' => $content, 'charge_code' => $chargeCode);
	}
	
	public static function prepareUpdateArticleData($data, $order,$consignment_number='')
	{
		$address = get_post_meta($order->id);
		$returnAddress = self::prepareReturnAddress();
		$deliveryInfo = self::prepareDeliveryAddress($address,$order);
		$articlesInfo = self::prepareUpdatedArticles($order,$data);
		
		$search = array(
			'[[articles]]',
			'[[RETURN-ADDRESS]]',
			'[[DELIVERY-ADDRESS]]',
			'[[CUSTOMER-EMAIL]]',
			'[[DELIVERY-SIGNATURE]]',
			'[[ORDER-ID]]',
			'[[CHARGE-CODE]]',
			'[[SHIPMENT-ID]]',
			'[[DANGER-GOODS]]',
			'[[printReturnLabels]]',
			'[[deliverPartConsignment]]',
			'[[cashToCollect]]',
  			'[[cashToCollectAmount]]',
			'[[emailNotification]]'
		);

		$chargeCode = self::getChargeCode($order,$consignment_number);
		
		$replace = array(
			$articlesInfo['info'],
			$returnAddress,
			$deliveryInfo,
			$address['_billing_email'][0],
			($data['delivery_signature_allowed'] ? 'true' : 'false'),
			self::getIncrementId($order),
			$chargeCode,
			$order->id,
			($data['contains_dangerous_goods'] ? 'true' : 'false'),
			($data['print_return_labels'] ? 'true' : 'false'),
			($data['partial_delivery_allowed'] ? 'Y' : 'N'),
			(isset($data['cash_to_collect']) ? '<cashToCollect>Y</cashToCollect>' : '<cashToCollect>N</cashToCollect>'),
			(isset($data['cash_to_collect']) ? '<cashToCollectAmount>'.number_format($data['cash_to_collect'],2).'</cashToCollectAmount>' : ''),
			($data['email_notification'] ? 'Y' : 'N')
		);
		$template = file_get_contents(linksynceparcel_DIR.'assets/xml/articles-template.xml');
		$content = str_replace($search, $replace, $template);
		return array('content' => $content, 'charge_code' => $chargeCode, 'total_weight' => $articlesInfo['total_weight']);
	}
	
	public static function prepareUpdatedArticles($order,$data)
	{
		$articlesInfo = '';
		$total_weight = 0;
		$articles = LinksynceparcelHelper::getArticles($order->id, $data['consignment_number']);
		foreach($articles as $article)
		{
			$search = array(
				'[[actualWeight]]',
				'[[articleDescription]]',
				'[[height]]',
				'[[length]]',
				'[[width]]',
				'[[isTransitCoverRequired]]',
				'[[transitCoverAmount]]',
				'[[articleNumber]]'
			);
		
			if($article->article_number == $data['article_number'])
			{
				$article = $data['article'];
				$article['weight'] = number_format($article['weight'],2,'.', '');
				$total_weight += $article['weight'];
				$replace = array(
					trim($article['weight']),
					self::xmlData(trim($article['description'])),
					trim($article['height']),
					trim($article['length']),
					'<width>'.trim($article['width']).'</width>',
					($data['transit_cover_required'] ? 'Y' : 'N'),
					($data['transit_cover_required'] ? trim($data['transit_cover_amount']) : 0),
				''
				);
			}
			else
			{
				$default_width = 0;
				$use_article_dimensions = (int)get_option('linksynceparcel_use_dimension');
				if($use_article_dimensions == 1)
				{
					$default_width = get_option('linksynceparcel__default_article_width');
				}
				
				$total_weight += $article->actual_weight;
				
				$replace = array(
					trim($article->actual_weight),
					trim($article->article_description),
					trim($article->height),
					trim($article->length),
					'<width>'.trim($article->width).'</width>',
					$article->is_transit_cover_required,
					($article->transit_cover_amount ? trim($article->transit_cover_amount) : 0),
					'<articleNumber>'.trim($article->article_number).'</articleNumber>'
				);
			}
			
			$template = file_get_contents(linksynceparcel_DIR.'assets/xml/article-template.xml');
			$articlesInfo .= str_replace($search, $replace, $template);
		}
		return array('info' => $articlesInfo, 'total_weight' => $total_weight);
	}
	public static function getManifestNumber()
	{
		try
		{
			$manifestNumber = false;
			$manifests = LinksynceparcelApi::getManifest();
			$xml = simplexml_load_string($manifests);
			LinksynceparcelHelper::log('manifest xml: '.preg_replace('/\s+/', ' ', trim($manifests)));
			$currentManifest = '';
			if($xml)
			{
				foreach($xml->manifest as $manifest)
				{
					$manifestNumber = $manifest->manifestNumber;
					if(empty($currentManifest))
					{
						$currentManifest = $manifestNumber;
					}
					
					$numberOfArticles = (int)$manifest->numberOfArticles;
					$numberOfConsignments = (int)$manifest->numberOfConsignments;
					LinksynceparcelHelper::updateManifest($manifestNumber,$numberOfArticles,$numberOfConsignments);
				}
				
				update_option('linksynceparcel_manifest_sync',0);
			}
			return $currentManifest;
		}
		catch(Exception $e)
		{
			LinksynceparcelHelper::log('getManifestNumber: '.$e->getMessage());
			return false;
		}
	}
	
	public static function updateManifestTable($manifestNumber,$columnName, $value)
	{
		global $wpdb;
		$table_name = $wpdb->prefix . "linksynceparcel_manifest";
		$query = "UPDATE {$table_name} SET {$columnName} = '{$value}' WHERE manifest_number='{$manifestNumber}'";
		$wpdb->query($query);
	}
	
	public static function isCurrentMainfestHasConsignmentsForDespatch()
	{
		global $wpdb;
	    $table_name = $wpdb->prefix . "linksynceparcel_consignment";
		$query = "SELECT order_id FROM {$table_name} WHERE despatched=0 and is_next_manifest=1";
		$result = $wpdb->get_results($query);
		if(count($result) > 0)
			return true;
		return false;
	}
	
	public static function addMessage($key,$message)
	{
		$existingMessage = get_option($key);
		if(!empty($existingMessage))
			$existingMessage .= '<br/>';
		update_option($key,$existingMessage.$message);
	}
	
	public static function getNotDespatchedConsignmentNumbers()
	{
		$notDespatchedConsignmentsArray = array();
		$notDespatchedConsignments = LinksynceparcelApi::getNotDespatchedConsignments();
		if(is_object($notDespatchedConsignments ))
		{
			$notDespatchedConsignmentsArray[] = $notDespatchedConsignments->consignmentNumber;
		}
		else
		{
			foreach($notDespatchedConsignments as $consignment)
			{
				$notDespatchedConsignmentsArray[] = $consignment->consignmentNumber;
			}
		}
		return $notDespatchedConsignmentsArray;
	}
	
	public static function getNotDespatchedAssignedConsignmentNumbers()
	{
		$notDespatchedConsignmentsArray = array();
		$notDespatchedConsignments = LinksynceparcelApi::getNotDespatchedConsignments();
		if(is_object($notDespatchedConsignments ))
		{
			if($notDespatchedConsignments->status == 'Assigned')
			{
				$notDespatchedConsignmentsArray[] = $notDespatchedConsignments->consignmentNumber;
			}
		}
		else
		{
			foreach($notDespatchedConsignments as $consignment)
			{
				if($consignment->status == 'Assigned')
				{
					$notDespatchedConsignmentsArray[] = $consignment->consignmentNumber;
				}
			}
		}
		return $notDespatchedConsignmentsArray;
	}
	
	public static function getNotDespatchedUnassignedConsignmentNumbers()
	{
		$notDespatchedConsignmentsArray = array();
		$notDespatchedConsignments = LinksynceparcelApi::getNotDespatchedConsignments();
		if(is_object($notDespatchedConsignments ))
		{
			if($notDespatchedConsignments->status == 'UnAssigned')
			{
				$notDespatchedConsignmentsArray[] = $notDespatchedConsignments->consignmentNumber;
			}
		}
		else
		{
			foreach($notDespatchedConsignments as $consignment)
			{
				if($consignment->status == 'UnAssigned')
				{
					$notDespatchedConsignmentsArray[] = $consignment->consignmentNumber;
				}
			}
		}
		return $notDespatchedConsignmentsArray;
	}
	
	public static function getNotDespatchedBothConsignmentNumbers()
	{
		$notDespatchedConsignmentsArray = array();
		$notDespatchedConsignments = LinksynceparcelApi::getNotDespatchedConsignments();
		foreach($notDespatchedConsignments as $consignment)
		{
			$notDespatchedConsignmentsArray[$consignment->status][] = $consignment->consignmentNumber;
		}
		return $notDespatchedConsignmentsArray;
	}

	public static function getConsignmentsByNumber($manifestNumber)
	{
		global $wpdb;
	    $table_name = $wpdb->prefix . "linksynceparcel_consignment";
		$query = "SELECT * FROM {$table_name} WHERE manifest_number = '{$manifestNumber}'";
		return $wpdb->get_results($query);
	}
	
	public static function notifyCustomers($manifestNumber)
	{
		$consignments = self::getConsignmentsByNumber($manifestNumber);
		foreach($consignments as $consignment)
		{
			if($consignment->notify_customers)
			{
				$address = get_post_meta($consignment->order_id);
				$toEmail = $address['_billing_email'][0];
				$toName = $address['_billing_first_name'][0].' '.$address['_billing_last_name'][0];
				$fromEmail  = get_option('linksynceparcel_from_email_address');
				$fromName = get_bloginfo('name');
				$subject  = get_option('linksynceparcel_subject');
				$siteUrl = get_bloginfo('url');

				$content  = get_option('linksynceparcel_email_body');
				$content = str_replace('[TrackingNumber]',$consignment->consignment_number,$content);
				
				$order = new WC_Order($consignment->order_id);
				
				$search = array(
					'[TrackingNumber]',
					'[OrderNumber]',
					'[CustomerFirstname]'
				);
		
				$replace = array(
					$consignment->consignment_number,
					self::getIncrementId($order),
					$address['_billing_first_name'][0]
				);
				
				$subject = str_replace($search, $replace, $subject);
				$content = str_replace($search, $replace, $content);
				
				$headers = 'From: '.$fromName;
				if(!empty($fromEmail))
				{
					$headers .= ' <'.$fromEmail.'>';
				}
			
				add_filter( 'wp_mail_content_type', 'linksyneparcel_set_html_content_type' );
				wp_mail($toEmail, $subject, $content,$headers);
				remove_filter( 'wp_mail_content_type', 'linksyneparcel_set_html_content_type' );
			}
		}
	}
	
	public static function getOrderWeight($order)
	{
		$weight = 0;
		if ( sizeof( $order->get_items() ) > 0 )
		{
			foreach( $order->get_items() as $item ) 
			{
				if ( $item['product_id'] > 0 ) 
				{
					$_product = $order->get_product_from_item( $item );
					if ( ! $_product->is_virtual() )
					{
						$weight += $_product->get_weight() * $item['qty'];
					}
				}
			}
		}
		
		$product_unit = trim(get_option('linksynceparcel_product_unit'));
		
		$packaging_allowance_type = trim(get_option('linksynceparcel_allowance_type'));
		$packaging_allowance_value = trim(get_option('linksynceparcel_allowance_value'));
		$product_unit = strtolower(trim(get_option('woocommerce_weight_unit')));
		
		if($weight > 0)
		{   
			if($product_unit == 'g')
			{
				$weight = $weight / 1000;
				$weight = number_format($weight,2,'.', '');
			}
			else if($product_unit == 'lbs')
			{
				$weight = $weight * 0.453592;
				$weight = number_format($weight,2,'.', '');
			}
			else if($product_unit == 'oz')
			{
				$weight = $weight * 0.0283495;
				$weight = number_format($weight,2,'.', '');
			}
		}
		
		if($packaging_allowance_value > 0)
		{
			if($packaging_allowance_type == 'F')
			{
				$weight += $packaging_allowance_value;
			}
			else
			{
				$weight += ($weight * ($packaging_allowance_value/100));
			}
		}
		return $weight;//number_format($weight,2);
	}
	
	public static function getAllowedWeightPerArticle()
	{
		return 22;
	}
	
	public static function presetMatch($presets,$weight)
	{
		$selected = false;
		if($presets && count($presets) > 0)
		{
			foreach($presets as $preset)
			{
				$presetWeight = floatval($preset->weight);
				$weight = floatval($weight);
				
				$presetWeight = ''.$presetWeight.'';
				$weight = ''.$weight.'';
			
				if($presetWeight === $weight)
				{
					$selected = true;
					break;
				}
			}
		}
		return $selected;
	}
	
	public static function getOrderedShippingDescriptions()
	{
		global $wpdb;
	    $table_name = $wpdb->prefix . "woocommerce_order_items";
		$query = "SELECT distinct(order_item_name) as code FROM {$table_name} WHERE order_item_type = 'shipping'";
		return $wpdb->get_results($query);
	}
}

function linksyneparcel_set_html_content_type()
{
	return 'text/html';
}

$is_greater_than_21 = LinksynceparcelHelper::woocommerce_version_check(LinksynceparcelHelper::wpbo_get_woo_version_number());
?>