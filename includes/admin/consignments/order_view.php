<?php
class LinksynceparcelAdminConsignmentsOrderView
{
	public static function output()
	{
		global $is_greater_than_21;
		require_once(linksynceparcel_DIR.'model/ArticlePreset/Model.php' );
		$articlePreset = new ArticlePreset();
		$presets = $articlePreset->get_by(array('status' => 1));
		$order_id = (int)($_GET['post']);
		$order = new WC_Order( $order_id );
		
		if($is_greater_than_21)
		{
			$order_status = substr($order->post_status,3);
		}
		else
		{
			$order_status = $order->status;
		}
		
		$use_order_weight = (int)get_option('linksynceparcel_use_order_weight');
		$use_dimension = (int)get_option('linksynceparcel_use_dimension');
		if($use_order_weight == 1 && $use_dimension != 1)
		{
			include_once(linksynceparcel_DIR.'views/admin/consignments/order_weight_view.php');
		}
		elseif($use_order_weight != 1 && $use_dimension != 1)
		{
			include_once(linksynceparcel_DIR.'views/admin/consignments/default_weight_view.php');
		}
		elseif($use_order_weight == 1 && $use_dimension == 1)
		{
			include_once(linksynceparcel_DIR.'views/admin/consignments/order_weight_articles_view.php');
		}
		else
		{
        	include_once(linksynceparcel_DIR.'views/admin/consignments/view.php');
		}
	}
}
?>