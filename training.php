

<?php 
$page_title='Operator Training';
$title_top='Operator Training Matrix';
include ('header.php'); ?>


<div class="container">


<?php echo '<link rel="stylesheet" href="css/matrix.css?v='.time().'">';?>
<?php echo '<link rel="stylesheet" href="css/training.css?v='.time().'">';?>
	<?php $_SESSION['temp']['addscan'] = array();?>
	<?php include ('navbar.php'); ?>
	
	<?php
	include('function_framework.php');
	include('function_training.php');
	//show($_POST);
    navbar_training($db);
	manage_post_training($db);
	general_view_training($db);
    
	
	
	
	
	
	
       
        
		

	?>
	
	
	


	
	
	
</div>
