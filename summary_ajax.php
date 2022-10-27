

<?php 
session_start();
include ('dbconnection.php');
include ('function.php');
include ('function_scanning_v2.php');

//if($_SESSION['temp']['id']=='CorentinHillion'){include ('function_roster2.php');}else{include ('function_roster.php');;}
//if($_SESSION['temp']['id']=='CorentinHillion'){show($_POST);}
//show($_POST);
managing_POST_scan_summary($db);
edit_scan_procedure($db);
summary_show_operator_detail($db);
//managing_POST_scan_summary($db);

	
	
	
	


?>