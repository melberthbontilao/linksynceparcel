<?php
if( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}
require_once(linksynceparcel_DIR.'model/Consignment/OrdersModel.php' );

class ConsignmentOrdersList extends WP_List_Table
{
	public function __construct()
	{
    	global $status, $page;

        parent::__construct( 
			array(
				'singular'  => 'Order',
				'plural'    => 'Orders',
				'ajax'      => false
    		)
		);
    }

	public function column_default( $item, $column_name )
	{
		switch( $column_name )
		{ 
			case 'order_id':
			case 'consignment_number':
			case 'add_date':
			case 'is_address_valid':
			case 'number_of_articles':
			case 'shipping_description':
			case 'is_label_printed':
			case 'is_return_label_printed':
			case 'is_next_manifest':
			case 'weight':
				return (is_object($item) ? $item->$column_name : $item[$column_name]);
			default:
				return print_r( $item, true );
		}
	}
	
	public function column_consignment_number($item)
	{
		$order_id = $item->ID;
		$html = '<a href="'.admin_url('post.php?post='.$order_id.'&action=edit').'">'.($item->consignment_number ? $item->consignment_number:'Create Consignment').'</a>';
		return $html;
	}
	
	public function column_order_id($item)
	{
		$order_id = $item->ID;
		$order = new WC_Order( $order_id );
		$html = '<a href="'.admin_url('post.php?post='.$order_id.'&action=edit').'">'.$order->get_order_number().'</a>';
		return $html;
	}
	
	public function column_customer_name($item)
	{
		$meta = get_post_meta($item->order_id);
		return $meta['_shipping_first_name'][0].' '.$meta['_shipping_last_name'][0];
	}
	
	public function column_shipping_description($item)
	{
		$chargecode = $item->chargecode;
		$shipping_description = $item->shipping_description;
		$html = $shipping_description.(!empty($chargecode) ? ' - '.$chargecode : '');
		return $html;
	}
	
	public function column_is_address_valid($item)
	{
		if($item->is_address_valid == 1)
		{
			$html = '<span class="column-order_status"><mark class="completed tips" style="cursor:pointer">Yes</mark></span>';
		}
		else
		{
			$html = '<span class="column-order_status"><mark class="cancelled tips">No</mark></span>';
		}
		return $html;
	}
	
	public function column_is_label_printed($item)
	{
		if(isset($item->is_label_printed))
		{
			if($item->is_label_printed == 1)
			{
				$html = '<a lang="'.$item->consignment_number.'" href="'.linksynceparcel_URL.'assets/label/consignment/'.$item->consignment_number.'.pdf?'.time().'" target="_blank" ><span class="column-order_status"><mark class="completed tips" style="cursor:pointer">Yes</mark></span></a>';
			}
			else if($item->is_label_created == 1)
			{
				$html = '<a class="print_label" lang="'.$item->consignment_number.'" href="'.linksynceparcel_URL.'assets/label/consignment/'.$item->consignment_number.'.pdf?'.time().'" target="_blank" ><span class="column-order_status"><mark class="cancelled tips">No</mark></span></a>';
			}
			else
			{
				$html = '<a href="'.admin_url('admin.php?page=linksynceparcel&action=massGenerateLabels&order[]='.$item->order_consignment).'" target="_blank" ><span class="column-order_status"><mark class="cancelled tips">No</mark></span></a>';
			}
		}
		else
		{
			$html = '&nbsp;';
		}
		return $html;
	}
	
	public function column_is_return_label_printed($item)
	{
		if($item->print_return_labels)
		{
			if($item->is_return_label_printed == 1)
			{
				$html = '<a lang="'.$item->consignment_number.'" href="'.linksynceparcel_URL.'assets/label/returnlabels/'.$item->consignment_number.'.pdf?'.time().'" target="_blank" ><span class="column-order_status"><mark class="completed tips" style="cursor:pointer">Yes</mark></span></a>';
			}
			else if(file_exists(linksynceparcel_DIR.'assets/label/returnlabels/'.$item->consignment_number.'.pdf'))
			{
				$html = '<a class="print_return_label" lang="'.$item->consignment_number.'" href="'.linksynceparcel_URL.'assets/label/returnlabels/'.$item->consignment_number.'.pdf?'.time().'" target="_blank" ><span class="column-order_status"><mark class="cancelled tips">No</mark></span></a>';
			}
			else
			{
				$html = '<a href="'.admin_url('admin.php?page=linksynceparcel&action=massGenerateReturnLabels&order[]='.$item->order_consignment).'" target="_blank" ><span class="column-order_status"><mark class="cancelled tips">No</mark></span></a>';
			}
		}
		else
		{
			$html = '&nbsp;';
		}
		return $html;
	}
	
