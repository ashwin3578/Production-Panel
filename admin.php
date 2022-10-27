

<?php 
$page_title='Admin';
$title_top='admin';
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
	include('function_manage_admin.php');
	//show($_POST);
    
	manage_post_admin();
    navbar_admin();
	general_view_admin();
    //test_mail();
   ?>
	
	
</div>
