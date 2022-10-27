

<?php 
session_start();
include ('dbconnection.php');
include ('function.php');
include ('function_framework.php');



include ('function_moulding_planning.php');
//show($_POST);

manage_post_moulding_planning($db);
	
	
	
	
	


?>