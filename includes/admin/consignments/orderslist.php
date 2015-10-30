<?php
class LinksynceparcelAdminConsignmentsOrdersList
{
	public static function output()
	{
		global $is_greater_than_21;
		include_once(linksynceparcel_DIR.'model/Consignment/OrdersList.php');
		$pluginMessage = self::in_plugin_update_message();
		include_once(linksynceparcel_DIR.'views/admin/consignments/orderslist.php');
	}
	
	public static function in_plugin_update_message()
	{
		$pluginMessage =  '';
		$currentTime = time();
		$lastCheckedTime = get_option('linksynceparcel_last_version_check_time');
		$version = get_option('linksynceparcel_version');
		$notsame = get_option('linksynceparcel_notsame');
		
		if(!$lastCheckedTime)
		{
			$lastCheckedTime = 0;
		}
		$elapsedTime = $currentTime - $lastCheckedTime;
		$elapsedDay = (int)($elapsedTime/86400);
		if($elapsedDay > 0)
		{
			$currentVersion = '0.3.3';
			$result = LinksynceparcelApi::getVersionNumber();
			if($result)
			{
				$latestVersion = isset($result->version_number) ? $result->version_number : '0.0.7';
				update_option('linksynceparcel_last_version_check_time',$currentTime);
				update_option('linksynceparcel_version',$latestVersion);
				update_option('linksynceparcel_notsame',0);
				if( intval(str_replace('.','',$currentVersion)) < intval(str_replace('.','',$latestVersion)) )
				{
					update_option('linksynceparcel_notsame',1);
					$pluginMessage = '<div class="updated" style="border-left: 4px solid rgb(255, 192, 58);">';
					$pluginMessage .= '<p>linksync eParcel '.$latestVersion.' is available! <a href="http://www.linksync.com/help/releases-eparcel-woocommerce" target="_blank">Please update now.</a></p>';
					$pluginMessage .= '</div>';
				}
			}
		}
		else if($notsame == 1)
		{
			$pluginMessage = '<div class="updated" style="border-left: 4px solid rgb(255, 192, 58);">';
			$pluginMessage .= '<p>linksync eParcel '.$version.' is available! <a href="http://www.linksync.com/help/releases-eparcel-woocommerce" target="_blank">Please update now.</a></p>';
			$pluginMessage .= '</div>';
		}
		
		return $pluginMessage;
	}
	
	public static function massCreateConsignment()
	{
		$ids = $_REQUEST['order'];
		if(is_array($ids))
		{
			require_once(linksynceparcel_DIR.'model/ArticlePreset/Model.php' );
			$articlePreset = new ArticlePreset();
			$presets = $articlePreset->get_by(array('status' => 1));
			$orders = $ids;
			$consignment = false;
			include_once(linksynceparcel_DIR.'views/admin/consignments/create_mass.php');
		}
		else
		{
			LinksynceparcelHelper::addMessage('linksynceparcel_consignment_error','Please select item(s)');
			wp_redirect(admin_url('admin.php?page=linksynceparcel'));
		}
	}
	
