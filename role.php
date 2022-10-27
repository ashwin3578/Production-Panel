

<?php 
$page_title='Manage Users';
$title_top='Manage Users';
$page = $_SERVER['PHP_SELF'];
?>
<head>

</head>
<?php
include ('header.php'); 

?>


<div class="container">
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

	
	<?php include ('navbar.php'); ?>
	
	<?php
	include('function_framework.php');
	include('function_manage_users.php');
	//show($_POST);
    //navbar_factory($db);
	echo'<div class="ajaxbox"></div>';
	manage_post_manage_users($db);
	//general_view_manage_users($db);?>
	<div class="main"><?php general_view_manage_users($db);?></div>
	
	<style>
		.main{
			text-align: center;
		}
	</style>


	
	
	
</div>
