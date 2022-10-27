

<?php 
$page_title='Operator Available';
$title_top='Operator Available';
include ('header.php'); ?>


<div class="container">
<link rel="stylesheet" href="css/alert.css">	
<link rel="stylesheet" href="css/issue_log.css">		
<link rel="stylesheet" href="css/metrology.css">
<link rel="stylesheet" href="css/roster.css">
	<?php $_SESSION['temp']['addscan'] = array();?>
	<?php include ('navbar.php'); ?>
	
	
	
	
	<?php
	
	include ('function_available.php');
    echo'<div id="here" class="here">';
    available_managing_POST($db);
	navbar_available($db);
	available_view_general($db);
	echo'</div>';

	?> 
	
	
	
	
	


	
	
	
</div>