	public static function massAssignConsignment()
	{
		$ids = $_REQUEST['order'];
		try 
		{
			if(is_array($ids))
			{
				$success = 0;
				$consignmentNumbers = array();
                foreach ($ids as $id) 
				{
					$values = explode('_',$id);
					$orderId = (int)($values[0]);
					$consignmentNumber = $values[1];
					$incrementId = $orderId;
					if($consignmentNumber == '0')
					{
						$error = sprintf('Order #%s: does not have consignment', $incrementId);
						LinksynceparcelHelper::addMessage('linksynceparcel_consignment_error',$error);
					}
					else
					{
						try 
						{
							$status = LinksynceparcelApi::assignConsignmentToManifest($consignmentNumber);
							$status = trim(strtolower($status));
							if($status == 'ok')
							{
								$success++;
								$consignmentNumbers[] = $consignmentNumber;
								LinksynceparcelHelper::updateConsignmentTable($consignmentNumber,'is_next_manifest', 1);
								$successmsg = sprintf('Consignment #%s: successfully assigned', $consignmentNumber);
								LinksynceparcelHelper::addMessage('linksynceparcel_consignment_success',$successmsg);
							}
							else
							{
								$error = sprintf('Consignment #%s: failed to assign', $consignmentNumber);
								LinksynceparcelHelper::addMessage('linksynceparcel_consignment_error',$error);
							}
						}
						catch (Exception $e) 
						{
							$error = sprintf('Consignment #%s, Error: %s', $consignmentNumber, $e->getMessage());
							$error = sprintf('Consignment #%s: failed to assign', $consignmentNumber);
							LinksynceparcelHelper::addMessage('linksynceparcel_consignment_error',$error);
						}
					}
					
                }
				
				if($orderId > 0 && $success > 0)
				{
					$manifestNumber = LinksynceparcelHelper::getManifestNumber();
					if($manifestNumber)
					{
						foreach($consignmentNumbers as $consignmentNumber)
						{
							LinksynceparcelHelper::updateConsignmentTable($consignmentNumber,'manifest_number', $manifestNumber);
						}
					}
				}
			}
			else
			{
				throw new Exception("Please select items");
			}
		} 
		catch (Exception $e) 
		{
			LinksynceparcelHelper::addMessage('linksynceparcel_consignment_error',$e->getMessage());
		}
		wp_redirect(admin_url('admin.php?page=linksynceparcel'));
	}
	
	public static function massUnassignConsignment()
	{
		$ids = $_REQUEST['order'];
		try 
		{
			if(is_array($ids))
			{
				$success = 0;
                foreach ($ids as $id) 
				{
					$values = explode('_',$id);
					$orderId = (int)($values[0]);
					$consignmentNumber = $values[1];
					$incrementId = $orderId;
					if($consignmentNumber == '0')
					{
						$error = sprintf('Order #%s: does not have consignment', $incrementId);
						LinksynceparcelHelper::addMessage('linksynceparcel_consignment_error',$error);
					}
					else
					{
						try 
						{
							$status = LinksynceparcelApi::unAssignConsignment($consignmentNumber);
							$status = trim(strtolower($status));
							if($status == 'ok')
							{
								$success++;
								LinksynceparcelHelper::updateConsignmentTable($consignmentNumber,'manifest_number', '');
								LinksynceparcelHelper::updateConsignmentTable($consignmentNumber,'is_next_manifest', 0);
								$successmsg = sprintf('Consignment #%s: successfully unassigned', $consignmentNumber);
								LinksynceparcelHelper::addMessage('linksynceparcel_consignment_success',$successmsg);
							}
							else
							{
								$error = sprintf('Consignment #%s: failed to unassign', $consignmentNumber);
								LinksynceparcelHelper::addMessage('linksynceparcel_consignment_error',$error);
							}
						}
						catch (Exception $e) 
						{
							$error = sprintf('Consignment #%s, Error: %s', $consignmentNumber, $e->getMessage());
							LinksynceparcelHelper::addMessage('linksynceparcel_consignment_error',$error);
						}
					}
                }
				
				if($orderId > 0 && $success > 0)
				{
					LinksynceparcelHelper::getManifestNumber();
					LinksynceparcelHelper::deleteManifest();
				}
			}
			else
			{
				throw new Exception("Please select items");
			}
		} 
		catch (Exception $e) 
		{
			LinksynceparcelHelper::addMessage('linksynceparcel_consignment_error',$e->getMessage());
			LinksynceparcelHelper::deleteManifest();
		}
		wp_redirect(admin_url('admin.php?page=linksynceparcel'));
	}
	
