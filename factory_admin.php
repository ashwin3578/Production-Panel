

<?php 
$page_title='Factory Live';
$title_top='Factory';
$page = $_SERVER['PHP_SELF'];
$sec = "10000";?>
<head>
<?php 
//echo'meta http-equiv="refresh" content="'.$sec.';URL=\''.$page.'\'">';  
?>
</head>
<?php
include ('header.php'); 

?>


<div class="container">
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<?php echo '<link rel="stylesheet" href="css/machine.css?v='.time().'">';?>
<?php echo '<link rel="stylesheet" href="css/factory.css?v='.time().'">';?>
<?php echo '<link rel="stylesheet" href="css/livefactory.css?v='.time().'">';?>
	<?php $_SESSION['temp']['addscan'] = array();?>
	<?php include ('navbar.php'); ?>
	
	<?php
	include('function_framework.php');
	include('function_machine2.php');
	//show($_POST);
    //navbar_factory($db);
	echo'<div class="ajaxbox"></div>';
	manage_post_machine($db);
	general_view_admin_factory($db);
    
	
	
	
	
	
	
       
        
		

	?>
	
	
	


	
	
	
</div>
