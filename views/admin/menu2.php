<?php
$titles = array();
$titles['consignments-search'] = 'eParcel Consignments Search';
$titles['manifests'] = 'eParcel Manifests View';
$titles['article-presets'] = 'eParcel Article Presets';
$titles['assign-shipping-types'] = 'eParcel Assign Shipping Types';
$titles['configuration'] = 'eParcel Configuration';
$titles['add-article'] = 'eParcel Add Article';
$titles['edit-consignment'] = 'eParcel Edit Consignment';
$titles['edit-article'] = 'eParcel Edit Article';

$page = isset($_REQUEST['page']) ? $_REQUEST['page']: '';
$subPage = isset($_REQUEST['subpage']) ? $_REQUEST['subpage']: '';
$title = !empty($subPage) ? $titles[$subPage] : '';

$currentPage = '';

if(!empty($page) && $page == 'linksynceparcel')
{
	$currentPage = 'admin.php?page=linksynceparcel';
	if(!empty($subPage))
	{
		$currentPage .= '&subpage='.$subPage;
	}
}
?>
<style>
li.wp-not-current-submenu ul.wp-submenu{
	min-width:175px !important;
}
div#linksynceparcel_address h3{
	color: red;
}
div#linksynceparcel_address .inside{
	background-color: #ffffcc;
    margin-top: 0;
	word-wrap: break-word;
	padding-top: 10px;
}
</style>
<script type="text/javascript">
var pageTitle = '<?php echo $title?>';
var currentPage = '<?php echo $currentPage?>';
$jEparcel = jQuery.noConflict();
$jEparcel(document).ready(function(){
	if(pageTitle.length > 0)
		$jEparcel('title').html(pageTitle);
	
	//$jEparcel('#toplevel_page_linksynceparcel > a').attr('href', 'javascript:void(0)');
	$jEparcel('#toplevel_page_linksynceparcel .wp-submenu a.wp-first-item').html('Consignments');
	$jEparcel('#toplevel_page_linksynceparcel .wp-submenu li').removeClass('current');
	$jEparcel('#toplevel_page_linksynceparcel .wp-submenu li a').each(function(){
		if($jEparcel(this).attr('href') == currentPage)
		{
			$jEparcel(this).parent().addClass('current');
		}
	});
		
	
	if($jEparcel('div#linksynceparcel_address .inside').length > 0)
	{
		var inside = $jEparcel('div#linksynceparcel_address .inside').html();
		inside = $jEparcel.trim(inside);
		if(inside.length==0)
		{
			$jEparcel('div#linksynceparcel_address').remove();
		}
	}
	
	$jEparcel('.print_label').click(function(){
		var consignmentNumber = $jEparcel(this).attr('lang');
		var ajaxCaller = '<?php echo admin_url('admin.php?page=linksynceparcel&subpage=update-label-as-printed') ?>&consignmentNumber='+consignmentNumber;
		$jEparcel.ajax({
			type: "POST",
			url: ajaxCaller,
			success: function(data){
				location.href = location.href;
			}
		});
	});
	
	$jEparcel('.print_return_label').click(function(){
		if(!$jEparcel(this).hasClass('printed'))
		{
			var consignmentNumber = $jEparcel(this).attr('lang');
			var ajaxCaller = '<?php echo admin_url('admin.php?page=linksynceparcel&subpage=update-return-label-as-printed') ?>&consignmentNumber='+consignmentNumber;
			$jEparcel.ajax({
				type: "POST",
				url: ajaxCaller,
				success: function(data){
					location.href = location.href;
				}
			});
		}
	});
});
</script>