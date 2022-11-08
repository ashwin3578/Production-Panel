

<?php 
session_start();
include ('dbconnection.php');
include ('function.php');
include ('function_framework.php');

include ('function_hazrep.php');

HazRepController::manage_post_hazrep();
	
	
	
	
	


?>