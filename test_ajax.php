

<?php 
session_start();
include ('dbconnection.php');
include ('function.php');

if($_SESSION['temp']['id']=='CorentinHillion'){include ('function_roster2.php');}else{include ('function_roster.php');;}
//if($_SESSION['temp']['id']=='CorentinHillion'){show($_POST);}
show($_POST);


	
	
	


?>