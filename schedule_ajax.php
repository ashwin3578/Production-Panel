

<?php 
session_start();
include ('dbconnection.php');
include ('function.php');
include ('function_framework.php');



include ('function_schedule.php');
//show($_POST);

manage_POST_schedule($db);
	
	
	
	
	


?>