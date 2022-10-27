

<?php 
session_start();
include ('dbconnection.php');
include ('function.php');
include ('function_metrology.php');
//if($_SESSION['temp']['id']=='CorentinHillion'){include ('function_metrology2.php');}else{include ('function_metrology.php');;}
//if($_SESSION['temp']['id']=='CorentinHillion'){show($_POST);}
//show($_POST);
managing_POST($db);
	
	
	
	
	


?>