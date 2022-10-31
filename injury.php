

<?php 
$page_title='REGII';
$title_top='Register of Injuries and Illnesses';
include ('header.php'); ?>


<div class="container">
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<?php echo '<link rel="stylesheet" href="css/injury.css?v='.time().'">';?>
	<?php $_SESSION['temp']['addscan'] = array();?>
	<?php include ('navbar.php'); ?>
	
	<?php
	include('function_framework.php');
	include('function_injury.php');
	//show($_POST);
	?>
	<div class="navbar_injury_container"><?php navbar_injury($db);?></div>
	<?php
    
	manage_post_injury($db);
	general_view_report($db);
    
	
	
	
	
	
	
       
        
		

	?>
	
	
	


	
	
	
</div>