	public function column_is_next_manifest($item)
	{
		if(isset($item->is_next_manifest))
		{
			if($item->is_next_manifest == 1)
			{
				$html = '<span class="column-order_status"><mark class="completed tips" style="cursor:pointer">Yes</mark></span>';
			}
			else
			{
				$html = '<span class="column-order_status"><mark class="cancelled tips">No</mark></span>';
			}
		}
		else
		{
			$html = '&nbsp;';
		}
		return $html;
	}

	public function get_columns()
	{
		$display_status = (int)(get_option('linksynceparcel_display_order_status'));
		if($display_status == 1)
		{
			$columns = array(
				'cb' => '<input type="checkbox" />',
				'order_id' => 'Order No.',
				'order_status' => '<span class="status_head tips" title="Status">Status</span>',
				'customer_name' => 'Delivery To',
				'weight' => 'Weight',
				'is_address_valid' => 'Address Valid',
				'consignment_number' => 'Consignment Number',
				'shipping_description' => 'Delivery Type',
				'is_label_printed' => 'Labels Printed?',
				'is_return_label_printed' => 'Return Labels Printed?',
				'is_next_manifest' => 'Next Manifest?',
				'number_of_articles' => 'No. of Articles',
				'add_date' => 'Date Created',
			);

		}
		else
		{
			$columns = array(
				'cb' => '<input type="checkbox" />',
				'order_id' => 'Order No.',
				'customer_name' => 'Delivery To',
				'weight' => 'Weight',
				'is_address_valid' => 'Address Valid',
				'consignment_number' => 'Consignment Number',
				'shipping_description' => 'Delivery Type',
				'is_label_printed' => 'Labels Printed?',
				'is_return_label_printed' => 'Return Labels Printed?',
				'is_next_manifest' => 'Next Manifest?',
				'number_of_articles' => 'No. of Articles',
				'add_date' => 'Date Created',
			);
		}
		return $columns;
    }
	
	public function column_order_status($item)
	{
		global $is_greater_than_21;
		$statuses = LinksynceparcelHelper::getOrderStatuses();
		if($is_greater_than_21)
		{
			$order_status = $item->post_status;
			$icon = substr($order_status,3);
			$label = str_replace('-',' ',$icon);
			$label = ucwords($label);
			foreach($statuses as $term_id => $status)
			{
				if($order_status == $status)
					$label = $status;
			}
			echo '<mark class="'.$icon.' tips" title="'.$label.'">'.$label.'</mark>';
		}
		else
		{
			$order_status = $item->status;
			$icon = $order_status;
			$label = str_replace('-',' ',$order_status);
			$label = ucwords($label);
			foreach($statuses as $status)
			{
				if($order_status == $status->slug)
					$label = $status->name;
			}
			echo '<mark class="'.$icon.' tips" title="'.$label.'">'.$label.'</mark>';
		}
	}
	
