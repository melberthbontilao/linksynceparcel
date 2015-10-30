<?php
class LinksynceparcelAdminArticlesEdit
{
	public static function save()
	{
		$order_id = (int)($_REQUEST['order_id']);
		$data = $_REQUEST;
		$order = new WC_Order( $order_id );
		$consignmentNumber = trim($_GET['consignment_number']);
		$articleNumber = trim($_GET['article_number']);
		try
		{
			$articleData = LinksynceparcelHelper::prepareUpdateArticleData($data, $order, $consignmentNumber);
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
				update_option('linksynceparcel_order_view_success','The article and consignment has been updated successfully.');
				wp_redirect(admin_url('post.php?post='.$order_id.'&action=edit'));
			}
			else
			{
				throw new Exception("modifyConsignment returned empty result");
			}
		}
		catch(Exception $e)
		{
			$error = 'Failed to update article and consignment, Error: '.$e->getMessage();
			update_option('linksynceparcel_order_view_error',$error);
			LinksynceparcelHelper::log($error);
			wp_redirect(admin_url('post.php?post='.$order_id.'&action=edit'));
		}
	}
	
	public static function output()
	{
		$order_id = (int)($_GET['order_id']);
		$order = new WC_Order( $order_id );
		$consignmentNumber = trim($_GET['consignment_number']);
		$articleNumber = trim($_GET['article_number']);
		$consignment = LinksynceparcelHelper::getConsignment($consignmentNumber);
		$article = LinksynceparcelHelper::getArticle($articleNumber);
		include_once(linksynceparcel_DIR.'views/admin/consignments/edit_article.php');
	}
}
?>