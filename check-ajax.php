

<?php 
session_start();
include ('dbconnection.php');
include ('function.php');
include ('function_framework.php');
include ('function_check.php');


//if($_SESSION['temp']['id']=='CorentinHillion'){show($_POST);}
//show($_POST);
manage_POST_check($db);
	
	
	
	
	


?>