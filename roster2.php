

<?php 
$page_title='Labour Allocation';
$title_top='Labour Allocation';
include ('header.php'); ?>


<div class="container">
<link rel="stylesheet" href="css/alert.css">	
<link rel="stylesheet" href="css/issue_log.css">		
<link rel="stylesheet" href="css/metrology.css">
<link rel="stylesheet" href="css/roster.css?v=<?=time();?>">
	<?php $_SESSION['temp']['addscan'] = array();?>
	<?php include ('navbar.php'); ?>
	
	
	
	
	<?php
	
	include ('function_roster2.php');

	if($_SESSION['temp']['id']=='CorentinHillion'){show($_POST);}
	//if(empty($_SESSION['temp']['id'])){header('Location: index.php');}
	//show($_SESSION['temp']['Fill']);
	
	
	roster_managing_POST($db);
	

	navbar_roster($db);
	
	//if($_SESSION['temp']['id']=='CorentinHillion'){show($_POST);}
	roster_view_general($db);
	
     //   if($_SESSION['temp']['id']=='CorentinHillion'){show($_POST);}
	//if($_SESSION['temp']['id']=='CorentinHillion'){show($_POST);}




	
	?> 
	
	
	
	
	


	
	
	
</div>


