

<?php $starttime = microtime(true); // Top of page
$page_title='Production Plan';
$title_top='Production Plan';
include ('header.php'); ?>


<div class="container">
<link rel="stylesheet" href="css/issue_log.css">


<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="/resources/demos/style.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>


	<?php $_SESSION['temp']['addscan'] = array();?>
	<?php include ('navbar.php'); ?>
	<?php include ('function_framework.php'); ?>
	<?php include ('function_prod_plan.php'); ?>
	<?php include ('function_roster.php'); ?>
	
	<?php 
	echo '<link rel="stylesheet" href="css/prod-plan.css?v='.time().'">';
    echo '<link rel="stylesheet" href="css/w3.css?v='.time().'">';
	manage_POST_prodplan($db);
    
	navbar_prodplan($db);
    //show($_SESSION['temp']);
	main_view($db);
	


	


	$endtime = microtime(true); // Bottom of page
	//printf("Page loaded in %f seconds", $endtime - $starttime );
	//update_div('Moulding-title','dont','Test');
	?>
	
	
	


	
	
	
</div>

