<?php
class LinksynceparcelAdminAssignShippingTypes
{
	public static function output()
	{
		$action = (isset($_GET['action']) ? $_GET['action'] : '');
		if($action == 'add')
		{
			if (isset($_POST['save'])) 
			{
				$data = $_POST['linksynceparcel'];
				if($data['shipping_type'] == 'desc')
				{
					$data['method'] = $data['method2'];
				}
				unset($data['method2']);
									
				$errors = LinksynceparcelValidator::validateAssignShippingTypes($data);
				if($errors)
				{
					$error = implode('<br/>',$errors);
				}
				else
				{
					try
					{
						require_once(linksynceparcel_DIR.'model/AssignShippingType/Model.php' );
						$assignShippingType = new AssignShippingType();
						$type = $assignShippingType->get_by(array('method' => $data['method']));
						if($type)
						{
							throw new Exception("For this shipping method, charge code already assigned.");
						}
						else
						{
							$assignShippingType->insert($data);
						}
						unset($_POST);
						unset($_REQUEST);
						$result = __( 'Charge code has been assigned to shipping type successfully.', 'linksynceparcel' );
					}
					catch(Exception $e)
					{
						$error = $e->getMessage();
					}
				}
			}
			$chargeCodes = LinksynceparcelHelper::getChargeCodeValues(true);
			$methods = WC()->shipping->load_shipping_methods();
			$shipping_titles = LinksynceparcelHelper::getOrderedShippingDescriptions();
			include_once(linksynceparcel_DIR.'views/admin/assign_shipping_type/add.php');
		}
		else if($action == 'edit')
		{
			$id = $_REQUEST['id'];
			require_once(linksynceparcel_DIR.'model/AssignShippingType/Model.php' );
			$assignShippingType = new AssignShippingType();
			
			if (isset($_POST['save'])) 
			{
				$data = $_POST['linksynceparcel'];
				if($data['shipping_type'] == 'desc')
				{
					$data['method'] = $data['method2'];
				}
				unset($data['method2']);
				$errors = LinksynceparcelValidator::validateAssignShippingTypes($data);
				if($errors)
				{
					$error = implode('<br/>',$errors);
				}
				else
				{
					try
					{
						$type = $assignShippingType->get_by(array('id' => $id));
						$type = $type[0];
						if($type->method != $data['method'])
						{
							$type = $assignShippingType->get_by(array('method' => $data['method']));
							if($type)
							{
								throw new Exception("For this shipping method, charge code already assigned.");
							}
							else
							{
								$assignShippingType->update($data, array('id' => $id));
							}
						}
						else
						{
							$assignShippingType->update($data, array('id' => $id));
						}
						$result = __( 'Updated successfully.', 'linksynceparcel' );
					}
					catch(Exception $e)
					{
						$error = $e->getMessage();
					}
				}
			}
			$type = $assignShippingType->get_by(array('id' => $id));
			$type = $type[0];
			$chargeCodes = LinksynceparcelHelper::getChargeCodeValues(true);
			$methods = WC()->shipping->load_shipping_methods();
			$shipping_titles = LinksynceparcelHelper::getOrderedShippingDescriptions();
			include_once(linksynceparcel_DIR.'views/admin/assign_shipping_type/edit.php');
		}
		else
		{
			if($action == 'delete')
			{
				$id = $_REQUEST['id'];
				require_once(linksynceparcel_DIR.'model/AssignShippingType/Model.php' );
				$assignShippingType = new AssignShippingType();
				
				try
				{
					$assignShippingType->delete(array('id' => $id));
					$result = __( 'An item has been deleted successfully.', 'linksynceparcel' );
				}
				catch(Exception $e)
				{
					$error = $e->getMessage();
				}
			}
			if( (isset($_REQUEST['action2']) || isset($_POST['action']) ) && ($_REQUEST['action2'] == 'delete' || $_POST['action'] == 'delete') )
			{
				$ids = $_REQUEST['assignshippingtype'];
				require_once(linksynceparcel_DIR.'model/AssignShippingType/Model.php' );
				$assignShippingType = new AssignShippingType();
				
				try
				{
					foreach($ids as $id)
					{
						$assignShippingType->delete(array('id' => $id));
					}
					$result = __( 'Item(s) have been deleted successfully.', 'linksynceparcel' );
				}
				catch(Exception $e)
				{
					$error = $e->getMessage();
				}
			}
			
			include_once(linksynceparcel_DIR.'model/AssignShippingType/List.php');
			include_once(linksynceparcel_DIR.'views/admin/assign_shipping_type/list.php');
		}
	}
}
?>