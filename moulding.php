

<?php $starttime = microtime(true); // Top of page
$page_title='Moulding View';
$title_top='Moulding Live View';
include ('header.php'); ?>


<div class="container">
<link rel="stylesheet" href="css/asset.css">	
	
	<?php include ('navbar.php'); ?>
	
	<?php
	include ('function_issue_log.php');
	include ('function_asset.php');
	load_role($db,$_SESSION['temp']['id']);
	manage_POST_asset($db);



	

	//navbar_asset($db);
	//show($_SESSION['temp']['show_all_location']);
	//show($_POST);
	//show($_FILES);
	if ( $_POST['type']=='detail' or $_POST['type']=='move' or $_POST['type']=='save' or $_POST['type']=='add_location' or $_POST['type']=='add_asset' or $_POST['type']=='edit'){
		show_details_asset($db,$_POST['asset_id']);
	}else{
		$filter=update_filter($db);
		show_live_list_moulder($db,' and asset_type=\'Moulding Machine\' ',$sort);
	}

	



	function show_live_list_moulder($db,$filter='',$sort='ORDER BY asset_position ASC'){
		echo'<b><div class="hidden-xs hidden-sm hidden-md row ">';
			echo'<div class="col-sm-6 asset-header">';
				echo'<div class="col-sm-4 ">Moulder</div>';
				echo'<div class="col-sm-2 ">Picture</div>';
				echo'<div class="col-sm-2 ">Tool</div>';
				echo'<div class="col-sm-2 ">Product</div>';
				echo'<div class="col-sm-2 ">Action</div>';
			echo '</div>';
			echo'<div class="col-sm-6 asset-header">';
				echo'<div class="col-sm-4 ">Moulder</div>';
				echo'<div class="col-sm-2 ">Picture</div>';
				echo'<div class="col-sm-2 ">Tool</div>';
				echo'<div class="col-sm-2 ">Product</div>';
				echo'<div class="col-sm-2 ">Action</div>';
			echo '</div>';
		
		echo '</div>
		<div class="visible-xs-block visible-sm-block visible-md-block row asset-header">';
		
			echo'<div class="col-sm-4 ">Moulder</div>';
			echo'<div class="col-sm-2 ">Picture</div>';
			echo'<div class="col-sm-2 ">Current Tool</div>';
			echo'<div class="col-sm-2 ">Product</div>';
			echo'<div class="col-sm-2 ">Action</div>';
		echo '</div></b>';
		
		echo'<div class="hidden-xs hidden-sm hidden-md row ">';
			echo'<div class="col-sm-6 " style="padding:0;">';
				$listeasset=load_all_asset($db,$filter.' and asset_row=1 ',$sort);
				show_list_moulder($db,$listeasset);
			echo '</div>';
			echo'<div class="col-sm-6 " style="padding:0;">';
				$listeasset=load_all_asset($db,$filter.' and asset_row=2 ',$sort);
				show_list_moulder($db,$listeasset);
			echo '</div>';
		echo '</div>';
		echo'<div class="visible-xs-block visible-sm-block visible-md-block ">';
		echo'<div class="col-sm-12 " style="padding:0;">';
				$listeasset=load_all_asset($db,$filter.' and asset_row=1 ',$sort);
				show_list_moulder($db,$listeasset);
				separator();
				$listeasset=load_all_asset($db,$filter.' and asset_row=2 ',$sort);
				show_list_moulder($db,$listeasset);
			echo '</div>';
		echo '</div>';
	   
	
	}

	function show_list_moulder($db,$listeasset){
		
		foreach ($listeasset as $asset){
			echo'<form id="formtosubmitmoulding-'.$asset['asset_id'].'" method="POST">';
			echo '<input type="hidden"  name="asset_id" value="'.$asset['asset_id'].'">';
			echo '<input type="hidden"  name="type" value="detail">';
			echo '</form>';
			
			echo'<div class="row asset-line" onClick="document.forms[\'formtosubmitmoulding-'.$asset['asset_id'].'\'].submit();" >';
			   
				
				echo'<div class="col-sm-4 line-entry">';
				echo $asset['asset_name']."(".$asset['asset_tool_number'].")";
				echo '</div>';
				echo'<div class="col-sm-2 line-entry">';
				if(!empty($asset['asset_picture'])){
					//echo'<a target="blank"  href="asset/'.$asset['asset_picture'].'">';
					echo'<img class="attachment" src="asset/mini-'.$asset['asset_picture'].'" width="50"  >';
					//echo'</a>';
				}
				echo '</div>';
				
				echo'<div class="col-sm-2 line-entry">';
				$allproduct=get_product_asset($db,$asset['asset_id']);
				foreach ($allproduct as &$product){
					echo'<small><small><div class="row">'.$product['assetproduct_productcode'].'</div></small></small>';
				}
				echo '</div>';
				
				echo'<div class="col-sm-2 col-lg-1 line-entry">';
				echo '</div>';
					
			echo '</div>';
	
	
	
	
	
	
			
	
			//show($asset);
		}
	}


	?>
	


	
	
	
</div>
