

<?php 
$page_title='Tool Register';
$title_top='Tool Register';
include ('header.php'); ?>


<div class="container">
<link rel="stylesheet" href="css/asset.css">	
	
	<?php include ('navbar.php'); ?>
	
	<?php
	include ('function_issue_log.php');
	include ('function_asset2.php');
	include ('function_framework.php');
	
	load_role($db,$_SESSION['temp']['id']);
	manage_POST_asset($db);



	

	navbar_asset($db);
	//show($_SESSION['temp']['show_all_location']);
	//show($_POST);
	//show($_FILES);
	if ( $_POST['type']=='detail' or $_POST['type']=='move' or $_POST['type']=='save' or $_POST['type']=='add_location' or $_POST['type']=='add_asset' or $_POST['type']=='edit'){
		show_details_asset($db,$_POST['asset_id']);
	}else{
		$filter=update_filter($db);
		show_list_asset($db,$filter,$sort);
	}

	



	


	?>
	


	
	
	
</div>
