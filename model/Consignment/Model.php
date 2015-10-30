<?php
require_once(linksynceparcel_DIR.'model/Model.php' );
class Consignment extends LinksynceparcelModel
{
	public function __construct()
    {
		global $wpdb;
        parent::__construct($wpdb->prefix . 'linksynceparcel_consignment');
    }
}
?>