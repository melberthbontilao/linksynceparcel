<?php
class LinksynceparcelAdminArticlePresets
{
	public static function output()
	{
		$action = (isset($_GET['action']) ? $_GET['action'] : '');
		if($action == 'add')
		{
			if (isset($_POST['save'])) 
			{
				$data = $_POST['linksynceparcel'];
				$errors = LinksynceparcelValidator::validateArticlePresets($data);
				if($errors)
				{
					$error = implode('<br/>',$errors);
				}
				else
				{
					try
					{
						require_once(linksynceparcel_DIR.'model/ArticlePreset/Model.php' );
						$articlePreset = new ArticlePreset();
						$articlePreset->insert($data);
						unset($_POST);
						unset($_REQUEST);
						$result = __( 'Article Preset has been added successfully.', 'linksynceparcel' );
					}
					catch(Exception $e)
					{
						$error = $e->getMessage();
					}
				}
			}
			include_once(linksynceparcel_DIR.'views/admin/article_presets/add.php');
		}
		else if($action == 'edit')
		{
			$id = $_REQUEST['id'];
			require_once(linksynceparcel_DIR.'model/ArticlePreset/Model.php' );
			$articlePreset = new ArticlePreset();
			
			if (isset($_POST['save'])) 
			{
				$data = $_POST['linksynceparcel'];
				$errors = LinksynceparcelValidator::validateArticlePresets($data);
				if($errors)
				{
					$error = implode('<br/>',$errors);
				}
				else
				{
					try
					{
						$articlePreset->update($data, array('id' => $id));
						$result = __( 'Article Preset has been updated successfully.', 'linksynceparcel' );
					}
					catch(Exception $e)
					{
						$error = $e->getMessage();
					}
				}
			}
			$preset = $articlePreset->get_by(array('id' => $id));
			$preset = $preset[0];
			include_once(linksynceparcel_DIR.'views/admin/article_presets/edit.php');
		}
		else
		{
			if($action == 'delete')
			{
				$id = $_REQUEST['id'];
				require_once(linksynceparcel_DIR.'model/ArticlePreset/Model.php' );
				$articlePreset = new ArticlePreset();
				
				try
				{
					$articlePreset->delete(array('id' => $id));
					$result = __( 'Article Preset has been deleted successfully.', 'linksynceparcel' );
				}
				catch(Exception $e)
				{
					$error = $e->getMessage();
				}
			}
			if( (isset($_REQUEST['action2']) || isset($_POST['action']) ) && ($_REQUEST['action2'] == 'delete' || $_POST['action'] == 'delete') )
			{
				$ids = $_REQUEST['articlepreset'];
				require_once(linksynceparcel_DIR.'model/ArticlePreset/Model.php' );
				$articlePreset = new ArticlePreset();
				
				try
				{
					foreach($ids as $id)
					{
						$articlePreset->delete(array('id' => $id));
					}
					$result = __( 'Article Preset(s) have been deleted successfully.', 'linksynceparcel' );
				}
				catch(Exception $e)
				{
					$error = $e->getMessage();
				}
			}
			include_once(linksynceparcel_DIR.'model/ArticlePreset/List.php');
			include_once(linksynceparcel_DIR.'views/admin/article_presets/list.php');
		}
	}
}
?>