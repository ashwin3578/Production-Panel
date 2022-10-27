

<?php 
$page_title='Moulding Planning';
$title_top='Moulding Planning';
include ('header.php'); ?>


<div class="container">
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<?php echo '<link rel="stylesheet" href="css/matrix.css?v='.time().'">';?>
<?php echo '<link rel="stylesheet" href="css/training.css?v='.time().'">';?>
	<?php $_SESSION['temp']['addscan'] = array();?>
	<?php include ('navbar.php'); ?>
	
	<?php
	include('function_framework.php');
	include('function_moulding_planning.php');
	//show($_POST);
    
	manage_post_moulding_planning();
    navbar_moulding_planning();
	general_view_moulding_planning();
   ?>
	
	
</div>
