

<?php 
session_start();
include ('dbconnection.php');
include ('function.php');
include ('function_framework.php');

include ('function_roster.php');
if($_SESSION['temp']['id']=='CorentinHillion'){include ('function_prod_plan2.php');}else{include ('function_prod_plan.php');}
//if($_SESSION['temp']['id']=='CorentinHillion'){show($_POST);}
//show($_POST);
manage_POST_prodplan($db);
	
	
	
	
	


?>