	public function prepare_items()
	{
		global $wpdb,$is_greater_than_21;
		$consignmentOrders = new ConsignmentOrders();
		$columns  = $this->get_columns();
		$hidden   = array();
		$sortable = $this->get_sortable_columns();
		$this->_column_headers = array( $columns, $hidden, $sortable );
		
		$chosen_statuses = get_option('linksynceparcel_chosen_statuses');
		if($is_greater_than_21)
		{
			$status_condition = 'post_status="wc-pending" OR post_status="wc-processing" OR post_status="wc-on-hold" OR ';
			
			$display_choosen_status = (int)get_option('linksynceparcel_display_choosen_status');
			if($display_choosen_status == 1)
			{
				if($chosen_statuses && count($chosen_statuses) > 0)
				{
					$status_condition = '';
					foreach($chosen_statuses as $chosen_status)
					{
						$status_condition .= 'post_status="'.$chosen_status.'" OR ';
					}
				}
			}
			
			$where = ' AND main_table.post_type="shop_order"';
			
			$join = ' LEFT JOIN '.$wpdb->prefix.'postmeta pm ON main_table.ID=pm.post_id';
			
			$where .= ' AND pm.meta_key="_shipping_country" AND pm.meta_value="AU" AND IFNULL(c.despatched,0) = 0';
			$where .= ' AND ('.$status_condition.' (case when (select count(*) from '.$wpdb->prefix.'linksynceparcel_consignment where order_id = main_table.ID) > 0 then (select count(*) from '.$wpdb->prefix.'linksynceparcel_consignment where order_id = main_table.ID and despatched = 0) else 0 end) > 0 )';
			
			$joinFields = ',c.chargecode,c.weight';
			$join .= ' LEFT JOIN '.$wpdb->prefix.'linksynceparcel_consignment c ON main_table.ID=c.order_id';
			
			$joinFields .= ",(case when c.consignment_number != '' then ( select count(*) from ".$wpdb->prefix."linksynceparcel_article where consignment_number = c.consignment_number) else null end) as number_of_articles";
			
			$joinFields .= ',av.is_address_valid,av.is_address_valid as is_not_open';
			$join .= ' LEFT JOIN '.$wpdb->prefix.'linksynceparcel_address_valid av ON main_table.ID=av.order_id';
			
			$joinFields .= ",woi.order_item_name as shipping_description";
			$join .= ' LEFT JOIN '.$wpdb->prefix.'woocommerce_order_items woi ON main_table.ID=woi.order_id AND woi.order_item_type="shipping"';
			
			$joinFields .= ",woim.meta_value as shipping_method";
			$join .= ' LEFT JOIN '.$wpdb->prefix.'woocommerce_order_itemmeta woim ON woi.order_item_id=woim.order_item_id AND woi.order_item_type="shipping" AND woim.meta_key="method_id"';
			
			$where .= ' AND ( 
							case 
							when (select count(*) from '.$wpdb->prefix.'linksynceparcel_nonlinksync where method = woim.meta_value and charge_code != "none") > 0 
								then 1
							when (select count(*) from '.$wpdb->prefix.'linksynceparcel_nonlinksync where method = woi.order_item_name and charge_code != "none") > 0 
								then 1
							when (select charge_code from '.$wpdb->prefix.'linksynceparcel_nonlinksync where method = woim.meta_value) = "none"
								then 0
							when (select charge_code from '.$wpdb->prefix.'linksynceparcel_nonlinksync where method = woi.order_item_name) = "none"
								then 0
							else 1
							end
						) > 0
					';
			
			$joinFields .= ',CONCAT(main_table.ID, "_",IFNULL(c.consignment_number,0)) as order_consignment';
			$joinFields .= ',main_table.ID as order_id';
			$joinFields .= ',c.is_label_printed,c.is_return_label_printed,c.consignment_number,c.add_date,c.is_next_manifest,c.is_label_created,c.print_return_labels';
		}
		else
		{
			$status_condition = 't.slug="pending" OR t.slug="processing" OR t.slug="on-hold" OR ';
			
			$display_choosen_status = (int)get_option('linksynceparcel_display_choosen_status');
			if($display_choosen_status == 1)
			{
				if($chosen_statuses && count($chosen_statuses) > 0)
				{
					$status_condition = '';
					foreach($chosen_statuses as $chosen_status)
					{
						$status_condition .= 't.slug="'.$chosen_status.'" OR ';
					}
				}
			}
			
			$where = ' AND main_table.post_type="shop_order" AND post_status="publish"';
			
			$join = ' LEFT JOIN '.$wpdb->prefix.'postmeta pm ON main_table.ID=pm.post_id';
			$join .= ' LEFT JOIN '.$wpdb->prefix.'term_relationships tr ON main_table.ID=tr.object_id';
			$join .= ' LEFT JOIN '.$wpdb->prefix.'term_taxonomy tt ON tr.term_taxonomy_id=tt.term_taxonomy_id';
			$join .= ' LEFT JOIN '.$wpdb->prefix.'terms t ON tt.term_id=t.term_id';
			
			$joinFields = ',t.slug as status';
			$where .= ' AND pm.meta_key="_shipping_country" AND pm.meta_value="AU" AND IFNULL(c.despatched,0) = 0';
			$where .= ' AND tt.taxonomy="shop_order_status"';
			$where .= ' AND ('.$status_condition.' (case when (select count(*) from '.$wpdb->prefix.'linksynceparcel_consignment where order_id = main_table.ID) > 0 then (select count(*) from '.$wpdb->prefix.'linksynceparcel_consignment where order_id = main_table.ID and despatched = 0) else 0 end) > 0 )';
			
			$joinFields .= ',c.chargecode,c.weight';
			$join .= ' LEFT JOIN '.$wpdb->prefix.'linksynceparcel_consignment c ON main_table.ID=c.order_id';
			
			$joinFields .= ",(case when c.consignment_number != '' then ( select count(*) from ".$wpdb->prefix."linksynceparcel_article where consignment_number = c.consignment_number) else null end) as number_of_articles";
			
			$joinFields .= ',av.is_address_valid,av.is_address_valid as is_not_open';
			$join .= ' LEFT JOIN '.$wpdb->prefix.'linksynceparcel_address_valid av ON main_table.ID=av.order_id';
			
			$joinFields .= ",woi.order_item_name as shipping_description";
			$join .= ' LEFT JOIN '.$wpdb->prefix.'woocommerce_order_items woi ON main_table.ID=woi.order_id AND woi.order_item_type="shipping"';
			
			$joinFields .= ",woim.meta_value as shipping_method";
			$join .= ' LEFT JOIN '.$wpdb->prefix.'woocommerce_order_itemmeta woim ON woi.order_item_id=woim.order_item_id AND woi.order_item_type="shipping" AND woim.meta_key="method_id"';
			
			$where .= ' AND ( 
							case 
							when (select count(*) from '.$wpdb->prefix.'linksynceparcel_nonlinksync where method = woim.meta_value and charge_code != "none") > 0 
								then 1 
							when (select charge_code from '.$wpdb->prefix.'linksynceparcel_nonlinksync where method = woim.meta_value) = "none"
								then 0 
							else 1
							end
						) > 0
					';
			
