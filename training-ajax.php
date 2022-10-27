

<?php 
session_start();
include ('dbconnection.php');
include ('function.php');
include ('function_framework.php');



if($_SESSION['temp']['id']=='CorentinHillion'){include ('function_training.php');}else{include ('function_training.php');}
//show($_POST);
manage_POST_training($db);
	
	
	
	
	


?>