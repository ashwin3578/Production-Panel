

<?php 
session_start();
include ('dbconnection.php');
include ('function.php');
include ('function_framework.php');



include ('function_manage_group.php');
//show($_POST);

manage_post_manage_group($db);
	
	
	
	
	


?>