	public static function massDeleteConsignment()
	{
		$ids = $_REQUEST['order'];
		try 
		{
			if(is_array($ids))
			{
				$success = 0;
                foreach ($ids as $id) 
				{
					$values = explode('_',$id);
					$orderId = (int)($values[0]);
					$consignmentNumber = $values[1];
					$incrementId = $orderId;
					if($consignmentNumber == '0')
					{
						$error = sprintf('Order #%s: does not have consignment', $incrementId);
						LinksynceparcelHelper::addMessage('linksynceparcel_consignment_error',$error);
					}
					else
					{
						try 
						{
							$status = LinksynceparcelApi::deleteConsignment($consignmentNumber);
							$status = trim(strtolower($status));
							if($status == 'ok')
							{
								$filename = $consignmentNumber.'.pdf';
								$filepath = linksynceparcel_DIR.'assets/label/consignment/'.$filename;
								if(file_exists($filepath))
								{
									unlink($filepath);
								}
								
								$filepath2 = linksynceparcel_DIR.'assets/label/returnlabels/'.$filename;
								if(file_exists($filepath2))
								{
									unlink($filepath2);
								}
								
								LinksynceparcelHelper::deleteConsignment($consignmentNumber);
								$success++;
								$successmsg = sprintf('Consignment #%s: successfully deleted', $consignmentNumber);
								LinksynceparcelHelper::addMessage('linksynceparcel_consignment_success',$successmsg);
							}
							else
							{
								$error = sprintf('Consignment #%s: failed to delete', $consignmentNumber);
								LinksynceparcelHelper::addMessage('linksynceparcel_consignment_error',$error);
							}
						}
						catch (Exception $e) 
						{
							$error = sprintf('Consignment #%s, Error: %s', $consignmentNumber, $e->getMessage());
							LinksynceparcelHelper::addMessage('linksynceparcel_consignment_error',$error);
						}
					}
                }
				
				if($orderId > 0 && $success > 0)
				{
					LinksynceparcelHelper::getManifestNumber();
					LinksynceparcelHelper::deleteManifest();
				}
			}
			else
			{
				throw new Exception("Please select items");
			}
		} 
		catch (Exception $e) 
		{
			LinksynceparcelHelper::addMessage('linksynceparcel_consignment_error',$e->getMessage());
			LinksynceparcelHelper::deleteManifest();
		}
		wp_redirect(admin_url('admin.php?page=linksynceparcel'));
	}
	
	public static function massGenerateLabels()
	{
		$ids = $_REQUEST['order'];
		try 
		{
			if(is_array($ids))
			{
				$sameGroup = true;
				$isExpressCode = false;
				$isStandardCode = false;
				
				foreach ($ids as $id) 
				{
					$values = explode('_',$id);
					$orderId = (int)($values[0]);
					$consignmentNumber = $values[1];
					
					$chargeCode = LinksynceparcelHelper::getOrderChargeCode($orderId,$consignmentNumber);
					if(!$isExpressCode && LinksynceparcelHelper::isExpressPostCode($chargeCode))
						$isExpressCode = true;
					if(!$isStandardCode && LinksynceparcelHelper::isLinksynceparcelStandardCode($chargeCode))
						$isStandardCode = true;
				}
				
				if (!($isExpressCode && $isStandardCode))
				{
					$consignmentNumbers = array();
					foreach ($ids as $id) 
					{
						$values = explode('_',$id);
						$orderId = (int)($values[0]);
						$consignmentNumber = $values[1];
						$incrementId = $orderId;
						if($consignmentNumber != '0')
						{
							$consignmentNumbers[] = $consignmentNumber;
							
							$labelContent = LinksynceparcelApi::getLabelsByConsignments($consignmentNumber);
							if($labelContent)
							{
								$filename = $consignmentNumber.'.pdf';
								$filepath = linksynceparcel_DIR.'assets/label/consignment/'.$filename;
								$handle = fopen($filepath,'wb');
								fwrite($handle, $labelContent);
								fclose($handle);
	
								LinksynceparcelHelper::updateConsignmentTable($consignmentNumber,'label', $filename);
								LinksynceparcelHelper::updateConsignmentTable($consignmentNumber,'is_label_created', 1);
								LinksynceparcelHelper::updateConsignmentTable($consignmentNumber,'is_label_printed', 1);
							}
						}
					}
					
					if(count($consignmentNumbers) > 0)
					{
						$labelContent = LinksynceparcelApi::getLabelsByConsignments(implode(',',$consignmentNumbers));
						if($labelContent)
						{
							$filename = 'bulk-consignments-label.pdf';
							$filepath = linksynceparcel_DIR.'assets/label/consignment/'.$filename;
							$handle = fopen($filepath,'wb');
							fwrite($handle, $labelContent);
							fclose($handle);
							$labelLink = linksynceparcel_URL.'assets/label/consignment/';
							$success = sprintf('Label is generated. <a href="%s" target="_blank" style="color:blue; font-weight:bold; font-size:14px; text-decoration:underline">Please click here to view it.</a>',$labelLink.$filename.'?'.time());
							LinksynceparcelHelper::addMessage('linksynceparcel_consignment_success',$success);
						}
						else
						{
							LinksynceparcelHelper::addMessage('linksynceparcel_consignment_error','Failed to generate label');
						}
					}
					else
					{
						LinksynceparcelHelper::addMessage('linksynceparcel_consignment_error','None of the selected items have consignments');
					}
				}
				else
				{
					$error = 'You can only print multiple consignment labels for the same Delivery Type - they must be all Express Post or all eParcel Standard.';
					LinksynceparcelHelper::addMessage('linksynceparcel_consignment_error',$error);
				}
			}
			else
			{
				throw new Exception("Please select items");
			}
		} 
		catch (Exception $e) 
		{
			LinksynceparcelHelper::addMessage('linksynceparcel_consignment_error',$e->getMessage());
		}
		wp_redirect(admin_url('admin.php?page=linksynceparcel'));
	}
	
