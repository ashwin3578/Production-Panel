

<?php 
$page_title='Documents Management';
$title_top='Documents Management';
$page = $_SERVER['PHP_SELF'];?>
<head>

</head>
<?php
include ('header.php'); 

?>


<div class="container">
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<?php echo '<link rel="stylesheet" href="css/machine.css?v='.time().'">';?>
<?php echo '<link rel="stylesheet" href="css/livefactory.css?v='.time().'">';?>
<?php echo '<link rel="stylesheet" href="css/livefactory.css?v='.time().'">';?>
<?php echo '<link rel="stylesheet" href="css/matrix.css?v='.time().'">';?>
<?php echo '<link rel="stylesheet" href="css/training.css?v='.time().'">';?>
	<?php $_SESSION['temp']['addscan'] = array();?>
	<?php include ('navbar.php'); ?>
	
	<?php
	include('function_framework.php');
	include('function_doc_management.php');
	//show($_POST);
    //navbar_factory($db);
	?>
	<div class="ajaxbox"></div>
	<?php
	
	manage_post_doc_management($db);
	general_view_doc_management($db); 
	
	?>
	
	
	
	
</div>