			$joinFields .= ',CONCAT(main_table.ID, "_",IFNULL(c.consignment_number,0)) as order_consignment';
			$joinFields .= ',main_table.ID as order_id';
			$joinFields .= ',c.is_label_printed,c.is_return_label_printed,c.consignment_number,c.add_date,c.is_next_manifest,c.is_label_created,c.print_return_labels';
		}
		if(isset($_REQUEST['order_id']) && !empty($_REQUEST['order_id']))
		{
			$order_id = (int)($_REQUEST['order_id']);
			$where .= ' AND main_table.ID like "%'.$order_id.'%"';
		}
		
		if(isset($_REQUEST['is_address_valid']) && !empty($_REQUEST['is_address_valid']))
		{
			$is_address_valid = ($_REQUEST['is_address_valid']);
			if($is_address_valid == 1)
				$where .= ' AND (case when (select count(*) from '.$wpdb->prefix.'linksynceparcel_address_valid where order_id = main_table.ID) > 0 then av.is_address_valid else 0 end) > 0';
			else
				$where .= ' AND (case when (select count(*) from '.$wpdb->prefix.'linksynceparcel_address_valid where order_id = main_table.ID) > 0 then av.is_address_valid else 0 end) = 0';
		}
		if(isset($_REQUEST['consignment_number']) && !empty($_REQUEST['consignment_number']))
		{
			$consignment_number = ($_REQUEST['consignment_number']);
			$where .= ' AND c.consignment_number like "%'.$consignment_number.'%"';
		}
		if(isset($_REQUEST['shipping_description']) && !empty($_REQUEST['shipping_description']))
		{
			$shipping_description = ($_REQUEST['shipping_description']);
			$where .= ' AND c.chargecode = "'.$shipping_description.'"';
		}
		if(isset($_REQUEST['is_label_printed']) && !empty($_REQUEST['is_label_printed']))
		{
			$is_label_printed = ($_REQUEST['is_label_printed']);
			if($is_label_printed == 1)
				$where .= ' AND c.is_label_printed = 1';
			else
				$where .= ' AND c.is_label_printed != 1';
		}
		if(isset($_REQUEST['is_return_label_printed']) && !empty($_REQUEST['is_return_label_printed']))
		{
			$is_return_label_printed = ($_REQUEST['is_return_label_printed']);
			if($is_return_label_printed == 1)
				$where .= ' AND c.is_return_label_printed = 1';
			else
				$where .= ' AND c.is_return_label_printed != 1';
		}
		if(isset($_REQUEST['is_next_manifest']) && !empty($_REQUEST['is_next_manifest']))
		{
			$is_next_manifest = ($_REQUEST['is_next_manifest']);
			if($is_next_manifest == 1)
				$where .= ' AND c.is_next_manifest = 1';
			else
				$where .= ' AND c.is_next_manifest != 1';
		}
		if(isset($_REQUEST['add_date_from']) && !empty($_REQUEST['add_date_from']))
		{
			$add_date_from = ($_REQUEST['add_date_from']);
			$where .= ' AND c.add_date >= "'.$add_date_from.' 00:00:00"';
		}
		if(isset($_REQUEST['add_date_to']) && !empty($_REQUEST['add_date_to']))
		{
			$add_date_to = ($_REQUEST['add_date_to']);
			$where .= ' AND c.add_date <= "'.$add_date_to.' 23:59:59"';
		}
		
		$groupBy = '';
		$orderBy = 'main_table.ID desc';
		if(isset($_GET['orderby']) && !empty($_GET['orderby']))
		{
			if($_GET['orderby'] == 'order_id')
			{
				$orderBy = 'main_table.ID '.$_GET['order'];
			}
			else if($_GET['orderby'] == 'number_of_articles')
			{
				$orderBy = 'number_of_articles '.$_GET['order'];
			}
			else
			{
				$orderBy = '';
			}
		}

		$data = $consignmentOrders->get_all($orderBy,$join,$joinFields,$where,$groupBy);

		if(isset($_GET['orderby']) && !empty($_GET['orderby']))
		{
			if( !($_GET['orderby'] == 'order_id' || $_GET['orderby'] == 'number_of_articles') )
			{
				usort( $data, array( &$this, 'usort_reorder' ) );
			}
		}
		
		$per_page = (int)get_option('consignment_per_page');
		if($per_page == 0)
		{
			$per_page = 20;
		}
		$current_page = $this->get_pagenum();
		$total_items = count($data);

 		$found_data = array_slice($data,(($current_page-1)*$per_page),$per_page);

		$this->set_pagination_args( array(
		 'total_items' => $total_items,
		 'per_page'    => $per_page 
		) );
		$this->items = $found_data;
	}
	
	function get_sortable_columns()
	{
	  $sortable_columns = array(
		'order_id'  => array('order_id',true),
		'consignment_number' => array('consignment_number',false),
		'created_date'   => array('created_date',false),
		'number_of_articles'   => array('number_of_articles',false),
	  );
	  return $sortable_columns;
	}
	
	function usort_reorder( $a, $b ) 
	{
	  $orderby = ( ! empty( $_GET['orderby'] ) ) ? $_GET['orderby'] : 'order_id';
	  $order = ( ! empty($_GET['order'] ) ) ? $_GET['order'] : 'desc';
	  $result = strcmp( $a->$orderby, $b->$orderby );
	  return ( $order === 'asc' ) ? $result : -$result;
	}
	
	public function column_cb($item)
	{
	 	return sprintf('<input type="checkbox" name="%1$s[]" value="%2$s" />', $this->_args['singular'], (is_object($item) ? $item->order_consignment : $item['order_consignment']));
	}
	
	public function get_bulk_actions()
	{
		$use_order_weight = (int)get_option('linksynceparcel_use_order_weight');
		$use_dimension = (int)get_option('linksynceparcel_use_dimension');

		if($use_order_weight == 1)
		{
			$actions['massCreateConsignment'] =  'Create Consignment';
		}
		else if($use_dimension == 1)
		{
			require_once(linksynceparcel_DIR.'model/ArticlePreset/Model.php' );
			$articlePreset = new ArticlePreset();
			$presets = $articlePreset->get_by(array('status' => 1));
			if(count($presets) > 0)
			{
				$actions['massCreateConsignment'] =  'Create Consignment';
			}
		}
		
		$actions2 = array(
			'massGenerateLabels' => 'Generate Labels',
			'massGenerateReturnLabels' => 'Generate Return Labels',
			'massUnassignConsignment' => 'Remove from Manifest',
			'massAssignConsignment' => 'Add to Manifest',
			'massDeleteConsignment' => 'Delete Consignment'
		);
		
		if(isset($actions) && is_array($actions))
			$actions = array_merge($actions,$actions2);
		else
			$actions = $actions2;
			
		if(trim(get_option('linksynceparcel_mark_despatch')) == 1)
		{
			$actions['massMarkDespatched'] =  'Mark as Despatched';
		}
		
		return $actions;
	}
	
	public function extra_tablenav($which)
	{
?>
        <div class="alignleft actions">
        <?php
			if ( 'top' == $which)
			{
				echo '<a id="despatchManifest" href="javascript:void(0)" onclick="setLocationConfirmDialog(\''.admin_url('admin.php?page=linksynceparcel&action=despatchManifest').'\')" class="button '.(!LinksynceparcelHelper::isCurrentMainfestHasConsignmentsForDespatch() ? 'disabled': '').'">Despatch</a>';
				echo '&nbsp;&nbsp;';
				echo '<a href="'.admin_url('admin.php?page=linksynceparcel&reset=1').'" class="button">Reset Filter</a>';
				echo '&nbsp;&nbsp;';
				submit_button( __( 'Search' ), 'button', false, false, array( 'id' => 'post-query-submit' ) );
			}
        ?>
        </div>
        <?php        
	}

	public function print_column_headers( $with_id = true ) 
	{
		parent::print_column_headers( $with_id );
		
		if($with_id)
		{
			list( $columns, $hidden, $sortable ) = $this->get_column_info();
			echo '<tr>';
			foreach ( $columns as $column_key => $column_display_name )
			{
				if($column_key != 'customer_name' && $column_key != 'cb' && $column_key != 'number_of_articles'  && $column_key != 'order_status'   && $column_key != 'weight')
				{
					if($column_key == 'add_date')
					{
						echo '<td>
								<input class="new-input datepicker" type="text" name="'.$column_key.'_from" value="'.(isset($_REQUEST[$column_key.'_from']) ? $_REQUEST[$column_key.'_from'] : '').'" />
								<br/>
								<input class="new-input datepicker" type="text" name="'.$column_key.'_to" value="'.(isset($_REQUEST[$column_key.'_to']) ? $_REQUEST[$column_key.'_to'] : '').'" />
							</td>';
					}
					else if($column_key == 'is_address_valid' || $column_key == 'is_label_printed' || $column_key == 'is_return_label_printed' || $column_key == 'is_next_manifest')
					{
						echo '<td style="text-align:center">
								<select name="'.$column_key.'">
									<option value=""></option>
									<option value="1" '.( (isset($_REQUEST[$column_key]) && $_REQUEST[$column_key] == 1) ? "selected='selected'" : "" ).'>Yes</option>
									<option value="2" '.( (isset($_REQUEST[$column_key]) && $_REQUEST[$column_key] == 2) ? "selected='selected'" : "" ).' >No</option>
								</select>
							</td>';
					}
					else if($column_key == 'shipping_description')
					{
						echo '<td style="text-align:center">
								<select name="'.$column_key.'">
									<option value=""></option>';
								$chargeCodes = LinksynceparcelHelper::getChargeCodeValues();
								foreach($chargeCodes as $chargeCode) 
								{
                            		echo '<option value="'.$chargeCode.'" '.( (isset($_REQUEST[$column_key]) && $_REQUEST[$column_key] == $chargeCode) ? "selected='selected'" : "" ).'>'.$chargeCode.'</option>';
								}
						echo '  </select>
							</td>';
					}
					else
					{
						echo '<td><input class="new-input" type="text" name="'.$column_key.'" value="'.(isset($_REQUEST[$column_key]) ? $_REQUEST[$column_key] : '').'" /></td>';
					}
				}
				else
				{
					echo '<td>&nbsp;</td>';
				}
			}
			echo '</tr>';
		}
	}
	
	public function checkNonLinksync() {
		global $wpdb;
		$table = $wpdb->prefix ."linksynceparcel_nonlinksync";
		$sql = "SELECT count(*) as num FROM ". $table;
		$rows = $wpdb->get_row($sql);
		return $rows->num;
	}
}
$myListTable = new ConsignmentOrdersList();
?>