	public static function massGenerateReturnLabels()
	{
		$ids = $_REQUEST['order'];
		try 
		{
			if(is_array($ids))
			{
				$sameGroup = true;
				$isExpressCode = false;
				$isStandardCode = false;
				
				foreach ($ids as $id) 
				{
					$values = explode('_',$id);
					$orderId = (int)($values[0]);
					$consignmentNumber = $values[1];
					
					$chargeCode = LinksynceparcelHelper::getOrderChargeCode($orderId,$consignmentNumber);
					if(!$isExpressCode && LinksynceparcelHelper::isExpressPostCode($chargeCode))
						$isExpressCode = true;
					if(!$isStandardCode && LinksynceparcelHelper::isLinksynceparcelStandardCode($chargeCode))
						$isStandardCode = true;
				}
				
				if (!($isExpressCode && $isStandardCode))
				{
					$consignmentNumbers = array();
					foreach ($ids as $id) 
					{
						$values = explode('_',$id);
						$orderId = (int)($values[0]);
						$consignmentNumber = $values[1];
						$incrementId = $orderId;
						if($consignmentNumber != '0')
						{
							$consignmentNumbers[] = $consignmentNumber;
							
							$labelContent = LinksynceparcelApi::getReturnLabelsByConsignments($consignmentNumber);
							if($labelContent)
							{
								$filename = $consignmentNumber.'.pdf';
								$filepath = linksynceparcel_DIR.'assets/label/returnlabels/'.$filename;
								$handle = fopen($filepath,'wb');
								fwrite($handle, $labelContent);
								fclose($handle);
								LinksynceparcelHelper::updateConsignmentTable($consignmentNumber,'is_return_label_printed', 1);
							}
						}
					}
					
					if(count($consignmentNumbers) > 0)
					{
						$labelContent = LinksynceparcelApi::getReturnLabelsByConsignments(implode(',',$consignmentNumbers));
						if($labelContent)
						{
							$filename = 'bulk-consignments-return-label.pdf';
							$filepath = linksynceparcel_DIR.'assets/label/returnlabels/'.$filename;
							$handle = fopen($filepath,'wb');
							fwrite($handle, $labelContent);
							fclose($handle);
							$labelLink = linksynceparcel_URL.'assets/label/returnlabels/';
							$success = sprintf('Return Label is generated. <a href="%s" target="_blank" style="color:blue; font-weight:bold; font-size:14px; text-decoration:underline">Please click here to view it.</a>',$labelLink.$filename.'?'.time());
							LinksynceparcelHelper::addMessage('linksynceparcel_consignment_success',$success);
						}
						else
						{
							LinksynceparcelHelper::addMessage('linksynceparcel_consignment_error','Failed to generate label');
						}
					}
					else
					{
						LinksynceparcelHelper::addMessage('linksynceparcel_consignment_error','None of the selected items have consignments');
					}
				}
				else
				{
					$error = 'You can only print multiple consignment labels for the same Delivery Type - they must be all Express Post or all eParcel Standard.';
					LinksynceparcelHelper::addMessage('linksynceparcel_consignment_error',$error);
				}
			}
			else
			{
				throw new Exception("Please select items");
			}
		} 
		catch (Exception $e) 
		{
			LinksynceparcelHelper::addMessage('linksynceparcel_consignment_error',$e->getMessage());
		}
		wp_redirect(admin_url('admin.php?page=linksynceparcel'));
	}
	
