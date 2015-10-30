<?php
class LinksynceparcelAdminConsignmentsDelete
{
	public static function save()
	{
		$order_id = (int)($_GET['order_id']);
		$consignmentNumber = trim($_GET['consignment_number']);
		$consignment = LinksynceparcelHelper::getConsignment($consignmentNumber);
		try
		{
			$ok = LinksynceparcelApi::deleteConsignment($consignmentNumber);
			if($ok)
			{
				$filename = $consignmentNumber.'.pdf';
				$filepath = linksynceparcel_DIR.'assets/label/consignment/'.$filename;
				if(file_exists($filepath))
				{
					unlink($filepath);
				}
				
				$filepath2 = linksynceparcel_DIR.'assets/label/returnlabels'.$filename;
				if(file_exists($filepath2))
				{
					unlink($filepath2);
				}
				LinksynceparcelHelper::deleteConsignment($consignmentNumber);
				LinksynceparcelHelper::deleteManifest2($consignment->manifest_number);

				update_option('linksynceparcel_order_view_success','Consignment #'.$consignmentNumber.' has been deleted successfully.');
				wp_redirect(admin_url('post.php?post='.$order_id.'&action=edit'));
			}
		}
		catch(Exception $e)
		{
			$error = 'Could not delete consignment. Error: '.$e->getMessage();
			update_option('linksynceparcel_order_view_error',$error);
			LinksynceparcelHelper::log($error);
			LinksynceparcelHelper::deleteManifest2($consignment->manifest_number);
            wp_redirect(admin_url('post.php?post='.$order_id.'&action=edit'));
		}
	}
}
?>