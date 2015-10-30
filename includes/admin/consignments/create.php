<?php
class LinksynceparcelAdminConsignmentsCreate
{
	public static function save()
	{
		$order_id = (int)($_POST['post_ID']);
		$number_of_articles = (int)trim($_POST['number_of_articles']);
		$shipping_country = $_POST['_shipping_country'];
		$data = $_POST;
		$order = new WC_Order( $order_id );
		$tempCanConsignments = (int)($number_of_articles/20);
		$canConsignments = $tempCanConsignments;
		$remainArticles = $number_of_articles % 20;
		if( $remainArticles > 0)
		{
			$canConsignments++;
		}
		
		for($i=0;$i<$canConsignments;$i++)
		{
			$data['start_index'] = ($i * 20 ) + 1;
			if( ($i+1) <= $tempCanConsignments)
			{
				$data['end_index'] = ($i * 20 ) + 20;
			}
			else
			{
				$data['end_index'] = ($i * 20 ) + $remainArticles;
			}
			
			try
			{
				$articleData = LinksynceparcelHelper::prepareArticleData($data, $order, '', $shipping_country);
				$content = $articleData['content'];
				$chargeCode = $articleData['charge_code'];
				$consignmentData = LinksynceparcelApi::createConsignment($content, 0, $chargeCode);
				$total_weight = $articleData['total_weight'];
				if($consignmentData)
				{
					$consignmentNumber = $consignmentData->consignmentNumber;
					$manifestNumber = $consignmentData->manifestNumber;
					LinksynceparcelHelper::insertConsignment($order_id,$consignmentNumber,$data,$manifestNumber,$chargeCode,$total_weight);
					LinksynceparcelHelper::updateArticles($order_id,$consignmentNumber,$consignmentData->articles,$data,$content);
					LinksynceparcelHelper::insertManifest($manifestNumber);
			
					LinksynceparcelHelper::labelCreate($consignmentNumber);
					if($data['print_return_labels'])
					{
						LinksynceparcelHelper::returnLabelCreate($consignmentNumber);
					}
					update_option('linksynceparcel_order_view_success','The consignment has been created successfully.');
				}
				else
				{
					throw new Exception("createConsignment returned empty result");
				}
			}
			catch(Exception $e)
			{
				$error = 'Cannot create consignment, Error: '.$e->getMessage();
				update_option('linksynceparcel_order_view_error',$error);
				LinksynceparcelHelper::log($error);
			}
		}
	}
	
	public static function saveOrderWeight()
	{
		$order_id = (int)($_POST['post_ID']);
		$number_of_articles = (int)trim($_POST['number_of_articles']);
		$shipping_country = $_POST['_shipping_country'];
		$data = $_POST;
		$order = new WC_Order( $order_id );
		$tempCanConsignments = (int)($number_of_articles/20);
		$canConsignments = $tempCanConsignments;
		$remainArticles = $number_of_articles % 20;
		if( $remainArticles > 0)
		{
			$canConsignments++;
		}
		
		for($i=0;$i<$canConsignments;$i++)
		{
			$data['start_index'] = ($i * 20 ) + 1;
			if( ($i+1) <= $tempCanConsignments)
			{
				$data['end_index'] = ($i * 20 ) + 20;
			}
			else
			{
				$data['end_index'] = ($i * 20 ) + $remainArticles;
			}
			
			try
			{
				$articleData = LinksynceparcelHelper::prepareOrderWeightArticleData($data, $order);
				$content = $articleData['content'];
				$chargeCode = $articleData['charge_code'];
				$total_weight = $articleData['total_weight'];
				$consignmentData = LinksynceparcelApi::createConsignment($content, 0, $chargeCode);

				if($consignmentData)
				{
					$consignmentNumber = $consignmentData->consignmentNumber;
					$manifestNumber = $consignmentData->manifestNumber;
					LinksynceparcelHelper::insertConsignment($order_id,$consignmentNumber,$data,$manifestNumber,$chargeCode,$total_weight);
					LinksynceparcelHelper::updateArticles($order_id,$consignmentNumber,$consignmentData->articles,$data,$content);
					LinksynceparcelHelper::insertManifest($manifestNumber);
			
					LinksynceparcelHelper::labelCreate($consignmentNumber);
					if($data['print_return_labels'])
					{
						LinksynceparcelHelper::returnLabelCreate($consignmentNumber);
					}
					update_option('linksynceparcel_order_view_success','The consignment has been created successfully.');
				}
				else
				{
					throw new Exception("createConsignment returned empty result");
				}
			}
			catch(Exception $e)
			{
				$error = 'Cannot create consignment, Error: '.$e->getMessage();
				update_option('linksynceparcel_order_view_error',$error);
				LinksynceparcelHelper::log($error);
			}
		}
	}
	
	public static function saveDefaultWeight()
	{
		$order_id = (int)($_POST['post_ID']);
		$number_of_articles = (int)trim($_POST['number_of_articles']);
		$shipping_country = $_POST['_shipping_country'];
		$data = $_POST;
		$order = new WC_Order( $order_id );
		$tempCanConsignments = (int)($number_of_articles/20);
		$canConsignments = $tempCanConsignments;
		$remainArticles = $number_of_articles % 20;
		if( $remainArticles > 0)
		{
			$canConsignments++;
		}
		
		for($i=0;$i<$canConsignments;$i++)
		{
			$data['start_index'] = ($i * 20 ) + 1;
			if( ($i+1) <= $tempCanConsignments)
			{
				$data['end_index'] = ($i * 20 ) + 20;
			}
			else
			{
				$data['end_index'] = ($i * 20 ) + $remainArticles;
			}
			
			try
			{
				$articleData = LinksynceparcelHelper::prepareOrderWeightArticleData($data, $order);
				$content = $articleData['content'];
				$chargeCode = $articleData['charge_code'];
				$total_weight = $articleData['total_weight'];
				$consignmentData = LinksynceparcelApi::createConsignment($content, 0, $chargeCode);

				if($consignmentData)
				{
					$consignmentNumber = $consignmentData->consignmentNumber;
					$manifestNumber = $consignmentData->manifestNumber;
					LinksynceparcelHelper::insertConsignment($order_id,$consignmentNumber,$data,$manifestNumber,$chargeCode,$total_weight);
					LinksynceparcelHelper::updateArticles($order_id,$consignmentNumber,$consignmentData->articles,$data,$content);
					LinksynceparcelHelper::insertManifest($manifestNumber);
			
					LinksynceparcelHelper::labelCreate($consignmentNumber);
					if($data['print_return_labels'])
					{
						LinksynceparcelHelper::returnLabelCreate($consignmentNumber);
					}
					update_option('linksynceparcel_order_view_success','The consignment has been created successfully.');
				}
				else
				{
					throw new Exception("createConsignment returned empty result");
				}
			}
			catch(Exception $e)
			{
				$error = 'Cannot create consignment, Error: '.$e->getMessage();
				update_option('linksynceparcel_order_view_error',$error);
				LinksynceparcelHelper::log($error);
			}
		}
	}
}
?>