	public static function massMarkDespatched()
	{
		$ids = $_REQUEST['order'];
		try 
		{
			if(is_array($ids))
			{
				$statuses = (array) get_terms('shop_order_status', array('hide_empty' => 0, 'orderby' => 'id'));
				$success = 0;
                foreach ($ids as $id) 
				{
					$values = explode('_',$id);
					$orderId = (int)($values[0]);
					$consignmentNumber = $values[1];
					$incrementId = $orderId;
					if($consignmentNumber == '0')
					{
						$error = sprintf('Order #%s: does not have consignment', $incrementId);
						LinksynceparcelHelper::addMessage('linksynceparcel_consignment_error',$error);
					}
					else
					{
						try 
						{
							LinksynceparcelHelper::updateConsignmentTable($consignmentNumber,'despatched', 1);
							$changeState = get_option('linksynceparcel_change_order_status');
							if(!empty($changeState))
							{
								$order = new WC_Order($order_id);

								$current_status = '';
								foreach($statuses as $status)
								{
									if($status->slug == $order->status)
									{
										$current_status = $status->term_id;
									}
								}
									
								if ($changeState && ($changeState !== $current_status))
								{
									foreach($statuses as $status)
									{
										if($status->term_id == $changeState)
										{
											$order->update_status($status->slug);
										}
									}
								}
							}
							$successmsg = sprintf('Consignment #%s: successfully marked as despatched', $consignmentNumber);
							LinksynceparcelHelper::addMessage('linksynceparcel_consignment_success',$successmsg);
						}
						catch (Exception $e) 
						{
							$error = sprintf('Consignment #%s, Error: %s', $consignmentNumber, $e->getMessage());
							LinksynceparcelHelper::addMessage('linksynceparcel_consignment_error',$error);
						}
					}
                }
			}
			else
			{
				throw new Exception("Please select items");
			}
		} 
		catch (Exception $e) 
		{
			LinksynceparcelHelper::addMessage('linksynceparcel_consignment_error',$e->getMessage());
		}
		wp_redirect(admin_url('admin.php?page=linksynceparcel'));
	}
	
	public static function despatchManifest() 
	{
		global $is_greater_than_21;
		try 
		{
			$isManifest = false;
			$manifestNumber = false;
			$manifests = LinksynceparcelApi::getManifest();
			$xml = simplexml_load_string($manifests);
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
					if($numberOfConsignments > 0)
					{
						LinksynceparcelHelper::updateManifest($manifestNumber,$numberOfArticles,$numberOfConsignments);
						$isManifest = true;
					}
				}
			}
			
			if(!$isManifest)
			{
				throw new Exception('No consignments are available in the current manifest');
			}
			
