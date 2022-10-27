

<?php 
session_start();
include ('dbconnection.php');
include ('function.php');
include ('function_available.php');
//show($_POST);
available_managing_POST($db);
navbar_available($db);
available_view_general($db);
	
	
	


?>