

<?php 
$page_title='Production Issues Log';
$title_top='Production Issues Log';
include ('header.php'); ?>


<div class="container">
<link rel="stylesheet" href="css/issue_log.css">	
	<?php $_SESSION['temp']['addscan'] = array();?>
	<?php include ('navbar.php'); ?>
	
	<?php
	
	include('function_issue_log2.php');
	include('function_framework.php');
	//show($_POST);
	manage_post_issue($db);
	general_view_prod_isue_log();
	
	
	
	
	 
	 
	

	?>
	
	
	


	
	
	
</div>