			$notDespatchedConsignmentNumbers = LinksynceparcelHelper::getNotDespatchedAssignedConsignmentNumbers();
			if(count($notDespatchedConsignmentNumbers) == 0)
			{
				$error = 'No consignments are available in the current manifest';
				LinksynceparcelHelper::addMessage('linksynceparcel_consignment_error',$error);
			} 
			else 
			{
				try 
				{
					$statuses = LinksynceparcelHelper::getOrderStatuses();
					
					$despatch = true;
					foreach ($notDespatchedConsignmentNumbers as $consignmentNumber) 
					{
						$consignmentNumber = trim($consignmentNumber);
						$consignment = LinksynceparcelHelper::getConsignment($consignmentNumber);
						if(!$consignment)
						{
							LinksynceparcelApi::deleteConsignment($consignmentNumber);
							continue;
							$despatch = false;
							$error = sprintf('Consignment #%s: not in the current DB', $consignmentNumber);
							LinksynceparcelHelper::addMessage('linksynceparcel_consignment_error',$error);
						}
						else if(!$consignment->is_label_printed)
						{
							$despatch = false;
							$error = sprintf('Consignment #%s: you have not printed labels for this consignment.', $consignmentNumber);
							LinksynceparcelHelper::addMessage('linksynceparcel_consignment_error',$error);
						}
						else if($consignment->print_return_labels && !$consignment->is_return_label_printed)
						{
							$despatch = false;
							$error = sprintf('Consignment #%s: you have not printed return labels for this consignment.', $consignmentNumber);
							LinksynceparcelHelper::addMessage('linksynceparcel_consignment_error',$error);
						}
					}
					
					if($despatch)
					{
						try 
						{
							$status = LinksynceparcelApi::despatchManifest();
							$status = trim(strtolower($status));
							if($status == 'ok')
							{
								$timestamp = time();
								$date = date('Y-m-d H:i:s', $timestamp);
								LinksynceparcelHelper::updateManifestTable($currentManifest,'despatch_date',$date);
								LinksynceparcelHelper::updateConsignmentTableByManifest($currentManifest,'despatched',1);
								LinksynceparcelHelper::updateConsignmentTableByManifest($currentManifest,'is_next_manifest',0);
								
								$changeState = get_option('linksynceparcel_change_order_status');
								if(!empty($changeState))
								{
									$ordersList = LinksynceparcelHelper::getOrdersByManifest($currentManifest);
									if($ordersList)
									{
										foreach($ordersList as $orderObj)
										{
											$order = new WC_Order($orderObj->order_id);
			
											$current_status = '';
											
											if($is_greater_than_21)
											{
												foreach($statuses as $term_id => $status)
												{
													if($term_id == $order->post_status)
													{
														$current_status = $term_id;
													}
												}
													
												if ($changeState && ($changeState !== $current_status))
												{
													$order->update_status($changeState);
			  									}
											}
											else
											{
												foreach($statuses as $status)
												{
													if($status->slug == $order->status)
													{
														$current_status = $status->term_id;
													}
												}
													
												if ($changeState && ($changeState !== $current_status))
												{
													foreach($statuses as $status)
													{
														if($status->term_id == $changeState)
														{
															$order->update_status($status->slug);
														}
													}
												}
											}
											
										}
									}
								}
								
								$success = 'Despatching manifest is successful';
								LinksynceparcelHelper::addMessage('linksynceparcel_consignment_success',$success);
									
								$labelContent = LinksynceparcelApi::printManifest($currentManifest);

								if($labelContent)
								{
									$filename = $currentManifest.'.pdf';
									$filepath = linksynceparcel_DIR.'assets/label/manifest/'.$filename;
									$handle = fopen($filepath,'wb');
									fwrite($handle, $labelContent);
									fclose($handle);
					
									LinksynceparcelHelper::updateManifestTable($currentManifest,'label',$filename);
									
									$labelLink =linksynceparcel_URL.'assets/label/manifest/';
									$success = sprintf('Your Manifest Summary has been generated. <a href="%s" target="_blank" style="color:blue; font-weight:bold; font-size:14px; text-decoration:underline">Please click here to view it.</a>', $labelLink.$filename.'?'.time());
									LinksynceparcelHelper::addMessage('linksynceparcel_consignment_success',$success);
								}
								else
								{
									$error = 'Manifest label content is empty';
									LinksynceparcelHelper::addMessage('linksynceparcel_consignment_error',$error);
								}
								
								LinksynceparcelHelper::notifyCustomers($currentManifest);
							}
							else
							{
								$error = 'Despatching manifest is failed';
								LinksynceparcelHelper::addMessage('linksynceparcel_consignment_error',$error);
							}
						}
						catch (Exception $e) 
						{
							$error = sprintf('Despatching manifest, Error: %s', $e->getMessage());
							LinksynceparcelHelper::addMessage('linksynceparcel_consignment_error',$error);
						}
					}
				} 
				catch (Exception $e) 
				{
					LinksynceparcelHelper::addMessage('linksynceparcel_consignment_error',$e->getMessage());
				}
			}
		}
		catch (Exception $e) 
		{
			LinksynceparcelHelper::addMessage('linksynceparcel_consignment_error',$e->getMessage());
		}
        wp_redirect(admin_url('admin.php?page=linksynceparcel'));
    }
}
?>