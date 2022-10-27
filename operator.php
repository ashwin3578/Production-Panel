

<?php 
$page_title='Operator';
$title_top='Operator';
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

<?php echo '<link rel="stylesheet" href="css/operator.css?v='.time().'">';?>
	<?php $_SESSION['temp']['addscan'] = array();?>
	<?php include ('navbar.php'); ?>
	
	<?php
	include('function_framework.php');
	include('function_operator.php');
	//show($_POST);
    //navbar_factory($db);
	?>
	<div class="ajaxbox"></div>
	<?php
	
	manage_post_operator($db);
	general_view_operator($db); 
	
	?>
	
	
	
	
</div>
