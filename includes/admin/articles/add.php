<?php
class LinksynceparcelAdminArticlesAdd
{
	public static function save()
	{
		$order_id = (int)($_REQUEST['order_id']);
		$number_of_articles = (int)trim($_POST['number_of_articles']);
		$data = $_POST;
		$order = new WC_Order( $order_id );
		$consignmentNumber = trim($_GET['consignment_number']);
		try
		{
			$articleData = LinksynceparcelHelper::prepareAddArticleData($data, $order, $consignmentNumber);
			$content = $articleData['content'];
			$chargeCode = $articleData['charge_code'];
			$total_weight = $articleData['total_weight'];
			$consignmentData = LinksynceparcelApi::modifyConsignment($content, $consignmentNumber);
			if($consignmentData)
			{
				$consignmentNumber = $consignmentData->consignmentNumber;
				$manifestNumber = $consignmentData->manifestNumber;
				LinksynceparcelHelper::updateConsignment($order_id,$consignmentNumber,$data,$manifestNumber,$chargeCode,$total_weight);
				LinksynceparcelHelper::updateArticles($order_id,$consignmentNumber,$consignmentData->articles,$data,$content);
				LinksynceparcelHelper::insertManifest($manifestNumber);
		
				LinksynceparcelHelper::labelCreate($consignmentNumber);
				if($data['print_return_labels'])
				{
					LinksynceparcelHelper::returnLabelCreate($consignmentNumber);
				}
				update_option('linksynceparcel_order_view_success','The article has been added successfully.');
				wp_redirect(admin_url('post.php?post='.$order_id.'&action=edit'));
			}
			else
			{
				throw new Exception("modifyConsignment returned empty result");
			}
		}
		catch(Exception $e)
		{
			$error = 'Failed to add article, Error: '.$e->getMessage();
			update_option('linksynceparcel_order_view_error',$error);
			LinksynceparcelHelper::log($error);
			wp_redirect(admin_url('post.php?post='.$order_id.'&action=edit'));
		}
	}
	
	public static function output()
	{
		global $is_greater_than_21;
		
		require_once(linksynceparcel_DIR.'model/ArticlePreset/Model.php' );
		$articlePreset = new ArticlePreset();
		$presets = $articlePreset->get_by(array('status' => 1));
		$order_id = (int)($_GET['order_id']);
		$order = new WC_Order( $order_id );
		if($is_greater_than_21)
		{
			$order_status = substr($order->post_status,3);
		}
		else
		{
			$order_status = $order->status;
		}
		$consignmentNumber = trim($_GET['consignment_number']);
		$consignment = LinksynceparcelHelper::getConsignment($consignmentNumber);
		include_once(linksynceparcel_DIR.'views/admin/consignments/add_article.php');
	}
}
?>