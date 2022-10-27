

<?php 
session_start();
include ('dbconnection.php');
include ('function.php');
include ('function_framework.php');



if($_SESSION['temp']['id']=='CorentinHillion'){include ('function_injury.php');}else{include ('function_injury.php');}
//show($_POST);
manage_POST_injury($db);
	
	
	
	
	


?>